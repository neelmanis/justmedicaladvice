<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mdl_home extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	
	/******************************************* Insert Query ********************************************/
	function insert($table,$data){
	   $this->db->insert($table,$data);
	   return 1;
	}
   
	/******************************************* Get Testimonial ********************************************/
	function getTestimonials(){
		$this->db->select('*');
		$this->db->from('testimonial');
		$this->db->where("isActive",'1');
		$this->db->order_by("createdDate", "desc");
		$this->db->limit('3');
		$testimonialRecord = $this->db->get();
								
        if($testimonialRecord->num_rows() > 0){ 
			return $testimonialRecord->result();
	   }else{
		   return 'no';
	   }
	}
}