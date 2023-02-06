<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Message extends MX_Controller{
	
	function __construct() {
		parent::__construct();
		$this->load->model('Mdl_message');
		$this->load->model('doctor/Mdl_doctor');
		$this->load->model('member/Mdl_member');
		$this->load->model('forum/Mdl_forum');
	}
	
	/********************************** Doctor Contact Form **********************************/
	function contactDoctor($id){
		if(!Modules::run('site_security/isLoggedIn')){
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
		
		$res3 = explode(",",$docs[0]->followedMem);
		$list['followedMem'] = sizeof($res3);
		
		$list['answerCount'] = $this->Mdl_doctor->findAnswerCount($docs[0]->regId);
		$list['thanks'] = $this->Mdl_doctor->findThanksCount($docs[0]->regId);
		$list['feedbackCount'] = $this->Mdl_doctor->findFeedbackCount($docs[0]->regId);
		
		$data['doctor'] = $list;
		$data['module'] = 'message';
		$data['viewFile'] = 'contact-doctor';
		$data['scriptFile'] = 'message';
		$data['docList'] = '1';
		$template = 'member';
		echo Modules::run('template/'.$template, $data);
	}
	
	/********************************** Member Send Message **********************************/
    function sendMessage(){
		$content = $this->input->post();
		
		$this->form_validation->set_rules("subject","Subject","required|xss_clean",array(
			'required'=>"<b>Subject is required.</b>"));
		$this->form_validation->set_rules("uid","uid","required|xss_clean",array(
			'required'=>"<b>Some Error Occured.</b>"));
		$this->form_validation->set_rules("docid","docid","required|xss_clean",array(
			'required'=>"<b>Some Error Occured.</b>"));
		$this->form_validation->set_rules("utype","utype","required|xss_clean",array(
			'required'=>"<b>Some Error Occured.</b>"));
		$this->form_validation->set_rules("message","Message","required",array(
                'required'      => "<b>Message is required</b>"));
		
		if($this->form_validation->run() == FALSE){
			echo validation_errors(); exit;
		}else{
			
			$base_64 = $content['docid'] . str_repeat('=', strlen($content['docid']) % 4);
			$doctorId = base64_decode($base_64);
			
			$data=array(
				'doctorId'=>$doctorId,
				'memberId'=>$content['uid'],
				'subject'=>$content['subject'],
				'message'=>$content['message'],
				'reply'=>'',
				'isReadByDoc'=>'0',
				'isReadByMem'=>'0',
				'isReplied'=>'0',
				'postedDate'=>date("Y-m-d H:i:s")
			);
			
			$this->Mdl_message->insert('messages', $data);		
			echo 1; exit;
		}
	}

	/********************************** Message inbox **********************************/
	function inbox(){
		if(!Modules::run('site_security/isLoggedIn')){
			redirect('login','refresh');
		}
		
		$id = $this->session->userdata('userId');
		$type = $this->session->userdata('type');
		
		if($type == 'doc'){
			$doc = $this->Mdl_doctor->getDoctorDetails($id);
			$speciality = $doc[0]->speciality; 		
			$followers = $doc[0]->followedMem;

			$findForums = $this->Mdl_forum->getForumsForDocs($speciality,$followers);
			if($findForums != 'no'){
				$forums = array();
				foreach($findForums as $val){
					$final = array();
					$final['question'] = $val->question;
					$final['url'] = base_url().'forum/view/'.$val->slug;
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
			$template = 'doctor';
		}else{
			$result = $this->Mdl_doctor->getFeaturedDoctors();
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
				$sub_array[] = sizeof(explode(",",$res->followedMem));
				$featuredDoc[] = $sub_array;
			}
			
			$data['docList'] = $featuredDoc;
			$template = 'member';
		}
		
		$data['module'] = 'message';
		$data['viewFile'] = 'message-list';
		$data['scriptFile'] = 'message';
		echo Modules::run('template/'.$template, $data);
	}
	
	/********************************** Message inbox **********************************/
	function getInboxData(){
		if(!Modules::run('site_security/isLoggedIn')){
			redirect('login','refresh');
		}
		
		$page =  $_GET['page'];
		$record = 15;
		$start = ($page - 1) * $record;
		
		$id = $this->session->userdata('userId');
		$type = $this->session->userdata('type');
		
		if($type == 'doc'){
			$result = $this->Mdl_message->getDoctorInbox($id,$start,$record);
		}else{
			$result = $this->Mdl_message->getMemberInbox($id,$start,$record);
		}
		
		if($result !== 'no'){
			$html = '';
			
			foreach($result as $res){
				$mem = $this->Mdl_doctor->getMemberDetails($res->memberId);
				$doc = $this->Mdl_doctor->getDoctorDetails($res->doctorId);
				
				$mid = base64_encode($res->messageId);
				$mid = str_replace(str_repeat('=',strlen($mid)/4),"",$mid);
		
				if($type == 'doc'){
					$html .= '<div class="msg_item">
								<div class="msg_dp"><img src="'.base_url().$mem[0]->profileImage.'"></div>
									<div class="msg_info">
											<div class="msgDate">'.date("d M Y ",strtotime($res->postedDate)).'</div>
											<button class="msgdelete" data-toggle="tooltip" title="Delete this Message"></button>
											<div class="messangerName"><strong class="txtblue">'.$mem[0]->name.'</strong></div>
											<a href="'.base_url().'message/view-message/'.$mid.'" class="messageShort">';
											if($res->isReadByDoc == 1){
												$html .= ' '.$res->subject.' ';
											}else{
												$html .= '<strong>'.$res->subject.'</strong> ';
											}
											$html .= substr($res->message,0,120).'...</a>
										</div>
										<div class="clearfix"></div>
									</div>';
				}else{
					$html .= '<div class="msg_item">
								<div class="msg_dp"><img src="'.base_url().$doc[0]->profileImage.'"></div>
									<div class="msg_info">
											<div class="msgDate">'.date("d M Y ",strtotime($res->postedDate)).'</div>
											<button class="msgdelete" data-toggle="tooltip" title="Delete this Message"></button>
											<div class="messangerName"><strong class="txtblue">'.$doc[0]->name.'</strong></div>
											<a href="'.base_url().'message/view-message/'.$mid.'" class="messageShort">';
											if($res->isReadByMem == 1){
												$html .= ' '.$res->subject.' ';
											}else{
												$html .= '<strong>'.$res->subject.'</strong> ';
											}
											$html .= substr($res->reply,0,120).'...</a>
										</div>
										<div class="clearfix"></div>
									</div>';
				}
				
			}
			echo $html; exit;
		}else{
			echo 1; exit;
		}
	}
	
	/********************************** Message Sent **********************************/
	function getSentData(){
		if(!Modules::run('site_security/isLoggedIn')){
			redirect('login','refresh');
		}
		
		$page =  $_GET['page'];
		$record = 15;
		$start = ($page - 1) * $record;
		
		$id = $this->session->userdata('userId');
		$type = $this->session->userdata('type');
		
		if($type == 'doc'){
			$result = $this->Mdl_message->getDoctorSent($id,$start,$record);
		}else{
			$result = $this->Mdl_message->getMemberSent($id,$start,$record);
		}
		
		if($result !== 'no'){
			$html = '';
			
			foreach($result as $res){
				$mem = $this->Mdl_doctor->getMemberDetails($res->memberId);
				$doc = $this->Mdl_doctor->getDoctorDetails($res->doctorId);
				
				$mid = base64_encode($res->messageId);
				$mid = str_replace(str_repeat('=',strlen($mid)/4),"",$mid);
				
				if($type == 'doc'){
					$html .= '<div class="msg_item">
								<div class="msg_dp"><img src="'.base_url().$mem[0]->profileImage.'"></div>
									<div class="msg_info">
											<div class="msgDate">'.date("d M Y ",strtotime($res->postedDate)).'</div>
											<button class="msgdelete" data-toggle="tooltip" title="Delete this Message"></button>
											<div class="messangerName"><strong class="txtblue">'.$mem[0]->name.'</strong></div>
											<a href="'.base_url().'message/view-message/'.$mid.'" class="messageShort">'.$res->subject.' '.substr($res->reply,0,120).'...</a>
										</div>
										<div class="clearfix"></div>
									</div>';
				}else{
					$html .= '<div class="msg_item">
								<div class="msg_dp"><img src="'.base_url().$doc[0]->profileImage.'"></div>
									<div class="msg_info">
											<div class="msgDate">'.date("d M Y ",strtotime($res->postedDate)).'</div>
											<button class="msgdelete" data-toggle="tooltip" title="Delete this Message"></button>
											<div class="messangerName"><strong class="txtblue">'.$doc[0]->name.'</strong></div>
											<a href="'.base_url().'message/view-message/'.$mid.'" class="messageShort">'.$res->subject.' '.substr($res->message,0,120).'...</a>
										</div>
										<div class="clearfix"></div>
									</div>';
				}
				
			}
			echo $html; exit;
		}else{
			echo 1; exit;
		}
	}
	
	/********************************** Message Sent **********************************/
	function viewMessage($id){
		if(!Modules::run('site_security/isLoggedIn')){
			redirect('login','refresh');
		}
		
		$data['mid'] = $id;
		
		$base_64 = $id . str_repeat('=', strlen($id) % 4);
		$mid = base64_decode($base_64);
		
		$msg = $this->Mdl_message->getMessageById($mid);
		$msgDetail = array();
		
		foreach($msg as $res){
			$mem = $this->Mdl_doctor->getMemberDetails($res->memberId);
			$doc = $this->Mdl_doctor->getDoctorDetails($res->doctorId);
			
			$msgDetail['messageId'] = $res->messageId;
			$msgDetail['docName'] = $doc[0]->name;
			$msgDetail['docImage'] = $doc[0]->profileImage;
			$msgDetail['memName'] = $mem[0]->name;
			$msgDetail['memImage'] = $mem[0]->profileImage;
			$msgDetail['subject'] = $res->subject;
			$msgDetail['message'] = $res->message;
			$msgDetail['reply'] = $res->reply;
			$msgDetail['isReplied'] = $res->isReplied;
			$msgDetail['postedDate'] = $res->postedDate;
		}
		
		$id = $this->session->userdata('userId');
		$type = $this->session->userdata('type');
		
		if($type == 'doc'){
			$data1 =array(
				'isReadByDoc'=>'1'
			);
			
			$this->Mdl_message->update('messages', $data1, $mid,'messageId');		
		
			$doc = $this->Mdl_doctor->getDoctorDetails($id);
			$speciality = $doc[0]->speciality; 		
			$followers = $doc[0]->followedMem;

			$findForums = $this->Mdl_forum->getForumsForDocs($speciality,$followers);
			if($findForums != 'no'){
				$forums = array();
				foreach($findForums as $val){
					$final = array();
					$final['question'] = $val->question;
					$final['url'] = base_url().'forum/view/'.$val->slug;
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
			$template = 'doctor';
		}else{
			
			$data1 = array(
				'isReadByMem'=>'1'
			);
			
			$this->Mdl_message->update('messages', $data1, $mid,'messageId');
			
			$result = $this->Mdl_doctor->getFeaturedDoctors();
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
				$sub_array[] = sizeof(explode(",",$res->followedMem));
				$featuredDoc[] = $sub_array;
			}
			
			$data['docList'] = $featuredDoc;
			$template = 'member';
		}
		
		$data['message'] = $msgDetail;
		$data['module'] = 'message';
		$data['viewFile'] = 'message-view';
		$data['scriptFile'] = 'message';
		echo Modules::run('template/'.$template, $data);
	
	}
	
	/********************************** Reply Message **********************************/
	function reply(){
		$content = $this->input->post();
		
		$this->form_validation->set_rules("reply","reply","required|xss_clean",array(
			'required'=>"Reply is required."));
		$this->form_validation->set_rules("msgId","message id","required|xss_clean",array(
			'required'=>"Some Error Occured."));
		
		if($this->form_validation->run() == FALSE){
			echo validation_errors(); exit;
		}else{
			$data=array(
				'reply'=>$content['reply'],
				'isReplied'=>'1'
			);
			
			$this->Mdl_message->update('messages', $data, $content['msgId'],'messageId');		
			echo 1; exit;
		}
	}
	
	/********************************** Delete Message **********************************/
	function deleteMessage($id){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$mid = base64_decode($base_64);
			
			$delete = $this->Mdl_message->deleteById($mid);
			redirect('message/inbox');
		}
	}
}