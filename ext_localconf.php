<?php

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') || die('Access denied.');

(static function () {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'Grevman',
        'Events',
        [
            \Buepro\Grevman\Controller\EventController::class => 'showMatrix, list, show, register, unregister, sendMail, addNote',
        ],
        // non-cacheable actions
        [
            \Buepro\Grevman\Controller\EventController::class => 'register, unregister, sendMail, addNote',
        ]
    );

    // wizards
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        'mod {
            wizards.newContentElement.wizardItems.plugins {
                elements {
                    events {
                        iconIdentifier = grevman-plugin-events
                        title = LLL:EXT:grevman/Resources/Private/Language/locallang_db.xlf:tx_grevman_events.name
                        description = LLL:EXT:grevman/Resources/Private/Language/locallang_db.xlf:tx_grevman_events.description
                        tt_content_defValues {
                            CType = list
                            list_type = grevman_events
                        }
                    }
                }
                show = *
            }
       }'
    );

    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    $icons = [
        'grevman-event',
        'grevman-group',
        'grevman-guest',
        'grevman-note',
        'grevman-plugin-events',
        'grevman-registration'
    ];
    foreach ($icons as $icon) {
        $iconRegistry->registerIcon(
            $icon,
            \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            ['source' => 'EXT:grevman/Resources/Public/Icons/' . $icon . 'svg']
        );
    }
}) ();
