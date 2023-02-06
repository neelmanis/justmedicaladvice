<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Mdl_admin extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	function checkLogin($username, $password){
		$this->db->where('username', $username);
		$this->db->where('password', $password);
        $this->db->where('isActive','1');
		$query=$this->db->get('admin_master');
		//	print_r($this->db->last_query());  exit;
		if($query->num_rows() > 0){
			return TRUE;
		}else{
			return false;
		}
	}
	
	function get_where_custom($col, $value) {
		$this->db->where($col, $value);
		$query=$this->db->get('admin_master');
		return $query;
	}
	
	function listAdmin(){
		$adminView = $this->db->get_where("admin_master");
		if($adminView->num_rows()>0){
			 return $adminView->result();
		}else{
			 return "no";
		 }
	}
	
	function _insert($data) {
		return $this->db->insert("admin_master", $data);
	}
	
	function _update($id, $data) {
		$this->db->where('id', $id);
		return $this->db->update("admin_master", $data);
	}
	
	function statusInactive($id){
		$this->db->where('id', $id);
		return $this->db->update("admin_master", array('isActive'=>'0'));
	}
	
	function statusActive($id){
		$this->db->where('id', $id);
		return $this->db->update("admin_master", array('isActive'=>'1'));
	}
	
	function _delete($id) {
		$this->db->where('id', $id);
		return $this->db->delete("admin_master");
	}
	
	function get_where($id) {
		$this->db->where('id', $id);
		$query=$this->db->get("admin_master");
		return $query;
	}

	function _custom_query($mysql_query) {
		$query = $this->db->query($mysql_query);
		return $query;
	}
	
	/***************************************** Get Admin Username ***************************************/
	function getUsername($id){
		$this->db->select('username');
		$query = $this->db->get_where('admin_master',array('id'=>$id));
		if($query->num_rows()>0){
			$result = $query->result();
			return $result[0]->username;
		}else{
			return "No Data Available";
		}
	}
}