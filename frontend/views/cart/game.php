<?php
/**
 * @var $this \yii\web\View
 */?>
<a href="game">开始新游戏</a>
<?php
$form=\yii\widgets\ActiveForm::begin();
echo $form->field($model,'num0');
echo $form->field($model,'num1');
echo $form->field($model,'num2');
echo $form->field($model,'num3');
echo \yii\bootstrap\Html::submitButton('提交',['id'=>'btn']);
\yii\widgets\ActiveForm::end();
?>
<h3><?=$suma?$suma:''?>A<?=$sumb?$sumb:''?>B</h3>
<p>游戏规则编辑
    通常由两个人玩，一方出数字，一方猜。出数字的人要想好一个没有重复数字的4个数，不能让猜的人知道。猜的人就可以开始猜。每猜一个数字，出数者就要根据这个数字给出几A几B，其中A前面的数字表示位置正确的数的个数，而B前的数字表示数字正确而位置不对的数的个数。
    如正确答案为 5234，而猜的人猜 5346，则是 1A2B，其中有一个5的位置对了，记为1A，而3和4这两个数字对了，而位置没对，因此记为 2B，合起来就是 1A2B。
    接着猜的人再根据出题者的几A几B继续猜，直到猜中（即 4A0B）为止。</p>
<?php
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
$('button').click(function() {
    console.debug('s');
});
JS
));
?>