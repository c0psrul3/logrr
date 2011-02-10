<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inputs extends CI_Controller {
	
	
	function __construct() {
		parent::__construct();
		date_default_timezone_set('UTC');
	}
	
    
	/**
	 *
	 */
	function index() {
		$this->load->view('inputs_index');
	}
	
}

/* End of file inputs.php */
/* Location: ./application/controllers/inputs.php */