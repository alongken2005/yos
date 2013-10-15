<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 支付
 * @version 1.0.0 12-10-22 下午9:31
 * @author 张浩
 */
class Test extends CI_Controller {

	private $timestamp;
	private $uid;

	public function __construct() {
		parent::__construct();
		$this->load->model('base_mdl', 'base');
		$this->timestamp = time();
		$this->uid = $this->session->userdata('uid');
	}

	public function index() {
		echo date('Z').'<br>';
		echo date('e').'<br>';
		echo date('T').'<br>';
		$this->load->view(THEME.'/paytest');
	}
}