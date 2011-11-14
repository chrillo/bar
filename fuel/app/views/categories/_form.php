<?php echo Form::open(); ?>
	<p>
		<?php echo Form::label('Label: ', 'label'); ?>
<?php echo Form::input('label', Input::post('label', isset($category) ? $category->label : '')); ?>
	</p>
	<p>
		<?php echo Form::label('Order:', 'order'); ?> 
<?php echo Form::input('order', Input::post('order', isset($category) ? $category->order : '')); ?>
	</p>
		<div class="well">
		<?php echo Form::submit('save','Save',array('class'=>'btn primary')); ?>	
		<?php echo Html::anchor('admin/items', 'Back',array('class'=>'btn')); ?>
		</div>

<?php echo Form::close(); ?>