<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @deprecated 书本
 * @see Book
 * @version 1.0.0 (12-10-8 下午3:03)
 * @author ZhangHao
 */

class Book extends MY_Controller {
	private $_data;
	private $_user;

	public function __construct() {
		parent::__construct();
		$this->load->model('base_mdl', 'base');
		
		$this->_user = $this->checkLogin();		
	}

	/**
	 * 默认方法
	 */
	public function index() {
		echo "fdfd";
	}
	
	public function lists() {
		
		$this->_data['view'] = $this->input->get('view') ? $this->input->get('view') : 'list';
		//检查登陆状态
		if(!$this->_user) {
			$this->msg->showmessage('请先登陆！', site_url('user/login'));
		}	
		
		$this->_data['books'] = $this->base->get_data('book', array('uid'=>$this->_user['uid']), '*', 0, 0, 'mtime DESC')->result_array();
		$this->load->view(THEME.'/book_list', $this->_data);
	}

	/**
	 *书本信息页
	 */
	public function detail () {
		$this->_data['do'] = $this->input->get('do');
		$this->_data['bid'] = $bid = intval($this->input->get('bid'));
		$this->_data['book'] = $this->base->get_data('book', array('id'=>$bid))->row_array();
		$this->_data['genre'] = $this->base->get_data('book_genre', array('id'=>$this->_data['book']['genre']))->row_array();
		$this->load->view(THEME.'/book_detail', $this->_data);
	}

	

	/**
	 *书本浮动信息
	 */
	public function floatinfo () {
		$bid = intval($this->input->get('bid'));

		$book = $this->base->get_data('book', array('id'=>$bid))->row_array();
		$content = '787897798';
		exit($content);
	}

	/**
	 *对书本评分
	 */
	public function score() {

		$bid = intval($this->input->post('bid'));
		$score = intval($this->input->post('score'));

		if(!$bid || !$score) exit("error");
		if(!$this->_user) exit("nologin");

		$re = $this->base->insert_data('book_score', array('bid'=>$bid, 'score'=>$score, 'uid'=>$this->_user['uid'], 'ctime'=>time()));
		if($re) {
			$row = $this->db->query('SELECT SUM(score) score, COUNT(*) total FROM yos_book_score WHERE bid='.$bid)->row_array();
			$avScore = floor($row['score']/$row['total']);
			$this->base->update_data('book', array('id'=>$bid), array('score'=>$avScore, 'scorenum'=>$row['total']));
			exit($avScore);
		} else {
			exit("error");
		}
	}
	
	/**
	 * 书本编辑
	 */
	public function edit () {
	
		//检查登陆状态
		if(!$this->_user) {
			$this->msg->showmessage('请先登陆！', site_url('user/login'));
		}	
		$uid 	= $this->_user['uid'];
		$id 	= intval($this->input->get('id'));
		$step 	= $this->input->get('step');
		$step 	= $step ? $step : 1;
		
		if($_POST) {
			if($step == 1) {
				$dirname = './data/books/'.date('Y/m/');
				createFolder($dirname);

				$insert_data = array(
					'title' 		=> $this->input->post('title'),
					'isbn'			=> $this->input->post('isbn'),
					'author'		=> $this->input->post('author'),
					'publisher' 	=> $this->input->post('publisher'),
					'genre'			=> $this->input->post('genre'),
					'price'			=> $this->input->post('price'),
					'paid_section'	=> $this->input->post('paid_section'),
					'description'	=> $this->input->post('description'),
					'mtime'			=> time()
				);

				$config = array(
					'upload_path'	=> $dirname,
					'allowed_types'	=> 'gif|jpg|png',
					'max_size'		=> 5000,
					'max_width'		=> 3000,
					'max_height'	=> 3000,
					'encrypt_name'	=> true,
				);

				$this->load->library('upload', $config);

				if($_FILES['cover']['size'] > 0) {
					if(!$this->upload->do_upload('cover')) {
						$this->_data['upload_err'] = $this->upload->display_errors();
						$this->load->view('book/edit', $this->_data);
					}
					$upload_data = $this->upload->data();

					$config = array(
						'create_thumb'	=> true,
						'source_image'	=> $upload_data['full_path'],
						'maintain_ratio'=> false,
						'width'			=> 225,
						'height'		=> 300
					);

					$this->load->library('image_lib', $config);
					$this->image_lib->resize();

					$insert_data['cover'] = date('Y/m/').$upload_data['file_name'];
				}
				
				if($id) {
					$this->base->update_data('book', array('id'=>$id), $insert_data);
					$this->msg->showmessage('修改完成', site_url('book/edit?step=1&id='.$id));
					//redirect(site_url('book/edit?step=2'));
				} else {
					$insert_data['uid'] 	= $uid;
					$insert_data['ctime'] 	= time();

					$id = $this->base->insert_data('book', $insert_data);
					$this->msg->showmessage('BaseInfo完成', site_url('book/edit?step=2&id='.$id));
				}
			}
			
		} else {
			
			if($step == 1) {
				$this->_data['genre'] = $this->base->get_data('book_genre', array('pid'=>0))->result_array();
				
				if($id) {
					$this->_data['row'] = $this->base->get_data('book', array('id'=>$id, 'uid'=>$uid), '*', 0, 0, 'mtime DESC')->row_array();
				}				
			} else if($step == 2) {
				if($id) {
					$this->_data['chapters'] = $this->base->get_data('book_chapter', array('bid'=>$id), '*', 0, 0, 'dis DESC')->result_array();
				}				
			}

			
			$this->_data['id'] = $id;
			$this->_data['step'] = $step;
			$this->_data['active'] = 'edit';
			
			
			$this->load->view(THEME.'/book_edit', $this->_data);
		}
	}
	
	
	public function chapter_edit () {
		$bid = $this->input->get('id');
		$cid = $this->input->get('cid');
	}

	public function chapter_add () {
		$bid = intval($this->input->get('bid'));
		$dis = intval($this->input->post('dis'));
		$title = $this->input->post('title');
		$content = $this->input->post('content');

		if(!$bid) { exit('add error'); }

		$insert_data = array(
			'bid'		=> $bid,
			'dis' 		=> $dis,
			'title'		=> $title,
			'content'	=> $content,
			'ctime'		=> time(),
			'mtime'		=> time(),
		);

		if($this->base->insert_data('book_chapter', $insert_data)) {
			exit('ok');
		}
	}
}