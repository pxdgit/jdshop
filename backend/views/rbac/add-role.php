<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'description')->textarea();
echo $form->field($model,'permissions',['inline'=>1])->checkboxList(\backend\models\RoleForm::getpermissions());
echo \yii\bootstrap\Html::submitButton('保存',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();