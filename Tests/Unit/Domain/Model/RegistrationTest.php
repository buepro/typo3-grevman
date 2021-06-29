<?php
declare(strict_types=1);

namespace Buepro\Grevman\Tests\Unit\Domain\Model;

use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 *
 * @author Roman Büchler <rb@buechler.pro>
 */
class RegistrationTest extends UnitTestCase
{
    /**
     * @var \Buepro\Grevman\Domain\Model\Registration
     */
    protected $subject;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \Buepro\Grevman\Domain\Model\Registration();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getStateReturnsInitialValueForInt()
    {
        self::assertSame(
            0,
            $this->subject->getState()
        );
    }

    /**
     * @test
     */
    public function setStateForIntSetsState()
    {
        $this->subject->setState(12);

        self::assertAttributeEquals(
            12,
            'state',
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
