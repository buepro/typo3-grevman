<?php

declare(strict_types=1);

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Grevman\Domain\Model;

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
 * Group
 */
class Group extends AbstractEntity
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
     * @var ObjectStorage<Event>
     */
    protected $events = null;

    /**
     * members
     *
     * @var ObjectStorage<Member>
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
     */
    public function initializeObject(): void
    {
        $this->events = $this->events ?: new ObjectStorage();
        $this->members = $this->members ?: new ObjectStorage();
    }

    /**
     * Adds a Event
     */
    public function addEvent(Event $event): self
    {
        $this->events->attach($event);
        return $this;
    }

    /**
     * Removes a Event
     */
    public function removeEvent(Event $eventToRemove): self
    {
        $this->events->detach($eventToRemove);
        return $this;
    }

    /**
     * Returns the events
     *
     * @return ObjectStorage<Event> $events
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Sets the events
     *
     * @param ObjectStorage<Event> $events
     */
    public function setEvents(ObjectStorage $events): self
    {
        $this->events = $events;
        return $this;
    }

    /**
     * Adds a Member
     */
    public function addMember(Member $member): self
    {
        $this->members->attach($member);
        return $this;
    }

    /**
     * Removes a Member
     */
    public function removeMember(Member $memberToRemove): self
    {
        $this->members->detach($memberToRemove);
        return $this;
    }

    /**
     * Returns the members
     *
     * @return ObjectStorage<Member> $members
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * Sets the members
     *
     * @param ObjectStorage<Member> $members
     */
    public function setMembers(ObjectStorage $members): self
    {
        $this->members = $members;
        return $this;
    }

    /**
     * Returns the name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the name
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
}
