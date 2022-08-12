<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

/*
 * Verificar parametros del proyecto
 */
if( !isset($db['dsn']) || !isset($db['username']) || !isset($db['password']) ) {
    die('Es necesario configurar algunos parametros. Por favor contacte al administrador');
}

$db['charset'] = 'utf8';
$db['class'] = 'yii\db\Connection';
$db['enableSchemaCache'] = true;
$db['schemaCacheDuration'] = 60;
$db['schemaCache'] = 'cache';

$config = [
    'id' => 'siaop-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
    ],
    'params' => $params,
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
