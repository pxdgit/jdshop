<?=\yii\bootstrap\Html::a('添加',['menu/add'],['class'=>'btn btn-primary btn-xs']);
//echo \yii\widgets\LinkPager::widget(['pagination'=>$page]);
$this->registerCssFile('http://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css');
$this->registerJsFile('http://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js',['depends'=>\yii\web\JqueryAsset::className()]);
?>

<table class="table table-bordered table-hover table-striped" id="myTable">
    <thead>
    <tr>
        <th>菜单ID</th>
        <th>名称</th>
        <th>路由</th>
        <th>排序</th>
        <th>操作</th>
    </tr></thead>
    <tbody>
    <?php foreach($model as $menu):?>
        <tr>
            <td><?=$menu->id?></td>
            <td><?=$menu->label?></td>
            <td><?=$menu->url?></td>
            <td><?=$menu->sort?></td>
            <td>
                <?=\Yii::$app->user->can('menu/edit')?\yii\bootstrap\Html::a('修改',['menu/edit','id'=>$menu->id],['class'=>'btn btn-warning btn-xs']):'';?>                  <?=\Yii::$app->user->can('menu/del')?\yii\bootstrap\Html::a('删除',['menu/del','id'=>$menu->id],['class'=>'btn btn-danger btn-xs']):'';?>
        </tr>
    <?php endforeach;?></tbody>
</table>


<?php
$js=<<<JS
 $(document).ready(function(){
        $('#myTable').DataTable();
    });
JS;
$this->registerJs($js);
