<?php

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Grevman\ViewHelpers\Link;

use Buepro\Grevman\Domain\Model\Event;

class EventActionViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\Link\ActionViewHelper
{
    public function initialize()
    {
        parent::initialize();
        if (
            isset($this->arguments['arguments']['event']) &&
            ($event = $this->arguments['arguments']['event']) instanceof Event &&
            !(bool)$event->getUid()
        ) {
            unset($this->arguments['arguments']['event']);
            $this->arguments['arguments']['eventId'] = $event->getId();
        }
    }
}
