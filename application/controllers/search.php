<?php
/**
 * 搜索
 * @version 2013-11-10
 * @author ZhangHao
 */
 
class Search extends CI_Controller {
	private $_data;
	
    public function __construct() {
		parent::__construct();
		$this->load->model('base_mdl', 'base');
    }

	public function lists() {
		$this->_data['keyword'] = $keyword = $this->input->get('keyword');
		$more = $this->input->get('more');
		$page = intval($this->input->get('page')) ? intval($this->input->get('page')) : 1;
		$limit = 25;
		$offset = ($page-1)*$limit;

		
		if($more) {
			$this->_data['lists'] = $this->db->query("SELECT * FROM yos_book WHERE title LIKE '%".$keyword."%' OR author LIKE '%".$keyword."%' LIMIT 0, ".$limit)->result_array();
			echo $this->load->view(THEME.'/search_morelist', $this->_data, true);
			exit;
		} else {
			$this->_data['lists'] = $this->db->query("SELECT * FROM yos_book WHERE title LIKE '%".$keyword."%' OR author LIKE '%".$keyword."%' LIMIT ".$offset.", ".$limit)->result_array();
			$this->_data['page'] = $page;
			$this->load->view(THEME.'/search_list', $this->_data);
		}
	}

	public function clists() {
		$genre = intval($this->input->get('genre'));
		$where = $genre ? 'WHERE genre='.$genre : '';
		$more = $this->input->get('more');

		$page = intval($this->input->get('page')) ? intval($this->input->get('page')) : 1;
		$limit = 25;
		$offset = ($page-1)*$limit;

		if($more) {
			$this->_data['lists'] = $this->db->query("SELECT * FROM yos_book ".$where." LIMIT ".$offset.", ".$limit)->result_array();
			$this->load->view(THEME.'/search_morelist', $this->_data);
		} else {
			$this->_data['lists'] = $this->db->query("SELECT * FROM yos_book ".$where." LIMIT ".$offset.", ".$limit)->result_array();
			$this->load->view(THEME.'/search_list', $this->_data);			
		}
	}
}