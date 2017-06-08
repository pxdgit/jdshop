<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'intro')->textarea();
echo $form->field($detail,'content')->textarea();
echo $form->field($model,'sort');

echo $form->field($model,'status',['inline'=>true])->radioList(\backend\models\Brand::$allstatus);
echo $form->field($model,'article_category_id')->dropDownList($cates);
echo \yii\bootstrap\Html::submitButton('保存',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();