<?php

declare(strict_types=1);

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Grevman\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
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
 * The repository for Events
 */
class EventRepository extends Repository
{
    protected $defaultOrderings = [
        'startdate' => QueryInterface::ORDER_ASCENDING
    ];

    /**
     * Returns all objects of this repository.
     *
     * @return QueryResultInterface|array
     * @throws InvalidQueryException
     */
    public function findAll(int $displayDays = 0)
    {
        $query = $this->createQuery();
        $constraints = [];
        $constraints[] = $query->greaterThanOrEqual('startdate', time());
        if ($displayDays > 0) {
            $maxStartdate = time() + $displayDays * 86400;
            $constraints[] = $query->lessThanOrEqual('startdate', $maxStartdate);
        }
        $query->matching(
            $query->logicalAnd($constraints)
        );
        return $query->execute();
    }
}
