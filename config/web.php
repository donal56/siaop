<?php

use yii\helpers\StringHelper;
use webvimark\modules\UserManagement\models\UserVisitLog;

date_default_timezone_set('America/Mexico_City');

/**
 * Crear estos tres archivos para una instalacion limpia
 */
$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$mailer = require __DIR__ . '/mailer.php';

/*
 * Verificar parametros del proyecto
 */
if( !isset($db['dsn']) || !isset($db['username']) || !isset($db['password']) ) {
    die('Es necesario configurar algunos parametros. Por favor contacte al administrador');
}

if( !isset($params['mode']) || !isset($params['adminEmail']) || !isset($params['siteName']) ) {
    die('Es necesario configurar algunos parametros. Por favor contacte al administrador');
}

$db['charset'] = 'utf8';
$db['class'] = 'yii\db\Connection';
$db['enableSchemaCache'] = true;
$db['schemaCacheDuration'] = 60;
$db['schemaCache'] = 'cache';

$config = [
    'id' => 'siaop',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'es-MX',
    'timezone' => 'America/Mexico_City',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    //'catchAll' => ['site/mantenimiento'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'IY_IQTaQjporcR30YBOhkyxkWDN1AZUG',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                $response->headers->add('X-Frame-Options', 'SAMEORIGIN');

                if ($response->data !== null && $response->format == 'json' && StringHelper::startsWith(Yii::$app->request->url, '/api/')) {

                    if(isset($response->data['code']) && isset($response->data['message'])) {
                        
                        if(Yii::$app->params['mode'] !== 'develop' || !isset($response->data['stack-trace'])) {
                            $result = [
                                'internal' => [
                                    $response->data['message']
                                ]
                            ];
                        }
                        else {
                            $result = [
                                'internal' => [
                                    $response->data['message'] . ' @ ' . $response->data['file'] . ':' . $response->data['line']
                                ],
                                'internalTrace' => $response->data['stack-trace']
                            ];
                        }
                    }
                    else {
                        $result = $response->data;
                    }

                    $response->data = [
                        'success' => $response->isSuccessful,
                        'result' => $result,
                        'statusCode' => $response->statusCode
                    ];
                }
            },
        ],
        'cache' => [
            'class' => 'yii\caching\ArrayCache',
            'serializer' => false,
        ],
        'fileCache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'class' => 'webvimark\modules\UserManagement\components\UserConfig',

            'on afterLogin' => function($event) {
                UserVisitLog::newVisitor($event->identity->id);
            }
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            //  'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => ($params['mode'] != 'production'),
            'transport' => $mailer
        ],
        'log' => [
            // Niveles de profundidad de la pila de rastreo
            'traceLevel' => $params['mode'] != 'production' ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_GET', '_POST'],      // '_FILES', '_COOKIE', '_SESSION', '_SERVER,
                    'except' => ['yii\web\NotFoundHttpException']
                ], [
                    'class' => 'yii\log\EmailTarget',
                    'levels' => ['error'],
                    'enabled' => $params['mode'] == 'production' && isset($params['devMail']),
                    'message' => [
                        'from' => [$mailer['username']],
                        'to' => [$params['devMail']],
                        'subject' => 'Automatic Error Handler Message @ ' . $params['siteName'],
                    ],
                    'except' => [
                        'yii\web\NotFoundHttpException',
                        'yii\web\ForbiddenHttpException',
                        'yii\web\UnauthorizedHttpException',
                        //'yii\db\IntegrityException'
                        'yii\web\HttpException:401'
                    ]
                ]
            ]
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                'api/version/app' => 'api/version-app',
                'api/version/api' => 'api/version-api',
                'api/token/registrar' => 'api/token-registrar',
                'api/notificaciones/enviar' => 'api/notificaciones-enviar',
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'itemTable' => 'permisos',
            'itemChildTable' => 'permisos_hijos',  
            'assignmentTable' => 'asignaciones_permisos',
            'ruleTable' => 'reglas',
        ],     
    ],
    'modules' => [
		'user-management' => [
			'class' => 'webvimark\modules\UserManagement\UserManagementModule',
			'auth_assignment_table' => 'asignaciones_permisos',
            'auth_item_table' => 'permisos',
            'auth_item_child_table' => 'permisos_hijos',  
            'auth_rule_table' => 'reglas',
            'user_table' => 'usuarios',
            'auth_item_group_table' => 'grupos_permisos',          
            'user_visit_log_table' => 'visitas',
			'passwordRegexp' => '/^\S*(?=\S{8,32})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/',
			'on beforeAction'=>function(yii\base\ActionEvent $event) {
				if ($event->action->uniqueId == 'user-management/auth/login') {
					$event->action->controller->layout = 'loginLayout.php';
				};
			},
		],
	],
    'params' => $params,
];

$config['bootstrap'][] = 'debug';
$config['modules']['debug'] = [
    'class' => 'yii\debug\Module',
    'traceLine' => '<a href="vscode://{file}:{line}:0">{file}:{line}</a>',
    'checkAccessCallback' => fn() => Yii::$app->user->isSuperAdmin || Yii::$app->request->getUserIP() == '127.0.0.1',
    'allowedIPs' => ['*'],
];

//configuration adjustments for 'dev' environment
if ($params['mode'] == 'develop') {
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
        'generators' => [
            'mvc' => [ 
                'class' => 'app\generators\mvc\Generator',
                'templates' => [
                    'mvc' => '@app/generators/mvc/default'
                ]
            ],
            'modelsMassive' => [ 
                'class' => 'app\generators\modelsMassive\Generator',
            ]
        ],
    ];
}

return $config;
