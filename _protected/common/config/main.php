<?php

return [
    'name' => 'Wx Template',
    //'language' => 'sr',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => ['common\components\Aliases'],

    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],

    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'session' => [
            'class' => 'yii\web\DbSession',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/translations',
                    'sourceLanguage' => 'en',
                ],
                'yii' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/translations',
                    'sourceLanguage' => 'en'
                ],
            ],
        ],

        'formatter' => [
            'class' => \common\components\Formatter::class,
            'dateFormat' => 'php:M j, Y',
            'datetimeFormat' => 'php:M j, Y, g:i:s a',
            'timeZone' => 'UTC',
        ],

        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\DbTarget::class,
                    'levels' => ['error', 'warning', 'info'],
                ],
            ],
        ],

        'logArchivator' => [
            'class' => \common\components\LogArchivatorComponent::class,
        ],
    ], // components

    'modules' => [
        'datecontrol' => [
            'class' => \kartik\datecontrol\Module::class,

            // format settings for displaying each date attribute (ICU format example)
            'displaySettings' => [
                \kartik\datecontrol\Module::FORMAT_DATE => 'php:Y-m-d',
                \kartik\datecontrol\Module::FORMAT_TIME => 'php:h:i:s A',
                \kartik\datecontrol\Module::FORMAT_DATETIME => 'php:Y-m-d h:i:s A',
            ],

            // format settings for saving each date attribute (PHP format example)
            'saveSettings' => [
                \kartik\datecontrol\Module::FORMAT_DATE => 'php:U', // saves as unix timestamp
                \kartik\datecontrol\Module::FORMAT_TIME => 'php:U',
                \kartik\datecontrol\Module::FORMAT_DATETIME => 'php:U',
            ],

            // set your display timezone
            'displayTimezone' => 'UTC',

            // set your timezone for date saved to db
            'saveTimezone' => 'UTC',

            // automatically use kartik\widgets for each of the above formats
            'autoWidget' => true,

            // default settings for each widget from kartik\widgets used when autoWidget is true
            'autoWidgetSettings' => [
                \kartik\datecontrol\Module::FORMAT_DATE => ['type' => 2, 'pluginOptions' => ['autoclose' => true]], // example
                \kartik\datecontrol\Module::FORMAT_DATETIME => [
                    'type' => \kartik\datetime\DateTimePicker::TYPE_INPUT,
                    'pluginOptions' => [
                        'showMeridian' => true,
                        'format' => 'php:Y-m-d h:i:s A',
                        'minuteStep' => 1,
                    ],
                ],
                \kartik\datecontrol\Module::FORMAT_TIME => [], // setup if needed
            ],

            // custom widget settings that will be used to render the date input instead of kartik\widgets,
            // this will be used when autoWidget is set to false at module or widget level.
            'widgetSettings' => [
                \kartik\datecontrol\Module::FORMAT_DATE => [
                    'class' => 'yii\jui\DatePicker', // example
                    'options' => [
                        'dateFormat' => 'php:d-M-Y',
                        'options' => ['class' => 'form-control'],
                    ],
                ],
            ],
            // other settings
        ],
    ], // modules
];
