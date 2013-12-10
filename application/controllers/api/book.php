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
		$genres = $this->base->get_data('book_genre', array(), 'id genreId, name', 0, 0, 'dis ASC')->result_array();
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

		if($type) {
			$result = $this->db->query("SELECT id bookId, title, cover, genre genreId FROM yos_book ORDER BY mtime DESC ".$pageLimit)->result_array();
		} else {
			if($genreId) $where = 'WHERE genre='.$genreId;
			$result = $this->db->query("SELECT id bookId, title, cover, genre genreId FROM yos_book ".$where." ORDER BY dis ASC, mtime DESC ".$pageLimit)->result_array();			
		}

		$books = array();
		if($result) {
			foreach($result as $row) {
				$row['cover'] = $row['cover'] ? base_url('data/books/'.$row['cover']) : base_url('data/books/nocover.jpg');
				$books[] = $row;
			}
		}
		$datas = array('page'=>$page, 'pageSize'=>$pageSize, 'books'=>$books);
		output(1, $datas);	
	}

	/**
	 * 获取书本详情
	 */	
	public function getBookInfo () {
		getSign(__function__, $this->input->get_post('random'), $this->input->get_post('sign'));

		$bid = intval($this->input->get_post('bookId'));
		if(!$bid) output(1006, '书本id错误');

		$book = $this->base->get_data('book', array('id'=>$bid), 'id bookId, uid authorid, author, title, cover, publisher, genre genreId, text_price textPrice, audio_price audioPrice, score, scorenum, description, ctime, mtime')->row_array();
		$book['cover'] = $book['cover'] ? base_url('data/books/'.$book['cover']) : base_url('data/books/nocover.jpg');
		$book['textPriceTitle'] = 'paid text 1000 words';
		$book['audioPriceTitle'] = 'paid Audio 1000 words';
		output(1, $book);
	}

	/**
	 * 获取目录列表
	 */	
	public function getDirectory () {
		getSign(__function__, $this->input->get_post('random'), $this->input->get_post('sign'));

		$bid = intval($this->input->get_post('bookId'));
		if(!$bid) output(1006, '书本id错误');

		$chapters = $this->base->get_data('book_chapter', array('bid'=>$bid), 'id chapterId, page, pagenum, title, dis')->result_array();
		if(!$chapters) output(3004, '章节为空');
		output(1, $chapters);
	}

	/**
	 * 获取章节内容
	 */	
	public function getChapter () {
		getSign(__function__, $this->input->get_post('random'), $this->input->get_post('sign'));

		$bid = intval($this->input->get_post('bookId'));
		$cid = intval($this->input->get_post('chapterId'));

		if(!$bid) output(1006, '书本id错误');
		if(!$cid) output(1007, '章节id错误');

		$chapter = $this->base->get_data('book_chapter', array('bid'=>$bid, 'id'=>$cid), 'bid bookId, id chapterId, content')->row_array();
		if(!$chapter) output(3005, '章节为空或者此书没有该章节');
		output(1, $chapter);
	}

	/**
	 * 获取书本评分情况
	 */	
	public function getScoreInfo () {
		getSign(__function__, $this->input->get_post('random'), $this->input->get_post('sign'));

		$bid = intval($this->input->get_post('bookId'));
		if(!$bid) output(1006, '书本id错误');
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
	 * 获取书本评论
	 */
	public function getReviews () {
		getSign(__function__, $this->input->get_post('random'), $this->input->get_post('sign'));

		$bid = intval($this->input->get_post('bookId'));
		if(!$bid) output(1006, '书本id错误');		
		$page = intval($this->input->get_post('page'));
		$pageSize = intval($this->input->get_post('pageSize')) ? intval($this->input->get_post('pageSize')) : 20;

		$pageLimit = $where = '';
		if($page) {
			$offset = ($page-1)*$pageSize;
			$pageLimit = 'LIMIT '.$offset.', '.$pageSize;
		}	

		$datas = array('page'=>$page, 'pageSize'=>$pageSize, 'books'=>$books);
		output(1, $datas);			
	}

	/**
	 * 发表评论
	 */
	public function writeRviews () {
		getSign(__function__, $this->input->get_post('random'), $this->input->get_post('sign'));

		//检查登陆状态
		if(!$this->member) output(1008, '请先登陆');

		$bid = intval($this->input->post('bookId'));
		$score = intval($this->input->post('score'));

		if($score <=0 || $score > 5) output(2203, '评分需在1-5分之间');
		if(!$bid) output(1006, '书本id错误');

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
		if(!$bid) output(1006, '书本id错误');
		if(!$cid) output(1007, '章节id错误');

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
			output(1, '分页成功添加');
		} else {
			output(2204, '没有章节内容');
		}
	}

	/**
	 * 查看本书章节和分页
	 */
	public function getBookContents() {
		getSign(__function__, $this->input->get_post('random'), $this->input->get_post('sign'));

		$bid = intval($this->input->get('bookId'));
		$type = intval($this->input->get('type'));
		$cid = intval($this->input->get('chapterId'));
		if(!$bid) exit('bookId错误');

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
		$bid = intval($this->input->get_post('bookId'));
		$cid = intval($this->input->get_post('chapterId'));
		$page = intval($this->input->get_post('page'));
		if(!$bid) output(1006, '书本id错误');
		
		$chapter = $this->base->get_data('book_chapter', array('id'=>$cid), 'page')->row_array();
		if(!$cid || !$chapter) output(1007, '章节id错误');	

		$chapterPage = $page - $chapter['page'];
		if(!$page || $chapterPage <= 0) output(2205, '页码错误');	

		$pageContent = $this->base->get_data('book_pages', array('bid'=>$bid, 'cid'=>$cid, 'num'=>$chapterPage), 'content')->row_array();
		if(!$pageContent) output(1009, '内容为空');

		output(1, array($pageContent));
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
					array('result'=>3005,	'message'=>'章节为空或者此书没有该章节',	'desc'=>'章节为空或者此书没有该章节'),
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
					array('name'=>'type',		'type'=>'String',	'value'=>'个性话题', 'desc'=>'可选，不填返回所有，continue、mylist、toppicks、popular、bestreted'),
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
					array('result'=>1009,	'message'=>'内容为空',		'desc'=>'内容为空'),
					array('result'=>2205,	'message'=>'页码错误',		'desc'=>'页码错误'),
					array('result'=>3001,	'message'=>'验证字符串错误',	'desc'=>'验证字符串错误'),
					array('result'=>3002,	'message'=>'随机数不能为空',	'desc'=>'随机数不能为空'),
					array('result'=>3003,	'message'=>'验证失败',		'desc'=>'验证失败'),
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
		);

		$this->_data['v'] = 'book';
		$this->load->view('api/header', $this->_data);
		$this->load->view('api/tpl', $this->_data);
		$this->load->view('api/footer');
	}

}