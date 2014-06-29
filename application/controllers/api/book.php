<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 书本
 * @version 1.0.0 (12-10-8 下午3:03)
 * @author ZhangHao
 */

class Book extends MY_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('base_mdl', 'base');

		write_log(debug($_REQUEST, 0, 1), 'api');
	}

	/**
	 * 默认方法
	 */
	public function index() {
		$this->views();
	}

	/**
	 * 获取书本分类
	 */	
	public function getGenre() {
		getSign(__function__, $this->input->get_post('random'), $this->input->get_post('sign'));
		$genres = $this->db->query('SELECT g.id genreId, g.name from yos_book_genre g JOIN yos_book b ON g.id=b.genre GROUP BY g.id ORDER BY g.dis ASC;')->result_array();
		output(1, $genres);		
	}

	/**
	 * 获取书本列表
	 */	
	public function getBooks() {
		getSign(__function__, $this->input->get_post('random'), $this->input->get_post('sign'));

		$genreId = intval($this->input->get_post('genreId'));
		$type = $this->input->get_post('type');
		$page = intval($this->input->get_post('page'));
		$pageSize = intval($this->input->get_post('pageSize')) ? intval($this->input->get_post('pageSize')) : 20;

		$pageLimit = $where = '';
		if($page) {
			$offset = ($page-1)*$pageSize;
			$pageLimit = 'LIMIT '.$offset.', '.$pageSize;
		}

		$result = array();

		if($type) {
			if($type == 'continue' || $type == 'mylist') {
				//检查登陆状态
				if(!$this->member) output(1008, lang('login_first'));

				if($type == 'continue') {
					$result = $this->db->query('SELECT b.id bookId, b.title, b.cover, b.genre genreId FROM yos_history h LEFT JOIN yos_book b ON b.id=h.bid WHERE h.uid='.$this->member['uid'].' ORDER BY h.mtime DESC '.$pageLimit)->result_array();
					$totalPage = $this->db->query('SELECT b.id bookId, b.title, b.cover, b.genre genreId FROM yos_history h LEFT JOIN yos_book b ON b.id=h.bid WHERE h.uid='.$this->member['uid'])->num_rows();
				} else if($type == 'mylist') {
					$favBookid = array();
					$where = ' ';

					$fav = $this->db->query('SELECT bid FROM yos_book_fav WHERE uid='.$this->member['uid'].' ORDER BY ctime DESC')->result_array();
					if($fav) {
						foreach($fav as $v) {
							$favBookid[] = $v['bid'];
						}
						$where = ' OR id IN('.implode(',', $favBookid).') ';
					}

					$result = $this->db->query('SELECT id bookId, title, cover, genre genreId FROM yos_book WHERE uid='.$this->member['uid'].$where.$pageLimit)->result_array();
					$totalPage = $this->db->query('SELECT * FROM yos_book WHERE uid='.$this->member['uid'].$where)->num_rows();

				}						
			} else if($type == 'popular') {
				$result = $this->base->get_data('book', array(), 'id bookId, title, cover, genre genreId', $pageSize, $offset, 'hits DESC')->result_array();
				$totalPage = $this->base->get_data('book', array(), 'id bookId, title, cover, genre genreId')->num_rows();
			} else if($type == 'toppicks') {
				$result = $this->base->get_data('book', array(), 'id bookId, title, cover, genre genreId', $pageSize, $offset, 'ctime DESC')->result_array();
				$totalPage = $this->base->get_data('book', array(), 'id bookId, title, cover, genre genreId')->num_rows();
			} else if($type == 'bestrated') {
				$result = $this->base->get_data('book', array(), 'id bookId, title, cover, genre genreId', $pageSize, $offset, 'score DESC, mtime DESC')->result_array();
				$totalPage = $this->base->get_data('book', array(), 'id bookId, title, cover, genre genreId')->num_rows();
			}
		} else {
			if($genreId) $where = 'WHERE genre='.$genreId;
			$result = $this->db->query("SELECT id bookId, title, cover, genre genreId FROM yos_book ".$where." ORDER BY dis ASC, mtime DESC ".$pageLimit)->result_array();			
			$totalPage = $this->db->query("SELECT id bookId, title, cover, genre genreId FROM yos_book ".$where)->num_rows();
		}

		$books = array();
		if($result) {
			foreach($result as $row) {
				$row['cover'] = $row['cover'] ? base_url('data/books/'.$row['cover']) : base_url('data/books/nocover.jpg');
				$books[] = $row;
			}
		}
		$datas = array('page'=>$page, 'pageSize'=>$pageSize, 'books'=>$books, 'totalPage'=>ceil($totalPage/$pageSize));
		output(1, $datas);	
	}

	/**
	 * 获取书本详情
	 */	
	public function getBookInfo () {
		getSign(__function__, $this->input->get_post('random'), $this->input->get_post('sign'));

		$bid = intval($this->input->get_post('bookId'));
		if(!$bid) output(1006, lang('book_id_error'));

		$book = $this->base->get_data('book', array('id'=>$bid), 'id bookId, uid authorid, author, title, cover, publisher, genre genreId, text_price textPrice, audio_price audioPrice, score, scorenum, description, ctime, mtime, paid_section_start, paid_section_end')->row_array();
		$book['cover'] = $book['cover'] ? base_url('data/books/'.$book['cover']) : base_url('data/books/nocover.jpg');
		$book['textPriceTitle'] = 'paid text 1000 words';
		$book['audioPriceTitle'] = 'paid Audio 1000 words';
		$book['hasPayed'] = 0;
		if($noWin = get_cookie('noWin_'.$bid)) {
			list($bbid, $uuid) = explode('_', $noWin);
			if($bbid == $bid && isset($this->member['uid']) && $uuid == $this->member['uid']) {
				$book['hasPayed'] = 1;
			}
		}
		output(1, $book);
	}

	/**
	 * 获取目录列表
	 */	
	public function getDirectory () {
		getSign(__function__, $this->input->get_post('random'), $this->input->get_post('sign'));

		$bid = intval($this->input->get_post('bookId'));
		if(!$bid) output(1006, lang('book_id_error'));

		$chapters = $this->base->get_data('book_chapter', array('bid'=>$bid), 'id chapterId, page, pagenum, title, dis')->result_array();
		if(!$chapters) output(2204, lang('chapter_empty'));
		output(1, $chapters);
	}

	/**
	 * 获取章节内容
	 */	
	public function getChapter () {
		getSign(__function__, $this->input->get_post('random'), $this->input->get_post('sign'));

		$bid = intval($this->input->get_post('bookId'));
		$cid = intval($this->input->get_post('chapterId'));

		if(!$bid) output(1006, lang('book_id_error'));
		if(!$cid) output(1007, lang('chapterId_error'));

		$chapter = $this->base->get_data('book_chapter', array('bid'=>$bid, 'id'=>$cid), 'bid bookId, id chapterId, content')->row_array();
		if(!$chapter) output(2204, lang('chapter_empty'));
		output(1, $chapter);
	}

	/**
	 * 获取书本评分情况
	 */	
	public function getScoreInfo () {
		getSign(__function__, $this->input->get_post('random'), $this->input->get_post('sign'));

		$bid = intval($this->input->get_post('bookId'));
		if(!$bid) output(1006, lang('book_id_error'));
		$scoreArray = $this->base->get_data('book_reviews', array('bid'=>$bid), 'score')->result_array();
		$score1 = $score2 = $score3 = $score4 = $score5 = array();

		if($scoreArray) {
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
		}

		output(1, array(count($score1), count($score2), count($score3), count($score4), count($score5)));
	}

	/**
	 * 获取书本评论列表
	 */
	public function getReviews () {
		getSign(__function__, $this->input->get_post('random'), $this->input->get_post('sign'));

		$bid = intval($this->input->get_post('bookId'));
		if(!$bid) output(1006, lang('book_id_error'));		
		$page = intval($this->input->get_post('page'));
		$pageSize = intval($this->input->get_post('pageSize')) ? intval($this->input->get_post('pageSize')) : 20;

		$pageLimit = $where = '';
		if($page) {
			$offset = ($page-1)*$pageSize;
			$pageLimit = ' LIMIT '.$offset.', '.$pageSize;
		}	

		$reviews = $this->db->query("SELECT username,title,content,score,ctime FROM yos_book_reviews WHERE bid=".$bid." ORDER BY ctime DESC".$pageLimit)->result_array();

		output(1, array('page'=>$page, 'pageSize'=>$pageSize, 'reviews'=>$reviews));			
	}

	//获取我的评论列表
	public function getMyReviews() {
		getSign(__function__, $this->input->get_post('random'), $this->input->get_post('sign'));

		//检查登陆状态
		if(!$this->member) output(1008, lang('login_first'));

		$bid = intval($this->input->get_post('bookId'));
		if(!$bid) output(1006, lang('book_id_error'));		
		$page = intval($this->input->get_post('page'));
		$pageSize = intval($this->input->get_post('pageSize')) ? intval($this->input->get_post('pageSize')) : 20;

		$pageLimit = $where = '';
		if($page) {
			$offset = ($page-1)*$pageSize;
			$pageLimit = ' LIMIT '.$offset.', '.$pageSize;
		}	

		$reviews = $this->db->query("SELECT username,title,content,score,ctime FROM yos_book_reviews WHERE bid=".$bid." AND uin=".$this->member['uid']." ORDER BY ctime DESC".$pageLimit)->result_array();

		output(1, array('page'=>$page, 'pageSize'=>$pageSize, 'reviews'=>$reviews));		
	}

	/**
	 * 发表评论
	 */
	public function writeRviews () {
		getSign(__function__, $this->input->get_post('random'), $this->input->get_post('sign'));

		//检查登陆状态
		if(!$this->member) output(1008, lang('login_first'));

		$bid = intval($this->input->post('bookId'));
		$score = intval($this->input->post('score'));

		if($score <=0 || $score > 5) output(2203, lang('ratings_between'));
		if(!$bid) output(1006, lang('book_id_error'));

		$insert_data = array(
			'bid'		=> $bid,
			'uid'		=> $this->member['uid'],
			'username'	=> $this->member['username'],
			'score'		=> $score,
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
		output(1, '评论成功');
	}

	/**
	 * 接收段落的单页内容
	 */
	public function inputPages() {
		getSign(__function__, $this->input->get_post('random'), $this->input->get_post('sign'));

		$bid = intval($this->input->post('bookId'));
		$cid = intval($this->input->post('chapterId'));
		if(!$bid) output(1006, lang('book_id_error'));
		if(!$cid) output(1007, lang('chapterId_error'));

		write_log($this->input->post('pages'));

		$pages = json_decode($this->input->post('pages'), true);
		write_log(debug($pages, 0, 1));

		if($pages) {
			$pagesData = array();
			foreach ($pages as $key => $value) {
				$pagesData[] = array('bid'=>$bid, 'cid'=>$cid, 'content'=>$value, 'num'=>$key+1, 'words'=>wordCount($value));
			}	
			$chapter = $this->base->get_data('book_chapter', array('id'=>$cid), 'dis')->row_array();
			$this->base->del_data('book_pages', array('bid'=>$bid, 'cid'=>$cid));
			$this->db->insert_batch('book_pages', $pagesData);
			$pagenum = $this->base->get_data('book_chapter', array('bid'=>$bid, 'dis < '=>$chapter['dis']), 'SUM(pagenum) num')->row_array();
			write_log($pagenum['num'].'_'.$chapter['dis']);
			$this->base->update_data('book_chapter', array('bid'=>$bid, 'id'=>$cid), array('page'=>$pagenum['num'], 'pagenum'=>count($pagesData)));
			output(1, lang('success'));
		} else {
			output(2204, lang('chapter_empty'));
		}
	}

	/**
	 * 查看本书章节和分页
	 */
	public function getBookContents() {
		//getSign(__function__, $this->input->get_post('random'), $this->input->get_post('sign'));

		$bid = intval($this->input->get('bookId'));
		$type = intval($this->input->get('type'));
		$cid = intval($this->input->get('chapterId'));
		if(!$bid) exit(lang('book_id_error'));

		$type = $type ? $type : 1;
		if($type == 2) {
			$this->_data['chapters'] = $this->base->get_data('book_chapter', array('bid'=>$bid))->result_array();
		} else if($type == 3 && $cid) {
			$this->_data['pages'] = $this->base->get_data('book_pages', array('bid'=>$bid, 'cid'=>$cid))->result_array();
		} else {
			$this->_data['book'] = $this->base->get_data('book', array('id'=>$bid))->row_array();
		}
		$this->_data['bookId'] = $bid;
		$this->_data['type'] = $type;
		$this->load->view('api/bookContent', $this->_data);
	}

	/**
	 * 获取单页内容
	 * page 章节页码
	 */
	public function getPageContent() {
		getSign(__function__, $this->input->get_post('random'), $this->input->get_post('sign'));

		//检查登陆状态
		//if(!$this->member) output(1008, lang('login_first'));

		$bid = intval($this->input->get_post('bookId'));
		$cid = intval($this->input->get_post('chapterId'));
		$page = intval($this->input->get_post('page'));
		if(!$bid) output(1006, lang('book_id_error'));
		
		$chapter = $this->base->get_data('book_chapter', array('id'=>$cid), 'page')->row_array();
		if(!$cid || !$chapter) output(1007, lang('chapterId_error'));	

		$chapterPage = $page - $chapter['page'];
		if(!$page || $chapterPage <= 0) output(2205, lang('page_error'));	

		$pageContent = $this->base->get_data('book_pages', array('bid'=>$bid, 'cid'=>$cid, 'num'=>$chapterPage), 'content')->row_array();
		if(!$pageContent) output(1009, lang('content_error'));

		if($this->member) {
			$farpage = $this->base->get_data('book_farpage', array('bid'=>$bid, 'uid'=>$this->member['uid']))->row_array();
			if(!$farpage) {
				$this->base->insert_data('book_farpage', array('bid'=>$bid, 'uid'=>$this->member['uid'], 'cid'=>$cid, 'page'=>$page));
			} else if(isset($farpage['page']) && $farpage['page'] < $page) {
				$this->base->update_data('book_farpage', array('bid'=>$bid, 'uid'=>$this->member['uid']), array('cid'=>$cid, 'page'=>$page));
			}
		}

		output(1, array($pageContent));
	}

	/**
	 * 收藏书本
	 */
	public function addFav() {
		getSign(__function__, $this->input->get_post('random'), $this->input->get_post('sign'));

		//检查登陆状态
		if(!$this->member) output(1008, lang('login_first'));
		$bid = intval($this->input->post('bookId'));
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
		getSign(__function__, $this->input->get_post('random'), $this->input->get_post('sign'));

		//检查登陆状态
		if(!$this->member) output(1008, lang('login_first'));
		$bid = intval($this->input->post('bookId'));
		if(!$bid) output(1006, lang('book_id_error'));

		$re = $this->base->del_data('book_fav', array('uid'=>$this->member['uid'], 'bid'=>$bid));
		if($re) {
			output(1, lang('success'));
		} else {
			output(100, lang('cancel_fav_failed'));
		}
	}

	//是否收藏过
	public function isFav() {
		getSign(__function__, $this->input->get_post('random'), $this->input->get_post('sign'));

		//检查登陆状态
		if(!$this->member) output(1008, lang('login_first'));

		$bid = intval($this->input->get_post('bookId'));
		if(!$bid) output(1006, lang('book_id_error'));

		$fav = $this->base->get_data('book_fav', array('bid'=>$bid, 'uid'=>$this->member['uid']))->num_rows();

		output(1, $fav);
	}

	/**
	 * 获取我对书本的评论
	 */
	public function getMyReview() {
		getSign(__function__, $this->input->get_post('random'), $this->input->get_post('sign'));

		//检查登陆状态
		if(!$this->member) output(1008, lang('login_first'));
		$bid = intval($this->input->get_post('bookId'));
		if(!$bid) output(1006, lang('book_id_error'));		

		$review = $this->base->get_data('book_reviews', array('bid'=>$bid, 'uid'=>$this->member['uid']), 'title, score, content', 0, 0, 'id DESC')->row_array();

		output(1, $review);
	}

	/**
	 * 添加笔记
	 */
	public function addNote() {
		getSign(__function__, $this->input->get_post('random'), $this->input->get_post('sign'));

		//检查登陆状态
		//if(!$this->member) output(1008, lang('login_first'));
		$this->member['uid'] = 14;
		$bid = intval($this->input->post('bookId'));
		if(!$bid) output(1006, lang('book_id_error'));	

		$cid = intval($this->input->get_post('chapterId'));
		if(!$cid) output(1007, lang('chapterId_error'));

		$num = $this->base->get_data('book_chapter', array('id'=>$cid, 'bid'=>$bid))->num_rows();
		if(!$num) output(1007, lang('chapterId_error'));

		$page = intval($this->input->post('page'));
		if(!$page) output(2209, lang('page_empty'));

		$insert_data = array(
			'bid'			=> $bid,
			'cid'			=> $cid,
			'uid'			=> $this->member['uid'],
			'page'			=> $page,
			'charContent'	=> $this->input->post('charContent'),
			'charBegin'		=> $this->input->post('charBegin'),
			'noteContent'	=> $this->input->post('noteContent'),
			'content'		=> $this->input->post('content'),
			'ctime'			=> time(),
		);

		$re = $this->base->insert_data('book_note', $insert_data);

		if($re) { 
			output(1, array('noteId'=>$re));
		} else {
			output(100, lang('failed'));
		}
	}

	/**
	 * 修改笔记
	 */
	public function editNote() {
		getSign(__function__, $this->input->get_post('random'), $this->input->get_post('sign'));

		//检查登陆状态
		//if(!$this->member) output(1008, lang('login_first');
		$this->member['uid'] = 14;
		$bid = intval($this->input->post('bookId'));
		if(!$bid) output(1006, lang('book_id_error'));	

		$nid = intval($this->input->get_post('noteId'));
		if(!$nid) output(1011, lang('noteId_error'));

		$num = $this->base->get_data('book_note', array('id'=>$nid, 'bid'=>$bid, 'uid'=>$this->member['uid']))->num_rows();
		if(!$num) output(1011, lang('noteId_error'));


		$update_data = array(
			'content'		=> $this->input->post('content'),
			'ctime'			=> time(),
		);

		$re = $this->base->update_data('book_note', array('id'=>$nid, 'bid'=>$bid, 'uid'=>$this->member['uid']), $update_data);

		if($re) { 
			output(1, lang('success'));
		} else {
			output(100, lang('failed'));
		}
	}	

	/**
	 * 删除笔记
	 */
	public function delNote() {
		getSign(__function__, $this->input->get_post('random'), $this->input->get_post('sign'));

		//检查登陆状态
		//if(!$this->member) output(1008, lang('login_first'));
		$this->member['uid'] = 14;
		$bid = intval($this->input->post('bookId'));
		if(!$bid) output(1006, lang('book_id_error'));	

		$nid = intval($this->input->get_post('noteId'));
		if(!$nid) output(1011, lang('noteId_error'));

		$re = $this->base->del_data('book_note', array('id'=>$nid, 'bid'=>$bid, 'uid'=>$this->member['uid']));

		if($re) { 
			output(1, lang('success'));
		} else {
			output(100, lang('failed'));
		}
	}	

	/**
	 * 获取笔记列表
	 */
	public function getNote() {
		getSign(__function__, $this->input->get_post('random'), $this->input->get_post('sign'));

		//检查登陆状态
		//if(!$this->member) output(1008, lang('login_first'));
		$this->member['uid'] = 14;
		$bid = intval($this->input->get_post('bookId'));
		if(!$bid) output(1006, lang('book_id_error'));	

		$noteList = $chapterList = array();
		$chapterResult = $this->base->get_data('book_chapter', array('bid'=>$bid), 'id, title')->result_array();
		foreach($chapterResult as $v) {
			$chapterList[$v['id']] = $v['title'];
		}

		$noteResult = $this->base->get_data('book_note', array('bid'=>$bid, 'uid'=>$this->member['uid']), 'id, cid, charContent, charBegin, page, content')->result_array();
		if($noteResult && $chapterList) {
			foreach($noteResult as $v) {
				if(isset($chapterList[$v['cid']])) {
					$noteList[$v['cid']]['title'] = $chapterList[$v['cid']];
					$noteList[$v['cid']]['notes'][] = array('noteId'=>$v['id'], 'chapterId'=>$v['cid'], 'charContent'=>$v['charContent'], 'charBegin'=>$v['charBegin'], 'page'=>$v['page'], 'content'=>$v['content']);
				}
			}
		}

		output(1, $noteList);
	}

	/**
	 * 添加书签
	 */
	public function addBookmark() {
		getSign(__function__, $this->input->get_post('random'), $this->input->get_post('sign'));

		//检查登陆状态
		//if(!$this->member) output(1008, lang('login_first'));
		$this->member['uid'] = 14;
		$bid = intval($this->input->get_post('bookId'));
		if(!$bid) output(1006, lang('book_id_error'));	

		$cid = intval($this->input->get_post('chapterId'));
		if(!$cid) output(1007, lang('chapterId_error'));

		$num = $this->base->get_data('book_chapter', array('id'=>$cid, 'bid'=>$bid))->num_rows();
		if(!$num) output(1007, lang('chapterId_error'));

		$page = intval($this->input->post('page'));
		if(!$page) output(2209, lang('page_empty'));	
		
		$insert_data = array(
			'bid'			=> $bid,
			'cid'			=> $cid,
			'uid'			=> $this->member['uid'],
			'page'			=> $page,
			'content'		=> $this->input->post('content'),
			'ctime'			=> time(),
		);

		$re = $this->base->insert_data('bookmark', $insert_data);

		if($re) { 
			output(1, array('bookmarkId'=>$re));
		} else {
			output(100, lang('failed'));
		}					
	}

	/**
	 * 删除书签
	 */
	public function delBookmark() {
		getSign(__function__, $this->input->get_post('random'), $this->input->get_post('sign'));

		//检查登陆状态
		//if(!$this->member) output(1008, lang('login_first'));
		$this->member['uid'] = 14;
		$bid = intval($this->input->get_post('bookId'));
		if(!$bid) output(1006, lang('book_id_error'));	

		$bkid = intval($this->input->get_post('bookmarkId'));
		if(!$bkid) output(1010, '书签id错误');

		$re = $this->base->del_data('bookmark', array('id'=>$bkid, 'bid'=>$bid, 'uid'=>$this->member['uid']));

		if($re) { 
			output(1, lang('success'));
		} else {
			output(100, lang('failed'));
		}					
	}	

	/**
	 * 获取书签列表
	 */
	public function getBookmark() {
		getSign(__function__, $this->input->get_post('random'), $this->input->get_post('sign'));

		//检查登陆状态
		//if(!$this->member) output(1008, lang('login_first'));
		$this->member['uid'] = 14;
		$bid = intval($this->input->get_post('bookId'));
		if(!$bid) output(1006, lang('book_id_error'));	

		$markList = $chapterList = array();
		$chapterResult = $this->base->get_data('book_chapter', array('bid'=>$bid), 'id, title')->result_array();
		foreach($chapterResult as $v) {
			$chapterList[$v['id']] = $v['title'];
		}

		$markResult = $this->base->get_data('bookmark', array('bid'=>$bid, 'uid'=>$this->member['uid']), 'id, cid, page, content')->result_array();
		if($markResult && $chapterList) {
			foreach($markResult as $v) {
				if(isset($chapterList[$v['cid']])) {
					$markList[$v['cid']]['title'] = $chapterList[$v['cid']];
					$markList[$v['cid']]['bookmarks'][] = array('bookmarkId'=>$v['id'], 'chapterId'=>$v['cid'], 'content'=>$v['content'], 'page'=>$v['page']);
				}
			}
		}

		output(1, $markList);
	}	

	/**
	 * 判断此页是否需要付费
	 */
	public function isPayPage() {
		write_log(debug($_REQUEST, 0, 1));
		getSign(__function__, $this->input->get_post('random'), $this->input->get_post('sign'));

		$bid = intval($this->input->get_post('bookId'));
		$cid = intval($this->input->get_post('chapterId'));
		$page = intval($this->input->get_post('page'));

		$res = $this->isPayFun($bid, $cid, $page);
		output(1, $res);
	}

	/**
	 * 付费
	 */	
	public function payPage() {
		write_log(debug($_REQUEST, 0, 1));
		getSign(__function__, $this->input->get_post('random'), $this->input->get_post('sign'));

		//检查登陆状态
		if(!$this->member) output(1008, lang('login_first'));

		$bid = intval($this->input->get_post('bookId'));
		$cid = intval($this->input->get_post('chapterId'));
		$page = intval($this->input->get_post('page'));
		$uid = $this->member['uid'];
		$res = $this->isPayFun($bid, $cid, $page, true);

		//不需要付费，直接返回成功
		if($res == 0) {
			output(1, lang('success'));
		}

		//从用户账户里扣钱
		if($res > 0) {		
			$ret = $this->db->query("UPDATE yos_account SET deposit = deposit-".$res." WHERE uid=".$uid);
			if($ret) {
				set_cookie('noWin_'.$bid, $bid.'_'.$this->member['uid'], 3600*24*180);
				output(1, lang('success'));
			} else {
				output(100, lang('failed'));
			}
		}
	}

	/**
	 * 判断需要付积分钱
	 */
	private function isPayFun($bid, $cid, $page, $insert=false) {
		//检查登陆状态
		if(!$this->member) output(1008, lang('login_first'));

		if(!$bid) output(1006, lang('book_id_error'));	

		if(!$cid) output(1007, lang('chapterId_error'));

		if(!$page) output(2205, lang('page_error'));

		$chapter = $this->base->get_data('book_chapter', array('id'=>$cid), 'page,dis')->row_array();
		if(!$chapter) output(1007, lang('chapterId_error'));

		$chapterPage = $page - $chapter['page'];
		if($chapterPage <= 0) output(2205, lang('page_error'));

		$book = $this->base->get_data('book', array('id'=>$bid), 'text_price, paid_section_start, paid_section_end')->row_array();

		if($chapter['dis'] < $book['paid_section_start'] || $book['text_price'] <= 0) {
			return 0;
		}

		$uid = $this->member['uid'];

		$payedNum = $this->base->get_data('payed_pages', array('uid'=>$uid, 'bid'=>$bid, 'cid'=>$cid, 'num'=>$chapterPage), '*')->num_rows();

		write_log("payedNum:".$payedNum, 'paybook');
		if($payedNum > 0) return 0;

		$pageContent = $this->base->get_data('book_pages', array('bid'=>$bid, 'cid'=>$cid, 'num'=>$chapterPage), 'words')->row_array();

		$payedInfo = $this->db->query('SELECT SUM(words) totalWord FROM yos_payed_pages WHERE uid='.$uid.' AND bid='.$bid)->row_array();
		$yu = $payedInfo['totalWord']%1000;

		$insert_data = array(
			'uid'	=> $uid,
			'bid'	=> $bid,
			'cid'	=> $cid,
			'words'	=> $pageContent['words'],
			'num'	=> $chapterPage,
			'ctime'	=> time()
		);

		if(($pageContent['words'] + $yu) < 1000) {
			write_log("xiaoyu 1000:".($pageContent['words'] + $yu), 'paybook');
			//if($insert == true) $this->base->insert_data('payed_pages', $insert_data);
			$this->base->insert_data('payed_pages', $insert_data);
			return 0;
		} else {
			$part = intval(($pageContent['words'] + $yu)/1000);
			$res = $part*$book['text_price'];	

			if($insert == true) {
				$user = $this->base->get_data('account', array('uid'=>$uid))->row_array();

				//账户余额不足
				if($user['deposit'] < $res) {
					output(3004, lang('deposit_error'));
				}
				$insert_data['payed'] = $res;
				$this->base->insert_data('payed_pages', $insert_data);
			}

			return $res;
		}
	}

	//获取阅读
	public function getFarPage() {
		$bid = intval($this->input->get_post('bookId'));
		if(!$bid) output(1006, lang('book_id_error'));

		//检查登陆状态
		if(!$this->member) output(1008, lang('login_first'));

		$farpage = $this->base->get_data('book_farpage', array('bid'=>$bid, 'uid'=>$this->member['uid']))->row_array();

		if(isset($farpage['page']) && $farpage['page'] > 0) {
			output(1, $farpage['page']);
		} else {
			output(1, 1);
		}
	}


	/**
	 * 文档显示
	 */
	public function views() {

		$this->_data['declare'] = array(
			array(
				'id'		=> 'book_getDirectory',
				'title'		=> '获取书本目录列表',
				'useway'	=> 'GET/POST',
				'apiurl'	=>  site_url('api/book/getDirectory'),
				'sam'		=>  site_url('apitest#book_getDirectory'),
				'arguments'	=> array(
					array('name'=>'random',		'type'=>'Int',		'value'=>'随机数', 'desc'=>'随机数'),
					array('name'=>'sign',		'type'=>'String',	'value'=>'strtoupper(md5("getDirectory".$random.$signPostfix));', 'desc'=>'验证字符串'),
					array('name'=>'bookId',		'type'=>'Int',		'value'=>'书本id', 'desc'=>'书本id'),
				),
				'code'		=> array(
					array('result'=>1,		'message'=>'目录列表',		'desc'=>'获取成功'),
					array('result'=>1006,	'message'=>'书本id错误',		'desc'=>'书本id错误'),
					array('result'=>3001,	'message'=>'验证字符串错误',	'desc'=>'验证字符串错误'),
					array('result'=>3002,	'message'=>'随机数不能为空',	'desc'=>'随机数不能为空'),
					array('result'=>3003,	'message'=>'验证失败',		'desc'=>'验证失败'),
				),
				'subdesc'=>array(
					array('title' => 'message数据格式','arguments'=>
						array(
							array('name'=>'chapterId',		'type'=>'String',	'desc'=>'章节id'),
							array('name'=>'page',	'type'=>'Int',	'desc'=>'全书本章起始页'),
							array('name'=>'pagenum','type'=>'Int',	'desc'=>'章节总页数'),
							array('name'=>'title',	'type'=>'String',	'desc'=>'章节标题'),
							array('name'=>'dis',	'type'=>'String',	'desc'=>'章节序号'),
						),
					),
				),				
			),
			array(
				'id'		=> 'book_getChapter',
				'title'		=> '获取章节内容',
				'useway'	=> 'GET/POST',
				'apiurl'	=>  site_url('api/book/getChapter'),
				'sam'		=>  site_url('apitest#book_getChapter'),
				'arguments'	=> array(
					array('name'=>'random',		'type'=>'Int',		'value'=>'随机数', 'desc'=>'随机数'),
					array('name'=>'sign',		'type'=>'String',	'value'=>'strtoupper(md5("getChapter".$random.$signPostfix));', 'desc'=>'验证字符串'),
					array('name'=>'bookId',		'type'=>'Int',		'value'=>'书本id', 'desc'=>'书本id'),
					array('name'=>'chapterId',	'type'=>'Int',		'value'=>'章节id', 'desc'=>'章节id'),
				),
				'code'		=> array(
					array('result'=>1,		'message'=>'章节信息',		'desc'=>'获取成功'),
					array('result'=>1006,	'message'=>'书本id错误',		'desc'=>'传了空值'),
					array('result'=>1007,	'message'=>'章节id错误',		'desc'=>'传了空值'),
					array('result'=>3001,	'message'=>'验证字符串错误',	'desc'=>'验证字符串错误'),
					array('result'=>3002,	'message'=>'随机数不能为空',	'desc'=>'随机数不能为空'),
					array('result'=>3003,	'message'=>'验证失败',		'desc'=>'验证失败'),
					array('result'=>2204,	'message'=>'章节为空或者此书没有该章节',	'desc'=>'章节为空或者此书没有该章节'),
				),
				'subdesc'=>array(
					array('title' => 'message数据格式','arguments'=>
						array(
							array('name'=>'chapterId',		'type'=>'Int',		'desc'=>'章节id'),
							array('name'=>'bookId',			'type'=>'Int',		'desc'=>'书本id'),
							array('name'=>'content',		'type'=>'String',	'desc'=>'章节内容'),
						),
					),
				),				
			),
			array(
				'id'		=> 'book_getGenre',
				'title'		=> '获取书本分类',
				'useway'	=> 'GET/POST',
				'apiurl'	=>  site_url('api/book/getGenre'),
				'sam'		=>  site_url('apitest#book_getGenre'),
				'arguments'	=> array(
					array('name'=>'random',		'type'=>'Int',		'value'=>'随机数', 'desc'=>'随机数'),
					array('name'=>'sign',		'type'=>'String',	'value'=>'strtoupper(md5("getGenre".$random.$signPostfix));', 'desc'=>'验证字符串'),
				),
				'code'		=> array(
					array('result'=>1,	'message'=>'类目信息',		'desc'=>'获取成功'),
					array('result'=>3001,	'message'=>'验证字符串错误',	'desc'=>'验证字符串错误'),
					array('result'=>3002,	'message'=>'随机数不能为空',	'desc'=>'随机数不能为空'),
					array('result'=>3003,	'message'=>'验证失败',		'desc'=>'验证失败'),
				),
				'subdesc'=>array(
					array('title' => 'message数据格式','arguments'=>
						array(
							array('name'=>'genreId',		'type'=>'Int',		'desc'=>'分类id'),
							array('name'=>'name',			'type'=>'String',	'desc'=>'分类名称'),
						),
					),
				),				
			),
			array(
				'id'		=> 'book_getBooks',
				'title'		=> '获取书本列表',
				'useway'	=> 'GET/POST',
				'apiurl'	=>  site_url('api/book/getBooks'),
				'sam'		=>  site_url('apitest#book_getBooks'),
				'arguments'	=> array(
					array('name'=>'random',		'type'=>'Int',		'value'=>'随机数', 'desc'=>'随机数'),
					array('name'=>'sign',		'type'=>'String',	'value'=>'strtoupper(md5("getBooks".$random.$signPostfix));', 'desc'=>'验证字符串'),
					array('name'=>'genreId',	'type'=>'Int',		'value'=>'分类id', 'desc'=>'可选，不填返回所有'),
					array('name'=>'type',		'type'=>'String',	'value'=>'个性话题', 'desc'=>'可选，不填返回所有，continue、mylist、toppicks、popular、bestrated'),
					array('name'=>'pageSize',	'type'=>'Int',		'value'=>'每页书本数量', 'desc'=>'每页书本数量'),
					array('name'=>'page',		'type'=>'Int',		'value'=>'请求页码', 'desc'=>'请求页码'),
				),
				'code'		=> array(
					array('result'=>1,	'message'=>'列表信息',		'desc'=>'获取成功'),
					array('result'=>3001,	'message'=>'验证字符串错误',	'desc'=>'验证字符串错误'),
					array('result'=>3002,	'message'=>'随机数不能为空',	'desc'=>'随机数不能为空'),
					array('result'=>3003,	'message'=>'验证失败',		'desc'=>'验证失败'),
				),
				'subdesc'=>array(
					array('title' => 'message数据格式','arguments'=>
						array(
							array('name'=>'bookId',		'type'=>'Int',		'desc'=>'书本id'),
							array('name'=>'title',		'type'=>'String',	'desc'=>'书名'),
							array('name'=>'cover',		'type'=>'String',	'desc'=>'书本封面'),
							array('name'=>'genreId',	'type'=>'Int',		'desc'=>'分类id'),
						),
					),
				),				
			),
			array(
				'id'		=> 'book_getBookInfo',
				'title'		=> '获取书本信息',
				'useway'	=> 'GET/POST',
				'apiurl'	=>  site_url('api/book/getBookInfo'),
				'sam'		=>  site_url('apitest#book_getBookInfo'),
				'arguments'	=> array(
					array('name'=>'random',		'type'=>'Int',		'value'=>'随机数', 'desc'=>'随机数'),
					array('name'=>'sign',		'type'=>'String',	'value'=>'strtoupper(md5("getBookInfo".$random.$signPostfix));', 'desc'=>'验证字符串'),
					array('name'=>'bookId',		'type'=>'Int',		'value'=>'书本id', 'desc'=>'书本id'),
				),
				'code'		=> array(
					array('result'=>1,	'message'=>'书本信息',		'desc'=>'获取成功'),
					array('result'=>3001,	'message'=>'验证字符串错误',	'desc'=>'验证字符串错误'),
					array('result'=>3002,	'message'=>'随机数不能为空',	'desc'=>'随机数不能为空'),
					array('result'=>3003,	'message'=>'验证失败',		'desc'=>'验证失败'),
				),
				'subdesc'=>array(
					array('title' => 'message数据格式','arguments'=>
						array(
							array('name'=>'bookId',			'type'=>'Int',		'desc'=>'书本id'),
							array('name'=>'authorid',		'type'=>'Int',		'desc'=>'作者id'),
							array('name'=>'author',			'type'=>'String',	'desc'=>'作者用户名'),
							array('name'=>'title',			'type'=>'String',	'desc'=>'书名'),
							array('name'=>'cover',			'type'=>'String',	'desc'=>'书本封面'),
							array('name'=>'publisher',		'type'=>'String',	'desc'=>'出版社'),
							array('name'=>'genreId',		'type'=>'Int',		'desc'=>'分类id'),
							array('name'=>'textPrice',		'type'=>'Float',	'desc'=>'文字单价'),
							array('name'=>'textPriceTitle',	'type'=>'String',	'desc'=>'文字单价描述'),
							array('name'=>'audioPrice',		'type'=>'Float',	'desc'=>'音频单价'),
							array('name'=>'audioPriceTitle','type'=>'String',	'desc'=>'音频单价描述'),
							array('name'=>'score',			'type'=>'Int',		'desc'=>'平均评分'),
							array('name'=>'scorenum',		'type'=>'Int',		'desc'=>'评分人数'),
							array('name'=>'description',	'type'=>'Text',		'desc'=>'简介'),
							array('name'=>'ctime',			'type'=>'Int',		'desc'=>'书本创建时间戳'),
							array('name'=>'mtime',			'type'=>'Int',		'desc'=>'书本最后更新时间戳'),
							array('name'=>'paid_section_start',			'type'=>'Int',		'desc'=>'开始的付费章节序号  0为不设置'),
							array('name'=>'paid_section_end',			'type'=>'Int',		'desc'=>'结束的付费章节序号  0为不设置'),
							array('name'=>'hasPayed',			'type'=>'Int',		'desc'=>'是否付费过  0为没付过，1为付过'),
						),
					),
				),				
			),
			array(
				'id'		=> 'book_writeRviews',
				'title'		=> '发表评论',
				'useway'	=> 'POST',
				'apiurl'	=>  site_url('api/book/writeRviews'),
				'sam'		=>  site_url('apitest#book_writeRviews'),
				'arguments'	=> array(
					array('name'=>'random',		'type'=>'Int',		'value'=>'随机数', 	'desc'=>'随机数'),
					array('name'=>'sign',		'type'=>'String',	'value'=>'strtoupper(md5("writeRviews".$random.$signPostfix));', 'desc'=>'验证字符串'),
					array('name'=>'bookId',		'type'=>'Int',		'value'=>'书本id', 	'desc'=>'书本id'),
					array('name'=>'score',		'type'=>'Int',		'value'=>'分数', 	'desc'=>'分数'),
					array('name'=>'title',		'type'=>'String',	'value'=>'评论标题', 'desc'=>'评论标题'),
					array('name'=>'content',	'type'=>'Text',		'value'=>'评论内容', 'desc'=>'评论内容'),
				),
				'code'		=> array(
					array('result'=>1,		'message'=>'评论成功',		'desc'=>'评论成功'),
					array('result'=>1006,	'message'=>'书本id错误',		'desc'=>'书本id错误'),
					array('result'=>1008,	'message'=>'请先登陆',		'desc'=>'请先登陆'),
					array('result'=>2203,	'message'=>'评分需在1-5分之间',		'desc'=>'评分需在1-5分之间'),
					array('result'=>3001,	'message'=>'验证字符串错误',	'desc'=>'验证字符串错误'),
					array('result'=>3002,	'message'=>'随机数不能为空',	'desc'=>'随机数不能为空'),
					array('result'=>3003,	'message'=>'验证失败',		'desc'=>'验证失败'),
				),			
			),				
			array(
				'id'		=> 'book_getReviews',
				'title'		=> '获取书本评论',
				'useway'	=> 'GET/POST',
				'apiurl'	=>  site_url('api/book/getReviews'),
				'sam'		=>  site_url('apitest#book_getReviews'),
				'arguments'	=> array(
					array('name'=>'random',		'type'=>'Int',		'value'=>'随机数', 	'desc'=>'随机数'),
					array('name'=>'sign',		'type'=>'String',	'value'=>'strtoupper(md5("getReviews".$random.$signPostfix));', 'desc'=>'验证字符串'),
					array('name'=>'bookId',		'type'=>'Int',		'value'=>'书本id', 	'desc'=>'书本id'),
				),
				'code'		=> array(
					array('result'=>1,		'message'=>'评论列表',		'desc'=>'获取成功'),
					array('result'=>1006,	'message'=>'书本id错误',		'desc'=>'书本id错误'),
					array('result'=>3001,	'message'=>'验证字符串错误',	'desc'=>'验证字符串错误'),
					array('result'=>3002,	'message'=>'随机数不能为空',	'desc'=>'随机数不能为空'),
					array('result'=>3003,	'message'=>'验证失败',		'desc'=>'验证失败'),
				),	
				'subdesc'=>array(
					array('title' => 'message数据格式','arguments'=>
						array(
							array('name'=>'username',		'type'=>'String',	'desc'=>'作者用户名'),
							array('name'=>'title',			'type'=>'String',	'desc'=>'评论标题'),
							array('name'=>'score',			'type'=>'Int',		'desc'=>'评分'),
							array('name'=>'content',		'type'=>'Text',		'desc'=>'评论内容'),
							array('name'=>'ctime',			'type'=>'Int',		'desc'=>'评论时间戳'),
						),
					),
				),							
			),
			array(
				'id'		=> 'book_getMyReviews',
				'title'		=> '获取我对书本的评论',
				'useway'	=> 'GET/POST',
				'apiurl'	=>  site_url('api/book/getMyReviews'),
				'sam'		=>  site_url('apitest#book_getReviews'),
				'arguments'	=> array(
					array('name'=>'random',		'type'=>'Int',		'value'=>'随机数', 	'desc'=>'随机数'),
					array('name'=>'sign',		'type'=>'String',	'value'=>'strtoupper(md5("getMyReviews".$random.$signPostfix));', 'desc'=>'验证字符串'),
					array('name'=>'bookId',		'type'=>'Int',		'value'=>'书本id', 	'desc'=>'书本id'),
				),
				'code'		=> array(
					array('result'=>1,		'message'=>'评论列表',		'desc'=>'获取成功'),
					array('result'=>1006,	'message'=>'书本id错误',		'desc'=>'书本id错误'),
					array('result'=>3001,	'message'=>'验证字符串错误',	'desc'=>'验证字符串错误'),
					array('result'=>3002,	'message'=>'随机数不能为空',	'desc'=>'随机数不能为空'),
					array('result'=>3003,	'message'=>'验证失败',		'desc'=>'验证失败'),
				),	
				'subdesc'=>array(
					array('title' => 'message数据格式','arguments'=>
						array(
							array('name'=>'username',		'type'=>'String',	'desc'=>'作者用户名'),
							array('name'=>'title',			'type'=>'String',	'desc'=>'评论标题'),
							array('name'=>'score',			'type'=>'Int',		'desc'=>'评分'),
							array('name'=>'content',		'type'=>'Text',		'desc'=>'评论内容'),
							array('name'=>'ctime',			'type'=>'Int',		'desc'=>'评论时间戳'),
						),
					),
				),							
			),
			array(
				'id'		=> 'book_inputPages',
				'title'		=> '接收段落的单页内容',
				'useway'	=> 'POST',
				'apiurl'	=>  site_url('api/book/inputPages'),
				'sam'		=>  site_url('apitest#book_inputPages'),
				'arguments'	=> array(
					array('name'=>'random',		'type'=>'Int',		'value'=>'随机数', 	'desc'=>'随机数'),
					array('name'=>'sign',		'type'=>'String',	'value'=>'strtoupper(md5("inputPages".$random.$signPostfix));', 'desc'=>'验证字符串'),
					array('name'=>'bookId',		'type'=>'Int',		'value'=>'书本id', 	'desc'=>'书本id'),
					array('name'=>'chapterId',	'type'=>'Int',		'value'=>'章节id', 	'desc'=>'章节id'),
					array('name'=>'pages',		'type'=>'Text',		'value'=>'单页的json数据', 'desc'=>'单页的json数据'),
				),
				'code'		=> array(
					array('result'=>1,	'message'=>'分页成功添加',		'desc'=>'分页成功添加'),
					array('result'=>3001,	'message'=>'验证字符串错误',	'desc'=>'验证字符串错误'),
					array('result'=>3002,	'message'=>'随机数不能为空',	'desc'=>'随机数不能为空'),
					array('result'=>3003,	'message'=>'验证失败',		'desc'=>'验证失败'),
				),			
			),	
			array(
				'id'		=> 'book_getScoreInfo',
				'title'		=> '获取书本评分情况',
				'useway'	=> 'POST',
				'apiurl'	=>  site_url('api/book/getScoreInfo'),
				'sam'		=>  site_url('apitest#book_getScoreInfo'),
				'arguments'	=> array(
					array('name'=>'random',		'type'=>'Int',		'value'=>'随机数', 	'desc'=>'随机数'),
					array('name'=>'sign',		'type'=>'String',	'value'=>'strtoupper(md5("getScoreInfo".$random.$signPostfix));', 'desc'=>'验证字符串'),
					array('name'=>'bookId',		'type'=>'Int',		'value'=>'书本id', 	'desc'=>'书本id'),
				),
				'code'		=> array(
					array('result'=>1,		'message'=>'获取成功',		'desc'=>'对应书本1到5星的评价人数'),
					array('result'=>3001,	'message'=>'验证字符串错误',	'desc'=>'验证字符串错误'),
					array('result'=>3002,	'message'=>'随机数不能为空',	'desc'=>'随机数不能为空'),
					array('result'=>3003,	'message'=>'验证失败',		'desc'=>'验证失败'),
				),							
			),	
			array(
				'id'		=> 'book_getPageContent',
				'title'		=> '获取单页内容',
				'useway'	=> 'GET/POST',
				'apiurl'	=>  site_url('api/book/getPageContent'),
				'sam'		=>  site_url('apitest#book_getPageContent'),
				'arguments'	=> array(
					array('name'=>'random',		'type'=>'Int',		'value'=>'随机数', 	'desc'=>'随机数'),
					array('name'=>'sign',		'type'=>'String',	'value'=>'strtoupper(md5("getPageContent".$random.$signPostfix));', 'desc'=>'验证字符串'),
					array('name'=>'bookId',		'type'=>'Int',		'value'=>'书本id', 	'desc'=>'书本id'),
					array('name'=>'chapterId',	'type'=>'Int',		'value'=>'章节id', 	'desc'=>'章节id'),
					array('name'=>'page',		'type'=>'Int',		'value'=>'页码', 	'desc'=>'页码'),
				),
				'code'		=> array(
					array('result'=>1,		'message'=>'获取成功',		'desc'=>'对应书本1到5星的评价人数'),
					array('result'=>1006,	'message'=>'书本id错误',		'desc'=>'书本id错误'),
					array('result'=>1007,	'message'=>'章节id错误',		'desc'=>'传了空值'),
					array('result'=>1008,	'message'=>'请先登陆',		'desc'=>'请先登陆'),
					array('result'=>1009,	'message'=>'内容为空',		'desc'=>'内容为空'),
					array('result'=>2205,	'message'=>'页码错误',		'desc'=>'页码错误'),
					array('result'=>3001,	'message'=>'验证字符串错误',	'desc'=>'验证字符串错误'),
					array('result'=>3002,	'message'=>'随机数不能为空',	'desc'=>'随机数不能为空'),
					array('result'=>3003,	'message'=>'验证失败',		'desc'=>'验证失败'),
				),							
			),	
			array(
				'id'		=> 'book_addFav',
				'title'		=> '收藏书本',
				'useway'	=> 'POST',
				'apiurl'	=>  site_url('api/book/addFav'),
				'sam'		=>  site_url('apitest#book_addFav'),
				'arguments'	=> array(
					array('name'=>'random',		'type'=>'Int',		'value'=>'随机数', 	'desc'=>'随机数'),
					array('name'=>'sign',		'type'=>'String',	'value'=>'strtoupper(md5("addFav".$random.$signPostfix));', 'desc'=>'验证字符串'),
					array('name'=>'bookId',		'type'=>'Int',		'value'=>'书本id', 	'desc'=>'书本id'),
				),
				'code'		=> array(
					array('result'=>1,		'message'=>'收藏成功',		'desc'=>'收藏成功'),
					array('result'=>1006,	'message'=>'书本id错误',		'desc'=>'书本id错误'),
					array('result'=>1008,	'message'=>'请先登陆',		'desc'=>'请先登陆'),
					array('result'=>2206,	'message'=>'已经收藏过',		'desc'=>'已经收藏过'),
					array('result'=>2207,	'message'=>'收藏失败',		'desc'=>'收藏失败'),
					array('result'=>3001,	'message'=>'验证字符串错误',	'desc'=>'验证字符串错误'),
					array('result'=>3002,	'message'=>'随机数不能为空',	'desc'=>'随机数不能为空'),
					array('result'=>3003,	'message'=>'验证失败',		'desc'=>'验证失败'),
				),							
			),	
			array(
				'id'		=> 'book_delFav',
				'title'		=> '取消收藏',
				'useway'	=> 'POST',
				'apiurl'	=>  site_url('api/book/delFav'),
				'sam'		=>  site_url('apitest#book_delFav'),
				'arguments'	=> array(
					array('name'=>'random',		'type'=>'Int',		'value'=>'随机数', 	'desc'=>'随机数'),
					array('name'=>'sign',		'type'=>'String',	'value'=>'strtoupper(md5("delFav".$random.$signPostfix));', 'desc'=>'验证字符串'),
					array('name'=>'bookId',		'type'=>'Int',		'value'=>'书本id', 	'desc'=>'书本id'),
				),
				'code'		=> array(
					array('result'=>1,		'message'=>'取消收藏成功',	'desc'=>'取消收藏成功'),
					array('result'=>100,	'message'=>'取消收藏失败',	'desc'=>'取消收藏失败'),
					array('result'=>1006,	'message'=>'书本id错误',		'desc'=>'书本id错误'),
					array('result'=>1008,	'message'=>'请先登陆',		'desc'=>'请先登陆'),
					array('result'=>3001,	'message'=>'验证字符串错误',	'desc'=>'验证字符串错误'),
					array('result'=>3002,	'message'=>'随机数不能为空',	'desc'=>'随机数不能为空'),
					array('result'=>3003,	'message'=>'验证失败',		'desc'=>'验证失败'),
				),							
			),	
			array(
				'id'		=> 'book_isFav',
				'title'		=> '是否收藏过',
				'useway'	=> 'POST',
				'apiurl'	=>  site_url('api/book/isFav'),
				'sam'		=>  site_url('apitest#book_isFav'),
				'arguments'	=> array(
					array('name'=>'random',		'type'=>'Int',		'value'=>'随机数', 	'desc'=>'随机数'),
					array('name'=>'sign',		'type'=>'String',	'value'=>'strtoupper(md5("isFav".$random.$signPostfix));', 'desc'=>'验证字符串'),
					array('name'=>'bookId',		'type'=>'Int',		'value'=>'书本id', 	'desc'=>'书本id'),
				),
				'code'		=> array(
					array('result'=>1,		'message'=>'获取成功',		'desc'=>'返回收藏的数量，0表示没收藏过，1或大于1表示收藏过'),
					array('result'=>1006,	'message'=>'书本id错误',		'desc'=>'书本id错误'),
					array('result'=>1008,	'message'=>'请先登陆',		'desc'=>'请先登陆'),
					array('result'=>3001,	'message'=>'验证字符串错误',	'desc'=>'验证字符串错误'),
					array('result'=>3002,	'message'=>'随机数不能为空',	'desc'=>'随机数不能为空'),
					array('result'=>3003,	'message'=>'验证失败',		'desc'=>'验证失败'),
				),							
			),			
			array(
				'id'		=> 'book_getMyReview',
				'title'		=> '获取我对书本的评论',
				'useway'	=> 'GET/POST',
				'apiurl'	=>  site_url('api/book/getMyReview'),
				'sam'		=>  site_url('apitest#book_getMyReview'),
				'arguments'	=> array(
					array('name'=>'random',		'type'=>'Int',		'value'=>'随机数', 	'desc'=>'随机数'),
					array('name'=>'sign',		'type'=>'String',	'value'=>'strtoupper(md5("getMyReview".$random.$signPostfix));', 'desc'=>'验证字符串'),
					array('name'=>'bookId',		'type'=>'Int',		'value'=>'书本id', 	'desc'=>'书本id'),
				),
				'code'		=> array(
					array('result'=>1,		'message'=>'获取成功',		'desc'=>'获取成功'),
					array('result'=>1006,	'message'=>'书本id错误',		'desc'=>'书本id错误'),
					array('result'=>1008,	'message'=>'请先登陆',		'desc'=>'请先登陆'),
					array('result'=>3001,	'message'=>'验证字符串错误',	'desc'=>'验证字符串错误'),
					array('result'=>3002,	'message'=>'随机数不能为空',	'desc'=>'随机数不能为空'),
					array('result'=>3003,	'message'=>'验证失败',		'desc'=>'验证失败'),
				),	
				'subdesc'=>array(
					array('title' => 'message数据格式','arguments'=>
						array(
							array('name'=>'title',			'type'=>'String',	'desc'=>'评论标题'),
							array('name'=>'score',			'type'=>'Int',		'desc'=>'评分'),
							array('name'=>'content',		'type'=>'Text',		'desc'=>'评论内容'),
						),
					),
				),							
			),	
			array(
				'id'		=> 'book_addNote',
				'title'		=> '添加笔记',
				'useway'	=> 'POST',
				'apiurl'	=>  site_url('api/book/addNote'),
				'sam'		=>  site_url('apitest#book_addNote'),
				'arguments'	=> array(
					array('name'=>'random',		'type'=>'Int',		'value'=>'随机数', 	'desc'=>'随机数'),
					array('name'=>'sign',		'type'=>'String',	'value'=>'strtoupper(md5("addNote".$random.$signPostfix));', 'desc'=>'验证字符串'),
					array('name'=>'chapterId',	'type'=>'Int',		'value'=>'章节id', 	'desc'=>'章节id'),
					array('name'=>'bookId',		'type'=>'Int',		'value'=>'书本id', 	'desc'=>'书本id'),
					array('name'=>'page',		'type'=>'Int',		'value'=>'页码', 	'desc'=>'页码'),
					array('name'=>'charContent','type'=>'String',	'value'=>'笔记针对的文字', 	'desc'=>'笔记针对的文字'),
					array('name'=>'charBegin',	'type'=>'String',	'value'=>'笔记文字在页码的起始字符数', 	'desc'=>'笔记文字在页码的起始字符数'),
					array('name'=>'noteContent','type'=>'String',	'value'=>'笔记的内容', 	'desc'=>'笔记的内容'),
					array('name'=>'content',	'type'=>'String',	'value'=>'书签页20个字', 	'desc'=>'书签页20个字'),
				),
				'code'		=> array(
					array('result'=>1,		'message'=>'添加成功',		'desc'=>'添加成功'),
					array('result'=>100,	'message'=>'添加失败',		'desc'=>'添加失败'),
					array('result'=>1006,	'message'=>'书本id错误',		'desc'=>'书本id错误'),
					array('result'=>1008,	'message'=>'请先登陆',		'desc'=>'请先登陆'),
					array('result'=>2209,	'message'=>'页码不能为空',	'desc'=>'页码不能为空'),
					array('result'=>3001,	'message'=>'验证字符串错误',	'desc'=>'验证字符串错误'),
					array('result'=>3002,	'message'=>'随机数不能为空',	'desc'=>'随机数不能为空'),
					array('result'=>3003,	'message'=>'验证失败',		'desc'=>'验证失败'),
				),							
			),	
			array(
				'id'		=> 'book_editNote',
				'title'		=> '修改笔记',
				'useway'	=> 'POST',
				'apiurl'	=>  site_url('api/book/editNote'),
				'sam'		=>  site_url('apitest#book_editNote'),
				'arguments'	=> array(
					array('name'=>'random',		'type'=>'Int',		'value'=>'随机数', 	'desc'=>'随机数'),
					array('name'=>'sign',		'type'=>'String',	'value'=>'strtoupper(md5("editNote".$random.$signPostfix));', 'desc'=>'验证字符串'),
					array('name'=>'noteId',		'type'=>'Int',		'value'=>'笔记id', 	'desc'=>'笔记id'),
					array('name'=>'bookId',		'type'=>'Int',		'value'=>'书本id', 	'desc'=>'书本id'),
					array('name'=>'content',	'type'=>'String',	'value'=>'书签页20个字', 	'desc'=>'书签页20个字'),
				),
				'code'		=> array(
					array('result'=>1,		'message'=>'修改成功',		'desc'=>'修改成功'),
					array('result'=>100,	'message'=>'修改失败',		'desc'=>'修改失败'),
					array('result'=>1006,	'message'=>'书本id错误',		'desc'=>'书本id错误'),
					array('result'=>1011,	'message'=>'笔记id错误',		'desc'=>'笔记id错误'),
					array('result'=>1008,	'message'=>'请先登陆',		'desc'=>'请先登陆'),
					array('result'=>3001,	'message'=>'验证字符串错误',	'desc'=>'验证字符串错误'),
					array('result'=>3002,	'message'=>'随机数不能为空',	'desc'=>'随机数不能为空'),
					array('result'=>3003,	'message'=>'验证失败',		'desc'=>'验证失败'),
				),							
			),
			array(
				'id'		=> 'book_delNote',
				'title'		=> '删除笔记',
				'useway'	=> 'POST',
				'apiurl'	=>  site_url('api/book/delNote'),
				'sam'		=>  site_url('apitest#book_delNote'),
				'arguments'	=> array(
					array('name'=>'random',		'type'=>'Int',		'value'=>'随机数', 	'desc'=>'随机数'),
					array('name'=>'sign',		'type'=>'String',	'value'=>'strtoupper(md5("delNote".$random.$signPostfix));', 'desc'=>'验证字符串'),
					array('name'=>'noteId',		'type'=>'Int',		'value'=>'笔记id', 	'desc'=>'笔记id'),
					array('name'=>'bookId',		'type'=>'Int',		'value'=>'书本id', 	'desc'=>'书本id'),
				),
				'code'		=> array(
					array('result'=>1,		'message'=>'删除成功',		'desc'=>'删除成功'),
					array('result'=>100,	'message'=>'删除失败',		'desc'=>'删除失败'),
					array('result'=>1006,	'message'=>'书本id错误',		'desc'=>'书本id错误'),
					array('result'=>1011,	'message'=>'笔记id错误',		'desc'=>'笔记id错误'),
					array('result'=>1008,	'message'=>'请先登陆',		'desc'=>'请先登陆'),
					array('result'=>3001,	'message'=>'验证字符串错误',	'desc'=>'验证字符串错误'),
					array('result'=>3002,	'message'=>'随机数不能为空',	'desc'=>'随机数不能为空'),
					array('result'=>3003,	'message'=>'验证失败',		'desc'=>'验证失败'),
				),							
			),
			array(
				'id'		=> 'book_getNote',
				'title'		=> '获取笔记列表',
				'useway'	=> 'POST',
				'apiurl'	=>  site_url('api/book/getNote'),
				'sam'		=>  site_url('apitest#book_getNote'),
				'arguments'	=> array(
					array('name'=>'random',		'type'=>'Int',		'value'=>'随机数', 	'desc'=>'随机数'),
					array('name'=>'sign',		'type'=>'String',	'value'=>'strtoupper(md5("getNote".$random.$signPostfix));', 'desc'=>'验证字符串'),
					array('name'=>'bookId',		'type'=>'Int',		'value'=>'书本id', 	'desc'=>'书本id'),
				),
				'code'		=> array(
					array('result'=>1,		'message'=>'添加成功',		'desc'=>'添加成功'),
					array('result'=>1006,	'message'=>'书本id错误',		'desc'=>'书本id错误'),
					array('result'=>1008,	'message'=>'请先登陆',		'desc'=>'请先登陆'),
					array('result'=>3001,	'message'=>'验证字符串错误',	'desc'=>'验证字符串错误'),
					array('result'=>3002,	'message'=>'随机数不能为空',	'desc'=>'随机数不能为空'),
					array('result'=>3003,	'message'=>'验证失败',		'desc'=>'验证失败'),
				),
				'subdesc'=>array(
					array('title' => 'message数据格式','arguments'=>
						array(
							array('name'=>'noteId',			'type'=>'Int',	'desc'=>'笔记id'),
							array('name'=>'chapterId',		'type'=>'Int',	'desc'=>'章节id'),
							array('name'=>'title',			'type'=>'String',	'desc'=>'文章标题'),
							array('name'=>'charContent',	'type'=>'String',	'desc'=>'笔记针对的文字'),
							array('name'=>'charBegin',		'type'=>'String',	'desc'=>'笔记文字在页码的起始字符数'),
							array('name'=>'content',		'type'=>'String',	'desc'=>'笔记的内容'),
							array('name'=>'page',			'type'=>'Int张',	'desc'=>'页码'),
						),
					),
				),										
			),				
			array(
				'id'		=> 'book_addBookmark',
				'title'		=> '添加书签',
				'useway'	=> 'POST',
				'apiurl'	=>  site_url('api/book/addBookmark'),
				'sam'		=>  site_url('apitest#book_addBookmark'),
				'arguments'	=> array(
					array('name'=>'random',		'type'=>'Int',		'value'=>'随机数', 	'desc'=>'随机数'),
					array('name'=>'sign',		'type'=>'String',	'value'=>'strtoupper(md5("addBookmark".$random.$signPostfix));', 'desc'=>'验证字符串'),
					array('name'=>'bookId',		'type'=>'Int',		'value'=>'书本id', 	'desc'=>'书本id'),
					array('name'=>'chapterId',	'type'=>'Int',		'value'=>'章节id', 	'desc'=>'章节id'),
					array('name'=>'page',		'type'=>'Int',		'value'=>'页码', 	'desc'=>'页码'),
					array('name'=>'content',	'type'=>'String',	'value'=>'书签内容', 	'desc'=>'书签内容'),
				),
				'code'		=> array(
					array('result'=>1,		'message'=>'添加成功',		'desc'=>'添加成功'),
					array('result'=>1006,	'message'=>'书本id错误',		'desc'=>'书本id错误'),
					array('result'=>1008,	'message'=>'请先登陆',		'desc'=>'请先登陆'),
					array('result'=>2205,	'message'=>'页码错误',		'desc'=>'页码错误'),
					array('result'=>3001,	'message'=>'验证字符串错误',	'desc'=>'验证字符串错误'),
					array('result'=>3002,	'message'=>'随机数不能为空',	'desc'=>'随机数不能为空'),
					array('result'=>3003,	'message'=>'验证失败',		'desc'=>'验证失败'),
				),
				'subdesc'=>array(
					array('title' => 'message数据格式','arguments'=>
						array(
							array('name'=>'title',			'type'=>'String',	'desc'=>'文章标题'),
							array('name'=>'charContent',	'type'=>'String',	'desc'=>'笔记针对的文字'),
							array('name'=>'charBegin',		'type'=>'String',	'desc'=>'笔记文字在页码的起始字符数'),
							array('name'=>'content',		'type'=>'String',	'desc'=>'笔记的内容'),
							array('name'=>'page',			'type'=>'Int',		'desc'=>'页码'),
						),
					),
				),										
			),
			array(
				'id'		=> 'book_delBookmark',
				'title'		=> '添加书签',
				'useway'	=> 'POST',
				'apiurl'	=>  site_url('api/book/delBookmark'),
				'sam'		=>  site_url('apitest#book_delBookmark'),
				'arguments'	=> array(
					array('name'=>'random',		'type'=>'Int',		'value'=>'随机数', 	'desc'=>'随机数'),
					array('name'=>'sign',		'type'=>'String',	'value'=>'strtoupper(md5("delBookmark".$random.$signPostfix));', 'desc'=>'验证字符串'),
					array('name'=>'bookId',		'type'=>'Int',		'value'=>'书本id', 	'desc'=>'书本id'),
					array('name'=>'bookmarkId',	'type'=>'Int',		'value'=>'书签id', 	'desc'=>'书签id'),
				),
				'code'		=> array(
					array('result'=>1,		'message'=>'添加成功',		'desc'=>'添加成功'),
					array('result'=>1006,	'message'=>'书本id错误',		'desc'=>'书本id错误'),
					array('result'=>1010,	'message'=>'书签id错误',		'desc'=>'书签id错误'),
					array('result'=>1008,	'message'=>'请先登陆',		'desc'=>'请先登陆'),
					array('result'=>3001,	'message'=>'验证字符串错误',	'desc'=>'验证字符串错误'),
					array('result'=>3002,	'message'=>'随机数不能为空',	'desc'=>'随机数不能为空'),
					array('result'=>3003,	'message'=>'验证失败',		'desc'=>'验证失败'),
				),										
			),
			array(
				'id'		=> 'book_getBookmark',
				'title'		=> '获取笔记列表',
				'useway'	=> 'POST',
				'apiurl'	=>  site_url('api/book/getBookmark'),
				'sam'		=>  site_url('apitest#book_getBookmark'),
				'arguments'	=> array(
					array('name'=>'random',		'type'=>'Int',		'value'=>'随机数', 	'desc'=>'随机数'),
					array('name'=>'sign',		'type'=>'String',	'value'=>'strtoupper(md5("getBookmark".$random.$signPostfix));', 'desc'=>'验证字符串'),
					array('name'=>'bookId',		'type'=>'Int',		'value'=>'书本id', 	'desc'=>'书本id'),
				),
				'code'		=> array(
					array('result'=>1,		'message'=>'添加成功',		'desc'=>'添加成功'),
					array('result'=>1006,	'message'=>'书本id错误',		'desc'=>'书本id错误'),
					array('result'=>1008,	'message'=>'请先登陆',		'desc'=>'请先登陆'),
					array('result'=>3001,	'message'=>'验证字符串错误',	'desc'=>'验证字符串错误'),
					array('result'=>3002,	'message'=>'随机数不能为空',	'desc'=>'随机数不能为空'),
					array('result'=>3003,	'message'=>'验证失败',		'desc'=>'验证失败'),
				),
				'subdesc'=>array(
					array('title' => 'message数据格式','arguments'=>
						array(
							array('name'=>'bookmarkId',		'type'=>'Int',		'desc'=>'书签id'),
							array('name'=>'chapterId',		'type'=>'Int',		'desc'=>'章节id'),
							array('name'=>'title',			'type'=>'String',	'desc'=>'文章标题'),
							array('name'=>'content',		'type'=>'String',	'desc'=>'笔记的内容'),
							array('name'=>'page',			'type'=>'Int',		'desc'=>'页码'),
						),
					),
				),										
			),		
			array(
				'id'		=> 'book_isPayPage',
				'title'		=> '判断此页是否需要付费',
				'useway'	=> 'GET/POST',
				'apiurl'	=>  site_url('api/book/isPayPage'),
				'sam'		=>  site_url('apitest#book_isPayPage'),
				'arguments'	=> array(
					array('name'=>'random',		'type'=>'Int',		'value'=>'随机数', 	'desc'=>'随机数'),
					array('name'=>'sign',		'type'=>'String',	'value'=>'strtoupper(md5("isPayPage".$random.$signPostfix));', 'desc'=>'验证字符串'),
					array('name'=>'bookId',		'type'=>'Int',		'value'=>'书本id', 	'desc'=>'书本id'),
					array('name'=>'chapterId',	'type'=>'Int',		'value'=>'章节id', 	'desc'=>'章节id'),
					array('name'=>'page',		'type'=>'Int',		'value'=>'当前实际页码', 	'desc'=>'当前实际页码'),
				),
				'code'		=> array(
					array('result'=>1,		'message'=>'添加成功',		'desc'=>'添加成功'),
					array('result'=>1006,	'message'=>'书本id错误',		'desc'=>'书本id错误'),
					array('result'=>1007,	'message'=>'章节id错误',		'desc'=>'章节id错误'),
					array('result'=>1008,	'message'=>'请先登陆',		'desc'=>'请先登陆'),
					array('result'=>2205,	'message'=>'页码错误',		'desc'=>'页码错误'),
					array('result'=>3001,	'message'=>'验证字符串错误',	'desc'=>'验证字符串错误'),
					array('result'=>3002,	'message'=>'随机数不能为空',	'desc'=>'随机数不能为空'),
					array('result'=>3003,	'message'=>'验证失败',		'desc'=>'验证失败'),
				),
				'subdesc'=>array(
					array('title' => 'message数据格式','arguments'=>
						array(
							array('name'=>'message',		'type'=>'String',		'desc'=>'0:不需要付费，大于0:需要付的钱'),
						),
					),
				),										
			),			
			array(
				'id'		=> 'book_payPage',
				'title'		=> '支付单页',
				'useway'	=> 'GET/POST',
				'apiurl'	=>  site_url('api/book/payPage'),
				'sam'		=>  site_url('apitest#book_payPage'),
				'arguments'	=> array(
					array('name'=>'random',		'type'=>'Int',		'value'=>'随机数', 	'desc'=>'随机数'),
					array('name'=>'sign',		'type'=>'String',	'value'=>'strtoupper(md5("payPage".$random.$signPostfix));', 'desc'=>'验证字符串'),
					array('name'=>'bookId',		'type'=>'Int',		'value'=>'书本id', 	'desc'=>'书本id'),
					array('name'=>'chapterId',	'type'=>'Int',		'value'=>'章节id', 	'desc'=>'章节id'),
					array('name'=>'page',		'type'=>'Int',		'value'=>'当前实际页码', 	'desc'=>'当前实际页码'),
				),
				'code'		=> array(
					array('result'=>1,		'message'=>'支付成功',		'desc'=>'支付成功'),
					array('result'=>100,	'message'=>'支付失败',		'desc'=>'支付失败'),
					array('result'=>1006,	'message'=>'书本id错误',		'desc'=>'书本id错误'),
					array('result'=>1007,	'message'=>'章节id错误',		'desc'=>'章节id错误'),
					array('result'=>1008,	'message'=>'请先登陆',		'desc'=>'请先登陆'),
					array('result'=>2205,	'message'=>'页码错误',		'desc'=>'页码错误'),
					array('result'=>3001,	'message'=>'验证字符串错误',	'desc'=>'验证字符串错误'),
					array('result'=>3002,	'message'=>'随机数不能为空',	'desc'=>'随机数不能为空'),
					array('result'=>3003,	'message'=>'验证失败',		'desc'=>'验证失败'),
					array('result'=>3104,	'message'=>'账户余额不足',	'desc'=>'账户余额不足'),
				),										
			),												
			array(
				'id'		=> 'book_getBookContents',
				'title'		=> '查看书本章节分页等内容',
				'useway'	=> 'GET',
				'apiurl'	=>  site_url('api/book/getBookContents?bookId=9'),
				'sam'		=>  site_url('api/book/getBookContents?bookId=9'),
				'arguments'	=> array(
					array('name'=>'bookId',		'type'=>'Int',		'value'=>'书本id', 	'desc'=>'书本id'),
				),		
			),
			array(
				'id'		=> 'book_getFarPage',
				'title'		=> '获取阅读最大页',
				'useway'	=> 'GET',
				'apiurl'	=>  site_url('api/book/getFarPage'),
				'sam'		=>  site_url('apitest#book_getFarPage'),
				'arguments'	=> array(
					array('name'=>'bookId',		'type'=>'Int',		'value'=>'书本id', 	'desc'=>'书本id'),
				),		
			),								
		);

		$this->_data['v'] = 'book';
		$this->load->view('api/header', $this->_data);
		$this->load->view('api/tpl', $this->_data);
		$this->load->view('api/footer');
	}

}