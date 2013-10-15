<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* 单本添加
* @see Content
* @version 1.0.0 (13-4-15 下午9:36)
* @author ZhangHao
*/
class SingleOrder extends CI_Controller
{
	private $_data;

    public function __construct()
    {
		parent::__construct();
		$this->load->model('base_mdl', 'base');
		$this->permission->power_check();
		//$this->output->enable_profiler(TRUE);
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
		$total_num = $this->base->get_data('single_order')->num_rows();
		$page = $this->input->get('page') > 1 ? $this->input->get('page') : '1';
		$limit = 25;
		$offset = ($page - 1) * $limit;

		$this->gpagination->currentPage($page);
		$this->gpagination->items($total_num);
		$this->gpagination->limit($limit);
		$this->gpagination->target(site_url('admin/singleOrder/lists'));

		$this->_data['pagination'] = $this->gpagination->getOutput();
		$this->_data['lists'] = $this->base->get_data('single_order', array(), '*', $limit, $offset, 'id DESC')->result_array();
        $this->load->view('admin/singleOrder_list', $this->_data);
    }

    /**
    * @deprecated 文章处理
    */
    public function op () {
    	//验证表单规则
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', '标题', 'required|trim');
		$this->form_validation->set_error_delimiters('<span class="err">', '</span>');

		if ($this->form_validation->run() == FALSE) {
			$this->load->helper('file');
			if ($id = $this->input->get('id')) {
				$this->_data['row'] = $this->base->get_data('single_order', array('id'=>$id))->row_array();
			}
			$this->load->view('admin/singleOrder_op', $this->_data);
		} else {
			$id = $this->input->get('id') ? (int)$this->input->get('id') : 0;
			$timestamp = time();

			$deal_data = array(
				'title'		=> $this->input->post('title'),
				'author1'	=> $this->input->post('author1'),
				'author2'	=> $this->input->post('author2'),
				'author3'	=> $this->input->post('author3'),
				'intro'		=> $this->input->post('intro'),
				'sort'		=> $this->input->post('sort'),
			);

			if($id) {
				$this->base->update_data('single', array('id' => $id), $deal_data);
			} else {
				$id = $this->base->insert_data('single', $deal_data);
			}

			$this->msg->showmessage('添加成功', site_url('admin/single/lists'));
		}
    }

    /**
    * @deprecated 文章删除
    */
    public function del () {
        $id = intval($this->input->get('id'));
        if($id && $this->base->del_data('single', array('id' => $id))) {
        	exit('ok');
        } else {
        	exit('no');
        }
    }

	public function file_del() {
		$id = $this->input->get('id');
		$type = $this->input->get('type');
		$pro = $this->base->get_data('stuff', array('id'=>$id))->row_array();
		if($type == 'img') {
			if(unlink('./data/uploads/stuff/'.$pro['filepic'])) {
				$this->base->update_data('stuff', array('id'=>$id), array('filepic'=>''));
				echo 'ok';
			} else {
				echo 'no';
			}
		} elseif($type == 'video') {
			if(unlink('./data/uploads/stuff/'.$pro['filename'])) {
				$this->base->update_data('stuff', array('id'=>$id), array('filename'=>''));
				echo 'ok';
			} else {
				echo 'no';
			}
		}
	}
}