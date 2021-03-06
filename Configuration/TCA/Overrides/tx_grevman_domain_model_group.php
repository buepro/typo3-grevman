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
            'iconfile' => 'EXT:grevman/Resources/Public/Icons/grevman-group.svg',
        ],
        'columns' => [
            'events' => [
                'config' => [
                    'MM' => 'tx_grevman_event_group_mm',
                    'MM_opposite_field' => 'member_groups',
                ],
            ],
            'members' => [
                'config' => [
                    'foreign_table_where' => 'AND {#fe_users}.{#pid} IN (###PAGE_TSCONFIG_IDLIST###)',
                ]
            ],
        ]
    ];

    $GLOBALS['TCA']['tx_grevman_domain_model_group'] =
        array_replace_recursive($GLOBALS['TCA']['tx_grevman_domain_model_group'], $config);
})();
