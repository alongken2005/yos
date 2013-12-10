<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 书本
 * @version 1.0.0 (12-10-8 下午3:03)
 * @author ZhangHao
 */

class User extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('base_mdl', 'base');
	}

	/**
	 * 默认方法
	 */
	public function index() {
		$this->views();
	}

	/**
	 * 登录
	 */	
	public function login () {
		$email 			= $this->input->post('email');
		$password 		= $this->input->post('password');
		$md5password	= md5($this->config->item('userPostfix').$password);

		getSign(__function__, $this->input->post('random'), $this->input->post('sign'));	
		
		if(!$email || !$password) output(2101, '用户名或密码为空');

		$user = $this->base->get_data('account', array('email'=>$this->db->escape_str($email)), 'uid,status,email,password,username')->row_array();

		if($user) {
			if($user['password'] != $md5password) {
				output(2102, '密码错误');		//密码错误
			} else {
				if($user['status'] == 1) {
					set_cookie('user', authcode(json_encode(array('uid'=>$user['uid'], 'email'=>$user['email'], 'username'=>$user['username'])), 'ENCODE'), 3600*24);
					output(1, array('uid'=>$user['uid'], 'username'=>$user['username'], 'email'=>$user['email']));
				} else {
					output(2103, '账号被锁');	//账号被锁
				}
			}
		} else {
			output(2104, '用户不存在');			//用户不存在
		}
	}

	/**
	 * 注册
	 */	
	public function reg() {
		$email = $this->input->post('email');
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		getSign(__function__, $this->input->post('random'), $this->input->post('sign'));

		if(!trim($username)) output(2110, '用户名不能为空！');
		if(!is_email($email)) output(1005, '邮箱格式错误');

		$count = $this->db->query('SELECT uid FROM yos_account WHERE email = "'.$this->db->escape_str($email).'"')->num_rows();
		if($count > 0) output(2109, '该邮箱已被注册');
		if(strlen($password) < 6) output(2106, '密码长度不能少于6位');

		$insert_data = array(
			'username'		=> $username,
			'email'			=> $email,
			'password'		=> md5($this->config->item('userPostfix').$password),
			'status'		=> 1,
			'ctime'			=> time(),
		);
		if($this->base->insert_data('account', $insert_data)) {
			set_cookie('user', authcode(json_encode(array('uid'=>$this->db->insert_id(), 'email'=>$email, 'username'=>$username)), 'ENCODE'), 3600*24);
			output(1, '注册成功');
		} else {
			output(100, '注册失败');
		}		
	}

	/**
	 * 文档显示
	 */
	public function views() {

		$this->_data['declare'] = array(
			array(
				'id'		=> 'user_login',
				'title'		=> '登录',
				'useway'	=> 'POST',
				'apiurl'	=>  site_url('api/user/login'),
				'sam'		=>  site_url('apitest#user_login'),
				'arguments'	=> array(
					array('name'=>'random',		'type'=>'String',	'value'=>'随机数', 'desc'=>'随机数'),
					array('name'=>'sign',		'type'=>'String',	'value'=>'strtoupper(md5("login".$random.$signPostfix));', 'desc'=>'验证字符串'),
					array('name'=>'email',		'type'=>'String',	'value'=>'邮箱地址', 'desc'=>'邮箱地址'),
					array('name'=>'password',	'type'=>'String',	'value'=>'登录密码', 'desc'=>'登录密码'),
				),
				'code'		=> array(
					array('result'=>1,		'message'=>'登录成功',		'desc'=>'登录成功'),
					array('result'=>2101,	'message'=>'用户名或密码为空',	'desc'=>'用户名或密码为空'),
					array('result'=>2102,	'message'=>'密码错误',		'desc'=>'密码错误'),
					array('result'=>2103,	'message'=>'账号被锁',		'desc'=>'账号被锁'),
					array('result'=>2104,	'message'=>'用户不存在',		'desc'=>'用户不存在'),
					array('result'=>3001,	'message'=>'验证字符串错误',	'desc'=>'验证字符串错误'),
					array('result'=>3002,	'message'=>'随机数不能为空',	'desc'=>'随机数不能为空'),
					array('result'=>3003,	'message'=>'验证失败',		'desc'=>'验证失败'),
				),				
			),
			array(
				'id'		=> 'user_reg',
				'title'		=> '注册',
				'useway'	=> 'POST',
				'apiurl'	=>  site_url('api/user/reg'),
				'sam'		=>  site_url('apitest#user_reg'),
				'arguments'	=> array(
					array('name'=>'random',		'type'=>'String',	'value'=>'随机数', 'desc'=>'随机数'),
					array('name'=>'sign',		'type'=>'String',	'value'=>'strtoupper(md5("reg".$random.$signPostfix));', 'desc'=>'验证字符串'),
					array('name'=>'username',	'type'=>'String',	'value'=>'用户名', 'desc'=>'用户名'),
					array('name'=>'email',		'type'=>'String',	'value'=>'邮箱地址', 'desc'=>'邮箱地址'),
					array('name'=>'password',	'type'=>'String',	'value'=>'登录密码', 'desc'=>'登录密码'),
				),
				'code'		=> array(
					array('result'=>1,		'message'=>'注册成功',		'desc'=>'注册成功'),
					array('result'=>100,	'message'=>'注册失败',		'desc'=>'注册失败'),
					array('result'=>2110,	'message'=>'用户名不能为空',	'desc'=>'用户名不能为空'),
					array('result'=>1005,	'message'=>'邮箱格式错误',	'desc'=>'邮箱格式错误'),
					array('result'=>2106,	'message'=>'密码长度不能少于6位',	'desc'=>'密码长度不能少于6位'),
					array('result'=>2109,	'message'=>'该邮箱已被注册',	'desc'=>'该邮箱已被注册'),
					array('result'=>3001,	'message'=>'验证字符串错误',	'desc'=>'验证字符串错误'),
					array('result'=>3002,	'message'=>'随机数不能为空',	'desc'=>'随机数不能为空'),
					array('result'=>3003,	'message'=>'验证失败',		'desc'=>'验证失败'),
				),				
			),			
		);

		$this->_data['v'] = 'mobile';
		$this->load->view('api/header', $this->_data);
		$this->load->view('api/tpl', $this->_data);
		$this->load->view('api/footer');
	}

}