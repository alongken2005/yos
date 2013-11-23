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

		//Continue Reading
		$this->_data['cread'] = $this->base->get_data('book')->result_array();

		//popular on YouShelf
		$this->_data['popular'] = $this->base->get_data('book', array(), '*', 0, 10, 'hits DESC')->result_array();


		$this->load->view(THEME.'/header');
		$this->load->view(THEME.'/index', $this->_data);
		$this->load->view(THEME.'/footer');
	}
}