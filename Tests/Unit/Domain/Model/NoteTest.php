<?php
declare(strict_types=1);

namespace Buepro\Grevman\Tests\Unit\Domain\Model;

use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 *
 * @author Roman Büchler <rb@buechler.pro>
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
