<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mdl_location extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	
	/**************************************** Country List ******************************************/
	function listCountries(){
		$query = $this->db->get("countries");
		if($query->num_rows() > 0){
			return $query->result();
		}else{
			return "no";
		}
	}
	
	/**************************************** State List ******************************************/
	function listStates(){
		$query = $this->db->get("states");
		if($query->num_rows() > 0){
			return $query->result();
		}else{
			return "no";
		}
	}
	
	/**************************************** City List ******************************************/
	function listCity($id){
		$this->db->select('a.id, a.cityName, a.isActive');
		$this->db->from('cities a, states b');
		$this->db->where("a.state_id = b.id AND b.country_id = $id");
		$this->db->order_by('a.cityName');
		$query = $this->db->get();
		
		if($query->num_rows()>0){
			return $query->result();
		}else{
			 return "no";
		}
	}
	
	function getMobileCodes(){
		$this->db->select('phonecode,shortname');
		$this->db->from('countries');
		$this->db->order_by('priority,countryName');
		$ans = $this->db->get();
		if($ans->num_rows() > 0){
			return $ans->result();
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
	
	/**************************************** Inactive Action ******************************************/
	function statusInactive($table, $id, $col){
		$this->db->where($col, $id);
		return $this->db->update($table, array('isActive'=>'0'));
	}
	
	/**************************************** Active Action ******************************************/
	function statusActive($table, $id, $col){
		$this->db->where($col, $id);
		return $this->db->update($table, array('isActive'=>'1'));
	}
}