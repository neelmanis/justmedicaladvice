<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mdl_category extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	/************************************** Inset Query **************************************/
	function insert($data){	
		$this->db->insert('category_master',$data);
		return 1;
	}
	
	/************************************** Update Query **************************************/
	function update($data,$id){
		$this->db->where('catId',$id);		
		$this->db->update("category_master",$data);
		return 1;
	}
	
	/************************************** Delete Query **************************************/
	function category_delete($id){
		$this->db->where('catId',$id);		
		$this->db->delete("category_master");
		return 1;
	}
	
	/************************************** Get All Categories **************************************/
	function listcategory($catid){
		 $categoryView =$this->db->get_where("category_master",array('parentCat'=>$catid));
		if($categoryView->num_rows() > 0){
			$getCategory = $categoryView->result();
		}else{
			$getCategory = "no";
		}	
		return $getCategory;
	}
	
	/************************************** Get All Parent Categories **************************************/
	function maincategory(){
		$this->db->select('*');
        $this->db->from("category_master as a"); 
        $this->db->join("category_master as b","a.parentCat=b.catId"); 
        $this->db->group_by('a.parentCat');  
        $categoryView = $this->db->get();
            
		if($categoryView->num_rows() > 0){
			$getCategory = $categoryView->result();
		}else{
			$getCategory = "no";
		}	
		return $getCategory;	
    }
	
	/************************************** Get Sub Categories **************************************/
	function getSubCategory($catid){
		$categoryView =$this->db->get_where("category_master",array('parentCat'=>$catid,'isActive'=>'1'));
		if($categoryView->num_rows() > 0){
			$getCategory = $categoryView->result();
		}else{
			$getCategory = "no";
		}	
		return $getCategory;
	}
	
	/************************************** Get Categories Under Main Category **************************************/
	function getMainCategory(){
		$categoryView = $this->db->get_where("category_master","isActive = '1' AND parentCat='1'");
		if($categoryView->num_rows() > 0){
			$getCategory = $categoryView->result();
		}else{
			$getCategory = "no";
		}	
		return $getCategory;
	}

	/************************************** Get All Main Category **************************************/
	function getMainCategories(){
		$categoryView = $this->db->get_where("category_master","isActive = '1' AND parentCat IN(1)");
		if($categoryView->num_rows() > 0){
			$getCategory = $categoryView->result();
		}else{
			$getCategory = "no";
		}	
		return $getCategory;
	}
	
	/************************************** Get Category Name **************************************/
	function catname($id){
		$categoryView = $this->db->get_where("category_master",array('catId' =>$id));
		if($categoryView->num_rows() > 0){
			$getCategory = $categoryView->result();
			$getCategory =  $getCategory[0]->catName;
		}else{
			$getCategory = "No Data";
		}	
		return $getCategory;
	}
	
	/************************************** Check Slug Url **************************************/
	function urlcheck($url){	
		$check = $this->db->get_where("category_master",array('catSlug' => $url));
		if($check->num_rows() > 0)
			return 2;
		else 
			return 1;
	}
	
	/************************************** Find Category By Id **************************************/
	function findCategoryById($id){
		$categorySingle = $this->db->get_where("category_master","catId = $id");
		if($categorySingle->num_rows() > 0){
			$singleCategory = $categorySingle->result();
		}else{
			$singleCategory = "no";
		}	
		return $singleCategory;
	}
	
	/************************************** Status Inactive **************************************/
	function statusInactive($id,$aid){
		$this->db->where('catId', $id);
		return $this->db->update("category_master", array('isActive'=>'0','adminId'=>$aid));
	}
	
	/************************************** Status Active **************************************/
	function statusActive($id,$aid){
		$this->db->where('catId', $id);
		return $this->db->update("category_master", array('isActive'=>'1','adminId'=>$aid));
	}
}