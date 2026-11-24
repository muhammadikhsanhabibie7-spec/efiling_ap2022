<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#[AllowDynamicProperties]
class Peminjaman extends CI_Controller {

	function __construct() {
		parent::__construct();
		login_check($this->session->userdata('login_state'));
		$this->_cek_keterlambatan();
	}

	private function _has_access($code) {
		$id_peg = $this->session->userdata('id_pegawai');
		if ($this->session->userdata('login_state') === 'root') return TRUE;
		if ($this->general_model->check_role($id_peg, 'OPRSA')) return TRUE;
		return $this->general_model->check_role($id_peg, $code);
	}

	private function _is_superadmin() {
		$id_peg = $this->session->userdata('id_pegawai');
		if ($this->session->userdata('login_state') === 'root') return TRUE;
		return $this->general_model->check_role($id_peg, 'OPRSA');
	}

	private function _cek_keterlambatan() {
		if ($this->db->table_exists('arsip_peminjaman')) {
			$today = date('Y-m-d');
			$this->db->where('status_pinjam', 'Dipinjam');
			$this->db->where('tgl_kembali <', $today);
			$this->db->update('arsip_peminjaman', array('status_pinjam' => 'Telat'));
		}
	}

	public function index() {
		$status = $this->input->get('status');
		$q = trim((string) $this->input->get('q'));

		$sql = "SELECT p.*, k.no_surat, k.judul, k.jenis_surat, k.tanggal AS tgl_surat,
				v.nama AS nama_verifikator
				FROM arsip_peminjaman p
				LEFT JOIN arsip_konten k ON k.id_arsip_konten = p.id_arsip_konten
				LEFT JOIN peg_pegawai v ON v.id_pegawai = p.diverifikasi_oleh
				WHERE 1=1";
		$binds = array();

		if (!empty($status) && in_array($status, array('Menunggu','Dipinjam','Ditolak','Dikembalikan','Telat'))) {
			$sql .= " AND p.status_pinjam = ?";
			$binds[] = $status;
		}
		if ($q !== '') {
			$like = '%'.$q.'%';
			$sql .= " AND (k.no_surat LIKE ? OR k.judul LIKE ? OR p.nama_peminjam LIKE ? OR p.unit_peminjam LIKE ? OR p.keperluan LIKE ?)";
			$binds = array_merge($binds, array($like, $like, $like, $like, $like));
		}
		$sql .= " ORDER BY FIELD(p.status_pinjam, 'Menunggu','Telat','Dipinjam','Dikembalikan','Ditolak'), p.id_peminjaman DESC";

		$rows = !empty($binds) ? $this->db->query($sql, $binds)->result() : $this->db->query($sql)->result();

		$stat = array('total' => 0, 'Menunggu' => 0, 'Dipinjam' => 0, 'Telat' => 0, 'Dikembalikan' => 0, 'Ditolak' => 0);
		$qstat = $this->db->query("SELECT status_pinjam, COUNT(*) AS jml FROM arsip_peminjaman GROUP BY status_pinjam");
		if ($qstat) {
			foreach ($qstat->result() as $st_row) {
				$stat[$st_row->status_pinjam] = (int) $st_row->jml;
				$stat['total'] += (int) $st_row->jml;
			}
		}

		$data['rows'] = $rows;
		$data['stat'] = $stat;
		$data['filter_status'] = $status;
		$data['filter_q'] = $q;
		$data['can_add'] = $this->_has_access('PJMAD');
		$data['can_verify'] = $this->_has_access('PJMVR');
		$data['can_del'] = $this->_has_access('PJMDE');
		$data['is_superadmin'] = $this->_is_superadmin();
		$data['title'] = 'Peminjaman Arsip';
		$data['descript'] = 'Pengajuan, konfirmasi admin, kontrol super admin, dan riwayat peminjaman arsip';
		$data['content'] = 'filing/peminjaman_view';
		$this->load->view('home', $data);
	}

	public function form($id_arsip_konten = null) {
		if (!$this->_has_access('PJMAD')) {
			$this->session->set_flashdata('fail', 'Anda tidak memiliki kewenangan mengajukan peminjaman arsip.');
			redirect('filing/peminjaman');
		}

		$data['selected_arsip'] = !empty($id_arsip_konten) ? (int) $id_arsip_konten : null;
		$data['arsip_list'] = $this->db->query("SELECT id_arsip_konten, no_surat, judul, tanggal, jenis_surat FROM arsip_konten ORDER BY id_arsip_konten DESC")->result();
		$data['bidang_list'] = $this->db->query("SELECT id_bidang, nama_bidang FROM ref_bidang ORDER BY urut, nama_bidang")->result();
		$data['default_nama'] = $this->session->userdata('nama');
		$data['default_unit'] = $this->session->userdata('bidang');
		$data['title'] = 'Ajukan Peminjaman Arsip';
		$data['content'] = 'filing/peminjaman_form';
		$this->load->view('home', $data);
	}

