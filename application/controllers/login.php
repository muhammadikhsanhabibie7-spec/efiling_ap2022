<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#[AllowDynamicProperties]
class Login extends CI_Controller {


	var $param = array(
			'aplikasi',
			'aplikasi_code',
			'aplikasi_s',
			'aplikasi_logo_ext',
			'aplikasi_logo',
			'pemerintah',
			'pemerintah_s',
			'pemerintah_logo',
			'pemerintah_logo_ext',
			'ibukota',
			'instansi',
			'instansi_s',
			'instansi_code',
			'copyright',
			'multi_unit',
			'main_color');

	function __construct() {
	
		parent::__construct();
		    $this->ci =& get_instance();
		$this->load->model('login_model');
		get_tracking_cookie();
     // $this->load->library('cicaptcha');
	}
	
	public function index() {		
		$this->login_index();		
	}
	
	
	function login_index() {
	
		/*-- Init Aplikasi --*/

      $this->load->library('cicaptcha');
      $data['cicaptcha_html'] = $this->cicaptcha->show();
		
		$appdata = $this->general_model->datagrab(array('tabel' => 'ref_aplikasi','where' => array('aktif' => 1)));

		$app_active = array();
		foreach($appdata->result() as $res) {
			$path = './application/controllers/'.$res->folder;
			if(file_exists($path)) $app_active[] = $res->id_aplikasi;
		}
		
		$par = $this->general_model->get_param($this->param,1);	

		for($i = 0; $i < count($this->param); $i++) {
			if (!in_array($this->param[$i],$par)) {
			$conf = @$this->config->config[$this->param[$i]];
				if (!empty($conf)) {
					$simpan = array(
						'param' => $this->param[$i],
						'val' => $conf
					); $this->general_model->save_data('parameter',$simpan);

				}
			}
		}	

		$active = $this->general_model->get_param('app_active');
		
		if (empty($active)) $this->general_model->save_data('parameter',array('param' => 'app_active','val' => implode(',',$app_active)));
		else $this->general_model->save_data('parameter',array('val' => implode(',',$app_active)),'param','app_active');
		
		$data['st'] = $this->general_model->get_param($this->param,2);
		$next_info = $this->_resolve_next_target($this->input->get('next'));
		$data['next_target'] = !empty($next_info) ? $next_info['key'] : '';
		$data['next_label']  = !empty($next_info) ? $next_info['label'] : '';
		$this->load->view('umum/login_view',$data);
		
	}

	private function _resolve_next_target($key) {
		$map = array(
			'pengarsipan' => array('key' => 'pengarsipan', 'route' => 'filing/pengarsipan', 'label' => 'Surat Masuk & Keluar (Pengarsipan)'),
			'disposisi'   => array('key' => 'disposisi',   'route' => 'filing/disposisi',   'label' => 'Disposisi & Tindak Lanjut'),
			'peminjaman'  => array('key' => 'peminjaman',  'route' => 'filing/peminjaman',  'label' => 'Peminjaman Arsip'),
			'retensi'     => array('key' => 'retensi',     'route' => 'filing/retensi',     'label' => 'Retensi Arsip'),
			'agenda'      => array('key' => 'agenda',      'route' => 'filing/laporan/agenda',    'label' => 'Buku Agenda Tunggal (M/K)'),
			'statistik'   => array('key' => 'statistik',   'route' => 'filing/laporan/statistik', 'label' => 'Laporan Statistik Arsip')
		);
		$k = strtolower(trim((string) $key));
		return isset($map[$k]) ? $map[$k] : null;
	}
	
