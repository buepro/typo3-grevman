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
 * Registration
 */
class Registration extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
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
     * @var \Buepro\Grevman\Domain\Model\Member
     */
    protected $member = null;

    /**
     * Returns the state
     *
     * @return int $state
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Sets the state
     *
     * @param int $state
     * @return void
     */
    public function setState(int $state)
    {
        $this->state = $state;
    }

    /**
     * Returns the member
     *
     * @return \Buepro\Grevman\Domain\Model\Member $member
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * Sets the member
     *
     * @param \Buepro\Grevman\Domain\Model\Member $member
     * @return void
     */
    public function setMember(\Buepro\Grevman\Domain\Model\Member $member)
    {
        $this->member = $member;
    }
}
