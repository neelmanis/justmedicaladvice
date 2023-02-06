<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mdl_banner extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	/******************************************** Banner Insert ********************************************/
	function insert($data){	
		 $this->db->insert('banner',$data);
		 return 1; 
	}
	
	/******************************************** Banner Update ********************************************/
	function update($data,$id){
		$this->db->where('bannerId',$id);		
		$this->db->update("banner",$data);
		return 1;
	}
	
	/******************************************* Find By Id *******************************************/ 
	function findById($id){
		$other = $this->db->get_where("banner",array("bannerId"=>$id));
		if($other->num_rows() > 0){
			$single = $other->result();
		}else{
			$single = "no";
		}	
		return $single;
	}
	
	/******************************************* Banner Delete *******************************************/ 
	function deleteById($id){
		$this->db->where('bannerId', $id);
		$this->db->delete('banner');		
	}
	
	/****************************************** Admin Banner Inactive ******************************************/ 
	function statusInactive($id){
		$this->db->where('bannerId', $id);
        $ans=$this->db->update('banner',array("isActive"=> "0"));
		return $ans;
	}
	
	/******************************************* Admin Blog Active *******************************************/ 
	function statusActive($id){
		$this->db->where('bannerId', $id);
        $ans=$this->db->update('banner',array("isActive"=> "1"));
		return $ans;
	}
	
	/******************************************* Admin Banner Listing ******************************************/ 
	function listAll(){
		$this->db->select("*");
		$this->db->from("banner");
		$this->db->order_by("createdDate","DESC");
		$result=$this->db->get();
		if($result->num_rows() > 0){
			return $result->result();
		}else{
			return "no";
		}
	}
	
	/******************************************* Home Banner List ******************************************/
	function getHomeBanner(){
		$this->db->select("*");
		$this->db->from("banner");
		$this->db->where("home","1");
		$this->db->where("isActive","1");
		$result=$this->db->get();
		if($result->num_rows() > 0){
			return $result->result();
		}else{
			return "no";
		}
	}
	
	/******************************************* Member Banner List ******************************************/
	function getMemberBanner(){
		$this->db->select("*");
		$this->db->from("banner");
		$this->db->where("memDash","1");
		$this->db->where("isActive","1");
		$result=$this->db->get();
		if($result->num_rows() > 0){
			return $result->result();
		}else{
			return "no";
		}
	}
	
	/******************************************* Member Banner List ******************************************/
	function getDoctorBanner(){
		$this->db->select("*");
		$this->db->from("banner");
		$this->db->where("docDash","1");
		$this->db->where("isActive","1");
		$result=$this->db->get();
		if($result->num_rows() > 0){
			return $result->result();
		}else{
			return "no";
		}
	}
	
	/******************************************* Find Admin Name *******************************************/ 
	function findName($id){
		$this->db->select('username');
		$details = $this->db->get_where("admin_master",array('id'=>$id));
		if($details->num_rows() > 0){
			$ans = $details->result();
			return $ans[0]->username;
		}else{
			return "No Data";
		}
	}
}