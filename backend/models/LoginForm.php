<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/14 0014
 * Time: 15:07
 */

namespace backend\models;


use backend\models\User;
use yii\base\Model;
use yii\web\Cookie;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $remenberme;
    public function rules()
    {
        return [
            [['username','password'],'required'],
            [['username','password'],'check'],
            ['remenberme','boolean']
        ];
    }
    public function attributeLabels()
    {
        return[
            'username'=>'用户名',
            'password'=>'密码',
            'remenberme'=>'记住密码',
        ];
    }
    public function check(){
        $user=User::findOne(['username'=>$this->username]);
        if($user){
            if(!\Yii::$app->security->validatePassword($this->password,$user->password_hash)){
               $this->addError('username','用户名或密码错误');
            }else{
                $duration=$this->remenberme?7*24*3600:0;
                \Yii::$app->user->login($user,$duration);
                $user->last_log_ip=\Yii::$app->request->userIP;
                $user->last_log_time=time();
                $user->auth_key=$user->getAuthKey();
                $user->save(false);

            }
        }else{
                $this->addError('username','用户名或密码错误');
        }
    }

}