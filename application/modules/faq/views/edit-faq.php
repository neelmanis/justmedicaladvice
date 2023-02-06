<div class="form-panel">
	<h4 class="mb">Edit FAQ</h4>
    <?php 
	   if($faqData !== "No Data"){
	?>
	<form id="editFaqForm" class="form-horizontal style-form" >
		
		<div id="formError" style="display: none;" class="alert alert-danger alert-dismissible"></div>
		<div id="formSuccess" style="display: none;" class="alert alert-success alert-dismissible"></div>

		<div class="form-group">
	       	<label class="col-sm-3 control-label" for="username">Question<span style="color:red;">*</span></label>
		    <div class="col-sm-9">
				<textarea name="question" id="question" class="form-control"><?php echo $faqData[0]->question;?></textarea>
			</div>
		</div>
		
		<div class="form-group">
	       	<label class="col-sm-3 control-label" for="username">Answer<span style="color:red;">*</span></label>
		    <div class="col-sm-9">
				<textarea name="answer" id="editor1" class="form-control"><?php echo $faqData[0]->answer;?></textarea>
			</div>
		</div>
		
	    <div class="form-group">
		    <label class="col-sm-3 control-label">Status <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
			    <select  name="status" id="status" class="form-control">  
			        <option <?php if($faqData[0]->isActive == '1'){ echo "selected"; } ?> value="1">Active</option>
			        <option <?php if($faqData[0]->isActive == '0'){ echo "selected"; } ?> value="0">Deactivate</option>
			    </select>
		    </div>
		</div>
		
		<input type="hidden" name="faqId" value="<?php echo $faqData[0]->faqId;?>">
		
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
	$("#editFaqForm").on("submit",function(e){
		e.preventDefault();
		for (instance in CKEDITOR.instances){
			CKEDITOR.instances[instance].updateElement();
		}
		
		if(window.FormData != 'undefined'){
			var formdata = new FormData(this);
	
			$.ajax({
				type:"POST",
				url:"<?php echo base_url();?>faq/editFaqAction",	
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
						$("#editFaqForm")[0].reset();
						CKEDITOR.instances[instance].setData('');
						$(window).scrollTop(0);
						$("#formSuccess").css("display","block");
						$("#formSuccess").html("<b>FAQ updated successfully.</b>").delay(5000).fadeOut(5000);
						window.location.href = baseUrl+"faq/listfaq";
					}else{
						$(window).scrollTop(0);
						$("#formError").css("display","block");
						$("#formError").html(result).delay(1000).fadeOut(5000);
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