<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#[AllowDynamicProperties]
class Persetujuan_akun extends CI_Controller {

	function __construct() {
		parent::__construct();
		login_check($this->session->userdata('login_state'));

		$id_peg = $this->session->userdata('id_pegawai');
		if ($this->session->userdata('login_state') !== 'root' && !$this->general_model->check_role($id_peg, 'OPRSA')) {
			$this->session->set_flashdata('fail', 'Akses ditolak. Menu Persetujuan Akun hanya dapat diakses oleh Super Admin.');
			redirect('filing/home');
		}
	}

	private function _get_filing_roles() {
		return $this->db->query(
			"SELECT r.id_role, r.nama_role
			 FROM ref_role r
			 LEFT JOIN ref_aplikasi a ON a.id_aplikasi = r.id_aplikasi
			 WHERE a.folder = ?
			 ORDER BY FIELD(LOWER(r.nama_role), 'operator', 'admin', 'pimpinan', 'super admin'), r.id_role ASC",
			array('filing')
		)->result();
	}

	public function index() {
		$status = strtolower(trim((string) $this->input->get('status')));
		$q = trim((string) $this->input->get('q'));

		$sql = "SELECT
					p.id_pegawai,
					p.nama,
					p.nip,
					p.username,
					p.email,
					p.status,
					p.reg_role_usulan,
					p.reg_created_at,
					p.reg_approved_by,
					p.reg_approved_at,
					p.reg_catatan,
					u.unit,
					b.nama_bidang,
					ru.nama_role AS role_usulan_nama,
					ap.nama AS approver_nama,
					(
						SELECT rr.nama_role
						FROM pegawai_role pr
						JOIN ref_role rr ON rr.id_role = pr.id_role
						JOIN ref_aplikasi ra ON ra.id_aplikasi = rr.id_aplikasi
						WHERE pr.id_pegawai = p.id_pegawai AND ra.folder = 'filing'
						LIMIT 1
					) AS role_aktif_nama,
					(
						SELECT rr.id_role
						FROM pegawai_role pr
						JOIN ref_role rr ON rr.id_role = pr.id_role
						JOIN ref_aplikasi ra ON ra.id_aplikasi = rr.id_aplikasi
						WHERE pr.id_pegawai = p.id_pegawai AND ra.folder = 'filing'
						LIMIT 1
					) AS role_aktif_id
				FROM peg_pegawai p
				LEFT JOIN peg_jabatan j ON j.id_pegawai = p.id_pegawai AND j.status = 1
				LEFT JOIN ref_bidang b ON b.id_bidang = j.id_bidang
				LEFT JOIN ref_unit u ON u.id_unit = b.id_unit
				LEFT JOIN ref_role ru ON ru.id_role = p.reg_role_usulan
				LEFT JOIN peg_pegawai ap ON ap.id_pegawai = p.reg_approved_by
				WHERE 1=1";
		$binds = array();

		if ($status === 'pending') {
			$sql .= " AND p.status = 0";
		} elseif ($status === 'approved') {
			$sql .= " AND p.status = 1";
		} elseif ($status === 'rejected') {
			$sql .= " AND p.status = 2";
		}

		if ($q !== '') {
			$like = '%'.$q.'%';
			$sql .= " AND (p.nama LIKE ? OR p.username LIKE ? OR p.nip LIKE ? OR p.email LIKE ? OR u.unit LIKE ? OR b.nama_bidang LIKE ?)";
			$binds = array_merge($binds, array($like, $like, $like, $like, $like, $like));
		}

		$sql .= " ORDER BY FIELD(p.status, 0, 2, 1), COALESCE(p.reg_created_at, '1970-01-01 00:00:00') DESC, p.id_pegawai DESC";

		$rows = !empty($binds) ? $this->db->query($sql, $binds)->result() : $this->db->query($sql)->result();

		$stat = array('pending' => 0, 'approved' => 0, 'rejected' => 0, 'total' => 0);
		$qstat = $this->db->query("SELECT status, COUNT(*) AS jml FROM peg_pegawai GROUP BY status");
		if ($qstat) {
			foreach ($qstat->result() as $st_row) {
				$st_code = (int) $st_row->status;
				$cnt = (int) $st_row->jml;
				$stat['total'] += $cnt;
				if ($st_code === 0) $stat['pending'] = $cnt;
				elseif ($st_code === 1) $stat['approved'] = $cnt;
				elseif ($st_code === 2) $stat['rejected'] = $cnt;
			}
		}

		$data['rows'] = $rows;
		$data['stat'] = $stat;
		$data['filing_roles'] = $this->_get_filing_roles();
		$data['filter_status'] = $status;
		$data['filter_q'] = $q;
		$data['current_id_pegawai'] = (int) $this->session->userdata('id_pegawai');
		$data['breadcrumb'] = array('' => 'e-Filing', 'filing/persetujuan_akun' => 'Persetujuan Akun');
		$data['title'] = 'Persetujuan Registrasi Akun';
		$data['descript'] = 'Verifikasi pendaftaran pengguna baru (ACC / Tolak) dan penetapan Role oleh Super Admin';
		$data['content'] = 'filing/persetujuan_akun_view';
		$this->load->view('home', $data);
	}

