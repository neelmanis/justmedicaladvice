<div class="row mt">
    <div class="col-lg-12">
        <div class="content-panel">
        	<h4>View States</h4>
			
			<div class="clearfix"></div>
			
	        <section id="no-more-tables">	
				<table class="table table-striped table-condensed cf" id='bannerTable'>
					<thead class="cf">
						<tr>
							<th>Id</th>
							<th>State Name</th>
							<th>isActive</th>
						</tr>
					</thead>
					<tbody>
					<?php 
						if(is_array($states) && !empty($states)){
							foreach($states as $state){
								$id = base64_encode($state->id);
								$id = str_replace(str_repeat('=',strlen($id)/4),"",$id);
								$url = base_url();
								if($state->isActive == 0){
									$msg = '<a class="label label-danger label-mini" href="'.$url.'location/stateActive/'.$id .'" onclick="return(window.confirm(\'Are you sure you want to Active?\'));">Inactive</a>';           
								}else{
									$msg = '<a class="label label-success label-mini" href="'.$url.'location/stateInactive/'.$id.'" onclick="return(window.confirm(\'Are you sure you want to Deactive?\'));">Active</a>';  
								}	 	
					?> 
						<tr>
							<td data-title="id"><?php echo $state->id;?></td>
							<td data-title="Name"><?php echo $state->stateName;?></td>
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