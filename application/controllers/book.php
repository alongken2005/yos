<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @deprecated 书本
 * @see Book
 * @version 1.0.0 (12-10-8 下午3:03)
 * @author ZhangHao
 */

class Book extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('base_mdl', 'base');
	}

	/**
	 * @deprecated 默认方法
	 */
	public function index() {
		return false;
	}

	/**
	 * @deprecated 获取下载图书的token
	 * http://dev.childroad.com/main.php/book/get_token?books=22,36&random=as23sf2q432&timestamp=17002120&sign=0da26aaa558a1478f49516b18d36b5df
	 */
	public function get_token() {

		$sign		= $this->input->get('sign');		//加密字符串
		$books		= $this->input->get('books');		//书本id
		$random		= $this->input->get('random');		//随机数
		$timestamp	= $this->input->get('timestamp');	//随机时间戳
		$resign		= md5("books".$books."random".$random."timestamp".$timestamp.$this->config->item('encryption_key')); //拼接验证sign

		//验证sign是否有效
		if($sign == $resign) {
			$token = md5($random.$timestamp.$this->config->item('encryption_key'));

			//判断是否有相同的token
			$num = $this->base->get_data('book_token', array('token'=>$token))->num_rows();

			if($num > 0) {
				output(array('success'=>'No', 'data'=>'Repeat request'));
			}

			//token存入数据库
			$result = $this->base->insert_data('book_token', array('token' => $token, 'data' => $books, 'ctime'=> time()));
			if($result) {
				output(array('success'=>'Yes', 'data'=>$token));
			} else {
				output(array('success'=>'No', 'data'=>'Data error'));
			}
		} else {
			output(array('success'=>'No', 'data'=>'Data error'));
		}
	}

	/**
	 * @deprecated 获取书本资源
	 * http://dev.childroad.com/main.php/book/get_res/22/b291b81a5dce382/100ChengYuGuShi01_p1/p1_swf.swf
	 */
	public function get_res($bookid = 0, $token = 0, $path = 0, $filename = 0) {

		if(!$bookid || !$token || !$path) {
			output(array('success'=>'No', 'data'=>'Data error'));
		}

		$result = $this->base->get_data('book_token', array('token'=>$token, 'status'=>0), 'ctime, data')->row_array();

		//检查书本信息
		if(!$result) {
			output(array('success'=>'No', 'data'=>'Token error'));
		}

		//检查token是否过期
		if($result['ctime']+3600*24 < time()) {
			$this->db->query("UPDATE ".$this->db->dbprefix('book_token')." SET status=1 WHERE token='".$token."'");
			output(array('success'=>'No', 'data'=>'Wrong token or time out'));
		}

		//检查书本id是否正确
		if(!in_array($bookid, explode(',', $result['data']))) {
			output(array('success'=>'No', 'data'=>'Wrong bookid'));
		}

		$books = $this->base->get_data('book', array('id'=>$bookid), 'content')->row_array();

		//检查书本信息
		if(!($books && $books['content'])) {
			output(array('success'=>'No', 'data'=>'Books information error'));
		}

		//请求下载文件
		$this->load->helper(array('download', 'file'));
		$filepath = $filename ? $path.'/'.$filename : $path;
		$filename = $filename ? $filename : $path;

		//检查文件是否存在
		if(!file_exists($this->config->item('book_file').$books['content'].'/'.$filepath)) {
			output(array('success'=>'No', 'data'=>'File not exist'));
		}

		$filedata = read_file($this->config->item('book_file').$books['content'].'/'.$filepath);
		force_download($filename, $filedata);
	}

	public function test() {
		$re = $this->base->get_data('book', array(), '*', 1)->result_array();
		debug($re);
	}
}