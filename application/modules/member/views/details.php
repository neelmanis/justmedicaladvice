<div class="row mt">
	<div class="col-lg-12">
		<div class="content-panel">
			<h4>View member Details</h4>
			<?php if(is_array($details)){  
			?>	
			<div class="clear"></div>   
				<form id=" " class="form-horizontal style-form categoryform">
					
					<div class="form-group">
						<label class="col-sm-3 control-label" for="username">Profile Image</label>
						<div class="col-sm-6">
							<img src="<?php echo base_url().$details[0]->profileImage;?>" height="50" width="50"/>
						</div>
						<div class="col-sm-3">
						<?php 
							$id = base64_encode($details[0]->regId);
							$id = str_replace(str_repeat('=',strlen($id)/4),"",$id);
							if($details[0]->isActive == 0){ 
						?>
						<a class="btn btn-danger" href="<?php echo base_url();?>member/activeAction/<?php echo $id?>" onclick="return(window.confirm('Are you sure you want to Active?'));">InActive</a>
						<?php }else{ ?>
							<a class="btn btn-success" href="<?php echo base_url();?>member/inActiveAction/<?php echo $id;?>" onclick="return(window.confirm('Are you sure you want to in Activate?'));">Active</a>
						<?php } ?>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-3 control-label" for="username">Name </label>
						<div class="col-sm-9">
							<input type="text" name="name" id="name" class="form-control" value="<?php echo $details[0]->name;?>" disabled/>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-3 control-label" for="username">Mobile </label>
						<div class="col-sm-9">
							<input type="text" name="name" id="name" class="form-control" value="<?php echo $details[0]->mobile;?>" disabled/>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-3 control-label" for="username">Gender</label>
						<div class="col-sm-9">
							<input type="text" name="name" id="name" class="form-control" value="<?php echo $details[0]->gender;?>" disabled/>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-3 control-label" for="username">Age </label>
						<div class="col-sm-9">
							<input type="text" name="name" id="name" class="form-control" value="<?php echo $details[0]->age;?>" disabled/>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-3 control-label" for="username">Location </label>
						<div class="col-sm-9">
							<input type="text" name="name" id="name" class="form-control" value="<?php echo $details[0]->city;?>" disabled/>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-3 control-label" for="username">Interested In </label>
						<div class="col-sm-9">
							<textarea name="name" rows="<?php echo count(explode(',',$details[0]->fieldsOfInterest));?>" class="form-control" readonly/><?php echo $interests;?></textarea>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-3 control-label" for="username">Status </label>
						<div class="col-sm-9">
							<input type="text" name="name" id="name" class="form-control" value="<?php if($details[0]->isActive == 0){echo 'Inactive';}else{ echo 'Active'; };?>" disabled/>
						</div>
					</div>
					
				</form>
			<?php  } ?> 
		</div>
    </div>
</div>