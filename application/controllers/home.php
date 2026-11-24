<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#[AllowDynamicProperties]
class Home extends CI_Controller {

	function __construct() {
	
		parent::__construct();
		
	}

	public function index() {
		// echo 'ok';
		$this->init();
	}
	
	function init() {

		$log = $this->session->userdata('login_state');
		// cek($log);die();
		// cek($this->session->userdata('id_pegawai'));die();
		if (!empty($log)) {
			if ($log == 'root') {
				redirect('inti/pengaturan/parameter');
			} else {
				$a = $this->general_model->datagrab(array(
					'tabel' => array(
						'pegawai_role r' => '',
						'ref_role ro' => 'ro.id_role = r.id_role',
						'ref_aplikasi a' => 'a.id_aplikasi = ro.id_aplikasi'),
					'select' => 'count(ro.id_aplikasi) as jml,a.folder',
					'group_by' => 'ro.id_aplikasi',
					'where' => array('r.id_pegawai' => $this->session->userdata('id_pegawai'))
				));
			
				if ($a->num_rows() > 1) redirect('login/role_choice');
				else redirect($a->row()->folder.'/home');
			}
		} else {
			$data['st'] = get_stationer();
			$data['cover_stats'] = $this->_get_cover_stats();
			$this->load->view('umum/cover_view', $data);
		}
	}

