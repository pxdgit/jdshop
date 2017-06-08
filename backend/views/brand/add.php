<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'intro')->textarea();
if($model->logo){echo \yii\bootstrap\Html::img($model->logo,['class'=>'img-circle','width'=>200,'height'=>200]);};
echo $form->field($model,'imgFile')->fileInput();
echo $form->field($model,'sort');
echo $form->field($model,'status',['inline'=>true])->radioList(\backend\models\Brand::$allstatus);
echo \yii\bootstrap\Html::submitButton('添加',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();