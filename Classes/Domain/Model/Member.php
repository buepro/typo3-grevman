<?php

declare(strict_types=1);

namespace Buepro\Grevman\Domain\Model;


use Buepro\Grevman\Service\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;

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
class Member extends \TYPO3\CMS\Extbase\Domain\Model\FrontendUser
{

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
     * __construct
     *
     * @param string $username
     * @param string $password
     */
    public function __construct($username = '', $password = '')
    {
        parent::__construct($username = '', $password = '');
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

    public function getScreenName(): string
    {
        $parts = [];
        if ($this->getFirstName()) {
            $parts[] = $this->getFirstName();
        }
        if ($this->getLastName()) {
            $parts[] = $this->getLastName();
        }
        return $parts ? implode(' ', $parts) : '';
    }

    /**
     * True if the member belongs to a leader group.
     *
     * @return bool
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
