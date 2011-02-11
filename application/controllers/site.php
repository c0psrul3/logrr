<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends CI_Controller {
	
	
	private $priorities = array(
		'0' => 'Emergency',
		'1' => 'Alert',
		'2' => 'Critical',
		'3' => 'Error',
		'4' => 'Warning',
		'5' => 'Notice',
		'6' => 'Informational',
		'7' => 'Debug'
	);
	
	
	function __construct() {
		parent::__construct();
		date_default_timezone_set('UTC');
	}
	
	/**
	 *
	 */
	function index() {
		$this->load->view('site_index', array(
			'toolbaritems' => $this->priorities
		));
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */