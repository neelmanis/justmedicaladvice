<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mdl_forum extends CI_Model{

	function __construct() {
		parent::__construct();
	}
	
	/******************************************* Forum Insert *******************************************/ 
	function insert($data){	
		 $this->db->insert('forum',$data);
		 return 1; 
	}
	
	/******************************************* Forum Update *******************************************/ 
		function update($data,$id){
		$this->db->where('forumId',$id);		
		$this->db->update("forum",$data);
		return 1;
	}
	
	/******************************************* Forum Delete *******************************************/ 
	function deleteById($id){
		$tables = array('forum', 'forum_answer', 'forum_answer_likes','forum_answer_dislikes');
		$this->db->where('forumId', $id);
		$this->db->delete($tables);		
	}
	
	/******************************************* Admin Forum Inactive *******************************************/ 
	function statusInactive($id){
		$this->db->where('forumId', $id);
        $ans=$this->db->update('forum',array("isActive"=> "0"));
		return $ans;
	}
	
	/******************************************* Admin Forum Active *******************************************/ 
	function statusActive($id){
		$this->db->where('forumId', $id);
        $ans=$this->db->update('forum',array("isActive"=> "1"));
		return $ans;
	}
	
	/******************************************* Forum Url Chcek *******************************************/ 
	function urlCheck($url){	
		$check = $this->db->get_where("forum",array('slug' => $url));
		if($check->num_rows() > 0){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	
	/******************************************* Find Forum By Id *******************************************/ 
	function findById($id){
		$other = $this->db->get_where("forum",array("forumId"=>$id));
		if($other->num_rows() > 0){
			$single = $other->result();
		}else{
			$single = "no";
		}	
		return $single;
	}
	
	/******************************************* Find Forum By Url *******************************************/
	function getForumByUrl($url){
		$result = $this->db->get_where('forum', array("slug"=>$url));
		
		if($result->num_rows() > 0){
			return $result->result();
		}else{
			return "no";
		}
	}
	
	/******************************************* Admin Forum Listing *******************************************/ 
	function listAll(){
		$this->db->select("*");
		$this->db->from("forum");
		$this->db->order_by("createdDate","DESC");
		$result=$this->db->get();
		if($result->num_rows() > 0){
			return $result->result();
		}else{
			return "no";
		}
	}
	
	/******************************************* Get Forums for Doctor Dashboard *******************************************/
	function getForumsForDocs($spec,$follow){
		if($follow !== ''){
			$query = $this->db->query("SELECT * FROM forum WHERE isActive = '1' AND specialityId IN($spec)
				UNION 
					SELECT * FROM forum WHERE isActive = '1' AND postedBy='mem' AND userId IN ($follow)
				UNION 
					SELECT * FROM forum WHERE isActive = '1' AND postedBy='admin'
					ORDER BY createdDate DESC LIMIT 5");
		}else{
			$query = $this->db->query("SELECT * FROM forum WHERE isActive = '1' AND specialityId IN($spec) UNION 
					SELECT * FROM forum WHERE isActive = '1' AND postedBy='admin' ORDER BY createdDate DESC LIMIT 5");
		}

		if($query->num_rows() > 0){
			return $query->result();
		}else{
			return "no";
		}
	}
	
	/******************************************* Forum for Doctor Profile *******************************************/
	function getForumByDoctorId($id,$start,$record){
		$this->db->select('*');
	    $this->db->from('forum');
		$this->db->where("isActive = '1' AND postedBy = 'doc' AND userId = $id");
		$this->db->order_by('createdDate','DESC');
		$this->db->limit($record,$start);
		$res = $this->db->get();
		
		if($res->num_rows() > 0)
	        return $res->result();
	    else
	       return "no";
	}
	
	/******************************************* Forum for Doctor Dashboard *******************************************/
	function doctorDashboard($spec, $follow, $start, $record){
		if($follow !== ''){
			$query = $this->db->query("SELECT * FROM forum WHERE isActive = '1' AND specialityId IN($spec)
				UNION 
					SELECT * FROM forum WHERE isActive = '1' AND postedBy='mem' AND userId IN ($follow) 
					ORDER BY createdDate DESC LIMIT $start,$record");
		}else{
			$query = $this->db->query("SELECT * FROM forum WHERE isActive = '1' AND specialityId IN($spec) ORDER BY createdDate DESC LIMIT $start,$record");
		}

		if($query->num_rows() > 0){
			return $query->result();
		}else{
			return "no";
		}
	}
	
	/******************************************* Forum Posted By Admin *******************************************/
	function getAdminPost($start,$record){
		$this->db->select('*');
	    $this->db->from('forum');
		$this->db->where("isActive = '1' AND postedBy = 'admin'");
		$this->db->order_by('createdDate','DESC');
		$this->db->limit($record,$start);
		$res = $this->db->get();
		
		if($res->num_rows() > 0)
	        return $res->result();
	    else
	       return "no";
	}
	
	/******************************************* Forum for Member Dashboard *******************************************/
	function memberDashboard($sp, $followers, $start, $record){
		
		if($followers !== ''){
			$query = $this->db->query("
			SELECT * FROM forum WHERE isActive = '1' AND postedBy = 'doc' AND userId IN($followers) AND visibleTo IN ('mem','all')
			UNION 
			SELECT * FROM forum WHERE isActive = '1' AND specialityId IN($sp) AND postedBy != 'admin' AND visibleTo IN ('mem','all') 
			UNION 
			SELECT * FROM forum WHERE isActive = '1' AND postedBy = 'admin' AND visibleTo IN ('mem','all')
			ORDER BY createdDate DESC LIMIT $start,$record");
		}else{
			$query = $this->db->query("
			SELECT * FROM forum WHERE isActive = '1' AND specialityId IN($sp) AND postedBy != 'admin' AND visibleTo IN ('mem','all') 
			UNION 
			SELECT * FROM forum WHERE isActive = '1' AND postedBy = 'admin' AND visibleTo IN ('mem','all')
			ORDER BY createdDate DESC LIMIT $start,$record");
		}
		
		if($query->num_rows() > 0)
	        return $query->result();
	    else
	       return "no";
	}
	
	/******************************************* Member Forum Listing *******************************************/
	function listForMembers($uid, $specialityList, $docs, $start, $limit){
		if($docs !== ''){
			$result = $this->db->query("
			SELECT * FROM forum WHERE isActive = '1' AND postedBy = 'mem' AND userId=$uid 
			UNION 
			SELECT * FROM forum WHERE isActive = '1' AND specialityId IN ($specialityList) AND visibleTo IN ('all','mem') 
			UNION 
			SELECT * FROM forum WHERE isActive = '1' AND postedBy = 'doc' AND userId IN ($docs) 
			UNION
			SELECT * FROM forum WHERE isActive = '1' AND postedBy = 'admin' AND visibleTo IN ('all','mem')
			UNION 
			SELECT * FROM forum WHERE isActive = '1' AND visibleTo IN ('all','mem')
			ORDER BY createdDate DESC LIMIT $start,$limit");
		}else{
			$result = $this->db->query("
			SELECT * FROM forum WHERE isActive = '1' AND postedBy = 'mem' AND userId=$uid 
			UNION 
			SELECT * FROM forum WHERE isActive = '1' AND specialityId IN ($specialityList) AND visibleTo IN ('all','mem')
			UNION
			SELECT * FROM forum WHERE isActive = '1' AND postedBy = 'admin' AND visibleTo IN ('all','mem')
			UNION 
			SELECT * FROM forum WHERE isActive = '1' AND visibleTo IN ('all','mem')
			ORDER BY createdDate DESC LIMIT $start,$limit");
		}
		
		if($result->num_rows() > 0){
			return $result->result();
		}else{
			return "no";
		}
	}
	
	/******************************************* Get Forums List By Speciality *******************************************/
	function getForumsBySpeciality($sp,$start,$limit){
		$this->db->select('*');
	    $this->db->from('speciality');
	    $this->db->join("forum","speciality.spId=forum.specialityId");
	    $this->db->where('speciality.isActive','1');
	    $this->db->where('forum.isActive','1');
	    $this->db->where_in('forum.visibleTo',array("mem","all"));
		$this->db->like('speciality.spSlug', $sp);
		$this->db->order_by('forum.createdDate','DESC');
		$this->db->limit($limit,$start);
	    $cat = $this->db->get();
		if($cat->num_rows() > 0)
	        return $cat->result();
	    else
	       return "no";
	}
	
	/******************************************* Search Forums By Category *******************************************/
	function getSpecialityBySearch($data){
		$this->db->distinct();
		$this->db->select('spName,spSlug');
	    $this->db->from('speciality');
	    $this->db->join("forum","speciality.spId=forum.specialityId");
	    $this->db->where('speciality.isActive','1');
	    $this->db->where('forum.isActive','1');
	    $this->db->where_in('forum.visibleTo',array("mem","all"));
		foreach($data as $key){
			$this->db->like('speciality.spName', $key);
		}
		$this->db->limit(4);
	    $sp = $this->db->get();
		if($sp->num_rows() > 0)
	        return $sp->result();
	    else
	       return "no";
	}	
	
	/******************************************* Search Forums By Title *******************************************/
	function getForumsBySearch($data){
		$this->db->distinct();
		$this->db->select('*');
	    $this->db->from('forum');
	    $this->db->where('isActive','1');
		$this->db->where_in('visibleTo',array("mem","all"));
		foreach($data as $key){
			$this->db->like('question', $key);
		}		
		$this->db->limit(4);
	    $forum = $this->db->get();
		if($forum->num_rows() > 0)
	        return $forum->result();
	    else
	       return "no";
	}
	
	/******************************************* Search Submit *******************************************/
	function getSearchSubmit($data,$start,$limit){
		$this->db->distinct();
		$this->db->select('*');
	    $this->db->from('blogs');
	    $this->db->where('isActive','1');
	    $this->db->where_in('visibleTo',array("mem","all"));
		foreach($data as $key){
			$this->db->like('title', $key);
		}		
		$this->db->order_by('createdDate','DESC');
		$this->db->limit($limit,$start);
	    $cat = $this->db->get();
		if($cat->num_rows() > 0)
	        return $cat->result();
	    else
	       return "no";
	}
	
	/******************************************* Get Suggested Forums *******************************************/
	function getsuggestedForums($sp, $type, $uid, $fid){
		$query = $this->db->query("
			SELECT * FROM forum WHERE forumId != $fid AND specialityId = $sp AND isActive = '1' AND visibleTo IN ('mem','all') 
		UNION 
			SELECT * FROM forum WHERE postedBy = '$type' AND userId = $uid AND isActive = '1' AND forumId != $fid AND visibleTo IN ('mem','all') 
		UNION 
			SELECT * FROM forum WHERE forumId != $fid AND postedBy = 'admin' AND isActive = '1' AND visibleTo IN ('mem','all') 
		order by createdDate DESC limit 4 ");
		
		//echo $this->db->last_query(); exit;
		if($query->num_rows() > 0){
			return $query->result();
		}else{
			return "no";
		}
	}
	
	/******************************************* Forum For Doctor Search *******************************************/
	function searchForum($data){
		$this->db->distinct();
		$this->db->select('*');
	    $this->db->from('forum');
	    $this->db->where('isActive','1');
	    $this->db->where_in('visibleTo',array("all","doc"));
		foreach($data as $key){
			$this->db->like('question', $key);
		}		
		$this->db->limit(5);
	    $cat = $this->db->get();
		if($cat->num_rows() > 0)
	        return $cat->result();
	    else
	       return "no";
	}
	
	/******************************************* Insert Answer *******************************************/ 
	function insert_answer($data){	
		$this->db->insert('forum_answer',$data);
		return $this->db->insert_id();
	}
	
	/******************************************* Answer Counts *******************************************/
	function getAnswerCount($id){
		$query = $this->db->query("SELECT * FROM forum_answer WHERE forumId = $id");
		return $query->num_rows();
	}
	
	/******************************************* Insert Like *******************************************/
	function insert_like($data){	
		$this->db->insert('forum_answer_likes',$data);
		return 1;
	}
	
	/******************************************* Check Weather User Liked the Answer*******************************************/
	function isLiked($aid,$uid,$type){
		$query = $this->db->query("SELECT * FROM forum_answer_likes WHERE answerId = $aid AND postedBy='$type' AND userId=$uid");
		if($query->num_rows() > 0){
			return 1;
		}else{
			return 0;
		}
	}
	
	/******************************************* Answer Like Counts *******************************************/
	function getLikeCount($id){
		$query = $this->db->query("SELECT * FROM forum_answer_likes WHERE answerId = $id");
		return $query->num_rows();		
	}
	
	/******************************************* Remove Like *******************************************/
	function removeLike($aid,$uid,$type){
		$this->db->where('answerId', $aid);
		$this->db->where('postedBy', $type);
		$this->db->where('userId', $uid);
		$this->db->delete('forum_answer_likes');
		return 1;
	}
	
	/******************************************* Insert Dislike *******************************************/
	function insert_dislike($data){	
		$this->db->insert('forum_answer_dislikes',$data);
		return 1;
	}
	
	/******************************************* Check Weather User Disliked the Answer*******************************************/
	function isDisliked($aid,$uid,$type){
		$query = $this->db->query("SELECT * FROM forum_answer_dislikes WHERE answerId = $aid AND postedBy='$type' AND userId=$uid");
		if($query->num_rows() > 0){
			return 1;
		}else{
			return 0;
		}
	}
	
	/******************************************* Answer Dislike Counts *******************************************/
	function getDisikeCount($id){
		$query = $this->db->query("SELECT * FROM forum_answer_dislikes WHERE answerId = $id");
		return $query->num_rows();		
	}
	
	/******************************************* Remove Dislike *******************************************/
	function removeDislike($aid,$uid,$type){
		$this->db->where('answerId', $aid);
		$this->db->where('postedBy', $type);
		$this->db->where('userId', $uid);
		$this->db->delete('forum_answer_dislikes');
		return 1;
	}
	
	/******************************************* Insert Answer Report *******************************************/
	function insert_report($data){	
		 $this->db->insert('forum_answer_report',$data);
		 return 1; 
	}
	
	/******************************************* Check Weather Answer is Reported *******************************************/
	function isReported($id){
		$query = $this->db->query("SELECT * FROM forum_answer_report WHERE answerId = $id");
		if($query->num_rows() > 0){
			return 1;
		}else{
			return 0;
		}
	}
	
	function removeReportedAns($aid){
		$this->db->where('answerId', $aid);
		$this->db->delete('forum_answer_report');
		return 1;
	}
	
	/******************************************* Get Reported Answer Id *******************************************/
	function getFlagAnswerId(){
		$query = $this->db->query("SELECT answerId FROM forum_answer_report order by createdDate desc");
		if($query->num_rows() > 0){
			return $query->result();
		}else{
			return "No Data";
		}
	}
	
	/******************************************* Get Reported Answer By Id *******************************************/
	function getAnswerById($id){
		$query = $this->db->query("SELECT * FROM forum_answer where answerId = $id");
		if($query->num_rows() > 0){
			return $query->result();
		}else{
			return "No Data";
		}
	}
	
	function inActiveAnswer($id){
		$this->db->where('answerId', $id);
        $ans=$this->db->update('forum_answer',array("isActive"=> "0"));
		return $ans;
	}
	
	function activeAnswer($id){
		$this->db->where('answerId', $id);
        $ans=$this->db->update('forum_answer',array("isActive"=> "1"));
		return $ans;
	}
	
    /******************************************* Get Comment Id	*******************************************/ 
	function tree_all($fid) {
        $query = $this->db->query("SELECT * FROM forum_answer where forumId = $fid AND isActive='1'");
		if($query->num_rows() > 0){
			$result = $query->result_array();
			foreach ($result as $row) {
				$data[] = $row;
			}
			return $data;
		}else{
			return "no";
		}
    }

	/******************************************* Get Child Comments	*******************************************/ 
    function tree_by_parent($fid,$in_parent) {
        $result = $this->db->query("SELECT * FROM forum_answer where parentId = $in_parent AND  forumId = $fid AND isActive='1' order by forumId DESC")->result_array();
        foreach ($result as $row) {
            $data[] = $row;
        }
        return $data;
    }
	
	/******************************************* Get Speciality List *******************************************/ 
	function getSpeciality(){
		$sp = $this->db->get_where("speciality", array('isActive'=>'1'));
		if($sp->num_rows()>0){
			 return $sp->result();
		}else{
			 return "no";
		 }
	}
	
	
	/******************************************* Find Category by Id *******************************************/ 
	function findCategoryId($id){
		$scat = $this->db->get_where("category_master", array('catId'=>$id));
		if($scat->num_rows()>0){
			 $res = $scat->result();
			 return $res[0]->parentCat;
		}else{
			 return "no";
		}
	}
	
	/******************************************* Find Speciality Name *******************************************/ 
	function findSpecialityName($id){
		$this->db->select('spName');
		$details = $this->db->get_where("speciality",array('spId'=>$id));
		if($details->num_rows() > 0){
			return $details->result();
		}else{
			return "no";
		}
	}
	
	/*************************************** Find Speciality ****************************************/ 
	function getSpecialityByCategoryId($cat){
		$this->db->select('specialities');
		$details = $this->db->get_where("category_master",array('catId'=>$cat));
		if($details->num_rows() > 0){
			$res = $details->result();
			return $res[0]->specialities;
		}else{
			return "No";
		}
	}
	
	/******************************************* Find User Name *******************************************/ 
	function findName($type,$id){
		if($type == 'admin'){
			$this->db->select('username');
			$details = $this->db->get_where("admin_master",array('id'=>$id));
			if($details->num_rows() > 0){
				$ans = $details->result();
				return $ans[0]->username;
			}else{
				return "No Data";
			}	
		}else{
			$this->db->select('name');
			$details = $this->db->get_where("registration",array('id'=>$id));
			if($details->num_rows() > 0){
				$ans = $details->result();
				return $ans[0]->name;
			}else{
				return "No Data";
			}
		}
	}

	/******************************************* Get Admin Details *******************************************/ 
	function getAdminDetails($id){
		$this->db->select('id,username,name,imageUrl');
		$result = $this->db->get_where('admin_master', array("id"=>$id));
		if($result->num_rows() > 0){
			return $result->result();
		}else{
			return "no";
		}
	}
	
	/******************************************* Get Doctor Details *******************************************/ 
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
	
	/******************************************* Get Member Details *******************************************/ 
	function getMemberDetails($id){
		$this->db->select('*');
		$this->db->from('registration');
		$this->db->join('member', 'registration.id = member.regId AND registration.id = '.$id);
		$query = $this->db->get();
		
		if($query->num_rows()>0){
			return $query->result();
		}else{
			 return "no";
		 }
	}
}