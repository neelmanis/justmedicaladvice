<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Errors extends MX_Controller{
	
	function __construct() {
		parent::__construct();
	}
	
	public function index(){
		$data['viewFile'] = '404_page';
		$data['module'] = 'errors';
		echo Modules::run('template/singlepage', $data);
	}
}