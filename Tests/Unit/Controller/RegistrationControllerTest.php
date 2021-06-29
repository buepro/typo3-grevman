<?php
declare(strict_types=1);

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Grevman\Tests\Unit\Controller;

use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 *
 */
class RegistrationControllerTest extends UnitTestCase
{
    /**
     * @var \Buepro\Grevman\Controller\RegistrationController
     */
    protected $subject;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder(\Buepro\Grevman\Controller\RegistrationController::class)
            ->setMethods(['redirect', 'forward', 'addFlashMessage'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function createActionAddsTheGivenRegistrationToRegistrationRepository()
    {
        $registration = new \Buepro\Grevman\Domain\Model\Registration();

        $registrationRepository = $this->getMockBuilder(\Buepro\Grevman\Domain\Repository\RegistrationRepository::class)
            ->setMethods(['add'])
            ->disableOriginalConstructor()
            ->getMock();

        $registrationRepository->expects(self::once())->method('add')->with($registration);
        $this->inject($this->subject, 'registrationRepository', $registrationRepository);

        $this->subject->createAction($registration);
    }

    /**
     * @test
     */
    public function editActionAssignsTheGivenRegistrationToView()
    {
        $registration = new \Buepro\Grevman\Domain\Model\Registration();

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('registration', $registration);

        $this->subject->editAction($registration);
    }

    /**
     * @test
     */
    public function updateActionUpdatesTheGivenRegistrationInRegistrationRepository()
    {
        $registration = new \Buepro\Grevman\Domain\Model\Registration();

        $registrationRepository = $this->getMockBuilder(\Buepro\Grevman\Domain\Repository\RegistrationRepository::class)
            ->setMethods(['update'])
            ->disableOriginalConstructor()
            ->getMock();

        $registrationRepository->expects(self::once())->method('update')->with($registration);
        $this->inject($this->subject, 'registrationRepository', $registrationRepository);

        $this->subject->updateAction($registration);
    }
}
