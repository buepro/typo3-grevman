<?php
defined('TYPO3') || die('Access denied.');

call_user_func(static function() {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'Grevman',
        'Events',
        [
            \Buepro\Grevman\Controller\EventController::class => 'list, show, register, unregister, sendMail, showMatrix',
            \Buepro\Grevman\Controller\RegistrationController::class => 'new, create, edit, update'
        ],
        // non-cacheable actions
        [
            \Buepro\Grevman\Controller\EventController::class => 'list, show, register, unregister, sendMail, showMatrix',
            \Buepro\Grevman\Controller\RegistrationController::class => 'new, create, edit, update'
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
    $iconRegistry->registerIcon(
        'grevman-plugin-events',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:grevman/Resources/Public/Icons/user_plugin_events.svg']
    );
});
