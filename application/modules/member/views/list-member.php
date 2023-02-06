<div class="row mt">
    <div class="col-lg-12">
        <div class="content-panel">
        	<h4>View Member</h4>
			
			<div class="clearfix" style="height:20px;"></div>
			
	        <section id="no-more-tables">	
				<table class="table table-striped table-condensed cf" id='bannerTable'>
					<thead class="cf">
						<tr>
							<th>Id</th>
							<th>Image</th>
							<th>Name</th>
							<th>Mobile</th>
							<th>Email</th>
							<th>Age</th>
							<th>Status</th>
							<th><i class=" fa fa-edit"></i> View</th>
						</tr>
					</thead>
					<tbody>
					<?php 
						if(is_array($members) && !empty($members)){
							$i = 1;
							foreach($members as $val){
								$id = base64_encode($val['regId']);
								$id = str_replace(str_repeat('=',strlen($id)/4),"",$id);
								$url = base_url();
								if($val['isActive'] == 0){
									$msg = '<a class="label label-danger label-mini" href="'.$url.'member/activeAction/'.$id .'" onclick="return(window.confirm(\'Are you sure you want to Active?\'));">Inactive</a>';           
								}else{
									$msg = '<a class="label label-success label-mini" href="'.$url.'member/inActiveAction/'.$id.'" onclick="return(window.confirm(\'Are you sure you want to Deactive?\'));">Active</a>';  
								}	 	
					?> 
						<tr>
							<td data-title="id"><?php echo $i;?></td>
							<td data-title="Name"><img src="<?php echo base_url().$val['image'];?>" height="35" width="35"/></td>
							<td data-title="Name"><?php echo $val['name'];?></td>
							<td data-title="Name"><?php echo $val['mobile'];?></td>
							<td data-title="Name"><?php echo $val['email'];?></td>
							<td data-title="Name"><?php echo $val['age'];?></td>
							<td data-title="Status"><?php echo $msg; ?></td>
							<td data-title="Actions">
								<a class="btn btn-primary btn-sm" href="<?php echo base_url();?>member/viewDetails/<?php echo $id; ?>"><i class="fa fa-eye" aria-hidden="true"></i></a>
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
$('#bannerTable').DataTable();
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