<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* @deprecated 权限检查
* @see Power
* @version 1.0.0 (Mon Feb 13 02:49:16 GMT 2012)
* @author ZhangHao
*/
class Permission
{
	private $ci;

	public function __construct()
	{
		$this->ci = & get_instance();
	}
	/**
	* @deprecated 权限检测
	*/
	public function power_check ($power = '', $class = false)
	{
//		$pre = get_cookie('power');

		//登录检查
		if(!get_cookie('uid')) {
			$this->ci->msg->showmessage('您还未登录！', site_url('admin/login'));
		}

//		$pre = unserialize($pre);
//		if($class)
//		{
//			return (!$pre || !in_array(strtolower($power), $pre)) ? false : true;
//		}
//		else
//		{
//			if(!$pre || !in_array($power, $pre))
//			{
//				$this->ci->msg->showmessage('您没有该操作权限！', site_url('data'));
//			}
//			else
//			{
//				return true;
//			}
//		}
	}

	/**
	* 前台权限检测
	*/
	public function login_check($url = '', $jump = true) {
		//登录检查
		if(!$this->ci->session->userdata('uid')) {
			if($user = $this->get_cr()) {
				$this->ci->session->set_userdata(array('uid'=>$user['id'], 'username'=>$user['username']));
				return $user['id'];
			} else {
				if($jump) {
					if($url) {
						$this->ci->input->set_cookie('redirect', $url, 0);
					}
					$this->ci->msg->showmessage('您还未登录！', site_url('reg'));
				} else {
					return false;
				}
			}
		}
		return $this->ci->session->userdata('uid');
	}

	/**
	 * 从儿童之路获取用户登录信息
	 */
	public function get_cr() {
		$sn_id = get_cookie('CR');
		if($sn_id) {
			$this->ci->db->select('account_id');
			$this->ci->db->where(array('id'=>$sn_id, 'logged'=>1, 'date_expire >'=>time()));
			$session = $this->ci->db->get('ab_session')->row_array();

			if($session && $session['account_id']) {
				$this->ci->db->select('id, username');
				$this->ci->db->where(array('id'=>$session['account_id']));
				$user = $this->ci->db->get('ab_account')->row_array();
				return $user;
			}
		}
		return false;
	}
}