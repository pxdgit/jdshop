<!DOCTYPE html>
<HTML>
<HEAD>
    <TITLE> ZTREE DEMO </TITLE>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
<!--    <link rel="stylesheet" href="/zTree/css/demo.css" type="text/css">-->
    <link rel="stylesheet" href="/zTree/css/zTreeStyle/zTreeStyle.css" type="text/css">
    <script type="text/javascript" src="/zTree/js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="/zTree/js/jquery.ztree.core.js"></script>
    <SCRIPT LANGUAGE="JavaScript">
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
            }
        };
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes =  <?=\yii\helpers\Json::encode($goodscategory)?>
//            [
            // 简单 JSON 数据格式，必须设置 setting.data.simple 内的属性
//            {id:1, pId:0, name: "父节点1"},
//            {id:11, pId:1, name: "子节点1"},
//            {id:12, pId:1, name: "子节点2"}
//        ];
        $(document).ready(function(){
            zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
        });
    </SCRIPT>
</HEAD>
<BODY>
<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>
</BODY>
</HTML>