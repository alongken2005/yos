<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @deprecated 用户
 * @version 1.0.0 12-10-22 下午9:31
 * @author 张浩
 */

class User extends CI_Controller {

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

	/**
	 * 登录
	 */
	public function login() {
		$username		= $this->input->post('username');
		$password		= $this->input->post('password');
		$md5password	= md5($password);

		if($username == '' || $password == '') {
			output(2101, '用户名或密码为空');			//用户名或密码为空
		}

		$user = $this->base->get_data('account', array('username'=>$username), 'id,status,username,password')->row_array();

		if($user) {
			if($user['password'] != $md5password) {
				output(2102, '密码错误');		//密码错误
			} else {
				if($user['status'] == 1) {
					$this->session->set_userdata(array('uid'=>$user['id'], 'username'=>$user['username']));
					output(0, '登录成功');
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
	public function register() {
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$password2 = $this->input->post('password2');
		$timestamp = time();

		if(!is_email($email)) output(1005, '邮箱格式错误');

		$count = $this->db->query('SELECT id FROM ab_account WHERE username = "'.mysql_escape_string($email).'" OR email = "'.mysql_escape_string($email).'"')->num_rows();
		write_log('SELECT id FROM ab_account WHERE username = "'.mysql_escape_string($email).'" OR email = "'.mysql_escape_string($email).'"');
		if($count > 0) output(2109, '该邮箱已被注册');
		if(strlen($password) < 6) output(2106, '密码长度不能少于6位');
		if($password != $password2) output(2107, '两次密码输入不一致');

		if($this->db->query("UPDATE ab_account_id SET id = LAST_INSERT_ID(id+1)")) {
			$uid = $this->db->insert_id();
			$insert_data = array(
				'id'			=> $uid,
				'site_id'		=> 1,
				'parent_id'		=> $uid,
				'username'		=> $email,
				'email'			=> $email,
				'password'		=> md5($password),
				'status'		=> 1,
				'date_orig'		=> $timestamp,
				'date_last'		=> $timestamp,
			);
			$this->base->insert_data('account', $insert_data);
			$this->session->set_userdata(array('uid'=>$uid, 'username'=>$email));
			output(0, '注册成功');
		} else {
			output(2108, '注册失败');
		}
	}
}