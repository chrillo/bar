<p>
	<strong>Email:</strong>
	<?php echo $user->email; ?></p>
<p>
	<strong>Pin:</strong>
	<?php echo $user->pin; ?></p>
<p>
	<strong>Last login:</strong>
	<?php echo date("d.m.Y h:i",$user->last_login); ?></p>
<p>
<?php echo Html::anchor('admin/users/edit/'.$user->id, 'Edit',array('class'=>'btn')); ?>

<?php 
$rows="";
$total=0;
$orderId=-1;
$orders=0;
$time=0;
if(sizeof($consumptions)>0){

$time=floor((end($consumptions)->created_at-$consumptions[0]->created_at)/(3600*24))+1;

foreach($consumptions as $consumption){
	if($consumption->order_id!=$orderId){
		$orders++;
		$orderId=$consumption->order_id;
	}
	$total+=$consumption->price;
	$rows.="<tr>
	<td>".$consumption->title."</td>
	<td>".$consumption->price."€</td>
	<td>".date("d.m.Y h:i",$consumption->created_at)."</td>
	<td>".$consumption->order_id."</td> 
	<td>".Html::anchor('admin/users/removeconsumption/'.$user->id."/".$consumption->id, 'Remove')."</td>
	<td>".Html::anchor('admin/users/payconsumption/'.$user->id."/".$consumption->id, 'Mark paid')."</td>
	</tr>";	
}
}
?>

<div class="well">
<span class="total"><?php echo $total ?>€</span>
<?php echo Html::anchor('admin/users/payment/'.$user->id, 'Mark consumptions as paid',array('class'=>'btn danger')); ?>

</div>

<table>
<tr>
	<th>Item</th>
	<th>Total (<?php echo $total; ?>€)</th>
	<th>Time (<?php echo $time." day"; if($time!=1){echo "s";} ?>)</th>
	<th>Order Id (<?php echo $orders." order"; if($orders!=1){echo "s";} ?>)</th>
	<th></th>
	<th></th>
</tr>
<?php
echo $rows
?>
 </table>
</p>

