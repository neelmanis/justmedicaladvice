<div class="row mt">
    <div class="col-lg-12">
        <div class="content-panel">
        	<h4>View FAQ</h4>
			<div class="col-lg-4"></div>
			<div class="col-lg-4"></div>
			<div class="col-lg-4">
				<a href="<?php echo base_url('faq/add-faq');?>"><button class="btn btn-success btn-lg btn-block">Add FAQ</button></a>
			</div>
			
			<div class="clearfix" style="height:20px;"></div>
			
	        <section id="no-more-tables">	
				<table class="table table-striped table-condensed cf" id='bannerTable'>
					<thead class="cf">
						<tr>
							<th>Id</th>
							<th>Question</th>
							<th>Answer</th>
							<th>Status</th>
							<th>Created Date</th>
							<th><i class=" fa fa-edit"></i> Option</th>
						</tr>
					</thead>
					<tbody>
					<?php 
						if($faq !== 'No Data'){
							$i = 1;
							foreach($faq as $val){
								
								$id = base64_encode($val['faqId']);
								$id = str_replace(str_repeat('=',strlen($id)/4),"",$id);
								$url = base_url();
								if($val['isActive'] == 0){
									$msg = '<a class="label label-danger label-mini" href="'.$url.'faq/activeAction/'.$id .'" onclick="return(window.confirm(\'Are you sure you want to Active?\'));">Inactive</a>';           
								}else{
									$msg = '<a class="label label-success label-mini" href="'.$url.'faq/inActiveAction/'.$id.'" onclick="return(window.confirm(\'Are you sure you want to Deactive?\'));">Active</a>';  
								}	 	
					?> 
						<tr>
							<td data-title="id"><?php echo $i;?></td>
							<td data-title="Name"><?php echo $val['question'];?></td>
							<td data-title="Name"><?php echo $val['answer'];?></td>
							<td data-title="Status"><?php echo $msg; ?></td>
							<td data-title="Status"><?php echo $val['createdDate']; ?></td>
							<td data-title="Actions">
								<a class="btn btn-primary btn-xs" href='<?php echo base_url();?>faq/edit-faq/<?php echo $id;?>' title='Edit Faq'><i class="fa fa-pencil"></i></a>
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
});			
</script>				