<?php

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Grevman\Tests\Unit\Utility;

use Buepro\Grevman\Domain\Model\Event;
use Buepro\Grevman\Utility\EventUtility;

class EventUtilityTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @test
     */
    public function createIdReturnsString(): void
    {
        $this->assertIsString(EventUtility::createId(new Event()));
        $event = new Event();
        $event->_setProperty('uid', 1);
        $this->assertIsString(EventUtility::createId($event));
    }

    public function createIdReturnsIdDataProvider(): array
    {
        $time = 1635146555;
        $parent = new Event();
        $parent->_setProperty('uid', 1);
        $child = new Event();
        $child->setParent($parent)->setStartdate((new \DateTime())->setTimestamp($time));
        $childWithoutParent = new Event();
        $childWithoutParent->setStartdate((new \DateTime())->setTimestamp($time));
        $childWithoutStartdate = new Event();
        $childWithoutStartdate->setParent($parent);
        return [
            'event' => [$parent, '1'],
            'child' => [$child, '1-' . $time],
            'child without parent' => [$childWithoutParent, '0-' . $time],
            'child without startdate' => [$childWithoutStartdate, '1-0']
        ];
    }

    /**
     * @dataProvider createIdReturnsIdDataProvider
     * @test
     */
    public function createIdReturnsCorrectValue(Event $event, string $expected): void
    {
        $this->assertSame($expected, EventUtility::createId($event));
    }

    /**
     * @test
     */
    public function getIdArrayReturnsArray(): void
    {
        $this->assertIsArray(EventUtility::getIdArray(''));
    }

    public function getIdArrayReturnsIdArrayDataProvider(): array
    {
        return [
            'no id' => ['', []],
            'uid' => ['1', ['uid' => 1]],
            'parentUid and startdate' => ['1-23456', ['parentUid' => 1, 'startdate' => 23456]],
        ];
    }

    /**
     * @dataProvider getIdArrayReturnsIdArrayDataProvider
     * @test
     */
    public function getIdArrayReturnsCorrectData(string $id, array $expected): void
    {
        $this->assertSame(EventUtility::getIdArray($id), $expected);
    }

    /**
     * @test
     */
    public function getParentUidFromIdReturnsUidOrZero(): void
    {
        $this->assertSame(0, EventUtility::getParentUidFromId(''));
        $this->assertSame(0, EventUtility::getParentUidFromId('23456'));
        $this->assertSame(1, EventUtility::getParentUidFromId('1-23456'));
    }

    /**
     * @test
     */
    public function getPropertyMappingArrayReturnsArray(): void
    {
        $this->assertIsArray(EventUtility::getPropertyMappingArray(new Event()));
    }

    public function getPropertyMappingArrayReturnsCorrectDataDataProvider(): array
    {
        $expectedDefaults = [
            'title' => '',
            'slug' => '',
            'teaser' => '',
            'description' => '',
            'price' => 0.0,
            'link' => '',
            'program' => '',
            'location' => '',
        ];
        $parent = new Event();
        $parent->_setProperty('uid', 1);
        return [
            'child without parent' => [new Event(), $expectedDefaults],
            'child with parent' => [(new Event())->setParent($parent), array_merge(['parent' => 1], $expectedDefaults)],
            'child with parent and title' => [
                (new Event())->setParent($parent)->setTitle('testevent'),
                array_merge(['parent' => 1], $expectedDefaults, ['title' => 'testevent'])
            ]
        ];
    }

    /**
     * @dataProvider getPropertyMappingArrayReturnsCorrectDataDataProvider
     * @test
     */
    public function getPropertyMappingArrayReturnsCorrectData(Event $event, array $expected): void
    {
        $this->assertSame($expected, EventUtility::getPropertyMappingArray($event));
    }
}
