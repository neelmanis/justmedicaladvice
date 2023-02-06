<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Banner extends MX_Controller{
	
	function __construct() {
		parent::__construct();
		$this->load->model('Mdl_banner');	
	}
	
	/******************************************* Admin Banner Listing *******************************************/
	function listBanner(){
		$banner = $this-> Mdl_banner->listAll();
		$bannerData = array();
		if($banner !== "no"){
			foreach($banner as $val){
				$res = array();
				$id = base64_encode($val->bannerId);
				$id = str_replace(str_repeat('=',strlen($id)/4),"",$id);
				$res['id'] = $id;
				$res['title'] = $val->title;
				$res['image'] = $val->image;
				$res['addedBy'] = $this->Mdl_banner->findName($val->addedBy);
				$res['status'] = $val->isActive;
				$res['createdDate'] = $val->createdDate;
				$bannerData[] = $res;
			}
			$data['bannerList'] = $bannerData;
		}else{
			$data['bannerList'] = "No Data";
		}
		$data['viewFile'] = "list-banner";
		$data['page'] = 'listBanner';
		$template = 'admin';
		echo Modules::run('template/'.$template, $data);
	}
	
	/***************************************** Active Banner Action ***************************************/
	function active_action($id){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$bid = base64_decode($base_64);
			$this->Mdl_banner->statusActive($bid);
			redirect('banner/listBanner');	
		}
	}
	
	/***************************************** Inactive Banner Action ***************************************/
	function inActive_action($id){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$bid = base64_decode($base_64);
			$this->Mdl_banner->statusInactive($bid);
			redirect('banner/listBanner');	
		}
	}
	
	/***************************************** Delete Banner ***************************************/
	function deleteBanner(){
		$bid = $_POST['bannerId'];
		$base_64 = $bid . str_repeat('=', strlen($bid) % 4);
		$bId = base64_decode($base_64);
		$delete = $this->Mdl_banner->deleteById($bId);
		echo $delete;
	}
	
	/***************************************** Admin Add Banner ***************************************/
	function addBanner(){
		$data['viewFile'] = 'add-banner';
		$data['page'] = 'addBanner';
		$template = 'admin';
		echo Modules::run('template/'.$template, $data);
	}
	
	/***************************************** Admin Add Banner Action ***************************************/
	function addBannerAction(){
		$content = $this->input->post();
		
        $this->form_validation->set_rules("ctype","Content Type","required|xss_clean",array(
					'required'      => '<b>Content Type is not selected.</b>'
		));
		$this->form_validation->set_rules("title","Title","required|xss_clean",array(
					'required'      => '<b>Title required.</b>'
		));
		$this->form_validation->set_rules("url","Url","required|xss_clean",array(
					'required'      => '<b>Url required.</b>'
		));
		$this->form_validation->set_rules("content","Description","xss_clean");
		$this->form_validation->set_rules('visible[]', 'Visible', 'required|xss_clean',array(
					'required'      => '<b>Visible type is not selected.</b>'
		));

		if($this->form_validation->run() == FALSE){
			echo validation_errors();
		}else{
			
			if(!empty($_FILES['image']['name'])){
				$blogimg = $_FILES['image']['name'];
				$imgname=str_replace(array(" ","'"),"_",time().$blogimg);
				$img = $this->uploadFile($imgname,"image","banner");	
				if($img !== 1){
					echo $img; exit;
				}
			}else{
				echo "<b>Image required.</b>"; exit;
			}
				
			if(in_Array("1",$content['visible'])){
				$home =  '1';
			}else{
				$home = '0';
			}
			
			if(in_Array("2",$content['visible'])){
				$mem = '1';
			}else{
				$mem = '0';
			}
			
			if(in_Array("3",$content['visible'])){
				$doc = '1';
			}else{
				$doc = '0';
			}
			
			$data = array(
				'title' => $content['title'],
				'url' => $content['url'],
				'content'=> $content['content'],
				'image'=> 'admin_assets/images/banner/'.$imgname,
				'addedBy' => $this->session->userdata('userId'),
				'home' => $home,
				'memDash' => $mem,
				'docDash' => $doc,
				'ctype' => $content['ctype'],
				'isActive' => '1',
				'createdDate' =>date('Y-m-d H:i:s'),
				'modifiedDate' =>date('Y-m-d H:i:s')
			);
				
			$insert = $this->Mdl_banner->insert($data);
			echo 1; exit;
		}
	}
	
	/***************************************** Admin Edit Blog ***************************************/
	function editBanner($id){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$bid = base64_decode($base_64);
			$banner = $this->Mdl_banner->findById($bid);
			$data['getData'] = $banner;
			$data['viewFile'] = "edit-banner";
			$data['page'] = "editBanner";
			$template = 'admin';
			echo Modules::run('template/'.$template, $data);		
		}
	}	
	
	/***************************************** Admin Edit Banner Action ***************************************/
	function editBannerAction(){
		$content = $this->input->post();
		
		$this->form_validation->set_rules("ctype","Content Type","required|xss_clean",array(
					'required'      => '<b>Content Type is not selected.</b>'
		));
        $this->form_validation->set_rules("title","Title","required|xss_clean",array(
					'required'      => '<b>Title is required.</b>'
		));
		$this->form_validation->set_rules("url","Url","required|xss_clean",array(
					'required'      => '<b>Url **</b> field is empty'
		));
		$this->form_validation->set_rules("content","Description","xss_clean");
		$this->form_validation->set_rules("status1","Status","required|xss_clean",array(
					'required'      => '<b>Status **</b> field is not selected'
		));
		$this->form_validation->set_rules('visible[]', 'Visible', 'required|xss_clean',array(
					'required'      => '<b>Visible **</b> field is not selected'
		));

		if($this->form_validation->run() == FALSE){
			echo validation_errors();
		}else{
			
			$imgUrl = $content['imgUrl'];
			
			if(!empty($_FILES['image']['name'])){
				$blogimg = $_FILES['image']['name'];
				$imgname=str_replace(array(" ","'"),"_",time().$blogimg);
				$img = $this->uploadFile($imgname,"image","banner");	
				if($img !== 1){
					echo $img; exit;
				}
				$imgUrl = 'admin_assets/images/banner/'.$imgname;
			}
			
			
			if(in_Array("1",$content['visible'])){
				$home =  '1';
			}else{
				$home = '0';
			}
			
			if(in_Array("2",$content['visible'])){
				$mem = '1';
			}else{
				$mem = '0';
			}
			
			if(in_Array("3",$content['visible'])){
				$doc = '1';
			}else{
				$doc = '0';
			}
				
			$data = array(
				'title' => $content['title'],
				'url' => $content['url'],
				'content'=> $content['content'],
				'image'=> $imgUrl,
				'addedBy' => $this->session->userdata('userId'),
				'home' => $home,
				'memDash' => $mem,
				'docDash' => $doc,
				'ctype' => $content['ctype'],
				'isActive' => $content['status1'],
				'modifiedDate' =>date('Y-m-d H:i:s')
			);
			$id = $content['bannerId'];
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$bid = base64_decode($base_64);
			$insert = $this->Mdl_banner->update($data,$bid);
			echo 1; exit;
		}
	}
	
	/***************************************** Admin View Banner ***************************************/
	function viewBanner($id){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$bid = base64_decode($base_64);
			$banner = $this->Mdl_banner->findById($bid);
			$data['user'] = $this->Mdl_banner->findName($banner[0]->addedBy);
			$data['bannerData'] = $banner;
			$data['viewFile'] = "view-banner";
			$data['page'] = "viewBanner";
			$template = 'admin';
			echo Modules::run('template/'.$template, $data);		
		}
	}
	
	/***************************************** File Upload ***************************************/
	function uploadFile($imageName,$key,$folderName){
		$config['file_name'] = $imageName;
		$config['upload_path'] = './admin_assets/images/'.$folderName; 
		$config['allowed_types'] = "jpg|png|jpeg|JPEG|svg";
		$config['max_width']  = '7000';
		$config['max_height']  = '7000';
		
		$this->load->library('upload',$config);
		$this->upload->initialize($config);
		   
		if (!$this->upload->do_upload($key)){
			return $this->upload->display_errors();
		}else{
			return 1;
		} 
	}
}

