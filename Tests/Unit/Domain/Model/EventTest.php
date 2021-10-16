<?php

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Grevman\Tests\Unit\Domain\Model;

use Buepro\Grevman\Domain\Model\Event;
use Buepro\Grevman\Domain\Model\Group;
use Buepro\Grevman\Domain\Model\Member;
use Buepro\Grevman\Domain\Model\Registration;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class EventTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    public function getRegistrationForMemberDataProvider(): array
    {
        $event = new Event();

        // Unknown member, not belonging to a group
        $unknownMemberProphecy = $this->prophesize(Member::class);
        $unknownMemberProphecy->getUid()->willReturn(1);

        // Known member, belonging to a group without registration
        $knownMemberProphecy = $this->prophesize(Member::class);
        $knownMemberProphecy->getUid()->willReturn(2);
        $memberGroupProphecy = $this->prophesize(Group::class);
        $memberGroupProphecy->getMembers()->willReturn((new ObjectStorage())->attach($memberGroupProphecy->reveal()));
        $event->addMemberGroup($memberGroupProphecy->reveal());

        // Member with registration
        $memberProphecy = $this->prophesize(Member::class);
        $memberProphecy->getUid()->willReturn(3);
        $registrationProphecy = $this->prophesize(Registration::class);
        $registrationProphecy->getMember()->willReturn($memberProphecy->reveal());
        $registrationProphecy->getUid()->willReturn(1);
        $event->addRegistration($registrationProphecy->reveal());

        // Some other member with registration
        $someMemberProphecy = $this->prophesize(Member::class);
        $someMemberProphecy->getUid()->willReturn(4);
        $someRegistrationProphecy = $this->prophesize(Registration::class);
        $someRegistrationProphecy->getMember()->willReturn($someMemberProphecy->reveal());
        $someRegistrationProphecy->getUid()->willReturn(2);
        $event->addRegistration($someRegistrationProphecy->reveal());

        return [
            'unknown member has no registration' => [$event, $unknownMemberProphecy->reveal(), null],
            'known member has no registration' => [$event, $knownMemberProphecy->reveal(), null],
            'member has registration' => [$event, $memberProphecy->reveal(), $registrationProphecy->reveal()],
            'some other member has registration' => [$event, $someMemberProphecy->reveal(), $someRegistrationProphecy->reveal()],
        ];
    }

    /**
     * @dataProvider getRegistrationForMemberDataProvider
     * @test
     */
    public function getRegistrationForMember(Event $event, Member $member, ?Registration $expected): void
    {
        if (isset($expected)) {
            $this->assertEquals($expected->getUid(), $event->getRegistrationForMember($member)->getUid());
        } else {
            $this->assertNull($event->getRegistrationForMember($member));
        }
    }

    public function getEventGroupForMemberDataProvider()
    {
        $event = new Event();

        // Unknown member
        $unknownMemberProphecy = $this->prophesize(Member::class);
        $unknownMemberProphecy->getUid()->willReturn(1);

        // Busy member belonging to all groups
        $busyMemberProphecy = $this->prophesize(Member::class);
        $busyMemberProphecy->getUid()->willReturn(2);

        // Member belonging to group
        $memberProphecy = $this->prophesize(Member::class);
        $memberProphecy->getUid()->willReturn(3);
        $groupProphecy = $this->prophesize(Group::class);
        $groupProphecy->getUid()->willReturn(1);
        $members = new ObjectStorage();
        $members->attach($busyMemberProphecy->reveal());
        $members->attach($memberProphecy->reveal());
        $groupProphecy->getMembers()->willReturn($members);
        $event->addMemberGroup($groupProphecy->reveal());

        // An-other group
        $otherGroupProphecy = $this->prophesize(Group::class);
        $otherGroupProphecy->getUid()->willReturn(2);
        $members = new ObjectStorage();
        $members->attach($busyMemberProphecy->reveal());
        $otherGroupProphecy->getMembers()->willReturn($members);
        $event->addMemberGroup($otherGroupProphecy->reveal());

        return [
            'unknown member' => [$event, $unknownMemberProphecy->reveal(), null],
            'busy member' => [$event, $busyMemberProphecy->reveal(), $groupProphecy->reveal()],
            'member' => [$event, $memberProphecy->reveal(), $groupProphecy->reveal()],
        ];
    }

    /**
     * @dataProvider getEventGroupForMemberDataProvider
     * @test
     */
    public function getEventGroupForMember(Event $event, Member $member, ?Group $expected): void
    {
        if (isset($expected)) {
            $this->assertEquals($expected->getUid(), $event->getEventGroupForMember($member)->getUid());
        } else {
            $this->assertNull($event->getEventGroupForMember($member));
        }
    }

    public function getSpontaneousRegistrationsDataProvider(): array
    {
        $emptyEvent = new Event();

        // Event just having spontaneous registrations
        $spontaneousEvent = new Event();
        $registrationProphecy = $this->prophesize(Registration::class);
        $registrationProphecy->getUid()->willReturn(1);
        $registrationProphecy->getMember()->willReturn(new Member());
        $registration = $registrationProphecy->reveal();
        $spontaneousRegistrations[] = $registration;
        $spontaneousEvent->addRegistration($registration);
        $registrationProphecy = $this->prophesize(Registration::class);
        $registrationProphecy->getUid()->willReturn(2);
        $registrationProphecy->getMember()->willReturn(new Member());
        $registration = $registrationProphecy->reveal();
        $spontaneousRegistrations[] = $registration;
        $spontaneousEvent->addRegistration($registration);

        return [
            'empty event' => [$emptyEvent, []],
            'spontaneous event' => [$spontaneousEvent, $spontaneousRegistrations]
        ];
    }

    /**
     * @dataProvider getSpontaneousRegistrationsDataProvider
     * @test
     */
    public function getSpontaneousRegistrations(Event $event, array $expected): void
    {
        if ($expected === []) {
            $this->assertEmpty($event->getSpontaneousRegistrations());
        } else {
            /** @var Registration $registration */
            foreach ($expected as $key => $registration) {
                $this->assertEquals($registration->getUid(), $event->getSpontaneousRegistrations()[$key]->getUid());
            }
        }
    }
}
