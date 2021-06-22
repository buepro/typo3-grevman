<?php
defined('TYPO3') || die('Access denied.');

(static function() {
    $columns = [
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
    ];

    $GLOBALS['TCA']['tx_grevman_domain_model_group']['columns'] =
        array_replace_recursive($GLOBALS['TCA']['tx_grevman_domain_model_group']['columns'], $columns);
})();
