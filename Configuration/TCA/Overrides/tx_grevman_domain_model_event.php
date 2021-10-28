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
            'default_sortby' => 'enable_recurrence DESC, tstamp DESC',
        ],
        'palettes' => [
            'dates' => [
                'showitem' => 'startdate, enddate',
            ],
            'recurrence' => [
                'showitem' => 'recurrence_enddate, --linebreak--, recurrence_rule, --linebreak--, recurrence_dates, '
                    . '--linebreak--, recurrence_exception_dates, --linebreak--, recurrence_set',
            ],
        ],
        'types' => [
            '1' => [
                'showitem' =>
                    '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                        title, slug, --palette--;;dates, teaser, description, program, link, location, price,
                    --div--;LLL:EXT:grevman/Resources/Private/Language/locallang.xlf:resources,
                        images, files,
                    --div--;LLL:EXT:grevman/Resources/Private/Language/locallang.xlf:relations,
                        member_groups, registrations, notes, guests, parent,
                    --div--;LLL:EXT:grevman/Resources/Private/Language/locallang.xlf:recurrence, enable_recurrence,
                       --palette--;;recurrence,
                    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                        sys_language_uid, l10n_parent, l10n_diffsource, hidden'
            ],
        ],
        'columns' => [
            'startdate' => [
                'config' => [
                    'size' => 13,
                    'eval' => 'datetime,int',
                    'default' => time(),
                ],
            ],
            'enddate' => [
                'config' => [
                    'size' => 13,
                    'eval' => 'datetime,int',
                    'default' => 0,
                ],
            ],
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
            'enable_recurrence' => [
                'exclude' => true,
                'label' => 'LLL:EXT:grevman/Resources/Private/Language/locallang_db.xlf:tx_grevman_domain_model_event.enable_recurrence',
                'onChange' => 'reload',
                'config' => [
                    'type' => 'check',
                    'renderType' => 'checkboxToggle',
                    'items' => [
                        [
                            0 => '',
                            1 => '',
                        ]
                    ],
                ],
            ],
            'recurrence_enddate' => [
                'exclude' => true,
                'label' => 'LLL:EXT:grevman/Resources/Private/Language/locallang_db.xlf:tx_grevman_domain_model_event.recurrence_enddate',
                'displayCond' => 'FIELD:enable_recurrence:REQ:true',
                'config' => [
                    'type' => 'input',
                    'renderType' => 'inputDateTime',
                    'eval' => 'datetime,int',
                    'default' => 0,
                ],
            ],
            'recurrence_rule' => [
                'exclude' => true,
                'label' => 'LLL:EXT:grevman/Resources/Private/Language/locallang_db.xlf:tx_grevman_domain_model_event.recurrence_rule',
                'description' => 'LLL:EXT:grevman/Resources/Private/Language/locallang_db.xlf:tx_grevman_domain_model_event.recurrence_rule.description',
                'displayCond' => 'FIELD:enable_recurrence:REQ:true',
                'config' => [
                    'type' => 'input',
                    'size' => 100,
                    'eval' => 'trim',
                    'default' => '',
                    'valuePicker' => [
                        'items' => [
                            [
                                'LLL:EXT:grevman/Resources/Private/Language/locallang_db.xlf:tx_grevman_domain_model_event.recurrence_rule.repeat_daily',
                                'FREQ=DAILY',
                            ],
                            [
                                'LLL:EXT:grevman/Resources/Private/Language/locallang_db.xlf:tx_grevman_domain_model_event.recurrence_rule.repeat_workdays',
                                'FREQ=WEEKLY;BYDAY=MO,TU,WE,TH,FR;INTERVAL=1',
                            ],
                            [
                                'LLL:EXT:grevman/Resources/Private/Language/locallang_db.xlf:tx_grevman_domain_model_event.recurrence_rule.repeat_weekly',
                                'FREQ=WEEKLY',
                            ],
                            [
                                'LLL:EXT:grevman/Resources/Private/Language/locallang_db.xlf:tx_grevman_domain_model_event.recurrence_rule.repeat_monthly',
                                'FREQ=MONTHLY',
                            ],
                            [
                                'LLL:EXT:grevman/Resources/Private/Language/locallang_db.xlf:tx_grevman_domain_model_event.recurrence_rule.repeat_yearly',
                                'FREQ=YEARLY',
                            ],
                        ],
                    ],
                ],
            ],
            'recurrence_dates' => [
                'exclude' => true,
                'label' => 'LLL:EXT:grevman/Resources/Private/Language/locallang_db.xlf:tx_grevman_domain_model_event.recurrence_dates',
                'description' => 'LLL:EXT:grevman/Resources/Private/Language/locallang_db.xlf:tx_grevman_domain_model_event.recurrence_dates.description',
                'displayCond' => 'FIELD:enable_recurrence:REQ:true',
                'config' => [
                    'type' => 'text',
                    'cols' => 15,
                    'rows' => 5,
                    'eval' => 'trim',
                ],
            ],
            'recurrence_exception_dates' => [
                'exclude' => true,
                'label' => 'LLL:EXT:grevman/Resources/Private/Language/locallang_db.xlf:tx_grevman_domain_model_event.recurrence_exception_dates',
                'description' => 'LLL:EXT:grevman/Resources/Private/Language/locallang_db.xlf:tx_grevman_domain_model_event.recurrence_exception_dates.description',
                'displayCond' => 'FIELD:enable_recurrence:REQ:true',
                'config' => [
                    'type' => 'text',
                    'cols' => 15,
                    'rows' => 5,
                    'eval' => 'trim',
                ],
            ],
            'recurrence_set' => [
                'exclude' => true,
                'label' => 'LLL:EXT:grevman/Resources/Private/Language/locallang_db.xlf:tx_grevman_domain_model_event.recurrence_set',
                'description' => 'LLL:EXT:grevman/Resources/Private/Language/locallang_db.xlf:tx_grevman_domain_model_event.recurrence_set.description',
                'displayCond' => 'FIELD:enable_recurrence:REQ:true',
                'config' => [
                    'type' => 'text',
                    'cols' => 40,
                    'rows' => 5,
                    'eval' => 'trim',
                ],
            ],
        ],
    ];

    $GLOBALS['TCA']['tx_grevman_domain_model_event'] =
        array_replace_recursive($GLOBALS['TCA']['tx_grevman_domain_model_event'], $config);
})();
