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
 * Note
 */
class Note extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * text
     *
     * @var string
     */
    protected $text = '';

    /**
     * member
     *
     * @var \Buepro\Grevman\Domain\Model\Member
     */
    protected $member = null;

    /**
     * Returns the text
     *
     * @return string $text
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Sets the text
     *
     * @param string $text
     * @return void
     */
    public function setText(string $text)
    {
        $this->text = $text;
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
