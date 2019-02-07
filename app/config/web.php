<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'name'=>'BloodBank',
    'basePath' => dirname(__DIR__),
    'defaultRoute' => 'blood-requests', //default
    'homeUrl' => ['/site/home'],
    'timeZone' => 'Asia/Bangkok',
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],

    'modules' => [
        'gridview' => [
            'class' => '\kartik\grid\Module'
        ],
    ],
    'components' => [

        'assetManager' => [
            'bundles' => [
                'dosamigos\google\maps\MapAsset' => [
                    'options' => [
                        //'key' => 'AIzaSyAfbsZQhZDh-bZIAZbS1ZSHDsYzRBsX1FY',
                        'key' => 'AIzaSyBnxU6j3O0XB3dmPrnKkTGHgYKp2bELax4',
                        'language' => 'en',
                        'version' => '3.1.18',
                        //'libraries' => 'places',
                        //'sensor'=> 'false'
                    ]
                ]
            ]
        ],
        'response' => [
            'formatters' => [
                \yii\web\Response::FORMAT_JSON => [
                    'class' => 'yii\web\JsonResponseFormatter',
                    'prettyPrint' => YII_DEBUG, // use "pretty" output in debug mode
                ],
            ],
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'dateFormat' => 'php:Y-m-d',
            'datetimeFormat' => 'php:Y-m-d H:i:s',
            'timeFormat' => 'php:H:i:s',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'THB',
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views' => '@app/themes/adminlte'
                ],
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'HwGhGn-ZtOIOzcZtrTuj-onohWXNXSQZ',
            //'enableCsrfValidation' => false, // rest api security
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\Managers',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
        'db' => $db,

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'sign-in' => 'site/login',
                'blood-requests/<id:\d+>' => 'blood-requests/view',
                'branch' => 'branch/index',
                'branch/new' => 'branch/create',
                'branch/<id:\d+>' => 'branch/view',
                'branch-requests/<id:\d+>' => 'branch-requests/view',
                'branch-requests/update/<id:\d+>' => 'branch-requests/update',
                //'branch/view/<code:>' => 'branch/viewer',\

                'blood-requests-verification/<id:\d+>' => 'blood-requests-verification/view',
                'branch-requests-verification/<id:\d+>' => 'branch-requests-verification/view',

                'users/<id:\d+>' => 'users/view',

                'API/blood-requests/fetchAll' => 'blood-requests/get_all_active',
                'API/blood-requests-verification/fetchAll' => 'blood-requests-verification/get_all_completed',
                'API/blood-requests-verification/fetchAllByUserID/<user_id:>' => 'blood-requests-verification/get_user_donations', // status = 1
                'API/blood-requests/fetchAllByUserID/<user_id:>' => 'blood-requests/get_user_requests',
                'API/blood-requests/create' => 'blood-requests/create_request',
                'API/branch-requests/fetchAll' => 'branch-requests/get_all_active',
                'API/blood-requests-verification/fetchAllPendingsBySavedUser/<user_id:>' => 'blood-requests-verification/get_pending_donations', //status = 0
                'API/branch-requests-verification/fetchAllPendingsBySavedUser/<user_id:>' => 'branch-requests-verification/get_pending_donations', //status = 0

                'API/blood-types/fetchAll' => 'blood-types/get_types',
                'API/blood-types/create' => 'blood-types/create_type',

                'API/blood-requests-verification/create' => 'blood-requests-verification/create_verifications',
                'API/branch-requests-verification/create' => 'branch-requests-verification/create_verifications',

                'API/campaigns/fetchAll' => 'campaigns/get_all_active',
                'API/campaigns/fetchSingle/<key:>' => 'campaigns/get_single',
                'API/campaigns/checkSubscriptions/<user_id:>/<camp_id:>' => 'campaigns/my_subscriptions',
                'API/campaigns/subscribe' => 'campaigns/subscribe',
                'API/campaigns/unsubscribe' => 'campaigns/unsubscribe',
                'campaigns/subscribers/<campaign_id:>' => 'campaigns/subscribers',

                'API/day-reservation/fetchAllByUserID/<user_id:>' => 'donation-day-reservation/get_user_reservations',
                'API/day-reservation/create' => 'donation-day-reservation/create_reservations',

                'API/saved-blood-requests/fetchAllByUserID/<user_id:>' => 'saved-blood-requests/get_user_saved_req',
                'API/saved-blood-requests/Create' => 'saved-blood-requests/create_save',

                'API/saved-branch-requests/fetchAllByUserID/<user_id:>' => 'saved-branch-requests/get_user_saved_req',
                'API/saved-branch-requests/Create' => 'saved-branch-requests/create_save',

                'API/analysis/bloodGroup' => 'analysis/blood_group',
                'API/analysis/bloodRequest' => 'analysis/request_status',

                'API/branches/fetchAll' => 'branch/get_all',

                'API/users/register' => 'users/register',
                'API/users/login' => 'users/login',
                'API/users/updateToken' => 'users/update_token',
                'API/users/removeToken' => 'users/remove_token',
                'users/OthersActiveTokens/<my_token:>' => 'users/othersactivetokens',

                'API' => 'site/api'
            ]
        ],

    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        // 'allowedIPs' => ['127.0.0.1', '::1', $_SERVER['REMOTE_ADDR']],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', $_SERVER['REMOTE_ADDR']],
    ];
}

return $config;
