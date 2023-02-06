<div class="row mt">
    <div class="col-lg-12">
        <div class="content-panel">
        	<h4>View Forum</h4>
			<div class="col-lg-4"></div>
			<div class="col-lg-4"></div>
			<div class="col-lg-4">
				<a href="<?php echo base_url('forum/addForum');?>"><button class="btn btn-success btn-lg btn-block">Add New Forum</button></a>
			</div>
			
			<div class="clearfix" style="height:20px;"></div>
	        <section id="no-more-tables">
				<table class="table table-striped table-condensed cf" id='bannerTable'>
					<thead class="cf">
						<tr>
							<th>Id</th>
							<th>Speciality</th>
							<th>Question</th>
							<th>Posted By</th>
							<th>Name</th>
							<th>Created Date</th>
							<th>Visible To</th>
							<th>Status</th>
							<th>Options</th>
						</tr>
					</thead>
					<tbody>
					<?php 
						if(is_array($forumList) && !empty($forumList)){
							$i = 1;
							foreach($forumList as $forum){
								$url = base_url();
								if($forum['status'] == 0){
									$msg = '<a class="label label-danger label-mini" href="'.$url.'forum/active_action/'.$forum['id'].'" onclick="return(window.confirm(\'Are you sure you want to Active?\'));">Inactive</a>';           
								}else{
									$msg = '<a class="label label-success label-mini" href="'.$url.'forum/inActive_action/'.$forum['id'].'" onclick="return(window.confirm(\'Are you sure you want to Deactive?\'));">Active</a>';  
								}
					?> 
					<tr>
						<td data-title="id"><?php echo $i;?></td>
						<td data-title="title"><?php echo $forum['spName'];?></td>
						<td data-title="catName"><?php echo $forum['question'];?></td>
						<td data-title="postedBy"><?php echo $forum['postedBy'];?></td>
						<td data-title="name"><?php echo $forum['name'];?></td>
						<td data-title="date"><?php echo $forum['createdDate'];?></td>
						<td data-title="date"><?php echo $forum['visible'];?></td>
						<td data-title="Status"><?php echo $msg; ?></td>
						<td data-title="Actions">
							<a class="btn btn-primary btn-xs" href="<?php echo base_url();?>forum/viewForum/<?php echo $forum['slug']?>"><i class="fa fa-eye" aria-hidden="true"></i></a>
							<a class="btn btn-primary btn-xs" href='<?php echo base_url();?>forum/editForum/<?php echo $forum['id'];?>' title='Edit forum'><i class="fa fa-pencil"></i></a>
							<a class="deleterows btn btn-danger btn-xs" id='<?php echo $forum['id'];?>' title='Delete forum'><i class="fa fa-trash-o "></i></a>
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
		{ width: "10%", targets: 1 },
		{ width: "25%", targets: 2 },
		{ width: "10%", targets: 3 },
		{ width: "10%", targets: 4 },
		{ width: "10%", targets: 5 },
		{ width: "10%", targets: 6 },
		{ width: "10%", targets: 7 },
		{ width: "10%", targets: 8 }
	],
	fixedColumns: true
});
$('body').delegate('.deleterows','click',function(e){
		 if (confirm("Do you want to delete?")) {
			e.preventDefault();
			var fid = this.id;
			  $.ajax({
				type:"POST",
				url:"<?php echo base_url();?>forum/deleteForum",
				data:{forumId:fid},
				success:function(result){
					alert("Forum Deleted successfuly !!");
					location.reload(true);
				}
			}) 
		 }
		});
	});			
</script>				