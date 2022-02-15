<?php

declare(strict_types=1);

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Grevman\Domain\Repository;

use Buepro\Grevman\Domain\Model\Member;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * This file is part of the "Group event manager" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2021 Roman Büchler <rb@buechler.pro>, buechler.pro gmbh
 */

/**
 * The repository for Registrations
 */
class MemberRepository extends Repository
{
    public function getIdentified(): ?Member
    {
        $identifiedMember = null;
        $context = GeneralUtility::makeInstance(Context::class);
        if (($uid = $context->getPropertyFromAspect('frontend.user', 'id')) !== 0) {
            /** @var ?Member $identifiedMember */
            $identifiedMember = $this->findByUid($uid);
        }
        return $identifiedMember;
    }
}
