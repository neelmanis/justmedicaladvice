<div class="form-panel">
    <h4 class="mb">Add Blog</h4>
	<form id="blogform" class="form-horizontal style-form categoryform" >
		<div id="formError" style="display: none;" class="alert alert-danger alert-dismissible"></div>
		<div id="formSuccess" style="display: none;" class="alert alert-success alert-dismissible"></div>

		<div class="form-group">
	       	<label class="col-sm-3 control-label" for="username">Title <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
				<input type="text" name="title" id="title" class="form-control"/>
			</div>
		</div>
		
		<div class="form-group">
		    <label class="col-sm-3 control-label">Select Category <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
			    <select  name="category" id="category" class="form-control">
			    	<option disabled selected>Choose Category</option>
					<?php if(is_array($category)){
							foreach($category as $cat){ 
					?>
						<option value="<?php echo $cat->catId;?>"><?php echo $cat->catName;?></option>	
					<?php	
						} } 
					?>
			    </select>
		    </div>
		</div>
		
		<div class="form-group">
		    <label class="col-sm-3 control-label">Select Sub-Category <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
			    <!--<select  name="subcategory[]" id="subcategory" class="form-control js-example-basic-multiple" multiple>-->			    				
			    <select  name="subcategory" id="subcategory" class="form-control" >		    				
			    </select>
		    </div>
		</div>
        
		<div class="form-group">
            <label class="col-sm-3 control-label" for="username">Image</label>
            <div class="col-sm-9">
                 <input type="file" name="image" id="image" class="form-control" />
            </div>
		</div>
		
		<div class="form-group">
	       	<label class="col-sm-3 control-label" for="username">Content <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
				<textarea name="content" id="editor1"></textarea>
			</div>
		</div>
		
		<div class="form-group">
	       	<label class="col-sm-3 control-label" for="username">Reference Links (optional)</label>
		    <div class="col-sm-9">
				<textarea name="reference" id="editor2"></textarea>
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
	
	$(document).ready(function(){	
		//$(".js-example-basic-multiple").select2();
		
		$("#subcategory").prop('disabled', true);

		$("#category").change(function() {
			var cat = $(this).val();
			if(cat != ""){
				$.ajax({
					type:"POST",
					url:"<?php echo base_url();?>blog/subcategory",	
					data:{ cid : cat},
					success:function(result){  
						if(result == 1){
							alert('Some Error occured. Please try again')
						}else{
							$("#subcategory").prop('disabled', false);
							$("#subcategory").html(result);
						}
				   }
				});
			}else{
				$("#subcategory").prop('disabled', true);
				$("#subcategory").html("");
			}
		});	
						   
		$("#blogform").on("submit",function(e){
			e.preventDefault();
			for (instance in CKEDITOR.instances){
				CKEDITOR.instances[instance].updateElement();
			}
			
			if(window.FormData != 'undefined'){
				var formdata = new FormData(this);
	
				$.ajax({
					type:"POST",
					url:"<?php echo base_url();?>blog/addBlogAction",	
					data:formdata,
					processData : false,
					contentType : false,
					mimeType : 'multipart/form-data',
                    beforeSend:function() {    
                        $("#preloader").show();
                    },
					success:function(result){
						$("#preloader").hide();	
						if(result == 1){
							$("#blogform")[0].reset();
							CKEDITOR.instances[instance].setData('');
							$(window).scrollTop(0);
							$("#formSuccess").css("display","block");
							$("#formSuccess").html("<b> New Blog added successfully. </b>").delay(5000).fadeOut();
						}else if(result == 2){
							$(window).scrollTop(0);
							$("#formError").css("display","block");
							$("#formError").html("<b>Title Already Exist !!</b>").delay(5000).fadeOut();
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
<script type="text/javascript" src="<?php echo base_url();?>admin_assets/ckeditor_ckfinder/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>admin_assets/ckeditor_ckfinder/ckfinder/ckfinder.js"></script>
<script type="text/javascript">
var baseUrl = '<?php echo base_url() ?>';
	var areas = Array('editor1','editor2');
    $.each(areas, function (i, area) {
		CKEDITOR.replace(area, {
		filebrowserBrowseUrl :  baseUrl+'admin_assets/ckeditor_ckfinder/ckfinder/ckfinder.html',
		filebrowserImageBrowseUrl :  baseUrl+'admin_assets/ckeditor_ckfinder/ckfinder/ckfinder.html?type=Images',
		filebrowserFlashBrowseUrl :  baseUrl+'admin_assets/ckeditor_ckfinder/ckfinder/ckfinder.html?type=Flash',
		filebrowserUploadUrl : baseUrl+'admin_assets/ckeditor_ckfinder/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
		filebrowserImageUploadUrl : baseUrl+'admin_assets/ckeditor_ckfinder/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
		filebrowserFlashUploadUrl : baseUrl+'admin_assets/ckeditor_ckfinder/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
     });
   });
</script>