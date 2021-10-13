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
 * Guest
 */
class Guest extends AbstractEntity
{

    /**
     * firstName
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $firstName = '';

    /**
     * lastName
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $lastName = '';

    /**
     * phone
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $phone = '';

    /**
     * email
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $email = '';

    /**
     * Returns the firstName
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * Sets the firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * Returns the lastName
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * Sets the lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * Returns the phone
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * Sets the phone
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * Returns the email
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Sets the email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getScreenName(): string
    {
        $parts = [];
        if ($this->getFirstName() !== '') {
            $parts[] = $this->getFirstName();
        }
        if ($this->getLastName() !== '') {
            $parts[] = $this->getLastName();
        }
        return (bool)$parts ? implode(' ', $parts) : '';
    }
}
