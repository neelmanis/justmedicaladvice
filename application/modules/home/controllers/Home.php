<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MX_Controller{
	
	function __construct() {
		parent::__construct();
		$this->load->model('Mdl_home');
		$this->load->model('banner/Mdl_banner');
		$this->load->model('doctor/Mdl_doctor');
	    $this->load->model('blog/Mdl_blog');
	    $this->load->model('media/Mdl_media');
	    $this->load->model('forum/Mdl_forum');
	    $this->load->model('event/Mdl_event');
	}

	/*************************************** Home Page **************************************/
	function index(){
		$data['isHomePage'] = '1';
		$data['banner'] = $this->Mdl_banner->getHomeBanner();
		
		$getDoctors = $this->Mdl_doctor->getHomeDoctors();
		if($getDoctors !== 'No Data'){
			$docList = array();
			foreach($getDoctors as $res){
				$sub_array = array();
				$id = base64_encode($res->regId);
				$id = str_replace(str_repeat('=',strlen($id)/4),"",$id);
				$sub_array['id'] = $id;
				$sub_array['image'] = $res->profileImage;
				$sub_array['name'] = $res->name;
				$sub_array['city'] = $res->city;
				$val = explode(",",$res->degree);
				$degree = '';
				$size = sizeof($val);
				
				if($size > 2){
					$size -= ($size-2);
				}
				
				for($i=0; $i< $size; $i++){
					$degree .= $this->Mdl_doctor->findDegreeName($val[$i]).', ';
				}
				$sub_array['degree'] = $degree;
				if($res->followedMem !== ''){
					$sub_array['follower'] = sizeof(explode(",",$res->followedMem));
				}else{
					$sub_array['follower'] = "No";
				}
				$docList[] = $sub_array;
			}
		}else{
			$docList = 'No Data';
		}
		
		$getBlogData = $this->Mdl_blog->getHomeBlogs();
		if($getBlogData !== 'No Data'){
			$blogList = array();
			foreach($getBlogData as $res){
				if($res->postedBy == 'admin'){
					$name = 'Just Medical Advice';
				}else{
					$docData = $this->Mdl_blog->getDoctorDetails($res->userId);
					$name = $docData[0]->name;
				}
				$result = array();
				$result['name'] = $name;
				$result['title'] = $res->title;
				$result['date'] = date("d M Y ",strtotime($res->createdDate));
				$result['image'] = 'admin_assets/images/blog/'.$res->image;
				$result['desc'] = substr($res->shortDesc,0,120);
				$result['slug'] = $res->slug;
				$blogList[] = $result;
			}
		}else{
			$blogList = 'No Data';
		}
		
		$getMediaData = $this->Mdl_media->getHomeMedia();
		if($getMediaData !== 'no'){
			$mediaList = array();
			foreach($getMediaData as $res){
				if($res->postedBy == 'admin'){
					$name = 'Just Medical Advice';
				}else{
					$docData = $this->Mdl_blog->getDoctorDetails($res->userId);
					$name = $docData[0]->name;
				}
				$result = array();
				$result['name'] = $name;
				$result['title'] = $res->title;
				$result['ctype'] = $res->ctype;
				$result['mtype'] = $res->mtype;
				$result['url'] = $res->url;
				$result['slug'] = $res->slug;
				$mediaList[] = $result;
			}
		}else{
			$mediaList = 'No Data';
		}
		
		$data['doctors'] = $docList; 
		$data['blogs'] = $blogList; 
		$data['media'] = $mediaList; 
		$data['testimonials'] = $this->Mdl_home->getTestimonials();
		$data['events'] = $this->Mdl_event->getHomeEvent();
		$template = 'home';
		$data['viewFile'] = 'index';
		$data['scriptFile'] = 'jma-home';
		$data['module'] = "home";
		echo Modules::run('template/'.$template, $data); 
	}
	
	/***************************************** Read Blog ***************************************/
	function read($url){
		$blog = $this->Mdl_blog->getBlogByUrl($url);
		if($blog == 'no'){
			redirect('errors');
		}
		
		$likes = $this->Mdl_blog->getLikeCount($blog[0]->blogId);
		$comments = $this->Mdl_blog->getCommentCount($blog[0]->blogId);
		
		if($blog[0]->postedBy == 'admin'){
			$userData = $this->Mdl_blog->getAdminDetails($blog[0]->userId);
		}else{
			$userData = $this->Mdl_blog->getDoctorDetails($blog[0]->userId);
		}
		 
		$comment = Modules::run('blog/show_tree/'.$blog[0]->blogId);
		if($comment == 'no'){
			$data['comments'] = '';
		}else{
			$data['comments'] = $comment;
		}
		
		$data['blogDetails'] = $blog;
		$data['likeCount'] = $likes;
		$data['commentCount'] = $comments;		
		$data['userDetails'] = $userData;
		
		
		$data['viewFile'] = 'view';
		$data['module'] = 'home';
		$template = 'home';
		echo Modules::run('template/'.$template, $data);
	}
	
	/*************************************** About Us Page **************************************/
	function aboutUs(){
		$type = '';
		
		if(Modules::run('site_security/isLoggedIn')){
			$type = $this->session->userdata('type');
		}
		
		if($type == 'doc'){
			$template = 'doctor';
			$data['home'] = '1';
		}else if($type == 'mem'){
			$template = 'member';
			$data['home'] = '1';
		}else{
			$template = 'home';
			$data['isHomePage'] = '1';
		}
		
		$data['viewFile'] = 'about-us';
		$data['module'] = "home";
		echo Modules::run('template/'.$template, $data); 
	}
	
	/*************************************** JMA for Doctors **************************************/
	function jmaForDoctors(){
		$type = '';
		
		if(Modules::run('site_security/isLoggedIn')){
			$type = $this->session->userdata('type');
		}
		
		if($type == 'doc'){
			$template = 'doctor';
			$data['home'] = '1';
		}else if($type == 'mem'){
			$template = 'member';
			$data['home'] = '1';
		}else{
			$template = 'home';
			$data['isHomePage'] = '1';
		}
		
		$data['viewFile'] = 'jma-for-doctors';
		$data['module'] = "home";
		echo Modules::run('template/'.$template, $data);
	}
	
	/*************************************** JMA for Members **************************************/
	function jmaForMembers(){
		$type = '';
		
		if(Modules::run('site_security/isLoggedIn')){
			$type = $this->session->userdata('type');
		}
		
		if($type == 'doc'){
			$template = 'doctor';
			$data['home'] = '1';
		}else if($type == 'mem'){
			$template = 'member';
			$data['home'] = '1';
		}else{
			$template = 'home';
			$data['isHomePage'] = '1';
		}
		
		$data['viewFile'] = 'jma-for-members';
		$data['module'] = "home";
		echo Modules::run('template/'.$template, $data);
	}
	
	/*************************************** FAQ **************************************/
	function faq(){
		$type = '';
		
		if(Modules::run('site_security/isLoggedIn')){
			$type = $this->session->userdata('type');
		}
		
		if($type == 'doc'){
			$template = 'doctor';
			$data['home'] = '1';
		}else if($type == 'mem'){
			$template = 'member';
			$data['home'] = '1';
		}else{
			$template = 'home';
			$data['isHomePage'] = '1';
		}
		
		$data['viewFile'] = 'faq';
		$data['module'] = "home";
		echo Modules::run('template/'.$template, $data);
	}
	
	/*************************************** Contact Us **************************************/
	function contactUs(){
		$type = '';
		
		if(Modules::run('site_security/isLoggedIn')){
			$type = $this->session->userdata('type');
		}
		
		if($type == 'doc'){
			$template = 'doctor';
			$data['home'] = '1';
		}else if($type == 'mem'){
			$template = 'member';
			$data['home'] = '1';
		}else{
			$template = 'home';
			$data['isHomePage'] = '1';
			$data['scriptFile'] = 'jma-home';
		}
		
		$data['viewFile'] = 'contact-us';
		$data['module'] = "home";
		echo Modules::run('template/'.$template, $data);
	}
	
	/*************************************** Terms Of Use **************************************/
	function termsOfUse(){
		$type = '';
		
		if(Modules::run('site_security/isLoggedIn')){
			$type = $this->session->userdata('type');
		}
		
		if($type == 'doc'){
			$template = 'doctor';
			$data['home'] = '1';
		}else if($type == 'mem'){
			$template = 'member';
			$data['home'] = '1';
		}else{
			$template = 'home';
			$data['isHomePage'] = '1';
		}
		
		$data['viewFile'] = 'terms-of-use';
		$data['module'] = "home";
		echo Modules::run('template/'.$template, $data);
	}
	
	/*************************************** Privacy **************************************/
	function privacy(){
		$type = '';
		
		if(Modules::run('site_security/isLoggedIn')){
			$type = $this->session->userdata('type');
		}
		
		if($type == 'doc'){
			$template = 'doctor';
			$data['home'] = '1';
		}else if($type == 'mem'){
			$template = 'member';
			$data['home'] = '1';
		}else{
			$template = 'home';
			$data['isHomePage'] = '1';
		}
		
		$data['viewFile'] = 'privacy';
		$data['module'] = "home";
		echo Modules::run('template/'.$template, $data);
	}
	
	/*************************************** Home Search **************************************/
	function search(){
		$search = $_POST['searchKey'];
		$data = explode(" ",$search);
		
		$msg = "";
		
		if(!empty($search)){
			$blogCat = $this->Mdl_blog->getCategoryBySearch($data);
			$mediaCat = $this->Mdl_media->getCategoryBySearch($data);
			$specialities = $this->Mdl_forum->getSpecialityBySearch($data);
			$doctors = $this->Mdl_doctor->getDoctorsBySearch($data);
			
			if(is_array($blogCat)){
		        $msg.="<div class='result_box'><div class='result_tl'>Blogs based On Category</div>";
                foreach($blogCat as $val){
                    $msg.='<a href="'.base_url().'blog/searches/'.$val->catSlug.'">'.$val->catName.'</a>';
                }
                $msg.="</div>"; 
		    } 
			
			if(is_array($mediaCat)){
		        $msg.="<div class='result_box'><div class='result_tl'>Audio/Video based On Category</div>";
                foreach($mediaCat as $val){
                     $msg.='<a href="'.base_url().'media/searches/'.$val->catSlug.'">'.$val->catName.'</a>';
                }
                $msg.="</div>"; 
		    } 
			
			if(is_array($specialities)){
		        $msg.="<div class='result_box'><div class='result_tl'>Forums based On Speciality</div>";
                foreach($specialities as $val){
                     $msg.='<a href="'.base_url().'forum/searches/'.$val->spSlug.'">'.$val->spName.'</a>';
                }
                $msg.="</div>"; 
		    }
			
			if(is_array($doctors)){
		        $msg.="<div class='result_box'><div class='result_tl'>Doctors Available</div>";
                foreach($doctors as $val){
					$docid = base64_encode($val->regId);
					$docid = str_replace(str_repeat('=',strlen($docid)/4),"",$docid);
                    $msg.="<a href='".base_url()."doctor/view/$docid'><img height='50px;' width='50px;' style='border-radius: 50%;' src='".$val->profileImage."'>  ".$val->name."</a>";
                }
                $msg.="</div>"; 
		    }
			
			if($blogCat=="no" && $mediaCat=="no" && $specialities=="no" && $doctors=="no"){
		      $msg.="<div class='result_box'><p style='margin-left: 10px;'>No results found for <b><span style='color:red'>$search</span></b>. </p></div>";
			}
			
			echo $msg;
		}else{
			echo 1;
		}
	}
	
	/*************************************** Subscribe **************************************/
	function subscribeinsert(){
		$subscribeRecord = $this->input->post();
		$this->form_validation->set_rules("emailId","Email","required|valid_email|is_unique[subscription.emailId]",array(
            "required" => "Please provide Email.",
            "valid_email" => "Entered Email is not valid",
            "is_unique" => "Email Already Exists."
        ));
			
		if($this->form_validation->run() == false){
			echo validation_errors();
		} else {
			$subscribeRecord['substatus'] = '1';
			$subscribeRecord['createdDate'] = date('y-m-d h:i:s');
			$subscribeRecord['modifiedDate'] = date('y-m-d h:i:s');
				
			$this->Mdl_home->insert("subscription",$subscribeRecord);
		}
	}
	
	/*************************************** Add Contact Us Action **************************************/
	function contactUsAction(){
		$content = $this->input->post();
		
		$this->form_validation->set_rules("queryType","Interested In","required|xss_clean",array(
            "required" => "<b>Please Select The Interest Type.</b>",
        ));
		
		$this->form_validation->set_rules("name","Name","trim|required|xss_clean|regex_match[/^[A-Z a-z]+$/]",
			array(
				'required' => '<b>Please enter %s.</b>',
				'regex_match' => '<b>%s should not contain numbers or other special characters.</b>'
		));
		
		$this->form_validation->set_rules("city","City","trim|required|xss_clean|regex_match[/^[A-Z a-z]+$/]",
			array(
				'required' => '<b>Please enter %s.</b>',
				'regex_match' => '<b>%s should not contain numbers or other special characters.</b>'
		));
		
		$this->form_validation->set_rules("mobile","Mobile Number","trim|required|xss_clean|regex_match[/^[0-9]*$/]|is_unique[registration.mobile]",
			array(
					'required'      => '<b>Please enter %s.</b>',
					'regex_match'   => '<b>Please enter valid %s.</b>',
					'is_unique'     => '<b>%s is already registered.</b>',
		));
		
		$this->form_validation->set_rules("email","Email","required|valid_email|xss_clean",array(
            "required" => "<b>Please provide Email.</b>",
            "valid_email" => "<b>Entered Email is not valid</b>",
        ));
		
		$this->form_validation->set_rules("message","Message","trim|required|xss_clean",
			array(
				'required' => '<b>Please enter %s.</b>'
		));
		
		if($this->form_validation->run() == FALSE){
			echo validation_errors();
		}else{	   
			
			$data=array(
				'interest'=>$content['queryType'],
				'name'=>$content['name'],
				'mobile' => $content['mobile'],
				'email' => $content['email'],
				'city'=> $content['city'],
				'message'=> $content['message'],
				'postedDate'=>date("Y-m-d H:i:s")
			);
			
			$id = $this->Mdl_home->insert('contact_us', $data);		
			echo 1; exit;
		}
	}
}

