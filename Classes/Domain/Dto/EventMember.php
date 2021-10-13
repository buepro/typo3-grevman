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
     * @var Registration|null
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

    public function getEmail(): string
    {
        return $this->member->getEmail();
    }

    public function getScreenName(): string
    {
        return $this->member->getScreenName();
    }

    public function getRegistrationState(): int
    {
        if (null === $this->registration) {
            return 0;
        }
        return $this->registration->getState();
    }

    public function getRegistered(): bool
    {
        return $this->registration !== null && $this->registration->getState() === Registration::REGISTRATION_CONFIRMED;
    }

    public function isEventGroupMember(): bool
    {
        /** @var Group $group */
        foreach ($this->event->getMemberGroups() as $group) {
            /** @var Member $member */
            foreach ($group->getMembers() as $member) {
                if ($this->member === $member) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * True if the member belongs to a leader group.
     */
    public function getIsLeader(): bool
    {
        return $this->member->getIsLeader();
    }
}
