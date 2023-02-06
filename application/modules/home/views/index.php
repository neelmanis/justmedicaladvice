<div class="container-fluid main_container" id="sticky-anchor">
    <div class="row">
	    
		<!-- Search Section -->
        <div class="home_search">
            <div class="container-fluid maxwidth">
                <div class="container-fluid">
                    <div class="homesearch_form">
                       <form id="homeSearch">
							<div class="input-group searchbar_holder">
								<input class="form-control" placeholder="Search blogs, videos, forums, doctors..." id="searchText" name="searchText" autocomplete="off">
								<div class="input-group-btn"><button class="btn bluebtn">Search</button></div>
								<div class="suggestion_dd" style="display:none;">
								</div>
							</div>
						</form>
                    </div>
                </div>
            </div>
        </div>
		
	    <!-- Banner Section -->
        <div class="homeSlider slick_bullet scroll_circle_arw">
            <?php if($banner !== 'no'){
				foreach($banner as $val){
			?>
			<div>
                <div class="slide"><img src="<?php echo base_url().$val->image;?>">
                    <div class="slideTextbox">
                        <div class="slidetxt"><p><?php echo $val->title;?></p>
						<!--<p><?php echo $val->content;?></p> -->
						<?php if($val->ctype == "blog"){ ?>
							<a href="<?php echo base_url().'home/read/'.$val->url;?>" class="btn bluebtn db_slideBtn">Read Now</a>
						<?php }else if($val->ctype == "media"){ ?>
							<a href="<?php echo base_url().'media/view/'.$val->url;?>" class="btn bluebtn db_slideBtn">Watch</a>
						<?php }else if($val->ctype == "forum"){ ?>
							<a href="<?php echo base_url().'forum/view/'.$val->url;?>" class="btn bluebtn db_slideBtn">Read Now</a>
						<?php } ?>
						</div>
                    </div>
                </div>
            </div>
            <?php } } ?>
        </div>
    
		<!-- Video Section -->
		<div class="videoSection">
			<div class="container-fluid maxwidth">
				<div class="container-fluid abouttext text-center">
				<h1 class="txtdark">Advice beyond Appointments</h1>
				<p class="hide">For relevant, evidence based medical advice, necessary for leading a healthy life.</p>
				</div>
			</div>
			<div class="homevideo">
				<video controls controlsList="nodownload">
					<source src="<?php echo base_url();?>public/images/brandvideo.mp4" type="video/mp4">
					Your browser doesn't support HTML5 video tag.
				</video>
			</div>
		</div>
    
        <!-- Doctor Scroll Section -->
        <div class="mvd_scroll_main">
            <div class="container-fluid maxwidth">
                <div class="container-fluid text-center">
                    <h1 class="txtdark">Doctors</h1>
                    <p class="hide">As Doctors provide more ways for their patients to engage with them outside their clinics and online.</p>
                    <div class="clearfix"></div>
                
                    <div class="mvdscroll scroll_circle_arw">
                        <?php if($doctors !== 'No Data'){
							foreach($doctors as $val){
						?>
                        <div>
                            <a href="<?php echo base_url().'doctor/view/'.$val['id'];?>" class="mvd_box fade_anim">
                            <div class="doc_viewcount"><!--<img src="<?php echo base_url()?>public/images/eye_white.svg"> 340--></div>
                            <div class="clearfix"></div>
                            <div class="doc_img"><img src="<?php echo base_url().$val['image'];?>"></div>
                            <h2 class="doc_name"><?php echo $val['name']; ?></h2>
                            <div class="doc_design"><?php echo $val['degree']; ?></div>
							<div class="doc_design"><?php echo $val['city']; ?></div>
							<div class="doc_followcount"><?php echo $val['follower']; ?> Followers</div>
                            </a>
                        </div>
						<?php } } ?>
					</div>
					<a href="<?php echo base_url();?>login" class="mvdviewall fade_anim">View all Doctors</a>
                </div>
            </div>
        </div>
    
	    <!-- Video Section -->    
        <div class="fv_scroll_main">
            <div class="container-fluid maxwidth">
                <div class="container-fluid text-center">
                    <h1 class="txtdark">Videos</h1>
                    <p class="hide">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh  volutpat.</p>
                    <div class="clearfix"></div>
					<?php if($media !== 'No Data'){ ?>
                    <div class="fvscroll scroll_circle_arw text-left">
                        <?php foreach($media as $val){ ?>
						<div>
                            <div class="fv_box">
							<?php if($val['mtype'] == 'video' && $val['ctype'] == 'youtube'){?>
                            <div class="fv_video">
							<iframe src="<?php echo $val['url'];?>?rel=0&autoplay=0&showinfo=0&controls=0" width="400" frameborder="0" allowfullscreen></iframe>
							</div>
							<?php }else if($val['mtype'] == 'video' && $val['ctype'] == 'upload'){ ?>
							<div class="">
							<video width="350" height="200" controls controlsList="nodownload">
								<source src="<?php echo $val['url'];?>" type="video/mp4">
								Your browser doesn't support HTML5 video tag.
							</video>
							</div>
							<?php } ?>
							
                            <div class="fv_videotxt txtdark"><?php echo $val['name'];?> - <?php echo $val['title'];?></div>
							</div>
                        </div>
						<?php } ?>
                    </div>
					<a href="<?php echo base_url();?>login" class="bluebtn fade_anim">More Videos</a>
					<?php } ?>
                </div>
            </div>
        </div>
    
        <!-- Articles Section -->
        <div class="articlesSection">
            <div class="container-fluid maxwidth">
                <div class="container-fluid text-center">
                    <h1 class="txtdark">Articles</h1>
                    <p class="hide">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh  volutpat.</p>
                    <div class="clearfix" style="height:15px;"></div>
            
                    <div class="articles_main text-left">
						<?php 
						if($blogs !== 'No Data'){
							$i = 1;
							foreach($blogs as $val){
								if($i ==  2){
							?>		
							<div class="article_item">
								<div class="article_box newsletterBox fade_anim">
									<h2 class="txtwhite" style="font-size:24px">Stay Updated</h2>
									<p class="txtwhite">Subscribe to receive alerts on our latest updates, events and much more</p>
									<div class="clearfix" style="height:20px"></div>
									<form id="subscribe">
										<div class="input-group newsletterForm">
											<input type="text" class="form-control" placeholder="Enter Email id" name="emailId">
											<span class="input-group-btn">
												<button id="subscribebtn" class="btn" type="button"></button>
											</span>
										</div>
									</form>
									
									<div class="txtwhite" style="height:50px;display:none;"><strong> Thank You! </strong> <span style="font-size:30px">&#9786;</span><br> We will keep you updated...</div>
								</div>
							</div>
							<?php }	?>
							<div class="article_item">
								<a href="<?php echo base_url();?>home/read/<?php echo $val['slug']; ?>" class="article_box fade_anim">
									<div class="article_img"><img src="<?php echo base_url().$val['image'];?>"></div>
									<div class="article_date"><?php echo $val['date']; ?></div>
									<h2 class="txtdark"><?php echo $val['title']; ?></h2>
									<p class="short_text"><?php echo $val['desc']; ?> ...</p>
									<div class="author"><?php echo $val['name']; ?></div>
									<div class="bluebtn readbtn">Read more</div>
								<div class="clearfix"></div>
								</a>
							</div>
						<?php $i++; 
							}
						?>
						<div class="article_item article_morebtn"><a href="<?php echo base_url();?>login" class="bluebtn">More Articles</a></div>
						<?php							
						}
						?>
                    </div>
                </div>
            </div>
        </div>
		
		<!-- Events & Testimonial Section -->
		<div class="count_testim_main">
			<div class="container-fluid maxwidth">
				<div class="container-fluid">
					<div class="container-fluid" style="background:#f9f9f9;">
						<div class="row">
							<div class="ct_table">
								<div class="col-md-5 statistics_box txtwhite">
									<h1 style="text-align:center;">Events</h1>
									<div class="clearfix" style="height:15px;"></div>
									<?php if($events !== 'no'){
										foreach($events as $res){ 
									?>
									<div class="eventTitle col-sm-12">
										<div class="calender">
											<div class="year"><?php echo date("Y ",strtotime($res->eDate)); ?></div><div class="date font_regular txtdark"><strong><?php echo date("d",strtotime($res->eDate)); ?></strong><?php echo date("M",strtotime($res->eDate)) ?></div>
										</div>
										<div class="info">
											<a href="<?php echo base_url();?>event/details" class="eventName"><?php echo $res->title; ?></a>
											<?php 
												$now = strtotime("now");
												$date = strtotime($res->eDate);
												if($date > $now){
													echo '<span class="upcomingTag">Upcoming</span>';
												}
											?>
											<p><?php echo $res->venue; ?></p>
											<p><?php echo $res->eTime; ?></p>
										</div>
									</div>
									<?php } } ?>
									<div class="clearfix"></div>    
								</div>                
                
								<div class="col-md-7">
									<div class="home_testimonials text-center">
										<h1 class="txtdark">Testimonials</h1>
										<div class="clearfix" style="height:25px;"></div>
										<?php if($testimonials !== 'no'){ ?>
										<div class="testimonials_scroll slick_bullet">
											<?php foreach($testimonials as $tsm){ ?>
                                            <div>
                                                <?php echo $tsm->testDesc; ?>
							    	            <p class="testim_author"><strong class="txtdark"><?php echo $tsm->testName; ?></strong>
												<small class="hide">Designation</small></p>
                                            </div>
							                <?php } ?>
										</div>
										<?php } ?>
									</div>                
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div style="background:#023086">
            <div class="container-fluid text-center txtwhite maxwidth footsignup">TO KNOW MORE &nbsp;&nbsp; <a href="<?php echo base_url()?>signup" class="bluebtn">Sign Up Now</a> 
			</div>
        </div> 
    </div>
</div>