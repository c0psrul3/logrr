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
		$this->load->view('inputs_index', array(
            'inputs' => $this->_getAllInputs()
        ));
	}
    
    /**
     *
     */
    function _getAllInputs() {
        
        $results = $this->db->query("
            SELECT 
                `Tag`, 
                COUNT(*) as `count`
            FROM `SystemEvents`
            GROUP BY
                `Tag`
            ORDER BY 
                `Tag` ASC;
        ");
        
        return $results->result();
        
    }
	
}

/* End of file inputs.php */
/* Location: ./application/controllers/inputs.php */