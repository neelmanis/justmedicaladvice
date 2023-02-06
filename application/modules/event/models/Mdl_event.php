<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mdl_event extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	/******************************************** Event Insert ********************************************/
	function insert($data){	
		 $this->db->insert('event',$data);
		 return 1; 
	}
	
	/******************************************** Event Update ********************************************/
	function update($data,$id){
		$this->db->where('eventId',$id);		
		$this->db->update("event",$data);
		return 1;
	}
	
	/******************************************* Find By Id *******************************************/ 
	function findById($id){
		$other = $this->db->get_where("event",array("eventId"=>$id));
		if($other->num_rows() > 0){
			$single = $other->result();
		}else{
			$single = "no";
		}	
		return $single;
	}
	
	/******************************************* Event Delete *******************************************/ 
	function deleteById($id){
		$this->db->where('eventId', $id);
		$this->db->delete('event');		
	}
	
	/****************************************** Admin Event Inactive ******************************************/ 
	function statusInactive($id){
		$this->db->where('eventId', $id);
        $ans=$this->db->update('event',array("isActive"=> "0"));
		return $ans;
	}
	
	/******************************************* Admin Event Active *******************************************/ 
	function statusActive($id){
		$this->db->where('eventId', $id);
        $ans=$this->db->update('event',array("isActive"=> "1"));
		return $ans;
	}
	
	/******************************************* Admin Event Listing ******************************************/ 
	function listAll(){
		$this->db->select("*");
		$this->db->from("event");
		$this->db->order_by('eDate','desc');
		$result=$this->db->get();
		if($result->num_rows() > 0){
			return $result->result();
		}else{
			return "no";
		}
	}
	
	function getEvents($start,$record){
		$this->db->select("*");
		$this->db->from("event");
		$this->db->where("isActive",'1');
		$this->db->order_by('eDate','desc');
		$this->db->limit($record,$start);
		$result=$this->db->get();
		if($result->num_rows() > 0){
			return $result->result();
		}else{
			return "no";
		}
	}
	
	/******************************************* Home Event List ******************************************/
	function getHomeEvent(){
		$this->db->select("*");
		$this->db->from("event");
		$this->db->where("isActive",'1');
		$this->db->order_by('eDate','desc');
		$this->db->limit(3);
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