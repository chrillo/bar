<?php echo Form::open(); ?>
	<p>
		<?php echo Form::label('Username', 'username'); ?>: 
<?php echo Form::input('username', Input::post('username', isset($user) ? $user->username : '')); ?>
	</p>
	<p>
		<?php echo Form::label('Firstname', 'firstname'); ?>: 
<?php echo Form::input('firstname', Input::post('firstname', isset($user) ? $user->firstname : '')); ?>
	</p>
	<p>
		<?php echo Form::label('Lastname', 'lastname'); ?>: 
<?php echo Form::input('lastname', Input::post('lastname', isset($user) ? $user->lastname : '')); ?>
	</p>
	<p>
		<?php echo Form::label('Password', 'password'); ?>: 
<?php echo Form::password('password', Input::post('password', '')); ?>
	</p>
	<p>
		<?php echo Form::label('Email', 'email'); ?>: 
<?php echo Form::input('email', Input::post('email', isset($user) ? $user->email : '')); ?>
	</p>
	<p>
				<?php echo Form::label('Pin', 'pin'); ?>: 
<?php echo Form::input('pin', Input::post('pin', isset($user) ? $user->pin : '')); ?>
	<p>
		<?php echo Form::label('Status', 'status'); ?>: 
		<?php 
		$status='active';
		if(isset($user)){$status=$user->status;}
		echo Form::select('status',$status, array('active','disabled'));
	 ?>
	</p>
<?php echo Form::hidden('profile_fields', ''); ?>


	<div class="well">
		<?php echo Form::submit('save','Save',array('class'=>'btn primary')); ?>	
		<?php echo Html::anchor('admin/users', 'Back',array('class'=>'btn')); ?>
	</div>

<?php echo Form::close(); ?>