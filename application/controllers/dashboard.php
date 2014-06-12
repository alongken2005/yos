<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends MY_Controller {
	private $_data;
	private $_user;

	public function __construct() {
		parent::__construct();
		$this->load->model('base_mdl', 'base');

		if($this->member) {
			$this->_data['userInfo'] = $this->getUserInfo($this->member['uid']);
		}

		//检查登陆状态
		if(!$this->member) {
			$this->msg->showmessage(lang('login_first'), site_url('user/login'));
		}		
	}

	/**
	 * 默认方法
	 */
	public function index() {
		//$this->login();
	}

	public function sales() {		

		$this->_data['active'] = 'sales';
		$this->_data['slider_left'] = $this->load->view(THEME.'/slider_left', $this->_data, true);

		$stime = strtotime($this->input->get('stime'));
		$etime = strtotime($this->input->get('etime'));
		$bids = array();

		$bookRes = $this->base->get_data('book', array('uid'=>$this->member['uid']), 'id')->result_array();
		foreach($bookRes as $v) {
			$bids[] = $v['id'];
		}


		$url = '';
		$where = 'WHERE p.bid IN('.implode(',', $bids).') AND p.payed>0';
		if($stime) {
			$where .= ' AND p.ctime >= '.$stime;
			$url = 'stime='.$stime.'&';
		}

		if($etime) {
			$where .= ' AND p.ctime < '.$etime;
			$url = 'etime='.$etime.'&';
		}


		if($bids) {

	        $this->load->library('gpagination');
			$total_num = $this->db->query('SELECT * FROM yos_payed_pages p '.$where)->num_rows();
			$page = $this->input->get('page') > 1 ? $this->input->get('page') : '1';
			$limit = 25;
			$offset = ($page - 1) * $limit;

			$this->gpagination->currentPage($page);
			$this->gpagination->items($total_num);
			$this->gpagination->limit($limit);
			$this->gpagination->target(site_url('dashboard/sales?'));

			$this->_data['pagination'] = $this->gpagination->getOutput();

			$lists = array();
			$rate = $this->config->item('rate');
			$res = $this->db->query('SELECT p.payed,p.ctime,b.title,b.text_price,b.audio_price FROM yos_payed_pages p LEFT JOIN yos_book b ON p.bid=b.id '.$where)->result_array();
			foreach($res as $v) {
				$v['each'] = 0;
				if($v['text_price'] > 0) {
					$v['each'] = intval($v['payed']/$v['text_price']);
				}

				$v['customer_price'] = $v['text_price'];
				$v['proceeds'] = sprintf("%.2f", $rate*$v['text_price']);
				$v['total_customer_price'] = $v['payed'];
				$v['total_proceeds'] = sprintf("%.2f", $rate*$v['payed']);
				$lists[] = $v;
			}
			$this->_data['lists'] = $lists;
		}
		
		$this->load->view(THEME.'/header');
		$this->load->view(THEME.'/sales', $this->_data);
		$this->load->view(THEME.'/footer');
	}	

	public function payment() {	

		$limit = intval($this->input->get('limit')) ? 'LIMIT '.intval($this->input->get('limit')) : '';

		$this->_data['active'] = 'payment';
		$this->_data['slider_left'] = $this->load->view(THEME.'/slider_left', $this->_data, true);


		$this->_data['lists'] = $this->db->query('SELECT * FROM yos_payment_report WHERE uid='.$this->member['uid'].' ORDER BY reportdate DESC '.$limit)->result_array();

		$this->load->view(THEME.'/payment', $this->_data);	
	}

	public function promote() {
        $this->load->library('gpagination');
		$total_num = $this->db->query('SELECT * FROM yos_book WHERE status=1')->num_rows();
		$page = $this->input->get('page') > 1 ? $this->input->get('page') : '1';
		$limit = 25;
		$offset = ($page - 1) * $limit;

		$this->gpagination->currentPage($page);
		$this->gpagination->items($total_num);
		$this->gpagination->limit($limit);
		$this->gpagination->target(site_url('dashboard/promote'));

		$this->_data['active'] = 'promote';
		$this->_data['slider_left'] = $this->load->view(THEME.'/slider_left', $this->_data, true);
		$this->_data['pagination'] = $this->gpagination->getOutput();		
		$this->_data['lists'] = $this->base->get_data('book', array('status'=>1), '*', $limit, $offset, 'id DESC')->result_array();
		$this->load->view(THEME.'/promote', $this->_data);
	}

	public function banking() {
	
        $this->load->library('gpagination');
		$total_num = $this->db->query('SELECT * FROM yos_card WHERE uid='.$this->member['uid'].' AND is_credit=0')->num_rows();
		$page = $this->input->get('page') > 1 ? $this->input->get('page') : '1';
		$limit = 25;
		$offset = ($page - 1) * $limit;

		$this->gpagination->currentPage($page);
		$this->gpagination->items($total_num);
		$this->gpagination->limit($limit);
		$this->gpagination->target(site_url('dashboard/banking'));

		$this->_data['active'] = 'banking';
		$this->_data['slider_left'] = $this->load->view(THEME.'/slider_left', $this->_data, true);
		$this->_data['pagination'] = $this->gpagination->getOutput();		
		
		$lists = $this->base->get_data('card', array('uid'=>$this->member['uid'], 'is_credit'=>0), '*', $limit, $offset, 'id DESC')->result_array();

		foreach($lists as $v) {
			$v['cardinfo'] = unserialize(authcode($v['cardinfo']));
			$this->_data['lists'][] = $v;
		}

		$this->load->view(THEME.'/banking', $this->_data);		
	}

	//银行卡编辑
	public function bankingEdit() {
		if($_POST) {
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
				'cardinfo'		=> authcode(serialize($cardinfo), 'ENCODE'),
				'mtime' 		=> time(),
			);
			$this->base->update_data('card', array('id'=>$this->input->post('id'), 'uid'=>$this->member['uid']), $insert_data);
			$this->msg->showmessage(lang('success'), site_url('dashboard/banking'));	
		} else {
			$this->_data['active'] = 'banking';
			$this->_data['slider_left'] = $this->load->view(THEME.'/slider_left', $this->_data, true);
			$card = $this->base->get_data('card', array('uid'=>$this->member['uid'], 'is_credit'=>0, 'id'=>$this->input->get('id')))->row_array();
			$card['cardinfo'] = isset($card['cardinfo']) ? unserialize(authcode($card['cardinfo'])) : array();
			$this->_data['card'] = $card;

			$this->load->view(THEME.'/cardEdit', $this->_data);
		}
	}

	//修改信用卡信息
	public function creditEdit() {
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span class="err">', '</span>');

    	//验证表单规则
		$this->form_validation->set_rules('holder_first_name', 'first name', 'required|trim');
		$this->form_validation->set_rules('holder_last_name', 'last name', 'required|trim');
		$this->form_validation->set_rules('card_num', 'card num', 'required|trim');
		$this->form_validation->set_rules('exp_date', 'exp_date', 'required|trim');

		if ($this->form_validation->run() != FALSE) {
			$cardinfo = array(
				'holder_first_name'		=> $this->input->post('holder_first_name'),
				'holder_last_name'		=> $this->input->post('holder_last_name'),
				'card_num'				=> $this->input->post('card_num'),
				'exp_date'				=> $this->input->post('exp_date'),
				'billing_ad'			=> $this->input->post('billing_ad'),
				'scur_code'				=> $this->input->post('scur_code'),
				'state'					=> $this->input->post('state'),
				'country'				=> $this->input->post('country'),
			);

			$insert_data = array(
				'uid' 			=> $this->member['uid'],
				'cardinfo'		=> authcode(serialize($cardinfo), 'ENCODE'),
				'mtime' 		=> time(),
			);
			$this->base->update_data('card', array('id'=>$this->input->post('id'), 'uid'=>$this->member['uid']), $insert_data);
			$this->msg->showmessage(lang('success'), site_url('user/info'));					

		} else {
			$this->_data['active'] = 'userinfo';
			$this->_data['slider_left'] = $this->load->view(THEME.'/slider_left', $this->_data, true);
			$card = $this->base->get_data('card', array('uid'=>$this->member['uid'], 'id'=>$this->input->get('id')))->row_array();
			$card['cardinfo'] = isset($card['cardinfo']) ? unserialize(authcode($card['cardinfo'])) : array();
			$this->_data['card'] = $card;			
			$this->load->view(THEME.'/creditEdit', $this->_data);
		}
	}

	public function contact() {

		$this->_data['active'] = 'contact';
		$this->_data['slider_left'] = $this->load->view(THEME.'/slider_left', $this->_data, true);

		$this->load->view(THEME.'/header');
		$this->load->view(THEME.'/contact', $this->_data);
		$this->load->view(THEME.'/footer');
	}
}