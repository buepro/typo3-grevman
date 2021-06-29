<?php

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') || die('Access denied.');

(static function () {
    $config = [
        'palettes' => [
            'dates' => [
                'showitem' => 'startdate, enddate',
            ],
        ],
        'types' => [
            '1' => [
                'showitem' =>
                    '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                        title, slug, --palette--;;dates, teaser, description, program, link, location, price,
                    --div--;Resources,
                        images, files,
                    --div--;Relations,
                        member_groups, registrations, notes, guests,
                    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                        sys_language_uid, l10n_parent, l10n_diffsource, hidden'
            ],
        ],
        'columns' => [
            'slug' => [
                'config' => [
                    'type' => 'slug',
                    'size' => 50,
                    'generatorOptions' => [
                        'fields' => ['title'],
                        'replacements' => [
                            '/' => '-'
                        ],
                    ],
                    'fallbackCharacter' => '-',
                    'default' => ''
                ],
            ],
            'link' => [
                'config' => [
                    'renderType' => 'inputLink',
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
        ],
    ];

//    $columns = [
//        'events' => [
//            'config' => [
//                'MM' => 'tx_grevman_event_group_mm',
//                'MM_opposite_field' => 'member_groups',
//            ],
//        ],
//        'members' => [
//            'config' => [
//                'foreign_table_where' => 'AND {#fe_users}.{#pid} IN (###PAGE_TSCONFIG_IDLIST###)',
//            ]
//        ],
//    ];
//
    $GLOBALS['TCA']['tx_grevman_domain_model_event'] =
        array_replace_recursive($GLOBALS['TCA']['tx_grevman_domain_model_event'], $config);
})();
