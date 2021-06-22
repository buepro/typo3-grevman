<?php
defined('TYPO3') || die('Access denied.');

(static function() {
    $columns = [
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
    ];

    $ctrl = [
        'label' => 'member',
    ];

    $GLOBALS['TCA']['tx_grevman_domain_model_registration']['columns'] =
        array_replace_recursive($GLOBALS['TCA']['tx_grevman_domain_model_registration']['columns'], $columns);
    $GLOBALS['TCA']['tx_grevman_domain_model_registration']['ctrl'] =
        array_replace_recursive($GLOBALS['TCA']['tx_grevman_domain_model_registration']['ctrl'], $ctrl);
})();
