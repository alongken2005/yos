<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* @desc 文字管理
* @see Content
* @version 1.0.0 (Tue Feb 21 08:17:46 GMT 2012)
* @author ZhangHao
*/
class report extends CI_Controller
{
	private $_data;

    public function __construct()
    {
		parent::__construct();

		$this->_data['thisClass'] = __CLASS__;
		$this->load->model('base_mdl', 'base');
		$this->config->load('common', TRUE);
		$this->permission->power_check();
		$this->_data['wayType'] = $this->config->item('wayType', 'common');
    }

    /**
    * @deprecated 默认方法
    */
    public function index () {
        self::lists();
    }

    /**
    * @deprecated 文章管理
    */
    public function lists () {
    	$year = $this->input->get('year') ? $this->input->get('year') : date('Y');
    	$month = $this->input->get('month') ? $this->input->get('month') : date('m');
    	$this->_data['year'] = $year;
    	$this->_data['month'] = $month;
    	//$year = 2014;
    	//$month = 2;

    	$stime = strtotime($year.'-'.$month);
      	if($month == 12) {
    		$year += 1;
    		$month = 1;
    	} else {
    		$month += 1;
    	}  	
    	$etime = strtotime($year.'-'.$month);

    	$report = array();
    	$reportRes = $this->db->query('SELECT * FROM yos_payment_report WHERE payeddate='.$stime)->result_array();
    	foreach($reportRes as $row) {
    		$report[$row['uid'].'_'.date('Ym', $row['payeddate'])] = $row['payment'];
    	}

		//分页配置
        $this->load->library('gpagination');

		//debug('SELECT COUNT(p.payed) payed,b.author,b.uid FROM yos_payed_pages p LEFT JOIN yos_book b ON p.bid=b.id WHERE p.ctime > '.$stime.' AND p.ctime <'.$etime.' GROUP BY b.uid');
		$total_num = $this->db->query('SELECT b.uid FROM yos_payed_pages p LEFT JOIN yos_book b ON p.bid=b.id WHERE p.ctime>'.$stime.' AND p.ctime <'.$etime.' GROUP BY b.uid')->num_rows();
		$this->_data['lists'] = $this->db->query('SELECT COUNT(p.payed) payed,b.author,b.uid, p.ctime FROM yos_payed_pages p LEFT JOIN yos_book b ON p.bid=b.id WHERE p.ctime>'.$stime.' AND p.ctime <'.$etime.' GROUP BY b.uid')->result_array();

		$page = $this->input->get('page') > 1 ? $this->input->get('page') : '1';
		$limit = 25;
		$offset = ($page - 1) * $limit;

		$this->gpagination->currentPage($page);
		$this->gpagination->items($total_num);
		$this->gpagination->limit($limit);
		$this->gpagination->target(site_url('admin/report/lists?year='.$year.'&month='.$month));

		$this->_data['pagination'] = $this->gpagination->getOutput();
		$this->_data['stime'] = $stime;
		$this->_data['report'] = $report;
		
        $this->load->view('admin/report_list', $this->_data);
    }

    /**
    * @deprecated 文章处理
    */
    public function op () {

		$uid = $this->input->get('uid');
		$payed = $this->input->get('payed');
		$time = $this->input->get('time');


		$deal_data = array(
			'uid' 			=> $uid,
			'payment' 		=> $payed,
			'earned' 		=> $payed,
			'preTax' 		=> $payed,
			'postTax' 		=> $payed,
			'reportdate' 	=> time(),
			'payeddate' 	=> $time,
			'currency' 		=> 'USD',
		);
		$this->base->insert_data('payment_report', $deal_data);
		$this->msg->showmessage('结算成功', site_url('admin/report/lists'));
    }

    /**
    * 文章删除
    */
    public function del () {
        $id = intval($this->input->get('id'));
        if($id && $this->base->del_data('reply', array('id' => $id))) {
        	exit('ok');
        } else {
        	exit('no');
        }
    }
}