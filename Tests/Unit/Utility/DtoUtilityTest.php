<?php

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Grevman\Tests\Unit\Utility;

use Buepro\Grevman\Domain\Dto\EventGroup;
use Buepro\Grevman\Domain\Model\Event;
use Buepro\Grevman\Domain\Model\Group;
use Buepro\Grevman\Utility\DtoUtility;

class DtoUtilityTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    public function getEventGroupsReturnsEventGroupArrayDataProvider(): array
    {
        $eventWithNoGroup = new Event();
        $eventWithOneGroup = (new Event())->addMemberGroup(new Group());
        $eventWithTwoGroups = (new Event())
            ->addMemberGroup(new Group())
            ->addMemberGroup(new Group());
        return [
            'empty' => [$eventWithNoGroup, 0],
            'one group' => [$eventWithOneGroup, 1],
            'two groups' => [$eventWithTwoGroups, 2],
        ];
    }

    /**
     * @dataProvider getEventGroupsReturnsEventGroupArrayDataProvider
     * @test
     */
    public function getEventGroupsReturnsEventGroupArray(Event $event, int $groupCount)
    {
        $result = DtoUtility::getEventGroups($event);
        self::assertContainsOnlyInstancesOf(EventGroup::class, $result);
        self::assertCount($groupCount, $result);
    }
}
