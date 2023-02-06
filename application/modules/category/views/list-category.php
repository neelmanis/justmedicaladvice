<div class="row mt">
    <div class="col-lg-12">
        <div class="content-panel">
        	<h4>View Category</h4>
			<div class="col-lg-12">
				<div class="col-lg-6">
					<label class="col-lg-4 control-label" style="line-height: 37px;">Category Filter</label>
					<div class="col-lg-8">
						<select  name="category" id="category" class="form-control"  onchange="location = this.value;">
							<?php if(is_array($main)){
									foreach($main as $cat){ 
							?>
								<option value="<?php echo base_url("category/listByFilter/$cat->catId"); ?>" <?php if($catid==$cat->catId){ echo "selected";}?>><?php  echo $cat->catName; ?></option>		
							<?php	}  } ?>
						</select>
					</div>
				</div>
				<div class="col-lg-2"></div>
				<div class="col-lg-4">
					<a href="<?php echo base_url('category/add-category');?>"><button class="btn btn-success btn-lg btn-block">Add Category</button></a>
				</div>
			</div>			
			<div class="clearfix" style="height:20px;"></div>
	    
			<section id="no-more-tables">
				
				
				<table class="table table-striped table-condensed cf" id='bannerTable'>
					<thead class="cf">
						<tr>
							<th>Id</th>
							<th>Parent Category</th>
							<th>Category Name</th>
							<th>Speciality</th>
							<th>AddedBy</th>
							<th>isActive</th>
							<th><i class=" fa fa-edit"></i> Option</th>
						</tr>
					</thead>
					<tbody>
					<?php 
						if(is_array($getAllCategories) && !empty($getAllCategories)){
							$i = 1;
							foreach($getAllCategories as $cat){
								$id = base64_encode($cat->catId);
								$id = str_replace(str_repeat('=',strlen($id)/4),"",$id);
								$url = base_url();
								if($cat->isActive == 0){
									$msg = '<a class="label label-danger label-mini" href="'.$url.'category/activeAction/'.$id .'" onclick="return(window.confirm(\'Are you sure you want to Active?\'));">Inactive</a>';           
								}else{
									$msg = '<a class="label label-success label-mini" href="'.$url.'category/inActiveAction/'.$id.'" onclick="return(window.confirm(\'Are you sure you want to Deactive?\'));">Active</a>';  
								}
								
								$speciality = explode(",",$cat->specialities);
								$list = '';
								foreach($speciality as $val){
									$list .= Modules::run('speciality/getName',$val) . '<br>';
								}
								$parent = Modules::run('category/getCategoryName',$cat->parentCat);
					?> 
						<tr>
							<td data-title="Id"><?php echo $i;?></td>
							<td data-title="parent"><?php echo $parent;?></td>
							<td data-title="Name"><?php echo $cat->catName;?></td>
							<td data-title="speciality"><?php echo $list; ?></td>
							<td data-title="admin"><?php echo Modules::run('admin/getAdminUsername',$cat->adminId);?></td>
							<td data-title="Status"><?php echo $msg; ?></td>
							<td data-title="Actions">
								<!--<a class="deleterows btn btn-danger btn-xs" id='<?php echo $id;?>' title='Delete Category'><i class="fa fa-trash-o "></i></a>-->
								<?php if($parent !== 'Main'){?>
								<a class="btn btn-primary btn-xs" href='<?php echo base_url();?>category/edit-category/<?php echo $id;?>' title='Edit Category'><i class="fa fa-pencil"></i></a>
								<?php } ?>
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
			var category_id = this.id;
				$.ajax({
				type:"POST",
				url:"<?php echo base_url();?>category/deleteCategory",
				data:{Category_id:category_id},
				success:function(result){
					alert("Category Delete successfuly..");
					location.reload(true);
				}
			});
		}
	});
});			
</script>				