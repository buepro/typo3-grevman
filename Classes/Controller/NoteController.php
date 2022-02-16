<?php

declare(strict_types=1);

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Grevman\Controller;

use Buepro\Grevman\Domain\DTO\Note as NoteDTO;
use Buepro\Grevman\Domain\Model\Event;
use Buepro\Grevman\Domain\Model\Note;
use Buepro\Grevman\Domain\Repository\EventRepository;
use Buepro\Grevman\Domain\Repository\NoteRepository;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class NoteController extends ActionController
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
     * @var NoteRepository
     */
    protected $noteRepository = null;

    public function injectPersistenceManager(PersistenceManager $persistenceManager): void
    {
        $this->persistenceManager = $persistenceManager;
    }

    public function injectEventRepository(EventRepository $eventRepository): void
    {
        $this->eventRepository = $eventRepository;
    }

    public function injectNoteRepository(NoteRepository $noteRepository): void
    {
        $this->noteRepository = $noteRepository;
    }

    public function addAction(NoteDTO $noteDTO): void
    {
        $noteDTO->getEvent()->addNote($noteDTO->createNote());
        $this->eventRepository->update($noteDTO->getEvent());
        $this->persistenceManager->persistAll();
        $this->addFlashMessage(
            LocalizationUtility::translate('noteAdded', 'grevman'),
            '',
            FlashMessage::INFO
        );
        $this->redirect('detail', 'Event', null, ['event' => $noteDTO->getEvent()]);
    }

    public function deleteAction(Note $note, Event $event): void
    {
        $event->removeNote($note);
        $this->noteRepository->remove($note);
        $this->addFlashMessage(
            LocalizationUtility::translate('noteDeleted', 'grevman'),
            '',
            FlashMessage::INFO
        );
        $this->redirect('detail', 'Event', null, ['event' => $event]);
    }

    public function editAction(Note $note, Event $event): void
    {
        $this->view->assignMultiple([
            'note' => $note,
            'event' => $event,
        ]);
    }

    public function updateAction(Note $note, Event $event): void
    {
        $this->noteRepository->update($note);
        $this->addFlashMessage(
            LocalizationUtility::translate('noteUpdated', 'grevman'),
            '',
            FlashMessage::INFO
        );
        $this->redirect('detail', 'Event', null, ['event' => $event]);
    }
}
