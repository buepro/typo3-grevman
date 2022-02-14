<?php

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Grevman\ViewHelpers\Event;

use Buepro\Grevman\Domain\Model\Event;
use Buepro\Grevman\Domain\Model\Group;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * View helper to get users joining the event (having a confirmed registration).
 * Assigns an array to a variable in the form: [confirmed => 3, total => 5]
 *
 * Usage:
 *
 *    - For event: `{gem:event.registrations(data: 'confirmedRegistrations', event: event, as: '_participation')}`
 *    - For group: `{gem:event.registrations(data: 'confirmedRegistrations', event: event, group: group, as: '_participation')}`
 *
 */
class ParticipationViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('event', '\\Buepro\\Grevman\\Domain\\Model\\Event', 'The registrations event', true);
        $this->registerArgument('group', '\\Buepro\\Grevman\\Domain\\Model\\Group', 'Get for this event group', false);
        $this->registerArgument('as', 'string', 'Name of variable to create', true);
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): string {
        if (($event = $arguments['event'] ?? false) === false ||
            !($event instanceof Event)
        ) {
            return '';
        }
        $total = count($event->getInvolvedMembers());
        $confirmed = count($event->getConfirmedRegistrations());
        if (($group = $arguments['group'] ?? false) !== false &&
            $group instanceof Group
        ) {
            $total = count($group->getMembers());
            $confirmed = count($event->getConfirmedRegistrations($group));
        }
        $renderingContext->getVariableProvider()->add($arguments['as'], ['confirmed' => $confirmed, 'total' => $total]);
        return '';
    }
}
