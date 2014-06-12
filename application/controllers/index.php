<?php
/**
 * 首页
 * @version 1.0.0 (Thu Feb 23 13:49:18 GMT 2012)
 * @author ZhangHao
 */

class Index extends MY_Controller {
	private $_data;

    public function __construct() {
		parent::__construct();
		$this->load->model('base_mdl', 'base');
    }

	public function index() {

		$myMenu = array();
		if($this->member) {
			$myMenu = array('cread'=>'Continue Reading', 'mylist'=>'My List', 'tops'=>'Top Picks');
		}
		$menu_list = array('popular'=>'Popular', 'rated'=>'Best Rated');
		$this->_data['menu_list'] = array_merge($myMenu, $menu_list);
		//$this->_data['menu_list'] = $menu_list;
		//debug($this->_data['menu_list']);


		//$this->_data['cread'] = $this->base->get_data('book')->result_array();
		if($this->member && $this->member['uid']) {
			$favBookid = array();
			$where = '';

			$fav = $this->db->query('SELECT bid FROM yos_book_fav WHERE uid='.$this->member['uid'].' ORDER BY ctime DESC')->result_array();
			if($fav) {
				foreach($fav as $v) {
					$favBookid[] = $v['bid'];
				}
				$where = ' OR id IN('.implode(',', $favBookid).')';
			}

			$this->_data['mylist'] = $this->db->query('SELECT * FROM yos_book WHERE uid='.$this->member['uid'].$where.' LIMIT 0, 30')->result_array();

			//Continue Reading
			$this->_data['cread'] = $this->db->query('SELECT b.* FROM yos_history h LEFT JOIN yos_book b ON b.id=h.bid WHERE h.uid='.$this->member['uid'].' ORDER BY h.mtime DESC LIMIT 0, 30')->result_array();
		}
		
		//popular on YouShelf
		$this->_data['popular'] = $this->base->get_data('book', array(), '*', 0, 30, 'hits DESC')->result_array();

		$this->_data['bestRated'] = $this->base->get_data('book', array(), '*', 0, 30, 'score DESC, mtime DESC')->result_array();

		//Top Picks for you
		$this->_data['tops'] = $this->base->get_data('book', array(), '*', 0, 30, 'ctime DESC')->result_array();

		$genres = $this->base->get_data('book_genre', array(), 'id, name')->result_array();

		foreach($genres as $v) {
			 $re = $this->base->get_data('book', array('genre'=>$v['id']), '*', 0, 30, 'hits DESC')->result_array();
			 if($re) {
			 	$lists[$v['id']]['name'] = $v['name'];
			 	$lists[$v['id']]['list'] = $re;
			 }
		}
		$this->_data['lists'] = $lists;
		$this->_data['nothome'] = $lists;

		/*
		$this->_data['adventure'] = $this->base->get_data('book', array('genre'=>1), '*', 0, 10, 'hits DESC')->result_array();
		$this->_data['biography'] = $this->base->get_data('book', array('genre'=>2), '*', 0, 10, 'hits DESC')->result_array();
		$this->_data['business'] = $this->base->get_data('book', array('genre'=>3), '*', 0, 10, 'hits DESC')->result_array();
		$this->_data['fantasy'] = $this->base->get_data('book', array('genre'=>7), '*', 0, 10, 'hits DESC')->result_array();
		$this->_data['kids'] = $this->base->get_data('book', array('genre'=>4), '*', 0, 10, 'hits DESC')->result_array();
		$this->_data['scifi'] = $this->base->get_data('book', array('genre'=>17), '*', 0, 10, 'hits DESC')->result_array();
		*/

		$this->load->view(THEME.'/header', $this->_data);
		$this->load->view(THEME.'/index', $this->_data);
		$this->load->view(THEME.'/footer');
	}
}