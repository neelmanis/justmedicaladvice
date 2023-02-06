<div class="container-fluid main_container" id="sticky-anchor">
	<div class="row staticPage_holder">

		<div class="aboutSection" style="margin-bottom:20px;">
			<div class="container-fluid maxwidth">
				<div class="container-fluid abouttext text-center" style="padding-top:50px;padding-bottom:50px;">
				<h1 style="margin:0">Frequently Asked Questions</h1>
				</div>
			</div>
		</div>
    
    
		<div class="container-fluid maxwidth">
			<div class="container-fluid terms_policy txtdark text-justify">
				<ul class="faqList">
				<?php if($faqData !== 'No Data'){
					foreach($faqData as $faq){
				?>
				<li>
					<div class="faq_Question"><?php echo $faq->question; ?></div>
					<div class="faq_Answer"><?php echo $faq->answer; ?></div>
				</li>
				<?php 	}
					}
				?>
				</ul>
        
				<div class="clearfix"></div>
        
				<div class="text-center" style="margin-bottom:40px;background:#f3f3f3;padding:15px;">
					<p style="font-size:16px">Still not recieved an answer, that you are looking for?</p>
					<a href="" class="bluebtn btn" style="color:#fff;text-decoration:none">Contact Us</a>
				</div>
			</div>
		</div>
	</div>    
</div>