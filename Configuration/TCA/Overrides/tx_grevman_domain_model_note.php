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
            'label_alt' => 'event, text',
            'label_alt_force' => true,
            'iconfile' => 'EXT:grevman/Resources/Public/Icons/grevman-note.svg',
            'default_sortby' => 'tstamp DESC',
        ],
        'types' => [
            '1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, text, event, member'],
        ],
        'columns' => [
            'text' => [
                'config' => [
                    'readOnly' => true,
                ],
            ],
            'event' => [
                'exclude' => true,
                'label' => 'LLL:EXT:grevman/Resources/Private/Language/locallang_db.xlf:tx_grevman_domain_model_event',
                'config' => [
                    'type' => 'select',
                    'renderType' => 'selectSingle',
                    'foreign_table' => 'tx_grevman_domain_model_event',
                    'readOnly' => true,
                ],
            ],
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
        ],
    ];

    $GLOBALS['TCA']['tx_grevman_domain_model_note'] =
        array_replace_recursive($GLOBALS['TCA']['tx_grevman_domain_model_note'], $config);
})();
