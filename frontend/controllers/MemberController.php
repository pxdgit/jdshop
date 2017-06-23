<?php

namespace frontend\controllers;



use frontend\models\LoginForm;
use frontend\models\Member;
use yii\helpers\Json;

//阿里大于
//use Flc\Alidayu\Client;
//use Flc\Alidayu\App;
//use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
//use Flc\Alidayu\Requests\IRequest;

class MemberController extends \yii\web\Controller
{
    public $layout="login";
    public function actionRegister()
    {
        $model=new Member();
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
               $model->save(false);
            return $this->redirect('index');
        }
        return $this->render('register',['model'=>$model]);
    }
    public function actionLogin()
    {
        $model=new  LoginForm();
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
              if($model->login()){
                  return $this->redirect('index');
              }
        }
        return $this->render('login',['model'=>$model]);
    }
    public function actionLogout(){
        \Yii::$app->user->logout();
    }
    public function actionNow(){
        $identity = \Yii::$app->user->identity;
        var_dump($identity);
    }

    public function actionSms(){
        $code=rand(1000,9000);
        $tel=\Yii::$app->request->post('tel');
        if(!preg_match('/^1[3578]\d{9}$/',$tel)){
            echo 'false';
            exit;
        }
//        $result=\Yii::$app->sms->setNum($tel)->setParam(['code'=>$code])->send();
        \Yii::$app->cache->set('tel_'.$tel,$code,60);
        if($result=1){
            echo Json::encode(['msg'=>'success','code'=>$code]);
        }else{
            echo Json::encode(['msg'=>'false','code'=>$code]);
        }

//
//// 配置信息
//        $config = [
//            'app_key'    => '24479817',
//            'app_secret' => '68e1d1be7905cf3552800d4c77492310',
//            // 'sandbox'    => true,  // 是否为沙箱环境，默认false
//        ];
//// 使用方法一
//        $client = new Client(new App($config));
//        $req    = new AlibabaAliqinFcSmsNumSend;
//
//        $code=rand(1000,9999);
//        $req->setRecNum('18982752327')//设置手机号码
//        ->setSmsParam([
////            'code' => rand(100000, 999999)//模板里面是{code}，所以此处用code
//            'code' =>$code//模板里面是{code}，所以此处用code
//        ])
//            ->setSmsFreeSignName('彭雪')//设置短信签名，必须是已审核的签名
//            ->setSmsTemplateCode('SMS_71540143');//设置短信模板ID
//
//
//        $resp = $client->execute($req);
//        var_dump($resp);
//        var_dump($code);
    }
    public function actionMail(){
        $result=\Yii::$app->mailer->compose()
             ->setFrom('PX_khh@163.com')
             ->setTo('PX_khh@163.com')
             ->setSubject('Message subject')
             ->setHtmlBody('ssssssss')
             ->send();
        var_dump($result);
    }
}
