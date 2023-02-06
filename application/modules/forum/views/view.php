<div class="container-fluid main_container">
	<div class="row">
		<div class="container-fluid dashboard_bg">    
			<div class="row searchbar_container">
				<div class="searchbar_box">
					<div class="container-fluid maxwidth">
						<div class="container-fluid">
						<form method="post" action="<?php echo base_url()?>forum/searchSubmit" style="margin-bottom:0;">
							<div class="input-group searchbar_holder">
								<div>
									<input class="form-control" placeholder="Search blogs, doctors..." id="searchText" name="text" autocomplete="off">
									<div class="suggestion_dd" style="display:none;">
										
									</div>
								</div>
								<div class="input-group-btn"><input type="submit" class="btn bluebtn"value="SEARCH"></div>
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
							<div class="article_Briefbox">	
								<!-- User Information -->	
								<?php
								$userData = $this->session->userdata('userData');
								$userId = $userData['userId'];
								$type = $userData['type'];
								$image = $userData['image'];
								$name = $userData['name'];
								$userImageUrl = base_url();
								$userImageUrl .= $image;
								
								if($forumDetails[0]->postedBy == 'admin'){ ?>
								
								<!-- Author Information -->
								<div class="container-fluid author_info">
									<div class="author_dp"><a href=""><img src="<?php echo base_url()?>admin_assets/images/JMA.png"></a></div>
									<div class="author_name"><strong><a href="" class="txtblue">Just Medical Advice</a></strong> asked</div>
								</div>
								
								<?php }else{ ?>
								
								<div class="container-fluid author_info">
									<?php if($forumDetails[0]->postedBy == 'doc'){?>
										<div class="author_dp"><a href=""><img src="<?php echo base_url().$userDetails[0]->profileImage; ?>"></a></div>
										<div class="author_name"><strong><a href="" class="txtblue"><?php echo $userDetails[0]->name; ?></a></strong> asked </div>
									<?php }else{ ?>
										<div class="author_dp"><a href=""><img src="<?php echo base_url().$userDetails[0]->profileImage;?>"></a></div>
										<div class="author_name"><strong><a href="" class="txtblue"> <?php echo $userDetails[0]->name; ?></a></strong> asked </div>
									<?php } ?>
									
								</div>
							<?php } ?>
							
								<!-- Title of the Forum -->
								<h2 class="container-fluid article_title txtblue"><?php echo $forumDetails[0]->question;?></h2>
							
								<div class="container-fluid" style="margin-bottom:10px;">
									<div class="row">
										<div class="col-sm-9 col-sm-push-3">
											<div class="article_stats stats_align">
												<div class="counts"><span class="blogging_icons comment"></span><?php echo $answerCount; ?> answers</div>
												<div class="counts" style="cursor:pointer" data-toggle="fpopover" data-container="body" data-placement="top" data-trigger="click" data-html="true" id="share"><span class="blogging_icons share"></span> Share</div>
												<!--<button data-toggle="fpopover" data-container="body" data-placement="top" data-trigger="focus" data-html="true" id="share"><span class="blogging_icons share"></span> Share</button>-->
											</div>
											
											<div class="hide" id="fpopover-content-share">
												<div class="shareoptions">
													<div class="font_regular txtdark" style="margin-bottom:5px;">Share this article on :</div>
													<ul class="social_links colored fade_anim">
														<li><a class="fb" title="Facebook" href="#" onclick="window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(location.href),'facebook-share-dialog','width=626,height=436');return false;"></a></li>
														<li><a class="tt" title="Twitter" href="#" onclick="window.open('http://twitter.com/share?text=justmedicaladvice&url='+encodeURIComponent(location.href),'facebook-share-dialog','width=626,height=436');return false;"></a></li>
														<li><a class="li" title="LinkedIn" href="#" onclick="window.open('http://www.linkedin.com/shareArticle?mini=true&url='+encodeURIComponent(location.href),'facebook-share-dialog','width=626,height=436');return false;"></a></li>
													</ul>     
												</div>
											</div>
										</div>
										
										<div class="col-sm-3 col-sm-pull-9 txtdark">Posted on <span class=" font_regular"><?php echo date("d-M, Y ",strtotime($forumDetails[0]->createdDate))?></span></div>
									</div>
								</div>            
									
								<!-- Comments Section -->
								<div class="container-fluid comments_area" id="answerToTop">
									<!-- Comment Form -->
									<div class="comment_box">
										<!-- Image of Account-->
										<div class="author_dp"><div><img src="<?php echo $userImageUrl; ?>"></div></div> 
										<div class="comment">
											<form id="answer_form">
												<input type="hidden" name="uid" id="uid" value="<?php echo $userId; ?>" />
												<input type="hidden" name="utype" id="utype" value="<?php echo $type; ?>" />
												<input type="hidden" name="fid" id="fid" value="<?php echo $forumDetails[0]->forumId;?>" />
												<input type='hidden' name='pid' id="pid" value="0"/>
												<div class="form-group" id="commentTrigger">
													<textarea class="form-control" name ="answer" id="answer" placeholder="Write a answer"></textarea>
												</div>
												<div id='submit_button'>
													<button id="postAnswer" class="btn bluebtn" style="margin:0 auto 20px;display:table;width:100%; max-width:280px;">Post Answer</button>
												</div>
											</form>
										</div>
									</div>

									<div id="comment_result"><?php echo $answers; ?></div>

								</div>
								<div class="clearfix"></div>
							</div>
						</div>    	
						
						<div class="col-md-3 aside_panel" data-sticky_column>
							<div class="row">
								<?php if($type == 'mem'){ ?>
								<div class="side_box">
									<div class="sb_title txtdark font_regular">Popular Forums</div>
									<ul class="forum_list fade_anim">
										<?php if($SuggestedForums !== 'No Data'){ 
												foreach($SuggestedForums as $forum){
										?>
										<li>
											<div class="author_info">
												<div class="author_dp"><a href="javascript:;"><img src="<?php echo $forum['image']; ?>"></a></div>
												<div class="author_name"><strong><a href="javascript:;" class="txtblue"><?php echo $forum['name']; ?></a></strong> asked</div>
											</div>
											<a href="" class="question"><?php echo $forum['question']; ?></a>
											<div class="txtdark" style="float:left;" data-toggle="tooltip" data-title="Replies received"><span class="blogging_icons comment"></span><?php echo $forum['answer']; ?> </div>
											<div style="font-size:12px;color:#999;float:right;">Posted on <?php echo date("d M Y ",strtotime($forum['createdDate']));?></div>
											<div class="clearfix"></div>
										</li>
										<?php } } ?>
									</ul>
								</div>
								<?php }else if($type == 'doc'){ ?>
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
								<?php } ?>
								
								<div class="side_box">
									<div class="sb_title txtdark font_regular">Ask a free question</div>
									<p style="font-size:11px;margin:0 0 5px;line-height:16px">Do you have any query? Create forum and get FREE multiple opinions from Doctors and medical helpers.</p>
									<div id="formError" style="display: none;" class="alert alert-danger"></div>
									<form id="ask_question">
									<div class="form-group" style="margin-bottom:10px;">
									<select class="selectpicker form-control" data-live-search="true" title="Select Speciality" id="speciality" name="speciality">
										<?php foreach($speciality as $val){ ?>
										<option value="<?php echo $val->spId;?>"><?php echo $val->spName; ?></option>
										<?php } ?>
									</select>
									</div>
									<div class="form-group" style="margin-bottom:10px;">
									<textarea class="form-control" id="question" name="question" placeholder="Enter your question..." style="width:100%;max-width:100%;height:60px;"></textarea>
									</div>
									<input type="hidden" name="visible" id="visible" value="all" />
									<div class="form-group text-center" style="margin-bottom:5px;"><button id="postForum" class="btn bluebtn" style="width:150px">Post Question</button></div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>	
	</div>    
</div>