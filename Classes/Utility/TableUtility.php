<?php

declare(strict_types=1);

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Grevman\Utility;

use Buepro\Grevman\Domain\Model\Event;
use Buepro\Grevman\Domain\Model\Group;
use Buepro\Grevman\Domain\Model\Guest;
use Buepro\Grevman\Domain\Model\Member;
use Buepro\Grevman\Domain\Model\Registration;

class TableUtility
{
    /**
     * Returns an array containing all event participants in the form:
     * ['groups' => $groups, 'spontaneousMembers' => $spontaneousMembers, 'guests' => $guests]
     */
    public static function getMemberAxis(array $events): array
    {
        // Compile member axis
        $groups = [];
        $guests = [];
        /** @var Event $event */
        foreach ($events as $event) {
            // Compile groups
            foreach ($event->getMemberGroups() as $group) {
                /** @var Group $group */
                if (!isset($groups[$group->getUid()])) {
                    $groups[$group->getUid()] = $group;
                }
            }
            // Compile guests
            foreach ($event->getGuests() as $guest) {
                /** @var Guest $guest */
                if (!isset($guests[$guest->getUid()])) {
                    $guests[$guest->getUid()] = $guest;
                }
            }
        }

        // Create member uid list used to retrieve spontaneous registrations
        $memberUids = [];
        /** @var Group $group */
        foreach ($groups as $group) {
            /** @var Member $member */
            foreach ($group->getMembers() as $member) {
                $memberUids[] = $member->getUid();
            }
        }
        $memberUids = array_unique($memberUids);

        // Compile spontaneous registrations
        $spontaneousMembers = [];
        /** @var Event $event */
        foreach ($events as $event) {
            /** @var Registration $spontaneousRegistration */
            foreach ($event->getSpontaneousRegistrations() as $spontaneousRegistration) {
                /** @var Member $member */
                $member = $spontaneousRegistration->getMember();
                if (!isset($spontaneousMembers[$member->getUid()]) && !in_array($member->getUid(), $memberUids, true)) {
                    $spontaneousMembers[$member->getUid()] = $member;
                }
            }
        }
        return [
            'groups' => $groups,
            'spontaneousMembers' => $spontaneousMembers,
            'guests' => $guests,
        ];
    }
}
