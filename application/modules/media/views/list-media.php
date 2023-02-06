<div class="row mt">
    <div class="col-lg-12">
        <div class="content-panel">
        	<h4>View Video/Audio</h4>
			<div class="col-lg-4"></div>
			<div class="col-lg-4"></div>
			<div class="col-lg-4">
				<a href="<?php echo base_url('media/newMedia');?>"><button class="btn btn-success btn-lg btn-block">Add Video/Audio</button></a>
			</div>
			
			<div class="clearfix" style="height:20px;"></div>
			
	        <section id="no-more-tables">
				<table class="table table-striped table-condensed cf" id='bannerTable'>
					<thead class="cf">
						<tr>
							<th>Id</th>
							<th>Type</th>
							<th>Title</th>
							<th>Category</th>
							<th>Posted By</th>
							<th>Name</th>
							<th>Created Date</th>
							<th>Visible</th>
							<th>Report</th>
							<th>Status</th>
							<th>Home</th>
							<th>Options</th>
						</tr>
					</thead>
					<tbody>
					<?php 
						if(is_array($mediaList) && !empty($mediaList)){
							$i = 1;
							foreach($mediaList as $media){
								$url = base_url();
								if($media['status'] == 0){
									$msg = '<a class="label label-danger label-mini" href="'.$url.'media/active_action/'.$media['id'].'" onclick="return(window.confirm(\'Are you sure you want to Active?\'));">Inactive</a>';           
								}else{
									$msg = '<a class="label label-success label-mini" href="'.$url.'media/inActive_action/'.$media['id'].'" onclick="return(window.confirm(\'Are you sure you want to Deactive?\'));">Active</a>';  
								}
								
								if($media['isHome'] == 0){
									$home = '<a class="label label-danger label-mini" href="'.$url.'media/setHome/'.$media['id'].'" onclick="return(window.confirm(\'Are you sure you want to set Media on Home Page?\'));">No</a>';           
								}else{
									$home = '<a class="label label-success label-mini" href="'.$url.'media/unsetHome/'.$media['id'].'" onclick="return(window.confirm(\'Are you sure you want to remove Media from Home Page?\'));">Yes</a>';  
								}
					?> 
					<tr>
						<td data-title="id"><?php echo $i;?></td>
						<td data-title="title"><?php echo $media['mtype'];?></td>
						<td data-title="title"><?php echo $media['title'];?></td>
						<td data-title="catName"><?php echo $media['catName'];?></td>
						<td data-title="postedBy"><?php echo $media['postedBy'];?></td>
						<td data-title="name"><?php echo $media['name'];?></td>
						<td data-title="date"><?php echo $media['createdDate'];?></td>
						<td data-title="Status"><?php echo $media['visible']; ?></td>
						<td data-title="Status"><?php echo $media['report']; ?></td>
						<td data-title="Status"><?php echo $msg; ?></td>
						<td data-title="Status"><?php echo $home; ?></td>
						<td data-title="Actions">
							<a class="btn btn-primary btn-xs" href="<?php echo base_url();?>media/viewMedia/<?php echo $media['slug']?>"><i class="fa fa-eye" aria-hidden="true"></i></a>
							<a class="btn btn-primary btn-xs" href='<?php echo base_url();?>media/editMedia/<?php echo $media['id'];?>' title='Edit Category'><i class="fa fa-pencil"></i></a>
							<a class="deleterows btn btn-danger btn-xs" id='<?php echo $media{'id'};?>' title='Delete Category'><i class="fa fa-trash-o "></i></a>
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
		{ width: "5%", targets: 0 },
		{ width: "5%", targets: 1 },
		{ width: "20%", targets: 2 },
		{ width: "10%", targets: 3 },
		{ width: "10%", targets: 4 },
		{ width: "15%", targets: 5 },
		{ width: "5%", targets: 6 },
		{ width: "5%", targets: 7 },
		{ width: "5%", targets: 8 },
		{ width: "5%", targets: 9 },
		{ width: "5%", targets: 10 },
		{ width: "10%", targets: 11 }
	],
	fixedColumns: true
});
$('body').delegate('.deleterows','click',function(e){
		 if (confirm("Do you want to delete?")) {
			e.preventDefault();
			var mid = this.id;
			  $.ajax({
				type:"POST",
				url:"<?php echo base_url();?>media/deleteMedia",
				data:{mediaId:mid},
				success:function(result){
					alert("Media Deleted successfuly..");
					location.reload(true);
				}
			}) 
		 }
		});
	});			
</script>				