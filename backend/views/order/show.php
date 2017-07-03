<?php
/**
* @var $this \yii\web\View
*/


$this->registerCssFile('http://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css');
$this->registerJsFile('http://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js',['depends'=>\yii\web\JqueryAsset::className()]);
?>


    <table class="table table-bordergoodsed table-hover table-striped" id="myTable">
        <thead>
        <tr>
            <th>商品ID</th>
            <th>名称</th>
            <th>Logo</th>
            <th>单价</th>
            <th>数量</th>
            <th>小计</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model as $ordergoods):?>
        <tr>
            <td><?=$ordergoods->goods_id?></td>
            <td><?=$ordergoods->goods_name?></td>
            <td><img src="http://admin.jx.com/<?=$ordergoods->logo?>" alt=""></td>
            <td><?=$ordergoods->price?></td>
            <td><?=$ordergoods->amount?></td>
            <td><?=$ordergoods->total?></td>
        </tr>
        </tbody>
        <?php endforeach;?>
    </table>
<?php
$js=<<<JS
 $(document).ready(function(){
        $('#myTable').DataTable();
    });
JS;
$this->registerJs($js);

