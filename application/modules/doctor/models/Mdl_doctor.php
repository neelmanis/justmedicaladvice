<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mdl_doctor extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	
	/************************************* Insert Query *******************************/
	function insert($table,$data){
	  	$this->db->insert($table,$data);
	  	return $this->db->insert_id();
	}
	
	/************************************* Update Query *******************************/
	function update($table,$data,$id,$col){
		$this->db->where($col, $id);
        $ans= $this->db->update($table,$data);
	    return $ans; 
	}
	
	/************************************* Get ISD Codes *******************************/
	function getISD($id){
		$this->db->select('isd');
		$query = $this->db->get_where("registration","id=$id");
		if($query->num_rows()>0){
			return $query->result();
		}else{
			 return "no";
		}
	}
	
	/************************************* Get Cities *******************************/
	function getCities($isd){
		$this->db->select('cityName,countryName,stateName');
		$this->db->from('countries a');
		$this->db->join('states b', 'a.id = b.country_id AND a.phonecode = '.$isd);
		$this->db->join('cities c', 'b.id = c.state_id');
		$this->db->order_by('cityName');
		$query = $this->db->get();
		
		if($query->num_rows()>0){
			return $query->result();
		}else{
			 return "no";
		}
	}
	
	/************************************* Get Degrees *******************************/
	function getDegree(){
		$query = $this->db->get_where("degree","isActive='1'");
		if($query->num_rows()>0){
			return $query->result();
		}else{
			 return "no";
		}
	}
	
	/************************************* Get Doctor Name *******************************/
	function getName($id){
		$this->db->select('name');
		$ans = $this->db->get_where('registration', array('id' => $id));
		if($ans->num_rows() > 0){
			$res= $ans->result();
		}else{
			$res=" ";
		}
		return $res;
	}

	/************************************* Get List Of Doctor *******************************/
	function listDoctor(){
		$this->db->select('*');
		$this->db->from('registration');
		$this->db->join('doctor', 'registration.id = doctor.regId');
		$this->db->order_by('registration.createdDate','DESC');
		$query = $this->db->get();
		
		if($query->num_rows()>0){
			return $query->result();
		}else{
			 return "No Data";
		}
	}

	/************************************* Get List Of Doctor *******************************/
	function searchDoctors($list){
		$this->db->select('*');
		$this->db->from('registration');
		if($list !== "No Data"){
			$this->db->join('doctor', "registration.id = doctor.regId AND doctor.regId NOT IN ($list)");
		}else{
			$this->db->join('doctor', "registration.id = doctor.regId AND doctor.regId");
		}
		$this->db->where("registration.isActive","1");
		//$this->db->order_by('registration.createdDate','DESC');
		$this->db->order_by('doctor.isFeatured','DESC');
		$query = $this->db->get();
		
		if($query->num_rows()>0){
			return $query->result();
		}else{
			 return "no";
		}
	}

	/************************************* Get Doctors By Search *******************************/
	function getDoctorsBySearch($data){
		$this->db->distinct();
		$this->db->select('name,profileImage,regId');
		$this->db->from('registration');
		$this->db->join('doctor', "registration.id = doctor.regId AND registration.isActive = '1'");
		foreach($data as $key){
			$this->db->like('registration.name', $key);
		}
		$this->db->limit(2);
		$doc = $this->db->get();
		if($doc->num_rows() > 0)
	        return $doc->result();
	    else
	       return "no";
	}
	
	/************************************* Get Doctor Details *******************************/	
	function getDoctorDetails($id){
		$this->db->select('*');
		$this->db->from('registration');
		$this->db->join('doctor', "registration.id = doctor.regId AND registration.id = $id");
		$query = $this->db->get();
		
		if($query->num_rows()>0){
			return $query->result();
		}else{
			 return "no";
		 }
	}

	/************************************* Get Doctor By Id *******************************/
	function findByUserId($id){
		$this->db->select('id');
		$ans = $this->db->get_where('doctor', array('regId' => $id));
		if($ans->num_rows() > 0){
			$res= $ans->result();
		}else{
			$res=" ";
		}
		return $res;
	}
	
	/************************************* Get Doctor Password *******************************/
	function getDoctorPassword($id){
		$this->db->select('password');
		$this->db->from('registration');
		$this->db->where('id='.$id);
		$query = $this->db->get();
		
		if($query->num_rows()>0){
			$res =  $query->result();
			return $res[0]->password;
		}else{
			 return "no";
		}
	}
	
	/************************************* Get Doctor Details *******************************/	
	function getMemberDetails($id){
		$this->db->select('*');
		$this->db->from('registration');
		$this->db->join('member', "registration.id = member.regId AND registration.id = $id");
		$query = $this->db->get();
		
		if($query->num_rows()>0){
			return $query->result();
		}else{
			 return "no";
		 }
	}
	
	/************************************* Get Doctor Feedbacks *******************************/
	function getFeedback($id, $start, $limit){
		$this->db->from('doctor_feedback');
		$this->db->where("docId = $id");
		$this->db->order_by('postedDate','DESC');
		$this->db->limit($limit,$start);		
		$query= $this->db->get();
		
		if($query->num_rows()>0){
			return $query->result();
		}else{
			 return "no";
		}
	}
	
	/************************************* Inactivate Doctor *******************************/
	function statusInactive($id){
		$this->db->where('id', $id);
        $ans=$this->db->update('registration',array("isActive"=> "0","statusCode"=>"8"));
		return $ans;
	}

	/************************************* Activate Doctor *******************************/
	function statusActive($id){
		$this->db->where('id', $id);
        $ans=$this->db->update('registration',array("isActive"=> "1","statusCode"=>"6"));
		return $ans;
	}
	
	/************************************* Mark Doctor As Featured *******************************/
	function markFeatured($id){
		$this->db->where('regId', $id);
        $ans=$this->db->update('doctor',array("isFeatured"=> "1"));
		return $ans;
	}
	
	/************************************* Unmark Doctor As Featured *******************************/
	function unmarkFeatured($id){
		$this->db->where('regId', $id);
        $ans=$this->db->update('doctor',array("isFeatured"=> "0"));
		return $ans;
	}

	/************************************* Set Doctor On Home *******************************/
	function setHome($id){
		$this->db->where('regId', $id);
        $ans=$this->db->update('doctor',array("isHome"=> "1"));
		return $ans;
	}
	
	/************************************* Remove Doctor from Home *******************************/
	function removeHome($id){
		$this->db->where('regId', $id);
        $ans=$this->db->update('doctor',array("isHome"=> "0"));
		return $ans;
	}
	
	/************************************* Delete Doctor *******************************/
	function deleteById($id){
	    $this ->db-> where('userId', $id);
        $result = $this ->db-> delete('doctor');
		$this ->db-> where('userId', $id);
        $result = $this ->db-> delete('users');
		return $result;
	}

	/************************************* Get Members Following *******************************/
	function getFollowersDetails($id){
		$this->db->select('followedMem');
		$ans = $this->db->get_where("doctor",array('regId'=>$id));
		if($ans->num_rows()>0){
			$result = $ans->result();
			if($result[0]->followedMem !== ''){
				return $result; 
			}else{
				return "no";
			}
		}else{
			 return "no";
		}
	}
	
	/************************************* Get Featured Doctors *******************************/
	function getFeaturedDoctors(){
		$this->db->select('*');
		$this->db->from('registration');
		$this->db->join('doctor', 'registration.id = doctor.regId AND registration.isActive = "1" AND doctor.isFeatured = "1"');
		$this->db->order_by('registration.createdDate','DESC');
		$this->db->limit(5);
		$query = $this->db->get();
		
		if($query->num_rows()>0){
			return $query->result();
		}else{
			 return "No Data";
		}
	}
	
	/************************************* Get Home Page Doctors *******************************/
	function getHomeDoctors(){
		$this->db->select('*');
		$this->db->from('registration');
		$this->db->join('doctor', 'registration.id = doctor.regId AND registration.isActive = "1" AND doctor.isHome = "1"');
		$this->db->order_by('registration.createdDate','DESC');
		$query = $this->db->get();
		
		if($query->num_rows()>0){
			return $query->result();
		}else{
			 return "No Data";
		}
	}
	
	/************************************* Find Speciality Name *******************************/
	function findSpecialityName($id){
		$this->db->select('spName');
		$ans = $this->db->get_where("speciality",array('spId'=>$id));
		if($ans->num_rows()>0){
			 $final =  $ans->result();
			 return $final[0]->spName;
		}else{
			 return "no";
		 }
	}
	
	/************************************* Find Degree Name *******************************/
	function findDegreeName($id){
		$this->db->select('name');
		$ans = $this->db->get_where("degree",array('degreeId'=>$id));
		if($ans->num_rows()>0){
			 $final = $ans->result();
			 return $final[0]->name;
		}else{
			 return "no";
		 }
	}
	
	/************************************* Get Cities *******************************/
	function getAllCities(){
		$res = $this->db->get('cities');
		if($res->num_rows()>0){
			$cities = $res->result();
		} else{
			$cities=" ";
		}
		return $cities;
	}
	
	/************************************* Get Answer Count *******************************/
	function findAnswerCount($id){
		$res = $this->db->get_where('forum_answer',"postedBy='doc' AND userId = $id");
		if($res->num_rows()>0){
			return $res->num_rows();
		} else{
			return 0;
		}
	}
	
	/************************************* Get Feedback Count *******************************/
	function findFeedbackCount($id){
		$res = $this->db->get_where('doctor_feedback',"docId = $id");
		if($res->num_rows()>0){
			return $res->num_rows();
		} else{
			return 0;
		}
	}
	
	/************************************* Get Thanks Count *******************************/
	function findThanksCount($id){
		$total = 0;
		
		$this->db->select('blogId');
		$blog = $this->db->get_where('blogs',"postedBy = 'doc' AND userId = $id");
		$blogIdList = array();
		foreach($blog->result() as $row){
			 $blogIdList[] = $row->blogId;
		}
		$res = implode(",",$blogIdList);
		
		if($res !== ''){
			$blogThanks = $this->db->get_where('blog_likes',"blogId IN ($res)");
			if($blogThanks->num_rows()>0){
				$total += $blogThanks->num_rows();
			}
		}
		
		$this->db->select('mediaId');
		$media = $this->db->get_where('media',"postedBy = 'doc' AND userId = $id");
		$mediaIdList = array();
		foreach($media->result() as $row){
			 $mediaIdList[] = $row->mediaId;
		}
		$res = implode(",",$mediaIdList);
		
		if($res !== ''){
			$mediaThanks = $this->db->get_where('media_likes',"mediaId IN ($res)");
			if($mediaThanks->num_rows()>0){
				$total += $mediaThanks->num_rows();
			}
		}
		
		return $total;
	}
}