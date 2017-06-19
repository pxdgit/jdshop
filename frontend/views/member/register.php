
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
                echo '<li>
                        <label for="">验证码：</label>
                        <input type="text" class="txt" value="" placeholder="请输入短信验证码" name="captcha" disabled="disabled" id="captcha"/> <input type="button" onclick="bindPhoneNum(this)" id="get_captcha" value="获取验证码" style="height: 25px;padding:3px 8px"/>
                    </li>';

                  echo $form->field($model,'code',['options'=>['class'=>'checkcode']])->widget(\yii\captcha\Captcha::className(),['template'=>'{input}{image}']);
                echo '
                  <li>
                        <label for="">&nbsp;</label>
                        <input type="submit" value="" class="login_btn"/>
                   </li>
                  
                    ';
                echo $form->field($model,'agree')->checkbox(['checked'=>"checked"]);

            echo '</ul>';
            \yii\widgets\ActiveForm::end();
            ?>
<!--            <form action="" method="post">-->
<!--                <ul>-->
<!--                    <li>-->
<!--                        <label for="">&nbsp;</label>-->
<!--                        <input type="checkbox" class="chb" checked="checked" /> 我已阅读并同意《用户注册协议》-->
<!--                    </li>-->
<!--                </ul>-->
<!--            </form>-->


        </div>

        <div class="mobile fl">
            <h3>手机快速注册</h3>
            <p>中国大陆手机用户，编辑短信 “<strong>XX</strong>”发送到：</p>
            <p><strong>1069099988</strong></p>
        </div>

    </div>
</div>
<!-- 登录主体部分end -->


<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
<script type="text/javascript">
    function bindPhoneNum(){
        //启用输入框
        $('#captcha').prop('disabled',false);

        var time=30;
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
