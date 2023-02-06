<div class="form-panel">
    <h4 class="mb">Edit Media</h4>
	<form id="editmedia" class="form-horizontal style-form categoryform" >
		<div id="formError" style="display: none;" class="alert alert-danger alert-dismissible"></div>
		<div id="formSuccess" style="display: none;" class="alert alert-success alert-dismissible"></div>
         
		<?php if(is_array($getData)) { 
			$selectedCat = $getData[0]->categoryId;
		?>  
		
		<div class="form-group">
		    <label class="col-sm-3 control-label">Select Media Type<span style="color:red;">*</span></label>
		    <div class="col-sm-9">
			    <select  name="mtype" id="mtype" class="form-control">
			    	<option disabled>Choose One</option>
			        <option value="video" <?php  if($getData[0]->mtype == 'video'){echo 'selected '; }?>>Video</option>
			        <option value="audio" <?php  if($getData[0]->mtype == 'audio'){echo 'selected '; }?>>Audio</option>
			    </select>
		    </div>
		</div>
		
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
						<option value="<?php echo $cat->catId;?>" <?php if($selectedCat == $cat->catId){ echo "selected";} ?>><?php echo $cat->catName;?></option>	
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
		
		<?php  if($getData[0]->ctype == 'upload'){ ?>
			<div class="form-group">
				<label class="col-sm-3 control-label">Uploaded Videos</label>
				<div class="col-sm-9">
					<video width="400" controls controlsList="nodownload">
						<source src="<?php echo $getData[0]->url ?>">
						Your browser does not support HTML5 video.
					</video>
				</div>
			</div>
		<?php }else if($getData[0]->ctype == 'none'){ ?>
			<div class="form-group">
				<label class="col-sm-3 control-label">Uploaded Audio</label>
				<div class="col-sm-9">
					<audio controls controlsList="nodownload">
						<source src="<?php echo $getData[0]->url ?>">
						Your browser does not support HTML5 audio.
					</audio>
				</div>
			</div>
		<?php }else{ ?>
			<div class="form-group">
				<label class="col-sm-3 control-label">Uploaded video</label>
				<div class="col-sm-9">
					<iframe width="100%" height="250px;" src="<?php echo $getData[0]->url?>?rel=0&autoplay=0&showinfo=0&controls=0" frameborder="0" allowfullscreen></iframe>
				</div>
			</div>
		<?php } ?>
		
		<div class="form-group <?php  if($getData[0]->mtype == 'audio'){echo 'hide';}?>" id="contentType">
		    <label class="col-sm-3 control-label">Select Content Type<span style="color:red;">*</span></label>
		    <div class="col-sm-9">
			    <select  name="ctype" id="ctype" class="form-control">
			    	<option disabled>Choose One</option>
			        <option value="upload" <?php  if($getData[0]->ctype == 'upload'){echo 'selected'; }?>>Upload File</option>
			        <option value="youtube" <?php  if($getData[0]->ctype == 'youtube'){echo 'selected'; }?>>Add youtube link</option>
			    </select>
		    </div>
		</div>
		
		<div id="ytube" class="form-group <?php  if($getData[0]->ctype == 'upload' || $getData[0]->ctype == 'none'){echo 'hide'; }?>">
            <label class="col-sm-3 control-label" for="username">Add Youtube Video Code</label>
            <div class="col-sm-9">
                 <input type="text" name="link" id="link" class="form-control" placeholder="eg. WvQ69t3vkpQ"/>
            </div>
			<label class="col-sm-3 control-label" for="username"><span style="color:red;">Hint</span></label>
			<div class="col-sm-9">
				<img src="<?php echo base_url(); ?>admin_assets/images/youtube-hint.jpg" width="100%">
			</div>
		</div>
		
		<div id="upld" class="form-group <?php  if($getData[0]->ctype == 'youtube'){echo 'hide'; }?>">
            <label class="col-sm-3 control-label" for="username">Upload Video</label>
            <div class="col-sm-9">
                 <input type="file" name="media" id="media" class="form-control" />
            </div>
		</div>
		
        <div class="form-group">
	       	<label class="col-sm-3 control-label" for="username">Description <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
				<textarea name="description" id="editor1"><?php echo $getData[0]->description; ?></textarea>
			</div>
		</div>

		<div class="form-group">
		    <label class="col-sm-3 control-label">Visible To </label>
		    <div class="col-sm-9">
			    <select  name="visible" id="visible" class="form-control">
			        <option value="mem" <?php if($getData[0]->visibleTo == 'mem'){ echo "selected"; }?>>Only For Members</option>
			        <option value="doc"  <?php if($getData[0]->visibleTo == 'doc'){ echo "selected"; }?>>Only For Doctors</option>
			        <option value="all"  <?php if($getData[0]->visibleTo == 'all'){ echo "selected"; }?>>All</option>
			    </select>
		    </div>
		</div>
		
		<div class="form-group">
		    <label class="col-sm-3 control-label">Status <span style="color:red;">*</span></label>
		    <div class="col-sm-9 ">
			    <select  name="status1" id="status1" class="form-control">
			    	<option disabled>Choose One</option>
			        <option <?php if($getData[0]->isActive == '1'){ echo "selected"; }?> value="1">Active</option>
			        <option <?php if($getData[0]->isActive == '0'){ echo "selected"; }?> value="0">Deactivate</option>
			    </select>
		    </div>
		</div>
			
        <input type="hidden" name="mediaId" value="<?php echo base64_encode($getData[0]->mediaId);?>">
        <input type="hidden" name="url" value="<?php echo $getData[0]->url; ?>">
        <input type="hidden" name="slug" value="<?php echo $getData[0]->slug; ?>">
		
        <?php } ?>
		
		<div class="form-group">
			<label class="col-sm-3 control-label">&nbsp;</label>
			<div class="col-sm-9">
				<input type="submit" class="btn btn-primary btn-lg btn-block" id="banner_btn" value='Update'>
			</div>
		</div>
	</form>
</div>			
<script>
var baseUrl = '<?php echo base_url() ?>';
$(document).ready(function(){	
		
	$("#mtype").change(function() {
		var type = $(this).val();
		if(type != ""){
			if(type == 'video'){
				$("#contentType").removeClass("hide");
			}else if(type == 'audio'){
				$("#upld").removeClass("hide");
				$("#contentType").addClass("hide");
				$("#ytube").addClass("hide");
			}
		}
	});
		
	$("#category").change(function() {
		var cat = $(this).val();
		if(cat != ""){
			$.ajax({
				type:"POST",
				url:"<?php echo base_url();?>media/subcategory",	
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

	$("#ctype").change(function() {
		var type = $(this).val();
		if(type != ""){
			if(type == 'upload'){
				$("#upld").removeClass("hide");
				$("#ytube").addClass("hide");
			}else if(type == 'youtube'){
				$("#ytube").removeClass("hide");
				$("#upld").addClass("hide");
			}
		}
	});
		
	$("#editmedia").on("submit",function(e){
		e.preventDefault();
		
		for (instance in CKEDITOR.instances){
			CKEDITOR.instances[instance].updateElement();
		}
			
		if(window.FormData != 'undefined'){
			var formdata = new FormData(this);
	
			$.ajax({
				type:"POST",
				url:"<?php echo base_url();?>media/editMediaAction",	
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
						$("#formSuccess").html("<b>Media is updated successfully. </b>").delay(5000).fadeOut();
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