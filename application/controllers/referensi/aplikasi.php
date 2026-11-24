<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Aplikasi extends CI_Controller {
	
		function __construct() {

			parent::__construct();
			login_check($this->session->userdata('login_state'));
			
		}

		public function index() {
			$this->list_aplikasi();
		}
		
		function list_aplikasi($offset = null) {

			$app = get_stationer(array('app_active'));
		
			$data['breadcrumb'] = array('' => 'Referensi', 'referensi/aplikasi' => 'Aplikasi');
			
			$offset = !empty($offset) ? $offset : null;
			
			$where = array('id_aplikasi IN ('.implode(',',$app).')' => null,'id_par_aplikasi' => null);
			
			$config['base_url']	= site_url('referensi/aplikasi/list_aplikasi');
			$config['total_rows'] = $this->general_model->datagrab(array('tabel'=>'ref_aplikasi','where' => $where))->num_rows();
			$config['per_page']	= '10';
			$config['uri_segment'] = '4';
			
			$this->pagination->initialize($config);
			
			$data['links']	= $this->pagination->create_links();
			
			$query = $this->general_model->datagrab(array('tabel'=>'ref_aplikasi','offset'=>$offset,'limit'=>$config['per_page'],'order' => 'kode_aplikasi','where' => $where));
			
			if ($query->num_rows() != 0) {
				$this->table->set_template(array('table_open'=>'<table class="table table-striped table-bordered table-condensed table-nonfluid">'));
				$this->table->set_heading(array('data'=>'No','style'=>'width:20px;text-align:center'),array('data' => 'Nama/Kode Aplikasi','colspan' => 2),'Deskripsi','',array('data' => 'Aksi','colspan' => 2));
			
				$no = $offset + 1;
				foreach($query->result() as $row){
				
					$ext = $this->general_model->datagrab(array('tabel' => 'ref_aplikasi','where' => array('aktif' => 1,'id_par_aplikasi' => $row->id_aplikasi)));
				
					$rows = array(
						array('data'=>$no,'class'=>'text-center'),
						array('data' => $row->nama_aplikasi.' ('.$row->kode_aplikasi.')','colspan' => 2),
						$row->deskripsi,
						array('data' => ' &nbsp; ','style' => 'background: '.$row->warna));
					$rows[] =  anchor('#','<i class="fa fa-pencil"></i>','class="btn-edit" act="'.site_url('referensi/aplikasi/form_data/'.in_de(array('id' => $row->id_aplikasi,'status' => 'user'))).'"');
					$this->table->add_row($rows);
					
					foreach($ext->result() as $e) {
						
						$rowsd = array('','',$e->nama_aplikasi.' ('.$e->kode_aplikasi.')',$e->deskripsi,
						array('data' => ' &nbsp; ','style' => 'background: '.$e->warna));
						$rowsd[]  = anchor('#','<i class="fa fa-pencil"></i>','class="btn-edit" act="'.site_url('referensi/aplikasi/form_data/'.in_de(array('id' => $e->id_aplikasi,'status' => 'user'))).'"');
						$this->table->add_row($rowsd);
					}
					$no++;
				}
				$tabel = $this->table->generate();
			}else{
				$tabel = '<div class="alert alert-info">Belum ada data Aplikasi</div>';
			}
			
			$data['total'] = $config['total_rows'];
			$data['tabel'] = $tabel;
			$data['title'] 		= 'Referensi Aplikasi';
			$data['content'] 	= "umum/standard_view";
			$this->load->view('home', $data);
		}
		
		function form_data($par) {
			
			$o = un_de($par);
			$data['multi'] = 1;
			
			$data['title']	= empty($o['id']) ? 'Tambah Aplikasi' : 'Ubah Aplikasi';
			
			$data['form_link'] = 'referensi/aplikasi/save_data';
			$def = !empty($o['id']) ? $this->general_model->datagrab(array('tabel'=>'ref_aplikasi','where'=>array('id_aplikasi'=>$o['id'])))->row(): null;
			
			$warna = !empty($o['id']) ? $def->warna : '#ccc';
			$logos = !empty($o['id']) ? $def->folder : 'logo';
			$data['form_data'] =
				form_hidden('status',$o['status']).
				form_hidden('id_aplikasi',@$def->id_aplikasi).
				'<p>'.form_label('Kode Aplikasi').form_input('kode_aplikasi',@$def->kode_aplikasi,'class="form-control" required').'</p>'.
				'<p>'.form_label('Nama Aplikasi').form_input('nama_aplikasi',@$def->nama_aplikasi,'class="form-control" required').'</p>'.
				'<p>'.form_label('Deskripsi').form_textarea('deskripsi',@$def->deskripsi,'class="form-control" style="height: 75px" rows="3"').'</p>'.
				'<p>'.form_label('Warna').
				'<div class="input-group colorize" >
				    <input name="warna" type="text" class="form-control" value="'.@$def->warna.'" />
				    <span class="input-group-addon"><i></i></span>
				</div></p><p>'.
				form_label('Icon').'
				<div style="max-width: 135px">
				<div class="app-icon" style="background: '.$warna.'; padding: 10px; width: 140px; height: 140px; margin-bottom: 10px;">
					<img src="'.base_url().'assets/logo/'.$logos.'.png" style="max-width: 110px;" /> 
				</div>
			</div>'.form_upload('icon_image').'</p>';

				
				
			if ($o['status'] == "root") {
				
				$data['form_data'].= 
					'<p>'.form_label('Folder Sistem').form_input('folder',@$def->folder,'class="form-control" required').'</p>'.
					'<p class="clear"></p>';
			}
			
			$this->load->view('umum/aplikasi_form_view',$data);
		}
		
		function save_data(){
		
			$status = $this->input->post('status');
			$id = $this->input->post('id_aplikasi');
			$kode_aplikasi = $this->input->post('kode_aplikasi');
			$folder = $this->input->post('folder');
			
			$check = $this->general_model->datagrab(array('tabel' => 'ref_aplikasi','where' => array('kode_aplikasi' => $kode_aplikasi)));

			if (empty($id) and $check->num_rows > 0) {
				
				$this->session->set_flashdata('fail','Referensi Kode aplikasi telah tersedia!');

			} else {
			
			$simpan = array(
				'kode_aplikasi' => $kode_aplikasi,
				'nama_aplikasi' => $this->input->post('nama_aplikasi'),
				'deskripsi' => $this->input->post('deskripsi'),
				'warna' => $this->input->post('warna')
			);
			
			if ($status == 'root') $simpan['folder'] = $folder;
			if (empty($id)) $simpan['aktif'] = 1;
			
			if (!empty($_FILES['icon_image']['tmp_name'])) {
			
				$path_app_logo = FCPATH.'assets/logo/'.$folder.'.png';
				if (file_exists($path_app_logo)) unlink($path_app_logo);
			
				$this->load->library('upload');
				$this->upload->initialize(array(
					'file_name' => $folder.'.png',
					'upload_path' => './assets/logo/',
					'allowed_types' => '*'));
				if (! $this->upload->do_upload('icon_image')) {
					$error = $this->upload->display_errors();
				} else {
					$data_up = $this->upload->data();
				}
			}
			
			$this->general_model->save_data('ref_aplikasi',$simpan,'id_aplikasi',$id);
			if (!empty($error)) $this->session->set_flashdata('fail',$error);
			else $this->session->set_flashdata('ok','Data berhasil disimpan');
			
			}

			if ($status == 'root') redirect('inti/pengaturan/aplikasi');
			else redirect('referensi/aplikasi');
		}
		
	}
?>