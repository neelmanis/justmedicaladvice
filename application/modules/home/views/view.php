<div class="container-fluid main_container">
	<div class="row">
		<div class="container-fluid dashboard_bg">    
			<div class="row" id="sticky-anchor">
				<div class="container-fluid maxwidth">
					<div class="container-fluid" data-sticky_parent>
						<div class="col-md-12 content_panel">
							<div class="article_Briefbox">
							
							<!-- Author Information -->
							<?php if($blogDetails[0]->postedBy == 'admin'){ ?>
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
										<div class="col-sm-3 col-sm-pull-9 txtdark font_regular"><?php echo date("d M, Y ",strtotime($blogDetails[0]->createdDate))?></div>
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
									<div class="col-xs-4 text-center"><a href="<?php echo base_url();?>blog/view/<?php echo $blogDetails[0]->slug;?>"><button><span class="blogging_icons thank"></span> Thank</button></a></div>
									<div class="col-xs-4 text-center"><a href="<?php echo base_url();?>blog/view/<?php echo $blogDetails[0]->slug;?>/#share"><button><span class="blogging_icons share"></span> Share</button></a></div>
									<div class="col-xs-4 text-center"><a href="<?php echo base_url();?>blog/view/<?php echo $blogDetails[0]->slug;?>"><button><span class="blogging_icons flag"></span> Report</button></a></div>        
								</div>
								
								<!-- Comments Section -->
								<div class="container-fluid comments_area" id="commentToTop">
									<!-- Comment Form -->
									<div class="comment_box">
										<!-- Image of Account-->
										<div class="comment">
											<a href="<?php echo base_url();?>blog/view/<?php echo $blogDetails[0]->slug;?>"><button class="btn bluebtn" style="margin:0 auto 20px;display:table;width:100%; max-width:280px;">Login To Comment</button></a>
											
										</div>
									</div>

									<div id="comment_result"><?php echo $comments; ?></div>
	          
								</div>
								<div class="clearfix"></div>
							</div>
						</div>    	
					</div>
				</div>
			</div>
		</div>	
	</div>    
</div>