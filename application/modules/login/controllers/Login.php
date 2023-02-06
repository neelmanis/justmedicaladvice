<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login extends MX_Controller{
	function __construct() {
		parent::__construct();
		$this->load->model('Mdl_login');
		$this->load->model('member/Mdl_member');
		$this->load->helper('cookie');
	}
	
	function index(){
		$this->load->library('user_agent');  // load user agent library
		//Set session for the referrer url
		//echo $this->agent->referrer(); exit;
		$this->session->set_userdata('referrer_url', $this->agent->referrer() );  

		$data['isHomePage'] = '0';
		$data['viewFile'] = "login";
		$data['module'] = "login";
		$data['scriptFile'] = 'jma-login';
		$template = 'home';
		echo Modules::run('template/'.$template, $data);
	}
	
	function submitLogin(){
		$content = $this->input->post();
	
		$this->form_validation->set_rules("mobile","Mobile Number","trim|required|xss_clean|regex_match[/^[0-9]+$/]",
			array(
					'required'      => 'You have not provided %s.',
					'regex_match'   => 'Entered %s is not valid.'
			)
		);
		$this->form_validation->set_rules("pass","Passowrd","required|xss_clean");
		
		if($this->form_validation->run() == FALSE){
			echo validation_errors();
		}else{
			$mobile=$content['mobile'];
			$password = Modules::run('site_security/makeHash',$content['pass']);
				
			$check=$this->Mdl_login->checkLogin($mobile);
			
			if(!empty($check) && is_array($check)){
				$pass=$check[0]->password;
					 
				if($password !== $pass){
					echo 2; exit;
				}else{
					$user = $this->session->set_userdata('userId',$check[0]->id);
					$user = $this->session->set_userdata('type',$check[0]->type);
					
					if(isset($content['setC'])){
						$pass = Modules::run('site_security/makeHash',$content['pass']); 
						$cookieUser = $cookie = array(
													'name'   => 'mobile',
													'value'  => $mobile,
													'expire' => '86500'
												);
						$cookiePass = array(
											"name"=>"password",
											"value"=>$pass,
											'expire' => '86500'
										  );
							
						$this->input->set_cookie($cookieUser);
						$test = $this->input->set_cookie($cookiePass);
					}else{
						delete_cookie('mobile');
						delete_cookie('password');
					}
					
					if($check[0]->statusCode == '6'){
						if($check[0]->type == 'doc'){
							echo 3; exit;
						}else if($check[0]->type == 'mem'){
							if( $this->session->userdata('referrer_url') ) {
								
								$redirect_back = $this->session->userdata('referrer_url');
								$this->session->unset_userdata('referrer_url');
								$base = base_url();
								$signup = base_url().'signup';
								$memlogout = base_url().'member/logout';
								
								if(strpos($redirect_back,'blog') > 0 || strpos($redirect_back,'media') > 0 || strpos($redirect_back,'forum') > 0 || strpos($redirect_back,'doctor/view') > 0){	
									$mem = $this->Mdl_member->getMemmberDetails($check[0]->id);
									
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
													
									$user = $this->session->set_userdata('type',$type);
									$this->session->set_userdata('userData', $sessionData);
									
									echo $redirect_back; exit;
								}else{
									echo 4; exit;
								}
							}else{
								echo 4; exit;
							}
						}
					}else if($check[0]->statusCode == '2'){
						$now = date('Y-m-d H:i:s',strtotime("now"));
						$end = date('Y-m-d H:i:s',strtotime($check[0]->expireAt));
						if($now > $end){
							$otp = rand(100000,999999);
							$isd = $check[0]->isd;
							$mobile = $check[0]->mobile;
							
							$res = $this->sendMsg($isd, $mobile, $otp,'jma');
							if($res == 1){
								echo 13; exit;
							}else{	
								$isSend = json_decode($res,true);
								$status = $isSend['Status']; // Success or Error
								if($status == 'Error'){
									echo 13; exit;
								}else{
									$enddate=strtotime("+30 minutes");
									
									$updatedata=array(
										'otp' => $otp,
										'expireAt' => date("Y-m-d H:i:s", $enddate),
										'statusCode' => '1',
										'modifiedDate'=>date("Y-m-d H:i:s")	
									);
									$this->Mdl_login->update("registration",$updatedata,$check[0]->id,"id");
									echo 5; exit;
								}
							}
						}else{
							echo 6; exit;
						}
					}else if($check[0]->statusCode == '3'){
						echo 14; exit;
					}else if($check[0]->type == 'doc' && $check[0]->statusCode == '7'){
						echo 7; exit;
					}else if($check[0]->statusCode == '8'){
						echo 8; exit;
					}else if($check[0]->statusCode == '1' ){
						$otp = rand(100000,999999);
						$isd = $check[0]->isd;
						$mobile = $check[0]->mobile;
							
						$res = $this->sendMsg($isd, $mobile, $otp,'jma');
						if($res == 1){
							echo 13; exit;
						}else{	
							$isSend = json_decode($res,true);
							$status = $isSend['Status']; // Success or Error
							if($status == 'Error'){
								echo 13; exit;
							}else{
								$enddate=strtotime("+30 minutes");
								
								$updatedata=array(
									'otp' => $otp,
									'expireAt' => date("Y-m-d H:i:s", $enddate),
									'statusCode' => '1',
									'modifiedDate'=>date("Y-m-d H:i:s")	
								);
								$this->Mdl_login->update("registration",$updatedata,$check[0]->id,"id");
								echo 9; exit;
							}
						}
					}else if($check[0]->statusCode == '4'){
						if($check[0]->type == 'doc'){
							echo 10; exit;
						}else if($check[0]->type == 'mem'){
							echo 11; exit;
						}
					}else if($check[0]->statusCode == '5'){
						if($check[0]->type == 'doc'){
							echo 12; exit;
						}
					}
				}
			}else{
				echo 1;
			}
		}
	}
	
	function forgotPassword(){		
		$data['isHomePage'] = '0';
		$data['viewFile'] = "forgot-password";
		$data['module'] = "login";
		$data['scriptFile'] = 'jma-login';
		$template = 'home';
		echo Modules::run('template/'.$template, $data);
	}
	
	function forgotPasswordAction(){
		$content = $this->input->post();
	
		$this->form_validation->set_rules("mobile","Mobile Number","trim|required|xss_clean|regex_match[/^[0-9]+$/]",
			array(
					'required'      => 'You have not provided %s.',
					'regex_match'   => 'Entered %s is not valid.'
			)
		);
		
		if($this->form_validation->run() == FALSE){
			echo validation_errors();
		}else{
			$condition = $this->Mdl_login->isMobileExist($content['mobile']);
			if($condition !== 'no'){
				$otp = rand(100000,999999);
				$isd = $condition[0]->isd;
				$mobile = $condition[0]->mobile;
				
				$res = $this->sendMsg($isd, $mobile, $otp, 'jma');
				if($res == 1){
					echo 1; exit;
				}else{	
					$isSend = json_decode($res,true);
					$status = $isSend['Status']; // Success or Error
					if($status == 'Error'){
						echo 2; exit;
					}else{
						$user = $this->session->set_userdata('userId',$condition[0]->id);
						$user = $this->session->set_userdata('passOTP',$otp);
						echo 3; exit;
					}
				}
			}else{
				echo 4; exit;
			}
		}
	}
	
	/******************************************** OTP Page ********************************************/
	function verifyotp(){
		$data['isHomePage'] = '0';
		$data['viewFile'] = "otp-verify";
		$data['module'] = "login";
		$data['scriptFile'] = "jma-login";
		$template = 'registration';
		echo Modules::run('template/'.$template, $data);
	}
	
	/******************************************** OTP verify Action ********************************************/
	function submitotp(){
		$content = $this->input->post();
		
		$this->form_validation->set_rules("otp","OTP","trim|required|xss_clean|exact_length[6]|regex_match[/^[0-9]+$/]",
		array(
			'required'      => 'Please enter %s.',
			'exact_length'  => 'Please enter 6 digit %s.',
			'regex_match'   => 'Entered %s must contains only numbers.'
		));
		
		if($this->form_validation->run() == FALSE){
			echo validation_errors();
		}else{
			$enteredOtp = $content['otp'];
			$id = $this->session->userdata('userId'); 
			$passOTP = $this->session->userdata('passOTP');
			if($enteredOtp == $passOTP){
				$this->session->unset_userdata('passOTP');
				echo 2; exit;
			}else{
				echo 1; exit;
			}
		}
	}
	
	/******************************************** Change Password ****************************************/
	function changePassword(){
		$data['isHomePage'] = '0';
		$data['viewFile'] = "change-password";
		$data['module'] = "login";
		$data['scriptFile'] = "jma-login";
		$template = 'registration';
		echo Modules::run('template/'.$template, $data);
	}
	
	/************************************** Change Password  Action ****************************************/
	function changePasswordAction(){
		$content = $this->input->post();
		
		$this->form_validation->set_rules("pass","New Password","trim|required|xss_clean|min_length[6]");
		$this->form_validation->set_rules("cnfPass","Confirm New Password","trim|required|xss_clean|matches[pass]");
		
		if($this->form_validation->run() == FALSE){
			echo validation_errors(); exit;
		}else{
			$id = $this->session->userdata('userId'); 
			$changePass = Modules::run('site_security/makeHash',$content['pass']);
			$data=array(
				'password' => $changePass, 
				'modifiedDate'=>date("Y-m-d H:i:s")
			);
			$this->Mdl_login->update('registration', $data, $id, 'id');
			
			$user = $this->Mdl_login->getUser($id);
			if($user[0]->isActive == '1' && $user[0]->statusCode == '6'){
				if($user[0]->type == 'mem'){
					$this->session->set_userdata('userId',$id);
					$this->session->set_userdata('type','mem');
					echo 2; exit;
				}else{
					$this->session->set_userdata('userId',$id);
					$this->session->set_userdata('type','doc');
					echo 3; exit;
				}
			}
			echo 1; exit;
		}
	}
	
	/******************************************** Send SMS ********************************************/
	function sendMsg($isd, $mob, $otp, $temp){
		$curl = curl_init();
		$url = 'http://2factor.in/API/V1/c703f17a-a27a-11e7-94da-0200cd936042/SMS/'.$isd.$mob.'/'.$otp.'/'.$temp;
		//$url = 'http://2factor.in/API/V1/c703f17a-a27a-11e7-94da-0200cd936042/SMS/'.$isd.$mob.'/'.$otp;
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $url,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "",
		  CURLOPT_HTTPHEADER => array(
			"content-type: application/x-www-form-urlencoded"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);

		if ($err) {
		  return 1; 
		} else {
		  return $response;
		}
	}
}