<div class="form-panel">
<?php 
	if(is_array($editRecord)): 
?>
    <h4 class="mb">Edit Admin</h4>
		<form id="adminEdit" class="form-horizontal style-form adminusereditform" >
			<div id="formError" style="display: none;" class="alert alert-danger alert-dismissible"></div>
			<div id="formSuccess" style="display: none;" class="alert alert-success alert-dismissible"></div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label" for="username">User Name <span style="color:red;">*</span></label>
				<div class="col-sm-9">
					<input type="text" name="username" id="username" class="form-control" value="<?php echo $editRecord[0]->username;?>"/>
				</div>
			</div>	      

			<div class="form-group">
				<label class="col-sm-3 control-label" for="username">Module Rights <span style="color:red;">*</span></label>
				<div class="col-sm-9">
					<?php $rightsRec = explode(",",$editRecord[0]->rights);?>
					
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" class="form-control" value="1" <?php if(in_array("1",$rightsRec)){ echo "checked"; }?>/>Manage Member
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" class="form-control" value="2" <?php if(in_array("2",$rightsRec)){ echo "checked"; }?>/>Manage Doctor 
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" class="form-control" value="3" <?php if(in_array("3",$rightsRec)){ echo "checked"; }?>/>Manage Speciality 
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" class="form-control"  value="4" <?php if(in_array("4",$rightsRec)){ echo "checked"; }?>/>Manage Category
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" class="form-control"  value="5" <?php if(in_array("5",$rightsRec)){ echo "checked"; }?>/>Manage Blog
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" class="form-control"  value="6" <?php if(in_array("6",$rightsRec)){ echo "checked"; }?>/>Manage Video/Audio
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" class="form-control"  value="7"<?php if(in_array("7",$rightsRec)){ echo "checked"; }?>/>Manage Forum
					</div>
					<div class="checkdisplay hide">
						<input type="checkbox" name="module[]" class="form-control"  value="8" <?php if(in_array("8",$rightsRec)){ echo "checked"; }?>/>Manage Webinar
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" class="form-control"  value="9" <?php if(in_array("9",$rightsRec)){ echo "checked"; }?>/>Manage Degree
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" class="form-control"  value="10" <?php if(in_array("10",$rightsRec)){ echo "checked"; }?>/>Manage Location
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" class="form-control"  value="11" <?php if(in_array("11",$rightsRec)){ echo "checked"; }?>/>Manage Banner
					</div>
					<div class="checkdisplay hide">
						<input type="checkbox" name="module[]" class="form-control"  value="12" <?php if(in_array("12",$rightsRec)){ echo "checked"; }?>/>Manage Home Page
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" class="form-control"  value="13" <?php if(in_array("13",$rightsRec)){ echo "checked"; }?>/>Manage Events
					</div>
					<div class="checkdisplay hide">
						<input type="checkbox" name="module[]" class="form-control"  value="14" <?php if(in_array("14",$rightsRec)){ echo "checked"; }?>/>Manage Contact Us
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" class="form-control"  value="15" <?php if(in_array("15",$rightsRec)){ echo "checked"; }?>/>Manage Testimonials
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" class="form-control"  value="16" <?php if(in_array("16",$rightsRec)){ echo "checked"; }?>/>Manage FAQ
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" class="form-control"  value="17" <?php if(in_array("17",$rightsRec)){ echo "checked"; }?>/>Manage Subscribers
					</div>
				</div>
			</div>
			
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
				<div class="col-sm-6">
					<input type="submit" class="btn btn-primary btn-lg btn-block" id="banner_btn" value='Update'>
				</div>
			</div>
		</form>
		<?php endif; ?>
</div>			

<script>
$(document).ready(function(){	
	$("#adminEdit").on("submit",function(e){
		e.preventDefault();
		if(window.FormData != 'undefined'){
			var formdata = new FormData(this);
			$.ajax({
				type:"POST",
				url:baseUrl+"admin/editAdminAction",	
				data:formdata,
				processData : false,
				contentType : false,
				mimeType : 'multipart/form-data',
				beforeSend:function() {    
                    $("#pageLoader").show();
                },
				success:function(result){ 
                    $("#pageLoader").hide();
					if(result == 1){
                        window.scrollTo(0,0);
						$("#formSuccess").css("display","block");
						$("#formSuccess").html("Updated Successfully!!!").delay(9000).fadeOut(5000);
						window.location.href="<?php echo base_url()?>admin/listadmin";
					}else{
						window.scrollTo(0,0);
						$("#formError").css("display","block");
						$("#formError").html(result).delay(8000).fadeOut(5000);
					}
				}
			});
		}
	});
})
</script>  