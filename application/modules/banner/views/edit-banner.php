<div class="form-panel">
    <h4 class="mb">Edit Banner</h4>
	<form id="editbanner" class="form-horizontal style-form categoryform" >
		<div id="formError" style="display: none;" class="alert alert-danger alert-dismissible"></div>
		<div id="formSuccess" style="display: none;" class="alert alert-success alert-dismissible"></div>
         
		<?php if(is_array($getData)) { ?>  
		
		<div class="form-group">
		    <label class="col-sm-3 control-label">Content Type <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
			    <select  name="ctype" id="ctype" class="form-control">
			    	<option disabled selected>Choose Type</option>
			        <option value="blog" <?php if($getData[0]->ctype == 'blog')echo "selected"; ?>>Blog</option>
			        <option value="media" <?php if($getData[0]->ctype == 'media')echo "selected"; ?>>Media</option>
			        <option value="forum" <?php if($getData[0]->ctype == 'forum')echo "selected"; ?>>Forum</option>
			    </select>
		    </div>
		</div>
		
		<div class="form-group">
	       	<label class="col-sm-3 control-label" for="username">Title <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
				<input type="text" name="title" id="title" value="<?php echo $getData[0]->title;?>" class="form-control"/>
			</div>
		</div>
		
		<div class="form-group">
            <label class="col-sm-3 control-label" for="username">Image <span style="color:red;">*</span></label>
            <div class="col-sm-9">
                 <input type="file" name="image" id="image" class="form-control" />
            </div>
			<div class="col-sm-6 col-sm-offset-3">
                <img src="<?php echo base_url().$getData[0]->image;?>" style="width:690px !important;height:350px !important;">
            </div>
		</div>
		
		<div class="form-group">
	       	<label class="col-sm-3 control-label" for="username"> Copy & Paste URL here <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
				<input type="text" name="url" id="url" value="<?php echo $getData[0]->url;?>" class="form-control"/>
			</div>
			<label class="col-sm-3 control-label" for="username"><span style="color:red;">Hint</span></label>
			<div class="col-sm-9">
				<img src="<?php echo base_url(); ?>admin_assets/images/banner-url.jpg" width="100%">
			</div>
		</div>
		
		<div class="form-group">
	       	<label class="col-sm-3 control-label" for="username">Short Description</label>
		    <div class="col-sm-9">
				<textarea name="content" id="editor1"><?php echo $getData[0]->content; ?></textarea>
			</div>
		</div>
		
		<div class="form-group">
		    <label class="col-sm-3 control-label">Status <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
			    <select  name="status1" id="status1" class="form-control">
			    	<option disabled selected>Choose One</option>
			        <option value="1" <?php if($getData[0]->isActive == '1'){ echo "selected"; }?>>Active</option>
			        <option value="0" <?php if($getData[0]->isActive == '0'){ echo "selected"; }?>>Deactivate</option>
			    </select>
		    </div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-3 control-label" for="username">Visible To <span style="color:red;">*</span></label>
				<div class="col-sm-9">
					<div class="checkdisplay">
						<input type="checkbox" name="visible[]" class="form-control" value="1" <?php if($getData[0]->home == '1'){ echo "checked";}?>/>Home Page
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="visible[]" class="form-control" value="2" <?php if($getData[0]->memDash == '1'){ echo "checked";}?>/>Member Dashboard 
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="visible[]" class="form-control" value="3" <?php if($getData[0]->docDash == '1'){ echo "checked";}?>/>Doctor Dashboard
					</div>
				</div>
		</div>
		
		<?php 
			$id = base64_encode($getData[0]->bannerId);
			$id = str_replace(str_repeat('=',strlen($id)/4),"",$id);
		?>
        <input type="hidden" name="bannerId" value="<?php echo $id; ?>">
        <input type="hidden" name="imgUrl" value="<?php echo $getData[0]->image; ?>">
		
        <?php } ?>
		
		<div class="form-group">
			<label class="col-sm-3 control-label">&nbsp;</label>
			<div class="col-sm-6">
				<input type="submit" class="btn btn-primary btn-block" id="banner_btn" value='Update'>
			</div>
		</div>
	</form>
</div>			
<script>
var baseUrl = '<?php echo base_url() ?>';
$(document).ready(function(){	

	$("#editbanner").on("submit",function(e){
		e.preventDefault();		
		
		for (instance in CKEDITOR.instances){
			CKEDITOR.instances[instance].updateElement();
		}
			
		if(window.FormData != 'undefined'){
			var formdata = new FormData(this);
	
			$.ajax({
				type:"POST",
				url:"<?php echo base_url();?>banner/editBannerAction",	
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
						$("#formSuccess").html("<b>Banner is updated successfully. </b>").delay(5000).fadeOut();
						window.location.reload(true);
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

 var areas = Array('editor1');
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