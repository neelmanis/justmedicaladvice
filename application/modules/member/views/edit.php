<div class="form-panel">
<?php 
	if(is_array($editRecord)): 
?>
    <h4 class="mb">Edit Admin</h4>
		<form id="adminusereditform" class="form-horizontal style-form adminusereditform" >
			<div style="display: none;" class="form_error"></div>
			<div style="display: none;" class="form_success"></div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="username">User Name <span style="color:red;">*</span></label>
				<div class="col-sm-9">
					<input type="text" name="username" id="username" class="form-control" value="<?php echo $editRecord[0]->username;?>"/>
				</div>
			</div>	      
			<div class="form-group">
				<label class="col-sm-3 control-label" for="username">Email <span style="color:red;">*</span></label>
				<div class="col-sm-9">
					<input type="text" name="email" id="email" class="form-control" value="<?php echo $editRecord[0]->email;?>" />
				</div>
			</div>
			<!--
			<div class="form-group">
				<label class="col-sm-3 control-label" for="username">Module Rights <span style="color:red;">*</span></label>
				<div class="col-sm-9">
					<div class="checkdisplay">
						<?php $rightsRec = explode(",",$editRecord[0]->rights);?>
						<input type="checkbox" name="module[]" id="User"  value="1" <?php if(in_array("1",$rightsRec)){ echo "checked"; }?> />User Management
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" id="Categoty"  value="2" <?php if(in_array("2",$rightsRec)){ echo "checked"; }?>/>Category Management
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" id="Parking" class="form-control" value="3" <?php if(in_array("3",$rightsRec)){ echo "checked"; }?>/>Disease Management
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" id="Banner" class="form-control" value="4" <?php if(in_array("4",$rightsRec)){ echo "checked"; }?>/>Service Management 
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" id="Ride" class="form-control" value="5" <?php if(in_array("5",$rightsRec)){ echo "checked"; }?>/>Video Management
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" id="Food" class="form-control" value="6" <?php if(in_array("6",$rightsRec)){ echo "checked"; }?>/>Subscription Management
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" id="Other-Attraction" class="form-control" value="7" <?php if(in_array("7",$rightsRec)){ echo "checked"; }?>/>Cms Management
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" id="Event" class="form-control" value="8" <?php if(in_array("8",$rightsRec)){ echo "checked"; }?>/>Testimonials Management
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" id="Entry-Rate" class="form-control" value="9" <?php if(in_array("9",$rightsRec)){ echo "checked"; }?>/>Article Management
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" id="Entry-Rate" class="form-control" value="10" <?php if(in_array("10",$rightsRec)){ echo "checked"; }?>/>Hospital Management
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" id="Entry-Rate" class="form-control" value="11" <?php if(in_array("11",$rightsRec)){ echo "checked"; }?>/>Doctor Management
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" id="Entry-Rate" class="form-control" value="12" <?php if(in_array("12",$rightsRec)){ echo "checked"; }?>/>Wellness Management
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" id="Entry-Rate" class="form-control" value="13" <?php if(in_array("13",$rightsRec)){ echo "checked"; }?>/>Faq Management
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" id="Entry-Rate" class="form-control" value="14" <?php if(in_array("14",$rightsRec)){ echo "checked"; }?>/>Awards-Service Management
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" id="Entry-Rate" class="form-control" value="15" <?php if(in_array("15",$rightsRec)){ echo "checked"; }?>/>Web-Content Management
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" id="Entry-Rate" class="form-control" value="16" <?php if(in_array("16",$rightsRec)){ echo "checked"; }?>/>SEO Management
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" id="Entry-Rate" class="form-control" value="17" <?php if(in_array("17",$rightsRec)){ echo "checked"; }?>/>Patient Management
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" id="Entry-Rate" class="form-control" value="18" <?php if(in_array("18",$rightsRec)){ echo "checked"; }?>/>News Management
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" id="Entry-Rate" class="form-control" value="19" <?php if(in_array("19",$rightsRec)){ echo "checked"; }?>/>Career Management
					</div>
				</div>
			</div>
			-->
			
			<div class="form-group">
				<label class="col-sm-3 control-label">Status <span style="color:red;">*</span></label>
				<div class="col-sm-9">
					<select  name="status" id="category_status" class="form-control">
						<option value="1" <?php if($editRecord[0]->isActive == '1'){ echo "selected"; } ?>>Active</option>
						<option value="0"  <?php if($editRecord[0]->isActive == '0'){ echo "selected"; } ?>>Deactivate</option>
					</select>
				</div>
			</div>
			<input type="hidden" value="<?php echo $editRecord[0]->id;?>" name="adminId">

			<div class="form-group">
				<label class="col-sm-3 control-label">&nbsp;</label>
				<div class="col-sm-9">
					<input type="submit" class="btn btn-primary" id="banner_btn" value='Update'>
				</div>
			</div>
		</form>
		<?php endif; ?>
</div>			

<script>
$(document).ready(function(){	
	$("#adminusereditform").on("submit",function(e){
		e.preventDefault();
		if(window.FormData != 'undefined')
		{
			var formdata = new FormData(this);
		 $.ajax({
			type:"POST",
			url:baseUrl+"admin/adminUserEditAction",	
			data:formdata,
			processData : false,
			contentType : false,
			mimeType : 'multipart/form-data',
                         beforeSend:function() {    
                          $("#preloader").show();
                           },
			success:function(result){ 
                           $("#preloader").hide();
				if(result == 1)
				{
                                window.scrollTo(0,0);
				 $(".form_success").css("display","block");
				 $(".form_success").html("Updated Successfully!!!").delay(1000).fadeOut(5000);
				  window.location.href="<?php echo base_url()?>admin/listadmin";
				}
				else
				{
                window.scrollTo(0,0);
				$(".form_error").css("display","block");
				$(".form_error").html(result).delay(1000).fadeOut(5000);
				}
			
			   }
			})
		}
	});
})
</script>  