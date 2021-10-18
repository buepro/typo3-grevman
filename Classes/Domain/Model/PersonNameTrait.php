<?php

declare(strict_types=1);

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Grevman\Domain\Model;

trait PersonNameTrait
{
    /**
     * @var string
     */
    protected $firstName = '';

    /**
     * @var string
     */
    protected $lastName = '';

    /**
     * Sets the firstName value
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * Returns the firstName value
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * Sets the lastName value
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * Returns the lastName value
     */
    public function getLastName(): string
    {
        return $this->lastName;
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
