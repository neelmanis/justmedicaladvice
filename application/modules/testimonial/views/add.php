<div class="form-panel">
    <h4 class="mb">Add Testimonial</h4>
	<form id="otherform" class="form-horizontal style-form categoryform" >
		 <div style="display: none;" class="form_error"></div>
         <div style="display: none;" class="form_success"></div>

		<div class="form-group">
	       	<label class="col-sm-3 control-label" for="username">Name <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
				<input type="text" name="name" id="name" class="form-control"/>
			</div>
		</div>
		
		<div class="form-group">
	       	<label class="col-sm-3 control-label" for="username">Description <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
				<textarea name="desc" id="editor1"></textarea>
			</div>
		</div>
		
		<div class="form-group">
		    <label class="col-sm-3 control-label">Status <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
			    <select  name="status" id="category_status" class="form-control">
			    	<option value="">Choose One</option>
			        <option value="1">Active</option>
			        <option value="0">Deactivate</option>
			    </select>
		    </div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-3 control-label">&nbsp;</label>
			<div class="col-sm-9">
				<input type="submit" class="btn btn-primary" id="banner_btn" value='Save'>
			</div>
		</div>
	</form>
</div>			

<script>
var baseUrl = '<?php echo base_url() ?>';
$(document).ready(function(){	
    
	$("#otherform").on("submit",function(e){
		e.preventDefault();
		
		for (instance in CKEDITOR.instances){
			CKEDITOR.instances[instance].updateElement();
			CKEDITOR.instances[instance].setData('');
		}
			
		if(window.FormData != 'undefined')
		{
			var formdata = new FormData(this);
	
			$.ajax({
				type:"POST",
				url:"<?php echo base_url();?>testimonial/addtest",	
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
						$(".form_success").css("display","block");
						$(".form_success").html("New testimonial added successfully.").delay(1000).fadeOut(5000);
                        $('#otherform')[0].reset();
					}else{
						$(window).scrollTop(0);
						$(".form_error").css("display","block");
						$(".form_error").html(result).delay(1000).fadeOut(5000);
					}
				}
			})
		}
	});
})
</script>			
	<script type="text/javascript" src="<?php echo base_url();?>admin_assets/ckeditor_ckfinder/ckeditor/ckeditor.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>admin_assets/ckeditor_ckfinder/ckfinder/ckfinder.js"></script>
    <script type="text/javascript">
var baseUrl = '<?php echo base_url() ?>';       
var editor = CKEDITOR.replace( 'editor1', {
	filebrowserBrowseUrl : baseUrl+'admin_assets/ckeditor_ckfinder/ckfinder/ckfinder.html',
	filebrowserImageBrowseUrl : baseUrl+'admin_assets/ckeditor_ckfinder/ckfinder/ckfinder.html?type=Images',
	filebrowserFlashBrowseUrl : baseUrl+'admin_assets/ckeditor_ckfinder/ckfinder/ckfinder.html?type=Flash',
	filebrowserUploadUrl : baseUrl+'admin_assets/ckeditor_ckfinder/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
	filebrowserImageUploadUrl : baseUrl+'admin_assets/ckeditor_ckfinder/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
	filebrowserFlashUploadUrl : baseUrl+'admin_assets/ckeditor_ckfinder/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
});
CKFinder.setupCKEditor( editor, '../' );
</script>