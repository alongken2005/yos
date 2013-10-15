<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 千岛湖
 * @datetime (12-10-8 下午3:03)
 * @author ZhangHao
 */

class Lake extends CI_Controller {

	private $_data;

	public function __construct() {
		parent::__construct();
		$this->load->model('base_mdl', 'base');
	}

	/**
	 * 默认方法
	 */
	public function index() {
		$this->main();
	}

	public function main() {

		//介绍
		$intros = array();
		$intro = $this->base->get_data('notice', array('area'=>'intro'), 'id, content, type')->result_array();
		foreach($intro as $v) {
			$intros[$v['type']] = $v['content'];
		}
		$this->_data['intros'] = $intros;

		//焦点图
		$this->_data['piclist'] = $this->base->get_data('pics', array('place'=>4), '*', 5, 0)->result_array();

		//教学设计
		$this->_data['desglist'] = $this->db->query("SELECT s.title, s.cover, s.hits, s.id, s.grade, s.authorid, a.name, g.name gname FROM ab_subject s LEFT JOIN ab_grade g ON s.grade=g.id LEFT JOIN ab_author a ON s.authorid=a.id WHERE s.type='lakeDesign' LIMIT 12")->result_array();

		//名师讲堂
		$this->_data['toplist'] = $this->db->query("SELECT s.title, s.cover, s.hits, s.id, s.grade, s.authorid, a.name, g.name gname FROM ab_subject s LEFT JOIN ab_grade g ON s.grade=g.id LEFT JOIN ab_author a ON s.authorid=a.id WHERE s.top=1 LIMIT 12")->result_array();

		//界数
		$kinds = $this->config->item('subject_kinds');
		$glist = $this->db->query("SELECT * FROM ab_grade")->result_array();
		$gradeResult = array();
		foreach($glist as $v) {
			foreach($kinds as $key=>$kind) {
				if($v['type'] == $key) {
					$gradeResult[$key][] = $v;
					break;
				}
			}
		}
		$this->_data['gradeResult'] = $gradeResult;

		/**
		//儿童阅读
		$this->_data['lakeCread'] = $this->db->query("SELECT s.title, s.cover, s.hits, s.id, s.grade, s.authorid, a.name, g.name gname FROM ab_subject s LEFT JOIN ab_grade g ON s.grade=g.id LEFT JOIN ab_author a ON s.authorid=a.id WHERE s.type='lakeCread' LIMIT 6")->result_array();
		//班级读书会
		$this->_data['lakeClass'] = $this->db->query("SELECT s.title, s.cover, s.hits, s.id, s.grade, s.authorid, a.name, g.name gname FROM ab_subject s LEFT JOIN ab_grade g ON s.grade=g.id LEFT JOIN ab_author a ON s.authorid=a.id WHERE s.type='lakeClass' LIMIT 6")->result_array();
		//故事妈妈
		$this->_data['lakeStory'] = $this->db->query("SELECT s.title, s.cover, s.hits, s.id, s.grade, s.authorid, a.name, g.name gname FROM ab_subject s LEFT JOIN ab_grade g ON s.grade=g.id LEFT JOIN ab_author a ON s.authorid=a.id WHERE s.type='lakeStory' LIMIT 6")->result_array();
		//新作文联盟
		$this->_data['lakeContent'] = $this->db->query("SELECT s.title, s.cover, s.hits, s.id, s.grade, s.authorid, a.name, g.name gname FROM ab_subject s LEFT JOIN ab_grade g ON s.grade=g.id LEFT JOIN ab_author a ON s.authorid=a.id WHERE s.type='lakeContent' LIMIT 6")->result_array();
		//国学经典
		$this->_data['lakeState'] = $this->db->query("SELECT s.title, s.cover, s.hits, s.id, s.grade, s.authorid, a.name, g.name gname FROM ab_subject s LEFT JOIN ab_grade g ON s.grade=g.id LEFT JOIN ab_author a ON s.authorid=a.id WHERE s.type='lakeState' LIMIT 6")->result_array();
		*/
		$this->_data['authorlist'] = $this->base->get_data('author', array(), '*', 16)->result_array();
		$this->load->view(THEME.'/lake', $this->_data);
	}

