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
use Buepro\Grevman\Domain\Model\Registration;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Used to get the registration or a registration property for a member and an event.
 * In case a registration property is requested and the property doesn't exist null is returned.
 * In case no registration exists for the event and user and the state is requested 0 is returned.
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
        $this->registerArgument('property', 'string', 'Property to be retrieved from registration.', false);
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
        $result = null;
        if ($event instanceof Event && $member instanceof Member) {
            /** @var Registration $result */
            $result = $event->getRegistrationForMember($member);
        }
        if ($arguments['property']) {
            if ($result instanceof Registration && method_exists($result, 'get' . ucfirst($arguments['property']))) {
                $method = 'get' . ucfirst($arguments['property']);
                $result = $result->{$method}();
            } else {
                $result = null;
            }
            if (!$result && $arguments['property'] === 'state') {
                $result = 0;
            }
        }
        if ($arguments['as']) {
            $renderingContext->getVariableProvider()->add($arguments['as'], $result);
        } else {
            return $result;
        }
    }
}
