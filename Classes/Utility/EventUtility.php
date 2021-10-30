<?php

declare(strict_types=1);

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Grevman\Utility;

use Buepro\Grevman\Domain\Model\Event;
use RRule\RSet;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class EventUtility
{
    public static function createId(Event $event): string
    {
        // The event is already persisted, we return the uid
        if ((bool)$event->getUid()) {
            return (string)$event->getUid();
        }

        // The event is not persisted yet, we return the parentUid and the startdate
        $parts = [
            $event->getParent() !== null ? $event->getParent()->getUid() : 0,
            $event->getStartdate() !== null ? $event->getStartdate()->getTimestamp() : 0,
        ];
        return implode('-', $parts);
    }

    public static function getIdArray(string $id): array
    {
        $parts = GeneralUtility::trimExplode('-', $id, true, 2);
        if (count($parts) === 2) {
            return [
                'parentUid' => (int)$parts[0],
                'startdate' => (int)$parts[1],
            ];
        }
        if (count($parts) === 1) {
            return [
                'uid' => (int)$parts[0],
            ];
        }
        return [];
    }

    public static function getParentUidFromId(string $id): int
    {
        $idArray = self::getIdArray($id);
        return isset($idArray['parentUid']) ? (int)$idArray['parentUid'] : 0;
    }

    public static function getStartdateFromId(string $id): ?\DateTime
    {
        $idArray = self::getIdArray($id);
        if (isset($idArray['startdate'])) {
            $startdate = new \DateTime();
            return $startdate->setTimestamp($idArray['startdate']);
        }
        return null;
    }

    public static function getPropertyMappingArray(Event $event): array
    {
        $result = [];
        $properties = [
            'parent', 'title', 'slug', 'startdate', 'enddate', 'teaser', 'description', 'price', 'link', 'program',
            'location', 'images', 'files', 'memberGroups'
        ];
        foreach ($properties as $property) {
            $method = 'get' . ucfirst($property);
            $value = $event->{$method}();
            if ($value !== null) {
                $result[$property] = $value;
                if ($value instanceof AbstractEntity && (bool)$value->getUid()) {
                    $result[$property] = $value->getUid();
                }
                if ($value instanceof ObjectStorage && ($value->count() === 0)) {
                    unset($result[$property]);
                }
            }
        }
        return $result;
    }

    /**
     * Creates an array of \DateTime objects where each object is a copy from the events startdate
     * with the date from the $fieldValue.
     *
     * Note: All date and time formats are as defined for the BE through `TYPO3_CONF_VARS` or the core directly.
     *
     * @param Event $event Event with a valid startdate
     * @param string $fieldValue Line separated dates
     * @return \DateTime[] Date elements with UTC timezone
     * @see \TYPO3\CMS\Backend\Form\Element\InputDateTimeElement
     */
    public static function getDatesFromField(Event $event, string $fieldValue, \DateTimeZone $timezone): array
    {
        $result = [];
        if ($fieldValue === '' || $event->getStartdate() === null) {
            return $result;
        }
        $fieldDates = GeneralUtility::trimExplode("\n", $fieldValue, true);
        foreach ($fieldDates as $fieldDate) {
            // Clone the startdate to get the time details
            $eventDate = clone $event->getStartdate();
            $eventDate->setTimezone($timezone);
            $date = \DateTime::createFromFormat(
                $GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy'],
                $fieldDate,
                $timezone
            );
            if ($date === false) {
                $date = \DateTime::createFromFormat(
                    'd-m-Y',
                    $fieldDate,
                    $timezone
                );
            }
            if ($date === false) {
                continue;
            }
            $eventDate->setDate(
                (int)$date->format('Y'),
                (int)$date->format('m'),
                (int)$date->format('j')
            );
            $result[] = $eventDate;
        }
        return $result;
    }

    /**
     * Gets upcoming recurrence events from events having `enableRecurrence` set.
     *
     * Note regarding time zone:
     * Under most circumstances the timezone is correctly defined for the script (e.g. by `$GLOBALS['TYPO3_CONF_VARS']
     * ['SYS']['phpTimeZone']`). For the use case where the time zone for the frontend is set to something else we need
     * to use the general time zone used by `grevman` (set through TS constants).
     *
     * @param Event[] $events
     * @return Event[]
     */
    public static function getEventRecurrences(array $events, int $displayDays, \DateTimeZone $timezone = null): array
    {
        $result = [];

        // Check preconditions
        if ($displayDays < 1 || count($events) < 1) {
            return $result;
        }

        // In case the timezone isn't set we use the system time zone
        if ($timezone === null) {
            $timezone = new \DateTimeZone(date_default_timezone_get());
        }
        $utcTimezone = new \DateTimeZone('UTC');

        foreach ($events as $event) {
            if (!$event->getEnableRecurrence() || $event->getStartdate() === null) {
                continue;
            }
            $properties = [];

            // Set start date (DTSTART)
            if (strpos($event->getRecurrenceSet(), 'DTSTART') === false) {
                $eventStartdate = clone $event->getStartdate();
                $eventStartdate->setTimezone($timezone);
                $properties[] = sprintf(
                    'DTSTART;TZID=%s:%s',
                    $timezone->getName(),
                    $eventStartdate->format('Ymd\THis')
                );
            }

            // Set recurrence rule (RRULE)
            if ($event->getRecurrenceRule() !== '') {
                $rruleParts = GeneralUtility::trimExplode(';', $event->getRecurrenceRule());
                // In case the user didn't define a recurrence end we set it here
                if (strpos($event->getRecurrenceRule(), 'UNTIL') === false) {
                    $maxEnddate = new \DateTime('now', $timezone);
                    $maxEnddate->add(new \DateInterval('P' . $displayDays . 'D'));
                    if (
                        $event->getRecurrenceEnddate() !== null &&
                        $event->getRecurrenceEnddate()->getTimestamp() < $maxEnddate->getTimestamp()
                    ) {
                        $maxEnddate = clone $event->getRecurrenceEnddate();
                    }
                    // UTC time is required
                    $maxEnddate->setTimezone($utcTimezone);
                    $rruleParts[] = sprintf(
                        'UNTIL=%s',
                        $maxEnddate->format('Ymd\THis\Z')
                    );
                }
                $properties[] = sprintf('RRULE:%s', implode(';', $rruleParts));
            }

            // Set including dates
            if ($event->getRecurrenceDates() !== '') {
                $dates = self::getDatesFromField($event, $event->getRecurrenceDates(), $timezone);
                foreach ($dates as $date) {
                    $properties[] = sprintf(
                        'RDATE;TZID=%s:%s',
                        $timezone->getName(),
                        $date->format('Ymd\THis')
                    );
                }
            }

            // Set exception dates
            if ($event->getRecurrenceExceptionDates() !== '') {
                $dates = self::getDatesFromField($event, $event->getRecurrenceExceptionDates(), $timezone);
                foreach ($dates as $date) {
                    $properties[] = sprintf(
                        'EXDATE;TZID=%s:%s',
                        $timezone->getName(),
                        $date->format('Ymd\THis')
                    );
                }
            }

            // Set recurrence set
            if ($event->getRecurrenceSet() !== '') {
                $lines = GeneralUtility::trimExplode("\n", $event->getRecurrenceSet(), true);
                foreach ($lines as $line) {
                    $properties[] = $line;
                }
            }

            $recurrences = new RSet(implode("\n", $properties));
            /** @var \DateTime $occurrence */
            foreach ($recurrences->getOccurrences() as $occurrence) {
                $child = Event::createChild($event, $occurrence);
                if ($child !== null) {
                    $result[] = $child;
                }
            }
        }
        // Remove past events
        return array_filter($result, static function (Event $event): bool {
            if ($event->getStartdate() !== null) {
                $yesterday = new \DateTime('midnight');
                return $event->getStartdate()->getTimestamp() > $yesterday->getTimestamp();
            }
            return false;
        });
    }

    public static function mergeAndOrderEvents(array ...$eventSets): array
    {
        $events = array_merge(...$eventSets);

        // Remove duplicates (caused by recurrence events that were converted to a persisted event)
        $uniqueEvents = [];
        /** @var Event $event */
        foreach ($events as $event) {
            $id = (string)$event->getUid();
            if ($event->getParent() !== null && $event->getStartdate() !== null) {
                $id = sprintf('%d-%d', $event->getParent()->getUid(), $event->getStartdate()->getTimestamp());
            }
            // Persisted events are always unique
            if ((bool)$event->getUid()) {
                $uniqueEvents[$id] = $event;
                continue;
            }
            if (!isset($uniqueEvents[$id])) {
                $uniqueEvents[$id] = $event;
            }
        }

        usort($uniqueEvents, function (Event $event1, Event $event2):int {
            if ($event1->getStartdate() !== null && $event2->getStartdate() !== null) {
                return $event1->getStartdate()->getTimestamp() - $event2->getStartdate()->getTimestamp();
            }
            return 0;
        });
        return $uniqueEvents;
    }
}
