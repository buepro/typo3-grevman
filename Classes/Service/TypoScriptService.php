<?php


namespace Buepro\Grevman\Service;


use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TypoScriptService implements SingletonInterface
{
    /**
     * @var array
     */
    protected $globalSetup;

    /**
     * @var array
     */
    protected $extensionSetup;

    public function __construct()
    {
        /** @var \TYPO3\CMS\Core\TypoScript\TypoScriptService $typoScriptService */
        $typoScriptService = GeneralUtility::makeInstance(\TYPO3\CMS\Core\TypoScript\TypoScriptService::class);
        $this->globalSetup = $typoScriptService->convertTypoScriptArrayToPlainArray($GLOBALS['TSFE']->tmpl->setup);
        $this->extensionSetup = $this->globalSetup['plugin']['tx_grevman_events'];
    }

    public function getLeaderGroups(): array
    {
        if (isset($this->extensionSetup['settings']['general']['leaderGroups'])) {
            return GeneralUtility::trimExplode(',', $this->extensionSetup['settings']['general']['leaderGroups'], true);
        }
        return [];
    }
}
