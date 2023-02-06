<div class="form-panel">
    <h4 class="mb">Change Password Form</h4>
		<form id="changepasswordform" class="form-horizontal style-form adminuserform" >
			<div id="formError" style="display: none;" class="alert alert-danger alert-dismissible"></div>
			<div id="formSuccess" style="display: none;" class="alert alert-success alert-dismissible"></div>
			    
				<div class="form-group">
				<label class="col-sm-3 control-label" for="username">Enter New Password</label>
				<div class="col-sm-9">
					<input type="password" name="password" id="password" class="form-control" />
				</div>
			</div>
			
			<input type="hidden" value="<?php echo $id; ?>" name="id">	
			<div class="form-group">
				<label class="col-sm-3 control-label">&nbsp;</label>
				<div class="col-sm-6">
					<input type="submit" class="btn btn-primary btn-lg btn-block" id="banner_btn" value='Save'>
				</div>
			</div>
		</form>
</div>			

<script>
$(document).ready(function(){	
	$("#changepasswordform").on("submit",function(e){
		e.preventDefault();
		if(window.FormData != 'undefined'){
			var formdata = new FormData(this);
			$.ajax({
				type:"POST",
				url:baseUrl+"admin/changePasswordAction",	
				data:formdata,
				processData : false,
				contentType : false,
				mimeType : 'multipart/form-data',
                beforeSend:function() {    
					$("#preloader").show();
				},
				success:function(result){ 
					$("#preloader").hide();
					if(result == 1){
						$("#formSuccess").css("display","block");
						$("#formSuccess").html("Password Updated!!!").delay(1000).fadeOut(5000);
						$('#changepasswordform')[0].reset();
						window.location.href="<?php echo base_url()?>admin/listadmin";
					}else{					
						window.scrollTo(0,0);
						$("#formError").css("display","block");
						$("#formError").html(result).delay(1000).fadeOut(5000);
					}
				}
			});
		}
	});
})
</script>  