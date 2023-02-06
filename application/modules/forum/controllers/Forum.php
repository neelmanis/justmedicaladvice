<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Forum extends MX_Controller{
	
	function __construct(){
		parent::__construct();
		$this->load->model('Mdl_forum');	
		$this->load->model('doctor/Mdl_doctor');
	}

/*****************************************************************************************************************************************************************************************************************************************************************************************														Admin Functionality
*****************************************************************************************************************************************************************************************************************************************************************************************/

	/******************************************* Forum Listing *******************************************/ 
	function forumList(){
		if(!Modules::run('site_security/isAdmin')){
			redirect('admin','refresh');
		}
		
		$forum = $this-> Mdl_forum->listAll();
		if($forum !== 'no'){
			$forumData = array();
			foreach($forum as $val){
				$res = array();
				$id = base64_encode($val->forumId);
				$id = str_replace(str_repeat('=',strlen($id)/4),"",$id);
				$res['id'] = $id;
				$sp = $this->Mdl_forum->findSpecialityName($val->specialityId);
				if($sp == 'no'){
					$res['spName'] = "No Data";
				}else{
					$res['spName'] = $sp[0]->spName;
				}
				$res['question'] = $val->question;
				$res['slug'] = $val->slug;
				$res['postedBy'] = $val->postedBy;
				$res['name'] = $this->Mdl_forum->findName($val->postedBy,$val->userId);
				$res['visible'] = $val->visibleTo;
				$res['status'] = $val->isActive;
				$res['createdDate'] = $val->createdDate;
				$forumData[] = $res;
			}
			$data['forumList'] = $forumData;
		}else{
			$data['forumList'] = 'No Data';
		}
		$data['viewFile'] = "list-forum";
		$data['page'] = 'listForum';
		$template = 'admin';
		echo Modules::run('template/'.$template, $data);
	}
	
	/***************************************** Active Forum Action ***************************************/
	function active_action($id){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$fid = base64_decode($base_64);
			$this->Mdl_forum->statusActive($fid);
			redirect('forum/list-forum');	
		}
	}
	
	/***************************************** Inactive Forum Action ***************************************/
	function inActive_action($id){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$fid = base64_decode($base_64);
			$this->Mdl_forum->statusInactive($fid);
			redirect('forum/list-forum');	
		}
	}
	
	/***************************************** Delete Forum ***************************************/
	function deleteForum(){
		$fid = $_POST['forumId'];
		$base_64 = $fid . str_repeat('=', strlen($fid) % 4);
		$fId = base64_decode($base_64);
		$delete = $this->Mdl_forum->deleteById($fId);
		echo $delete;
	}
	
	/***************************************** Add Forum ***************************************/
	function addForum(){
		if(!Modules::run('site_security/isAdmin')){
			redirect('admin','refresh');
		}
		
		$data['speciality'] = $this->Mdl_forum->getSpeciality();
        $data['viewFile'] = 'add-forum';
		$data['page'] = 'addForum';
		$template = 'admin';
		echo Modules::run('template/'.$template, $data);
	}
	
	/***************************************** Add Forum Action ***************************************/
	function addForumAction(){
		$content = $this->input->post();
		
        $this->form_validation->set_rules("speciality","Speciality","required|xss_clean",array(
					'required'      => '<b>Speciality is not selected.</b>'
		));
		$this->form_validation->set_rules("question","Question","required|xss_clean",array(
					'required'      => '<b>Question is required</b>'
		));
		
		if($this->form_validation->run() == FALSE){
			echo validation_errors();
		}else{
			$find = array("/","_","?","(",")","-",":","'","!",".",",","\r","\n","\r\n");
			$replace = array("");
			$new_string = str_replace($find,$replace,strtolower($content['question']));
			$new_string = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $new_string); 
			$urlKey = str_replace(" ","-",trim($new_string));
				
			$urlCheck = $this->Mdl_forum->urlCheck($urlKey);
			
			if($urlCheck){  
				$data = array(
					'specialityId' => $content['speciality'],
					'question' => $content['question'],
					'slug' => $urlKey,
					'postedBy' => $this->session->type,
					'userId' => $this->session->userdata('userId'),
					'isActive' => '1',
					'visibleTo' => $content['visible'],
					'createdDate' =>date('Y-m-d H:i:s'),
					'modifiedDate' =>date('Y-m-d H:i:s')
				);
				
				$insert = $this->Mdl_forum->insert($data);
				echo 1; exit;
			}else{
				echo 2; exit;
			}
		}
	}
	
	/***************************************** Edit Forum ***************************************/
	function editForum($id){
		if(!Modules::run('site_security/isAdmin')){
			redirect('login','refresh');
		}
		
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$fid = base64_decode($base_64);
			$data['speciality'] = $this->Mdl_forum->getSpeciality();
			$forum = $this->Mdl_forum->findById($fid);			
			$data['getData'] = $forum;
			$data['viewFile'] = "edit-forum";
			$data['page'] = "editForum";
			$template = 'admin';
			echo Modules::run('template/'.$template, $data);		
		}
	}	

	/***************************************** Edit Forum Action ***************************************/
	function editForumAction(){
		$content = $this->input->post();
		
        $this->form_validation->set_rules("speciality","Speciality","required|xss_clean",array(
					'required'      => '<b>Speciality **</b> field is required'
		));
		$this->form_validation->set_rules("question","Question","required|xss_clean",array(
					'required'      => '<b>Question **</b> is required'
		));
		$this->form_validation->set_rules("status1","Status","required|xss_clean",array(
					'required'      => '<b>Status **</b> field is not selected'
		));
		$this->form_validation->set_rules("visible","Visible","required|xss_clean",array(
					'required'      => '<b>Select Visible type.</b>'
		));
		
		if($this->form_validation->run() == FALSE){
			echo validation_errors();
		}else{
			$find = array("/","_","?","(",")","-",":","!",".",",");
			$replace = array("");
			$new_string = str_replace($find,$replace,strtolower($content['question']));
			$new_string = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $new_string); 
			$urlKey = str_replace(" ","-",trim($new_string));
			
			if($urlKey !== $content['forumSlug']){
				$urlCheck = $this->Mdl_forum->urlCheck($urlKey);
			}else{
				$urlCheck = TRUE;
			}
			
			if($urlCheck){  
				$data = array(
					'specialityId' => $content['speciality'],
					'question' => $content['question'],
					'visibleTo' => $content['visible'],
					'slug' => $urlKey,
					'isActive' => $content['status1'],
					'modifiedDate' =>date('Y-m-d H:i:s')
				);
				$edit = $this->Mdl_forum->update($data,$content['forumId']);
				echo 1; exit;
			}else{
				echo 2; exit;
			}
		}
	}
	
	/***************************************** View Forum ***************************************/
	function viewForum($url){
		if(!Modules::run('site_security/isAdmin')){
			redirect('admin','refresh');
		}
		
		$forum = $this->Mdl_forum->getForumByUrl($url);

		$answer = $this->show_tree($forum[0]->forumId);
		if($answer == 'no'){
			$data['answers'] = '';
		}else{
			$data['answers'] = $answer;
		}
		
		if($forum[0]->postedBy == 'admin'){
			$userData = $this->Mdl_forum->getAdminDetails($forum[0]->userId);
		}else if($forum[0]->postedBy == 'doc'){
			$userData = $this->Mdl_forum->getDoctorDetails($forum[0]->userId);
		}else{
			$userData = $this->Mdl_forum->getMemberDetails($forum[0]->userId);
		}
		
		$data['forumDetails'] = $forum;
		$data['userDetails'] = $userData;
		$data['answerCount'] = $this->Mdl_forum->getAnswerCount($forum[0]->forumId);
		//$data['viewFile'] = 'forum-view';
		$data['viewFile'] = 'view-forum';
		$data['page'] = 'viewForum';
		$template = 'admin';
		echo Modules::run('template/'.$template, $data);
	}
	
	/***************************************** Flagged Answer Listing ***************************************/
	function flagComments(){
		if(!Modules::run('site_security/isAdmin')){
			redirect('admin','refresh');
		}
		
		$report = $this-> Mdl_forum->getFlagAnswerId();
		if($report !== 'No Data'){
			$comment = array();
			foreach($report as $re){
				$result = $this->Mdl_forum->getAnswerById($re->answerId);
				if($result !== 'No Data'){
					$set = array();
					$set['answerId'] = $result[0]->answerId;
					$set['answer'] = $result[0]->answer;
					$set['isActive'] = $result[0]->isActive;
					$comment[] = $set;
				}
			}
		}else{
			$comment = 'No Data';
		}
		$data['comments'] = $comment;
		$data['viewFile'] = "list-forum-comment";
		$data['page'] = 'forumFlagComments';
		$template = 'admin';
		echo Modules::run('template/'.$template, $data);
	}
	
	/***************************************** Deactivate Answer ***************************************/
	function inActiveComment($id){
		$result = $this->Mdl_forum->inActiveAnswer($id);
		redirect('forum/flag-comments');
	}
	
	/***************************************** Activate Answer ***************************************/
	function activeComment($id){
		$result = $this->Mdl_forum->activeAnswer($id);
		redirect('forum/flag-comments');
	}
	
