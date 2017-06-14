<?php
//$this \yii\web\View
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'parent_id')->hiddenInput();
echo '<ul id="treeDemo" class="ztree"></ul>';
echo \yii\bootstrap\Html::submitButton('保存',['class'=>'btn btn-danger']);
//<link rel="stylesheet" href="/zTree/css/zTreeStyle/zTreeStyle.css" type="text/css">
//    <script type="text/javascript" src="/zTree/js/jquery-1.4.4.min.js"></script>
//    <script type="text/javascript" src="/zTree/js/jquery.ztree.core.js"></script>
//添加静态资源
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);//当前js文件依赖于jquery[写上配置依赖于什么静态资源管理器]
//$this->registerJsFile('@web/zTree/js/jquery-1.4.4.min.js');
$zNode=\yii\helpers\Json::encode($option);//将数据转为json字符串
$js=new \Yii\web\JsExpression(
 <<<JS
  var zTreeObj;
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {
            data: {
               simpleData: {
                        enable: true,//不需要用户再把数据库中取出的 List 强行转换为复杂的 JSON 嵌套格式
                        idKey: "id",//节点数据中保存唯一标识的属性名称。[setting.data.simpleData.enable = true 时生效]
                        pIdKey: "parent_id",//节点数据中保存其父节点唯一标识的属性名称。[setting.data.simpleData.enable = true 时生效]
                        rootPId: 0//用于修正根节点父节点数据，即 pIdKey 指定的属性值。[setting.data.simpleData.enable = true 时生效]
                           }
                 },
            callback: {
		        onClick:function(event, treeId, treeNode) {//当前的树节点  
                        // console.debug(treeNode.id );//当前树节点的id值，也就是商品分类的id
                        $('#goodscategory-parent_id').val(treeNode.id )//要添加分类的父id为当前点击的分类id
                              }
		                }
}
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes = {$zNode};
//            [
// 简单 JSON 数据格式，必须设置 setting.data.simple 内的属性
//            {id:1, pId:0, name: "父节点1"},
//            {id:11, pId:1, name: "子节点1"},
//            {id:12, pId:1, name: "子节点2"}
//        ];
zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
zTreeObj.expandAll(1);//展开所有节点
//获取当前节点的父节点，根据id查找
var node=zTreeObj.getNodeByParam("id",$('#goodscategory-parent_id').val(),null);//获取id与分类父id相同的节点
zTreeObj.selectNode(node)//选择当前节点的父节点
JS
);
$this->registerJs($js);
\yii\bootstrap\ActiveForm::end();
?>
