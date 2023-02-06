<div class="container-fluid main_container" id="sticky-anchor">
	<div class="row">
		<div class="login_signup_bg">
			<div class="loginbox postsignupbox">
			
				<p class="text-center" style="font-size:12px;line-height:20px;margin-bottom:3px">Help doctors to know you better, so as to personalize their answers</p>
				<div class="txtdark text-center" style="font-size:24px;margin-bottom:5px;">Lets create your profile</div>
				<p class="text-center" style="font-size:12px;line-height:20px;margin-bottom:10px">in 5 simple steps</p>
			
				<!--<ul class="stepsbox">
					<li rel="step_1" class="active"><span>1</span></li>
					<li rel="step_2" class="active"><span>2</span></li>
					<li rel="step_3"><span>3</span></li>
					<li rel="step_4"><span>4</span></li>
					<li rel="step_5"><span>5</span></li>
				</ul>-->
				
				<ul class="stepsbox">
					<li rel="step_1" class="active"><span>1</span></li>
					<li rel="step_2"><span>2</span></li>
					<li rel="step_3"><span>3</span></li>
					<li rel="step_4"><span>4</span></li>
				</ul>
				
				<form id="mem_profile_form" style="max-width:300px;margin:auto;">
			
				<!--	<div class="stepform" id="step_1">
						<div class="img_uploadBox fade_anim">
							<span>Upload<br>Profile Picture</span>
							<input type="file" class="img_uploadBtn" id="fileupload" name="fileupload">
							<div class="img_uploadPreview" id="imgPreview"></div>
						</div>
						<p style="font-size:12px;text-align:center;line-height:16px">Note: For best display, kindly upload the profile image in 1:1 ratio (squared-sized)<br><b>(Optional)<b></p>
						<div class="form-group">
							<button class="bluebtn btn btn_next" id="step1" rel="step_2" style="width:100%">Next</button> 
						</div>
					</div>-->
			
					<div class="stepform" id="step_1">			
						<div class="gender_radio text-center">
							<div><input type="radio" name="gender" value="female" id="female"><label for="female"><span class="female"></span> Female</label></div>
							<div class="txtdark font_regular">Select Gender <sup>*</sup></div>
							<div><input type="radio" name="gender" value="male" id="male"><label for="male"><span class="male"></span> Male</label></div>
						</div>
				
						<div class="form-group">
							<button class="bluebtn btn btn_next" id="step1" rel="step_2" style="width:100%">Next</button>
						</div>
					</div>
			
					<div class="stepform" id="step_2">
						<div class="form-group">
							<select id="age" name="age" class="form-control selectpicker" data-live-search="true">
								<option value="0">Your Age (in years) <sup>(required)</sup></option>
								<?php
									for($i=18; $i<100; $i++){
								?>
									<option value="<?php echo $i?>"><?php echo $i?> Years</option>
								<?php
									}
								?>
							</select>
						</div>						
						<div class="form-group">
							<button class="bluebtn btn btn_next" id="step2" rel="step_3" style="width:100%">Next</button>
						</div>
					</div>
			
					<div class="stepform" id="step_3">
						<div class="form-group">
							<select id="city" name="city" class="form-control selectpicker" data-live-search="true">
								<option value="no">Your Location <sup>(required)</sup></option>
								<?php
									foreach($location as $city){
								?>
									<option value="<?php echo $city->cityName.','.$city->stateName.','.$city->countryName;?>"><?php echo $city->cityName.'('.$city->countryName.')';?></option>
								<?php
									}
								?>
							</select>
						</div>
				
						<div class="form-group">
							<button class="bluebtn btn btn_next" id="step3" rel="step_4" style="width:100%">Next</button>
						</div>
					</div>
			
					<div class="stepform" id="step_4">
						<p class="text-center" style="font-size:13px;line-height:16px">Please select three or more topics,<br> for personalize experience (required)</p>
			
						<div class="topics_container">
							<?php 
							$alpha = array('a','b','c');
							$index = 0;
							foreach($parentCat as $parent){ ?>
							<div class="topic_heading txtdark font_regular active" rel="<?php echo $alpha[$index];?>" ><?php echo $parent->catName; ?></div>
							<div class="topic_holder" id="<?php echo $alpha[$index];?>" >
								<ul>
								<?php 
									$i=1;
									foreach($subCat[$parent->catName] as $cat){ 	
								?>
									<li>
										<input class="catCheck" type="checkbox" name="cat[]" value="<?php echo $cat->catId; ?>" id="<?php echo $alpha[$index]?><?php echo $i;?>" <?php if($alpha[$index] == 'a') echo "checked";?>>
										<label for="<?php echo $alpha[$index]?><?php echo $i;?>">
											<span class="imgtl"><?php echo $cat->catName; ?></span></label>
									</li>
								<?php 
										$i++; 
									} 
								?>
								</ul>
							</div>
							<?php  $index++; } ?>
						</div>
				
						<div class="form-group">
							<input type="submit" class="bluebtn btn" id="step4" style="width:100%" value="Create my profile"/>
						</div>
				
					</div>
				</form>
			</div>
		</div>	
	</div>    
</div>