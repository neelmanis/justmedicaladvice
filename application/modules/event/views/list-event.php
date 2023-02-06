<div class="row mt">
    <div class="col-lg-12">
        <div class="content-panel">
        	<h4>View Events</h4>
			<div class="col-lg-4"></div>
			<div class="col-lg-4"></div>
			<div class="col-lg-4">
				<a href="<?php echo base_url('event/add-event');?>"><button class="btn btn-success btn-lg btn-block">Add Event</button></a>
			</div>
			
			<div class="clearfix" style="height:20px;"></div>
	        <section id="no-more-tables">
				<table class="table table-striped table-condensed cf" id='bannerTable'>
					<thead class="cf">
						<tr>
							<th>Id</th>
							<th>Title</th>
							<th>Venue</th>
							<th>Date</th>
							<th>Time</th>
							<th>Added By</th>
							<th>Created Date</th>
							<th>Status</th>
							<th>Options</th>
						</tr>
					</thead>
					<tbody>
					<?php 
						if(!empty($eventList) && $eventList != 'No Data'){
							$i = 1;
							foreach($eventList as $event){
								$url = base_url();
								if($event['status'] == 0){
									$msg = '<a class="label label-danger label-mini" href="'.$url.'event/activeAction/'.$event['id'].'" onclick="return(window.confirm(\'Are you sure you want to Active?\'));">Inactive</a>';           
								}else{
									$msg = '<a class="label label-success label-mini" href="'.$url.'event/inActiveAction/'.$event['id'].'" onclick="return(window.confirm(\'Are you sure you want to Deactive?\'));">Active</a>';  
								}
					?> 
					<tr>
						<td data-title="id"><?php echo $i;?></td>
						<td data-title="title"><?php echo $event['title'];?></td>
						<td data-title="title"><?php echo $event['venue'];?></td>
						<td data-title="title"><?php echo $event['date'];?></td>
						<td data-title="title"><?php echo $event['time'];?></td>
						<td data-title="addedBy"><?php echo $event['addedBy'];?></td>
						<td data-title="date"><?php echo $event['createdDate'];?></td>
						<td data-title="Status"><?php echo $msg; ?></td>
						<td data-title="Actions">
							<!--<a class="btn btn-primary btn-xs" href="<?php echo base_url();?>event/view-event/<?php echo $event['id']?>"><i class="fa fa-eye" aria-hidden="true"></i></a>-->
							<a class="btn btn-primary btn-xs" href='<?php echo base_url();?>event/edit-event/<?php echo $event['id'];?>'><i class="fa fa-pencil"></i></a>
							<a class="deleterows btn btn-danger btn-xs" id='<?php echo $event{'id'};?>' ><i class="fa fa-trash-o "></i></a>
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
			var eid = this.id;
			  $.ajax({
				type:"POST",
				url:"<?php echo base_url();?>event/deleteEvent",
				data:{eventId:eid},
				success:function(result){
					swal("Event Deleted successfuly..");
					location.reload(true);
				}
			}) 
		 }
		});
	});			
</script>				