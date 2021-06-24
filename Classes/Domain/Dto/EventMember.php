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

    public function getEvent(): Event
    {
        return $this->event;
    }

    public function getMember(): Member
    {
        return $this->member;
    }

    public function getScreenName(): string
    {
        return $this->member->getScreenName();
    }

    public function getRegistrationState(): int
    {
        if (!$this->registration) {
            return 0;
        }
        return $this->registration->getStatus();
    }

    public function getRegistered(): bool
    {
        return $this->registration && $this->registration->getStatus() === Registration::REGISTRATION_CONFIRMED;
    }
}
