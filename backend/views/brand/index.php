<table class="table table-bordered table-hover table-striped">
    <tr>
        <th>商品ID</th>
        <th>名称</th>
        <th>简介</th>
        <th>Logo</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach($model as $brand):?>
        <tr>
            <td><?=$brand->id?></td>
            <td><?=$brand->name?></td>
            <td><?=$brand->intro?></td>
            <td><?php echo \yii\bootstrap\Html::img($brand->logo,['class'=>'img-circle','width'=>50,'height'=>50])?></td>
            <td><?=$brand->sort?></td>
            <td><?=\backend\models\Brand::$allstatus[$brand->status]?></td>
            <td><?=\yii\bootstrap\Html::a('修改',['brand/edit','id'=>$brand->id],['class'=>'btn btn-warning btn-xs']);?> <?=\yii\bootstrap\Html::a('删除',['brand/del','id'=>$brand->id],['class'=>'btn btn-danger btn-xs']);?> </td>
        </tr>
    <?php endforeach;?>
</table>
<?=\yii\bootstrap\Html::a('添加',['brand/del'],['class'=>'btn btn-danger btn-xs']);?>

