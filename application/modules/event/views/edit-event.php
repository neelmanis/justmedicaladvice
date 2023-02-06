<div class="form-panel">
    <h4 class="mb">Edit Event</h4>
	<form id="editEvent" class="form-horizontal style-form categoryform" >
		<div id="formError" style="display: none;" class="alert alert-danger alert-dismissible"></div>
		<div id="formSuccess" style="display: none;" class="alert alert-success alert-dismissible"></div>
         
		<?php if(is_array($getData)) { ?>  
		
		<div class="form-group">
	       	<label class="col-sm-3 control-label" for="username">Title <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
				<input type="text" name="title" id="title" value="<?php echo $getData[0]->title;?>" class="form-control"/>
			</div>
		</div>
		
		<div class="form-group">
	       	<label class="col-sm-3 control-label" for="username">Venue <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
				<input type="text" name="venue" id="venue" value="<?php echo $getData[0]->venue;?>" class="form-control"/>
			</div>
		</div>
		
		<div class="form-group">
	       	<label class="col-sm-3 control-label" for="username">Event Date <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
				<input type="date" name="eDate" id="eDate" value="<?php echo $getData[0]->eDate;?>" class="form-control"/>
			</div>
		</div>
		
		<div class="form-group">
	       	<label class="col-sm-3 control-label" for="username">Event Time <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
				<input type="text" name="eTime" id="eTime" value="<?php echo $getData[0]->eTime;?>" class="form-control" placeholder="eg. 2.00 PM - 4.00 PM"/>
			</div>
		</div>
		
		<div class="form-group">
	       	<label class="col-sm-3 control-label" for="username">Description <span style="color:red;">*</span></label>
		    <div class="col-sm-9">
				<textarea name="description" id="editor1"><?php echo $getData[0]->description; ?></textarea>
			</div>
		</div>
		
		<div class="form-group">
            <label class="col-sm-3 control-label" for="username">Image </label>
            <div class="col-sm-9">
                 <input type="file" name="upload[]" class="form-control" multiple="multiple"/>
            </div>
			
			<div class="col-sm-6 col-sm-offset-3">
			<?php if($getData[0]->images !== 'No Data'){
				$imageList = explode(",",$getData[0]->images);
				foreach($imageList as $val){
			?>
			<a class="grouped_elements" rel="group1" href="<?php echo base_url();?>admin_assets/images/event/<?php echo $val;?>"><img src="<?php echo base_url();?>admin_assets/images/event/<?php echo $val;?>" height="300px" width="300px" alt=""/></a>	
			<?php }  } ?>
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
		
        <input type="hidden" name="eventId" value="<?php echo $getData[0]->eventId; ?>">
        <input type="hidden" name="images" value="<?php echo $getData[0]->images; ?>">
		
        <?php } ?>
		
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
	$("a.grouped_elements").fancybox();
	$("#editEvent").on("submit",function(e){
		e.preventDefault();		
		
		for (instance in CKEDITOR.instances){
			CKEDITOR.instances[instance].updateElement();
		}
			
		if(window.FormData != 'undefined'){
			var formdata = new FormData(this);
	
			$.ajax({
				type:"POST",
				url:"<?php echo base_url();?>event/editEventAction",	
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
						$("#formSuccess").html("<b>Event is updated successfully. </b>").delay(5000).fadeOut();
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
</script>