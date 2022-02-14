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

    public function getConfirmedRegistrationsTestDataProvider(): array
    {
        $event = new Event();
        $group = new Group();
        $event->addMemberGroup($group);

        $memberProphecy1 = $this->prophesize(Member::class);
        $memberProphecy1->getUid()->willReturn(1);
        $member1 = $memberProphecy1->reveal();
        $memberProphecy2 = $this->prophesize(Member::class);
        $memberProphecy2->getUid()->willReturn(2);
        $member2 = $memberProphecy2->reveal();
        $memberProphecy3 = $this->prophesize(Member::class);
        $memberProphecy3->getUid()->willReturn(3);
        $member3 = $memberProphecy3->reveal();
        $group->addMember($member1)->addMember($member2)->addMember($member3);
        $registration1 = (new Registration())->setMember($member1)->setState(Registration::REGISTRATION_CONFIRMED);
        $registration2 = (new Registration())->setMember($member2)->setState(Registration::REGISTRATION_CANCELED);
        $registration3 = (new Registration())->setMember($member3);
        $event->addRegistration($registration1)->addRegistration($registration2)->addRegistration($registration3);

        $memberProphecy4 = $this->prophesize(Member::class);
        $memberProphecy4->getUid()->willReturn(4);
        $member4 = $memberProphecy4->reveal();
        $memberProphecy5 = $this->prophesize(Member::class);
        $memberProphecy5->getUid()->willReturn(5);
        $member5 = $memberProphecy5->reveal();
        $memberProphecy6 = $this->prophesize(Member::class);
        $memberProphecy6->getUid()->willReturn(6);
        $member6 = $memberProphecy6->reveal();
        $registration4 = (new Registration())->setMember($member4)
            ->setState(Registration::REGISTRATION_CONFIRMED);
        $registration5 = (new Registration())->setMember($member5)
            ->setState(Registration::REGISTRATION_CANCELED);
        $registration6 = (new Registration())->setMember($member6);
        $event->addRegistration($registration4)->addRegistration($registration5)->addRegistration($registration6);

        $involvedMembers = [$member1, $member2, $member3, $member4, $member5, $member6];

        return [
            'registrations for event' => [$event, null, [$registration1, $registration4], $involvedMembers],
            'registrations for group' => [$event, $group, [$registration1], $involvedMembers],
        ];
    }

    /**
     * @param Event $event
     * @param Group|null $group
     * @param Registration[] $expectedRegistrations
     * @return void
     * @test
     * @dataProvider getConfirmedRegistrationsTestDataProvider
     */
    public function getConfirmedRegistrationsTest(Event $event, ?Group $group, array $expectedRegistrations): void
    {
        $this->assertSame($expectedRegistrations, $event->getConfirmedRegistrations($group));
    }

    /**
     * @param Event $event
     * @param Group|null $unused1
     * @param Registration[] $unused2
     * @param Member[] $expectedMembers
     * @return void
     * @test
     * @dataProvider getConfirmedRegistrationsTestDataProvider
     */
    public function getInvolvedMembersTest(Event $event, ?Group $unused1, array $unused2, array $expectedMembers): void
    {
        $this->assertSame($expectedMembers, $event->getInvolvedMembers());
    }
}
