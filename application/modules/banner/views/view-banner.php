<div class="col-md-12 content_panel">
	<div class="article_Briefbox">
	
	<!-- Title of the Banner -->
	<h2 class="container-fluid article_title txtblue"><?php echo $bannerData[0]->title;?></h2>
	<h4 class="container-fluid"><?php echo $bannerData[0]->content;?></h4>
	
	<div class="container-fluid" style="margin-bottom:10px;">
		<div class="row">
			<div class="col-sm-9 col-sm-push-3">
				<div class="article_stats stats_align">
					<div class="counts"> 
						<?php 
						$id = base64_encode($bannerData[0]->bannerId);
						$id = str_replace(str_repeat('=',strlen($id)/4),"",$id);
						if($bannerData[0]->isActive == '0'){?>
							<a class="label label-danger label-mini" href="<?php echo base_url();?>banner/active_action/<?php echo $id;?>" onclick="return(window.confirm(\'Are you sure you want to Active?\'));">Inactive</a>
						<?php }else{?>		
							<a class="label label-success label-mini" href="<?php echo base_url();?>banner/inActive_action/<?php echo $id;?>" onclick="return(window.confirm(\'Are you sure you want to Deactive?\'));">Active</a>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="col-sm-3 col-sm-pull-9 txtdark font_regular"><?php echo date("d-M, Y ",strtotime($bannerData[0]->createdDate))?></div>
		</div>
	</div>            
	
	<!-- Banner Image -->
	<div class="container-fluid article_content">
		<img src="<?php echo base_url().$bannerData[0]->image;?>" height="450" width="700"/>
	</div>    

	<div class="container-fluid comments_area">
		<div><h4><a href="<?php echo $bannerData[0]->url;?>"><?php echo $bannerData[0]->url;?></a></h4></div>
		
		<div><h4>Added By : <?php echo $user;?></h4></div>
	</div>
	<div class="clearfix"></div>
</div>