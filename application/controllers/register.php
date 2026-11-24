<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#[AllowDynamicProperties]
class Register extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->ci =& get_instance();
		get_tracking_cookie();
	}

	public function index() {
		if (!empty($this->session->userdata('login_state'))) {
			redirect('home');
		}

		$this->load->library('cicaptcha');
		$data['cicaptcha_html'] = $this->cicaptcha->show();
		$data['st'] = get_stationer();

		$data['units'] = $this->db->order_by('id_unit', 'ASC')->get('ref_unit')->result();
		$data['bidangs'] = $this->db->order_by('id_unit', 'ASC')->order_by('urut', 'ASC')->get('ref_bidang')->result();
		$data['roles'] = $this->db->query(
			"SELECT r.id_role, r.nama_role
			 FROM ref_role r
			 LEFT JOIN ref_aplikasi a ON a.id_aplikasi = r.id_aplikasi
			 WHERE a.folder = ? AND LOWER(r.nama_role) <> ?
			 ORDER BY FIELD(LOWER(r.nama_role), 'operator', 'admin', 'pimpinan'), r.id_role ASC",
			array('filing', 'super admin')
		)->result();

		$this->load->view('umum/register_view', $data);
	}

	public function process() {
		$this->load->library('cicaptcha');

		$nama             = trim((string) $this->input->post('nama'));
		$nip              = trim((string) $this->input->post('nip'));
		$email            = trim((string) $this->input->post('email'));
		$username_raw     = trim((string) $this->input->post('username'));
		$username         = preg_replace('/\s+/', ' ', $username_raw);
		$password         = (string) $this->input->post('password');
		$password_confirm = (string) $this->input->post('password_confirm');
		$id_unit          = (int) $this->input->post('id_unit');
		$id_bidang        = (int) $this->input->post('id_bidang');
		$textcaptcha      = trim((string) $this->input->post('cicaptcha'));

		// 1. Validasi Captcha
		$expiration = time() - 7200;
		$this->ci->db->query("DELETE FROM captcha WHERE captcha_time < ".$expiration);

		$ip_cap = substr((string) $this->ci->input->ip_address(), 0, 16);
		$sql = "SELECT COUNT(*) AS count FROM captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?";
		$row = $this->ci->db->query($sql, array($textcaptcha, $ip_cap, $expiration))->row();

		if (empty($row) || (int) $row->count === 0) {
			audit_log('Autentikasi', 'REGISTER_GAGAL', $username !== '' ? $username : '(kosong)', 'Kode Captcha tidak cocok saat registrasi', array(
				'id_pegawai' => null,
				'username'   => $username !== '' ? $username : 'guest',
				'nama_user'  => $nama !== '' ? $nama : 'Tamu',
				'nama_role'  => '-'
			));
			$this->_json_error('Kode Captcha yang Anda masukkan tidak cocok. Silakan ulangi kembali.');
		}

		// 2. Validasi field wajib
		if ($nama === '' || strlen($nama) < 3) {
			$this->_json_error('Nama lengkap wajib diisi (minimal 3 karakter).');
		}
		if (strlen($nama) > 50) {
			$this->_json_error('Nama lengkap maksimal 50 karakter.');
		}

		if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$this->_json_error('Alamat email tidak valid.');
		}
		if (strlen($email) > 80) {
			$this->_json_error('Alamat email maksimal 80 karakter.');
		}

		if ($username === '' || strlen($username) < 3 || strlen($username) > 50) {
			$this->_json_error('Username harus terdiri dari 3 hingga 50 karakter.');
		}
		if (!preg_match('/^[a-zA-Z0-9 ._-]+$/', $username)) {
			$this->_json_error('Username hanya boleh berisi huruf, angka, spasi, titik (.), garis bawah (_), atau strip (-).');
		}
		if (strtolower($username) === 'nimda') {
			$this->_json_error('Username tersebut tidak diizinkan.');
		}

		if (strlen($password) < 5) {
			$this->_json_error('Password minimal 5 karakter.');
		}
		if ($password !== $password_confirm) {
			$this->_json_error('Konfirmasi password tidak cocok.');
		}

		// 3. Validasi Unit Kerja & Bidang
		$unit_row = $this->db->get_where('ref_unit', array('id_unit' => $id_unit))->row();
		if (empty($unit_row)) {
			$this->_json_error('Silakan pilih Unit Kerja yang valid.');
		}
		$bidang_row = $this->db->get_where('ref_bidang', array('id_bidang' => $id_bidang, 'id_unit' => $id_unit))->row();
		if (empty($bidang_row)) {
			$this->_json_error('Silakan pilih Bidang yang sesuai dengan Unit Kerja.');
		}

		// 4. Cek duplikasi Username (tidak boleh bentrok dengan username maupun NIP akun lain)
		$username_space = str_replace('_', ' ', $username);
		$cek_usr = $this->db->query(
			"SELECT id_pegawai FROM peg_pegawai WHERE username = ? OR REPLACE(username, '_', ' ') = ? OR nip = ? LIMIT 1",
			array($username, $username_space, $username)
		);
		if ($cek_usr->num_rows() > 0) {
			$this->_json_error('Username <strong>'.html_escape($username).'</strong> sudah digunakan akun lain. Silakan pilih username lain.');
		}

		// 5. Validasi NIP / ID Pegawai (wajib diisi, unik, maksimal 18 karakter, dapat dipakai untuk login)
		if ($nip === '') {
			$this->_json_error('NIP / ID Pegawai wajib diisi sesuai ID/NIP Anda.');
		}
		if (strlen($nip) < 3 || strlen($nip) > 18) {
			$this->_json_error('NIP / ID Pegawai harus terdiri dari 3 hingga 18 karakter.');
		}
		if (!preg_match('/^[a-zA-Z0-9._-]+$/', $nip)) {
			$this->_json_error('NIP / ID Pegawai hanya boleh berisi angka, huruf, titik (.), strip (-), atau garis bawah (_) tanpa spasi.');
		}
		if (strtolower($nip) === 'nimda') {
			$this->_json_error('NIP / ID Pegawai tersebut tidak diizinkan.');
		}
		$cek_nip = $this->db->query(
			"SELECT id_pegawai FROM peg_pegawai WHERE nip = ? OR username = ? LIMIT 1",
			array($nip, $nip)
		);
		if ($cek_nip->num_rows() > 0) {
			$this->_json_error('NIP / ID Pegawai <strong>'.html_escape($nip).'</strong> sudah terdaftar di sistem.');
		}

		// 6. Tentukan Role usulan untuk e-Filing (bukan Super Admin; role final ditetapkan Super Admin saat ACC)
		$id_role_post = (int) $this->input->post('id_role_usulan');
		$role_row = null;
		if ($id_role_post > 0) {
			$role_row = $this->db->query(
				"SELECT r.id_role, r.nama_role FROM ref_role r LEFT JOIN ref_aplikasi a ON a.id_aplikasi = r.id_aplikasi WHERE a.folder = ? AND r.id_role = ? AND LOWER(r.nama_role) <> ? LIMIT 1",
				array('filing', $id_role_post, 'super admin')
			)->row();
		}
		if (empty($role_row)) {
			$role_row = $this->db->query(
				"SELECT r.id_role, r.nama_role FROM ref_role r LEFT JOIN ref_aplikasi a ON a.id_aplikasi = r.id_aplikasi WHERE a.folder = ? AND LOWER(r.nama_role) = ? LIMIT 1",
				array('filing', 'operator')
			)->row();
		}
		$id_role_usulan = !empty($role_row) ? (int) $role_row->id_role : 82;
		$nama_role_usulan = !empty($role_row) ? $role_row->nama_role : 'Operator';

		// 7. Simpan ke peg_pegawai (status = 0 Menunggu ACC Super Admin) dan peg_jabatan dalam transaksi
		// Role aktif di pegawai_role baru diberikan setelah Super Admin menyetujui (ACC)
		$this->db->trans_start();

		$simpan_peg = array(
			'username'        => $username,
			'password'        => md5($password),
			'nip'             => $nip,
			'nama'            => $nama,
			'email'           => $email,
			'id_agama'        => 0,
			'id_jeniskelamin' => 0,
			'id_gol_darah'    => 0,
			'id_tempat_lahir' => 0,
			'id_kelurahan'    => 0,
			'status'          => 0,
			'reg_role_usulan' => $id_role_usulan,
			'reg_created_at'  => date('Y-m-d H:i:s'),
			'reg_approved_by' => null,
			'reg_approved_at' => null,
			'reg_catatan'     => null
		);
		$id_peg = $this->general_model->save_data('peg_pegawai', $simpan_peg);

		// Bersihkan kemungkinan sisa relasi orphan untuk ID pegawai baru
		$this->general_model->delete_data('peg_jabatan', 'id_pegawai', $id_peg);
		$this->general_model->delete_data('pegawai_role', 'id_pegawai', $id_peg);

		$simpan_jab = array(
			'id_pegawai'        => $id_peg,
			'id_unit'           => $id_unit,
			'id_bidang'         => $id_bidang,
			'id_jabatan'        => 0,
			'id_status_pegawai' => 0,
			'no_sk'             => '',
			'tmt_jabatan'       => date('Y-m-d'),
			'selesai_jabatan'   => date('Y-m-d'),
			'status'            => 1
		);
		$this->general_model->save_data('peg_jabatan', $simpan_jab);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE || empty($id_peg)) {
			$this->_json_error('Terjadi kesalahan saat menyimpan data registrasi. Silakan coba lagi.');
		}

		// Hapus captcha yang sudah terpakai
		$this->ci->db->query("DELETE FROM captcha WHERE word = ? AND ip_address = ?", array($textcaptcha, $ip_cap));

		// Catat audit trail
		audit_log('Autentikasi', 'REGISTER_PENDING', $username, 'Pendaftaran akun baru menunggu ACC Super Admin | Usulan Role: '.$nama_role_usulan.' ('.$nama.' | '.$unit_row->unit.' - '.$bidang_row->nama_bidang.')', array(
			'id_pegawai' => $id_peg,
			'username'   => $username,
			'nama_user'  => $nama,
			'nama_role'  => 'Menunggu ACC'
		));

		$this->session->set_flashdata('ok', 'Pendaftaran berhasil dikirim! Akun <strong>'.html_escape($username).'</strong> (NIP/ID: <strong>'.html_escape($nip).'</strong>, Usulan Role: <strong>'.html_escape($nama_role_usulan).'</strong>) saat ini <strong>menunggu persetujuan (ACC) Super Admin</strong>. Setelah disetujui, Anda dapat login menggunakan Username atau NIP / ID Pegawai.');
		$this->session->set_flashdata('welcome', $nip);

		header('Content-Type: application/json');
		die(json_encode(array(
			'sign'     => '200',
			'text'     => 'Registrasi berhasil dikirim! Menunggu ACC Super Admin. Mengalihkan ke halaman login...',
			'redirect' => site_url('login')
		)));
	}

	private function _json_error($message) {
		header('Content-Type: application/json');
		die(json_encode(array(
			'sign'    => '404',
			'text'    => $message,
			'captcha' => $this->cicaptcha->show()
		)));
	}
}
