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

/**
 * This file is part of the "Group event manager" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2021 Roman Büchler <rb@buechler.pro>, buechler.pro gmbh
 */

/**
 * Registration
 */
class Registration extends AbstractEntity
{
    public const REGISTRATION_UNDEFINED = 0;
    public const REGISTRATION_CONFIRMED = 6;
    public const REGISTRATION_CANCELED = 9;

    /**
     * state
     *
     * @var int
     */
    protected $state = self::REGISTRATION_UNDEFINED;

    /**
     * member
     *
     * @var Member
     */
    protected $member = null;

    /**
     * Returns the state
     */
    public function getState(): int
    {
        return $this->state;
    }

    /**
     * Sets the state
     */
    public function setState(int $state): void
    {
        $this->state = $state;
    }

    /**
     * Returns the member
     */
    public function getMember(): Member
    {
        return $this->member;
    }

    /**
     * Sets the member
     */
    public function setMember(Member $member): void
    {
        $this->member = $member;
    }
}
