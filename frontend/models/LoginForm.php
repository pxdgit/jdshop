<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/19 0019
 * Time: 14:43
 */

namespace frontend\models;


use yii\base\Model;

class LoginForm extends Model
{
    public $password;
    public $username;
    public $code;
    public $rememberme;
    public function rules(){
       return [
           [['username','password'],'required'],
           ['code','captcha','captchaAction'=>'site/captcha'],
           ['rememberme','boolean']
       ];
    }
    public function attributeLabels()
    {
        return [
            'username' => '用户名：',
            'password' => '密码：',
            'code：'=>'验证码',
            'rememberme'=>' '
        ];
    }
    public function login(){
        $menber=Member::findOne(['username'=>$this->username]);
        if($menber){
            if(!\Yii::$app->security->validatePassword($this->password,$menber->password_hash)){
                $this->addError('username','用户名或密码不正确');
                return false;
            }else{
                $duration=$this->rememberme?7*24*3600:0;
                \Yii::$app->user->login($menber,$duration);
                $menber->last_login_ip=ip2long(\Yii::$app->request->userIP);
                $menber->last_login_time=time();
                $menber->auth_key=$menber->getAuthKey();
                $menber->save(false);
                $cookies=\Yii::$app->request->cookies;

                if($cookies->get('cart')) {//cookie中是否已有数据//有则取出反序列化为数组
                    $cart = unserialize($cookies->get('cart'));
                    foreach ($cart as $k => $v) {
                        if ($cart = Cart::findOne(['goods_id' => $k,'member_id'=>\Yii::$app->user->id])) {
                            $cart->amount += $v;
                        } else {
                            $cart = new Cart();
                            $cart->goods_id = $k;
                            $cart->amount = $v;
                            $cart->member_id = \Yii::$app->user->id;
                        }

                        $cart->save();
                    }
                    $cookies = \Yii::$app->response->cookies;
                    $cookies->remove('cart');
                }

                }
                return true;
            }
                $this->addError('username','用户名或密码不正确');
                return false;

    }
}