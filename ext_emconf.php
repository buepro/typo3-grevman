<?php

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

$EM_CONF[$_EXTKEY] = [
    'title' => 'Group event manager',
    'description' => 'Typically used to manage group events in sport clubs. Members can easily register and unregister for events and trainers might notify participants by email. Provides a matrix-, list- and detail view from events.',
    'category' => 'plugin',
    'author' => 'Roman Büchler',
    'author_email' => 'rb@buechler.pro',
    'state' => 'beta',
    'clearCacheOnLoad' => 0,
    'version' => '0.1.1-dev',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.0-10.4.99',
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
