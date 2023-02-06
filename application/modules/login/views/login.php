<div class="container-fluid main_container" id="sticky-anchor">
<div class="row">

<div class="login_signup_bg">

	<div class="loginbox">
    
    <div class="txtdark text-center" style="font-size:24px;margin-bottom:30px;">Welcome <span class="txtblue" style="font-size:35px">&#9786;</span></div>
    
    <form id="loginForm" style="max-width:300px;margin:auto;display:block">
	
		<div id="formError" style="display: none;" class="alert alert-danger"></div>
		<div id="formSuccess" style="display: none;" class="alert alert-success"></div>
		
    	<div class="form-group">
        	<input type="text" name="mobile" id="mobile" class="form-control text-center" placeholder="Mobile no.">
        </div>
        
    	<div class="form-group">
        	<input type="password" id="pass" name="pass" class="form-control text-center" placeholder="Password">
        </div>
        
    	<div class="form-group">
        	<div class="chkbox"><input type="checkbox" id="setC" name="setC" value="setC" checked><label></label></div> Keep me signed in
        </div>
        
    	<div class="form-group">
        	<input type="submit" class="bluebtn btn" value="Log In" style="width:100%">
        </div>
    
    </form>
    
    <p><a href="<?php echo base_url()?>forgot-password" class="txtblue text-center font_regular" style="font-size:12px;display:block;margin:auto;">Forgot password?</a></p>
    
    <p style="font-weight: bold; font-size:16px;line-height:18px;text-align:center"> New Member ? <a href="<?php echo base_url()?>signup" class="txtblue font_regular">Sign up Now</a></p>
    
    </div>


</div>	
    
</div>    
</div>