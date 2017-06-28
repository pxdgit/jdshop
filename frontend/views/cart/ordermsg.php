<?php
/**
 * @var $this \yii\web\View
 */?>
<!-- 页面头部 start -->
<?php $form=\yii\bootstrap\ActiveForm::begin()?>
<div class="header w990 bc mt15">
    <div class="logo w990">
        <h2 class="fl"><a href="index.html"><img src="images/logo.png" alt="京西商城"></a></h2>
        <div class="flow fr flow2">
            <ul>
                <li>1.我的购物车</li>
                <li class="cur">2.填写核对订单信息</li>
                <li>3.成功提交订单</li>
            </ul>
        </div>
    </div>
</div>
<!-- 页面头部 end -->

<div style="clear:both;"></div>
<!-- 主体部分 start -->
<div class="fillin w990 bc mt15">
    <div class="fillin_hd">
        <h2>填写并核对订单信息</h2>
    </div>
    <div class="fillin_bd">
        <!-- 收货人信息  start-->
        <div class="address">
            <h3>收货人信息</h3>
            <div class="address_info">
                <?php foreach ($alladdress as $k=>$address):
                    $province=$address->pro->name;
                    $city=$address->cit->name;
                    $area=$address->are->name;
                    ?>
                    <p>
                        <span><input type="radio" value="<?=$address->id?>" name="address_id"  <?=$k?'':'checked'?>/><?="$address->addressee $address->tel $province $city $area $address->address"?></span> </p>
                <?php endforeach;?>
                <?= $form->field($model,'name')->hiddenInput(['value'=>'','class'=>'put'])->label(false)?>
                <?= $form->field($model,'tel')->hiddenInput(['value'=>'','class'=>'put'])->label(false)?>
                <?= $form->field($model,'province')->hiddenInput(['value'=>'','class'=>'put'])->label(false)?>
                <?= $form->field($model,'city')->hiddenInput(['value'=>'','class'=>'put'])->label(false)?>
                <?=$form->field($model,'area')->hiddenInput(['value'=>'','class'=>'put'])->label(false)?>
                <?=$form->field($model,'address')->hiddenInput(['value'=>'','class'=>'put'])->label(false)?>
            </div>


        </div>
        <!-- 收货人信息  end-->

        <!-- 配送方式 start -->
        <div class="delivery">
            <h3>送货方式 </h3>


            <div class="delivery_select">
                <table>
                    <thead>
                    <tr>
                        <th class="col1">送货方式</th>
                        <th class="col2">运费</th>
                        <th class="col3">运费标准</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach (\frontend\models\Order::$delivers as $k=>$deliver):?>
                        <tr <?=$deliver['id']==1?'class="cur"':''?>>
                            <td data-name="<?=$deliver['id']?>">
                                <input type="radio" name="delivery" <?=$k?'':'checked'?>/><?=$deliver['name']?>
                            </td >
                            <td data-name="<?=$deliver['name']?>" >￥<?=$deliver['price']?></td>
                            <td data-name="<?=$deliver['price']?>" ><?=$deliver['msg']?></td>
                        </tr>
                    <?php endforeach;?>
                    <?= $form->field($model,'delivery_id')->hiddenInput(['value'=>'','class'=>'put'])->label(false)?>
                    <?= $form->field($model,'delivery_name')->hiddenInput(['value'=>'','class'=>'put'])->label(false)?>
                    <?= $form->field($model,'delivery_price')->hiddenInput(['value'=>'','class'=>'put'])->label(false)?>
                    </tbody>
                </table>

            </div>
        </div>
        <!-- 配送方式 end -->

        <!-- 支付方式  start-->
        <div class="pay">
            <h3>支付方式 </h3>


            <div class="pay_select">
                <table>
                    <?php foreach (\frontend\models\Order::$payments as $k=>$payment):?>
                    <tr  <?=$payment['id']==1?'class="cur"':''?>>
                        <td class="col1" data-name="<?=$payment['id']?>"><input type="radio" name="pay"  <?=$k?'':'checked'?>/><?=$payment['name']?></td>
                        <td class="col2" data-name="<?=$payment['name']?>"><?=$payment['msg']?></td>
                    </tr>
                    <?php endforeach;?>
                    <?= $form->field($model,'payment_id')->hiddenInput(['value'=>'','class'=>'put'])->label(false)?>
                    <?= $form->field($model,'payment_name')->hiddenInput(['value'=>'','class'=>'put'])->label(false)?>
                </table>

            </div>
        </div>
        <!-- 支付方式  end-->

        <!-- 商品清单 start -->
        <div class="goods">
            <h3>商品清单</h3>
            <table>
                <thead>
                <tr>
                    <th class="col1">商品</th>
                    <th class="col3">价格</th>
                    <th class="col4">数量</th>
                    <th class="col5">小计</th>
                </tr>
                </thead>
                <tbody>
                <?php $count=0;$sum=0;foreach($allgoods as $goods): $count++?>
                    <tr>
                        <td class="col1"><a href=""><img src="http://admin.jx.com<?=$goods['logo']?>" alt="" /></a>  <strong><a href=""><?=$goods['name']?></a></strong></td>
                        <td class="col3">￥<?=$goods['shop_price']?></td>
                        <td class="col4"><?=$goods['amount']?></td>
                        <td class="col5"><span>￥<?=$one_total=$goods['shop_price']*$goods['amount'];$sum+=$one_total?></span></td>
                    </tr>
                <?php endforeach;?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">
                        <ul>
                            <li>
                                <span><?=$count?>件商品，总商品金额：</span>
                                <em>￥<?=$sum?></em>
                            </li>
                            <li>
                                <span>返现：</span>
                                <em>-￥240.00</em>
                            </li>
                            <li>
                                <span>运费：</span>
                                <em id="freight">￥10.00</em>
                            </li>
                            <li>
                                <span>应付总额：</span>
                                <em id="total">￥5076.00</em>
                                <?= $form->field($model,'total')->hiddenInput(['value'=>'','id'=>'put'])->label(false)?>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        <!-- 商品清单 end -->
    </div>

    <div class="fillin_ft">
        <?=\yii\bootstrap\Html::submitButton(' ',['style'=>"float: right; display: inline; width: 135px; height: 36px; background: url(".Yii::getAlias('@web')."/images/order_btn.jpg?>) 0 0 no-repeat; vertical-align: middle; margin: 7px 10px 0;",'value'=>'','id'=>"submit_button"])?>
        <p>应付总额：<strong>￥5076.00元</strong></p>

    </div>

