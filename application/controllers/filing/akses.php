<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Akses extends CI_Controller {

	function __construct() {
	
		parent::__construct();
		login_check($this->session->userdata('login_state'));
		
	}

	public function index() {
		
		$this->load_list();
	
	}
	
	function in_app() {
		
		return $this->general_model->datagrab(array(
			'tabel' => 'ref_aplikasi','where' => array('folder' => $this->uri->segment(1))
		))->row()->nama_aplikasi;
		
	}

	function cr($e) {
	    return $this->general_model->check_role($this->session->userdata('id_pegawai'),$e);
    }

	function load_list($search = null,$offset = null) {
		
		$judul = 'Akses Arsip';

		$data['breadcrumb'] = array('' => $this->in_app(), 'filing/akses' => $judul);
		
		$offset = !empty($offset) ? $offset : null;

		$to_url = array();
		$search_key = $this->input->post('key');
		if (!empty($search_key)) {
			$to_url = array(
				'text' => $search_key);
		} else if ($search) {
			$to_url=un_de($search);
		}
	
		$from = array(
			'arsip_akses a' => '',
			'ref_arsip_jenis j' => 'j.id_arsip_jenis = a.id_arsip_jenis',
			'ref_unit u' => 'u.id_unit = a.id_unit',
			'ref_bidang b' => array('b.id_bidang = a.id_bidang','left'));
	
		if (!empty($to_url['text'])) {
			$s_text = $to_url['text'];
			$search_text = array(
				'u.unit' => $s_text,
				'b.nama_bidang' => $s_text,
				'j.arsip_jenis' => $s_text);
		}
		
		$config['base_url']	= site_url('filing/akses/load_list/'.in_de($to_url));
		$config['total_rows'] = $this->general_model->datagrab(array(
			'tabel' => $from,
			'search' => @$search_text,
			'group_by' => 'a.id_arsip_jenis',
			'select' => 'count(*) as jml'))->num_rows();
		$config['per_page']	= '10';
		$config['uri_segment'] = '5';
		$this->pagination->initialize($config);
		
		$data['total']	= $config['total_rows'];
		$data['links']	= $this->pagination->create_links();
		
		$lim = ($offset == "cetak" or $offset == "excel") ? null : $config['per_page'];
		$offs = ($offset == "cetak" or $offset == "excel") ? null : $offset;
		
		$s_url = in_de($to_url).'~'.(!empty($offset)?$offset:0);
		
		$load_data = $this->general_model->datagrab(array(
			'tabel' => $from, 'limit' => $lim, 'offset' => $offs, 
			'order' => 'j.arsip_jenis',
			'group_by' => 'a.id_arsip_jenis',
			'select' => 'CONCAT(
					j.arsip_jenis,
					IF(j.kode_arsip_jenis = "" or j.kode_arsip_jenis IS NULL,"",CONCAT("(",j.kode_arsip_jenis,")"))) arsip,
				a.id_akses,
				a.id_arsip_jenis,
				j.arsip_jenis', 
			'search' => @$search_text));
			
		$id_a = array();
		foreach($load_data->result() as $l) { $id_a[] = $l->id_arsip_jenis; }

		$where_unit = (count($id_a) > 0) ? array('a.id_arsip_jenis IN ('.implode(',',$id_a).')' => null) : null;
	
		$load_unit = $this->general_model->datagrab(array(
			'tabel' => $from,
			'order' => 'j.arsip_jenis',
			'where' => $where_unit,
			'select' => 'a.id_arsip_jenis,u.unit,b.nama_bidang', 
			'search' => @$search_text));
			
		if ($load_data->num_rows() > 0) {
			
			$heads = array('No','Jenis Arsip','Unit Kerja','Unit Organisasi');
	
			if (!in_array($offset,array("cetak","excel"))) $heads[] = array('data' => 'Aksi','colspan' => 2);
			$classy = (in_array($offset,array("cetak","excel"))) ? 'class="tabel_print" border=1' : 'class="table table-striped table-bordered table-condensed table-nonfluid"';
			
			$this->table->set_template(array('table_open'=>'<table '.$classy.'>'));
			$this->table->set_heading($heads);
		
			$no = 1 + $offset;
			foreach($load_data->result() as $row) {
				$rows = array(
					$no,
					array('data' => $row->arsip_jenis,'colspan' => 5)
				);
				$this->table->add_row($rows);
				
				$nama_unit = null; $nama_bidang = null;
				foreach($load_unit->result() as $u) {
					if ($u->id_arsip_jenis == $row->id_arsip_jenis) {
						
						$r_unit = array('','');
						$r_unit[] = $u->unit;
						$r_unit[] = !empty($u->nama_bidang)?$u->nama_bidang:array('data' =>  ' <i>Seluruh Unit Organisasi</i> ','class' => 'text-center text-muted');
						
					
					if (!in_array($offset,array("cetak","excel"))) {
						$r_unit[] = anchor('#','<i class="fa fa-pencil"></i>','class="btn-edit btn btn-xs btn-warning" act="'.site_url('filing/akses/form_data/'.$s_url.'/'.$row->id_akses).'"');
						$r_unit[] = anchor('#','<i class="fa fa-trash"></i>','class="btn-delete btn btn-xs btn-danger" act="'.site_url('filing/akses/delete_data/'.$s_url.'/'.$row->id_akses).'" msg="Apakah Anda ingin menghapus data ini?"');
			
					}
					$this->table->add_row($r_unit);
					}
				}
				$no++;
			}
			
			$tabel = $this->table->generate();
			
		} else {
			$tabel = '<div class="alert">Kewenangan akses per jenis dokumen belum ditentukan ...</div>';
		}
		
		$btn_tambah = anchor('#','<i class="fa fa-plus fa-btn"></i> Akses Baru','
			class="btn btn-success btn-edit"
			act="'.site_url('filing/akses/form_data/'.$s_url).'" title="Tambah akses arsip baru ..."');
		
		$btn_cetak =
			'<div class="btn-group">
			<a class="btn btn-warning dropdown-toggle" data-toggle="dropdown" href="#" style="margin: 0 0 0 5px">
			<i class="fa fa-print"></i> <span class="caret"></span>
			</a>
			<ul class="dropdown-menu pull-right">
			<li>'.anchor('filing/akses/load_list/'.in_de($to_url).'/cetak','<i class="fa fa-print"></i> Cetak','target="_blank"').'</li>
			<li>'.anchor('filing/akses/load_list/'.in_de($to_url).'/excel','<i class="fa fa-file-excel-o"></i> Ekspor Excel','target="_blank"').'</li>
			</ul>
			</div>';
		
		$data['tombol'] = $btn_tambah.' '.$btn_cetak;
		$data['extra_tombol'] = 
		form_open('filing/akses/load_list','id="form_search" role="form"').
		'<div style="width: 5%; float: right"></div>
		<div class="input-group pull-right" style="min-width: 60%; max-width: 60%">
				  <input name="key" type="text" placeholder="Pencarian ..." class="form-control" value="'.@$to_url['text'].'">
				  <div class="input-group-btn">
					<button class="btn btn-default"><i class="fa fa-search"></i></button>
				  </div>
				</div><div class="clear"></div>'.
				form_close();
		
		
		if ($offset == "cetak") {
		$data['title'] = '<h3>'.$judul.'</h3>';
		$data['content'] = $tabel;
		$this->load->view('umum/print',$data);
		
		} else if ($offset == "excel") {
		$data['file_name'] = 'akses_arsip.xls';
		$data['title'] = '<h3>'.$judul.'</h3>';
		$data['content'] = $tabel;
		$this->load->view('umum/excel',$data);
		
		} else {
			
		$data['tabel'] = $tabel;
		$data['title'] 	= $judul; 
		$data['content'] = 'umum/standard_view';
		$this->load->view('home', $data);
		
		}
	}
	
	function form_data($s_url,$id = null) {
		
		$data['title'] = (!empty($id)?'Ubah':'Tambah').' Akses Arsip';
		
		$data['def'] = (!empty($id)) ? $this->general_model->datagrab(array('tabel' => 'arsip_akses','where' => array('id_akses' => $id)))->row() : null;
		$data['s_url'] = $s_url;
		$data['cb_arsip'] = $this->general_model->combo_box(array(
			'tabel' => 'ref_arsip_jenis','key' => 'id_arsip_jenis','val' => array('arsip_jenis')));
		$data['cb_unit'] = $this->general_model->combo_box(array(
			'tabel' => 'ref_unit','key' => 'id_unit','val' => array('unit'),'order' => 'urut'));
		
		if (!empty($data['def'])) {
			
			$data['cb_bidang'] = $this->general_model->combo_box(array(
				'tabel' => 'ref_bidang',
				'key' => 'id_bidang',
				'val' => array('nama_bidang'),
				'where' => array('id_unit' => $data['def']->id_unit),
				'pilih' => ' -- Seluruh Unit Organisasi -- '));
						
		}
		$this->load->view('filing/akses_form_view',$data);
		
		
	}
	
	function cb_bidang($id) {
		$data_comb = $this->general_model->combo_box(array(
			'tabel'=>array('ref_bidang'=>''),
			'key'=>'id_bidang',
			'val'=>array('nama_bidang'),
			'order' => 'urut',
			'where'=>array('id_unit'=>$id),
			'pilih' => ' -- Seluruh Unit Organisasi -- '));
		$combo = array();
		foreach($data_comb as $k=>$v){
			$combo .= '<option value="'.$k.'">'.$v.'</option>';
		}
		echo $combo;
	}
	
	function save_data() {
		
		$id = $this->input->post('id_akses');
		
		$simpan = array(
			'id_arsip_jenis' => $this->input->post('id_arsip_jenis'),
			'id_unit' => $this->input->post('id_unit'),
			'id_bidang' => $this->input->post('id_bidang')
		); $this->general_model->save_data('arsip_akses',$simpan,'id_akses',$id);
		
		$this->session->set_flashdata('ok','Akses Arsip berhasil disimpan ...');
		redirect('filing/akses/load_list/'.str_replace('~','/',$this->input->post('s_url')));
		
	}
	
	function delete_data($s,$id) {
		
		$this->general_model->delete_data('arsip_akses','id_akses',$id);
		
		$this->session->set_flashdata('ok','Akses Arsip berhasil dihapus ...');
		redirect('filing/akses/load_list/'.str_replace('~','/',$s));
		
	}
}
