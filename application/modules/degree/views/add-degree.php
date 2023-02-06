<div class="form-panel">
    <h4 class="mb">Add Degree</h4>
	<form id="addDegreeForm" class="form-horizontal style-form categoryform" >
		<div id="formError" style="display: none;" class="alert alert-danger alert-dismissible"></div>
		<div id="formSuccess" style="display: none;" class="alert alert-success alert-dismissible"></div>
			
		<div class="form-group">
	       	<label class="col-sm-3 control-label" for="username">Degree Name <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
				<input type="text" name="name" id="name" class="form-control" />
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
	$("#addDegreeForm").on("submit",function(e){
		e.preventDefault();
		if(window.FormData != 'undefined'){
			var formdata = new FormData(this);
	
			$.ajax({
				type:"POST",
				url:baseUrl + "degree/addDegreeAction",	
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
						$("#formSuccess").html("<b>New Degree added successfully.</b>").delay(5000).fadeOut(5000);
						$("#addDegreeForm")[0].reset();
						window.location.href = baseUrl+"degree/list-degree";
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
			