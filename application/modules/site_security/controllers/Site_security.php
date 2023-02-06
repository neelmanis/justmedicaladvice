<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
class Site_security extends MX_Controller
{
	function __construct() {
		parent::__construct();
	}
	
	function isAdmin(){
		if($this->session->userdata('userId') && $this->session->userdata('type') == 'admin'){
			$type = $this->session->userdata('userId');
			/*if(is_numeric($type)){
				echo 'false';
			}else{
				echo 'true';
			}
			exit;*/
			if(is_numeric($type)){
				return TRUE;
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}

	function isMember(){
		if($this->session->userdata('userId') && $this->session->userdata('type') == 'mem'){
			$type = $this->session->userdata('userId');
			if(is_numeric($type)){
				return TRUE;
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}
	
	function isDoctor(){
		if($this->session->userdata('userId') && $this->session->userdata('type') == 'doc'){
			$type = $this->session->userdata('userId');
			if(is_numeric($type)){
				return TRUE;
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}
	
	function isLoggedIn(){
		if(!$this->session->userdata('userId')){
			return FALSE;
		}
		else{
			$uId = $this->session->userdata('userId');
			if(!is_numeric($uId))
				return FALSE;
			else
				return TRUE;
		}
	}
	
	function makeHash($password){
		$hashPassword = sha1($password);
		return $hashPassword;
	}
}