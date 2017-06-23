<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'language'=>'zh-CN',//语言
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'frontend\models\Member',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [//地址管理（伪静态）
            'enablePrettyUrl' => true,//开启好看的地址
            'showScriptName' => false,//不显示脚本文件（index.php）
//            'suffix'=>'.html',//伪静态的后缀
            'rules' => [//自定义路由规则
            ],
        ],
        //配置短信组件
        'sms'=>[
            'class'=>\frontend\components\Sms::className(),
            'app_key'=>'24479817',
            'app_secret'=>'68e1d1be7905cf3552800d4c77492310',
            'sign_name'=>'彭雪',
            'templatecode'=>'SMS_71540143',
        ]
    ],
    'params' => $params,
];
