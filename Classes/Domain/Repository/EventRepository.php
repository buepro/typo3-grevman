<?php

declare(strict_types=1);

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Grevman\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
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

    public function findAll(int $displayDays = 0, \DateTime $startDate = null, ?ObjectStorage $groups = null): QueryResultInterface
    {
        $startDate = $startDate ?? new \DateTime('midnight');
        $query = $this->createQuery();
        $constraints = [];
        $constraints[] = $query->greaterThanOrEqual('startdate', $startDate->getTimestamp());
        if ($displayDays > 0) {
            $maxStartDate = new \DateTime(sprintf('midnight + %d day', $displayDays));
            $constraints[] = $query->lessThanOrEqual('startdate', $maxStartDate->getTimestamp());
        }
        return $this->findAllForConstraints($query, $constraints, $groups);
    }

    public function findAllRecurrences(?ObjectStorage $groups = null): QueryResultInterface
    {
        $query = $this->createQuery();
        $constraints = [];
        $constraints[] = $query->equals('enableRecurrence', 1);
        return $this->findAllForConstraints($query, $constraints, $groups);
    }

    protected function findAllForConstraints(QueryInterface $query, array $constraints, ?ObjectStorage $groups): QueryResultInterface
    {
        if ($groups !== null && $groups->count() === 0) {
            // Return no events
            // @phpstan-ignore-next-line
            return $this->findByPid(-1);
        }
        if ($groups !== null) {
            $groupConstraints = [];
            foreach ($groups as $group) {
                $groupConstraints[] = $query->contains('memberGroups', $group);
            }
            $constraints[] = $query->logicalOr($groupConstraints);
        }
        $query->matching(
            $query->logicalAnd($constraints)
        );
        return $query->execute();
    }
}
