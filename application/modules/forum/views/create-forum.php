<div class="container-fluid main_container">
	<div class="row">
		<div class="container-fluid dashboard_bg">    
			<div class="row searchbar_container">
				<div class="searchbar_box">
					<div class="container-fluid maxwidth">
						<div class="container-fluid">
							<form method="post" action="<?php echo base_url()?>forum/searchSubmit" style="margin-bottom:0;">
								<div class="input-group searchbar_holder">
									<div>
										<input class="form-control" placeholder="Search Forum..." id="searchText" name="text" autocomplete="off">
										<div class="suggestion_dd" style="display:none;">
											<div class='result_box'>
											</div>
										</div>
									</div>
									<div class="input-group-btn"><input type="submit" class="btn bluebtn"value="SEARCH"></div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
    
			<div class="row" id="sticky-anchor">
				<div class="container-fluid maxwidth">
					<div class="container-fluid" data-sticky_parent>
						<div class="col-md-9 content_panel">
							<div class="pageform container-fluid">
								<div class="container-fluid pageform_title">Create a Forum</div>
									<div id="formError" style="display: none;" class="alert alert-danger"></div>
									<div id="formSuccess" style="display: none;" class="alert alert-success"></div>
                
									<form id="ask_question" class="container-fluid">
                
									<div  class="row">
										<div class="col-sm-12 txtdark font_regular form-group">Select Speciality<sup>*</sup>
											<select class="selectpicker form-control" data-live-search="true" title="Select Speciality" id="speciality" name="speciality">
												<?php foreach($speciality as $val){ ?>
												<option value="<?php echo $val->spId;?>"><?php echo $val->spName; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<input type="hidden" name="visible" id="visible" value="all" />
									<div class="row">
										<div class="col-sm-12 txtdark font_regular form-group">Type Your Question<sup>*</sup>
										<textarea class="form-control" id="question" name="question" placeholder="Enter your question..." style="min-height:100px;"></textarea>
										</div>
									</div>
            
									<div class="row">
										<div class="col-sm-12 text-center"><button id="postForum" class="btn bluebtn" style="width:250px">Create Forum</button></div>
									</div>
								</form>
							</div>
						</div>    	
        
						<div class="col-md-3 aside_panel" data-sticky_column>
							<div class="row">        
								<div class="side_box">
									<div class="sb_title txtdark font_regular">Tips for creating forum</div>
									<ul class="article_tips">
										<li>A forum question is utilized mainly by a member who has a query with respect to a particular medical condition or topic.</li>
										<li>A Doctor can ask questions to check for awareness with respect to a particular topic and use it to clear doubts with respect to the same.</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>	
    </div>    
</div>