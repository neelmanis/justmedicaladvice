<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mdl_message extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	
	/********************************************** Insert Query *******************************************/
	function insert($table,$data){
	  	$this->db->insert($table,$data);
	  	return $this->db->insert_id();
	}
	
	/********************************************** Update Query *******************************************/
	function update($table,$data,$id,$col){
		$this->db->where($col, $id);
        $ans= $this->db->update($table,$data);
	    return $ans; 
	}
	
	/********************************************** Delete Query *******************************************/
	function deleteById($id){
	    $this ->db-> where('messageId', $id);
        $result = $this ->db->delete('messages');
		return $result;
	}
	
	/********************************************** Get Message By Id *******************************************/
	function getMessageById($id){
		$query = $this->db->get_where("messages","messageId = $id");
		if($query->num_rows()>0){
			return $query->result();
		}else{
			 return "no";
		}
	}
	
	/********************************************** Doctor Inbox Query *******************************************/
	function getDoctorInbox($id, $start, $limit){
		$this->db->select('*');
		$this->db->from('messages');
		$this->db->where("doctorId = $id AND isReplied = '0'");
		$this->db->order_by('postedDate','DESC');
		$this->db->limit($limit,$start);
		$query = $this->db->get();
		
		if($query->num_rows()>0){
			return $query->result();
		}else{
			 return "no";
		}
	}
	
	/********************************************** Member Inbox Query *******************************************/
	function getMemberInbox($id, $start, $limit){
		$this->db->select('*');
		$this->db->from('messages');
		$this->db->where("memberId = $id AND isReplied = '1'");
		$this->db->order_by('postedDate','DESC');
		$this->db->limit($limit,$start);
		$query = $this->db->get();
		
		if($query->num_rows()>0){
			return $query->result();
		}else{
			 return "no";
		}
	}
	
	/********************************************** Doctor Inbox Query *******************************************/
	function getDoctorSent($id, $start, $limit){
		$this->db->select('*');
		$this->db->from('messages');
		$this->db->where("doctorId = $id AND isReplied = '1'");
		$this->db->order_by('postedDate','DESC');
		$this->db->limit($limit,$start);
		$query = $this->db->get();
		
		if($query->num_rows()>0){
			return $query->result();
		}else{
			 return "no";
		}
	}
	
	/********************************************** Member Inbox Query *******************************************/
	function getMemberSent($id, $start, $limit){
		$this->db->select('*');
		$this->db->from('messages');
		$this->db->where("memberId = $id AND isReplied = '0'");
		$this->db->order_by('postedDate','DESC');
		$this->db->limit($limit,$start);
		$query = $this->db->get();
		
		if($query->num_rows()>0){
			return $query->result();
		}else{
			 return "no";
		}
	}
}