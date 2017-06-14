<?php
/**
 * @var $this \yii\web\View
 */
$form=\yii\bootstrap\ActiveForm::begin(['method'=>'get','action'=>'index']);?>
<div class="row">
    <div class="col-lg-6">
        <div class="input-group ">
<!--            condition字段   key关键字   tablename表名-->
            <?=$form->field($search,'key')->label(false);?>
            <?=$form->field($search,'tablename')->label(false)->hiddenInput(['value'=>'','id'=>'tablename']);;?>
            <?=$form->field($search,'condition')->label(false)->hiddenInput(['value'=>'','id'=>'buttonkey']);?>
            <div class="input-group-btn">
                <button type="bottom" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top: 10px" id="btn" >关键字<span class="caret"></span></button>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li><a href="#" name="name" data-val="Goods">名称</a></li>
                    <li><a href="#" name="name" data-val="Brand">品牌</a></li>
                    <li><a href="#" name="name" data-val="GoodsCategory">分类</a></li>
                    <li><a href="#" name="sn" data-val="Goods">货号</a></li>
                </ul>
                <button type="submit" class="btn btn-default " style="margin-top: 10px" >搜索</button>
            </div><!-- /btn-group -->
        </div><!-- /input-group -->
    </div><!-- /.col-lg-6 -->
</div><!-- /.row -->
<?php $form=\yii\bootstrap\ActiveForm::end();
echo'</br>';
echo \yii\bootstrap\Html::a('添加',['goods/add'],['class'=>'btn btn-danger btn-xs']);?>
<?php
$js=new \yii\web\JsExpression(
        <<<JS
        $('.dropdown-menu a').click(function() {
            $('#btn').html($(this).text()+'<span class="caret"></span>');
            $('#buttonkey').val($(this).attr('name'));
            $('#tablename').val($(this).attr('data-val'));
        })
JS
);
$this->registerJs($js);
?>
</br>
<table class="table table-bordered table-hover table-striped">
    <tr>
        <th>商品ID</th>
        <th>名称</th>
        <th>货号</th>
        <th>Logo</th>
        <th>商品分类</th>
        <th>品牌分类</th>
        <th>市场价格</th>
        <th>商品价格</th>
        <th>库存</th>
        <th>在售</th>
        <th>状态</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    <?php foreach($model as $goods):?>
        <tr>
            <td><?=$goods->id?></td>
            <td><?=$goods->name?></td>
            <td><?=$goods->sn?></td>
            <td><?php echo \yii\bootstrap\Html::img($goods->logo,['class'=>'img-circle','width'=>50,'height'=>50])?></td>
            <td><?=$goods->cates->name?></td>
            <td><?=$goods->brand->name?></td>
            <td><?=$goods->market_price?></td>
            <td><?=$goods->shop_price?></td>
            <td><?=$goods->stock?></td>
            <td><?=\app\models\Goods::$allis_on_sale[$goods->is_on_sale]?></td>
            <td><?=\app\models\Goods::$allstatus[$goods->status]?></td>
            <td><?=$goods->sort?></td>
            <td><?=\yii\bootstrap\Html::a('修改',['goods/edit','id'=>$goods->id],['class'=>'btn btn-warning btn-xs']);?>　<?=\yii\bootstrap\Html::a('删除',['goods/del','id'=>$goods->id],['class'=>'btn btn-danger btn-xs']);?>　
                <?=\yii\bootstrap\Html::a('添加商品图',['goods-images/add','id'=>$goods->id],['class'=>'btn btn-success btn-xs']);?>
                <?=\yii\bootstrap\Html::a('添加商品图2',['goods-album/index','id'=>$goods->id],['class'=>'btn btn-success btn-xs']);?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?=yii\widgets\LinkPager::widget(['pagination'=>$page]);
?>


