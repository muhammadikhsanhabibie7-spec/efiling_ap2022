<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Laporan_keuangan extends CI_Controller {

		var $tabel = 'sap_laporan';
		
		function __construct() {
		
			parent::__construct();
			login_check($this->session->userdata('login_state'));
			
		}

		public function index() {
			$this->list_laporan_keuangan();
		}
		
		function list_laporan_keuangan($offset = null) {
		
			$data['breadcrumb'] = array('' => 'Referensi', 'referensi/laporan_keuangan' => 'Laporan Keuangan');
			
			$offset = !empty($offset) ? $offset : null;
			
			$config['base_url'] = site_url('referensi/laporan_keuangan/list_laporan_keuangan');
			$config['total_rows'] = $this->general_model->datagrab(array('tabel'=>$this->tabel))->num_rows();
			$config['per_page']	= '10';
			$config['uri_segment'] = '4';
			
			$this->pagination->initialize($config);
			
			$data['links']	= $this->pagination->create_links();
			
			$query = $this->general_model->datagrab(array('tabel'=>$this->tabel,'offset'=>$offset,'limit'=>$config['per_page']));
			
			if ($query->num_rows() != 0) {
				$this->table->set_template(array('table_open'=>'<table class="table table-striped table-bordered table-condensed table-nonfluid">'));
				$this->table->set_heading(
					array('data'=>'No','style'=>'width:20px;text-align:center'),
					'Judul Laporan',
					'Deskripsi',
					array('data'=>'Aksi','style'=>'text-align:center')
				);
			
				$no = $offset + 1;
				foreach($query->result() as $row){
					$link = anchor('referensi/laporan_keuangan/konfig/'.$row->id_laporan,'<i class="fa fa-file-text"></i>','class="link-normal"').' &nbsp; ';
					$link.= anchor('#','<i class="fa fa-pencil"></i>','class="btn-edit" act="'.site_url('referensi/laporan_keuangan/form_data/'.$row->id_laporan).'"');
					if ($row->tipe == 2) $link .=' '.anchor('#','<i class="fa fa-trash"></i>','class="btn-delete" act="'.site_url('referensi/laporan_keuangan/delete_data/'.$row->id_laporan).'" msg="Apakah Anda ingin menghapus data <b>'.$row->nama_laporan.'</b>?"');
					
					$this->table->add_row(
						array('data'=>$no,'class'=>'text-center'),
						$row->nama_laporan,
						$row->deskripsi,
						array('data'=>$link,'class'=>'text-center')
					);
					$no++;
				}
				$tabel = $this->table->generate();
			}else{
				$tabel = '<div class="alert alert-info">Belum ada data laporan_keuangan</div>';
			}
			
			$data['tombol'] = anchor('#','<i class="icon-white icon-plus"></i> Tambah Laporan Keuangan','class="btn-edit btn btn-success" act="'.site_url('referensi/laporan_keuangan/form_data/').'"');
			$data['total'] = $config['total_rows'];
			$data['tabel']	= $tabel;
			$data['title'] 		= 'Referensi Laporan Keuangan';
			$data['content'] 	= "umum/standard_view";
			$this->load->view('home', $data);
		}
		
		function form_data($id = null) {
			$data['title']	= empty($id) ? 'Tambah Laporan keuangan' : 'Ubah Laporan Keuangan';
			
			$data['form_link'] = 'referensi/laporan_keuangan/save_data';
			$def = !empty($id) ? $this->general_model->datagrab(array('tabel'=>$this->tabel,'where'=>array('id_laporan'=>$id)))->row(): null;
			
			$data['form_data'] =
				form_hidden('id_laporan',@$def->id_laporan).
				'<p>'.form_label('Judul Laporan').form_input('nama_laporan',@$def->nama_laporan,'class="required"').'</p>'.
				'<p>'.form_label('Deskripsi').form_textarea('deskripsi',@$def->deskripsi,'style="width: 60%; height: 60px"').'</p>';
				if (empty($id)) $data['form_data'].= form_hidden('tipe',2);
			$this->load->view('umum/form_view',$data);
		}
		
		function save_data(){

			$id = $this->input->post('id_laporan');
			
			$simpan = array(
				'nama_laporan' => $this->input->post('nama_laporan'),
				'deskripsi' => $this->input->post('deskripsi')
			);
			if (empty($id)) $simpan['tipe'] = $this->input->post('tipe');
		
			$save = $this->general_model->save_data($this->tabel,$simpan,'id_laporan',$id);
			
			$this->session->set_flashdata('ok','Laporan Keuangan berhasil disimpan');
			
			redirect('referensi/laporan_keuangan');
		}
		
		function delete_data($id){

			$delete = $this->general_model->delete_data($this->tabel,'id_laporan',$id);
			
			if($delete) $this->session->set_flashdata('ok','Data berhasil dihapus');
			else $this->session->set_flashdata('ok','Data gagal dihapus');
			
			redirect('referensi/laporan_keuangan');
		}
		
		function konfig($id) {
		
			$data['breadcrumb'] = array('' => 'Referensi', 'referensi/laporan_keuangan' => 'Laporan Keuangan','referensi/laporan_keuangan/konfig/'.$id => 'Konfigurasi');
			
			
			$from = array(
				'sap_laporan_rekening s' => '',
				'sap_laporan l' => 'l.id_laporan = s.id_laporan',
				'ref_rekening r' => 'r.id_rek = s.id_rek');				
			$data['rek_kiri'] = $this->general_model->datagrab(array('tabel' => $from,'where' => array('r.jenis' => 5,'l.id_laporan' => $id),'order' => 's.urut'));
			$data['rek_kanan'] = $this->general_model->datagrab(array('tabel' => $from,'where' => array('r.jenis' => 6,'l.id_laporan' => $id),'order' => 's.urut'));
			
			
			$data['content'] = 'simsap_akrual/konfigurasi_laporan_view';
			$this->load->view('home',$data);
		
		}
	}
?>