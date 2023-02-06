<div class="container-fluid main_container">
	<div class="row">
		<div class="container-fluid dashboard_bg">
			
			<!--  Seach Options -->
			<div class="row searchbar_container">
				<div class="searchbar_box">
					<div class="container-fluid maxwidth">
						<div class="container-fluid">
							<form id="memDashSearch" style="margin-bottom:0;">
								<div class="input-group searchbar_holder">
									<input class="form-control" placeholder="Search blogs, videos, forums ..." id="searchText" name="searchText" autocomplete="off">
									<div class="input-group-btn"><button class="btn bluebtn">Search</button></div>
									
									<div class="suggestion_dd" style="display:none;">
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			
			<!-- Banner Information -->
			<div class="row" id="sticky-anchor">
				<div class="container-fluid maxwidth">
					<div class="container-fluid" data-sticky_parent>
						<div class="col-md-9 content_panel">
							<?php if($banner !== 'no'){ ?>
							<div class="dashboard_banner slick_bullet">
								<?php foreach($banner as $val){?>
								<div>
									<div class="db_slides">
										<div class="img_holder" 
										style="background-image:url(<?php echo base_url().$val->image;?>); background-position:center center;">
										</div>
										<div class="db_slideinfo">
											<h2><?php echo $val->title;?></h2>
											<p><?php echo $val->content;?></p>
											<?php if($val->ctype == "blog"){?>
											<a href="<?php echo base_url()."blog/view/".$val->url;?>" class="btn bluebtn db_slideBtn">Read Now</a>
											<?php }else if($val->ctype == "media"){?>
											<a href="<?php echo base_url()."media/view/".$val->url;?>" class="btn bluebtn db_slideBtn">Watch Now</a>
											<?php }else if($val->ctype == "forum"){?>
											<a href="<?php echo base_url()."forum/view/".$val->url;?>" class="btn bluebtn db_slideBtn">Read Now</a>
											<?php } ?>
										</div>
									</div>
								</div>
								<?php } ?>
							</div>
							<?php } ?>
							<div class="row fade_anim">
								<div class="col-sm-4">
									<a href="<?php echo base_url()?>member/select-category" class="db_btns">
										<div class="db_btn_name">
											<div class="bd_btn_tl"><span>Select</span>Topics</div>
											<div class="icon ic_1"></div>
										</div>
										<div class="db_btn_txt">Get more personalised experience</div>
									</a>
								</div>
								
								<div class="col-sm-4">
									<a href="<?php echo base_url()?>member/search-doctor" class="db_btns">
										<div class="db_btn_name">
											<div class="bd_btn_tl"><span>Find a</span>Doctor</div>
											<div class="icon ic_2"></div>
										</div>
										<div class="db_btn_txt">More and more doctors sign up everyday</div>
									</a>
								</div>
        	
								<div class="col-sm-4">
									<a href="<?php echo base_url()?>forum/create-forum" class="db_btns">
										<div class="db_btn_name">
											<div class="bd_btn_tl"><span>Create a Forum</span>Ask Query</div>
											<div class="icon ic_3"></div>
										</div>
										<div class="db_btn_txt">Get answers to all your queries</div>
									</a>
								</div>
							</div>
						</div>
        
						<div class="col-md-3 aside_panel" data-sticky_column>
							<div class="row">
								<div class="side_box hide_overflow">
									<?php $followedDocs = Modules::run('member/getDocList');?>
									<div class="sb_title txtdark font_regular">Suggested Doctors</div>
									<ul class="suggested_doc_list">
									<?php if($docList !== "No Data"){ 
										foreach($docList as $doc){ ?>
										<li>
											<?php if($followedDocs !== 'No Data' && in_array($doc[4],$followedDocs)){ ?>
												<button id="<?php echo $doc[0] ?>" class="doct_follow_btn unfollow" data-toggle="tooltip" data-title="Following"><span></span></button>
											<?php }else{ ?>
												<button id="<?php echo $doc[0] ?>" class="doct_follow_btn follow" data-toggle="tooltip" data-title="Follow Doctor"><span></span></button>
											<?php } ?>
								
											<div class="doct_prof">
												<a href="<?php echo base_url()?>doctor/view/<?php echo $doc[0] ?>" class="doct_dp">
													<img src="<?php echo base_url()?><?php echo $doc[1] ?>">
												</a>
												<div class="doct_details">
													<a href="<?php echo base_url()?>doctor/view/<?php echo $doc[0] ?>" class="doct_name txtblue"><strong><?php echo $doc[2] ?></strong></a>
													<div class="doct_speciality"><?php echo $doc[3] ?></div>
													<div class="doct_follow_count font_regular"><?php echo $doc[5] ?> Followers</div>
												</div>
											</div>
										</li>	
									<?php  } } ?>
									</ul>
								</div>
							</div>
						</div>
    
						<div class="col-md-9 content_panel">
							<div id="result"></div>
							<button class="btn bluebtn" id="load_more" data-val = "1" style="margin:0 auto 20px;display:table;width:100%; max-width:280px;">Load More Data</button>
						</div>
					</div>
				</div>			
			</div>
		</div>			
	</div>    
</div>
