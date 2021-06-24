<?php
defined('TYPO3') || die('Access denied.');

(static function() {
    $config = [
        'ctrl' => [
            'label' => 'member',
            'label_alt' => 'event',
            'label_alt_force' => true,
        ],
        'types' => [
            '1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, status, event, member'],
        ],
        'columns' =>[
            'status' => [
                'config' => [
                    'type' => 'select',
                    'renderType' => 'selectSingle',
                    'size' => 1,
                    'default' => 0,
                    'items' => [
                        [
                            'LLL:EXT:grevman/Resources/Private/Language/locallang_db.xlf:tx_grevman_domain_model_registration.status_undefined',
                            \Buepro\Grevman\Domain\Model\Registration::REGISTRATION_UNDEFINED
                        ],
                        [
                            'LLL:EXT:grevman/Resources/Private/Language/locallang_db.xlf:tx_grevman_domain_model_registration.status_confirmed',
                            \Buepro\Grevman\Domain\Model\Registration::REGISTRATION_CONFIRMED
                        ],
                        [
                            'LLL:EXT:grevman/Resources/Private/Language/locallang_db.xlf:tx_grevman_domain_model_registration.status_canceled',
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
