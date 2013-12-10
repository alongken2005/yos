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

		$myMenu = array();
		if($this->member) {
			$myMenu = array('Continue Reading', 'My List', 'Top Picks');
		}
		$menu_list = array('Popular', 'Rated', 'Romance');
		$this->_data['menu_list'] = array_merge($myMenu, $menu_list);
		//$this->_data['menu_list'] = $menu_list;
		//debug($this->_data['menu_list']);

		//Continue Reading
		$this->_data['cread'] = $this->base->get_data('book')->result_array();
		if($this->member && $this->member['uid']) {
			$this->_data['mylist'] = $this->base->get_data('book', array('uid'=>$this->member['uid']), '*', 0, 10, 'mtime DESC')->result_array();
		}
		//popular on YouShelf
		$this->_data['popular'] = $this->base->get_data('book', array(), '*', 0, 10, 'hits DESC')->result_array();


		$this->load->view(THEME.'/header', $this->_data);
		$this->load->view(THEME.'/index', $this->_data);
		$this->load->view(THEME.'/footer');
	}

	public function score() {
		echo "fdfd";
	}
}