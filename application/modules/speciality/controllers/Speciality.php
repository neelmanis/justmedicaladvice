<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Speciality extends MX_Controller{
	
	function __construct() {
		parent::__construct();
		$this->load->model('Mdl_speciality');	
		$this->load->model('admin/Mdl_admin');	
	}
	
	/***************************************** Speciality Listing Page ***************************************/
	function listall(){
		$getSpecialities = $this->Mdl_speciality->getSpecialityList();
		$speciality = array();
		foreach($getSpecialities as $val){
			$res = array();
			$res['spId'] = $val->spId;
			$res['spName'] = $val->spName;
			$res['adminName'] = $this->Mdl_admin->getUsername($val->adminId);
			$res['isActive'] = $val->isActive;
			$speciality[] = $res;
		}
		$data['specialities'] = $speciality;
		$data['viewFile'] = "list-speciality";
		$data['page'] = 'listSpeciality';
		$template = 'admin';
		echo Modules::run('template/'.$template, $data);
	}
	
	/***************************************** Add Speciality Page ***************************************/
	function addSpeciality(){
		$data['viewFile'] = 'add-speciality';
		$data['page'] = 'addSpeciality';
		$template = 'admin';
		echo Modules::run('template/'.$template, $data);
	}
	
	/***************************************** Add Speciality Action ***************************************/
	function addSpecialityAction(){
		$content = $this->input->post();
        $this->form_validation->set_rules("speciality_name","Speciality Name","required|xss_clean",
		array(
			'required'=>'<b>Speciality Name is Required<b>'
		));
	
		if($this->form_validation->run() == FALSE){
			echo validation_errors();
		}else{
			$find = array("/","_","?","(",")","-",":","!","'",".",",","\r","\n","\r\n");
			$replace = array("");
			$new_string = str_replace($find,$replace,strtolower($content['speciality_name']));
			$new_string = preg_replace('/^\s+|\s+$|\s+(?=\s)/','',$new_string); 
			$urlKey = str_replace(" ","-",trim($new_string));
			
			$urlCheck = $this->Mdl_speciality->urlCheck($urlKey);
			
			if($urlCheck){  				  
				$data = array(
					'spName' => $content['speciality_name'],
					'spSlug' => $urlKey,
					'adminId' => $this->session->userId,
					'isActive' => '1',
					'createdDate' =>date('Y-m-d H:i:s'),
					'modifiedDate' =>date('Y-m-d H:i:s')
				);
                                                               
				$specialityinsert = $this->Mdl_speciality->insert($data);
				echo "1";
			} else {
				echo "2";
			}	
		}
	}
	
	/***************************************** Edit Speciality Page ***************************************/
	function editSpeciality($id){
		$base_64 = $id . str_repeat('=', strlen($id) % 4);
		$spId = base64_decode($base_64);
		$data['getData'] = $this->Mdl_speciality->getSpeciality($spId);
		$data['viewFile'] = "edit-speciality";
		$data['page'] = "editSpeciality";
		$template = 'admin';
		echo Modules::run('template/'.$template, $data);
	}	
	
	/***************************************** Edit Speciality Page ***************************************/
	function editSpecialityAction(){
		$content = $this->input->post();
		
		$this->form_validation->set_rules("speciality_name","Speciality Name","required|xss_clean",array(
			'required' => '<b>Speciality Name is Required</b>'
		));
		$this->form_validation->set_rules("speciality_status","Speciality Status","required|xss_clean",array(
			'required' => '<b>Please Select the status</b>'
		));
	
		if($this->form_validation->run() == FALSE){
			echo validation_errors();
		}else{
			
			if($content['spNameOg'] !== $content['speciality_name']){
				$find = array("/","_","?","(",")","-",":","!","'",".",",","\r","\n","\r\n");
				$replace = array("");
				$new_string = str_replace($find,$replace,strtolower($content['speciality_name']));
				$new_string = preg_replace('/^\s+|\s+$|\s+(?=\s)/','',$new_string); 
				$urlKey = str_replace(" ","-",trim($new_string));
				$urlCheck = $this->Mdl_speciality->urlCheck($urlKey);
				if($urlCheck){  				  
					$specialityid = $content['spId']; 
					$edit_data = array(
						'spName' => $content['speciality_name'],
						'spSlug' => $urlKey,
						'adminId' => $this->session->userId,
						'isActive' => $content['speciality_status'],
						'modifiedDate' =>date('Y-m-d H:i:s')
					);
				}else{
					echo 2; exit;   
				} 
			}else{
				$specialityid = $content['spId']; 
				$edit_data = array(
					'adminId' => $this->session->userId,
					'isActive' => $content['speciality_status'],
					'modifiedDate' =>date('Y-m-d H:i:s')
				);
			}
			
			$editSp = $this->Mdl_speciality->update($edit_data,$specialityid);
			echo "1";
		}
	}
	
	/***************************************** Inactive Speciality Action ***************************************/
	function inActiveAction($id){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$spId = base64_decode($base_64);
			$aid = $this->session->userId;
			$this->Mdl_speciality->statusInactive($spId,$aid);
			redirect('speciality/listall');		
		}
	}
	
	/***************************************** Active Speciality Action ***************************************/
	function activeAction($id){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$spId = base64_decode($base_64);
			$aid = $this->session->userId;
			$this->Mdl_speciality->statusActive($spId,$aid);
			redirect('speciality/listall');	
		}
	}
	
	/***************************************** Get All Speciality Records ***************************************/	
	function getAllSpecialities(){
	   $getSpecialities = $this->Mdl_speciality->getSpecialities();
	   return $getSpecialities;
    }

	/***************************************** Get Speciality Name ***************************************/
	function getName($id){
		$res = $this->Mdl_speciality->getname($id);
		echo $res;
	}
	
	/***************************************** Delete Speciality ***************************************/
	function deleteSpeciality(){
		$spid = $_POST['spId'];
		$base_64 = $spid . str_repeat('=', strlen($spid) % 4);
		$sId = base64_decode($base_64);
		$delete_sp = $this->Mdl_speciality->speciality_delete($sId);
		echo $delete_sp;
	}
}

