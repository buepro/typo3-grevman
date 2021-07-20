<?php

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') || die('Access denied.');

(static function () {
    $config = [
        'ctrl' => [
            'iconfile' => 'EXT:grevman/Resources/Public/Icons/grevman-guest.svg',
        ],
    ];

    $GLOBALS['TCA']['tx_grevman_domain_model_guest'] =
        array_replace_recursive($GLOBALS['TCA']['tx_grevman_domain_model_guest'], $config);
})();
