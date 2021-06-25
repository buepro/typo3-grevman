<?php


namespace Buepro\Grevman\Domain\Dto;


use Buepro\Grevman\Domain\Model\Event;
use Buepro\Grevman\Domain\Model\Group;
use Buepro\Grevman\Domain\Model\Guest;
use Buepro\Grevman\Domain\Model\Member;
use Buepro\Grevman\Domain\Model\Registration;
use TYPO3\CMS\Extbase\Annotation as Extbase;

class Mail
{
    /**
     * @var Event
     */
    protected $event;

    /**
     * @var Member
     */
    protected $sender;

    /**
     * @var string
     * @Extbase\Validate("NotEmpty")
     */
    protected $subject;

    /**
     * @var string
     * @Extbase\Validate("NotEmpty")
     */
    protected $message;

    /**
     * SendMail constructor.
     * @param Event $event
     * @param Member $sender
     * @param string $subject
     * @param string $message
     */
    public function __construct(Event $event, Member $sender, string $subject, string $message)
    {
        $this->event = $event;
        $this->sender = $sender;
        $this->subject = $subject;
        $this->message = $message;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }

    public function getSender(): Member
    {
        return $this->sender;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getReceivers(): array
    {
        $receivers = [];
        // From event groups
        foreach ($this->event->getMemberGroups() as $memberGroup) {
            /** @var Group $memberGroup */
            foreach ($memberGroup->getMembers() as $member) {
                /** @var Member $member */
                if ($member->getEmail()) {
                    $receivers[] = new \Symfony\Component\Mime\Address($member->getEmail(), $member->getScreenName());
                }
            }
        }
        // From spontaneous registrations
        foreach ($this->event->getSpontaneousRegistrations() as $registration) {
            /** @var Registration $registration */
            if ($registration->getMember()->getEmail()) {
                $receivers[] = new \Symfony\Component\Mime\Address($registration->getMember()->getEmail(), $registration->getMember()->getScreenName());
            }
        }
        // Visitors
        foreach ($this->event->getGuests() as $guest) {
            /** @var Guest $guest */
            if ($guest->getEmail()) {
                $receivers[] = new \Symfony\Component\Mime\Address($guest->getEmail(), $guest->getScreenName());
            }
        }
        return $receivers;
    }
}