	/**
	 * 课程
	 */
	public function subject() {
		$id = (int)$this->input->get('id');

		//更新阅读数
		$this->db->query("UPDATE ab_subject SET hits = hits+1 WHERE id=".$id);

		$this->_data['subject'] = $subject = $this->db->query("SELECT * FROM ab_subject WHERE id=".$id)->row_array();
		$this->_data['author'] = $this->db->query("SELECT * FROM ab_author WHERE id=".$subject['authorid'])->row_array();
		$this->_data['attachs'] = $this->base->get_data('attach', array('kind'=>'lake', 'relaid'=>$id), '*', 0, 0, 'sort DESC, ctime DESC')->result_array();
		$this->_data['author_subject'] = $this->base->get_data('subject', array('authorid'=>$subject['authorid']), '*', 0, 0, 'sort DESC, ctime DESC')->result_array();

		//分页配置
        $this->load->library('gpagination');
		$total_num = $this->base->get_data('comment', array('type'=>'subject', 'typeid'=>$id))->num_rows();
		$page = $this->input->get('page') > 1 ? $this->input->get('page') : '1';
		$limit = 10;
		$offset = ($page - 1) * $limit;

		$this->gpagination->currentPage($page);
		$this->gpagination->items($total_num);
		$this->gpagination->limit($limit);
		$this->gpagination->target(site_url('lake/subject?id='.$id));

		$this->_data['pagination'] = $this->gpagination->getOutput();
		$this->_data['com_lists'] = $com_lists = $this->db->query("SELECT c.id, c.content, c.ctime, c.authorid, c2.content ccontent, c2.authorid cuid FROM ab_comment c LEFT JOIN ab_comment c2 ON c2.id=c.cid WHERE c.type='subject' AND c.typeid=".$id." ORDER BY c.ctime DESC LIMIT ".$offset.",".$limit)->result_array();

		$uids = $users = array();
		foreach($com_lists as $v) {
			$uids[] = $v['authorid'];
			$uids[] = $v['cuid'];
		}

		if($uid = array_unique(array_filter($uids))) {
			$query  = $this->db->query("SELECT id, username FROM ab_account WHERE id IN(".implode(',', array_unique(array_filter($uids))).")")->result_array();
			foreach($query as $v) {
				$users[$v['id']] = $v['username'];
			}
		}
		$this->_data['users'] = $users;
		$this->_data['uid'] = $this->session->userdata('uid');
		$this->load->view(THEME.'/lake_subject', $this->_data);
	}

	/**
	 * 作者
	 */
	public function author() {
		$id = (int)$this->input->get('id');

		$this->_data['author'] = $this->base->get_data('author', array('id'=>$id))->row_array();
		$this->_data['author_subject'] = $this->base->get_data('subject', array('authorid'=>$id), '*', 0, 0, 'sort DESC, ctime DESC')->result_array();
		$this->_data['author_top'] = $this->base->get_data('author', array('top'=>1), '*', 0, 0, 'sort DESC, id DESC')->result_array();
		$this->load->view(THEME.'/lake_author', $this->_data);
	}

	/**
	 * 搜索
	 */
	public function search() {
		$type = $this->input->get('type');
		$stype = $this->input->get('stype') ? $this->input->get('stype') : 'subject';
		$keyword = $this->input->get('keyword');
		$this->_data['subject_kinds'] = $this->config->item('subject_kinds');
		$kinds = array_keys($this->config->item('subject_kinds'));

		//分页配置
        $this->load->library('gpagination');
		$page = $this->input->get('page') > 1 ? $this->input->get('page') : '1';
		$limit = 6;
		$offset = ($page - 1) * $limit;

		$where = 'WHERE 1';
		if($stype == 'subject') {			//课件搜索
			if($type == 'top') {
				$where .= ' AND s.top=1';
			} elseif($type && in_array($type, $kinds)) {
				$where .= ' AND s.type="'.$type.'"';
			}

			if($keyword) {
				$where .= ' AND s.title LIKE "%'.$keyword.'%"';
			}
			$total_num = $this->db->query('SELECT * FROM ab_subject s '.$where)->num_rows();
			$this->_data['lists'] = $this->db->query('SELECT s.title, s.cover, s.hits, s.id, s.grade, s.authorid, a.name, g.name gname FROM ab_subject s LEFT JOIN ab_grade g ON s.grade=g.id LEFT JOIN ab_author a ON a.id=s.authorid '.$where.' LIMIT '.$offset.','.$limit)->result_array();
		} elseif($stype == 'author') {		//作者搜索
			if($keyword) {
				$where .= ' AND name LIKE "%'.$keyword.'%"';
			}
			$total_num = $this->db->query('SELECT * FROM ab_author '.$where)->num_rows();
			$this->_data['lists'] = $this->db->query('SELECT * FROM ab_author '.$where)->result_array();
		} else {
			$this->_data['lists'] = array();
		}

		$this->gpagination->currentPage($page);
		$this->gpagination->items($total_num);
		$this->gpagination->limit($limit);
		$this->gpagination->target(site_url('lake/search?type='.$type.'&stype='.$stype.'&keyword='.$keyword));

		$this->_data['pagination'] = $this->gpagination->getOutput();

		$this->_data['stype'] = $stype;
		$this->_data['type'] = $type;
		$this->_data['keyword'] = $keyword;
		$this->_data['total_num'] = $total_num;
		$this->load->view(THEME.'/lake_search', $this->_data);
	}

