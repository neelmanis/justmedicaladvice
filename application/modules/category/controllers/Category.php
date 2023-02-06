<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Category extends MX_Controller{
	function __construct() {
		parent::__construct();
		$this->load->model('Mdl_category');	
		$this->load->model('speciality/Mdl_speciality');	
	}
	
	/************************************** Category Listing By FilterPage **************************************/
	function listByFilter($catid=""){
		$getCategories = $this->Mdl_category->listcategory($catid);
		$data['main'] = $this->Mdl_category->maincategory();
		$data['getAllCategories'] = $getCategories;
		$data['viewFile'] = "list-category";
		$data['page'] = 'listCategory';
		$data['catid']=$catid;
		$template = 'admin';
		echo Modules::run('template/'.$template, $data);
	}
	
	/**************************************  Add Category **************************************/
	function addCategory(){
		$getCategory = $this->Mdl_category->getMainCategories();
		$newArray=array(); 
		$i=0;
		foreach($getCategory as $val){
			$parent=$val->parentCat;
			$parentCat=$this->Mdl_category->catname($parent); 
	          
			$newArray[$i]['catId']=$val->catId;
			$newArray[$i]['catName']=$val->catName;
			$newArray[$i]['catSlug']=$val->catSlug;
			$newArray[$i]['parentCat']=$val->parentCat;
			$newArray[$i]['isMain']=$val->isMain;
			$newArray[$i]['parentCatName']=$parentCat;
			$newArray[$i]['isActive']=$val->isActive;
			$newArray[$i]['createdDate']=$val->createdDate;
			$newArray[$i]['modifiedDate']=$val->modifiedDate;
			$i++; 
		}
		$getSpeciality = $this->Mdl_speciality->getSpecialities();
		$data['speciality'] = $getSpeciality;
		$data['categories'] = $newArray;
		$data['viewFile'] = 'add-category';
		$data['page'] = 'addCategory';
		$template = 'admin';
		echo Modules::run('template/'.$template, $data);
	}

	/************************************** Add Category Action **************************************/
	function addCategoryAction(){
		$content = $this->input->post();
		
        $this->form_validation->set_rules("category_name","Category Name","required|xss_clean",
		array(
			'required'=>'<b>Category Name is Required</b>'
		));
		$this->form_validation->set_rules("parent_category","Parent Category","required|xss_clean",
		array(
			'required'=>'<b>Select Parent Category</b>'
		));
		$this->form_validation->set_rules("speciality[]","Speciality","required|xss_clean",
		array(
			'required'=>'<b>Select Specialities</b>'
		));
		
		if($this->form_validation->run() == FALSE){
			echo validation_errors();
		}else{
			
			$find = array("/","_","?","(",")","-",":","!","'",".",",","\r","\n","\r\n");
			$replace = array("");
			$new_string = str_replace($find,$replace,strtolower($content['category_name']));
			$new_string = preg_replace('/^\s+|\s+$|\s+(?=\s)/','',$new_string); 
			$urlKey = str_replace(" ","-",trim($new_string));

			$urlCheck = $this->Mdl_category->urlcheck($urlKey);
			
			if($urlCheck == 1){  
				$data = array(
					'catName' => $content['category_name'],
					'parentCat' => $content['parent_category'],
					'catSlug' => $urlKey,
					'isActive' => '1',
					'isMain' => '0',
					'specialities' => implode(",",$_POST['speciality']),
					'adminId' => $this->session->userId,
					'createdDate' =>date('Y-m-d H:i:s'),
					'modifiedDate' =>date('Y-m-d H:i:s')
				);
						
				$categoryinsert = $this->Mdl_category->insert($data);
				echo "1"; exit;
			}else {
				echo 2;
			}
		}
	}
	
	/************************************** Edit Category **************************************/
	function editCategory($id){
		$base_64 = $id . str_repeat('=', strlen($id) % 4);
		$catId = base64_decode($base_64);
		$data['getData'] = $this->Mdl_category->findCategoryById($catId);
		$getCategory = $this->Mdl_category->getMainCategories();
		$data['categories'] =$getCategory;
		$getSpeciality = $this->Mdl_speciality->getSpecialities();
		$data['speciality'] = $getSpeciality;
		$data['viewFile'] = "edit-category";
		$data['page'] = "editCategory";
		$template = 'admin';
		echo Modules::run('template/'.$template, $data);
	}	
	
	/************************************** Edit Category Action **************************************/
	function editCategoryAction(){
		$content = $this->input->post();

		$this->form_validation->set_rules("category_name","Category Name","required|xss_clean",
		array(
			'required'=>'<b>Category Name is Required<b>'
		));
		$this->form_validation->set_rules("parent_category","Parent Category","required|xss_clean",
		array(
			'required'=>'<b>Select parent category<b>'
		));
		$this->form_validation->set_rules("speciality[]","Speciality","required|xss_clean",
		array(
			'required'=>'<b>Select Specialities<b>'
		));
		$this->form_validation->set_rules("category_status","Category Status","required|xss_clean",
		array(
			'required'=>'<b>Select Status is Required<b>'
		));
	
		if($this->form_validation->run() == FALSE){
			echo validation_errors();
		}else{
			$find = array("/","_","?","(",")","-",":","!","'",".",",","\r","\n","\r\n");
			$replace = array("");
			$new_string = str_replace($find,$replace,strtolower($content['category_name']));
			$new_string = preg_replace('/^\s+|\s+$|\s+(?=\s)/','',$new_string); 
			$urlKey = str_replace(" ","-",trim($new_string));
			
			if($urlKey !== $content['catSlug']){
				$urlCheck = $this->Mdl_category->urlcheck($urlKey);
			}else{
				$urlCheck = 1;
			}
			
			if($urlCheck == 1){  
				$data = array(
					'catName' => $content['category_name'],
					'parentCat' => $content['parent_category'],
					'catSlug' => $urlKey,
					'isActive' => $content['category_status'],
					'isMain' => '0',
					'specialities' => implode(",",$content['speciality']),
					'adminId' => $this->session->userId,
					'modifiedDate' =>date('Y-m-d H:i:s')
				);
						
				$categoryupdate = $this->Mdl_category->update($data,$content['catId']);
				echo "1"; exit;
			} else {
				echo 2;
			}
		}
	}
	
	/************************************** Get Category Name **************************************/
	function getCategoryName($id){
	   $getCategories = $this->Mdl_category->catname($id);
	   echo  $getCategories;
    }
	
	/***************************************** Inactive Category Action ***************************************/
	function inActiveAction($id){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$spId = base64_decode($base_64);
			$aid = $this->session->userId;
			$this->Mdl_category->statusInactive($spId,$aid);
			redirect('category/listByFilter/1');		
		}
	}
	
	/***************************************** Active Speciality Action ***************************************/
	function activeAction($id){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$spId = base64_decode($base_64);
			$aid = $this->session->userId;
			$this->Mdl_category->statusActive($spId,$aid);
			redirect('category/listByFilter/1');	
		}
	}
	
	/************************************** Delete Category **************************************/
	function deleteCategory(){
		$cid = $_POST['Category_id'];
		$base_64 = $cid . str_repeat('=', strlen($cid) % 4);
		$catId = base64_decode($base_64);
		$delete_cat = $this->Mdl_category->category_delete($catId);
		echo $delete_cat;
	}	
}