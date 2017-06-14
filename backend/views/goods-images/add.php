<?php
use kartik\file\FileInput;
$form=\yii\bootstrap\ActiveForm::begin();
//完全就是定义一个多文件上传表单,没说的
echo $form->field($model, 'imgfiles[]')->widget(FileInput::classname(), [
    'options' => ['multiple' => true],]);
\yii\bootstrap\ActiveForm::end();