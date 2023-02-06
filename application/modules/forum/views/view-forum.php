<div class="col-md-12 content_panel">
	<div class="article_Briefbox">
	
		<!-- User Information -->	
		<?php
		$adminData = $this->session->userdata('adminData');
		$userId = $adminData['userId'];
		$type = $adminData['type'];
		$name = "Just Medical Advice";
		$userImageUrl = base_url().'admin_assets/images/JMA.png';
		
		if($forumDetails[0]->postedBy == 'admin'){ ?>
		
		<!-- Author Information -->
		<div class="container-fluid author_info">
			<div class="author_dp"><a href=""><img src="<?php echo $userImageUrl;?>"></a></div>
			<div class="author_name"><strong><a href="" class="txtblue">Just Medical Advice</a></strong> asked</div>
		</div>
		
		<?php }else{ ?>
		
		<div class="container-fluid author_info">
			<?php if($forumDetails[0]->postedBy == 'doc'){ ?>
				<div class="author_dp"><a href=""><img src="<?php echo base_url().$userDetails[0]->profileImage; ?>"></a></div>
				<div class="author_name"><strong><a href="" class="txtblue"><?php echo $userDetails[0]->name; ?></a></strong> asked </div>
			<?php }else{ ?>
				<div class="author_dp"><a href=""><img src="<?php echo base_url().$userDetails[0]->profileImage; ?>"></a></div>
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
                        <div class="counts"><span class="blogging_icons share"></span> Share</div>
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
							<textarea class="form-control" name ="answer" id="answerBox" placeholder="Write a answer"></textarea>
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

<script>var CI_ROOT = '<?php echo base_url()?>';</script>
<script>
$(document).ready(function(){
	
	$("#postAnswer").click(function(e){
		e.preventDefault();
		var formdata = $("#answer_form").serialize();
		$.ajax({
			type:"POST",
			url:CI_ROOT+"forum/addAnswer",	
			data:formdata,
			beforeSend:function() { 
				$("#pageLoader").show();
			},
			success : function(result){
				if(result == 1){
					swal({
					  title: "Successful !",
					  icon: "success",
					  text: "Answer is posted."
					}).then(function(){ 
						location.reload(true);
					});
				}else{
					swal({
					  title: "Error !",
					  icon: "error",
					  text: result
					}).then(function(){ 
						location.reload(true);
					});
				}
			}
		});
	});
	
	$("a.reply").click(function (e){
		e.preventDefault();
		$("#answerBox").focus();
		var id = $(this).attr("id");
		$("#pid").attr("value", id);
		$('html, body').animate({
			scrollTop: $("#answerToTop").offset().top-80
		}, 200);
		$('#commentTrigger').addClass('comment_trigger');
	});
	
	$('#answerBox').keypress(function() { $('#commentTrigger').removeClass('comment_trigger'); });
	
	$("a.like").click(function (e){
		e.preventDefault();
		var id = $(this).attr("id");
		var utype = $('#utype').val();
		var uid = $('#uid').val();
		$.ajax({
			type:"POST",
			url:CI_ROOT+"forum/answerLike",	
			data:{answerId:id, type:utype, user:uid},
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
	
	$("a.dislike").click(function (e){
		e.preventDefault();
		var id = $(this).attr("id");
		var utype = $('#utype').val();
		var uid = $('#uid').val();
		$.ajax({
			type:"POST",
			url:CI_ROOT+"forum/answerDislike",	
			data:{answerId:id, type:utype, user:uid},
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
	
	$("a.report").click(function (e){
		e.preventDefault();
		var id = $(this).attr("id");
		$.ajax({
			type:"POST",
			url:CI_ROOT+"forum/addReport",	
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