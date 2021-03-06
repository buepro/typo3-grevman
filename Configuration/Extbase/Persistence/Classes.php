<?php
declare(strict_types = 1);

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

return [
    \Buepro\Grevman\Domain\Model\Member::class => [
        'tableName' => 'fe_users',
    ],
    \Buepro\Grevman\Domain\Model\FrontendUserGroup::class => [
        'tableName' => 'fe_groups',
    ],
];
