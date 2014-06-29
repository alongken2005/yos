<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Author extends MY_Controller {
	public function __construct() {
		parent::__construct();

		$this->load->model('base_mdl', 'base');

		if($this->member) {
			$this->_data['userInfo'] = $this->getUserInfo($this->member['uid']);
		}		
	}

	public function index() {
		$this->load->view(THEME.'/author');
	}	
}