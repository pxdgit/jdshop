<table class="table">
    <tr>
        <th>角色名</th>
        <th>介绍</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
    <tr>
        <td><?=$model->name?></td>
        <td><?=$model->description?></td>
        <td>
            <?=\Yii::$app->user->can('rbac/edit-role')?\yii\bootstrap\Html::a('修改',['rbac/edit-role','name'=>$model->name],['class'=>'btn btn-info btn-xs']):''?>
            <?=\Yii::$app->user->can('rbac/del-role')?\yii\bootstrap\Html::a('删除',['rbac/del-role','name'=>$model->name],['class'=>'btn btn-danger btn-xs']):''?>
        </td>
    </tr>
    <?php endforeach;?>
</table>
