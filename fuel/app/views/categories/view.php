<p>
	<strong>Label:</strong>
	<?php echo $category->label; ?></p>
<p>
	<strong>User id:</strong>
	<?php echo $category->user_id; ?></p>

<?php echo Html::anchor('categories/edit/'.$category->id, 'Edit'); ?> | 
<?php echo Html::anchor('categories', 'Back'); ?>