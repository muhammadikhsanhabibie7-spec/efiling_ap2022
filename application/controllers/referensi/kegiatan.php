<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class kegiatan extends CI_Controller {

		function __construct() {
		
			parent::__construct();
			login_check($this->session->userdata('login_state'));
			
		}

		public function index() {
			$this->list_kegiatan();
		}
		
		function list_kegiatan($offset = null) {
		
			$data['breadcrumb'] = array('' => 'Referensi', 'referensi/kegiatan' => 'kegiatan');
			
			$offset = !empty($offset) ? $offset : null;
			$from = array(
			'ref_program j' => '',
			'ref_kegiatan e' => 'e.id_program = j.id_program'
			);
		
			$config['base_url']		= site_url('referensi/kegiatan/list_kegiatan');
			$config['total_rows']	= $this->general_model->datagrab(array('tabel'=>$from))->num_rows();
			$config['per_page']		= '10';
			$config['uri_segment'] = '4';
			
			$this->pagination->initialize($config);
			
			$data['total']			= $config['total_rows'];
			$data['links']	= $this->pagination->create_links();
			
			$query = $this->general_model->datagrab(array('tabel'=>$from,'offset'=>$offset,'limit'=>$config['per_page'],'order' => 'e.id_kegiatan'));
			
			if ($query->num_rows() != 0) {
				$this->table->set_template(array('table_open'=>'<table class="table table-striped table-bordered table-condensed table-nonfluid">'));
				$this->table->set_heading(
					array('data'=>'No','style'=>'width:20px;text-align:center'),
					'Kode',
					'Nama kegiatan','Nama Program',
					array('data'=>'Aksi','style'=>'text-align:center')
				);
			
				$no = $offset + 1;
				foreach($query->result() as $row){
					$link  = 
						anchor('#','<i class="fa fa-pencil"></i>','class="btn-edit" act="'.site_url('referensi/kegiatan/form_data/'.$row->id_kegiatan).'"').' '.
						anchor('#','<i class="fa fa-trash"></i>','class="btn-delete" act="'.site_url('referensi/kegiatan/delete_data/'.$row->id_kegiatan).'" msg="Apakah Anda ingin menghapus data <b>'.$row->kode_kegiatan.'</b>?"');
					
					$this->table->add_row(
						array('data'=>$no,'class'=>'text-center'),
						$row->kode_kegiatan,
						$row->nama_kegiatan,						
						$row->nama_program,
						array('data'=>$link,'class'=>'text-center')
					);
					$no++;
				}
				$this->table->add_row(array('data'=>'Total : '.$config['total_rows'], 'class'=>'text-right', 'colspan' => '6'));
				$tabel = $this->table->generate();
			}else{
				$tabel = '<div class="alert alert-info">Belum ada data kegiatan</div>';
			}
			
			$data['tombol'] = anchor('#','<i class="icon-white icon-plus"></i> Tambah kegiatan','class="btn-edit btn btn-success" act="'.site_url('referensi/kegiatan/form_data/').'"');
			$data['tabel']	= $tabel;
			$data['title'] 		= 'Referensi kegiatan';
			$data['content'] 	= "umum/standard_view";
			$this->load->view('home', $data);
		}
		
		function form_data($id = null) {
			$data['title']	= empty($id) ? 'Tambah kegiatan' : 'Ubah kegiatan';
			$combo_program = $this->general_model->combo_box(array('tabel' => 'ref_program','key' => 'id_program','val' => array('nama_program')));
			$data['form_link'] = 'referensi/kegiatan/save_data';
			$def = !empty($id) ? $this->general_model->datagrab(array('tabel'=>'ref_kegiatan','where'=>array('id_kegiatan'=>$id)))->row(): null;
			
			$data['form_data'] =
				form_hidden('id_kegiatan',@$def->id_kegiatan).
				
				'<p>'.form_label('Nama Program').form_dropdown('id_program',$combo_program,@$def->id_program,'class="required combo-box"').'</p>'.
				'<p>'.form_label('Kode kegiatan').form_input('kode_kegiatan',@$def->kode_kegiatan,'class="required"').'</p>'.
				'<p>'.form_label('Nama kegiatan').form_textarea('nama_kegiatan',@$def->nama_kegiatan,'style="width: 60%; height: 60px"').'</p>';
			
			$this->load->view('umum/form_view',$data);
		}
		
		function save_data(){
			$id = $this->input->post('id_kegiatan');
			$kode_kegiatan = $this->input->post('kode_kegiatan');
			
			$check = $this->general_model->datagrab(array('tabel' => 'ref_kegiatan','where' => array('kode_kegiatan' => $kode_kegiatan)));

			if (empty($id) and $check->num_rows > 0) {
				
				$this->session->set_flashdata('fail','Referensi Kode kegiatan telah tersedia!');

			} else {
			
			$simpan = array(
			'kode_kegiatan' => $kode_kegiatan,
			'nama_kegiatan' => $this->input->post('nama_kegiatan'),
			
			'id_program' => $this->input->post('id_program')
			);
			
			$save = $this->general_model->save_data('ref_kegiatan',$simpan,'id_kegiatan',$id);
			
			if($save) $this->session->set_flashdata('ok','Data berhasil disimpan');
			else $this->session->set_flashdata('fail','Data gagal disimpan');
			}

			redirect('referensi/kegiatan');
		}
		
		function delete_data($id){

			$delete = $this->general_model->delete_data('ref_kegiatan','id_kegiatan',$id);
			
			if($delete) $this->session->set_flashdata('ok','Data berhasil dihapus');
			else $this->session->set_flashdata('ok','Data gagal dihapus');
			
			redirect('referensi/kegiatan');
		}
	}
?>