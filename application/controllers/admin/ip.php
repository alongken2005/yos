<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* IP访问管理
* @see Ip
* @version 1.0.0 (12-12-13 下午7:25)
* @author ZhangHao
*/
class Ip extends CI_Controller
{
	private $_data;

    public function __construct() {
		parent::__construct();
		$this->load->model('base_mdl', 'base');
		$this->permission->power_check();
    }

    /**
    * @deprecated 默认方法
    */
    public function index () {
        self::lists();
    }

	/**
	 * 教案附件内容列表
	 */
	public function lists() {
		//分页配置
        $this->load->library('gpagination');
		$total_num = $this->base->get_data('access_ip')->num_rows();
		$page = $this->input->get('page') > 1 ? $this->input->get('page') : '1';
		$limit = 25;
		$offset = ($page - 1) * $limit;

		$this->gpagination->currentPage($page);
		$this->gpagination->items($total_num);
		$this->gpagination->limit($limit);
		$this->gpagination->target(site_url('admin/ip/lists'));

		$this->_data['pagination'] = $this->gpagination->getOutput();
		$this->_data['lists'] = $this->base->get_data('access_ip', array(), '*', $limit, $offset, 'ip DESC')->result_array();
        $this->load->view('admin/ip_list', $this->_data);
	}

	/**
	 * 教案附件内容添加
	 */
	public function op() {
    	//验证表单规则
		$this->load->library('form_validation');
		$this->form_validation->set_rules('ip', 'ip', 'required|trim');
		$this->form_validation->set_rules('date_expire', '过期时间', 'required|trim');
		$this->form_validation->set_error_delimiters('<span class="err">', '</span>');

		if ($this->form_validation->run() == FALSE) {
			if ($id = $this->input->get('id')) {
				$this->_data['content'] = $this->base->get_data('access_ip', array('id'=>$id))->row_array();
			}
			$this->load->view('admin/ip_op', $this->_data);
		} else {
			$id = $this->input->get('id');

			$deal_data = array(
				'ip'			=> $this->input->post('ip'),
				'date_expire'	=> strtotime($this->input->post('date_expire')),
			);

			if($id) {
				$this->base->update_data('access_ip', array('id' => $id), $deal_data);
			} else {
				$id = $this->base->insert_data('access_ip', $deal_data);
			}

			$this->msg->showmessage('添加成功', site_url('admin/ip/lists'));
		}
	}

    /**
    * 删除
    */
    public function del () {
        $id = intval($this->input->get('id'));
        if($id && $this->base->del_data('access_ip', array('id' => $id))) {
        	exit('ok');
        } else {
        	exit('no');
        }
    }
}