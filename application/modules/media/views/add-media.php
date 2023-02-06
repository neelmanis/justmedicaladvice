<div class="form-panel">
    <h4 class="mb">Add Media</h4>
	<form id="mediaform" class="form-horizontal style-form categoryform" >
		<div id="formError" style="display: none;" class="alert alert-danger alert-dismissible"></div>
		<div id="formSuccess" style="display: none;" class="alert alert-success alert-dismissible"></div>
		
		<div class="form-group">
		    <label class="col-sm-3 control-label">Select Media Type<span style="color:red;">*</span></label>
		    <div class="col-sm-9">
			    <select  name="mtype" id="mtype" class="form-control">
			    	<option disabled selected>Choose One</option>
			        <option value="video">Video</option>
			        <option value="audio">Audio</option>
			    </select>
		    </div>
		</div>
		
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
			    <select  name="subcategory" id="subcategory" class="form-control">			    				
			    </select>
		    </div>
		</div>
        
		<div class="form-group" id="contentType">
		    <label class="col-sm-3 control-label">Select Content Type<span style="color:red;">*</span></label>
		    <div class="col-sm-9">
			    <select  name="ctype" id="ctype" class="form-control">
			    	<option value="" selected>Choose One</option>
			        <option value="upload">Upload File</option>
			        <option value="youtube">Add YouTube Link</option>
			    </select>
		    </div>
		</div>
		
		<div id="ytube" class="form-group">
            <label class="col-sm-3 control-label" for="username">Copy & Paste Youtube Video Code</label>
            <div class="col-sm-9">
                 <input type="text" name="link" id="link" class="form-control" placeholder="eg. WvQ69t3vkpQ" />
            </div>
			<label class="col-sm-3 control-label" for="username"><span style="color:red;">Hint</span></label>
			<div class="col-sm-9">
				<img src="<?php echo base_url(); ?>admin_assets/images/youtube-hint.jpg" width="100%">
			</div>
		</div>
		
		<div id="upld" class="form-group">
            <label class="col-sm-3 control-label" for="username">Upload Video</label>
            <div class="col-sm-9">
                 <input type="file" name="media" id="media" class="form-control" />
            </div>
		</div>
		
		<div class="form-group">
	       	<label class="col-sm-3 control-label" for="username">Description </label>
		    <div class="col-sm-9">
				<textarea name="description" id="editor1"></textarea>
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
		
		$(".js-example-basic-multiple").select2();
		
		$("#contentType").addClass("hide");
		$("#ytube").addClass("hide");
		$("#upld").addClass("hide");
		$("#subcategory").prop('disabled', true);

		$("#mtype").change(function() {
			var type = $(this).val();
			if(type != ""){
				if(type == 'video'){
					$("#contentType").removeClass("hide");
					$("#upld").addClass("hide");
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
						   
		$("#mediaform").on("submit",function(e){
			e.preventDefault();
			for (instance in CKEDITOR.instances){
				CKEDITOR.instances[instance].updateElement();
			}
			
			if(window.FormData != 'undefined'){
				var formdata = new FormData(this);
	
				$.ajax({
					type:"POST",
					url:"<?php echo base_url();?>media/addMediaAction",	
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
							$("#mediaform")[0].reset();
							CKEDITOR.instances[instance].setData('');
							$(window).scrollTop(0);
							$("#formSuccess").css("display","block");
							$("#formSuccess").html("<b> New Media added successfully. </b>").delay(5000).fadeOut();
						}else if(result == 2){
							$(window).scrollTop(0);
							$("#formError").css("display","block");
							$("#formError").html("<b>Title Already Exist !!</b>").delay(5000).fadeOut();
						}else if(result == 3){
							$(window).scrollTop(0);
							$("#formError").css("display","block");
							$("#formError").html("please upload file").delay(5000).fadeOut();
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
</script>