<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url(); ?>public_assets/images/favicon.png">
    
	<title>Just Medical Advice Admin</title>

    <link rel="stylesheet" href="<?php echo base_url(); ?>admin_assets/css/bootstrap.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>admin_assets/font-awesome/css/font-awesome.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>admin_assets/css/style.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>admin_assets/css/style-responsive.css">

</head>

<body>
    <div id="login-page">
	<div class="container">
		<?php 
		echo form_open('admin/submit',array('class'=>'form-login'));
		echo '<h2 class="form-login-heading">Just Medical Advice Admin</h2>';
		echo '<div class="login-wrap">';
		echo validation_errors('<p style="color:red">','</p>');
		echo form_input(array('name'=>'username','class'=>'form-control','placeholder'=>'User Name','autofocus'=>'true'));
		echo '<br>';
		echo form_password(array('name'=>'password','class'=>'form-control','placeholder'=>'Password'));
		echo '<br>';
		echo '<button class="btn btn-theme btn-block" type="submit"><i class="fa fa-lock"></i> SIGN IN</button>';
		echo form_close();
		echo '</div>';
		?>
	</div>
</div>

    <script src="<?php echo base_url(); ?>admin_assets/js/jquery.js"></script>
    <script src="<?php echo base_url(); ?>admin_assets/js/bootstrap.min.js"></script>

    <!--BACKSTRETCH-->
    <!-- You can use an image of whatever size. This script will stretch to fit in any screen size.
    <script type="text/javascript" src="<?php echo base_url(); ?>admin_assets/js/jquery.backstretch.min.js"></script>
    <script>
        $.backstretch("<?php echo base_url(); ?>admin_assets/img/login-bg.jpg", {speed: 500});
    </script>-->
</body>
</html>