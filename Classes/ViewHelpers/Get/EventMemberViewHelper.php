<?php

declare(strict_types=1);

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Grevman\ViewHelpers\Get;

use Buepro\Grevman\Domain\DTO\EventMember;
use Buepro\Grevman\Domain\Model\Event;
use Buepro\Grevman\Domain\Model\Member;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Usage: {gem:get.eventMember(event: event, member: member, as: 'identifiedEventMember')}
 */
class EventMemberViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('event', '\\Buepro\\Grevman\\Domain\\Model\\Event', 'Event of interest', true);
        $this->registerArgument('member', '\\Buepro\\Grevman\\Domain\\Model\\Member', 'Member of interest', true);
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
        $identifiedEventMember = null;
        if ($arguments['event'] instanceof Event && $arguments['member'] instanceof Member) {
            $identifiedEventMember = new EventMember($arguments['event'], $arguments['member']);
        }
        $renderingContext->getVariableProvider()->add($arguments['as'], $identifiedEventMember);
        return '';
    }
}
