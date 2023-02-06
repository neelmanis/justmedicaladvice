<div class="col-md-12 content_panel">
	<div class="article_Briefbox">
	
		<!-- User Information -->	
		<?php
		$adminData = $this->session->userdata('adminData');
		$userId = $adminData['userId'];
		$type = $adminData['type'];
		$name = "Just Medical Advice";
		$userImageUrl = base_url().'admin_assets/images/JMA.png';
		
		if($mediaDetails[0]->postedBy == 'admin'){
		?>
		
		<!-- Author Information -->
		<div class="container-fluid author_info">
			<div class="author_dp"><a href=""><img src="<?php echo $userImageUrl;?>"></a></div>
			<div class="author_name"><strong><a href="" class="txtblue">Just Medical Advice</a></strong> shared <?php echo $mediaDetails[0]->mtype; ?></div>
		</div>
		<?php }else{ ?>
		
		<div class="container-fluid author_info">
			<div class="author_dp"><a href=""><img src="<?php echo base_url().$userDetails[0]->profileImage; ?>"></a></div>
			<div class="author_name"><strong><a href="" class="txtblue"><?php echo $userDetails[0]->name; ?></a></strong> shared <?php echo $mediaDetails[0]->mtype; ?></div>
		</div>
		<?php } ?>
								
		<!-- Video Link -->
		<?php if($mediaDetails[0]->mtype == 'video' && $mediaDetails[0]->ctype == 'youtube'){?>
		<div class="container-fluid">
			<div class="videoBox_holder">
			<?php $link = $mediaDetails[0]->url; ?>
			<iframe src="<?php echo $link;?>?rel=0&autoplay=0&showinfo=0&controls=0" frameborder="0" allowfullscreen></iframe>
			</div>
		</div>
		<?php }else if($mediaDetails[0]->mtype == 'video' && $mediaDetails[0]->ctype == 'upload'){ ?>
		<div class="videoBox_holder">
			<video width="400" controls controlsList="nodownload">
				<source src="<?php echo $mediaDetails[0]->url ?>">
				Your browser does not support HTML5 video.
			</video>
		</div>
		<?php }else if($mediaDetails[0]->mtype == 'audio'){ ?>
		<div>
			<audio controls controlsList="nodownload">
				<source src="<?php echo $mediaDetails[0]->url ?>">
				Your browser does not support HTML5 audio.
			</audio>
		</div>
		<?php } ?>
								
		<!-- Video title -->
		<h2 class="container-fluid video_title txtblue"><?php echo $mediaDetails[0]->title ?></h2>
											
		<div class="container-fluid" style="margin-bottom:10px;">
			<div class="row">
				<div class="col-sm-6 txtdark font_regular">Published on <?php echo date("d M Y ",strtotime($mediaDetails[0]->createdDate))?></div>
				<div class="col-sm-6">
					<div class="article_stats stats_align">
						<div class="counts"><span class="blogging_icons thank"></span> <?php echo $likeCount; ?> Thanks</div>
						<div class="counts"><span class="blogging_icons comment"></span> <?php echo $commentCount; ?> Comments</div>
					</div>
				</div>
			</div>
		</div>
							
		<!-- Count Buttons -->
		<div class="row blogactivity no_last">
			<div class="col-xs-4 text-center"><button id="like_media" class="<?php if($isLiked == 1){
			echo "thanked";}?>"><span class="blogging_icons thank"></span> Thank</button></div>
			<div class="col-xs-4 text-center"><button data-toggle="fpopover" data-container="body" data-placement="top" data-trigger="focus" data-html="true" id="share"><span class="blogging_icons share"></span> Share</button></div>
			<div class="col-xs-4 text-center"><button id="report_media"><span class="blogging_icons <?php if($isReport == 1){ echo "flagged";}else{ echo "flag";}?>"></span> Report</button></div>        
		</div>
		
		<div class="hide" id="fpopover-content-share">
			<div class="shareoptions">
				<div class="font_regular txtdark" style="margin-bottom:5px;">Share this <?php echo $mediaDetails[0]->mtype; ?> on :</div>
				<ul class="social_links colored fade_anim">
					<li><a class="fb" title="Facebook" href="#" onclick="window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(location.href),'facebook-share-dialog','width=626,height=436');return false;"></a></li>
					<li><a class="tt" title="Twitter" href="#" onclick="window.open('http://twitter.com/share?text=justmedicaladvice&url='+encodeURIComponent(location.href),'facebook-share-dialog','width=626,height=436');return false;"></a></li>
					<li><a class="li" title="LinkedIn" href="#" onclick="window.open('http://www.linkedin.com/shareArticle?mini=true&url='+encodeURIComponent(location.href),'facebook-share-dialog','width=626,height=436');return false;"></a></li>
				</ul>     
			</div>
		</div>
		
		<?php if($mediaDetails[0]->description !== 'No Data'){ ?>
		<div class="container-fluid article_content collapsible_readmore">
			<span class="txtdark font_regular">Description :</span>
            <?php echo  $mediaDetails[0]->description; ?>               
        </div>
        <?php } ?> 
		 
        <div class="clearfix" style="height:30px;"></div>
			
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
						<input type="hidden" name="mid" id="mid" value="<?php echo $mediaDetails[0]->mediaId;?>" />
						<input type='hidden' name='pid' id="pid" value="0"/>
						<div class="form-group" id="commentTrigger">
							<textarea class="form-control" name ="comment" id="commentBox" placeholder="Write a comment"></textarea>
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

