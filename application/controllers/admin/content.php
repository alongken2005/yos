<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* @deprecated 文字管理
* @see Content
* @version 1.0.0 (Tue Feb 21 08:17:46 GMT 2012)
* @author ZhangHao
*/
class Content extends CI_Controller
{
	private $_data;

    public function __construct()
    {
		parent::__construct();

		$this->_data['thisClass'] = __CLASS__;
		$this->load->model('base_mdl', 'base');
		$this->config->load('common', TRUE);
		$this->permission->power_check();
		$this->_data['contType'] = $this->config->item('contType', 'common');
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
		$tid = $this->_data['tid'] = (int)$this->input->get('tid');

		$where = array();
		if($tid) {
			$where['tid'] = $tid;
		} else {
			$where['tid !='] = 2;
		}
		//分页配置
        $this->load->library('gpagination');
		$total_num = $this->base->get_data('content', $where)->num_rows();
		$page = $this->input->get('page') > 1 ? $this->input->get('page') : '1';
		$limit = 25;
		$offset = ($page - 1) * $limit;

		$this->gpagination->currentPage($page);
		$this->gpagination->items($total_num);
		$this->gpagination->limit($limit);
		$this->gpagination->target(site_url('admin/content/content?tid='.$tid));

		$this->_data['pagination'] = $this->gpagination->getOutput();
		$this->_data['lists'] = $this->base->get_data('content', $where, '*', $limit, $offset, 'sort DESC, cid DESC')->result_array();
        $this->load->view('admin/content_list', $this->_data);
    }

    /**
    * @deprecated 文章处理
    */
    public function op () {
    	//验证表单规则
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', '标题', 'required|trim');
		$this->form_validation->set_rules('content', '内容', 'required|trim');
		$this->form_validation->set_error_delimiters('<span class="err">', '</span>');

		if ($this->form_validation->run() == FALSE) {
			if ($id = $this->input->get_post('cid')) {
				$this->_data['content'] = $this->base->get_data('content', array('cid' => $id))->row_array();
			}
			$this->load->view('admin/content_op', $this->_data);
		} else {
			$deal_data = array(
				'content'		=> $this->input->post('content'),
				'description'	=> mb_substr(trim(strip_tags($this->input->post('content'))), 0, 180, 'utf-8'),
				'title'			=> $this->input->post('title'),
				'tid'			=> $this->input->post('tid'),
				'ctime'			=> strtotime($this->input->post('ctime')),
				'sort'			=> $this->input->post('sort')
			);

			if ($id = $this->input->get('cid')) {
				if ($this->base->update_data('content', array('cid' => $id), $deal_data)) {
					$this->msg->showmessage('更新成功', site_url('admin/content/op?cid='.$id));
				} else {
					$this->msg->showmessage('更新失败', site_url('admin/content/op?cid='.$id));
				}
			} else {
				if ($this->base->insert_data('content', $deal_data)) {
					$this->msg->showmessage('添加成功', site_url('admin/content/lists?tid='.$this->input->post('tid')));
				} else {
					$this->msg->showmessage('添加失败', site_url('admin/content/op?tid='.$this->input->post('tid')));
				}
			}
		}
    }

    /**
    * @deprecated 文章删除
    */
    public function del () {
        $id = intval($this->input->get('cid'));
        if($id && $this->base->del_data('content', array('cid' => $id))) {
        	exit('ok');
        } else {
        	exit('no');
        }
    }
}