<?php

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Grevman\Utility;

use Buepro\Grevman\Domain\Dto\EventGroup;
use Buepro\Grevman\Domain\Model\Event;

class DtoUtility
{
    public static function getEventGroups(Event $event)
    {
        $eventGroups = [];
        foreach ($event->getMemberGroups() as $group) {
            $eventGroups[] = new EventGroup($event, $group);
        }
        return $eventGroups;
    }
}
