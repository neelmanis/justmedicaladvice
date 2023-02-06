<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mdl_login extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	function insert($table,$data){
	  	$this->db->insert($table,$data);
	  	return $this->db->insert_id();
	}
	
	function update($table,$data,$id,$col){
		$this->db->where($col, $id);
        $ans= $this->db->update($table,$data);
	    return $ans; 
	}
	
	function get_where($table,$id){
		$ans = $this->db->get_where($table, array('id' => $id));
		if($ans->num_rows() > 0){
			$res= $ans->result();
		}else{
			$res="No Data";
		}
		return $res;
	}
	
	function getDoctorDetails($id){
		$ans = $this->db->get_where('doctor', array('userId' => $id));
		if($ans->num_rows() > 0){
			$res= $ans->result();
		}else{
			$res=" ";
		}
		return $res;
	}
	
	function checkLogin($mob){
		$this->db->select("*");
		$this->db->from("registration");
		$this->db->where("mobile",$mob);
		$ans=$this->db->get();
		if($ans->num_rows()==1){
			return $ans->result();
	   } 
	}
	
	function isMobileExist($mobile){
		$this->db->select("*");
		$this->db->from("registration");
		$this->db->where("mobile",$mobile);
		$ans=$this->db->get();
		if($ans->num_rows() > 0){
			return $ans->result();
	   }else{
		   return 'no';
	   }
	}
	
	function getUser($id){
		$this->db->select('*');
		$this->db->from("registration");
		$this->db->where("id",$id);
		$ans=$this->db->get();
		if($ans->num_rows() > 0){
			return $ans->result();
	   }else{
		   return 'no';
	   }
	}
}