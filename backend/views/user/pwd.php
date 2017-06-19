<?php
$form=\yii\bootstrap\ActiveForm::begin();

if(!empty($model->password_hash)){echo $form->field($model,'password')->passwordInput();}
echo $form->field($model,'newpassword')->passwordInput();
echo $form->field($model,'repassword')->passwordInput();
echo \yii\bootstrap\Html::submitButton('保存',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();