<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Kesejahteraan extends CI_Controller {

		function __construct() {
		
			parent::__construct();
			login_check($this->session->userdata('login_state'));
			
		}

		public function index() {
			$this->list_kesejahteraan();
		}
		
		function list_kesejahteraan($offset = null) {
		
			$data['breadcrumb'] = array('' => 'Referensi', 'referensi/kesejahteraan' => 'Kesejahteraan');
			
			$offset = !empty($offset) ? $offset : null;
			
			$config['base_url']		= site_url('referensi/kesejahteraan/list_kesejahteraan');
			$config['total_rows']	= $this->general_model->datagrab(array('tabel'=>'ref_kesejahteraan'))->num_rows();
			$config['per_page']		= '10';
			$config['uri_segment'] = '4';
			
			$this->pagination->initialize($config);
			
			$data['links']	= $this->pagination->create_links();
			
			$query = $this->general_model->datagrab(array('tabel'=>'ref_kesejahteraan','offset'=>$offset,'limit'=>$config['per_page'],'order' => 'kode_kesejahteraan'));
			
			if ($query->num_rows() != 0) {
				$this->table->set_template(array('table_open'=>'<table class="table table-striped table-bordered table-condensed table-nonfluid">'));
				$this->table->set_heading(
					array('data'=>'No','style'=>'width:20px;text-align:center'),
					'Kode',
					'Kesejahteraan',
					array('data'=>'Aksi','style'=>'text-align:center')
				);
			
				$no = $offset + 1;
				foreach($query->result() as $row){
					$link  = 
						anchor('#','<i class="fa fa-pencil"></i>','class="btn-edit" act="'.site_url('referensi/kesejahteraan/form_data/'.$row->id_kesejahteraan).'"').' '.
						anchor('#','<i class="fa fa-trash"></i>','class="btn-delete" act="'.site_url('referensi/kesejahteraan/delete_data/'.$row->id_kesejahteraan).'" msg="Apakah Anda ingin menghapus data <b>'.$row->kode_kesejahteraan.'</b>?"');
					
					$this->table->add_row(
						array('data'=>$no,'class'=>'text-center'),
						$row->kode_kesejahteraan,
						$row->kesejahteraan,
						array('data'=>$link,'class'=>'text-center')
					);
					$no++;
				}
				$this->table->add_row(array('data'=>'Total : '.$config['total_rows'], 'class'=>'text-right', 'colspan' => '6'));
				$tabel = $this->table->generate();
			}else{
				$tabel = '<div class="alert alert-info">Belum ada data Kesejahteraan</div>';
			}
			
			$data['tombol'] = anchor('#','<i class="icon-white icon-plus"></i> Tambah Kesejahteraan','class="btn-edit btn btn-success" act="'.site_url('referensi/kesejahteraan/form_data/').'"');
			$data['tabel']	= $tabel;
			$data['title'] 		= 'Referensi Kesejahteraan';
			$data['content'] 	= "umum/standard_view";
			$this->load->view('home', $data);
		}
		
		function form_data($id = null) {
			$data['title']	= empty($id) ? 'Tambah Kesejahteraan' : 'Ubah Kesejahteraan';
			
			$data['form_link'] = 'referensi/kesejahteraan/save_data';
			$def = !empty($id) ? $this->general_model->datagrab(array('tabel'=>'ref_kesejahteraan','where'=>array('id_kesejahteraan'=>$id)))->row(): null;
			
			$data['form_data'] =
				form_hidden('id_kesejahteraan',@$def->id_kesejahteraan).
				'<p>'.form_label('Kode Kesejahteraan').form_input('kode_kesejahteraan',@$def->kode_kesejahteraan,'class="required"').'</p>'.
				'<p>'.form_label('Nama Kesejahteraan').form_input('kesejahteraan',@$def->kesejahteraan,'class="required"').'</p>';
			
			$this->load->view('umum/form_view',$data);
		}
		
		function save_data(){

			$id = $this->input->post('id_kesejahteraan');
			$kode_kesejahteraan = $this->input->post('kode_kesejahteraan');
			
			$check = $this->general_model->datagrab(array('tabel' => 'ref_kesejahteraan','where' => array('kode_kesejahteraan' => $kode_kesejahteraan)));

			if (empty($id) and $check->num_rows > 0) {
				
				$this->session->set_flashdata('fail','Referensi Kode kesejahteraan telah tersedia!');

			} else {
			
			$simpan = array('kode_kesejahteraan' => $kode_kesejahteraan,'kesejahteraan' => $this->input->post('kesejahteraan'));
			
			$save = $this->general_model->save_data('ref_kesejahteraan',$simpan,'id_kesejahteraan',$id);
			
			if($save) $this->session->set_flashdata('ok','Data berhasil disimpan');
			else $this->session->set_flashdata('fail','Data gagal disimpan');
			}

			redirect('referensi/kesejahteraan');
		}
		
		function delete_data($id){

			$delete = $this->general_model->delete_data('ref_kesejahteraan','id_kesejahteraan',$id);
			
			if($delete) $this->session->set_flashdata('ok','Data berhasil dihapus');
			else $this->session->set_flashdata('ok','Data gagal dihapus');
			
			redirect('referensi/kesejahteraan');
		}
	}
?>