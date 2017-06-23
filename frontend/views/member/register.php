<?php
/* @var $this yii\web\View */
?>

<!-- 登录主体部分start -->
<div class="login w990 bc mt10 regist">
    <div class="login_hd">
        <h2>用户注册</h2>
        <b></b>
    </div>
    <div class="login_bd">
        <div class="login_form fl">
            <?php
            $form=\yii\widgets\ActiveForm::begin(
                [
                    'fieldConfig'=>[
                        'options'=>['tag'=>'li'],
                        'errorOptions'=>['tag'=>'p']
                    ]
                ]
            );
            echo '<ul>';
                echo $form->field($model,'username')->textInput(['class'=>'txt']);
                echo $form->field($model,'password')->passwordInput(['class'=>'txt']);
                echo $form->field($model,'repassword')->passwordInput(['class'=>'txt']);
                echo $form->field($model,'email')->textInput(['class'=>'txt']);
                echo $form->field($model,'tel')->textInput(['class'=>'txt']);
//                echo '<li>
//                        <label for="">验证码：</label>
//                        <input type="text" class="txt" value="" placeholder="请输入短信验证码" name="captcha" disabled ="disabled" id="captcha"/>
//             <input type="button" onclick="bindPhoneNum(this)" id="get_captcha" value="获取验证码" style="height: 25px;padding:3px 8px"/>
//                    </li>';
           $button= '<input type="button"  id="get_captcha" value="获取验证码" style="height: 25px;padding:3px 8px"/>';
//                $button=\yii\helpers\Html::button('获取验证码',['id'=>'send_sms_button']);
                echo  $form->field($model,'smscaptcha',['template'=>"{label}\n{input} $button\n{hint}\n{error}"])->textInput(['placeholder'=>"请输入短信验证码",'name'=>"captcha" ,'disabled '=>"disabled", 'id'=>"captcha",'class'=>"txt"]);


                echo $form->field($model,'code',['options'=>['class'=>'checkcode']])->widget(\yii\captcha\Captcha::className(),['template'=>'{input}{image}']);
                $agree='我已阅读并同意《用户注册协议》';
                echo $form->field($model,'agree',['template'=>"{label}\n{input}$agree\n{hint}\n{error}"])->checkbox();
                echo '
                  <li>
                        <label for="">&nbsp;</label>
                        <input type="submit" value="" class="login_btn"/>
                   </li>
                  
                    ';
            echo '</ul>';
            \yii\widgets\ActiveForm::end();
            ?>


        </div>

        <div class="mobile fl">
            <h3>手机快速注册</h3>
            <p>中国大陆手机用户，编辑短信 “<strong>XX</strong>”发送到：</p>
            <p><strong>1069099988</strong></p>
        </div>

    </div>
</div>
<!-- 登录主体部分end -->
<?php
$url=\yii\helpers\Url::to(['member/sms']);
$js=new \yii\web\JsExpression(
    <<<JS
       $('#get_captcha').click(function() {
         if($('#member-tel').val()==''){
                alert('请填写手机号码');
               return false;
           }else{
             bindPhoneNum();
             var tel=$('#member-tel').val();
              $.post('$url',{tel:tel},function(responce) {                           
                if(responce.msg=='success'){
                    console.debug('短信发送成功');
                    console.debug(responce.code);              
                }else{
                    console.debug('短信发送失败');
                }
           },'json')
           }         
       })
    $('.login_btn').click(function() {
      if(!$('#member-agree').prop('checked')){
          alert('请阅读并同意用户注册协议');
      }
    })
       
JS
);
$this->registerJs($js);

?>

<script type="text/javascript">
    function bindPhoneNum(){
        //启用输入框
        $('#captcha').prop('disabled',false);
        var time=60;
        var interval = setInterval(function(){
            time--;
            if(time<=0){
                clearInterval(interval);
                var html = '获取验证码';
                $('#get_captcha').prop('disabled',false);
            } else{
                var html = time + ' 秒后再次获取';
                $('#get_captcha').prop('disabled',true);
            }
            $('#get_captcha').val(html);
        },1000);
    }
</script>
