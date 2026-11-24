<?php if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

class unit extends CI_Controller {

	var $dir = 'referensi';

	function __construct() {
	
		parent::__construct();
		login_check($this->session->userdata('login_state'));
		
	}
	
	public function index() {
			
		$this->show_unit();
			
	}
	
	function show_unit() {
		
		load_controller('inti','unitkerja','show_unit',$this->dir);
		
	}
	
	function add_unit($param = null) {
		load_controller('inti','unitkerja','add_unit',$param);
	
	}
	
	function pindah_kosong($p) {
		
		load_controller('inti','unitkerja','pindah_kosong',$this->dir,$p);
		
	}
	
	function pindah_unit($p) {
		
		load_controller('inti','unitkerja','pindah_unit',$this->dir,$p);
	
	}
	
	function removing($par){
	
		load_controller('inti','unitkerja','removing',$par);
		
	}
	
	function urutkan($par) {
		
		load_controller('inti','unitkerja','urutkan',$par);
	
	}
	
}