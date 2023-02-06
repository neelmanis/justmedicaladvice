<div class="container-fluid main_container">
	<div class="row">
		<div class="container-fluid dashboard_bg">
			<div class="row searchbar_container hide">
				<div class="searchbar_box">
					<div class="container-fluid maxwidth">
						<div class="container-fluid">
						<form>
							<div class="input-group searchbar_holder">
								<input class="form-control" placeholder="Search blogs, videos, forums, doctors...">
								<div class="input-group-btn"><button class="btn bluebtn">Search</button></div>
							</div>
						</form>
						</div>
					</div>
				</div>
			</div>
			
			<div class="clearfix" style="height:20px;"></div>
			<div class="row" id="sticky-anchor">
				<div class="container-fluid maxwidth">
					<div class="center-block centerContainer">
						<div class="container-fluid breadcrum">
							Contact Doctor
						</div>
						<div class="clearfix"></div>
						<div class="container-fluid">
							<div class="contactDoc_main">
								<div class="doc_profile_table">
									<div class="col-sm-8 doc_details">
										<div class="doc_pic"><img src="<?php echo $doctor['image'];?>"></div>
										<div class="doc_info_box">
										<div class="doc_name"><h2 class="txtdark"><?php echo $doctor['name'];?></h2> 
										<?php if($doctor['featured']==1){ ?>
										<img src="<?php echo base_url();?>public/images/approved_tag.png" class="verified_tag" data-toggle="tooltip" data-title="Just Medical Advice Verified Doctor">
										<?php } ?>
										</div>
										<ul class="dd_creds">
											<li><span class="ddc_icon ic_1"></span> <?php echo $doctor['degree']; ?> - <?php echo $doctor['speciality'];?></li>
											<li><span class="ddc_icon ic_2"></span> <?php echo $doctor['city']; ?></li>
										</ul>
									</div>
									<div class="clearfix"></div> 
								</div>
                
								<div class="col-sm-4 doc_stats">
									<table>
										<tr><td align="right"><strong><?php echo $doctor['followedMem']; ?></strong></td><td>Followers</td></tr>
										<tr><td align="right"><strong><?php echo $doctor['thanks']; ?></strong></td><td>Thanks recieved</td></tr>
										<tr><td align="right"><strong><?php echo $doctor['answerCount']; ?></strong></td><td>Forums answered</td></tr>
										<tr><td align="right"><strong><?php echo $doctor['feedbackCount']; ?></strong></td><td>Feedbacks</td></tr>
									</table>
								</div>
								<div class="clearfix"></div>
							</div>
        
							<div class="clearfix"></div>
								<div class="container-fluid contactDoc_form">
									<div class="row">
										<div style="background:#f6f6f6;text-align:center;padding:10px;margin-bottom:15px;" class="txtdark"><strong>Send a personal message and ask your query directly to a doctor.</strong></div>
									</div>
									<?php
										$userData = $this->session->userdata('userData');
										$userId = $userData['userId'];
										$type = $userData['type'];
									?>
									<div id="formError" style="display: none;" class="alert alert-danger"></div>
									<div id="formSuccess" style="display: none;" class="alert alert-success"></div>
									
									<form id="doctorContactForm">
										<div class="col-sm-12 txtdark font_regular form-group">Subject for your message<sup>*</sup>
											<input type="text" id="subject" name="subject" class="form-control">
											<input type="hidden" id="utype" name="utype" value="<?php echo $type; ?>"/>
											<input type="hidden" id="uid" name="uid" value="<?php echo $userId; ?>"/>
											<input type="hidden" id="docid" name="docid" value="<?php echo $doctor['regId']; ?>"/>
										</div>
                
										<div class="col-sm-12 txtdark font_regular form-group">Your Message<sup>*</sup>
											<textarea id="message" name="message" class="form-control" placeholder="Type your query here..." style="max-width:100%;height:150px;"></textarea>
										</div>
                
										<input type="submit" value="Send Message" class="bluebtn btn center-block" style="display:block">
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>	
	</div>    
</div>