<?php
declare(strict_types=1);

namespace Buepro\Grevman\Tests\Unit\Domain\Model;

use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 *
 * @author Roman Büchler <rb@buechler.pro>
 */
class GroupTest extends UnitTestCase
{
    /**
     * @var \Buepro\Grevman\Domain\Model\Group
     */
    protected $subject;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \Buepro\Grevman\Domain\Model\Group();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getNameReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getName()
        );
    }

    /**
     * @test
     */
    public function setNameForStringSetsName()
    {
        $this->subject->setName('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'name',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getEventsReturnsInitialValueForEvent()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getEvents()
        );
    }

    /**
     * @test
     */
    public function setEventsForObjectStorageContainingEventSetsEvents()
    {
        $event = new \Buepro\Grevman\Domain\Model\Event();
        $objectStorageHoldingExactlyOneEvents = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneEvents->attach($event);
        $this->subject->setEvents($objectStorageHoldingExactlyOneEvents);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOneEvents,
            'events',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addEventToObjectStorageHoldingEvents()
    {
        $event = new \Buepro\Grevman\Domain\Model\Event();
        $eventsObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $eventsObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($event));
        $this->inject($this->subject, 'events', $eventsObjectStorageMock);

        $this->subject->addEvent($event);
    }

    /**
     * @test
     */
    public function removeEventFromObjectStorageHoldingEvents()
    {
        $event = new \Buepro\Grevman\Domain\Model\Event();
        $eventsObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $eventsObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($event));
        $this->inject($this->subject, 'events', $eventsObjectStorageMock);

        $this->subject->removeEvent($event);
    }

    /**
     * @test
     */
    public function getMembersReturnsInitialValueForMember()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getMembers()
        );
    }

    /**
     * @test
     */
    public function setMembersForObjectStorageContainingMemberSetsMembers()
    {
        $member = new \Buepro\Grevman\Domain\Model\Member();
        $objectStorageHoldingExactlyOneMembers = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneMembers->attach($member);
        $this->subject->setMembers($objectStorageHoldingExactlyOneMembers);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOneMembers,
            'members',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addMemberToObjectStorageHoldingMembers()
    {
        $member = new \Buepro\Grevman\Domain\Model\Member();
        $membersObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $membersObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($member));
        $this->inject($this->subject, 'members', $membersObjectStorageMock);

        $this->subject->addMember($member);
    }

    /**
     * @test
     */
    public function removeMemberFromObjectStorageHoldingMembers()
    {
        $member = new \Buepro\Grevman\Domain\Model\Member();
        $membersObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $membersObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($member));
        $this->inject($this->subject, 'members', $membersObjectStorageMock);

        $this->subject->removeMember($member);
    }
}
