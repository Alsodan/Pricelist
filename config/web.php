<?php

$config = [
    'id' => 'pricekzp-main',
    'language' => 'ru-RU',
    'defaultRoute' => 'main/default/index',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
            // Enable JSON Input:
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                if ($response->data !== null && !empty(Yii::$app->request->get('suppress_response_code'))) {
                    $response->data = [
                        'success' => $response->isSuccessful,
                        'data' => $response->data,
                    ];
                    $response->statusCode = 200;
                }
            },
            'formatters' => [
                \yii\web\Response::FORMAT_JSON => [
                    'class' => 'yii\web\JsonResponseFormatter',
                    'prettyPrint' => YII_DEBUG, // используем "pretty" в режиме отладки
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                ],
            ],
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
        'gridview' => [
            'class' => '\kartik\grid\Module',
            'i18n' => [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@kvgrid/messages',
                'forceTranslation' => false
            ]
        ],
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
                'organization' => [
                    'class' => 'app\modules\organization\Module',
                    'layout' => '@app/views/layouts/admin',
                    'controllerNamespace' => 'app\modules\organization\controllers\backend',
                    'viewPath' => '@app/modules/organization/views/backend',
                ],
                'product' => [
                    'class' => 'app\modules\product\Module',
                    'layout' => '@app/views/layouts/admin',
                    'controllerNamespace' => 'app\modules\product\controllers\backend',
                    'viewPath' => '@app/modules/product/views/backend',
                ],
                'region' => [
                    'class' => 'app\modules\region\Module',
                    'layout' => '@app/views/layouts/admin',
                    'controllerNamespace' => 'app\modules\region\controllers\backend',
                    'viewPath' => '@app/modules/region/views/backend',
                ],
                'site' => [
                    'class' => 'app\modules\site\Module',
                    'layout' => '@app/views/layouts/product',
                    'controllerNamespace' => 'app\modules\site\controllers\backend',
                    'viewPath' => '@app/modules/site/views/backend',
                ],
            ]
        ],
        'site' => [
            'class' => 'app\modules\site\Module',
            'layout' => '@app/views/layouts/site',
            'controllerNamespace' => 'app\modules\site\controllers\frontend',
            'viewPath' => '@app/modules/site/views/frontend',
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
        'product' => [
            'class' => 'app\modules\product\Module',
            'layout' => '@app/views/layouts/product',
            'controllerNamespace' => 'app\modules\product\controllers\frontend',
            'viewPath' => '@app/modules/product/views/frontend',
        ],
        'v1' => [
            'class' => 'app\api\modules\v1\Module'
        ]
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
