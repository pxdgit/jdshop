<?php
/**
* @var $this \yii\web\View
*/


$this->registerCssFile('http://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css');
$this->registerJsFile('http://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js',['depends'=>\yii\web\JqueryAsset::className()]);
?>


<table class="table" id="myTable">
    <thead>
    <tr>
        <th>权限名</th>
        <th>权限介绍</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($models as $model):?>
    <tr>
        <td><?=$model->name?></td>
        <td><?=$model->description?></td>
        <td>
            <?=\Yii::$app->user->can('rbac/edit-permission')?\yii\bootstrap\Html::a('修改',['rbac/edit-permission','name'=>$model->name],['class'=>'btn btn-info btn-xs']):''?>
            <?=\Yii::$app->user->can('rbac/edit-permission')?\yii\bootstrap\Html::a('删除',['rbac/del-permission','name'=>$model->name],['class'=>'btn btn-danger btn-xs']):''?>
        </td>
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

