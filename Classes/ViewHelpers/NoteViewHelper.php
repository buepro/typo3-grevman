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
 * In case notes can not be found '' is returned.
 *
 * Usage:
 * {gem:note(event: event, member: member, as: 'notes', glue: '<br />')}
 *
 */
class NoteViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    public const DEFAULT_GLUE = '<br />';

    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('event', '\\Buepro\\Grevman\\Domain\\Model\\Event', 'Event', true);
        $this->registerArgument('member', '\\Buepro\\Grevman\\Domain\\Model\\Member', 'Member', true);
        $this->registerArgument('glue', 'string', 'Glue used to implode notes. If empty array is returned.', false, self::DEFAULT_GLUE);
        $this->registerArgument('as', 'string', 'Name of variable to create.', false);
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

        if (!($event instanceof Event) || !($member instanceof Member)) {
            if ($arguments['as']) {
                $renderingContext->getVariableProvider()->add($arguments['as'], '');
            }
            return '';
        }

        $result = [];
        $notes = $event->getNotes();
        foreach ($notes as $note) {
            /** @var Note $note */
            if ($note->getMember() !== null && $note->getMember()->getUid() === $member->getUid()) {
                $result[] = $note->getText();
            }
        }
        $result = implode($arguments['glue'], $result);
        if ($arguments['as']) {
            $renderingContext->getVariableProvider()->add($arguments['as'], $result);
            return '';
        }
        return $result;
    }
}
