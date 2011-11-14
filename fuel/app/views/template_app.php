<!DOCTYPE html>
<html>
<head>
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<link rel="apple-touch-icon" href="<?php echo Asset::find_file('app-icon.png', 'img'); ?>"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta charset="utf-8">
	<title><?php echo $title; ?></title>
	 
	 <?php echo Asset::css('style.css'); ?>
	 <?php echo Asset::css('style.app.css'); ?>
	 
	 <?php echo Asset::js('jquery.min.js'); ?>
	 <?php echo Asset::js('underscore.js'); ?>
	 <?php echo Asset::js('backbone.js'); ?>
	 <?php echo Asset::js('iCanHaz-min.js'); ?>
	 <?php echo Asset::js('keymaster.min.js'); ?>
	 <?php echo Asset::js('app.js'); ?>
	
	 
</head>
<body>
	<?php echo $content; ?>
</body>
</html>
