<?php

declare(strict_types=1);

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Grevman\Domain\Model;

use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

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
class Event extends AbstractEntity
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
     * @var \DateTime|null
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $startdate = null;

    /**
     * enddate
     *
     * @var \DateTime|null
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
     * @var ObjectStorage<FileReference>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $images = null;

    /**
     * files
     *
     * @var ObjectStorage<FileReference>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $files = null;

    /**
     * memberGroups
     *
     * @var ObjectStorage<Group>
     */
    protected $memberGroups = null;

    /**
     * registrations
     *
     * @var ObjectStorage<Registration>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $registrations = null;

    /**
     * notes
     *
     * @var ObjectStorage<Note>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $notes = null;

    /**
     * guests
     *
     * @var ObjectStorage<Guest>
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
        $this->images = $this->images ?: new ObjectStorage();
        $this->files = $this->files ?: new ObjectStorage();
        $this->memberGroups = $this->memberGroups ?: new ObjectStorage();
        $this->registrations = $this->registrations ?: new ObjectStorage();
        $this->notes = $this->notes ?: new ObjectStorage();
        $this->guests = $this->guests ?: new ObjectStorage();
    }

    /**
     * Returns the title
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Sets the title
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Adds a Group
     *
     * @param Group $memberGroup
     */
    public function addMemberGroup(Group $memberGroup): self
    {
        $this->memberGroups->attach($memberGroup);
        return $this;
    }

    /**
     * Removes a Group
     *
     * @param Group $memberGroupToRemove The Group to be removed
     */
    public function removeMemberGroup(Group $memberGroupToRemove): self
    {
        $this->memberGroups->detach($memberGroupToRemove);
        return $this;
    }

    /**
     * Returns the memberGroups
     *
     * @return ObjectStorage<Group> $memberGroups
     */
    public function getMemberGroups()
    {
        return $this->memberGroups;
    }

    /**
     * Sets the memberGroups
     *
     * @param ObjectStorage<Group> $memberGroups
     */
    public function setMemberGroups(ObjectStorage $memberGroups): self
    {
        $this->memberGroups = $memberGroups;
        return $this;
    }

    /**
     * Adds a Registration
     *
     * @param Registration $registration
     */
    public function addRegistration(Registration $registration): self
    {
        $this->registrations->attach($registration);
        return $this;
    }

    /**
     * Removes a Registration
     *
     * @param Registration $registrationToRemove The Registration to be removed
     */
    public function removeRegistration(Registration $registrationToRemove): void
    {
        $this->registrations->detach($registrationToRemove);
    }

    /**
     * Returns the registrations
     *
     * @return ObjectStorage<Registration> $registrations
     */
    public function getRegistrations()
    {
        return $this->registrations;
    }

    /**
     * Sets the registrations
     *
     * @param ObjectStorage<Registration> $registrations
     */
    public function setRegistrations(ObjectStorage $registrations): self
    {
        $this->registrations = $registrations;
        return $this;
    }

    /**
     * Adds a Note
     */
    public function addNote(Note $note): self
    {
        $this->notes->attach($note);
        return $this;
    }

    /**
     * Removes a Note
     */
    public function removeNote(Note $noteToRemove): void
    {
        $this->notes->detach($noteToRemove);
    }

    /**
     * Returns the notes
     *
     * @return ObjectStorage<Note> $notes
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Sets the notes
     *
     * @param ObjectStorage<Note> $notes
     */
    public function setNotes(ObjectStorage $notes): self
    {
        $this->notes = $notes;
        return $this;
    }

    /**
     * Adds a Guest
     */
    public function addGuest(Guest $guest): self
    {
        $this->guests->attach($guest);
        return $this;
    }

    /**
     * Removes a Guest
     */
    public function removeGuest(Guest $guestToRemove): void
    {
        $this->guests->detach($guestToRemove);
    }

    /**
     * Returns the guests
     *
     * @return ObjectStorage<Guest> $guests
     */
    public function getGuests()
    {
        return $this->guests;
    }

    /**
     * Sets the guests
     *
     * @param ObjectStorage<Guest> $guests
     */
    public function setGuests(ObjectStorage $guests): self
    {
        $this->guests = $guests;
        return $this;
    }

    /**
     * Returns the slug
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Sets the slug
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * Returns the startdate
     */
    public function getStartdate(): ?\DateTime
    {
        return $this->startdate;
    }

    /**
     * Sets the startdate
     *
     * @param \DateTime $startdate
     */
    public function setStartdate(\DateTime $startdate): self
    {
        $this->startdate = $startdate;
        return $this;
    }

    /**
     * Returns the enddate
     */
    public function getEnddate(): ?\DateTime
    {
        return $this->enddate;
    }

    /**
     * Sets the enddate
     */
    public function setEnddate(\DateTime $enddate): self
    {
        $this->enddate = $enddate;
        return $this;
    }

    /**
     * Returns the teaser
     */
    public function getTeaser(): string
    {
        return $this->teaser;
    }

    /**
     * Sets the teaser
     */
    public function setTeaser(string $teaser): self
    {
        $this->teaser = $teaser;
        return $this;
    }

    /**
     * Returns the description
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Sets the description
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Returns the price
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * Sets the price
     */
    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }

    /**
     * Returns the link
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * Sets the link
     */
    public function setLink(string $link): self
    {
        $this->link = $link;
        return $this;
    }

    /**
     * Returns the program
     */
    public function getProgram(): string
    {
        return $this->program;
    }

    /**
     * Sets the program
     */
    public function setProgram(string $program): self
    {
        $this->program = $program;
        return $this;
    }

    /**
     * Returns the location
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * Sets the location
     */
    public function setLocation(string $location): self
    {
        $this->location = $location;
        return $this;
    }

    /**
     * Adds a FileReference
     */
    public function addImage(FileReference $image): self
    {
        $this->images->attach($image);
        return $this;
    }

    /**
     * Removes a FileReference
     */
    public function removeImage(FileReference $imageToRemove): void
    {
        $this->images->detach($imageToRemove);
    }

    /**
     * Returns the images
     *
     * @return ObjectStorage<FileReference> $images
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Sets the images
     *
     * @param ObjectStorage<FileReference> $images
     */
    public function setImages(ObjectStorage $images): self
    {
        $this->images = $images;
        return $this;
    }

    /**
     * Adds a FileReference
     */
    public function addFile(FileReference $file): self
    {
        $this->files->attach($file);
        return $this;
    }

    /**
     * Removes a FileReference
     */
    public function removeFile(FileReference $fileToRemove): void
    {
        $this->files->detach($fileToRemove);
    }

    /**
     * Returns the files
     *
     * @return ObjectStorage<FileReference> $files
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Sets the files
     *
     * @param ObjectStorage<FileReference> $files
     */
    public function setFiles(ObjectStorage $files): self
    {
        $this->files = $files;
        return $this;
    }

    public function getRegistrationForMember(Member $member): ?Registration
    {
        $result = null;
        $memberUid = $member->getUid();
        /** @var Registration $registration */
        foreach ($this->getRegistrations() as $registration) {
            if ($registration->getMember() !== null && $registration->getMember()->getUid() === $memberUid) {
                $result = $registration;
            }
        }
        return $result;
    }

    /**
     * Returns the first group found being assigned to this event for a given member. In case the member belongs to a
     * group that isn't assigned to this event null is returned.
     */
    public function getEventGroupForMember(Member $member): ?Group
    {
        /** @var Group $memberGroup */
        foreach ($this->memberGroups as $memberGroup) {
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
     */
    public function getSpontaneousRegistrations(): array
    {
        $result = [];
        foreach ($this->registrations as $registration) {
            /** @var Registration $registration */
            if ($registration->getMember() !== null && $this->getEventGroupForMember($registration->getMember()) === null) {
                $result[] = $registration;
            }
        }
        return $result;
    }
}
