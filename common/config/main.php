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
            'dsn' => 'mysql:host=w1.php-x.com;dbname=ihengtech_adv',
            'username' => 'test',
            'password' => 'test_WSX3edc',
            'charset' => 'utf8mb4',
            'tablePrefix' => 'adv_',
        ],
    ],
];
