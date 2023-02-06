<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mdl_webinar extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	
	function insert($data){	
		 $this->db->insert('webinar',$data);
		 return 1; 
	}
	 
	function _insert($data){	
		 $this->db->insert('webinar_registration',$data);
		 return 1; 
	}
	
	function getWebinar($id){
		$query = $this->db->get_where("webinar",array("webinarId"=>$id));
		if($query->num_rows() > 0){
			return $query->result();
		}else{
			return "no";
		}	
	}
	
	function getUpcomingWebinars($date){
		$query = $this->db->query("SELECT * FROM `webinar` where date(startTime) > date '$date'");
		if($query->num_rows()>0){
			return $query->result();
		}else{
			 return "no";
		 }
	}
	
	function isExist($key){
		$query = $this->db->get_where("webinar",array("webinarId"=>$key));
		if($query->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}	
	}
	
	function isRegistered($key,$uid){
		$query = $this->db->get_where("webinar_registration",array("webinarId"=>$key,"userId"=>$uid));
		if($query->num_rows() > 0){
			return '1';
		}else{
			return '0';
		}	
	}
	
	function getDoctorWebinar($id,$start,$record){
		$this->db->select('*');
		$this->db->from('webinar');
		$this->db->where(array("createdBy"=>"doc","userId"=>$id));
		$this->db->order_by('createdDate');
		$this->db->limit($record,$start);
		$query = $this->db->get();
		
		if($query->num_rows()>0){
			return $query->result();
		}else{
			 return "no";
		 }
	}
	
	function getDoctorDetails($id){
		$this->db->select('*');
		$this->db->from('registration');
		$this->db->join('doctor', 'registration.id = doctor.regId AND registration.id = '.$id);
		$query = $this->db->get();
		
		if($query->num_rows()>0){
			return $query->result();
		}else{
			 return "no";
		 }
	}
}