<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');

use yii\helpers\ArrayHelper;
use app\models\User;

$sfParamLocal = __DIR__ . DIRECTORY_SEPARATOR . 'params-local.php';

$params = ArrayHelper::merge(
    require(__DIR__ . '/params.php'),
    file_exists($sfParamLocal) ? require($sfParamLocal) : []
);

$sDb = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'data.db';

$configComm = [
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'charset' => 'utf8',

            'dsn' => 'sqlite:' . $sDb,
            'username' => 'mailtempluser',
            'password' => 'mailtemplpassword',
            'tablePrefix' => 'mtpl_',
            'enableSchemaCache' => true,
            'schemaCacheDuration' => 24 * 3600,
        ],

//        'authManager' => [
//            'class' => 'yii\rbac\PhpManager',
//            'defaultRoles' => [ 'admin', ],
//        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'cache' => false,
            'rules' => [
                '<_a:(register|login)>' => 'site/<_a>',
                '<_c:[\w\-]+>/<_a:[\w\-]+>/<id:\d+>' => '<_c>/<_a>',
                '<_c:[\w\-]+>/<id:\d+>' => '<_c>/view',
                '<_c:[\w\-]+>/<_a:[\w\-]+>' => '<_c>/<_a>',
                '<_c:[\w\-]+>' => '<_c>/index',
            ],
        ],

        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@app/views/mail',
            'htmlLayout' => false,
            'useFileTransport' => true,
        ],

//        'cache' => [
//            'class' => 'yii\caching\DummyCache',
//        ],

        'log' => [
            'class' => 'yii\log\Dispatcher',
        ],
    ],
    'params' => $params,
];

return $configComm;
