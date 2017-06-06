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
                'main' => [
                    'class' => 'app\modules\main\Module',
                    'layout' => '@app/views/layouts/admin',
                    'controllerNamespace' => 'app\modules\main\controllers\backend',
                    'viewPath' => '@app/modules/main/views/backend',
                ],
                'group' => [
                    'class' => 'app\modules\group\Module',
                    'layout' => '@app/views/layouts/admin',
                    'controllerNamespace' => 'app\modules\group\controllers\backend',
                    'viewPath' => '@app/modules/group/views/backend',
                ],
                'warehouse' => [
                    'class' => 'app\modules\warehouse\Module',
                    'layout' => '@app/views/layouts/admin',
                    'controllerNamespace' => 'app\modules\warehouse\controllers\backend',
                    'viewPath' => '@app/modules/warehouse/views/backend',
                ],
                'crop' => [
                    'class' => 'app\modules\crop\Module',
                    'layout' => '@app/views/layouts/admin',
                    'controllerNamespace' => 'app\modules\crop\controllers\backend',
                    'viewPath' => '@app/modules/crop/views/backend',
                ],
            ]
        ],
        'main' => [
            'class' => 'app\modules\main\Module',
            'controllerNamespace' => 'app\modules\main\controllers\frontend',
            'viewPath' => '@app/modules/main/views/frontend',
        ],
        'user' => [
            'class' => 'app\modules\user\Module',
            'controllerNamespace' => 'app\modules\user\controllers\frontend',
            'viewPath' => '@app/modules/user/views/frontend',
        ],
        'group' => [
            'class' => 'app\modules\group\Module',
            'controllerNamespace' => 'app\modules\group\controllers\frontend',
            'viewPath' => '@app/modules/group/views/frontend',
        ],
        'warehouse' => [
            'class' => 'app\modules\warehouse\Module',
            'controllerNamespace' => 'app\modules\warehouse\controllers\frontend',
            'viewPath' => '@app/modules/warehouse/views/frontend',
        ],
        'crop' => [
            'class' => 'app\modules\crop\Module',
            'controllerNamespace' => 'app\modules\crop\controllers\frontend',
            'viewPath' => '@app/modules/crop/views/frontend',
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
