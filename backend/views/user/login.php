<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($loginform,'username');
echo $form->field($loginform,'password')->passwordInput();
echo $form->field($loginform,'remenberme')->checkbox();
echo \yii\bootstrap\Html::submitButton('登陆',['class'=>'btn btn-danger']);
\yii\bootstrap\ActiveForm::end();