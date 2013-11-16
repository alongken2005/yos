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
		$page = intval($this->input->get('page')) ? intval($this->input->get('page')) : 1;
		$limit = 30;
		$offset = ($page-1)*$limit;

		$this->_data['lists'] = $this->db->query("SELECT * FROM yos_book WHERE title LIKE '%".$keyword."%' OR author LIKE '%".$keyword."%' LIMIT ".$offset.", ".$limit)->result_array();
		$this->load->view(THEME.'/search_list', $this->_data);
	}
}