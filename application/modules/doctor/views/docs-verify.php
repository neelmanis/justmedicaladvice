<div class="container-fluid main_container" id="sticky-anchor">
	<div class="row">
		<div class="login_signup_bg">
			<div class="loginbox postsignupbox">
			
				<p class="text-center" style="line-height:20px;margin:0 auto 10px;">You are just a few steps away from accessing the JUST MEDICAL ADVICE platform.
				Provide the below mentioned documents for your account verification </p><p class="text-center">OR</p><p class="text-center"> you can send your documents to us at <strong>documents@justmedicaladvice.com</strong> with your name and contact details and we shall help you with the same.</p>
				<div class="txtdark text-center" style="font-size:24px;margin-bottom:20px;">Upload documents for verification</div>
				<div id="formError" style="display: none;" class="alert alert-danger"></div>
				<div id="formSuccess" style="display: none;" class="alert alert-success"></div>
				<form style="max-width:300px;margin:auto" id="doc_verify">
				
					<div class="form-group">
						<div class="file_upload">
							<input type="file" id="govproof" name="file[]" onChange="document.getElementById('identity_proof').value = this.value;">
							<div class="file_field"><input type="text" value="" placeholder="Identity proof" class="form-control" id="identity_proof"></div>
						</div>
						<p style="font-size:12px;line-height:18px;color:#999;margin-top:4px">Add Government issued identity proof</p>
					</div>
					
					<div class="form-group">
						<div class="file_upload">
							<input type="file" id="degreeproof" name="file[]" onChange="document.getElementById('degree_proof').value = this.value;">
							<div class="file_field"><input type="text" value="" placeholder="Degree proof" class="form-control" id="degree_proof"></div>
						</div>
						<p style="font-size:12px;line-height:18px;color:#999;margin-top:4px">Add your highest qualification medical degree proof</p>
					</div>
					
					<div class="form-group">
						<div class="file_upload">
							<input type="file" id="medproof" name="file[]" onChange="document.getElementById('registration_proof').value = this.value;">
							<div class="file_field"><input type="text" value="" placeholder="Registration proof" class="form-control" id="registration_proof"></div>
						</div>
						<p style="font-size:12px;line-height:18px;color:#999;margin-top:4px">Add your medical registration proof</p>
					</div>
					
					<div class="clearfix"></div>
					
					<div class="form-group">
						<input type="submit" class="bluebtn btn" value="Save & Proceed" style="width:100%;">
					</div>
				
				</form>
				
			</div>
		</div>		
	</div>    
</div>
