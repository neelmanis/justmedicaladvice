<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mdl_notifications extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	/*************************************** Insert Notifications *******************************************/
	function insert($table,$data){	
		 $this->db->insert($table,$data);
		 return 1; 
	}
	
	/*************************************** Get Member Notifications *******************************************/
	function getMemberNotification($id){
		$this->db->select('*');
		$this->db->from('notification_members');
		$this->db->where("allMembers","1");
		$this->db->or_where("userId",$id);
		$this->db->order_by("createdDate","DESC");
		$this->db->limit(7);
		$query = $this->db->get();
		//echo $this->db->last_query(); exit;
		if($query->num_rows() > 0){
			return $query->result();
		}else{
			return "No Data";
		}
	}
	
	/*************************************** Get Member Notifications *******************************************/
	function getDoctorNotification($id){
		$this->db->select('*');
		$this->db->from('notification_doctors');
		$this->db->where("allDoctors","1");
		$this->db->or_where("userId",$id);
		$this->db->order_by("createdDate","DESC");
		$this->db->limit(7);
		$query = $this->db->get();
		//echo $this->db->last_query(); exit;
		if($query->num_rows() > 0){
			return $query->result();
		}else{
			return "No Data";
		}
	}
}