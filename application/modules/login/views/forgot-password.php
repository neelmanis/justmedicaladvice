<div class="container-fluid main_container" id="sticky-anchor">
<div class="row">

<div class="login_signup_bg">

	<div class="loginbox">
    
    <div class="txtdark text-center" style="font-size:24px;margin-bottom:30px;">Forgot Password</div>
    
    <p style="font-size:12px;line-height:20px;">Provide us the email mobile no. of your registered account and we will send you an OTP to reset your password.</p>
    
	<div style="display: none;" class="formerror"><span class="errorcode" style="color:red"> </span></div>
	
    <form id="forgotPassword" style="max-width:300px;margin:auto;display:block">
    	
		<div class="form-group">
        	<input type="text" id="mobile" name="mobile" class="form-control text-center" placeholder="Mobile no.">
        </div>
        
    	<div class="form-group">
        	<input type="submit" class="bluebtn btn" value="Send OTP" style="width:100%">
        </div>    
    </form>
    
    <a href="<?php echo base_url()?>login" class="txtblue text-center font_regular" style="font-size:12px;display:block;margin:auto;">Back to Login</a>
        
    </div>


</div>	
    
</div>    
</div>