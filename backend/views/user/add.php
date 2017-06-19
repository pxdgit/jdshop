<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username');
echo $form->field($model,'email');
//if(!$model->id){
    echo $form->field($model,'roles',['inline'=>1])->checkboxList(\backend\models\User::getroles());
//};
if (!$model->password_hash){echo $form->field($model,'password')->passwordInput();echo $form->field($model,'repassword')->passwordInput();};
echo $form->field($model,'status',['inline'=>true])->radioList(\backend\models\User::$allstatus);
echo \yii\bootstrap\Html::submitButton('保存',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();