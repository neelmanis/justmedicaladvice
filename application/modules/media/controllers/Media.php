<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use Aws\S3\S3Client;
require ('aws/vendor/autoload.php');

class Media extends MX_Controller{
	/*------------------------------ CONSTRUCTOR FUNCTION---------------------------------------*/	
	function __construct(){
		parent::__construct();
		$this->load->model('Mdl_media');
		$this->load->model('doctor/Mdl_doctor');
		$this->load->model('forum/Mdl_forum');
		$this->load->model('notifications/Mdl_notifications');
		$this->s3=S3Client::factory([
			'key'=>'AKIAIBEUVUITAMQ4P3UQ',
			'secret'=>'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',
			'signature' => 'v4',
			'region'=>'ap-south-1'
		]);		
	}

/*****************************************************************************************************************************************************************************************************************************************************************************************														Admin Functionality
*****************************************************************************************************************************************************************************************************************************************************************************************/

	/******************************************* Media Listing *******************************************/ 
	function mediaList(){
		if(!Modules::run('site_security/isAdmin')){
			redirect('admin','refresh');
		}
		
		$media = $this-> Mdl_media->listAll();
		
		if($media !== 'no'){
			$mediaData = array();
			foreach($media as $val){
				$res = array();
				$id = base64_encode($val->mediaId);
				$id = str_replace(str_repeat('=',strlen($id)/4),"",$id);
				$res['id'] = $id;
				$cat = $this->Mdl_media->findCategoryName($val->categoryId);
				if($cat == 'no'){
					$res['catName'] = "No Data";
				}else{
					$res['catName'] = $cat[0]->catName;
				}
				$res['mtype'] = $val->mtype;
				$res['title'] = $val->title;
				$res['slug'] = $val->slug;
				$res['postedBy'] = $val->postedBy;
				$res['name'] = $this->Mdl_media->findName($val->postedBy,$val->userId);
				$res['report'] = $this->Mdl_media->countReport($val->mediaId);
				$res['visible'] = $val->visibleTo;
				$res['isHome'] = $val->isHome;
				$res['status'] = $val->isActive;
				$res['createdDate'] = $val->createdDate;
				$mediaData[] = $res;
			}
			$data['mediaList'] = $mediaData;
		}else{
			$data['mediaList'] = "No Data";
		}
		
		$data['viewFile'] = "list-media";
		$data['page'] = 'listMedia';
		$template = 'admin';
		echo Modules::run('template/'.$template, $data);
	}
	
