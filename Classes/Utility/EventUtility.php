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
                if ($value instanceof AbstractEntity && $value->getUid() !== 0) {
                    $result[$property] = $value->getUid();
                }
                if ($value instanceof ObjectStorage && ($value->count() === 0)) {
                    unset($result[$property]);
                }
            }
        }
        return $result;
    }

    public static function getDatesFromField(Event $event, string $fieldValue): array
    {
        $result = [];
        if ($event->getStartdate() === null) {
            return $result;
        }
        $dates = GeneralUtility::trimExplode("\n", $fieldValue, true);
        foreach ($dates as $date) {
            $tempDate = \DateTime::createFromFormat(
                $GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy'],
                $date,
                $event->getStartdate()->getTimezone()
            );
            if ($tempDate === false) {
                $tempDate = \DateTime::createFromFormat(
                    'd-m-Y',
                    $date,
                    $event->getStartdate()->getTimezone()
                );
            }
            if ($tempDate === false) {
                continue;
            }
            $tempDate->setTime(
                (int)$event->getStartdate()->format('G'),
                (int)$event->getStartdate()->format('i')
            );
            // UTC time is required
            $tempDate->setTimezone(new \DateTimeZone('UTC'));
            $result[] = $tempDate;
        }
        return $result;
    }

    /**
     * Note: All date and time formats are as defined for the BE through `TYPO3_CONF_VARS` or the core directly.
     * Note: The frontend timezone is defined by extbase.
     *
     * @see \TYPO3\CMS\Backend\Form\Element\InputDateTimeElement
     */
    public static function getEventRecurrences(array $events, int $displayDays): array
    {
        $result = [];

        /** @var Event $event */
        foreach ($events as $event) {
            if ($event->getStartdate() === null) {
                continue;
            }
            $properties = [];

            // Set start date (DTSTART)
            if (strpos($event->getRecurrenceSet(), 'DTSTART') === false) {
                $properties[] = sprintf(
                    'DTSTART;TZID=%s:%s',
                    date_default_timezone_get(),
                    $event->getStartdate()->format('Ymd\THis')
                );
            }

            // Set recurrence rule (RRULE)
            if ($event->getRecurrenceRule() !== '') {
                $rruleParts = GeneralUtility::trimExplode(';', $event->getRecurrenceRule());
                // In case the user didn't define a recurrence end we set it here
                if (strpos($event->getRecurrenceRule(), 'UNTIL') === false) {
                    $maxEnddate = new \DateTime();
                    $maxEnddate->setTimestamp($event->getStartdate()->getTimestamp() + $displayDays * 86400);
                    if (
                        $event->getRecurrenceEnddate() !== null &&
                        $event->getRecurrenceEnddate()->getTimestamp() < $maxEnddate->getTimestamp()
                    ) {
                        $maxEnddate = $event->getRecurrenceEnddate();
                    }
                    // UTC time is required
                    $maxEnddate->setTimezone(new \DateTimeZone('UTC'));
                    $rruleParts[] = sprintf(
                        'UNTIL=%s',
                        $maxEnddate->format('Ymd\THis\Z')
                    );
                }
                $properties[] = sprintf('RRULE:%s', implode(';', $rruleParts));
            }

            // Set including dates
            if ($event->getRecurrenceDates() !== '') {
                $dates = self::getDatesFromField($event, $event->getRecurrenceDates());
                /** @var \DateTime $date */
                foreach ($dates as $date) {
                    $properties[] = sprintf(
                        'RDATE:%s',
                        $date->format('Ymd\THis\Z')
                    );
                }
            }

            // Set excluding dates
            if ($event->getRecurrenceExceptionDates() !== '') {
                $dates = self::getDatesFromField($event, $event->getRecurrenceExceptionDates());
                /** @var \DateTime $date */
                foreach ($dates as $date) {
                    $properties[] = sprintf(
                        'EXDATE:%s',
                        $date->format('Ymd\THis\Z')
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
                // Ensure parent and child have same time zone (iCalendar uses UTC in some places)
                $occurrence->setTimezone($event->getStartdate()->getTimezone());
                $child = Event::createChild($event, $occurrence);
                if ($child !== null) {
                    $result[] = $child;
                }
            }
        }
        return $result;
    }

    public static function mergeEvents(array ...$eventSets): array
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
