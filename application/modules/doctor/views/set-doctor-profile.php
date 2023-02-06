<div class="container-fluid main_container" id="sticky-anchor">
	<div class="row">
		<div class="login_signup_bg">
			<div class="loginbox postsignupbox">
				<p class="text-center" style="line-height:20px;margin:0 auto 10px;max-width:400px">Hello <strong><?php if(isset($uname)){ echo $uname[0]->name; }?></strong> the below details help us connect you better with relevant patients and caretakers worldwide</p>
				<div class="txtdark text-center" style="font-size:24px;margin-bottom:20px;">Lets create your profile</div>
				<div id="formError" style="display: none;" class="alert alert-danger"></div>
				<div id="formSuccess" style="display: none;" class="alert alert-success"></div>
				<form id="doc_profile">
				
					<div class="img_uploadBox fade_anim">
						<span>Upload<br>Profile Picture</span>
						<input type="file" class="img_uploadBtn" name="image" id="image">
						<div class="img_uploadPreview" id="imgPreview"></div>
					</div>
					
					<p style="font-size:12px;text-align:center;line-height:16px;">Note: For best display, kindly upload the profile image in 1:1 ratio (squared-sized) (Optional)</p>
				
					<div class="gender_radio text-center">
						<div>
							<input type="radio" name="gender" id="female" value="female"><label for="female"><span class="female"></span> Female</label>
						</div>
						<div class="txtdark font_regular">Select Gender <sup>*</sup></div>
						<div>
							<input type="radio" name="gender" id="male" value="male"><label for="male"><span class="male"></span> Male</label>
						</div>
					</div>
					<div class="text-center"><label for="gender" generated="true" class="error"></label></div>
					
					<div class="row">
						<div class="form-group col-sm-12">
							<input type="text" id="email" name="email" class="form-control" placeholder="Enter Your Email (Required)" />
						</div>
						
						<div class="form-group col-sm-6">
							<select id="speciality" name="speciality[]" class="form-control selectpicker" multiple data-live-search="true" title="Select Speciality (Required)">
								<?php if(isset($speciality)){
									foreach($speciality as $sp){ 
								?>
									<option value="<?php  echo $sp->spId; ?>"><?php  echo $sp->spName; ?></option>	
								<?php  } } ?>
							</select>
						</div>
				
						<div class="form-group col-sm-6">
							<select id="degree" name="degree[]" class="form-control selectpicker" multiple data-live-search="true" title="Select Degree (Required)">
							<?php if(isset($degree)){
									foreach($degree as $deg){ 
								?>
									<option value="<?php echo $deg->degreeId; ?>"><?php  echo $deg->name; ?></option>	
								<?php  } } ?>
							</select>
						</div>
					</div>
					
					<div class="row">
						<div class="form-group col-sm-6">
							<select id="experience" name="experience" class="form-control selectpicker">
							<option value="">Years of experience (Required)</option>
							<?php  
								$i = 1;
								while($i<=70){
							?>
								<option value="<?php echo $i; ?>"><?php echo $i; ?> years</option>
							<?php
									$i++;
								}
							?>
							</select>
						</div>
					
						<div class="form-group col-sm-6">
							<select id="city" name="city" class="form-control selectpicker" data-live-search="true">
								<option value="">Your Location (Required)</option>
									<?php
										foreach($location as $city){
									?>
										<option value="<?php echo $city->cityName.','.$city->stateName.','.$city->countryName;?>"><?php echo $city->cityName.'('.$city->countryName.')';?></option>
									<?php
										}
									?>
							</select>
						</div>
						
					</div>
				
					<div class="clearfix"></div>
					
					<div class="row">
						<div class="form-group col-sm-6 col-sm-push-3">
							<input type="submit" class="bluebtn btn" value="Next" style="width:100%;">
						</div>
					</div>				
				</form>
			</div>
		</div>		
	</div>    
</div>