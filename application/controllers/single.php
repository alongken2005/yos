<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 千岛湖
 * @datetime (12-10-8 下午3:03)
 * @author ZhangHao
 */

class Single extends CI_Controller {

	private $_data;
	private $alipay_config;

	public function __construct() {
		parent::__construct();
		$this->load->model('base_mdl', 'base');

		$this->alipay_config = array(
			'partner'		=> '2088701566065495',
			'key'			=> '1kntmksinlraz5xd1bu5g69b0q03xkz2',
			'seller_email'	=> 'tuangou@childroad.com',
			'sign_type'		=> 'MD5',
			'input_charset'	=> 'utf-8',
			'transport'		=> 'http',
		);
	}

	/**
	 * 默认方法
	 */
	public function index() {
		$this->main();
	}

	public function main() {
		$this->_data['lists'] = $this->base->get_data('single', array(), '*', 0, 0, 'sort DESC')->result_array();
		$this->load->view(THEME.'/single', $this->_data);
	}

	//购买结算
	public function check() {
		$id = $this->input->get('id');

		$this->permission->login_check(site_url('single/check?id='.$id));
		$uid = $this->session->userdata('uid');
		$this->_data['address'] = $this->base->get_data('address', array('uid'=>$uid), '*', 0, 0, 'state DESC, id DESC')->result_array();

		$this->_data['suit_id'] = $id;
		$this->_data['province'] = $this->base->get_data('provinces')->result_array();
		$this->_data['suit'] = $this->base->get_data('single_suit', array('id'=>$id))->row_array();
		$this->load->view(THEME.'/single_check', $this->_data);
	}

	//付款
	public function checkout() {
		$suit_id = $this->input->post('suit_id');
		$this->permission->login_check(site_url('single/check?id='.$suit_id));
		$uid	= $this->session->userdata('uid');
		$username	= $this->session->userdata('username');
		$addid	= (int)$this->input->post('addid');
		$amount = (int)$this->input->post('amount');
		$suit	= $this->base->get_data('single_suit', array('id'=>$suit_id))->row_array();

		//计算总价
		$price = $suit['price']*$amount;

		//添加收货地址
		if($addid == -1) {
			$receiver	= $this->input->post('receiver');
			$area		= $this->input->post('province').' '.$this->input->post('city').' '.$this->input->post('area');
			$address	= $this->input->post('address');
			$tel		= $this->input->post('tel');

			$zip = $this->db->query("SELECT z.zip FROM ab_areas a LEFT JOIN ab_zipcode z ON a.areaid=z.areaid WHERE a.area='".$this->input->post('area')."' LIMIT 1")->row_array();

			$postcode = $zip['zip'];
			$this->base->update_data('address', array('uid'=>$uid), array('state'=>0));
			$insert_data = array(
				'uid'		=> $uid,
				'receiver'	=> $receiver,
				'area'		=> $area,
				'address'	=> $address,
				'postcode'	=> $postcode,
				'tel'		=> $tel,
				'state'		=> 1,
			);
			$this->base->insert_data('address', $insert_data);
		} elseif($addid > 0) {
			$add = $this->base->get_data('address', array('id'=>$addid))->row_array();

			$receiver	= $add['receiver'];
			$area		= $add['area'];
			$address	= $add['address'];
			$postcode	= $add['postcode'];
			$tel		= $add['tel'];
		}

		//添加订单
		$order_data = array(
			'uid'		=> $uid,
			'username'	=> $username,
			'price'		=> $price,
			'amount'	=> $amount,
			'receiver'	=> $receiver,
			'address'	=> $area.' '.$address,
			'postcode'	=> $postcode,
			'tel'		=> $tel,
			'state'		=> 0,
			'ctime'		=> time(),
		);

		if(!$orderid = $this->base->insert_data('single_order', $order_data)) {
			exit('订单生成失败');
		}

		require APPPATH.'libraries/alipay/alipay_submit.class.php';

		//构造要请求的参数数组，无需改动
		$parameter = array(
			"service"			=> "create_direct_pay_by_user",
			"partner"			=> $this->alipay_config['partner'],
			"payment_type"		=> '1',
			"notify_url"		=> site_url('single/do_notify'),
			"return_url"		=> site_url('single/do_return'),
			"seller_email"		=> $this->alipay_config['seller_email'],	//支付宝帐户,
			"out_trade_no"		=> $orderid,								//商户订单号
			"subject"			=> 'chlidroad',									//订单名称
			"total_fee"			=> $price,									//必填,付款金额
			//"total_fee"			=> 0.01,									//必填,付款金额
			"body"				=> 'chlidroad',								//必填,订单描述
			"show_url"			=> site_url('single'),						//商品展示地址
			"_input_charset "	=> $this->alipay_config['input_charset']
		);

		//建立请求
		$alipaySubmit = new AlipaySubmit($this->alipay_config);
		$html_text = $alipaySubmit->buildRequestForm($parameter, "get", "Loading...");
		echo '正在跳往支付宝,请等待 '.$html_text;
	}

	//支付后续处理
	public function do_return() {
		write_log('do_return', 'pay', 'alipay');
		$this->_data['suit'] = $this->base->get_data('single_suit', array('id'=>1))->row_array();

		require APPPATH.'libraries/alipay/alipay_notify.class.php';
        $alipayNotify = new AlipayNotify($this->alipay_config);
        $verify_result = $alipayNotify->verifyReturn();

//		if(!$verify_result) {
//			exit('非法的交易');
//		}

        $id			= (int)$_GET['out_trade_no'];		//商户订单号
        $orderid	= $_GET['trade_no'];				//支付宝交易号
		$price		= $_GET['total_fee'];				//支付总额
        $status		= $_GET['trade_status'];			//交易状态
		$sorder		= $this->base->get_data('single_order', array('id'=>$id))->row_array();
		$this->_data['sorder'] = $sorder;

        if($status == 'TRADE_FINISHED' || $status == 'TRADE_SUCCESS') {

			if($price >= $sorder['price']) {
				$update_data = array(
					'orderid'	=> $orderid,
					'state'		=> 1,
					'ptime'		=> time()
				);
				$this->base->update_data('single_order', array('id'=>$id), $update_data);
				$this->db->query("UPDATE ab_single_suit SET total=total-".$sorder['amount']." WHERE id=1");
			}
			$this->_data['buy_state'] = 'ok';
			$this->load->view(THEME.'/single_return', $this->_data);
        } else {
			$update_data = array(
				'orderid'	=> $orderid,
				'state'		=> 2,
				'ptime'		=> time()
			);
			$this->base->update_data('single_order', array('id'=>$id), $update_data);
			$this->_data['buy_state'] = 'no';
			$this->load->view(THEME.'/single_return', $this->_data);
        }
	}

	public function do_notify() {
		write_log('do_notify', 'pay', 'alipay');
	}

	public function set_default() {

	}

	public function getcity() {
		$id = $this->input->get('id');
		$city = $this->base->get_data('cities', array('provinceid'=>$id), 'cityid, city')->result_array();
		$option = '<option value="0">请选择市</option>';
		foreach($city as $v) {
			$option .= '<option value="'.$v['cityid'].'">'.$v['city'].'</option>';
		}
		exit($option);
	}

	public function getarea() {
		$id = $this->input->get('id');
		$city = $this->base->get_data('areas', array('cityid'=>$id), 'areaid, area')->result_array();
		$option = '<option value="0">请选择区/县</option>';
		foreach($city as $v) {
			$option .= '<option value="'.$v['areaid'].'">'.$v['area'].'</option>';
		}
		exit($option);
	}
}