<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* 教案课件管理
* @version 1.0.0 (12-12-13 下午7:25)
* @author ZhangHao
*/
class Lake_attach extends CI_Controller
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
		$total_num = $this->base->get_data('attach', array('kind'=>'lake'))->num_rows();
		$page = $this->input->get('page') > 1 ? $this->input->get('page') : '1';
		$limit = 25;
		$offset = ($page - 1) * $limit;

		$this->gpagination->currentPage($page);
		$this->gpagination->items($total_num);
		$this->gpagination->limit($limit);
		$this->gpagination->target(site_url('admin/stuff/attach_lists'));

		$this->_data['pagination'] = $this->gpagination->getOutput();
		$this->_data['lists'] = $this->base->get_data('attach', array('kind'=>'lake'), '*', $limit, $offset, 'sort ASC, ctime DESC')->result_array();
        $this->load->view('admin/lake_attach_list', $this->_data);
	}

	/**
	 * 教案附件内容添加
	 */
	public function op() {
    	//验证表单规则
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', '标题', 'required|trim');
		$this->form_validation->set_rules('filetype', '课件类型', 'required|trim');
		$this->form_validation->set_error_delimiters('<span class="err">', '</span>');
		$this->load->helper('file');

		if ($this->form_validation->run() == FALSE) {
			$this->_data['stufflist'] = $this->base->get_data('subject')->result_array();

			if ($id = $this->input->get('id')) {
				$this->_data['row'] = $this->base->get_data('attach', array('id'=>$id))->row_array();
			}
			$this->load->view('admin/lake_attach_op', $this->_data);
		} else {
			$id = $this->input->get('id');

			$deal_data = array(
				'relaid'	=> $this->input->post('relaid'),
				'title'		=> $this->input->post('title'),
				'kind'		=> 'lake',
				'filetype'	=> $this->input->post('filetype'),
				'sort'		=> $this->input->post('sort'),
				'ctime'		=> time(),
			);

			$this->load->library('upload');

			if($_FILES['cover']['size'] > 0) {
				$dirname = './data/uploads/pics/'.date('Y/m/');
				createFolder($dirname);
				$config = array(
					'upload_path'	=> $dirname,
					'allowed_types'	=> 'gif|jpg|png',
					'max_size'		=> 5000,
					'max_width'		=> 3000,
					'max_height'	=> 3000,
					'encrypt_name'	=> TRUE,
					'overwrite'		=> FALSE
				);

				$this->upload->initialize($config);

				if(!$this->upload->do_upload('cover')) {
					$this->_data['upload_err'] = $this->upload->display_errors();
					$this->load->view('admin/lake_attach_op', $this->_data);
				}
				$upload_data = $this->upload->data();

				$config2 = array(
					'source_image'		=> $upload_data['full_path'],
					'maintain_ratio'	=> TRUE,
					'width'				=> 300,
					'height'			=> 180,
				);

				$this->load->library('image_lib', $config2);
				$this->image_lib->resize();

				$deal_data['cover'] = date('Y/m/').$upload_data['file_name'];
			}

			$filetype = $this->input->post('filetype');
			if($filetype == 'doc' && $_FILES['doc']['size'] > 0) {
				$dirname = './data/uploads/attach/'.date('Y/m/');
				createFolder($dirname);
				$config = array(
					'upload_path'	=> $dirname,
					'allowed_types'	=> 'doc|docx|xls|txt|rar|jpg|png|gif|pdf|ppt|xlsx',
					'max_size'		=> 5000,
					'encrypt_name'	=> TRUE,
					'overwrite'		=> FALSE
				);

				$this->upload->initialize($config);
				if(!$this->upload->do_upload('doc')) {
					$this->_data['upload_err'] = $this->upload->display_errors();
					$this->load->view('admin/lake_attach_op', $this->_data);
				}

				$upload_data = $this->upload->data();

				$deal_data['filename'] = date('Y/m/').$upload_data['file_name'];
				$deal_data['realname'] = $upload_data['orig_name'];
				$deal_data['filesize'] = $upload_data['file_size'];
			} elseif($filetype == 'online') {
				$deal_data['filename'] = $this->input->post('online');
			} elseif($filetype == 'local') {
				$deal_data['filename'] = $this->input->post('local');
			}

			if($id) {
				$this->base->update_data('attach', array('id' => $id), $deal_data);
			} else {
				$id = $this->base->insert_data('attach', $deal_data);
			}

			$this->msg->showmessage('添加成功', site_url('admin/lake_attach/lists'));
		}
	}

    /**
    * 删除
    */
    public function del () {
        $id = intval($this->input->get('id'));
        if($id && $this->base->del_data('attach', array('id' => $id))) {
        	exit('ok');
        } else {
        	exit('no');
        }
    }
}