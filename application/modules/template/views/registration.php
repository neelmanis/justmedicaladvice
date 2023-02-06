<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="format-detection" content="telephone=no" />
	<meta name="theme-color" content="#000" />
    
    <title>Just Medical Advice</title>
    
	<link href="<?php echo base_url()?>public/images/favicon.png" rel="shortcut icon" />
    <link href="<?php echo base_url()?>public/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo base_url()?>public/css/bootstrap-select.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url()?>public/css/style.css" rel="stylesheet">
	
    <script src="<?php echo base_url()?>public/js/jquery.min.js"></script>
    <script src="<?php echo base_url()?>public/js/bootstrap.min.js"></script>
    <script>var CI_ROOT = "<?php echo base_url(); ?>";</script>
</head>

<body>
<div id="pageLoader" class="pageLoader"></div>

<?php $this->load->view('includes/home-header');
	$path = $module. '/' . $viewFile; 
	echo $this->load->view($path);  ?>
<?php $this->load->view('includes/footer');?>

<script src="<?php echo base_url()?>public/js/bootstrap-select.min.js"></script>
<script src="<?php echo base_url()?>public/js/jquery.validate.js"></script>
<script src="<?php echo base_url()?>public/js/pushy.min.js"></script>
<script src="<?php echo base_url()?>public/js/sticky-kit.min.js"></script>
<script src="<?php echo base_url()?>public/js/slick.min.js"></script>
<script src="<?php echo base_url()?>public/js/isotope.pkgd.min.js"></script>
<script src="<?php echo base_url()?>public/js/waypoints.min.js"></script>
<script src="<?php echo base_url()?>public/js/general_scripts.js"></script>
<script src="<?php echo base_url()?>public/js/sweetalert.min.js"></script>
<script src="<?php echo base_url()?>public/js/custom/step-form.js"></script>
<script src="<?php echo base_url()?>public/js/custom/<?php echo $scriptFile ?>.js"></script>
<script>
window.onload = function () {$("#pageLoader").hide(); }
</script>
<script type="text/javascript">
// Google Translate
function googleTranslateElementInit() {new google.translate.TranslateElement({pageLanguage:'en',includedLanguages:'en,es,fr,hi,mr,ru,ta,te,bn',layout:google.translate.TranslateElement.InlineLayout.SIMPLE },'google_translate_element');}
$(window).bind("load",function(){$("span:first",".goog-te-menu-value").text('English');})
</script>
<script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
</body>
</html>