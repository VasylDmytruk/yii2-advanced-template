<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-admin',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'logs' => [
            'class' => \backend\modules\logs\LogModule::class,
        ],
        'documentation' => [
            'class' => \autoxloo\yii2\rest_api_doc\Module::class,
            'as access' => [
                'class' => \yii\filters\AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [\common\models\User::ROLE_MEMBER],
                    ],
                ],
            ],
        ],
    ],
    'components' => [
        // you can set your theme here - template comes with: 'light' and 'dark'
        'view' => [
            'theme' => [
                'pathMap' => ['@app/views' => '@webroot/themes/light/views'],
                'baseUrl' => '@web/themes/light',
            ],
        ],
        'assetManager' => [
            'bundles' => [
                'yii\bootstrap\BootstrapAsset' => [
                    'basePath' => '@webroot',
                    'baseUrl' => '@themes',
                    'css' => ['css/bootstrap.min.css']
                ],
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\UserIdentity',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'rules' => [
                'doc/page' => 'doc/page',
                'doc/<view>' => 'doc/view',

                '<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>' => '<module>/<controller>/<action>',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',

                // need for documentation module
                // example
//                [
//                    'class' => \yii\rest\UrlRule::class,
//                    'controller' => 'sip',
//                    'pluralize' => false,
//                    'patterns' => [
//                        'POST auth/<id>' => 'auth',
//                        'POST set-token' => 'set-token',
//                        'POST delete-token' => 'delete-token',
//                        'POST hello' => 'hello',
//                    ],
//                ],
            ],
        ],
    ],
    'params' => $params,

    // need for documentation module
    'controllerMap' => [
//        'sip' => \api\controllers\SipController::class,   // example
    ],
];
