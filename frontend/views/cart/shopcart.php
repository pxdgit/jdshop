<?php
/**
 * @var $this \yii\web\View
 */?>
<!-- 页面头部 start -->
<div class="header w990 bc mt15">
    <div class="logo w990">
        <h2 class="fl"><a href="index.html"><img src="images/logo.png" alt="京西商城"></a></h2>
        <div class="flow fr">
            <ul>
                <li class="cur">1.我的购物车</li>
                <li>2.填写核对订单信息</li>
                <li>3.成功提交订单</li>
            </ul>
        </div>
    </div>
</div>
<!-- 页面头部 end -->

<div style="clear:both;"></div>

<!-- 主体部分 start -->
<div class="mycart w990 mt10 bc">
    <h2><span>我的购物车</span></h2>
    <table>
        <thead>
        <tr>
            <th class="col1">商品名称</th>
            <th class="col3">单价</th>
            <th class="col4">数量</th>
            <th class="col5">小计</th>
            <th class="col6">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php $money=0;?>
        <?php foreach ($model as $goods):?>
            <tr data-goods_id="<?=$goods['id']?>">
                <td class="col1"><a href=""><img src="http://admin.jx.com/<?=$goods['logo']?>" alt="" /></a>  <strong><a href=""><?=$goods['name']?></a></strong></td>
                <td class="col3">￥<span><?=$goods['shop_price']?></span></td>
                <td class="col4">
                    <a href="javascript:;" class="reduce_num"></a>
                    <input type="text" name="amount" value="<?=$goods['amount']?>" class="amount"/>
                    <a href="javascript:;" class="add_num"></a>
                </td>
                <td class="col5">￥<span id="money"><?=$goods['amount']*$goods['shop_price']?></span></td>
                <td class="col6"><a href="javascript:;" class="del_goods">删除</a></td>
            </tr>
            <?=$money+=$goods['amount']*$goods['shop_price']?>
        <?php endforeach;?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="6">购物金额总计： <strong>￥ <span id="total"><?=$money?></span></strong></td>
        </tr>
        </tfoot>
    </table>
    <div class="cart_btn w990 bc mt10">
        <a href="" class="continue">继续购物</a>
        <a href="order-msg" class="checkout" >结 算</a>
    </div>
</div>
<?php
$url=\yii\helpers\Url::to(['cart/update']);
$csrf=Yii::$app->request->csrfToken;
$this->registerJs(new \yii\web\JsExpression(
        <<<JS
    $('.reduce_num ,.add_num').click(function() {
        var goods_id=$(this).closest('tr').attr('data-goods_id');
        var amount=$(this).closest('td').find('input').val();
        $.post("$url",{goods_id:goods_id,amount:amount,'_csrf-frontend':"$csrf"});
    });

    $('.amount').change(function() {
        var goods_id=$(this).closest('tr').attr('data-goods_id');
        var amount=$(this).val();
        $.post("$url",{goods_id:goods_id,amount:amount,'_csrf-frontend':"$csrf"});
    })


       $('.del_goods').click(function() {
        var goods_id=$(this).closest('tr').attr('data-goods_id');
        var money=$('#money').text();
        $.post("$url",{goods_id:goods_id,amount:0,'_csrf-frontend':"$csrf"});
        $(this).closest('tr').remove();
        $('#total').text($('#total').text()-money);
        console.debug(h);

    })
JS
))
?>