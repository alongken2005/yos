<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * API
 * @version 1.0.0 12-10-22 下午9:39
 * @author 张浩
 */

class Apitest extends CI_Controller {

	private $_data = array();

	public function __construct() {
		parent::__construct();
		$this->load->model('base_mdl', 'base');
	}

	/**
	 * 默认方法
	 */
	public function index() {
		$this->load->view('api/apitest', $this->_data);
	}
}