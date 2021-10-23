<?php

declare(strict_types=1);

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Grevman\Domain\Dto;

use Buepro\Grevman\Domain\Model\Event;
use Buepro\Grevman\Domain\Model\Member;
use TYPO3\CMS\Extbase\Annotation as Extbase;

class Note
{
    /**
     * @var Event
     */
    protected $event;

    /**
     * @var Member
     */
    protected $member;

    /**
     * @var string
     * @Extbase\Validate("NotEmpty")
     */
    protected $text;

    /**
     * SendMail constructor.
     * @param Event $event
     * @param Member $member
     * @param string $text
     */
    public function __construct(Event $event, Member $member, string $text)
    {
        $this->event = $event;
        $this->member = $member;
        $this->text = $text;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }

    public function getMember(): Member
    {
        return $this->member;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function createNote(): \Buepro\Grevman\Domain\Model\Note
    {
        $note = new \Buepro\Grevman\Domain\Model\Note();
        $note->setMember($this->member);
        $note->setText($this->text);
        return $note;
    }
}