	public function proses() {
		$id_pegawai = (int) $this->input->post('id_pegawai');
		$aksi = strtolower(trim((string) $this->input->post('aksi')));
		$id_role = (int) $this->input->post('id_role');
		$catatan = trim((string) $this->input->post('catatan'));
		$redirect_to = trim((string) $this->input->post('redirect_to'));

		$dest = ($redirect_to === 'dashboard') ? 'filing/home' : 'filing/persetujuan_akun';

		if ($id_pegawai <= 0 || !in_array($aksi, array('acc', 'tolak'))) {
			$this->session->set_flashdata('fail', 'Permintaan persetujuan akun tidak valid.');
			redirect($dest);
		}

		$self_id = (int) $this->session->userdata('id_pegawai');
		if ($self_id > 0 && $id_pegawai === $self_id && $aksi === 'tolak') {
			$this->session->set_flashdata('fail', 'Anda tidak dapat menolak akun yang sedang Anda gunakan.');
			redirect($dest);
		}

		$target = $this->db->get_where('peg_pegawai', array('id_pegawai' => $id_pegawai))->row();
		if (empty($target)) {
			$this->session->set_flashdata('fail', 'Data akun pengguna tidak ditemukan.');
			redirect($dest);
		}

		// Ambil semua id_role aplikasi e-Filing untuk pembersihan sebelum penetapan ulang
		$filing_role_rows = $this->_get_filing_roles();
		$filing_role_ids = array();
		foreach ($filing_role_rows as $fr) {
			$filing_role_ids[] = (int) $fr->id_role;
		}

		$approver_id = ($this->session->userdata('login_state') === 'root') ? 0 : $self_id;
		$now = date('Y-m-d H:i:s');

		if ($aksi === 'acc') {
			$role_row = $this->db->query(
				"SELECT r.id_role, r.nama_role
				 FROM ref_role r
				 LEFT JOIN ref_aplikasi a ON a.id_aplikasi = r.id_aplikasi
				 WHERE a.folder = ? AND r.id_role = ? LIMIT 1",
				array('filing', $id_role)
			)->row();

			if (empty($role_row)) {
				$this->session->set_flashdata('fail', 'Silakan pilih Role yang valid sebelum menyetujui (ACC) akun.');
				redirect($dest);
			}

			$this->db->trans_start();

			$this->db->where('id_pegawai', $id_pegawai)->update('peg_pegawai', array(
				'status'          => 1,
				'reg_approved_by' => $approver_id,
				'reg_approved_at' => $now,
				'reg_catatan'     => ($catatan !== '' ? substr($catatan, 0, 255) : null)
			));

			if (!empty($filing_role_ids)) {
				$this->db->where('id_pegawai', $id_pegawai);
				$this->db->where_in('id_role', $filing_role_ids);
				$this->db->delete('pegawai_role');
			}

			$this->db->insert('pegawai_role', array(
				'id_role'    => (int) $role_row->id_role,
				'id_pegawai' => $id_pegawai
			));

			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE) {
				$this->session->set_flashdata('fail', 'Gagal menyimpan persetujuan akun. Silakan coba lagi.');
				redirect($dest);
			}

			audit_log(
				'Persetujuan Akun',
				'ACC_AKUN',
				$target->username,
				'Akun disetujui (ACC) Super Admin | Nama: '.$target->nama.' | NIP: '.$target->nip.' | Role ditetapkan: '.$role_row->nama_role.($catatan !== '' ? ' | Catatan: '.$catatan : '')
			);

			$this->session->set_flashdata(
				'ok',
				'Akun <strong>'.html_escape($target->nama).'</strong> (Username: <strong>'.html_escape($target->username).'</strong>) berhasil <strong>disetujui (ACC)</strong> dengan role <strong>'.html_escape($role_row->nama_role).'</strong>.'
			);
			redirect($dest);
		} else {
			$this->db->trans_start();

			$this->db->where('id_pegawai', $id_pegawai)->update('peg_pegawai', array(
				'status'          => 2,
				'reg_approved_by' => $approver_id,
				'reg_approved_at' => $now,
				'reg_catatan'     => ($catatan !== '' ? substr($catatan, 0, 255) : 'Pendaftaran tidak disetujui oleh Super Admin')
			));

			if (!empty($filing_role_ids)) {
				$this->db->where('id_pegawai', $id_pegawai);
				$this->db->where_in('id_role', $filing_role_ids);
				$this->db->delete('pegawai_role');
			}

			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE) {
				$this->session->set_flashdata('fail', 'Gagal menolak registrasi akun. Silakan coba lagi.');
				redirect($dest);
			}

