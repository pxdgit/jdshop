<table class="table table-bordered table-hover table-striped">
    <tr>
        <th>文章ID</th>
        <th>名称</th>
        <th>分类名称</th>
        <th>简介</th>
        <th>排序</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>
    <?php foreach($model as $article):?>
        <tr>
            <td><?=$article->id?></td>
            <td><?=$article->name?></td>
            <td><?=$article->articlecategory->name?></td>
            <td><?=$article->intro?></td>
            <td><?=$article->sort?></td>
            <td><?=\backend\models\ArticleCategory::$allstatus[$article->status]?></td>
            <td><?=date('Y-m-d H:i:s',$article->create_time)?></td>
            <td><?=\yii\bootstrap\Html::a('修改',['article/edit','id'=>$article->id],['class'=>'btn btn-warning btn-xs']);?> <?=\yii\bootstrap\Html::a('删除',['article/del','id'=>$article->id],['class'=>'btn btn-danger btn-xs']);?>
                <?=\yii\bootstrap\Html::a('查看',['article/show','id'=>$article->id],['class'=>'btn btn-danger btn-xs']);?></td>
        </tr>
    <?php endforeach;?>
</table>
<?=\yii\bootstrap\Html::a('添加',['article/add'],['class'=>'btn btn-primary btn-xs']);
?>　
<?=\yii\bootstrap\Html::a('文章分类列表',['article-category/index'],['class'=>'btn btn-danger btn-xs']);?>