	/***************************************** Active Media Action ***************************************/
	function active_action($id){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$mid = base64_decode($base_64);
			$this->Mdl_media->statusActive($mid);
			redirect('media/list-media');	
		}
	}
	
	/***************************************** Inactive Media Action ***************************************/
	function inActive_action($id){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$mid = base64_decode($base_64);
			$this->Mdl_media->statusInactive($mid);
			redirect('media/list-media');	
		}
	}
	
	/***************************************** Media Set Home ***************************************/
	function setHome($id){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$mid = base64_decode($base_64);
			$this->Mdl_media->setHome($mid);
			redirect('media/list-media');	
		}
	}
	
	/***************************************** Media Unset Home ***************************************/
	function unsetHome($id){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$mid = base64_decode($base_64);
			$this->Mdl_media->unsetHome($mid);
			redirect('media/list-media');	
		}
	}
	
	/***************************************** Delete Media ***************************************/
	function deleteMedia(){
		$mid = $_POST['mediaId'];
		$base_64 = $mid . str_repeat('=', strlen($mid) % 4);
		$mId = base64_decode($base_64);
		$delete = $this->Mdl_media->deleteById($mId);
		echo $delete;
	}
	
	/***************************************** Add Media ***************************************/
	function newMedia(){
		if(!Modules::run('site_security/isAdmin')){
			redirect('admin','refresh');
		}

		$data['category'] = $this->Mdl_media->getCategory();
        $data['viewFile'] = 'add-media';
		$data['page'] = 'addMedia';
		$template = 'admin';
		echo Modules::run('template/'.$template, $data);
	}

	/***************************************** Add Media Action ***************************************/
	function addMediaAction(){
		$content = $this->input->post();
		
		$this->form_validation->set_rules("category","Category","required|xss_clean",array(
					'required'      => '<b>Category is not selected. </b>'
		));
		$this->form_validation->set_rules("subcategory","Subcategory","required|xss_clean",array(
					'required'      => '<b>Subcategory is not selected. </b>'
		));
		$this->form_validation->set_rules("title","Title","required|xss_clean",array(
					'required'      => '<b>Title is required. </b>'
		));
		$this->form_validation->set_rules("mtype","Select Media Type","required|xss_clean",array(
			'required'      => '<b>Media Type is not selected. </b>'
		));
		$this->form_validation->set_rules("visible","visible","xss_clean");
		
		if($content['mtype'] == 'video'){
			$this->form_validation->set_rules("ctype","Content Type","required|xss_clean",array(
						'required'      => '<b>Select The Format of Video.</b>'
			));
		}
		
		if($content['ctype'] !== ''){
			$ctype = $content['ctype']; 
			if($ctype== 'youtube'){
				$this->form_validation->set_rules("link","Youtube Video Code","required|xss_clean",array(
						'required'      => '<b>Youtube Video Code is not attached.</b>'
				));
			}	
		}else{
			$ctype = 'none';
		}
		
		if($content['description'] !== ''){
			$description = $content['description']; 
		}else{
			$description = "No Data";
		}
		
		if($this->form_validation->run() == FALSE){
			echo validation_errors();
		}else{
			/* Create a Slug url from title */
			/*$find = array("@", "#", "$", "%", "`", "^", "*", "+", "=", "{", "}", "", "/", "_", "?", "(", ")", ";", "-", ":", "'", "!", ".", ",", "\r", "\n", "\r\n");
			$replace = array("");
			$new_string = str_replace($find,$replace,strtolower($content['title']));
			$new_string = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $new_string); 
			*/
			//$new_string = preg_replace("/[^a-zA-Z]/", "", strtolower($content['title'])); 
			$new_string = preg_replace("/[^a-zA-Z0-9\s]/", "", strtolower($content['title'])); 
			$new_string = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $new_string);
			$urlKey = str_replace(" ","-",trim($new_string));
			
			/* Check whether slug url already exist or not */
			$urlCheck = $this->Mdl_media->urlCheck($urlKey);
			if($urlCheck){
				if($ctype=='upload' || $ctype == 'none'){
					
					if(empty($_FILES['media']['name'])){
						echo 3; exit;
					}else{
						$medianame = $_FILES['media']['name'];
						$ext = pathinfo($medianame, PATHINFO_EXTENSION);
						$allowed = array('mp4','mp3');
						if( ! in_array( $ext, $allowed ) ) {echo '<b>File format not supported. Please upload mp4, mp3 files.</b>'; exit;}
						$medianame=str_replace(array(" ","'"),"_",time().$medianame);
						$tmp_name = $_FILES['media']['tmp_name'];
						try{
							$this->s3->putObject([
								'Bucket'=> 'justmedicaladvice.com',
								'Key'=>'media/'.$medianame,
								'Body'=>fopen($tmp_name,'rb'),
								'ACL'=>'public-read'
							]);
							$url = 'https://s3.ap-south-1.amazonaws.com/justmedicaladvice.com/media/'.$medianame;
							$upload = true;
						}catch(S3Exception $e){
						   $upload = false;
						}
					}
				}else{
					$url = 'https://www.youtube.com/embed/'.$content['link'];
					$upload = true;
				}
					
				if($upload){
					$data = array(
						'categoryId' => $content['subcategory'],
						'mtype' => $content['mtype'],
						'title' => $content['title'],
						'slug' => $urlKey,
						'ctype' => $ctype,
						'url' => $url,
						'description'=> strip_tags($description),
						'postedBy' => $this->session->type,
						'userId' => $this->session->userdata('userId'),
						'visibleTo' => $content['visible'],
						'isActive' => '1',
						'isHome' => '0',
						'createdDate' =>date('Y-m-d H:i:s'),
						'modifiedDate' =>date('Y-m-d H:i:s')
					);
					
					$insert = $this->Mdl_media->insert($data);
					
					// Add Notification
					if($this->session->type == 'admin'){
						$name = "Just Medical Advice";
						$image = base_url().'admin_assets/images/JMA.png';
						$text = '<div class="user_dp"><img src="'.$image.'"></div> '.$name.' posted a '.$content['mtype'].' - “'.$content['title'].'” <div class="date">'.date("d M Y ").'</div>';
					
						$data1 = array(
							'notification' => $text,
							'url' => base_url().'media/view/'.$urlKey,
							'allMembers' => '1',
							'userId' => 0,
							'createdDate' => date('Y-m-d H:i:s'),
							'endDate' => date('Y-m-d H:i:s',strtotime("+2 months"))
						);
						
						$this->Mdl_notifications->insert("notification_members",$data1);
						
						$data2 = array(
							'notification' => $text,
							'url' => base_url().'media/watch/'.$urlKey,
							'allDoctors' => '1',
							'userId' => 0,
							'createdDate' => date('Y-m-d H:i:s'),
							'endDate' => date('Y-m-d H:i:s',strtotime("+2 months"))
						);
						
						$this->Mdl_notifications->insert("notification_doctors",$data2);
						
					}else if($this->session->type == 'doc'){
						$doc = $this->Mdl_media->getDoctorDetails($this->session->userdata('userId'));
						$name = $doc[0]->name;
						$image = base_url().$doc[0]->profileImage;
						$text = '<div class="user_dp"><img src="'.$image.'"></div> '.$name.' posted '.$content['mtype'].' - “'.$content['title'].'” <div class="date">'.date("d M Y ").'</div>';
					
						$data1 = array(
							'notification' => $text,
							'url' => base_url().'media/view/'.$urlKey,
							'allMembers' => '1',
							'userId' => 0,
							'createdDate' => date('Y-m-d H:i:s'),
							'endDate' => date('Y-m-d H:i:s',strtotime("+2 months"))
						);
						
						$this->Mdl_notifications->insert("notification_members",$data1);
					}
	
					echo 1; exit;
				}else{
					echo "Error while uploading file."; exit; 
				}
			}else{
				echo 2;
			}
		}
	}
	
	/***************************************** Edit Media ***************************************/
	function editMedia($id){
		if(!Modules::run('site_security/isAdmin')){
			redirect('admin','refresh');
		}
		
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$mid = base64_decode($base_64);
			$data['category'] = $this->Mdl_media->getCategory();
			$media = $this->Mdl_media->findById($mid);			
			$data['categoryId'] = $this->Mdl_media->findCategoryId($media[0]->categoryId);
			$data['getData'] = $media;
			$subCat = $this->Mdl_media->getSubCategory($data['categoryId']);
			$str = '<option disabled>Choose one</option>';
			$selected = $media[0]->categoryId;
			if(is_array($subCat)){
				foreach($subCat as $val){
					if($selected == $val->catId){
						$str .= '<option value="'.$val->catId.'"selected >'.$val->catName.'</option>';
					}else{
						$str .= '<option value="'.$val->catId.'">'.$val->catName.'</option>';
					} 
				}
			}
			$data['subCategory'] = $str;
			$data['viewFile'] = "edit-media";
			$data['page'] = "editMedia";
			$template = 'admin';
			echo Modules::run('template/'.$template, $data);		
		}
	}	

	/***************************************** Admin Edit Media Action ***************************************/
	function editMediaAction(){
		$content = $this->input->post();
		
        $this->form_validation->set_rules("mtype","Select Media Type","required|xss_clean",array(
			'required'      => '<b>Media **</b> Type is not selected'
		));
        $this->form_validation->set_rules("title","Title","required|xss_clean",array(
					'required'      => '<b>Title **</b> field is required'
		));
		$this->form_validation->set_rules("category","Category","required|xss_clean",array(
					'required'      => '<b>Category **</b> is not selected'
		));
		$this->form_validation->set_rules("subcategory","Subcategory","required|xss_clean",array(
					'required'      => '<b>Subcategory **</b> is not selected'
		));
		
		if(isset($content['ctype'])){
			$ctype = $content['ctype']; 
			if($ctype== 'youtube'){
				$this->form_validation->set_rules("link","Youtube Video Code","required|xss_clean",array(
						'required'      => '<b>Youtube Video Code **</b> is not provided'
				));
			}	
		}else{
			$ctype = 'none';
		}
		
		if($content['description'] !== ''){
			$description = $content['description']; 
		}else{
			$description = "No Data";
		}
		
		if($this->form_validation->run() == FALSE){
			echo validation_errors();
		}else{
			
			$find = array("/","_","?","(",")","-",":","'","!",".",",","\r","\n","\r\n");
			$replace = array("");
			$new_string = str_replace($find,$replace,strtolower($content['title']));
			$new_string = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $new_string); 
			$urlKey = str_replace(" ","-",trim($new_string));
			
			if($urlKey !== $content['slug']){
				$urlCheck = $this->Mdl_media->urlCheck($urlKey);
			}else{
				$urlCheck = TRUE;
			}
			
			if($urlCheck){
				if($ctype=='youtube' && isset($content['link'])){
					$url = 'https://www.youtube.com/embed/'.$content['link'];
					$upload = true;
				}else if(empty($_FILES['media']['name'])){
					$url = $content['url'];
					$upload = true;
				}else if($ctype=='upload' || $ctype =='none'){
					$medianame = $_FILES['media']['name'];
					$medianame=str_replace(array(" ","'"),"_",time().$medianame);
					$tmp_name = $_FILES['media']['tmp_name'];
					try{
						$this->s3->putObject([
							'Bucket'=> 'justmedicaladvice.com',
							'Key'=>'media/'.$medianame,
							'Body'=>fopen($tmp_name,'rb'),
							'ACL'=>'public-read'
						]);
						$url = 'https://s3.ap-south-1.amazonaws.com/justmedicaladvice.com/media/'.$medianame;
						$upload = true;
					}catch(S3Exception $e){
					   $upload = false;
					}
				}					
				
				if($upload){
					$data = array(
						'categoryId' => $content['subcategory'],
						'mtype' => $content['mtype'],
						'title' => $content['title'],
						'slug' => $urlKey,
						'ctype' => $ctype,
						'url' => $url,
						'description'=> $description,
						'postedBy' => $this->session->type,
						'userId' => $this->session->userdata('userId'),
						'isActive' => '1',
						'modifiedDate' =>date('Y-m-d H:i:s')
					);
					
					$this->Mdl_media->update($data,base64_decode($content['mediaId']));
					echo 1; exit;
				}else{
					echo "Error while uploading file."; exit; 
				}
			}else{
				echo 2;
			}
		}
	}
	
	/***************************************** View Media ***************************************/
	function viewMedia($url){
		if(!Modules::run('site_security/isAdmin')){
			redirect('admin','refresh');
		}
		
		$media = $this->Mdl_media->getMediaByUrl($url);
		$likes = $this->Mdl_media->getLikeCount($media[0]->mediaId);
		$comments = $this->Mdl_media->getCommentCount($media[0]->mediaId);
		$data['mediaDetails'] = $media;
		$data['likeCount'] = $likes;
		$data['commentCount'] = $comments;
		$data['isLiked'] = $this->Mdl_media->isLiked($media[0]->mediaId,$this->session->type,$this->session->userdata('userId'));
		$data['isReport'] = $this->Mdl_media->isReport($media[0]->mediaId,$this->session->type,$this->session->userdata('userId'));
		
		if($media[0]->postedBy == 'admin'){
			$userData = $this->Mdl_media->getAdminDetails($media[0]->userId);
		}else{
			$userData = $this->Mdl_media->getDoctorDetails($media[0]->userId);
		}
		 
		$comment = $this->show_tree($media[0]->mediaId);
		if($comment == 'no'){
			$data['comments'] = '';
		}else{
			$data['comments'] = $comment;
		}
		
		$data['userDetails'] = $userData;
		$data['viewFile'] = 'view-media';
		$data['page'] = 'viewMedia';
		$template = 'admin';
		echo Modules::run('template/'.$template, $data);
	}
	
	/***************************************** Flag Comment Listing ***************************************/
	function flagComments(){
		if(!Modules::run('site_security/isAdmin')){
			redirect('admin','refresh');
		}
		
		$report = $this-> Mdl_media->getFlagCommentId();
		if($report !== 'No Data'){
			$comment = array();
			foreach($report as $re){
				$result = $this->Mdl_media->getCommentById($re->commentId);
				if($result !== 'No Data'){
					$set = array();
					$set['commentId'] = $result[0]->commentId;
					$set['comment'] = $result[0]->comment;
					$set['isActive'] = $result[0]->isActive;
					$comment[] = $set;
				}
			}
		}else{
			$comment = 'No Data';
		}
		$data['comments'] = $comment;
		$data['viewFile'] = "list-media-comment";
		$data['page'] = 'mediaFlagComments';
		$template = 'admin';
		echo Modules::run('template/'.$template, $data);
	}
	
	/***************************************** Inactive Comment ***************************************/
	function inActiveComment($id){
		$result = $this->Mdl_media->inActiveComment($id);
		redirect('media/flag-comments');
	}
	
	/***************************************** Active Comment ***************************************/
	function activeComment($id){
		$result = $this->Mdl_media->activeComment($id);
		redirect('media/flag-comments');
	}
	
