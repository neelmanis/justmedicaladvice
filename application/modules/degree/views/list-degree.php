<div class="row mt">
    <div class="col-lg-12">
        <div class="content-panel">
        	<h4>View Degree</h4>
			<div class="col-lg-4"></div>
			<div class="col-lg-4"></div>
			<div class="col-lg-4">
				<a href="<?php echo base_url('degree/add-degree');?>"><button class="btn btn-success btn-lg btn-block">Add Degree</button></a>
			</div>
			
			<div class="clearfix" style="height:20px;"></div>
			
	        <section id="no-more-tables">	
				<div id="formError" style="display: none;" class="alert alert-danger alert-dismissible"></div>
				<div id="formSuccess" style="display: none;" class="alert alert-success alert-dismissible"></div>	
				<table class="table table-striped table-condensed cf" id='bannerTable'>
					<thead class="cf">
						<tr>
							<th>Id</th>
							<th>Degree Name</th>
							<th>Added By</th>
							<th>isActive</th>
							<th><i class=" fa fa-edit"></i> Option</th>
						</tr>
					</thead>
					<tbody>
					<?php 
						if(is_array($degree) && !empty($degree)){
							$i = 1;
							foreach($degree as $val){
								$id = base64_encode($val['degreeId']);
								$id = str_replace(str_repeat('=',strlen($id)/4),"",$id);
								$url = base_url();
								if($val['isActive'] == 0){
									$msg = '<a class="label label-danger label-mini" href="'.$url.'degree/activeAction/'.$id .'" onclick="return(window.confirm(\'Are you sure you want to Active?\'));">Inactive</a>';           
								}else{
									$msg = '<a class="label label-success label-mini" href="'.$url.'degree/inActiveAction/'.$id.'" onclick="return(window.confirm(\'Are you sure you want to Deactive?\'));">Active</a>';  
								}	 	
					?> 
						<tr>
							<td data-title="id"><?php echo $i;?></td>
							<td data-title="Name"><?php echo $val['name'];?></td>
							<td data-title="Name"><?php echo $val['adminName'];?></td>
							<td data-title="Status"><?php echo $msg; ?></td>
							<td data-title="Actions">
								<!--<a class="deleterows btn btn-danger btn-xs" id='<?php echo $id;?>' title='Delete Degree'><i class="fa fa-trash-o "></i></a>-->
								<a class="btn btn-primary btn-xs" href='<?php echo base_url();?>degree/edit-degree/<?php echo $id;?>' title='Edit Degree'><i class="fa fa-pencil"></i></a>
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