	private function _get_cover_stats() {
		$stats = array(
			'total_konten'       => 0,
			'surat_masuk'        => 0,
			'surat_keluar'       => 0,
			'arsip_aktif'        => 0,
			'arsip_retensi'      => 0,
			'total_folder'       => 0,
			'total_disposisi'    => 0,
			'disposisi_menunggu' => 0,
			'disposisi_diproses' => 0,
			'disposisi_proses'   => 0,
			'disposisi_selesai'  => 0,
			'total_peminjaman'   => 0,
			'pinjam_menunggu'    => 0,
			'pinjam_dipinjam'    => 0,
			'pinjam_kembali'     => 0,
			'pinjam_ditolak'     => 0,
			'total_jenis'        => 0,
			'total_unit'         => 0,
			'total_bidang'       => 0,
			'jenis_dist'         => array(),
			'sifat_dist'         => array('Biasa' => 0, 'Penting' => 0, 'Segera' => 0, 'Rahasia' => 0),
			'retensi_dist'       => array('Aktif' => 0, 'Inaktif' => 0, 'Retensi' => 0, 'Permanen' => 0, 'Musnah' => 0),
			'monthly_trend'      => array()
		);

		if ($this->db->table_exists('arsip_konten')) {
			$row = $this->db->query("
				SELECT
					COUNT(*) AS total_konten,
					SUM(CASE WHEN jenis_surat = 'M' THEN 1 ELSE 0 END) AS surat_masuk,
					SUM(CASE WHEN jenis_surat = 'K' THEN 1 ELSE 0 END) AS surat_keluar,
					SUM(CASE WHEN status_arsip = 'Aktif' THEN 1 ELSE 0 END) AS arsip_aktif,
					SUM(CASE WHEN status_arsip <> 'Aktif' THEN 1 ELSE 0 END) AS arsip_retensi
				FROM arsip_konten
			")->row();
			if (!empty($row)) {
				$stats['total_konten']  = (int) $row->total_konten;
				$stats['surat_masuk']   = (int) $row->surat_masuk;
				$stats['surat_keluar']  = (int) $row->surat_keluar;
				$stats['arsip_aktif']   = (int) $row->arsip_aktif;
				$stats['arsip_retensi'] = (int) $row->arsip_retensi;
			}

			// Distribusi status retensi
			$ret_rows = $this->db->query("SELECT status_arsip, COUNT(*) AS jml FROM arsip_konten GROUP BY status_arsip")->result();
			foreach ($ret_rows as $rr) {
				if (isset($stats['retensi_dist'][$rr->status_arsip])) {
					$stats['retensi_dist'][$rr->status_arsip] = (int) $rr->jml;
				}
			}

			// Distribusi per jenis arsip (top 5)
			if ($this->db->table_exists('ref_arsip_jenis')) {
				$j_rows = $this->db->query("
					SELECT j.arsip_jenis, COUNT(k.id_arsip_konten) AS jml
					FROM ref_arsip_jenis j
					LEFT JOIN arsip_konten k ON k.id_arsip_jenis = j.id_arsip_jenis
					GROUP BY j.id_arsip_jenis, j.arsip_jenis
					ORDER BY jml DESC, j.arsip_jenis ASC
					LIMIT 5
				")->result();
				foreach ($j_rows as $jr) {
					$stats['jenis_dist'][] = array(
						'label' => (string) $jr->arsip_jenis,
						'value' => (int) $jr->jml
					);
				}
			}

			// Tren bulanan 6 bulan terakhir (berdasarkan bulan terbaru yg punya data atau bulan ini)
			$m_rows = $this->db->query("
				SELECT
					DATE_FORMAT(COALESCE(tanggal, tanggal_unggah), '%Y-%m') AS bln,
					SUM(CASE WHEN jenis_surat = 'M' THEN 1 ELSE 0 END) AS masuk,
					SUM(CASE WHEN jenis_surat = 'K' THEN 1 ELSE 0 END) AS keluar
				FROM arsip_konten
				WHERE COALESCE(tanggal, tanggal_unggah) IS NOT NULL
				GROUP BY bln
				ORDER BY bln DESC
				LIMIT 6
			")->result();
			$m_map = array();
			$anchor_ym = date('Y-m');
			if (!empty($m_rows)) {
				foreach ($m_rows as $mr) {
					$m_map[$mr->bln] = array('masuk' => (int) $mr->masuk, 'keluar' => (int) $mr->keluar);
				}
				if (!empty($m_rows[0]->bln)) {
					$anchor_ym = $m_rows[0]->bln;
				}
			}
			$bulan_id = array('01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'Mei','06'=>'Jun','07'=>'Jul','08'=>'Agu','09'=>'Sep','10'=>'Okt','11'=>'Nov','12'=>'Des');
			$anchor_ts = strtotime($anchor_ym.'-01');
			if (!$anchor_ts) $anchor_ts = time();
			for ($i = 5; $i >= 0; $i--) {
				$ts = strtotime('-'.$i.' months', $anchor_ts);
				$ym = date('Y-m', $ts);
				$mm = date('m', $ts);
				$yy = date('y', $ts);
				$stats['monthly_trend'][] = array(
					'label'  => (isset($bulan_id[$mm]) ? $bulan_id[$mm] : $mm).' '.$yy,
					'masuk'  => isset($m_map[$ym]) ? $m_map[$ym]['masuk'] : 0,
					'keluar' => isset($m_map[$ym]) ? $m_map[$ym]['keluar'] : 0
				);
			}
		}

		if ($this->db->table_exists('arsip')) {
			$stats['total_folder'] = (int) $this->db->count_all('arsip');
		}

		if ($this->db->table_exists('arsip_disposisi')) {
			$row = $this->db->query("
				SELECT
					COUNT(*) AS total_disposisi,
					SUM(CASE WHEN status_tl = 'Menunggu' THEN 1 ELSE 0 END) AS disposisi_menunggu,
					SUM(CASE WHEN status_tl = 'Diproses' THEN 1 ELSE 0 END) AS disposisi_diproses,
					SUM(CASE WHEN status_tl = 'Selesai' THEN 1 ELSE 0 END) AS disposisi_selesai
				FROM arsip_disposisi
			")->row();
			if (!empty($row)) {
				$stats['total_disposisi']    = (int) $row->total_disposisi;
				$stats['disposisi_menunggu'] = (int) $row->disposisi_menunggu;
				$stats['disposisi_proses']   = (int) $row->disposisi_menunggu + (int) $row->disposisi_diproses;
				$stats['disposisi_diproses'] = (int) $row->disposisi_diproses;
				$stats['disposisi_selesai']  = (int) $row->disposisi_selesai;
			}
			$sf_rows = $this->db->query("SELECT sifat, COUNT(*) AS jml FROM arsip_disposisi GROUP BY sifat")->result();
			foreach ($sf_rows as $sr) {
				if (isset($stats['sifat_dist'][$sr->sifat])) {
					$stats['sifat_dist'][$sr->sifat] = (int) $sr->jml;
				}
			}
		}

		if ($this->db->table_exists('arsip_peminjaman')) {
			$row = $this->db->query("
				SELECT
					COUNT(*) AS total_peminjaman,
					SUM(CASE WHEN status_pinjam = 'Menunggu' THEN 1 ELSE 0 END) AS pinjam_menunggu,
					SUM(CASE WHEN status_pinjam IN ('Dipinjam','Telat') THEN 1 ELSE 0 END) AS pinjam_dipinjam,
					SUM(CASE WHEN status_pinjam = 'Dikembalikan' THEN 1 ELSE 0 END) AS pinjam_kembali,
					SUM(CASE WHEN status_pinjam = 'Ditolak' THEN 1 ELSE 0 END) AS pinjam_ditolak
				FROM arsip_peminjaman
			")->row();
			if (!empty($row)) {
				$stats['total_peminjaman'] = (int) $row->total_peminjaman;
				$stats['pinjam_menunggu']  = (int) $row->pinjam_menunggu;
				$stats['pinjam_dipinjam']  = (int) $row->pinjam_dipinjam;
				$stats['pinjam_kembali']   = (int) $row->pinjam_kembali;
				$stats['pinjam_ditolak']   = (int) $row->pinjam_ditolak;
			}
		}

		if ($this->db->table_exists('ref_arsip_jenis')) {
			$stats['total_jenis'] = (int) $this->db->count_all('ref_arsip_jenis');
		}
		if ($this->db->table_exists('ref_unit')) {
			$stats['total_unit'] = (int) $this->db->count_all('ref_unit');
		}
		if ($this->db->table_exists('ref_bidang')) {
			$stats['total_bidang'] = (int) $this->db->count_all('ref_bidang');
		}

		return $stats;
	}
	
	function aplikasi($dir) {
		redirect($dir.'/home');
	}

	function general_delete($par) {
	
		login_check($this->session->userdata('login_state'));
	
		$p = un_de($par);
		$old_row = $this->db->get_where($p['tabel'], array($p['kolom'] => $p['id']))->row_array();
		
		$this->general_model->delete_data($p['tabel'],$p['kolom'],$p['id']);
		audit_log('Referensi', 'HAPUS', $p['tabel'].' #'.$p['id'], !empty($old_row) ? $old_row : 'Kolom: '.$p['kolom'].'='.$p['id']);
		$this->session->set_flashdata('ok','Data berhasil dihapus');
			
		redirect($p['redirect']);
		
	}
	
	function akun() {
		
		$folder = 'filing';
		login_check($this->session->userdata('login_state'));
		$data['title'] = 'Ubah Profil & Password';
		if (!$this->general_model->check_role($this->session->userdata('id_pegawai'),'OPRTT')) {
		$id = $this->session->userdata('id_pegawai');
		$data['row'] = $this->general_model->datagrab(array('tabel' => 'peg_pegawai','where' => array('id_pegawai' => $id)))->row();
		$data['link_foto'] = 'home/save_foto';
		$data['link_base'] = $folder;
		} 
		$this->load->view('umum/akun_form',$data);
		

	}


	
	function update_akun(){
	
		$id	= $this->input->post('id_pegawai');
		
		$a = $this->input->post('username');
		$al = $this->input->post('username_lama');
		$pwd_lama = $this->input->post('pwd_lama');
		$pb = $this->input->post('pwd_baru');
		$pb2 = $this->input->post('pwd_baru2');

		if (!empty($pwd_lama) and !empty($pb) and !empty($pb2)) {
		
		$cek = $this->general_model->datagrab(array('tabel' => 'peg_pegawai','where' => array('id_pegawai' => $id, 'password' => md5($pwd_lama))));	
		
		if ($pb === $pb2) {
			
				if ($cek->num_rows() > 0) {
					$this->general_model->save_data('peg_pegawai',array('password' => md5($pb)),'id_pegawai',$id);
					audit_log('Akun', 'UBAH', (string) $al, 'Mengubah password akun');
					die(json_encode(array('sign' => '1','teks' => 'Password berhasil diubah!')));
				} else {
					die(json_encode(array('sign' => '404','teks' => 'Password lama salah!')));
				}
			
			} else {

				die(json_encode(array('sign' => '404','teks' => 'Password baru dan ulangnya tidak sama!')));
			
			}
		
		} else if ($al != $a) {
			if (!empty($pwd_lama)) {
				$cek = $this->general_model->datagrab(array(
					'tabel' => 'peg_pegawai',
					'where' => array('id_pegawai' => $id,
					'password' => md5($pwd_lama))));	
				if ($cek->num_rows() > 0) {
					$this->general_model->save_data('peg_pegawai',array('username' => $a),'id_pegawai',$id);
					audit_log('Akun', 'UBAH', $al.' -> '.$a, 'Mengubah username akun');
					die(json_encode(array('sign' => '1','teks' => 'Akun berhasil diubah!')));
				} else {
					die(json_encode(array('sign' => '404','teks' => 'Password lama salah!')));
				}
			
			} else {
				
				die(json_encode(array('sign' => '404','teks' => 'Password lama harus dimasukkan!')));
			
			}
		
		}
				
	}


	function save_foto() {
		$id = $this->input->post('id_pegawai');
		$offs = $this->input->post('offs');
		$pasfoto = $_FILES['foto']['tmp_name'];
		if (!empty($pasfoto)) {	

			$path = './uploads/kepegawaian/pasfoto';
			if (!is_dir($path)) mkdir($path,0777,TRUE);
		
			if (!empty($id)) {
			
				$prev = $this->input->post('foto_prev');
				$path_pasfoto = $path.'/'.$prev;
				if(file_exists($path_pasfoto)) unlink($path_pasfoto);
				// cek($path_pasfoto);die();	
			} 
			
			$nama_file   = $id.'.jpg';
			
			if (!file_exists('uploads/kepegawaian/pasfoto/'.$nama_file)) {
				
				$this->load->library('upload');
				
				$this->upload->initialize(array(
					'upload_path' => $path,
					'allowed_types' => 'jpg|jpeg|png|gif|pdf',
					'file_name' => $nama_file,
					'overwrite' => TRUE
				));
				$this->upload->do_upload('foto');

			}
			
			$this->general_model->save_data(
				'peg_pegawai',array('photo' => $nama_file),'id_pegawai',$id);
			audit_log('Akun', 'UBAH', 'Pegawai #'.$id, 'Memperbarui foto profil: '.$nama_file);
			
			$this->session->set_flashdata('ok','Foto berhasil disimpan ...');
		} else {
			$this->session->set_flashdata('fail','Foto belum dipilih ...');
		} 
		redirect($this->input->post('link_base').'/home');
		
	}
	
}