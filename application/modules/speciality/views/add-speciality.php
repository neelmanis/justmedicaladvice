<div class="form-panel">
    <h4 class="mb">Add Speciality</h4>
	<form id="addSpecialityForm" class="form-horizontal style-form categoryform" >
		<div id="formError" style="display: none;" class="alert alert-danger alert-dismissible"></div>
		<div id="formSuccess" style="display: none;" class="alert alert-success alert-dismissible"></div>
			
		<div class="form-group">
	       	<label class="col-sm-3 control-label" for="username">Speciality Name <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
				<input type="text" name="speciality_name" id="speciality_name" class="form-control" />
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
$(document).ready(function(){	
	$("#addSpecialityForm").on("submit",function(e){
		e.preventDefault();
		if(window.FormData != 'undefined'){
			var formdata = new FormData(this);
	
			$.ajax({
				type:"POST",
				url:baseUrl + "speciality/addSpecialityAction",	
				data:formdata,
				processData : false,
				contentType : false,
				mimeType : 'multipart/form-data',
				beforeSend: function() {    
                    $("#pageLoader").show();
                },
				success:function(result){
					$("#pageLoader").hide();
                    if(result == 1){
						$("#formSuccess").css("display","block");
						$("#formSuccess").html("New <b>speciality<b> added successfully.").delay(5000).fadeOut(5000);
						$("#addSpecialityForm")[0].reset();
						window.location.href = baseUrl+"speciality/listall";
					}else if(result == 2){
						$("#formError").css("display","block");
						$("#formError").html("<b>speciality<b> Already Exists!").delay(5000).fadeOut(5000);
					}else{
						$("#formError").css("display","block");
						$("#formError").html(result).delay(5000).fadeOut(5000);
					}
				}
			})
		}
	});
})
</script>			
			