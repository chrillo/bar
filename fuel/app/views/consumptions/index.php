<h2 class="first">Listing Consumptions</h2>

<table cellspacing="0">
	<tr>
		<th>User id</th>
		<th>Item id</th>
		<th>Price</th>
		<th></th>
	</tr>

	<?php foreach ($consumptions as $consumption): ?>	<tr>

		<td><?php echo $consumption->user_id; ?></td>
		<td><?php echo $consumption->item_id; ?></td>
		<td><?php echo $consumption->price; ?></td>
		<td>
			<?php echo Html::anchor('consumptions/view/'.$consumption->id, 'View'); ?> |
			<?php echo Html::anchor('consumptions/edit/'.$consumption->id, 'Edit'); ?> |
			<?php echo Html::anchor('consumptions/delete/'.$consumption->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>		</td>
	</tr>
	<?php endforeach; ?></table>

<br />

<?php echo Html::anchor('consumptions/create', 'Add new Consumption'); ?>