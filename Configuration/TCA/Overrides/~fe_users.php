<?php

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') || die('Access denied.');

(static function () {
    $columns = [
        'member_groups' => [
            'config' => [
                'MM' => 'tx_grevman_group_member_mm',
                'MM_opposite_field' => 'members',
            ],
        ],
        'registrations' => [
            'config' => [
                'appearance' => [
                    'collapseAll' => 1,
                    'expandSingle' => 1,
                ],
            ],

        ],
        'notes' => [
            'config' => [
                'appearance' => [
                    'collapseAll' => 1,
                    'expandSingle' => 1,
                ],
            ],
        ],
    ];

    $GLOBALS['TCA']['fe_users']['columns'] =
        array_replace_recursive($GLOBALS['TCA']['fe_users']['columns'], $columns);
})();
