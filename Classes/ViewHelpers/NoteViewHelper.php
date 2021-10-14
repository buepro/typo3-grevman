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
        $this->registerArgument('glue', 'string', 'Glue used to implode notes. If empty array is returned.', false, '<br />');
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @throws \Exception
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): string {
        $event = $arguments['event'];
        $member = $arguments['member'];

        if (!($event instanceof Event)) {
            throw new \Exception('Argument \'event\' must be an instance of ' . Event::class, 1634201039234);
        }
        if (!($member instanceof Member)) {
            throw new \Exception('Argument \'member\' must be an instance of ' . Member::class, 1634201168034);
        }

        $result = [];
        $notes = $event->getNotes();
        foreach ($notes as $note) {
            /** @var Note $note */
            if ($note->getMember()->getUid() === $member->getUid()) {
                $result[] = $note->getText();
            }
        }
        if ($arguments['as']) {
            $renderingContext->getVariableProvider()->add($arguments['as'], $result);
            return '';
        }
        return implode($arguments['glue'], $result);
    }
}
