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
		$this->priority();
	}

	/**
	 *
	 */
	function priority( $priority = -1, $offset = 0 ) {
		
		$this->load->library('pagination');
		
		$results = $this->_getLogsByPriority($priority, $offset);
		
		$config['base_url'] = '/#/priority/'.$priority.'/';
		$config['total_rows'] = $this->_getLogCountByPriority($priority);
		$config['per_page'] = 50;
		$this->pagination->initialize($config);
		
		$this->load->view('site_index', array(
			'logs' => $results,
			'priorities' => $this->priorities,
			'priority' => $priority,
			'offset' => $offset
		));
		
	}
	
	/**
	 *
	 */
	function host( $host = '', $offset = 0 ) {
		
		$this->load->library('pagination');
		
		$results = $this->_getLogsByHost($host, $offset);
		
		$config['base_url'] = '/#/host/'.$host.'/';
		$config['total_rows'] = $this->_getLogCountByHost($host);
		$config['per_page'] = 50;
		$this->pagination->initialize($config);
		
		$this->load->view('site_index', array(
			'logs' => $results,
			'priorities' => $this->priorities,
			'host' => $host,
			'offset' => $offset
		));
		
	}
	
	/**
	 *
	 */
	function graph($extra = 'base', $period = 'day') {
		
		require_once APPPATH.'third_party/pData.php';
		require_once APPPATH.'third_party/pChart.php';
		
		$this->_drawGraph($this->_getDayStats('-1 day'), 'Log Messages Received per Hour');
		
	}
	
	/**
	 *
	 */
	function _getLogsByPriority( $priority = -1, $offset = 0 ) {
		
		$this->db->select('*');
		$this->db->from('SystemEvents');
		if ($priority >= 0) $this->db->where('Priority', $priority);
		$this->db->order_by('ReceivedAt', 'DESC');
		$this->db->limit(50, $offset);
		$results = $this->db->get();
		
		return $results->result();
		
	}
	
	/**
	 *
	 */
	function _getLogCountByPriority( $priority = -1 ) {
		
		$this->db->select('*');
		$this->db->from('SystemEvents');
		if ($priority >= 0) $this->db->where('Priority', $priority);
		return $this->db->count_all_results();
		
	}
	
	/**
	 *
	 */
	function _getLogsByHost( $host = '', $offset = 0 ) {
		
		$this->db->select('*');
		$this->db->from('SystemEvents');
		if (!empty($host)) $this->db->where('FromHost', $host);
		$this->db->order_by('ReceivedAt', 'DESC');
		$this->db->limit(50, $offset);
		$results = $this->db->get();
		
		return $results->result();
		
	}
	
	/**
	 *
	 */
	function _getLogCountByHost( $host = '' ) {
		
		$this->db->select('*');
		$this->db->from('SystemEvents');
		if (!empty($host) >= 0) $this->db->where('FromHost', $host);
		return $this->db->count_all_results();
		
	}
	
	/**
	 *
	 */
	function _drawGraph($stats, $title, $points = true, $skip = 0) {
		
		// Build the Dataset
		//
		$DataSet = new pData();
		foreach ($stats as $key => $value) {
			$DataSet->AddPoint($value['count'], 'Serie1');  
			$DataSet->AddPoint($key, 'Serie2');
		}
		$DataSet->AddSerie("Serie1");  
		$DataSet->SetSerieName("Total Messages", "Serie1");
		$DataSet->SetAbsciseLabelSerie("Serie2");
		
		// Setup the Graph
		//
		$Test = new pChart(1100,200);  
		$Test->setFontProperties(APPPATH."fonts/tahoma.ttf", 7);
		$Test->setGraphArea(50,30,1050,175);  
		$Test->drawGraphArea(252,252,252);
		
		$Test->setColorPalette(0, 206,18,18,0);
		
		// Draw the 1st Graph
		$DataSet->SetYAxisName("Log Messages");
		$Test->drawScale($DataSet->GetData(), $DataSet->GetDataDescription(), SCALE_START0, 150, 150, 150, true, 0, 0, true);  
		$Test->drawGrid(4,TRUE,240,240,240,50);  
		//$Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());  
		//if ($points) $Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),3,2,255,255,255);
		if ($points) $Test->drawBarGraph($DataSet->GetData(), $DataSet->GetDataDescription(), true);
		
		$Test->clearScale();
		
		// Finish the graph
		//
		$Test->drawLegend(40,260,$DataSet->GetDataDescription(),255,255,255,-1,-1,-1,0,0,0,false);  
		$Test->drawTitle(55,22,$title,50,50,50);  
		
		// Output Graph
		//
		$Test->Stroke();
		
	}
	
	/**
	 *
	 */
	function _getDayStats($timeframe, $timezone = 'EST') {
		
		$sql = "
			SELECT 
				`ReceivedAt`, 
				COUNT(*) as `count`
			FROM `SystemEvents`
			WHERE 
				`ReceivedAt` >= ?
			GROUP BY
				YEAR(`ReceivedAt`), MONTH(`ReceivedAt`), DAY(`ReceivedAt`), HOUR(`ReceivedAt`)
			ORDER BY 
				`ReceivedAt` DESC;
		";
		
		$CI = get_instance();
		$result = $CI->db->query($sql, array(
			date('Y-m-d', strtotime($timeframe))
		));
		
		// Populate an array with empty values
		//
		$base_dates = array();
		$current_date = strtotime($timeframe);
		$system_timezone = new DateTimeZone('UTC');
		$user_timezone = new DateTimeZone($timezone);
		while ($current_date < now()) {
			$date = new DateTime(date('Y-m-d H:'.$this->_roundToDecima($current_date).':00', $current_date), $system_timezone);
			$offset = $user_timezone->getOffset($date);
			$base_dates[date('H:00:00', $date->format('U') + $offset)] = array( 'count' => 0 );
			$current_date = strtotime('+1 hour', $current_date);
		}
		
		$return = array();
		$system_timezone = new DateTimeZone('UTC');
		$user_timezone = new DateTimeZone($timezone);
		foreach ($result->result() as $item) {
			$date = new DateTime(date('Y-m-d H:i:s',mysql_to_unix($item->ReceivedAt)), $system_timezone);
			$offset = $user_timezone->getOffset($date);
			$base_dates[date('H:00:00', $date->format('U') + $offset)] = array(
				'count' => $item->count
			);
		}
		
		return array_reverse($base_dates, true);
		
	}
	
	function _roundToDecima($time) {
		$minutes = date('i', $time);
		return str_pad($minutes - ($minutes % 10), 2, '0');
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */