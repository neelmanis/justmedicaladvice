<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mdl_reg extends CI_Model {
	
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
	
	function getUserById($table,$id){
		$ans = $this->db->get_where($table, array('id' => $id));
		if($ans->num_rows() > 0){
			$res= $ans->result();
		}else{
			$res=" ";
		}
		return $res;
	}
}