<div class="row mt">
    <div class="col-lg-12">
        <div class="content-panel">
        	<h4>View All Subscription</h4>
	        <section id="no-more-tables">
	            <table class="table table-striped table-condensed cf" id='bannerTable'>
	            <thead class="cf">
					<tr>
					  	<th>Email ID</th>
                        <th>Status</th>
						<th><i class=" fa fa-edit"></i> Option</th>
					</tr>
				</thead>
				<tbody>
				<?php 
					if(is_array($sublist) && !empty($sublist))
					 {
					  foreach($sublist as $val)
					   {
						   if($val->substatus == 1){$status="Active";}else{$status="Deactive";}
				 ?> 
					<tr>
						<td data-title="Order"><?php echo $val->emailId; ?></td>
                        <td data-title="Status"><?php echo $status; ?></td>
						<td data-title="Actions">
                          <a class="deleterows btn btn-danger btn-xs" id='<?php echo $val->id;?>' title='Delete Subscription'><i class="fa fa-trash-o"></i></a>
						</td>
					</tr>
			<?php
					}  }
			?>
				</tbody>
	        </table>        	
        </div>
    </div>
</div>

<script>				
	jQuery(document).ready(function(){
		$('#bannerTable').DataTable();
		$('body').delegate('.deleterows','click',function(e){
		     if (confirm("Do you want to delete?")) {
			e.preventDefault();
			var subscriptionid = this.id; 
			  jQuery.ajax({
				type:"POST",
				url:"<?php echo base_url();?>subscription/deleteSubscription",
				data:{subscriptionid:subscriptionid},
				success:function(result){
					alert("Subscription  is deleted successfuly..");
					location.reload(true);
				}
			}) 
		     }
		});
	});			
</script>				