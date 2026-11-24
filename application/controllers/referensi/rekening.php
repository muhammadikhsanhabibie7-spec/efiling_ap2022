<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rekening extends CI_Controller {

	function __construct() {	
		parent::__construct();
		login_check($this->session->userdata('login_state'));
	}

	public function index() {
		$this->list_data();
	}
	
	function child_check($id) {
		$data = $this->general_model->datagrab(array(
			'tabel' => 'ref_rekening',
			'where' => array('id_rek_parent' => $id)
		));
		return $data;
	}
	
	function tabelSubRekening($id,$tipe) {
	
		if ($this->child_check($id)->num_rows() > 0) {
			foreach($this->child_check($id)->result() as $row) {
				
				$data_cell = array();
				
				for ($j=0;$j < $row->level; $j++) {
					if ($j>0) $data_cell[] = array('data'=>'','width' => '20');
				}

				$cell_aksi = '&nbsp; &nbsp; <a href="#" class="btn-edit" act="'.site_url('referensi/rekening/form_rekening/'.$row->id_rek).'"><i class="glyphicon glyphicon-edit"></i></a> &nbsp';
				
				if ($this->child_check($row->id_rek)->num_rows() == 0) {
				
					$cell_aksi.='<a href="#" class="btn-delete" act="'.site_url('referensi/rekening/delete_rekening/'.$row->id_rek).'" msg="Apakah Rekening <b>'.$row->kode_rekening.' '.$row->nama_rekening.'</b> akan dihapus?"><i class="fa fa-trash"></i></a>';
				
				}
				
				$binded_data = !empty($row->id_rek_bind) ? $this->general_model->datagrab(array('tabel' => 'ref_rekening','where' => array('id_rek_bind' => $row->id_rek_bind)))->row() : null;
				$binded = !empty($binded_data) ? ' - <i>'.$binded_data->kode_rekening.' '.$binded_data->nama_rekening.'</i>' : null;
				
				$oncell = ($row->jenis == $tipe) ? '<b>'.$row->kode_rekening.'</b> &nbsp; &nbsp; '.$row->nama_rekening.$binded : '(<b>'.$row->kode_rekening.'</b> &nbsp; &nbsp; '.$row->nama_rekening.$binded.')';
				
				$data_cell[] = array('data'=> $oncell.' <a href="#" class="btn-edit" act="'.site_url('referensi/rekening/form_rekening_child/'.$row->id_rek).'"><i class="icon-plus-sign"><i>','colspan'=> 8-$row->level);
				$data_cell[] = $cell_aksi;
					
				$this->table->add_row($data_cell);
				
				if ($this->child_check($row->id_rek)->num_rows() > 0) $this->tabelSubRekening($row->id_rek,$tipe);
			}
		}
		
	}
	
	function table_content($tipe) {
		$rek_root = $this->general_model->datagrab(array('tabel' => 'ref_rekening','where' => array('level' => 1,'jenis' => $tipe)));
		if ($rek_root->num_rows() > 0) {
            $this->table->set_template(array('table_open' => '<table class="table table-striped table-bordered table-condensed gap">'));
            $this->table->set_empty("&nbsp;");	
			$this->table->set_heading(
				array('data' => 'Kode / Nama Rekening','colspan' => '7'),
				array('data' => 'Aksi', 'width' => '70'));
	    

			if ($rek_root->num_rows() > 0) {
				foreach ($rek_root->result() as $row) {
				
					$cell_aksi = '&nbsp; &nbsp; 
						<a href="#" class="btn-edit" act="'.site_url('referensi/rekening/form_rekening/'.$row->id_rek).'"><i class="icon icon-pencil"></i></a> &nbsp;';
					
					if ($this->child_check($row->id_rek)->num_rows() == 0)  {
						$cell_aksi.= '<a href="#" class="btn-delete" act="'.site_url('referensi/rekening/delete_rekening/'.$row->id_rek).'" msg="Apakah Rekening <b>'.$row->kode_rekening.' '.$row->nama_rekening.'</b> akan dihapus?"><i class="fa fa-trash"></i></a>';
					}
					
					$binded_data = !empty($row->id_rek_bind) ? $this->general_model->data_list(null,null,$row->id_rek_bind)->row() : null;
					$binded = !empty($binded_data) ? '- <i>'.$binded_data->kode_rekening.' '.$binded_data->nama_rekening.'</i>' : null;
					$this->table->add_row(
						array('data'=> '<b>'.$row->kode_rekening.'</b> '.$row->nama_rekening.' '.$binded.' <a href="#" class="btn-edit" act="'.site_url('referensi/rekening/form_rekening_child/'.$row->id_rek).'"><i class="icon-plus-sign"></i></a>','colspan'=> 7),
						$cell_aksi
					);
					if ($this->child_check($row->id_rek)->num_rows() > 0) echo $this->tabelSubRekening($row->id_rek, $tipe);
				}
				return $this->table->generate();
			} else {
				return notif('Belum ada rekening terdaftar');
			}
		} else {
		
		return '<div class="alert alert-warning gap">Belum ada rekening terdaftar</div>';
		
		}
	
	
	}
	
	function list_data() {
		
		$data['breadcrumb'] = array('' => 'Referensi', 'referensi/rekening' => 'Referensi Rekening');
		
		$data['title'] = 'Bagan Akun Standar';
		$data['tombol'] = anchor('#','<i class="icon-plus-sign icon-white"></i> Tambah Akun','class="btn btn-edit btn-success" act="'.site_url('referensi/rekening/form_rekening').'"');
		
		$prn = '
		<ul class="nav nav-tabs" id="myTab">
			<li class="active"><a href="#tab1" data-toggle="tab1">Akun Pemerintahan</a></li>
			<li><a href="#tab2" data-toggle="tab">Akun Pajak</a></li>
			<li><a href="#tab3" data-toggle="tab">Akun Umum</a></li>
		</ul>

		<div class="tab-content">

		';

		
		$prn.= '
		<div class="tab-pane active" id="tab1">
		<h3>Akun Masuk</h3>'.$this->table_content(4).'<h3>Akun Keluar</h3>'.$this->table_content(1).'</div>';
		$prn.= '<div class="tab-pane" id="tab2">
		<h3>Pajak Masuk</h3>'.$this->table_content(2).'<h3>Pajak Keluar</h3>'.$this->table_content(3).'</div>';
		$prn.= '<div class="tab-pane" id="tab3">
		<h3>Akun Debit</h3>'.$this->table_content(5).'<h3>Akun Kredit</h3>'.$this->table_content(6).'</div>';
		
		$data['tabel'] = $prn.'</div>';
		$data['content'] = "umum/standard_view";
		$this->load->view('home', $data);
		
	}
	
	
	function form_rekening($id = null) {
	
		$data['title'] = (!empty($id)) ? "Ubah Akun" : "Tambah Akun";
		$def = (!empty($id)) ? $this->general_model->datagrab(array('tabel' => 'ref_rekening','where' => array('id_rek' => $id)))->row() : null;
		$pasangan = array('' => '-- Tanpa Pasangan --');
		$data_bind = $this->general_model->datagrab(array('tabel' => 'ref_rekening','where' => array('jenis' => 3)));
		foreach($data_bind->result() as $bind) {
			$pasangan[$bind->id_rek] = $bind->kode_rekening.' '.$bind->nama_rekening;
		}
		
		$data['form_link'] = 'referensi/rekening/save_rekening_root';
		
		$pajak = array(
			'4' => 'Akun Masuk',
			'1' => 'Akun Keluar',
			'2' => 'Pajak Masuk',
			'3' => 'Pajak Keluar',
			'5' => 'Akun Debit',
			'6' => 'Akun Kredit'
		);

		$data['form_data'] = 
			form_hidden("id_rek", isset($def->id_rek) ? $def->id_rek : '').
			form_hidden("level", isset($def->level) ? $def->level : '1').
			form_hidden("is_child", isset($def->is_child) ? $def->is_child : '1').
			'<p>'.form_label('Kode Akun').form_input("kode_rekening", @$def->kode_rekening,'class="input-xlarge" placeHolder="Kode Rekening ..."').'</p>'.
			'<p>'.form_label('Nama Akun').form_input("nama_rekening", @$def->nama_rekening,'class="input-xlarge" placeHolder="Nama Rekening ..."').'</p>';
			
			if (!empty($def) and $def->jenis == "2") '<p>'.form_label('Rekening Pasangan').form_dropdown("id_rek_bind", $pasangan,isset($def->id_rek_bind) ? $def->id_rek_bind : null,'class="input-xlarge combo-box"').'</p>';
			else $data['form_data'].='<p>'.form_label('Jenis Rekening').form_dropdown('jenis',$pajak,@$def->jenis,'class="combo-box input-medium"').'</p>';

		
		
		$this->load->view('umum/form_view', $data);
		
	}
	
	function form_rekening_child($id=null) {
	
		$data['title'] = 'Tambah Sub Akun';
		$parent = $this->general_model->datagrab(array('tabel' => 'ref_rekening','where' => array('id_rek' => $id)))->row();
		$pasangan = array('' => '-- Tanpa Pasangan --');
		$data_bind = $this->general_model->datagrab(array('tabel' => 'ref_rekening','where' => array('jenis' => '3')));
		foreach($data_bind->result() as $bind) {
			$pasangan[$bind->id_rek] = $bind->kode_rekening.' '.$bind->nama_rekening;
		}
		$data['form_data'] = 
			form_hidden("id_rek", @$def->id_rek).
			form_hidden("level", isset($def->level) ? $def->level : $parent->level+1).
			form_hidden("is_child", isset($def->is_child) ? $def->is_child : '1').
			form_hidden("id_rek_parent", $parent->id_rek).
			form_hidden("jenis_parent", $parent->jenis).
			'<p>'.form_label('Akun Kepala').form_input('rek_parent',$parent->kode_rekening,'class="uneditable-input" readonly="true"').'</p>'.
			'<p>'.form_label('Kode Akun').form_input("kode_rekening", @$def->kode_rekening).'</p>'.
			'<p>'.form_label('Nama Akun').form_input("nama_rekening", @$def->nama_rekening,'class="input-xlarge"').'</p>';
		
		if ($parent->jenis == "2") {
			$data['form_data'] = '<p>'.form_label('Rekening Pasangan').form_dropdown("id_rek_bind", $pasangan,isset($def->id_rek_bind) ? $def->id_rek_bind : null,'class="input-xlarge"').'</p>';
		}
		$data['form_link'] = 'referensi/rekening/save_rekening';
		
		$this->load->view('umum/form_view', $data);
	}
	
	function save_rekening_root() {
		
		$id = $this->input->post('id_rek');
		$id_rek_bind = $this->input->post('id_rek_bind');
		$simpan = array(
            'kode_rekening' => $this->input->post('kode_rekening'),
            'nama_rekening' => $this->input->post('nama_rekening'),
			'is_child' => $this->input->post('is_child'),
			'level' => $this->input->post('level'),
			'jenis' => $this->input->post('jenis')
        );
		if (!empty($id_rek_bind)) $simpan['id_rek_bind'] = $id_rek_bind;
		$this->general_model->save_data('ref_rekening',$simpan,'id_rek',$id);
		$this->session->set_flashdata('ok', 'Rekening berhasil disimpan');
        redirect('referensi/rekening');
		
	}
	
	
	function save_rekening() {

		$id = $this->input->post('id_rek');
		$id_parent = $this->input->post('id_rek_parent');
		$id_rek_bind = $this->input->post('id_rek_bind');
		
		$this->general_model->save_data('ref_rekening',array('is_child' => '0'),'id_rek',$id_parent);
		$simpan = array(
            'kode_rekening' => $this->input->post('kode_rekening'),
            'nama_rekening' => $this->input->post('nama_rekening'),
            'jenis' => $this->input->post('jenis_parent'),
			'id_rek_parent' => $id_parent,
			'is_child' => $this->input->post('is_child'),
			'level' => $this->input->post('level')
        );
		if (!empty($id_rek_bind)) $simpan['id_rek_bind'] = $id_rek_bind;
		$this->general_model->save_data('ref_rekening',$simpan,'id_rek',$id);
		$this->session->set_flashdata('ok', 'Data berhasil disimpan');
        redirect('referensi/rekening');
		
	}
	
	function delete_rekening($id) {
		$getParents = $this->general_model->datagrab(array('tabel' => 'ref_rekening','where' => array('id_rek' => $id)))->row();
	
		$this->general_model->delete_data('ref_rekening','id_rek', $id);
        
		if (!empty($getParents)) {
			if ($this->general_model->datagrab(array('tabel' => 'ref_rekening','where' => array('id_rek_parent' => $getParents->id_rek_parent)))->num_rows() == 0)  {
				$this->general_model->save_data('ref_rekening',array('is_child' => '1'),'id_rek',$getParents->id_rek_parent);
			}
		}
		$this->session->set_flashdata('ok', 'Rekening Berhasil dihapus');
      
        redirect('referensi/rekening');
    
	}
	
}
