<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#[AllowDynamicProperties]
class Retensi extends CI_Controller {

	function __construct() {
		parent::__construct();
		login_check($this->session->userdata('login_state'));
		$this->_sync_status_retensi();
	}

	private function _has_access($code) {
		$id_peg = $this->session->userdata('id_pegawai');
		if ($this->session->userdata('login_state') === 'root') return TRUE;
		if ($this->general_model->check_role($id_peg, 'OPRSA')) return TRUE;
		return $this->general_model->check_role($id_peg, $code);
	}

	private function _sync_status_retensi() {
		$today = date('Y-m-d');
		// Aktif -> Inaktif jika sudah lewat tgl_inaktif
		$to_inaktif = $this->db->query("SELECT id_arsip_konten FROM arsip_konten WHERE status_arsip = 'Aktif' AND tgl_inaktif IS NOT NULL AND tgl_inaktif <= ?", array($today));
		if ($to_inaktif && $to_inaktif->num_rows() > 0) {
			foreach ($to_inaktif->result() as $r) {
				$this->db->where('id_arsip_konten', $r->id_arsip_konten)->update('arsip_konten', array('status_arsip' => 'Inaktif'));
				$this->db->insert('arsip_retensi_log', array(
					'id_arsip_konten' => $r->id_arsip_konten,
					'status_lama' => 'Aktif',
					'status_baru' => 'Inaktif',
					'keterangan' => 'Otomatis melewati tanggal jatuh tempo masa aktif',
					'id_pegawai' => $this->session->userdata('id_pegawai')
				));
			}
		}

		// Inaktif -> Retensi jika sudah lewat tgl_retensi
		$to_retensi = $this->db->query("SELECT id_arsip_konten FROM arsip_konten WHERE status_arsip = 'Inaktif' AND tgl_retensi IS NOT NULL AND tgl_retensi <= ?", array($today));
		if ($to_retensi && $to_retensi->num_rows() > 0) {
			foreach ($to_retensi->result() as $r) {
				$this->db->where('id_arsip_konten', $r->id_arsip_konten)->update('arsip_konten', array('status_arsip' => 'Retensi'));
				$this->db->insert('arsip_retensi_log', array(
					'id_arsip_konten' => $r->id_arsip_konten,
					'status_lama' => 'Inaktif',
					'status_baru' => 'Retensi',
					'keterangan' => 'Otomatis melewati tanggal jatuh tempo masa inaktif',
					'id_pegawai' => $this->session->userdata('id_pegawai')
				));
			}
		}
	}

	public function index() {
		$status = $this->input->get('status');
		$q = trim((string) $this->input->get('q'));

		$sql = "SELECT k.*, j.arsip_jenis
				FROM arsip_konten k
				LEFT JOIN ref_arsip_jenis j ON j.id_arsip_jenis = k.id_arsip_jenis
				WHERE 1=1";
		$binds = array();

		if (!empty($status) && in_array($status, array('Aktif','Inaktif','Retensi','Permanen','Musnah'))) {
			$sql .= " AND k.status_arsip = ?";
			$binds[] = $status;
		}
		if ($q !== '') {
			$like = '%'.$q.'%';
			$sql .= " AND (k.no_surat LIKE ? OR k.judul LIKE ? OR j.arsip_jenis LIKE ?)";
			$binds = array_merge($binds, array($like, $like, $like));
		}
		$sql .= " ORDER BY FIELD(k.status_arsip,'Retensi','Inaktif','Aktif','Permanen','Musnah'), k.tanggal DESC";

		$rows = !empty($binds) ? $this->db->query($sql, $binds)->result() : $this->db->query($sql)->result();

		$stat = array('Aktif' => 0, 'Inaktif' => 0, 'Retensi' => 0, 'Permanen' => 0, 'Musnah' => 0);
		$qstat = $this->db->query("SELECT status_arsip, COUNT(*) AS jml FROM arsip_konten GROUP BY status_arsip");
		if ($qstat) {
			foreach ($qstat->result() as $s) {
				$stat[$s->status_arsip] = (int) $s->jml;
			}
		}

		$logs = $this->db->query("SELECT l.*, k.no_surat, k.judul, p.nama AS nama_pegawai
			FROM arsip_retensi_log l
			LEFT JOIN arsip_konten k ON k.id_arsip_konten = l.id_arsip_konten
			LEFT JOIN peg_pegawai p ON p.id_pegawai = l.id_pegawai
			ORDER BY l.id_log DESC LIMIT 30")->result();

		$data['rows'] = $rows;
		$data['stat'] = $stat;
		$data['logs'] = $logs;
		$data['filter_status'] = $status;
		$data['filter_q'] = $q;
		$data['can_edit'] = $this->_has_access('RETED');
		$data['title'] = 'Retensi &amp; Masa Inaktif Arsip';
		$data['descript'] = 'Pemantauan jadwal retensi, status arsip aktif/inaktif/retensi/permanen/musnah, dan riwayat';
		$data['content'] = 'filing/retensi_view';
		$this->load->view('home', $data);
	}

	public function update_status() {
		if (!$this->_has_access('RETED')) {
			$this->session->set_flashdata('fail', 'Anda tidak memiliki kewenangan mengubah status retensi arsip.');
			redirect('filing/retensi');
		}

		$id = (int) $this->input->post('id_arsip_konten');
		$status_baru = $this->input->post('status_arsip');
		$masa_aktif = (int) $this->input->post('masa_aktif');
		$tgl_inaktif_in = trim((string) $this->input->post('tgl_inaktif'));
		$tgl_retensi_in = trim((string) $this->input->post('tgl_retensi'));
		$keterangan = trim((string) $this->input->post('keterangan'));

		if ($id > 0 && in_array($status_baru, array('Aktif','Inaktif','Retensi','Permanen','Musnah'))) {
			$old = $this->db->get_where('arsip_konten', array('id_arsip_konten' => $id))->row();
			if ($old) {
				$upd = array('status_arsip' => $status_baru);
				if ($masa_aktif > 0) $upd['masa_aktif'] = $masa_aktif;
				if ($tgl_inaktif_in !== '') $upd['tgl_inaktif'] = (strpos($tgl_inaktif_in, '/') !== FALSE) ? tanggal_php($tgl_inaktif_in) : $tgl_inaktif_in;
				if ($tgl_retensi_in !== '') $upd['tgl_retensi'] = (strpos($tgl_retensi_in, '/') !== FALSE) ? tanggal_php($tgl_retensi_in) : $tgl_retensi_in;

				$this->db->where('id_arsip_konten', $id)->update('arsip_konten', $upd);
				$this->db->insert('arsip_retensi_log', array(
					'id_arsip_konten' => $id,
					'status_lama' => $old->status_arsip,
					'status_baru' => $status_baru,
					'keterangan' => $keterangan !== '' ? $keterangan : 'Perubahan status retensi manual',
					'id_pegawai' => $this->session->userdata('id_pegawai')
				));
				audit_log('Retensi', 'UBAH', trim($old->no_surat.' - '.$old->judul), 'Status Arsip: '.$old->status_arsip.' -> '.$status_baru.($masa_aktif > 0 ? ' | Masa Aktif: '.$masa_aktif.' thn' : '').($keterangan !== '' ? ' | Ket: '.$keterangan : ''));
				$this->session->set_flashdata('ok', 'Status retensi arsip berhasil diperbarui.');
			}
		}
		redirect('filing/retensi');
	}
}
