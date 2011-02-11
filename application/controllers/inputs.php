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
                LEFT(SysLogTag, LOCATE('[',SysLogTag) - 1) as `SysLogTag`, 
                LEFT(SysLogTag, LOCATE('[',SysLogTag) - 1) as `SysLogTag1`, 
                COUNT(*) as `count`
            FROM `SystemEvents`
			WHERE `SysLogTag` != ''
            GROUP BY
                `SysLogTag1`
            ORDER BY 
                `SysLogTag1` ASC;
        ");
        
        return $results->result();
        
    }
	
}

/* End of file inputs.php */
/* Location: ./application/controllers/inputs.php */