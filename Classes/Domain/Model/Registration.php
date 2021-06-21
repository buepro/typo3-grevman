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
 * Registration
 */
class Registration extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * status
     *
     * @var int
     */
    protected $status = 0;

    /**
     * member
     *
     * @var \Buepro\Grevman\Domain\Model\Member
     */
    protected $member = null;

    /**
     * Returns the status
     *
     * @return int $status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets the status
     *
     * @param int $status
     * @return void
     */
    public function setStatus(int $status)
    {
        $this->status = $status;
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
