<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
	
	function __construct() {
        parent::__construct();
    }
    
    public function checkLogin() {
	    if($user = get_cookie('user')) {
		    return json_decode(authcode($user), true);
	    }
	    
	    return false;
    }
	
}