</div>
<!-- 主体部分 end -->
<?php \yii\bootstrap\ActiveForm::end()?>

<?php
$this->registerJs(new \yii\web\JsExpression(
        <<<JS
    $('#submit_button').click(function() {
        console.debug('s');
       var dataaddr=$('.address_info input:checked').closest('span').text().split(' ');
       var arraddr=$('.address_info .put');
          $(dataaddr).each(function(i,k) {
              $(arraddr[i]).val(k);
             });
       var datadeli=$('.delivery').find('input:checked').closest('tr').find('td');
       var arrdeli=$('.delivery .put');
          $(datadeli).each(function(i,k) {
               $(arrdeli[i]).val($(k).attr('data-name')); 
          });
       var datapany=$('.pay').find('input:checked').closest('tr').find('td');
       var arrpany=$('.pay .put');
           $(datapany).each(function(i,k) {
               $(arrpany[i]).val($(k).attr('data-name')); 
          });
    });
       $('.delivery input').change(function() {
            $('#freight').text('￥'+$(this).closest('tr').find('td:eq(2)').attr('data-name'));
                   gettotal();
       });
       $(function() {
         gettotal();
       });
       function gettotal() {
            var num=0;
        $('tfoot em:not(:last)').each(function(i,k) {
            var str=$(k).text();
            num= num+parseInt(str.match(/\d+/g));     
        });
        $('#put').val(num);
        $('tfoot #total').text('￥'+num);
        $('.fillin_ft strong').text('￥'+num);
       }
        
JS

))

?>
