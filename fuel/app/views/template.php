<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php echo $title; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<?php  echo Asset::css('bootstrap.min.css');  ?>
	
	<?php //<link rel="stylesheet/less" href="/bar/public/assets/css/bootstrap/bootstrap.less"> ?>
	<?php echo Asset::css('style.css'); ?>
	<?php echo Asset::js('jquery.min.js'); ?>
	<?php // echo Asset::js('less.min.js'); ?>

</head>
<body>
	<div class="container">
		
		<?php
		
		if(Auth::check()){ 
		?>
		<div class='topbar'>
			<div class='fill'>
				<div class='container'>
					<?php echo Html::anchor('admin/', 'Dashboard',array('class'=>'brand')); ?>
					<ul>
						<li><?php echo Html::anchor('admin/users', 'Users'); ?></li>
						<li><?php echo Html::anchor('admin/items', 'Items'); ?></li>
					</ul>
					<p class="pull-right"><?php echo Html::anchor('admin/logout', 'Log out'); ?></p>
				</div>
			</div>
		</div>
		<?php } ?>
		 <h1><?php echo $title; ?></h1>
		
		<?php if (Session::get_flash('notice')): ?>
			<div class="notice"><p><?php echo implode('</p><p>', (array) Session::get_flash('notice')); ?></div></p>
		<?php endif; ?>

		<?php echo $content; ?>

		
	</div>
</body>
</html>
