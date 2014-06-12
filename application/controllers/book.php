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
		
		if($this->member) {
			$this->_data['userInfo'] = $this->getUserInfo($this->member['uid']);
		}
			
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
			$this->msg->showmessage(lang('login_first'), site_url('user/login'));
		}
		
		$this->_data['active'] = 'lists';
		$this->_data['slider_left'] = $this->load->view(THEME.'/slider_left', $this->_data, true);
		$this->_data['books'] = $this->base->get_data('book', array('uid'=>$this->member['uid']), '*', 0, 0, 'mtime DESC')->result_array();
		$this->load->view(THEME.'/book_list', $this->_data);
	}

	/**
	 *书本信息页
	 */
	public function detail () {
		$this->_data['do'] = $do = $this->input->get('do');
		$this->_data['bid'] = $bid = intval($this->input->get('bid'));
		$this->_data['book'] = $book =  $this->base->get_data('book', array('id'=>$bid))->row_array();

		$this->_data['addFav'] = '';
		$this->_data['removeFav'] = 'no';
		if($this->member) {
			$myBook = $this->base->get_data('book', array('uid'=>$this->member['uid']), 'id')->result_array();
			$bookIds = array();
			if($myBook) {
				foreach($myBook as $v) {
					$bookIds[] = $v['id'];
				}
			}

			if(in_array($bid, $bookIds)) {
				$this->_data['addFav'] = 'no';
				$this->_data['removeFav'] = 'no';				
			}

			$isfav = $this->base->get_data('book_fav', array('bid'=>$bid, 'uid'=>$this->member['uid']))->num_rows();


			//收藏过，添加收藏隐藏
			if($isfav > 0) {
				$this->_data['addFav'] = 'no';
				$this->_data['removeFav'] = '';
			}
		}

		if($do == 'detail') {
			$desLen = mb_strlen($book['description'], 'utf-8');
			if($desLen > 500) {
				$this->_data['more'] = true;
			}
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
			output(1008, lang('login_first'));
		}

		$bid = intval($this->input->post('bid')) ? intval($this->input->post('bid')) : 0;

		if(!$bid) output(1006, lang('book_id_error'));

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
		output(1, lang('success'));
	}

	//评论点赞
	public function helpful() {
		$bid = $this->input->get('bid');

	}

	public function addFav() {

		//检查登陆状态
		if(!$this->member) output(1008, lang('login_first'));

		$bid = intval($this->input->get('bid'));
		if(!$bid) output(1006, lang('book_id_error'));

		$num = $this->base->get_data('book_fav', array('uid'=>$this->member['uid'], 'bid'=>$bid))->num_rows();
		if($num > 0) output(2206, lang('has_fav'));

		$re = $this->base->insert_data('book_fav', array('uid'=>$this->member['uid'], 'bid'=>$bid, 'ctime'=>time()));
		if($re) {
			output(1, lang('success'));
		} else {
			output(2207, lang('fav_failed'));
		}		
	}

	/**
	 * 取消收藏
	 */
	public function delFav() {

		//检查登陆状态
		if(!$this->member) output(1008, lang('login_first'));
		$bid = intval($this->input->get('bid'));
		if(!$bid) output(1006, lang('book_id_error'));

		$re = $this->base->del_data('book_fav', array('uid'=>$this->member['uid'], 'bid'=>$bid));
		if($re) {
			output(1, lang('success'));
		} else {
			output(100, lang('cancel_fav_failed'));
		}
	}	
	
	
	/**
	 * 书本编辑
	 */
	public function edit () {
	
		//检查登陆状态
		if(!$this->member) {
			$this->msg->showmessage(lang('login_first'), site_url('user/login'));
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
					'paid_section_start'	=> $this->input->post('paid_section_start'),
					'paid_section_end'	=> $this->input->post('paid_section_end'),
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
				} else {
					$insert_data['covered'] = $this->input->post('covered');
				}
				
				if($id > 0) {
					$this->base->update_data('book', array('id'=>$id), $insert_data);
					$this->msg->showmessage(lang('success'), site_url('book/edit?step=1&id='.$id));
				} else {
					$insert_data['uid'] 	= $uid;
					$insert_data['ctime'] 	= time();

					$id = $this->base->insert_data('book', $insert_data);

					$this->msg->showmessage(lang('success'), site_url('book/edit?step=2&id='.$id));
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
			$this->_data['slider_left'] = $this->load->view(THEME.'/slider_left', $this->_data, true);
			
			$this->load->view(THEME.'/book_edit', $this->_data);
		}
	}
	
	public function chapter_edit () {
		$bid = $this->_data['bid'] = intval($this->input->get('bid'));
		$cid = intval($this->input->get_post('cid'));

		if($_POST) {
			$dis = intval($this->input->post('dis'));
			$title = $this->input->post('title') ? $this->input->post('title') : 'Chapter '.$dis;
			$content = $this->input->post('content');

			if(!$bid) $this->msg->showmessage(lang('book_id_error'), site_url('book/chapter_edit?bid='.$bid));
			if($dis <= 0) $this->msg->showmessage(lang('chapter_order_error'), site_url('book/chapter_edit?bid='.$bid));

			$numr = $this->base->get_data('book_chapter', array('bid'=>$bid, 'dis'=>$dis))->num_rows();

			if($numr > 0 && !$cid) $this->msg->showmessage(lang('chapter_sequence_exist'), site_url('book/chapter_edit?bid='.$bid));

			$insert_data = array(
				'dis' 		=> $dis,
				'title'		=> $title,
				'content'	=> $content,
				'is_change'	=> 0,
				'mtime'		=> time(),
			);

			if($cid) {
				$this->base->update_data('book_chapter', array('id'=>$cid), $insert_data);
				$this->msg->showmessage(lang('chapter_edit_ok')."\\0", '', 50, array("<embed allowScriptAccess=\"sameDomain\" src=\"".base_url('common/reader/SplitWord.swf?showSave=1&bookId='.$bid.'&chapterId='.$cid)."\" width=0 height=0 type=\"application/x-shockwave-flash\"></embed><script>function callback() {parent.callback();}</script>"));
			} else {
				$insert_data['ctime'] = time();
				$insert_data['bid'] = $bid;
				$cid = $this->base->insert_data('book_chapter', $insert_data);
				$this->msg->showmessage(lang('chapter_creat_ok')."\\0", '', 1, array("<embed allowScriptAccess=\"sameDomain\" src=\"".base_url('common/reader/SplitWord.swf?showSave=1&bookId='.$bid.'&chapterId='.$cid)."\" width=0 height=0 type=\"application/x-shockwave-flash\"></embed><script>function callback() {parent.callback();}</script>"));
			}
		} else {
			if($cid) {
				$this->_data['chapter'] = $this->base->get_data('book_chapter', array('id'=>$cid))->row_array();
			}
			$this->load->view(THEME.'/header_sam');
			$this->load->view(THEME.'/book_edit_box', $this->_data);
			$this->load->view(THEME.'/footer_sam');			
		}
	}

	public function chapter_add () {
		$bid = intval($this->input->get('bid'));
		$dis = intval($this->input->post('dis'));
		$title = $this->input->post('title');
		$content = $this->input->post('content');

		if(!$bid) $this->msg->showmessage(lang('book_id_error'), site_url('book/edit?step=2&id='.$bid));
		if($dis <= 0) $this->msg->showmessage(lang('chapter_order_error'), site_url('book/edit?step=2&id='.$bid));

		$numr = $this->base->get_data('book_chapter', array('bid'=>$bid, 'dis'=>$dis))->num_rows();
		if($numr > 0) $this->msg->showmessage(lang('chapter_sequence_exist'), site_url('book/edit?step=2&id='.$bid));
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
			$this->msg->showmessage(lang('chapter_creat_ok')."\\0", site_url('book/edit?step=2&id='.$bid), 1, array("<embed src=\"".base_url('common/reader/SplitWord.swf?showSave=1&bookId='.$bid.'&chapterId='.$cid)."\" width=0 height=0 type=\"application/x-shockwave-flash\"></embed>"));
			//output(1, '章节添加成功');
			//<embed src='".base_url('common/SplitWord.swf?bookId='.$bid.'&chapterId='.$cid),"' quality=high width=1 height=1 wmode=transparent type='application/x-shockwave-flash'></embed>
		}
	}

	public function reading() {

		$bid = intval($this->input->get('bid'));
		if($bid) {
			if(isset($this->member['uid'])) {
				$num = $this->base->get_data('history', array('bid'=>$bid, 'uid'=>$this->member['uid']))->num_rows();
				if($num > 0) {
					$this->base->update_data('history', array('bid'=>$bid, 'uid'=>$this->member['uid']), array('mtime'=>time()));
				} else {
					$this->base->insert_data('history', array('bid'=>$bid, 'uid'=>$this->member['uid'], 'mtime'=>time()));
				}

				$this->db->query('UPDATE yos_book SET hits=hits+1 WHERE id='.$bid);
				
			}
			redirect(base_url('common/reader/PageReader.swf?bookId='.$bid));
		} else {
			$this->msg->showmessage(lang('failed'));
		}
	}
}