<?php


namespace Buepro\Grevman\Domain\Dto;


use Buepro\Grevman\Domain\Model\Event;
use Buepro\Grevman\Domain\Model\Member;
use Buepro\Grevman\Domain\Model\Registration;

class EventMember
{
    /**
     * @var Event
     */
    protected $event;

    /**
     * @var Member
     */
    protected $member;

    /**
     * @var Registration
     */
    protected $registration;

    public function __construct(Event $event, Member $member)
    {
        $this->event = $event;
        $this->member = $member;
        $this->registration = $event->getRegistrationForMember($member);
    }
}
