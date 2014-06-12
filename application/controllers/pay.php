<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @deprecated 用户
 * @version 1.0.0 12-10-22 下午9:31
 * @author 张浩
 */

class Pay extends MY_Controller {
	private $_data;

	public function __construct() {
		parent::__construct();
		$this->load->model('base_mdl', 'base');

		if(!$this->member) {
			$this->msg->showmessage(lang('login_first'), site_url('user/login'));
		}		
	}

	public function index() {

		if(!$this->member) {
			$this->msg->showmessage(lang('login_first'), site_url('user/login'));
		}

		$this->_data['step'] = $step = $this->input->get_post('step') ? $this->input->get_post('step') : 'payTools';
		$this->_data['active'] = 'pay';

		if($step == 'selectCard') {
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<span class="err">', '</span>');

			$whichCard = $this->input->post('whichCard');
			$this->_data['selectMoney'] = $selectMoney = floatval($this->input->get_post('selectMoney'));

			$this->form_validation->set_rules('whichCard', 'whichCard', 'required|trim');

			if($whichCard == 'new') {

		    	//验证表单规则
				$this->form_validation->set_rules('holder_first_name', 'first name', 'required|trim');
				$this->form_validation->set_rules('holder_last_name', 'last name', 'required|trim');
				$this->form_validation->set_rules('card_num', 'card num', 'required|trim');
				$this->form_validation->set_rules('exp_date', 'exp_date', 'required|trim');
			}

			if ($this->form_validation->run() != FALSE) {

				if($whichCard == 'new') {
					$save = intval($this->input->post('save'));

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

					if($save == 1) {
						$insert_data = array(
							'uid' 			=> $this->member['uid'],
							'is_credit'		=> 1,
							'cardinfo'		=> authcode(serialize($cardinfo), 'ENCODE'),
							'ctime' 		=> time(),
							'mtime' 		=> time(),
						);
						$this->base->insert_data('card', $insert_data);						
					}					
				} else {
					$cardinfo = $this->base->get_data('card', array('id'=>intval($whichCard)))->row_array();
					$cardinfo = unserialize(authcode($cardinfo['cardinfo']));					
				}

				list($st, $et) = explode('.', microtime(true));
				$orderID = date('Ymd').substr($st, 2).substr($et, 0, 2);
				//$orderID = date('YmdHis').uniqid();
				$orderArr = array(
					'uid'		=> $this->member['uid'],
					'orderID'	=> $orderID,
					'price'		=> $selectMoney,
					'ctime'		=> date('Y-m-d H:i:s'),
					'cardnum'	=> substr($cardinfo['card_num'], -4),
				);

				$this->base->insert_data('pay_order', $orderArr);
				
				$ret = $this->charge($selectMoney, $cardinfo);
				if($ret[0] == 1) {
					$this->db->query("UPDATE yos_account SET deposit = deposit+".$selectMoney." WHERE uid=".$this->member['uid']);
					$this->db->query("UPDATE yos_pay_order SET status = 1, transactionID='".$ret[6]."', paytime='".date('Y-m-d H:i:s')."' WHERE uid=".$this->member['uid']." AND orderID='".$orderID."'");
					redirect(site_url('pay/paysucc?orderID='.$orderID));
				} else {
					$this->msg->showmessage(lang('failed'), site_url('pay'));
				}

			} 

			$cardList = $this->base->get_data('card', array('uid'=>$this->member['uid'], 'is_credit'=>1))->result_array();
			foreach($cardList as &$value) {
				$value['cardinfo'] = unserialize(authcode($value['cardinfo']));
			}

			$this->_data['card'] = $cardList;
		}


		$this->_data['userInfo'] = $this->getUserInfo($this->member['uid']);
		$this->_data['slider_left'] = $this->load->view(THEME.'/slider_left', $this->_data, true);
		$this->load->view(THEME.'/header');
		$this->load->view(THEME.'/pay', $this->_data);
		$this->load->view(THEME.'/footer');
	}


	public function charge($amount, $cardinfo) {
		$post_url = "https://secure.authorize.net/gateway/transact.dll";

		$post_values = array(
			
			"x_login"			=> "75B4XakF",
			"x_tran_key"		=> "9284F7j8BgGMYy25",

			"x_version"			=> "3.1",
			"x_delim_data"		=> "TRUE",
			"x_delim_char"		=> "|",
			"x_relay_response"	=> "FALSE",

			"x_type"			=> "AUTH_CAPTURE",
			"x_method"			=> "CC",
			"x_card_num"		=> $cardinfo['card_num'],
			"x_exp_date"		=> $cardinfo['exp_date'],

			"x_amount"			=> $amount,
			"x_description"		=> "Sample Transaction",

			"x_first_name"		=> isset($cardinfo['holder_first_name']) ? $cardinfo['holder_first_name'] : '',
			"x_last_name"		=> isset($cardinfo['holder_last_name']) ? $cardinfo['holder_last_name'] : '',
			"x_address"			=> isset($cardinfo['billing_ad']) ? $cardinfo['billing_ad'] : '',
			"x_state"			=> isset($cardinfo['state']) ? $cardinfo['state'] : '',
			"x_zip"				=> "98004"
		);

		$post_string = "";
		foreach( $post_values as $key => $value ) { 
			$post_string .= "$key=" . urlencode( $value ) . "&"; 
		}
		$post_string = rtrim( $post_string, "& " );

		$request = curl_init($post_url); 
		curl_setopt($request, CURLOPT_HEADER, 0);
		curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($request, CURLOPT_POSTFIELDS, $post_string);
		curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE);
		$post_response = curl_exec($request);
		curl_close ($request);

		$response_array = explode($post_values["x_delim_char"],$post_response);
		write_log(debug($response_array,0,1));
		return $response_array;
	}

	public function paysucc() {
		$orderID = $this->input->get('orderID');
		$this->_data['active'] = 'pay';

		$this->_data['order'] = $this->base->get_data('pay_order', array('orderID'=>$orderID))->row_array();

		$this->_data['userInfo'] = $this->getUserInfo($this->member['uid']);
		$this->_data['slider_left'] = $this->load->view(THEME.'/slider_left', $this->_data, true);
		$this->load->view(THEME.'/header');
		$this->load->view(THEME.'/paysucc', $this->_data);
		$this->load->view(THEME.'/footer');		
	}




}