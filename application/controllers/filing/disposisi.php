<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#[AllowDynamicProperties]
class Disposisi extends CI_Controller {

	function __construct() {
		parent::__construct();
		login_check($this->session->userdata('login_state'));
	}

	private function _has_access($code) {
		$id_peg = $this->session->userdata('id_pegawai');
		if ($this->session->userdata('login_state') === 'root') return TRUE;
		if ($this->general_model->check_role($id_peg, 'OPRSA')) return TRUE;
		return $this->general_model->check_role($id_peg, $code);
	}

	public function index() {
		$status = $this->input->get('status');
		$q = trim((string) $this->input->get('q'));

		$sql = "SELECT d.*, k.no_surat, k.judul, k.tanggal AS tgl_surat, k.jenis_surat, k.pengirim, k.penerima,
				b.nama_bidang, p1.nama AS nama_dari, p2.nama AS nama_tujuan, p3.nama AS nama_updater
				FROM arsip_disposisi d
				LEFT JOIN arsip_konten k ON k.id_arsip_konten = d.id_arsip_konten
				LEFT JOIN ref_bidang b ON b.id_bidang = d.tujuan_bidang
				LEFT JOIN peg_pegawai p1 ON p1.id_pegawai = d.dari_pegawai
				LEFT JOIN peg_pegawai p2 ON p2.id_pegawai = d.tujuan_pegawai
				LEFT JOIN peg_pegawai p3 ON p3.id_pegawai = d.updated_by
				WHERE 1=1";
		$binds = array();

		if (!empty($status) && in_array($status, array('Menunggu', 'Diproses', 'Selesai'))) {
			$sql .= " AND d.status_tl = ?";
			$binds[] = $status;
		}
		if ($q !== '') {
			$like = '%'.$q.'%';
			$sql .= " AND (k.no_surat LIKE ? OR k.judul LIKE ? OR d.instruksi LIKE ? OR d.tembusan LIKE ? OR d.diteruskan_kepada LIKE ? OR b.nama_bidang LIKE ?)";
			$binds = array_merge($binds, array($like, $like, $like, $like, $like, $like));
		}
		$sql .= " ORDER BY d.id_disposisi DESC";

		$rows = !empty($binds) ? $this->db->query($sql, $binds)->result() : $this->db->query($sql)->result();

		$stat = array('total' => 0, 'Menunggu' => 0, 'Diproses' => 0, 'Selesai' => 0);
		$qstat = $this->db->query("SELECT status_tl, COUNT(*) AS jml FROM arsip_disposisi GROUP BY status_tl");
		if ($qstat) {
			foreach ($qstat->result() as $st_row) {
				$stat[$st_row->status_tl] = (int) $st_row->jml;
				$stat['total'] += (int) $st_row->jml;
			}
		}

		$data['rows'] = $rows;
		$data['stat'] = $stat;
		$data['filter_status'] = $status;
		$data['filter_q'] = $q;
		$data['can_add'] = $this->_has_access('DSPAD');
		$data['can_tl'] = $this->_has_access('DSPTL');
		$data['can_del'] = $this->_has_access('DSPDE');
		$data['title'] = 'Disposisi &amp; Tindak Lanjut Surat';
		$data['descript'] = 'Kelola penerusan surat, instruksi disposisi, tembusan, dan tindak lanjut (TL)';
		$data['content'] = 'filing/disposisi_view';
		$this->load->view('home', $data);
	}

	public function form($id_arsip_konten = null, $id_disposisi = null) {
		if (!$this->_has_access('DSPAD')) {
			$this->session->set_flashdata('fail', 'Anda tidak memiliki kewenangan menambah/mengubah disposisi.');
			redirect('filing/disposisi');
		}

		$data['def'] = null;
		if (!empty($id_disposisi)) {
			$q = $this->db->get_where('arsip_disposisi', array('id_disposisi' => (int) $id_disposisi));
			if ($q && $q->num_rows() > 0) {
				$data['def'] = $q->row();
				$id_arsip_konten = $data['def']->id_arsip_konten;
			}
		}

		$data['selected_arsip'] = !empty($id_arsip_konten) ? (int) $id_arsip_konten : null;
		$data['arsip_list'] = $this->db->query("SELECT id_arsip_konten, no_surat, judul, tanggal, jenis_surat, pengirim, penerima FROM arsip_konten ORDER BY id_arsip_konten DESC")->result();
		$data['bidang_list'] = $this->db->query("SELECT id_bidang, kode_bidang, nama_bidang FROM ref_bidang ORDER BY urut, nama_bidang")->result();
		$data['pegawai_list'] = $this->db->query("SELECT id_pegawai, nama, username FROM peg_pegawai ORDER BY nama")->result();
		$data['title'] = !empty($data['def']) ? 'Ubah Disposisi' : 'Tambah Disposisi Surat';
		$data['content'] = 'filing/disposisi_form';
		$this->load->view('home', $data);
	}

