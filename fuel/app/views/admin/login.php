<div class="login">
<div class="well">
<?php if(isset($errors)) {?>

<div class='alert-message error'><?php echo $errors ?></div>

<? } ?>
<?php echo Form::open('/admin/login'); ?>
<p>
    
    <?php echo Form::input('username', NULL, array('size' => 30,'placeholder'=>'Username')); ?>
</p><p>
    
    <?php echo Form::password('password', NULL, array('size' => 30,'placeholder'=>'Password')); ?>
</p><p>

    <?php echo Form::submit('login', 'Login',array('class'=>'btn primary large')); ?>
</p>
</div>