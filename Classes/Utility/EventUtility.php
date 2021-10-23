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
use TYPO3\CMS\Core\Utility\GeneralUtility;

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

    public static function getPropertyMappingArray(Event $event): array
    {
        $result = [];
        $result['parent'] = $event->getParent() !== null ? $event->getParent()->getUid() : 0;
        $properties = [
            'title', 'slug', 'startdate', 'enddate', 'teaser', 'description', 'price', 'link', 'program', 'location',
            'images', 'files', 'memberGroups'
        ];
        foreach ($properties as $property) {
            $method = 'get' . ucfirst($property);
            $value = $event->{$method}();
            if ($value !== null) {
                $result[$property] = $value;
            }
        }
        return $result;
    }
}
