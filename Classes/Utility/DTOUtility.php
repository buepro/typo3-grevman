<?php

declare(strict_types=1);

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Grevman\Utility;

use Buepro\Grevman\Domain\DTO\EventGroup;
use Buepro\Grevman\Domain\Model\Event;

class DTOUtility
{
    public static function getEventGroups(Event $event): array
    {
        $eventGroups = [];
        foreach ($event->getMemberGroups() as $group) {
            $eventGroups[] = new EventGroup($event, $group);
        }
        return $eventGroups;
    }
}
