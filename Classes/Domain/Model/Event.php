<?php

declare(strict_types=1);

namespace Buepro\Grevman\Domain\Model;


/**
 * This file is part of the "Group event manager" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2021 Roman Büchler <rb@buechler.pro>, buechler.pro gmbh
 */

/**
 * Event
 */
class Event extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * title
     *
     * @var string
     */
    protected $title = '';

    /**
     * memberGroups
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Buepro\Grevman\Domain\Model\Group>
     */
    protected $memberGroups = null;

    /**
     * registrations
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Buepro\Grevman\Domain\Model\Registration>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $registrations = null;

    /**
     * notes
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Buepro\Grevman\Domain\Model\Note>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $notes = null;

    /**
     * guests
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Buepro\Grevman\Domain\Model\Guest>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $guests = null;

    /**
     * __construct
     */
    public function __construct()
    {

        // Do not remove the next line: It would break the functionality
        $this->initializeObject();
    }

    /**
     * Initializes all ObjectStorage properties when model is reconstructed from DB (where __construct is not called)
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    public function initializeObject()
    {
        $this->memberGroups = $this->memberGroups ?: new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->registrations = $this->registrations ?: new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->notes = $this->notes ?: new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->guests = $this->guests ?: new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Returns the title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * @param string $title
     * @return void
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * Adds a Group
     *
     * @param \Buepro\Grevman\Domain\Model\Group $memberGroup
     * @return void
     */
    public function addMemberGroup(\Buepro\Grevman\Domain\Model\Group $memberGroup)
    {
        $this->memberGroups->attach($memberGroup);
    }

    /**
     * Removes a Group
     *
     * @param \Buepro\Grevman\Domain\Model\Group $memberGroupToRemove The Group to be removed
     * @return void
     */
    public function removeMemberGroup(\Buepro\Grevman\Domain\Model\Group $memberGroupToRemove)
    {
        $this->memberGroups->detach($memberGroupToRemove);
    }

    /**
     * Returns the memberGroups
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Buepro\Grevman\Domain\Model\Group> $memberGroups
     */
    public function getMemberGroups()
    {
        return $this->memberGroups;
    }

    /**
     * Sets the memberGroups
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Buepro\Grevman\Domain\Model\Group> $memberGroups
     * @return void
     */
    public function setMemberGroups(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $memberGroups)
    {
        $this->memberGroups = $memberGroups;
    }

    /**
     * Adds a Registration
     *
     * @param \Buepro\Grevman\Domain\Model\Registration $registration
     * @return void
     */
    public function addRegistration(\Buepro\Grevman\Domain\Model\Registration $registration)
    {
        $this->registrations->attach($registration);
    }

    /**
     * Removes a Registration
     *
     * @param \Buepro\Grevman\Domain\Model\Registration $registrationToRemove The Registration to be removed
     * @return void
     */
    public function removeRegistration(\Buepro\Grevman\Domain\Model\Registration $registrationToRemove)
    {
        $this->registrations->detach($registrationToRemove);
    }

    /**
     * Returns the registrations
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Buepro\Grevman\Domain\Model\Registration> $registrations
     */
    public function getRegistrations()
    {
        return $this->registrations;
    }

    /**
     * Sets the registrations
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Buepro\Grevman\Domain\Model\Registration> $registrations
     * @return void
     */
    public function setRegistrations(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $registrations)
    {
        $this->registrations = $registrations;
    }

    /**
     * Adds a Note
     *
     * @param \Buepro\Grevman\Domain\Model\Note $note
     * @return void
     */
    public function addNote(\Buepro\Grevman\Domain\Model\Note $note)
    {
        $this->notes->attach($note);
    }

    /**
     * Removes a Note
     *
     * @param \Buepro\Grevman\Domain\Model\Note $noteToRemove The Note to be removed
     * @return void
     */
    public function removeNote(\Buepro\Grevman\Domain\Model\Note $noteToRemove)
    {
        $this->notes->detach($noteToRemove);
    }

    /**
     * Returns the notes
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Buepro\Grevman\Domain\Model\Note> $notes
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Sets the notes
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Buepro\Grevman\Domain\Model\Note> $notes
     * @return void
     */
    public function setNotes(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $notes)
    {
        $this->notes = $notes;
    }

    /**
     * Adds a Guest
     *
     * @param \Buepro\Grevman\Domain\Model\Guest $guest
     * @return void
     */
    public function addGuest(\Buepro\Grevman\Domain\Model\Guest $guest)
    {
        $this->guests->attach($guest);
    }

    /**
     * Removes a Guest
     *
     * @param \Buepro\Grevman\Domain\Model\Guest $guestToRemove The Guest to be removed
     * @return void
     */
    public function removeGuest(\Buepro\Grevman\Domain\Model\Guest $guestToRemove)
    {
        $this->guests->detach($guestToRemove);
    }

    /**
     * Returns the guests
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Buepro\Grevman\Domain\Model\Guest> $guests
     */
    public function getGuests()
    {
        return $this->guests;
    }

    /**
     * Sets the guests
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Buepro\Grevman\Domain\Model\Guest> $guests
     * @return void
     */
    public function setGuests(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $guests)
    {
        $this->guests = $guests;
    }
}
