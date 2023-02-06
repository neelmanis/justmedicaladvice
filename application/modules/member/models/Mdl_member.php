<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mdl_member extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	
	/************************************************ Insert Query ****************************************************/
	function insert($table,$data){
	  	$this->db->insert($table,$data);
	  	return $this->db->insert_id();
	}
	
	/************************************************ Update Query ****************************************************/
	function update($table,$data,$id,$col){
		$this->db->where($col, $id);
        $ans= $this->db->update($table,$data);
	    return $ans; 
	}
	
	/************************************************ Get Member List ****************************************************/
	function listMember(){
		$this->db->select('*');
		$this->db->from('registration');
		$this->db->join('member', 'registration.id = member.regId');
		$this->db->order_by('registration.createdDate','DESC');
		$query = $this->db->get();
		
		if($query->num_rows()>0){
			return $query->result();
		}else{
			 return "No Data";
		}
	}
	
	/************************************************ Get Member Interest ****************************************************/
	function getInterest($id){
		$this->db->select('fieldsOfInterest');
		$query = $this->db->get_where("member","regId=$id");
		if($query->num_rows()>0){
			return $query->result();
		}else{
			 return "no";
		}
	}
	
	/************************************************ Get Member Details By Id ****************************************************/
	function getMemmberDetails($id){
		$this->db->select('*');
		$this->db->from('registration');
		$this->db->join('member', 'registration.id = member.regId AND registration.id = '.$id);
		$query = $this->db->get();
		if($query->num_rows()>0){
			return $query->result();
		}else{
			 return "no";
		}
	}
	
	/************************************************ Get Member Password****************************************************/
	function getMemmberPassword($id){
		$this->db->select('password');
		$this->db->from('registration');
		$this->db->where('id='.$id);
		$query = $this->db->get();
		
		if($query->num_rows()>0){
			$res =  $query->result();
			return $res[0]->password;
		}else{
			 return "no";
		}
	}

	/************************************************ Status Inactive ****************************************************/
	function statusInactive($id){
		$this->db->where('id', $id);
        $ans=$this->db->update('registration',array("isActive"=> "0","statusCode"=>"8"));
		return $ans;
	}
	
	/************************************************ Status Active ****************************************************/
	function statusActive($id){
		$this->db->where('id', $id);
        $ans=$this->db->update('registration',array("isActive"=> "1","statusCode"=>"6"));
		return $ans;
	}
	
	/************************************************ Delete Member Query ****************************************************/
	function deleteById($id){
	    $this ->db-> where('regId', $id);
        $result = $this ->db-> delete('member');
		$this ->db-> where('id', $id);
        $result = $this ->db-> delete('registration');
		return $result;
	}
	
	/************************************************ Get Doctor Name ****************************************************/
	function getDoctorName($id){
		$ans = $this->db->get_where('registration',array('id'=>$id));
		if($ans->num_rows()>0){
			$res = $ans->result();
			return $res[0]->name; 
		}else{
			 return "no";
		 }
  	}
	
	function getISD($id){
		$this->db->select('isd');
		$query = $this->db->get_where("registration","id=$id");
		if($query->num_rows()>0){
			return $query->result();
		}else{
			 return "no";
		}
	}
	
	function getCities($isd){
		$this->db->select('cityName,countryName,stateName');
		$this->db->from('countries a');
		$this->db->join('states b', 'a.id = b.country_id AND a.phonecode = '.$isd);
		$this->db->join('cities c', "b.id = c.state_id AND c.isActive = '1'");
		$this->db->order_by('cityName');
		$query = $this->db->get();
		
		if($query->num_rows()>0){
			return $query->result();
		}else{
			 return "no";
		}
	}
}