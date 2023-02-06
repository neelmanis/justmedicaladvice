<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Member extends MX_Controller{
	
	function __construct() {
		parent::__construct();
		$this->load->model('Mdl_member');
		$this->load->model('doctor/Mdl_doctor');
		$this->load->model('category/Mdl_category');
		$this->load->model('notifications/Mdl_notifications');
	}
	
/*****************************************************************************************************************************************************************************************************************************************************************************************														Memeber Registration Steps	
*****************************************************************************************************************************************************************************************************************************************************************************************/

	/********************************** Profile Setup Form **********************************/
	function memberProfile(){
		if(!Modules::run('site_security/isMember')){
			redirect('login','refresh');
		}
		
		$id = $this->session->userdata('userId');
		$isd = $this->Mdl_member->getISD($id);
		$cityDump = $this->Mdl_member->getCities($isd[0]->isd);
		
		$data['isHomePage'] = '0';
		$parentCat = $this->Mdl_category->getMainCategory();
		$allCategories = array();
		foreach($parentCat as $val){
			$subCategory = $this->Mdl_category->getSubCategory($val->catId);
			$allCategories[$val->catName] = $subCategory; 
		}
		
		$data['parentCat'] = $parentCat;
		$data['subCat'] = $allCategories;
		$data['location'] = $cityDump;
		$data['viewFile'] = "set-profile";
		$data['module'] = "member";
		$data['scriptFile'] = "member-set-profile";
		$template = 'registration';
		echo Modules::run('template/'.$template, $data);
	}
	
	/********************************** Profile Submit Action **********************************/
    function memberProfileSubmit(){
		$content = $this->input->post();
		
		$this->form_validation->set_rules("gender","Gender","required|xss_clean");
		$this->form_validation->set_rules("age","Age","required|xss_clean");
		$this->form_validation->set_rules("city","City","required|xss_clean");
		$this->form_validation->set_rules("cat[]","Category","required",array(
                'required'      => 'You have not provided Field of Interest.'
        ));
		
		if($this->form_validation->run() == FALSE){
			echo validation_errors(); exit;
		}else{
			$id = $this->session->userdata('userId');
			if(!empty($_FILES['fileupload']['name'])){
				$profileimg = $_FILES['fileupload']['name'];
				$imgname=str_replace(array(" ","'"),"_",time().$profileimg);
				$img = $this->uploadFile($imgname,"fileupload","member");	
				if($img !== 1){
					echo $img; exit;
				}
				$imgPath = 'admin_assets/images/member/'.$imgname;
			}else{
				$imgPath = "admin_assets/images/default.jpg"; 
			}
			
			$data=array(
				'regId'=>$id,
				'gender'=>$content['gender'],
				'age'=>$content['age'],
				'city'=>$content['city'],
				'profileImage'=>$imgPath,
				'height'=> 0,
				'weight'=> 0,
				'bmi'=> 0,
				'ailments'=>'',
				'allergies'=>'',
				'familyHistory'=>'',
				'alcohol'=>'0',
				'tobacco'=>'0',
				'other'=>'',
				'fieldsOfInterest'=> implode(",",$content['cat']),
				'followedDocs'=>'',
				'createdDate'=>date("Y-m-d H:i:s"),
				'modifiedDate'=>date("Y-m-d H:i:s")
			);
			$this->Mdl_member->insert('member',$data);
			
			$data2 =array(
				'statusCode'=> '6',
				'modifiedDate'=>date("Y-m-d H:i:s")
			);
			$this->Mdl_member->update('registration', $data2, $id, 'id');		
			echo 1; exit;
		}
	}
	
/*****************************************************************************************************************************************************************************************************************************************************************************************
											Memeber Dashboard Functionality	
*****************************************************************************************************************************************************************************************************************************************************************************************/
	
	/********************************** Dashboard Page **********************************/
	function home(){
		if(!Modules::run('site_security/isMember')){
			redirect('login','refresh');
		}
		redirect('dashboard/member');
	}
	
	/********************************** Change Password Form **********************************/
	function changePassword(){
		if(!Modules::run('site_security/isMember')){
			redirect('login','refresh');
		}
		
		$data['module'] = 'member';
		$data['viewFile'] = 'change-password';
		$data['scriptFile'] = 'member';
		$data['home'] = '1';
		$template = 'member';
		echo Modules::run('template/'.$template, $data);
	}
	
	/********************************** Change Password Action **********************************/
	function changePasswordAction(){
		$content = $this->input->post();
		
		$this->form_validation->set_rules("oldPassword","Old Password","trim|required|xss_clean");
		$this->form_validation->set_rules("newPassword","New Password","trim|required|xss_clean|min_length[6]");
		$this->form_validation->set_rules("cnfNewPassword","Confirm New Password","trim|required|xss_clean|matches[newPassword]");
		
		if($this->form_validation->run() == FALSE){
			echo validation_errors(); exit;
		}else{
			$id = $this->session->userdata('userId'); 
			$storedPass = $this->Mdl_member->getMemmberPassword($id);
			$oldPass =  Modules::run('site_security/makeHash',$content['oldPassword']);
			if($storedPass == $oldPass){
				$changePass = Modules::run('site_security/makeHash',$content['newPassword']);
				$data=array(
					'password' => $changePass, 
					'modifiedDate'=>date("Y-m-d H:i:s")
				);
				$this->Mdl_member->update('registration', $data, $id, 'id');
				echo 1; exit;
			}else{
				echo "You Have Entered Wrong Password !!";
			}
		}		
	}
	
	/********************************** Profile Page **********************************/
	function myProfile(){
		if(!Modules::run('site_security/isMember')){
			redirect('login','refresh');
		}
		
		$id = $this->session->userdata('userId'); 
		$data['memData'] = $this->Mdl_member->getMemmberDetails($id);
		$data['module'] = 'member';
		$data['viewFile'] = 'my-profile';
		$data['scriptFile'] = 'member';
		$data['home'] = '1';
		$template = 'member';
		echo Modules::run('template/'.$template, $data);
	}
	
	/********************************** Edit Profile Action **********************************/
	function editProfileAction(){
		$content = $this->input->post();
		
		$this->form_validation->set_rules("name","Name","trim|required|xss_clean");
		$this->form_validation->set_rules("email","Email","trim|valid_email|xss_clean");
		$this->form_validation->set_rules("age","Age","trim|required|xss_clean");
		$this->form_validation->set_rules("gender","Gender","trim|required|xss_clean");
		$this->form_validation->set_rules("city","Location","trim|required|xss_clean");
		$this->form_validation->set_rules("height","Height","trim|xss_clean");
		$this->form_validation->set_rules("weight","Weight","trim|xss_clean");
		$this->form_validation->set_rules("bmi","BMI","trim|xss_clean");
		$this->form_validation->set_rules("ailments","Ailments","trim|xss_clean");
		$this->form_validation->set_rules("allergies","Allergies","trim|xss_clean");
		$this->form_validation->set_rules("familyHistory","Family History","trim|xss_clean");
		$this->form_validation->set_rules("alcohol","alcohol","trim|xss_clean");
		$this->form_validation->set_rules("tobacco","Tobacco","trim|xss_clean");
		$this->form_validation->set_rules("other","Other","trim|xss_clean");
		
		if($this->form_validation->run() == FALSE){
			echo validation_errors(); exit;
		}else{
			$id = $this->session->userdata('userId');
			if(!empty($_FILES['fileupload']['name'])){
				$profileimg = $_FILES['fileupload']['name'];
				$imgname=str_replace(array(" ","'"),"_",time().$profileimg);
				$img = $this->uploadFile($imgname,"fileupload","member");	
				if($img !== 1){
					echo $img; exit;
				}
				$imgPath = 'admin_assets/images/member/'.$imgname;
			}else{
				$imgPath = $content['imageName'];
			}
			
			$data=array(
				'gender'=>$content['gender'],
				'age'=>$content['age'],
				'city'=>$content['city'],
				'profileImage'=>$imgPath,
				'height'=>$content['height'], 
				'weight'=>$content['weight'], 
				'bmi'=>$content['bmi'], 
				'ailments'=>$content['ailments'], 
				'allergies'=>$content['allergies'], 
				'familyHistory'=>$content['familyHistory'], 
				'alcohol'=>$content['alcohol'], 
				'tobacco'=>$content['tobacco'], 
				'other'=>$content['other'], 
				'modifiedDate'=>date("Y-m-d H:i:s")
			);
			$this->Mdl_member->update('member', $data, $id, 'regId');;
			
			$data2 =array(
				'name'=> $content['name'],
				'email'=> $content['email'],
				'modifiedDate'=>date("Y-m-d H:i:s")
			);
			$this->Mdl_member->update('registration', $data2, $id, 'id');

			$mem = $this->Mdl_member->getMemmberDetails($id);		
			foreach ($mem as $row) {
				$userId = $row->regId;
				$type = $row->type;
				$name = $row->name;	
				$image = $row->profileImage;	
				$doclist = $row->followedDocs;
			}
			
			$sessionData = array(
								'userId'=>$userId,
								'type'=>$type,
								'name'=>$name,
								'image'=>$image,
								'doclist'=>$doclist
							);
							
			$this->session->set_userdata('userData', $sessionData);
			echo 1; exit;
		}
	}
	
	/********************************** Doctor Search Page **********************************/
	function searchDoctor(){
		if(!Modules::run('site_security/isMember')){
			redirect('login','refresh');
		}
		
		$followedDocs = $this->getDocList();
		if($followedDocs !== 'No Data'){
			$docs = $this->Mdl_doctor->searchDoctors(implode(",",$followedDocs));
		}else{
			$docs = $this->Mdl_doctor->searchDoctors($followedDocs);
		}
		
		if($docs !== 'no'){
			$doctorList = array();
			foreach($docs as $res){
				$list = array();
				
				$id = base64_encode($res->regId);
				$id = str_replace(str_repeat('=',strlen($id)/4),"",$id);
				$list['regId'] = $id;
				$list['name'] = $res->name;
				$list['experience'] = $res->experience;
				$list['city'] = $res->city;
				$list['image'] = base_url().$res->profileImage;
				$list['featured'] = $res->isFeatured;
				
				$res1 = explode(",",$res->degree);
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
				
				$res2 = explode(",",$res->speciality);
				$list['speciality'] = $this->Mdl_doctor->findSpecialityName($res2[0]);
				
				$res3 = explode(",",$res->followedMem);
				$list['followedMem'] = sizeof($res3);
				
				$list['answerCount'] = $this->Mdl_doctor->findAnswerCount($res->regId);
				$list['thanks'] = $this->Mdl_doctor->findThanksCount($res->regId);
				$list['feedbackCount'] = $this->Mdl_doctor->findFeedbackCount($res->regId);
				
				$doctorList[] = $list;
			}
			$data['doctorsData'] = $doctorList;
		}else{
			$data['doctorsData'] = 'No Data';
		}
		
 		$data['module'] = 'member';
		$data['viewFile'] = 'search-doctor';
		$data['scriptFile'] = 'doctor-search';
		$data['home'] = '1';
		$template = 'member';
		echo Modules::run('template/'.$template, $data);
	}
	
	/********************************** Followed Doctors Listing **********************************/
	function followedDoctors(){
		if(!Modules::run('site_security/isMember')){
			redirect('login','refresh');
		}
		
		$followedDocs = $this->getDocList();
		if($followedDocs !== 'No Data'){
			$doctorList = array();
			foreach($followedDocs as $res){
				$list = array();
				$docs = $this->Mdl_doctor->getDoctorDetails($res);
				
				$id = base64_encode($docs[0]->regId);
				$id = str_replace(str_repeat('=',strlen($id)/4),"",$id);
				$list['regId'] = $id;
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
				$list['speciality'] = $this->Mdl_doctor->findSpecialityName($res2[0]);
				
				$res3 = explode(",",$docs[0]->followedMem);
				$list['followedMem'] = sizeof($res3);
				
				$list['answerCount'] = $this->Mdl_doctor->findAnswerCount($docs[0]->regId);
				$list['thanks'] = $this->Mdl_doctor->findThanksCount($docs[0]->regId);
				$list['feedbackCount'] = $this->Mdl_doctor->findFeedbackCount($docs[0]->regId);
				
				$doctorList[] = $list;
			}
			$data['doctorsData'] = $doctorList;
		}else{
			$data['doctorsData'] = 'No';
		}
				
 		$data['module'] = 'member';
		$data['viewFile'] = 'doctor-listing';
		$data['scriptFile'] = 'member-doctor-list';
		$data['docList'] = '1';
		$template = 'member';
		echo Modules::run('template/'.$template, $data);
	}
	
	/********************************** Select Category **********************************/
	function selectCategory(){
		if(!Modules::run('site_security/isMember')){
			redirect('login','refresh');
		}
		
		$id = $this->session->userdata('userId');
		$interest = $this->Mdl_member->getInterest($id);
		$interestList = explode(",",$interest[0]->fieldsOfInterest);
		
		$parentCat = $this->Mdl_category->getMainCategory();
		$allCategories = array();
		foreach($parentCat as $val){
			$subCategory = $this->Mdl_category->getSubCategory($val->catId);
			$allCategories[$val->catName] = $subCategory; 
		}
		
		$data['parentCat'] = $parentCat;
		$data['subCat'] = $allCategories;
		$data['interest'] = $interestList;
		$data['module'] = 'member';
		$data['viewFile'] = 'select-category';
		$data['scriptFile'] = 'member-select-category';
		$data['home'] = '1';
		$template = 'member';
		echo Modules::run('template/'.$template, $data);
	}
	
	/********************************** Categoty Update Action **********************************/
	function updateInterest(){
		$content = $this->input->post();
		$this->form_validation->set_rules("cat[]","Category","required");
		if($this->form_validation->run() == FALSE){
			echo 1; exit;
		}else{
			$id = $this->session->userdata('userId');
			
			$data=array(
				'fieldsOfInterest'=> implode(",",$content['cat']),
				'modifiedDate'=>date("Y-m-d H:i:s")
			);
			
			$this->Mdl_member->update('member',$data,$id,'regId');
			echo 2; exit;
		}
	}
	
	/********************************** Get Doctors List **********************************/
	function getDocList(){
		$uid = $this->session->userdata('userId'); 
		$mem = $this->Mdl_member->getMemmberDetails($uid);	
		if($mem[0]->followedDocs !== ''){
			$data = explode(",",$mem[0]->followedDocs);
			return $data;
		}else{
			return 'No Data';
		}
	}
	
	/********************************** Get Notification List **********************************/
	function getMemberNotifications(){
		$uid = $this->session->userdata('userId'); 
		$notification = $this->Mdl_notifications->getMemberNotification($uid);	
		return $notification;
	}
	
	/********************************** Follow Doctors **********************************/
	function followDoctor(){
		$content = $this->input->post();
		$id = $content['docId'];
		$base_64 = $id . str_repeat('=', strlen($id) % 4);
		$docid = base64_decode($base_64);
		$doc = $this->Mdl_doctor->getDoctorDetails($docid);
		
		$uid = $this->session->userdata('userId'); 
		$mem = $this->Mdl_member->getMemmberDetails($uid);
		
		if($mem[0]->followedDocs == ''){
			$dlist = $docid;
		}else{
			$dlist = $mem[0]->followedDocs .','.$docid;
		}
		
		$data1=array(
			'followedDocs'=>$dlist,
			'modifiedDate'=>date("Y-m-d H:i:s")
		);
		
		if($doc[0]->followedMem == ''){
			$mlist = $uid;
		}else{
			$mlist = $doc[0]->followedMem .','.$uid;
		}
		
		$data2=array(
			'followedMem'=>$mlist,
			'modifiedDate'=>date("Y-m-d H:i:s")
		);
				
		$this->Mdl_member->update('member',$data1,$uid,'regId');
		$this->Mdl_doctor->update('doctor',$data2,$docid,'regId');
		
		echo 'You are now following '.$doc[0]->name;
	}
	
	/********************************** Unfollow Doctors **********************************/
	function unfollowDoctor(){
		$content = $this->input->post();
		$id = $content['docId'];
		$base_64 = $id . str_repeat('=', strlen($id) % 4);
		$docid = base64_decode($base_64);
		$doc = $this->Mdl_doctor->getDoctorDetails($docid);
		$memList = explode(",",$doc[0]->followedMem);
		
		$uid = $this->session->userdata('userId'); 
		$mem = $this->Mdl_member->getMemmberDetails($uid);
		$docList = explode(",",$mem[0]->followedDocs);

		if(in_array($docid,$docList)){
			$my_array1 = array_diff($docList, array($docid));
			$updatedDocList = implode(",",$my_array1);
		}
		
		$data1=array(
			'followedDocs'=>$updatedDocList,
			'modifiedDate'=>date("Y-m-d H:i:s")
		);
		
		if(in_array($uid,$memList)){
			$my_array2 = array_diff($memList, array($uid));
			$updatedMemList = implode(",",$my_array2);
		}
		
		$data2=array(
			'followedMem'=>$updatedMemList,
			'modifiedDate'=>date("Y-m-d H:i:s")
		);

		$this->Mdl_member->update('member',$data1,$uid,'regId');
		$this->Mdl_doctor->update('doctor',$data2,$docid,'regId');
		
		echo 'You have unfollow '.$doc[0]->name;
	}

	/********************************** Member Logout **********************************/
	function logout(){
		$sessionData = array(
							'userId'=>'',
							'type'=>''
					);
		$this->session->unset_userdata('userData', $sessionData);
		$this->session->sess_destroy();
     	redirect('login','refresh');
	}
	
/*****************************************************************************************************************************************************************************************************************************************************************************************
														Admin Member Management	
*****************************************************************************************************************************************************************************************************************************************************************************************/
	
	/********************************** Member Listing **********************************/
	function listMember(){
		$getMember = $this->Mdl_member->listMember();
		
		if($getMember !== 'No Data'){
			$docs = array();
			foreach($getMember as $val){
				$res = array();
				$res['regId'] = $val->regId;
				$res['name'] = $val->name;
				$res['image'] = $val->profileImage;
				$res['mobile'] = $val->mobile;
				$res['email'] = $val->email;
				$res['age'] = $val->age;
				$res['isActive'] = $val->isActive;
				$mem[] = $res;
			}
			$data['members'] = $mem;
		}else{
			$data['members'] = 'No Data';
		}
		$data['viewFile'] = "list-member";
		$data['page'] = 'listMember';
		$template = 'admin';
		echo Modules::run('template/'.$template, $data);
	}
	
	/********************************** Member Inactive **********************************/
	function inActiveAction($id){
		if(!empty($id)){
			$uid = base64_decode($id);
			$this->Mdl_member->statusInactive($uid);
			redirect('member/list-member');		
		}
	}
		
	/********************************** Member Active **********************************/
	function activeAction($id){
		if(!empty($id)){
			$uid = base64_decode($id);
			$this->Mdl_member->statusActive($uid);
			redirect('member/list-member');	
		}
	}
	
	/********************************** Delete Member **********************************/
	function deleteMember($id){
		if(!empty($id)){
			$uid = base64_decode($id);
			$deleteData = $this->Mdl_member->deleteById($uid);
			redirect('member/list-member');	
		}
	}
	
	/********************************** Admin View Member Details **********************************/
	function viewDetails($id){
		if(!empty($id)){
			$uid = base64_decode($id);
			$userDetails = $this->Mdl_member->getMemmberDetails($uid);
			$res = explode(',',$userDetails[0]->fieldsOfInterest);
			$interest = '';
			foreach($res as $var){
				$str = $this->Mdl_category->catname($var);
				$interest .= $str . '&#13;&#10;';
			}
			$data['details'] = $userDetails;
			$data['interests'] = $interest;
			$data['viewFile'] = "details";
			$data['page'] = 'viewMember';
			$template = 'admin';
			echo Modules::run('template/'.$template, $data);
		}
	}
	
	/********************************** Upload File **********************************/
	function uploadFile($imageName, $key, $folderName){
		$config['file_name'] = $imageName;
		$path = './admin_assets/images/'.$folderName;
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