<?php
	$userData = $this->session->userdata('userData');
	$userId = $userData['userId'];
	$type = $userData['type'];
	$image = $userData['image'];
	$name = $userData['name']; 
	
	$notifications = Modules::run('member/getMemberNotifications');
?>

<header class="container-fluid">
    <div class="row">
    	<div class="container-fluid maxwidth">
        	<div class="container-fluid">
            	<div class="headlogo fade_anim">
                <a href="<?php echo base_url();?>dashboard/member"><img src="<?php echo base_url();?>public/images/jma_logo.png" title="Just Medical Advice"></a>
                </div>
                
                <ul class="myaccount_btns">
                	<li><div class="language" data-toggle="tooltip" data-placement="auto left" title="Select Language"><div id="google_translate_element"></div></div></li>
                	<li class="notification_btn show"><button data-toggle="fpopover" data-container="header" data-placement="bottom" data-trigger="click" data-html="true" id="notification">
					<?php if($notifications !== 'No Data'){ ?>
					<span class="badge"> <?php echo sizeof($notifications);?></span>
					<?php } ?>
					</button></li>
                	<li class="mails_btn"><a href="<?php echo base_url();?>message/inbox"><span class="badge">0</span></a></li>
                	<li class="profile_btn"><button data-toggle="fpopover" data-placement="bottom" data-html="true" id="profile"><img src="<?php echo base_url().$image;?>"></button>
                    </li>
                	<li class="mymenu_btn"><a class="menu-btn menu_open"><span></span><span></span><span></span></a></li>
                </ul>
                
                <div class="site-overlay"></div>
                <div class="pushy nonpushy pushy-right">
                <div class="menu-btn menu_close">X</div>
                <div class="clearfix"></div>
                
                <div class="menu_logo"><img src="<?php echo base_url()?>public/images/jma_logo.png" title="Just Medical Advice" style="width:100%;display:block;"></div>
                
                <ul class="myaccount_menus fade_anim">
					<li class="ic_menu1 <?php if(isset($home) && $home == 1){echo 'active';}?>"><a href="<?php echo base_url()?>dashboard/member"><span></span> Dashboard</a></li>
					<li class="ic_menu2 <?php if(isset($blog) && $blog == 1){echo 'active';}?>"><a href="<?php echo base_url()?>blog/listAll"><span></span> Blogs</a></li>
					<li class="ic_menu3 <?php if(isset($media) && $media == 1){echo 'active';}?>"><a href="<?php echo base_url()?>media/listall"><span></span> Videos/Audios</a></li>
					<li class="ic_menu4 <?php if(isset($forum) && $forum == 1){echo 'active';}?>"><a href="<?php echo base_url()?>forum/listall"><span></span> Forums</a></li>
					<li class="ic_menu5 <?php if(isset($webinar) && $webinar == 1){echo 'active';}?>"><a href="<?php echo base_url()?>webinar/listall"><span></span> Webinars</a></li>
					<li class="ic_menu6 <?php if(isset($docList) && $docList == 1){echo 'active';}?>"><a href="<?php echo base_url()?>member/followedDoctors"><span></span> Followed Doctors</a></li>
				</ul>
              </div>
                
            </div>
        </div>
    </div>
    
    <!-- My Account popover -->    
    <div class="hide" id="fpopover-content-profile">
    <div class="myaccount_box">
    	<div class="myaccount_Name txtdark">
				<span style="font-size:12px;">Welcome,</span><br>
				<strong class="txtblue"><?php echo $name?></strong>
			</div>
			<ul class="fade_anim">
				<li><a href="<?php echo base_url()?>member/myProfile">My Profile</a></li>
				<li><a href="<?php echo base_url()?>member/changePassword">Change Password</a></li>
				<li><a href="<?php echo base_url()?>member/logout">Logout</a></li>
			</ul>
    </div>
    </div>
    
    <!-- My Notification popover -->   
	<div class="hide" id="fpopover-content-notification">
		<div class="mynotif_box">
			<div class="txtdark" style="padding:5px 10px;border-bottom:1px solid #eee;">Notifications</div>
			<ul class="fade_anim">
				<?php 
					if($notifications !== "No Data"){ 
					foreach($notifications as $result){ ?>
				<li>
					<a href="<?php echo $result->url; ?>"><?php echo $result->notification;?>
					</a>
				</li>
				<?php }  } ?>
			</ul>
			<a href="javascript:;" class="viewall bluebtn">See All</a>
		</div>
    </div>
</header>