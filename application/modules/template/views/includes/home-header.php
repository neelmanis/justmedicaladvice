<header class="container-fluid">
	<div class="row">
    	<div class="container-fluid maxwidth">
        	<div class="container-fluid">
            	<div class="headlogo fade_anim">
                <a href="<?php echo base_url();?>"><img src="<?php echo base_url();?>public/images/jma_logo.png" title="Just Medical Advice"></a>
                </div>
                
                <div class="static_menu">
                	<div class="menubtn menu-btn menu_open"><span></span><span></span><span></span></div>
                	<?php  if(isset($isHomePage) && $isHomePage == 1){ ?>
                <a href="<?php echo base_url()?>login" class="loginbtn fade_anim"><span>LOGIN/ SIGNUP</span></a>
				<?php } ?>
                	<div class="language" data-toggle="tooltip" data-placement="auto left" title="Select Language"><div id="google_translate_element"></div></div>
                </div>
                
            </div>
        </div>
    </div>
    
    <div class="site-overlay"></div>
	<div class="nav pushy pushy-right">
		<div class="menu-btn menu_close">X</div>
		<div class="clearfix"></div>

		<ul class="menu fade_anim">
			<li><a href="<?php echo base_url()?>about-us">About Us</a></li>
			<li><a href="<?php echo base_url()?>jma-for-doctors">JMA for Doctors</a></li>
			<li><a href="<?php echo base_url()?>jma-for-members">JMA for Members</a></li>
			<!--<li><a href="<?php echo base_url()?>faq">FAQs</a></li>-->
			<li><a href="<?php echo base_url()?>contact-us">Contact Us</a></li>
		</ul>

		<ul class="social_links fade_anim">
			<li><a href="https://www.facebook.com/justmedicaladvice/" class="fb" title="Facebook"></a></li>
                    	<li><a href="javascript:;" class="tt" title="Twitter"></a></li>
                    	<li><a href="https://www.youtube.com/channel/UCSYdJHhRE_umY_vTlFgQW7Q" class="yt" title="Youtube"></a></li>
                    	<li><a href="https://www.linkedin.com/company/justmedicaladvice/" class="li" title="LinkedIn"></a></li>
                    	<li><a href="https://www.instagram.com/justmedicaladvice/" class="ig" title="Instagram"></a></li>
		</ul>
	</div>
</header>