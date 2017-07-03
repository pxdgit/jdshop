<?php
/**
* @var $this \yii\web\View
*/
$this->registerCssFile('http://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css');
$this->registerJsFile('http://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js',['depends'=>\yii\web\JqueryAsset::className()]);
?>


    <table class="table table-bordered table-hover table-striped" id="myTable">
        <thead>
        <tr>
            <th>订单ID</th>
            <th>收货人</th>
            <th>收货地址</th>
            <th>联系电话</th>
            <th>配送方式</th>
            <th>支付方式</th>
            <th>状态</th>
            <th>创建时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model as $order):?>
        <tr>
            <td><?=$order->id?></td>
            <td><?=$order->name?></td>
            <td><?=$order->province.' '.$order->city.' '.$order->area.' '.$order->address?></td>
            <td><?=$order->tel?></td>
            <td><?=$order->delivery_name?></td>
            <td><?=$order->payment_name?></td>
            <td><?=\backend\models\order::$allstatus[$order->status]?></td>
            <td><?=date('Y-m-d H:i:s',$order->create_time)?></td>
            <td>
                <?=\yii\bootstrap\Html::a('订单详情',['order/show','id'=>$order->id],['class'=>'btn btn-danger btn-xs']);?>
                <?=$order->status==2?\yii\bootstrap\Html::button('发货',['class'=>'btn btn-warning btn-xs status_btn']):''?>
            </td>
        </tr>
        </tbody>
        <?php endforeach;?>
    </table>
<?php
$js=<<<JS
 $(document).ready(function(){
        $('#myTable').DataTable();
    });
$('.status_btn').click(function() {
    var id=$(this).closest('tr').find('td:first').text();
    $(this).closest('tr').find('td:eq(6)').text('已发货');
    $.get("edit",{id:id});  
    alert('修改订单状态成功');
    $(this).remove();
 
})
JS;
$this->registerJs($js);

?>

