
<?php echo Html::anchor('admin/categories/create', 'Add new Category',array('class'=>'btn primary')); ?>


<table cellspacing="0">
	<tr>
		<th>Label</th>
		<th>Items</th>
	</tr>

	<?php foreach ($categories as $category): ?>	<tr>

		<td><?php echo $category->label; ?></td>
		<td><?php echo sizeof($category->items); ?></td>
		<td>
			<?php echo Html::anchor('admin/categories/edit/'.$category->id, 'Edit'); ?> |
			<?php echo Html::anchor('admin/categories/delete/'.$category->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>
		</td>
	</tr>
	<?php endforeach; ?></table>


