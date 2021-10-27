<?php

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Grevman\Domain\Model;

trait EventRecurrenceTrait
{
    /**
     * @var Event
     */
    protected $parent = null;

    /**
     * @var bool
     */
    protected $enableRecurrence = false;

    /**
     * recurrenceEnddate
     *
     * @var \DateTime|null
     */
    protected $recurrenceEnddate = null;

    /**
     * @var string
     */
    protected $recurrenceRule = '';

    /**
     * @var string
     */
    protected $recurrenceDates = '';

    /**
     * @var string
     */
    protected $recurrenceExceptionDates = '';

    /**
     * @var string
     */
    protected $recurrenceSet = '';

    /**
     * Returns the parent
     */
    public function getParent(): ?self
    {
        return $this->parent;
    }

    /**
     * Sets the parent
     */
    public function setParent(self $parent): self
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Returns the enableRecurrence
     */
    public function getEnableRecurrence(): bool
    {
        return $this->enableRecurrence;
    }

    /**
     * Sets the enableRecurrence
     */
    public function setEnableRecurrence(bool $enableRecurrence): self
    {
        $this->enableRecurrence = $enableRecurrence;
        return $this;
    }

    /**
     * Returns the recurrenceEnddate
     */
    public function getRecurrenceEnddate(): ?\DateTime
    {
        return $this->recurrenceEnddate;
    }

    /**
     * Sets the recurrenceEnddate
     */
    public function setRecurrenceEnddate(\DateTime $recurrenceEnddate): self
    {
        $this->recurrenceEnddate = $recurrenceEnddate;
        return $this;
    }

    /**
     * Returns the recurrenceRule
     */
    public function getRecurrenceRule(): string
    {
        return $this->recurrenceRule;
    }

    /**
     * Sets the recurrenceRule
     */
    public function setRecurrenceRule(string $recurrenceRule): self
    {
        $this->recurrenceRule = $recurrenceRule;
        return $this;
    }

    /**
     * Returns the recurrenceDates
     */
    public function getRecurrenceDates(): string
    {
        return $this->recurrenceDates;
    }

    /**
     * Sets the recurrenceDates
     */
    public function setRecurrenceDates(string $recurrenceDates): self
    {
        $this->recurrenceDates = $recurrenceDates;
        return $this;
    }

    /**
     * Returns the recurrenceExceptionDates
     */
    public function getRecurrenceExceptionDates(): string
    {
        return $this->recurrenceExceptionDates;
    }

    /**
     * Sets the recurrenceExceptionDates
     */
    public function setRecurrenceExceptionDates(string $recurrenceExceptionDates): self
    {
        $this->recurrenceExceptionDates = $recurrenceExceptionDates;
        return $this;
    }

    /**
     * Returns the recurrenceSet
     */
    public function getRecurrenceSet(): string
    {
        return $this->recurrenceSet;
    }

    /**
     * Sets the recurrenceSet
     */
    public function setRecurrenceSet(string $recurrenceSet): self
    {
        $this->recurrenceSet = $recurrenceSet;
        return $this;
    }
}
