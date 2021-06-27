<?php

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Grevman\ViewHelpers;

use Buepro\Grevman\Domain\Model\Event;
use Buepro\Grevman\Domain\Model\Member;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Used to get the registration from an event.
 *
 * Usage:
 * {gem:registration(event: event, member: member, as: 'registration')}
 *
 */
class RegistrationViewHelper extends AbstractViewHelper
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
        $registration = null;
        if ($event instanceof Event && $member instanceof Member) {
            $registration = $event->getRegistrationForMember($member);
        }
        if ($arguments['as']) {
            $renderingContext->getVariableProvider()->add($arguments['as'], $registration);
        } else {
            return $registration;
        }
    }
}
