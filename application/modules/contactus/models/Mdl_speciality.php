<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mdl_speciality extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	function speciality_insert($data){	
		 $this->db->insert('speciality',$data);
		 return 1; 
	}
	
	function listspeciality(){
		$specialityView =$this->db->get_where("speciality");
		if($specialityView->num_rows() > 0){
			$getSpeciality = $specialityView->result();
		}else{
			$getSpeciality = "no";
		}	
		return $getSpeciality;
	}
	
	function speciality_delete($id){
		$this->db->where('spId',$id);		
		$this->db->delete("speciality");
		return 1;
	}
	
	
	function urlCheck($url){	
		$check = $this->db->get_where("speciality",array('isActive' =>'1' , 'spSlug' => $url));
		if($check->num_rows() > 0)
			return false;
		else 
			return true;
	}
	
	function datatobeEdit($id){
		$specialitySingle = $this->db->get_where("speciality","spId = $id");
		if($specialitySingle->num_rows() > 0){
			$singleSpeciality = $specialitySingle->result();
		}else{
			$singleSpeciality = "no";
		}	
		return $singleSpeciality;
	}
	
	
	function speciality_edit_update($data,$id){
		$this->db->where('spId',$id);		
		$this->db->update("speciality",$data);
		return 1;
	}
	
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
	
	function statusInactive($id,$aid){
		$this->db->where('spId', $id);
		return $this->db->update("speciality", array('isActive'=>'0','adminId'=>$aid));
	}
	
	function statusActive($id,$aid){
		$this->db->where('spId', $id);
		return $this->db->update("speciality", array('isActive'=>'1','adminId'=>$aid));
	}
}