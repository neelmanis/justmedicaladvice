<div class="row mt">
    <div class="col-lg-12">
        <div class="content-panel">
        	<h4>View Blogs</h4>
			<div class="col-lg-4"></div>
			<div class="col-lg-4"></div>
			<div class="col-lg-4">
				<a href="<?php echo base_url('blog/newBlog');?>"><button class="btn btn-success btn-lg btn-block">Add New blog</button></a>
			</div>
			
			<div class="clearfix" style="height:20px;"></div>
	        <section id="no-more-tables">
				<table class="table table-striped table-condensed cf" id='bannerTable'>
					<thead class="cf">
						<tr>
							<th>Id</th>
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
						if(is_array($blogList) && !empty($blogList)){
							$i = 1;
							foreach($blogList as $blog){
								$url = base_url();
								if($blog['status'] == 0){
									$status = '<a class="label label-danger label-mini" href="'.$url.'blog/active_action/'.$blog['id'].'" onclick="return(window.confirm(\'Are you sure you want to Active?\'));">Inactive</a>';           
								}else{
									$status = '<a class="label label-success label-mini" href="'.$url.'blog/inActive_action/'.$blog['id'].'" onclick="return(window.confirm(\'Are you sure you want to Deactive?\'));">Active</a>';  
								}
								
								if($blog['isHome'] == 0){
									$home = '<a class="label label-danger label-mini" href="'.$url.'blog/setHome/'.$blog['id'].'" onclick="return(window.confirm(\'Are you sure you want to set Blog on Home Page?\'));">No</a>';           
								}else{
									$home = '<a class="label label-success label-mini" href="'.$url.'blog/unsetHome/'.$blog['id'].'" onclick="return(window.confirm(\'Are you sure you want to remove Blog from Home Page?\'));">Yes</a>';  
								}
					?> 
					<tr>
						<td data-title="id"><?php echo $i;?></td>
						<td data-title="title"><?php echo $blog['title'];?></td>
						<td data-title="catName"><?php echo $blog['catName'];?></td>
						<td data-title="postedBy"><?php echo $blog['postedBy'];?></td>
						<td data-title="name"><?php echo $blog['name'];?></td>
						<td data-title="date"><?php echo $blog['createdDate'];?></td>
						<td data-title="Status"><?php echo $blog['visible']; ?></td>
						<td data-title="Status"><?php echo $blog['report']; ?></td>
						<td data-title="Status"><?php echo $status; ?></td>
						<td data-title="Status"><?php echo $home; ?></td>
						<td data-title="Actions">
							<a class="btn btn-primary btn-xs" href="<?php echo base_url();?>blog/viewBlog/<?php echo $blog['slug']?>"><i class="fa fa-eye" aria-hidden="true"></i></a>
							<a class="btn btn-primary btn-xs" href='<?php echo base_url();?>blog/editBlog/<?php echo $blog['id'];?>' title='Edit Category'><i class="fa fa-pencil"></i></a>
							<a class="deleterows btn btn-danger btn-xs" id='<?php echo $blog{'id'};?>' title='Delete Category'><i class="fa fa-trash-o "></i></a>
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
	/*dom: 'Bfrtip',
	buttons: [
		'copyHtml5',
		'excelHtml5',
		'csvHtml5',
		'pdfHtml5'
	],*/
	paging:         true,
	columnDefs: [
		{ width: "5%", targets: 0 },
		{ width: "25%", targets: 1 },
		{ width: "10%", targets: 2 },
		{ width: "5%", targets: 3 },
		{ width: "10%", targets: 4 },
		{ width: "15%", targets: 5 },
		{ width: "5%", targets: 6 },
		{ width: "5%", targets: 7 },
		{ width: "5%", targets: 8 },
		{ width: "5%", targets: 9 },
		{ width: "10%", targets: 10 }
	],
	fixedColumns: true
});

$('body').delegate('.deleterows','click',function(e){
		 if (confirm("Do you want to delete?")) {
			e.preventDefault();
			var bid = this.id;
			  $.ajax({
				type:"POST",
				url:"<?php echo base_url();?>blog/deleteBlog",
				data:{blogId:bid},
				success:function(result){
					alert("Blog Deleted successfuly..");
					location.reload(true);
				}
			}) 
		 }
		});
	});			
</script>				