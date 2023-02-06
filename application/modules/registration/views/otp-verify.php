<div class="container-fluid main_container" id="sticky-anchor">
	<div class="row">
		<div class="login_signup_bg">
			<div class="loginbox">
				<div class="txtdark text-center" style="font-size:24px;margin-bottom:30px;">OTP Verification</div>
				<p style="font-size:12px;line-height:20px;">Enter the OTP recieved as a SMS on your registered mobile number.</p>
				<div id="formError" style="display: none;" class="alert alert-danger"></div>
				<div id="formSuccess" style="display: none;" class="alert alert-success"></div>
				<form id="verify_otp" style="max-width:300px;margin:auto;display:block">
					<div class="form-group">
						<input type="text" name="otp" id="otp" class="form-control text-center" placeholder="Enter OTP">
					</div>
					<div class="form-group">
						<input type="submit" class="bluebtn btn" value="Verify & Proceed" style="width:100%">
					</div>				
				</form>
			
				<a href="javascript:;" id="resend" class="txtblue text-center font_regular" style="font-size:12px;display:block;margin:auto;">OTP not received ? Resend otp</a>
			</div>
		</div>		
	</div>    
</div>