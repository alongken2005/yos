<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* 教案管理
* @see Content
* @version 1.0.0 (Tue Feb 21 08:17:46 GMT 2012)
* @author ZhangHao
*/
class Subject extends CI_Controller
{
	private $_data;

    public function __construct()
    {
		parent::__construct();
		$this->_data['kinds'] = $this->config->item('subject_kinds');
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
		$total_num = $this->base->get_data('subject')->num_rows();
		$page = $this->input->get('page') > 1 ? $this->input->get('page') : '1';
		$limit = 25;
		$offset = ($page - 1) * $limit;

		$this->gpagination->currentPage($page);
		$this->gpagination->items($total_num);
		$this->gpagination->limit($limit);
		$this->gpagination->target(site_url('admin/subject/lists'));

		$this->_data['pagination'] = $this->gpagination->getOutput();
		$this->_data['lists'] = $this->base->get_data('subject', array(), '*', $limit, $offset, 'sort ASC, ctime DESC')->result_array();
        $this->load->view('admin/subject_list', $this->_data);
    }

    /**
    * @deprecated 文章处理
    */
    public function op () {
		$this->_data['kind'] = $kind = $this->input->get_post('kind') ? $this->input->get_post('kind') : 'video';

    	//验证表单规则
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', '标题', 'required|trim');
		$this->form_validation->set_error_delimiters('<span class="err">', '</span>');

		if ($this->form_validation->run() == FALSE) {
			$this->load->helper('file');
			$this->_data['authorlist'] = $this->base->get_data('author')->result_array();
			$this->_data['gradelist'] = $this->base->get_data('grade')->result_array();

			if ($id = $this->input->get('id')) {
				$tags = array();
				$rows = $this->db->query('SELECT t.name FROM ab_subject_tag vt LEFT JOIN ab_tag t ON vt.tid=t.id WHERE vt.vid='.$id)->result_array();
				foreach($rows as $v) {
					$tags[] = $v['name'];
				}
				$this->_data['tags'] = implode(' ', $tags);
				$this->_data['row'] = $this->base->get_data('subject', array('id'=>$id))->row_array();
			}
			$this->load->view('admin/subject_op', $this->_data);
		} else {
			$id = $this->input->get('id') ? (int)$this->input->get('id') : 0;
			$timestamp = time();
			$filename = uniqid();
			$dirname = './data/uploads/pics/'.date('Y/m/');
			createFolder($dirname);

			$deal_data = array(
				'title'		=> $this->input->post('title'),
				'type'		=> $this->input->post('type'),
				'grade'		=> $this->input->post('grade'),
				'sort'		=> $this->input->post('sort'),
				'authorid'	=> $this->input->post('authorid'),
				'content'	=> $this->input->post('content'),
				'videoType'	=> $this->input->post('videoType'),
				'length'	=> $this->input->post('length'),
				'ctime'		=> $timestamp,
				'mtime'		=> $timestamp,
			);

			if($_FILES['cover']['size'] > 0) {
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

				if(!$this->upload->do_upload('cover')) {
					$this->_data['upload_err'] = $this->upload->display_errors();
					$this->load->view('admin/subject_op', $this->_data);
				}
				$upload_data = $this->upload->data();

				$config2 = array(
					'source_image'		=> $upload_data['full_path'],
					'maintain_ratio'	=> TRUE,
					'width'				=> 400,
					'height'			=> 250,
				);

				$this->load->library('image_lib', $config2);
				$this->image_lib->resize();

				$deal_data['cover'] = date('Y/m/').$upload_data['file_name'];
			}

			$filetype = $this->input->post('videoType');
			if($filetype == 'online') {
				$deal_data['video'] = $this->input->post('online');
			} elseif($filetype == 'local') {
				$deal_data['video'] = $this->input->post('local');
			}

			if($id) {
				$this->base->update_data('subject', array('id' => $id), $deal_data);
			} else {
				$id = $this->base->insert_data('subject', $deal_data);
				$tag = array_filter(explode(' ', $this->input->post('tag')));
				if($tag) {
					foreach($tag as $v) {
						$tagrow = $this->base->get_data('tag', array('name'=>$v), 'id')->row_array();
						if(!$tagrow) {
							$tid = $this->base->insert_data('tag', array('name'=>$v));
						} else {
							$tid = $tagrow['id'];
						}
						$this->base->insert_data('subject_tag', array('vid'=>$id, 'tid'=>$tid));
					}
				}
			}

			$this->msg->showmessage('添加成功', site_url('admin/subject/lists'));
		}
    }

    /**
    * @deprecated 文章删除
    */
    public function del () {
        $id = intval($this->input->get('id'));
        if($id && $this->base->del_data('stuff', array('id' => $id))) {
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

	/**
	 * 教案附件内容列表
	 */
	public function attach_lists() {
		//分页配置
        $this->load->library('gpagination');
		$this->_data['relaid'] = $relaid = $this->input->get('relaid');
		$total_num = $this->base->get_data('attach', array('kind'=>'lake'))->num_rows();
		$page = $this->input->get('page') > 1 ? $this->input->get('page') : '1';
		$limit = 25;
		$offset = ($page - 1) * $limit;

		$this->gpagination->currentPage($page);
		$this->gpagination->items($total_num);
		$this->gpagination->limit($limit);
		$this->gpagination->target(site_url('admin/subject/attach_lists'));

		$this->_data['pagination'] = $this->gpagination->getOutput();
		$this->_data['lists'] = $this->base->get_data('attach', array('kind'=>'lake', 'relaid'=>$relaid), '*', $limit, $offset, 'sort ASC, ctime DESC')->result_array();
        $this->load->view('admin/subject_attach_list', $this->_data);
	}

	/**
	 * 教案附件内容添加
	 */
	public function attach_op() {
		$this->_data['relaid'] = $relaid = $this->input->get('relaid');

    	//验证表单规则
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', '标题', 'required|trim');
		$this->form_validation->set_rules('filetype', '课件类型', 'required|trim');
		$this->form_validation->set_error_delimiters('<span class="err">', '</span>');
		$this->load->helper('file');

		if ($this->form_validation->run() == FALSE) {
			if ($id = $this->input->get('id')) {
				$this->_data['row'] = $this->base->get_data('attach', array('id'=>$id))->row_array();
			}
			$this->load->view('admin/subject_attach_op', $this->_data);
		} else {
			$id = $this->input->get('id');

			$deal_data = array(
				'relaid'	=> $relaid,
				'title'		=> $this->input->post('title'),
				'kind'		=> 'lake',
				'filetype'	=> $this->input->post('filetype'),
				'sort'		=> $this->input->post('sort'),
				'other'		=> $this->input->post('other'),
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

			$this->msg->showmessage('添加成功', site_url('admin/subject/attach_lists?relaid='.$relaid));
		}
	}

    /**
    * 删除
    */
    public function attach_del () {
        $id = intval($this->input->get('id'));
        if($id && $this->base->del_data('attach', array('id' => $id))) {
        	exit('ok');
        } else {
        	exit('no');
        }
    }

	public function getSubGrade() {
		$type = $this->input->get('type');
		$grade = $this->input->get('grade');
		$lists = $this->base->get_data('grade', array('type'=>$type))->result_array();
		write_log($grade);
		if($lists) {
			$option = '';
			$select = '';
			foreach($lists as $v) {
				$select = ($grade && $grade == $v['id']) ? 'selected' : '';
				$option .= '<option value="'.$v['id'].'" '.$select.'>'.$v['name'].'</option>';
			}
		} else {
			$option = '<option>暂无界数可选</option>';
		}

		exit($option);
	}
}