<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Barang extends CI_Controller {

	function __construct() {	
		parent::__construct();
		$this->load->model('referensi/barang_model');
	}

	public function index() {
		$this->list_data();
	}
	
	function tabelSubbarang($id) {
	
		if ($this->barang_model->cekbarangAnak($id)->num_rows() > 0) {
			foreach($this->barang_model->cekbarangAnak($id)->result() as $row) {
				
				$data_cell = array();
				
				for ($j=0;$j < $row->level; $j++) {
					if ($j>0) $data_cell[] = array('data'=>'','width' => '20');
				}

				$cell_aksi = '&nbsp; &nbsp; <a href="#" class="btn-edit" act="'.site_url('referensi/barang/form_barang/'.$row->id_brg).'"><i class="fa fa-pencil"></i></a> &nbsp';
				
				if ($this->barang_model->cekbarangAnak($row->id_brg)->num_rows() == 0) {
				
					$cell_aksi.='<a href="#" class="btn-delete" act="'.site_url('referensi/barang/delete_barang/'.$row->id_brg).'" msg="Apakah barang <b>'.$row->kode_barang.' '.$row->nama_barang.'</b> akan dihapus?"><i class="fa fa-trash"></i></a></a>';
				
				}
				
				$binded_data = !empty($row->id_brg_bind) ? $this->barang_model->data_list(null,null,$row->id_brg_bind)->row() : null;
				$binded = !empty($binded_data) ? '- <i>'.$binded_data->kode_barang.' '.$binded_data->nama_barang.'</i>' : null;
				
				$data_cell[] = array('data'=> '<b>'.$row->kode_barang.'</b> &nbsp; &nbsp; '.$row->nama_barang.' '.$binded.' <a href="#" class="btn-edit" act="'.site_url('referensi/barang/form_barang_child/'.$row->id_brg).'"><i class="icon-plus-sign"></i></a></span>','colspan'=> 8-$row->level);
				$data_cell[] = $cell_aksi;
					
				$this->table->add_row($data_cell);
				
				if ($this->barang_model->cekbarangAnak($row->id_brg)->num_rows() > 0) $this->tabelSubbarang($row->id_brg);
			}
		}
		
	}
	
	function table_content($tipe) {
	
		if ($this->barang_model->data_list($tipe,1)->num_rows() > 0) {
            $this->table->set_template(array('table_open' => '<table class="table table-striped table-bordered table-condensed">'));
            $this->table->set_empty("&nbsp;");	
			$this->table->set_heading(
				array('data' => 'Kode / Nama barang','colspan' => '7'),
				array('data' => 'Aksi', 'width' => '70'));
	    

			if ($this->barang_model->data_list($tipe,1)->num_rows() > 0) {
				foreach ($this->barang_model->data_list($tipe,1)->result() as $row) {
				
					$cell_aksi = '&nbsp; &nbsp; 
						<a href="#" class="btn-edit" act="'.site_url('referensi/barang/form_barang/'.$row->id_brg).'"><i class="fa fa-pencil"></i></a> &nbsp;';
					
					if ($this->barang_model->cekbarangAnak($row->id_brg)->num_rows() == 0)  {
						$cell_aksi.= '<a href="#" class="btn-delete" act="'.site_url('referensi/barang/delete_barang/'.$row->id_brg).'" msg="Apakah barang <b>'.$row->kode_barang.' '.$row->nama_barang.'</b> akan dihapus?"><i class="fa fa-trash"></i></a>';
					}
					
					$binded_data = !empty($row->id_brg_bind) ? $this->barang_model->data_list(null,null,$row->id_brg_bind)->row() : null;
					$binded = !empty($binded_data) ? '- <span>'.$binded_data->kode_barang.' '.$binded_data->nama_barang.'</span>' : null;
					$this->table->add_row(
						array('data'=> '<b>'.$row->kode_barang.'</b> '.$row->nama_barang.' '.$binded.' <a href="#" class="btn-edit" act="'.site_url('referensi/barang/form_barang_child/'.$row->id_brg).'"><i class="icon-plus-sign"></i></a>','colspan'=> 7),
						$cell_aksi
					);
					if ($this->barang_model->cekbarangAnak($row->id_brg)->num_rows() > 0) $this->tabelSubbarang($row->id_brg);
				}
				return $this->table->generate();
			} else {
				return notif('Belum ada daftar kode barang');
			}
		} else {
		
		return '<div class="alert alert-warning gap">Belum ada daftar kode barang</div>';
		
		}
	
	
	}
	
	function list_data() {
		$data['breadcrumb'] = array('' => 'Referensi', 'referensi/barang' => 'Referensi Kode Barang');
		$data['tabel_reg'] = $this->table_content(1);
		$data['pajak_masuk'] = $this->table_content(2);
		$data['pajak_keluar'] = $this->table_content(3);
		$data['kas'] = $this->table_content(4);		
		$data['content'] = "referensi/barang_view";
		$this->load->view('home', $data);	
	}
	
	function form_barang($id = null) {
		$data['title'] = (!empty($id)) ? "Ubah" : "Tambah";
		$data['default'] = (!empty($id)) ? $this->barang_model->data_list(null,null,$id) : null;
		$pasangan = array('' => '-- Tanpa Pasangan --');
		$data_bind = $this->barang_model->data_list(3,null,null,1);
		foreach($data_bind->result() as $bind) {
			$pasangan[$bind->id_brg] = $bind->kode_barang.' '.$bind->nama_barang;
		}
		$data['pasangan'] = $pasangan;
		$this->load->view('referensi/barang_form_root', $data);	
	}
	
	function form_barang_child($id=null) {
		$data['title'] = (!empty($id)) ? "Ubah" : "Tambah";
		$data['parent'] = $this->barang_model->data_list(null,null,$id)->row();
		$this->load->view('referensi/barang_form', $data);
	}
	
	function save_barang_root() {
		$id = $this->input->post('id_brg');
		$id_brg_bind = $this->input->post('id_brg_bind');
		$simpan = array(
            'kode_barang' => $this->input->post('kode_barang'),
            'nama_barang' => $this->input->post('nama_barang'),
			'is_child' => $this->input->post('is_child'),
			'level' => $this->input->post('level'),
			'jenis' => $this->input->post('jenis')
        );
		if (!empty($id_brg_bind)) $simpan['id_brg_bind'] = $id_brg_bind;
		$this->barang_model->save_barang($simpan, $id);
		$this->session->set_flashdata('ok', 'Data berhasil disimpan');
        redirect('referensi/barang');
	}
	
	
	function save_barang() {
		$id = $this->input->post('id_brg');
		$id_parent = $this->input->post('id_brg_parent');
		$id_brg_bind = $this->input->post('id_brg_bind');
		
		$this->barang_model->notchildbarang($id_parent);
		$simpan = array(
            'kode_barang' => $this->input->post('kode_barang'),
            'nama_barang' => $this->input->post('nama_barang'),
			'id_brg_parent' => $id_parent,
			'is_child' => $this->input->post('is_child'),
			'level' => $this->input->post('level')
        );
		if (!empty($id_brg_bind)) $simpan['id_brg_bind'] = $id_brg_bind;
		$this->barang_model->save_barang($simpan, $id);
		$this->session->set_flashdata('ok', 'Data berhasil disimpan');
        redirect('referensi/barang');
		
	}
	
	function delete_barang($id) {
		
		$this->barang_model->delete_barang($id);
		$this->session->set_flashdata('ok', 'barang Berhasil dihapus');
      
        redirect('referensi/barang');
    
	}
	
}
