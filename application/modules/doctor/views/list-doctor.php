<div class="row mt">
    <div class="col-lg-12">
        <div class="content-panel">
        	<h4>View Doctor</h4>
			<div class="col-lg-4"></div>
			<div class="col-lg-4"></div>
			<div class="col-lg-4">
				<a href="<?php echo base_url('doctor/add-doctor');?>"><button class="btn btn-success btn-lg btn-block">Add Doctor</button></a>
	        </div>
			
			<div class="clearfix" style="height:20px;"></div>
			
	        <section id="no-more-tables">	
				<div id="formError" style="display: none;" class="alert alert-danger alert-dismissible"></div>
				<div id="formSuccess" style="display: none;" class="alert alert-success alert-dismissible"></div>	
				<table class="table table-striped table-condensed cf" id='bannerTable'>
					<thead class="cf">
						<tr>
							<th>Id</th>
							<th>Image</th>
							<th>Name</th>
							<th>Mobile</th>
							<th>email</th>
							<th>Degree</th>
							<th>Speciality</th>
							<th>Status</th>
							<th>Featured</th>
							<th>Home</th>
							<th><i class=" fa fa-edit"></i> View</th>
						</tr>
					</thead>
					<tbody>
					<?php 
						if(is_array($doctors) && !empty($doctors)){
							$i = 1;
							foreach($doctors as $val){
								$id = base64_encode($val['regId']);
								$id = str_replace(str_repeat('=',strlen($id)/4),"",$id);
								$url = base_url();
								if($val['isActive'] == 0){
									$msg1 = '<a class="label label-danger label-mini" href="'.$url.'doctor/active_action/'.$id .'" onclick="return(window.confirm(\'Are you sure you want to Active?\'));">Inactive</a>';           
								}else{
									$msg1 = '<a class="label label-success label-mini" href="'.$url.'doctor/inActive_action/'.$id.'" onclick="return(window.confirm(\'Are you sure you want to Deactive?\'));">Active</a>';  
								}
								
								if($val['isFeatured'] == 0){
									$msg2 = '<a class="label label-danger label-mini" href="'.$url.'doctor/makeFeatured/'.$id .'" onclick="return(window.confirm(\'Are you sure you want to make featured ?\'));">No</a>';           
								}else{
									$msg2 = '<a class="label label-success label-mini" href="'.$url.'doctor/removeFeatured/'.$id.'" onclick="return(window.confirm(\'Are you sure you want to remove as featured ?\'));">Yes</a>';  
								}
								
								if($val['isHome'] == 0){
									$msg3 = '<a class="label label-danger label-mini" href="'.$url.'doctor/setHome/'.$id .'" onclick="return(window.confirm(\'Are yoo want to show on home ?\'));">No</a>';           
								}else{
									$msg3 = '<a class="label label-success label-mini" href="'.$url.'doctor/removeHome/'.$id.'" onclick="return(window.confirm(\'Are you sure you want to remove from home ?\'));">Yes</a>';  
								}
					?> 
						<tr>
							<td data-title="id"><?php echo $i;?></td>
							<td data-title="Name"><img src="<?php echo base_url().$val['image'];?>" height="35" width="35"/></td>
							<td data-title="Name"><?php echo $val['name'];?></td>
							<td data-title="Name"><?php echo $val['mobile'];?></td>
							<td data-title="Name"><?php echo $val['email'];?></td>
							<td data-title="Name"><?php echo $val['degree'];?></td>
							<td data-title="Name"><?php echo $val['speciality'];?></td>
							<td data-title="Status"><?php echo $msg1; ?></td>
							<td data-title="Status"><?php echo $msg2; ?></td>
							<td data-title="Status"><?php echo $msg3; ?></td>
							<td data-title="Actions">
								<a class="btn btn-primary btn-sm" href="<?php echo base_url();?>doctor/viewDetails/<?php echo $id; ?>"><i class="fa fa-eye" aria-hidden="true"></i></a>
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
$('#bannerTable').DataTable(
{
	/*dom: 'Bfrtip',
	buttons: [
		'copyHtml5',
		'excelHtml5',
		'csvHtml5',
		'pdfHtml5'
	],*/
	paging: true,
	columnDefs: [
		{ width: "5%", targets: 0 },
		{ width: "10%", targets: 1 },
		{ width: "10%", targets: 2 },
		{ width: "10%", targets: 3 },
		{ width: "10%", targets: 4 },
		{ width: "15%", targets: 5 },
		{ width: "15%", targets: 6 },
		{ width: "5%", targets: 7 },
		{ width: "5%", targets: 8 },
		{ width: "5%", targets: 9 },
		{ width: "5%", targets: 10 },
	],
	fixedColumns: true
});
$('body').delegate('.deleterows','click',function(e){
		 if (confirm("Do you want to delete?")) {
			e.preventDefault();
			var id = this.id;
			  $.ajax({
				type:"POST",
				url:"<?php echo base_url();?>degree/deleteDegree",
				data:{degreeId:id},
				success:function(result){
					alert("Degree Delete successfuly !!");
					location.reload(true);
				}
			}); 
		}
	});
});			
</script>				