<?php

declare(strict_types=1);

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Grevman\Controller;

use Buepro\Grevman\Domain\DTO\Mail;
use Buepro\Grevman\Domain\Model\Event;
use Buepro\Grevman\Domain\Model\Registration;
use Buepro\Grevman\Domain\Repository\EventRepository;
use Buepro\Grevman\Domain\Repository\MemberRepository;
use Buepro\Grevman\Utility\DTOUtility;
use Buepro\Grevman\Utility\EventUtility;
use Buepro\Grevman\Utility\TableUtility;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class EventController extends ActionController
{
    /**
     * @var PersistenceManager
     */
    protected $persistenceManager;

    /**
     * @var EventRepository
     */
    protected $eventRepository = null;

    /**
     * @var MemberRepository
     */
    protected $memberRepository = null;

    public function injectPersistenceManager(PersistenceManager $persistenceManager): void
    {
        $this->persistenceManager = $persistenceManager;
    }

    public function injectEventRepository(EventRepository $eventRepository): void
    {
        $this->eventRepository = $eventRepository;
    }

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
                        'addNote' => ['noteDTO' => ['event' => $child->getUid()]],
                        'sendMail' => ['mailDTO' => ['event' => $child->getUid()]],
                    ];
                    $arguments = array_replace_recursive($arguments, $actionArgumentMap[$arguments['action']]);
                    $this->request->setArguments($arguments);
                } else {
                    // Provide the non persisted event
                    if ($arguments['action'] === 'sendMail') {
                        $arguments['mailDTO']['event'] = EventUtility::getPropertyMappingArray($child);
                        $this->request->setArguments($arguments);
                        $configuration = $this->arguments['mailDTO']->getPropertyMappingConfiguration();
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

    public function detailAction(Event $event): void
    {
        $this->view->assignMultiple([
            'event' => $event,
            'eventGroups' => DTOUtility::getEventGroups($event),
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
            LocalizationUtility::translate('registerConfirmation', 'grevman'),
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
            LocalizationUtility::translate('unregisterConfirmation', 'grevman'),
            '',
            FlashMessage::INFO
        );
        $this->eventRepository->update($event);
        $this->persistenceManager->persistAll();
        $this->redirect($targetAction, null, null, ['event' => $event]);
    }

    public function sendMailAction(Mail $mailDTO): void
    {
        try {
            /** @var MailMessage $mail */
            $mail = GeneralUtility::makeInstance(MailMessage::class);
            $mail->from(new \Symfony\Component\Mime\Address($GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailFromAddress'], $mailDTO->getSender()->getScreenName()));
            if (GeneralUtility::validEmail($mailDTO->getSender()->getEmail())) {
                $mail->replyTo(new \Symfony\Component\Mime\Address($mailDTO->getSender()->getEmail(), $mailDTO->getSender()->getScreenName()));
            }
            $mail->bcc(...$mailDTO->getReceivers());
            $mail->subject($mailDTO->getSubject());
            $mail->text($mailDTO->getMessage());
            if ($mail->send()) {
                $this->addFlashMessage(
                    LocalizationUtility::translate('mailConfirmation', 'grevman'),
                    '',
                    FlashMessage::INFO
                );
            } else {
                throw new \DomainException('Email could not be sent. Check the mail configuration.', 1645015923);
            }
        } catch (\Exception $e) {
            $this->addFlashMessage(
                LocalizationUtility::translate('mailError', 'grevman'),
                '',
                FlashMessage::ERROR
            );
        }
        if ((bool)$mailDTO->getEvent()->getUid()) {
            $this->redirect('detail', null, null, ['event' => $mailDTO->getEvent()]);
        }
        $this->redirect('detail', null, null, ['eventId' => $mailDTO->getEvent()->getId()]);
    }
}
