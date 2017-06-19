<?php
use yii\web\JsExpression;
use xj\uploadify\Uploadify;

$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'logo')->hiddenInput();
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo Uploadify::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'width' => 120,
        'height' => 40,
        'onUploadError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadSuccess' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        $('#img_logo').attr('src',data.fileUrl).show();
        $('#brand-logo').val(data.fileUrl);
         
    }
}
EOF
        ),
    ]
]);
if($model->logo){
    echo \yii\bootstrap\Html::img($model->logo,['class'=>'img-circle','width'=>200,'height'=>200,'id'=>'img_logo']);
}else{
    echo \yii\bootstrap\Html::img($model->logo,['class'=>'img-circle','width'=>200,'height'=>200,'style'=>'display:none','id'=>'img_logo']);
}

echo $form->field($model,'sort');
echo $form->field($model,'status',['inline'=>true])->radioList(\backend\models\Brand::$allstatus);
echo \yii\bootstrap\Html::submitButton('添加',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();