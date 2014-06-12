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

	/*
	 * 获取用户登陆状态
	 */
	public function getUserInfo() {
		if(!isset($_COOKIE['user']) || !$_COOKIE['user']) {
			output(1008, lang('login_first'));
		} else {
			$user = json_decode(authcode($_COOKIE['user']), true);
			output(1, $user);
		}
	}

	/**
	 * 登录
	 */	
	public function login () {
		$email 			= $this->input->post('email');
		$password 		= $this->input->post('password');
		$md5password	= md5($this->config->item('userPostfix').$password);

		getSign(__function__, $this->input->post('random'), $this->input->post('sign'));	
		
		if(!$email || !$password) output(2101, lang('name_pswd_empty'));

		$user = $this->base->get_data('account', array('loginname'=>$this->db->escape_str($email)), 'uid,status,email,password,username')->row_array();

		if($user) {
			if($user['password'] != $md5password) {
				output(2102, lang('pswd_error'));		//密码错误
			} else {
				if($user['status'] == 1) {
					setcookie('user', authcode(json_encode(array('uid'=>$user['uid'], 'email'=>$user['email'], 'username'=>$user['username'])), 'ENCODE'), time()+3600*24, '/');
					output(1, array('uid'=>$user['uid'], 'username'=>$user['username'], 'email'=>$user['email']));
				} else {
					output(2103, lang('account_lock'));	//账号被锁
				}
			}
		} else {
			output(2104, lang('account_not_exist'));			//用户不存在
		}
	}

	/**
	 * 退出
	 */
	public function loginOut() {
		set_cookie('user', '', -100);
		output(1, lang('success'));
	}

	/**
	 * 注册
	 */	
	public function reg() {
		$email = $this->input->post('email');
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		getSign(__function__, $this->input->post('random'), $this->input->post('sign'));

		if(!trim($username)) output(2110, lang('account_empty'));
		if(!is_email($email)) output(1005, lang('email_error'));

		$count = $this->db->query('SELECT uid FROM yos_account WHERE email = "'.$this->db->escape_str($email).'"')->num_rows();
		if($count > 0) output(2109, lang('email_used'));
		if(strlen($password) < 6) output(2106, lang('pswd_6'));

		$insert_data = array(
			'username'		=> $username,
			'email'			=> $email,
			'loginname'		=> $email,
			'password'		=> md5($this->config->item('userPostfix').$password),
			'status'		=> 1,
			'deposit'		=> 5.00,
			'ctime'			=> time(),
		);
		if($this->base->insert_data('account', $insert_data)) {
			set_cookie('user', authcode(json_encode(array('uid'=>$this->db->insert_id(), 'email'=>$email, 'username'=>$username)), 'ENCODE'), 3600*24);
			output(1, lang('success'));
		} else {
			output(100, lang('failed'));
		}		
	}

	/**
	 * facebook登陆
	 */		
	public function facebookLogin() {
		$facebookId = $this->input->get_post('facebookId');
		$username = $this->input->get_post('username');
		$email = $this->input->get_post('email');
		$sign = $this->input->post('sign');

		if(!$sign) output(3001, lang('sign_error'));
		if(!$username) output(2110, lang('account_empty'));
		if(!$email) output(1005, lang('email_error'));

		$signPostfix = config_item('signPostfix');
		$sign2 = strtoupper(md5(__function__.$facebookId.$username.$email.$signPostfix));

		if($sign != $sign2) output(3003, lang('verification_failed'));

		$user = $this->db->query('SELECT * FROM yos_account WHERE loginname = "'.$this->db->escape_str($facebookId).'"')->row_array();
		if(!$user) {
			$insert_data = array(
				'username'		=> $username,
				'facebookId'	=> $facebookId,
				'loginname'		=> $facebookId,
				'email'			=> $email,
				'password'		=> md5($this->config->item('userPostfix').$facebookId),
				'status'		=> 1,
				'deposit'		=> 5.00,
				'ctime'			=> time(),
			);	
			if($this->base->insert_data('account', $insert_data)) {
				set_cookie('user', authcode(json_encode(array('uid'=>$this->db->insert_id(), 'email'=>$email, 'username'=>$username)), 'ENCODE'), 3600*24);
				output(1, lang('success'));
			} else {
				output(100, lang('failed'));
			}						
		} else {
			if($user['status'] == 1) {
				set_cookie('user', authcode(json_encode(array('uid'=>$user['uid'], 'email'=>$user['email'], 'username'=>$user['username'])), 'ENCODE'), 3600*24);
				output(1, array('uid'=>$user['uid'], 'username'=>$user['username'], 'email'=>$user['email']));
			} else {
				output(2103, lang('account_lock'));	//账号被锁
			}
		}

	}

	/**
	 * 修改用户信息
	 */	
	public function editInfo() {
		$uid = intval($this->input->post('uid'));
		$username = $this->input->post('username');
		$oldPassword = $this->input->post('oldPassword');		
		$newPassword = $this->input->post('newPassword');		
		$confirmNewPassword = $this->input->post('confirmNewPassword');	

		if(!trim($username)) output(2110, lang('account_empty'));
		if(strlen($newPassword) < 6) output(2106, lang('pswd_6'));
		if($newPassword != $confirmNewPassword) output(2107, lang('pswd_diff'));

		$user = $this->db->query('SELECT uid, password FROM yos_account WHERE uid = '.$uid)->row_array();
		if($user['password'] != md5($this->config->item('userPostfix').$oldPassword)) {
			output(2102, lang('pswd_error'));
		}

		$update_data = array(
			'username'		=> $username,
			'password'		=> md5($this->config->item('userPostfix').$newPassword),
		);		

		if($this->base->update_data('account', array('uid'=>$uid), $update_data)) {
			output(1, lang('success'));
		} else {
			output(100, lang('failed'));
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
				'id'		=> 'user_loginOut',
				'title'		=> '退出登录',
				'useway'	=> 'POST',
				'apiurl'	=>  site_url('api/user/loginOut'),
				'sam'		=>  site_url('apitest#user_loginOut'),
				'arguments'	=> array(
					array('name'=>'random',		'type'=>'String',	'value'=>'随机数', 'desc'=>'随机数'),
					array('name'=>'sign',		'type'=>'String',	'value'=>'strtoupper(md5("loginOut".$random.$signPostfix));', 'desc'=>'验证字符串'),
				),
				'code'		=> array(
					array('result'=>1,		'message'=>'退出成功',		'desc'=>'退出成功'),
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
			array(
				'id'		=> 'user_editInfo',
				'title'		=> '修改用户信息',
				'useway'	=> 'POST',
				'apiurl'	=>  site_url('api/user/editInfo'),
				'sam'		=>  site_url('apitest#user_editInfo'),
				'arguments'	=> array(
					array('name'=>'random',		'type'=>'String',	'value'=>'随机数', 'desc'=>'随机数'),
					array('name'=>'sign',		'type'=>'String',	'value'=>'strtoupper(md5("editInfo".$random.$signPostfix));', 'desc'=>'验证字符串'),
					array('name'=>'uid',		'type'=>'Int',	'value'=>'用户id', 'desc'=>'用户id'),
					array('name'=>'username',	'type'=>'String',	'value'=>'用户名', 'desc'=>'用户名'),
					array('name'=>'oldPassword','type'=>'String',	'value'=>'旧密码', 'desc'=>'旧密码'),
					array('name'=>'newPassword',	'type'=>'String',	'value'=>'新密码', 'desc'=>'新密码'),
					array('name'=>'confirmNewPassword',	'type'=>'String',	'value'=>'确认新密码', 'desc'=>'确认新密码'),
				),
				'code'		=> array(
					array('result'=>1,		'message'=>'成功',		'desc'=>'成功'),
					array('result'=>100,	'message'=>'失败',		'desc'=>'失败'),
					array('result'=>2102,	'message'=>'密码错误',	'desc'=>'密码错误'),
					array('result'=>2106,	'message'=>'密码长度不能少于6位',	'desc'=>'密码长度不能少于6位'),
					array('result'=>2107,	'message'=>'两次密码输入不一致',	'desc'=>'两次密码输入不一致'),					
					array('result'=>2110,	'message'=>'用户名不能为空',	'desc'=>'用户名不能为空'),
					array('result'=>3001,	'message'=>'验证字符串错误',	'desc'=>'验证字符串错误'),
					array('result'=>3002,	'message'=>'随机数不能为空',	'desc'=>'随机数不能为空'),
					array('result'=>3003,	'message'=>'验证失败',		'desc'=>'验证失败'),
				),				
			),				
			array(
				'id'		=> 'user_facebookLogin',
				'title'		=> 'facebook登陆',
				'useway'	=> 'GET/POST',
				'apiurl'	=>  site_url('api/user/facebookLogin'),
				'sam'		=>  site_url('apitest#user_facebookLogin'),
				'arguments'	=> array(
					array('name'=>'random',		'type'=>'String',	'value'=>'随机数', 'desc'=>'随机数'),
					array('name'=>'sign',		'type'=>'String',	'value'=>'strtoupper(md5("facebookLogin".facebookId.username.email.$signPostfix));', 'desc'=>'验证字符串'),
					array('name'=>'facebookId',	'type'=>'String',	'value'=>'facebookId', 'desc'=>'facebookId'),
					array('name'=>'username',	'type'=>'String',	'value'=>'用户名', 'desc'=>'用户名'),
					array('name'=>'email',		'type'=>'String',	'value'=>'邮箱', 'desc'=>'邮箱'),
				),
				'code'		=> array(
					array('result'=>1,		'message'=>'成功',			'desc'=>'成功'),
					array('result'=>100,	'message'=>'失败',			'desc'=>'失败'),
					array('result'=>2103,	'message'=>'账号被锁',		'desc'=>'账号被锁'),
					array('result'=>2110,	'message'=>'用户名不能为空',	'desc'=>'用户名不能为空'),
					array('result'=>3001,	'message'=>'验证字符串错误',	'desc'=>'验证字符串错误'),
					array('result'=>3003,	'message'=>'验证失败',		'desc'=>'验证失败'),
					array('result'=>1005,	'message'=>'邮箱格式错误',	'desc'=>'邮箱格式错误'),
				),				
			),	
			array(
				'id'		=> 'user_getUserInfo',
				'title'		=> '获取用户登陆信息',
				'useway'	=> 'GET/POST',
				'apiurl'	=>  site_url('api/user/getUserInfo'),
				'sam'		=>  site_url('apitest#user_getUserInfo'),
				'arguments'	=> array(
					array('name'=>'random',		'type'=>'String',	'value'=>'随机数', 'desc'=>'随机数'),
					array('name'=>'sign',		'type'=>'String',	'value'=>'strtoupper(md5("getUserInfo".$random.$signPostfix));', 'desc'=>'验证字符串'),
				),
				'code'		=> array(
					array('result'=>1,		'message'=>'成功',			'desc'=>'成功'),
					array('result'=>100,	'message'=>'失败',			'desc'=>'失败'),
					array('result'=>3001,	'message'=>'验证字符串错误',	'desc'=>'验证字符串错误'),
					array('result'=>3003,	'message'=>'验证失败',		'desc'=>'验证失败'),
					array('result'=>1008,	'message'=>'请先登陆',		'desc'=>'请先登陆'),
				),				
			),						
		);

		$this->_data['v'] = 'mobile';
		$this->load->view('api/header', $this->_data);
		$this->load->view('api/tpl', $this->_data);
		$this->load->view('api/footer');
	}

}