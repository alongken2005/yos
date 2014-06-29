<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* @desc 文字管理
* @see Content
* @version 1.0.0 (Tue Feb 21 08:17:46 GMT 2012)
* @author ZhangHao
*/
class Msgs extends CI_Controller
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

		//分页配置
        $this->load->library('gpagination');
		$total_num = $this->base->get_data('msg')->num_rows();
		$page = $this->input->get('page') > 1 ? $this->input->get('page') : '1';
		$limit = 25;
		$offset = ($page - 1) * $limit;

		$this->gpagination->currentPage($page);
		$this->gpagination->items($total_num);
		$this->gpagination->limit($limit);
		$this->gpagination->target(site_url('admin/msg/lists'));

		$this->_data['pagination'] = $this->gpagination->getOutput();
		$this->_data['lists'] = $this->base->get_data('msg', array(), '*', $limit, $offset, 'ctime DESC')->result_array();
        $this->load->view('admin/msg_list', $this->_data);
    }

    /**
    * 文章处理
    */
    public function op () {
    	//验证表单规则
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', '标题', 'required|trim');
		$this->form_validation->set_rules('name', '称呼', 'required|trim');
		$this->form_validation->set_rules('content', '内容', 'required|trim');
		$this->form_validation->set_error_delimiters('<span class="err">', '</span>');
		$id = $this->input->get_post('id');

		if ($this->form_validation->run() == FALSE) {
			$this->_data['row'] = $this->base->get_data('msg', array('id' => $id))->row_array();
			$this->load->view('admin/msg_op', $this->_data);
		} else {

			$deal_data = array(
				'name' 		=> $this->input->post('name'),
				'title' 	=> $this->input->post('title'),
				'email' 	=> $this->input->post('email'),
				'content'	=> $this->input->post('content'),
			);
			$this->base->update_data('msg', array('id' => $id), $deal_data);
			$this->msg->showmessage('修改成功', site_url('admin/msgs/lists'));
				
		}
    }

    /**
    * 文章删除
    */
    public function msgDel () {
        $id = intval($this->input->get('id'));
        if($id && $this->base->del_data('msg', array('id' => $id))) {
        	$this->base->del_data('reply', array('mid' => $id));
        	exit('ok');
        } else {
        	exit('no');
        }
    }
}