<?php

$config = require __DIR__ . '/../config/web.php';

// El modo puede ser production, develop, test
define('YII_DEBUG', $params['mode'] == 'develop');
define('YII_ENABLE_ERROR_HANDLER', true);
define('YII_ENV', $params['mode'] == 'production' ?'prod' : 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

(new yii\web\Application($config))->run();
