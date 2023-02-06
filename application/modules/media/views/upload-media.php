<div class="container-fluid main_container">
	<div class="row">
		<div class="container-fluid dashboard_bg">
			<div class="row searchbar_container">
				<div class="searchbar_box">
					<div class="container-fluid maxwidth">
						<div class="container-fluid">
						<form style="margin-bottom:0;">
							<div class="input-group searchbar_holder">
								<div>
									<input class="form-control" placeholder="Search blogs,forums,videos/audios ..." id="searchText" name="searchText" autocomplete="off">
									<div class="suggestion_dd" style="display:none;">
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
							<div id="formError" style="display: none;" class="alert alert-danger"></div>
							<div id="formSuccess" style="display: none;" class="alert alert-success"></div>
							<div class="container-fluid pageform_title">Upload a Video / Audio</div>
								<form id="mediaForm" class="container-fluid">
			
									<div class="row">
										<div class="col-sm-6 txtdark font_regular form-group">Select Category <sup>*</sup>
										<select class="form-control selectpicker" title="Choose one" name="category" id="category">
										<?php if(is_array($category)){
											foreach($category as $cat){ 
										?>
										<option value="<?php echo $cat->catId;?>"><?php echo $cat->catName;?></option>	
										<?php	
											} } 
										?>
										</select>
										</div>
                    
										<div class="col-sm-6 txtdark font_regular form-group">Select Sub-category <sup>*</sup>
										<select class="form-control selectpicker" title="Choose one" name="subcategory" id="subcategory">
										</select>
										</div>
									</div>
		
									<div class="row">
										<div class="col-sm-12 txtdark font_regular form-group">Title For The Video/Audio <sup>*</sup>
										<input type="text" id="title" name="title" class="form-control">
										</div>
									</div>
									
									<div class="row">
										<div class="col-sm-6 txtdark font_regular form-group">Select Type Of Media <sup>*</sup>
										<select class="form-control selectpicker" title="Choose one" name="mtype" id="mtype">
										<option value="video">Video</option>	
										<option value="audio">Audio</option>	
										</select>
										</div>
                    
										<div class="col-sm-6 txtdark font_regular form-group" id="contentType">Select Format <sup>*</sup>
										<select class="form-control selectpicker" title="Choose one" name="ctype" id="ctype">
										<option value="upload">Upload File</option>	
										<option value="youtube">Add YouTube Link</option>
										</select>
										</div>
									</div>
									
									<div class="row">
										<div id="upld" class="col-sm-12 txtdark font_regular form-group">Select a Video/Audio File to Upload (mp4/mp3)<sup>*</sup>
											<!--<div>
												<input type="file" id="media" name="media" class="fileuploader">
											</div>-->
											<div class="file_upload">
												<input type="file" id="media" name="media" onChange="document.getElementById('video_file').value = this.value;">
												<div class="file_field graybtn"><input type="text" value="" placeholder="No file chosen" class="form-control" id="video_file"></div>
											</div>
										</div>
										
										<div id="ytube" class="col-sm-12 txtdark font_regular form-group">Paste the video code form the YouTube below<sup>*</sup>
										<input type="text" name="link" id="link" class="form-control" placeholder="eg. WvQ69t3vkpQ">
										<div><img src="<?php echo base_url(); ?>admin_assets/images/youtube-hint.jpg""></div>
										</div>
									</div>
		
									<div class="row">
										<div class="col-sm-12 txtdark font_regular form-group">Description (optional)
										<textarea name="description" id="editor1" class="textEditor_Box"></textarea>
										</div>
									</div>
									<input type="hidden" name="visible" value="mem">
									<div class="row">
										<div class="col-sm-12 text-center"><input type="submit" class="bluebtn btn" value="Publish the Video/Audio"></div>
									</div>
								</form>
							</div>
						</div>    	
        
						<div class="col-md-3 aside_panel" data-sticky_column>
							<div class="row">
								<div class="side_box">
									<div class="sb_title txtdark font_regular">Tips for uploading a video/audio </div>
									<ul class="article_tips">
										<li> Video/Audio is a powerful medium to share your medical advice with the relevant patient population.</li>
										<li>Include strong scientific evidence based information.</li>
										<li>If you choose the media type as video, you will need to choose from the either of the options present in select file type – Upload File or Add youtube Link</li>
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