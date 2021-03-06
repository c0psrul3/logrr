<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Graph extends CI_Controller {
        
        
        private $priorities = array(
            '-1' => 'All',
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
        function chart($type = 'all', $extra = 'all', $period = 'day', $timezone = 'EST') {
            
            require_once APPPATH.'third_party/pData.php';
            require_once APPPATH.'third_party/pChart.php';
            
            if ($type == 'all' || $extra == '-1') {
                $this->_drawGraph($this->_getStats('-24 hours', $timezone), sprintf('Log Messages Received per Hour'));
            } elseif ($type == 'priority') {
                $this->_drawGraph($this->_getStatsByPriority($extra, '-24 hours', $timezone), sprintf('Log Messages Received per Hour for Priority:%s', $this->priorities[$extra]));
            } elseif ($type == 'host') {
                $this->_drawGraph($this->_getStatsByHost($extra, '-24 hours', $timezone), sprintf('Log Messages Received per Hour for Host:%s', $extra));
            } elseif ($type == 'input') {
                $this->_drawGraph($this->_getStatsByInput($extra, '-24 hours', $timezone), sprintf('Log Messages Received per Hour for Input:%s', $extra));
            }
            
        }
        
        /**
         *
         */
        function sparkline($extra = 'base', $period = 'day', $timezone = 'EST') {
            
            require_once APPPATH.'third_party/pData.php';
            require_once APPPATH.'third_party/pChart.php';
            
            if ($extra == '-1') {
                $this->_drawSparkline($this->_getStats('-24 hours', $timezone), sprintf('Log Messages Received per Hour'));
            } elseif (is_numeric($extra)) {
                $this->_drawSparkline($this->_getStatsByPriority($extra, '-24 hours', $timezone), sprintf('Log Messages Received per Hour for Priority:%s', $this->priorities[$extra]));
            } else {
                $this->_drawSparkline($this->_getStatsByHost($extra, '-24 hours', $timezone), sprintf('Log Messages Received per Hour for Host:%s', $extra));
            }
            
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
        function _drawSparkline($stats, $title, $points = true, $skip = 0) {
            
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
            $Test = new pChart(400,25);  
            $Test->setFontProperties(APPPATH."fonts/tahoma.ttf", 7);
            $Test->setGraphArea(0,0,400,21);  
            //$Test->drawGraphArea(255,255,255);
            
            $Test->setColorPalette(0, 206,18,18,0);
            
            // Draw the 1st Graph
            $DataSet->SetYAxisName("Log Messages");
            $Test->drawScale($DataSet->GetData(), $DataSet->GetDataDescription(), SCALE_START0, 255, 255, 255, false, 0, 0, false);  
            $Test->drawGrid(4,TRUE,255,255,255,100);  
            $Test->drawFilledLineGraph($DataSet->GetData(),$DataSet->GetDataDescription(),25);
            $Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());  
            //if ($points) $Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),1,1,206,18,18);
            //if ($points) $Test->drawBarGraph($DataSet->GetData(), $DataSet->GetDataDescription(), true);
            
            $Test->clearScale();
            
            // Finish the graph
            //
            //$Test->drawLegend(40,260,$DataSet->GetDataDescription(),255,255,255,-1,-1,-1,0,0,0,false);  
            //$Test->drawTitle(55,22,$title,50,50,50);  
            
            // Output Graph
            //
            $Test->Stroke();
            
        }
        
        /**
         *
         */
        function _getStats($timeframe, $timezone = 'EST') {
            return $this->_getStatsFormatted(1, '', $timeframe, $timezone);
        }
        
        /**
         *
         */
        function _getStatsByPriority($priority, $timeframe, $timezone = 'EST') {
            return $this->_getStatsFormatted(2, $priority, $timeframe, $timezone);
        }
        
        /**
         *
         */
        function _getStatsByHost($host, $timeframe, $timezone = 'EST') {
            return $this->_getStatsFormatted(3, $host, $timeframe, $timezone);
        }
        
        /**
         *
         */
        function _getStatsByInput($input, $timeframe, $timezone = 'EST') {
            return $this->_getStatsFormatted(4, $input, $timeframe, $timezone);
        }
        
        /**
         *
         */
        function _getStatsFormatted($type, $extra, $timeframe, $timezone = 'EST') {
            
            $system_timezone = new DateTimeZone('UTC');
            $user_timezone = new DateTimeZone($timezone);
            $next_date = strtotime($timeframe);
            
            $CI = get_instance();
            
            switch ($type) {
                case 1:
                    $result = $CI->db->query("
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
                    ", array(
                        date('Y-m-d', $next_date)
                    ));
                    break;
                case 2:
                    $result = $CI->db->query("
                        SELECT 
                            `ReceivedAt`, 
                            COUNT(*) as `count`
                        FROM `SystemEvents`
                        WHERE 
                            `ReceivedAt` >= ? AND
                            `Priority` = ?
                        GROUP BY
                            YEAR(`ReceivedAt`), MONTH(`ReceivedAt`), DAY(`ReceivedAt`), HOUR(`ReceivedAt`)
                        ORDER BY 
                            `ReceivedAt` DESC;
                    ", array(
                        date('Y-m-d', $next_date),
                        $extra
                    ));
                    break;
                case 3:
                    $result = $CI->db->query("
                        SELECT 
                            `ReceivedAt`, 
                            COUNT(*) as `count`
                        FROM `SystemEvents`
                        WHERE 
                            `ReceivedAt` >= ? AND
                            `FromHost` = ?
                        GROUP BY
                            YEAR(`ReceivedAt`), MONTH(`ReceivedAt`), DAY(`ReceivedAt`), HOUR(`ReceivedAt`)
                        ORDER BY 
                            `ReceivedAt` DESC;
                    ", array(
                        date('Y-m-d', $next_date),
                        $extra
                    ));
                    break;
                case 4:
                    $result = $CI->db->query("
                        SELECT 
                            `ReceivedAt`, 
                            COUNT(*) as `count`
                        FROM `SystemEvents`
                        WHERE 
                            `ReceivedAt` >= ? AND
                            `SysLogTag` LIKE ?
                        GROUP BY
                            YEAR(`ReceivedAt`), MONTH(`ReceivedAt`), DAY(`ReceivedAt`), HOUR(`ReceivedAt`)
                        ORDER BY 
                            `ReceivedAt` DESC;
                    ", array(
                        date('Y-m-d', $next_date),
                        $extra.'%'
                    ));
                    break;
            }
            
            // Populate an array with empty values
            //
            $base_dates = array();
            while ($next_date < now()) {
                $next_date = strtotime('+1 hour', $next_date);
                $date = new DateTime(date('Y-m-d H:'.$this->_roundToDecima($next_date).':00', $next_date), $system_timezone);
                $offset = $user_timezone->getOffset($date);
                $base_dates[date('H:00:00', $date->format('U') + $offset)] = array( 'count' => 0 );
            }
            
            $return = array();
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

/* End of file graph.php */
/* Location: ./application/controllers/graph.php */