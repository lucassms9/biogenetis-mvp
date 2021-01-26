<?php
$baseDir = dirname(dirname(__FILE__));
return [
    'plugins' => [
        'Bake' => $baseDir . '/vendor/cakephp/bake/',
        'CakeDC/OracleDriver' => $baseDir . '/vendor/cakedc/cakephp-oracle-driver/',
        'Cewi/Excel' => $baseDir . '/vendor/Cewi/Excel/',
        'DebugKit' => $baseDir . '/vendor/cakephp/debug_kit/',
        'Migrations' => $baseDir . '/vendor/cakephp/migrations/',
        'Rest' => $baseDir . '/vendor/sprintcube/cakephp-rest/',
        'WyriHaximus/TwigView' => $baseDir . '/vendor/wyrihaximus/twig-view/'
    ]
];