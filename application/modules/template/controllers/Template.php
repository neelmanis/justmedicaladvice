<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template extends MX_Controller {

	function home($data){
		$this->load->view('home',$data);
	}
	
	function admin($data){	
		if(Modules::run('site_security/isAdmin')){
			$this->load->view('admin', $data);
		}else{
			redirect('admin/login','refresh');
		}
	}
	
	function doctor($data){
		if(Modules::run('site_security/isDoctor')){
			$this->load->view('doctor', $data);
		}else{
			redirect('login','refresh');
		}
	}
	
	function member($data){
		if(Modules::run('site_security/isMember')){
			$this->load->view('member', $data);
		}else{
			redirect('login','refresh');
		}
	}
	
	function registration($data){
		if(Modules::run('site_security/isLoggedIn')){
			$this->load->view('registration', $data);
		}
		else{
			redirect('login','refresh');
		}
	}
	
	function singlepage($data){
		$this->load->view('singlepage', $data);
	}
}