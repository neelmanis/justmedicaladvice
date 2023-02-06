<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Notifications extends MX_Controller
{
	/*------------------------------ CONSTRUCTOR FUNCTION---------------------------------------*/	
	function __construct() {
		parent::__construct();
		$this->load->model('Mdl_notifications');	
	}
	
	function member($id){
		$notification = $this->Mdl_notifications->getMemberNotification($id);
		print_r($notification);
	}
}