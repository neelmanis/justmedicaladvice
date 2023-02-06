<div class="row mt">
    <div class="col-lg-12">
        <div class="content-panel">
        	<h4>View Speciality</h4>
			<div class="col-lg-4"></div>
			<div class="col-lg-4"></div>
			<div class="col-lg-4">
				<a href="<?php echo base_url('speciality/addSpeciality');?>"><button class="btn btn-success btn-lg btn-block">Add Speciality</button></a>
			</div>
			
			<div class="clearfix"></div>
			
	        <section id="no-more-tables">	
				<div id="formError" style="display: none;" class="alert alert-danger alert-dismissible"></div>
				<div id="formSuccess" style="display: none;" class="alert alert-success alert-dismissible"></div>	
				<table class="table table-striped table-condensed cf" id='bannerTable'>
					<thead class="cf">
						<tr>
							<th>Id</th>
							<th>Speciality Name</th>
							<th>Added By</th>
							<th>isActive</th>
							<th><i class=" fa fa-edit"></i> Option</th>
						</tr>
					</thead>
					<tbody>
					<?php 
						if(is_array($specialities) && !empty($specialities)){
							$i = 1;
							foreach($specialities as $sp){
								//if($sp->isActive == 1){$status="Active";}else{$status="Deactive";}
								$id = base64_encode($sp['spId']);
								$id = str_replace(str_repeat('=',strlen($id)/4),"",$id);
								$url = base_url();
								if($sp['isActive'] == 0){
									$msg = '<a class="label label-danger label-mini" href="'.$url.'speciality/active_action/'.$id .'" onclick="return(window.confirm(\'Are you sure you want to Active?\'));">Inactive</a>';           
								}else{
									$msg = '<a class="label label-success label-mini" href="'.$url.'speciality/inActive_action/'.$id.'" onclick="return(window.confirm(\'Are you sure you want to Deactive?\'));">Active</a>';  
								}	 	
					?> 
						<tr>
							<td data-title="id"><?php echo $i;?></td>
							<td data-title="Name"><?php echo $sp['spName'];?></td>
							<td data-title="Name"><?php echo $sp['adminName'];?></td>
							<td data-title="Status"><?php echo $msg; ?></td>
							<td data-title="Actions">
								<a class="deleterows btn btn-danger btn-xs" id='<?php echo $id;?>' title='Delete Category'><i class="fa fa-trash-o "></i></a>
								<a class="btn btn-primary btn-xs" href='<?php echo base_url();?>speciality/editSpeciality/<?php echo $id;?>' title='Edit Category'><i class="fa fa-pencil"></i></a>
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
			var speciality_id = this.id;
			  $.ajax({
				type:"POST",
				url:"<?php echo base_url();?>speciality/deleteSpeciality",
				data:{spId:speciality_id},
				success:function(result){
					alert("Speciality Delete successfuly..");
					location.reload(true);
				}
			}) 
		 }
		});
	});			
</script>				