/*****************************************************************************************************************************************************************************************************************************************************************************************														Doctor Functionality
*****************************************************************************************************************************************************************************************************************************************************************************************/

	/***************************************** Add Media ***************************************/
	function uploadMedia(){
		if(!Modules::run('site_security/isDoctor')){
			redirect('login','refresh');
		}
		
		$data['category'] = $this->Mdl_media->getCategory();
        $data['module'] = 'media';
        $data['viewFile'] = 'upload-media';
        $data['scriptFile'] = 'doctor-media-create';
        $data['media'] = '1';
		$template = 'doctor';
		echo Modules::run('template/'.$template, $data);
	}
	
	/***************************************** View Media ***************************************/
	function watch($url){
		if(!Modules::run('site_security/isDoctor')){
			redirect('login','refresh');
		}
		
		$media = $this->Mdl_media->getMediaByUrl($url);
		$likes = $this->Mdl_media->getLikeCount($media[0]->mediaId);
		$comments = $this->Mdl_media->getCommentCount($media[0]->mediaId);
		
		$id = $this->session->userdata('userId');
		$type = $this->session->userdata('type');
		
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
	
		$data['scriptFile'] = 'doctor-media';
		$data['media'] = '1';
		$template = 'doctor';

		$data['mediaDetails'] = $media;
		$data['likeCount'] = $likes;
		$data['commentCount'] = $comments;
		
		if($media[0]->postedBy == 'admin'){
			$userData = $this->Mdl_media->getAdminDetails($media[0]->userId);
		}else{
			$userData = $this->Mdl_media->getDoctorDetails($media[0]->userId);
		}
		 
		$comment = $this->show_tree($media[0]->mediaId);
		if($comment == 'no'){
			$data['comments'] = '';
		}else{
			$data['comments'] = $comment;
		}
		
		$data['isLiked'] = $this->Mdl_media->isLiked($media[0]->mediaId, $type, $id);
		$data['isReport'] = $this->Mdl_media->isReport($media[0]->mediaId,$type,$id);
		$data['speciality'] = $this->Mdl_forum->getSpeciality();
		$data['userDetails'] = $userData;
		$data['viewFile'] = 'view';
		$data['module'] = 'media';
		echo Modules::run('template/'.$template, $data);
	}
	
