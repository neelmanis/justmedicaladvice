<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Subscription extends MX_Controller
{
	/*------------------------------ CONSTRUCTOR FUNCTION---------------------------------------*/	
	function __construct(){
		parent::__construct();
		$this->load->model('Mdl_subscription');	
	}
	
	/*------------------------------ VIEW ALL SUBSCRIPTION FUNCTION -----------------------------------*/
	function index(){
		$data['sublist'] = $this->Mdl_subscription->mainSubscritpion();
		$data['viewFile'] = "view";
		$data['page'] = "subscription";
		$template = "admin";
		echo Modules::run('template/'.$template, $data);
	}

/*--------------------------------DELETE SUBSCRIPTION FUNCTION------*/
			
	function deleteSubscription(){
		$entryid = $_POST['subscriptionid'];
		$delete = $this->Mdl_subscription->deleteSubscription($entryid);
		echo $delete;                                   
	}	
}

