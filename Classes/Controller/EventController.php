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
use Buepro\Grevman\Domain\Model\Member;
use Buepro\Grevman\Domain\Model\Registration;
use Buepro\Grevman\Domain\Repository\EventRepository;
use Buepro\Grevman\Domain\Repository\MemberRepository;
use Buepro\Grevman\Utility\DtoUtility;
use Buepro\Grevman\Utility\EventUtility;
use Buepro\Grevman\Utility\MatrixUtility;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter;

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
     * @var PersistenceManager
     */
    protected $persistenceManager;

    /**
     * eventRepository
     *
     * @var EventRepository
     */
    protected $eventRepository = null;

    /**
     * memberRepository
     *
     * @var MemberRepository
     */
    protected $memberRepository = null;

    public function injectPersistenceManager(PersistenceManager $persistenceManager): void
    {
        $this->persistenceManager = $persistenceManager;
    }

    /**
     * @param EventRepository $eventRepository
     */
    public function injectEventRepository(EventRepository $eventRepository): void
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * @param MemberRepository $memberRepository
     */
    public function injectMemberRepository(MemberRepository $memberRepository): void
    {
        $this->memberRepository = $memberRepository;
    }

    /**
     * In case the request arguments contain the field `eventId` it will be used to define an event with the property
     * mapper.
     *
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
     */
    public function initializeAction(): void
    {
        // Handle not persisted recurrence events
        if (
            $this->request->hasArgument('eventId') &&
            is_string($eventId = $this->request->getArgument('eventId')) &&
            ($parentUid = EventUtility::getParentUidFromId($eventId)) > 0
        ) {
            $arguments = $this->request->getArguments();
            unset($arguments['eventId']);
            /** @var Event $parentEvent */
            $parentEvent = $this->eventRepository->findByUid($parentUid);
            $child = Event::createChild($parentEvent, EventUtility::getStartdateFromId($eventId));
            if ($child !== null) {
                if (in_array($arguments['action'], ['register', 'unregister', 'addNote'], true)) {
                    // The action requires the event to be persisted
                    $this->eventRepository->add($child);
                    $this->persistenceManager->persistAll();
                    $actionArgumentMap = [
                        'register' => ['event' => $child->getUid()],
                        'unregister' => ['event' => $child->getUid()],
                        'addNote' => ['noteDto' => ['event' => $child->getUid()]],
                        'sendMail' => ['mailDto' => ['event' => $child->getUid()]],
                    ];
                    $arguments = array_replace_recursive($arguments, $actionArgumentMap[$arguments['action']]);
                    $this->request->setArguments($arguments);
                } else {
                    // Provide the non persisted event
                    if ($arguments['action'] === 'sendMail') {
                        $arguments['mailDto']['event'] = EventUtility::getPropertyMappingArray($child);
                        $this->request->setArguments($arguments);
                        $configuration = $this->arguments['mailDto']->getPropertyMappingConfiguration();
                        $configuration->forProperty('event')
                            ->allowAllProperties()
                            ->setTypeConverterOption(
                                PersistentObjectConverter::class,
                                (string) PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED,
                                true
                            );
                    } else {
                        $arguments['event'] = EventUtility::getPropertyMappingArray($child);
                        $this->request->setArguments($arguments);
                        $configuration = $this->arguments['event']->getPropertyMappingConfiguration();
                    }
                    $configuration
                        ->allowAllProperties()
                        ->setTypeConverterOption(
                            PersistentObjectConverter::class,
                            (string) PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED,
                            true
                        );
                }
            }
        }
        parent::initializeAction();
    }

    /**
     * Gets the events and identified member and assignes them to the view.
     *
     * @return Event[]
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    private function listAndMatrixAction(): array
    {
        $displayDays = (int)$this->settings['event']['list']['displayDays'];
        $displayDays = $displayDays > 0 ? $displayDays : 720;
        /** @var Event[] $regularEvents */
        $regularEvents = $this->eventRepository->findAll($displayDays)->toArray();
        /** @var Event[] $recurrenceParentEvents */
        $recurrenceParentEvents = $this->eventRepository->findByEnableRecurrence(1)->toArray();
        /** @var Event[] $recurrenceEvents */
        $recurrenceEvents = EventUtility::getEventRecurrences(
            $recurrenceParentEvents,
            $displayDays,
            $this->settings['general']['timeZone'] ? new \DateTimeZone($this->settings['general']['timeZone']) : null
        );
        /** @var Event[] $events */
        $events = EventUtility::mergeAndOrderEvents($regularEvents, $recurrenceEvents);
        $identifiedMember = null;
        if ($GLOBALS['TSFE']->fe_user && $GLOBALS['TSFE']->fe_user->user['uid']) {
            $identifiedMember = $this->memberRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        }
        $this->view->assignMultiple([
            'events' => $events,
            'identifiedMember' => $identifiedMember,
        ]);
        return $regularEvents;
    }

    /**
     * action list
     *
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function listAction(): void
    {
        $this->listAndMatrixAction();
    }

    /**
     * The matrix to be shown comprises of an event and a member axis where the member axis is grouped by the
     * member group.
     */
    public function showMatrixAction(): void
    {
        $events = $this->listAndMatrixAction();
        $this->view->assignMultiple([
            'memberAxis' => MatrixUtility::getMemberAxis($events),
        ]);
    }

    /**
     * action show
     *
     * @param Event $event
     */
    public function showAction(Event $event): void
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
        Event $event,
        \Buepro\Grevman\Domain\Model\Member $member
    ): void {
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

        $this->eventRepository->update($event);
        $this->persistenceManager->persistAll();
        $this->redirect('show', null, null, ['event' => $event]);
    }

    public function unregisterAction(
        Event $event,
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
        $this->eventRepository->update($event);
        $this->persistenceManager->persistAll();
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

        if ((bool)$mailDto->getEvent()->getUid()) {
            $this->redirect('show', null, null, ['event' => $mailDto->getEvent()]);
        }
        $this->redirect('show', null, null, ['eventId' => $mailDto->getEvent()->getId()]);
    }

    /**
     * @param Note $noteDto
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function addNoteAction(Note $noteDto): void
    {
        $noteDto->getEvent()->addNote($noteDto->createNote());
        $this->eventRepository->update($noteDto->getEvent());
        $this->persistenceManager->persistAll();
        $this->addFlashMessage(
            \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('noteAdded', 'grevman') ?? 'Translation missing at 1634056465800',
            '',
            FlashMessage::INFO
        );
        $this->redirect('show', null, null, ['event' => $noteDto->getEvent()]);
    }
}
