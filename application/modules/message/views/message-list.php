<div class="container-fluid main_container">
	<div class="row">
		<div class="container-fluid dashboard_bg">    
			<div class="row searchbar_container hide">
				<div class="searchbar_box hide">
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
								<div class="msgtabs">
									<ul class="tabs">
										<li id="inbox" class="active">Inbox <!--(50)--></li>
										<li id="sent" class="">Sent <!--(15)--></li>
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
        
								<div class="container-fluid messagesList fade_anim">
									<div id="inboxData" class="">
										<div id="inboxMsg"></div><br>
										<button class="btn bluebtn" id="load_inbox" data-val = "1" style="margin:0 auto 	20px;display:table;width:100%; max-width:280px;">Load More Message</button>
									</div>
									
									<div id="sentData" class="hide">
										<div id="sentMsg"></div>
										<div class="clearfix"></div><br>
										<button class="btn bluebtn" id="load_sent" data-val = "1" style="margin:0 auto 	20px;display:table;width:100%; max-width:280px;">Load More Message</button>
									</div>
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
													<a href="<?php echo base_url()?>doctor/view/<?php echo $doc[0] ?>" class="doct_name txtblue"><strong><?php echo $doc[2] ?></strong></a>
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