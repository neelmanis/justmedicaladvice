<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require ('vendor/autoload.php');

class Webinar extends MX_Controller{
	/*------------------------------ CONSTRUCTOR FUNCTION---------------------------------------*/	
	function __construct(){
		parent::__construct();
		$this->load->model('Mdl_webinar');
		$this->load->model('doctor/Mdl_doctor');
		$this->load->model('forum/Mdl_forum');
		$this->load->model('notifications/Mdl_notifications');
	}

	function test(){
		/*$client = new \Citrix\Authentication\Direct('xbXUkZwPGijZz9B3FeD2vlK6A8eT58M7');
		$client->auth('sameer_dr@yahoo.co.in', 'Brother678');
		
		$goToWebinar = new \Citrix\GoToWebinar($client);
		$webinars = $goToWebinar->getPast();
		print_r($webinars);*/
		$now = date('Y-m-d H:i:s');
		$getStoredData = $this->Mdl_webinar->getUpcomingWebinars($now);
		print_r($getStoredData);
	}
	
	function create(){
		if(!Modules::run('site_security/isDoctor')){
			redirect('login','refresh');
		}
		
		$data['module'] = 'webinar';
        $data['viewFile'] = 'create-webinar';
        $data['scriptFile'] = 'doctor-webinar';
        $data['webinar'] = '1';
		$template = 'doctor';
		echo Modules::run('template/'.$template, $data);
	}
	
	
	function addWebinarAction(){
		$content = $this->input->post();
		
		$this->form_validation->set_rules("subject","Subject","required|xss_clean",array(
					'required'      => '<b>Subject is required. </b>'
		));
		$this->form_validation->set_rules("edate","Date","required|xss_clean",array(
					'required'      => '<b>Date is not selected. </b>'
		));
		$this->form_validation->set_rules("startTime","Start Time","required|xss_clean",array(
					'required'      => '<b>Start time is not selected. </b>'
		));		
		$this->form_validation->set_rules("endTime","End Time","required|xss_clean",array(
					'required'      => '<b>End time is not selected. </b>'
		));
		$this->form_validation->set_rules("description","Description","required|xss_clean",array(
			'required'      => '<b>Description is required. </b>'
		));
		
		if($this->form_validation->run() == FALSE){
			echo validation_errors();
		}else{
			
			$eventD = date("Y-m-d",strtotime($content['edate'])); 
			$eventST = date("H:i:s",strtotime($content['startTime'])); 
			$eventET = date("H:i:s",strtotime($content['endTime'])); 
			
			$eventSDT = $eventD.' '.$eventST;
			$eventEDT = $eventD.' '.$eventET; 
			
			$start = str_replace('+00:00', 'Z', gmdate('c', strtotime($eventSDT))); 
			$end = str_replace('+00:00', 'Z', gmdate('c', strtotime($eventEDT)));
			$sub = $content['subject'];
			$desc = $content['description'];
				
			$client = new \Citrix\Authentication\Direct('xbXUkZwPGijZz9B3FeD2vlK6A8eT58M7');
			$client->auth('sameer_dr@yahoo.co.in', 'Brother678');	
			
			$goToWebinar = new \Citrix\GoToWebinar($client);
			$token = $goToWebinar->getToken();
			
			$curl = curl_init();

			curl_setopt_array($curl, array(
			CURLOPT_URL => "https://api.getgo.com/G2W/rest/organizers/1659238205564947718/webinars",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "{\r\n  \"subject\": \"$sub\",\r\n  \"description\": \"$desc\",\r\n  \"times\": [\r\n    {\r\n      \"startTime\": \"$start\",\r\n      \"endTime\": \"$end\"\r\n    }\r\n  ],\r\n  \"timeZone\": \"Asia/Calcutta\"\r\n}",
			CURLOPT_HTTPHEADER => array(
				"Accept: application/json",
				"Cache-Control: no-cache",
				"Content-Type: application/json",
				"Authorization:$token"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);

			if ($err) {
				echo "Something went wrong."; exit;
			} else {
				$result = json_decode($response, true);
				$data = array(
					'webinarId' => $result['webinarKey'],
					'subject'=> $sub,
					'startTime'=> date('Y-m-d H:i:s',strtotime($eventSDT)),
					'endTime' => date('Y-m-d H:i:s',strtotime($eventEDT)),
					'description' => $desc,
					'createdBy' => $this->session->type,
					'userId' => $this->session->userdata('userId'),
					'createdDate' =>date('Y-m-d H:i:s'),
					'modifiedDate' =>date('Y-m-d H:i:s')
				);
				
				$this->Mdl_webinar->insert($data);
				
				$docs = $this->Mdl_webinar->getDoctorDetails($this->session->userdata('userId'));
				
				$data['receiverEmail'] =  $docs[0]->email;
				$data['name'] =  $docs[0]->name;
				$data['title'] = $sub;
				$data['receiverEmail'] =  $docs[0]->email;
				$data['subject'] =  "Webinar confirmation";
					
				$emailans =  Modules::Run('email/mailer',$data);
				if($emailans){
					echo 1; exit;
				}else{
					echo 2; exit;
				}
			}
		}
	}
	
	function listall(){
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
		
		$client = new \Citrix\Authentication\Direct('xbXUkZwPGijZz9B3FeD2vlK6A8eT58M7');
		$client->auth('sameer_dr@yahoo.co.in', 'Brother678');
		
		$goToWebinar = new \Citrix\GoToWebinar($client);
		
		$now = date('Y-m-d H:i:s',strtotime("-1 day"));
		$upcoming = $this->Mdl_webinar->getUpcomingWebinars($now);
		//print_r($upcoming); exit;
		
		$webinarList = array();
		if($upcoming !== "no"){
			foreach($upcoming as $val){
				$custom = array();
				$custom['webinarKey'] = $val->webinarId;
				$custom['subject'] = $val->subject;
				$custom['description'] = $val->description;
				$custom['start'] = $val->startTime;
				$custom['end'] = $val->endTime;
				if($val->createdBy == 'admin'){
					$custom['name'] = 'Just Medical Advice';
					$custom['url'] = 'admin_assets/images/JMA.png';
					$custom['profileUrl'] = 'javascript:;';
				}else{
					$docData = $this->Mdl_webinar->getDoctorDetails($val->userId);
					$custom['name'] = $docData[0]->name;
					$custom['url'] = $docData[0]->profileImage;
					$docid = base64_encode($docData[0]->regId);
					$docid = str_replace(str_repeat('=',strlen($docid)/4),"",$docid);	
					$custom['profileUrl'] = base_url().'doctor/view/'.$docid;						
				}
				$custom['isRegister'] = $this->Mdl_webinar->isRegistered($val->webinarId, $this->session->userdata('userId'));
				$webinarList[] = $custom;
			}
		}
		
		$data['docList'] = $featuredDoc;
		$data['webinars'] = $webinarList;
		$data['speciality'] = $this->Mdl_forum->getSpeciality();
		$data['viewFile'] = 'list';
		$data['scriptFile'] = 'member-webinar';
		$data['module'] = 'webinar';
		$data['webinar'] = '1';
		$template = 'member';
		echo Modules::run('template/'.$template, $data);
	}
	
	function register($id){
		if(!Modules::run('site_security/isMember')){
			redirect('login','refresh');
		}
		
		$data['id'] = $id;
		$data['module'] = 'webinar';
        $data['viewFile'] = 'register';
        $data['scriptFile'] = 'member-webinar';
        $data['webinar'] = '1';
		$template = 'member';
		echo Modules::run('template/'.$template, $data);
	}
	
	function registerAction(){
		$content = $this->input->post();
		$this->form_validation->set_rules("fname","First Name","required|xss_clean|regex_match[/^[A-Z a-z]+$/]",array(
			'required'      => '<b>First Name is required. </b>',
			'regex_match' => '<b>First Name should not contain numbers or other special characters.</b>'
		));
		$this->form_validation->set_rules("lname","Last Name","required|xss_clean|regex_match[/^[A-Z a-z]+$/]",array(
			'required'      => '<b>Last Name is required. </b>',
			'regex_match' => '<b>Last Name should not contain numbers or other special characters.</b>'
		));
		$this->form_validation->set_rules("email","Email","required|trim|valid_email|xss_clean",array(
			'required'=>'<b>Email is Required</b>',
			'valid_email'=>'<b>Email is not valid</b>'
		));
		$this->form_validation->set_rules("key","Key","required|trim|regex_match[/^[0-9]+$/]|xss_clean",array(
			'required'    => '<b>Something went wrong </b>',
			'regex_match' => '<b>Something went wrong</b>'
		));
		
		if($this->form_validation->run() == FALSE){
			echo validation_errors();
		}else{
			if($this->Mdl_webinar->isExist($content['key'])){
				$client = new \Citrix\Authentication\Direct('xbXUkZwPGijZz9B3FeD2vlK6A8eT58M7');
				$client->auth('sameer_dr@yahoo.co.in', 'Brother678');	

				$goToWebinar = new \Citrix\GoToWebinar($client);
				$registrantData = array('email' => $content['email'], 'firstName' => $content['fname'], 'lastName' => $content['lname']);
				$registration = $goToWebinar->register($content['key'],$registrantData);
				
				$res = (array)$registration;

				if(isset($res["status"]) && $res["status"] == "APPROVED"){
					$data = array(
						'webinarId' => $content['key'],
						'userType' => $this->session->type,
						'userId' => $this->session->userdata('userId'),
						'email' => $content['email'],
						'createdDate' =>date('Y-m-d H:i:s')
					);
				
					$this->Mdl_webinar->_insert($data);
					echo 1; exit;
				}else{
					echo "Your request is rejected. try again"; exit;
				}
			}else{
				echo "Something went wrong. Webinar does not exist."; exit;
			}
		}
	}
}