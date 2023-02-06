<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Doctor extends MX_Controller{
	
	function __construct() {
		parent::__construct();
		$this->load->model('Mdl_doctor');
		$this->load->model('member/Mdl_member');
		$this->load->model('speciality/Mdl_speciality');
		$this->load->model('blog/Mdl_blog');
		$this->load->model('media/Mdl_media');
		$this->load->model('forum/Mdl_forum');
		$this->load->model('webinar/Mdl_webinar');
		$this->load->model('location/Mdl_location');
		$this->load->model('notifications/Mdl_notifications');
	}
	
	/********************************* Doctor Profile Page **********************************/
	function doctorProfile(){
		if(!Modules::run('site_security/isDoctor')){
			redirect('login','refresh');
		}
		
		$id = $this->session->userdata('userId'); 
		$isd = $this->Mdl_member->getISD($id);
		$cityDump = $this->Mdl_member->getCities($isd[0]->isd);
		
		$data['isHomePage'] = '0';
		$data['uname'] = $this->Mdl_doctor->getName($id);
		$data['degree'] = $this->Mdl_doctor->getDegree();
		$data['speciality'] = Modules::run('speciality/getAllSpecialities');
		$data['location'] = $cityDump;
		$data['viewFile'] = "set-doctor-profile";
		$data['module'] = "doctor";
		$data['scriptFile'] = "doctor-profile";
		$template = 'registration';
		echo Modules::run('template/'.$template, $data);
	}
	
	/********************************* Doctor Profile Submit Action **********************************/
	function doctorProfileSubmit(){
		$content = $this->input->post();
		
		$this->form_validation->set_rules("email","Email","required|trim|valid_email|xss_clean",
		array(
			'required'=>'<b>Email is Required</b>',
			'valid_email'=>'<b>Email is not valid</b>'
		));
		$this->form_validation->set_rules("gender","Gender","required|xss_clean",
		array(
			'required'=>'<b>Gender is required</b>'
		));
		$this->form_validation->set_rules("speciality[]","Speciality","required|xss_clean",
		array(
			'required'=>'<b>Select Speciality</b>'
		));
		$this->form_validation->set_rules("degree[]","Degree","required|xss_clean",
		array(
			'required'=>'<b>Select Degree</b>'
		));
		$this->form_validation->set_rules("experience","Experience","required|xss_clean",
		array(
			'required'=>'<b>Select Experience</b>'
		));
		$this->form_validation->set_rules("city","City","required|xss_clean",
		array(
			'required'=>'<b>Select Location</b>'
		));
		
		if($this->form_validation->run() == FALSE){
			echo validation_errors(); exit;
		}else{	   
			$uid = $this->session->userdata('userId');
			if(!empty($_FILES['image']['name'])){
				$profileimg = $_FILES['image']['name'];
				$imgname=str_replace(array(" ","'"),"_",time().$profileimg);
				$img = $this->uploadFile($imgname,"image","doctor",$uid);	
				if($img !== 1){
					echo $img; exit;
				}
				$imgUrl = 'admin_assets/images/doctor/'.$uid.'/'.$imgname;
			}else{
				$imgUrl = "admin_assets/images/default.jpg"; 
			}
						
			$data=array(
				'regId'=>$uid,
				'gender'=>$content['gender'],
				'speciality'=>implode(",",$content['speciality']),
				'degree'=>implode(",",$content['degree']),
				'experience'=>$content['experience'],
				'city'=>$content['city'],
				'profileImage'=>$imgUrl,
				'govProof'=>'',
				'degreeProof'=>'',
				'medProof'=>'',
				'isFeatured'=>'0',
				'followedMem'=>'',
				'education'=>'',
				'membership'=>'',
				'myself'=>'',
				'language'=>'',
				'clinicAddress'=>'',
				'contacts'=>'',
				'timing'=>'',
				'createdDate'=>date("Y-m-d H:i:s"),
				'modifiedDate'=>date("Y-m-d H:i:s")
			);
			
			$this->Mdl_doctor->insert('doctor', $data);	
			
			$data2 =array(
				'email'=>$content['email'],
				'statusCode'=> '5',
				'modifiedDate'=>date("Y-m-d H:i:s")
			);
			$this->Mdl_doctor->update('registration', $data2, $uid, 'id');	
			
			echo 1; exit;
		}
	}
	
	/********************************* Doctor Document Verification Page **********************************/
	function doctorDocumentVerification(){
		if(!Modules::run('site_security/isDoctor')){
			redirect('login','refresh');
		}
		
		$uid = $this->session->userdata('userId'); 
		$data['isHomePage'] = '0';
		$data['viewFile'] = "docs-verify";
		$data['module'] = "doctor";
		$data['scriptFile'] = "doctor-profile";
		$template = 'registration';
		echo Modules::run('template/'.$template, $data);
	}
	
	/********************************* Doctor Document Verification Action **********************************/
	function documentVerification(){
		$uid = $this->session->userdata('userId');
		$docId = $this->Mdl_doctor->findByUserId($uid);
			
		if(!empty($_FILES['file']['name'][0])){
			$total = count($_FILES['file']['name']);
			for($i=0; $i<$total; $i++){
				$profileimg = $_FILES['file']['name'][$i];
				$imgname = str_replace(array(" ","'"),"_",time().$profileimg);
                $_FILES['image']['name']     = $_FILES['file']['name'][$i];
                $_FILES['image']['type']     = $_FILES['file']['type'][$i];
                $_FILES['image']['tmp_name'] = $_FILES['file']['tmp_name'][$i];
                $_FILES['image']['error']     = $_FILES['file']['error'][$i];
                $_FILES['image']['size']     = $_FILES['file']['size'][$i];
				$img = $this->uploadFile($imgname,"image","doctor",$uid);
				if($img !== 1){
					echo $img; exit;
				}
				$fileNames[$i] = $imgname;
			}
			
			$data=array(
				'govProof'=>'admin_assets/images/doctor/'.$uid.'/'.$fileNames[0],
				'degreeProof'=>'admin_assets/images/doctor/'.$uid.'/'.$fileNames[1],
				'medProof'=>'admin_assets/images/doctor/'.$uid.'/'.$fileNames[2],
				'modifiedDate'=>date("Y-m-d H:i:s")
			);
			
			$this->Mdl_doctor->update('doctor', $data, $docId[0]->id, 'id');		
			
			$data2 =array(
				'statusCode'=> '6',
				'modifiedDate'=>date("Y-m-d H:i:s")
			);
			$this->Mdl_doctor->update('registration', $data2, $uid, 'id');
			echo 1; exit;
		}else{
			$data=array(
				'govProof'=>'',
				'degreeProof'=>'',
				'medProof'=>'',
				'modifiedDate'=>date("Y-m-d H:i:s")
			);
			
			$this->Mdl_doctor->update('doctor', $data, $docId[0]->id, 'id');		
			
			$data2 =array(
				'statusCode'=> '7',
				'modifiedDate'=>date("Y-m-d H:i:s")
			);
			$this->Mdl_doctor->update('registration', $data2, $uid, 'id');
			echo 1; exit;
		}
	}
	
	/********************************* Doctor Document Upload Success **********************************/
	function uploadSuccess(){
		if(!Modules::run('site_security/isDoctor')){
			redirect('login','refresh');
		}
		
		$sessionData = array(
				'userId'=>'',
				'type'=>''
		);
		$this->session->unset_userdata('userData', $sessionData);
		$this->session->sess_destroy();

		$data['isHomePage'] = '0';
		$data['viewFile'] = "doc-upload-success";
		$data['module'] = "doctor";
		$data['scriptFile'] = "doctor-profile";
		$template = 'home';
		echo Modules::run('template/'.$template, $data);
	}
	
	/********************************* Doctor Dashboard **********************************/
	function home(){
		if(!Modules::run('site_security/isDoctor')){
			redirect('login','refresh');
		}
		
		redirect('dashboard/doctor');
	}
	
	/********************************* Doctor Change Password Page **********************************/
	function changePassword(){
		if(!Modules::run('site_security/isDoctor')){
			redirect('login','refresh');
		}
		
		$data['module'] = 'doctor';
		$data['viewFile'] = 'change-password';
		$data['scriptFile'] = 'doctor-dashboard';
		$data['home'] = '1';
		$template = 'doctor';
		echo Modules::run('template/'.$template, $data);
	}
	
	/********************************* Doctor Change Password Action **********************************/
	function changePasswordAction(){
		$content = $this->input->post();
		
		$this->form_validation->set_rules("oldPassword","Old Password","trim|required|xss_clean");
		$this->form_validation->set_rules("newPassword","New Password","trim|required|xss_clean|min_length[6]");
		$this->form_validation->set_rules("cnfNewPassword","Confirm New Password","trim|required|xss_clean|matches[newPassword]");
		
		if($this->form_validation->run() == FALSE){
			echo validation_errors(); exit;
		}else{
			$id = $this->session->userdata('userId'); 
			$storedPass = $this->Mdl_doctor->getDoctorPassword($id);
			$oldPass =  Modules::run('site_security/makeHash',$content['oldPassword']);
			if($storedPass == $oldPass){
				$changePass = Modules::run('site_security/makeHash',$content['newPassword']);
				$data=array(
					'password' => $changePass, 
					'modifiedDate'=>date("Y-m-d H:i:s")
				);
				$this->Mdl_doctor->update('registration', $data, $id, 'id');
				echo 1; exit;
			}else{
				echo "You Have Entered Wrong Password !!";
			}
		}		
	}
	
	/********************************* Doctor Profile Update **********************************/
	function updateProfile(){
		if(!Modules::run('site_security/isDoctor')){
			redirect('login','refresh');
		}
		
		$id = $this->session->userdata('userId'); 
		
		$data['degree'] = $this->Mdl_doctor->getDegree();
		$data['speciality'] = Modules::run('speciality/getAllSpecialities');
		
		$docs = $this->Mdl_doctor->getDoctorDetails($id);
	
		$list = array();		
		$dId = base64_encode($docs[0]->regId);
		$dId = str_replace(str_repeat('=',strlen($dId)/4),"",$dId);
		$list['regId'] = $dId;
		$list['name'] = $docs[0]->name;
		$list['experience'] = $docs[0]->experience;
		$list['city'] = $docs[0]->city;
		$list['image'] = $docs[0]->profileImage;
		$list['featured'] = $docs[0]->isFeatured;
		$list['mobile'] = $docs[0]->isd.' '.$docs[0]->mobile;
		$list['gender'] = $docs[0]->gender;
		$list['email'] = $docs[0]->email;
		$list['myself'] = $docs[0]->myself;
		$list['degree'] = $docs[0]->degree;
		$list['speciality'] = $docs[0]->speciality;
		$list['language'] = $docs[0]->language;
		$list['clinicAddress'] = $docs[0]->clinicAddress;
		$list['contacts'] = $docs[0]->contacts;
		$list['timing'] = $docs[0]->timing;
		$list['education'] = $docs[0]->education;
		$list['membership'] = $docs[0]->membership;
		
		$data['doctor'] = $list;
		
		$data['module'] = 'doctor';
		$data['viewFile'] = 'update-profile';
		$data['scriptFile'] = 'doctor';
		$data['home'] = '1';
		$template = 'doctor';
		echo Modules::run('template/'.$template, $data);
	}

	/********************************* Doctor Profile Update Action **********************************/
	function updateProfileAction(){
		$content = $this->input->post();
		
		$this->form_validation->set_rules("name","Name","trim|required|xss_clean|regex_match[/^[A-Z a-z .]+$/]",
		array(
			'required' => '<b>%s is required.</b>',
			'regex_match' => '<b>%s should not contain numbers or other special characters.</b>'
		));
		
		$this->form_validation->set_rules("email","Email","required|trim|valid_email|xss_clean",
		array(
			'required'=>'<b>Email is Required</b>',
			'valid_email'=>'<b>Email is not valid</b>'
		));
		
		$this->form_validation->set_rules("gender","Gender","required|xss_clean",
		array(
			'required'=>'<b>Gender is required</b>'
		));
		
		$this->form_validation->set_rules("city","City","required|xss_clean",
		array(
			'required'=>'<b>Please Enter City</b>'
		));
		
		$this->form_validation->set_rules("myself","My Self","xss_clean");
		
		$this->form_validation->set_rules("speciality[]","Speciality","required|xss_clean",
		array(
			'required'=>'<b>Please select Speciality</b>'
		));
		
		$this->form_validation->set_rules("degree[]","Degree","required|xss_clean",
		array(
			'required'=>'<b>Please select Degree</b>'
		));
		
		$this->form_validation->set_rules("experience","Experience","required|xss_clean",
		array(
			'required'=>'<b>Please select Experience</b>'
		));
		
		$this->form_validation->set_rules("language[]","Language","xss_clean");
		$this->form_validation->set_rules("education[]","Education","xss_clean");
		$this->form_validation->set_rules("membership[]","Membership","xss_clean");
		$this->form_validation->set_rules("contacts","Contact","trim|xss_clean");
		$this->form_validation->set_rules("caddress","Clinic Address","xss_clean");
		$this->form_validation->set_rules("timing","Clinic Timing","xss_clean");
		
		if($this->form_validation->run() == FALSE){
			echo validation_errors(); exit;
		}else{	
		
			$id = $content['regId'];
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$uid = base64_decode($base_64);
			
			$data=array(
				'name'=>$content['name'],
				'email'=>$content['email'],
				'modifiedDate'=>date("Y-m-d H:i:s")
			);
	
			$this->Mdl_doctor->update('registration', $data, $uid, 'id');
			
			if(!empty($_FILES['upload']['name'])){
				$pimage = $_FILES['upload']['name'];
				$imgname=str_replace(array(" ","'"),"_",time().$pimage);
				$img = $this->uploadFile($imgname,"upload","doctor",$uid);	
				if($img !== 1){
					echo $img; exit;
				}
				$imgUrl = 'admin_assets/images/doctor/'.$uid.'/'.$imgname;
			}else{
				$imgUrl = $content['profileImg']; 
			}
			
			$edu = array_diff($content['education'], array(''));
			$mem = array_diff($content['membership'], array(''));
			if(isset($content['language'])){
				$lang = implode(",",$content['language']);
			}else{
				$lang = '';
			}		
			
			$data2=array(
				'speciality'=>implode(",",$content['speciality']),
				'degree'=>implode(",",$content['degree']),
				'experience'=>$content['experience'],
				'city'=>$content['city'],
				'profileImage'=>$imgUrl,
				'education'=>implode("#",$edu),
				'membership'=>implode("#",$mem),
				'myself'=>$content['myself'],
				'language'=>$lang,
				'clinicAddress'=>$content['caddress'],
				'contacts'=>$content['contacts'],
				'timing'=>$content['timing'],
				'modifiedDate'=>date("Y-m-d H:i:s")
			);
			
			$this->Mdl_doctor->update('doctor', $data2, $uid, 'regId');	
			
			$doc = $this->Mdl_doctor->getDoctorDetails($uid);		
			foreach ($doc as $row) {
				$type = $row->type;
				$name = $row->name;	
				$image = $row->profileImage;
				$speciality = $row->speciality; 			
				$degree = $row->degree;
				$followers = $row->followedMem;
			}
		
			$sessionData = array(
								'userId'=>$uid,
								'type'=>$type,
								'name'=>$name,
								'image'=>$image,
								'speciality'=>$speciality,
								'degree'=>$degree,
								'followedMem'=>$followers
							);
			$user = $this->session->set_userdata('type',$type);
			$this->session->set_userdata('userData', $sessionData);
							
			echo 1; exit;
		}
	}
	
	/********************************* Doctor Profile Page **********************************/
	function myProfile(){
		if(!Modules::run('site_security/isDoctor')){
			redirect('login','refresh');
		}
		
		$id = $this->session->userdata('userId'); 
		
		$docs = $this->Mdl_doctor->getDoctorDetails($id);
		$list = array();		
		$dId = base64_encode($docs[0]->regId);
		$dId = str_replace(str_repeat('=',strlen($dId)/4),"",$dId);
		$list['regId'] = $dId;
		$list['name'] = $docs[0]->name;
		$list['experience'] = $docs[0]->experience;
		$list['city'] = $docs[0]->city;
		$list['image'] = base_url().$docs[0]->profileImage;
		$list['featured'] = $docs[0]->isFeatured;
		
		$res1 = explode(",",$docs[0]->degree);
		$deg = '';
		$size = sizeof($res1);
		
		if($size > 2){
			$size -= ($size-2);
		}
		
		for($i=0; $i< $size; $i++){
			$deg .= $this->Mdl_doctor->findDegreeName($res1[$i]);
			if($i == 0){
				$deg .= ', ';
			}
		}
		
		$list['degree'] = $deg;
		
		$res2 = explode(",",$docs[0]->speciality);
		$spList = array();
		foreach($res2 as $sp){
			$spList[] = $this->Mdl_doctor->findSpecialityName($sp);
		}
		
		$list['speciality'] = $this->Mdl_doctor->findSpecialityName($res2[0]);
		$list['specialityList'] = $spList;
		
		if($docs[0]->followedMem !== ''){
			$res3 = explode(",",$docs[0]->followedMem);
			$list['followedMem'] = sizeof($res3);
		}else{
			$list['followedMem'] = 0;
		}
		
		$list['education'] = $docs[0]->education;
		$list['membership'] = $docs[0]->membership;
		$list['myself'] = $docs[0]->myself;
		$list['language'] = $docs[0]->language;
		$list['caddress'] = $docs[0]->clinicAddress;
		$list['contacts'] = $docs[0]->contacts;
		$list['mobile'] = $docs[0]->mobile;
		$list['timing'] = $docs[0]->timing;
		
		$list['answerCount'] = $this->Mdl_doctor->findAnswerCount($docs[0]->regId);
		$list['thanks'] = $this->Mdl_doctor->findThanksCount($docs[0]->regId);
		$list['feedbackCount'] = $this->Mdl_doctor->findFeedbackCount($docs[0]->regId);
		
		$data['doctor'] = $list;
		$data['module'] = 'doctor';
		$data['viewFile'] = 'my-profile';
		$data['scriptFile'] = 'doctor';
		$data['home'] = '1';
		$template = 'doctor';
		echo Modules::run('template/'.$template, $data);
	}
	
	/********************************* View Doctors Profile **********************************/
	function view($id){
		if(!Modules::run('site_security/isMember')){
			redirect('login','refresh');
		}
		$base_64 = $id . str_repeat('=', strlen($id) % 4);
		$did = base64_decode($base_64);
		
		$docs = $this->Mdl_doctor->getDoctorDetails($did);
		$list = array();		
		$dId = base64_encode($docs[0]->regId);
		$dId = str_replace(str_repeat('=',strlen($dId)/4),"",$dId);
		$list['regId'] = $dId;
		$list['name'] = $docs[0]->name;
		$list['experience'] = $docs[0]->experience;
		$list['city'] = $docs[0]->city;
		$list['image'] = base_url().$docs[0]->profileImage;
		$list['featured'] = $docs[0]->isFeatured;
		
		$res1 = explode(",",$docs[0]->degree);
		$deg = '';
		$size = sizeof($res1);
		
		if($size > 2){
			$size -= ($size-2);
		}
		
		for($i=0; $i< $size; $i++){
			$deg .= $this->Mdl_doctor->findDegreeName($res1[$i]);
			if($i == 0){
				$deg .= ', ';
			}
		}
		
		$list['degree'] = $deg;
		
		$res2 = explode(",",$docs[0]->speciality);
		$spList = array();
		foreach($res2 as $sp){
			$spList[] = $this->Mdl_doctor->findSpecialityName($sp);
		}
		
		$list['speciality'] = $this->Mdl_doctor->findSpecialityName($res2[0]);
		$list['specialityList'] = $spList;
		
		if($docs[0]->followedMem !== ''){
			$res3 = explode(",",$docs[0]->followedMem);
			$list['followedMem'] = sizeof($res3);
		}else{
			$list['followedMem'] = 0;
		}
		
		$list['education'] = $docs[0]->education;
		$list['membership'] = $docs[0]->membership;
		$list['myself'] = $docs[0]->myself;
		$list['language'] = $docs[0]->language;
		$list['caddress'] = $docs[0]->clinicAddress;
		$list['contacts'] = $docs[0]->contacts;
		$list['mobile'] = $docs[0]->mobile;
		$list['timing'] = $docs[0]->timing;
		
		$list['answerCount'] = $this->Mdl_doctor->findAnswerCount($docs[0]->regId);
		$list['thanks'] = $this->Mdl_doctor->findThanksCount($docs[0]->regId);
		$list['feedbackCount'] = $this->Mdl_doctor->findFeedbackCount($docs[0]->regId);
		
		$data['doctor'] = $list;
		$data['module'] = 'member';
		$data['viewFile'] = 'doc-profile';
		$data['scriptFile'] = 'member-view-doctor';
		$data['docList'] = '1';
		$template = 'member';
		echo Modules::run('template/'.$template, $data);
	}
	
	/********************************* Get Doctor Feedback **********************************/
	function getDoctorFeedback(){
		$page =  $_GET['page'];
		$id =  $_GET['id'];
		$record = 5;
		$start = ($page - 1) * $record;
		
		$base_64 = $id . str_repeat('=', strlen($id) % 4);
		$uid = base64_decode($base_64);
		
		$feedbackRes = $this->Mdl_doctor->getFeedback($uid,$start,$record);
		
		$msg = '';
		
		if($feedbackRes !== "no"){
			foreach($feedbackRes as $val){
				$userId = $val->userId;
				$mem = $this->Mdl_doctor->getMemberDetails($userId);
				$name = $mem[0]->name;
				$image = base_url().$mem[0]->profileImage;
				
				$msg .= '<div class="db_article_box fade_anim">
							<div class="row articleDetails">                
								<div class="col-xs-12 author_info">
									<div class="author_dp"><a href="javascript:;"><img src="'.$image.'"></a></div>
									<div class="author_name"><a><strong class="txtdark">'.$name.'</strong></a></div>
								</div>                    
								<div class="col-xs-12 article_info">
									<div class="articleDate txtdark">'.date("d M Y ",strtotime($val->postedDate)).'</div>
									<p>'.$val->feedback.'</p>
								</div>
							</div>
						</div>';
			}
			echo $msg;
			exit;
		}else{
			echo 1; exit;
		}
	}	
	
	/********************************* Add Doctor Feedback **********************************/
	function addFeedback(){
		$content = $this->input->post();
		
		$this->form_validation->set_rules("utype","Usertype","required|xss_clean",array(
					'required' => 'Some Error Occured.' ));
		$this->form_validation->set_rules("uid","userid","required|xss_clean",array(
					'required' => 'Some Error Occured.' ));
		$this->form_validation->set_rules("did","doctorid","required|xss_clean",array(
					'required' => 'Some Error Occured.' ));
		$this->form_validation->set_rules("feedback","Feedback","required|xss_clean",array(
					'required' => 'Feedback is required.' ));

		if($this->form_validation->run() == FALSE){
			echo validation_errors();
		}else{
			$base_64 = $content['did']. str_repeat('=', strlen($content['did']) % 4);
			$docId = base64_decode($base_64);
			
			$data = array(
				'docId' => $docId,
				'postedBy' => $content['utype'],
				'userId' => $content['uid'],
				'feedback'=> $content['feedback'],
				'isActive'=> '1',
				'postedDate' =>date('Y-m-d H:i:s')
			);
					
			$insert = $this->Mdl_doctor->insert('doctor_feedback',$data);
			echo 1;
		}
	}
	
	/********************************* Get Doctor Data **********************************/
	function getDoctorProfileContent(){
		$page =  $_GET['page'];
		$id =  $_GET['id'];
		$record = 1;
		$start = ($page - 1) * $record;
		
		$base_64 = $id . str_repeat('=', strlen($id) % 4);
		$uid = base64_decode($base_64);
		
		$docs = $this->Mdl_doctor->getDoctorDetails($uid);
		$name = $docs[0]->name; 
		$url = $docs[0]->profileImage;

		$finalData = '';
		$baseurl = base_url();
		
		$resBlog = $this->Mdl_blog->getBlogByDoctorId($uid,$start,$record);
		if($resBlog !== 'no'){
			$likes = $this->Mdl_blog->getLikeCount($resBlog[0]->blogId);
			$comments = $this->Mdl_blog->getCommentCount($resBlog[0]->blogId);
					
			$finalData .= '<div class="db_article_box fade_anim"><div class="row articleDetails"><div class="col-sm-6 col-xs-12 author_info"><div class="author_dp"><a href=""><img src="'.$baseurl.''.$url.'"></a></div><div class="author_name"><strong><a href="" class="txtblue">'.$name.'</a></strong> shared article</div></div><div class="col-sm-6 col-xs-12 article_pic"><a href="" class="img_holder"><img src="'.$baseurl.'admin_assets/images/blog/'.$resBlog[0]->image.'"></a></div><div class="col-sm-6 col-xs-12 article_info"><div class="articleDate txtdark">'.date("d-M, Y ",strtotime($resBlog[0]->createdDate)).'</div><a href="'.$baseurl.'blog/view/'.$resBlog[0]->slug.'" class="txtblue"><h2>'.$resBlog[0]->title.'</h2></a><p class="">'.substr($resBlog[0]->content,0,120).'...</p><div class="article_stats"><div class="counts"><span class="blogging_icons thank"></span>'.$likes.' Thanks</div><div class="counts"><span class="blogging_icons comment"></span>'.$comments.' Comments</div></div></div></div><div class="row blogactivity"><div class="col-sm-3 col-xs-6 text-center"><button class=""><span class="blogging_icons thank"></span> Thank</button></div><div class="col-sm-3 col-xs-6 text-center"><button data-toggle="fpopover" data-container="header" data-placement="top" data-trigger="focus" data-html="true" id="share"><span class="blogging_icons share"></span> Share</button></div><div class="col-sm-6 col-xs-12"><a href="'.$baseurl.'blog/view/'.$resBlog[0]->slug.'" class="form-control commentanchor txtdark">Write a comment</a></div></div></div>';
		}else{
			$record = 2;
		}
		
		$resMedia = $this->Mdl_media->getMediaByDoctorId($uid,$start,$record);
		if($resMedia !== 'no'){
			$likes = $this->Mdl_media->getLikeCount($resMedia[0]->mediaId);
			$comments = $this->Mdl_media->getCommentCount($resMedia[0]->mediaId);
					
			$finalData .= '<div class="db_article_box fade_anim">
								<div class="row articleDetails">
									<div class="col-sm-6 col-xs-12 author_info">
										<div class="author_dp">
											<a href="">
												<img src="'.base_url().$url.'">
											</a>
										</div>
										<div class="author_name">
											<strong><a href="" class="txtblue">'.$name.'</a></strong> shared video
										</div>
									</div>';
				
				if($resMedia[0]->ctype == 'youtube'){	
					$finalData .=  '<div class="col-sm-6 col-xs-12 article_pic">
								<div class="img_holder">
									<iframe src="'.$resMedia[0]->url.'" frameborder="0" allowfullscreen></iframe>
								</div>
							</div>';
				}else if($resMedia[0]->ctype == 'upload'){
					$finalData .= '<div class="col-sm-6 col-xs-12 article_pic">
								<div class="img_holder">
									<video width="400" controls controlsList="nodownload">
										<source src="'.$resMedia[0]->url.'">
										Your browser does not support HTML5 video.
									</video>
								</div>
							</div>'; 
				}else{
					$finalData .= '<div class="col-sm-6 col-xs-12">
								<div class="img_holder" style="text-align:center; margin-top:60px;">
									<audio controls controlsList="nodownload">
										<source src="'.$resMedia[0]->url.'">
										Your browser does not support HTML5 video.
									</audio>
								</div>
							</div>';
				}
				
				$finalData .= '<div class="col-sm-6 col-xs-12 article_info">
								<div class="articleDate txtdark">'.date("d-M, Y ",strtotime($resMedia[0]->createdDate)).'</div>
								<a href="'.base_url().'media/view/'.$resMedia[0]->slug.'" class="txtblue">
									<h2>'.$resMedia[0]->title.'</h2>
								</a>
								<p class="">'.substr($resMedia[0]->description,0,120).'...</p>
								<div class="article_stats">
									<div class="counts"><span class="blogging_icons thank"></span>'.$likes.' Thanks</div>
									<div class="counts"><span class="blogging_icons comment"></span>'.$comments.' Comments</div>
								</div>
							</div>
						</div>
						<div class="row blogactivity">
							<div class="col-sm-3 col-xs-6 text-center">
								<button class=""><span class="blogging_icons thank"></span> Thank</button>
							</div>
							<div class="col-sm-3 col-xs-6 text-center">
								<button><span class="blogging_icons share"></span> Share</button>
							</div>
							<div class="col-sm-6 col-xs-12">
								<a href="" class="form-control commentanchor txtdark">Write a comment</a>
							</div>        
						</div>
					</div>';
		}else{
			$record = 3;
		}
		
		$resForum = $this->Mdl_forum->getForumByDoctorId($uid, $start, $record);
		if($resForum !== 'no'){
			$answers = $this->Mdl_forum->getAnswerCount($resForum[0]->forumId);
				
			$finalData .= '<div class="db_article_box fade_anim">
				<div class="row articleDetails">
					<div class="col-xs-12 author_info">
						<div class="author_dp">
							<a href="">
								<img src="'.base_url().$url.'">
							</a>
						</div>
						<div class="author_name">
							<strong>
								<a href="" class="txtblue">'.$name.'</a>
							</strong> asked
						</div>
					</div>
            
					<div class="col-xs-12 article_info">
						<div class="articleDate txtdark">'.date("d-M, Y ",strtotime($resForum[0]->createdDate)).'</div>
						<a href="'.base_url().'forum/view/'.$resForum[0]->slug.'" class="txtblue">
							<h2>'.$resForum[0]->question.'</h2>
						</a>
						<div class="article_stats">
							<div class="counts"><span class="blogging_icons comment"></span> '.$answers.' answers</div>
						</div>
					</div>
				</div>
        
				<div class="row blogactivity">
					<div class="col-sm-3 col-xs-6 text-center"><button class=""><span class="blogging_icons thank"></span> Like</button></div>
					<div class="col-sm-3 col-xs-6 text-center"><button><span class="blogging_icons share"></span> Share</button></div>
					<div class="col-sm-6 col-xs-12"><a href="'.base_url().'forum/view/'.$resForum[0]->slug.'" class="form-control commentanchor txtdark">Write a answer</a></div>        
				</div>
			</div>';
		}
		
		if($resBlog == 'no' && $resMedia == 'no' && $resForum == 'no'){
			echo 1; exit;
		}else{
			echo $finalData;
		}
	}
	
	/********************************* Get Doctor Data **********************************/
	function getMyData(){
		$page =  $_GET['page'];
		$record = 1;
		$start = ($page - 1) * $record;
		
		$uid = $this->session->userdata('userId');
		$type = $this->session->type;
		$userData = $this->session->userdata('userData');
		$name = $userData['name']; 
		$url = $userData['image'];

		$finalData = '';
		$baseurl = base_url();
		
		$webinar = $this->Mdl_webinar->getDoctorWebinar($uid,$start,$record);
		
		if($webinar !== 'no'){
			foreach($webinar as $res){
				
$finalData .=  '<div class="db_article_box fade_anim">
					<div class="row articleDetails">
						<div class="col-sm-12 col-xs-12 author_info">
							<div class="author_dp">
								<a href="javascript:;"><img src="'.$baseurl.''.$url.'"></a>
							</div>
							<div class="author_name">
								<strong><a href="" class="txtblue">'.$name.'</a></strong> created webinar
							</div>
						</div>
						
						<div class="col-sm-12 col-xs-12 article_info">
							<div class="articleDate txtdark">';
						$now = strtotime("now");
						$start = strtotime($res->startTime);
						if($now < $start){
							$finalData .= '<strong class="upcomingTag">UPCOMING</strong>';
						}
						$finalData .= date("D, d M Y | h.i a",strtotime($res->startTime)).'</div>
							<a href="javascript:;" class="txtblue"><h2>'.$res->subject.'</h2></a>
							<p class="">'.substr($res->description,0,200).'...</p>
						</div>
					</div>
				</div>';
			} 
		}
		
		$resBlog = $this->Mdl_blog->getBlogByDoctorId($uid,$start,$record);
		if($resBlog !== 'no'){
			$likes = $this->Mdl_blog->getLikeCount($resBlog[0]->blogId);
			$comments = $this->Mdl_blog->getCommentCount($resBlog[0]->blogId);
					
			$finalData .= '<div class="db_article_box fade_anim"><div class="row articleDetails"><div class="col-sm-6 col-xs-12 author_info"><div class="author_dp"><a href=""><img src="'.$baseurl.''.$url.'"></a></div><div class="author_name"><strong><a href="" class="txtblue">'.$name.'</a></strong> shared article</div></div><div class="col-sm-6 col-xs-12 article_pic"><a href="" class="img_holder"><img src="'.$baseurl.'admin_assets/images/blog/'.$resBlog[0]->image.'"></a></div><div class="col-sm-6 col-xs-12 article_info"><div class="articleDate txtdark">'.date("d-M, Y ",strtotime($resBlog[0]->createdDate)).'</div><a href="'.$baseurl.'blog/read/'.$resBlog[0]->slug.'" class="txtblue"><h2>'.$resBlog[0]->title.'</h2></a><p class="">'.substr($resBlog[0]->content,0,120).'...</p><div class="article_stats"><div class="counts"><span class="blogging_icons thank"></span>'.$likes.' Thanks</div><div class="counts"><span class="blogging_icons comment"></span>'.$comments.' Comments</div></div></div></div><div class="row blogactivity"><div class="col-sm-3 col-xs-6 text-center"><button class=""><span class="blogging_icons thank"></span> Thank</button></div><div class="col-sm-3 col-xs-6 text-center"><button data-toggle="fpopover" data-container="header" data-placement="top" data-trigger="focus" data-html="true" id="share"><span class="blogging_icons share"></span> Share</button></div><div class="col-sm-6 col-xs-12"><a href="'.$baseurl.'blog/read/'.$resBlog[0]->slug.'/#comment" class="form-control commentanchor txtdark">Write a comment</a></div></div></div>';
		}else{
			$record = 2;
		}
		
		$resMedia = $this->Mdl_media->getMediaByDoctorId($uid,$start,$record);
		if($resMedia !== 'no'){
			$likes = $this->Mdl_media->getLikeCount($resMedia[0]->mediaId);
			$comments = $this->Mdl_media->getCommentCount($resMedia[0]->mediaId);
					
			$finalData .= '<div class="db_article_box fade_anim">
								<div class="row articleDetails">
									<div class="col-sm-6 col-xs-12 author_info">
										<div class="author_dp">
											<a href="">
												<img src="'.base_url().$url.'">
											</a>
										</div>
										<div class="author_name">
											<strong><a href="" class="txtblue">'.$name.'</a></strong> shared video
										</div>
									</div>';
				
				if($resMedia[0]->ctype == 'youtube'){	
					$finalData .=  '<div class="col-sm-6 col-xs-12 article_pic">
								<div class="img_holder">
									<iframe src="'.$resMedia[0]->url.'" frameborder="0" allowfullscreen></iframe>
								</div>
							</div>';
				}else if($resMedia[0]->ctype == 'upload'){
					$finalData .= '<div class="col-sm-6 col-xs-12 article_pic">
								<div class="img_holder">
									<video width="400" controls controlsList="nodownload">
										<source src="'.$resMedia[0]->url.'">
										Your browser does not support HTML5 video.
									</video>
								</div>
							</div>'; 
				}else{
					$finalData .= '<div class="col-sm-6 col-xs-12">
								<div class="img_holder" style="text-align:center; margin-top:60px;">
									<audio controls controlsList="nodownload">
										<source src="'.$resMedia[0]->url.'">
										Your browser does not support HTML5 video.
									</audio>
								</div>
							</div>';
				}
				
				$finalData .= '<div class="col-sm-6 col-xs-12 article_info">
								<div class="articleDate txtdark">'.date("d-M, Y ",strtotime($resMedia[0]->createdDate)).'</div>
								<a href="'.base_url().'media/watch/'.$resMedia[0]->slug.'" class="txtblue">
									<h2>'.$resMedia[0]->title.'</h2>
								</a>
								<p class="">'.substr($resMedia[0]->description,0,120).'...</p>
								<div class="article_stats">
									<div class="counts"><span class="blogging_icons thank"></span>'.$likes.' Thanks</div>
									<div class="counts"><span class="blogging_icons comment"></span>'.$comments.' Comments</div>
								</div>
							</div>
						</div>
						<div class="row blogactivity">
							<div class="col-sm-3 col-xs-6 text-center">
								<button class=""><span class="blogging_icons thank"></span> Thank</button>
							</div>
							<div class="col-sm-3 col-xs-6 text-center">
								<button><span class="blogging_icons share"></span> Share</button>
							</div>
							<div class="col-sm-6 col-xs-12">
								<a href="" class="form-control commentanchor txtdark">Write a comment</a>
							</div>        
						</div>
					</div>';
		}else{
			$record = 3;
		}
		
		$resForum = $this->Mdl_forum->getForumByDoctorId($uid, $start, $record);
		if($resForum !== 'no'){
			$answers = $this->Mdl_forum->getAnswerCount($resForum[0]->forumId);
				
			$finalData .= '<div class="db_article_box fade_anim">
				<div class="row articleDetails">
					<div class="col-xs-12 author_info">
						<div class="author_dp">
							<a href="">
								<img src="'.base_url().$url.'">
							</a>
						</div>
						<div class="author_name">
							<strong>
								<a href="" class="txtblue">'.$name.'</a>
							</strong> asked
						</div>
					</div>
            
					<div class="col-xs-12 article_info">
						<div class="articleDate txtdark">'.date("d-M, Y ",strtotime($resForum[0]->createdDate)).'</div>
						<a href="'.base_url().'forum/read/'.$resForum[0]->slug.'" class="txtblue">
							<h2>'.$resForum[0]->question.'</h2>
						</a>
						<div class="article_stats">
							<div class="counts"><span class="blogging_icons comment"></span> '.$answers.' answers</div>
						</div>
					</div>
				</div>
        
				<div class="row blogactivity">
					<div class="col-sm-3 col-xs-6 text-center"><button class=""><span class="blogging_icons thank"></span> Like</button></div>
					<div class="col-sm-3 col-xs-6 text-center"><button><span class="blogging_icons share"></span> Share</button></div>
					<div class="col-sm-6 col-xs-12"><a href="'.base_url().'forum/read/'.$resForum[0]->slug.'" class="form-control commentanchor txtdark">Write a answer</a></div>        
				</div>
			</div>';
		}
		
		if($webinar == 'no' && $resBlog == 'no' && $resMedia == 'no' && $resForum == 'no'){
			echo 1; exit;
		}else{
			echo $finalData;
		}
	}
	
	/********************************* Followers Listing **********************************/
	function myFollowers(){
		if(!Modules::run('site_security/isDoctor')){
			redirect('login','refresh');
		}
		
		$id = $this->session->userdata('userId'); 
		$follower = $this->Mdl_doctor->getFollowersDetails($id);
		
		if($follower !== 'no'){
			$mem = explode(",",$follower[0]->followedMem);
			$memData = array();
			foreach($mem as $val){
				$user = array();
				$res = $this->Mdl_member->getMemmberDetails($val);
				$user['name'] = $res[0]->name;
				$user['email'] = $res[0]->email;
				$user['age'] = $res[0]->age;
				$user['city'] = $res[0]->city;
				$user['mobile'] = '+'.$res[0]->isd.' '.$res[0]->mobile;
				$user['image'] = base_url().$res[0]->profileImage;
				$memData[] = $user;
			}
			$data['memberList'] = $memData;
		}else{
			$data['memberList'] = 'No data';
		}
		
		$data['module'] = 'doctor';
		$data['viewFile'] = 'my-followers';
		$data['follower'] = '1';
		$template = 'doctor';
		echo Modules::run('template/'.$template, $data);
	}
	
	/********************************** Get Notification List **********************************/
	function getDoctorNotifications(){
		$uid = $this->session->userdata('userId'); 
		$notification = $this->Mdl_notifications->getDoctorNotification($uid);	
		return $notification;
	}
	
	/********************************* Doctor Logout **********************************/
	function logout(){
		$sessionData = array(
							'userId'=>'',
							'type'=>''
					);
		$this->session->unset_userdata('userData', $sessionData);
		$this->session->sess_destroy();
     	redirect('login','refresh');
	}
	
/*********************************************************************************************************************************************************************************************************************************************************************************
														Admin Doctor Management	
*********************************************************************************************************************************************************************************************************************************************************************************/
	
	/********************************* Doctor Listing Page **********************************/
	function listDoctor(){
		if(!Modules::run('site_security/isAdmin')){
			redirect('admin','refresh');
		}
		
		$getDoctor = $this->Mdl_doctor->listDoctor();
		
		if($getDoctor !== 'No Data'){
			$docs = array();
			foreach($getDoctor as $val){
				$res = array();
				$res['regId'] = $val->regId;
				$res['name'] = $val->name;
				$res['image'] = $val->profileImage;
				$res['mobile'] = $val->mobile;
				$res['email'] = $val->email;
	
				$res1 = explode(",",$val->degree);
				$deg = '';
				foreach($res1 as $val1){
					$deg .= $this->Mdl_doctor->findDegreeName($val1).'<br>';
				}
				$res['degree'] = $deg;
				
				$res2 = explode(",",$val->speciality);
				$sp = '';
				foreach($res2 as $val2){
					$sp .= $this->Mdl_doctor->findSpecialityName($val2).'<br>';
				}
				$res['speciality'] = $sp;
				
				$res['isActive'] = $val->isActive;
				$res['isHome'] = $val->isHome;
				$res['isFeatured'] = $val->isFeatured;
				$docs[] = $res;
			}
			$data['doctors'] = $docs;
		}else{
			$data['doctors'] = 'No Data';
		}
		$data['viewFile'] = "list-doctor";
		$data['page'] = 'listDoctor';
		$template = 'admin';
		echo Modules::run('template/'.$template, $data);
	}
	
	/****************************************** Admin Add Doctor ************************************************/
	function addDoctor(){
		if(!Modules::run('site_security/isAdmin')){
			redirect('admin','refresh');
		}
		
		$data['isd'] = $this->Mdl_location->getMobileCodes();
		$data['degree'] = $this->Mdl_doctor->getDegree();
		$data['speciality'] = Modules::run('speciality/getAllSpecialities');
		$data['viewFile'] = "add-doctor";
		$data['module'] = "doctor";
		$data['page'] = "listDoctor";
		$template = 'admin';
		echo Modules::run('template/'.$template, $data);
	}

	/****************************************** Admin Add Doctor Action ************************************************/
	function addDoctorAction(){
		$content = $this->input->post();
		
		$this->form_validation->set_rules("name","Name","trim|required|xss_clean|regex_match[/^[A-Z a-z]+$/]",
		array(
			'required' => '<b>%s is required.</b>',
			'regex_match' => '<b>%s should not contain numbers or other special characters.</b>'
		));
		$this->form_validation->set_rules("email","Email","required|trim|valid_email|xss_clean",
		array(
			'required'=>'<b>Email is Required</b>',
			'valid_email'=>'<b>Email is not valid</b>'
		));
		$this->form_validation->set_rules("pass","Password","trim|required|xss_clean|min_length[6]",
		array(
			'required'      => '<b>%s is required.</b>',
			'min_length'   => '<b>Password should be 6 characters long.</b>'
		));
		
		$this->form_validation->set_rules("gender","Gender","required|xss_clean",
		array(
			'required'=>'<b>Gender is required</b>'
		));
		$this->form_validation->set_rules("isd","ISD","trim|required|xss_clean",
		array(
			'required'      => '<b>Please Select ISD</b>'
		));
		$this->form_validation->set_rules("mobile","Mobile Number","trim|required|xss_clean|regex_match[/^[0-9]*$/]|is_unique[registration.mobile]",
		array(
			'required'      => '<b>%s is required.</b>',
			'regex_match'   => '<b>Please enter valid %s.</b>',
			'is_unique'     => '<b>%s is already registered.</b>',
		));
		$this->form_validation->set_rules("speciality[]","Speciality","required|xss_clean",
		array(
			'required'=>'<b>Please select Speciality</b>'
		));
		$this->form_validation->set_rules("degree[]","Degree","required|xss_clean",
		array(
			'required'=>'<b>Please select Degree</b>'
		));
		$this->form_validation->set_rules("experience","Experience","required|xss_clean",
		array(
			'required'=>'<b>Please select Experience</b>'
		));
		$this->form_validation->set_rules("city","City","required|xss_clean",
		array(
			'required'=>'<b>Please select City</b>'
		));
		
		if($this->form_validation->run() == FALSE){
			echo validation_errors(); exit;
		}else{	
			$password =  Modules::run('site_security/makeHash',$content['pass']);
			
			$data=array(
				'type'=>'doc',
				'name'=>$content['name'],
				'email'=>$content['email'],
				'isd' => $content['isd'],
				'mobile' => $content['mobile'],
				'otp' => '111111',
				'counts' => '1',
				'expireAt' => date("Y-m-d H:i:s"),
				'password'=>$password,
				'mobileVerify'=>'1',
				'statusCode'=>'4',
				'isActive'=>'1',
				'adminId' => $this->session->userId,
				'ipAddress'=> $_SERVER['REMOTE_ADDR'],
				'createdDate'=>date("Y-m-d H:i:s"),
				'modifiedDate'=>date("Y-m-d H:i:s")
			);
	
			$id = $this->Mdl_doctor->insert('registration', $data);
			
			if(!empty($_FILES['profile']['name'])){
				$profileimg = $_FILES['profile']['name'];
				$imgname=str_replace(array(" ","'"),"_",time().$profileimg);
				$img = $this->uploadFile($imgname,"profile","doctor",$id);	
				if($img !== 1){
					echo $img; exit;
				}
				$imgUrl = 'admin_assets/images/doctor/'.$id.'/'.$imgname;
			}else{
				$imgUrl = "admin_assets/images/default.jpg"; 
			}
						
			$data=array(
				'regId'=>$id,
				'gender'=>$content['gender'],
				'speciality'=>implode(",",$content['speciality']),
				'degree'=>implode(",",$content['degree']),
				'experience'=>$content['experience'],
				'city'=>$content['city'],
				'profileImage'=>$imgUrl,
				'govProof'=>'',
				'degreeProof'=>'',
				'medProof'=>'',
				'isFeatured'=>'0',
				'followedMem'=>'',
				'education'=>'',
				'membership'=>'',
				'myself'=>'',
				'language'=>'',
				'clinicAddress'=>'',
				'contacts'=>'',
				'timing'=>'',
				'createdDate'=>date("Y-m-d H:i:s"),
				'modifiedDate'=>date("Y-m-d H:i:s")
			);
			
			$this->Mdl_doctor->insert('doctor', $data);	
			
			$data2 =array(
				'statusCode'=> '6',
				'modifiedDate'=>date("Y-m-d H:i:s")
			);
			$this->Mdl_doctor->update('registration', $data2, $id, 'id');	
			
			echo 1; exit;
		}
	}
	
	/********************************* Admin View Doctor Details **********************************/
	function viewDetails($id){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$uid = base64_decode($base_64);

			$userDetails = $this->Mdl_doctor->getDoctorDetails($uid);
			$data['details'] = $userDetails;
			
			$res1 = explode(",",$userDetails[0]->degree);
			$deg = '';
			foreach($res1 as $val1){
				$deg .= $this->Mdl_doctor->findDegreeName($val1).'&#13;&#10;';
			}
			$data['degree'] = $deg;
				
			$res2 = explode(",",$userDetails[0]->speciality);
			$sp = '';
			foreach($res2 as $val2){
				$sp .= $this->Mdl_doctor->findSpecialityName($val2).'&#13;&#10;';
			}
			$data['speciality'] = $sp;
			
			$data['viewFile'] = "view-doctor";
			$data['page'] = 'viewDoctor';
			$template = 'admin';
			echo Modules::run('template/'.$template, $data);
		}
	}
	
	/********************************* Admin Doctor Inactive Action **********************************/
	function inActive_action($id){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$uid = base64_decode($base_64);
			$this->Mdl_doctor->statusInactive($uid);
			redirect('doctor/list-doctor');		
		}
	}
	
	/********************************* Admin Doctor Active Action **********************************/
	function active_action($id){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$uid = base64_decode($base_64);
			$this->Mdl_doctor->statusActive($uid);
			redirect('doctor/list-doctor');	
		}
	}
	
	/********************************* Mark Doctor As Featured **********************************/
	function makeFeatured($id){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$uid = base64_decode($base_64);
			$this->Mdl_doctor->markFeatured($uid);
			redirect('doctor/list-doctor');	
		}
	}
	
	/********************************* Unmark Doctor As Featured **********************************/
	function removeFeatured($id){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$uid = base64_decode($base_64);
			$this->Mdl_doctor->unmarkFeatured($uid);
			redirect('doctor/list-doctor');	
		}
	}
	
	/********************************* Mark Doctor As Featured **********************************/
	function setHome($id){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$uid = base64_decode($base_64);
			$this->Mdl_doctor->setHome($uid);
			redirect('doctor/list-doctor');	
		}
	}
	
	/********************************* Unmark Doctor As Featured **********************************/
	function removeHome($id){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$uid = base64_decode($base_64);
			$this->Mdl_doctor->removeHome($uid);
			redirect('doctor/list-doctor');	
		}
	}
	
	/*********************************  Doctor Delete Action **********************************/
	function deleteDoctor($id){
		if(!empty($id)){
			$uid = base64_decode($id);
			$deleteData = $this->Mdl_doctor->deleteById($uid);
			redirect('doctor/listdoctor');	
		}
	}
	
	/********************************* File Upload **********************************/
	function uploadFile($imageName, $key, $folderName, $id){
		$config['file_name'] = $imageName;
		$path = './admin_assets/images/'.$folderName.'/'.$id;
		$config['upload_path'] = $path; 
		$config['allowed_types'] = "jpeg|jpg|png";
		$config['max_width']  = '5000';
		$config['max_height']  = '5000';
		
		if (!is_dir($path)){
			mkdir($path, 0777, true);
        }
		
		$this->load->library('upload',$config);
		$this->upload->initialize($config);
		   
		 if (!$this->upload->do_upload($key)){
			return $this->upload->display_errors();
		}else{
			return 1;
		} 
	}
}