<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#[AllowDynamicProperties]
class Operator extends CI_Controller {

	/* 
	 * Keterangan Variabel Global
	 * $indie 
	 *		TRUE = Tambah Operator Independen di controller operator
	 *		FALSE = Non-Akfif Tambah Operator
	 * $app
	 *		1 = Aplikasi Tunggal
	 *		0 = Seluruh Aplikasi
	 * $dir	
	 		Pengguna Aplikasi / Folder Aplikasi
	 */

	var $indie = TRUE;
	var $unit = TRUE;
	var $app = 1;
	var $dir = 'filing';

	function __construct() {
	
		parent::__construct();
		login_check($this->session->userdata('login_state'));
		$id_peg = $this->session->userdata('id_pegawai');
		if ($this->session->userdata('login_state') !== 'root' && !$this->general_model->check_role($id_peg, 'OPRSA')) {
			$this->session->set_flashdata('fail', 'Akses ditolak. Menu Manajemen Operator hanya dapat diakses oleh Super Admin.');
			redirect('filing/home');
		}
		
		$app_std = $this->general_model->datagrab(array(
			'tabel' => 'ref_aplikasi','where' => array('folder' => $this->uri->segment(1))
		))->row();
		$this->app_aktif = $app_std;
		$this->in_app = !empty($app_std->nama_aplikasi) ? $app_std->nama_aplikasi : 'Root';

	}
	
	public function index(){
		
		$this->list_data();
	
	}
	
