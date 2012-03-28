<h2 class="first">
	<?php echo Html::anchor('admin/items/create', 'Add new Item',array('class'=>'btn primary')); ?>
	<?php echo Html::anchor('admin/categories/create', 'Add new Category',array('class'=>'btn primary')); ?>
</h2>

<?php foreach ($categories as $category):?>
	<?php $items=$category->items; ?>
	
		<?php if(sizeof($items)>0):?>
		<h3 class="category-label">
			<?php echo $category->label; ?>
			<?php if($category->id>0){ ?>
			<?php echo Html::anchor('admin/categories/delete/'.$category->id, 'Remove Category', array('class'=>'btn small pull-right','onclick' => "return confirm('Are you sure?')")); ?>
			<?php echo Html::anchor('admin/categories/edit/'.$category->id, 'Edit',array('class'=>'btn small pull-right')); ?>
			
			<?php }?>
		</h3>
			
		<div class="category-items">
		
		<?php foreach ($items as $item): ?>
		 	<div class="item">
				<a href="items/edit/<?php echo $item->id; ?>" class="item-link">
					<span class="item-label"><?php echo $item->title; ?> (<?php echo $item->inventory; ?>)</span>
					<span class="item-price"><?php echo $item->price; ?>€</span>
					<span class="item-cost">/ <?php echo $item->cost; ?>€</span>
				</a>
				
					<?php echo Html::anchor('admin/items/delete/'.$item->id, 'delete', array('class'=>'danger item-delete-btn','onclick' => "return confirm('Are you sure?')")); ?>	
					
				
				
			</div>
		<?php endforeach; ?>
			<div class="clear"></div>
		</div>
	<?php endif; ?>
<?php endforeach; ?>

