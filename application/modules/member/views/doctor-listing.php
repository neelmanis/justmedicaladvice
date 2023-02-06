<div class="container-fluid main_container">
	<div class="row">
		<div class="container-fluid dashboard_bg">    
			<div class="row searchbar_container">
				<div class="searchbar_box">
					<div class="container-fluid maxwidth">
						<div class="container-fluid">
							<form id="memDashSearch" style="margin-bottom:0;">
								<div class="input-group searchbar_holder">
									<input class="form-control" placeholder="Search blogs, videos, forums, doctors..." id="searchText" name="searchText" autocomplete="off">
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
        
							<div class="container-fluid serchPage_title">
								<div class="row">
									<div class="col-sm-8">Followed Doctors</div>
									<div class="col-sm-4 text-right">
										<div class="row txtdark">
											<div class="foundcount"><span class="font_regular" style="font-size:14px"><?php if($doctorsData !== 'No'){echo sizeof($doctorsData);}else{ echo 0;} ?></span> Doctors</div>
										</div>
									</div>
								</div>
							</div>
        
							<div class="doclist">
							
								<?php 
									if($doctorsData !== 'No'){
										foreach($doctorsData as $res){ ?>
								<div class="doclist_item fade_anim">
									<div class="doc_profile_table">
										<div class="col-sm-8 doc_details">
											<div class="doc_pic">
												<a href="javascript:;"><img src="<?php echo $res['image']; ?>"></a>
											</div>
											<div class="doc_info_box">
												<div class="doc_name"><a href="javascript:;" class="txtdark"><h2><?php echo $res['name']; ?></h2></a> 
												<?php if($res['featured'] == 1){ ?>
												<img src="<?php echo base_url();?>public/images/approved_tag.png" class="verified_tag" data-toggle="tooltip" data-title="Just Medical Advice Verified Doctor">
												<?php } ?>
												</div>
										
												<ul class="dd_creds">
													<li><span class="ddc_icon ic_1"></span> <?php echo $res['degree']; ?> - <?php echo $res['speciality']; ?></li>
													<li><span class="ddc_icon ic_3"></span> <?php echo $res['experience']; ?> yrs in practice</li>
													<li><span class="ddc_icon ic_2"></span> <?php echo $res['city']; ?></li>
												</ul>
												<a href="<?php echo base_url(); ?>doctor/view/<?php echo $res['regId']; ?>" class="txtblue font_regular clearfix">View Profile</a>
											</div>
											<div class="clearfix"></div> 
										</div>
                        
										<div class="col-sm-4 doc_stats">
											<table>
												<tr><td align="right"><strong><?php echo $res['followedMem']; ?></strong></td><td>Followers</td></tr>
												<tr><td align="right"><strong><?php echo $res['thanks']; ?></strong></td><td>Thanks recieved</td></tr>
												<tr><td align="right"><strong><?php echo $res['answerCount']; ?></strong></td><td>Forums answered</td></tr>
												<tr><td align="right"><strong><?php echo $res['feedbackCount']; ?></strong></td><td>Feedbacks</td></tr>
											</table>
										</div>
										<div class="clearfix"></div>
									</div>
            
									<div class="doc_cta_btns">
										<div><button id="<?php echo $res['regId']; ?>" class="bluebtn dcb_btn unfollow"><span class="dcb_icon ic_1w"></span> Following </button></div>
										<div><a href="<?php echo base_url(); ?>doctor/view/<?php echo $res['regId']; ?>" class="dcb_btn"><span class="dcb_icon ic_2"></span> Medical Advice</a></div>
										<div><a href="<?php echo base_url(); ?>message/contact-doctor/<?php echo $res['regId']; ?>" class="dcb_btn"><span class="dcb_icon ic_3"></span> Contact Doctor</a></div>
									</div>
								</div>
									<?php } } ?> 
							</div>
						</div>    	
        
						<div class="col-md-3 aside_panel fade_anim" data-sticky_column>
							<div class="side_box">
								<div class="sb_title txtdark font_regular">Ask a free question</div>
								<p style="font-size:11px;margin:0 0 5px;line-height:16px">Do you have any query? Create forum and get FREE multiple opinions from Doctors and medical helpers.</p>
                
								<form>
									<div class="form-group" style="margin-bottom:10px;">
										<textarea class="form-control" placeholder="Enter your question..." style="width:100%;max-width:100%;height:60px;"></textarea>
									</div>
									<div class="form-group" style="margin-bottom:10px;">
										<select class="selectpicker form-control" title="Select Speciality">
											<option>Speciality 1</option>
											<option>Speciality 2</option>
											<option>Speciality 3</option>
											<option>Speciality 4</option>
											<option>Speciality 5</option>
											<option>Speciality 6</option>
											<option>Speciality 7</option>
											<option>Speciality 8</option>
											<option>Speciality 9</option>
											<option>Speciality 10</option>
										</select>
									</div>
									<div class="form-group text-center" style="margin-bottom:5px;"><input type="submit" value="Submit" class="btn bluebtn" style="width:150px"></div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div>    
</div>