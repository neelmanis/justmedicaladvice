<div class="row mt">
    <div class="col-lg-12">
        <div class="content-panel">
        	<h4>View Cities</h4>
			<div class="col-lg-12">
				<div class="col-lg-6">
					<label class="col-lg-4 control-label" style="line-height: 37px;">Select Country</label>
					<div class="col-lg-8">
						<select  name="city" id="city" class="form-control"  onchange="location = this.value;">
							<?php if(is_array($main)){
									foreach($main as $con){ 
							?>
								<option value="<?php echo base_url("location/city-list/$con->id"); ?>" <?php if($conId==$con->id){ echo "selected";}?>><?php  echo $con->countryName; ?></option>		
							<?php	}  } ?>
						</select>
					</div>
				</div>
				<div class="col-lg-2"></div>
				<div class="col-lg-4"></div>
			</div>
			<div class="clearfix"></div>
			
	        <section id="no-more-tables">	
				<table class="table table-striped table-condensed cf" id='bannerTable'>
					<thead class="cf">
						<tr>
							<th>Id</th>
							<th>City Name</th>
							<th>isActive</th>
						</tr>
					</thead>
					<tbody>
					<?php 
						if(is_array($cities) && !empty($cities)){
							
							foreach($cities as $city){
								$id = base64_encode($city->id);
								$id = str_replace(str_repeat('=',strlen($id)/4),"",$id);
								$id = str_replace(str_repeat('=',strlen($id)/7),"",$id);
								$url = base_url();
								if($city->isActive == 0){
									$msg = '<a class="label label-danger label-mini" href="'.$url.'location/cityActive/'.$id .'/'.$conId.'" onclick="return(window.confirm(\'Are you sure you want to Active?\'));">Inactive</a>';           
								}else{
									$msg = '<a class="label label-success label-mini" href="'.$url.'location/cityInactive/'.$id.'/'.$conId.'" onclick="return(window.confirm(\'Are you sure you want to Deactive?\'));">Active</a>';  
								}	 	
					?> 
						<tr>
							<td data-title="id"><?php echo $city->id;?></td>
							<!--<td data-title="id"><?php echo $id;?></td>-->
							<td data-title="Name"><?php echo $city->cityName;?></td>
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