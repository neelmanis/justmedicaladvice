<div class="form-panel">
    <h4 class="mb">Add Banner</h4>
	<form id="bannerform" class="form-horizontal style-form categoryform" >
		<div id="formError" style="display: none;" class="alert alert-danger alert-dismissible"></div>
		<div id="formSuccess" style="display: none;" class="alert alert-success alert-dismissible"></div>

		<div class="form-group">
		    <label class="col-sm-3 control-label">Content Type <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
			    <select  name="ctype" id="ctype" class="form-control">
			    	<option disabled selected>Choose Type</option>
			        <option value="blog">Blog</option>
			        <option value="media">Media</option>
			        <option value="forum">Forum</option>
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
            <label class="col-sm-3 control-label" for="username">Image <span style="color:red;">*</span></label>
            <div class="col-sm-9">
                 <input type="file" name="image" id="image" class="form-control" />
            </div>
		</div>
		
		<div class="form-group">
	       	<label class="col-sm-3 control-label" for="username"> Copy & Paste URL here <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
				<input type="text" name="url" id="url" class="form-control"/>
			</div>
			<label class="col-sm-3 control-label" for="username"><span style="color:red;">Hint</span></label>
			<div class="col-sm-9">
				<img src="<?php echo base_url(); ?>admin_assets/images/banner-url.jpg" width="100%">
			</div>
		</div>
		
		<div class="form-group">
	       	<label class="col-sm-3 control-label" for="username">Short Description</label>
		    <div class="col-sm-9">
				<textarea name="content" id="editor1"></textarea>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-3 control-label" for="username">Visible To <span style="color:red;">*</span></label>
				<div class="col-sm-9">
					<div class="checkdisplay">
						<input type="checkbox" name="visible[]" class="form-control" value="1" />Home Page
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="visible[]" class="form-control" value="2" />Member Dashboard 
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="visible[]" class="form-control" value="3" />Doctor Dashboard
					</div>
				</div>
		</div>
			
		<div class="form-group">
			<label class="col-sm-3 control-label">&nbsp;</label>
			<div class="col-sm-6">
				<input type="submit" class="btn btn-primary btn-block" id="banner_btn" value='Save'>
			</div>
		</div>
	</form>
</div>			

<script>
	var baseUrl = '<?php echo base_url() ?>';
	
	$(document).ready(function(){	
					   
		$("#bannerform").on("submit",function(e){
			e.preventDefault();
			for (instance in CKEDITOR.instances){
				CKEDITOR.instances[instance].updateElement();
			}
			
			if(window.FormData != 'undefined'){
				var formdata = new FormData(this);
	
				$.ajax({
					type:"POST",
					url:"<?php echo base_url();?>banner/addBannerAction",	
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
							$("#bannerform")[0].reset();
							CKEDITOR.instances[instance].setData('');
							$(window).scrollTop(0);
							$("#formSuccess").css("display","block");
							$("#formSuccess").html("<b> New Banner added successfully. </b>").delay(5000).fadeOut();
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