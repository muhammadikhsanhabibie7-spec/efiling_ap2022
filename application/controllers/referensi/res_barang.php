<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Res_barang extends CI_Controller {

		function __construct() {
		
			parent::__construct();
			login_check($this->session->userdata('login_state'));
			
		}

		public function index() {
			$this->list_data();
		}
		
		function list_data($offset = null) {
		
			$data['breadcrumb'] = array('' => 'Referensi', 'referensi/res_barang' => 'Barang');
			
			$offset = !empty($offset) ? $offset : null;
			
			$config['base_url']		= site_url('referensi/res_barang/list_data');
			$config['total_rows']	= $this->general_model->datagrab(array('tabel'=>'ref_eselon'))->num_rows();
			$config['per_page']		= '10';
			$config['uri_segment'] = '4';
			
			$this->pagination->initialize($config);
			
			$data['links']	= $this->pagination->create_links();
			
			$query = $this->general_model->datagrab(array('tabel'=>'ref_eselon','offset'=>$offset,'limit'=>$config['per_page'],'order' => 'eselon'));
			
			if ($query->num_rows() != 0) {
				$this->table->set_template(array('table_open'=>'<table class="table table-striped table-bordered table-condensed table-nonfluid">'));
				$this->table->set_heading(
					array('data'=>'No','style'=>'width:20px;text-align:center'),
					'Eselon',
					array('data'=>'Aksi','style'=>'text-align:center')
				);
			
				$no = $offset + 1;
				foreach($query->result() as $row){
					$link  = 
						anchor('#','<i class="fa fa-pencil"></i>','class="btn-edit" act="'.site_url('referensi/eselon/form_data/'.$row->id_eselon).'"').' '.
						anchor('#','<i class="fa fa-trash"></i>','class="btn-delete" act="'.site_url('referensi/eselon/delete_data/'.$row->id_eselon).'" msg="Apakah Anda ingin menghapus data <b>'.$row->eselon.'</b>?"');
					
					$this->table->add_row(
						array('data'=>$no,'class'=>'text-center'),
						$row->eselon,
						array('data'=>$link,'class'=>'text-center')
					);
					$no++;
				}
				$this->table->add_row(array('data'=>'Total : '.$config['total_rows'], 'class'=>'text-right', 'colspan' => '6'));
				$tabel = $this->table->generate();
			}else{
				$tabel = '<div class="alert alert-info">Belum ada data Eselon</div>';
			}
			
			$data['tombol'] = anchor('#','<i class="icon-white icon-plus"></i> Tambah Eselon','class="btn-edit btn btn-success" act="'.site_url('referensi/eselon/form_data/').'"');
			$data['tabel']	= $tabel;
			$data['title'] 		= 'Referensi Eselon';
			$data['content'] 	= "umum/standard_view";
			$this->load->view('home', $data);
		}
		
		function form_data($id = null) {
			$data['title']	= empty($id) ? 'Tambah Eselon' : 'Ubah eselon';
			
			$data['form_link'] = 'referensi/eselon/save_data';
			$def = !empty($id) ? $this->general_model->datagrab(array('tabel'=>'ref_eselon','where'=>array('id_eselon'=>$id)))->row(): null;
			
			$data['form_data'] =
				form_hidden('id_eselon',@$def->id_eselon).
				'<p>'.form_label('Eselon').form_input('eselon',@$def->eselon,'class="required"').'</p>';
			
			$this->load->view('umum/form_view',$data);
		}
		
		function save_data(){

			$id = $this->input->post('id_eselon');
			$eselon = $this->input->post('eselon');
			
			$check = $this->general_model->datagrab(array('tabel' => 'ref_eselon','where' => array('eselon' => $eselon)));

			if (empty($id) and $check->num_rows > 0) {
				
				$this->session->set_flashdata('fail','Referensi Eselon telah tersedia!');

			} else {
			
			$simpan = array('eselon' => $eselon);
			
			$save = $this->general_model->save_data('ref_eselon',$simpan,'id_eselon',$id);
			
			if($save) $this->session->set_flashdata('ok','Data berhasil disimpan');
			else $this->session->set_flashdata('fail','Data gagal disimpan');
			}

			redirect('referensi/eselon');
		}

		
		function delete_data($id){

			$delete = $this->general_model->delete_data('ref_eselon','id_eselon',$id);
			
			if($delete) $this->session->set_flashdata('ok','Data berhasil dihapus');
			else $this->session->set_flashdata('ok','Data gagal dihapus');
			
			redirect('referensi/eselon');
		}
	}
?>