<?php

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') or die('Access denied.');

(static function (): void {
    $config = [
        'ctrl' => [
            'label_alt' => 'first_name, last_name',
            'label_alt_force' => true,
        ],
        'columns' => [
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
                    'disableMovingChildrenWithParent' => true,
                ],

            ],
            'notes' => [
                'config' => [
                    'appearance' => [
                        'collapseAll' => 1,
                        'expandSingle' => 1,
                    ],
                    'disableMovingChildrenWithParent' => true,
                ],
            ],
        ],
    ];

    $GLOBALS['TCA']['fe_users'] =
        array_replace_recursive($GLOBALS['TCA']['fe_users'], $config);
})();
