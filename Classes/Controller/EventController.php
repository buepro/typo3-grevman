<?php

declare(strict_types=1);

namespace Buepro\Grevman\Controller;


use Buepro\Grevman\Domain\Dto\EventMember;
use Buepro\Grevman\Domain\Dto\Mail;
use Buepro\Grevman\Domain\Model\Registration;
use Buepro\Grevman\Utility\DtoUtility;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

/**
 * This file is part of the "Group event manager" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2021 Roman Büchler <rb@buechler.pro>, buechler.pro gmbh
 */

/**
 * EventController
 */
class EventController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * eventRepository
     *
     * @var \Buepro\Grevman\Domain\Repository\EventRepository
     */
    protected $eventRepository = null;

    /**
     * memberRepository
     *
     * @var \Buepro\Grevman\Domain\Repository\MemberRepository
     */
    protected $memberRepository = null;

    /**
     * @param \Buepro\Grevman\Domain\Repository\EventRepository $eventRepository
     */
    public function injectEventRepository(
        \Buepro\Grevman\Domain\Repository\EventRepository $eventRepository
    ) {
        $this->eventRepository = $eventRepository;
    }

    /**
     * @param \Buepro\Grevman\Domain\Repository\MemberRepository $memberRepository
     */
    public function injectMemberRepository(
        \Buepro\Grevman\Domain\Repository\MemberRepository $memberRepository
    ) {
        $this->memberRepository = $memberRepository;
    }

    /**
     * action list
     *
     * @return string|object|null|void
     */
    public function listAction()
    {
        $events = $this->eventRepository->findAll();
        $this->view->assign('events', $events);
    }

    /**
     * action show
     *
     * @param \Buepro\Grevman\Domain\Model\Event $event
     * @return string|object|null|void
     */
    public function showAction(\Buepro\Grevman\Domain\Model\Event $event)
    {
        $identifiedEventMember = null;
        if ($GLOBALS['TSFE']->fe_user && $GLOBALS['TSFE']->fe_user->user['uid']) {
            $member = $this->memberRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
            $identifiedEventMember = new EventMember($event, $member);
        }
        $this->view->assignMultiple([
            'event' => $event,
            'eventGroups' => DtoUtility::getEventGroups($event),
            'identifiedEventMember' =>  $identifiedEventMember,
        ]);
    }

    public function registerAction(
        \Buepro\Grevman\Domain\Model\Event $event,
        \Buepro\Grevman\Domain\Model\Member $member
    ) {
        $persistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);
        $registration = $event->getRegistrationForMember($member);
        if (!$registration) {
            /** @var Registration $registration */
            $registration = GeneralUtility::makeInstance(Registration::class);
            $registration->setMember($member);
            $event->addRegistration($registration);
        }
        $registration->setStatus(Registration::REGISTRATION_CONFIRMED);
        $this->addFlashMessage(
            \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('registerConfirmation', 'grevman'),
            '', FlashMessage::INFO);

        $persistenceManager->add($event);
        $persistenceManager->persistAll();
        $this->redirect('show', null, null, ['event' => $event]);
    }

    public function unregisterAction(
        \Buepro\Grevman\Domain\Model\Event $event,
        \Buepro\Grevman\Domain\Model\Member $member
    ) {
        $registration = $event->getRegistrationForMember($member);
        $registration->setStatus(Registration::REGISTRATION_CANCELED);
        $this->addFlashMessage(
            \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('unregisterConfirmation', 'grevman'),
            '', FlashMessage::INFO);
        $persistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);
        $persistenceManager->add($registration);
        $persistenceManager->persistAll();
        $this->redirect('show', null, null, ['event' => $event]);
    }

    /**
     * @param Mail $mailDto
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function sendMailAction(Mail $mailDto)
    {
        /** @var MailMessage $mail */
        $mail = GeneralUtility::makeInstance(MailMessage::class);
        $mail->from(new \Symfony\Component\Mime\Address($mailDto->getSender()->getEmail(), $mailDto->getSender()->getScreenName()));
        $mail->to(...$mailDto->getReceivers());
        $mail->subject($mailDto->getSubject());
        $mail->text($mailDto->getMessage());
        if ($mail->send()) {
            $this->addFlashMessage(
                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('mailConfirmation', 'grevman'),
                '', FlashMessage::INFO);
        } else {
            $this->addFlashMessage(
                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('mailError', 'grevman'),
                '', FlashMessage::ERROR);
        }

        $this->redirect('show', null, null, ['event' => $mailDto->getEvent()]);
    }

    /**
     * action showMatrix
     *
     * @return string|object|null|void
     */
    public function showMatrixAction()
    {
    }
}
