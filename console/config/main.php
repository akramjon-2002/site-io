<?php
return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'console\controllers',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            // 'cache' => 'cache', // Uncomment if cache component is configured
        ],
        // It's good practice to also configure 'db' component for console applications
        // if your migrations or console commands need database access.
        // Assuming common/config/main-local.php holds the actual db config.
        'db' => require(__DIR__ . '/../../common/config/main-local.php')['components']['db'] ?? [],
    ],
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];
