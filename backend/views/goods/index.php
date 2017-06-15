<?php
/**
 * @var $this \yii\web\View
 */
$form=\yii\bootstrap\ActiveForm::begin(['method'=>'get','action'=>'index']);
echo '<div class="form-group col-lg-2">'.$form->field($search,'name')->textInput(['placeholder'=>"商品名"])->label(false).'</div>';
echo '<div class="form-group col-lg-2">'.$form->field($search,'sn')->textInput(['placeholder'=>"货号"])->label(false).'</div>';
echo '<div class="form-group col-lg-2">'.$form->field($search,'goods_category_id')->dropDownList($cates,['prompt'=>'请选择商品分类'])->label(false).'</div>';
echo '<div class="form-group col-lg-2">'.$form->field($search,'brand_id')->dropDownList($brands,['prompt'=>'请选择品牌'])->label(false).'</div>';
echo \yii\bootstrap\Html::submitButton('搜索',['class'=>'btn']);
$form=\yii\bootstrap\ActiveForm::end();
?>

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
<?php  echo \yii\bootstrap\Html::a('添加',['goods/add'],['class'=>'btn btn-danger btn-xs']);?>