/*****************************************************************************************************************************************************************************************************************************************************************************************														Memeber Functionality
*****************************************************************************************************************************************************************************************************************************************************************************************/

	
	/***************************************** Media Listing ***************************************/
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
		$data['scriptFile'] = 'member-media-list';
		$data['module'] = 'media';
		$data['media'] = '1';
		$data['searchCat'] = '';
		$data['searchSubmit'] = '';
		$template = 'member';
		echo Modules::run('template/'.$template, $data);
	}
	
	/***************************************** Get Media List ***************************************/
	function getMediaList(){
		$page =  $_GET['page'];
		$search =  $_GET['searchCat'];
		$searchKeys = $_GET['searchSubmited'];
		$searchSubmit =  explode(" ",$searchKeys);
		$record = 5;
		$start = ($page - 1) * $record;
		
		$uid = $this->session->userdata('userId');
		$utype = $this->session->userdata('type');
		
		if($utype == 'mem'){
			$udata = $this->Mdl_media->getMemberDetails($uid);
			if($searchKeys !== ''){
				$result = $this->Mdl_media->getSearchSubmit($searchSubmit, $start, $record);
			}else if($search !== ''){
				$result = $this->Mdl_media->getMediaByCategory($search, $start, $record);
			}else{
				$result = $this->Mdl_media->listForMembers($udata[0]->fieldsOfInterest, $udata[0]->followedDocs, $start, $record);
			}
			
			if($result !== "no"){
				$medialisting = '';
				$baseurl = base_url();
				foreach($result as $res){
					if($res->postedBy == 'admin'){
						$name = 'Just Medical Advice';
						$url = 'admin_assets/images/JMA.png';
						$profileUrl = 'javascript:;';
					}else{
						$docData = $this->Mdl_media->getDoctorDetails($res->userId);
						$name = $docData[0]->name;
						$url = $docData[0]->profileImage;
						$docid = base64_encode($docData[0]->regId);
						$docid = str_replace(str_repeat('=',strlen($docid)/4),"",$docid);	
						$profileUrl = base_url().'doctor/view/'.$docid;						
					}
					$likes = $this->Mdl_media->getLikeCount($res->mediaId);
					$comments = $this->Mdl_media->getCommentCount($res->mediaId);
					$isLiked = $this->Mdl_media->isLiked($res->mediaId,$utype,$uid);
					
					$medialisting .= '<div class="db_article_box fade_anim">
										<div class="row articleDetails">
											<div class="col-sm-6 col-xs-12 author_info">
												<div class="author_dp">
													<a href="'.$profileUrl.'">
														<img src="'.base_url().$url.'">
													</a>
												</div>
												<div class="author_name">
													<strong><a href="'.$profileUrl.'" class="txtblue">'.$name.'</a></strong> shared video
												</div>
											</div>';
						
						if($res->ctype == 'youtube'){	
							$medialisting .=  '<div class="col-sm-6 col-xs-12 article_pic">
										<div class="img_holder">
											<iframe src="'.$res->url.'?rel=0&autoplay=0&showinfo=0&controls=0" frameborder="0" allowfullscreen></iframe>
										</div>
									</div>';
						}else if($res->ctype == 'upload'){
							$medialisting .= '<div class="col-sm-6 col-xs-12 article_pic">
										<div class="img_holder">
											<video width="400" controls controlsList="nodownload">
												<source src="'.$res->url.'">
												Your browser does not support HTML5 video.
											</video>
										</div>
									</div>'; 
						}else{
							$medialisting .= '<div class="col-sm-6 col-xs-12">
										<div class="img_holder" style="text-align:center; margin-top:60px;">
											<audio controls controlsList="nodownload">
												<source src="'.$res->url.'" >
												Your browser does not support HTML5 video.
											</audio>
										</div>
									</div>';
						}
						
						$medialisting .= '<div class="col-sm-6 col-xs-12 article_info">
										<div class="articleDate txtdark">'.date("d M, Y ",strtotime($res->createdDate)).'</div>
										<a href="'.base_url().'media/view/'.$res->slug.'" class="txtblue">
											<h2>'.$res->title.'</h2>
										</a>';
						if($res->description !== "No Data"){
							$medialisting .= '<p class="">'.substr(strip_tags($res->description),0,120).'...</p>';
						}
										
						$medialisting .= '<div class="article_stats">
											<div class="counts"><span class="blogging_icons thank"></span>'.$likes.' Thanks</div>
											<div class="counts"><span class="blogging_icons comment"></span>'.$comments.' Comments</div>
										</div>
									</div>
								</div>
								<div class="row blogactivity">
									<div class="col-sm-3 col-xs-6 text-center">
										<button class="" onclick="addMediaLike('.$res->mediaId.')")><span id="'.$res->mediaId.'" class="blogging_icons ';
										if($isLiked == 1){
											$medialisting .= 'thanked';
										}else{
											$medialisting .= 'thank';
										}
									$medialisting .='"></span> Thank</button>
									</div>
									<div class="col-sm-3 col-xs-6 text-center">
										<a href="'.$baseurl.'media/view/'.$res->slug.'/#share"><button><span class="blogging_icons share"></span> Share</button>
									</div></a>
									<div class="col-sm-6 col-xs-12">
										<a href="'.$baseurl.'media/view/'.$res->slug.'/#comment" class="form-control commentanchor txtdark">Write a comment</a>
									</div>        
								</div>
							</div>';
				}
				echo $medialisting;
			}else{
				echo 1;
			}
		}
	}	
	
	/***************************************** Member View Media ***************************************/
	function view($url){
		if(!Modules::run('site_security/isMember')){
			redirect('login','refresh');
		}
		
		$media = $this->Mdl_media->getMediaByUrl($url);
		if($media == 'no'){
			redirect('errors');
		}
		
		$likes = $this->Mdl_media->getLikeCount($media[0]->mediaId);
		$comments = $this->Mdl_media->getCommentCount($media[0]->mediaId);
		
		$id = $this->session->userdata('userId');
		$type = $this->session->type;
		
		$findSimilar = $this->Mdl_media->getsuggestedMedia($media[0]->categoryId,$media[0]->postedBy,$media[0]->userId,$media[0]->mediaId);
		
		if($findSimilar !== 'no'){
			$relatedMedia = array();
			foreach($findSimilar as $val){
				$final = array();
				$final['title'] = $val->title;
				$final['url'] = base_url().'media/view/'.$val->slug;
				$final['like'] =  $this->Mdl_media->getLikeCount($val->mediaId);
				$relatedMedia[] = $final;
			}
		}else{
			$relatedMedia = "No Data";
		}
		
		$data['suggestedMedia'] = $relatedMedia;
		$data['mediaDetails'] = $media;
		$data['likeCount'] = $likes;
		$data['commentCount'] = $comments;
		
		if($media[0]->postedBy == 'admin'){
			$userData = $this->Mdl_media->getAdminDetails($media[0]->userId);
		}else{
			$userData = $this->Mdl_media->getDoctorDetails($media[0]->userId);
		}
		 
		$comment = $this->show_tree($media[0]->mediaId);
		if($comment == 'no'){
			$data['comments'] = '';
		}else{
			$data['comments'] = $comment;
		}
		
		$template = 'member';
		$data['media'] = '1';
		$data['isLiked'] = $this->Mdl_media->isLiked($media[0]->mediaId, $type, $id);
		$data['isReport'] = $this->Mdl_media->isReport($media[0]->mediaId,$type,$id);
		$data['speciality'] = $this->Mdl_forum->getSpeciality();
		$data['userDetails'] = $userData;
		$data['viewFile'] = 'view';
		$data['scriptFile'] = 'member-media-view';
		$data['module'] = 'media';
		echo Modules::run('template/'.$template, $data);
	}
	
	/***************************************** Load Search Results Based on Key ***************************************/
	function search(){
		$search = $_POST['searchKey'];
		$data = explode(" ",$search);
		
		$msg = "";
		
		if(!empty($search)){
			$categories = $this->Mdl_media->getCategoryBySearch($data);
			$video = $this->Mdl_media->getVideoBySearch($data);
			$audio = $this->Mdl_media->getAudioBySearch($data);
			
			if(is_array($categories)){
		        $msg.="<div class='result_box'><div class='result_tl'>Media based On Category</div>";
                foreach($categories as $val){
                    $msg.="<a href='".base_url()."media/searches/".$val->catSlug."'>".$val->catName."</a>";
                }
                $msg.="</div>"; 
		    } 
			
			if(is_array($video)){
		        $msg.="<div class='result_box'><div class='result_tl'>Video Availables</div>";
                foreach($video as $val){
                    $msg.="<a href='".base_url()."media/view/".$val->slug."'>".$val->title."</a>";
                }
                $msg.="</div>"; 
		    }
			
			if(is_array($audio)){
		        $msg.="<div class='result_box'><div class='result_tl'>Audio Availables</div>";
                foreach($audio as $val){
                    $msg.="<a href='".base_url()."media/view/".$val->slug."'>".$val->title."</a>";
                }
                $msg.="</div>"; 
		    }
			
			if($categories=="no" && $video=="no" && $audio=="no"){
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
		$data['scriptFile'] = 'member-media-list';
		$data['module'] = 'media';
		$data['media'] = '1';
		$data['searchCat'] = $key;
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
		$data['scriptFile'] = 'member-media-list';
		$data['module'] = 'media';
		$data['media'] = '1';
		$data['searchCat'] = '';
		$data['searchSubmit'] = $content['text'];
		$template = 'member';
		echo Modules::run('template/'.$template, $data);
	}
	
	/***************************************** Get Sub-category ***************************************/
	function subcategory(){
		$cid = $_POST['cid'];
		$data=$this->Mdl_media->getSubCategory($cid);
		$str = '<option disabled selected>Choose one</option>';
		if(is_array($data)){
			foreach($data as $val){
				$str .= '<option value="'.$val->catId.'">'.$val->catName.'</option>'; 
			}
			echo $str;
		}else{
			echo "1";
		}
	}
	
/*****************************************************************************************************************************************************************************************************************************************************************************************														Common Functionality
*****************************************************************************************************************************************************************************************************************************************************************************************/

	/***************************************** Add Media Like ***************************************/
	function addLike(){
		$content = $this->input->post();
		
		$uid = $content['user'];
		$utype = $content['type'];
		$mid = $content['mediaId'];
		
		if($uid !== '' || $utype !== '' || $mid !== ''){
			
			$isExist = $this->Mdl_media->isLiked($mid,$utype,$uid);
			if($isExist == 1){
				$this->Mdl_media->deleteLike($uid,$utype,$mid);	
				echo 1; exit;	
			}else{
				$data = array(
					'mediaId' => $mid,
					'userType' => $utype,
					'userId'=> $uid,
					'createdDate' =>date('Y-m-d H:i:s')
				);
				$insert = $this->Mdl_media->insert_like($data);		
				
				$media = $this->Mdl_media->findById($mid);
				
				// Add Notification
				if($utype == 'mem' && $media[0]->postedBy == 'doc'){
					$mem = $this->Mdl_media->getMemberDetails($uid);
					$name = $mem[0]->name;
					$image = base_url().$mem[0]->profileImage;
					
					$text = '<div class="user_dp"><img src="'.$image.'"></div> '.$name.' like your '.$media[0]->mtype.' - “'.$media[0]->title.'” <div class="date">'.date("d M Y ").'</div>';

					$data1 = array(
						'notification' => $text,
						'url' => base_url().'media/watch/'.$media[0]->slug,
						'allDoctors' => '0',
						'userId' => $media[0]->userId,
						'createdDate' => date('Y-m-d H:i:s'),
						'endDate' => date('Y-m-d H:i:s',strtotime("+2 months"))
					);
			
					$this->Mdl_notifications->insert("notification_doctors",$data1);
				}
				
				echo 1; exit;
			}		
		}else{
			echo "Some Error Occured";
		}
	}
	
	/***************************************** Add Media Like Action ***************************************/	
	function mediaLikeAction(){
		$content = $this->input->post();

		$mid = $content['mediaId'];
		$uid = $this->session->userdata('userId');
		$utype = $this->session->type;
		
		if($uid !== '' || $utype !== '' || $mid !== ''){	
			$isExist = $this->Mdl_media->isLiked($mid,$utype,$uid);
			if($isExist == 1){
				$this->Mdl_media->deleteLike($uid,$utype,$mid);
				echo 2;	exit;
			}else{
				$data = array(
					'mediaId' => $mid,
					'userType' => $utype,
					'userId'=> $uid,
					'createdDate' =>date('Y-m-d H:i:s')
				);
				$insert = $this->Mdl_media->insert_like($data);	

				$media = $this->Mdl_media->findById($mid);
				
				// Add Notification
				if($utype == 'mem' && $media[0]->postedBy == 'doc'){
					$mem = $this->Mdl_media->getMemberDetails($uid);
					$name = $mem[0]->name;
					$image = base_url().$mem[0]->profileImage;
					
					$text = '<div class="user_dp"><img src="'.$image.'"></div> '.$name.' like your '.$media[0]->mtype.' - “'.$media[0]->title.'” <div class="date">'.date("d M Y ").'</div>';

					$data1 = array(
						'notification' => $text,
						'url' => base_url().'media/watch/'.$media[0]->slug,
						'allDoctors' => '0',
						'userId' => $media[0]->userId,
						'createdDate' => date('Y-m-d H:i:s'),
						'endDate' => date('Y-m-d H:i:s',strtotime("+2 months"))
					);
			
					$this->Mdl_notifications->insert("notification_doctors",$data1);
				}
				
				echo 1; exit;
			}		
		}else{
			echo "Some Error Occured";
		}
	}
	
	/***************************************** Add Media Report ***************************************/
	function mediaReport(){
		$content = $this->input->post();
		
		$uid = $content['user'];
		$utype = $content['type'];
		$mid = $content['mediaId'];
		
		if($uid !== '' && $utype !== '' && $mid !== ''){
			
			$isReported = $this->Mdl_media->isReport($mid,$utype,$uid);
			if($isReported == 1){
				$this->Mdl_media->deleteMediaReport($uid,$utype,$mid);
				echo 1;	
			}else{
				$data = array(
					'mediaId' => $mid,
					'userType' => $utype,
					'userId'=> $uid,
					'createdDate' =>date('Y-m-d H:i:s')
				);
				$insert = $this->Mdl_media->insert_media_report($data);		
				echo 1;
			}		
		}else{
			echo "Some Error Occured";
		}
	}
	
	/***************************************** Add Comments ***************************************/
	function addComment(){
		$content = $this->input->post();
		
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules("comment","Comment","required|xss_clean",array(
					'required' => 'Comment field should not be empty' ));

		if($this->form_validation->run() == FALSE){
			echo validation_errors();
		}else{
			$data = array(
				'mediaId' => $content['mid'],
				'comment' => $content['comment'],
				'postedBy' => $content['utype'],
				'userId'=> $content['uid'],
				'parentId'=> $content['pid'],
				'isActive'=> 1,
				'createdDate' =>date('Y-m-d H:i:s')
			);
					
			$insert = $this->Mdl_media->insert_comment($data);
			
			$media = $this->Mdl_media->findById($content['mid']);
				
			// Add Notification
			if($content['utype'] == 'mem' && $media[0]->postedBy == 'doc'){
				$mem = $this->Mdl_media->getMemberDetails($content['uid']);
				$name = $mem[0]->name;
				$image = base_url().$mem[0]->profileImage;
				
				$text = '<div class="user_dp"><img src="'.$image.'"></div> '.$name.' commented on your '.$media[0]->mtype.' - “'.$media[0]->title.'” <div class="date">'.date("d M Y ").'</div>';

				$data1 = array(
					'notification' => $text,
					'url' => base_url().'media/watch/'.$media[0]->slug,
					'allDoctors' => '0',
					'userId' => $media[0]->userId,
					'createdDate' => date('Y-m-d H:i:s'),
					'endDate' => date('Y-m-d H:i:s',strtotime("+2 months"))
				);
		
				$this->Mdl_notifications->insert("notification_doctors",$data1);
			}
			echo 1; exit;
		}
	}
	
	function addReport(){
		$content = $this->input->post();
		
		$cid = $content['cid'];
		
		if($cid !== ''){
			$isExist = $this->Mdl_media->isReported($cid);
			if($isExist == 1){
				$this->Mdl_media->deleteCommentReport($cid);
				echo 1; exit;
			}else{
				$data = array(
					'commentId'=> $cid,
					'createdDate' =>date('Y-m-d H:i:s')
				);
				$insert = $this->Mdl_media->insert_report($data);		
				echo 1; exit;
			}		
		}else{
			echo 2; exit;
		}
	}

	/***************************************** Get Comment Id ***************************************/
	function show_tree($mid){
        // create array to store all comments ids
        $store_all_id = array();
        // get all parent comments ids by using news id
        $id_result = $this->Mdl_media->tree_all($mid);
        // loop through all comments to save parent ids $store_all_id array
		
		if($id_result !== 'no'){
			foreach ($id_result as $comment_id) {
				array_push($store_all_id, $comment_id['parentId']);
			}
			// return all hierarchical tree data from in_parent by sending
			//  initiate parameters 0 is the main parent,news id, all parent ids
			return  $this->in_parent(0,$mid, $store_all_id);
		}else{
			return "no";
		}
    }
	
    /***************************************** Get Comment Hierarchy ***************************************/
    function in_parent($in_parent,$mid,$store_all_id) {
        // this variable to save all concatenated html
        $html = "";
        if (in_array($in_parent,$store_all_id)) {
            $result = $this->Mdl_media->tree_by_parent($mid,$in_parent);
            foreach ($result as $re){
				$type = $re['postedBy'];
				$uid = $re['userId'];
				$commentId = $re['commentId'];
				$comment = $re['comment'];
				$date = $re['createdDate'];
				$name = '';
				$url = '';
				$isReported = $this->Mdl_media->isReported($commentId);
				
				if($type == 'admin'){
					$name = 'Just Medical Advice';
					$url = base_url() . 'admin_assets/images/JMA.png';
				}else if($type == 'doc'){
					$docData = $this->Mdl_media->getDoctorDetails($uid);
					$name = $docData[0]->name;
					$url = base_url().$docData[0]->profileImage;
				}else if($type == 'mem'){
					$memData = $this->Mdl_media->getMemberDetails($uid);
					$name = $memData[0]->name;
					$url = base_url().$memData[0]->profileImage;
				}
				
				if($type == 'doc'){
					$html .= '<div class="comment_box">
					<div class="author_dp"><div><img src="'.$url.'"></div></div>
					<div class="comment">
					<div class="comment_container">
					<div class="comment_holder doc_comment">
					<strong class="txtblue">'.$name.' -</strong> '.$comment.'
					</div>
					<div class="comment_detail"><span style="margin-right:15px;">'.date("d-M, Y ",strtotime($date)).'</span> <a href="javascript:;" class="reply" id="'.$commentId.'" >Reply</a>
					<a href="javascript:;" class="report" id="'.$commentId.'"><div class="ldl_box"><button class="like_btn"><span class="blogging_icons flagged"></span> Report Abuse</button></div></a>
					</div></div>';
					$html .= $this->in_parent($commentId, $mid, $store_all_id);
					$html .= '</div></div>';
				}else{
					$html .= '<div class="comment_box">
					<div class="author_dp"><div><img src="'.$url.'"></div></div>
					<div class="comment">
					<div class="comment_container">
					<div class="comment_holder">
					<strong class="txtblue">'.$name.' -</strong> '.$comment.'
					</div>
					<div class="comment_detail"><span style="margin-right:15px;">'.date("d-M, Y ",strtotime($date)).'</span> <a href="javascript:;" class="reply" id="'.$commentId.'">Reply</a>
					<a href="javascript:;" class="report" id="'.$commentId.'"><div class="ldl_box"><button class="like_btn"><span class="blogging_icons ';
					if($isReported == 1){$html .= 'flagged';}else{$html .= 'flag';}
					$html .= '"></span> Report Abuse</button></div></a>
					</div></div>';
					$html .= $this->in_parent($commentId,$mid, $store_all_id);
					$html .= '</div></div>';
				}
			}
        }
        return $html;
    }
}