<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'language'=>'zh-CN',
    'timeZone' => 'Asia/Phnom_Penh',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'nHqhOUsoOzvBjm8w4MFLTrcKaET-TCpK',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\modules\home\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => '/home/login/index',
        ],
        'errorHandler' => [
            'errorAction' => '/home/default/deny',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'officeaction2017@gmail.com',
                'password' => 'Officeaction123',
                'port' => '25',
                'encryption' => 'tls',
            ],
            'messageConfig'=>[
                'charset'=>'UTF-8',
                'from'=>['officeaction2017@gmail.com'=>'验证码']
            ],
        ],

        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],

        'assetManager' => [
            'bundles' => [
                'yii\bootstrap\BootstrapAsset' => [
                    'sourcePath' => null, //通常都要重置为null，
                    'basePath' => '@webroot',
                    'baseUrl' => '@web',
                    'css' => [
                        'css/global/bootstrap.min.css?v=3.3.6', //改成你要用的web输出地址
                    ],
                    'js' => [
                        'js/global/bootstrap.min.js?v=3.3.6', //改成你要用的web输出地址
                    ]
                ],
            ],
        ],
    ],
    'params' => $params,
    'defaultRoute'=>'home',
    'modules' => [
        'home' => [
            'class' => 'app\modules\home\Module'
        ],
        'admin' => [
            'class' => 'app\modules\admin\Module'
        ],

        'redactor' => 'yii\redactor\RedactorModule',
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
