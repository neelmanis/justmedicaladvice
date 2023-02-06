<div class="form-panel">
    <h4 class="mb">Edit Forum</h4>
	<form id="editForum" class="form-horizontal style-form categoryform" >
		<div id="formError" style="display: none;" class="alert alert-danger alert-dismissible"></div>
		<div id="formSuccess" style="display: none;" class="alert alert-success alert-dismissible"></div>
         
		<?php if(is_array($getData)) { 	?>  

		<div class="form-group">
		    <label class="col-sm-3 control-label">Select Speciality <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
			    <select  class="js-example-basic-multiple" name="speciality" id="speciality" class="form-control">
			    	<option disabled selected>Choose Speciality</option>
					<?php if(is_array($speciality)){
							foreach($speciality as $sp){ 
					?>
						<option value="<?php echo $sp->spId;?>" <?php if($getData[0]->specialityId == $sp->spId){echo 'selected';}?>><?php echo $sp->spName;?></option>	
					<?php	
						} } 
					?>
			    </select>
		    </div>
		</div>
		
		<div class="form-group">
	       	<label class="col-sm-3 control-label" for="username">Question <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
				<textarea name="question" id="question" class="form-control"><?php echo $getData[0]->question;?></textarea>
			</div>
		</div>
		
		<div class="form-group">
		    <label class="col-sm-3 control-label">Status <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
			    <select  name="status1" id="status1" class="form-control">
			    	<option disabled>Choose One</option>
			        <option <?php if($getData[0]->isActive == '1'){ echo "selected"; }?> value="1">Active</option>
			        <option <?php if($getData[0]->isActive == '0'){ echo "selected"; }?> value="0">Deactivate</option>
			    </select>
		    </div>
		</div>
		
		<div class="form-group">
		    <label class="col-sm-3 control-label">Visible To <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
			    <select  name="visible" id="visible" class="form-control">
			        <option value="mem" <?php if($getData[0]->visibleTo == 'mem'){ echo "selected"; }?>>Only For Member</option>
			        <option value="doc" <?php if($getData[0]->visibleTo == 'doc'){ echo "selected"; }?>>Only For Doctor</option>
			        <option value="all" <?php if($getData[0]->visibleTo == 'all'){ echo "selected"; }?>>All</option>
			    </select>
		    </div>
		</div>
		
        <input type="hidden" name="forumId" value="<?php echo $getData[0]->forumId; ?>">
        <input type="hidden" name="forumSlug" value="<?php echo $getData[0]->slug; ?>">
		
        <?php } ?>
		
		<div class="form-group">
			<label class="col-sm-3 control-label">&nbsp;</label>
			<div class="col-sm-6">
				<input type="submit" class="btn btn-primary btn-lg btn-block" id="banner_btn" value='Update'>
			</div>
		</div>
	</form>
</div>			
<script>
var baseUrl = '<?php echo base_url() ?>';
$(".js-example-basic-multiple").select2();	
$(document).ready(function(){	
	$("#editForum").on("submit",function(e){
		e.preventDefault();
		
		if(window.FormData != 'undefined'){
			var formdata = new FormData(this);
	
			$.ajax({
				type:"POST",
				url:"<?php echo base_url();?>forum/editForumAction",	
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
						$(window).scrollTop(0);
						$("#formSuccess").css("display","block");
						$("#formSuccess").html("<b>Forum is updated successfully !!</b>").delay(5000).fadeOut();
						window.location.reload(true);
					}else if(result == 2){
						$(window).scrollTop(0);
						$("#formError").css("display","block");
						$("#formError").html("<b>Entered Question already exist.</b>").delay(5000).fadeOut();
					}else{
						$(window).scrollTop(0);
						$("#formError").css("display","block");
						$("#formError").html(result).delay(5000).fadeOut();
					}
				}
			});
		}
	});
})
</script>			