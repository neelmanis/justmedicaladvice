<div class="container-fluid main_container">
	<div class="row">
		<div class="container-fluid dashboard_bg">
			<div class="row searchbar_container">
				<div class="searchbar_box">
					<div class="container-fluid maxwidth">
						<div class="container-fluid">
						<form method="post" action="<?php echo base_url()?>blog/searchSubmit" style="margin-bottom:0;">
							<div class="input-group searchbar_holder">
								<div>
									<input type="hidden" id="cat" value="<?php echo $searchCat;?>"/>
									<input type="hidden" id="searchSubmit" value="<?php echo $searchSubmit;?>"/>
									<input class="form-control" placeholder="Search blogs, doctors..." id="searchText" name="searchText" autocomplete="off">
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
						<div class="col-md-9 breadcrum">
							<div class="row">
								<div class="col-sm-8"><div class="row">Blogs</div></div>
								<!--<div class="col-sm-4 text-right" style="font-size:12px;vertical-align:baseline">Total 10 articles</div>-->
							</div>
						</div>
		
						<div class="col-md-9 content_panel">
							<div id="result"></div>
							<button class="btn bluebtn" id="load_more" data-val = "1" style="margin:0 auto 20px;display:table;width:100%; max-width:280px;">View more Blogs</button>
						</div> 
						
						<div class="col-md-3 aside_panel" data-sticky_column>
							<div class="row">
								<div class="side_box">
									<div class="sb_title txtdark font_regular">Suggested Doctors</div>
									<?php $followedDocs = Modules::run('member/getDocList');  ?>
									<ul class="suggested_doc_list">
										<?php 
										if($docList !== "No Data"){ 
										foreach($docList as $doc){ ?>
										<li>
											<?php if($followedDocs !== 'No Data' && in_array($doc[4],$followedDocs)){ ?>
												<button id="<?php echo $doc[0] ?>" class="doct_follow_btn unfollow" data-toggle="tooltip" data-title="Following"><span></span></button>
											<?php }else{ ?>
												<button id="<?php echo $doc[0] ?>" class="doct_follow_btn follow" data-toggle="tooltip" data-title="Follow Doctor"><span></span></button>
											<?php } ?>
								
											<div class="doct_prof">
												<a href="<?php echo base_url()?>doctor/view/<?php echo $doc[0] ?>" class="doct_dp">
													<img src="<?php echo base_url()?><?php echo $doc[1] ?>">
												</a>
												<div class="doct_details">
													<a href="<?php echo base_url()?>doctor/view/<?php echo $doc[0] ?>" class="doct_name txtblue"><strong><?php echo $doc[2] ?></strong></a>
													<div class="doct_speciality"><?php echo $doc[3] ?></div>
													<div class="doct_follow_count font_regular"><?php echo $doc[5] ?> Followers</div>
												</div>
											</div>
										</li>	
									<?php  } } ?>
									</ul>
								</div>
        
								<div class="side_box">
									<div class="sb_title txtdark font_regular">Ask a question</div>
									<p style="font-size:11px;margin:0 0 5px;line-height:16px">To get advice from relevant doctors and insights from members with similar issues.</p>
									<div id="formError" style="display: none;" class="alert alert-danger"></div>
									<form id="ask_question">
									<div class="form-group" style="margin-bottom:10px;">
									<select class="selectpicker form-control" data-live-search="true" title="Select Speciality" id="speciality" name="speciality">
										<?php foreach($speciality as $val){ ?>
										<option value="<?php echo $val->spId;?>"><?php echo $val->spName; ?></option>
										<?php } ?>
									</select>
									</div>
									<div class="form-group" style="margin-bottom:10px;">
									<textarea class="form-control" id="question" name="question" placeholder="Enter your question..." style="width:100%;max-width:100%;height:60px;"></textarea>
									</div>
									<input type="hidden" name="visible" id="visible" value="all" />
									<div class="form-group text-center" style="margin-bottom:5px;"><button id="postForum" class="btn bluebtn" style="width:150px">Post Question</button></div>
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