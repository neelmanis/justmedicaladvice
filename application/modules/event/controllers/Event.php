<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Event extends MX_Controller{
	
	function __construct() {
		parent::__construct();
		$this->load->model('Mdl_event');	
	}
	
	function details(){
		$data['scriptFile'] = "jma-event";
		$data['module'] = "event";
		$data['viewFile'] = "details";
		$template = 'home';
		echo Modules::run('template/'.$template, $data);
	}
	
	function getEventList(){
		$page =  $_GET['page'];
		$record = 5;
		$start = ($page - 1) * $record;
		
		$events = $this-> Mdl_event->getEvents($start,$record);
		
		if($events == 'no'){
			echo 1; exit;
		}else{
			$msg = '';
			$i = 1;
			$now = strtotime("now");
			
			foreach($events as $res){
				$msg .= '<div class="row">
							<div class="container-fluid">
								<div class="eventBox">
									<div class="eventTitle col-sm-12">
										<div class="calender">
											<div class="year">'.date("Y ",strtotime($res->eDate)).'</div>
											<div class="date font_regular txtdark"><strong>'.date("d",strtotime($res->eDate)).'</strong>'.date("M",strtotime($res->eDate)).'</div>
										</div>
										<div class="info">
											<div class="eventName">'.$res->title;
											
											$date = strtotime($res->eDate);
											if($date > $now){
												$msg .= '  <span class="upcomingTag">Upcoming</span>';
											}
							$msg .= '</div><p>Venue: '.$res->venue.'</p>
									<p>Time: '.$res->eTime.'</p>
								</div>
							</div>
							<div class="eventBrief">
								<div class="row">
									<div class="col-md-9">'.$res->description.'</div>
									<div class="col-md-3 eventGallery">';
									
									if($res->images !== 'No Data'){
										$imageList = explode(",",$res->images);
										foreach($imageList as $image){
											$url = base_url().'admin_assets/images/event/'.$image;
											$msg .= '<a href="'.$url.'" data-fancybox="gallery'.$i.'" data-caption="">
											<img src="'.$url.'"></a>';
										}
									}
									
							$msg .= '</div></div></div></div></div></div>';
							$i++;
			}
			echo $msg;
		}
	}
	
	/******************************************* Admin Event Listing *******************************************/
	function listEvent(){
		if(!Modules::run('site_security/isLoggedIn')){
			redirect('admin','refresh');
		}
		
		$events = $this-> Mdl_event->listAll();
		$eventData = array();
		if($events !== "no"){
			foreach($events as $val){
				$res = array();
				$id = base64_encode($val->eventId);
				$id = str_replace(str_repeat('=',strlen($id)/4),"",$id);
				$res['id'] = $id;
				$res['title'] = $val->title;
				$res['venue'] = $val->venue;
				$res['date'] = $val->eDate;
				$res['time'] = $val->eTime;
				$res['addedBy'] = $this->Mdl_event->findName($val->addedBy);
				$res['status'] = $val->isActive;
				$res['createdDate'] = $val->createdDate;
				$eventData[] = $res;
			}
			$data['eventList'] = $eventData;
		}else{
			$data['eventList'] = "No Data";
		}
		$data['viewFile'] = "list-event";
		$data['page'] = 'listEvents';
		$template = 'admin';
		echo Modules::run('template/'.$template, $data);
	}
	
	/***************************************** Active Event Action ***************************************/
	function activeAction($id){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$eid = base64_decode($base_64);
			$this->Mdl_event->statusActive($eid);
			redirect('event/list-event');	
		}
	}
	
	/***************************************** Inactive Event Action ***************************************/
	function inActiveAction($id){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$eid = base64_decode($base_64);
			$this->Mdl_event->statusInactive($eid);
			redirect('event/list-event');	
		}
	}
	
	/***************************************** Delete Event ***************************************/
	function deleteEvent(){
		$eid = $_POST['eventId'];
		$base_64 = $eid . str_repeat('=', strlen($eid) % 4);
		$eId = base64_decode($base_64);
		$delete = $this->Mdl_event->deleteById($eId);
		echo $delete;
	}
	
	/***************************************** Admin Add Event ***************************************/
	function addEvent(){
		if(!Modules::run('site_security/isLoggedIn')){
			redirect('admin','refresh');
		}
		
		$data['viewFile'] = 'add-event';
		$data['page'] = 'addEvents';
		$template = 'admin';
		echo Modules::run('template/'.$template, $data);
	}
	
	/***************************************** Admin Add Event Action ***************************************/
	function addEventAction(){
		$content = $this->input->post();
		
        $this->form_validation->set_rules("title","Title","required|xss_clean",array(
					'required'      => '<b>Title is required</b>'
		));
		$this->form_validation->set_rules("description","Description","required|xss_clean",array(
					'required'      => '<b>Description is required</b>'
		));
		$this->form_validation->set_rules("venue","Venue","required|xss_clean",array(
					'required'      => '<b>Venue is required</b>'
		));
		$this->form_validation->set_rules("eDate","Event Date","required|xss_clean",array(
					'required'      => '<b>Event Date is not selected</b>'
		));
		$this->form_validation->set_rules("eTime","Event Time","required|xss_clean",array(
					'required'      => '<b>Event Time is required</b>'
		));

		if($this->form_validation->run() == FALSE){
			echo validation_errors();
		}else{
			$imageNames = '';
			
			$myFile = $_FILES['upload'];
			if($myFile["error"][0] == 4){
				$isUpload = false; 
			}else{
				$isUpload = true;
			}
			
			if($isUpload){
				$total = count($_FILES['upload']['name']);
				for($i=0; $i<$total; $i++){
					$eventimg = $myFile['name'][$i];
					$imgname = str_replace(array(" ","'"),"_",time().$eventimg);
					$_FILES['image']['name']     = $myFile['name'][$i];
					$_FILES['image']['type']     = $myFile['type'][$i];
					$_FILES['image']['tmp_name'] = $myFile['tmp_name'][$i];
					$_FILES['image']['error']     = $myFile['error'][$i];
					$_FILES['image']['size']     = $myFile['size'][$i];
					$img = $this->uploadFile($imgname,"image","event");
					if($img !== 1){
						echo $img; exit;
					}
					
					if($total == ($i+1)){
						$imageNames .= $imgname;
					}else{
						$imageNames .= $imgname.',';
					}
				}
			}
			
			if($imageNames == ''){
				$imageNames = 'No Data';
			}
			
			$data = array(
				'title' => $content['title'],
				'description'=> $content['description'],
				'venue'=> $content['venue'],
				'eDate'=> $content['eDate'],
				'eTime'=> $content['eTime'],
				'images'=> $imageNames,
				'addedBy' => $this->session->userdata('userId'),
				'isActive' => '1',
				'createdDate' =>date('Y-m-d H:i:s'),
				'modifiedDate' =>date('Y-m-d H:i:s')
			);
				
			$insert = $this->Mdl_event->insert($data);
			echo 1; exit;
		}
	}
	
	/***************************************** Admin Edit Blog ***************************************/
	function editEvent($id){
		if(!Modules::run('site_security/isLoggedIn')){
			redirect('admin','refresh');
		}
		
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$bid = base64_decode($base_64);
			$event = $this->Mdl_event->findById($bid);
			$data['getData'] = $event;
			$data['viewFile'] = "edit-event";
			$data['page'] = "editEvents";
			$template = 'admin';
			echo Modules::run('template/'.$template, $data);		
		}
	}	
	
	/***************************************** Admin Edit Event Action ***************************************/
	function editEventAction(){
		$content = $this->input->post();
		
        $this->form_validation->set_rules("title","Title","required|xss_clean",array(
					'required'      => '<b>Title is required</b>'
		));
		$this->form_validation->set_rules("description","Description","required|xss_clean",array(
					'required'      => '<b>Description is required</b>'
		));
		$this->form_validation->set_rules("venue","Venue","required|xss_clean",array(
					'required'      => '<b>Venue is required</b>'
		));
		$this->form_validation->set_rules("eDate","Event Date","required|xss_clean",array(
					'required'      => '<b>Event Date is not selected</b>'
		));
		$this->form_validation->set_rules("eTime","Event Time","required|xss_clean",array(
					'required'      => '<b>Event Time is required</b>'
		));
		$this->form_validation->set_rules("status1","Status","required|xss_clean",array(
					'required'      => '<b>Select the status</b>'
		));

		if($this->form_validation->run() == FALSE){
			echo validation_errors();
		}else{
			
			$myFile = $_FILES['upload'];
			$fileCount = count($myFile["name"]);
			if($myFile["error"][0] == 4){
				$isUpload = false; 
			}else{
				$isUpload = true;
			}
			
			$imageNames = '';
			
			if($isUpload){
				$total = count($_FILES['upload']['name']);
				for($i=0; $i<$total; $i++){
					$eventimg = $_FILES['upload']['name'][$i];
					$imgname = str_replace(array(" ","'"),"_",time().$eventimg);
					$_FILES['image']['name']     = $_FILES['upload']['name'][$i];
					$_FILES['image']['type']     = $_FILES['upload']['type'][$i];
					$_FILES['image']['tmp_name'] = $_FILES['upload']['tmp_name'][$i];
					$_FILES['image']['error']     = $_FILES['upload']['error'][$i];
					$_FILES['image']['size']     = $_FILES['upload']['size'][$i];
					$img = $this->uploadFile($imgname,"image","event");
					if($img !== 1){
						echo $img; exit;
					}
					
					if($total == ($i+1)){
						$imageNames .= $imgname;
					}else{
						$imageNames .= $imgname.',';
					}
				}
			}
			
			if($imageNames == ''){
				$imageNames = $content['images'];
			}	
				
			$data = array(
				'title' => $content['title'],
				'description'=> $content['description'],
				'venue'=> $content['venue'],
				'eDate'=> $content['eDate'],
				'eTime'=> $content['eTime'],
				'images'=> $imageNames,
				'addedBy' => $this->session->userdata('userId'),
				'isActive' => $content['status1'],
				'createdDate' =>date('Y-m-d H:i:s'),
				'modifiedDate' =>date('Y-m-d H:i:s')
			);
			
			$id = $content['eventId'];
			$insert = $this->Mdl_event->update($data,$id);
			echo 1; exit;
		}
	}
	
	/***************************************** File Upload ***************************************/
	function uploadFile($imageName,$key,$folderName){
		$config['file_name'] = $imageName;
		$config['upload_path'] = './admin_assets/images/'.$folderName; 
		$config['allowed_types'] = "jpg|png|jpeg|JPEG|svg";
		$config['max_size'] = '1000';
		$config['max_width']  = '3000';
		$config['max_height']  = '3000';
		
		$this->load->library('upload',$config);
		$this->upload->initialize($config);
		   
		if (!$this->upload->do_upload($key)){
			return $this->upload->display_errors();
		}else{
			return 1;
		} 
	}
}

