<div class="form-panel">
    <h4 class="mb">Add Doctor</h4>
	<form id="doctorForm" class="form-horizontal style-form" >
		<div id="formError" style="display: none;" class="alert alert-danger alert-dismissible"></div>
		<div id="formSuccess" style="display: none;" class="alert alert-success alert-dismissible"></div>

		<div class="form-group">
	       	<label class="col-sm-3 control-label">Enter Name <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
				<input type="text" name="name" id="name" class="form-control" />
			</div>
		</div>
		
		<div class="form-group">
	       	<label class="col-sm-3 control-label">Select Gender <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
				<input type="radio" name="gender" id="female" value="female"><label for="female"><span class="female"></span> Female</label>&nbsp;&nbsp;&nbsp;
				<input type="radio" name="gender" id="male" value="male"><label for="male"><span class="male"></span> Male</label>
			</div>
		</div>
		
		<div class="form-group">
	       	<label class="col-sm-3 control-label">Enter Email <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
				<input type="text" name="email" id="email" class="form-control" />
			</div>
		</div>
		
		<div class="form-group">
	       	<label class="col-sm-3 control-label">Enter Password <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
				<input type="text" name="pass" id="pass" class="form-control" />
			</div>
		</div>
		
		<div class="form-group">
	       	<label class="col-sm-3 control-label">Upload Profile Image</label>
		    <div class="col-sm-9">
				<input type="file" name="profile" id="profile" class="form-control" />
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-3 control-label">Enter Mobile<span style="color:red;">*</span></label>
		    <div class="col-sm-2">
			    <select  name="isd" id="isd" class="form-control">
					<option value="">Select ISD</option>
					<?php foreach($isd as $val){ ?>
					<option value="<?php echo $val->phonecode?>"><?php echo $val->phonecode.' ('.$val->shortname.')'?></option>
					<?php } ?>
			    </select>
		    </div>
			<div class="col-sm-4">
			    <input type="text" name="mobile" id="mobile" class="form-control" />
		    </div>
		</div>
		
		<div class="form-group" id="select2Custom">
		    <label class="col-sm-3 control-label">Select Specialities <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
			 	<select class="js-example-basic-multiple" multiple="multiple" name="speciality[]" id="speciality">
                    <?php foreach($speciality as $sp){ ?>
						<option value="<?php echo $sp->spId;?>"><?php echo $sp->spName;?></option>
					<?php } ?> 
				</select>
		    </div>
		</div>
		
		<div class="form-group" id="select2Custom">
		    <label class="col-sm-3 control-label">Select Degree <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
			 	<select class="js-example-basic-multiple" multiple="multiple" name="degree[]" id="degree">
                    <?php foreach($degree as $deg){ ?>
						<option value="<?php echo $deg->degreeId; ?>"><?php  echo $deg->name; ?></option>
					<?php } ?> 
				</select>
		    </div>
		</div>
		
		<div class="form-group" id="select2Custom">
		    <label class="col-sm-3 control-label">Select Experience <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
			 	<select id="experience" name="experience" class="js-example-basic-multiple">
					<option value="">Select Experience</option>
					<?php  
						$i = 1;
						while($i<=70){
					?>
						<option value="<?php echo $i; ?>"><?php echo $i; ?> years</option>
					<?php
						$i++;
					}
					?>
				</select>
		    </div>
		</div>
		
		<div class="form-group">
		    <label class="col-sm-3 control-label">Select City <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
			    <select  name="city" id="city" class="form-control js-example-basic-multiple">			    				
			    </select>
		    </div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-3 control-label">&nbsp;</label>
			<div class="col-sm-6">
				<input type="submit" class="btn btn-primary btn-lg btn-block" value='Save'>
			</div>
		</div>
	</form>
</div>			
<script>
var baseUrl = '<?php echo base_url() ?>';
$(".js-example-basic-multiple").select2();

$("#isd").change(function() {
	var code = $(this).val();
	if(code != ""){
		$.ajax({
			type:"POST",
			url:"<?php echo base_url();?>location/getCities",	
			data:{isd : code},
			success:function(result){  
				if(result == 1){
					alert('Some Error occured. Please try again');
				}else{
					$("#city").html(result);
					$("#city").val($("#city").data(""));
				}
		   }
		});
	}else{
		$("#city").html("");
		$("#city").val($("#city").data(""));
	}
});	

$(document).ready(function(){	
	$("#doctorForm").on("submit",function(e){
		e.preventDefault();
		if(window.FormData != 'undefined'){
			var formdata = new FormData(this);
			
			$.ajax({
				type:"POST",
				url:baseUrl + "doctor/addDoctorAction",	
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
						$(window).scrollTop(0);
						$("#formSuccess").css("display","block");
						$("#formSuccess").html("<b>Doctor is Successfully Added.</b>").delay(5000).fadeOut(2000);
						$("#doctorForm")[0].reset();
						window.location.href = baseUrl+"doctor/list-doctor";
					}else{
						$(window).scrollTop(0);
						$("#formError").css("display","block").delay(5000).fadeOut(2000);
						$("#formError").html(result);
					}
				}
			});
		}
	});
})
</script>			