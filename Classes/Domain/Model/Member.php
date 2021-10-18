<?php

declare(strict_types=1);

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Grevman\Domain\Model;

use Buepro\Grevman\Service\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
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
 * Member
 */
class Member extends AbstractEntity
{
    use PersonNameTrait;

    /**
     * @var string
     */
    protected $username = '';

    /**
     * @var string
     */
    protected $password = '';

    /**
     * @var ObjectStorage<FrontendUserGroup>
     */
    protected $usergroup;

    /**
     * @var string
     */
    protected $email = '';

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
     * __construct
     *
     * @param string $username
     * @param string $password
     */
    public function __construct($username = '', $password = '')
    {
        $this->username = $username;
        $this->password = $password;
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
        $this->memberGroups = $this->memberGroups ?: new ObjectStorage();
        $this->registrations = $this->registrations ?: new ObjectStorage();
        $this->notes = $this->notes ?: new ObjectStorage();
    }

    /**
     * Sets the username value
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Returns the username value
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Sets the password value
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Returns the password value
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Sets the usergroups. Keep in mind that the property is called "usergroup"
     * although it can hold several usergroups.
     *
     * @param ObjectStorage<FrontendUserGroup> $usergroup
     */
    public function setUsergroup(ObjectStorage $usergroup): self
    {
        $this->usergroup = $usergroup;
        return $this;
    }

    /**
     * Adds a usergroup to the frontend user
     */
    public function addUsergroup(FrontendUserGroup $usergroup): self
    {
        $this->usergroup->attach($usergroup);
        return $this;
    }

    /**
     * Removes a usergroup from the frontend user
     */
    public function removeUsergroup(FrontendUserGroup $usergroup): self
    {
        $this->usergroup->detach($usergroup);
        return $this;
    }

    /**
     * Returns the usergroups. Keep in mind that the property is called "usergroup"
     * although it can hold several usergroups.
     *
     * @return ObjectStorage<FrontendUserGroup> An object storage containing the usergroup
     */
    public function getUsergroup()
    {
        return $this->usergroup;
    }

    /**
     * Sets the email value
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Returns the email value
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Adds a Group
     */
    public function addMemberGroup(Group $memberGroup): self
    {
        $this->memberGroups->attach($memberGroup);
        return $this;
    }

    /**
     * Removes a Group
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
     */
    public function addRegistration(Registration $registration): self
    {
        $this->registrations->attach($registration);
        return $this;
    }

    /**
     * Removes a Registration
     */
    public function removeRegistration(Registration $registrationToRemove): self
    {
        $this->registrations->detach($registrationToRemove);
        return $this;
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
    public function removeNote(Note $noteToRemove): self
    {
        $this->notes->detach($noteToRemove);
        return $this;
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
     * True if the member belongs to a leader group.
     */
    public function getIsLeader(): bool
    {
        /** @var TypoScriptService $service */
        $service = GeneralUtility::makeInstance(TypoScriptService::class);
        $leaderGroups = $service->getLeaderGroups();
        foreach ($this->getUsergroup() as $usergroup) {
            if (in_array((string) $usergroup->getUid(), $leaderGroups, true)) {
                return true;
            }
        }
        return false;
    }
}
