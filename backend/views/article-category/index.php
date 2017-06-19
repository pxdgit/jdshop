<?=\yii\bootstrap\Html::a('添加',['article-category/add'],['class'=>'btn btn-primary btn-md']); ?>
<p></p>
<table class="table table-bordered table-hover table-striped">
    <tr>
        <th>分类ID</th>
        <th>名称</th>
        <th>简介</th>
        <th>排序</th>
        <th>状态</th>
        <th>类型</th>
        <th>操作</th>
    </tr>
    <?php foreach($model as $articlecate):?>
        <tr>
            <td><?=$articlecate->id?></td>
            <td><?=$articlecate->name?></td>
            <td><?=$articlecate->intro?></td>
            <td><?=$articlecate->sort?></td>
            <td><?=\backend\models\ArticleCategory::$allstatus[$articlecate->status]?></td>
            <td><?=\backend\models\ArticleCategory::$allhelp[$articlecate->is_help]?></td>
            <td>
                <?=\Yii::$app->user->can('article-category/edit')?\yii\bootstrap\Html::a('修改',['article-category/edit','id'=>$articlecate->id],['class'=>'btn btn-warning btn-xs']):'';?>
                <?=\Yii::$app->user->can('article-category/del')?\yii\bootstrap\Html::a('删除',['article-category/del','id'=>$articlecate->id],['class'=>'btn btn-danger btn-xs']):'';?>
                <?=\Yii::$app->user->can('article-category/show')?\yii\bootstrap\Html::a('查看文章',['article-category/show','id'=>$articlecate->id],['class'=>'btn btn-success btn-xs']):'';?></td>
        </tr>
    <?php endforeach;?>
</table>
<?php
echo \yii\widgets\LinkPager::widget(['pagination'=>$page]);?>


