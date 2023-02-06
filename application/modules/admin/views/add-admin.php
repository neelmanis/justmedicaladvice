	<div class="form-panel">
		<h4 class="mb">Add Admin</h4>
		<form id="addAdminForm" class="form-horizontal style-form adminuserform" >
			<div id="formError" style="display: none;" class="alert alert-danger alert-dismissible"></div>
			<div id="formSuccess" style="display: none;" class="alert alert-success alert-dismissible"></div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="username">User Name <span style="color:red;">*</span></label>
				<div class="col-sm-9">
					<input type="text" name="username" id="username" class="form-control" />
				</div>
			</div>	      
			<div class="form-group">
				<label class="col-sm-3 control-label" for="username">Password <span style="color:red;">*</span></label>
				<div class="col-sm-9">
					<input type="password" name="password" id="password" class="form-control" />
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label" for="username">Module Rights <span style="color:red;">*</span></label>
				<div class="col-sm-9">
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" class="form-control" value="1" />Manage Member
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" class="form-control" value="2" />Manage Doctor 
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" class="form-control" value="3" />Manage Speciality 
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" class="form-control"  value="4" />Manage Category
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" class="form-control"  value="5" />Manage Blog
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" class="form-control"  value="6" />Manage Video/Audio
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" class="form-control"  value="7" />Manage Forum
					</div>
					<div class="checkdisplay hide">
						<input type="checkbox" name="module[]" class="form-control"  value="8" />Manage Webinar
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" class="form-control"  value="9" />Manage Degree
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" class="form-control"  value="10" />Manage Locations
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" class="form-control"  value="11" />Manage Banner
					</div>
					<div class="checkdisplay hide">
						<input type="checkbox" name="module[]" class="form-control"  value="12" />Manage Home Page
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" class="form-control"  value="13" />Manage Events
					</div>
					<div class="checkdisplay hide">
						<input type="checkbox" name="module[]" class="form-control"  value="14" />Manage Contact Us
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" class="form-control"  value="15" />Manage Testimonials
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" class="form-control"  value="16" />Manage FAQ
					</div>
					<div class="checkdisplay">
						<input type="checkbox" name="module[]" class="form-control"  value="17" />Manage Subscribers
					</div>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label">&nbsp;</label>
				<div class="col-sm-6">
					<input type="submit" class="btn btn-primary btn-lg btn-block" id="banner_btn" value='Save'>
				</div>
			</div>
		</form>
	</div>			

	<script>
	$(document).ready(function(){	
		
		$("#addAdminForm").on("submit",function(e){
			e.preventDefault();
			if(window.FormData != 'undefined')
			{
				var formdata = new FormData(this);
				$.ajax({
					type:"POST",
					url:baseUrl+"admin/addAdminAction",	
					data:formdata,
					processData : false,
					contentType : false,
					mimeType : 'multipart/form-data',
					beforeSend:function() {    
						$("#pageLoader").show();
					},
					success:function(result){ 
						$("#pageLoader").hide();
						if(result == 1){
							window.scrollTo(0,0);
							$("#formSuccess").css("display","block");
							$("#formSuccess").html("Admin User added successfully!!!").delay(5000).fadeOut(5000);
							$('#addAdminForm')[0].reset();
							window.location.href = baseUrl+"admin/listAdmin";
						}else{
							window.scrollTo(0,0);
							$("#formError").css("display","block");
							$("#formError").html(result).delay(5000).fadeOut(5000);
						}
					}
				});
			}
		});
	})
	</script>  