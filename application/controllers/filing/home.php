<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	var $unit_akar_atas = array();
	var $unit_akar_bawah = array();
	var $bidang_akar_atas = array();
	var $bidang_akar_bawah = array();


	function __construct() {
	
		parent::__construct();
		login_check($this->session->userdata('login_state'));
		
	}


	function statistik($tabel) {
		
		$total = $this->general_model->datagrab(array('tabel' => $tabel,'select' => 'count(*) as jumlah'))->row();
		return $total->jumlah;
		
	}

	public function index() {
		$this->list_data();
		
	}



	
	function set_unit_bawah($e) {
		if (!empty($e)) {
		$dt_ats = $this->general_model->datagrab(array(
			'tabel' => 'ref_unit',
			'where' => array('id_par_unit' => $e)
			));

		if ($dt_ats->num_rows() > 0) {
			foreach ($dt_ats->result() as $r_u) {
				$this->unit_bawah[] = $r_u->id_unit;
				$this->set_unit_bawah($r_u->id_unit);

				// get bidang:
				$dt_bidang = $this->general_model->datagrabs(array(
					'tabel'=>'ref_bidang',
					'where'=>array('id_unit'=>$r_u->id_unit)));
				// cek($this->db->last_query());
				if ($dt_bidang->num_rows() > 0) {
					foreach ($dt_bidang->result() as $r_b) {
						$this->unit_bidang_bawah[] = $r_b->id_bidang;

						$this->set_bidang_bawah($r_b->id_bidang);
						// cek($this->unit_bidang_bawah);

						// get operator
							$jabatan = $this->general_model->datagrabs(array(
								'tabel'=>'peg_jabatan',
								'where'=>array('id_bidang'=>$r_b->id_bidang)));
											// cek($this->db->last_query());

							if ($jabatan->num_rows() > 0) {
								foreach ($jabatan->result() as $r_jab) {
									$this->operators_data[] = $r_jab->id_pegawai;
								}
							}
							// ./get operator


					}
				}
				
					}
				}
			}
		}
			

	
	function set_bidang_bawah($e) {
		if (!empty($e)) {
		//$this->unit_bidang_bawah[] = $e;
		$dt_ats = $this->general_model->datagrab(array('tabel' => 'ref_bidang','where' => array('id_par_bidang' => $e)));
		if ($dt_ats->num_rows() > 0) {
			
			foreach ($dt_ats->result() as $r_b) {
				$this->unit_bidang_bawah[] = $r_b->id_bidang;

				$this->set_bidang_bawah($r_b->id_bidang);

				// get operator
				$jabatan = $this->general_model->datagrabs(array(
					'tabel'=>'peg_jabatan',
					'where'=>array('id_bidang'=>$r_b->id_bidang)));
				// cek($this->db->last_query());
				if ($jabatan->num_rows() > 0) {
					foreach ($jabatan->result() as $r_jab) {
						$this->operators_data[] = $r_jab->id_pegawai;
					}
				}
				// ./get operator

			}

			// if (!empty($dt_ats)) $this->set_bidang_bawah($dt_ats->id_bidang);
		}

		}
	}

	function list_data(){
		$id_jenis = 'all';
		// UNIT
		$this->unit_bawah = array();
		$this->unit_bidang_bawah = array();

		$this->operators_data = array();

		$this->set_unit_bawah($this->session->userdata('id_unit'));
		// cek($this->unit_bawah);
		// ./UNIT

		// $this->unit_bidang_atas = array();
		
		
		
		// $this->set_bidang_atas($this->session->userdata('id_bidang'));
		$this->set_bidang_bawah($this->session->userdata('id_bidang'));
		$this->unit_bidang_bawah[] = !empty($this->session->userdata('id_bidang')) ? $this->session->userdata('id_bidang') : 0;
		$this->operators_data[] = $this->session->userdata('id_pegawai');

		$where_bidang = array();
	
		if (count($this->unit_bidang_bawah) > 0) $where_bidang = array_merge_recursive($where_bidang,$this->unit_bidang_bawah);

		// cek oprator
		// cek($this->operators_data);
		// ./cek oprator

		// cek($where_bidang);
		// cek($this->unit_bidang_bawah);
		$tb_arsip_jenis = $this->general_model->datagrabs(array(
			'tabel' => array(
				'ref_arsip_jenis j' => '',
				'ref_arsip_jenis_role k' => 'k.id_arsip_jenis = j.id_arsip_jenis',
				'ref_bidang b' => 'b.id_bidang = k.id_bidang',
				'ref_unit u' => 'u.id_unit = b.id_unit',
				),
			'select' => 'j.*',
			'where'=>array('k.id_bidang IN ('.implode(',', $where_bidang).')'=>null, 'j.id_par IS NULL'=>null),
			'order' => 'arsip_jenis ASC',
			'group_by' => 'k.id_arsip_jenis' 
		));
		// cek($this->db->last_query());

		// ---- ---//
		// SET ARSIP BY ROLE

					$where_operators = array();
					$where_ar_kt_role = array();
					// operator
					if(count($this->operators_data) > 0)  $where_operators = array_merge_recursive($where_operators, $this->operators_data);
					// ./operator


					// cek arsip bebas / arsip belum dapat kewenangan
					$get_arsip_konten_bebas = $this->general_model->datagrabs(array(
						'tabel'=>'arsip_konten',
						'where'=>array('id_arsip_konten NOT IN (select id_arsip_konten from arsip_konten_role)'=>null)));
					$kt_bebas = array();
					if ($get_arsip_konten_bebas->num_rows() > 0) {
						foreach ($get_arsip_konten_bebas->result() as $r_akt) {
							$kt_bebas[] = $r_akt->id_arsip_konten;
						}
					}


					if (count($kt_bebas) > 0)  $where_ar_kt_role = array_merge_recursive($where_ar_kt_role, $kt_bebas);

					// cek($where_ar_kt_role);

					// ./cek arsip bebas / arsip belum dapat kewenangan

					// get arsip konten in role
					$arsip_kt_role = $this->general_model->datagrabs(array(
						'tabel'=>'arsip_konten_role',
						'where'=>array('(id_bidang IN ('.implode(',', $where_bidang).'))'=>null),
						'group_by'=>'id_arsip_konten'));

					$kt_role = array();
					if ($arsip_kt_role->num_rows() > 0) {
						foreach ($arsip_kt_role->result() as $akt) {
							$kt_role[] = $akt->id_arsip_konten;
						}
						
					}

					// cek(count($kt_role));
					// ./get arsip konten in role
					if (count($kt_role) > 0)  $where_ar_kt_role = array_merge_recursive($where_ar_kt_role, $kt_role);

					// cek(count($kt_role));
					$where_ar_kt_role = count($where_ar_kt_role) > 0 ? $where_ar_kt_role : array(0);
								       
					// ./SET ARSIP BY ROLE
					// ----- ----//
		
		// arsip jenis side bar

			if ($tb_arsip_jenis->num_rows() > 0) {
				$no = 0;
				$this->tampug_aj = array();

				$this->table->set_template(array('table_open'=>'<table class="table table-bordered table-condensed">'));
				$r = 0;
				$num = 1;
				$this->jm_jenis = 0;
				$this->jm_konten = 0;
				$this->jm_butir = 0;

				$heads = array(
	            array('data'=>'No.', 'colspan'=>''),
	            array('data'=>''),
	            array('data'=>'Jenis Arsip', 'colspan'=>'7', 'style'=>'white-space:nowrap;'),
	            array('data'=>'Jumlah Arsip Konten', 'class'=>'', 'style'=>'width:8px'),
	            array('data'=>'Jumlah Butir Arsip', 'class'=>'', 'style'=>'width:8px')
	           
	           );

				$this->table->set_heading($heads);
				foreach ($tb_arsip_jenis->result() as $aj) {
					$this->tampug_aj[] = $aj->id_arsip_jenis;

					$jml_konten = $this->general_model->datagrabs(array(
			            'tabel'=>'arsip_konten',
			            'where'=>array('id_arsip_jenis'=>$aj->id_arsip_jenis, '(id_arsip_konten IN ('.implode(',', $where_ar_kt_role).'))'=>null),
			            'select'=>'ifnull(count(id_arsip_jenis), 0) jml_konten'))->row()->jml_konten;

					// cari butir arsip
					$jml_arsip = $this->general_model->datagrabs(array(
			            'tabel'=>array(
			            	'arsip_konten_set a'=>'',
			            	'arsip_konten b'=>'b.id_arsip_konten=a.id_arsip_konten',
			            	'arsip c'=>'c.id_arsip=a.id_arsip',
			            	),
			            'where'=>array('b.id_arsip_jenis'=>$aj->id_arsip_jenis, '(b.id_arsip_konten IN ('.implode(',', $where_ar_kt_role).'))'=>null),
			            'select'=>'ifnull(count(a.id_arsip), 0) jml_arsip'))->row()->jml_arsip;
				//	cek($this->db->last_query());
					// ./cari butir arsip

					$folder_aktif = ($aj->id_arsip_jenis == $id_jenis) ? '#00c0ef' : '#00c0ef';
					$bg_col = ($aj->id_arsip_jenis == $id_jenis) ? 'bg-active link-normal':null;
					$aj_aktif = $id_jenis;

				  	// data
					$rows = array();

					$kode = '<b>'.$aj->kode_arsip_jenis.'</b>';
					$nama = $aj->arsip_jenis;

					$sub_jenis = $this->general_model->datagrabs(array(
					'tabel' => array(
						'ref_arsip_jenis j' => '',
						'ref_arsip_jenis_role k' => 'k.id_arsip_jenis = j.id_arsip_jenis'),
					'select' => 'j.*',
					'where'=>array('id_par'=>$aj->id_arsip_jenis, 'k.id_bidang IN ('.implode(',', $where_bidang).')'=>null),
					));
					// cek($this->db->last_query());

					$btn = '<li>'.anchor('filing/pengarsipan/list_data/'.in_de($aj->id_arsip_jenis), $aj->arsip_jenis,'style="font-size: 0.95em;"').'</li>';
					$rows[] = array('data'=>++$no, 'width'=>'5px');
					$rows[] = array('data'=>'<i class="fa fa-folder" style="color:'.$folder_aktif.';"></i>', 'width'=>'5px');
					$rows[] = array('data'=>$btn, 'colspan'=>7, 'class'=>$bg_col);
					$rows[] = array('data'=>'<span class="badge bg-blue text-center">'.$jml_konten.'</span>', 'class'=>'text-center');
					$rows[] = array('data'=>'<span class="badge bg-green text-center">'.$jml_arsip.'</span>', 'class'=>'text-center');
					//++ $this->jm_jenis;
					$num;
					$this->jm_jenis +=  $num;
					$this->jm_konten += $jml_konten;
					$this->jm_butir += $jml_arsip;

					$this->table->add_row($rows);

					if ($sub_jenis->num_rows() > 0){
					$this->tabel_sub(2, $aj->id_arsip_jenis, $aj_aktif, $where_ar_kt_role, $where_bidang);
					$r += 1;
				}
					
				}

				$this->table->add_row(
					array('data'=>'<b>TOTAL : </b>', 'colspan'=>2, 'class'=>'label-default', 'style'=>'white-space:nowrap;'),
					array('data'=>'<b>'.$this->jm_jenis.'</b>', 'colspan'=>7, 'class'=>'text-center label-default'),
					array('data'=>'<b>'.$this->jm_konten.'</b>', 'class'=>'text-center label-default'),
					array('data'=>'<b>'.$this->jm_butir.'</b>', 'class'=>'text-center label-default')
					);

				
				$table_jenis = $this->table->generate();


			}else{
				$table_jenis = 'Tidak/belum ada data Jenis Arsip';
				$this->jm_butir = '0';
				$this->jm_konten = '0';
				$this->jm_jenis = '0';
			}

			$data['butir_arsip'] = $this->jm_butir;	
			$data['konten_arsip'] = $this->jm_konten;	
			$data['jenis_arsip'] = $this->jm_jenis;	

			// Statistik Tambahan: Surat Masuk (M), Surat Keluar (K), Disposisi, Peminjaman, Retensi, Grafik 6 Bulan
			$q_mk = $this->db->query("SELECT
				SUM(CASE WHEN jenis_surat = 'M' OR jenis_surat IS NULL THEN 1 ELSE 0 END) AS jml_masuk,
				SUM(CASE WHEN jenis_surat = 'K' THEN 1 ELSE 0 END) AS jml_keluar,
				SUM(CASE WHEN status_arsip IN ('Inaktif','Retensi') THEN 1 ELSE 0 END) AS jml_retensi
				FROM arsip_konten")->row();
			$data['jml_surat_masuk'] = ($q_mk && $q_mk->jml_masuk) ? (int) $q_mk->jml_masuk : 0;
			$data['jml_surat_keluar'] = ($q_mk && $q_mk->jml_keluar) ? (int) $q_mk->jml_keluar : 0;
			$data['jml_retensi'] = ($q_mk && $q_mk->jml_retensi) ? (int) $q_mk->jml_retensi : 0;

			$data['jml_disposisi'] = 0;
			$data['jml_disposisi_menunggu'] = 0;
			$data['recent_disposisi'] = array();
			if ($this->db->table_exists('arsip_disposisi')) {
				$qd = $this->db->query("SELECT COUNT(*) AS total, SUM(CASE WHEN status_tl='Menunggu' THEN 1 ELSE 0 END) AS menunggu FROM arsip_disposisi")->row();
				if ($qd) {
					$data['jml_disposisi'] = (int) $qd->total;
					$data['jml_disposisi_menunggu'] = (int) $qd->menunggu;
				}
				$data['recent_disposisi'] = $this->db->query("SELECT d.*, k.no_surat, k.judul, b.nama_bidang
					FROM arsip_disposisi d
					LEFT JOIN arsip_konten k ON k.id_arsip_konten = d.id_arsip_konten
					LEFT JOIN ref_bidang b ON b.id_bidang = d.tujuan_bidang
					ORDER BY d.id_disposisi DESC LIMIT 5")->result();
			}

			$data['jml_peminjaman'] = 0;
			$data['jml_peminjaman_menunggu'] = 0;
			$data['recent_peminjaman'] = array();
			if ($this->db->table_exists('arsip_peminjaman')) {
				$qp = $this->db->query("SELECT COUNT(*) AS total, SUM(CASE WHEN status_pinjam='Menunggu' THEN 1 ELSE 0 END) AS menunggu FROM arsip_peminjaman")->row();
				if ($qp) {
					$data['jml_peminjaman'] = (int) $qp->total;
					$data['jml_peminjaman_menunggu'] = (int) $qp->menunggu;
				}
				$data['recent_peminjaman'] = $this->db->query("SELECT p.*, k.no_surat, k.judul
					FROM arsip_peminjaman p
					LEFT JOIN arsip_konten k ON k.id_arsip_konten = p.id_arsip_konten
					ORDER BY p.id_peminjaman DESC LIMIT 5")->result();
			}

			// Data Grafik Bulanan (6 Bulan Terakhir)
			$bulan_labels = array();
			$bulan_masuk = array();
			$bulan_keluar = array();
			$nama_bln = array('01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'Mei','06'=>'Jun','07'=>'Jul','08'=>'Agu','09'=>'Sep','10'=>'Okt','11'=>'Nov','12'=>'Des');
			for ($i = 5; $i >= 0; $i--) {
				$ym = date('Y-m', strtotime("-{$i} months"));
				$m = substr($ym, 5, 2);
				$y = substr($ym, 0, 4);
				$bulan_labels[] = $nama_bln[$m].' '.$y;
				$qb = $this->db->query("SELECT
					SUM(CASE WHEN jenis_surat='M' OR jenis_surat IS NULL THEN 1 ELSE 0 END) AS m,
					SUM(CASE WHEN jenis_surat='K' THEN 1 ELSE 0 END) AS k
					FROM arsip_konten WHERE DATE_FORMAT(tanggal, '%Y-%m') = ?", array($ym))->row();
				$bulan_masuk[] = ($qb && $qb->m) ? (int) $qb->m : 0;
				$bulan_keluar[] = ($qb && $qb->k) ? (int) $qb->k : 0;
			}
			$data['chart_labels'] = $bulan_labels;
			$data['chart_masuk'] = $bulan_masuk;
			$data['chart_keluar'] = $bulan_keluar;

			// Persetujuan Registrasi Akun Baru & Statistik Tambahan (untuk 10 kotak Beranda)
			$id_peg_login = $this->session->userdata('id_pegawai');
			$is_superadmin = ($this->session->userdata('login_state') === 'root' || $this->general_model->check_role($id_peg_login, 'OPRSA'));
			$data['is_superadmin'] = $is_superadmin;

			$qa = $this->db->query("SELECT
				COUNT(*) AS total_akun,
				SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) AS aktif,
				SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) AS menunggu,
				SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) AS ditolak
				FROM peg_pegawai")->row();
			$data['jml_akun_total'] = ($qa && $qa->total_akun) ? (int) $qa->total_akun : 0;
			$data['jml_akun_aktif'] = ($qa && $qa->aktif) ? (int) $qa->aktif : 0;
			$data['jml_akun_menunggu'] = ($qa && $qa->menunggu) ? (int) $qa->menunggu : 0;
			$data['jml_akun_ditolak'] = ($qa && $qa->ditolak) ? (int) $qa->ditolak : 0;

			$data['jml_audit_log'] = 0;
			if ($this->db->table_exists('arsip_audit_log')) {
				$qaud = $this->db->query("SELECT COUNT(*) AS jml FROM arsip_audit_log")->row();
				if ($qaud) $data['jml_audit_log'] = (int) $qaud->jml;
			}
			$can_audit = $is_superadmin;
			if (!$can_audit && (int) $id_peg_login > 0) {
				$qca = $this->db->query(
					"SELECT COUNT(*) AS jml FROM pegawai_role pr INNER JOIN ref_role_nav rn ON rn.id_role = pr.id_role WHERE pr.id_pegawai = ? AND rn.id_nav = 1861",
					array((int) $id_peg_login)
				)->row();
				$can_audit = ($qca && (int) $qca->jml > 0);
			}
			$data['can_audit'] = $can_audit;

			$qunit = $this->db->query("SELECT COUNT(*) AS jml FROM ref_unit")->row();
			$qbid = $this->db->query("SELECT COUNT(*) AS jml FROM ref_bidang")->row();
			$data['jml_unit'] = ($qunit && $qunit->jml) ? (int) $qunit->jml : 0;
			$data['jml_bidang'] = ($qbid && $qbid->jml) ? (int) $qbid->jml : 0;

			$data['pending_accounts'] = array();
			$data['filing_roles'] = array();

			if ($is_superadmin) {
				$data['pending_accounts'] = $this->db->query("SELECT
						p.id_pegawai, p.nama, p.nip, p.username, p.email, p.reg_created_at, p.reg_role_usulan,
						u.unit, b.nama_bidang, ru.nama_role AS role_usulan_nama
					FROM peg_pegawai p
					LEFT JOIN peg_jabatan j ON j.id_pegawai = p.id_pegawai AND j.status = 1
					LEFT JOIN ref_bidang b ON b.id_bidang = j.id_bidang
					LEFT JOIN ref_unit u ON u.id_unit = b.id_unit
					LEFT JOIN ref_role ru ON ru.id_role = p.reg_role_usulan
					WHERE p.status = 0
					ORDER BY COALESCE(p.reg_created_at, '1970-01-01 00:00:00') DESC, p.id_pegawai DESC
					LIMIT 10")->result();
				$data['filing_roles'] = $this->db->query(
					"SELECT r.id_role, r.nama_role
					 FROM ref_role r
					 LEFT JOIN ref_aplikasi a ON a.id_aplikasi = r.id_aplikasi
					 WHERE a.folder = ?
					 ORDER BY FIELD(LOWER(r.nama_role), 'operator', 'admin', 'pimpinan', 'super admin'), r.id_role ASC",
					array('filing')
				)->result();
			}

			$data['title'] = 'Beranda &amp; Statistik Arsip';
		
			$data['tabel_statistik'] = '<ul class="direktori">'.$table_jenis.'</ul>';	
			$data['content'] = 'filing/dashboard_view';
			$this->load->view('home', $data);
	}

	function tabel_sub($level,$id, $arsip_aktif=null, $where_ar_kt_role=array(0), $where_bidang=array(0)) {


		
		$from = array('ref_arsip_jenis j' => '',
						'ref_arsip_jenis_role k' => 'k.id_arsip_jenis = j.id_arsip_jenis');


		
		$sub_jenis = $this->general_model->datagrabs(array(
			'tabel' => $from,
			'where' => array('j.id_par' => $id, 'k.id_bidang IN ('.implode(',', $where_bidang).')'=>null),
			'select' => 'j.*',
			'group_by' => 'j.id_arsip_jenis',
			'order' => 'j.urut, j.arsip_jenis',

		));

		$awal_sub = array();
		
		if ($sub_jenis->num_rows() > 0) {
			foreach ($sub_jenis->result() as $row) {
				$awal_sub[] = array($row->id_arsip_jenis,$row->urut);
			}
			$no = 0;
			$num = 1;
			$r = 0;
			foreach($sub_jenis->result() as $row) {
				$this->tampug_aj[] = $row->id_arsip_jenis;
				$jml_konten = $this->general_model->datagrabs(array(
			            'tabel'=>'arsip_konten',
			            'where'=>array('id_arsip_jenis'=>$row->id_arsip_jenis, '(id_arsip_konten IN ('.implode(',', $where_ar_kt_role).'))'=>null),
			            'select'=>'ifnull(count(id_arsip_jenis), 0) jml_konten'))->row()->jml_konten;

				// cari butir arsip
					$jml_arsip = $this->general_model->datagrabs(array(
			            'tabel'=>array(
			            	'arsip_konten_set a'=>'',
			            	'arsip_konten b'=>'b.id_arsip_konten=a.id_arsip_konten',
			            	'arsip c'=>'c.id_arsip=a.id_arsip',
			            	),
			            'where'=>array('b.id_arsip_jenis'=>$row->id_arsip_jenis, '(b.id_arsip_konten IN ('.implode(',', $where_ar_kt_role).'))'=>null),
			            'select'=>'ifnull(count(a.id_arsip), 0) jml_arsip'))->row()->jml_arsip;
				//	cek($this->db->last_query());
					// ./cari butir arsip

				$folder_aktif = ($row->id_arsip_jenis == $arsip_aktif) ? '#00c0ef' : '#00c0ef';

				$cell = array();
			
				for ($j=0;$j < $level; $j++) {
					if ($j>0) $cell[] = array('data'=>'','width' => '20');
				}
				
				
				
				$sub_jenis2 = $this->general_model->datagrabs(array(
					'tabel' => $from, 
					'where' => array('id_par' => $row->id_arsip_jenis, 'k.id_bidang IN ('.implode(',', $where_bidang).')'=>null)));

			

				$act = ($row->id_arsip_jenis == $arsip_aktif) ? 'class="active"':null;
				$bg_col = ($row->id_arsip_jenis == $arsip_aktif) ? 'bg-active link-normal':null;
				$font = ($row->id_arsip_jenis == $arsip_aktif) ? 'font-weight: bold;':null;
				$p = ($row->id_arsip_jenis == $arsip_aktif) ? 'label-primary':'label-default';
				$se = in_de(array(
					  		'arsip_judul' => $row->arsip_jenis,
					  		'id_arsip_jenis' => $row->id_arsip_jenis
				  		));
				  		
				  		$jml = null;

				  		
				  		$btn =  '<li '.$act.'>'.anchor('filing/pengarsipan/list_data/'.
				  			in_de($row->id_arsip_jenis),$row->arsip_jenis,'style="font-size: 0.95em; '.$font.'"').'</li>';
				$cell[] = array('data'=>++$no, 'width'=>'5px');
				$cell[] = array('data'=>'<i class="fa fa-folder" style="color:'.$folder_aktif.';"></i>', 'style'=>'width:5px');
				$cell[] = array('data'=>$btn, 'colspan' => 8-$level, 'class'=>$bg_col);
				$cell[] = array('data'=>'<span class="badge bg-blue text-center">'.$jml_konten.'</span>', 'class'=>'text-center');
				$cell[] = array('data'=>'<span class="badge bg-green text-center">'.$jml_arsip.'</span>', 'class'=>'text-center');
				$cell = array_merge_recursive($cell);

				$num;
				$this->jm_jenis += $num;
				$this->jm_konten += $jml_konten;
				$this->jm_butir += $jml_arsip;
				$this->table->add_row($cell);
				if ($sub_jenis2->num_rows() > 0) $this->tabel_sub($level+1,$row->id_arsip_jenis, $arsip_aktif, $where_ar_kt_role, $where_bidang);
				$r+=1;
			}
		}
		
	}

	
	
}
