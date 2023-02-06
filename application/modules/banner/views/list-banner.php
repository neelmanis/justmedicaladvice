<div class="row mt">
    <div class="col-lg-12">
        <div class="content-panel">
        	<h4>View Banners</h4>
			<div class="col-lg-4"></div>
			<div class="col-lg-4"></div>
			<div class="col-lg-4">
				<a href="<?php echo base_url('banner/addBanner');?>"><button class="btn btn-success btn-lg btn-block">Add New Banner</button></a>
			</div>
			
			<div class="clearfix" style="height:20px;"></div>
			
	        <section id="no-more-tables">
				<table class="table table-striped table-condensed cf" id='bannerTable'>
					<thead class="cf">
						<tr>
							<th>Id</th>
							<th>Image</th>
							<th>Title</th>
							<th>Added By</th>
							<th>Created Date</th>
							<th>Status</th>
							<th>Options</th>
						</tr>
					</thead>
					<tbody>
					<?php 
						if(is_array($bannerList) && !empty($bannerList)){
							$i = 1;
							foreach($bannerList as $banner){
								$url = base_url();
								if($banner['status'] == 0){
									$msg = '<a class="label label-danger label-mini" href="'.$url.'banner/active_action/'.$banner['id'].'" onclick="return(window.confirm(\'Are you sure you want to Active?\'));">Inactive</a>';           
								}else{
									$msg = '<a class="label label-success label-mini" href="'.$url.'banner/inActive_action/'.$banner['id'].'" onclick="return(window.confirm(\'Are you sure you want to Deactive?\'));">Active</a>';  
								}
					?> 
					<tr>
						<td data-title="id"><?php echo $i;?></td>
						<td data-title="image"><img src="<?php echo base_url().$banner['image'];?>" height="250"width="350"/></td>
						<td data-title="title"><?php echo $banner['title'];?></td>
						<td data-title="addedBy"><?php echo $banner['addedBy'];?></td>
						<td data-title="date"><?php echo $banner['createdDate'];?></td>
						<td data-title="Status"><?php echo $msg; ?></td>
						<td data-title="Actions">
							<!--<a class="btn btn-primary btn-xs" href="<?php echo base_url();?>banner/viewBanner/<?php echo $banner['id']?>"><i class="fa fa-eye" aria-hidden="true"></i></a>-->
							<a class="btn btn-primary btn-xs" href='<?php echo base_url();?>banner/editBanner/<?php echo $banner['id'];?>'><i class="fa fa-pencil"></i></a>
							<a class="deleterows btn btn-danger btn-xs" id='<?php echo $banner{'id'};?>' ><i class="fa fa-trash-o "></i></a>
						</td>
					</tr>
					<?php
						$i++; } } 
					?>
				</tbody>
			</table>        	
		</div>
    </div>
</div>

<script>				
$(document).ready(function(){
$('#bannerTable').DataTable({
	paging:         true,
	columnDefs: [
		{ width: "10%", targets: 0 },
		{ width: "20%", targets: 1 },
		{ width: "30%", targets: 2 },
		{ width: "10%", targets: 3 },
		{ width: "10%", targets: 4 },
		{ width: "10%", targets: 5 },
		{ width: "10%", targets: 6 }
	],
	fixedColumns: true
});
$('body').delegate('.deleterows','click',function(e){
		 if (confirm("Do you want to delete?")) {
			e.preventDefault();
			var bid = this.id;
			  $.ajax({
				type:"POST",
				url:"<?php echo base_url();?>banner/deleteBanner",
				data:{bannerId:bid},
				success:function(result){
					alert("Banner Deleted successfuly..");
					location.reload(true);
				}
			}) 
		 }
		});
	});			
</script>				