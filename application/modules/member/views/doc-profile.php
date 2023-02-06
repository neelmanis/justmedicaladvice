<div class="container-fluid main_container">
	<div class="row">
		<div class="container-fluid dashboard_bg">    
			<div class="row searchbar_container">
				<div class="searchbar_box">
					<div class="container-fluid maxwidth">
						<div class="container-fluid">
							<form style="margin-bottom:0;">
								<div class="input-group searchbar_holder">
									<input class="form-control" placeholder="Search blogs, videos, forums, doctors...">
									<div class="input-group-btn"><button class="btn bluebtn">Search</button></div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="container-fluid maxwidth">
					<div class="container-fluid">    	
						<div class="doclist_item fade_anim">
							<div class="doc_profile_table">
								
								<!-- Docotr Details -->
								<div class="col-sm-8 doc_details">
									<input type="hidden" id="docId" value="<?php echo $doctor['regId'];?>" />
									<div class="doc_pic"><img src="<?php echo $doctor['image'];?>"></div>
									<div class="doc_info_box">
										<div class="doc_name"><h2 class="txtdark"><?php echo $doctor['name'];?></h2> 
										<?php if($doctor['featured'] == 1){?>
										<img src="<?php echo base_url();?>public/images/approved_tag.png" class="verified_tag" data-toggle="tooltip" data-title="Just Medical Advice Verified Doctor">
										<?php } ?>
										</div>
										<ul class="dd_creds">
										<li><span class="ddc_icon ic_1"></span> <?php echo $doctor['degree'];?> - <?php echo $doctor['speciality']; ?></li>
										<li><span class="ddc_icon ic_3"></span> <?php echo $doctor['experience'];?> yrs in practice</li>
										<li><span class="ddc_icon ic_2"></span> <?php echo $doctor['city'];?></li>
										</ul>
									</div>
									<div class="clearfix"></div> 
								</div>
									
								<div class="col-sm-4 doc_stats">
								<table>
									<tr><td align="right"><strong><?php echo $doctor['followedMem']; ?></strong></td><td>Followers</td></tr>
									<tr><td align="right"><strong><?php echo $doctor['thanks']; ?></strong></td><td>Thanks recieved</td></tr>
									<tr><td align="right"><strong><?php echo $doctor['answerCount']; ?></strong></td><td>Forums answered</td></tr>
									<tr><td align="right"><strong><?php echo $doctor['feedbackCount']; ?></strong></td><td>Feedbacks</td></tr>
								</table>
								</div>
							<div class="clearfix"></div>
							</div>
							
							<div class="doc_activity_btns">
								<div class="col-sm-8">
									<?php $followedDocs = Modules::run('member/getDocList');
										$id = $doctor['regId'];
										$base_64 = $id . str_repeat('=', strlen($id) % 4);
										$docId = base64_decode($base_64);	
										if($followedDocs !== 'No Data' && in_array($docId,$followedDocs)){
									?>
									<button id="<?php echo $doctor['regId']; ?>" class="bluebtn dcb_btn unfollow"><span class="dcb_icon ic_1w"></span> Following</button>
									<?php }else{ ?>
									<button id="<?php echo $doctor['regId']; ?>" class="bluebtn dcb_btn follow"><span class="dcb_icon ic_1w"></span> Follow Doctor </button>
									<?php } ?>
									<a href="<?php echo base_url();?>message/contact-doctor/<?php echo $doctor['regId']; ?>" class="dcb_btn"><span class="dcb_icon ic_2w"></span> Contact Doctor</a>
									<a href="javascript:;" class="dcb_btn openFeedback"><span class="dcb_icon ic_4w"></span> Give Feedback</a>
								</div>
								<div class="col-sm-4">Share: 
									<ul class="social_links fade_anim">
									<li><a href="javascript:;" onclick="window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(location.href),'facebook-share-dialog','width=626,height=436');return false;" class="fb" title="Facebook"></a></li>
									<li><a href="javascript:;" onclick="window.open('http://twitter.com/share?text=justmedicaladvice&url='+encodeURIComponent(location.href),'facebook-share-dialog','width=626,height=436');return false;" class="tt" title="Twitter"></a></li>
									<li><a href="javascript:;" onclick="window.open('http://www.linkedin.com/shareArticle?mini=true&url='+encodeURIComponent(location.href),'facebook-share-dialog','width=626,height=436');return false;" class="li" title="LinkedIn"></a></li>
									</ul>
								</div>
								<div class="clearfix"></div> 
							</div>
						</div>
        
						<div class="container-fluid maxwidth fade_anim onscroll_profile">
							<div class="row">        
								<div class="col-md-6">
									<div class="doc_pic"><a href="javascript:;"><img src="<?php echo $doctor['image'];?>"></a></div>
									<div class="doc_info_box">
										<div class="doc_name"><h2 class="txtdark"><?php echo $doctor['name'];?></h2> 
										<?php if($doctor['featured'] == 1){?>
										<img src="<?php echo base_url();?>public/images/approved_tag.png" class="verified_tag" data-toggle="tooltip" data-title="Just Medical Advice Verified Doctor">
										<?php } ?>
										</div>
										<ul class="dd_creds">
										<li><span class="ddc_icon ic_1"></span> <?php echo $doctor['degree'];?> - <?php echo $doctor['speciality'];?></li>
										</ul>
									</div>
								</div>
								<div class="col-md-6 doc_activity_btns text-center" style="background:#fff;padding:7px 0 0;">
									<?php if($followedDocs !== 'No Data' && in_array($docId,$followedDocs)){ ?>
									<button id="<?php echo $doctor['regId']; ?>" class="bluebtn dcb_btn unfollow"><span class="dcb_icon ic_1w"></span> Following</button>
									<?php }else{ ?>
									<button id="<?php echo $doctor['regId']; ?>" class="bluebtn dcb_btn follow"><span class="dcb_icon ic_1w"></span> Follow Doctor </button>
									<?php } ?>
									<a href="<?php echo base_url();?>member/contact-doctor/<?php echo $doctor['regId']; ?>" class="dcb_btn"><span class="dcb_icon ic_2w"></span> Contact Doctor</a>
									<a href="javascript:;" class="dcb_btn openFeedback"><span class="dcb_icon ic_4w"></span> Give Feedback</a>
								</div>
							</div>
						</div>
					</div>    
				</div>
			</div>
    						
			<div class="row" id="sticky-anchor">
				<div class="container-fluid maxwidth">
					<div class="container-fluid" data-sticky_parent>
						<div class="col-md-4 profile_panel" data-sticky_column>
							<div class="row">
            
								<div class="side_box">
									<div class="sb_title txtdark font_regular">Clinic Address</div>

									<div class="col-sm-4 hide">
										<div class="row">
											<div class="mapBox"></div>
										</div>
									</div>
									<div class="col-sm-12 mapInfo">
										<?php if($doctor['caddress']==''){ ?>
											<div class="address">No Data Available</div>
										<?php  }else{ ?>
											<div class="address"><?php echo $doctor['caddress']; ?></div>
											<div class="telno"><?php echo $doctor['mobile'].' '.$doctor['contacts']; ?></div>
											<div class="timing"><?php echo $doctor['timing']; ?></div>
										<?php } ?>
									</div>
								<div class="clearfix"></div>
								</div>
            
								<div class="side_box">
									<div class="sb_title txtdark font_regular">About Me</div>
									
									<div class="collapsible_readmore">
									<?php if($doctor['myself'] == ''){ ?>
									<p>No Data Available</p>
									<?php }else{ ?>
									<p><?php echo $doctor['myself']; ?></p>
									<?php } ?>
									</div>
								
								</div>
            
								<div class="side_box">
									<div class="sb_title txtdark font_regular">Information</div>

									<strong>Specialization</strong>
									<ul>
									<?php foreach($doctor['specialityList'] as $val){ ?>
										<li><?php echo $val; ?></li>
									<?php } ?>
									</ul>

									<strong>Education & Training</strong>
									<?php if($doctor['education'] == ''){ ?>
									<ul>
									<li>No Data Available.</li>
									</ul>
									<?php }else{ ?>
									<ul>
									<?php $eduList = explode("#",$doctor['education']);
										  foreach($eduList as $val){
									?>
									<li><?php echo $val; ?></li>
									<?php } ?>
									</ul>
									<?php } ?>

									<strong>Memberships</strong>
									<?php if($doctor['membership'] == ''){ ?>
									<ul>
									<li>No Data Available.</li>
									</ul>
									<?php }else{ ?>
									<ul>
									<?php $memList = explode("#",$doctor['membership']);
										  foreach($memList as $val){
									?>
									<li><?php echo $val; ?></li>
									<?php } ?>
									</ul>
									<?php } ?>

									<strong>Languages Spoken</strong>
									<?php if($doctor['language'] == ''){ ?>
									<ul>
									<li>No Data Available.</li>
									</ul>
									<?php }else{ ?>
									<ul>
									<?php $lang = explode(",",$doctor['language']);
										  foreach($lang as $val){
									?>
									<li><?php echo $val; ?></li>
									<?php } ?>
									</ul>
									<?php } ?>
								</div>
							</div>
						</div>    	
        
						<div class="col-md-8 feeds_panel">
							<div class="row">
								<div class="tabs_container">
								
									<div class="tabsBox clearfix">
										<ul>
											<li id="advice" class="active">Medical Advice</li>
											<li id="feedback" class="">Feedback</li>
										</ul>
									</div>
           
									<!-- Feeds Section -->
									
									<div id="adviceData" class="">
										<div id="result"></div>
										<button class="btn bluebtn" id="load_more" data-val = "1" style="margin:0 auto 	20px;display:table;width:100%; max-width:280px;">View More Content</button>
									</div>
           
									<!-- Feedback Section -->
									
									<div id="feedbackData" class="hide">
										<div class="container-fluid fdbk_form_container">
											<div class="row">
												<div class="container-fluid">
													<p style="float:left;margin-bottom:0;"><strong class="txtdark">Your Feedback Matters!</strong><br> Submit a review for <?php echo $doctor['name'];?>.</p>
													<button class="bluebtn btn write_reviewBtn" style="float:right;margin-left:15px;">Write a review</button>
												</div>
											
												<?php
													$userData = $this->session->userdata('userData');
													$userId = $userData['userId'];
													$type = $userData['type'];
												?>
							
												<div class="container-fluid review_form" style="display:none;margin-top:10px">
													<form id="feedbackForm">
														<div class="form-group">
														<input type="hidden" id="utype" name="utype" value="<?php echo $type; ?>"/>
														<input type="hidden" id="uid" name="uid" value="<?php echo $userId; ?>"/>
														<input type="hidden" id="did" name="did" value="<?php echo $doctor['regId']; ?>"/>
														<textarea id="feedback" name="feedback" class="form-control" placeholder="Type your review..." style="max-width:100%;"></textarea>
														</div>
														<input type="submit" value="Submit" class="bluebtn btn" />
													</form>
												</div>
											</div>
											<div class="clearfix"></div>
										</div>
										
										<h2 class="txtdark">Reviews</h2>
										<div id="feedbackResult"></div>
										<button class="btn bluebtn" id="load-feedback" data-val = "1" style="margin:0 auto 	20px;display:table;width:100%; max-width:280px;">View More Feedback</button>
									</div>
								</div>    
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>	
	</div>    
</div>