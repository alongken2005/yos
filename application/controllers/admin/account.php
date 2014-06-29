<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* 作者管理
* @see Author
* @version 1.0.0 (12-12-13 下午7:25)
* @author ZhangHao
*/
class Account extends CI_Controller
{
	private $_data;

    public function __construct()
    {
		parent::__construct();

		$this->_data['thisClass'] = __CLASS__;
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
    * 管理
    */
    public function lists () {
    	$is_author = $this->input->get('is_author') ? $this->input->get('is_author') : '';
    	$where = array();
    	if($is_author == 1) {
    		$where = array('is_author'=>1);
    	} else if($is_author == 2) {
    		$where = array('is_author'=>0);
    	}
    	

		//分页配置
        $this->load->library('gpagination');
		$total_num = $this->base->get_data('account', $where)->num_rows();
		$page = $this->input->get('page') > 1 ? $this->input->get('page') : '1';
		
		$limit = 25;
		$offset = ($page - 1) * $limit;


		$this->gpagination->currentPage($page);
		$this->gpagination->items($total_num);
		$this->gpagination->limit($limit);
		$this->gpagination->target(site_url('admin/account/lists?is_author='.$is_author));

		$this->_data['pagination'] = $this->gpagination->getOutput();
		$this->_data['lists'] = $this->base->get_data('account', $where, '*', $limit, $offset, 'ctime DESC')->result_array();
        $this->load->view('admin/account_list', $this->_data);
    }

    /**
    * @deprecated 处理
    */
    public function op () {
    	//验证表单规则
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name', '名字', 'required|trim');
		$this->form_validation->set_error_delimiters('<span class="err">', '</span>');


		if ($this->form_validation->run() == FALSE) {
			if ($id = $this->input->get_post('id')) {
				$this->_data['row'] = $this->base->get_data('doctor', array('id' => $id))->row_array();
			}
			$this->load->view('admin/doctor_op', $this->_data);
		} else {

			$deal_data = array(
				'name'			=> $this->input->post('name'),
				'depart'		=> $this->input->post('depart'),
				'edu'			=> $this->input->post('edu'),
				'position'		=> $this->input->post('position'),
				'description'	=> $this->input->post('description'),
				'zhug'			=> $this->input->post('zhug'),
				'way'			=> $this->input->post('way'),
				'result'		=> $this->input->post('result'),
				'dis'			=> $this->input->post('dis'),
			);

			if($_FILES['userfile']['size'] > 0) {
				createFolder('data/uploads/pics/'.date('Y/m/'));
				$config['upload_path']		= 'data/uploads/pics/'.date('Y/m/');
				$config['allowed_types']	= 'gif|jpg|png';
				$config['max_size']			= '5000';
				$config['max_width']		= '3000';
				$config['max_height']		= '3000';
				$config['encrypt_name']		= TRUE;
				$config['overwrite']		= FALSE;

				$this->load->library('upload', $config);

				if(!$this->upload->do_upload()) {
					$this->_data['upload_err'] = $this->upload->display_errors();
					$this->load->view('admin/doctor_op', $this->_data);
				}
				$upload_data = $this->upload->data();
				$deal_data['header'] = date('Y/m/').$upload_data['file_name'];
			}			


			if ($id = $this->input->get('id')) {
				if ($this->base->update_data('doctor', array('id' => $id), $deal_data)) {
					$this->msg->showmessage('更新成功', site_url('admin/doctor/lists'));
				} else {
					$this->msg->showmessage('更新失败', site_url('admin/doctor/op?cid='.$id));
				}
			} else {
				if ($this->base->insert_data('doctor', $deal_data)) {
					$this->msg->showmessage('添加成功', site_url('admin/doctor/lists'));
				} else {
					$this->msg->showmessage('添加失败', site_url('admin/doctor/op'));
				}
			}
		}
    }

    //更改书本状态
    public function status() {
    	$ids = $this->input->post('bookId');
    	$status = $this->input->post('status');

    	if(!$ids) exit('no');
    	$str_ids = implode(',', $ids);
    	//exit("UPDATE yos_book SET status=".$status." WHERE id IN(".$str_ids.")");
    	if($this->db->query("UPDATE yos_book SET status=".$status." WHERE id IN(".$str_ids.")")) {
    		exit('ok');
    	} else {
    		exit('no');
    	}
    }

    //删除
    public function del () {
        $id = intval($this->input->get('id'));
        if($id && $this->base->del_data('author', array('id' => $id))) {
        	exit('ok');
        } else {
        	exit('no');
        }
    }


    public function audioList() {
    	$id = intval($this->input->get('id'));

    	$this->_data['lists'] = $this->base->get_data('book_audio', array('bid'=>$id), '*', 5, 0, 'id DESC')->result_array();

    	$this->load->view('admin/audio_list', $this->_data);
    }

    public function audiodown() {
    	$this->load->helper('download');
    	$audio = $this->base->get_data('book_audio', array('id'=>$_GET['id']))->row_array();

    	$audioUrl = 'data/audio/'.$audio['fileurl'];
    	$data = file_get_contents($audioUrl);

    	force_download($audio['filename'], $data);
    }
}