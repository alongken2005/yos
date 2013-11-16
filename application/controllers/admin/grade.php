<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* 教案课件管理
* @see Stuff_grade
* @version 1.0.0 (12-12-13 下午7:25)
* @author ZhangHao
*/
class Grade extends CI_Controller
{
	private $_data;

    public function __construct() {
		parent::__construct();
		$this->_data['kinds'] = $this->config->item('subject_kinds');
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
		$total_num = $this->base->get_data('grade')->num_rows();
		$page = $this->input->get('page') > 1 ? $this->input->get('page') : '1';
		$limit = 25;
		$offset = ($page - 1) * $limit;

		$this->gpagination->currentPage($page);
		$this->gpagination->items($total_num);
		$this->gpagination->limit($limit);
		$this->gpagination->target(site_url('admin/grade/lists'));

		$this->_data['pagination'] = $this->gpagination->getOutput();
		$this->_data['lists'] = $this->base->get_data('grade', array(), '*', $limit, $offset)->result_array();
        $this->load->view('admin/grade_list', $this->_data);
	}

	/**
	 * 教案附件内容添加
	 */
	public function op() {
    	//验证表单规则
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name', '名称', 'required|trim');
		$this->form_validation->set_error_delimiters('<span class="err">', '</span>');

		if ($this->form_validation->run() == FALSE) {
			$this->load->helper('file');
			if ($id = $this->input->get('id')) {
				$this->_data['row'] = $this->base->get_data('grade', array('id'=>$id))->row_array();
			}
			$this->load->view('admin/grade_op', $this->_data);
		} else {
			$id = $this->input->get('id');
			$dirname = './data/uploads/pics/'.date('Y/m/');
			createFolder($dirname);

			$deal_data = array(
				'name'		=> $this->input->post('name'),
				//'title'		=> $this->input->post('title'),
				'content'	=> $this->input->post('content'),
				'type'		=> $this->input->post('type'),
				'chm'		=> $this->input->post('chm'),
			);

			$config = array(
				'upload_path'	=> $dirname,
				'allowed_types'	=> 'gif|jpg|png',
				'max_size'		=> 5000,
				'max_width'		=> 3000,
				'max_height'	=> 3000,
				'encrypt_name'	=> TRUE,
				'overwrite'		=> FALSE
			);

			$this->load->library('upload', $config);

			if($_FILES['cover']['size'] > 0) {
				if(!$this->upload->do_upload('cover')) {
					$this->_data['upload_err'] = $this->upload->display_errors();
					$this->load->view('admin/grade_op', $this->_data);
				}
				$upload_data = $this->upload->data();
				$deal_data['cover'] = date('Y/m/').$upload_data['file_name'];
			}

			$lo = $this->input->post('local');
			$ol = $this->input->post('online');
			if($this->input->post('is_local') == 'local' && $lo) {
				$stuffdir = './data/uploads/attach/'.date('Y/m/');
				createFolder($stuffdir);
				$fname = uniqid().'.'.pathinfo($lo, PATHINFO_EXTENSION);
				if(copy('./data/tmp/'.$lo, $stuffdir.$fname)) {
					unlink('./data/tmp/'.$lo);
				}
				$deal_data['video'] = date('Y/m/').$fname;
				$deal_data['is_local'] = 1;
			} elseif($this->input->post('is_local') == 'online' && $ol) {
				$deal_data['video'] = $this->input->post('online');
				$deal_data['is_local'] = 0;
			}

			if($id) {
				$this->base->update_data('grade', array('id' => $id), $deal_data);
			} else {
				$id = $this->base->insert_data('grade', $deal_data);
			}

			$this->msg->showmessage('添加成功', site_url('admin/grade/lists'));
		}
	}

    /**
    * 删除
    */
    public function del () {
        $id = intval($this->input->get('id'));
        if($id && $this->base->del_data('grade', array('id' => $id))) {
        	exit('ok');
        } else {
        	exit('no');
        }
    }

	public function pic_op() {
    	//验证表单规则
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', '标题', 'required|trim');
		$this->form_validation->set_error_delimiters('<span class="err">', '</span>');
		$this->_data['place_id'] = $place_id =  $this->input->get_post('place_id');
		if ($this->form_validation->run() == FALSE) {
			if ($id = $this->input->get('id')) {
				$this->_data['row'] = $this->base->get_data('pics', array('id'=>$id))->row_array();
			}
			$this->load->view('admin/grade_pic_op', $this->_data);
		} else {
			$id = $this->input->get('id');
			$dirname = './data/uploads/pics/'.date('Y/m/');
			createFolder($dirname);

			$deal_data = array(
				'title'		=> $this->input->post('title'),
				'sort'		=> $this->input->post('sort'),
			);

			$config = array(
				'upload_path'	=> $dirname,
				'allowed_types'	=> 'gif|jpg|png',
				'max_size'		=> 5000,
				'max_width'		=> 3000,
				'max_height'	=> 3000,
				'encrypt_name'	=> TRUE,
				'overwrite'		=> FALSE
			);

			$this->load->library('upload', $config);

			if($_FILES['cover']['size'] > 0) {
				if(!$this->upload->do_upload('cover')) {
					$this->_data['upload_err'] = $this->upload->display_errors();
					$this->load->view('admin/grade_pic_op', $this->_data);
				}
				$upload_data = $this->upload->data();

				$config2 = array(
					'create_thumb'	=> true,
					'source_image'	=> $upload_data['full_path'],
					'maintain_ratio'=> true,
					'width'			=> 200,
					'height'		=> 150
				);

				$this->load->library('image_lib', $config2);
				$this->image_lib->resize();

				$deal_data['filename'] = date('Y/m/').$upload_data['file_name'];
			}

			if($id) {
				$this->base->update_data('pics', array('id' => $id), $deal_data);
			} else {
				$deal_data['place_id'] = $place_id;
				$deal_data['place'] = 5;
				$this->base->insert_data('pics', $deal_data);
			}

			$this->msg->showmessage('添加成功', site_url('admin/grade/pic_lists?place_id='.$place_id));
		}
	}

	public function pic_lists() {
		$place_id = $this->input->get('place_id');

		//分页配置
        $this->load->library('gpagination');
		$total_num = $this->base->get_data('pics', array('place'=>5, 'place_id'=>$place_id))->num_rows();
		$page = $this->input->get('page') > 1 ? $this->input->get('page') : '1';
		$limit = 25;
		$offset = ($page - 1) * $limit;

		$this->gpagination->currentPage($page);
		$this->gpagination->items($total_num);
		$this->gpagination->limit($limit);
		$this->gpagination->target(site_url('admin/grade/pic_lists?place_id='.$place_id));

		$this->_data['place_id'] = $place_id;
		$this->_data['pagination'] = $this->gpagination->getOutput();
		$this->_data['lists'] = $this->base->get_data('pics', array('place'=>5, 'place_id'=>$place_id), '*', $limit, $offset)->result_array();
        $this->load->view('admin/grade_pic_list', $this->_data);
	}

    public function pic_del () {
        $id = intval($this->input->get('id'));
        if($id && $this->base->del_data('pics', array('id' => $id))) {
        	exit('ok');
        } else {
        	exit('no');
        }
    }
}