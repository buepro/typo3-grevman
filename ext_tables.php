<?php
defined('TYPO3_MODE') || die();

call_user_func(static function() {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_grevman_domain_model_event', 'EXT:grevman/Resources/Private/Language/locallang_csh_tx_grevman_domain_model_event.xlf');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_grevman_domain_model_event');

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_grevman_domain_model_group', 'EXT:grevman/Resources/Private/Language/locallang_csh_tx_grevman_domain_model_group.xlf');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_grevman_domain_model_group');

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_grevman_domain_model_registration', 'EXT:grevman/Resources/Private/Language/locallang_csh_tx_grevman_domain_model_registration.xlf');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_grevman_domain_model_registration');

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_grevman_domain_model_note', 'EXT:grevman/Resources/Private/Language/locallang_csh_tx_grevman_domain_model_note.xlf');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_grevman_domain_model_note');
});