	public function save() {
		if (!$this->_has_access('PJMAD')) {
			$this->session->set_flashdata('fail', 'Anda tidak memiliki kewenangan mengajukan peminjaman.');
			redirect('filing/peminjaman');
		}

		$id_arsip_konten = (int) $this->input->post('id_arsip_konten');
		$nama_peminjam = trim((string) $this->input->post('nama_peminjam'));
		$unit_peminjam = trim((string) $this->input->post('unit_peminjam'));
		$keperluan = trim((string) $this->input->post('keperluan'));
		$tgl_in = trim((string) $this->input->post('tgl_pinjam'));
		$tgl_pinjam = (strpos($tgl_in, '/') !== FALSE) ? tanggal_php($tgl_in) : ($tgl_in !== '' ? $tgl_in : date('Y-m-d'));
		$lama_pinjam = (int) $this->input->post('lama_pinjam');
		if ($lama_pinjam < 1) $lama_pinjam = 7;
		$tgl_kembali = date('Y-m-d', strtotime($tgl_pinjam.' +'.$lama_pinjam.' days'));

		if ($id_arsip_konten <= 0 || $nama_peminjam === '' || $keperluan === '') {
			$this->session->set_flashdata('fail', 'Arsip, Nama Peminjam, dan Keperluan wajib diisi.');
			redirect('filing/peminjaman/form/'.$id_arsip_konten);
		}

		$simpan = array(
			'id_arsip_konten' => $id_arsip_konten,
			'id_pegawai_peminjam' => $this->session->userdata('id_pegawai'),
			'nama_peminjam' => $nama_peminjam,
			'unit_peminjam' => $unit_peminjam,
			'keperluan' => $keperluan,
			'tgl_pinjam' => $tgl_pinjam,
			'lama_pinjam' => $lama_pinjam,
			'tgl_kembali' => $tgl_kembali,
			'status_pinjam' => 'Menunggu'
		);

		$this->general_model->save_data('arsip_peminjaman', $simpan);
		$surat_info = $this->db->select('no_surat, judul')->get_where('arsip_konten', array('id_arsip_konten' => $id_arsip_konten))->row();
		$obj = !empty($surat_info) ? trim($surat_info->no_surat.' - '.$surat_info->judul) : 'Arsip #'.$id_arsip_konten;
		audit_log('Peminjaman', 'TAMBAH', $obj, 'Pengajuan peminjaman oleh '.$nama_peminjam.($unit_peminjam !== '' ? ' ('.$unit_peminjam.')' : '').' | Durasi: '.$lama_pinjam.' hari ('.$tgl_pinjam.' s/d '.$tgl_kembali.') | Keperluan: '.$keperluan);
		$this->session->set_flashdata('ok', 'Pengajuan peminjaman arsip berhasil dikirim dan menunggu konfirmasi Admin.');
		redirect('filing/peminjaman');
	}

	public function konfirmasi() {
		if (!$this->_has_access('PJMVR')) {
			$this->session->set_flashdata('fail', 'Anda tidak memiliki kewenangan verifikasi peminjaman.');
			redirect('filing/peminjaman');
		}

		$id = (int) $this->input->post('id_peminjaman');
		$aksi = $this->input->post('aksi');
		$catatan = trim((string) $this->input->post('catatan_verifikasi'));

		if ($id > 0 && in_array($aksi, array('Dipinjam', 'Ditolak'))) {
			$old = $this->db->query("SELECT p.*, k.no_surat, k.judul FROM arsip_peminjaman p LEFT JOIN arsip_konten k ON k.id_arsip_konten = p.id_arsip_konten WHERE p.id_peminjaman = ?", array($id))->row();
			$upd = array(
				'status_pinjam' => $aksi,
				'diverifikasi_oleh' => $this->session->userdata('id_pegawai'),
				'tgl_verifikasi' => date('Y-m-d H:i:s'),
				'catatan_verifikasi' => $catatan
			);
			if ($this->_is_superadmin()) {
				$upd['divalidasi_superadmin'] = 1;
				$upd['tgl_validasi_superadmin'] = date('Y-m-d H:i:s');
			}
			$this->general_model->save_data('arsip_peminjaman', $upd, 'id_peminjaman', $id);
			$obj = !empty($old) ? trim($old->no_surat.' - '.$old->judul) : 'Peminjaman #'.$id;
			$log_aksi = ($aksi === 'Dipinjam') ? 'TERIMA' : 'TOLAK';
			$peminjam_str = !empty($old) ? $old->nama_peminjam : '-';
			audit_log('Peminjaman', $log_aksi, $obj, 'Peminjaman #'.$id.' oleh '.$peminjam_str.' -> '.$aksi.($catatan !== '' ? ' | Catatan: '.$catatan : ''));
			$this->session->set_flashdata('ok', 'Status peminjaman berhasil diverifikasi menjadi: '.$aksi);
		}
		redirect('filing/peminjaman');
	}

