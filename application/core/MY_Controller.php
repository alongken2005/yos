<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
	public $member;

	function __construct() {
        parent::__construct();
        $this->member = $this->checkLogin();
    }
    
    //����¼״̬
    public function checkLogin() {
	    if($user = get_cookie('user')) {
		    return json_decode(authcode($user), true);
	    }
	    
	    return false;
    }

    //��ȡ�û���Ϣ
    public function getUserInfo($uid) {
    	return $this->base->get_data('account', array('uid'=>$uid))->row_array();
    }
	
}