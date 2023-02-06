<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Admin extends MX_Controller{
	
	function __construct() {
		parent::__construct();
		$this->load->model('Mdl_admin');
	}

	/***************************************** Admin Login Page ***************************************/
	function login(){	
		//$status = Modules::run('security/isAdmin');
		//if($status){
		//	redirect('dashboard/admin','refresh');	
		//} else {
			$this->load->view('login');
		//}
	}
	
	/***************************************** Admin Login Submit ***************************************/
	function submit(){
		if($this->input->post()){
			
			$this->form_validation->set_rules('username', 'Username', 'trim|required|max_length[30]|xss_clean');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
			
			if ($this->form_validation->run($this) == FALSE){
				$this->index();
			}else{
				$username = $this->input->post('username');
				$pass = $this->password_check($this->input->post('password'));
				
				if($pass == 2){ 
					$this->_authenticate($username);
				}else{ 
					echo 'Username and password don\'t match or Account is not active';
				}	
			}
		}else{
			show_404();
		}
	}
	
	/***************************************** Admin Authentication ***************************************/
	function password_check($password){
		$username = $this->input->post('username');
		$password = Modules::run('Site_security/makeHash', $password);
		$isAdmin = $this->Mdl_admin->checkLogin($username, $password);
			
		if(!$isAdmin){
			return 1;
		}
		else{
			return 2;
		}
	}
	
	/***************************************** Admin Set Session ***************************************/
	function _authenticate($username){
		$query = $this->get_where_custom('username', $username);
		
		foreach($query->result() as $row) {
			$userId = $row->id;
			$username = $row->username;
			$name = $row->name;
			$image = $row->imageUrl;
			$rights = $row->rights;
			$isAdmin = $row->isAdmin;	
		}
		
		$sessionData = array(
							'userId'=>$userId,
							'type'=> 'admin',
							'username'=>$username,
							'name'=>$name,
							'imageUrl'=>$image,
							'rights'=>$rights,
							'isAdmin'=>$isAdmin
					);

		$this->session->set_userdata('type', 'admin');
		$this->session->set_userdata('userId', $userId);
		$this->session->set_userdata('adminData', $sessionData);
		redirect('dashboard/admin');
	}

	/***************************************** Admin Logout ***************************************/
	function logout(){
		$sessionData = array(
							'userId'=>'',
							'type'=>''
					);
		$this->session->unset_userdata('adminData', $sessionData);
		$this->session->sess_destroy();
     	redirect('admin/login','refresh');
	}
	
	function get_where_custom($col, $value) {
		$query = $this->Mdl_admin->get_where_custom($col, $value);
		return $query;
	}
	
	/***************************************** Admin Listing  ***************************************/
	function listAdmin(){
		$getAll = $this->Mdl_admin->listAdmin();
		if(is_array($getAll)){
			$data['admins'] = $getAll;
		}else{ 
			$data['admins'] = ""; 
		}
		 
		$data['viewFile'] = "list-admin";
		$data['page'] = 'listAdmin';
		$template = 'admin';
		echo Modules::run('template/'.$template, $data);
	}
	
	/***************************************** Add Admin Page ***************************************/
	function addAdmin(){
		$template = 'admin';
		$data['viewFile'] = 'add-admin';
		$data['page'] = 'addAdmin';
		echo Modules::run('template/'.$template, $data);
	}
	
	/***************************************** Add Admin Action ***************************************/
	function addAdminAction(){	
		$this->form_validation->set_rules('username', 'Username', 'trim|required|max_length[30]|xss_clean|is_unique[admin_master.username]',
		array(
			'required'=>'<b>Username is Required</b>',
			'is_unique'=>'<b>Username Already Exist</b>'
		));
		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean',
		array(
			'required'=>'<b>Password is Required</b>'
		));
		$this->form_validation->set_rules('module[]', 'select Module', 'required|xss_clean',
			array(
			'required'=>'<b>Select Admin Rights</b>'
		));
			
		if ($this->form_validation->run($this) == FALSE){
			echo validation_errors();
		}else{
			$rights = implode(",",$_POST['module']);
	        $data = array(
				'name' => 'Just Medical Advice',
				'imageUrl' => 'admin_assets/images/JMA.png',
				'username' => $_POST['username'],
				'password' => sha1($_POST['password']),
				'pswd' => $_POST['password'],
				'rights' => $rights,
				'isActive' => '1',
				'createdDate'=>date('Y-m-d H:i:s'),
				'modifiedDate'=>date('Y-m-d H:i:s')	
			);
			$mainAns = $this->db->insert("admin_master",$data);
			echo $mainAns;
		}
	}
	
	/***************************************** Edit Admin Page ***************************************/
	function editAdmin($id){
		if($id){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$adminId = base64_decode($base_64);
			$record = $this->Mdl_admin->get_where($adminId);
			if($record->num_rows() > 0) :
				$data['editRecord'] = $record->result();	
				$template = 'admin';
				$data['viewFile'] = 'edit-admin';
				$data['page'] = 'editAdmin';
				echo Modules::run('template/'.$template, $data);
			endif;
		} else 
			show_404();
	}
	
	/***************************************** Edit Admin Action ***************************************/
	function editAdminAction(){	
		$this->form_validation->set_rules('username', 'Username', 'trim|required|max_length[30]|xss_clean',
		array(
			'required'=>'<b>Username is Required</b>'
		));
		$this->form_validation->set_rules('module[]', 'select Module', 'required|xss_clean',
			array(
			'required'=>'<b>Select Admin Rights</b>'
		));
			
		if ($this->form_validation->run($this) == FALSE){
			echo validation_errors();
		}else{
			$rights = implode(",",$_POST['module']);
	        $data = array(
				'username' => $_POST['username'],
				'rights' => $rights,
				'isActive' => $_POST['status'],
				'modifiedDate'=>date('Y-m-d H:i:s')	
			);
			
			$id = $_POST['adminId'];
			
			$mainAns = $this->Mdl_admin->_update($id,$data);
			echo $mainAns;
		}
	}
	
	/***************************************** Change Password Page ***************************************/
	function change_password($id){
		if($id){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$adminId = base64_decode($base_64);
    		$template = 'admin';
    		$data['viewFile'] = 'changepassword';
    		$data['page'] = 'addadmin';
    		$data['id'] = $adminId;
    		//$data['script'] = "admin.js";
    		echo Modules::run('template/'.$template, $data);
		} else 
		show_404();
	}
	
	/***************************************** Change Password Action ***************************************/
	function changePasswordAction(){
	    $this->form_validation->set_rules('password', 'Password', 'required|xss_clean');

		if ($this->form_validation->run($this) == FALSE){
			echo validation_errors();
		}else{
			
			$pwd = $_POST['password'];
			$newpassword = sha1($_POST['password']);
			
	        $data = array(
				'password' => $newpassword,	
				'pswd' => $pwd
			);	
			
			$id = $_POST['id'];
			$mainAns = $this->Mdl_admin->_update($id, $data);
			echo $mainAns;
			exit;
		}
	}
	
	/***************************************** Delete Admin Action ***************************************/
	function delete_admin(){
	    $id = $_POST['admin_id']; 
		$base_64 = $id . str_repeat('=', strlen($id) % 4);
		$adminId = base64_decode($base_64);
		$delete_admin = $this->Mdl_admin->_delete($adminId);
		echo $delete_admin;
	}
	
	/***************************************** Inactive Admin Action ***************************************/
	function inactiveAction($id){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$adminId = base64_decode($base_64);
			$this->Mdl_admin->statusInactive($adminId);
			redirect('admin/list-admin');		
		}
	}
	
	/***************************************** Active Admin Action ***************************************/
	function activeAction($id){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$adminId = base64_decode($base_64);
			$this->Mdl_admin->statusActive($adminId);
			redirect('admin/list-admin');	
		}
	}
	
	/************************************** Get Admin Username **************************************/
	function getAdminUsername($id){
	   $uname = $this->Mdl_admin->getUsername($id);
	   echo  $uname;
    }
}