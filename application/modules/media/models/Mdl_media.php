<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mdl_media extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	
	/******************************************* Media Insert *******************************************/ 
	function insert($data){	
		 $this->db->insert('media',$data);
		 return 1; 
	}
	
	/******************************************* Media Update *******************************************/ 
		function update($data,$id){
		$this->db->where('mediaId',$id);		
		$this->db->update("media",$data);
		return 1;
	}
	
	/******************************************* Media Delete *******************************************/ 
	function deleteById($id){
		$tables = array('media', 'media_likes', 'media_comments');
		$this->db->where('mediaId', $id);
		$this->db->delete($tables);	
	}
	
	/******************************************* Admin Media Inactive *******************************************/ 
	function statusInactive($id){
		$this->db->where('mediaId', $id);
        $ans=$this->db->update('media',array("isActive"=> "0"));
		return $ans;
	}
	
	/******************************************* Admin Media Active *******************************************/ 
	function statusActive($id){
		$this->db->where('mediaId', $id);
        $ans=$this->db->update('media',array("isActive"=> "1"));
		return $ans;
	}
	
	/******************************************* Set Media Home *******************************************/ 
	function setHome($id){
		$this->db->where('mediaId', $id);
        $ans=$this->db->update('media',array("isHome"=> "1"));
		return $ans;
	}
	
	/******************************************* Unset Blog Home *******************************************/ 
	function unsetHome($id){
		$this->db->where('mediaId', $id);
        $ans=$this->db->update('media',array("isHome"=> "0"));
		return $ans;
	}
	
	/******************************************* Count Media Report *******************************************/
	function countReport($id){
		$query = $this->db->get_where("media_report",array('mediaId' => $id));
		return $query->num_rows();		
	}
	
	/******************************************* Media Url Chcek *******************************************/ 
	function urlCheck($url){	
		$check = $this->db->get_where("media",array('slug' => $url));
		if($check->num_rows() > 0){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	
	/******************************************* Find By Id *******************************************/ 
	function findById($id){
		$other = $this->db->get_where("media",array("mediaId"=>$id));
		if($other->num_rows() > 0){
			$single = $other->result();
		}else{
			$single = "no";
		}	
		return $single;
	}
	
	/******************************************* Find Media By Url *******************************************/
	function getMediaByUrl($url){
		$result = $this->db->get_where('media', array("slug"=>$url));
		if($result->num_rows() > 0){
			return $result->result();
		}else{
			return "no";
		}
	}
	
	/******************************************* Get Home Page Media *******************************************/ 
	function getHomeMedia(){
		$this->db->select("*");
		$this->db->from("media");
		$this->db->where(array("isActive"=>"1","isHome"=>"1"));
		$this->db->order_by('createdDate','DESC');
		$result=$this->db->get();
		if($result->num_rows() > 0){
			return $result->result();
		}else{
			return "no";
		}
	}
	
	/******************************************* Admin Media Listing *******************************************/ 
	function listAll(){
		$this->db->select("*");
		$this->db->from("media");
		$this->db->order_by('createdDate','DESC');
		$result=$this->db->get();
		if($result->num_rows() > 0){
			return $result->result();
		}else{
			return "no";
		}
	}
	
	/******************************************* Member Media Listing *******************************************/
	function listForMembers($interest, $docs, $start, $limit){
		
		if($docs !== ''){
			$query = $this->db->query("SELECT * FROM media WHERE isActive = '1' AND postedBy = 'doc' AND userId IN($docs) AND visibleTo IN ('mem','all') UNION 
				  SELECT * FROM media WHERE isActive = '1' AND categoryId IN($interest) AND visibleTo IN ('mem','all') UNION
				  SELECT * FROM media WHERE isActive = '1' AND postedBy = 'admin' AND visibleTo IN ('mem','all') ORDER BY createdDate DESC LIMIT $start,$limit");
		}else{
			$query = $this->db->query("SELECT * FROM media WHERE isActive = '1' AND categoryId IN($interest) AND visibleTo IN ('mem','all') UNION
				  SELECT * FROM media WHERE isActive = '1' AND postedBy = 'admin' AND visibleTo IN ('mem','all') ORDER BY createdDate DESC LIMIT $start,$limit");
		}
		
		if($query->num_rows() > 0){
			return $query->result();
		}else{
			return "no";
		}
	}
	
	/******************************************* Media for Doctor Dashboard *******************************************/
	function doctorDashboard($start,$record){
		$this->db->select('*');
	    $this->db->from('media');
		$this->db->where("isActive = '1' AND postedBy = 'admin'");
		$this->db->order_by('createdDate','DESC');
		$this->db->limit($record,$start);
		$res = $this->db->get();
		if($res->num_rows() > 0)
	        return $res->result();
	    else
	       return "no";
	}
	
	/******************************************* Media Posted By Admin *******************************************/
	function getAdminPost($start,$record){
		$this->db->select('*');
	    $this->db->from('media');
		$this->db->where("isActive = '1' AND postedBy = 'admin'");
		$this->db->order_by('createdDate','DESC');
		$this->db->limit($record,$start);
		$res = $this->db->get();
		
		if($res->num_rows() > 0)
	        return $res->result();
	    else
	       return "no";
	}
	
	/******************************************* Media for Member Dashboard *******************************************/
	function memberDashboard($cat, $followers, $start, $record){
		if($followers !== ''){
			$query = $this->db->query("
			SELECT * FROM media WHERE isActive = 1 AND postedBy = 'doc' AND userId IN($followers) AND visibleTo IN ('mem','all') 
			UNION 
			SELECT * FROM media WHERE isActive = 1 AND categoryId IN($cat) AND postedBy != 'admin' AND visibleTo IN ('mem','all') 
			UNION 
			SELECT * FROM media WHERE isActive = '1' AND visibleTo IN ('mem','all') AND postedBy = 'admin'
			ORDER BY createdDate DESC LIMIT $start,$record");
		}else{
			$query = $this->db->query("
			SELECT * FROM media WHERE isActive = 1 AND categoryId IN($cat) AND postedBy != 'admin' AND visibleTo IN ('mem','all') 
			UNION 
			SELECT * FROM media WHERE isActive = '1' AND visibleTo IN ('mem','all') AND postedBy = 'admin'
			ORDER BY createdDate DESC LIMIT $start,$record");
		}
		
		if($query->num_rows() > 0)
	        return $query->result();
	    else
	       return "no";
	}
	
	/******************************************* Media for Doctor Profile *******************************************/
	function getMediaByDoctorId($id,$start,$record){
		$this->db->select('*');
	    $this->db->from('media');
		$this->db->where("isActive = '1' AND postedBy = 'doc' AND userId = $id");
		$this->db->order_by('createdDate','DESC');
		$this->db->limit($record,$start);
		$res = $this->db->get();
		
		if($res->num_rows() > 0)
	        return $res->result();
	    else
	       return "no";
	}

	/******************************************* Get Media List By Category *******************************************/
	function getMediaByCategory($cat,$start,$limit){
		$this->db->select('*');
	    $this->db->from('category_master');
	    $this->db->join("media","category_master.catId=media.categoryId");
	    $this->db->where('category_master.isActive','1');
		$this->db->where_not_in('category_master.parentCat', array('0','1'));
	    $this->db->where_in('media.visibleTo',array('mem','all'));
	    $this->db->where('media.isActive','1');
		$this->db->like('category_master.catSlug', $cat);
		$this->db->order_by('media.createdDate','DESC');
		$this->db->limit($limit,$start);
	    $cat = $this->db->get();
		if($cat->num_rows() > 0)
	        return $cat->result();
	    else
	       return "no";
	}
	
	/******************************************* Get Suggested Media *******************************************/
	function getsuggestedMedia($cat, $type, $uid, $mid){
		$query = $this->db->query("SELECT * FROM media WHERE mediaId != $mid AND categoryId = $cat AND isActive = '1' AND visibleTo IN ('mem','all') UNION SELECT * FROM media WHERE postedBy = '$type' AND userId = $uid AND isActive = '1' AND mediaId != $mid AND visibleTo IN ('mem','all') UNION SELECT * FROM media WHERE mediaId != $mid AND postedBy = 'admin' AND isActive = '1' AND visibleTo IN ('mem','all') order by createdDate DESC limit 4");
		
		if($query->num_rows() > 0){
			return $query->result();
		}else{
			return "no";
		}
	}
	
	/******************************************* Member Search Media By Category *******************************************/
	function getCategoryBySearch($data){
		$this->db->distinct();
		$this->db->select('catName,catSlug');
	    $this->db->from('category_master');
	    $this->db->join("media","category_master.catId=media.categoryId");
	    $this->db->where('category_master.isActive','1');
		$this->db->where_not_in('category_master.parentCat', array('0','1'));
	    $this->db->where('media.isActive','1');
	    $this->db->where_in('media.visibleTo',array("all","mem"));
		foreach($data as $key){
			$this->db->like('category_master.catName', $key);
		}
		$this->db->limit(4);
	    $cat = $this->db->get();
		if($cat->num_rows() > 0)
	        return $cat->result();
	    else
	       return "no";
	}	
	
	/******************************************* Search Media By Title *******************************************/
	function getMediaBySearch($data){
		$this->db->distinct();
		$this->db->select('*');
	    $this->db->from('media');
	    $this->db->where('isActive','1');
		foreach($data as $key){
			$this->db->like('title', $key);
		}		
		$this->db->limit(5);
	    $cat = $this->db->get();
		if($cat->num_rows() > 0)
	        return $cat->result();
	    else
	       return "no";
	}
	
	/******************************************* Member Search Video By Title *******************************************/
	function getVideoBySearch($data){
		$this->db->distinct();
		$this->db->select('*');
	    $this->db->from('media');
	    $this->db->where('isActive','1');
	    $this->db->where('mtype','video');
	    $this->db->where_in('visibleTo',array('all','mem'));
		foreach($data as $key){
			$this->db->like('title', $key);
		}		
		$this->db->limit(3);
	    $cat = $this->db->get();
		
		if($cat->num_rows() > 0)
	        return $cat->result();
	    else
	       return "no";
	}
	
	/******************************************* Member Search Audio By Title *******************************************/
	function getAudioBySearch($data){
		$this->db->distinct();
		$this->db->select('*');
	    $this->db->from('media');
	    $this->db->where('isActive','1');
	    $this->db->where('mtype','audio');
	    $this->db->where_in('visibleTo',array('all','mem'));
		foreach($data as $key){
			$this->db->like('title', $key);
		}		
		$this->db->limit(3);
	    $cat = $this->db->get();
		if($cat->num_rows() > 0)
	        return $cat->result();
	    else
	       return "no";
	}
	
	/******************************************* Search Submit *******************************************/
	function getSearchSubmit($data,$start,$limit){
		$this->db->distinct();
		$this->db->select('*');
	    $this->db->from('media');
	    $this->db->where('isActive','1');
	    $this->db->where_in('visibleTo',array('all','mem'));
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
	
	/******************************************* Media For Doctor Search *******************************************/
	function searchMedia($data){
		$this->db->distinct();
		$this->db->select('*');
	    $this->db->from('media');
	    $this->db->where('isActive','1');
	    $this->db->where('postedBy','admin');
	    $this->db->where_in('visibleTo',array("all","doc"));
		foreach($data as $key){
			$this->db->like('title', $key);
		}		
		$this->db->limit(5);
	    $cat = $this->db->get();
		if($cat->num_rows() > 0)
	        return $cat->result();
	    else
	       return "no";
	}
	
	/******************************************* Find Doctors Video Count *******************************************/ 
	function getDoctorMediaCount($id){
		$count = $this->db->get_where("media",array('postedBy'=>'doc','userId'=>$id));
		if($count->num_rows() > 0){
			return $count->num_rows();
		}else{
			return "no";
		}
	}
	
	/******************************************* Insert Comment *******************************************/ 
	function insert_comment($data){	
		$this->db->insert('media_comments',$data);
		return $this->db->insert_id();
	}
	
	/******************************************* Comment Counts *******************************************/
	function getCommentCount($id){
		$query = $this->db->query("SELECT * FROM media_comments WHERE mediaId = $id");
		return $query->num_rows();		
	}
	
	/******************************************* Get Comment By Id	*******************************************/
	function getCommentById($id){
		$query = $this->db->query("SELECT * FROM media_comments where commentId = $id");
		if($query->num_rows() > 0){
			return $query->result();
		}else{
			return "No Data";
		}
	}
	
	/******************************************* Inactive Comment *******************************************/
	function inActiveComment($id){
		$this->db->where('commentId', $id);
        $ans=$this->db->update('media_comments',array("isActive"=> "0"));
		return $ans;
	}
	
	/******************************************* Active Comment *******************************************/
	function activeComment($id){
		$this->db->where('commentId', $id);
        $ans=$this->db->update('media_comments',array("isActive"=> "1"));
		return $ans;
	}
	
	/******************************************* Get Comment Id	*******************************************/ 
	function tree_all($mid) {
        $query = $this->db->query("SELECT * FROM media_comments where mediaId = $mid AND isActive='1'");
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
    function tree_by_parent($mid,$in_parent) {
        $result = $this->db->query("SELECT * FROM media_comments where parentId = $in_parent AND  mediaId = $mid AND isActive='1' order by commentId DESC")->result_array();
        foreach ($result as $row) {
            $data[] = $row;
        }
        return $data;
    }
	
	/******************************************* Insert Comment Report *******************************************/
	function insert_report($data){	
		 $this->db->insert('media_comment_report',$data);
		 return 1; 
	}
	
	/******************************************* Check Comment Report *******************************************/
	function isReported($id){
		$query = $this->db->query("SELECT * FROM media_comment_report WHERE commentId = $id");
		if($query->num_rows() > 0){
			return 1;
		}else{
			return 0;
		}
	}
	
	/******************************************* Delete Comment Report	*******************************************/
	function deleteCommentReport($cid){
		$this->db->where("commentId",$cid);
		$this->db->delete("media_comment_report");
	}
	
	/******************************************* Get Flag Comment Ids *******************************************/
	function getFlagCommentId(){
		$query = $this->db->query("SELECT commentId FROM media_comment_report order by createdDate desc");
		if($query->num_rows() > 0){
			return $query->result();
		}else{
			return "No Data";
		}
	}
	
	/******************************************* Insert Like *******************************************/
	function insert_like($data){	
		$this->db->insert('media_likes',$data);
		return 1;
	}
	
	/****************************************** Check Media Like ******************************************/
	function isLiked($mid,$type,$uid){
		$query = $this->db->query("SELECT * FROM media_likes WHERE mediaId = $mid AND userType='$type' AND userId=$uid");
		if($query->num_rows() > 0){
			return 1;
		}else{
			return 0;
		}
	}
	
	/******************************************* Like Counts *******************************************/
	function getLikeCount($id){
		$query = $this->db->query("SELECT * FROM media_likes WHERE mediaId = $id");
		return $query->num_rows();
	}
	
	/******************************************* Remove Like *******************************************/
	function deleteLike($uid,$utype,$mid){
		$this->db->where(array("userType"=>$utype, "userId"=>$uid, "mediaId"=>$mid));
		$this->db->delete("media_likes");
	}
	
	/******************************************* Insert media Report *******************************************/
	function insert_media_report($data){
		$this->db->insert('media_report',$data);
		return 1;
	}
	
	/******************************************* Check For Report *******************************************/
	function isReport($mid,$type,$uid){
		$query = $this->db->query("SELECT * FROM media_report WHERE mediaId = $mid AND userType='$type' AND userId=$uid");
		if($query->num_rows() > 0){
			return 1;
		}else{
			return 0;
		}
	}
	
	/******************************************* Remove Media Report *******************************************/
	function deleteMediaReport($uid,$utype,$mid){
		$this->db->where(array("userType"=>$utype, "userId"=>$uid, "mediaId"=>$mid));
		$this->db->delete("media_report");
	}
	
	/******************************************* Get Category List *******************************************/ 
	function getCategory(){
		$cat = $this->db->get_where("category_master", array('isMain'=>'1'));
		if($cat->num_rows()>0){
			 return $cat->result();
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
	
	/******************************************* Find Category Name *******************************************/ 
	function findCategoryName($id){
		$this->db->select('catName');
		$details = $this->db->get_where("category_master",array('catId'=>$id));
		if($details->num_rows() > 0){
			return $details->result();
		}else{
			return "no";
		}
	}
	
	/******************************************* Find Sub-Category By Id *******************************************/ 
	function getSubCategory($id){
		$scat = $this->db->get_where("category_master", array('parentCat'=>$id,'isActive'=>'1'));
		if($scat->num_rows()>0){
			 return $scat->result();
		}else{
			 return "no";
		}
	}
	
	/******************************************* Find User Name *******************************************/ 
	function findName($type,$id){
		if($type == 'doc'){
			$this->db->select('name');
			$details = $this->db->get_where("registration",array('id'=>$id));
			if($details->num_rows() > 0){
				$ans = $details->result();
				return $ans[0]->name;
			}else{
				return "No Data";
			}	
		}else{
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

	/******************************************* Get Admin Details *******************************************/ 
	function getAdminDetails($id){
		$this->db->select('id,username');
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