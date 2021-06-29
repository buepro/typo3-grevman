<?php
declare(strict_types=1);

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Grevman\Tests\Unit\Domain\Model;

use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 *
 */
class NoteTest extends UnitTestCase
{
    /**
     * @var \Buepro\Grevman\Domain\Model\Note
     */
    protected $subject;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \Buepro\Grevman\Domain\Model\Note();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getTextReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getText()
        );
    }

    /**
     * @test
     */
    public function setTextForStringSetsText()
    {
        $this->subject->setText('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'text',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getMemberReturnsInitialValueForMember()
    {
        self::assertEquals(
            null,
            $this->subject->getMember()
        );
    }

    /**
     * @test
     */
    public function setMemberForMemberSetsMember()
    {
        $memberFixture = new \Buepro\Grevman\Domain\Model\Member();
        $this->subject->setMember($memberFixture);

        self::assertAttributeEquals(
            $memberFixture,
            'member',
            $this->subject
        );
    }
}
