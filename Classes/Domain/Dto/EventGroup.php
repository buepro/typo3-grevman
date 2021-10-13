<?php

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Grevman\Domain\Dto;

use Buepro\Grevman\Domain\Model\Event;
use Buepro\Grevman\Domain\Model\Group;
use Buepro\Grevman\Domain\Model\Member;

class EventGroup
{
    /**
     * @var Event
     */
    private $event;

    /**
     * @var Group
     */
    protected $group;

    /**
     * @var array<EventMember>
     */
    protected $members;

    public function __construct(Event $event, Group $group)
    {
        $this->event = $event;
        $this->group = $group;
        /** @var Member $member */
        foreach ($group->getMembers() as $member) {
            $this->members[] = new EventMember($event, $member);
        }
    }

    public function getMembers(): array
    {
        return $this->members;
    }

    public function getName(): string
    {
        return isset($this->group) ? $this->group->getName() : 'Group not defined';
    }
}
