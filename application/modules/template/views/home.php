<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="format-detection" content="telephone=no" />
	<meta name="theme-color" content="#000" />
    <meta name="description"  content=" Follow Online Doctors for Free, Evidence based medical advice. Get access to Medical Videos, Articles, 		Blogs, Live Webinars and Forums"/>
	
    <title> Follow Online Doctors For Free Verified Medical Advice</title>
    
	<link href="<?php echo base_url()?>public/images/favicon.png" rel="shortcut icon" />
    <link rel="apple-touch-icon" sizes="192x192" href="<?php echo base_url()?>public/images/JMA_icon.png">
    <link rel="icon" href="<?php echo base_url()?>public/images/JMA_icon.png" sizes="192x192">
    <link href="<?php echo base_url()?>public/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo base_url()?>public/css/jquery.fancybox.min.css" rel="stylesheet">
    <link href="<?php echo base_url()?>public/css/style.css" rel="stylesheet">
	
    <script src="<?php echo base_url()?>public/js/jquery.min.js"></script>
    <script src="<?php echo base_url()?>public/js/bootstrap.min.js"></script>
    <script>var CI_ROOT = "<?php echo base_url(); ?>";</script>
	<!-- Facebook Pixel Code -->
	<script>
	!function(f,b,e,v,n,t,s)
	{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
	n.callMethod.apply(n,arguments):n.queue.push(arguments)};
	if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
	n.queue=[];t=b.createElement(e);t.async=!0;
	t.src=v;s=b.getElementsByTagName(e)[0];
	s.parentNode.insertBefore(t,s)}(window,document,'script',
	'https://connect.facebook.net/en_US/fbevents.js');
	fbq('init', '576473759436392'); 
	fbq('track', 'PageView');
	</script>
	<noscript>
	<img height="1" width="1" 
	src="https://www.facebook.com/tr?id=576473759436392&ev=PageView
	&noscript=1"/>
	</noscript>
	<!-- End Facebook Pixel Code -->
</head>

<body>

<div id="pageLoader" class="pageLoader"></div>

<?php $this->load->view('includes/home-header');
	$path = $module. '/' . $viewFile; 
	echo $this->load->view($path);  ?>
<?php $this->load->view('includes/footer');?>

<script src="<?php echo base_url()?>public/js/jquery.validate.js"></script>
<script src="<?php echo base_url()?>public/js/pushy.min.js"></script>
<script src="<?php echo base_url()?>public/js/jquery.fancybox.min.js"></script>
<script src="<?php echo base_url()?>public/js/sticky-kit.min.js"></script>
<script src="<?php echo base_url()?>public/js/slick.min.js"></script>
<script src="<?php echo base_url()?>public/js/isotope.pkgd.min.js"></script>
<script src="<?php echo base_url()?>public/js/waypoints.min.js"></script>
<script src="<?php echo base_url()?>public/js/general_scripts.js"></script>
<script src="<?php echo base_url()?>public/js/sweetalert.min.js"></script>
<script src="<?php echo base_url()?>public/js/ddaccordion.js"></script>
<?php if(isset($scriptFile)){?>
<script src="<?php echo base_url()?>public/js/custom/<?php echo $scriptFile;?>.js"></script>
<?php } ?>

