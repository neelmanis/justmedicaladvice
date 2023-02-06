<div class="form-panel">
    <h4 class="mb">Edit Article</h4>
	<form id="editblog" class="form-horizontal style-form categoryform" >
		<div id="formError" style="display: none;" class="alert alert-danger alert-dismissible"></div>
		<div id="formSuccess" style="display: none;" class="alert alert-success alert-dismissible"></div>
         
		<?php if(is_array($getData)) { ?>  

		<div class="form-group">
	       	<label class="col-sm-3 control-label" for="username">Title <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
				<input type="text" name="title" id="title" class="form-control" value="<?php echo $getData[0]->title; ?>"/>
			</div>
		</div>
		
        <div class="form-group">
		    <label class="col-sm-3 control-label">Select Category <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
			    <select  name="category" id="category" class="form-control">
			    	<option disabled>Choose Category</option>
					<?php if(is_array($category)){
							foreach($category as $cat){ 
					?>
						<option value="<?php echo $cat->catId;?>" <?php if($categoryId == $cat->catId){ echo "selected";} ?>><?php echo $cat->catName;?></option>	
					<?php	
						} } 
					?>
			    </select>
		    </div>
		</div>
				
		<div class="form-group">
		    <label class="col-sm-3 control-label">Select Sub-Category <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
			    <select  name="subcategory" id="subcategory" class="form-control">		
					<?php  echo $subCategory; ?>
			    </select>
		    </div>
		</div>
		
		<div class="form-group">
            <label class="col-sm-3 control-label" for="username">Image</label>
            <div class="col-sm-9">
				<input type="file" name="image" id="image" class="form-control" />
				<?php if($getData[0]->image !== "No Data") {?>
				<img src="<?php echo base_url();?>admin_assets/images/blog/<?php echo $getData[0]->image;?>" style="width:350px !important;height:350px !important;">
				<?php }else{
					echo '<b>'.'No Image Availble'.'</b>';
				} ?>
                <input type="hidden" name="imgName" value="<?php echo $getData[0]->image;?>">
            </div>
		</div>
		
        <div class="form-group">
	       	<label class="col-sm-3 control-label" for="username">Content <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
				<textarea name="content" id="editor1"><?php echo $getData[0]->content; ?></textarea>
			</div>
		</div>
		
		<div class="form-group">
	       	<label class="col-sm-3 control-label" for="username">Reference Links (optional)</label>
		    <div class="col-sm-9">
				<textarea name="reference" id="editor2"><?php if($getData[0]->reference !== 'No Data'){ echo $getData[0]->reference; } ?></textarea>
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
		
		<?php 
			$id = base64_encode($getData[0]->blogId);
			$id = str_replace(str_repeat('=',strlen($id)/4),"",$id);
		?>
        <input type="hidden" name="blogId" value="<?php echo $id; ?>">
        <input type="hidden" name="slug" value="<?php echo $getData[0]->slug; ?>">
		
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
$(document).ready(function(){	
	
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

	$("#editblog").on("submit",function(e){
		e.preventDefault();
		
		for (instance in CKEDITOR.instances){
			CKEDITOR.instances[instance].updateElement();
		}
			
		if(window.FormData != 'undefined'){
			var formdata = new FormData(this);
	
			$.ajax({
				type:"POST",
				url:"<?php echo base_url();?>blog/editBlogAction",	
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
						$(window).scrollTop(0);
						$("#formSuccess").css("display","block");
						$("#formSuccess").html("<b>Blog is updated successfully. </b>").delay(5000).fadeOut();
						window.location.reload(true);
					}else if(result == 2){
						$(window).scrollTop(0);
						$("#formError").css("display","block");
						$("#formError").html("<b>Entered Title already exist.</b>").delay(5000).fadeOut();
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

/*var editor = CKEDITOR.replace(editor1, {
	filebrowserBrowseUrl : 'ckfinder/ckfinder.html',
	filebrowserImageBrowseUrl : 'ckfinder/ckfinder.html?type=Images',
	filebrowserFlashBrowseUrl : 'ckfinder/ckfinder.html?type=Flash',
	filebrowserUploadUrl : baseUrl+'admin_assets/ckeditor_ckfinder/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
	filebrowserImageUploadUrl : baseUrl+'admin_assets/ckeditor_ckfinder/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
	filebrowserFlashUploadUrl : baseUrl+'admin_assets/ckeditor_ckfinder/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
});
CKFinder.setupCKEditor( editor, '../' );*/
</script>