	function list_data($search = null, $offset = null){
		$id_peg = $this->session->userdata('id_pegawai');

		$role_admin = $this->general_model->check_role($id_peg,'OPRSA');
		
		$data['breadcrumb'] = array('' => $this->in_app, $this->dir.'/operator' => 'Operator');
		
		$offset = !empty($offset) ? $offset : 0;
		
		$s = $this->input->post('text_search');
		if (!empty($search)) {
			$o = un_de($search);
			$vse = array('text_search' => $o['text_search']);
		} else if (!empty($s)) {
			$vse = array('text_search' => $s);
		} else {
			$vse = array('text_search' => null);
		}
		
		$se = !empty($vse) ? $se = array ('nama' => $vse['text_search'],'username' => $vse['text_search']) : null;
		
		$config['base_url']	= site_url($this->dir.'/operator/list_data/'.in_de($vse));
		$config['total_rows'] = $this->general_model->datagrab(array('tabel' => 'peg_pegawai','search' => $se))->num_rows();
		$config['per_page']	= '10';
		$config['uri_segment'] = '5';
		
		$this->pagination->initialize($config);
		
		$data['total']			= $config['total_rows'];
		$data['links']			= $this->pagination->create_links();
		
		if ($this->indie and $this->unit) {	
			$from = array(
				'peg_pegawai p' => '',
				'peg_jabatan j' => array('j.id_pegawai = p.id_pegawai AND j.status = 1','left'),
				'ref_bidang b' => array('b.id_bidang = j.id_bidang','left'),
				'ref_unit u' => array('u.id_unit = b.id_unit','left')
			);
			$sel = 'p.nama,p.nip,p.username,p.id_pegawai,p.status,b.nama_bidang bidang, b.id_bidang,u.id_unit, u.unit';
		} else {
			$from = 'peg_pegawai';
			$sel = 'nama,nip,username,id_pegawai,status';
		}
		
		$dtoperator	= $this->general_model->datagrab(array(
			'tabel' => $from,
			'limit' => $config['per_page'],'offset' => $offset,
			'search' => $se,
			'order' => 'nama',
		'select' => $sel));
		
		if($dtoperator->num_rows() > 0){
		
			$this->table->set_template(array('table_open'=>'<table class="table table-bordered table-striped table-hover">'));
			$heads = array('No','Nama','ID','Username');
			if ($this->indie and $this->unit) {
				$heads[] = 'Unit Kerja (Pusat/Cabang)';
				$heads[] = 'Bidang';
			}
			$heads[] = 'Kewenangan';
			$heads[] = 'Status';
			if ($role_admin)  $heads[] = array('data' => 'Aksi','colspan' => 3);
			$this->table->set_heading($heads);
		
			$no = 1 + $offset;
			foreach($dtoperator->result() as $row){
				
				$where = ($this->app == 1) ? 
					array('a.folder' => $this->dir) :
					array('a.id_aplikasi IN ('.$this->general_model->get_param('app_active').')' => null);
			
				$role = $this->general_model->datagrab(array(
					'tabel' => array(
						'pegawai_role r' => '',
						'ref_role rr' => 'rr.id_role = r.id_role',
						'ref_aplikasi a' => 'a.id_aplikasi = rr.id_aplikasi'
					),'where' => array_merge_recursive(
						array('id_pegawai' => $row->id_pegawai),
						$where)
				));
				
				$disp_role = array();
				foreach($role->result() as $r) {
					$disp_role[] = $r->nama_role.' ('.$r->nama_aplikasi.')';
				}
			
				$link1 = ($role_admin) ? '<a href="#" act="'.site_url($this->dir.'/operator/add_operator/'.(in_de($vse).'~'.$offset).'/'.$row->id_pegawai).'" class="btn btn-xs btn-warning btn-edit"><i class="fa fa-pencil"></i></a>' : null;
				$link2= ($role_admin) ? ' <a href="'.site_url($this->dir.'/operator/reset_operator/'.$row->id_pegawai).'" class="btn btn-xs btn-primary btn-reset" title="Reset Password" msg="Yakin untuk mereset password operator? <br>(Password standar adalah <b>qwerty</b>)"><i class="fa fa-repeat"></i></a>' : null;
				
				$rows = array(array('data'=>$no,'style'=>'text-align:center'),
					$row->nama,
					$row->nip,
					$row->username);
					
					
				if ($this->indie and $this->unit) {
					$rows[] = $row->unit;
					$rows[] = $row->bidang;
					
				}
				$rows[] = !empty($disp_role) ? implode('<br/>',$disp_role) : '<span class="text-muted">Belum ditetapkan</span>';
				$st_user = isset($row->status) ? (int) $row->status : 1;
				if ($st_user === 0) {
					$rows[] = '<span class="label label-warning">Menunggu ACC</span>';
				} elseif ($st_user === 2) {
					$rows[] = '<span class="label label-danger">Ditolak</span>';
				} else {
					$rows[] = '<span class="label label-success">Aktif</span>';
				}
				if ($role_admin) $rows[] = $link1;
				if ($role_admin) $rows[] = $link2;
				if ($this->indie) {
					if ($role_admin) $rows[] = anchor($this->dir.'/operator/delete_data/'.$row->id_pegawai.'/'.(in_de($vse).'~'.$offset),'
						<i class="fa fa-trash"></i>','
						class="btn-delete btn btn-xs btn-danger"
						msg="Apakah Anda ingin menghapus data ini?"');
				}
				
				$this->table->add_row($rows);
				
				$no++;
			}
			$tabel = '<div class="table-responsive">'.$this->table->generate().'</div>';
		}else{
			$tabel = '<div class="alert">Tidak ada data operator</div>';
		}
		
		$data['script'] = "
		$(document).ready(function(){
			$('.go').click( function() {
				$('#form_search').submit();
				return false;
			});
			
			$('.btn-reset').click(function() {
			   $('.form-delete-msg').html($('.btn-reset').attr('msg'));
			   $('.form-title').html('<i class=\"fa fa-check-square-o\"></i> Reset Password');
			   $('.form-delete-url').attr('href',$(this).attr('href')).children().html('<i class=\"fa fa-check-square-o\"></i> Reset Password');
			   $('#modal-delete').modal('show');
			   return false;
		   });
		});";

		$data['extra_tombol'] = form_open($this->dir.'/operator','id="form_search"').
					'<div class="input-group">
				  <input name="text_search" type="text" placeholder="Pencarian ..." class="form-control pull-right" value="'.@$vse['text_search'].'">
				  <div class="input-group-btn">
					<button class="btn btn-default"><i class="fa fa-search"></i></button>
				  </div>
				</div>'.
		form_close();
		
		if ($this->indie) {
			$pending_cnt = (int) $this->db->query("SELECT COUNT(*) AS jml FROM peg_pegawai WHERE status = 0")->row()->jml;
			$btn_acc = ($role_admin) ? ' '.anchor($this->dir.'/persetujuan_akun'.($pending_cnt > 0 ? '?status=pending' : ''),
				'<i class="fa fa-user-plus fa-btn"></i> Persetujuan Akun'.($pending_cnt > 0 ? ' <span class="badge bg-yellow">'.$pending_cnt.'</span>' : ''),
				'class="btn btn-warning" title="Verifikasi & ACC Pendaftaran Akun Baru"') : '';
			$data['tombol'] = ($role_admin) ? anchor('','<i class="fa fa-plus fa-btn"></i> Tambah Operator','
				class="btn btn-success btn-edit" 
				act="'.site_url($this->dir.'/operator/add_operator').'"
				title="Tambah Operator"').$btn_acc : null;
		}
		
		$data['tabel']	= $tabel;
		$data['title'] 	= 'Pengaturan Operator';
		$data['content'] = "umum/standard_view";
		$this->load->view('home', $data);
	}
	
	function add_operator($offs = null,$id = null) {
	
		$data['title']	= 'Ubah Kewenangan Operator';
		if ($this->indie and $this->unit) {
			
			$from = array('peg_pegawai p' => '','peg_jabatan j' => array('j.id_pegawai = p.id_pegawai AND j.status = 1','left'));
			$sel = 'p.*,j.id_peg_jabatan,j.id_unit,j.id_bidang';
		} else {
			
			$from = 'peg_pegawai p';
			$sel = 'p.*';
			
		}
		$data['row']	= !empty($id) ? $this->general_model->datagrab(array(
			'tabel' => $from,'where' => array('p.id_pegawai' => $id),'select' => $sel))->row() : null;
		
		$data['indie'] = $this->indie;
		$data['unit'] = $this->unit;
		
		if ($this->unit) {
			$data['link_unit'] = site_url($this->dir.'/operator/cb_bidang');
			$data['cb_unit'] = $this->general_model->combo_box(array(
			'tabel' => 'ref_unit','key' => 'id_unit','val' => array('unit'),'order' => 'urut'));
		
			if (!empty($data['row'])) {
				
				$data['cb_bidang'] = $this->general_model->combo_box(array(
					'tabel' => 'ref_bidang',
					'key' => 'id_bidang',
					'val' => array('nama_bidang'),
					'where' => array('id_unit' => $data['row']->id_unit),
					'pilih' => '--Pilih Bidang--'));
							
			}	
		}
		
		$data['offs'] = $offs;
		$where = ($this->app == 1) ? 
			array('a.folder' => $this->dir) :
			array('a.id_aplikasi IN ('.$this->general_model->get_param('app_active').')' => null);
		
		$data['role']	= $this->general_model->datagrab(array(
			'tabel' => array(
				'ref_role r' => '',
				'ref_aplikasi a' => 'a.id_aplikasi = r.id_aplikasi'),
			'where' => $where));
		$roles = $this->general_model->datagrab(array('tabel' => array('peg_pegawai p' => '','pegawai_role rr' => 'rr.id_pegawai = p.id_pegawai'),'where' => array('p.id_pegawai' => $id)));
		$r = array();
		foreach($roles->result() as $ro) { $r[] = $ro->id_role; }
		
		$data['link_save'] = $this->dir.'/operator/save_operator';
		$data['link_foto'] = $this->dir.'/operator/save_foto';
		$data['pegawai_role'] = $r;
		$this->load->view('filing/operator_form',$data);
		
	}
	
	function save_operator(){

		$offs = $this->input->post('offs');
		$id_pegawai = $this->input->post('id_pegawai');
		$role = $this->input->post('role');
		$usr = preg_replace('/\s+/', ' ', trim((string) $this->input->post('username')));
		
		$where = array('username' => $usr);
		if (!empty($id_pegawai)) $where['id_pegawai !='] = $id_pegawai;
		
		$cek = $this->general_model->datagrab(array('tabel' => 'peg_pegawai','where' => $where));

		$nip_in = trim((string) $this->input->post('nip'));
		$where_nip = array('nip' => $nip_in);
		if (!empty($id_pegawai)) $where_nip['id_pegawai !='] = $id_pegawai;
		$cek_nip = ($this->indie && $nip_in !== '') ? $this->general_model->datagrab(array('tabel' => 'peg_pegawai','where' => $where_nip)) : null;

		if ($cek->num_rows() > 0) {
		
		$this->session->set_flashdata('fail','Username sudah tersedia!');
		
		} else if (!empty($cek_nip) && $cek_nip->num_rows() > 0) {

		$this->session->set_flashdata('fail','NIP/ID Operator <b>'.html_escape($nip_in).'</b> sudah digunakan akun lain!');

		} else {
			
			if (strtolower($usr) != 'nimda') {
			if ($this->indie) {
				
				$simpan = array(
					'username' => $usr,
					'nama' => $this->input->post('nama'),
					'nip' => $nip_in);
	
				if (empty($id_pegawai)) {
					$simpan['password'] = md5($usr);
					$simpan['id_agama'] = 0;
					$simpan['id_jeniskelamin'] = 0;
					$simpan['id_gol_darah'] = 0;
					$simpan['id_tempat_lahir'] = 0;
					$simpan['id_kelurahan'] = 0;
					$simpan['email'] = '';
					$simpan['status'] = 1;
				}
				
			} else {
				$simpan = array('username' => $usr);
			}

			$idp = $this->general_model->save_data('peg_pegawai',$simpan,'id_pegawai',$id_pegawai);
			
			$id_peg =  (!empty($id_pegawai))?$id_pegawai:$idp;
			
			if ($this->indie and $this->unit) {
					
					$id_jab = $this->input->post('id_peg_jabatan');
					$simpan_jab = array(
							'id_pegawai' => $id_peg,
							'id_unit' => $this->input->post('id_unit'),
							'id_bidang' => $this->input->post('id_bidang'),
							'status' => 1
						);
					if (empty($id_jab)) {
						$simpan_jab['id_jabatan'] = 0;
						$simpan_jab['id_status_pegawai'] = 0;
						$simpan_jab['no_sk'] = '';
						$simpan_jab['tmt_jabatan'] = date('Y-m-d');
						$simpan_jab['selesai_jabatan'] = date('Y-m-d');
					}
					$this->general_model->save_data('peg_jabatan',$simpan_jab,'id_peg_jabatan',
						$id_jab
					);
					
			}
			// if (count($role) > 0) {
			
			// 	$this->general_model->delete_data('pegawai_role','id_pegawai',$id_peg);
				
			// 	foreach($role as $r) {
			// 		$this->general_model->save_data('pegawai_role',array('id_role' => $r,'id_pegawai' => $id_peg));
			// 	}
				
			// 	if (!empty($id_pegawai)) $psn = 'Ubah operator berhasil dilakukan ... ';
			// 	else $psn = 'Operator berhasil ditambahkan ... '.(($this->indie)?'<br>Username : <strong>'.$usr.'</strong><br/>Password : <strong>'.$usr.'</strong>':null);
			// 	$this->session->set_flashdata('ok',$psn);
			// } else {
			
			// $this->session->set_flashdata('fail','Salah satu kewenangan harus diisi!');
					
			// }
			if (!empty($role)) {

				$get_role_inapp = $this->general_model->datagrab(array(
					'tabel'=>'ref_role',
					'where'=>array('id_aplikasi'=>$this->app_aktif->id_aplikasi)
					));
				if ($get_role_inapp->num_rows() > 0) {
					 $arr_role = array();
					 foreach ($get_role_inapp->result() as $r_role) {
					 	$arr_role[] = $r_role->id_role;
					 }
				}
				// $this->general_model->delete_data('pegawai_role','id_pegawai',$id_peg);
				if (!empty($arr_role)) {
					
					$this->general_model->delete_data(array(
						'tabel'=>'pegawai_role',
					'where'=>array('id_role IN ('.implode(',', $arr_role).')'=>null, 'id_pegawai'=>$id_peg)
					));
				}

				// cek($this->db->last_query()); die();
				
				$this->general_model->save_data('pegawai_role',array('id_role' => $role,'id_pegawai' => $id_peg));
				$r_info = $this->db->select('nama_role')->get_where('ref_role', array('id_role' => $role))->row();
				$r_name = !empty($r_info) ? $r_info->nama_role : 'Role #'.$role;
				audit_log('Pengaturan', !empty($id_pegawai) ? 'UBAH' : 'TAMBAH', 'Operator: '.$usr, 'Nama: '.(string) $this->input->post('nama').' | Kewenangan: '.$r_name);

				if (!empty($id_pegawai)) $psn = 'Ubah operator berhasil dilakukan ... ';
				else $psn = 'Operator berhasil ditambahkan ... '.(($this->indie)?'<br>Username : <strong>'.$usr.'</strong><br/>Password : <strong>'.$usr.'</strong>':null);
				$this->session->set_flashdata('ok',$psn);

			}else{
			$this->session->set_flashdata('fail','Kewenangan harus diisi!');
			}
			
			} else {
				
				$this->session->set_flashdata('fail','Tidak diijinkan menggunakan <i>Username</i> &nbsp; tersebut ...');
				
			}
		
		}
		redirect($this->dir.'/operator/list_data/'.str_replace('~','/',$offs));
		
	}
	
	function reset_operator($id=null){
		$op = $this->db->select('username, nama')->get_where('peg_pegawai', array('id_pegawai' => $id))->row();
		$this->general_model->save_data('peg_pegawai',array('password' => md5('qwerty')),'id_pegawai',$id);
		$lbl = !empty($op) ? $op->username.' ('.$op->nama.')' : 'ID #'.$id;
		audit_log('Pengaturan', 'RESET_PW', 'Operator: '.$lbl, 'Reset password operator menjadi default');
		$this->session->set_flashdata('ok', 'Password operator berhasil di reset');
		redirect($this->dir.'/operator');
	}
	
	function delete_data($id,$offs = null) {
		$op = $this->db->select('username, nama')->get_where('peg_pegawai', array('id_pegawai' => $id))->row();
		if ($this->indie and $this->unit) $this->general_model->delete_data('peg_jabatan','id_pegawai',$id);
		
		$this->general_model->delete_data('peg_pegawai','id_pegawai',$id);
		$lbl = !empty($op) ? $op->username.' ('.$op->nama.')' : 'ID #'.$id;
		audit_log('Pengaturan', 'HAPUS', 'Operator: '.$lbl, 'Menghapus akun operator ID #'.$id);
		$this->session->set_flashdata('ok', 'Operator berhasil dihapus ...');
		
		redirect($this->dir.'/operator/list_data/'.str_replace('~','/',$offs));
	}
	
	function cb_bidang($id) {
		$data_comb = $this->general_model->combo_box(array(
			'tabel'=>array('ref_bidang'=>''),
			'key'=>'id_bidang',
			'val'=>array('nama_bidang'),
			'order' => 'urut',
			'where'=>array('id_unit'=>$id),
			'pilih' => '--Pilih Bidang-- '));
		$combo = '';
		foreach($data_comb as $k=>$v){
			$combo .= '<option value="'.$k.'">'.$v.'</option>';
		}
		echo $combo;
	}
	
	function save_foto() {
		$id = $this->input->post('id_pegawai');
		$offs = $this->input->post('offs');
		$pasfoto = $_FILES['foto']['tmp_name'];
		if (!empty($pasfoto)) {	
			
			$path = './uploads/kepegawaian/pasfoto';
			if (!is_dir($path)) mkdir($path,0777,TRUE);
		
			if (!empty($id_pegawai)) {
			
				$prev = $this->input->post('foto_prev');
				$path_pasfoto = $path.'/'.$prev;
				if(file_exists($path_pasfoto)) unlink($path_pasfoto);
					
			} 
			
			$nama_file   = $id.'.jpg';
			
			if (!file_exists($path.'/'.$nama_file)) {
				
				$this->load->library('upload');
				
				$this->upload->initialize(array(
					'upload_path' => $path,
					'allowed_types' => 'jpg|jpeg|png|gif|pdf|word',
					'file_name' => $nama_file
				));
				$this->upload->do_upload('foto');

			}
			
			$this->general_model->save_data(
				'peg_pegawai',array('photo' => $nama_file),'id_pegawai',$id);
			
			$this->session->set_flashdata('ok','Foto berhasil disimpan ...');
		} else {
			$this->session->set_flashdata('fail','Foto belum dipilih ...');
		} 
		redirect($this->dir.'/operator/list_data/'.str_replace('~','/',$offs));
		
	}
	
}
?>