			audit_log(
				'Persetujuan Akun',
				'TOLAK_AKUN',
				$target->username,
				'Registrasi akun ditolak Super Admin | Nama: '.$target->nama.' | NIP: '.$target->nip.($catatan !== '' ? ' | Alasan: '.$catatan : '')
			);

			$this->session->set_flashdata(
				'ok',
				'Pendaftaran akun <strong>'.html_escape($target->nama).'</strong> (Username: <strong>'.html_escape($target->username).'</strong>) telah <strong>ditolak</strong>.'
			);
			redirect($dest);
		}
	}

	public function hapus($id = null) {
		$id_pegawai = (int) $id;
		$target = $this->db->get_where('peg_pegawai', array('id_pegawai' => $id_pegawai))->row();
		if (empty($target)) {
			$this->session->set_flashdata('fail', 'Data registrasi tidak ditemukan.');
			redirect('filing/persetujuan_akun');
		}

		if ((int) $target->status === 1) {
			$this->session->set_flashdata('fail', 'Akun aktif hanya dapat dihapus melalui menu Pengaturan &rarr; Operator.');
			redirect('filing/persetujuan_akun');
		}

		$this->db->trans_start();
		$this->db->delete('pegawai_role', array('id_pegawai' => $id_pegawai));
		$this->db->delete('peg_jabatan', array('id_pegawai' => $id_pegawai));
		$this->db->delete('peg_pegawai', array('id_pegawai' => $id_pegawai));
		$this->db->trans_complete();

		audit_log('Persetujuan Akun', 'HAPUS_REGISTER', $target->username, 'Menghapus data registrasi akun (Nama: '.$target->nama.' | NIP: '.$target->nip.')');
		$this->session->set_flashdata('ok', 'Data pendaftaran akun <strong>'.html_escape($target->nama).'</strong> berhasil dihapus permanen.');
		redirect('filing/persetujuan_akun');
	}
}
