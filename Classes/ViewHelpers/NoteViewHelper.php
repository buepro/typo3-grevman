<?php

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Grevman\ViewHelpers;

use Buepro\Grevman\Domain\Model\Event;
use Buepro\Grevman\Domain\Model\Member;
use Buepro\Grevman\Domain\Model\Note;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Used to get the notes a member added to an event.
 *
 * Usage:
 * {gem:note(event: event, member: member, as: 'notes', glue: '<br />')}
 *
 */
class NoteViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('as', 'string', 'Name of variable to create.', false);
        $this->registerArgument('event', '\\Buepro\\Grevman\\Domain\\Model\\Event', 'Event', false);
        $this->registerArgument('member', '\\Buepro\\Grevman\\Domain\\Model\\Member', 'Member', false);
        $this->registerArgument('glue', 'string', 'Glue used to implode notes. If empty array is returned.', false);
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return mixed
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        $event = $arguments['event'];
        $member = $arguments['member'];
        $result = [];
        if ($event instanceof Event && $member instanceof Member) {
            $notes = $event->getNotes();
            foreach ($notes as $note) {
                /** @var Note $note */
                if ($note->getMember()->getUid() === $member->getUid()) {
                    $result[] = $note->getText();
                }
            }
        }
        if ($arguments['glue']) {
            $result = implode($arguments['glue'], $result);
        }
        if ($arguments['as']) {
            $renderingContext->getVariableProvider()->add($arguments['as'], $result);
        } else {
            return $result;
        }
    }
}
