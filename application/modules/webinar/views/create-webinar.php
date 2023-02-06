<div class="container-fluid main_container">
<div class="row">

	<div class="container-fluid dashboard_bg">
    
    <div class="row searchbar_container">
        <div class="searchbar_box hide">
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
    
    <div class="row" id="sticky-anchor">
    
    <div class="container-fluid maxwidth">
    <div class="container-fluid" data-sticky_parent>
        
    	<div class="col-md-9 content_panel">
        
        	<div class="pageform container-fluid">
            
            	<div class="container-fluid pageform_title">Create a Webinar</div>
                <div id="formError" style="display: none;" class="alert alert-danger"></div>
				<div id="formSuccess" style="display: none;" class="alert alert-success"></div>
                <form id="createWebinar" class="container-fluid">
                <div class="row">
                	<div class="col-sm-12 txtdark font_regular form-group">Subject for the webinar<sup>*</sup>
                	<input type="text" class="form-control" name="subject" id="subject">
                    </div>
                </div>
                
                <!--<div class="row hide">
                	<div class="col-sm-6 txtdark font_regular form-group">Select Start Date<sup>*</sup>
                	<input type="text" class="form-control form_datetime" id="startDate" name="startDate" readonly style="background:#fff">
                    </div>
                    
					<div class="col-sm-6 txtdark font_regular form-group">Select End Date<sup>*</sup>
                	<input type="text" class="form-control form_datetime" id="endDate" name="endDate" readonly style="background:#fff">
                    </div>
                </div>-->
					
				 <div class="row">
                	<div class="col-sm-6 txtdark font_regular form-group">Select Date<sup>*</sup>
                	<input type="text" class="form-control form_date" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input1" readonly style="background:#fff">
                    <input type="hidden" id="dtp_input1" name="edate" value="" />
                    </div>
                    
                	<div class="col-sm-3 col-xs-6 txtdark font_regular form-group">Select Start Time<sup>*</sup>
                        <input type="text" class="form-control form_time" data-date="" data-date-format="HH:00 P" data-link-field="dtp_input2" readonly style="background:#fff">
                        <input type="hidden" id="dtp_input2" name="startTime" value="" />
                    </div>
                    
                	<div class="col-sm-3 col-xs-6 txtdark font_regular form-group">Select End Time<sup>*</sup>
                        <input type="text" class="form-control form_time" data-date="" data-date-format="HH:00 P" data-link-field="dtp_input3" readonly style="background:#fff">
                        <input type="hidden" id="dtp_input3" name="endTime"  value="" />
                    </div>
                </div>
            
                <div class="row">
                	<div class="col-sm-12 txtdark font_regular form-group">Description<sup>*</sup>
                	<textarea name="description" id="description" class="textEditor_Box"></textarea>
                    </div>
                </div>
            
                <div class="row">
                	<div class="col-sm-12 text-center"><input type="submit" class="bluebtn btn" value="Create Webinar"></div>
                </div>
            
            	</form>
            
            </div>
        
        </div>    	
        
        <div class="col-md-3 aside_panel" data-sticky_column>
        <div class="row">
        
        	<div class="side_box">
            	<div class="sb_title txtdark font_regular">Tips for creating a webinar</div>
            	<ul class="article_tips">
                	<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
                    <li>Pretium quam vulputate dignissim suspendisse in est ante in nibh. Nec feugiat nisl pretium fusce id.</li>
                    <li>Facilisi etiam dignissim diam quis enim. Ultrices gravida dictum fusce ut placerat orci.</li>
                    <li>Porta lorem mollis aliquam ut porttitor. Purus in mollis nunc sed. Pretium quam vulputate dignissim suspendisse.</li>
                    <li>Convallis convallis tellus id interdum. Cursus eget nunc scelerisque viverra mauris in aliquam sem.</li>
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

