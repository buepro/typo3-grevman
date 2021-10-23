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
            'iconfile' => 'EXT:grevman/Resources/Public/Icons/grevman-event.svg',
        ],
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
                        member_groups, registrations, notes, guests, parent,
                    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                        sys_language_uid, l10n_parent, l10n_diffsource, hidden'
            ],
        ],
        'columns' => [
            'parent' => [
                'exclude' => true,
                'label' => 'LLL:EXT:grevman/Resources/Private/Language/locallang_db.xlf:tx_grevman_domain_model_event.parent',
                'config' => [
                    'type' => 'select',
                    'renderType' => 'selectSingle',
                    'foreign_table' => 'tx_grevman_domain_model_event',
                    'readOnly' => 1,
                ],
            ],
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

    $GLOBALS['TCA']['tx_grevman_domain_model_event'] =
        array_replace_recursive($GLOBALS['TCA']['tx_grevman_domain_model_event'], $config);
})();
