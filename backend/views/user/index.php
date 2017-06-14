<?php
/**
 * @var $this \yii\web\View
 */
$form=\yii\bootstrap\ActiveForm::begin(['method'=>'get','action'=>'index']);?>
<table class="table table-bordered table-hover table-striped">
    <tr>
        <th>ID</th>
        <th>管理员名称</th>
        <th>邮箱</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>修改时间</th>
        <th>最后登录时间</th>
        <th>最后登录IP</th>
        <th>操作</th>
    </tr>
    <?php foreach($model as $user):?>
        <tr>
            <td><?=$user->id?></td>
            <td><?=$user->username?></td>
            <td><?=$user->email?></td>
            <td><?=\backend\models\User::$allstatus[$user->status]?></td>
            <td><?=$user->created_at?></td>
            <td><?=$user->updated_at?></td>
            <td><?=$user->last_log_time?></td>
            <td><?=$user->last_log_ip?></td>
            <td><?=\yii\bootstrap\Html::a('修改',['user/edit','id'=>$user->id],['class'=>'btn btn-warning btn-xs']);?>　<?=\yii\bootstrap\Html::a('删除',['user/del','id'=>$user->id],['class'=>'btn btn-danger btn-xs']);?>　
            </td>
        </tr>
    <?php endforeach;?>
</table>


