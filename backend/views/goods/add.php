<?php
/**
 * @var $this \yii\web\View
 */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'goods_category_id')->hiddenInput();
echo '<ul id="treeDemo" class="ztree"></ul>';
echo $form->field($model,'is_on_sale',['inline'=>1])->radioList(\app\models\Goods::$allis_on_sale);
echo $form->field($model,'status',['inline'=>1])->radioList(\app\models\ Goods::$allstatus);
echo $form->field($model,'brand_id')->dropDownList($brandcate);
echo $form->field($model,'market_price');
echo $form->field($model,'shop_price');
echo $form->field($model,'stock');
echo $form->field($model,'sort');
echo $form->field($model,'logo')->hiddenInput();
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo \xj\uploadify\Uploadify::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'width' => 120,
        'height' => 40,
        'onUploadError' => new \yii\web\JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadSuccess' => new \yii\web\JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
    	    $('#img_logo').attr('src',data.fileUrl).show();
        $('#goods-logo').val(data.fileUrl);//将上传的图片地址放到隐藏域         
    }
}
EOF
        ),
    ]
]);

$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
$znode=\yii\helpers\Json::encode($goodscate);
$js=new \yii\web\JsExpression(
    <<<JS
    var zTreeObj;
    var setting = {
        data: {
		simpleData: {
			enable: true,
			idKey: "id",
			pIdKey: "parent_id",
		   	rootPId: 0
		    }
	    },
	    callback: {
		    onClick:function zTreeOnClick(event, treeId, treeNode) {
                  $('#goods-goods_category_id').val(treeNode.id);  
             }
	    }
    };
    var zNodes = {$znode};
      zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
      zTreeObj.expandAll(1);//展开分类
JS
);
$this->registerJs($js);
if($model->logo){
    echo \yii\bootstrap\Html::img($model->logo,['class'=>'img-circle','width'=>200,'height'=>200,'id'=>'img_logo']);
}else{
    echo \yii\bootstrap\Html::img($model->logo,['class'=>'img-circle','width'=>200,'height'=>200,'style'=>'display:none','id'=>'img_logo']);
}
echo $form->field($intromodel,'content')->widget('kucha\ueditor\UEditor',[]);
echo '<br/>';
echo \yii\bootstrap\Html::submitButton('保存',['class'=>'btn btn-danger']);
\yii\bootstrap\ActiveForm::end();

?>
