<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=ihengtech_adv',
            'username' => 'adv',
            'password' => 'adv_WSX3edc',
            'charset' => 'utf8mb4',
            'tablePrefix' => 'adv_',
        ],
    ],
];
