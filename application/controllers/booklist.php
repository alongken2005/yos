<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @deprecated 书本
 * @see Book
 * @version 1.0.0 (12-10-8 下午3:03)
 * @author ZhangHao
 */

class Booklist extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('base_mdl', 'base');
	}

	/**
	 * @deprecated 默认方法
	 */
	public function index() {
		echo "fdfd";
	}
}