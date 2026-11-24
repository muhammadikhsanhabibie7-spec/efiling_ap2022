<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kewenangan extends CI_Controller {


	/* 
	 * Keterangan Variabel Global
	 * $app
	 *		1 = Aplikasi Tunggal
	 *		0 = Seluruh Aplikasi
	 * $dir	
	 		Pengguna Aplikasi / Folder Aplikasi
	 */

	var $app = 0; 
	var $dir = 'inti';
	var $unit = TRUE;

	function __construct() {
	
		parent::__construct();
		login_check($this->session->userdata('login_state'));
		
		$app_std = $this->general_model->datagrab(array(
			'tabel' => 'ref_aplikasi','where' => array('folder' => $this->uri->segment(1))
		))->row();
		$this->in_app = !empty($app_std->nama_aplikasi) ? $app_std->nama_aplikasi : 'Root';
	}

	public function index() {
		$this->list_kewenangan();
	}
	
	function list_kewenangan($offset = null) {
	
		load_controller('inti','akses','data_list',array('app' => $this->app,'dir' => $this->dir),$offset);
		
	}
	
	function set_kewenangan($par) {
		
		$o = un_de($par);
		load_controller('inti','akses','set_kewenangan',
			array_merge_recursive($o,array('app' => $this->app,'dir' => $this->dir)));
	
	}
	
	function add_kewenangan($id_role = null){
		
		load_controller('inti','akses','add_kewenangan',array('unit' => $this->unit,'app' => $this->app,'dir' => $this->dir),$id_role);
	}
	
	function delete_kewenangan($id_role){
		
		load_controller('inti','akses','delete_kewenangan',$this->dir,$id_role);
		
	}
	
}
