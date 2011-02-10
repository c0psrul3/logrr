<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Devices extends CI_Controller {
	
	
	function __construct() {
		parent::__construct();
		date_default_timezone_set('UTC');
	}
	
    
	/**
	 *
	 */
	function index() {
		$this->load->view('devices_index', array(
            'devices' => $this->_getAllDevices()
        ));
	}
    
    /**
     *
     */
    function _getAllDevices() {
        
        $results = $this->db->query("
            SELECT 
                `FromHost`, 
                COUNT(*) as `count`
            FROM `SystemEvents`
            GROUP BY
                `FromHost`
            ORDER BY 
                `FromHost` ASC;
        ");
        
        return $results->result();
        
    }
	
}

/* End of file devices.php */
/* Location: ./application/controllers/devices.php */