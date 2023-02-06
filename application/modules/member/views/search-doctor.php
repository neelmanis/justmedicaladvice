<div class="container-fluid main_container">
	<div class="row">
		<div class="container-fluid dashboard_bg">    
			<div class="row searchbar_container hide">
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
			
			<div class="clearfix" style="height:20px;"></div>
			<div class="row" id="sticky-anchor">
				<div class="container-fluid maxwidth">
					<div class="container-fluid" data-sticky_parent>
						<div class="col-md-9 content_panel">
							<div class="container-fluid doc_search_filter hide">
								<div class="row">
									<div class="col-sm-4">
										<div class="row">
											<div class="form-group">
												<span class="icon map"></span>
												<input type="text" class="form-control" placeholder="Mumbai">
											</div>
											<div class="filter_drop" style="display:none">
												<div class="result_box">
													<a href="#">City 1</a>
													<a href="#">City 2</a>
													<a href="#">City 3</a>
													<a href="#">City 4</a>
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-8">
										<div class="row">
											<div class="form-group">
												<span class="icon search"></span>
												<input type="text" class="form-control" placeholder="Speciality, Doctors, etc.">
											</div>
											<div class="filter_drop" style="display:none">
												<div class="result_box">
												  <div class="result_tl txtdark">Speciality</div>
												  <a href="#">Pacemaker Surgery</a>
												  <a href="#">Coronary Artery Bypass Grafting</a>
												  <a href="#">Implantable Cardioverter Defibrillator (ICD)</a>
												</div>
												<div class="result_box">
												  <div class="result_tl txtdark">Doctors</div>
												  <a href="#">Dr. Ashok Seth</a>
												  <a href="#">Dr. Naresh Trehan</a>
												  <a href="#">Dr. Aparna Jaswal</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
        
							<div class="container-fluid serchPage_title">
								<div class="row">
									<div class="col-sm-8 ">
										<div class="row hide">
											<button class="filter_btn"><span class="filter_icon"></span></button>
											<div class="" style="margin-right:40px">
												<span class="txtdark" style="font-size:12px;line-height:18px;display:block;">Search result for : </span>
												<h3 class="txtblue">Dermatologist in Mumbai</h3>
											</div>
										</div>
									</div>
        	
									<div class="col-sm-4">
										<div class="row txtdark">
											<div class="foundcount"><span class="font_regular" style="font-size:14px"><?php if($doctorsData !== 'No Data'){echo sizeof($doctorsData);}else{ echo 0;} ?></span> matches found</div>
										</div>
									</div>
								</div>
							</div>
        
							<div class="doclist">
							<?php 
								if($doctorsData !== 'No Data'){
								foreach($doctorsData as $res){ 
							?>
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
										<div><button id="<?php echo $res['regId']; ?>" class="bluebtn dcb_btn follow"><span class="dcb_icon ic_1w"></span> Follow Doctor </button></div>
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
</div>