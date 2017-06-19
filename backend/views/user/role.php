<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'roles',['inline'=>1])->checkboxList(\backend\models\User::getroles());
echo \yii\bootstrap\Html::submitButton('保存',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();