<script>
$(".homeSlider").slick({arrows:!1,dots:!0,infinite:!0,slidesToShow:1,slidesToScroll:1,autoplay:!0,autoplaySpeed:8e3,speed:1e3,cssEase:"ease-out",fade:!0,pauseOnHover:!1,pauseOnFocus:!1,responsive:[{breakpoint:650,settings:{arrows:!0,dots:!1,adaptiveHeight:!0}}]}),$(".mvdscroll").slick({dots:!1,infinite:!1,slidesToShow:4,slidesToScroll:4,speed:1e3,cssEase:"ease-in-out",responsive:[{breakpoint:1160,settings:{slidesToShow:3,slidesToScroll:3}},{breakpoint:900,settings:{slidesToShow:2,slidesToScroll:2}},{breakpoint:650,settings:{slidesToShow:1,slidesToScroll:1}}]}),$(".fvscroll").slick({dots:!1,infinite:!1,slidesToShow:3,slidesToScroll:3,speed:1e3,cssEase:"ease-in-out",adaptiveHeight:!0,responsive:[{breakpoint:991,settings:{slidesToShow:2,slidesToScroll:2}},{breakpoint:650,settings:{slidesToShow:1,slidesToScroll:1}}]}),$(".testimonials_scroll").slick({arrows:!1,dots:!0,infinite:!0,slidesToShow:1,slidesToScroll:1,autoplay:!0,autoplaySpeed:7e3,speed:1e3,cssEase:"ease-in-out",adaptiveHeight:!0,pauseOnHover:!0,pauseOnFocus:!1}),$(".articles_main").isotope({itemSelector:".article_item"}),$(".newsletterForm input").focus(function(){$(".btn").addClass("change")}),$(".newsletterForm input").blur(function(){$(".btn").removeClass("change")}),function(s){"use strict";s.fn.counterUp=function(e){var t=s.extend({time:400,delay:10},e);return this.each(function(){var r=s(this),u=t;r.waypoint(function(){var e=[],t=u.time/u.delay,s=r.text(),o=/[0-9]+,[0-9]+/.test(s);s=s.replace(/,/g,"");/^[0-9]+$/.test(s);for(var i=/^[0-9]+\.[0-9]+$/.test(s),n=i?(s.split(".")[1]||[]).length:0,a=t;1<=a;a--){var l=parseInt(s/t*a);if(i&&(l=parseFloat(s/t*a).toFixed(n)),o)for(;/(\d+)(\d{3})/.test(l.toString());)l=l.toString().replace(/(\d+)(\d{3})/,"$1,$2");e.unshift(l)}r.data("counterup-nums",e),r.text("0");r.data("counterup-func",function(){r.text(r.data("counterup-nums").shift()),r.data("counterup-nums").length?setTimeout(r.data("counterup-func"),u.delay):(r.data("counterup-nums"),r.data("counterup-nums",null),r.data("counterup-func",null))}),setTimeout(r.data("counterup-func"),u.delay)},{offset:"95%",triggerOnce:!0})})}}(jQuery),$(".counter_1").counterUp({delay:10,time:1200}),$(".counter_2").counterUp({delay:10,time:1400}),$(".counter_3").counterUp({delay:10,time:1600}),$(".ft_right").hover(function(){$(".fromto_container").addClass("ft_slide")}),$(".ft_left").hover(function(){$(".fromto_container").removeClass("ft_slide")});

ddaccordion.init({
		headerclass: "faq_Question",
		contentclass: "faq_Answer",
		revealtype: "click",
		mouseoverdelay:0,
		collapseprev: true,
		defaultexpanded: [],
		onemustopen: false,
		scrolltoheader: true,
		animatedefault: false,
		persiststate: false,
		toggleclass: ["", "active"],
		togglehtml: ["prefix", "", ""],
		animatespeed: "fast",
		oninit:function(headers, expandedindices){},
		onopenclose:function(header, index, state, isuseractivated){}
});

$( document ).ready(function() {
	$("#pageLoader").hide();
});
//window.onload = function () {$("#pageLoader").hide(); }
</script>
<script type="text/javascript">
// Google Translate
function googleTranslateElementInit() {new google.translate.TranslateElement({pageLanguage:'en',includedLanguages:'en,es,fr,hi,mr,ru,ta,te,bn',layout:google.translate.TranslateElement.InlineLayout.SIMPLE },'google_translate_element');}
$(window).bind("load",function(){$("span:first",".goog-te-menu-value").text('English');})
</script>
<script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
</body>
</html>