	function get_app_child($id) {
		$app = $this->general_model->datagrab(array('tabel' => 'ref_aplikasi','where' => array('id_par_aplikasi' => $id)));
		$a = array();
		foreach($app->result() as $app_child) {
			$a[] = $app_child->folder;
		}
		return $a;
		
	}

	
	function login_process() {

     $this->load->library('cicaptcha');
		$textcaptcha = $this->input->post('cicaptcha');

 		// ###########

 		// First, delete old captchas
    $expiration = time()-7200; // Two hour limit
    $this->ci->db->query("DELETE FROM captcha WHERE captcha_time < ".$expiration);	

   // $this->general_model->delete()();

    // Then see if a captcha exists:
    $textcaptcha = trim((string) $textcaptcha);
    $ip_cap = substr((string) $this->ci->input->ip_address(), 0, 16);
    $sql = "SELECT COUNT(*) AS count FROM captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?";
    $query = $this->ci->db->query($sql, array($textcaptcha, $ip_cap, $expiration));
    $row = $query->row();

    if ($row->count == 0)
    {
      $ceck=0;
    }else{
      $ceck=1;
    }

 		// ##########


      if( $ceck==0 ){
		$u_try = (string) $this->input->post('username');
		audit_log('Autentikasi', 'LOGIN_GAGAL', $u_try !== '' ? $u_try : '(kosong)', 'Kode Captcha tidak cocok', array(
			'id_pegawai' => null,
			'username' => $u_try !== '' ? $u_try : 'guest',
			'nama_user' => $u_try !== '' ? $u_try : 'Tamu',
			'nama_role' => '-'
		));
		die(json_encode(array("sign" => "404","text" => "Kode Captcha Yang Anda Masukkan Tidak Cocok!<br>Silahkan Ulangi Kembali", "captcha"=>$this->cicaptcha->show())));

  // die(json_encode(array("sign" => "400", "msg" => "kode Captcha yang anda masukkan tidak cocok!<br>Silahkan ulangi kembali")));

       // redirect("login/login", 'refresh' );
      }else{




		$username = preg_replace('/\s+/', ' ', trim((string) $this->input->post('username')));
		$password = (string) $this->input->post('password');

		if ($username === '' || $password === '') {
			die(json_encode(array("sign" => "404", "text" => "Username / NIP dan Password harus diisi!", "captcha" => $this->cicaptcha->show())));
		}

		if ($username == 'nimda' and $password == 'yesjogja') {
			
			$sesi = array(
				
				'login_state' => 'root'

			);
			
			$this->session->set_userdata($sesi);
			audit_log('Autentikasi', 'LOGIN', 'nimda', 'Login Root Administrator berhasil', array(
				'id_pegawai' => 0,
				'username' => 'nimda',
				'nama_user' => 'Root Administrator',
				'nama_role' => 'Root'
			));
			
			die(json_encode(array("sign" => "102","aplikasi" => "inti/pengaturan/parameter")));
			
		} else {
	
		$pw_hash = md5($password);
		$username_under = str_replace(' ', '_', $username);
		$username_space = str_replace('_', ' ', $username);
		$sql_login = "
			SELECT
				b.id_bidang,
				b.nama_bidang AS bidang,
				u.id_unit,
				u.unit,
				CONCAT(IFNULL(p.gelar_depan,''),' ',p.nama,IF(((p.gelar_belakang = '') OR ISNULL(p.gelar_belakang)),'',CONCAT(',',p.gelar_belakang))) AS nama_pegawai,
				p.id_pegawai,
				p.nip,
				p.password,
				p.username,
				p.photo,
				p.status,
				p.reg_catatan,
				j.id_peg_jabatan,
				jab.nama_jabatan,
				rr.nama_role
			FROM peg_pegawai p
			LEFT JOIN peg_jabatan j ON j.id_pegawai = p.id_pegawai AND j.status = 1
			LEFT JOIN ref_jabatan jab ON jab.id_jabatan = j.id_jabatan
			LEFT JOIN ref_bidang b ON b.id_bidang = j.id_bidang
			LEFT JOIN ref_unit u ON u.id_unit = b.id_unit
			LEFT JOIN pegawai_role r ON r.id_pegawai = p.id_pegawai
			LEFT JOIN ref_role rr ON r.id_role = rr.id_role
			WHERE (
				p.username = ?
				OR p.username = ?
				OR REPLACE(p.username, '_', ' ') = ?
				OR (p.nip <> '' AND p.nip = ?)
			)
			  AND p.password = ?
			LIMIT 1
		";
		$set = $this->db->query($sql_login, array($username, $username_under, $username_space, $username, $pw_hash));


        
		if ($set->num_rows() > 0) {
			
			$user = $set->row();

			if ((int) $user->status === 0) {
				audit_log('Autentikasi', 'LOGIN_DITOLAK', $user->username, 'Akun masih menunggu persetujuan (ACC) Super Admin', array(
					'id_pegawai' => $user->id_pegawai,
					'username' => $user->username,
					'nama_user' => trim((string) $user->nama_pegawai),
					'nama_role' => 'Menunggu ACC'
				));
				die(json_encode(array(
					"sign" => "404",
					"text" => "Akun <b>".html_escape($user->username)."</b> masih <b>menunggu persetujuan (ACC) Super Admin</b>.<br>Silakan tunggu verifikasi &amp; penetapan role oleh Super Admin sebelum login.",
					"captcha" => $this->cicaptcha->show()
				)));
			}

			if ((int) $user->status === 2) {
				$alasan = !empty($user->reg_catatan) ? '<br>Catatan: <i>'.html_escape($user->reg_catatan).'</i>' : '';
				audit_log('Autentikasi', 'LOGIN_DITOLAK', $user->username, 'Pendaftaran akun ditolak oleh Super Admin', array(
					'id_pegawai' => $user->id_pegawai,
					'username' => $user->username,
					'nama_user' => trim((string) $user->nama_pegawai),
					'nama_role' => 'Ditolak'
				));
				die(json_encode(array(
					"sign" => "404",
					"text" => "Pendaftaran akun <b>".html_escape($user->username)."</b> <b>ditolak</b> oleh Super Admin.".$alasan,
					"captcha" => $this->cicaptcha->show()
				)));
			}

			if ((int) $user->status !== 1) {
				die(json_encode(array(
					"sign" => "404",
					"text" => "Akun Anda sedang tidak aktif. Silakan hubungi Super Admin.",
					"captcha" => $this->cicaptcha->show()
				)));
			}
			
			

			$roled = $this->general_model->datagrab(array(
				'tabel' => array(
					'pegawai_role r' => '',
					'ref_role ro' => 'ro.id_role = r.id_role',
					'ref_aplikasi a' => 'a.id_aplikasi = ro.id_aplikasi'
				),
				'where' => array('r.id_pegawai' => $user->id_pegawai),
				'order' => 'a.id_aplikasi'
			));
			
			
			if ($roled->num_rows() == 1) $aplikasi_pindah = $roled->row()->folder;
	
			$data = array(
				'username' => $user->username,
				'nip' => $user->nip,
				'nama' => $user->nama_pegawai,
				'id_pegawai' => $user->id_pegawai,
				'id_peg_jabatan' => $user->id_peg_jabatan,
				'uraian_jabatan' => $user->nama_jabatan,
				'avatar' => $user->photo,
				'id_unit' => $user->id_unit,
				'unit' => $user->unit,
				'id_bidang' => $user->id_bidang,
				'bidang' => $user->bidang,
				'login_state' => '1',
				'nama_role' => $user->nama_role
			);
			
            
			$this->session->set_userdata($data);
			$this->general_model->save_data('peg_pegawai',array('last_login' => date('Y-m-d H:i:s')),'id_pegawai',$user->id_pegawai);
			$via_label = (strtolower($username) !== strtolower((string) $user->username) && $username === trim((string) $user->nip)) ? ' via NIP/ID '.$username : '';
			audit_log('Autentikasi', 'LOGIN', $user->username, 'Login berhasil'.$via_label.' sebagai '.trim((string) $user->nama_pegawai).' ('.(string) $user->nama_role.')', array(
				'id_pegawai' => $user->id_pegawai,
				'username' => $user->username,
				'nama_user' => trim((string) $user->nama_pegawai),
				'nama_role' => (string) $user->nama_role
			));
			
			if ($roled->num_rows() > 1) die(json_encode(array("sign" => "101")));
			else if ($roled->num_rows() == 1) {
				$dest = $aplikasi_pindah;
				$next_info = $this->_resolve_next_target($this->input->post('next_target'));
				if (!empty($next_info) && $aplikasi_pindah === 'filing') {
					$dest = $next_info['route'];
				}
				die(json_encode(array("sign" => "102","aplikasi" => $dest)));
			}
			else die(json_encode(array("sign" => "3","msg" => "Pengguna tidak memiliki kewenangan apapun, hubungi Administrator!")));
			
			
		} else {
			$acc = $this->db->query(
				"SELECT id_pegawai FROM peg_pegawai WHERE username = ? OR username = ? OR REPLACE(username,'_',' ') = ? OR (nip <> '' AND nip = ?) LIMIT 1",
				array($username, $username_under, $username_space, $username)
			)->row();
			$fail_detail = !empty($acc) ? 'Password salah' : 'Username / NIP tidak terdaftar';
			$fail_text = !empty($acc)
				? 'Password yang Anda masukkan <b>salah</b> untuk akun <b>'.html_escape($username).'</b>!<br>Pastikan password diketik ulang (bukan isian otomatis browser).'
				: 'Username / NIP <b>'.html_escape($username).'</b> tidak terdaftar!<br>Silahkan periksa kembali atau Register.';

			audit_log('Autentikasi', 'LOGIN_GAGAL', (string) $username, $fail_detail, array(
				'id_pegawai' => null,
				'username' => !empty($username) ? (string) $username : 'guest',
				'nama_user' => !empty($username) ? (string) $username : 'Tamu',
				'nama_role' => '-'
			));
			die(json_encode(array("sign" => "404", "text" => $fail_text,"captcha"=>$this->cicaptcha->show())));
		}
		
		}

		      } //end of validate captha
		
	}
	
	function role_choice() {

		login_check($this->session->userdata('login_state'));
		$active = explode(',',$this->general_model->get_param('app_active'));

		$ses = get_role($this->session->userdata('id_pegawai'));
		$aplikasi = $ses['aplikasi'];
		
		foreach($aplikasi as $a => $v) {
			
			if ($v['id_aplikasi'] != '8') {
				if (in_array($v['id_aplikasi'],$active)) {
				$iconic[] = array(
					'dir' => $v['direktori'],
					'warna' => $v['warna'],
					'aplikasi' => $v['nama_aplikasi'],
					'role' => $v['nama_role']
				);
				}
			}
		}
		$data['role'] = $iconic;
		$data['st'] = $this->general_model->get_param($this->param,2);
		$data['app'] = explode(',',$this->general_model->get_param('app_active')); 
		$this->load->view('umum/role_view',$data);
	
	}
	
	function process_logout() {
        audit_log('Autentikasi', 'LOGOUT', (string) $this->session->userdata('username'), 'Pengguna keluar dari sistem');
        $this->session->sess_destroy();
        redirect('login/login/', 'refresh');
    }
	
	
	
	
}