/*****************************************************************************************************************************************************************************************************************************************************************************************														Doctor Functionality
*****************************************************************************************************************************************************************************************************************************************************************************************/
	
	/***************************************** Add Forum ***************************************/
	function postForum(){
		if(!Modules::run('site_security/isDoctor')){
			redirect('login','refresh');
		}
		
		$data['speciality'] = $this->Mdl_forum->getSpeciality();
        $data['module'] = 'forum';
        $data['viewFile'] = 'create-forum';
		$data['forum'] = '1';
		$data['scriptFile'] = 'doctor-forum-create';
		$template = 'doctor';
		
		echo Modules::run('template/'.$template, $data);   
	}
	
	/***************************************** Add Forum Action ***************************************/
	function addDoctorForumAction(){
		$content = $this->input->post();
		
		//$this->form_validation->set_error_delimiters('','');
        $this->form_validation->set_rules("speciality","Speciality","required|xss_clean",array(
					'required'      => '<b>Speciality is Not Selected</b>'
		));
		$this->form_validation->set_rules("question","Question","required|xss_clean",array(
					'required'      => '<b>Question  is Required</b>'
		));
		
		if($this->form_validation->run() == FALSE){
			echo validation_errors();
		}else{
			$find = array("/","_","?","(",")","-",":","'","!",".",",","\r","\n","\r\n");
			$replace = array("");
			$new_string = str_replace($find,$replace,strtolower($content['question']));
			$new_string = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $new_string); 
			$urlKey = str_replace(" ","-",trim($new_string));
				
			$urlCheck = $this->Mdl_forum->urlCheck($urlKey);
			
			if($urlCheck){  
				$data = array(
					'specialityId' => $content['speciality'],
					'question' => $content['question'],
					'slug' => $urlKey,
					'postedBy' => $this->session->type,
					'userId' => $this->session->userdata('userId'),
					'visibleTo' => $content['visible'],
					'isActive' => '1',
					'createdDate' =>date('Y-m-d H:i:s'),
					'modifiedDate' =>date('Y-m-d H:i:s')
				);
				
				$insert = $this->Mdl_forum->insert($data);
				echo 1; exit;
			}else{
				echo 2; exit;
			}
		}
	}
	
	/***************************************** Doctor View Forum ***************************************/
	function read($url){
		if(!Modules::run('site_security/isDoctor')){
			redirect('login','refresh');
		}
		
		$id = $this->session->userdata('userId');
		$doc = $this->Mdl_doctor->getDoctorDetails($id);
		$findForums = $this->Mdl_forum->getForumsForDocs($doc[0]->speciality,$doc[0]->followedMem);
		
		if($findForums != 'no'){
			$forums = array();
			foreach($findForums as $val){
				$final = array();
				$final['question'] = $val->question;
				$final['url'] = base_url().'forum/read/'.$val->slug;
				if($val->postedBy == 'admin'){
					$name = 'Just Medical Advice';
					$image = base_url().'admin_assets/images/JMA.png'; 
				}else if($val->postedBy == 'doc'){
					$user = $this->Mdl_forum->getDoctorDetails($val->userId);
					$name = $user[0]->name;
					$image = base_url().$user[0]->profileImage; 
				}else{
					$user = $this->Mdl_forum->getMemberDetails($val->userId);
					$name = $user[0]->name;
					$image = base_url().$user[0]->profileImage;
				}
				$final['uname'] = $name;
				$final['uimage'] = $image;
				$final['answer'] =  $this->Mdl_forum->getAnswerCount($val->forumId);
				$final['cdate'] = $val->createdDate;
				$forums[] = $final;
			}
		}else{
			$data['forumList'] = 'No Data';
		}
		$data['forumList'] = $forums;
		
		$forum = $this->Mdl_forum->getForumByUrl($url);
		if($forum[0]->postedBy == 'admin'){
			$userData = $this->Mdl_forum->getAdminDetails($forum[0]->userId);
		}else if($forum[0]->postedBy == 'doc'){
			$userData = $this->Mdl_forum->getDoctorDetails($forum[0]->userId);
		}else{
			$userData = $this->Mdl_forum->getMemberDetails($forum[0]->userId);
		}
		
		$answer = $this->show_tree($forum[0]->forumId);
		if($answer == 'no'){
			$data['answers'] = '';
		}else{
			$data['answers'] = $answer;
		}
		
		$data['forumDetails'] = $forum;
		$data['answerCount'] = $this->Mdl_forum->getAnswerCount($forum[0]->forumId);
		$data['userDetails'] = $userData;
		$data['speciality'] = $this->Mdl_forum->getSpeciality();
		$data['viewFile'] = 'view';
		$data['module'] = 'forum';
		$data['forum'] = '1';
		$data['scriptFile'] = 'doctor-forum';
		$template = 'doctor';
		
		echo Modules::run('template/'.$template, $data);
	}


