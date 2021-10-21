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
 * Note
 */
class Note extends AbstractEntity
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
     * @var Member
     */
    protected $member = null;

    /**
     * Returns the text
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Sets the text
     */
    public function setText(string $text): self
    {
        $this->text = $text;
        return $this;
    }

    /**
     * Returns the member
     */
    public function getMember(): ?Member
    {
        return $this->member;
    }

    /**
     * Sets the member
     */
    public function setMember(Member $member): self
    {
        $this->member = $member;
        return $this;
    }
}
