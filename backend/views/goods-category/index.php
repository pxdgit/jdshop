<?=\yii\bootstrap\Html::a('添加',['goods-category/add'],['class'=>'btn btn-danger btn-md']);?>
<p></p>

<table class="cate table table-bordered table-hover table-striped">
    <tr>
        <th>分类ID</th>
        <th>名称</th>
        <th>操作</th>
    </tr>
    <?php foreach($models as $model):?>
        <tr data-lft="<?=$model->lft?>"  data-rgt="<?=$model->rgt?>"  data-tree="<?=$model->tree?>">
            <td><?=$model->id?></td>
            <td><?=str_repeat(' - -',$model->depth).$model->name?>
              <span class="toggle_cate glyphicon glyphicon-chevron-down" style="float:right;"></span>
            </td>

            <td>
                <?=\Yii::$app->user->can('goods-category/edit')?\yii\bootstrap\Html::a('修改',['goods-category/edit','id'=>$model->id],['class'=>'btn btn-warning btn-xs']):'';?>
                <?=\Yii::$app->user->can('goods-category/del')?\yii\bootstrap\Html::a('删除',['goods-category/del','id'=>$model->id],['class'=>'btn btn-danger btn-xs']):'';?> </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
$js=<<<JS
 $(".toggle_cate").click(function () {
        //查找当前分类的子孙分类
        var tree =parseInt( $(this).closest('tr').attr('data-tree'));
        var lft = parseInt($(this).closest('tr').attr('data-lft'));
        var rgt = parseInt($(this).closest('tr').attr('data-rgt'));

        //是否显示，根据图标判断
        var show=$(this).hasClass('glyphicon glyphicon-chevron-down');//图标是向上时，显示    向下是focus
        console.debug(show);
        // 图标切换
        $(this).toggleClass('glyphicon glyphicon-chevron-up');
        $(this).toggleClass('glyphicon glyphicon-chevron-down');
        $(".cate tr").each(function () {
//            同一颗tree，左值大于lft，右值小于rgt
               console.debug( parseInt($(this).attr('data-tree')),tree,parseInt($(this).attr('data-lft')),lft,parseInt($(this).attr('data-rgt')),rgt);
          if(parseInt($(this).attr('data-tree'))==tree && parseInt($(this).attr('data-lft')) > lft && $(this).attr('data-rgt') < rgt){   
              
               show?$(this).hide():$(this).show();
               // show?$(this).fadeIn():$(this).fadeOut();
            }
        });
    })
JS;
$this->registerJS($js);
?>