	public function save() {
		if (!$this->_has_access('DSPAD')) {
			$this->session->set_flashdata('fail', 'Anda tidak memiliki kewenangan menyimpan disposisi.');
			redirect('filing/disposisi');
		}

		$id_disposisi = (int) $this->input->post('id_disposisi');
		$id_arsip_konten = (int) $this->input->post('id_arsip_konten');
		$tgl_in = trim((string) $this->input->post('tgl_disposisi'));
		$tgl_disposisi = (strpos($tgl_in, '/') !== FALSE) ? tanggal_php($tgl_in) : ($tgl_in !== '' ? $tgl_in : date('Y-m-d'));

		$tujuan_bidang = (int) $this->input->post('tujuan_bidang');
		$tujuan_pegawai = (int) $this->input->post('tujuan_pegawai');
		$diteruskan_kepada = trim((string) $this->input->post('diteruskan_kepada'));
		$sifat = $this->input->post('sifat');
		if (!in_array($sifat, array('Biasa', 'Penting', 'Segera', 'Rahasia'))) $sifat = 'Biasa';

		$instruksi_preset = $this->input->post('instruksi_preset');
		$instruksi_text = trim((string) $this->input->post('instruksi'));
		$parts = array();
		if (is_array($instruksi_preset) && !empty($instruksi_preset)) {
			$parts[] = implode(', ', $instruksi_preset);
		}
		if ($instruksi_text !== '') {
			$parts[] = $instruksi_text;
		}
		$instruksi_final = implode(' - ', $parts);

		if ($id_arsip_konten <= 0 || $instruksi_final === '') {
			$this->session->set_flashdata('fail', 'Surat/Arsip dan Instruksi Disposisi wajib diisi.');
			redirect('filing/disposisi/form/'.$id_arsip_konten.'/'.$id_disposisi);
		}

		$status_tl = $this->input->post('status_tl');
		if (!in_array($status_tl, array('Menunggu', 'Diproses', 'Selesai'))) $status_tl = 'Menunggu';
		$tindak_lanjut = trim((string) $this->input->post('tindak_lanjut'));

		$simpan = array(
			'id_arsip_konten' => $id_arsip_konten,
			'no_agenda' => trim((string) $this->input->post('no_agenda')),
			'tgl_disposisi' => $tgl_disposisi,
			'tujuan_bidang' => $tujuan_bidang > 0 ? $tujuan_bidang : null,
			'tujuan_pegawai' => $tujuan_pegawai > 0 ? $tujuan_pegawai : null,
			'diteruskan_kepada' => $diteruskan_kepada,
			'sifat' => $sifat,
			'instruksi' => $instruksi_final,
			'tembusan' => trim((string) $this->input->post('tembusan')),
			'catatan' => trim((string) $this->input->post('catatan')),
			'tindak_lanjut' => $tindak_lanjut,
			'status_tl' => $status_tl
		);

		if ($tindak_lanjut !== '' || $status_tl !== 'Menunggu') {
			$simpan['tgl_tindak_lanjut'] = date('Y-m-d H:i:s');
			$simpan['updated_by'] = $this->session->userdata('id_pegawai');
		}

		$surat_info = $this->db->select('no_surat, judul')->get_where('arsip_konten', array('id_arsip_konten' => $id_arsip_konten))->row();
		$surat_label = !empty($surat_info) ? trim($surat_info->no_surat.' - '.$surat_info->judul) : 'Arsip #'.$id_arsip_konten;
		$tujuan_parts = array();
		if ($tujuan_bidang > 0) {
			$bd = $this->db->select('nama_bidang')->get_where('ref_bidang', array('id_bidang' => $tujuan_bidang))->row();
			if ($bd) $tujuan_parts[] = $bd->nama_bidang;
		}
		if ($tujuan_pegawai > 0) {
			$pg = $this->db->select('nama')->get_where('peg_pegawai', array('id_pegawai' => $tujuan_pegawai))->row();
			if ($pg) $tujuan_parts[] = $pg->nama;
		}
		if ($diteruskan_kepada !== '') $tujuan_parts[] = $diteruskan_kepada;
		$tujuan_str = !empty($tujuan_parts) ? implode(' / ', $tujuan_parts) : '-';

		if ($id_disposisi > 0) {
			$this->general_model->save_data('arsip_disposisi', $simpan, 'id_disposisi', $id_disposisi);
			audit_log('Disposisi', 'UBAH', $surat_label, 'Disposisi #'.$id_disposisi.' | Tujuan: '.$tujuan_str.' | Sifat: '.$sifat.' | Instruksi: '.$instruksi_final.' | Status TL: '.$status_tl);
			$this->session->set_flashdata('ok', 'Data disposisi berhasil diperbarui.');
		} else {
			$simpan['dari_pegawai'] = $this->session->userdata('id_pegawai');
			$this->general_model->save_data('arsip_disposisi', $simpan);
			audit_log('Disposisi', 'TAMBAH', $surat_label, 'Tujuan: '.$tujuan_str.' | Sifat: '.$sifat.' | Instruksi: '.$instruksi_final.(!empty($simpan['tembusan']) ? ' | Tembusan: '.$simpan['tembusan'] : ''));
			$this->session->set_flashdata('ok', 'Disposisi baru berhasil ditambahkan.');
		}

		redirect('filing/disposisi');
	}

