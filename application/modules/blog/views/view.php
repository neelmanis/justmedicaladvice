<div class="container-fluid main_container">
	<div class="row">
		<div class="container-fluid dashboard_bg">    
			<div class="row searchbar_container">
				<div class="searchbar_box">
					<div class="container-fluid maxwidth">
						<div class="container-fluid">
						<form method="post" action="<?php echo base_url()?>blog/searchSubmit" style="margin-bottom:0;">
							<div class="input-group searchbar_holder">
								<div>
									<input class="form-control" placeholder="Search blogs, doctors..." id="searchText" name="searchText" autocomplete="off">
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
								$userImageUrl = base_url().$image;
							
								if($blogDetails[0]->postedBy == 'admin'){
							?>
								<!-- Author Information -->
								<div class="container-fluid author_info">
									<div class="author_dp"><a href=""><img src="<?php echo base_url()?>admin_assets/images/JMA.png"></a></div>
									<div class="author_name"><strong><a href="" class="txtblue">Just Medical Advice</a></strong> shared article</div>
								</div>
							<?php }else{ ?>
								<div class="container-fluid author_info">	
								<?php 
								$docId = base64_encode($blogDetails[0]->userId);
								$docId = str_replace(str_repeat('=',strlen($docId)/4),"",$docId);
								?>
								<div class="author_dp"><a href="<?php echo base_url();?>doctor/view/<?php echo $docId;?>"><img src="<?php echo base_url().$userDetails[0]->profileImage; ?>"></a></div>
								<div class="author_name"><strong><a href="<?php echo base_url();?>doctor/view/<?php echo $docId;?>" class="txtblue"><?php echo $userDetails[0]->name; ?></a></strong> shared article</div>
								<?php if($type == 'mem'){
								$followedDocs = Modules::run('member/getDocList'); 
								if($followedDocs !== 'No Data' && in_array($blogDetails[0]->userId,$followedDocs)){ ?>
									<div class="author_follow"><button id="<?php echo  $docId;?>" class="doct_follow_btn unfollow" data-toggle="tooltip" data-title="Following"><span></span></button></div>
								<?php }else{ ?>
									<div class="author_follow"><button id="<?php echo $docId; ?>" class="doct_follow_btn follow" data-toggle="tooltip" data-title="Follow Doctor"><span></span></button></div>
								<?php } }?>
								
								
								
								
								<!--<div class="author_follow"><button class="doct_follow_btn" data-toggle="tooltip" data-title="Follow Doctor"><span></span></button></div>-->
								</div>
							<?php } ?>
							
								<!-- Title of the Blog -->
								<h2 class="container-fluid article_title txtblue"><?php echo $blogDetails[0]->title;?></h2>
							
								<!-- Counts -->
								<div class="container-fluid" style="margin-bottom:10px;">
									<div class="row">
										<div class="col-sm-9 col-sm-push-3">
											<div class="article_stats stats_align">
												<div class="counts"><span class="blogging_icons thank"></span> <?php echo $likeCount; ?> Thanks</div>
												<div class="counts"><span class="blogging_icons comment share"></span> <?php echo $commentCount; ?> Comments</div>
											</div>
										</div>
										<div class="col-sm-3 col-sm-pull-9 txtdark font_regular"><?php echo date("d-M, Y ",strtotime($blogDetails[0]->createdDate))?></div>
									</div>
								</div>            
							
								<!-- Blog Content Here -->
								<div class="container-fluid article_content">
									<!-- Blog Image Here -->
									<?php  if($blogDetails[0]->image !== 'No Data'){?>
										<img src="<?php echo base_url()?>admin_assets/images/blog/<?php echo $blogDetails[0]->image;?>" class="right">
									<?php } ?>
								
									<?php echo $blogDetails[0]->content; ?>
								
									<!-- Refence Links -->	
									<?php if($blogDetails[0]->reference !== 'No Data'){ ?>
									<div class="referencelinks">
										<div class="tl"><strong>References</strong></div>
										<div class="clearfix"></div>
											<?php echo $blogDetails[0]->reference; ?>
									</div>
									<?php } ?>
								</div>    

								<!-- Count Buttons -->
								<div class="row blogactivity no_last" id="commentBox">
									<div class="col-xs-4 text-center"><button id="like_blog" class="<?php if($isLiked == 1){
									echo "thanked";}?>"><span class="blogging_icons thank"></span> Thank</button></div>
									<div class="col-xs-4 text-center"><button data-toggle="fpopover" data-container="body" data-placement="top" data-trigger="focus" data-html="true" id="share"><span class="blogging_icons share"></span> Share</button></div>
									<div class="col-xs-4 text-center"><button id="report_blog"><span class="blogging_icons <?php if($isReport == 1){ echo "flagged";}else{ echo "flag";}?>"></span> Report</button></div>        
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
								<!-- Comments Section -->
								<div class="container-fluid comments_area" id="commentToTop">
									<!-- Comment Form -->
									<div class="comment_box">
										<!-- Image of Account-->
										<div class="author_dp"><div><img src="<?php echo $userImageUrl; ?>"></div></div> 
										<div class="comment">
											<form id="comment_form">
												<input type="hidden" name="uid" id="uid" value="<?php echo $userId; ?>" />
												<input type="hidden" name="utype" id="utype" value="<?php echo $type; ?>" />
												<input type="hidden" name="bid" id="bid" value="<?php echo $blogDetails[0]->blogId;?>" />
												<input type='hidden' name='pid' id="pid" value="0"/>
												<div id="commentTrigger" class="form-group">
													<textarea class="form-control" name ="comment" id="comment" placeholder="Write a comment"></textarea>
												</div>
												<div id='submit_button'>
													<button id="postComment" class="btn bluebtn" style="margin:0 auto 20px;display:table;width:100%; max-width:280px;">Post Comment</button>
												</div>
											</form>
										</div>
									</div>

									<div id="comment_result"><?php echo $comments; ?></div>
	          
								</div>
								<div class="clearfix"></div>
							</div>
						</div>    	
        
						<div class="col-md-3 aside_panel" data-sticky_column>
							<div class="row">
								<?php if($type == 'mem'){ ?> 
								<div class="side_box">
									<div class="sb_title txtdark font_regular">Related Articles</div>
									<ul class="relArticle_list">
										<?php 
										if($suggestedBlog !== "No Data"){
										foreach($suggestedBlog as $suggest){?>
										<li><a href="<?php echo $suggest['url']?>">
											<?php  if($suggest['image'] !== ''){?>
												<div class="relart_img">
												<div><img src="<?php echo $suggest['image'];?>"></div></div>
											<?php } ?>
											<div class="relart_detail"><?php echo $suggest['title']; ?>
											<div class="likeCounts"><span class="blogging_icons thank"></span> <?php echo $suggest['like']; ?> Thanks</div> 
											</div> 
											</a>               
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
												<a href=""><img src="<?php echo $forum['uimage'];?>"></a></div>
													<div class="author_name"><strong><a href="" class="txtblue"><?php echo $forum['uname']; ?></a></strong> asked</div>
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
									<div class="sb_title txtdark font_regular">Ask a question</div>
									<p style="font-size:11px;margin:0 0 5px;line-height:16px">To get advice from relevant doctors and insights from members with similar issues.</p>
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