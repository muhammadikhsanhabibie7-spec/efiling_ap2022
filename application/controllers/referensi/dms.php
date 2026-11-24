<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dms extends CI_Controller {

	function __construct() {
	
		parent::__construct();
		login_check($this->session->userdata('login_state'));
		$this->session->set_userdata(array('cut' => 1));
		
	}

	public function index() {

		$this->list_data();
	
	}
	
	function list_data($search = null, $offset = null) {
		
		$data['breadcrumb'] = array('' => 'Referensi','referensi/dms' => 'Jenis Dokumen');
		
		$offset = !empty($offset) ? $offset : null;
		
		$key = $this->input->post('search');
		$se = array();
		
		if (!empty($key)) {
			$se['arsip_jenis'] = $key;
			$se['kode_arsip_jenis'] = $key;
		} else if ($search) {
			$o = un_de($search);
			$se['arsip_jenis'] = @$o['arsip_jenis'];
			$se['kode_arsip_jenis'] = @$o['kode_arsip_jenis'];
		} 
		
		$config['base_url']	= site_url('referensi/dms/list_data/'.in_de($se));
		$config['total_rows'] = $this->general_model->datagrab(array('tabel'=> 'ref_arsip_jenis','search' => $se))->num_rows();
		$config['per_page']	= '10';
		$config['uri_segment'] = '5';
		
		$this->pagination->initialize($config);
		
		$data['links']	= $this->pagination->create_links();
		
		$offs = (!in_array($offset,array("cetak","excel"))) ?  $offset : null;
		$lim = (!in_array($offset,array("cetak","excel"))) ? $config['per_page'] : null;
		
		$query = $this->general_model->datagrab(array(
			'tabel' => 'ref_arsip_jenis',
			'offset' => $offs,
			'limit' => '10',
			'order' => 'arsip_jenis',
			'search' => $se
		));
		

		if ($query->num_rows() != 0) {
			$classy = (in_array($offset,array("cetak","excel"))) ? 'class="tabel_print" border=1' : 'class="table table-striped table-bordered table-condensed table-nonfluid"';
			$this->table->set_template(array('table_open'=>'<table '.$classy.'>'));
			$heads = array('No');
			if (!in_array($offset,array('cetak','excel'))) $heads[] = 'Konten<br>Dokumen';
			$heads = array_merge_recursive($heads,array('Kode Jenis','Jenis Arsip','Direktori'));
			if (!in_array($offset,array('cetak','excel'))) $heads[] = array('data' => 'Aksi','colspan' => '3');
			$this->table->set_heading($heads);
		
			$no = $offset + 1;
			foreach($query->result() as $row){
				$rows = array($no);
				
				if (!in_array($offset,array('cetak','excel'))) {
					$rows[] = array('data' => anchor('referensi/dms/kolom/'.$row->id_arsip_jenis,'<i class="fa fa-file-text-o"></i>'),'class' => 'text-center');
				}
				
				$rows[] = $row->kode_arsip_jenis;
				$rows[] = $row->arsip_jenis;
				$rows[] = $row->direktori;
			
				if (!in_array($offset,array('cetak','excel'))) {
					$rows[] = anchor('#','<i class="fa fa-pencil"></i>','class="btn-edit" act="'.site_url('referensi/dms/form_data/'.$row->id_arsip_jenis).'"');
					$rows[] = anchor('#','<i class="fa fa-trash"></i>','class="btn-delete" act="'.site_url('home/general_delete/'.in_de(array('id' => $row->id_arsip_jenis,'redirect' => 'referensi/dms','tabel' => 'ref_arsip_jenis','kolom' => 'id_arsip_jenis'))).'" msg="Apakah Anda ingin menghapus data ini?"');
				}
				
				$this->table->add_row($rows);
				$no++;
			}
	
			$tabel = $this->table->generate();
		}else{
			$tabel = '<div class="alert alert-confirm pull-left">Belum ada data</div><div class="clear"></div>';
		}
		
		
		$btn_cetak = ($query->num_rows() > 0) ?
		'<div class="btn-group"  tyle="margin-left: 5px;">
		<a class="btn btn-warning dropdown-toggle" data-toggle="dropdown" href="#" style="margin: 0 0 0 5px">
		<i class="fa fa-print"></i> <span class="caret"></span>
		</a>
		<ul class="dropdown-menu pull-right">
		<li>'.anchor('referensi/dms/list_data/'.in_de($se).'/cetak','<i class="fa fa-print"></i> Cetak','target="_blank"').'</li>
		<li>'.anchor('referensi/dms/list_data/'.in_de($se).'/excel','<i class="fa fa-file-excel-o"></i> Ekspor Excel','target="_blank"').'</li>
		</ul>
		</div>' : null;
		
		$data['total'] = $config['total_rows'];
		$data['tombol'] = anchor('#','<i class="fa fa-plus"></i> Jenis Arsip','class="btn-edit btn btn-success" act="'.site_url('referensi/dms/form_data').'"').$btn_cetak;
		
		$data['extra_tombol'] = 
		form_open('referensi/dms','id="form_search"').
		'<div class="input-group">
                  <input name="search" type="text" placeholder="Pencarian ..." class="form-control pull-right" value="'.(!empty($key)?$key:(!empty($o['arsip_jenis'])?$o['arsip_jenis']:null)).'">
                  <div class="input-group-btn">
                    <button class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>'.form_close();

		$data['tabel']	= $tabel;
	
		if ($offset == "cetak") {
			$data['title'] = '<h3>Jenis Arsip</h3>';
			$data['content'] = $tabel;
			$this->load->view('umum/print',$data);
		} else if ($offset == "excel") {
			$data['file_name'] = str_replace(" ","_",strtolower('jenis_arsip')).'.xls';
			$data['title'] = '<h3>Jenis Arsip</h3>';
			$data['content'] = $tabel;
			$this->load->view('umum/excel',$data);
		} else {
			$data['tabel'] = $tabel;
			$data['title'] = 'Jenis Arsip';
			$data['content'] = "umum/standard_view";
			$this->load->view('home', $data);
		}
	}

	function form_data($id = null) {
		
		$data['title'] = !empty($id) ? 'Ubah Jenis Arsip' : 'Tambah Jenis Arsip';
		$data['form_link'] = 'referensi/dms/save_dok';
		$def = !empty($id) ? $this->general_model->datagrab(array('tabel' => 'ref_arsip_jenis','where' => array('id_arsip_jenis' => $id)))->row() : null;
		
		$ap = $this->general_model->get_param('app_active');
		$c_app = $this->general_model->combo_box(array('tabel' => 'ref_aplikasi','where' => array('id_aplikasi IN ('.$ap.')' => null),'key' => 'id_aplikasi','val' => array('nama_aplikasi')));
		
		$data['form_data'] = 
			form_hidden('id_arsip_jenis',@$def->id_arsip_jenis).'
			<p>'.form_label('Aplikasi').form_dropdown('id_aplikasi',$c_app,@$def->id_aplikasi,'class="combo-box form-control" style="width: 100%" required').'</p>
			<p>'.form_label('Kode').form_input('kode_arsip_jenis',@$def->kode_arsip_jenis,'class="form-control" required').'<p>
			<p>'.form_label('Jenis Arsip').form_input('arsip_jenis',@$def->arsip_jenis,'class="form-control" required').'<p>
			<p>'.form_label('Direktori Fisik').form_input('direktori',@$def->direktori,'class="form-control"'.(!empty($id)?' readonly ':null).' required').'</p>';
		
		$this->load->view('umum/form_view',$data);
	}
		
	function save_dok() {
		
		$id = $this->input->post('id_arsip_jenis');
		$simpan = array(
			'id_aplikasi' => $this->input->post('id_aplikasi'),
			'kode_arsip_jenis' => $this->input->post('kode_arsip_jenis'),
			'arsip_jenis' => $this->input->post('arsip_jenis'),
			'direktori' => $this->input->post('direktori')
		);
		
		$this->general_model->save_data('ref_arsip_jenis',$simpan,'id_arsip_jenis',$id);
		$this->session->set_flashdata('ok','Jenis Arsip berhasil disimpan ...');
		redirect('referensi/dms');
		
	}
	
	function kolom($id,$search = null,$offset = null) {
		
		$data['breadcrumb'] = array('' => 'Referensi','referensi/dms' => 'Jenis Dokumen','referensi/dms/kolom/'.$id => 'Kolom');
		
		$offset = !empty($offset) ? $offset : null;
		
		$key = $this->input->post('search');
		$se = array();
		
		if (!empty($key)) {
			$se['kolom'] = $key;
		} else if ($search) {
			$o = un_de($search);
			$se['kolom'] = @$o['kolom'];
		} 
		
		$config['base_url']	= site_url('referensi/dms/kolom/'.$id.'/'.in_de($se));
		$config['total_rows'] = $this->general_model->datagrab(array('tabel'=> 'ref_arsip_kolom','where' => array('id_arsip_jenis' => $id),'search' => $se))->num_rows();
		$config['per_page']	= '10';
		$config['uri_segment'] = '6';
		
		$this->pagination->initialize($config);
		
		$data['links']	= $this->pagination->create_links();
		
		$offs = (!in_array($offset,array("cetak","excel"))) ?  $offset : null;
		$lim = (!in_array($offset,array("cetak","excel"))) ? $config['per_page'] : null;
		
		$query = $this->general_model->datagrab(array(
			'tabel' => 'ref_arsip_kolom',
			'offset' => $offs,
			'limit' => '10',
			'order' => 'kolom',
			'where' => array('id_arsip_jenis' => $id),
			'search' => $se
		));
		

		if ($query->num_rows() != 0) {
			$classy = (in_array($offset,array("cetak","excel"))) ? 'class="tabel_print" border=1' : 'class="table table-striped table-bordered table-condensed table-nonfluid"';
			$this->table->set_template(array('table_open'=>'<table '.$classy.'>'));
			$heads = array('No','Kolom','Tipe');
			if (!in_array($offset,array('cetak','excel'))) $heads[] = array('data' => 'Aksi','colspan' => '3');
			$this->table->set_heading($heads);
		
			$no = $offset + 1;
			foreach($query->result() as $row){
				$rows = array($no);
				
				
				$c = array(
			'' => ' -- Pilih Jenis Kolom -- ',
			'1' => 'Isian teks',
			'2' => 'Boks Teks',
			'3' => 'Tanggal'
		);
				$rows[] = $row->kolom;
				$rows[] = $c[$row->tipe];
			
				if (!in_array($offset,array('cetak','excel'))) {
					$rows[] = anchor('#','<i class="fa fa-pencil"></i>','class="btn-edit" act="'.site_url('referensi/dms/form_kolom/'.$id.'/'.$row->id_arsip_kolom).'"');
					$rows[] = anchor('#','<i class="fa fa-trash"></i>','class="btn-delete" act="'.site_url('home/general_delete/'.in_de(array('id' => $row->id_arsip_kolom,'redirect' => 'referensi/dms/kolom/'.$id,'tabel' => 'ref_arsip_kolom','kolom' => 'id_arsip_kolom'))).'" msg="Apakah Anda ingin menghapus kolom ini?<br>Menghapus kolom akan menghapus semua data di dokumen terhubung dengan kolom ini!"');
				}
				
				$this->table->add_row($rows);
				$no++;
			}
	
			$tabel = $this->table->generate();
		}else{
			$tabel = '<div class="alert alert-confirm pull-left">Belum ada data</div><div class="clear"></div>';
		}
		
		$btn_cetak = ($query->num_rows() > 0) ? 
		'<div class="btn-group"  tyle="margin-left: 5px;">
		<a class="btn btn-warning dropdown-toggle" data-toggle="dropdown" href="#" style="margin: 0 0 0 5px">
		<i class="fa fa-print"></i> <span class="caret"></span>
		</a>
		<ul class="dropdown-menu pull-right">
		<li>'.anchor('referensi/dms/kolom/'.$id.'/'.in_de($se).'/cetak','<i class="fa fa-print"></i> Cetak','target="_blank"').'</li>
		<li>'.anchor('referensi/dms/kolom/'.$id.'/'.in_de($se).'/excel','<i class="fa fa-file-excel-o"></i> Ekspor Excel','target="_blank"').'</li>
		</ul>
		</div>' : null;
		
		$data['total'] = $config['total_rows'];
		$data['tombol'] = 
			anchor('referensi/dms','<i class="fa fa-arrow-left"></i>','class="btn btn-default"').' &nbsp; '.
			anchor('#','<i class="fa fa-plus"></i> &nbsp; Kolom Arsip','class="btn-edit btn btn-success" act="'.site_url('referensi/dms/form_kolom/'.$id).'"').
			$btn_cetak;
		
		$data['extra_tombol'] = 
		form_open('referensi/dms/kolom/'.$id,'id="form_search"').
		'<div class="input-group">
                  <input name="search" type="text" placeholder="Pencarian ..." class="form-control pull-right" value="'.(!empty($key)?$key:(!empty($o['arsip_jenis'])?$o['arsip_jenis']:null)).'">
                  <div class="input-group-btn">
                    <button class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>'.form_close();

		$data['tabel']	= $tabel;
	
		if ($offset == "cetak") {
			$data['title'] = '<h3>kolom Arsip</h3>';
			$data['content'] = $tabel;
			$this->load->view('umum/print',$data);
		} else if ($offset == "excel") {
			$data['file_name'] = str_replace(" ","_",strtolower('kolom_arsip')).'.xls';
			$data['title'] = '<h3>Kolom Arsip</h3>';
			$data['content'] = $tabel;
			$this->load->view('umum/excel',$data);
		} else {
			$data['tabel'] = $tabel;
			$data['title'] = 'Kolom Arsip';
			$data['content'] = "umum/standard_view";
			$this->load->view('home', $data);
		}
	}
	
	function form_kolom($id_arsip,$id = null) {
		
		$data['title'] = !empty($id) ? 'Ubah Kolom Arsip' : 'Tambah Kolom Arsip';
		$data['form_link'] = 'referensi/dms/save_kolom';
		$def = !empty($id) ? $this->general_model->datagrab(array('tabel' => 'ref_arsip_kolom','where' => array('id_arsip_kolom' => $id)))->row() : null;
		
		$c = array(
			'' => ' -- Pilih Jenis Kolom -- ',
			'1' => 'Isian teks',
			'2' => 'Boks Teks',
			'3' => 'Tanggal'
		);
		
		$data['form_data'] = 
			form_hidden('id_arsip_kolom',@$def->id_arsip_kolom).
			form_hidden('id_arsip_jenis',$id_arsip).'
			<p>'.form_label('Judul Kolom').form_input('kolom',@$def->kolom,'class="form-control" required').'<p>
			<p>'.form_label('Jenis Kolom').form_dropdown('tipe',$c,@$def->tipe,'class="form-control combo-box" style="width: 100%" required').'<p>';
		
		$this->load->view('umum/form_view',$data);
	}
		
	function save_kolom() {
		
		$id = $this->input->post('id_arsip_kolom');
		$id_arsip = $this->input->post('id_arsip_jenis');
		$simpan = array(
			'id_arsip_jenis' => $id_arsip,
			'kolom' => $this->input->post('kolom'),
			'tipe' => $this->input->post('tipe')
		);
		
		$this->general_model->save_data('ref_arsip_kolom',$simpan,'id_arsip_kolom',$id);
		$this->session->set_flashdata('ok','Kolom arsip berhasil disimpan ...');
		redirect('referensi/dms/kolom/'.$id_arsip);
		
	}

}
?>