<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Location extends MX_Controller{
	
	function __construct() {
		parent::__construct();
		$this->load->model('Mdl_location');	
	}
	
	/***************************************** Countries Listing Page ***************************************/
	function countryList(){
		$getCountries = $this->Mdl_location->listCountries();
		$data['countries'] = $getCountries;
		$data['viewFile'] = "list-countries";
		$data['page'] = 'country';
		$template = 'admin';
		echo Modules::run('template/'.$template, $data);
	}
	
	/***************************************** Inactive Country Action ***************************************/
	function countryInactive($id){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$cid = base64_decode($base_64);
			$this->Mdl_location->statusInactive('countries',$cid,'id');
			redirect('location/countryList');		
		}
	}
	
	/***************************************** Active Country Action ***************************************/
	function countryActive($id){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$cid = base64_decode($base_64);
			$this->Mdl_location->statusActive('countries',$cid,'id');
			redirect('location/countryList');	
		}
	}
	
	/***************************************** State Listing Page ***************************************/
	function stateList(){
		$getStates = $this->Mdl_location->listStates();
		$data['states'] = $getStates;
		$data['viewFile'] = "list-states";
		$data['page'] = 'state';
		$template = 'admin';
		echo Modules::run('template/'.$template, $data);
	}
	
	/***************************************** Inactive State Action ***************************************/
	function stateInactive($id){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$cid = base64_decode($base_64);
			$this->Mdl_location->statusInactive('states',$cid,'id');
			redirect('location/stateList');		
		}
	}
	
	/***************************************** Active State Action ***************************************/
	function stateActive($id){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$cid = base64_decode($base_64);
			$this->Mdl_location->statusActive('states',$cid,'id');
			redirect('location/stateList');	
		}
	}
	
	/***************************************** City Listing Page ***************************************/
	
	function listByCountry($id){
		$getCity = $this->Mdl_location->listCity($id);
		$data['main'] = $this->Mdl_location->listCountries();
		$data['cities'] = $getCity;
		$data['viewFile'] = "list-cities";
		$data['page'] = 'city';
		$data['conId'] = $id;
		$template = 'admin';
		echo Modules::run('template/'.$template, $data);
	}
	
	/***************************************** Inactive State Action ***************************************/
	function cityInactive($id,$code){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$cid = base64_decode($base_64);
			$this->Mdl_location->statusInactive('cities',$cid,'id');
			redirect('location/city-list/'.$code);		
		}
	}
	
	/***************************************** Active State Action ***************************************/
	function cityActive($id,$code){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$cid = base64_decode($base_64);
			$this->Mdl_location->statusActive('cities',$cid,'id');
			redirect('location/city-list/'.$code);	
		}
	}
	
	function getCities(){
		$content = $this->input->post();
		
		$records = $this->Mdl_location->getCities($content['isd']);
		if($records !== 'no'){
			$msg = '';
			foreach($records as $val){
				$msg .= '<option value="'.$val->cityName.','.$val->stateName.','.$val->countryName.'">'.$val->cityName.'('.$val->countryName.')</option>';
			}
			echo $msg;
		}else{
			echo "Data Not Available";
		}
	}
}