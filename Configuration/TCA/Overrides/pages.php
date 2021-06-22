<?php
defined('TYPO3') || die('Access denied.');

(static function() {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
        'grevman',
        'Configuration/TsConfig/Page/Storage.tsconfig',
        'Grevman: General storage'
    );
})();
