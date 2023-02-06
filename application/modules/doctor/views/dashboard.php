<div class="container-fluid main_container">
	<div class="row">
		<div class="container-fluid dashboard_bg">		
			<div class="row searchbar_container">
				<div class="searchbar_box">
					<div class="container-fluid maxwidth">
						<div class="container-fluid">
							<form id="docDashSearch" style="margin-bottom:0;">
								<div class="input-group searchbar_holder">
									<input class="form-control" placeholder="Search blogs, videos, forums..." id="searchText" name="searchText" autocomplete="off">
									<div class="input-group-btn"><button class="btn bluebtn">Search</button></div>
									
									<div class="suggestion_dd" style="display:none;">
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		
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
											<a href="<?php echo base_url()."blog/read/".$val->url;?>" class="btn bluebtn db_slideBtn">Read Now</a>
											<?php }else if($val->ctype == "media"){?>
											<a href="<?php echo base_url()."media/watch/".$val->url;?>" class="btn bluebtn db_slideBtn">Watch Now</a>
											<?php }else if($val->ctype == "forum"){?>
											<a href="<?php echo base_url()."forum/read/".$val->url;?>" class="btn bluebtn db_slideBtn">Read Now</a>
											<?php } ?>
										</div>
									</div>
								</div>
								<?php } ?>
							</div>
							<?php } ?>
							
							<div class="row fade_anim">
								<div class="col-sm-4">
									<a href="<?php echo base_url()?>blog/write-an-article" class="db_btns">
										<div class="db_btn_name">
											<div class="bd_btn_tl"><span>Write an</span>Article</div>
											<div class="icon ic_4"></div>
										</div>
										<?php if($blogCount !== 'no'){?>
										<div class="db_btn_txt">You have <strong><?php echo $blogCount;?></strong> articles online</div>
										<?php }else{  ?>
										<div class="db_btn_txt">No Blog written by you. Write Now!</div>
										<?php } ?>
									</a>
								</div>
								<div class="col-sm-4">
									<a href="<?php echo base_url()?>media/upload-media" class="db_btns">
										<div class="db_btn_name">
											<div class="bd_btn_tl"><span>Upload a</span>Video/Audio</div>
											<div class="icon ic_5"></div>
										</div>
										<?php if($videoCount !== 'no'){?>
										<div class="db_btn_txt">You have <strong><?php echo $videoCount;?></strong> video/audio online</div>
										<?php }else{  ?>
										<div class="db_btn_txt">No video uploaded by you. Upload Now!</div>
										<?php } ?>
									</a>
								</div>
								<div class="col-sm-4">
									<a href="<?php echo base_url()?>forum/post-forum" class="db_btns">
										<div class="db_btn_name">
											<div class="bd_btn_tl"><span>Create a Forum</span>Ask Query</div>
											<div class="icon ic_3"></div>
										</div>
										<div class="db_btn_txt">Get answers to all your queries</div>
									</a>
								</div>
								<div class="col-sm-4 hide">
									<a href="<?php echo base_url()?>webinar/create-webinar" class="db_btns">
										<div class="db_btn_name">
											<div class="bd_btn_tl"><span>Connect with a </span>Webinar</div>
											<div class="icon ic_6"></div>
										</div>
										<div class="db_btn_txt">Your <strong>15</strong> webinars online.</div>
									</a>
								</div>
							</div>
						</div>
			
						<div class="col-md-3 aside_panel" data-sticky_column>
							<div class="row">
								<div class="side_box">
									<div class="sb_title txtdark font_regular">Answer these forums</div>
									<ul class="forum_list fade_anim">
										<?php if($forumList !== 'No Data'){ 
												foreach($forumList as $forum){
										?>
										<li>
											<div class="author_info">
												<div class="author_dp">
												<a href="javascript:;"><img src="<?php echo $forum['uimage'];?>"></a></div>
													<div class="author_name"><strong><a href="javascript:;" class="txtblue"><?php echo $forum['uname']; ?></a></strong> asked</div>
											</div>
											<a href="<?php echo $forum['url'];?>" class="question"><?php echo $forum['question'];?></a>
											<div class="txtdark" style="float:left;" data-toggle="tooltip" data-title="Replies received"><span class="blogging_icons comment"></span><?php echo $forum['answer'];?> </div>
											<div style="font-size:12px;color:#999;float:right;"> Posted on <?php echo date("d M Y ",strtotime($forum['cdate']));?></div>
											<div class="clearfix"></div>
										</li>
										<?php } } ?>
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