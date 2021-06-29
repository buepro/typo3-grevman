<?php

declare(strict_types=1);

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

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
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $title = '';

    /**
     * slug
     *
     * @var string
     */
    protected $slug = '';

    /**
     * startdate
     *
     * @var \DateTime
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $startdate = null;

    /**
     * enddate
     *
     * @var \DateTime
     */
    protected $enddate = null;

    /**
     * teaser
     *
     * @var string
     */
    protected $teaser = '';

    /**
     * description
     *
     * @var string
     */
    protected $description = '';

    /**
     * price
     *
     * @var float
     */
    protected $price = 0.0;

    /**
     * link
     *
     * @var string
     */
    protected $link = '';

    /**
     * program
     *
     * @var string
     */
    protected $program = '';

    /**
     * location
     *
     * @var string
     */
    protected $location = '';

    /**
     * images
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $images = null;

    /**
     * files
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $files = null;

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
        $this->images = $this->images ?: new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->files = $this->files ?: new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
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

    /**
     * Returns the slug
     *
     * @return string $slug
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Sets the slug
     *
     * @param string $slug
     * @return void
     */
    public function setSlug(string $slug)
    {
        $this->slug = $slug;
    }

    /**
     * Returns the startdate
     *
     * @return \DateTime $startdate
     */
    public function getStartdate()
    {
        return $this->startdate;
    }

    /**
     * Sets the startdate
     *
     * @param \DateTime $startdate
     * @return void
     */
    public function setStartdate(\DateTime $startdate)
    {
        $this->startdate = $startdate;
    }

    /**
     * Returns the enddate
     *
     * @return \DateTime $enddate
     */
    public function getEnddate()
    {
        return $this->enddate;
    }

    /**
     * Sets the enddate
     *
     * @param \DateTime $enddate
     * @return void
     */
    public function setEnddate(\DateTime $enddate)
    {
        $this->enddate = $enddate;
    }

    /**
     * Returns the teaser
     *
     * @return string $teaser
     */
    public function getTeaser()
    {
        return $this->teaser;
    }

    /**
     * Sets the teaser
     *
     * @param string $teaser
     * @return void
     */
    public function setTeaser(string $teaser)
    {
        $this->teaser = $teaser;
    }

    /**
     * Returns the description
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the description
     *
     * @param string $description
     * @return void
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * Returns the price
     *
     * @return float $price
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Sets the price
     *
     * @param float $price
     * @return void
     */
    public function setPrice(float $price)
    {
        $this->price = $price;
    }

    /**
     * Returns the link
     *
     * @return string $link
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Sets the link
     *
     * @param string $link
     * @return void
     */
    public function setLink(string $link)
    {
        $this->link = $link;
    }

    /**
     * Returns the program
     *
     * @return string $program
     */
    public function getProgram()
    {
        return $this->program;
    }

    /**
     * Sets the program
     *
     * @param string $program
     * @return void
     */
    public function setProgram(string $program)
    {
        $this->program = $program;
    }

    /**
     * Returns the location
     *
     * @return string $location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Sets the location
     *
     * @param string $location
     * @return void
     */
    public function setLocation(string $location)
    {
        $this->location = $location;
    }

    /**
     * Adds a FileReference
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $image
     * @return void
     */
    public function addImage(\TYPO3\CMS\Extbase\Domain\Model\FileReference $image)
    {
        $this->images->attach($image);
    }

    /**
     * Removes a FileReference
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $imageToRemove The FileReference to be removed
     * @return void
     */
    public function removeImage(\TYPO3\CMS\Extbase\Domain\Model\FileReference $imageToRemove)
    {
        $this->images->detach($imageToRemove);
    }

    /**
     * Returns the images
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $images
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Sets the images
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $images
     * @return void
     */
    public function setImages(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $images)
    {
        $this->images = $images;
    }

    /**
     * Adds a FileReference
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $file
     * @return void
     */
    public function addFile(\TYPO3\CMS\Extbase\Domain\Model\FileReference $file)
    {
        $this->files->attach($file);
    }

    /**
     * Removes a FileReference
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $fileToRemove The FileReference to be removed
     * @return void
     */
    public function removeFile(\TYPO3\CMS\Extbase\Domain\Model\FileReference $fileToRemove)
    {
        $this->files->detach($fileToRemove);
    }

    /**
     * Returns the files
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $files
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Sets the files
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $files
     * @return void
     */
    public function setFiles(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $files)
    {
        $this->files = $files;
    }

    public function getRegistrationForMember(Member $member): ?Registration
    {
        $result = null;
        if (!$member) {
            return $result;
        }
        $memberUid = $member->getUid();
        foreach ($this->getRegistrations() as $registration) {
            /** @var Registration $registration */
            if ($registration->getMember() && $registration->getMember()->getUid() === $memberUid) {
                $result = $registration;
            }
        }
        return $result;
    }

    /**
     * Returns the group assigned to this event for a given member. In case the member belongs to a group that isn't
     * assigned to this event null is returned.
     *
     * @param Member $member
     * @return Group|null
     */
    public function getEventGroup(Member $member): ?Group
    {
        foreach ($this->memberGroups as $memberGroup) {
            /** @var Group $memberGroup */
            foreach ($memberGroup->getMembers() as $groupMember) {
                if ($groupMember === $member) {
                    return $memberGroup;
                }
            }
        }
        return null;
    }

    /**
     * Gets all registrations from members not belonging to an event group being assigned to this event. In other words
     * the members from the returned registrations might belong to other event groups used for other events.
     *
     * @return array|null
     */
    public function getSpontaneousRegistrations(): ?array
    {
        $result = null;
        foreach ($this->registrations as $registration) {
            /** @var Registration $registration */
            if (!$this->getEventGroup($registration->getMember())) {
                $result[] = $registration;
            }
        }
        return $result;
    }
}
