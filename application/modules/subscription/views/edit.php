<link href="<?php echo base_url('admin_assets/jquery-simple-datetimepicker/jquery.simple-dtpicker.css')?>" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url('admin_assets/jquery-simple-datetimepicker/jquery.simple-dtpicker.js')?>"></script>


<div class="form-panel">
    <h4 class="mb">Edit Entry Rate</h4>
    <?php if(is_array($singlecategory))
	        {
		    foreach($singlecategory as $val)
		    {
		?>
	<form id="myform" class="form-horizontal style-form categoryform" >
		 <div style="display: none;" class="form_error"></div>
         <div style="display: none;" class="form_success"></div>

		
		<div class="form-group">
		    <label class="col-sm-3 control-label">Category</label>
		    <div class="col-sm-9">
			    <select  name="category" id="category" class="form-control">
			    	<option value="">Choose One</option>
					<option <?php if($val->catId=='1'){ echo "selected";}?> value="1">Esselworld</option>
                    <option <?php if($val->catId=='2'){ echo "selected";}?> value="2">Water Kingdom</option>
                    <option <?php if($val->catId=='3'){ echo "selected";}?> value="3">Downtown</option>
                    <option <?php if($val->catId=='4'){ echo "selected";}?> value="4">Combined Ticket (Esselworld + Water Kingdom)</option>	
			    </select>
		    </div>
		</div>
		
        <div class="form-group">
		    <label class="col-sm-3 control-label">Visitor Type</label>
		    <div class="col-sm-9">
			    <select  name="vtype" id="vtype" class="form-control">
			    	<option value="">Choose One</option>
					<option  <?php if($val->visitor_type=='1'){ echo "selected";}?> value="1">Adult</option>
                    <option  <?php if($val->visitor_type=='2'){ echo "selected";}?> value="2">Child</option>
                    <option  <?php if($val->visitor_type=='3'){ echo "selected";}?> value="3">Sr. Citizen</option>	
			    </select>
		    </div>
		</div>
        
        <div class="form-group">
	       	<label class="col-sm-3 control-label" for="username">Price</label>
		    <div class="col-sm-9">
				<input type="text" name="price" id="price" class="form-control" value="<?php echo $val->price;?>">
			</div>
		</div>
		
	    <div class="form-group">
		    <label class="col-sm-3 control-label">Status</label>
		    <div class="col-sm-9">
			    <select  name="category_status" id="category_status" class="form-control">
			    	<option value="">Choose One</option>
			        <option  <?php if($val->isActive=='1'){ echo "selected";}?> value="1">Active</option>
			        <option  <?php if($val->isActive=='0'){ echo "selected";}?> value="0">Deactivate</option>
			    </select>
		    </div>
		</div>
		
        <input type="hidden" name="id" value="<?php echo $val->id;?>">
		<div class="form-group">
			<label class="col-sm-3 control-label">&nbsp;</label>
			<div class="col-sm-9">
				<input type="submit" class="btn btn-primary" id="banner_btn" value='Save'>
			</div>
		</div>
	</form>
    <?php } }?>
</div>			
<script>
$(document).ready(function(){	
	$("#myform").on("submit",function(e){
		e.preventDefault();
		var formdata = $(this).serialize();
	     
			$.ajax({
			type:"POST",
			url:"<?php echo base_url();?>entry_rate/edit_action",	
			data:formdata,
                        beforeSend:function() {    
                          $("#preloader").show();
                           },
    		success:function(result)
			    { 
                               $("#preloader").hide();
			               if(result==1)
						   {
						    $(".form_success").css("display","block");
							$(".form_success").html("Updated successfully.").delay(1000).fadeOut(5000);
						   }
						   else
						   {
						    $(".form_error").css("display","block");
							$(".form_error").html(result).delay(1000).fadeOut(5000);
 						   }
			
			   }
			})
	});
})
</script>			