	/**
	 * 文件通知
	 */
	public function notice_list() {
		//分页配置
        $this->load->library('gpagination');
		$total_num = $this->base->get_data('notice', array('area'=>'lake'))->num_rows();
		$page = $this->input->get('page') > 1 ? $this->input->get('page') : '1';
		$limit = 25;
		$offset = ($page - 1) * $limit;

		$this->gpagination->currentPage($page);
		$this->gpagination->items($total_num);
		$this->gpagination->limit($limit);
		$this->gpagination->target(site_url('lake/notice_list'));

		$this->_data['pagination'] = $this->gpagination->getOutput();
		$this->_data['lists'] = $this->base->get_data('notice', array('area'=>'lake'), '*', $limit, $offset, 'sort DESC, ctime DESC')->result_array();

		$this->load->view(THEME.'/lake_notice_list', $this->_data);
	}

	/**
	 * 通知详情
	 */
	public function notice_detail() {
		$id = (int)$this->input->get('id');
		$this->_data['row'] = $this->base->get_data('notice', array('id'=>$id))->row_array();
		$this->load->view(THEME.'/lake_notice', $this->_data);
	}

	/**
	 * 界数
	 */
	public function grade() {
		$id = (int)$this->input->get('id');
		$this->_data['tab'] = $tab = $this->input->get('tab') ? $this->input->get('tab') : 'index';
		$this->_data['row'] = $this->base->get_data('grade', array('id'=>$id))->row_array();

		if($tab == 'pic') {
			//分页配置
			$this->load->library('gpagination');
			$total_num = $this->base->get_data('pics', array('place'=>5, 'place_id'=>$id))->num_rows();
			$page = $this->input->get('page') > 1 ? $this->input->get('page') : '1';
			$limit = 25;
			$offset = ($page - 1) * $limit;

			$this->gpagination->currentPage($page);
			$this->gpagination->items($total_num);
			$this->gpagination->limit($limit);
			$this->gpagination->target(site_url('lake/grade?tab=pic&id='.$id));

			$this->_data['pagination'] = $this->gpagination->getOutput();
			$this->_data['pic_lists'] = $this->base->get_data('pics', array('place'=>5, 'place_id'=>$id), '*', $limit, $offset, 'sort DESC, ctime DESC')->result_array();
		} elseif($tab == 'subject') {
			//分页配置
			$this->load->library('gpagination');
			$total_num = $this->base->get_data('subject', array('grade'=>$id))->num_rows();
			$page = $this->input->get('page') > 1 ? $this->input->get('page') : '1';
			$limit = 9;
			$offset = ($page - 1) * $limit;

			$this->gpagination->currentPage($page);
			$this->gpagination->items($total_num);
			$this->gpagination->limit($limit);
			$this->gpagination->target(site_url('lake/grade?tab=subject&id='.$id));

			$this->_data['pagination'] = $this->gpagination->getOutput();
			$this->_data['subject_lists'] = $this->db->query('SELECT s.title, s.cover, s.hits, s.id, s.grade, s.authorid, a.name FROM ab_subject s LEFT JOIN ab_author a ON s.authorid=a.id WHERE grade = '.$id.' LIMIT '.$offset.', '.$limit)->result_array();
		}
		$this->load->view(THEME.'/lake_grade', $this->_data);
	}

	public function comment() {
		$id = (int)$this->input->post('typeid');
		$this->permission->login_check(site_url('lake/subject?id='.$id));
		$uid = $this->session->userdata('uid');
		$insert_data = array(
			'content'	=> $this->input->post('content'),
			'type'		=> 'subject',
			'ctime'		=> time(),
			'typeid'	=> $id,
			'authorid'	=> $uid,
			'cid'		=> (int)$this->input->post('cid')
		);

		$this->base->insert_data('comment', $insert_data);

		$this->msg->showmessage('操作完成', site_url('lake/subject?id='.$id));
	}

	/**
	 * 删除留言
	 */
	public function comment_del() {
		echo 'ok';
	}

	/**
	 * 课件下载
	 */
	public function attach_down() {
		$this->load->helper('download');
		$id = $this->input->get('id');
		$sid = $this->input->get('sid');
		$attach = $this->base->get_data('attach', array('id'=>$id))->row_array();
		if(file_exists('./data/uploads/attach/'.$attach['filename'])) {
			$data = file_get_contents(base_url('data/uploads/attach/'.$attach['filename']));
			$ext = pathinfo($attach['filename'], PATHINFO_EXTENSION);
			$name = $attach['title'] ? $attach['title'].'.'.$ext : $attach['realname'];
			force_download($name, $data);
		} else {
			$this->msg->showmessage('该文件不存在！', site_url('lake/subject?id='.$sid));
		}

	}
}