<script>var CI_ROOT = '<?php echo base_url()?>';</script>
<script>
$(document).ready(function(){
	
	$("#like_media").click(function(){
		var mid = $('#mid').val();
		var utype = $('#utype').val();
		var uid = $('#uid').val();
		$.ajax({
			type:"POST",
			url:CI_ROOT+"media/addLike",	
			data:{mediaId:mid, type:utype, user:uid},
			beforeSend:function() {    
						$("#pageLoader").show();
					},
			success : function(result){
				if(result == 1){
					window.location.reload(true);
				}else if(result == 2){
					swal('Already Liked');
					$("#pageLoader").hide();
				}else{
					swal(result);
					$("#pageLoader").hide();
				}
			}
		});
	});
	
	$("#report_media").click(function(){
		var mid = $('#mid').val();
		var utype = $('#utype').val();
		var uid = $('#uid').val();
		$.ajax({
			type:"POST",
			url:CI_ROOT+"media/mediaReport",	
			data:{mediaId:mid, type:utype, user:uid},
			beforeSend:function() {    
						$("#pageLoader").show();
					},
			success : function(result){
				if(result == 1){
					window.location.reload(true);
				}else if(result == 2){
					swal('Already Reported');
					$("#pageLoader").hide();
				}else{
					swal(result);
					$("#pageLoader").hide();
				}
			}
		});
	});
	
	$("#postComment").click(function(e){
		e.preventDefault();
		var formdata = $("#comment_form").serialize();
		$.ajax({
			type:"POST",
			url:CI_ROOT+"media/addComment",	
			data:formdata,
			beforeSend:function() { 
				$("#pageLoader").show();
			},
			success : function(result){
				if(result == 1){
					window.location.reload();
				}else{
					alert(result);
					window.location.reload();
				}
			}
		});
	});
	
	$("a.reply").click(function (e){
		e.preventDefault();
		var id = $(this).attr("id");
		$("#pid").attr("value", id);
		$('html, body').animate({
			scrollTop: $("#commentToTop").offset().top-80
		}, 200);
		$("#commentBox").focus();
		$('#commentTrigger').addClass('comment_trigger');
	});
	
	$('#commentBox').keypress(function() { $('#commentTrigger').removeClass('comment_trigger'); });
	
	$("a.report").click(function (e){
		e.preventDefault();
		var id = $(this).attr("id");
		$.ajax({
			type:"POST",
			url:CI_ROOT+"media/addReport",	
			data:{cid:id},
			beforeSend:function() {    
						$("#pageLoader").show();
					},
			success : function(result){
				if(result == 1){
					window.location.reload(true);
				}else{
					swal({
					  title: "Error !",
					  icon: "error",
					  text: result
					}).then(function(){ 
						$("#pageLoader").hide();
					});
				}
			}
		});
	});
});
</script>