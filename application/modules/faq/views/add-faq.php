<div class="form-panel">
    <h4 class="mb">Add FAQ</h4>
	<form id="addFaqForm" class="form-horizontal style-form categoryform" >
		
		<div id="formError" style="display: none;" class="alert alert-danger alert-dismissible"></div>
		<div id="formSuccess" style="display: none;" class="alert alert-success alert-dismissible"></div>
			
		<div class="form-group">
	       	<label class="col-sm-3 control-label" for="username">Question<span style="color:red;">*</span></label>
		    <div class="col-sm-9">
				<textarea name="question" id="question" class="form-control"></textarea>
			</div>
		</div>
		
		<div class="form-group">
	       	<label class="col-sm-3 control-label" for="username">Answer<span style="color:red;">*</span></label>
		    <div class="col-sm-9">
				<textarea name="answer" id="editor1" class="form-control"></textarea>
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
	$("#addFaqForm").on("submit",function(e){
		e.preventDefault();
		for (instance in CKEDITOR.instances){
			CKEDITOR.instances[instance].updateElement();
		}
		
		if(window.FormData != 'undefined'){
			var formdata = new FormData(this);
	
			$.ajax({
				type:"POST",
				url:baseUrl + "faq/addFaqAction",	
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
						$("#addFaqForm")[0].reset();
						CKEDITOR.instances[instance].setData('');
						$(window).scrollTop(0);
						$("#formSuccess").css("display","block");
						$("#formSuccess").html("<b>FAQ added successfully.</b>").delay(5000).fadeOut(5000);
						window.location.href = baseUrl+"faq/list-faq";
					}else{
						$(window).scrollTop(0);
						$("#formError").css("display","block");
						$("#formError").html(result).delay(5000).fadeOut(5000);
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
			