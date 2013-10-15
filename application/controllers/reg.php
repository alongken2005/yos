<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @deprecated 用户注册
 * @version 1.0.0 12-10-22 下午9:31
 * @author 张浩
 */

class Reg extends CI_Controller {
	private $_data;

	public function __construct() {
		parent::__construct();
		$this->load->model('base_mdl', 'base');
	}

	/**
	 * @deprecated 默认方法
	 */
	public function index() {
		$this->load->view(THEME.'/reg', $this->_data);
	}

	public function redirect() {
		$redirect = get_cookie('redirect') ? get_cookie('redirect') : base_url('movie');
		delete_cookie('redirect');
		redirect($redirect);
	}
}