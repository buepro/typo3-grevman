<?php

declare(strict_types=1);

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Grevman\Domain\DTO;

use Buepro\Grevman\Domain\Model\Event;
use Buepro\Grevman\Domain\Model\Group;
use Buepro\Grevman\Domain\Model\Guest;
use Buepro\Grevman\Domain\Model\Member;
use Buepro\Grevman\Domain\Model\Registration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
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
        /** @var Group $memberGroup */
        foreach ($this->event->getMemberGroups() as $memberGroup) {
            /** @var Member $member */
            foreach ($memberGroup->getMembers() as $member) {
                if (GeneralUtility::validEmail($member->getEmail())) {
                    $receivers[] = new \Symfony\Component\Mime\Address($member->getEmail(), $member->getScreenName());
                }
            }
        }
        // From spontaneous registrations
        /** @var Registration $registration */
        foreach ($this->event->getSpontaneousRegistrations() as $registration) {
            if ($registration->getMember() !== null && GeneralUtility::validEmail($registration->getMember()->getEmail())) {
                $receivers[] = new \Symfony\Component\Mime\Address($registration->getMember()->getEmail(), $registration->getMember()->getScreenName());
            }
        }
        // Visitors
        foreach ($this->event->getGuests() as $guest) {
            /** @var Guest $guest */
            if (GeneralUtility::validEmail($guest->getEmail())) {
                $receivers[] = new \Symfony\Component\Mime\Address($guest->getEmail(), $guest->getScreenName());
            }
        }
        return $receivers;
    }
}
