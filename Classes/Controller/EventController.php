<?php

declare(strict_types=1);

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Grevman\Controller;

use Buepro\Grevman\Domain\Dto\Mail;
use Buepro\Grevman\Domain\Dto\Note;
use Buepro\Grevman\Domain\Model\Event;
use Buepro\Grevman\Domain\Model\Member;
use Buepro\Grevman\Domain\Model\Registration;
use Buepro\Grevman\Domain\Repository\EventRepository;
use Buepro\Grevman\Domain\Repository\MemberRepository;
use Buepro\Grevman\Utility\DtoUtility;
use Buepro\Grevman\Utility\EventUtility;
use Buepro\Grevman\Utility\TableUtility;
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
     * @return Event[]
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    private function getListEvents(): array
    {
        $displayDays = (int)($this->settings['event']['list']['displayDays'] ?? 720);
        $startDate = new \DateTime(sprintf(
            'midnight - %d day',
            (int)($this->settings['event']['list']['startDatePastDays'] ?? 0)
        ));
        /** @var Event[] $regularEvents */
        $regularEvents = $this->eventRepository->findAll($displayDays, $startDate)->toArray();
        /** @var Event[] $recurrenceParentEvents */
        $recurrenceParentEvents = $this->eventRepository->findByEnableRecurrence(1)->toArray();
        $recurrenceEvents = EventUtility::getEventRecurrences(
            $recurrenceParentEvents,
            $displayDays,
            $this->settings['general']['timeZone'] ? new \DateTimeZone($this->settings['general']['timeZone']) : null
        );
        return EventUtility::mergeAndOrderEvents($regularEvents, $recurrenceEvents);
    }

    /**
     * action list
     *
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function listAction(): void
    {
        $this->view->assignMultiple([
            'events' => $this->getListEvents(),
            'identifiedMember' => $this->memberRepository->getIdentified(),
        ]);
    }

    /**
     * The table to be shown comprises of an event and a member axis where the member axis is grouped by the
     * member group.
     */
    public function tableAction(): void
    {
        $events = $this->getListEvents();
        $this->view->assignMultiple([
            'events' => $events,
            'identifiedMember' => $this->memberRepository->getIdentified(),
            'memberAxis' => TableUtility::getMemberAxis($events),
        ]);
    }

    /**
     * action detail
     *
     * @param Event $event
     */
    public function detailAction(Event $event): void
    {
        $this->view->assignMultiple([
            'event' => $event,
            'eventGroups' => DtoUtility::getEventGroups($event),
            'identifiedMember' =>  $this->memberRepository->getIdentified(),
        ]);
    }

    public function registerAction(
        Event $event,
        \Buepro\Grevman\Domain\Model\Member $member,
        string $targetAction = 'detail'
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
            \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('registerConfirmation', 'grevman') ?? 'Translation missing at 1639137804',
            '',
            FlashMessage::INFO
        );

        $this->eventRepository->update($event);
        $this->persistenceManager->persistAll();
        $this->redirect($targetAction, null, null, ['event' => $event]);
    }

    public function unregisterAction(
        Event $event,
        \Buepro\Grevman\Domain\Model\Member $member,
        string $targetAction = 'detail'
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
            \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('unregisterConfirmation', 'grevman') ?? 'Translation missing at 1639137847',
            '',
            FlashMessage::INFO
        );
        $this->eventRepository->update($event);
        $this->persistenceManager->persistAll();
        $this->redirect($targetAction, null, null, ['event' => $event]);
    }

    /**
     * @param Mail $mailDto
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function sendMailAction(Mail $mailDto): void
    {
        /** @var MailMessage $mail */
        $mail = GeneralUtility::makeInstance(MailMessage::class);
        $mail->from(new \Symfony\Component\Mime\Address($GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailFromAddress'], $mailDto->getSender()->getScreenName()));
        $mail->replyTo(new \Symfony\Component\Mime\Address($mailDto->getSender()->getEmail(), $mailDto->getSender()->getScreenName()));
        $mail->to(...$mailDto->getReceivers());
        $mail->subject($mailDto->getSubject());
        $mail->text($mailDto->getMessage());
        if ($mail->send()) {
            $this->addFlashMessage(
                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('mailConfirmation', 'grevman') ?? 'Translation missing at 1639137855',
                '',
                FlashMessage::INFO
            );
        } else {
            $this->addFlashMessage(
                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('mailError', 'grevman') ?? 'Translation missing at 1639137860',
                '',
                FlashMessage::ERROR
            );
        }

        if ((bool)$mailDto->getEvent()->getUid()) {
            $this->redirect('detail', null, null, ['event' => $mailDto->getEvent()]);
        }
        $this->redirect('detail', null, null, ['eventId' => $mailDto->getEvent()->getId()]);
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
            \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('noteAdded', 'grevman') ?? 'Translation missing at 1639137866',
            '',
            FlashMessage::INFO
        );
        $this->redirect('detail', null, null, ['event' => $noteDto->getEvent()]);
    }
}
