<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Testimonial extends MX_Controller
{
	/*------------------------------ CONSTRUCTOR FUNCTION---------------------------------------*/	
	function __construct() {
		parent::__construct();
		$this->load->model('Mdl_testimonial');	
	}
	
	/*function index(){
	    $data['getAll'] = $this->Mdl_testimonial->listAllTestimonial();
		$data['viewFile'] = "list";
		$template = 'main';
		$data['seorecord'] = modules::run('admin/getSeoContent',"testimonials");
		$data['pagetitle'] = "Testimonials";
		$data['module'] = 'testimonial';
		echo Modules::run('template/'.$template, $data);
	}
	*/
	
	function listall(){
	    $data['getAll'] = $this->Mdl_testimonial->listAll();
		$data['viewFile'] = "list";
		$data['page'] = 'testimoniallist';
		$template = 'admin';
		echo Modules::run('template/'.$template, $data);
	}
	
	function getotherCategoryName($id){
		$getCategories = $this->Mdl_testimonial->othercatname($id);
	    echo  $getCategories;
	}

	function delete(){
		$other_id = $_POST['other_id'];
		$delete = $this->Mdl_testimonial->other_delete($other_id);
		echo $delete;
	}	
	
	function addNew(){
		$data['viewFile'] = 'add';
		$data['page'] = 'testimonial';
		$template = 'admin';
		echo Modules::run('template/'.$template, $data);
	}
	
	function addtest(){ 
		$content = $this->input->post();
       	$this->form_validation->set_rules("name","Name","required|xss_clean");
		$this->form_validation->set_rules("desc","Description","required|xss_clean");
		$this->form_validation->set_rules("status","Status","required|xss_clean");
		
		if($this->form_validation->run() == FALSE){
			echo validation_errors();
		}else{
						 
			$data = array(
				'testName' => $content['name'],
				'testDesc' => $content['desc'],
				'isActive' => $content['status'],
				'createdDate' =>date('Y-m-d H:i:s'),
				'modifiedDate' =>date('Y-m-d H:i:s')
			);
						
			$insert = $this->Mdl_testimonial->test_insert($data);
			echo "1";
		}
	}			
	
	public function uploadFile($imageName,$key,$folderName){
		//echo $imageName."--".$key."--".$folderName;
	   	$config['file_name'] = $imageName;
		$config['upload_path'] = './admin_assets/images/'.$folderName; 
		$config['allowed_types'] = "gif|jpg|png|jpeg|JPEG";
		$config['max_width']  = '5000';
		$config['max_height']  = '5000';
				
        $this->load->library('upload',$config);
		$this->upload->initialize($config);
		if(!$this->upload->do_upload($key)){
			echo $this->upload->display_errors();
		} 
	}
	
	function edit($id){
		$data['getData'] = $this->Mdl_testimonial->datatobeEdit($id);
		$data['viewFile'] = "edit";
		$data['page'] = "testimonial";
		$template = 'admin';
		echo Modules::run('template/'.$template, $data);
	}	
	
	function editTest(){
		$content = $this->input->post();
		$id=$content['testid'];
        $this->form_validation->set_rules("name","Name","required|xss_clean");
		$this->form_validation->set_rules("desc","Description","required|xss_clean");
		$this->form_validation->set_rules("status","Status","required|xss_clean");
		
		if($this->form_validation->run() == FALSE){
			echo validation_errors();
		}else{		 	
			$data = array(
				'testName' => $content['name'],
				'testDesc' => $content['desc'],
				'isActive' => $content['status'],
				'modifiedDate' =>date('Y-m-d H:i:s')
			);
			$edit = $this->Mdl_testimonial->testUpdate($data,$id);
			echo "1";
		}
	}
}