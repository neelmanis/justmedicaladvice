<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mdl_subscription extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	
	/*------------------------------ GET ALL SUBSCRIPTION FUNCTION ------------------------------------*/
	function mainSubscritpion(){
		 $subscriptionView =$this->db->get_where("subscription",array('substatus' =>'1'));
		if($subscriptionView->num_rows() > 0)
			$subscriptions = $subscriptionView->result();
		else
			$subscriptions = "no";
		return $subscriptions;
	}
	
	/*------------------------------ GET ALL CATEGORY FUNCTION ------------------------------------*/
	function deleteSubscription($entryid){
		$this->db->where('id',$entryid);		
		$this->db->delete("subscription");
		return 1;
	}
	
}