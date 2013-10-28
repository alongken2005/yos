<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @deprecated 用户
 * @version 1.0.0 12-10-22 下午9:31
 * @author 张浩
 */

class User extends CI_Controller {
	private $_data;

	public function __construct() {
		parent::__construct();
		$this->load->model('base_mdl', 'base');
	}

	/**
	 * @deprecated 默认方法
	 */
	public function index() {
		$this->login();
	}

	public function reg() {
		$this->load->view(THEME.'/reg', $this->_data);
	}

	public function info() {
		$uid = $this->input->get('uid');

		if($_POST) {

		} else {
			$user = $this->base->get_data('account', array('uid'=>$uid))->row_array();
		}
	}

	/**
	 * 登录
	 */
	public function do_login() {
		$username		= $this->input->post('username');
		$password		= $this->input->post('password');
		$md5password	= md5($password);

		if($username == '' || $password == '') {
			output(2101, '用户名或密码为空');			//用户名或密码为空
		}

		$user = $this->base->get_data('account', array('email'=>$username), 'uid,status,email,password,username')->row_array();

		if($user) {
			if($user['password'] != $md5password) {
				output(2102, '密码错误');		//密码错误
			} else {
				if($user['status'] == 1) {
					set_cookie('user', authcode(json_encode(array('uid'=>$user['uid'], 'email'=>$user['email'], 'username'=>$user['username'])), 'ENCODE'), 3600*24);
					output(0, '登录成功');
				} else {
					output(2103, '账号被锁');	//账号被锁
				}
			}
		} else {
			output(2104, '用户不存在');			//用户不存在
		}
	}
	
	public function loginout() {
		set_cookie('user', '', -100);
		redirect('index');
	}

	/**
	 * 注册
	 */
	public function do_reg() {
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$password2 = $this->input->post('password2');
		$timestamp = time();

		if(!is_email($email)) output(1005, '邮箱格式错误');

		$count = $this->db->query('SELECT uid FROM yos_account WHERE email = "'.mysql_escape_string($email).'"')->num_rows();
		write_log('SELECT uid FROM yos_account WHERE email = "'.mysql_escape_string($email).'"');
		write_log($password);
		if($count > 0) output(2109, '该邮箱已被注册');
		if(strlen($password) < 6) output(2106, '密码长度不能少于6位');
		if($password != $password2) output(2107, '两次密码输入不一致');

		$insert_data = array(
			'username'		=> $email,
			'email'			=> $email,
			'password'		=> md5($password),
			'status'		=> 1,
			'ctime'			=> $timestamp,
		);
		if($this->base->insert_data('account', $insert_data)) {
		
			set_cookie('user', authcode(json_encode(array('uid'=>$this->db->insert_id(), 'email'=>$email, 'username'=>$email)), 'ENCODE'), 3600*24);
			//$this->session->set_userdata(array('uid'=>$this->db->insert_id(), 'email'=>$email));
			output(0, '注册成功');
		} else {
			output(2108, '注册失败');
		}
	}

	public function redirect() {
		$redirect = get_cookie('redirect') ? get_cookie('redirect') : site_url('index');
		delete_cookie('redirect');
		redirect($redirect);
	}
}