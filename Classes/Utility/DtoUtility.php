<?php


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
