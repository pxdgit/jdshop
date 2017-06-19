<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'language'=>'zh-CN',//语言
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'class'=>\yii\web\User::className(),
            'identityClass' => 'backend\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
            'loginUrl'=>['user/login'],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
        'qiniu'=>[
            'class'=>\backend\components\Qiniu::className(),
            'up_host'=>'http://up-z2.qiniu.com',
            'accessKey'=>'43g5nYcengHvUx2sdNOsfCg_0qK4SOQmy4dckGoI',
            'secretKey'=>'byhCSvjH2e_3B4jamWLfRu6FSY0YPFFLKE16igdE',
            'bucket'=>'jxshop',
            'domain'=>'http://or9r19axb.bkt.clouddn.com/',
        ]

    ],
    'params' => $params,
];
