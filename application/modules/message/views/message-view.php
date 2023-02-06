<div class="container-fluid main_container">
	<div class="row">
		<div class="container-fluid dashboard_bg">
			<div class="row searchbar_container hide">
				<div class="searchbar_box">
					<div class="container-fluid maxwidth">
						<div class="container-fluid">
							<form>
								<div class="input-group searchbar_holder">
									<input class="form-control" placeholder="Search blogs, videos, forums, doctors...">
									<div class="input-group-btn"><button class="btn bluebtn">Search</button></div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
    
			<div class="clearfix" style="height:20px;"></div>
			<div class="row" id="sticky-anchor">
				<div class="container-fluid maxwidth">
					<div class="container-fluid" data-sticky_parent>
						<div class="col-md-9 content_panel">
							<div class="messages_main">
								<div class="msgtabs hide">
									<ul class="tabs">
										<li class="active"><a href="">Inbox (50)</a></li>
										<li class=""><a href="">Sent (15)</a></li>
									</ul>
                
									<div class="msgsortBox hide">
										<select class="selectpicker" title="Sort by">
											<option>All</option>
											<option>Read</option>
											<option>Unread</option>
										</select>
									</div>
            
									<div class="clearfix"></div>
								</div>
								
								<div class="clearfix"></div>
        
								<div class="container-fluid messageDisplaybox fade_anim">
									<div class="container-fluid font_regular msg_actions" style="padding-bottom:5px;margin-bottom:5px;">
										<div class="clearfix" style="height:20px"></div>    
										<div class="row">
											<div class="col-xs-6 text-left"><div class="row"><a href="<?php echo base_url();?>message/inbox"><img src="<?php echo base_url();?>public/images/back_arw.png"> Back</a></div></div>
											<div class="col-xs-6 text-right"><div class="row"><a href="<?php echo base_url();?>message/deleteMessage/<?php echo $mid;?>"><img src="<?php echo base_url();?>public/images/ic_bin.png"> Delete</a></div></div>
										</div>
									</div>
                
									<div class="messageBox">
										<div class="container-fluid" style="margin-bottom:10px;">
											<div class="row">
												<div class="col-sm-6 col-sm-push-6 text-right" style="font-size:12px;">
													<div class="row" style="padding-top:10px;"><?php echo date("d M Y",strtotime($message['postedDate']))?></div>
												</div>
											
												<div class="col-sm-6 col-sm-pull-6">
													<?php 
														$userData = $this->session->userdata('userData');
														$type = $userData['type'];
														if($type == 'doc'){
													?>
													<div class="row">
														<div class="msg_dp"><img src="<?php echo base_url().$message['memImage'];?>"></div>
														<div class="msg_info" style="padding-top:10px;"><strong class="txtblue"><?php echo $message['memName'];?></strong> asked </div>
													</div>
													<?php }else{ ?>
													<div class="row">
														<div class="msg_dp"><img src="<?php echo base_url().$message['docImage'];?>"></div>
														<div class="msg_info" style="padding-top:10px;"><strong class="txtblue"><?php echo $message['docName'];?></strong><?php if($message['isReplied'] == 1){echo " replied to ";}?></div>
													</div>	
													<?php } ?>
												</div>
												
											</div>
										</div>
                
										<div class="container-fluid txtdark msg_tl" style="background:#f8f8f8;font-size:18px;padding-top:7px;padding-bottom:7px;margin-bottom:15px;">
											<?php echo $message['subject'];?>
										</div>
                
										<div class="container-fluid">
											<p><?php echo $message['message'];?></p>                
										</div>
										
										<?php if($message['isReplied'] == 1){ ?>
										<div class="container-fluid" style="border-top: 1px solid #ddd; padding-top:10px;">
											<p><?php echo $message['reply'];?></p>                
										</div>
										<?php } ?>

										<?php 
											$userData = $this->session->userdata('userData');
											$type = $userData['type'];
											if($type == 'doc' && $message['isReplied'] == 0){
										?>
										<div class="container-fluid text-center">
											<button class="bluebtn btn" id="reply" style="width:150px;"><img src="<?php echo base_url();?>public/images/ic_reply.png" style="margin-right:4px;"> Reply</button>
										</div>
										<?php } ?>
										
										<div class="clearfix" style="height:10px"></div>
									</div>
                
									<div id="writeReply" class="container-fluid hide">
										<div class="row">
											<form id="messageReply">
												<div class="form-group">
													<textarea name="reply" id="reply" class="form-control" placeholder="Type your reply..." style="height:100px;"></textarea>
													<input type="hidden" id="msgId" name="msgId" value="<?php echo $message['messageId'];?>" />
												</div>
												<div class="row">
													<div class="col-xs-6"><input type="submit" class="bluebtn btn" value="Send" style="width:100px"></div>
													<div class="col-xs-6 text-right font_regular hide" style="padding-top:10px;"><a href=""><img src="<?php echo base_url();?>public/images/ic_bin.png"> Delete Draft</a></div>
												</div>
											</form>
										</div>
									</div>
                
									<div class="clearfix" style="height:20px"></div>    
								</div>
							</div>
						</div>    	
        
						<div class="col-md-3 aside_panel" data-sticky_column>
							<div class="row">
								<?php 
									$userData = $this->session->userdata('userData');
									$type = $userData['type'];
									if($type !== 'doc'){
								?>
								
								<div class="side_box hide_overflow">
									<div class="sb_title txtdark font_regular">Suggested Doctors</div>
									<ul class="suggested_doc_list">
										<?php 
										$followedDocs = Modules::run('member/getDocList');
										if(isset($docList)){ 
										foreach($docList as $doc){ ?>
										<li>
											<?php if($followedDocs !== 'No Data' && in_array($doc[4],$followedDocs)){ ?>
												<button id="<?php echo $doc[0] ?>" class="doct_follow_btn unfollow" data-toggle="tooltip" data-title="Following"><span></span></button>
											<?php }else{ ?>
												<button id="<?php echo $doc[0] ?>" class="doct_follow_btn follow" data-toggle="tooltip" data-title="Follow Doctor"><span></span></button>
											<?php } ?>
								
											<div class="doct_prof">
												<a href="" class="doct_dp">
													<img src="<?php echo base_url()?><?php echo $doc[1] ?>">
												</a>
												<div class="doct_details">
													<a href="<?php echo base_url()?>doctor/profile/<?php echo $doc[0] ?>" class="doct_name txtblue"><strong><?php echo $doc[2] ?></strong></a>
													<div class="doct_speciality"><?php echo $doc[3] ?></div>
													<div class="doct_follow_count font_regular"><?php echo $doc[5] ?> Followers</div>
												</div>
											</div>
										</li>	
									<?php  } } ?>
									</ul>
								</div>
        
								<div class="side_box hide">
									<div class="sb_title txtdark font_regular">Ask a free question</div>
									<p style="font-size:11px;margin:0 0 5px;line-height:16px">Do you have any query? Create forum and get FREE multiple opinions from Doctors and medical helpers.</p>
                
									<form>
										<div class="form-group" style="margin-bottom:10px;">
											<textarea class="form-control" placeholder="Enter your question..." style="width:100%;max-width:100%;height:60px;"></textarea>
										</div>
										<div class="form-group" style="margin-bottom:10px;">
											<select class="selectpicker form-control" data-live-search="true" title="Select Speciality">
											<option>Speciality 1</option><option>Speciality 2</option><option>Speciality 3</option><option>Speciality 4</option><option>Speciality 5</option>
											<option>Speciality 6</option><option>Speciality 7</option><option>Speciality 8</option><option>Speciality 9</option><option>Speciality 10</option>
											</select>
										</div>
										<div class="form-group text-center" style="margin-bottom:5px;"><input type="submit" value="Submit" class="btn bluebtn" style="width:150px"></div>
									</form>
								</div>
								<?php }else{ ?>
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
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>	
	</div>    
</div>