	public function update_tl() {
		if (!$this->_has_access('DSPTL')) {
			$this->session->set_flashdata('fail', 'Anda tidak memiliki kewenangan memperbarui tindak lanjut.');
			redirect('filing/disposisi');
		}

		$id = (int) $this->input->post('id_disposisi');
		$status_tl = $this->input->post('status_tl');
		if (!in_array($status_tl, array('Menunggu', 'Diproses', 'Selesai'))) $status_tl = 'Diproses';
		$tindak_lanjut = trim((string) $this->input->post('tindak_lanjut'));

		if ($id > 0) {
			$old = $this->db->query("SELECT d.status_tl, d.instruksi, k.no_surat, k.judul FROM arsip_disposisi d LEFT JOIN arsip_konten k ON k.id_arsip_konten = d.id_arsip_konten WHERE d.id_disposisi = ?", array($id))->row();
			$this->general_model->save_data('arsip_disposisi', array(
				'status_tl' => $status_tl,
				'tindak_lanjut' => $tindak_lanjut,
				'tgl_tindak_lanjut' => date('Y-m-d H:i:s'),
				'updated_by' => $this->session->userdata('id_pegawai')
			), 'id_disposisi', $id);
			$obj = !empty($old) ? trim($old->no_surat.' - '.$old->judul) : 'Disposisi #'.$id;
			$prev_st = !empty($old) ? $old->status_tl : '-';
			audit_log('Disposisi', 'TINDAK_LANJUT', $obj, 'Disposisi #'.$id.' | Status TL: '.$prev_st.' -> '.$status_tl.($tindak_lanjut !== '' ? ' | Catatan TL: '.$tindak_lanjut : ''));
			$this->session->set_flashdata('ok', 'Tindak Lanjut (TL) disposisi berhasil diperbarui.');
		}
		redirect('filing/disposisi');
	}

	public function delete($id = null) {
		if (!$this->_has_access('DSPDE')) {
			$this->session->set_flashdata('fail', 'Anda tidak memiliki kewenangan menghapus disposisi.');
			redirect('filing/disposisi');
		}
		$id = (int) $id;
		if ($id > 0) {
			$old = $this->db->query("SELECT d.*, k.no_surat, k.judul FROM arsip_disposisi d LEFT JOIN arsip_konten k ON k.id_arsip_konten = d.id_arsip_konten WHERE d.id_disposisi = ?", array($id))->row();
			$this->general_model->delete_data('arsip_disposisi', 'id_disposisi', $id);
			$obj = !empty($old) ? trim($old->no_surat.' - '.$old->judul) : 'Disposisi #'.$id;
			$det = !empty($old) ? 'Menghapus Disposisi #'.$id.' | Instruksi: '.$old->instruksi.' | Diteruskan: '.$old->diteruskan_kepada.' | Status TL: '.$old->status_tl : 'Menghapus Disposisi #'.$id;
			audit_log('Disposisi', 'HAPUS', $obj, $det);
			$this->session->set_flashdata('ok', 'Disposisi berhasil dihapus.');
		}
		redirect('filing/disposisi');
	}

	public function cetak($id = null) {
		$id = (int) $id;
		$sql = "SELECT d.*, k.no_surat, k.judul, k.tanggal AS tgl_surat, k.jenis_surat, k.pengirim, k.penerima,
				b.nama_bidang, p1.nama AS nama_dari, p2.nama AS nama_tujuan, p3.nama AS nama_updater
				FROM arsip_disposisi d
				LEFT JOIN arsip_konten k ON k.id_arsip_konten = d.id_arsip_konten
				LEFT JOIN ref_bidang b ON b.id_bidang = d.tujuan_bidang
				LEFT JOIN peg_pegawai p1 ON p1.id_pegawai = d.dari_pegawai
				LEFT JOIN peg_pegawai p2 ON p2.id_pegawai = d.tujuan_pegawai
				LEFT JOIN peg_pegawai p3 ON p3.id_pegawai = d.updated_by
				WHERE d.id_disposisi = ?";
		$q = $this->db->query($sql, array($id));
		if (!$q || $q->num_rows() == 0) {
			show_404();
		}
		$data['row'] = $q->row();
		$data['st'] = get_stationer();
		$this->load->view('filing/disposisi_cetak', $data);
	}
}
