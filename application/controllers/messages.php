<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Messages extends CI_Controller {
        
        
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
            
            $config['base_url'] = '/#/messages/priority/'.$priority.'/';
            $config['total_rows'] = $this->_getLogCountByPriority($priority);
            $config['per_page'] = 50;
            $this->pagination->initialize($config);
            
            $this->load->view('messages_index', array(
                'logs' => $results,
                'priorities' => $this->priorities,
                'toolbaritems' => $this->priorities,
                'priority' => $priority,
                'offset' => $offset,
                'title' => (($priority != '-1') ? 'Recent Syslog Messages for Priority:'.$this->priorities[$priority] : 'All Recent Syslog Messages'),
                'graph' => '/graph/chart/priority/'.$priority.'/EST'
            ));
            
        }
        
        /**
         *
         */
        function host( $host = '', $offset = 0 ) {
            
            $this->load->library('pagination');
            
            $results = $this->_getLogsByHost($host, $offset);
            
            $config['base_url'] = '/#/messages/host/'.$host.'/';
            $config['total_rows'] = $this->_getLogCountByHost($host);
            $config['per_page'] = 50;
            $this->pagination->initialize($config);
            
            $this->load->view('messages_index', array(
                'logs' => $results,
                'priorities' => $this->priorities,
                'toolbaritems' => $this->priorities,
                'host' => $host,
                'offset' => $offset,
                'title' => 'Recent Syslog Messages for Host:'.$host,
                'graph' => '/graph/chart/host/'.$host.'/EST'
            ));
            
        }
        
        /**
         *
         */
        function input( $input = '', $offset = 0 ) {
            
            $this->load->library('pagination');
            
            $results = $this->_getLogsByInput($input, $offset);
            
            $config['base_url'] = '/#/messages/input/'.$input.'/';
            $config['total_rows'] = $this->_getLogCountByInput($input);
            $config['per_page'] = 50;
            $this->pagination->initialize($config);
            
            $this->load->view('messages_index', array(
                'logs' => $results,
                'priorities' => $this->priorities,
                'toolbaritems' => $this->priorities,
                'input' => $input,
                'offset' => $offset,
                'title' => 'Recent Syslog Messages for Input:'.$input,
                'graph' => '/graph/chart/input/'.$input.'/EST'
            ));
            
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
        function _getLogsByInput( $input = '', $offset = 0 ) {
            
            $this->db->select('*');
            $this->db->from('SystemEvents');
            if (!empty($input)) $this->db->like('SysLogTag', $input, 'after');
            $this->db->order_by('ReceivedAt', 'DESC');
            $this->db->limit(50, $offset);
            $results = $this->db->get();
            
            return $results->result();
            
        }
        
        /**
         *
         */
        function _getLogCountByInput( $input = '' ) {
            
            $this->db->select('*');
            $this->db->from('SystemEvents');
            if (!empty($input)) $this->db->like('SysLogTag', $input, 'after');
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
        
    }

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */