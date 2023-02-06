<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Registration extends MX_Controller{
	
	function __construct() {
		parent::__construct();
		$this->load->model('Mdl_reg');
		$this->load->model('category/Mdl_category');
		$this->load->model('location/Mdl_location');
	}
	
	/******************************************** Signup Page ********************************************/
	function signup(){
		$data['isHomePage'] = '0';	
		$data['isd'] = $this->Mdl_location->getMobileCodes();
		$data['viewFile'] = "signup";
		$data['module'] = "registration";
		$data['scriptFile'] = "jma-signup";
		$template = 'home';
		echo Modules::run('template/'.$template, $data);
	}
	
	/******************************************** Signup Action ********************************************/
	function submitSignup(){
		$content = $this->input->post();
		
		$this->form_validation->set_rules("name","Name","trim|required|xss_clean|regex_match[/^[A-Z a-z]+$/]",
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
	  
		if($this->form_validation->run() == FALSE){
			echo validation_errors();
		}else{	   
			$otp = rand(100000,999999);
			$isd = $content['isd'];
			$mobile = $content['mobile'];
			$temp = 'jma';
			
			if($content['Doc_YN'] == 'doc'){
				$name = 'Dr. '.$content['name'];
			}else{
				$name = $content['name'];
			}
			
			$res = $this->sendMsg($isd, $mobile, $otp, $temp);
			
			if($res == 1){
				echo 1; exit;
			}else{	
				$isSend = json_decode($res,true);
				$status = $isSend['Status']; // Success or Error
				if($status == 'Error'){
					echo 2; exit;
				}else{
					$enddate=strtotime("+30 minutes");
			
					$data=array(
						'type'=>$content['Doc_YN'],
						'name'=>$name,
						'isd' => $isd,
						'mobile' => $mobile,
						'otp' => $otp,
						'counts' => '1',
						'expireAt' => date("Y-m-d H:i:s", $enddate),
						'password'=>'',
						'mobileVerify'=>'0',
						'statusCode'=>'1',
						'isActive'=>'0',
						'adminId' => '0',
						'ipAddress'=> $_SERVER['REMOTE_ADDR'],
						'createdDate'=>date("Y-m-d H:i:s"),
						'modifiedDate'=>date("Y-m-d H:i:s")
					);
			
					$id = $this->Mdl_reg->insert('registration', $data);		
					$user = $this->session->set_userdata('userId',$id);
					$user = $this->session->set_userdata('type',$content['Doc_YN']);
					echo 3; exit;
				}
			}
		}
    }
  
	/******************************************** OTP Page ********************************************/
	function verifyotp(){
		$data['isHomePage'] = '0';
		$data['viewFile'] = "otp-verify";
		$data['module'] = "registration";
		$data['scriptFile'] = "jma-otp";
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
			$data = $this->Mdl_reg->getUserById("registration",$id);
			
			$count = $data[0]->counts;
			$expire = $data[0]->expireAt;
			$isd = $data[0]->isd;
			$mobile = $data[0]->mobile;
			$expireDate = strtotime("+60 minutes");
			
			/* 1. Check the Attempts. */
			if($count > 3){
				/* 2. If Attempts > 3 , Block User for 60 minutes. */
				$updatedata=array(
					'counts' => '1',
					'statusCode' => '2',
					'expireAt' => date("Y-m-d H:i:s",$expireDate),
					'modifiedDate'=>date("Y-m-d H:i:s")	
				);
				$this->Mdl_reg->update("registration",$updatedata,$id,"id");
				echo 1; exit;
			}else{
				/* 3. Else check the expiration time. */
				$now = date('Y-m-d H:i:s',strtotime("now"));
				$end = date('Y-m-d H:i:s',strtotime($expire));
				if($now > $end){
					/* 4. If Expire then resend the OTP. */
					$otp = rand(100000,999999);				
					$res = $this->sendMsg($isd, $mobile, $otp);
					if($res == 1){
						echo 2; exit;
					}else{	
						$isSend = json_decode($res,true);
						$status = $isSend['Status']; // Success or Error
						if($status == 'Error'){
							echo 3; exit;
						}else{
							$updatedata=array(
								'otp' => $otp,
								'counts' => ++$count,
								'expireAt' => date("Y-m-d H:i:s",strtotime("+30 minutes")),
								'modifiedDate'=>date("Y-m-d H:i:s")	
							);
							$this->Mdl_reg->update("registration",$updatedata,$id,"id");
							echo 4; exit;
						}
					}
				}else{
					/* 5. Else check the OTP entered. */
					if($data[0]->otp !== $enteredOtp){
						/* 6. If OTP does not match with entered OTP, Increase the count, resend the OTP. */
						$updatedata=array(
							'counts' => ++$count,
							'modifiedDate'=>date("Y-m-d H:i:s")	
						);
						$this->Mdl_reg->update("registration",$updatedata,$id,"id");
						echo "You have Entered Wrong OTP."; exit;
					}else{
						/* 7. Else OTP Matches then continue with next step. */	
							
						/*$temp = 'jma registration';
						$password = $this->generateRandomString();
						$pass = Modules::run('site_security/makeHash',$password);
						$this->sendMsg($isd, $mobile, $password, $temp);*/
						
						if($data[0]->type === 'mem'){
							$updatedata=array(
								'mobileVerify'=>'1',
								'statusCode'=> '3',
								'isActive'=>'1',
								'modifiedDate'=>date("Y-m-d H:i:s")	
							);
							//$res = true;
						}else{
							$updatedata=array(
								'mobileVerify'=>'1',
								'statusCode'=> '3',
								'modifiedDate'=>date("Y-m-d H:i:s")	
							);	
							//$res=FALSE;
						}
						$this->Mdl_reg->update("registration",$updatedata,$id,"id");
						
						/*if($res){
							echo 5; exit;
						}else{
							echo 6; exit;
						}*/
						
						echo 5; exit;
					}
				}
			}
		}
	}
	
	/******************************************** Set Password Page ********************************************/
	function setPassword(){
		$data['isHomePage'] = '0';
		$data['viewFile'] = "set-password";
		$data['module'] = "registration";
		$data['scriptFile'] = "jma-signup";
		$template = 'registration';
		echo Modules::run('template/'.$template, $data);
	}
	
	/******************************************** Set Passowrd Action ********************************************/
	function setPasswordAction(){
		$content = $this->input->post();
		
		$this->form_validation->set_rules("pass","Password","trim|required|xss_clean|min_length[6]",
		array(
			'required'      => '<b>Please enter %s.</b>',
			'min_length'   => '<b>Password should be 6 characters long.</b>'
		));
		
		$this->form_validation->set_rules("cnfpass","Confirm Password","trim|required|xss_clean|matches[pass]",
		array(
			'required'=>'<b>Please re-type your password</b>',
			'matches'=>'<b>Password do not matched</b>'
		));
	  
		if($this->form_validation->run() == FALSE){
			echo validation_errors();
		}else{	   
			$password =  Modules::run('site_security/makeHash',$content['pass']);
			$id = $this->session->userdata('userId'); 
			$type = $this->session->userdata('type'); 

			$data=array(
				'password'=>$password,
				'statusCode'=>'4',
				'modifiedDate'=>date("Y-m-d H:i:s")
			);
			
			$this->Mdl_reg->update("registration",$data,$id,"id");
			
			if($type == 'mem'){
				echo 1; exit;
			}else{
				echo 2; exit;
			}
		}
	}

	/******************************************** Resend OTP Page ********************************************/
	function resendOtp(){
		$id = $this->session->userdata('userId'); 
		if(!empty($id)){
			$uData = $this->Mdl_reg->getUserById("registration",$id);
			$otp = rand(100000,999999);
			$isd = $uData[0]->isd;
			$mobile = $uData[0]->mobile;
			$res = $this->sendMsg($isd, $mobile, $otp);
			
			if($res == 1){
				echo 1; exit;
			}else{	
				$isSend = json_decode($res,true);
				$status = $isSend['Status']; // Success or Error
				if($status == 'Error'){
					echo 2; exit;
				}else{
					$data=array(
						'otp' => $otp,
						'expireAt' => date("Y-m-d H:i:s",strtotime("+30 minutes")),
						'modifiedDate'=>date("Y-m-d H:i:s")	
					);
			
					$this->Mdl_reg->update("registration",$data,$id,"id");
					echo 3; exit;
				}
			}
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
