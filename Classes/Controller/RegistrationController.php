<?php

declare(strict_types=1);

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Grevman\Controller;

/**
 * This file is part of the "Group event manager" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2021 Roman Büchler <rb@buechler.pro>, buechler.pro gmbh
 */

/**
 * RegistrationController
 */
class RegistrationController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * registrationRepository
     *
     * @var \Buepro\Grevman\Domain\Repository\RegistrationRepository
     */
    protected $registrationRepository = null;

    /**
     * @param \Buepro\Grevman\Domain\Repository\RegistrationRepository $registrationRepository
     */
    public function injectRegistrationRepository(\Buepro\Grevman\Domain\Repository\RegistrationRepository $registrationRepository)
    {
        $this->registrationRepository = $registrationRepository;
    }

    /**
     * action new
     *
     * @return string|object|null|void
     */
    public function newAction()
    {
    }

    /**
     * action create
     *
     * @param \Buepro\Grevman\Domain\Model\Registration $newRegistration
     * @return string|object|null|void
     */
    public function createAction(\Buepro\Grevman\Domain\Model\Registration $newRegistration)
    {
        $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->registrationRepository->add($newRegistration);
        $this->redirect('list');
    }

    /**
     * action edit
     *
     * @param \Buepro\Grevman\Domain\Model\Registration $registration
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("registration")
     * @return string|object|null|void
     */
    public function editAction(\Buepro\Grevman\Domain\Model\Registration $registration)
    {
        $this->view->assign('registration', $registration);
    }

    /**
     * action update
     *
     * @param \Buepro\Grevman\Domain\Model\Registration $registration
     * @return string|object|null|void
     */
    public function updateAction(\Buepro\Grevman\Domain\Model\Registration $registration)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->registrationRepository->update($registration);
        $this->redirect('list');
    }
}
