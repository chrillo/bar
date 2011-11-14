<p>
	<strong>Title:</strong>
	<?php echo $item->title; ?></p>
<p>
	<strong>Price:</strong>
	<?php echo $item->price; ?>€</p>


<?php echo Html::anchor('admin/items/edit/'.$item->id, 'Edit'); ?> | 
<?php echo Html::anchor('admin/items', 'Back'); ?>