<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Degree extends MX_Controller{
	
	function __construct() {
		parent::__construct();
		$this->load->model('Mdl_degree');	
		$this->load->model('admin/Mdl_admin');	
	}
	
	/***************************************** Degree Listing Page ***************************************/
	function listDegree(){
		$getDegree = $this->Mdl_degree->listDegree();
		if($getDegree !== 'No Data'){
			$degree = array();
			foreach($getDegree as $val){
				$res = array();
				$res['degreeId'] = $val->degreeId;
				$res['name'] = $val->name;
				$res['adminName'] = $this->Mdl_admin->getUsername($val->adminId);
				$res['isActive'] = $val->isActive;
				$degree[] = $res;
			}
			$data['degree'] = $degree;
		}else{
			$data['degree'] = 'No Data';
		}
		$data['viewFile'] = "list-degree";
		$data['page'] = 'listDegree';
		$template = 'admin';
		echo Modules::run('template/'.$template, $data);
	}
	
	/***************************************** Add Degree Page ***************************************/
	function addDegree(){
		$data['viewFile'] = 'add-degree';
		$data['page'] = 'addDegree';
		$template = 'admin';
		echo Modules::run('template/'.$template, $data);
	}
	
	/***************************************** Add Degree Action ***************************************/
	function addDegreeAction(){
		$content = $this->input->post();
        $this->form_validation->set_rules("name","Degree Name","required|xss_clean",
		array(
			'required'=>'<b>Degree Name is required<b>'
		));
		
		if($this->form_validation->run() == FALSE){
			echo validation_errors();
		}else{
			$data = array(
				'name' => $content['name'],
				'adminId' => $this->session->userId,
				'isActive' => '1',
				'createdDate' =>date('Y-m-d H:i:s'),
				'modifiedDate' =>date('Y-m-d H:i:s')
			);
                                                               
			$insert = $this->Mdl_degree->insert($data);
			echo "1";
		} 
	}
	
	/***************************************** Edit Degree Page ***************************************/
	function editDegree($id){
		$base_64 = $id . str_repeat('=', strlen($id) % 4);
		$dId = base64_decode($base_64);
		$data['getData'] = $this->Mdl_degree->getDegreeById($dId);
		$data['viewFile'] = "edit-degree";
		$data['page'] = "editDegree";
		$template = 'admin';
		echo Modules::run('template/'.$template, $data);
	}	
	
	/***************************************** Edit Degree Page ***************************************/
	function editDegreeAction(){
		$content = $this->input->post();
        $this->form_validation->set_rules("name","Degree Name","required|xss_clean");
		$this->form_validation->set_rules("status","Degree Status","required|xss_clean");
	
		if($this->form_validation->run() == FALSE){
			echo validation_errors();
		}else{
			$data = array(
				'name' => $content['name'],
				'adminId' => $this->session->userId,
				'isActive' => $content['status'],
				'modifiedDate' =>date('Y-m-d H:i:s')
			);
                                                               
			$edit = $this->Mdl_degree->update($data,$content['degreeId']);
			echo "1";
		} 
	}
	
	/***************************************** Delete Degree ***************************************/
	function deleteDegree(){
		$dId = $_POST['degreeId'];
		$base_64 = $dId . str_repeat('=', strlen($dId) % 4);
		$degreeId = base64_decode($base_64);
		$delete = $this->Mdl_degree->delete_degree($degreeId);
		echo $delete;
	}
	
	/***************************************** Inactive Degree Action ***************************************/
	function inActiveAction($id){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$dId = base64_decode($base_64);
			$aid = $this->session->userId;
			$this->Mdl_degree->statusInactive($dId,$aid);
			redirect('degree/list-degree');		
		}
	}
	
	/***************************************** Active Degree Action ***************************************/
	function activeAction($id){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$dId = base64_decode($base_64);
			$aid = $this->session->userId;
			$this->Mdl_degree->statusActive($spId,$aid);
			redirect('degree/list-degree');	
		}
	}
}

