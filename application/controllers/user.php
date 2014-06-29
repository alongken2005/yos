<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 用户
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
	 * 默认方法
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
			$this->msg->showmessage(lang('login_first'), site_url('user/login'));
		}

		$this->_data['userInfo'] = $this->getUserInfo($this->member['uid']);

		$card = $this->base->get_data('card', array('uid'=>$this->member['uid'], 'is_credit'=>1))->row_array();

		if($card) {
			$card['cardinfo'] = unserialize(authcode($card['cardinfo']));
		    $this->_data['card'] = $card;
		}

		$this->_data['account'] = $this->base->get_data('account', array('uid'=>$this->member['uid']))->row_array();
		$this->_data['active'] = 'userinfo';
		$this->_data['slider_left'] = $this->load->view(THEME.'/slider_left', $this->_data, true);

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
			output(2101, lang('name_pswd_empty'));			//用户名或密码为空
		}

		$user = $this->base->get_data('account', array('loginname'=>$username), 'uid,status,email,password,username')->row_array();

		if($user) {
			if($user['password'] != $md5password) {
				output(2102, lang('pswd_error'));		//密码错误
			} else {
				if($user['status'] == 1) {
					set_cookie('user', authcode(json_encode(array('uid'=>$user['uid'], 'email'=>$user['email'], 'username'=>$user['username'])), 'ENCODE'), 3600*24);
					output(1, lang('success'));
				} else {
					output(2103, lang('account_lock'));	//账号被锁
				}
			}
		} else {
			output(2104, lang('account_not_exist'));			//用户不存在
		}
	}
	
	public function loginout() {
		set_cookie('user', '', -100);
		redirect('index');
	}


	public function fLogin() {
		require_once(APPPATH . '/libraries/facebook.php');
		$facebook = new Facebook(array(
			'appId'  => '480908935362028',
			'secret' => 'f42bcc6c8f27b7c2cc8231d71481e300',
		));

		$params = array(
		  	'scope' => 'read_stream, friends_likes',
		  	'redirect_uri' => site_url('user/fBack'),
		);

		$loginUrl = $facebook->getLoginUrl($params);

		redirect($loginUrl);
	}

	public function fBack() {
		require_once(APPPATH . '/libraries/facebook.php');
		$facebook = new Facebook(array(
			'appId'  => '480908935362028',
			'secret' => 'f42bcc6c8f27b7c2cc8231d71481e300',
		));					

		$facebookId = $facebook->getUser();
		if($facebookId) {
			$user_profile = $facebook->api('/me');

			$user = $this->db->query('SELECT * FROM yos_account WHERE facebookId = '.$facebookId)->row_array();
			if(!$user) {
				$insert_data = array(
					'username'		=> $user_profile['name'],
					'facebookId'	=> $facebookId,
					'loginname'		=> $facebookId,
					'password'		=> md5($this->config->item('userPostfix').$facebookId),
					'status'		=> 1,
					'deposit'		=> 5.00,
					'ctime'			=> time(),
				);	
				if($this->base->insert_data('account', $insert_data)) {
					set_cookie('user', authcode(json_encode(array('uid'=>$this->db->insert_id(), 'email'=>'', 'username'=>$user_profile['name'])), 'ENCODE'), 3600*24);
					redirect();
				} else {
					output(100, lang('failed'));
				}						
			} else if($user && $user['facebookId'] == $facebookId) {
				if($user['status'] == 1) {
					set_cookie('user', authcode(json_encode(array('uid'=>$user['uid'], 'email'=>$user['email'], 'username'=>$user['username'])), 'ENCODE'), 3600*24);
					
					redirect();
					//output(1, array('uid'=>$user['uid'], 'username'=>$user['username'], 'email'=>$user['email']));
				} else {
					output(2103, lang('account_lock'));	//账号被锁
				}
			} else {
				output(100, lang('failed'));
			}			
		}
	}

	public function facebookEdit() {
		if(!$this->member) {
			$this->msg->showmessage(lang('login_first'), site_url('user/login'));
		}

		$username = $this->input->post('username');
		$email = $this->input->post('email');

		if(!is_email($email)) output(1005, lang('email_error'));
		if(!trim($username)) output(2110, lang('account_empty'));

		$this->base->update_data('account', array('uid'=>$this->member['uid']), array('username'=>$username, 'email'=>$email));

		$this->msg->showmessage(lang('success'), site_url('user/info'));
	}

	public function userEdit() {
		if(!$this->member) {
			$this->msg->showmessage(lang('login_first'), site_url('user/login'));
		}		
		$uid = $this->member['uid'];
		$username = $this->input->post('username');
		$email = $this->input->post('email');
		$oldPassword = $this->input->post('oldPassword');		
		$newPassword = $this->input->post('newPassword');		
		$confirmNewPassword = $this->input->post('confirmNewPassword');	

		if(!is_email($email)) $this->msg->showmessage(lang('email_error'), site_url('user/info'), 2);
		if(!trim($username)) $this->msg->showmessage(lang('account_empty'), site_url('user/info'), 2);
		
		if($newPassword != $confirmNewPassword) $this->msg->showmessage(lang('pswd_diff'), site_url('user/info'), 2);


		$emailnum = $this->db->query('SELECT uid, password FROM yos_account WHERE loginname = "'.$email.'" AND uid <> '.$uid)->num_rows();
		if($emailnum>0) $this->msg->showmessage(lang('email_used'), site_url('user/info'), 2);
		$user = $this->db->query('SELECT uid, password FROM yos_account WHERE uid = '.$uid)->row_array();

		$update_data = array(
			'username'		=> $username,
			'email'			=> $email,
		);

		if($oldPassword) {
			if(strlen($newPassword) < 6) $this->msg->showmessage(lang('pswd_6'), site_url('user/info'));
			if($user['password'] != md5($this->config->item('userPostfix').$oldPassword)) {
				$this->msg->showmessage(lang('pswd_error'), site_url('user/info'), 2);
			}

			$update_data['password'] = md5($this->config->item('userPostfix').$newPassword);
		}

		

		if($this->base->update_data('account', array('uid'=>$uid), $update_data)) {
			$this->msg->showmessage(lang('success'), site_url('user/info'));
		} else {
			$this->msg->showmessage(lang('failed'), site_url('user/info'), 2);
		}		
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

		if(!trim($username)) output(2110, lang('account_empty'));
		if(!is_email($email)) output(1005, lang('email_error'));

		$count = $this->db->query('SELECT uid FROM yos_account WHERE loginname = "'.$this->db->escape_str($email).'"')->num_rows();
		if($count > 0) output(2109, lang('email_used'));
		if(strlen($password) < 6) output(2106, lang('pswd_6'));
		if($password != $password2) output(2107, lang('pswd_diff'));

		$insert_data = array(
			'username'		=> $username,
			'email'			=> $email,
			'loginname'		=> $email,
			'password'		=> md5($this->config->item('userPostfix').$password),
			'status'		=> 1,
			'deposit'		=> 5.00,
			'ctime'			=> $timestamp,
		);
		if($this->base->insert_data('account', $insert_data)) {
			set_cookie('user', authcode(json_encode(array('uid'=>$this->db->insert_id(), 'email'=>$email, 'username'=>$username)), 'ENCODE'), 3600*24);
			output(1, 'Success! You have signed up with YouShelf.');
		} else {
			output(100, lang('failed'));
		}
	}

	/**
	 * 申请作者
	 */	
	public function apply_author() {
		if(!$this->member) {
			$this->msg->showmessage(lang('login_first'), site_url('user/login'));
		}

		$this->_data['userInfo'] = $this->getUserInfo($this->member['uid']);

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
					'zipcode' 		=> $this->input->post('zipcode'),
					'country' 		=> $this->input->post('country'),
				);


				$this->base->update_data('account', array('uid'=>$this->member['uid']), $update_data);
				redirect(site_url('user/apply_author?step=2'));
			} else if($step == 2) {
				$cardinfo = array(
					'bank_name'		=> $this->input->post('bank_name'),
					'owner_name'	=> $this->input->post('owner_name'),
					'bank_account'	=> $this->input->post('bank_account'),
					'bank_routing'	=> $this->input->post('bank_routing'),
					'bank_street'	=> $this->input->post('bank_street'),
					'city'			=> $this->input->post('city'),
					'state'			=> $this->input->post('state'),
					'zipcode'		=> $this->input->post('zipcode'),
					'country'		=> $this->input->post('country'),
				);
				$insert_data = array(
					'uid' 			=> $this->member['uid'],
					'is_credit'		=> 0,
					'cardinfo'		=> authcode(serialize($cardinfo), 'ENCODE'),
					'ctime' 		=> time(),
					'mtime' 		=> time(),
				);
				$this->base->insert_data('card', $insert_data);

				redirect(site_url('user/apply_author?step=3'));
			} else if($step == 3) {
				$this->base->update_data('account', array('uid'=>$this->member['uid']), array('is_author'=>1));
				redirect(site_url('user/apply_author?step=4'));
			}
		} else {
			$this->load->view(THEME.'/header');
			$this->_data['slider_left'] = $this->load->view(THEME.'/slider_left', $this->_data, true);
			$this->load->view(THEME.'/apply_author', $this->_data);
			$this->load->view(THEME.'/footer');		
		}

	}

	public function redirect() {
		$redirect = get_cookie('redirect') ? get_cookie('redirect') : site_url('index');
		delete_cookie('redirect');
		redirect($redirect);
	}
}