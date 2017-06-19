<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'description')->textarea();
echo \yii\bootstrap\Html::submitButton('保存',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();