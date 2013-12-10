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
		
		//$this->_user = $this->checkLogin();		
	}

	/**
	 * 默认方法
	 */
	public function index() {
		return false;
	}
	
	public function lists() {
		$this->_data['view'] = $this->input->get('view') ? $this->input->get('view') : 'list';
		//检查登陆状态
		if(!$this->member) {
			$this->msg->showmessage('请先登陆！', site_url('user/login'));
		}
		
		$this->_data['active'] = 'edit';
		$this->_data['books'] = $this->base->get_data('book', array('uid'=>$this->member['uid']), '*', 0, 0, 'mtime DESC')->result_array();
		$this->load->view(THEME.'/book_list', $this->_data);
	}

	/**
	 *书本信息页
	 */
	public function detail () {
		$this->_data['do'] = $do = $this->input->get('do');
		$this->_data['bid'] = $bid = intval($this->input->get('bid'));
		$this->_data['book'] = $this->base->get_data('book', array('id'=>$bid))->row_array();

		if($do == 'detail') {
			$this->_data['genre'] = $this->base->get_data('book_genre', array('id'=>$this->_data['book']['genre']))->row_array();
		} else if($do == 'reviews') {
			$scoreArray = $this->base->get_data('book_reviews', array('bid'=>$bid), 'score')->result_array();
			$score1 = $score2 = $score3 = $score4 = $score5 = array();
			foreach ($scoreArray as $v) {
				if ($v['score'] == 5) {
					$score5[] = $v['score'];
				} else if($v['score'] == 4) {
					$score4[] = $v['score'];
				} else if($v['score'] == 3) {
					$score3[] = $v['score'];
				} else if($v['score'] == 2) {
					$score2[] = $v['score'];
				} else if($v['score'] == 1) {
					$score1[] = $v['score'];
				}				
			}

			$scoretotal = count($scoreArray);
			if($scoretotal == 0) $scoretotal = 1;
			$this->_data['score1'] = count($score1)*100/$scoretotal;
			$this->_data['score2'] = count($score2)*100/$scoretotal;
			$this->_data['score3'] = count($score3)*100/$scoretotal;
			$this->_data['score4'] = count($score4)*100/$scoretotal;
			$this->_data['score5'] = count($score5)*100/$scoretotal;

			//reviews列表
	        $this->load->library('gpagination');
			$total_num = $this->base->get_data('book_reviews', array('bid'=>$bid))->num_rows();
			$page = $this->input->get('page') > 1 ? $this->input->get('page') : '1';
			$limit = 15;
			$offset = ($page - 1) * $limit;

			$this->gpagination->currentPage($page);
			$this->gpagination->items($total_num);
			$this->gpagination->limit($limit);
			$this->gpagination->target(site_url('book/detail?do=reviews&bid='.$bid));

			$this->_data['pagination'] = $this->gpagination->getOutput();			
			$this->_data['reviewsList'] = $this->base->get_data('book_reviews', array('bid'=>$bid), '*', $limit, $offset, 'ctime DESC')->result_array();
		}


		$this->load->view(THEME.'/book_detail', $this->_data);
	}

	/**
	 *书本浮动信息
	 */
	public function floatinfo () {
		$bid = intval($this->input->get('bid'));

		$this->_data['book'] = $this->base->get_data('book', array('id'=>$bid))->row_array();
		
		echo $this->load->view(THEME.'/book_floatinfo', $this->_data, true);
		exit;
	}

	/**
	 *对书本评分
	 */
	public function score() {

		$bid = intval($this->input->post('bid'));
		$score = intval($this->input->post('score'));
		if(!$bid || !$score) exit("error");
		if(!$this->member) exit("nologin");

		$re = $this->base->insert_data('book_score', array('bid'=>$bid, 'score'=>$score, 'uid'=>$this->member['uid'], 'ctime'=>time()));
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
	 *对书本评论 
	 */	
	public function writeReview() {
		//检查登陆状态
		if(!$this->member) {
			$this->msg->showmessage('请先登陆！', site_url('user/login'));
		}

		$bid = intval($this->input->post('bid')) ? intval($this->input->post('bid')) : 0;

		if(!$bid) $this->msg->showmessage('bookid error', site_url());

		$insert_data = array(
			'bid'		=> $bid,
			'uid'		=> $this->member['uid'],
			'username'	=> $this->member['username'],
			'score'		=> $this->input->post('score'),
			'title'		=> $this->input->post('title'),
			'content'	=> $this->input->post('content'),
			'ctime'		=> time(),
		);

		$rid = $this->base->insert_data('book_reviews', $insert_data);
		if($rid) {
			$row = $this->db->query('SELECT SUM(score) score, COUNT(*) total FROM yos_book_reviews WHERE bid='.$bid)->row_array();
			$avScore = floor($row['score']/$row['total']);
			$this->base->update_data('book', array('id'=>$bid), array('score'=>$avScore, 'scorenum'=>$row['total']));			
		}
		$this->msg->showmessage('write success', site_url('book/detail?do=reviews&bid='.$bid));
	}

	//评论点赞
	public function helpful() {
		$bid = $this->input->get('bid');

	}
	
	/**
	 * 书本编辑
	 */
	public function edit () {
	
		//检查登陆状态
		if(!$this->member) {
			$this->msg->showmessage('请先登陆！', site_url('user/login'));
		}	
		$uid 	= $this->member['uid'];
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
					'text_price'	=> (float)$this->input->post('price'),
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
				
				if($id > 0) {
					$this->base->update_data('book', array('id'=>$id), $insert_data);
					$this->msg->showmessage('修改完成', site_url('book/edit?step=1&id='.$id));
				} else {
					$insert_data['uid'] 	= $uid;
					$insert_data['ctime'] 	= time();

					$id = $this->base->insert_data('book', $insert_data);

					$this->msg->showmessage('BaseInfo完成', site_url('book/edit?step=2&id='.$id));
				}
			} else if($setp == 2) {
				if(!$id) $this->msg->showmessage('Add Book Fisrt！', site_url('book/edit'));

			}
			
		} else {
			
			if($step == 1) {
				$this->_data['genre'] = $this->base->get_data('book_genre', array('pid'=>0))->result_array();
				
				if($id) {
					$this->_data['row'] = $this->base->get_data('book', array('id'=>$id, 'uid'=>$uid), '*', 0, 0, 'mtime DESC')->row_array();
				}				
			} else if($step == 2) {
				if($id) {
					$this->_data['chapters'] = $this->base->get_data('book_chapter', array('bid'=>$id), '*', 0, 0, 'dis ASC')->result_array();
				} else {
					$this->msg->showmessage('Add Book Fisrt！', site_url('book/edit'));
				}
			} else if($step == 3) {
				if($id) {
					$this->_data['chapters'] = $this->base->get_data('book_chapter', array('bid'=>$id), '*', 0, 0, 'dis ASC')->result_array();
				} else {
					$this->msg->showmessage('Add Book Fisrt！', site_url('book/edit'));
				}				
			}

			
			$this->_data['id'] = $id;
			$this->_data['step'] = $step;
			$this->_data['active'] = 'edit';
			
			
			$this->load->view(THEME.'/book_edit', $this->_data);
		}
	}
	
	public function chapter_edit () {
		$bid = $this->input->get('bid');
		$cid = $this->input->get('cid');
	}

	public function chapter_add () {
		$bid = intval($this->input->get('bid'));
		$dis = intval($this->input->post('dis'));
		$title = $this->input->post('title');
		$content = $this->input->post('content');

		if(!$bid) $this->msg->showmessage('书本id错误', site_url('book/edit?step=2&id='.$bid));
		if($dis <= 0) $this->msg->showmessage('章节排序错误', site_url('book/edit?step=2&id='.$bid));
		if(!$title) $this->msg->showmessage('章节标题不能为空', site_url('book/edit?step=2&id='.$bid));

		$numr = $this->base->get_data('book_chapter', array('bid'=>$bid, 'dis'=>$dis))->num_rows();
		if($numr > 0) $this->msg->showmessage('章节排序已存在', site_url('book/edit?step=2&id='.$bid));
		// if(!$bid) output(1006, '书本id错误');	
		// if($dis <= 0) output(2201, '章节排序错误');
		// if(!$title) output(2202, '章节标题不能为空');

		$insert_data = array(
			'bid'		=> $bid,
			'dis' 		=> $dis,
			'title'		=> $title,
			'content'	=> $content,
			'is_change'	=> 0,
			'ctime'		=> time(),
			'mtime'		=> time(),
		);


		if($cid = $this->base->insert_data('book_chapter', $insert_data)) {
			//$this->run_change($bid, $cid);
			$this->msg->showmessage("章节添加成功\\0", site_url('book/edit?step=2&id='.$bid), 1, array("<embed src=\"".base_url('common/splitWord.swf?showSave=1&bookId='.$bid.'&chapterId='.$cid)."\" width=0 height=0 type=\"application/x-shockwave-flash\"></embed>"));
			//output(1, '章节添加成功');
			//<embed src='".base_url('common/SplitWord.swf?bookId='.$bid.'&chapterId='.$cid),"' quality=high width=1 height=1 wmode=transparent type='application/x-shockwave-flash'></embed>
		}
	}

	private function run_change($bid, $cid) {
		echo "<embed src='".base_url('common/SplitWord.swf?bookId='.$bid.'&chapterId='.$cid),"' quality=high width=1 height=1 wmode=transparent type='application/x-shockwave-flash'></embed>";
	}
}