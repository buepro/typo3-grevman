<?php

declare(strict_types=1);

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Grevman\Controller;

use Buepro\Grevman\Domain\Dto\EventMember;
use Buepro\Grevman\Domain\Dto\Mail;
use Buepro\Grevman\Domain\Dto\Note;
use Buepro\Grevman\Domain\Model\Event;
use Buepro\Grevman\Domain\Model\Group;
use Buepro\Grevman\Domain\Model\Guest;
use Buepro\Grevman\Domain\Model\Member;
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
    ): void {
        $this->eventRepository = $eventRepository;
    }

    /**
     * @param \Buepro\Grevman\Domain\Repository\MemberRepository $memberRepository
     */
    public function injectMemberRepository(
        \Buepro\Grevman\Domain\Repository\MemberRepository $memberRepository
    ): void {
        $this->memberRepository = $memberRepository;
    }

    /**
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    private function listAndMatrixActionProcessing()
    {
        $events = $this->eventRepository->findAll();
        $identifiedMember = null;
        if ($GLOBALS['TSFE']->fe_user && $GLOBALS['TSFE']->fe_user->user['uid']) {
            $identifiedMember = $this->memberRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        }
        $this->view->assignMultiple([
            'events' => $events,
            'identifiedMember' => $identifiedMember,
        ]);
        return $events;
    }

    /**
     * action list
     *
     * @return string|object|null|void
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function listAction()
    {
        $this->listAndMatrixActionProcessing();
    }

    /**
     * The matrix to be shown comprises of an event and a member axis where the member axis is grouped by the
     * member group.
     *
     * @return string|object|null|void
     */
    public function showMatrixAction()
    {
        $events = $this->listAndMatrixActionProcessing();

        // Compile member axis
        $groups = [];
        $spontaneousMembers = [];
        $guests = [];
        /** @var Event $event */
        foreach ($events as $event) {
            // Compile groups
            foreach ($event->getMemberGroups() as $group) {
                /** @var Group $group */
                if (!isset($groups[$group->getUid()])) {
                    $groups[$group->getUid()] = $group;
                }
            }
            // Compile spontaneous registrations
            foreach ($event->getSpontaneousRegistrations() as $spontaneousRegistration) {
                /** @var Registration $spontaneousRegistration */
                /** @var Member $member */
                $member = $spontaneousRegistration->getMember();
                if (!isset($spontaneousMembers[$member->getUid()])) {
                    $spontaneousMembers[$member->getUid()] = $member;
                }
            }
            // Compile guests
            foreach ($event->getGuests() as $guest) {
                /** @var Guest $guest */
                if (!isset($guests[$guest->getUid()])) {
                    $guests[$guest->getUid()] = $guest;
                }
            }
        }
        $memberAxis = [
            'groups' => $groups,
            'spontaneousMembers' => $spontaneousMembers,
            'guests' => $guests,
        ];

        $this->view->assignMultiple([
            'memberAxis' => $memberAxis,
        ]);
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
            /** @var Member $member */
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
    ): void {
        $persistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);
        $registration = $event->getRegistrationForMember($member);
        if (null === $registration) {
            /** @var Registration $registration */
            $registration = GeneralUtility::makeInstance(Registration::class);
            $registration->setMember($member);
            $event->addRegistration($registration);
        }
        $registration->setState(Registration::REGISTRATION_CONFIRMED);
        $this->addFlashMessage(
            \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('registerConfirmation', 'grevman') ?? 'Translation missing at 1634056035253',
            '',
            FlashMessage::INFO
        );

        $persistenceManager->add($event);
        $persistenceManager->persistAll();
        $this->redirect('show', null, null, ['event' => $event]);
    }

    public function unregisterAction(
        \Buepro\Grevman\Domain\Model\Event $event,
        \Buepro\Grevman\Domain\Model\Member $member
    ):void {
        $registration = $event->getRegistrationForMember($member);
        if (null === $registration) {
            /** @var Registration $registration */
            $registration = GeneralUtility::makeInstance(Registration::class);
            $registration->setMember($member);
            $event->addRegistration($registration);
        }
        $registration->setState(Registration::REGISTRATION_CANCELED);
        $this->addFlashMessage(
            \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('unregisterConfirmation', 'grevman') ?? 'Translation missing at 1634056367543',
            '',
            FlashMessage::INFO
        );
        $persistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);
        $persistenceManager->add($registration);
        $persistenceManager->persistAll();
        $this->redirect('show', null, null, ['event' => $event]);
    }

    /**
     * @param Mail $mailDto
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function sendMailAction(Mail $mailDto): void
    {
        /** @var MailMessage $mail */
        $mail = GeneralUtility::makeInstance(MailMessage::class);
        $mail->from(new \Symfony\Component\Mime\Address($mailDto->getSender()->getEmail(), $mailDto->getSender()->getScreenName()));
        $mail->to(...$mailDto->getReceivers());
        $mail->subject($mailDto->getSubject());
        $mail->text($mailDto->getMessage());
        if ($mail->send()) {
            $this->addFlashMessage(
                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('mailConfirmation', 'grevman') ?? 'Translation missing at 1634056431706',
                '',
                FlashMessage::INFO
            );
        } else {
            $this->addFlashMessage(
                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('mailError', 'grevman') ?? 'Translation missing at 1634056449875',
                '',
                FlashMessage::ERROR
            );
        }

        $this->redirect('show', null, null, ['event' => $mailDto->getEvent()]);
    }

    /**
     * @param Note $noteDto
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function addNoteAction(Note $noteDto): void
    {
        $noteDto->getEvent()->addNote($noteDto->createNote());
        $persistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);
        $persistenceManager->add($noteDto->getEvent());
        $persistenceManager->persistAll();
        $this->addFlashMessage(
            \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('noteAdded', 'grevman') ?? 'Translation missing at 1634056465800',
            '',
            FlashMessage::INFO
        );
        $this->redirect('show', null, null, ['event' => $noteDto->getEvent()]);
    }
}
