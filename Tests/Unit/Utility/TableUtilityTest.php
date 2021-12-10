<?php

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Grevman\Tests\Unit\Utility;

use Buepro\Grevman\Domain\Model\Event;
use Buepro\Grevman\Domain\Model\Group;
use Buepro\Grevman\Domain\Model\Member;
use Buepro\Grevman\Domain\Model\Registration;
use Buepro\Grevman\Utility\TableUtility;

class TableUtilityTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @test
     */
    public function getMemberAxisReturnsCorrectArray(): void
    {
        $result = TableUtility::getMemberAxis([]);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('groups', $result);
        $this->assertArrayHasKey('spontaneousMembers', $result);
        $this->assertArrayHasKey('guests', $result);
    }

    /**
     * @test
     */
    public function getMemberAxisSpontaneousMembersAreNotListedInGroups()
    {
        // Members
        $members = [];
        $index = 0;
        while (count($members) < 3) {
            $index++;
            $member = new Member();
            $member->_setProperty('uid', $index);
            $members[$index] = $member;
        }

        // Groups
        $groups = [];
        $index = 0;
        while (count($groups) < 2) {
            $index++;
            $group = new Group();
            $group->_setProperty('uid', $index);
            $group->addMember($members[$index]);
            $groups[$index] = $group;
        }

        // Events
        $event1 = (new Event())->addMemberGroup($groups[1]);
        $event2 = (new Event())->addMemberGroup($groups[2]);

        // Add spontaneous registrations
        $event1->addRegistration((new Registration())->setMember($members[2]));
        $event1->addRegistration((new Registration())->setMember($members[3]));

        $result = TableUtility::getMemberAxis([$event1, $event2]);
        $this->assertCount(1, $result['spontaneousMembers']);
        $this->assertEquals(3, array_values($result['spontaneousMembers'])[0]->getUid());
    }
}
