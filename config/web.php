<?php

$config = [
    'id' => 'pricekzp-main',
    'language' => 'ru-RU',
    'defaultRoute' => 'main/default/index',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
        'user' => [
            'identityClass' => 'app\modules\user\models\common\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['user/default/login']
        ],
        'errorHandler' => [
            'errorAction' => 'main/default/error',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
        ]
    ],
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
            'modules' => [
                'user' => [
                    'class' => 'app\modules\user\Module',
                    'layout' => '@app/views/layouts/admin',
                    'controllerNamespace' => 'app\modules\user\controllers\backend',
                    'viewPath' => '@app/modules/user/views/backend',
                ],
            ]
        ],
        'main' => [
            'class' => 'app\modules\main\Module',
        ],
        'user' => [
            'class' => 'app\modules\user\Module',
            'controllerNamespace' => 'app\modules\user\controllers\frontend',
            'viewPath' => '@app/modules/user/views/frontend',
        ],
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
