<div class="row mt">
    <div class="col-lg-12">
        <div class="content-panel">
        	<h4>View Testimonials</h4>
			<div class="col-lg-4"></div>
			<div class="col-lg-4"></div>
			<div class="col-lg-4">
				<a href="<?php echo base_url('testimonial/addNew');?>"><button class="btn btn-success btn-lg btn-block">Add New blog</button></a>
			</div>
			
			<div class="clearfix" style="height:20px;"></div>
			
			<section id="no-more-tables">
				<table class="table table-striped table-condensed cf" id='bannerTable'>
					<thead class="cf">
						<tr>
							<th>Sr. No.</th>
							<th>Name</th>
							<th>Decsription</th>
							<th>isActive</th>
							<th><i class=" fa fa-edit"></i>Option</th>
						</tr>
					</thead>

					<tbody>
						<?php 
							if(is_array($getAll) && !empty($getAll)){
								$i=1;
								foreach($getAll as $val){
									if($val->isActive == 1){$status="Active";}else{$status="Deactive";}
						?> 
						<tr>
							<td data-title="Name"><?php echo $i++;?></td>
							<td data-title="Name"><?php echo $val->testName;?></td>
							<td data-title="Order"><?php echo $val->testDesc;?></td>
							<td data-title="Status"><?php echo $status; ?></td>
							<td data-title="Actions">
								<a class="deleterows btn btn-danger btn-xs" id='<?php echo $val->testId;?>' title='Delete'><i class="fa fa-trash-o "></i></a>
								<a class="btn btn-primary btn-xs" href='<?php echo base_url();?>testimonial/edit/<?php echo $val->testId;?>' title='Edit'><i class="fa fa-pencil"></i></a>
							</td>
						</tr>
						<?php
							} }
						?>
					</tbody>
				</table>        	
        </div>
    </div>
</div>

<script>				
	jQuery(document).ready(function(){
		$('#bannerTable').DataTable({
			paging:         true,
			columnDefs: [
				{ width: "10%", targets: 0 },
				{ width: "20%", targets: 1 },
				{ width: "50%", targets: 2 },
				{ width: "10%", targets: 3 },
				{ width: "10%", targets: 4 }
			],
			fixedColumns: true
		});
		$('body').delegate('.deleterows','click',function(e){
		      if (confirm("Do you want to delete?")) {
			e.preventDefault();
			var other_id = this.id;
			  jQuery.ajax({
				type:"POST",
				url:"<?php echo base_url();?>testimonial/delete",
				data:{other_id:other_id},
				success:function(result){
					    alert("Testimonials Delete successfuly..");
					    location.reload(true);
				}
			}) 
		      }
		});
	});			
</script>				