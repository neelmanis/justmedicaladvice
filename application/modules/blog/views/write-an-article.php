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
							<div class="container-fluid pageform_title">Write an Article</div>
								<form id="blogForm" class="container-fluid">
                
									<div class="row">
										<div class="col-sm-6 txtdark font_regular form-group">Select Category<sup>*</sup>
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
                    
										<div class="col-sm-6 txtdark font_regular form-group">Select Sub-category<sup>*</sup>
										<select class="form-control selectpicker" title="Choose one" name="subcategory" id="subcategory">
										</select>
										</div>
									</div>
            
									<div class="row">
										<div class="col-sm-12 txtdark font_regular form-group">Title For The Article<sup>*</sup>
										<input type="text" name="title" id="title" class="form-control">
										</div>
									</div>
								
									<div class="row">
										<div class="col-sm-12 txtdark font_regular form-group">Image For Article
										<div class="formImg_upload">
											<div class="img_uploadPreview" id="imgPreview"></div>
											<div class="img_uploadBtn bluebtn btn">Upload Image<input type="file" name="image" id="image" title="Upload Image"></div>
											<div style="font-size:12px">Note: Kindly upload image of file size upto 1MB.</div>
											<div class="clearfix"></div>
										</div>
										</div>
									</div>
            
									<div class="row">
										<div class="col-sm-12 txtdark font_regular form-group">Write Here / Copy-Paste from document here<sup>*</sup>
										<textarea name="content" id="editor1" class="textEditor_Box"></textarea>
										</div>
									</div>
									
									<div class="row">
										<div class="col-sm-12 txtdark font_regular form-group">Reference Links (optional)
										<textarea name="reference" id="editor2" class="textEditor_Box"></textarea>
										</div>
									</div>
									
									<input type="hidden" name="visible" value="mem"/>
									<div class="row">
										<div class="col-sm-12 text-center"><input type="submit" class="bluebtn btn" value="Publish the article"></div>
									</div>
								</form>
							</div>
						</div>    	
        
						<div class="col-md-3 aside_panel" data-sticky_column>
							<div class="row">        
								<div class="side_box">
									<div class="sb_title txtdark font_regular">Tips for writing an articles</div>
									<ul class="article_tips">
										<li>Make use of words which are easy to understand for the population at large</li>
										<li>Include strong scientific evidence based information.</li>
										<li>Selecting a category, sub category and title is compulsory.</li>
										<li>Uploading a picture makes it more appealing to the eye. However, you can choose not to do so since it is optional</li>
										<li>You can either choose to write or copy and paste from other document where you have written down your article.</li>
										<li>Adding of Reference links is optional.</li>
										<li>Pressing on – “Publish the Article” button will showcase the same on our platform. You can view the same under your profile.</li>
										<li>In case of any doubts/ queries you can write to us at doctors@justmedicaladvice.com.</li>
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