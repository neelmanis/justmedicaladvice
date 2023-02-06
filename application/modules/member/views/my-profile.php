<div class="container-fluid main_container">
	<div class="row">
		<div class="container-fluid dashboard_bg">
			<div class="row searchbar_container hide">
				<div class="searchbar_box">
					<div class="container-fluid maxwidth">
						<div class="container-fluid">
						<form style="margin-bottom:0;">
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
					<div class="center-block centerContainer">    	
						<div class="container-fluid breadcrum">
							My Profile
						</div>
						<?php
							$name = $memData[0]->name;
							$imgUrl = $memData[0]->profileImage;
							$email = $memData[0]->email;
							$isd = $memData[0]->isd;
							$mobile = $memData[0]->mobile;
							$age = $memData[0]->age;
							$gender = $memData[0]->gender;
							$city = $memData[0]->city;
							$height = $memData[0]->height;
							$weight = $memData[0]->weight;
							$bmi = $memData[0]->bmi;
							$ailments = $memData[0]->ailments;
							$allergies = $memData[0]->allergies;
							$familyHistory = $memData[0]->familyHistory;
							$alcohol = $memData[0]->alcohol;
							$tobacco = $memData[0]->tobacco;
							$other = $memData[0]->other;
						?>
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
													<input type="file" class="img_uploadBtn" name="fileupload" id="fileupload">
													<input type="hidden" name="imageName" id="imageName" value="<?php echo $imgUrl; ?>">
													<div class="img_uploadPreview" id="imgPreview">
														<img src="<?php echo base_url().$imgUrl;?>">
													</div>
												</div>
											</div>            
											<div class="col-sm-6 form-group member_profile_name">
												<label>Name</label>
												<input type="text" id="name" name="name" class="form-control" value="<?php echo $name;?>" />
											</div>
											
											<hr class="clearfix" style="">
										
											<div class="row">
												<div class="col-sm-6 form-group"><label>Email Id</label>
												<input type="text" id="email" name="email" class="form-control" value="<?php echo $email;?>" />
												</div>
												
												<div class="col-sm-6 form-group"><label>Mobile No.</label>
												<input type="text" id="mobile" name="mobile" class="form-control" value="<?php echo '+'.$isd.' '.$mobile; ?>" disabled />
												</div>
											</div>
										
											<div class="row">
												<div class="col-sm-6 form-group"><label>Age</label>
												<select class="form-control selectpicker" id="age" name="age">
												<?php for($i=18; $i<99; $i++){ ?>
												<option value="<?php echo $i;?>" <?php if($i == $age)echo 'selected'; ?>><?php echo $i;?></option> 
												<?php } ?>
												</select>
												</div>
												
												<div class="col-sm-6 form-group"><label>Gender</label>
												<select class="form-control selectpicker" name="gender" id="gender">
													<option value="male" <?php if($gender=="male")echo 'selected';?>>Male</option>
													<option value="female" <?php if($gender=="female")echo 'selected';?>>Female</option>
												</select>
												</div>
											</div>
										
											<div class="row">
												<div class="col-sm-6 form-group"><label>Location</label>
												<input type="text" class="form-control" id="city" name="city" value="<?php echo $city;?>" />
												</div>
											</div>
											
											<hr class="clearfix" style="">
											
											<div class="row">
												<div class="col-sm-6 form-group"><label>Weight (Kg)</label>
												<input type="number" id="weight" name="weight" class="form-control" value="<?php echo $weight;?>" />
												</div>
												
												<div class="col-sm-6 form-group"><label>Height (cm)</label>
												<input type="number" id="height" name="height" class="form-control" value="<?php echo $height;?>" />
												</div>
											</div>
										
											<div class="row">
												<div class="col-sm-6 form-group"><label>BMI</label>
												<input type="text" class="form-control" id="bmi" name="bmi" value="<?php echo $bmi;?>" readonly />
												</div>
												<div class="col-sm-6 form-group">
													<div class="clearfix" style="height:30px;"></div>
													<div id="bmiResult"></div>
												</div>
											</div>
											
											<hr class="clearfix" style="">
											
											<div class="row">
												<div class="col-sm-12 form-group"><label>Prevailing Ailments</label>
												<input type="text" id="ailments" name="ailments" class="form-control" value="<?php echo $ailments;?>" />
												</div>
												
												<div class="col-sm-12 form-group"><label>Allergies</label>
												<input type="text" id="allergies" name="allergies" class="form-control" value="<?php echo $allergies; ?>"/>
												</div>
												
												<div class="col-sm-12 form-group"><label>Family History</label>
												<textarea class="form-control" style="min-height:100px;max-width:100%;" id="familyHistory" name="familyHistory"  placeholder="Write a brief about your Family History..."><?php echo $familyHistory; ?></textarea>
												</div>
												
												<div class="col-sm-6 form-group"><label>Alcohol</label>
												<select class="form-control selectpicker" name="alcohol" id="alcohol">
													<option value="1" <?php if($alcohol=="1")echo 'selected';?>>Yes</option>
													<option value="0" <?php if($alcohol=="0")echo 'selected';?>>No</option>
												</select>
												</div>
												
												<div class="col-sm-6 form-group"><label>Tobacco</label>
												<select class="form-control selectpicker" name="tobacco" id="tobacco">
													<option value="1" <?php if($tobacco=="1")echo 'selected';?>>Yes</option>
													<option value="0" <?php if($tobacco=="0")echo 'selected';?>>No</option>
												</select>
												</div>
												
												<div class="col-sm-12 form-group"><label>Other</label>
												<input type="text" id="other" name="other" class="form-control" value="<?php echo $other;?>" placeholder="Specify if any"/>
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