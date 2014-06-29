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
		//echo "<embed src='".base_url('common/SplitWord.swf?bookId=11&chapterId=11'),"' quality=high width=100 height=100 wmode=transparent type='application/x-shockwave-flash'></embed>";
		require_once(APPPATH . '/libraries/facebook.php');
		$facebook = new Facebook(array(
		'appId'  => '480908935362028',
		'secret' => 'f42bcc6c8f27b7c2cc8231d71481e300',
		'cookie' => true,
		));

		$params = array(
		  'scope' => 'read_stream, friends_likes',
		  'redirect_uri' => site_url('test/callback'),
		);

		$loginUrl = $facebook->getLoginUrl($params);
		debug($loginUrl);	


		$this->load->view(THEME.'/test', array('facebook'=>$facebook));
	}

	public function callback() {
		require_once(APPPATH . '/libraries/facebook.php');
		$facebook = new Facebook(array(
		'appId'  => '480908935362028',
		'secret' => 'f42bcc6c8f27b7c2cc8231d71481e300',
		'cookie' => true,
		));		
		$signed_request = $facebook->getSignedRequest();
		debug($signed_request);
	}
}