<?php


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
        foreach ($group->getMembers() as $member) {
            /** @var Member $member */
            $this->members[] = new EventMember($event, $member);
        }
    }

    public function getMembers(): array
    {
        return $this->members;
    }

    public function getName(): string
    {
        return $this->group ? $this->group->getName() : 'Group not defined';
    }
}
