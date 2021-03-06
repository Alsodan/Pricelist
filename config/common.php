<?php

use yii\helpers\ArrayHelper;
 
$params = ArrayHelper::merge(
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);
 
return [
    'name' => 'ПРАЙС ООО «КРАСНОДАРЗЕРНОПРОДУКТ-ЭКСПО»',
    'basePath' => dirname(__DIR__),
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'bootstrap' => [
        'log',
        'app\modules\site\Bootstrap',
        'app\modules\admin\Bootstrap',
        'app\modules\main\Bootstrap',
        'app\modules\user\Bootstrap',
        'app\modules\group\Bootstrap',
        'app\modules\warehouse\Bootstrap',
        'app\modules\crop\Bootstrap',
        'app\modules\organization\Bootstrap',
        'app\modules\product\Bootstrap',
        'app\modules\region\Bootstrap',
    ],
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'charset' => 'utf8',
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'pluralize' => false,
                    'prefix' => 'api',
                    'controller' => [
                        'v1/pricelist'
                    ],
                    'patterns' => [
                        'GET,HEAD warehouses' => 'warehouses',
                        'GET,HEAD regions' => 'regions',
                        'GET,HEAD crops' => 'crops',
                        'GET,HEAD prices' => 'prices',
                        'GET,HEAD products' => 'products',
                        //'GET,HEAD warehouses/<id:\d+>' => 'warehouses',
                        //'PUT,PATCH {id}' => 'update',
                        //'DELETE {id}' => 'delete',
                        //'GET,HEAD {id}' => 'view',
                        //'GET,HEAD all' => 'prices',
                        //'POST' => 'create',
                        //'GET,HEAD' => 'index',
                        //'{id}' => 'options',
                        //'' => 'index',
                    ],
                ],
                [
                    'class' => 'yii\web\GroupUrlRule',
                    'prefix' => 'admin',
                    'routePrefix' => 'admin',
                    'rules' => [
                        '' => 'default/index',
                        '<_m:[\w\-]+>' => '<_m>/default/index',
                        '<_m:[\w\-]+>/<id:\d+>' => '<_m>/default/view',

                        '<_m:[\w\-]+>/<id:\d+>/<_a:[\w-]+>' => '<_m>/default/<_a>',
                        '<_m:[\w\-]+>/<_c:[\w\-]+>/<id:\d+>' => '<_m>/<_c>/view',
                        '<_m:[\w\-]+>/<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => '<_m>/<_c>/<_a>',
                        '<_m:[\w\-]+>/<_c:[\w\-]+>' => '<_m>/<_c>/index',
                    ],
                ],

                '' => 'site/default/pricelist',
                'warehouses' => 'site/default/warehouses',
                'warehouse/<id:\d+>' => 'site/default/warehouse/',
                'supplier' => 'site/default/supplier',
                'products' => 'site/default/products',
                'product/<id:\d+>' => 'site/default/product/',
                'contacts' => 'site/default/contacts',
                
                'administrator' => 'main/default/index',
                //'contact' => 'main/contact/index',
                '<_a:error>' => 'main/default/<_a>',

                '<_a:(login|logout|signup|email-confirm|password-reset-request|password-reset)>' => 'user/default/<_a>',

                '<_m:[\w\-]+>' => '<_m>/default/index',
                '<_m:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>/<wh:\d+>' => '<_m>/default/<_a>',
                '<_m:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => '<_m>/default/<_a>',
                '<_m:[\w\-]+>/<_c:[\w\-]+>' => '<_m>/<_c>/index',
                '<_m:[\w\-]+>/<_c:[\w\-]+>/<_a:[\w-]+>' => '<_m>/<_c>/<_a>',
                '<_m:[\w\-]+>/<_c:[\w\-]+>/<id:\d+>' => '<_m>/<_c>/view',
                '<_m:[\w\-]+>/<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => '<_m>/<_c>/<_a>',
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
        ],
        'cache' => [
            'class' => 'yii\caching\DummyCache',
        ],
        'log' => [
            'class' => 'yii\log\Dispatcher',
        ],
        'i18n' => [
            'translations' => [
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'forceTranslation' => true,
                ],
            ],
        ],
        'authManager' => [
            'class' => 'app\components\auth\AuthManager'
        ],
    ],
    'params' => $params,
];