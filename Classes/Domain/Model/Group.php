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
 * Group
 */
class Group extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * name
     *
     * @var string
     */
    protected $name = '';

    /**
     * events
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Buepro\Grevman\Domain\Model\Event>
     */
    protected $events = null;

    /**
     * members
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Buepro\Grevman\Domain\Model\Member>
     */
    protected $members = null;

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
        $this->events = $this->events ?: new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->members = $this->members ?: new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Adds a Event
     *
     * @param \Buepro\Grevman\Domain\Model\Event $event
     * @return void
     */
    public function addEvent(\Buepro\Grevman\Domain\Model\Event $event)
    {
        $this->events->attach($event);
    }

    /**
     * Removes a Event
     *
     * @param \Buepro\Grevman\Domain\Model\Event $eventToRemove The Event to be removed
     * @return void
     */
    public function removeEvent(\Buepro\Grevman\Domain\Model\Event $eventToRemove)
    {
        $this->events->detach($eventToRemove);
    }

    /**
     * Returns the events
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Buepro\Grevman\Domain\Model\Event> $events
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Sets the events
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Buepro\Grevman\Domain\Model\Event> $events
     * @return void
     */
    public function setEvents(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $events)
    {
        $this->events = $events;
    }

    /**
     * Adds a Member
     *
     * @param \Buepro\Grevman\Domain\Model\Member $member
     * @return void
     */
    public function addMember(\Buepro\Grevman\Domain\Model\Member $member)
    {
        $this->members->attach($member);
    }

    /**
     * Removes a Member
     *
     * @param \Buepro\Grevman\Domain\Model\Member $memberToRemove The Member to be removed
     * @return void
     */
    public function removeMember(\Buepro\Grevman\Domain\Model\Member $memberToRemove)
    {
        $this->members->detach($memberToRemove);
    }

    /**
     * Returns the members
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Buepro\Grevman\Domain\Model\Member> $members
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * Sets the members
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Buepro\Grevman\Domain\Model\Member> $members
     * @return void
     */
    public function setMembers(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $members)
    {
        $this->members = $members;
    }

    /**
     * Returns the name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name
     *
     * @param string $name
     * @return void
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }
}
