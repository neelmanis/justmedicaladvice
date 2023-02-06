<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mdl_testimonial extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	function test_insert($data){	
		 $this->db->insert('testimonial',$data);
		 return 1; 
	}
	
	function listAll(){
		$this->db->select("*");
		$this->db->from("testimonial");
		$otherView=$this->db->get();
		if($otherView->num_rows() > 0){
			$getData = $otherView->result();
		}else{
			$getData = "";
		}	
		return $getData;
	}
	
	function datatobeEdit($id){
		$other = $this->db->get_where("testimonial","testId = $id");
		if($other->num_rows() > 0){
			$single = $other->result();
		}else{
			$single = "no";
		}	
		return $single;
	}
	
	function testUpdate($data,$id){
		$this->db->where('testId',$id);		
		$this->db->update("testimonial",$data);
		return 1;
	}
	
	function listAllTestimonial(){
		$this->db->select('*');
		$this->db->from('testimonial');
		$this->db->join("country","testimonial.testCountry=country.id");
		$this->db->where("testimonial.isActive",'1');
		$testimonialRecord = $this->db->get();
		   
        if($testimonialRecord->num_rows() > 0){
			return $testimonialRecord->result();
		}else{
			return "no";
		}
	}
	
	function other_delete($id){
		$this->db->where('testId',$id);		
		$this->db->delete("testimonial");
		return 1;
	}
	
	function getRecords($type,$name){
	    if(empty($name)){
			$this->db->select('*');
			$this->db->from('testimonial');
			$this->db->join("country","testimonial.testCountry=country.id");
			$this->db->where("testimonial.isActive",'1');
			if($type == 1){
				$this->db->where("testimonial.type",'1');
			}else{
				$this->db->where("testimonial.type",'0');
			} 
		    
			$this->db->order_by("testimonial.testId",'RANDOM');
			$this->db->limit('1');
			$testimonialRecord = $this->db->get();
		   
			if($testimonialRecord->num_rows > 0){
				return $testimonialRecord->result();
			}else{
				return "no"; 
			}
        }else {
	        $this->db->select('catId');
			$this->db->from('category_master');
			$this->db->where("catName",$name);
			$testimonialRecord = $this->db->get();  
			if($testimonialRecord->num_rows > 0) {
				$rec = $testimonialRecord->result();
			   
				if(is_array($rec)){
					$this->db->select('*');
					$this->db->from('testimonial');
					$this->db->join("country","testimonial.testCountry=country.id");
					$this->db->order_by("testimonial.createdDate", "desc");
        		    
					$testimonialRecords = $this->db->get();
					if($testimonialRecords->num_rows > 0) {
						$tested = $testimonialRecords->result();
						$ids = array();
							foreach($tested as $vals){
								$mainAns = explode(",",$vals->disease);
								if(in_array($rec[0]->catId,$mainAns)){
									array_push($ids,$vals);
								} 
							}	
							if(!empty($ids)){
							    return $ids;
							} else {
								$this->db->select('*');
								$this->db->from('testimonial');
								$this->db->join("country","testimonial.testCountry=country.id");
								$this->db->where("testimonial.isActive",'1');
								if($type == 1)
									$this->db->where("testimonial.type",'1');
								else 
									$this->db->where("testimonial.type",'0');
                    		   
							   $this->db->order_by("testimonial.testId",'RANDOM');
                    		   $this->db->limit('1');
                    		   $testimonialRecord = $this->db->get();
                    		   
                             if($testimonialRecord->num_rows > 0)
                                 return $testimonialRecord->result();
                             else
                                 return "no";
							}
					} else {
						 return "no";
				}
			} 
	   } else {
	       return "no";
	      }
	}
	}
	
}