	public function validasi_superadmin($id = null) {
		if (!$this->_is_superadmin()) {
			$this->session->set_flashdata('fail', 'Hanya Super Admin yang dapat melakukan validasi kontrol.');
			redirect('filing/peminjaman');
		}
		$id = (int) $id;
		if ($id > 0) {
			$old = $this->db->query("SELECT p.*, k.no_surat, k.judul FROM arsip_peminjaman p LEFT JOIN arsip_konten k ON k.id_arsip_konten = p.id_arsip_konten WHERE p.id_peminjaman = ?", array($id))->row();
			$this->general_model->save_data('arsip_peminjaman', array(
				'divalidasi_superadmin' => 1,
				'tgl_validasi_superadmin' => date('Y-m-d H:i:s')
			), 'id_peminjaman', $id);
			$obj = !empty($old) ? trim($old->no_surat.' - '.$old->judul) : 'Peminjaman #'.$id;
			audit_log('Peminjaman', 'VALIDASI', $obj, 'Kontrol/Validasi Super Admin pada Peminjaman #'.$id.(!empty($old) ? ' (Peminjam: '.$old->nama_peminjam.', Status: '.$old->status_pinjam.')' : ''));
			$this->session->set_flashdata('ok', 'Verifikasi peminjaman telah dikonfirmasi oleh Super Admin.');
		}
		redirect('filing/peminjaman');
	}

	public function kembalikan($id = null) {
		if (!$this->_has_access('PJMVR')) {
			$this->session->set_flashdata('fail', 'Anda tidak memiliki kewenangan mencatat pengembalian.');
			redirect('filing/peminjaman');
		}
		$id = (int) $id;
		if ($id > 0) {
			$old = $this->db->query("SELECT p.*, k.no_surat, k.judul FROM arsip_peminjaman p LEFT JOIN arsip_konten k ON k.id_arsip_konten = p.id_arsip_konten WHERE p.id_peminjaman = ?", array($id))->row();
			$this->general_model->save_data('arsip_peminjaman', array(
				'status_pinjam' => 'Dikembalikan',
				'tgl_dikembalikan' => date('Y-m-d')
			), 'id_peminjaman', $id);
			$obj = !empty($old) ? trim($old->no_surat.' - '.$old->judul) : 'Peminjaman #'.$id;
			audit_log('Peminjaman', 'KEMBALI', $obj, 'Pengembalian arsip Peminjaman #'.$id.(!empty($old) ? ' dari '.$old->nama_peminjam : ''));
			$this->session->set_flashdata('ok', 'Arsip telah dicatat dikembalikan.');
		}
		redirect('filing/peminjaman');
	}

	public function delete($id = null) {
		if (!$this->_has_access('PJMDE')) {
			$this->session->set_flashdata('fail', 'Anda tidak memiliki kewenangan menghapus data peminjaman.');
			redirect('filing/peminjaman');
		}
		$id = (int) $id;
		if ($id > 0) {
			$old = $this->db->query("SELECT p.*, k.no_surat, k.judul FROM arsip_peminjaman p LEFT JOIN arsip_konten k ON k.id_arsip_konten = p.id_arsip_konten WHERE p.id_peminjaman = ?", array($id))->row();
			$this->general_model->delete_data('arsip_peminjaman', 'id_peminjaman', $id);
			$obj = !empty($old) ? trim($old->no_surat.' - '.$old->judul) : 'Peminjaman #'.$id;
			audit_log('Peminjaman', 'HAPUS', $obj, !empty($old) ? 'Menghapus Peminjaman #'.$id.' oleh '.$old->nama_peminjam.' (Status: '.$old->status_pinjam.')' : 'Menghapus Peminjaman #'.$id);
			$this->session->set_flashdata('ok', 'Data peminjaman berhasil dihapus.');
		}
		redirect('filing/peminjaman');
	}
}
