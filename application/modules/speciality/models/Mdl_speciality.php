<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mdl_speciality extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	
	/***************************************** Insert Query ***************************************/
	function insert($data){	
		 $this->db->insert('speciality',$data);
		 return 1; 
	}

	/***************************************** Update Query ***************************************/	
	function update($data,$id){
		$this->db->where('spId',$id);		
		$this->db->update("speciality",$data);
		return 1;
	}
	
	/***************************************** List Speciality ***************************************/
	function getSpecialityList(){
		$specialityView =$this->db->get_where("speciality");
		if($specialityView->num_rows() > 0){
			$getSpeciality = $specialityView->result();
		}else{
			$getSpeciality = "no";
		}	
		return $getSpeciality;
	}
	
	/***************************************** Check URL ***************************************/
	function urlCheck($url){	
		$check = $this->db->get_where("speciality",array('spSlug' => $url));
		if($check->num_rows() > 0)
			return false;
		else 
			return true;
	}
	
	/***************************************** Get Speciality By ID ***************************************/
	function getSpeciality($id){
		$specialitySingle = $this->db->get_where("speciality","spId = $id");
		if($specialitySingle->num_rows() > 0){
			$singleSpeciality = $specialitySingle->result();
		}else{
			$singleSpeciality = "no";
		}	
		return $singleSpeciality;
	}
	
	function getSpecialities(){
		$query = $this->db->get_where("speciality","isActive = '1'");
		if($query->num_rows() > 0){
			$getSpeciality = $query->result();
		}else{
			$getSpeciality = "no";
		}	
		return $getSpeciality;
	}
	
	/***************************************** Find Speciality Name ***************************************/	
	function getname($id){
		$this->db->select('spName');
		$res = $this->db->get_where('speciality',array('spId'=>$id));
		
		if($res->num_rows() > 0){
			$data =  $res->result();
			return $data[0]->spName;
		}else{
			return "No Data";
		}
	}
	
	/***************************************** Status Inactive ***************************************/
	function statusInactive($id,$aid){
		$this->db->where('spId', $id);
		return $this->db->update("speciality", array('isActive'=>'0','adminId'=>$aid));
	}

	/***************************************** Status Active ***************************************/
	function statusActive($id,$aid){
		$this->db->where('spId', $id);
		return $this->db->update("speciality", array('isActive'=>'1','adminId'=>$aid));
	}
	
	/***************************************** Delete Speciality ***************************************/
	function speciality_delete($id){
		$this->db->where('spId',$id);		
		$this->db->delete("speciality");
		return 1;
	}
}