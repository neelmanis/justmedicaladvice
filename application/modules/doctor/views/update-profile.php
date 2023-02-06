<div class="container-fluid main_container">
	<div class="row">
		<div class="container-fluid dashboard_bg">    
			<div class="clearfix" style="height:20px;"></div>
			<div class="row" id="sticky-anchor">
				<div class="container-fluid maxwidth">
					<div class="center-block centerContainer">    	
						<div class="container-fluid breadcrum">My Profile</div>
						<div class="clearfix"></div>
						<div class="container-fluid">
							<div class="profile_main">
								<div id="formError" style="display: none;" class="alert alert-danger"></div>
								<form id="editProfile">
									<div class="row">
										<div class="container-fluid">            
											
											<div class="member_profile_pic">
												<div class="img_uploadBox fade_anim">
												<span>Upload or Change<br>Profile Picture</span>
												<input type="file" name="upload" class="img_uploadBtn" id="fileupload">
												<div class="img_uploadPreview" id="imgPreview">
													<img src="<?php echo base_url().$doctor['image']?>">
													<input type="hidden" name="profileImg" value="<?php echo $doctor['image']?>">
												</div>
												</div>
											</div>            
											
											<div class="col-sm-6 form-group member_profile_name">
												<label class="txtdark">Name</label>
												<input type="hidden" class="form-control" id="regId" name="regId" value="<?php echo $doctor['regId'];?>" />
												<input type="text" class="form-control" id="name" name="name" value="<?php echo $doctor['name'];?>" />
											</div>
			
											<hr class="clearfix" style="">
											<p><strong class="txtdark">Basic Details</strong></p>
		
											<div class="row">
												<div class="col-sm-6 form-group"><label class="txtdark">Email Id</label>
												<input type="text" class="form-control" id="email" name="email" value="<?php echo $doctor['email'];?>" />
												</div>
												
												<div class="col-sm-6 form-group"><label class="txtdark">Mobile No.</label>
												<input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo $doctor['mobile'];?>" disabled />
												</div>
											</div>
		
											<div class="row">
												<div class="col-sm-6 form-group"><label class="txtdark">Gender</label>
												<select id="gender" name="gender" class="form-control selectpicker">
													<option value="male" <?php if($doctor['gender']=='male')echo "selected";?>>Male</option>
													<option value="female" <?php if($doctor['gender']=='female')echo "selected";?>>Female</option>
												</select>
												</div>
												
												<div class="col-sm-6 form-group"><label class="txtdark">Location</label>
												<input type="text" class="form-control" id="city" name="city" value="<?php echo $doctor['city'];?>" />
												</div>
											</div>
			
											<div class="row">
												<div class="col-sm-12 form-group"><label class="txtdark">Introduce yourself</label>
													<textarea class="form-control" style="min-height:100px;max-width:100%;" id="myself" name="myself"  placeholder="Write a brief about yourself..."><?php echo $doctor['myself'];?></textarea>
												</div>
											</div>
			
											<hr class="clearfix" style="">
			
											<p><strong class="txtdark">Professional Details</strong></p>
		
											<div class="row">
												<div class="col-sm-6 form-group"><label class="txtdark">Speciality</label>
												<select id="speciality" name="speciality[]" class="form-control selectpicker" multiple data-live-search="true" title="Select Speciality (Required)">
												<?php 
													$spList = explode(",",$doctor['speciality']);
													foreach($speciality as $sp){ 
												?>
													<option value="<?php  echo $sp->spId; ?>" <?php if(in_array($sp->spId,$spList)) echo "selected";?>><?php  echo $sp->spName; ?></option>	
												<?php  } ?>
												</select>
												</div>
												
												<div class="col-sm-6 form-group"><label class="txtdark">Degree</label>
												<select id="degree" name="degree[]" class="form-control selectpicker" multiple data-live-search="true" title="Select Degree (Required)">
												<?php 
													$degList = explode(",",$doctor['degree']);
													foreach($degree as $deg){ 
												?>
													<option value="<?php echo $deg->degreeId; ?>" <?php if(in_array($deg->degreeId,$degList)) echo "selected";?>><?php  echo $deg->name; ?></option>	
												<?php  } ?>
												</select>
												</div>
											</div>
		
											<div class="row">
												<div class="col-sm-6 form-group"><label class="txtdark">Years of experience</label>
												<select id="experience" name="experience" class="form-control selectpicker">
												<option value="">Years of experience (Required)</option>
												<?php  
													$i = 1;
													while($i<=70){
												?>
													<option value="<?php echo $i;?>" <?php if($doctor['experience'] == $i) echo 'selected'; ?>><?php echo $i; ?> years</option>
												<?php
														$i++;
													}
												?>
												</select>
												</div>
												
												<div class="col-sm-6 form-group"><label class="txtdark">Languages Spoken</label>
												<?php 
													$langList = explode(",",$doctor['language']);
												?>
												<select id="language" name="language[]" class="form-control selectpicker" multiple data-live-search="true" title="Select Languages">
													<option value="English" <?php if(in_array("English",$langList)) echo "selected";?> >English</option>
													<option value="Hindi" <?php if(in_array("Hindi",$langList)) echo "selected";?>>Hindi</option>
													<option value="Marathi" <?php if(in_array("Marathi",$langList)) echo "selected";?>>Marathi</option>
													<option value="Gujarati" <?php if(in_array("Gujarati",$langList)) echo "selected";?>>Gujarati</option>
													<option value="Bengali" <?php if(in_array("Bengali",$langList)) echo "selected";?>>Bengali</option>
												</select>
												</div>
											</div>
		
											<div class="row">
												<div class="col-sm-12"><label class="txtdark">Education &amp; Training</label>
													
													<?php if($doctor['education'] == ''){ 
														$x = 1;
														echo '<input type="hidden" id="x" value="'.$x.'"/>';
													?>
													<div class="form-group">
													<input type="text" class="form-control" name="education[]" value="" placeholder="Enter College or Institute Name">   
													</div>		
													<div class="form-group field_wrapper_edu">
													</div>
													<?php } ?>
													
													<?php if($doctor['education'] !== ''){ 
														$eduList = explode("#",$doctor['education']);
														$x = sizeof($eduList);
														echo '<input type="hidden" id="x" value="'.$x.'"/>';
													?>
													<div class="form-group field_wrapper_edu">
													<?php for($i=0; $i< $x; $i++){ 
														if($i == 0){ 
													?>
													<div class="form-group">
													<input type="text" class="form-control" name="education[]" value="<?php echo $eduList[$i];?>" placeholder="Enter College or Institute Name">   
													</div>
													<?php }else{ ?>
													<div class="input-group">
													<input type="text" class="form-control" name="education[]" value="<?php echo $eduList[$i];?>" placeholder="Enter College or Institute Name">
													<span class="input-group-btn"><a href="javascript:void(0);" class="btn bluebtn remove_button_edu" ata-toggle="tooltip" data-original-title="Delete">X</a></span>
													</div>
													<div class="clearfix" style="height:5px;"></div>
													<?php } } ?>
													</div>
													<?php } ?>	
													<div class="form-group">
														<button class="btn bluebtn add_button_edu">Add more</button>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12"><label class="txtdark">Memberships</label>
													<?php if($doctor['membership'] == ''){ 
														$y = 1;
														echo '<input type="hidden" id="y" value="'.$y.'"/>';
													?>
													<div class="form-group"><input type="text" class="form-control" name="membership[]" value="" placeholder="Enter Membership Name">                    
													</div>
													<div class="form-group field_wrapper_mem"> <!-- Append this div -->
													</div>
													<?php } ?>
													
													<?php if($doctor['membership'] !== ''){ 
														$memList = explode("#",$doctor['membership']);
														$y = sizeof($memList);
														echo '<input type="hidden" id="y" value="'.$y.'"/>';
													?>
													<div class="form-group field_wrapper_mem"> <!-- Append this div -->
													<?php for($i=0; $i < $y; $i++){ 
														if($i == 0){ 
													?>
													<div class="form-group"><input type="text" class="form-control" name="membership[]" value="<?php echo $memList[$i]; ?>" placeholder="Enter Membership Name">                    
													</div>
													<?php }else{ ?>
													<div class="input-group"><input type="text" class="form-control" name="membership[]" value="<?php echo $memList[$i]; ?>" placeholder="Enter Membership Name">
													<span class="input-group-btn"><a href="javascript:void(0);" class="btn bluebtn remove_button_mem" ata-toggle="tooltip" data-original-title="Delete">X</a></span>
													</div>
													<div class="clearfix" style="height:5px;"></div>
													<?php } } ?>
													</div>
													<?php } ?>
													<div class="form-group">
													<button class="btn bluebtn add_button_mem">Add more</button>
													</div>

												</div>
											</div>
			
											<hr class="clearfix" style="">
											<p><strong class="txtdark">Clinic Details</strong></p>
			
											<div class="row">
												<div class="col-sm-6 form-group"><label class="txtdark">Clinic Address</label>
													<textarea class="form-control" id="caddress" name="caddress" style="min-height:130px;max-width:100%;"><?php echo $doctor['clinicAddress'];?></textarea>
												</div>
												<div class="col-sm-6">
												<div class="row">
													<div class="col-sm-12  form-group"><label class="txtdark">Contact Numbers</label>
													<input type="text" class="form-control" id="contacts" name="contacts" value="<?php echo $doctor['contacts'];?>" />
													</div>
													
													<div class="col-sm-12  form-group"><label class="txtdark">Days & Timings</label>
													<input type="text" class="form-control" id="timing" name="timing" value="<?php echo $doctor['timing'];?>" placeholder="eg. Mon-Sat : 9am - 12pm" />
													</div>                    
												</div>
												</div>
											</div>
							
											<div class="row text-center"><input type="submit" class="btn bluebtn" value="Save Changes" style="width:150px;" /></div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>	
	</div>    
</div>