<div class="container-fluid main_container">
	<div class="row">
		<div class="container-fluid dashboard_bg">
			
			<div class="row" id="sticky-anchor">
				<div class="container-fluid maxwidth">
					<div class="center-block" style="margin-top:20px">
						<div class="container-fluid">
							<div class="profile_main">    	
								<div class="breadcrum">
									Select Topics
								</div>
								<div class="clearfix"></div>
        
								<p>Select more health topics of your interest, to get more personalised experience.</p>
								<div id="formError" style="display: none;" class="alert alert-danger"></div>
								<div id="formSuccess" style="display: none;" class="alert alert-success"></div>
								<form id="catSelect">
									<?php 
									$alpha = array('a','b','c');
									$index = 0;
									foreach($parentCat as $parent){ ?>
									<div class="topic_section">
										<div class="topic_title txtdark"><strong><?php echo $parent->catName;?></strong></div>
										<ul class="row topic_list">
										<?php 
											$i=1;
											foreach($subCat[$parent->catName] as $cat){ 	
										?>
										<li class="col-md-3 col-sm-4">
											<input type="checkbox" class="catCheck" name="cat[]" value="<?php echo $cat->catId; ?>" id="<?php echo $alpha[$index]?><?php echo $i;?>" <?php if(in_array($cat->catId,$interest)){echo "checked";}?>>
											<label class="topicBox" for="<?php echo $alpha[$index]?><?php echo $i;?>"> <span class="topicTick"></span>
											<span class="topicName"><?php echo $cat->catName; ?></span>
											</label>
										</li>
										<?php 
												$i++; 
											} 
										?>
										</ul>
										<div class="clearfix"></div>
									</div>
									<?php  $index++; } ?>
									
									<div class="form-group" style="text-align: center;">
										<input type="submit" id="submit" class="bluebtn btn" style="display:inline-block;" value="Save Changes"/>
									</div>								
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>	
	</div>    
</div>