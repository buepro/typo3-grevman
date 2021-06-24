<?php

declare(strict_types=1);

namespace Buepro\Grevman\Controller;


use Buepro\Grevman\Utility\DtoUtility;

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
     * @param \Buepro\Grevman\Domain\Repository\EventRepository $eventRepository
     */
    public function injectEventRepository(\Buepro\Grevman\Domain\Repository\EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
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

        $this->view->assignMultiple([
            'event' => $event,
            'eventGroups' => DtoUtility::getEventGroups($event),
        ]);
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
