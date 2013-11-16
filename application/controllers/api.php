<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @deprecated API
 * @version 1.0.0 12-10-22 下午9:39
 * @author 张浩
 */

class Api extends CI_Controller {

	private $_data = array();

	public function __construct() {
		parent::__construct();
		$this->load->model('base_mdl', 'base');
	}

	/**
	 * 默认方法
	 */
	public function index() {
		$this->login();
	}

	/**
	 * 登录
	 */
	public function login() {
		if(!defined("SESS_LOGGED")) define ('SESS_LOGGED', $this->session->userdata('logged'));
		if(!defined("SESS_ACCOUNT")) define ('SESS_ACCOUNT', $this->session->userdata('uid'));
		$this->_data['slider'] = $this->load->view('api/slider', array('active'=>__FUNCTION__), TRUE);
		$this->load->view('api/login', $this->_data);
	}

	/**
	 * 注册
	 */
	public function register() {
		$this->_data['slider'] = $this->load->view('api/slider', array('active'=>__FUNCTION__), TRUE);
		$this->load->view('api/register', $this->_data);
	}

	/**
	 * 资源下载
	 */
	public function down() {
		$this->_data['slider'] = $this->load->view('api/slider', array('active'=>__FUNCTION__), TRUE);
		$this->load->view('api/down', $this->_data);
	}

	/**
	 * 支付
	 */
	public function pay() {
		$this->_data['slider'] = $this->load->view('api/slider', array('active'=>__FUNCTION__), TRUE);
		$this->_data['info1'] = array(
			'done'	=> array(
				'method'	=> 'POST',
				'url'		=> site_url('pay/figureUp'),
				'params'	=> array(
					array(
						'name'	=> 'pids',
						'type'	=> 'String',
						'value'	=> '',
						'desc'	=> 'id之间用,隔开，例如：1,45,33',
					),
				),
			),
			'return'=> array(
				array(
					'state'	=> '',
					'msg'	=> ''
				),
			)
		);

		$this->_data['info2'] = array(
			'done'	=> array(
				'method'	=> 'POST',
				'url'		=> site_url('pay/billover'),
				'params'	=> array(
					array(
						'name'	=> 'option',
						'type'	=> 'Int',
						'value'	=> '',
						'desc'	=> '支付方式id',
					),
					array(
						'name'	=> 'invoid_id',
						'type'	=> 'Int',
						'value'	=> '',
						'desc'	=> '订单号',
					),
					array(
						'name'	=> 'receipt',
						'type'	=> 'String',
						'value'	=> '',
						'desc'	=> '苹果receipt值',
					),
					array(
						'name'	=> 'mode',
						'type'	=> 'Int',
						'value'	=> '',
						'desc'	=> '模式',
					),
				),
			),
			'return'=> array(
				array(
					'state'	=> '0',
					'msg'	=> 'ok',
					'desc'	=> '成功',
				),
				array(
					'state'	=> '21000',
					'msg'	=> 'receipt check failed',
					'desc'	=> 'App Store无法解析提交的值',
				),
				array(
					'state'	=> '21002',
					'msg'	=> 'receipt check failed',
					'desc'	=> 'receipt值被非法修改',
				),
				array(
					'state'	=> '21003',
					'msg'	=> 'receipt check failed',
					'desc'	=> 'receipt不能被验证',
				),
				array(
					'state'	=> '21004',
					'msg'	=> 'receipt check failed',
					'desc'	=> 'The shared secret you provided does not match the shared secret on file for your account',
				),
				array(
					'state'	=> '21005',
					'msg'	=> 'receipt check failed',
					'desc'	=> 'The receipt server is not currently available',
				),
				array(
					'state'	=> '21006',
					'msg'	=> 'receipt check failed',
					'desc'	=> 'This receipt is valid but the subscription has expired. When this status code is returned to your server, the receipt data is also decoded and returned as part of the response',
				),
				array(
					'state'	=> '21007',
					'msg'	=> 'receipt check failed',
					'desc'	=> 'This receipt is a sandbox receipt, but it was sent to the production service for verification',
				),
				array(
					'state'	=> '21008',
					'msg'	=> 'receipt check failed',
					'desc'	=> 'This receipt is a production receipt, but it was sent to the sandbox service for verification',
				),
				array(
					'state'	=> '21010',
					'msg'	=> 'void receipt',
					'desc'	=> 'receipt无效',
				),
			)
		);
		$this->load->view('api/pay', $this->_data);
	}

	public function state_code() {
		$this->_data['slider'] = $this->load->view('api/slider', array('active'=>__FUNCTION__), TRUE);
		$this->_data['state_code'] = array(
			array('code' => 1000, 'msg' => '请求重复', 'info'=>''),
			array('code' => 1001, 'msg' => '验证不通过', 'info'=>''),
			array('code' => 1002, 'msg' => '书本不存在', 'info'=>''),
			array('code' => 1003, 'msg' => '文件不存在', 'info'=>''),
			array('code' => 1004, 'msg' => '参数缺失', 'info'=>''),
			array('code' => 1005, 'msg' => '邮箱格式错误', 'info'=>''),
			array('code' => 2000, 'msg' => 'token获取失败', 'info'=>''),
			array('code' => 2001, 'msg' => 'token错误', 'info'=>''),
			array('code' => 2002, 'msg' => 'token过期', 'info'=>''),
			array('code' => 2003, 'msg' => 'bookid不匹配', 'info'=>''),
			array('code' => 3000, 'msg' => 'bookid不匹配', 'info'=>''),
		);
		$this->load->view('api/state_code', $this->_data);
	}
}