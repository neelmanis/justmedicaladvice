<div class="container-fluid main_container">
	<div class="row">
		<div class="container-fluid dashboard_bg">
			<div class="row searchbar_container">
				<div class="searchbar_box">
					<div class="container-fluid maxwidth">
						<div class="container-fluid">
							<form style="margin-bottom:0;">
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
					<div class="center-block centerContainer">
						<div class="container-fluid breadcrum">
							<div class="row">
							<div class="col-sm-8">My Followers</div>
							<div class="col-sm-4 text-right" style="font-size:12px;vertical-align:baseline"> <?php if($memberList !== 'No data'){echo sizeof($memberList);}else{ echo '0'; }?> Followers</div>
							</div>
						</div>
        
						<div class="container-fluid userfollowers_list">
							
							<?php if($memberList !== 'No data'){ 
										foreach($memberList as $mem){ 
							?>
							<div class="user_listitem">
								<div class="user_pic"><img src="<?php echo $mem['image']; ?>"></div>
								<div class="user_info">
									<h2 class="txtblue"><?php echo $mem['name']?></h2>
									<p><strong>Location :</strong> <?php echo $mem['city']?></p>
									<p><strong>Age :</strong> <?php echo $mem['age']?> yrs</p>
									<p><strong>Mobile :</strong> <?php echo $mem['mobile']?></p>
									<p><strong>Email :</strong> <?php echo $mem['email']?></p>
								</div>
							</div>
							<?php } } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>    
</div>