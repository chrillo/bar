<?php echo Form::open(); ?>
	<p>
		<?php echo Form::label('User id', 'user_id'); ?>
<?php echo Form::input('user_id', Input::post('user_id', isset($consumption) ? $consumption->user_id : '')); ?>
	</p>
	<p>
		<?php echo Form::label('Item id', 'item_id'); ?>
<?php echo Form::input('item_id', Input::post('item_id', isset($consumption) ? $consumption->item_id : '')); ?>
	</p>
	<p>
		<?php echo Form::label('Price', 'price'); ?>
<?php echo Form::input('price', Input::post('price', isset($consumption) ? $consumption->price : '')); ?>
	</p>

	<div class="actions">
		<?php echo Form::submit(); ?>	</div>

<?php echo Form::close(); ?>