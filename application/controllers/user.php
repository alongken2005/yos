<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @deprecated 用户
 * @version 1.0.0 12-10-22 下午9:31
 * @author 张浩
 */

class User extends MY_Controller {
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

	public function login() {
		$this->load->view(THEME.'/login', $this->_data);
	}
	
	public function reg() {
		$this->load->view(THEME.'/reg', $this->_data);
	}

	public function info() {
		if(!$this->member) {
			$this->msg->showmessage('请先登陆！', site_url('user/login'));
		}


		$this->_data['account'] = $this->base->get_data('account', array('uid'=>$this->member['uid']))->row_array();
		$this->_data['active'] = 'userinfo';
		$this->load->view(THEME.'/header');
		$this->load->view(THEME.'/user_info', $this->_data);
		$this->load->view(THEME.'/footer');
	}

	/**
	 * 登录
	 */
	public function do_login() {
		$username		= $this->input->post('username');
		$password		= $this->input->post('password');
		$md5password	= md5($this->config->item('userPostfix').$password);

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
					output(1, '登录成功');
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
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$password2 = $this->input->post('password2');
		$timestamp = time();

		if(!trim($username)) output(2110, '用户名不能为空！');
		if(!is_email($email)) output(1005, '邮箱格式错误');

		$count = $this->db->query('SELECT uid FROM yos_account WHERE email = "'.$this->db->escape_str($email).'"')->num_rows();
		if($count > 0) output(2109, '该邮箱已被注册');
		if(strlen($password) < 6) output(2106, '密码长度不能少于6位');
		if($password != $password2) output(2107, '两次密码输入不一致');

		$insert_data = array(
			'username'		=> $username,
			'email'			=> $email,
			'password'		=> md5($this->config->item('userPostfix').$password),
			'status'		=> 1,
			'ctime'			=> $timestamp,
		);
		if($this->base->insert_data('account', $insert_data)) {
			set_cookie('user', authcode(json_encode(array('uid'=>$this->db->insert_id(), 'email'=>$email, 'username'=>$username)), 'ENCODE'), 3600*24);
			output(1, '注册成功');
		} else {
			output(2108, '注册失败');
		}
	}

	/**
	 * 申请作者
	 */	
	public function apply_author() {
		if(!$this->member) {
			$this->msg->showmessage('请先登陆！', site_url('user/login'));
		}

		$this->_data['user'] = $this->member;
		$step = $this->_data['step'] = $this->input->get('step') ? $this->input->get('step') : 1;
		if($_POST) {
			if($step == 1) {
				$update_data = array(
					'organization' 	=> $this->input->post('organization'),
					'username' 		=> $this->input->post('username'),
					'street' 		=> $this->input->post('street'),
					'city' 			=> $this->input->post('city'),
					'state' 		=> $this->input->post('state'),
					'country' 		=> $this->input->post('country'),
				);


				$this->base->update_data('account', array('uid'=>$this->member['uid']), $update_data);

				$this->msg->showmessage('Successful operation', site_url('user/apply_author?step=2'));
			} else if($step == 2) {
				$insert_data = array(
					'uid' 			=> $this->member['uid'],
					'bank_name' 	=> $this->input->post('bank_name'),
					'owner_name' 	=> $this->input->post('owner_name'),
					'bank_account' 	=> $this->input->post('bank_account'),
					'bank_routing' 	=> $this->input->post('bank_routing'),
					'bank_street' 	=> $this->input->post('bank_street'),
					'city' 			=> $this->input->post('city'),
					'state' 		=> $this->input->post('state'),
					'country' 		=> $this->input->post('country'),
					'ctime' 		=> time(),
					'mtime' 		=> time(),
				);
				$this->base->insert_data('card', $insert_data);

				$this->msg->showmessage('Successful operation', site_url('user/apply_author?step=3'));		
			} else if($step == 3) {
				$this->base->update_data('account', array('uid'=>$this->member['uid']), array('is_author'=>1));
				$this->msg->showmessage('Successful operation', site_url('user/apply_author?step=4'));
			}
		} else {
			$this->load->view(THEME.'/header_sam');
			$this->load->view(THEME.'/apply_author', $this->_data);
			$this->load->view(THEME.'/footer_sam');			
		}

	}

	public function redirect() {
		$redirect = get_cookie('redirect') ? get_cookie('redirect') : site_url('index');
		delete_cookie('redirect');
		redirect($redirect);
	}
}