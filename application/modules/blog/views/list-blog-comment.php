<div class="row mt">
    <div class="col-lg-12">
        <div class="content-panel">
        	<h4>View Flag Comments</h4>
	        <section id="no-more-tables">
				
				<table class="table table-striped table-condensed cf" id='bannerTable'>
					<thead class="cf">
						<tr>
							<th>Id</th>
							<th>Comment</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
					<?php 
						if(!empty($comments) && $comments !== 'No Data'){
							$i = 1;
							foreach($comments as $com){
								$url = base_url();
								if($com['isActive'] == 0){
									$msg = '<a class="label label-danger label-mini" href="'.$url.'blog/activeComment/'.$com['commentId'].'" onclick="return(window.confirm(\'Are you sure you want to Active?\'));">Inactive</a>';           
								}else{
									$msg = '<a class="label label-success label-mini" href="'.$url.'blog/inActiveComment/'.$com['commentId'].'" onclick="return(window.confirm(\'Are you sure you want to Deactive?\'));">Active</a>';  
								}
					?> 
					<tr>
						<td data-title="id"><?php echo $i;?></td>
						<td data-title="title"><?php echo $com['comment'];?></td>
						<td data-title="Status"><?php echo $msg; ?></td>
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
});	
</script>	