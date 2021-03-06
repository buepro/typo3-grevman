<?php

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

$EM_CONF[$_EXTKEY] = [
    'title' => 'Group event manager',
    'description' => 'Typically used to manage group events in sport clubs. Members can easily register and unregister for events and trainers might notify participants by email. Provides a table-, list- and detail view from events.',
    'category' => 'plugin',
    'author' => 'Roman Büchler',
    'author_email' => 'rb@buechler.pro',
    'state' => 'stable',
    'clearCacheOnLoad' => 0,
    'version' => '2.0.1-dev',
    'constraints' => [
        'depends' => [
            'php'   => '>=7.3.0',
            'typo3' => '10.4.0-11.5.99',
            'auxlibs' => '1.4.0-1.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' => [
        'psr-4' => [
            'Buepro\\Grevman\\' => 'Classes'
        ],
    ],
];
