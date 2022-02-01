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
            'label' => 'member',
            'label_alt' => 'event',
            'label_alt_force' => true,
            'iconfile' => 'EXT:grevman/Resources/Public/Icons/grevman-registration.svg',
            'typeicon_column' => 'state',
            'typeicon_classes' => [
                'default' => 'grevman-registration',
                '0' => 'grevman-registration-undefined',
                '6' => 'grevman-registration',
                '9' => 'grevman-registration-cancelled',
            ],
            'default_sortby' => 'tstamp DESC',
        ],
        'types' => [
            '1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, state, event, member'],
        ],
        'columns' =>[
            'state' => [
                'config' => [
                    'type' => 'select',
                    'renderType' => 'selectSingle',
                    'size' => 1,
                    'default' => 0,
                    'items' => [
                        [
                            'LLL:EXT:grevman/Resources/Private/Language/locallang_db.xlf:tx_grevman_domain_model_registration.state_undefined',
                            \Buepro\Grevman\Domain\Model\Registration::REGISTRATION_UNDEFINED
                        ],
                        [
                            'LLL:EXT:grevman/Resources/Private/Language/locallang_db.xlf:tx_grevman_domain_model_registration.state_confirmed',
                            \Buepro\Grevman\Domain\Model\Registration::REGISTRATION_CONFIRMED
                        ],
                        [
                            'LLL:EXT:grevman/Resources/Private/Language/locallang_db.xlf:tx_grevman_domain_model_registration.state_canceled',
                            \Buepro\Grevman\Domain\Model\Registration::REGISTRATION_CANCELED],
                    ],
                ]
            ],
            'event' => [
                'exclude' => true,
                'label' => 'LLL:EXT:grevman/Resources/Private/Language/locallang_db.xlf:tx_grevman_domain_model_event',
                'config' => [
                    'type' => 'select',
                    'renderType' => 'selectSingle',
                    'readOnly' => true,
                    'foreign_table' => 'tx_grevman_domain_model_event',
                    'default' => 0,
                    'minitems' => 0,
                    'maxitems' => 1,
                ],
            ],
            'member' => [
                'exclude' => true,
                'label' => 'LLL:EXT:grevman/Resources/Private/Language/locallang_db.xlf:tx_grevman_domain_model_registration.member',
                'config' => [
                    'type' => 'select',
                    'renderType' => 'selectMultipleSideBySide',
                    'foreign_table' => 'fe_users',
                    'foreign_table_where' => 'AND {#fe_users}.{#pid} IN (###PAGE_TSCONFIG_IDLIST###)',
                    'size' => 10,
                    'autoSizeMax' => 30,
                    'minitems' => 1,
                    'maxitems' => 1,
                    'multiple' => 0,
                    'fieldControl' => [
                        'editPopup' => [
                            'disabled' => false,
                        ],
                        'addRecord' => [
                            'disabled' => false,
                        ],
                        'listModule' => [
                            'disabled' => true,
                        ],
                    ],
                ],
            ],
        ],
    ];

    $GLOBALS['TCA']['tx_grevman_domain_model_registration'] =
        array_replace_recursive($GLOBALS['TCA']['tx_grevman_domain_model_registration'], $config);
})();
