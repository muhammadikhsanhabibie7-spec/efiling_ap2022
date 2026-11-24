<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#[AllowDynamicProperties]
class Audit_log extends CI_Controller {

	function __construct() {
		parent::__construct();
		login_check($this->session->userdata('login_state'));
		if (!$this->_can_view()) {
			$this->session->set_flashdata('fail', 'Akses ditolak. Menu Log Aktivitas (Audit) hanya untuk Admin, Super Admin, atau Pimpinan.');
			redirect('filing/home');
		}
	}

	private function _can_view() {
		if ($this->session->userdata('login_state') === 'root') return TRUE;
		$id_peg = (int) $this->session->userdata('id_pegawai');
		if ($id_peg <= 0) return FALSE;
		if ($this->general_model->check_role($id_peg, 'OPRSA')) return TRUE;
		$q = $this->db->query(
			"SELECT COUNT(*) AS jml
			 FROM pegawai_role pr
			 INNER JOIN ref_role_nav rn ON rn.id_role = pr.id_role
			 WHERE pr.id_pegawai = ? AND rn.id_nav = 1861",
			array($id_peg)
		);
		return ($q && $q->num_rows() > 0 && (int) $q->row()->jml > 0);
	}

	private function _parse_date($str) {
		$str = trim((string) $str);
		if ($str === '') return '';
		if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $str)) {
			return tanggal_php($str);
		}
		if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $str)) {
			return $str;
		}
		return '';
	}

	public function index($mode = null) {
		$tgl_mulai_in   = trim((string) $this->input->get('tgl_mulai'));
		$tgl_selesai_in = trim((string) $this->input->get('tgl_selesai'));
		$modul          = trim((string) $this->input->get('modul'));
		$aksi           = trim((string) $this->input->get('aksi'));
		$device         = trim((string) $this->input->get('device'));
		$q              = trim((string) $this->input->get('q'));
		$page           = (int) $this->input->get('page');
		if ($page < 1) $page = 1;

		$tgl_mulai_db   = $this->_parse_date($tgl_mulai_in);
		$tgl_selesai_db = $this->_parse_date($tgl_selesai_in);

		$where_sql = " WHERE 1=1";
		$binds = array();

		if ($tgl_mulai_db !== '') {
			$where_sql .= " AND DATE(waktu) >= ?";
			$binds[] = $tgl_mulai_db;
		}
		if ($tgl_selesai_db !== '') {
			$where_sql .= " AND DATE(waktu) <= ?";
			$binds[] = $tgl_selesai_db;
		}
		if ($modul !== '') {
			$where_sql .= " AND modul = ?";
			$binds[] = $modul;
		}
		if ($aksi !== '') {
			$where_sql .= " AND aksi = ?";
			$binds[] = $aksi;
		}
		if ($device !== '') {
			$where_sql .= " AND device_cookie = ?";
			$binds[] = $device;
		}
		if ($q !== '') {
			$like = '%'.$q.'%';
			$where_sql .= " AND (username LIKE ? OR nama_user LIKE ? OR nama_role LIKE ? OR objek LIKE ? OR detail LIKE ? OR ip_address LIKE ? OR device_cookie LIKE ? OR url_path LIKE ?)";
			$binds = array_merge($binds, array($like, $like, $like, $like, $like, $like, $like, $like));
		}

		// Hitung total baris sesuai filter
		$q_total = !empty($binds)
			? $this->db->query("SELECT COUNT(*) AS jml FROM arsip_audit_log".$where_sql, $binds)
			: $this->db->query("SELECT COUNT(*) AS jml FROM arsip_audit_log".$where_sql);
		$total_filtered = ($q_total && $q_total->num_rows() > 0) ? (int) $q_total->row()->jml : 0;

		$per_page = 50;
		$total_pages = max(1, (int) ceil($total_filtered / $per_page));
		if ($page > $total_pages) $page = $total_pages;
		$offset = ($page - 1) * $per_page;

		$is_export = in_array($mode, array('cetak', 'excel'), true);
		$limit_sql = $is_export ? " LIMIT 5000" : (" LIMIT ".$per_page." OFFSET ".$offset);

		$sql_rows = "SELECT * FROM arsip_audit_log".$where_sql." ORDER BY id_audit DESC".$limit_sql;
		$rows = !empty($binds)
			? $this->db->query($sql_rows, $binds)->result()
			: $this->db->query($sql_rows)->result();

		// Periode label untuk cetak / export
		if ($tgl_mulai_in !== '' && $tgl_selesai_in !== '') {
			$periode_label = $tgl_mulai_in.' s/d '.$tgl_selesai_in;
		} elseif ($tgl_mulai_in !== '') {
			$periode_label = 'Mulai '.$tgl_mulai_in;
		} elseif ($tgl_selesai_in !== '') {
			$periode_label = 'Sampai '.$tgl_selesai_in;
		} else {
			$periode_label = 'Semua Waktu';
		}

		if ($is_export) {
			$data['rows'] = $rows;
			$data['mode'] = $mode;
			$data['st'] = get_stationer();
			$data['periode_label'] = $periode_label;
			$data['filter_modul'] = $modul;
			$data['filter_aksi'] = $aksi;
			$data['filter_device'] = $device;
			$data['filter_q'] = $q;
			$data['total_filtered'] = $total_filtered;

			if ($mode === 'excel') {
				$filename = 'Log_Audit_eFiling_'.date('Ymd_His').'.xls';
				header("Content-Type: application/vnd.ms-excel; charset=utf-8");
				header("Content-Disposition: attachment; filename=\"$filename\"");
				header("Cache-Control: max-age=0");
			}
			$this->load->view('filing/audit_log_cetak', $data);
			return;
		}

		// Statistik ringkas global
		$stat = array(
			'total' => 0,
			'hari_ini' => 0,
			'hapus' => 0,
			'login_gagal' => 0,
			'perangkat_unik' => 0
		);
		$qs = $this->db->query(
			"SELECT
				COUNT(*) AS total,
				SUM(CASE WHEN DATE(waktu) = CURDATE() THEN 1 ELSE 0 END) AS hari_ini,
				SUM(CASE WHEN aksi = 'HAPUS' THEN 1 ELSE 0 END) AS hapus,
				SUM(CASE WHEN aksi = 'LOGIN_GAGAL' THEN 1 ELSE 0 END) AS login_gagal,
				COUNT(DISTINCT NULLIF(device_cookie, '')) AS perangkat_unik
			 FROM arsip_audit_log"
		);
		if ($qs && $qs->num_rows() > 0) {
			$sr = $qs->row();
			$stat['total'] = (int) $sr->total;
			$stat['hari_ini'] = (int) $sr->hari_ini;
			$stat['hapus'] = (int) $sr->hapus;
			$stat['login_gagal'] = (int) $sr->login_gagal;
			$stat['perangkat_unik'] = (int) $sr->perangkat_unik;
		}

		$data['rows'] = $rows;
		$data['stat'] = $stat;
		$data['total_filtered'] = $total_filtered;
		$data['page'] = $page;
		$data['per_page'] = $per_page;
		$data['total_pages'] = $total_pages;
		$data['offset'] = $offset;
		$data['filter_mulai'] = $tgl_mulai_in;
		$data['filter_selesai'] = $tgl_selesai_in;
		$data['filter_modul'] = $modul;
		$data['filter_aksi'] = $aksi;
		$data['filter_device'] = $device;
		$data['filter_q'] = $q;
		$data['current_device_cookie'] = get_tracking_cookie();
		$data['modul_list'] = array('Autentikasi', 'Pengarsipan', 'Disposisi', 'Peminjaman', 'Retensi', 'Referensi', 'Pengaturan', 'Akun');
		$data['aksi_list'] = array(
			'LOGIN' => 'Login',
			'LOGOUT' => 'Logout',
			'LOGIN_GAGAL' => 'Login Gagal',
			'TAMBAH' => 'Tambah Data',
			'UBAH' => 'Ubah Data',
			'HAPUS' => 'Hapus Data',
			'TERIMA' => 'Terima / ACC',
			'TOLAK' => 'Tolak',
			'VALIDASI' => 'Validasi Super Admin',
			'KEMBALI' => 'Pengembalian Arsip',
			'TINDAK_LANJUT' => 'Tindak Lanjut (TL)',
			'PINDAH' => 'Pindah Arsip/Unit',
			'RESET_PW' => 'Reset Password'
		);
		$data['title'] = 'Log Aktivitas Pengguna (Audit Trail)';
		$data['descript'] = 'Rekaman permanen seluruh aksi pengguna lengkap dengan Cookie ID perangkat, IP, dan detail perubahan';
		$data['content'] = 'filing/audit_log_view';
		$this->load->view('home', $data);
	}

	public function cetak() {
		$this->index('cetak');
	}

	public function excel() {
		$this->index('excel');
	}
}
