<link rel="stylesheet" href="<?php echo base_url(); ?>admin_assets/css/easy-responsive-tabs.css">
<script src="<?php echo base_url(); ?>admin_assets/js/easy-responsive-tabs.js"></script>
<script>
$(document).ready(function () {
$('#horizontalTab').easyResponsiveTabs({
type: 'default', //Types: default, vertical, accordion           
width: 'auto', //auto or any width like 600px
fit: true,   // 100% fit in a container
closed: 'accordion', // Start closed if in accordion view
activate: function(event) { // Callback function if tab is switched
var $tab = $(this);
var $info = $('#tabInfo');
var $name = $('span', $info);
$name.text($tab.text());
$info.show();
}
});
});
</script>

<div class="row mt">
    <div class="col-lg-12">
		<div class="content-panel">
			<h4>View Details</h4>
			<div class="clear"></div>
			<div id="horizontalTab">
				<ul class="resp-tabs-list">
					<li>PERSONAL INFORMATION</li>
					<li>DOCUMENTS SUBMITTED</li>
				</ul>
				<div class="resp-tabs-container">
					<div>
						<div class="profile_right fade_anim shadow_style">  
							<h4> Personal Information  </h4>    
							<?php if(is_array($details)){ ?>    
							<div class="clear"> </div>
							<form id=" " class="form-horizontal style-form categoryform">
					
								<div class="form-group">
									<label class="col-sm-3 control-label" for="username">Profile Image</label>
									<div class="col-sm-3">
										<img src="<?php echo base_url().$details[0]->profileImage;?>" height="100" width="100"/>
									</div>
									<div class="col-sm-3">
									<?php 
										$id = base64_encode($details[0]->regId);
										$id = str_replace(str_repeat('=',strlen($id)/4),"",$id);
										if($details[0]->isActive == 0){ 
									?>
										<a class="btn btn-danger btn-block" href="<?php echo base_url();?>doctor/active_action/<?php echo $id;?>" onclick="return(window.confirm('Are you sure you want to Active?'));">InActive</a>
										<?php }else{ ?>
										<a class="btn btn-success" href="<?php echo base_url();?>doctor/inActive_action/<?php echo $id;?>" onclick="return(window.confirm('Are you sure you want to in Activate?'));">Active</a>
									<?php } ?>
									</div>
									<div class="col-sm-3 hide">									
									<?php 
										if($details[0]->isFeatured == 0){ 
									?>
										<a class="btn btn-danger btn-block" href="<?php echo base_url();?>doctor/markAsFeatured/<?php echo $id;?>" onclick="return(window.confirm('Are you sure you want to mark as Featured?'));">Mark As Featured</a>
										<?php }else{ ?>
										<a class="btn btn-success" href="<?php echo base_url();?>doctor/unmarkAsFeatured/<?php echo $id;?>" onclick="return(window.confirm('Are you sure you want to remove as featured ?'));">Featured</a>
										<?php } ?>
									</div>
								</div>
					
								<div class="form-group">
									<label class="col-sm-3 control-label" for="username">Name </label>
									<div class="col-sm-9">
										<input type="text" name="name" id="name" class="form-control" value=" <?php echo $details[0]->name;?>" disabled/>
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
									<label class="col-sm-3 control-label" for="username">Degree </label>
									<div class="col-sm-9">
										<textarea name="name" rows="<?php echo count(explode(',',$details[0]->degree));?>" class="form-control" readonly><?php echo $degree;?></textarea>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label" for="username">Speciality </label>
									<div class="col-sm-9">
										<textarea name="speciality" rows="<?php echo count(explode(',',$details[0]->speciality));?>" class="form-control" readonly><?php echo $speciality;?></textarea>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label" for="username">Experience </label>
									<div class="col-sm-9">
										<input type="text" name="name" id="name" class="form-control" value="<?php echo $details[0]->experience;?> years" disabled/>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label" for="username">City </label>
									<div class="col-sm-9">
										<input type="text" name="name" id="name" class="form-control" value="<?php echo $details[0]->city;?>" disabled/>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label" for="username">Status </label>
									<div class="col-sm-9">
										<input type="text" name="name" id="name" class="form-control" value="<?php if($details[0]->isActive == 0){echo 'Inactive';}else{ echo 'Active'; };?>" disabled/>
									</div>
								</div>
							</form>
							<?php } ?>
						</div>
					</div>
					<div>
						<div class="profile_right fade_anim shadow_style">		
							<h4>Documents Submitted</h4>						
							<form id=" " class="form-horizontal style-form categoryform">
								<div class="form-group">
									<div class="col-sm-12">
										<a id="single_image" href="<?php echo base_url().$details[0]->govProof;?>"><img src="<?php echo base_url().$details[0]->govProof;?>" height="350" width="350" alt=""/></a>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-12">
										<a id="single_image" href="<?php echo base_url().$details[0]->degreeProof;?>"><img src="<?php echo base_url().$details[0]->degreeProof;?>" height="350" width="350" alt=""/></a>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-12">
										<a id="single_image" href="<?php echo base_url().$details[0]->medProof;?>"><img src="<?php echo base_url().$details[0]->medProof;?>" height="350" width="350" alt=""/></a>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
        </div>
    </div>
</div>
<script>
$(document).ready(function () {
	$("a#single_image").fancybox();
});
</script>