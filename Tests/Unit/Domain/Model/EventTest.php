<?php
declare(strict_types=1);

namespace Buepro\Grevman\Tests\Unit\Domain\Model;

use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 *
 * @author Roman Büchler <rb@buechler.pro>
 */
class EventTest extends UnitTestCase
{
    /**
     * @var \Buepro\Grevman\Domain\Model\Event
     */
    protected $subject;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \Buepro\Grevman\Domain\Model\Event();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getTitleReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getTitle()
        );
    }

    /**
     * @test
     */
    public function setTitleForStringSetsTitle()
    {
        $this->subject->setTitle('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'title',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getMemberGroupsReturnsInitialValueForGroup()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getMemberGroups()
        );
    }

    /**
     * @test
     */
    public function setMemberGroupsForObjectStorageContainingGroupSetsMemberGroups()
    {
        $memberGroup = new \Buepro\Grevman\Domain\Model\Group();
        $objectStorageHoldingExactlyOneMemberGroups = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneMemberGroups->attach($memberGroup);
        $this->subject->setMemberGroups($objectStorageHoldingExactlyOneMemberGroups);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOneMemberGroups,
            'memberGroups',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addMemberGroupToObjectStorageHoldingMemberGroups()
    {
        $memberGroup = new \Buepro\Grevman\Domain\Model\Group();
        $memberGroupsObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $memberGroupsObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($memberGroup));
        $this->inject($this->subject, 'memberGroups', $memberGroupsObjectStorageMock);

        $this->subject->addMemberGroup($memberGroup);
    }

    /**
     * @test
     */
    public function removeMemberGroupFromObjectStorageHoldingMemberGroups()
    {
        $memberGroup = new \Buepro\Grevman\Domain\Model\Group();
        $memberGroupsObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $memberGroupsObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($memberGroup));
        $this->inject($this->subject, 'memberGroups', $memberGroupsObjectStorageMock);

        $this->subject->removeMemberGroup($memberGroup);
    }

    /**
     * @test
     */
    public function getRegistrationsReturnsInitialValueForRegistration()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getRegistrations()
        );
    }

    /**
     * @test
     */
    public function setRegistrationsForObjectStorageContainingRegistrationSetsRegistrations()
    {
        $registration = new \Buepro\Grevman\Domain\Model\Registration();
        $objectStorageHoldingExactlyOneRegistrations = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneRegistrations->attach($registration);
        $this->subject->setRegistrations($objectStorageHoldingExactlyOneRegistrations);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOneRegistrations,
            'registrations',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addRegistrationToObjectStorageHoldingRegistrations()
    {
        $registration = new \Buepro\Grevman\Domain\Model\Registration();
        $registrationsObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $registrationsObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($registration));
        $this->inject($this->subject, 'registrations', $registrationsObjectStorageMock);

        $this->subject->addRegistration($registration);
    }

    /**
     * @test
     */
    public function removeRegistrationFromObjectStorageHoldingRegistrations()
    {
        $registration = new \Buepro\Grevman\Domain\Model\Registration();
        $registrationsObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $registrationsObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($registration));
        $this->inject($this->subject, 'registrations', $registrationsObjectStorageMock);

        $this->subject->removeRegistration($registration);
    }

    /**
     * @test
     */
    public function getNotesReturnsInitialValueForNote()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getNotes()
        );
    }

    /**
     * @test
     */
    public function setNotesForObjectStorageContainingNoteSetsNotes()
    {
        $note = new \Buepro\Grevman\Domain\Model\Note();
        $objectStorageHoldingExactlyOneNotes = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneNotes->attach($note);
        $this->subject->setNotes($objectStorageHoldingExactlyOneNotes);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOneNotes,
            'notes',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addNoteToObjectStorageHoldingNotes()
    {
        $note = new \Buepro\Grevman\Domain\Model\Note();
        $notesObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $notesObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($note));
        $this->inject($this->subject, 'notes', $notesObjectStorageMock);

        $this->subject->addNote($note);
    }

    /**
     * @test
     */
    public function removeNoteFromObjectStorageHoldingNotes()
    {
        $note = new \Buepro\Grevman\Domain\Model\Note();
        $notesObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $notesObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($note));
        $this->inject($this->subject, 'notes', $notesObjectStorageMock);

        $this->subject->removeNote($note);
    }

    /**
     * @test
     */
    public function getGuestsReturnsInitialValueForGuest()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getGuests()
        );
    }

    /**
     * @test
     */
    public function setGuestsForObjectStorageContainingGuestSetsGuests()
    {
        $guest = new \Buepro\Grevman\Domain\Model\Guest();
        $objectStorageHoldingExactlyOneGuests = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneGuests->attach($guest);
        $this->subject->setGuests($objectStorageHoldingExactlyOneGuests);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOneGuests,
            'guests',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addGuestToObjectStorageHoldingGuests()
    {
        $guest = new \Buepro\Grevman\Domain\Model\Guest();
        $guestsObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $guestsObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($guest));
        $this->inject($this->subject, 'guests', $guestsObjectStorageMock);

        $this->subject->addGuest($guest);
    }

    /**
     * @test
     */
    public function removeGuestFromObjectStorageHoldingGuests()
    {
        $guest = new \Buepro\Grevman\Domain\Model\Guest();
        $guestsObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $guestsObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($guest));
        $this->inject($this->subject, 'guests', $guestsObjectStorageMock);

        $this->subject->removeGuest($guest);
    }
}
