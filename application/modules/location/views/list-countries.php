<div class="row mt">
    <div class="col-lg-12">
        <div class="content-panel">
        	<h4>View Counties</h4>
			
			<div class="clearfix"></div>
			
	        <section id="no-more-tables">	
				<table class="table table-striped table-condensed cf" id='bannerTable'>
					<thead class="cf">
						<tr>
							<th>Id</th>
							<th>Country Name</th>
							<th>Phonecode</th>
							<th>isActive</th>
						</tr>
					</thead>
					<tbody>
					<?php 
						if(is_array($countries) && !empty($countries)){
							foreach($countries as $con){
								$id = base64_encode($con->id);
								$id = str_replace(str_repeat('=',strlen($id)/4),"",$id);
								$url = base_url();
								if($con->isActive == 0){
									$msg = '<a class="label label-danger label-mini" href="'.$url.'location/countryActive/'.$id .'" onclick="return(window.confirm(\'Are you sure you want to Active?\'));">Inactive</a>';           
								}else{
									$msg = '<a class="label label-success label-mini" href="'.$url.'location/countryInactive/'.$id.'" onclick="return(window.confirm(\'Are you sure you want to Deactive?\'));">Active</a>';  
								}	 	
					?> 
						<tr>
							<td data-title="id"><?php echo $con->id;?></td>
							<td data-title="Name"><?php echo $con->countryName;?></td>
							<td data-title="Name"><?php echo $con->phonecode;?></td>
							<td data-title="Status"><?php echo $msg; ?></td>
						</tr>
					<?php	} }  ?>
					</tbody>
				</table>        	
		</div>
    </div>
</div>
<script>				
$(document).ready(function(){
$('#bannerTable').DataTable();
})		
</script>