<?php
/**
 * @deprecated 工具类
 * @see Tool
 * @version 1.0.0 (Thu Feb 23 13:49:18 GMT 2012)
 * @author ZhangHao
 */
 
class Index extends MY_Controller {
	private $_data;
	
    public function __construct() {
		parent::__construct();
		$this->load->model('base_mdl', 'base');
    }

	public function index() {
		$this->_data['cread'] = $this->base->get_data('book')->result_array();
		$this->load->view(THEME.'/index', $this->_data);
	}
}