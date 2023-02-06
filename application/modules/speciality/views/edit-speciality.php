<div class="form-panel">
	<h4 class="mb">Edit Speciality</h4>
    <?php 
	   if(is_array($getData)){
	?>
	<form id="editSpecialityForm" class="form-horizontal style-form" >
		<div id="formError" style="display: none;" class="alert alert-danger alert-dismissible"></div>
		<div id="formSuccess" style="display: none;" class="alert alert-success alert-dismissible"></div>

		<div class="form-group">
	       	<label class="col-sm-3 control-label" for="username">Speciality Name <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
				<input type="text" name="speciality_name" id="speciality_name" class="form-control" value="<?php echo $getData[0]->spName?>"/>
			</div>
		</div>
		
	    <div class="form-group">
		    <label class="col-sm-3 control-label">Status <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
			    <select  name="speciality_status" id="speciality_status" class="form-control">  
			        <option <?php if($getData[0]->isActive == '1'){ echo "selected"; } ?> value="1">Active</option>
			        <option <?php if($getData[0]->isActive == '0'){ echo "selected"; } ?> value="0">Deactivate</option>
			    </select>
		    </div>
		</div>
		
		<input type="hidden" name="spId" value="<?php echo $getData[0]->spId;?>">
		<input type="hidden" name="spNameOg" id="spNameOg" value="<?php echo $getData[0]->spName?>"/>
		<div class="form-group">
			<label class="col-sm-3 control-label">&nbsp;</label>
			<div class="col-sm-6">
				<input type="submit" class="btn btn-primary btn-lg btn-block" id="banner_btn" value="Update">
			</div>
		</div>
	</form>
    <?php } ?>
</div>			
<script>
var baseUrl = '<?php echo base_url() ?>';
$(document).ready(function(){	
	$("#editSpecialityForm").on("submit",function(e){
		e.preventDefault();
		if(window.FormData != 'undefined'){
			var formdata = new FormData(this);
	
			$.ajax({
				type:"POST",
				url:"<?php echo base_url();?>speciality/editSpecialityAction",	
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
						$("#formSuccess").css("display","block");
						$("#formSuccess").html("The <b>speciality<b> updated successfully.").delay(5000).fadeOut(5000);
						window.location.href = baseUrl+"speciality/listall";
					}else if(result == 2){
						$("#formError").css("display","block");
						$("#formError").html("<b>speciality<b> Already Exists!").delay(3000).fadeOut(5000);
					}else{
						$("#formError").css("display","block");
						$("#formError").html(result).delay(1000).fadeOut(5000);
					}
				}
			});
		}
	});
})
</script>			
			