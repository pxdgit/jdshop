<?php
namespace frontend\components;

use yii\base\Component;
use Flc\Alidayu\Client;
use Flc\Alidayu\App;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
use Flc\Alidayu\Requests\IRequest;

class Sms extends Component
{
    public $app_key;
    public $app_secret;
    public $sign_name;
    public $templatecode;
    private $num;//发给谁
    private $_param=[];
    //设置手机号码
    public function setNum($num){
        $this->num=$num;
        return $this;//返回值，好进行后面的连贯操作
    }
    //设置短信内容[只能发模板里定义的{$code}的内容]
    public function setParam(array $param){
        $this->_param=$param;
        return $this;//返回值，好进行后面的连贯操作

    }
    //设置签名
    public function setSign($sign){
        $this->sign_name=$sign;
        return $this;//返回值，好进行后面的连贯操作

    }
    //设置短信模板
    public function setTemple($id){
        $this->templatecode=$id;
        return $this;//返回值，好进行后面的连贯操作

    }

    public function send(){
        $client = new Client(new App(['app_key'=>$this->app_key,'app_secret'=>$this->app_secret]));
        $req    = new AlibabaAliqinFcSmsNumSend;
        $req->setRecNum($this->num)//设置手机号码
        ->setSmsParam($this->_param)
            ->setSmsFreeSignName($this->sign_name)//设置短信签名，必须是已审核的签名
            ->setSmsTemplateCode($this->templatecode);//设置短信模板ID
        $resp = $client->execute($req);
        return $resp;
    }
}