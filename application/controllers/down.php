<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 资源下载
 * @see Down
 * @version 1.0.0 (12-10-8 下午3:03)
 * @author ZhangHao
 */

class Down extends CI_Controller {
	private $tdate;

	public function __construct() {
		parent::__construct();
		$this->load->model('base_mdl', 'base');
		$this->tdate = date('Y/m/');
	}

	/**
	 * @deprecated 默认方法
	 */
	public function index() {
		return false;
	}

	/**
	 * @deprecated 获取下载图书的token
	 * http://dev.childroad.com/main.php/down/get_token?books=22,36&random=as23sf2q432&timestamp=17002120&sign=0da26aaa558a1478f49516b18d36b5df
	 */
	public function get_token() {

		$sign		= $this->input->get('sign');		//加密字符串
		$books		= $this->input->get('books');		//书本id
		$random		= $this->input->get('random');		//随机数
		$timestamp	= $this->input->get('timestamp');	//随机时间戳
		$resign		= md5("books".$books."random".$random."timestamp".$timestamp.$this->config->item('encryption_key')); //拼接验证sign

		//删除超过24小时的过期token
		$passtime = time() - 3600*24;
		$re = $this->base->del_data('book_token', array('ctime < '=>$passtime));
		write_log('del timeout token passtime='.date('Y-m-d H:i:s', $passtime).'__ren='.$re, '', 'down');

		//验证sign是否有效
		if($sign == $resign) {
			$token = md5($random.$timestamp.$this->config->item('encryption_key'));

			//判断是否有相同的token
			$num = $this->base->get_data('book_token', array('token'=>$token))->num_rows();

			if($num > 0) {
				write_log('7__books='.$books.'__sign='.$sign, '', 'down');
				output(1000, false); //请求重复
			}

			//token存入数据库
			$result = $this->base->insert_data('book_token', array('token' => $token, 'data' => $books, 'ctime'=> time()));
			if($result) {
				write_log('ok__token='.$token.'__books='.$books, '', 'down');
				output(0, $token); //成功
			} else {
				write_log('8__books='.$books.'__sign='.$sign, '', 'down');
				output(2000, false); //token获取失败
			}
		} else {
			write_log('9__books='.$books.'__sign='.$sign, '', 'down');
			output(1001, false); //验证不通过
		}
	}

	/**
	 * 获取书本资源
	 * http://dev.childroad.com/main.php/down/get_res?bookid=22&token=e28156ba31952a7cf7e232b795769dc9&fpath=100ChengYuGuShi01_p1/p1_swf.swf
	 */
	public function get_res() {

		$bookid = (int)$this->input->get('bookid');
		$token	= $this->input->get('token');
		$fpath	= $this->input->get('fpath');
		$extion = pathinfo($fpath, PATHINFO_EXTENSION);
		$this->load->helper(array('download', 'file'));
		$this->load->config('common');
		$book_dir = $this->config->item('book_dir');

		if($extion == 'mp3' && !$token) {	//mp3下载

			$books = $this->base->get_data('book', array('id'=>$bookid), 'content, content_type')->row_array();

			//检查书本信息
			if(!($books && $books['content'])) {
				write_log('5__bookid='.$bookid.'__token='.$token.'__fpath='.$fpath, '', 'down');
				output(1002, false);	//书本不存在
			}

			$path = $book_dir[$books['content_type']].$books['content'].'/'.$fpath;

			if(!file_exists($path)) {
				write_log('6__bookid='.$bookid.'__token='.$token.'__fpath='.$fpath, '', 'down');
				output(1003, false);	//文件不存在
			}

			force_download(basename($fpath), read_file($path));

		} else {	//资源下载

			if(!$bookid || !$token || !$fpath) {
				write_log('1__bookid='.$bookid.'__token='.$token.'__fpath='.$fpath, '', 'down');
				output(1004, false);	//参数缺失
			}

			$result = $this->base->get_data('book_token', array('token'=>$token, 'status'=>0), 'ctime, data')->row_array();

			//检查书本信息
			if(!$result) {
				write_log('2__bookid='.$bookid.'__token='.$token.'__fpath='.$fpath, '', 'down');
				output(2001, false); //token错误;
			}

			//检查token是否过期
			if($result['ctime']+3600*24 < time()) {
				$this->db->query("UPDATE ".$this->db->dbprefix('book_token')." SET status=1 WHERE token='".$token."'");
				write_log('3__bookid='.$bookid.'__token='.$token.'__fpath='.$fpath, '', 'down');
				output(2002, false);	//token过期
			}

			//检查书本id是否正确
			if(!in_array($bookid, explode(',', $result['data']))) {
				write_log('4__bookid='.$bookid.'__token='.$token.'__fpath='.$fpath, '', 'down');
				output(2003, false);	//bookid不匹配
			}

			$books = $this->base->get_data('book', array('id'=>$bookid), 'content, content_type')->row_array();

			//检查书本信息
			if(!($books && $books['content'])) {
				write_log('5__bookid='.$bookid.'__token='.$token.'__fpath='.$fpath, '', 'down');
				output(1002, false);	//书本不存在
			}

			//请求下载文件
			$path = $book_dir[$books['content_type']].$books['content'].'/'.$fpath;

			//检查文件是否存在
			if(!file_exists($path)) {
				write_log('6__bookid='.$bookid.'__token='.$token.'__path='.$path, '', 'down');
				output(1003, false);	//文件不存在
			}

			force_download(basename($fpath), read_file($path));
		}
	}

//	public function test() {
//		//http://dev.childroad.com/main.php/down/get_token?books=481&random=17002120&timestamp=17002120&sign=a819f9a60d3b5cd96c99e7e4ea7ff6ff
//		$sign = md5("books481random17002120timestamp17002120".$this->config->item('encryption_key'));
//		debug($sign);
//	}
}