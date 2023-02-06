<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mdl_blog extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	
	/******************************************* Blog Insert *******************************************/ 
	function insert($data){	
		 $this->db->insert('blogs',$data);
		 return 1; 
	}
	
	/******************************************* Blog Update *******************************************/ 
		function update($data,$id){
		$this->db->where('blogId',$id);		
		$this->db->update("blogs",$data);
		return 1;
	}
	
	/******************************************* Blog Delete *******************************************/ 
	function deleteById($id){
		$tables = array('blogs', 'blog_likes', 'blog_comments', 'blog_report');
		$this->db->where('blogId', $id);
		$this->db->delete($tables);		
	}
	
	/******************************************* Admin Blog Inactive *******************************************/ 
	function statusInactive($id){
		$this->db->where('blogId', $id);
        $ans=$this->db->update('blogs',array("isActive"=> "0"));
		return $ans;
	}
	
	/******************************************* Admin Blog Active *******************************************/ 
	function statusActive($id){
		$this->db->where('blogId', $id);
        $ans=$this->db->update('blogs',array("isActive"=> "1"));
		return $ans;
	}
	
	/******************************************* Set Blog Home *******************************************/ 
	function setHome($id){
		$this->db->where('blogId', $id);
        $ans=$this->db->update('blogs',array("isHome"=> "1"));
		return $ans;
	}
	
	/******************************************* Unset Blog Home *******************************************/ 
	function unsetHome($id){
		$this->db->where('blogId', $id);
        $ans=$this->db->update('blogs',array("isHome"=> "0"));
		return $ans;
	}
	
	/******************************************* Count Blog Report *******************************************/
	function countReport($id){
		$query = $this->db->get_where("blog_report",array('blogId' => $id));
		return $query->num_rows();		
	}
	
	/******************************************* Blog Url Chcek *******************************************/ 
	function urlCheck($url){	
		$check = $this->db->get_where("blogs",array('slug' => $url));
		if($check->num_rows() > 0){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	
	/******************************************* Find By Id *******************************************/ 
	function findById($id){
		$other = $this->db->get_where("blogs",array("blogId"=>$id));
		if($other->num_rows() > 0){
			$single = $other->result();
		}else{
			$single = "no";
		}	
		return $single;
	}
	
	/******************************************* Find Blogs By Url *******************************************/
	function getBlogByUrl($url){
		$result = $this->db->get_where('blogs', array("slug"=>$url));
		if($result->num_rows() > 0){
			return $result->result();
		}else{
			return "no";
		}
	}
	
	/******************************************* Blogs for Home Page *******************************************/
	function getHomeBlogs(){
		$this->db->select("*");
		$this->db->from("blogs");
		$this->db->where("isHome",'1');
		$this->db->where("isActive",'1');
		$this->db->order_by('createdDate','DESC');
		$this->db->limit(5);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->result();
		}else{
			return "No Data";
		}
	}
	
	/******************************************* Admin Blog Listing *******************************************/ 
	function listAll(){
		$this->db->select("*");
		$this->db->from("blogs");
		$this->db->order_by('createdDate','DESC');
		$result=$this->db->get();
		if($result->num_rows() > 0){
			return $result->result();
		}else{
			return "No Data";
		}
	}
	
	/******************************************* Member Blog Listing *******************************************/
	function listForMembers($interest, $docs, $start, $limit){
		if($docs == ''){
			$query = $this->db->query(
					"SELECT * FROM blogs WHERE isActive = '1' AND visibleTo IN ('mem','all') AND categoryId IN($interest) 
				UNION
					SELECT * FROM blogs WHERE isActive = '1' AND visibleTo IN ('mem','all') AND postedBy = 'admin' 
				UNION 
					SELECT * FROM blogs WHERE isActive = '1' AND visibleTo IN ('mem','all') 
				ORDER BY createdDate DESC LIMIT $start,$limit");
		}else{
			$query = $this->db->query(
					"SELECT * FROM blogs WHERE isActive = '1' AND visibleTo IN ('mem','all') AND postedBy = 'doc' AND userId IN($docs) 
				UNION 
					SELECT * FROM blogs WHERE isActive = '1' AND visibleTo IN ('mem','all') AND categoryId IN($interest) 
				UNION
					SELECT * FROM blogs WHERE isActive = '1' AND visibleTo IN ('mem','all') AND postedBy = 'admin' 
				UNION 
					SELECT * FROM blogs WHERE isActive = '1' AND visibleTo IN ('mem','all')
				ORDER BY createdDate DESC LIMIT $start,$limit");
		}
		
		if($query->num_rows() > 0){
			return $query->result();
		}else{
			return "no";
		}
	}
	
	/******************************************* Search Submit *******************************************/
	function getSearchSubmit($data,$start,$limit){
		$this->db->distinct();
		$this->db->select('*');
	    $this->db->from('blogs');
	    $this->db->where('isActive','1');
		$this->db->where_in('visibleTo',array('all', 'mem'));
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
	
	/******************************************* Get Blogs List By Category *******************************************/
	function getBlogsByCategory($cat,$start,$limit){
		$this->db->select('*');
	    $this->db->from('category_master');
	    $this->db->join("blogs","category_master.catId=blogs.categoryId");
	    $this->db->where('category_master.isActive','1');
		$this->db->where_not_in('category_master.parentCat', array('0','1'));
	    $this->db->where('blogs.isActive','1');
	    $this->db->where_in('blogs.visibleTo',array('all', 'mem'));
		$this->db->like('category_master.catSlug', $cat);
		$this->db->order_by('blogs.createdDate','DESC');
		$this->db->limit($limit,$start);
	    $cat = $this->db->get();
		if($cat->num_rows() > 0)
	        return $cat->result();
	    else
	       return "no";
	}
	
	/******************************************* Get Suggested Blogs *******************************************/
	function getsuggestedBlogs($cat, $type, $uid, $bid){
		$query = $this->db->query("
			SELECT * FROM blogs WHERE blogId != $bid AND categoryId = $cat AND isActive = '1' AND visibleTo IN ('mem','all') 
		UNION 
			SELECT * FROM blogs WHERE postedBy = '$type' AND userId = $uid AND isActive = '1' AND blogId != $bid AND visibleTo IN ('mem','all') 
		UNION 
			SELECT * FROM blogs WHERE blogId != $bid AND postedBy = 'admin' AND isActive = '1' AND visibleTo IN ('mem','all') 
		order by createdDate DESC limit 4 ");
		
		if($query->num_rows() > 0){
			return $query->result();
		}else{
			return "no";
		}
	}
	
	/******************************************* Blogs for Member Dashboard *******************************************/
	function memberDashboard($cat, $followers, $start, $record){
		if($followers !== ''){
			$query = $this->db->query("
				SELECT * FROM blogs WHERE isActive = '1' AND postedBy = 'doc' AND userId IN($followers) AND visibleTo IN ('mem','all')
			UNION 
				SELECT * FROM blogs WHERE isActive = '1' AND categoryId IN($cat) AND postedBy != 'admin' AND visibleTo IN ('mem','all')
			UNION 
				SELECT * FROM blogs WHERE isActive = '1' AND visibleTo IN ('mem','all') AND postedBy = 'admin'
				ORDER BY createdDate DESC LIMIT $start,$record");
		}else{
			$query = $this->db->query("SELECT * FROM blogs WHERE isActive = '1' AND categoryId IN($cat) AND postedBy != 'admin' AND visibleTo IN ('mem','all') 
			UNION 
				SELECT * FROM blogs WHERE isActive = '1' AND visibleTo IN ('mem','all') AND postedBy = 'admin'
				ORDER BY createdDate DESC LIMIT $start,$record");
		}
		
		if($query->num_rows() > 0)
	        return $query->result();
	    else
	       return "no";
	}
	
	/******************************************* Blogs for Doctor Dashboard *******************************************/
	function doctorDashboard($start,$record){
		$this->db->select('*');
	    $this->db->from('blogs');
		$this->db->where("isActive = '1' AND postedBy = 'admin'");
		$this->db->where_in("visibleTo",array("all","doc"));
		$this->db->order_by('createdDate','DESC');
		$this->db->limit($record,$start);
		$res = $this->db->get();
		
		if($res->num_rows() > 0)
	        return $res->result();
	    else
	       return "no";
	}
	
	/******************************************* Blogs Posted By Admin *******************************************/
	function getAdminPost($start,$record){
		$this->db->select('*');
	    $this->db->from('blogs');
		$this->db->where("isActive = '1' AND postedBy = 'admin'");
		$this->db->where_in("visibleTo",array('mem','all'));
		$this->db->order_by('createdDate','DESC');
		$this->db->limit($record,$start);
		$res = $this->db->get();
		
		if($res->num_rows() > 0)
	        return $res->result();
	    else
	       return "no";
	}
	
	/******************************************* Blogs for Doctor Profile *******************************************/
	function getBlogByDoctorId($id,$start,$record){
		$this->db->select('*');
	    $this->db->from('blogs');
		$this->db->where("isActive = '1' AND postedBy = 'doc' AND userId = $id");
		$this->db->order_by('createdDate','DESC');
		$this->db->limit($record,$start);
		$res = $this->db->get();
		
		if($res->num_rows() > 0)
	        return $res->result();
	    else
	       return "no";
	}
	
	/******************************************* Search Blogs By Category *******************************************/
	function getCategoryBySearch($data){
		$this->db->distinct();
		$this->db->select('catName,catSlug');
	    $this->db->from('category_master');
	    $this->db->join("blogs","category_master.catId=blogs.categoryId");
	    $this->db->where('category_master.isActive','1');
		$this->db->where_not_in('category_master.parentCat', array('0','1'));
	    $this->db->where('blogs.isActive','1');
	    $this->db->where_in('blogs.visibleTo',array('mem','all'));
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
	
	/******************************************* Search Blogs By Title *******************************************/
	function getBlogBySearch($data){
		$this->db->distinct();
		$this->db->select('*');
	    $this->db->from('blogs');
	    $this->db->where('isActive','1');
		$names = array('all', 'mem');
		$this->db->where_in('visibleTo', $names);
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
	
	/******************************************* Search Blogs By Title *******************************************/
	function getBlogByTitle($data){
		$this->db->distinct();
		$this->db->select('*');
	    $this->db->from('blogs');
	    $this->db->where('isActive','1');
	    $this->db->where('postedBy','admin');
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
	
	/******************************************* Blogs For Doctor Search *******************************************/
	function searchBlogs($data){
		$this->db->distinct();
		$this->db->select('*');
	    $this->db->from('blogs');
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
	
	/******************************************* Insert Comment *******************************************/ 
	function insert_comment($data){	
		$this->db->insert('blog_comments',$data);
		return $this->db->insert_id();
	}
	
	/******************************************* Insert Like *******************************************/
	function insert_like($data){	
		$this->db->insert('blog_likes',$data);
		return 1;
	}
		
	/******************************************* Remove Like *******************************************/
	function deleteLike($uid,$utype,$bid){
		$this->db->where(array("userType"=>$utype, "userId"=>$uid, "blogId"=>$bid));
		$this->db->delete("blog_likes");
	}
	
	/******************************************* Check Weather User Liked the Blog*******************************************/
	function isLiked($bid,$type,$uid){
		$query = $this->db->query("SELECT * FROM blog_likes WHERE blogId = $bid AND userType='$type' AND userId=$uid");
		if($query->num_rows() > 0){
			return 1;
		}else{
			return 0;
		}
	}
	
	function insert_blog_report($data){
		$this->db->insert('blog_report',$data);
		return 1;
	}
	
	function isReport($bid,$type,$uid){
		$query = $this->db->query("SELECT * FROM blog_report WHERE blogId = $bid AND userType='$type' AND userId=$uid");
		if($query->num_rows() > 0){
			return 1;
		}else{
			return 0;
		}
	}
	
	/******************************************* Remove Like *******************************************/
	function deleteBlogReport($uid,$utype,$bid){
		$this->db->where(array("userType"=>$utype, "userId"=>$uid, "blogId"=>$bid));
		$this->db->delete("blog_report");
	}
	
	/******************************************* Like Counts *******************************************/
	function getLikeCount($id){
		$query = $this->db->query("SELECT * FROM blog_likes WHERE blogId = $id");
		return $query->num_rows();
	}
	
	/******************************************* Comment Counts *******************************************/
	function getCommentCount($id){
		$query = $this->db->query("SELECT * FROM blog_comments WHERE blogId = $id");
		return $query->num_rows();		
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
	
	/******************************************* Blog Comment Report *******************************************/ 
	function insert_report($data){	
		 $this->db->insert('blog_comment_report',$data);
		 return 1; 
	}
	
	function isReported($id){
		$query = $this->db->query("SELECT * FROM blog_comment_report WHERE commentId = $id");
		if($query->num_rows() > 0){
			return 1;
		}else{
			return 0;
		}
	}
	
	function getFlagCommentId(){
		$query = $this->db->query("SELECT commentId FROM blog_comment_report order by createdDate desc");
		if($query->num_rows() > 0){
			return $query->result();
		}else{
			return "No Data";
		}
	}
	
	function getCommentById($id){
		$query = $this->db->query("SELECT * FROM blog_comments where commentId = $id");
		if($query->num_rows() > 0){
			return $query->result();
		}else{
			return "No Data";
		}
	}
	
	function inActiveComment($id){
		$this->db->where('commentId', $id);
        $ans=$this->db->update('blog_comments',array("isActive"=> "0"));
		return $ans;
	}
	
	function activeComment($id){
		$this->db->where('commentId', $id);
        $ans=$this->db->update('blog_comments',array("isActive"=> "1"));
		return $ans;
	}
	
	function deleteCommentReport($cid){
		$this->db->where("commentId",$cid);
		$this->db->delete("blog_comment_report");
	}
	
    /******************************************* Get Comment Id	*******************************************/ 
	function tree_all($bid) {
        $query = $this->db->query("SELECT * FROM blog_comments where blogId = $bid AND  isActive='1'");
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
    function tree_by_parent($bid,$in_parent) {
        $result = $this->db->query("SELECT * FROM blog_comments where parentId = $in_parent AND  blogId = $bid AND isActive='1' order by commentId DESC")->result_array();
        foreach ($result as $row) {
            $data[] = $row;
        }
        return $data;
    }
	
	/******************************************* Find Sub-Category By Id *******************************************/ 
	function getSubCategory($id){
		$scat = $this->db->get_where("category_master", array('parentCat'=>$id, 'isActive'=>'1'));
		if($scat->num_rows()>0){
			 return $scat->result();
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

	/******************************************* Find Doctors Blog Count *******************************************/ 
	function getDoctorBlogCount($id){
		$count = $this->db->get_where("blogs",array('postedBy'=>'doc','userId'=>$id));
		if($count->num_rows() > 0){
			return $count->num_rows();
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