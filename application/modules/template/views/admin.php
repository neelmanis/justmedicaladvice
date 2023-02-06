<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url(); ?>public/images/favicon.png">

		<meta name="description" content="">
		<title>Just Medical Advice Admin</title>
		
		<link rel="stylesheet" href="<?php echo base_url(); ?>admin_assets/css/bootstrap.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>admin_assets/font-awesome/css/font-awesome.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>admin_assets/css/style-responsive.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>admin_assets/css/table-responsive.css">
		<link href="<?php echo  base_url();?>admin_assets/css/select2-bootstrap.css" rel='stylesheet'/>
		<link href="<?php echo  base_url();?>admin_assets/css/select2.css" rel='stylesheet'/>
	    <link href="<?php echo base_url()?>public/css/jquery.fancybox.min.css" rel="stylesheet">
	    <link href="<?php echo base_url()?>public/css/bootstrap-datetimepicker.css" rel="stylesheet">
		<link rel="stylesheet" href="<?php echo base_url(); ?>admin_assets/css/style.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>admin_assets/css/custom-style.css">
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/b-1.5.2/b-flash-1.5.2/b-html5-1.5.2/b-print-1.5.2/datatables.min.css"/>
		
		<script src="<?php echo base_url(); ?>admin_assets/js/jquery.min.js"></script>
		<script src="<?php echo base_url(); ?>admin_assets/js/bootstrap.min.js"></script>
		<script src="<?php echo  base_url();?>admin_assets/js/select2.min.js" ></script>
		<script> var baseUrl = '<?php echo base_url()?>'</script>
  </head>

  <body>
	<div id="pageLoader" class="pageLoader"></div>
	<section id="container" >
		  <!-- **********************************************************************************************************************************************************
		  TOP BAR CONTENT & NOTIFICATIONS
		  *********************************************************************************************************************************************************** -->
		<!--header start-->
		<header class="header black-bg">
            <div class="sidebar-toggle-box">
				<div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
            </div>
            
			<!--logo start-->
			<a href="<?php echo base_url(); ?>dashboard/adminHome" class="logo"><b>Menu</b></a>
            <!--logo end-->
            
			<div class="nav notify-row hide" id="top_menu">
                <!--  notification start -->
                <ul class="nav top-menu">
                    <!-- settings start -->
                    <!-- inbox dropdown end -->
                </ul>
                <!--  notification end -->
            </div>
            <div class="top-menu">
				<ul class="nav pull-right top-menu">
					<li><?php echo anchor('admin/logout', 'Log Out',array('class'=>'logout')); ?></li>
				</ul>
            </div>
        </header>
		<!--header end-->
 
		<!-- **********************************************************************************************************************************************************
												MAIN SIDEBAR MENU
		*********************************************************************************************************************************************************** -->
		
		<!--sidebar start-->
		<aside>
			<div id="sidebar"  class="nav-collapse ">
				<!-- sidebar menu start-->
				<ul class="sidebar-menu" id="nav-accordion">
                   
					<?php  $adminData = $this->session->userdata('adminData');
						$userId = $adminData['userId'];
						$rights = $adminData['rights'];
						$admin = $adminData['isAdmin'];
						$arrays = explode(",",$rights); 
					?>
		 
					<li class="mt">
						<a <?php if($page=="home"){?>class="active" <?php }?> href="<?php echo base_url();?>dashboard/admin">
                        <i class="fa fa-dashboard"></i>
                        <span>Dashboard</span></a>
					</li>
					
					<?php if($admin == 0) { ?>
					<li class="sub-menu">
						<a <?php if($page=="listAdmin" || $page=="addAdmin" || $page=="editAdmin")echo "class='active'";?> href="javascript:;" >
                        <i class="fa fa-desktop"></i><span>Manage Admin</span></a>
						<ul class="sub">
							<li <?php if($page=="listAdmin"){?>class="active" <?php }?>><?php echo anchor('admin/list-admin','List All'); ?></li>
						</ul>
					</li>
					<?php } ?>
					
					<?php if(in_Array("1",$arrays)){?>
					<li class="sub-menu">
						<a <?php if($page=="listMember" || $page=="viewMember" || $page=="editMember")echo "class='active'";?> href="javascript:;" >
                        <i class="fa fa-desktop"></i><span>Manage Member</span></a>
						<ul class="sub">
							<li <?php if($page=="listMember"){?>class="active" <?php }?>><?php echo anchor('member/list-member','List All'); ?></li>
						</ul>
					</li>
					<?php } ?>	

					<?php if(in_Array("2",$arrays)){?>
					<li class="sub-menu">
						<a <?php if($page=="listDoctor" || $page=="viewDoctor" || $page=="addDoctor")echo "class='active'";?> href="javascript:;" >
                        <i class="fa fa-desktop"></i><span>Manage Doctor</span></a>
						<ul class="sub">
							<li <?php if($page=="listDoctor"){?>class="active" <?php }?>><?php echo anchor('doctor/list-doctor','List All'); ?></li>
							<!--<li <?php if($page=="listDoctor"){?>class="active" <?php }?>><?php echo anchor('doctor/list-doctor-request','List Request'); ?></li>
							<li <?php if($page=="listDoctor"){?>class="active" <?php }?>><?php echo anchor('doctor/list-featured-doctors','List Featured Doctors'); ?></li>-->
						</ul>
					</li>
					<?php } ?>	


					<?php if(in_Array("3",$arrays)){?>
					<li class="sub-menu">
						<a <?php if($page=="listSpeciality" || $page=="addSpeciality" || $page=="editSpeciality")echo "class='active'";?> href="javascript:;" >
							<i class="fa fa-desktop"></i><span>Manage Speciality</span>
						</a>
						<ul class="sub">
							<li <?php if($page=="listsp"){?> class="active" <?php }?>><?php echo anchor('speciality/list-speciality','List All'); ?></li>
						</ul>
					</li>
					<?php } ?>	

					<?php if(in_Array("4",$arrays)){?>
					<li class="sub-menu">
						<a <?php if($page=="listCategory" || $page=="addCategory" || $page=="editCategory")echo "class='active'";?> href="javascript:;" >
							<i class="fa fa-desktop"></i><span>Manage Category</span>
						</a>
						<ul class="sub">
							<li <?php if($page=="listCategory"){?> class="active" <?php }?>><?php echo anchor('category/listByFilter/1','List All'); ?></li>
						</ul>
					</li>
					<?php } ?>	

					<?php if(in_Array("5",$arrays)){?>
					<li class="sub-menu">
						<a <?php if($page=="addBlog" || $page=="listBlog" || $page=="viewBlog" || $page=="editBlog" || $page=="blogFlagComments" )echo "class='active'";?> href="javascript:;" >
							<i class="fa fa-desktop"></i><span>Manage Blog</span>
						</a>
						<ul class="sub">
							<li <?php if($page=="listBlog"){?>class="active" <?php }?>><?php echo anchor('blog/list-blog','List All'); ?></li>
							<li <?php if($page=="blogFlagComments"){?>class="active" <?php }?>><?php echo anchor('blog/flag-comments','Flag Comments'); ?></li>
						</ul>
					</li>
					<?php } ?>	

					<?php if(in_Array("6",$arrays)){?>
					<li class="sub-menu">
						<a <?php if($page=="addMedia" || $page=="listMedia" || $page=="viewMedia" || $page=="editMedia" || $page=="mediaFlagComments")echo "class='active'";?> href="javascript:;" >
							<i class="fa fa-desktop"></i><span>Manage Video/Audio</span>
						</a>
						<ul class="sub">
							<li <?php if($page=="listMedia"){?>class="active" <?php }?>><?php echo anchor('media/list-media','List All'); ?></li>
							<li <?php if($page=="mediaFlagComments"){?>class="active" <?php }?>><?php echo anchor('media/flag-comments','Flag Comments'); ?></li>
						</ul>
					</li>
					<?php } ?>	
					
					<?php if(in_Array("7",$arrays)){?>
					<li class="sub-menu">
						<a <?php if($page=="listForum" || $page=="addForum" || $page=="editForum" || $page=="viewForum" || $page=='forumFlagComments')echo "class='active'";?> href="javascript:;" >
							<i class="fa fa-desktop"></i><span>Manage Forum</span>
						</a>
						<ul class="sub">
							<li <?php if($page=="listForum"){?> class="active" <?php }?>><?php echo anchor('forum/list-forum','List All'); ?></li>
							<li <?php if($page=="forumFlagComments"){?> class="active" <?php }?>><?php echo anchor('forum/flag-comments','Flag Comments'); ?></li>
						</ul>
					</li>
					<?php } ?>
					
					<?php if(in_Array("8",$arrays)){?>
					<li class="sub-menu hide">
						<a <?php if($page=="listWebinar" || $page=="addWebinar" || $page=="editWebinar" || $page=="viewWebinar")echo "class='active'";?> href="javascript:;" >
							<i class="fa fa-desktop"></i><span>Manage Webinar</span>
						</a>
						<ul class="sub">
							<li <?php if($page=="listWebinar"){?> class="active" <?php }?>><?php echo anchor('webinar/list-webinar','List All'); ?></li>
						</ul>
					</li>
					<?php } ?>

					<?php if(in_Array("9",$arrays)){?>
					<li class="sub-menu">
						<a <?php if($page=="listDegree" || $page=="addDegree" || $page=="editDegree")echo "class='active'";?> href="javascript:;" >
							<i class="fa fa-desktop"></i><span>Manage Degree</span>
						</a>
						<ul class="sub">
							<li <?php if($page=="listDegree"){?> class="active" <?php }?>><?php echo anchor('degree/list-degree','List All'); ?></li>
						</ul>
					</li>
					<?php } ?>
	
					<?php if(in_Array("10",$arrays)){?>	
					<li class="sub-menu">
						<a <?php if($page=="country" || $page=="state" || $page=="city")echo "class='active'";?> href="javascript:;" >
							<i class="fa fa-desktop"></i><span>Manage Locations</span>
						</a>
						<ul class="sub">
							<li <?php if($page=="country"){?>class="active" <?php }?>><?php echo anchor('location/country-list','List All Countries'); ?></li>
							<li <?php if($page=="state"){?>class="active" <?php }?>><?php echo anchor('location/state-list','List All States'); ?></li>
							<li <?php if($page=="city"){?>class="active" <?php }?>><?php echo anchor('location/city-list/101','List All Cities'); ?></li>
						</ul>
					</li>
					<?php } ?>

					<?php if(in_Array("11",$arrays)){?>
					<li class="sub-menu">
						<a <?php if($page=="listBanner" || $page=="addBanner" || $page=="editBanner" || $page=="viewBanner")echo "class='active'";?> href="javascript:;" >
							<i class="fa fa-desktop"></i><span>Manage Banner</span>
						</a>
						<ul class="sub">
							<li <?php if($page=="listBanner"){?> class="active" <?php }?>><?php echo anchor('banner/list-banner','List All'); ?></li>
						</ul>
					</li>
					<?php } ?>
					
					<?php if(in_Array("12",$arrays)){?>
					<li class="sub-menu hide">
						<a <?php if($page=="homepage")echo "class='active'";?> href="javascript:;" >
							<i class="fa fa-desktop"></i><span>Manage Home Page</span>
						</a>
						<ul class="sub">
							<li <?php if($page=="homepage"){?> class="active" <?php }?>><?php echo anchor('banner/list-banner','List All'); ?></li>
						</ul>
					</li>
					<?php } ?>

					<?php if(in_Array("13",$arrays)){?>
					<li class="sub-menu">
						<a <?php if($page=="listEvents" || $page=="addEvents" || $page=="editEvents" || $page=="viewEvents")echo "class='active'";?> href="javascript:;" >
							<i class="fa fa-desktop"></i><span>Manage Events</span>
						</a>
						<ul class="sub">
							<li <?php if($page=="listEvents"){?> class="active" <?php }?>><?php echo anchor('event/list-event','List All'); ?></li>
						</ul>
					</li>
					<?php } ?>

					<?php if(in_Array("14",$arrays)){?>
					<li class="sub-menu hide">
						<a <?php if($page=="listContactUs")echo "class='active'";?> href="javascript:;" >
							<i class="fa fa-desktop"></i><span>Manage Contact Us</span>
						</a>
						<ul class="sub">
							<li <?php if($page=="listContactUs"){?> class="active" <?php }?>><?php echo anchor('contactus/list-contact-us','List All'); ?></li>
						</ul>
					</li>
					<?php } ?>
					
					<?php if(in_Array("15",$arrays)){?>	
					<li class="sub-menu">
						<a <?php if($page=="testimoniallist" || $page=="testimonial")echo "class='active'";?> href="javascript:;" >
							<i class="fa fa-desktop"></i><span>Manage Testimonials</span>
						</a>
						<ul class="sub">
							<li <?php if($page=="testimoniallist"){?>class="active" <?php }?>><?php echo anchor('testimonial/listall','List All'); ?></li>
						</ul>
					</li>
					<?php } ?>

					<?php if(in_Array("16",$arrays)){?>	
					<li class="sub-menu">
						<a <?php if($page=="listFAQ" || $page=="addFAQ" || $page=="editFAQ" || $page=="viewFAQ")echo "class='active'";?> href="javascript:;" >
							<i class="fa fa-desktop"></i><span>Manage FAQ</span>
						</a>
						<ul class="sub">
							<li <?php if($page=="listFAQ"){?>class="active" <?php }?>><?php echo anchor('faq/list-faq','List All'); ?></li>
						</ul>
					</li>
					<?php } ?>

					<?php if(in_Array("17",$arrays)){?>	
					<li class="sub-menu">
						<a <?php if($page=="subscription")echo "class='active'";?> href="javascript:;" >
							<i class="fa fa-desktop"></i><span>Manage Subscribers</span>
						</a>
						<ul class="sub">
							<li <?php if($page=="subscription"){?>class="active" <?php }?>><?php echo anchor('subscription/','List All'); ?></li>
						</ul>
					</li>
					<?php } ?>
				</ul>
				<!-- sidebar menu end-->
			</div>
		</aside>
		<!--sidebar end-->
      
		<!-- **********************************************************************************************************************************************************
															MAIN CONTENT
		*********************************************************************************************************************************************************** -->
		
		<!--main content start-->
		<section id="main-content">
			<section class="wrapper site-min-height">
				<div class="row mt">
					<div class="col-lg-12">
						<?php 
							if(!isset($module)){
								$module = $this->uri->segment(1);
							}

							if(!isset($viewFile)){
								$viewFile = $this->uri->segment(2);
							}

							if( $module != '' && $viewFile != '' ){
								$path = $module. '/' . $viewFile;
								echo $this->load->view($path);
							}
						?>  
					</div>  
				</div>
			</section>
		</section>

	</section>

    <!-- js placed at the end of the document so the pages load faster -->
	
	<script src="<?php echo  base_url();?>public/js/bootstrap-datetimepicker.js" ></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/b-1.5.2/b-flash-1.5.2/b-html5-1.5.2/b-print-1.5.2/datatables.min.js"></script>
	<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>admin_assets/resources/syntax/shCore.js"></script>
	<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>admin_assets/resources/demo.js"></script>
	<script src="<?php echo base_url()?>public/js/jquery.fancybox.min.js"></script>
	<script src="<?php echo base_url()?>public/js/sweetalert.min.js"></script>
    <script class="include" type="text/javascript" src="<?php echo base_url(); ?>admin_assets/js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="<?php echo base_url(); ?>admin_assets/js/jquery.scrollTo.min.js"></script>
    <script src="<?php echo base_url(); ?>admin_assets/js/jquery.nicescroll.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>admin_assets/js/common-scripts.js"></script>
    <script src="<?php echo base_url(); ?>admin_assets/js/share-script.js"></script>
	<script>
		window.onload = function () {$("#pageLoader").hide(); }
	</script>
	</body>
</html>