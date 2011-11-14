<p>
	<strong>User id:</strong>
	<?php echo $consumption->user_id; ?></p>
<p>
	<strong>Item id:</strong>
	<?php echo $consumption->item_id; ?></p>
<p>
	<strong>Price:</strong>
	<?php echo $consumption->price; ?></p>

<?php echo Html::anchor('consumptions/edit/'.$consumption->id, 'Edit'); ?> | 
<?php echo Html::anchor('consumptions', 'Back'); ?>