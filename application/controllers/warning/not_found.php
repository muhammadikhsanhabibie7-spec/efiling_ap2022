<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class not_found extends CI_Controller {
	function __construct() {
			parent::__construct();
			login_check($this->session->userdata('login_state'));
	}
	public function index() {
		$data['content'] = 'not_found';
		$this->load->view('home',$data);
	}
}