<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* 文件通知
* @see Content
* @version 1.0.0 (Tue Feb 21 08:17:46 GMT 2012)
* @author ZhangHao
*/
class Lake_intro extends CI_Controller
{
	private $_data;

    public function __construct() {
		parent::__construct();

		$this->load->model('base_mdl', 'base');
		$this->config->load('common', TRUE);
		$this->permission->power_check();
    }

    /**
    * 默认方法
    */
    public function index () {
        self::lists();
    }

    /**
    * 文章管理
    */
    public function lists () {
		//分页配置
        $this->load->library('gpagination');
		$total_num = $this->base->get_data('notice', array('area'=>'intro'))->num_rows();
		$page = $this->input->get('page') > 1 ? $this->input->get('page') : '1';
		$limit = 25;
		$offset = ($page - 1) * $limit;

		$this->gpagination->currentPage($page);
		$this->gpagination->items($total_num);
		$this->gpagination->limit($limit);
		$this->gpagination->target(site_url('admin/lake_intro/lists'));

		$this->_data['pagination'] = $this->gpagination->getOutput();
		$this->_data['lists'] = $this->base->get_data('notice', array('area'=>'intro'), '*', $limit, $offset, 'sort DESC, ctime DESC')->result_array();
        $this->load->view('admin/lake_intro_list', $this->_data);
    }

    /**
    * 文章处理
    */
    public function op () {
    	//验证表单规则
//		$this->load->library('form_validation');
//		$this->form_validation->set_rules('content', '内容', 'required|trim');
//		$this->form_validation->set_error_delimiters('<span class="err">', '</span>');

		if ($_POST) {
			$deal_data = array(
				'content'		=> $this->input->post('content'),
			);

			if ($id = $this->input->get('id')) {
				$this->base->update_data('notice', array('id' => $id), $deal_data);
			} else {
				$deal_data['ctime']	= time();
				$this->base->insert_data('notice', $deal_data);
			}
			$this->msg->showmessage('操作完成', site_url('admin/lake_intro/lists'));
		} else {
			if ($id = $this->input->get_post('id')) {
				$this->_data['content'] = $this->base->get_data('notice', array('id' => $id))->row_array();
			}
			$this->load->view('admin/lake_intro_op', $this->_data);
		}
    }

    /**
    * 文章删除
    */
    public function del () {
        $id = intval($this->input->get('id'));
        if($id && $this->base->del_data('notice', array('id' => $id))) {
        	exit('ok');
        } else {
        	exit('no');
        }
    }
}