/*****************************************************************************************************************************************************************************************************************************************************************************************														Member Functionality
*****************************************************************************************************************************************************************************************************************************************************************************************/

	/***************************************** Add Forum ***************************************/
	function createForum(){
		if(!Modules::run('site_security/isMember')){
			redirect('login','refresh');
		}
		
		$data['speciality'] = $this->Mdl_forum->getSpeciality();
        $data['module'] = 'forum';
        $data['viewFile'] = 'create-forum';
		$data['forum'] = '1';
		$data['scriptFile'] = 'member-forum-create';
		$template = 'member';
		
		echo Modules::run('template/'.$template, $data);
	}
	
	/***************************************** Add Forum Action ***************************************/
	function addMemberForumAction(){
		$content = $this->input->post();
		
		//$this->form_validation->set_error_delimiters('','');
        $this->form_validation->set_rules("speciality","Speciality","required|xss_clean",array(
					'required'      => '<b>Speciality is Not Selected</b>'
		));
		$this->form_validation->set_rules("question","Question","required|xss_clean",array(
					'required'      => '<b>Question  is Required</b>'
		));
		
		if($this->form_validation->run() == FALSE){
			echo validation_errors();
		}else{
			$find = array("/","_","?","(",")","-",":","'","!",".",",","\r","\n","\r\n");
			$replace = array("");
			$new_string = str_replace($find,$replace,strtolower($content['question']));
			$new_string = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $new_string); 
			$urlKey = str_replace(" ","-",trim($new_string));
				
			$urlCheck = $this->Mdl_forum->urlCheck($urlKey);
			
			if($urlCheck){  
				$data = array(
					'specialityId' => $content['speciality'],
					'question' => $content['question'],
					'slug' => $urlKey,
					'postedBy' => $this->session->type,
					'userId' => $this->session->userdata('userId'),
					'visibleTo' => $content['visible'],
					'isActive' => '1',
					'createdDate' =>date('Y-m-d H:i:s'),
					'modifiedDate' =>date('Y-m-d H:i:s')
				);
				
				$insert = $this->Mdl_forum->insert($data);
				echo 1; exit;
			}else{
				echo 2; exit;
			}
		}
	}
	
	/***************************************** Member Forum Listing ***************************************/
	function listAll(){
		if(!Modules::run('site_security/isMember')){
			redirect('login','refresh');
		}
		
		$result = $this->Mdl_doctor->getFeaturedDoctors();
		if($result !== 'No Data'){
			$featuredDoc = array();
			foreach($result as $res){
				$sub_array = array();
				$id = base64_encode($res->regId);
				$id = str_replace(str_repeat('=',strlen($id)/4),"",$id);
				$sub_array[] = $id;
				$img = $res->profileImage;
				$sub_array[] = $img;
				$name = $res->name;
				$sub_array[] = $name;
				$res2 = explode(",",$res->speciality);
				$sp = '';
				$size = sizeof($res2);
				
				if($size > 2){
					$size -= ($size-2);
				}
				
				for($i=0; $i< $size; $i++){
					$sp .= $this->Mdl_doctor->findSpecialityName($res2[$i]).'<br>';
				}
				$sub_array[] = $sp;
				$sub_array[] = $res->regId;
				if($res->followedMem !== ''){
					$sub_array[] = sizeof(explode(",",$res->followedMem));
				}else{
					$sub_array[] = "No ";
				}
				
				$featuredDoc[] = $sub_array;
			}
		}else{
			$featuredDoc = "No Data";
		}
		
		$data['docList'] = $featuredDoc;
		$data['speciality'] = $this->Mdl_forum->getSpeciality();
		$data['viewFile'] = 'list';
		$data['scriptFile'] = 'member-forum-list';
		$data['module'] = 'forum';
		$data['forum'] = '1';
		$data['searchSP'] = '';
		$data['searchSubmit'] = '';
		$template = 'member';
		echo Modules::run('template/'.$template, $data);
	}
	
	/***************************************** Member Get Forum List ***************************************/
	function getForumList(){
		$page =  $_GET['page'];
		$search =  $_GET['searchSP'];
		$searchKeys = $_GET['searchSubmited'];
		$searchSubmit =  explode(" ",$searchKeys);
		$record = 5;
		$start = ($page - 1) * $record;
		
		$uid = $this->session->userdata('userId');
		$utype = $this->session->userdata('type');
		
		if($utype == 'mem'){
			$udata = $this->Mdl_forum->getMemberDetails($uid);
			$categoryList = explode(",",$udata[0]->fieldsOfInterest);
			$specialityList = '';
			
			foreach($categoryList as $cat){
				if($specialityList !== ''){
					$specialityList .= ','.$this->Mdl_forum->getSpecialityByCategoryId($cat);
				}else{
					$specialityList .= $this->Mdl_forum->getSpecialityByCategoryId($cat);
				}
			}
			
			if($searchKeys !== ''){
				$result = $this->Mdl_forum->getSearchSubmit($searchSubmit, $start, $record);
			}else if($search !== ''){
				$result = $this->Mdl_forum->getForumsBySpeciality($search, $start, $record);
			}else{
				$result = $this->Mdl_forum->listForMembers($uid, $specialityList, $udata[0]->followedDocs, $start, $record);
			}
		}
			
		if($result !== "no"){
			$forumlisting = '';
			$baseurl = base_url();
			foreach($result as $res){
				if($res->postedBy == 'admin'){
					$name = 'Just Medical Advice';
					$url = base_url().'admin_assets/images/JMA.png';
					$profileUrl = 'javascript:;';
				}else if($res->postedBy == 'doc'){
					$docData = $this->Mdl_forum->getDoctorDetails($res->userId);
					$name = $docData[0]->name;
					$url = base_url().$docData[0]->profileImage;
					$docid = base64_encode($docData[0]->regId);
					$docid = str_replace(str_repeat('=',strlen($docid)/4),"",$docid);
					$profileUrl = base_url().'doctor/view/'.$docid;					
				}else{
					$memData = $this->Mdl_forum->getMemberDetails($res->userId);
					$name = $memData[0]->name;
					$url = base_url().$memData[0]->profileImage;
					$profileUrl = 'javascript:;';
				}
				
				$answers = $this->Mdl_forum->getAnswerCount($res->forumId);
				
				$forumlisting .= '<div class="db_article_box fade_anim">
				<div class="row articleDetails">
					<div class="col-xs-12 author_info">
						<div class="author_dp">
							<a href="">
								<img src="'.$url.'">
							</a>
						</div>
						<div class="author_name">
							<strong>
								<a href="'.$profileUrl.'" class="txtblue">'.$name.'</a>
							</strong> asked
						</div>
					</div>
            
					<div class="col-xs-12 article_info">
						<div class="articleDate txtdark">'.date("d-M, Y ",strtotime($res->createdDate)).'</div>
						<a href="'.base_url().'forum/view/'.$res->slug.'" class="txtblue">
							<h2>'.$res->question.'</h2>
						</a>
						<div class="article_stats">
							<div class="counts"><span class="blogging_icons comment"></span> '.$answers.' answers</div>
						</div>
					</div>
				</div>
        
				<div class="row blogactivity">
					<div class="col-xs-6 text-center"><a href="'.base_url().'forum/view/'.$res->slug.'/#share"><button><span class="blogging_icons share"></span> Share</button></a></div>
					<div class="col-sm-6 col-xs-12"><a href="'.base_url().'forum/view/'.$res->slug.'/#comment" class="form-control commentanchor txtdark">Write a answer</a></div>        
				</div>
				</div>';
			}
			echo $forumlisting;
		}else{
			echo 1;
		}
	}
	
	/***************************************** View Forum ***************************************/
	function view($url){
		if(!Modules::run('site_security/isMember')){
			redirect('login','refresh');
		}
		
		$forum = $this->Mdl_forum->getForumByUrl($url);
		
		$findSimilar = $this->Mdl_forum->getsuggestedForums($forum[0]->specialityId,$forum[0]->postedBy,$forum[0]->userId,$forum[0]->forumId);
		
		if($findSimilar !== 'no'){
			$relatedForum = array();
			foreach($findSimilar as $val){
				$final = array();
				$final['question'] = $val->question;
				$final['url'] = base_url().'forum/view/'.$val->slug;
				$final['answer'] =  $this->Mdl_forum->getAnswerCount($val->forumId);
				$final['createdDate'] =  $val->createdDate;
				if($val->postedBy == 'admin'){
					$name = 'Just Medical Advice';
					$image = base_url().'admin_assets/images/JMA.png'; 
				}else if($val->postedBy == 'doc'){
					$user = $this->Mdl_forum->getDoctorDetails($val->userId);
					$name = $user[0]->name;
					$image = base_url().$user[0]->profileImage; 
				}else{
					$user = $this->Mdl_forum->getMemberDetails($val->userId);
					$name = $user[0]->name;
					$image = base_url().$user[0]->profileImage;
				}
				$final['name'] = $name;
				$final['image'] = $image;
				$relatedForum[] = $final;
			}
			$data['SuggestedForums'] = $relatedForum;
		}else{
			$data['SuggestedForums'] = "No Data";
		}
		
		if($forum[0]->postedBy == 'admin'){
			$userData = $this->Mdl_forum->getAdminDetails($forum[0]->userId);
		}else if($forum[0]->postedBy == 'doc'){
			$userData = $this->Mdl_forum->getDoctorDetails($forum[0]->userId);
		}else{
			$userData = $this->Mdl_forum->getMemberDetails($forum[0]->userId);
		}
		
		$answer = $this->show_tree($forum[0]->forumId);
		if($answer == 'no'){
			$data['answers'] = '';
		}else{
			$data['answers'] = $answer;
		}
		
		$data['forumDetails'] = $forum;
		$data['answerCount'] = $this->Mdl_forum->getAnswerCount($forum[0]->forumId);
		$data['userDetails'] = $userData;
		$data['speciality'] = $this->Mdl_forum->getSpeciality();
		$data['viewFile'] = 'view';
		$data['module'] = 'forum';
		$data['forum'] = '1';
		$data['scriptFile'] = 'member-forum-view';
		$template = 'member';
		
		echo Modules::run('template/'.$template, $data);
	}
	
	/***************************************** Load Search Results Based on Key ***************************************/
	function search(){
		$search = $_POST['searchKey'];
		$data = explode(" ",$search);
		
		$msg = "";
		
		if(!empty($search)){
			$specialities = $this->Mdl_forum->getSpecialityBySearch($data);
			$forums = $this->Mdl_forum->getForumsBySearch($data);
			
			if(is_array($specialities)){
		        $msg.="<div class='result_box'><div class='result_tl'>Forums based On Speciality</div>";
                foreach($specialities as $val){
                    $msg.="<a href='".base_url()."forum/searches/".$val->spSlug."'>".$val->spName."</a>";
                }
                $msg.="</div>"; 
		    } 
			
			if(is_array($forums)){
		        $msg.="<div class='result_box'><div class='result_tl'>Forums Available</div>";
                foreach($forums as $val){
                    $msg.="<a href='".base_url()."forum/view/".$val->slug."'>".$val->question."</a>";
                }
                $msg.="</div>"; 
		    }
			
			if($specialities=="no" && $forums=="no"){
		      $msg.="<div class='result_box'><p style='margin-left: 10px;'>No results found for <b><span style='color:red'>$search</span></b>. </p></div>";
			}
			echo $msg;
		}else{
			echo 1;
		}
	}
	
	/***************************************** Display Search Results ***************************************/
	function searches($key){
		if(!Modules::run('site_security/isMember')){
			redirect('login','refresh');
		}
		
		$result = $this->Mdl_doctor->getFeaturedDoctors();
		if($result !== 'No Data'){
			$featuredDoc = array();
			foreach($result as $res){
				$sub_array = array();
				$id = base64_encode($res->regId);
				$id = str_replace(str_repeat('=',strlen($id)/4),"",$id);
				$sub_array[] = $id;
				$img = $res->profileImage;
				$sub_array[] = $img;
				$name = $res->name;
				$sub_array[] = $name;
				$res2 = explode(",",$res->speciality);
				$sp = '';
				$size = sizeof($res2);
				
				if($size > 2){
					$size -= ($size-2);
				}
				
				for($i=0; $i< $size; $i++){
					$sp .= $this->Mdl_doctor->findSpecialityName($res2[$i]).'<br>';
				}
				$sub_array[] = $sp;
				$sub_array[] = $res->regId;
				if($res->followedMem !== ''){
					$sub_array[] = sizeof(explode(",",$res->followedMem));
				}else{
					$sub_array[] = "No ";
				}
				
				$featuredDoc[] = $sub_array;
			}
		}else{
			$featuredDoc = "No Data";
		}
		
		$data['docList'] = $featuredDoc;
		$data['speciality'] = $this->Mdl_forum->getSpeciality();
		$data['viewFile'] = 'list';
		$data['scriptFile'] = 'member-forum-list';
		$data['module'] = 'forum';
		$data['forum'] = '1';
		$data['searchSP'] = $key;
		$data['searchSubmit'] = '';
		$template = 'member';
		echo Modules::run('template/'.$template, $data);
	}

	/***************************************** Search Submit ***************************************/
	function searchSubmit(){
		if(!Modules::run('site_security/isMember')){
			redirect('login','refresh');
		}
		
		$content = $this->input->post();
		
		$result = $this->Mdl_doctor->getFeaturedDoctors();
		if($result !== 'No Data'){
			$featuredDoc = array();
			foreach($result as $res){
				$sub_array = array();
				$id = base64_encode($res->regId);
				$id = str_replace(str_repeat('=',strlen($id)/4),"",$id);
				$sub_array[] = $id;
				$img = $res->profileImage;
				$sub_array[] = $img;
				$name = $res->name;
				$sub_array[] = $name;
				$res2 = explode(",",$res->speciality);
				$sp = '';
				$size = sizeof($res2);
				
				if($size > 2){
					$size -= ($size-2);
				}
				
				for($i=0; $i< $size; $i++){
					$sp .= $this->Mdl_doctor->findSpecialityName($res2[$i]).'<br>';
				}
				$sub_array[] = $sp;
				$sub_array[] = $res->regId;
				if($res->followedMem !== ''){
					$sub_array[] = sizeof(explode(",",$res->followedMem));
				}else{
					$sub_array[] = "No ";
				}
				
				$featuredDoc[] = $sub_array;
			}
		}else{
			$featuredDoc = "No Data";
		}
		
		$data['docList'] = $featuredDoc;
		$data['speciality'] = $this->Mdl_forum->getSpeciality();
		$data['viewFile'] = 'list';
		$data['scriptFile'] = 'member-forum-list';
		$data['module'] = 'forum';
		$data['forum'] = '1';
		$data['searchSP'] = '';
		$data['searchSubmit'] = $content['text'];
		$template = 'member';
		echo Modules::run('template/'.$template, $data);
	}
	
