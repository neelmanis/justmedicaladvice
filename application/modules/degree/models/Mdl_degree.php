<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mdl_degree extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	
	/***************************************** Insert Degree ***************************************/
	function insert($data){	
		$this->db->insert('degree',$data);
		return 1; 
	}
	
	/***************************************** Update Degree ***************************************/
	function update($data,$id){
		$this->db->where('degreeId',$id);		
		$this->db->update("degree",$data);
		return 1;
	}
	
	/***************************************** Delete Degree ***************************************/	
	function delete_degree($id){
		$this->db->where('degreeId',$id);		
		$this->db->delete("degree");
		return 1;
	}
	
	/***************************************** List Degree ***************************************/
	function listDegree(){
		$degreeData = $this->db->get_where("degree");
		if($degreeData->num_rows() > 0){
			$getDegree = $degreeData->result();
		}else{
			$getDegree = "No Data";
		}	
		return $getDegree;
	}

	/***************************************** Get Degree By Id ***************************************/
	function getDegreeById($id){
		$degree = $this->db->get_where("degree","degreeId = $id");
		if($degree->num_rows() > 0){
			$degree = $degree->result();
		}else{
			$degree = "No Data";
		}	
		return $degree;
	}

	/***************************************** Inactivate Degree ***************************************/
	function statusInactive($id,$aid){
		$this->db->where('degreeId', $id);
		return $this->db->update("degree", array('isActive'=>'0','adminId'=>$aid));
	}
	
	/***************************************** Activate Degree ***************************************/
	function statusActive($id,$aid){
		$this->db->where('degreeId', $id);
		return $this->db->update("degree", array('isActive'=>'1','adminId'=>$aid));
	}
}