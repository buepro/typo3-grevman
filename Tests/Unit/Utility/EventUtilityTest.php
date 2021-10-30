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
     * @var \DateTimeZone
     */
    protected $timezone;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->timezone = new \DateTimeZone('Europe/Helsinki');
    }

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
    public function getStartdateFromIdReturnsStartdateOrNull(): void
    {
        $this->assertNull(EventUtility::getStartdateFromId(''));
        $this->assertNull(EventUtility::getStartdateFromId('1'));
        $this->assertSame(23456, EventUtility::getStartdateFromId('1-23456')->getTimestamp());
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

    /**
     * @test
     */
    public function getDatesFromFieldReturnsEmptyArray(): void
    {
        $fieldValue = date($GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy']);
        $wrongFieldValues = ['6. Oct. 2005', '6', '10', '2005'];
        $startdate = new \DateTime('20211006T080000', $this->timezone);
        $eventWithStartdate = (new Event())->setStartdate($startdate);
        $this->assertSame([], EventUtility::getDatesFromField(new Event(), $fieldValue, $this->timezone));
        $this->assertSame([], EventUtility::getDatesFromField($eventWithStartdate, '', $this->timezone));
        foreach ($wrongFieldValues as $wrongFieldValue) {
            $this->assertSame([], EventUtility::getDatesFromField($eventWithStartdate, $wrongFieldValue, $this->timezone));
        }
    }

    /**
     * @test
     */
    public function getDatesFromFieldReturnsSameTimeAndTimezone(): void
    {
        $summerDate = new \DateTime('20220801T143000', $this->timezone);
        $winterDate = new \DateTime('20230201T100000', $this->timezone);
        $fieldValue = sprintf(
            "%s\n%s",
            date($GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy'], $summerDate->getTimestamp()),
            date($GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy'], $winterDate->getTimestamp())
        );
        $startdate = new \DateTime('20211006T080000', $this->timezone);
        $actualDates = EventUtility::getDatesFromField(
            (new Event())->setStartdate($startdate),
            $fieldValue,
            $this->timezone
        );
        foreach ($actualDates as $actual) {
            $this->assertEquals($startdate->format('H.i.s'), $actual->format('H.i.s'));
            $this->assertEquals($this->timezone->getName(), $actual->getTimezone()->getName());
        }
    }

    /**
     * @test
     */
    public function getEventRecurrencesReturnsEmptyArray(): void
    {
        $startdate = new \DateTime('20211006T080000', $this->timezone);
        $validEvent = (new Event())->setStartdate($startdate)->setEnableRecurrence(true);
        $this->assertSame([], EventUtility::getEventRecurrences([$validEvent], 0));
        $this->assertSame([], EventUtility::getEventRecurrences([], 90));
        $this->assertSame([], EventUtility::getEventRecurrences([new Event()], 90));
        $this->assertSame([], EventUtility::getEventRecurrences([(new Event())->setStartdate($startdate)], 90));
        $this->assertSame([], EventUtility::getEventRecurrences([(new Event())->setEnableRecurrence(true)], 90));
    }

    public function getEventRecurrencesReturnsEventRecurrencesDataProvider(): array
    {
        $now = time();
        $startdate = new \DateTime('now', $this->timezone);
        $startdate->setTimestamp($now);
        $baseEvent = (new Event())->setStartdate($startdate);

        // Test RRule
        $rruleEvent = clone $baseEvent;
        $rruleEvent->setEnableRecurrence(true)
            ->setRecurrenceRule('FREQ=MONTHLY;INTERVAL=3');
        $i = 0;
        $expectedRRuleEvents = [];
        while (++$i < 4) {
            $expectedRRuleEvent = clone $baseEvent;
            $expectedRRuleEvent->getStartdate()->add(new \DateInterval('P' . $i * 3 . 'M'));
            $expectedRRuleEvents[] = $expectedRRuleEvent;
        }

        // Test including dates
        $datesEvent = clone $baseEvent;
        $datesEvent->setEnableRecurrence(true)
            ->setRecurrenceDates(date($GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy'], time() + 86400));
        $expectedDatesEvent = clone $baseEvent;
        $expectedDatesEvent->getStartdate()->add(new \DateInterval('P1D'));

        // Test exception dates
        $exDate = clone $baseEvent->getStartdate();
        $exDate->add(new \DateInterval('P40D'));
        $exDatesEvent = clone $baseEvent;
        $exDatesEvent->setEnableRecurrence(true)
            ->setRecurrenceRule('FREQ=DAILY;INTERVAL=40')
            ->setRecurrenceExceptionDates(date($GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy'], $exDate->getTimestamp()));
        $expectedExDatesEvent = clone $baseEvent;
        $expectedExDatesEvent->getStartdate()->add(new \DateInterval('P80D'));

        return [
            'rrule' => [[$rruleEvent], $expectedRRuleEvents],
            'including dates' => [[$datesEvent], [$expectedDatesEvent]],
            'exception dates' => [[$exDatesEvent], [$expectedExDatesEvent]],
        ];
    }

    /**
     * @dataProvider getEventRecurrencesReturnsEventRecurrencesDataProvider
     * @test
     * @param Event[] $events
     * @param Event[] $expectedEvents
     * @throws \Exception
     */
    public function getEventRecurrencesReturnsEventRecurrences(array $events, array $expectedEvents): void
    {
        $recurrences = EventUtility::getEventRecurrences($events, 360, $this->timezone);
        reset($recurrences);
        foreach ($expectedEvents as $expectedEvent) {
            $this->assertEquals(
                $expectedEvent->getStartdate()->format('d.m.Y H.i.s'),
                current($recurrences)->getStartdate()->format('d.m.Y H.i.s')
            );
            next($recurrences);
        }
    }

    public function mergeAndOrderEventsMergesAndOrdersDataProvider(): array
    {
        $now = time();
        $testTime = $now + 86400;
        $startdate = new \DateTime('now', $this->timezone);
        $startdate->setTimestamp($now);
        $baseEvent = (new Event())->setStartdate($startdate);

        // Regular event
        $event = clone $baseEvent;
        $event->_setProperty('uid', 1);
        $event->getStartdate()->setTimestamp($testTime);

        // Not persisted recurrence event
        $recurrenceEvent = $event::createChild($event, clone $startdate);
        $recurrenceEvent->getStartdate()->setTimestamp($testTime + 1);

        // Persisted event from recurrence
        $persistedEvent = clone $recurrenceEvent;
        $persistedEvent->_setProperty('uid', 2);

        return [
            'merge' => [[$event, $recurrenceEvent], [[$event], [$recurrenceEvent]]],
            'merge unordered' => [[$event, $recurrenceEvent], [[$recurrenceEvent], [$event]]],
            'merge with duplicate' => [[$event, $persistedEvent], [[$recurrenceEvent], [$event, $persistedEvent]]],
        ];
    }

    /**
     * @dataProvider mergeAndOrderEventsMergesAndOrdersDataProvider
     * @test
     */
    public function mergeAndOrderEventsMergesAndOrders(array $expectedEvents, array $eventSets): void
    {
        $this->assertSame($expectedEvents, EventUtility::mergeAndOrderEvents(...$eventSets));
    }
}
