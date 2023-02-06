<div class="form-panel">
    <h4 class="mb">Add Forum</h4>
	<form id="forumForm" class="form-horizontal style-form categoryform" >
		<div id="formError" style="display: none;" class="alert alert-danger alert-dismissible"></div>
		<div id="formSuccess" style="display: none;" class="alert alert-success alert-dismissible"></div>
		
		<div class="form-group">
		    <label class="col-sm-3 control-label">Select Speciality <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
			    <select  class="js-example-basic-multiple" name="speciality" id="speciality" class="form-control">
			    	<option disabled selected>Choose Speciality</option>
					<?php if(is_array($speciality)){
							foreach($speciality as $sp){ 
					?>
						<option value="<?php echo $sp->spId;?>"><?php echo $sp->spName;?></option>	
					<?php	
						} } 
					?>
			    </select>
		    </div>
		</div>
		
		<div class="form-group">
	       	<label class="col-sm-3 control-label" for="username">Question <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
				<textarea name="question" id="question" class="form-control"></textarea>
			</div>
		</div>
		
		<div class="form-group">
		    <label class="col-sm-3 control-label">Visible To </label>
		    <div class="col-sm-9">
			    <select  name="visible" id="visible" class="form-control">
			        <option value="mem">Only For Members</option>
			        <option value="doc">Only For Doctors</option>
			        <option value="all" selected>All</option>
			    </select>
		    </div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-3 control-label">&nbsp;</label>
			<div class="col-sm-6">
				<input type="submit" class="btn btn-primary btn-lg btn-block" id="banner_btn" value='Save'>
			</div>
		</div>
	</form>
</div>			

<script>
var baseUrl = '<?php echo base_url() ?>';
$(".js-example-basic-multiple").select2();	
$(document).ready(function(){	
			   
	$("#forumForm").on("submit",function(e){
		e.preventDefault();
		
		if(window.FormData != 'undefined'){
			var formdata = new FormData(this);

			$.ajax({
				type:"POST",
				url:"<?php echo base_url();?>forum/addForumAction",	
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
						$("#forumForm")[0].reset();
						$(window).scrollTop(0);
						$("#formSuccess").css("display","block");
						$("#formSuccess").html("<b> New Forum added successfully. </b>").delay(5000).fadeOut();
					}else if(result == 2){
						$(window).scrollTop(0);
						$("#formError").css("display","block");
						$("#formError").html("<b>Question Already Exist !!</b>").delay(5000).fadeOut();
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