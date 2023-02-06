<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mdl_faq extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	
	/***************************************** Insert Query ***************************************/
	function insert($data){	
		 $this->db->insert('faq',$data);
		 return 1; 
	}

	/***************************************** Update Query ***************************************/	
	function update($data,$id){
		$this->db->where('faqId',$id);		
		$this->db->update("faq",$data);
		return 1;
	}
	
	/***************************************** List Faq ***************************************/
	function getFaqList(){
		$query = $this->db->get("faq");
		if($query->num_rows() > 0){
			return $query->result();
		}else{
			return "No Data";
		}			
	}
	
	/***************************************** List Faq ***************************************/
	function getFaq(){
		$query = $this->db->get_where("faq","isActive='1'");
		if($query->num_rows() > 0){
			return $query->result();
		}else{
			return "No Data";
		}			
	}
	
	/***************************************** Get Faq By ID ***************************************/
	function getFaqById($id){
		$query = $this->db->get_where("faq","faqId = $id");
		if($query->num_rows() > 0){
			return $query->result();
		}else{
			return "No Data";
		}	
	}
	
	/***************************************** Status Inactive ***************************************/
	function statusInactive($id){
		$this->db->where('faqId', $id);
		return $this->db->update("faq", array('isActive'=>'0'));
	}

	/***************************************** Status Active ***************************************/
	function statusActive($id){
		$this->db->where('faqId', $id);
		return $this->db->update("faq", array('isActive'=>'1'));
	}
}