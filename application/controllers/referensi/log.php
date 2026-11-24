<?php if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

class Log extends CI_Controller {

	function __construct() {
	
		parent::__construct();
		
	}
	
	public function index() {
		
		$this->userlog();
	
	}
	

	function userlog() {
		
		$data['breadcrumb'] = array('referensi/log' => 'Aktivitas Pengguna');
		
		$total = $this->general_model->datagrab(array(
			'tabel' => array(
				'log_book k' => '',
				'peg_pegawai p' => 'p.id_pegawai = k.op'),
			'select' => 'count(k.op) as jumlah,p.nama,p.id_pegawai,action',
			'group_by' => 'k.op,action',
			'order' => 'jumlah desc'
		));
		
		$peg = $this->general_model->datagrab(array(
			'tabel' => array(
				'log_book k' => '',
				'peg_pegawai p' => 'p.id_pegawai = k.op'),
			'select' => 'count(k.op) as jumlah,p.nama,p.id_pegawai',
			'group_by' => 'k.op',
			'order' => 'jumlah desc'
		));
		
		$op = null;
		
		$classy = (in_array($op,array("cetak","excel"))) ? 'class="tabel_print" border=1' : 'class="table table-striped table-bordered table-condensed table-nonfluid"';
		$this->table->set_template(array('table_open'=>'<table '.$classy.'>'));
		$heads = array('No','Pegawai');
		
		$heads = array_merge_recursive($heads,array('Tambah','Ubah','Hapus','Jumlah'));
		$this->table->set_heading($heads);
		$no = 1;
		foreach($peg->result() as $row) {
			$tambah = 0;
			$edit = 0;
			$delete = 0;
			foreach($total->result() as $t) {
				if ($t->action == 1 and $t->id_pegawai == $row->id_pegawai) $tambah = $t->jumlah;
				if ($t->action == 2 and $t->id_pegawai == $row->id_pegawai) $edit = $t->jumlah;
				if ($t->action == 3 and $t->id_pegawai == $row->id_pegawai) $delete = $t->jumlah;
			}
			
			$this->table->add_row($no,$row->nama_pegawai,$tambah,$edit,$delete,$row->jumlah);
			$no+=1;
		}

		$data['tabel_total'] = $this->table->generate();
		$data['title'] = 'Aktivitas Pengguna';
		$data['content'] = 'referensi/logbook_view';
		$this->load->view('home',$data);
		
		
		
	}
	
}