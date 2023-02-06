<div class="row mt">
    <div class="col-lg-12">
        <div class="content-panel">
        	<h4>View Admin</h4>
            
			<div class="col-lg-4"></div>
			<div class="col-lg-4"></div>
			<div class="col-lg-4">
				<a href="<?php echo base_url('admin/add-admin');?>"><button class="btn btn-success btn-lg btn-block">Add Admin</button></a>
	        </div>
			
			<div class="clearfix" style="height:20px;"></div>
			
			<section id="no-more-tables">
				<div id="formError" style="display: none;" class="alert alert-danger alert-dismissible"></div>
				<div id="formSuccess" style="display: none;" class="alert alert-success alert-dismissible"></div>	            
				<table class="table table-striped table-condensed cf" id='bannerTable'>
	            <thead class="cf">
					<tr>
					  	<th>Id</th>
					  	<th>Username</th>
					  	<th>Rights</th>
					  	<th>Change Password</th>
                        <th>Status</th>
						<th><i class=" fa fa-edit"></i>Option</th>
					</tr>
				</thead>

				<tbody>
				<?php 
					if(is_array($admins) && !empty($admins)){
						$i = 1;
						foreach($admins as $val)
						{
						    if($val->isAdmin != '0'){
								if(!empty($val->rights)){
                                    $data = explode(",",$val->rights);
                                    $rights = "";
                                    if(in_array("1",$data)){
                                        $rights .= "Manage Member<br/>";
                                    } 
                                    if(in_array("2",$data)){
                                        $rights .= "Manage Doctor<br/>";
                                    } 
                                    if(in_array("3",$data)){
                                        $rights .= "Manage Speciality<br/>";
                                    } 
                                    if(in_array("4",$data)){
                                        $rights .= "Manage Category<br/>";
                                    } 
                                    if(in_array("5",$data)){
                                        $rights .= "Manage Blog<br/>";
                                    } 
                                    if(in_array("6",$data)){
                                        $rights .= "Manage Video/Audio<br/>";
                                    } 
                                    if(in_array("7",$data)){
                                        $rights .= "Manage Forum<br/>";
                                    } 
                                    /*if(in_array("8",$data)){
                                        $rights .= "Manage Webinar<br/>";
                                    }*/
									if(in_array("9",$data)){
                                        $rights .= "Manage Degree<br/>";
                                    }
									if(in_array("10",$data)){
                                        $rights .= "Manage Location<br/>";
                                    }
									if(in_array("11",$data)){
                                        $rights .= "Manage Banner<br/>";
                                    }
									/*if(in_array("12",$data)){
                                        $rights .= "Manage Home Page<br/>";
                                    }*/
									if(in_array("13",$data)){
                                        $rights .= "Manage Events<br/>";
                                    }
									/*if(in_array("14",$data)){
                                        $rights .= "Manage Contact Us<br/>";
                                    }*/
									if(in_array("15",$data)){
                                        $rights .= "Manage Testimonials<br/>";
                                    }
									if(in_array("16",$data)){
                                        $rights .= "Manage FAQ<br/>";
                                    }
									if(in_array("17",$data)){
                                        $rights .= "Manage Subscribers<br/>";
                                    }
								} else {
                                    $rights = "No Data";
                                }
								
								$id = base64_encode($val->id);
								$id = str_replace(str_repeat('=',strlen($id)/4),"",$id);
								$url = base_url();
								if($val->isActive == 0){
									$msg = '<a class="label label-danger label-mini" href="'.$url.'admin/active-action/'.$id .'" onclick="return(window.confirm(\'Are you sure you want to Active?\'));">Inactive</a>';           
								}else{
									$msg = '<a class="label label-success label-mini" href="'.$url.'admin/inactive-action/'.$id.'" onclick="return(window.confirm(\'Are you sure you want to Deactive?\'));">Active</a>';  
								}	
								
							?> 
							<tr>
								<td data-title="Name"><?php echo $i;?></td>
								<td data-title="Name"><?php echo $val->username;?></td>
								<td data-title="Status"><?php echo $rights; ?></td>
	                            <td data-title="Status"><a href="<?php echo base_url();?>admin/change_password/<?php echo $id;?>">click here</a></td>
								<td data-title="Status"><?php echo $msg; ?></td>						
								<td data-title="Actions">
								<!--<a class="deleterows btn btn-danger btn-xs" id='<?php echo $id;?>' title='Delete'><i class="fa fa-trash-o "></i></a>-->
								<a class="btn btn-primary btn-xs" href='<?php echo base_url();?>admin/edit-admin/<?php echo $id;?>' title='Edit Admin'><i class="fa fa-pencil"></i></a> 
								</td>
							</tr>
						<?php
							$i++;
							} } }
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
				var admin_id = this.id;
				jQuery.ajax({
					type:"POST",
					url:"<?php echo base_url();?>admin/delete_admin",
					data:{
						admin_id:admin_id
					},
					success:function(result){ 
						$("#formSuccess").css("display","block");
						$("#formSuccess").html("Admin Deleted successfuly..").delay(1000).fadeOut(5000);
						location.reload(true);
					}
				}); 
		    }
		});
	});			
</script>				