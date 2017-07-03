<?php /* @var $this yii\web\View */?>
<!-- 页面主体 start -->
<div class="main w1210 bc mt10">
    <div class="crumb w1210">
        <h2><strong>我的XX </strong><span>> 我的订单</span></h2>
    </div>

    <!-- 左侧导航菜单 start -->
    <div class="menu fl">
        <h3>我的XX</h3>
        <div class="menu_wrap">
            <dl>
                <dt>订单中心 <b></b></dt>
                <dd><b>.</b><a href="">我的订单</a></dd>
                <dd><b>.</b><a href="">我的关注</a></dd>
                <dd><b>.</b><a href="">浏览历史</a></dd>
                <dd><b>.</b><a href="">我的团购</a></dd>
            </dl>

            <dl>
                <dt>账户中心 <b></b></dt>
                <dd class="cur"><b>.</b><a href="">账户信息</a></dd>
                <dd><b>.</b><a href="">账户余额</a></dd>
                <dd><b>.</b><a href="">消费记录</a></dd>
                <dd><b>.</b><a href="">我的积分</a></dd>
                <dd><b>.</b><a href="">收货地址</a></dd>
            </dl>

            <dl>
                <dt>订单中心 <b></b></dt>
                <dd><b>.</b><a href="">返修/退换货</a></dd>
                <dd><b>.</b><a href="">取消订单记录</a></dd>
                <dd><b>.</b><a href="">我的投诉</a></dd>
            </dl>
        </div>
    </div>
    <!-- 左侧导航菜单 end -->

    <!-- 右侧内容区域 start -->
    <div class="content fl ml10">
        <div class="address_hd">
            <h3>收货地址薄</h3>
            <?php foreach ($models as $addr):?>
            <dl>
                <dt>
                    <?=$addr->addressee?>&nbsp;
                    <?=\frontend\models\Locations::findOne(['id'=>$addr->province])->name?>&nbsp;
                    <?=\frontend\models\Locations::findOne(['id'=>$addr->city])->name?>&nbsp;
                    <?=\frontend\models\Locations::findOne(['id'=>$addr->area])->name?>&nbsp;
                    <?=$addr->tel?>&nbsp;
                </dt>
                <dd>
                    <a href="<?php echo Yii::getAlias('@web')?>.'/address/edit?id='.<?=$addr->id?>">修改</a>
                    <a href="<?php if(Yii::$app->user->identity){echo '<a href='.Yii::getAlias('@web').'/member/login>收货地址</a>';}?>">去购物车结算</a>
                    <a href="del?id=<?=$addr->id?>">删除</a>
                    <a href="addr?id=<?=$addr->id?>" style="color:<?=$addr->status?'red':''?>">设为默认地址</a>
                </dd>
            </dl>
            <?php endforeach;?>

        </div>

        <div class="address_bd mt10">
            <h4>新增收货地址</h4>
            <?php $form=\yii\widgets\ActiveForm::begin(
                [
                    'fieldConfig'=>[
                        'options'=>['tag'=>'li'],
                        'errorOptions'=>['tag'=>'p']
                    ],
                ]
            );
            echo'<ul>';
                echo $form->field($model,'addressee')->textInput(['class'=>'txt']);
                echo $form->field($model,'tel')->textInput(['class'=>'txt']);

                $url=\yii\helpers\Url::toRoute(['get-region']);
                echo $form->field($model, 'province')->widget(\chenkby\region\Region::className(),[
                    'model'=>$model,
                    'url'=>$url,
                    'province'=>[
                        'attribute'=>'province',
                        'items'=>\frontend\models\Locations::getRegion(),
                        'options'=>['class'=>'form-control form-control-inline','prompt'=>'选择省份']
                    ],
                    'city'=>[
                        'attribute'=>'city',
                        'items'=>\frontend\models\Locations::getRegion($model['province']),
                        'options'=>['class'=>'form-control form-control-inline','prompt'=>'选择城市']
                    ],
                    'district'=>[
                        'attribute'=>'area',
                        'items'=>\frontend\models\Locations::getRegion($model['city']),
                        'options'=>['class'=>'form-control form-control-inline','prompt'=>'选择县/区']
                    ]
                ]);
                echo $form->field($model,'address')->textInput();
                echo $form->field($model,'status')->checkbox(['class'=>'check']);
                echo ' <li>
                        <label for="">&nbsp;</label>
                        <input type="submit" name="" class="btn" value="保存" />
                    </li>';
            echo '</ul>';
            \yii\widgets\ActiveForm::end();
            ?>

        </div>

    </div>
    <!-- 右侧内容区域 end -->
</div>
<!-- 页面主体 end-->
