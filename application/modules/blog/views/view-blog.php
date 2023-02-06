<div class="col-md-12 content_panel">
	<div class="article_Briefbox">
	
	<!-- User Information -->	
	<?php
		$adminData = $this->session->userdata('adminData');
		$userId = $adminData['userId'];
		$type = $adminData['type'];
		$name = "Just Medical Advice";
		$userImageUrl = base_url().'admin_assets/images/JMA.png';
		
		if($blogDetails[0]->postedBy == 'admin'){
	?>
		<!-- Author Information -->
		<div class="container-fluid author_info">
			<div class="author_dp"><a href=""><img src="<?php echo $userImageUrl;?>"></a></div>
			<div class="author_name"><strong><a href="" class="txtblue">Just Medical Advice</a></strong> shared article</div>
		</div>
	<?php }else{ ?>
		<div class="container-fluid author_info">
			<div class="author_dp"><a href=""><img src="<?php echo base_url().$userDetails[0]->profileImage; ?>"></a></div>
			<div class="author_name"><strong><a href="" class="txtblue"><?php echo $userDetails[0]->name; ?></a></strong> shared article</div>
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
						<div class="counts"><span class="blogging_icons comment"></span> <?php echo $commentCount; ?> Comments</div>
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
		<div class="row blogactivity no_last">
			<div class="col-xs-4 text-center"><button id="like_blog" class="<?php if($isLiked == 1){
			echo "thanked";}?>"><span class="blogging_icons thank"></span> Thank</button></div>
			<div class="col-xs-4 text-center"><button data-toggle="fpopover" data-container="body" data-placement="top" data-trigger="focus" data-html="true" id="share"><span class="blogging_icons share"></span> Share</button></div>
			<div class="col-xs-4 text-center"><button id="report_blog"><span class="blogging_icons <?php if($isReport == 1){ echo "flagged";}else{ echo "flag";}?>"></span> Report</button></div>        
		</div>
		<?php $blogUrl = base_url().'blog/view/'.$blogDetails[0]->slug;?>
		<div class="hide" id="fpopover-content-share">
			<div class="shareoptions">
				<div class="font_regular txtdark" style="margin-bottom:5px;">Share this article on :</div>
				<ul class="social_links colored fade_anim">
					<li><a class="fb" title="Facebook" href="#" onclick="window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent('<?php echo $blogUrl; ?>'),'facebook-share-dialog','width=626,height=436');return false;"></a></li>
					<li><a class="tt" title="Twitter" href="#" onclick="window.open('http://twitter.com/share?text=justmedicaladvice&url='+encodeURIComponent(<?php echo $blogUrl; ?>),'facebook-share-dialog','width=626,height=436');return false;"></a></li>
					<li><a class="li" title="LinkedIn" href="#" onclick="window.open('http://www.linkedin.com/shareArticle?mini=true&url='+encodeURIComponent(<?php echo $blogUrl; ?>),'facebook-share-dialog','width=626,height=436');return false;"></a></li>
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
	
	$("#like_blog").click(function(){
		var bid = $('#bid').val();
		var utype = $('#utype').val();
		var uid = $('#uid').val();
		$.ajax({
			type:"POST",
			url:CI_ROOT+"blog/addLike",	
			data:{blogId:bid, type:utype, user:uid},
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
	
	$("#report_blog").click(function(){
		var bid = $('#bid').val();
		var utype = $('#utype').val();
		var uid = $('#uid').val();
		$.ajax({
			type:"POST",
			url:CI_ROOT+"blog/blogReport",	
			data:{blogId:bid, type:utype, user:uid},
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
			url:CI_ROOT+"blog/addComment",	
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
		//$("#commentBox").focus();
		$('#commentTrigger').addClass('comment_trigger');
	});
	
	$('#commentBox').click(function() { $('#commentTrigger').removeClass('comment_trigger'); });
	
	$("a.report").click(function (e){
		e.preventDefault();
		var id = $(this).attr("id");
		$.ajax({
			type:"POST",
			url:CI_ROOT+"blog/addReport",	
			data:{cid:id},
			beforeSend:function() {    
						$("#pageLoader").show();
					},
			success : function(result){
				if(result == 1){
					swal("Already Reported");
					$("#pageLoader").hide();
				}else if(result == 2){
					swal("Comment Reported");
					$("#pageLoader").hide();
				}else if(result == 3){
					swal("Some Error Occured");
					$("#pageLoader").hide();
				}
			}
		});
	});
});
</script>