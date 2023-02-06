<div class="container-fluid main_container" id="sticky-anchor">
	<div class="row">
		<div class="login_signup_bg">
			<div class="loginbox">
				<div id="formError" style="display: none;" class="alert alert-danger"></div>
				<div id="formSuccess" style="display: none;" class="alert alert-success"></div>
				<form id="signup" style="max-width:315px;margin:auto;display:block">
				
					<div class="form-group txtdark font_regular">
						<strong>Signup as a </strong>
						<div class="doc_YN_radio">
							<input type="radio" name="Doc_YN" value="mem" id="Doc_Y" checked><label for="Doc_Y">Member</label>
							<input type="radio" name="Doc_YN" value="doc" id="Doc_N"><label for="Doc_N">Doctor</label>
						</div>
					</div>
					
					<div class="form-group">
						<input type="text" name="name" id="name" class="form-control" placeholder="Full Name">
					</div>
					
					<!--<div class="form-group">
						<input type="email" name="email" id="email" class="form-control" placeholder="Email">
						<p style="margin:3px 0 0;font-size:10px;line-height:14px;">(Optional)</p>
					</div>-->
					
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-addon signupselect">
								<select id="isd" name="isd">
									<?php foreach($isd as $val){ ?>
									<option value="<?php echo $val->phonecode?>"><?php echo $val->phonecode.' ('.$val->shortname.')'?></option>
									<?php } ?>
								</select>
							</div>
							<input type="text" id="mobile" name="mobile" class="form-control" placeholder="Mobile No.">
						</div>
						<!--<p style="margin:3px 0 0;font-size:10px;line-height:14px;">You'll receive an SMS on this number with an OTP for verification.</p>-->
						<label for="mobile" generated="true" class="error" style="display:block;"></label>
					</div>
					
					<div class="form-group hide">
						<input type="password" name="pass" id="pass" class="form-control" placeholder="Enter Password">
					</div>
					
					<div class="form-group hide">
						<input type="password" name="cnfpass" id="cnfpass" class="form-control" placeholder="Re-type Password">
					</div>
					
					<div class="form-group">
						<input type="submit" class="bluebtn btn" value="Sign Up" style="width:100%">
					</div>
					
					<p style="font-size:12px;line-height:18px;margin-bottom:20px;">By signing up, you agree to our <a href="<?php echo base_url();?>terms-and-conditions" class="txtblue font_regular">Terms</a> and <a href="<?php echo base_url();?>privacy-policy" class="txtblue font_regular">Privacy Policy</a></p>
					
					<p style="font-size:16px;line-height:18px;text-align:center">Already had an account? <a href="<?php echo base_url()?>login" class="txtblue font_regular">Log in</a></p>
				
				</form>
			</div>
		</div>    
	</div>    
</div>