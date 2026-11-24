<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Parameter extends CI_Controller {

	function __construct() {
	
		parent::__construct();
		login_check($this->session->userdata('login_state'));
		
	}

	public function index() {
		
		$this->paging();
		
	}
	
	function simpan_param($par,$dat) {
		$this->general_model->save_data('parameter',array('param' => $par,'val'=> $dat));
	}
	
	function ubah_param($par,$dat) {
		$this->general_model->save_data('parameter',array('val' => $dat),'param',$par);
	}
	
	
	function save_data() {

		foreach($this->param as $o) {
			
			$g = $this->general_model->datagrab(array('tabel' => 'parameter', 'where' => array('param' => $o)));
			if ($g->num_rows() > 0) $this->ubah_param($o,$this->input->post($o));
			else $this->simpan_param($o,$this->input->post($o));
			
		}
	
	}
	
	function paging() {
		
		$data['breadcrumb'] = array('' => 'Pengaturan', 'dms/parameter' => 'Parameter');
		$data['title'] = 'Pengaturan Umum';
		
		$data['vals'] = $this->general_model->get_param(array(
			'dms_namanip',
			'dms_nip',
			'dms_jab',
			'dms_golru'
			
		),2);
		
		
		$data['content'] = 'dms/parameter_own_view';
		$this->load->view('home',$data);
		
	}
	
	function simpan() {
		
		$keys = $this->input->post('keys');
		$vals = $this->input->post('vals');
		
		for ($i = 0; $i < count($keys); $i++) {
			$g = $this->general_model->datagrab(array('tabel' => 'parameter', 'where' => array('param' => $keys[$i])));
			if ($g->num_rows() > 0) $this->ubah_param($keys[$i],$vals[$i]);
			else $this->simpan_param($keys[$i],$vals[$i]);	

		}
		
		$this->session->set_flashdata('ok','Pengaturan umum berhasil disimpan ...');
		redirect('dms/parameter');
		
	}
	
}
