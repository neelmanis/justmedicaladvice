<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Faq extends MX_Controller{
	
	function __construct() {
		parent::__construct();
		$this->load->model('Mdl_faq');	
	}
	
	function index(){
		$data['faqData'] = $this->Mdl_faq->getFaq();
		$data['isHomePage'] = '1';
		$data['viewFile'] = "faq";
		$data['module'] = "faq";
		$template = 'home';
		echo Modules::run('template/'.$template, $data);
	}
	
	/***************************************** List FAQ ***************************************/
	function listFaq(){
		if(!Modules::run('site_security/isAdmin')){
			redirect('admin','refresh');
		}
		
		$getFaq = $this->Mdl_faq->getFaqList();
		
		if($getFaq !== 'No Data'){
			$faq = array();
			foreach($getFaq as $val){
				$res = array();
				$res['faqId'] = $val->faqId;
				$res['question'] = $val->question;
				$res['answer'] = $val->answer;
				$res['isActive'] = $val->isActive;
				$res['createdDate'] = $val->createdDate;
				$faq[] = $res;
			}
			$data['faq'] = $faq;
		}else{
			$data['faq'] = "No Data";
		}
		
		$data['viewFile'] = "list-faq";
		$data['page'] = 'listFAQ';
		$template = 'admin';
		echo Modules::run('template/'.$template, $data);
	}
	
	/***************************************** Add FAQ Page ***************************************/
	function addFaq(){
		if(!Modules::run('site_security/isAdmin')){
			redirect('admin','refresh');
		}
		
		$data['viewFile'] = 'add-faq';
		$data['page'] = 'addFAQ';
		$template = 'admin';
		echo Modules::run('template/'.$template, $data);
	}
	
	/***************************************** Add FAQ Action ***************************************/
	function addFaqAction(){
		$content = $this->input->post();
        $this->form_validation->set_rules("question","Question","required|xss_clean",
		array(
			'required'=>'<b>%s is Required<b>'
		));
		$this->form_validation->set_rules("answer","Answer","required|xss_clean",
		array(
			'required'=>'<b>%s is Required<b>'
		));
		
		if($this->form_validation->run() == FALSE){
			echo validation_errors();
		}else{
			$data = array(
				'question' => $content['question'],
				'answer' => $content['answer'],
				'isActive' => '1',
				'createdDate' =>date('Y-m-d H:i:s'),
				'modifiedDate' =>date('Y-m-d H:i:s')
			);
                                                               
			$faqInsert = $this->Mdl_faq->insert($data);
			echo "1";
		}
	}
	
	/***************************************** Edit FAQ Page ***************************************/
	function editFaq($id){
		if(!Modules::run('site_security/isAdmin')){
			redirect('admin','refresh');
		}
		
		$base_64 = $id . str_repeat('=', strlen($id) % 4);
		$faqid = base64_decode($base_64);
		$data['faqData'] = $this->Mdl_faq->getFaqById($faqid);
		$data['viewFile'] = "edit-faq";
		$data['page'] = "editFAQ";
		$template = 'admin';
		echo Modules::run('template/'.$template, $data);
	}	
	
	/***************************************** Edit FAQ Action ***************************************/
	function editFaqAction(){
		$content = $this->input->post();
		
		$this->form_validation->set_rules("question","Question","required|xss_clean",
		array(
			'required'=>'<b>%s is Required<b>'
		));
		$this->form_validation->set_rules("answer","Answer","required|xss_clean",
		array(
			'required'=>'<b>%s is Required<b>'
		));
		$this->form_validation->set_rules("status","Select Status","required|xss_clean",
		array(
			'required' => '<b>Please Select status</b>'
		));
	
		if($this->form_validation->run() == FALSE){
			echo validation_errors();
		}else{
			$data = array(
				'question' => $content['question'],
				'answer' => $content['answer'],
				'isActive' => $content['status'],
				'modifiedDate' =>date('Y-m-d H:i:s')
			);
			$editFaq = $this->Mdl_faq->update($data,$content['faqId']);
			echo "1";
		}
	}
	
	/***************************************** Inactive FAQ Action ***************************************/
	function inActiveAction($id){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$faqid = base64_decode($base_64);
			$this->Mdl_faq->statusInactive($faqid);
			redirect('faq/listFaq');		
		}
	}
	
	/***************************************** Active FAQ Action ***************************************/
	function activeAction($id){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$faqid = base64_decode($base_64);
			$this->Mdl_faq->statusActive($faqid);
			redirect('faq/listFaq');	
		}
	}
}

