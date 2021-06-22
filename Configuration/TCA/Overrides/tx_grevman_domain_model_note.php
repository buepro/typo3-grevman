<?php
defined('TYPO3') || die('Access denied.');

(static function() {
    $columns = [
        'member' => [
            'exclude' => true,
            'label' => 'LLL:EXT:grevman/Resources/Private/Language/locallang_db.xlf:tx_grevman_domain_model_note.member',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'fe_users',
                'readOnly' => true,
            ],
        ],
    ];

    $GLOBALS['TCA']['tx_grevman_domain_model_note']['columns'] =
        array_replace_recursive($GLOBALS['TCA']['tx_grevman_domain_model_note']['columns'], $columns);
})();