/*****************************************************************************************************************************************************************************************************************************************************************************************												 	Common Functionality For All
*****************************************************************************************************************************************************************************************************************************************************************************************/
	
	/***************************************** Add Answer ***************************************/
	function addAnswer(){
		$content = $this->input->post();
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules("answer","answer","required|xss_clean",array(
					'required' => 'Answer is required.' ));

		if($this->form_validation->run() == FALSE){
			echo validation_errors();
		}else{
			$data = array(
				'forumId' => $content['fid'],
				'answer' => $content['answer'],
				'postedBy' => $content['utype'],
				'userId'=> $content['uid'],
				'parentId'=> $content['pid'],
				'isActive'=>'1',
				'createdDate' =>date('Y-m-d H:i:s'),
				'modifiedDate' =>date('Y-m-d H:i:s'),
			);
					
			$insert = $this->Mdl_forum->insert_answer($data);
			echo 1;
		}
	}
	
	/***************************************** Add Forum Like ***************************************/
	function answerLike(){
		$content = $this->input->post();
		
		$uid = $content['user'];
		$type = $content['type'];
		$aid = $content['answerId'];
		
		if($uid !== '' || $utype !== '' || $aid !== ''){
			$isExist = $this->Mdl_forum->isLiked($aid,$uid,$type);
			$isDisliked = $this->Mdl_forum->isDisliked($aid,$uid,$type);
			
			if($isExist == 1){
				//echo 2;
				$delete = $this->Mdl_forum->removeLike($aid,$uid,$type);
				echo 1;	exit;			
			}else{
				if($isDisliked == 1){
					$this->Mdl_forum->removeDislike($aid,$uid,$type);
				}
				
				$data = array(
					'answerId' => $aid,
					'postedBy' => $type,
					'userId'=> $uid,
					'postedDate' =>date('Y-m-d H:i:s')
				);
				$insert = $this->Mdl_forum->insert_like($data);		
				echo 1;
			}		
		}else{
			echo "Some Error Occured";
		}
	}
	
	/***************************************** Add Forum Dislike ***************************************/
	function answerDislike(){
		$content = $this->input->post();
		
		$uid = $content['user'];
		$type = $content['type'];
		$aid = $content['answerId'];
		
		if($uid !== '' || $utype !== '' || $aid !== ''){
			$isExist = $this->Mdl_forum->isDisliked($aid,$uid,$type);
			$isLiked = $this->Mdl_forum->isLiked($aid,$uid,$type);
			
			if($isExist == 1){
				//echo 2;
				$delete = $this->Mdl_forum->removeDislike($aid,$uid,$type);
				echo 1; exit;				
			}else{
				if($isLiked == 1){
					$this->Mdl_forum->removeLike($aid,$uid,$type);
				}
				
				$data = array(
					'answerId' => $aid,
					'postedBy' => $type,
					'userId'=> $uid,
					'postedDate' =>date('Y-m-d H:i:s')
				);
				$insert = $this->Mdl_forum->insert_dislike($data);		
				echo 1;
			}		
		}else{
			echo "Some Error Occured";
		}
	}
	
	/***************************************** Add Answer Report ***************************************/
	function addReport(){
		$content = $this->input->post();
		
		$cid = $content['cid'];
		
		if($cid !== ''){
			$isExist = $this->Mdl_forum->isReported($cid);
			if($isExist == 1){
				//echo 1;	exit;
				$delete = $this->Mdl_forum->removeReportedAns($cid);
				echo 1;	exit;
			}else{
				$data = array(
					'answerId'=> $cid,
					'createdDate' =>date('Y-m-d H:i:s')
				);
				$insert = $this->Mdl_forum->insert_report($data);		
				echo 1; exit;
			}		
		}else{
			echo 2; exit;
		}
	}
	
	/***************************************** Get Answer Id ***************************************/
	function show_tree($fid){
        $store_all_id = array();
        $id_result = $this->Mdl_forum->tree_all($fid);
		
		if($id_result !== 'no'){
			foreach ($id_result as $comment_id) {
				array_push($store_all_id, $comment_id['parentId']);
			}
			return  $this->in_parent(0,$fid, $store_all_id);
		}else{
			return "no";
		}
    }

    /***************************************** Get Answer Hierarchy ***************************************/
    function in_parent($in_parent,$fid,$store_all_id) {
        // this variable to save all concatenated html
        $html = "";
        if (in_array($in_parent,$store_all_id)) {
            $result = $this->Mdl_forum->tree_by_parent($fid,$in_parent);
            foreach ($result as $re){
				$type = $re['postedBy'];
				$uid = $re['userId'];
				$answerId = $re['answerId'];
				$answer = $re['answer'];
				$date = $re['createdDate'];
				$name = '';
				$url = '';
				if($type == 'admin'){
					$name = 'Just Medical Advice';
					$url = base_url() . 'admin_assets/images/JMA.png';
				}else if($type == 'doc'){
					$docData = $this->Mdl_forum->getDoctorDetails($uid);
					$name = $docData[0]->name;
					$url = base_url().$docData[0]->profileImage;
				}else if($type == 'mem'){
					$memData = $this->Mdl_forum->getMemberDetails($uid);
					$name = $memData[0]->name;
					$url = base_url().$memData[0]->profileImage;
				}
				
				$likeCount = $this->Mdl_forum->getLikeCount($answerId);
				$dislikeCount = $this->Mdl_forum->getDisikeCount($answerId);
				$uType = $this->session->type;
				$uId = $this->session->userdata('userId');
				$isLike = $this->Mdl_forum->isLiked($answerId,$uId,$uType);
				$isDislike = $this->Mdl_forum->isDisliked($answerId,$uId,$uType);
				$isReported = $this->Mdl_forum->isReported($answerId);
				
				$html .= '<div class="comment_box ">
				<div class="author_dp"><div><img src="'.$url.'"></div></div>
				<div class="comment">
				<div class="comment_container">';
				if($type == 'doc'){
					$html .= '<div class="comment_holder doc_comment">';
				}else{
					$html .= '<div class="comment_holder">';
				}
				$html .= '<strong class="txtblue">'.$name.' -</strong> '.$answer.'
				</div>
				<div class="comment_detail"><span style="margin-right:15px;">'.date("d-M, Y ",strtotime($date)).'</span> <a href="javascript:;" class="reply" id="'.$answerId.'" >Reply</a>
				<div class="ldl_box"><a href="javascript:;" class="like" id="'.$answerId.'" ><button class="like_btn"><span class="blogging_icons ';
				if($isLike == 1){$html .= 'thanked';}else{$html .= 'thank';}
				$html .='"></span></button>'.$likeCount.' Likes</a></div>
                <div class="ldl_box"><a href="javascript:;" class="dislike" id="'.$answerId.'" ><button class="like_btn"><span class="blogging_icons ';
				if($isDislike == 1){$html .= 'thumbdownactive'; }else{$html .= 'thumbdown';}
				$html .= '"></span></button>'.$dislikeCount.' Dislike</a>
				<a href="javascript:;" class="report" id="'.$answerId.'"><div class="ldl_box"><button class="like_btn"><span class="blogging_icons ';
				if($isReported == 1){$html .= 'flagged';}else{$html .= 'flag';}
				$html .= '"></span> Report Abuse</button></div></a>
				</div>
				</div></div>';
				$html .= $this->in_parent($answerId, $fid, $store_all_id);
				$html .= '</div></div>';
			}
			return $html;
		}
    }
}