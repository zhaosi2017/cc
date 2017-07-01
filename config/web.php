<?php

$params = require(__DIR__ . '/params.php');
$config = [
    'id' => 'basic',
    'language'=>'zh-CN',
    'timeZone' => 'Asia/Phnom_Penh',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'i18n' => [
            //'class' => yii\i18n\I18N::className(), 默认的就不需要修改--------------------
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'zh-CN',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/model/telegram' => 'telegram.php',
                    ],
                    'on missingTranslation' => ['app\modules\home\controllers\TranslationEventHandler', 'handleMissingTranslation']
                ],
                '*' => [
                    'class' => 'yii\i18n\GettextMessageSource'
                ]
            ],
        ],
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
            'loginUrl' => ['/home/login/index'],
            'identityCookie' => ['name' => '_identity-home', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'home',
        ],
        'errorHandler' => [
            'errorAction' => '/home/default/deny',
        ],
        'ip2region' => [
          'class' => '\xiaogouxo\ip2region\Geolocation',
          'mode' => 'SEARCH_BTREE',
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
        'redis' => require(__DIR__.'/redis.php'),

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
        // 'admin' => [
        //     'class' => 'app\modules\admin\Module'
        // ],

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
