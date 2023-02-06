<div class="form-panel">
	<h4 class="mb">Edit Category</h4>
    <?php 
	   if(is_array($getData)){
	?>
	<form id="categoryform" class="form-horizontal style-form categoryform" >
		<div id="formError" style="display: none;" class="alert alert-danger alert-dismissible"></div>
		<div id="formSuccess" style="display: none;" class="alert alert-success alert-dismissible"></div>

		<div class="form-group">
	       	<label class="col-sm-3 control-label" for="username">Category Name <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
				<input type="text" name="category_name" id="category_name" class="form-control" value="<?php echo $getData[0]->catName?>"/>
			</div>
		</div>
		
		<div class="form-group">
		    <label class="col-sm-3 control-label">Parent Category <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
			    <select  name="parent_category" id="parent_category" class="form-control">
					<option value="">Choose One</option>
					<?php if(is_array($categories)){
						foreach($categories as $cat){ 
							$categoryView = $this->db->get_where("category_master",array('catId' =>$cat->parentCat));
								if($categoryView->num_rows() > 0)								{
									$getCategory = $categoryView->result();
									$getCategory =  $getCategory[0]->catName;
								}else{
									$getCategory = "no";
								}	
					?>
					<option <?php if($cat->catId == $getData[0]->parentCat) echo "selected";?> value="<?php echo $cat->catId;?>"><?php  echo $cat->catName." (".$getCategory.")";  ?></option>	
					<?php	}
				   } ?>
			    </select>
		    </div>
		</div>
		
		<?php $arrays = explode(",",$getData[0]->specialities);?>
		<div class="form-group" id="select2Custom">
		    <label class="col-sm-3 control-label">Select Specialities <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
			 	<select class="js-example-basic-multiple" multiple="multiple" name="speciality[]" id="speciality">
                    <?php foreach($speciality as $sp){ ?>
						<option value="<?php echo $sp->spId;?>" <?php  if(in_Array($sp->spId,$arrays)){echo 'selected';}?>><?php echo $sp->spName;?></option>
					<?php } ?> 
				</select>
		    </div>
		</div>
		
	    <div class="form-group">
		    <label class="col-sm-3 control-label">Status <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
			    <select  name="category_status" id="category_status" class="form-control">  
			        <option <?php if($getData[0]->isActive == '1'){ echo "selected"; } ?> value="1">Active</option>
			        <option <?php if($getData[0]->isActive == '0'){ echo "selected"; } ?> value="0">Deactivate</option>
			    </select>
		    </div>
		</div>
		
		<input type="hidden" name="catId" value="<?php echo $getData[0]->catId;?>">
		<input type="hidden" name="catSlug" value="<?php echo $getData[0]->catSlug;?>">
		
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
	$(".js-example-basic-multiple").select2();

	$("#categoryform").on("submit",function(e){
		e.preventDefault();
		if(window.FormData != 'undefined'){
			var formdata = new FormData(this);
			var catId = $("#parent_category").val();
			
			 $.ajax({
				type:"POST",
				url:"<?php echo base_url();?>category/editCategoryAction",	
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
						 $("#formSuccess").html("<b>The category updated successfully.</b>").delay(5000).fadeOut(2000);
						 window.location.href="<?php echo base_url()?>category/listByFilter/"+catId;
					}else if(result == 2){
						$("#formError").css("display","block");
						$("#formError").html("<b>Category Already Exists </b>").delay(5000).fadeOut(2000);
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