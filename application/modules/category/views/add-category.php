<div class="form-panel">
    <h4 class="mb">Add Category</h4>
	<form id="categoryform" class="form-horizontal style-form categoryform" >
		<div id="formError" style="display: none;" class="alert alert-danger alert-dismissible"></div>
		<div id="formSuccess" style="display: none;" class="alert alert-success alert-dismissible"></div>

		<div class="form-group">
	       	<label class="col-sm-3 control-label" for="username">Category Name <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
				<input type="text" name="category_name" id="category_name" class="form-control" />
			</div>
		</div>
		
		<div class="form-group">
		    <label class="col-sm-3 control-label">Parent Category <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
			    <select  name="parent_category" id="parent_category" class="form-control">
					<option value="">Choose One</option>
					<?php if(is_array($categories)){
						foreach($categories as $cat){ 
					?>
						<option value="<?php echo $cat['catId'];?>"><?php if($cat['catId'] == '1'){echo $cat['catName'];} else { echo $cat['catName']." (".$cat['parentCatName'].")"; } ?></option>		
					<?php } } ?>
			    </select>
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
$(document).ready(function(){	
	$("#categoryform").on("submit",function(e){
		e.preventDefault();
		if(window.FormData != 'undefined'){
			var formdata = new FormData(this);
			var catId = $("#parent_category").val();
			
			$.ajax({
				type:"POST",
				url:baseUrl + "category/addCategoryAction",	
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
						$("#formSuccess").html("<b>New category added successfully</b>").delay(5000).fadeOut(2000);
						$("#categoryform")[0].reset();
						window.location.href = baseUrl+"category/listByFilter/"+catId;
					}else if(result == 2){
						$("#formError").css("display","block");
						$("#formError").html("<b>Category Already Exists</b>").delay(5000).fadeOut(2000);
					}else{
						$("#formError").css("display","block");
						$("#formError").html(result).delay(5000).fadeOut(2000);
					}
				}
			});
		}
	});
})
</script>			