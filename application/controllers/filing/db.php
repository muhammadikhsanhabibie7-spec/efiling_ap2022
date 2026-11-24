<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Db extends CI_Controller {

	var $init = array(
			'judul' => 'e-Filing',
			'deskripsi' => 'Sistem Informasi Manajemen File Elektronik',
			'folder' => 'filing',
			'warna' => '#C59FD4',
			'kode' => 'FIL'
	);

	function __construct() {
	
		parent::__construct();
		
	}

	public function index() {
	
		$this->set_db();
		
	}
	
	function init() {
	
		return $this->init;
		
	}

	function set_db($n = null) {
				
		$app = array(
			null,
			$this->init['kode'],
			$this->init['judul'],
			$this->init['deskripsi'],
			$this->init['folder'],
			$this->init['warna']
		);
			
	
		/* 
			Keterangan 
			
			Indeks Array pada $nav : 	
				0 ID Aplikasi
				1 1:modul, 2:Referensi
				2 Kode khusus kewenangan
				4 Judul menu/navigasi
				5 Judul menu induknya
				6 Link khusus menu/navigasi
				7 Icon (Fontawesome)
				
		*/
		
		$nav = array(
			array(null,1,'OPRTT',1,'Pengguna Non Ubah Akun',null,null,null),
			array(null,1,null,2,'Pengaturan',null,null,'cog'),
			array(null,1,null,2,'Kewenangan','Pengaturan','filing/kewenangan','shield'),
			array(null,1,null,2,'Operator','Pengaturan','filing/operator','user'),
		
			array(null,1,'OPRSA',1,'Super Admin','Operator',null,null),			
			array(null,1,null,2,'Referensi',null,null,'cube'),
			array(null,1,null,2,'Jenis Arsip','Referensi','filing/jenis_arsip','cog'),
			array(null,1,null,2,'Ekstensi Unggah','Referensi','filing/referensi/ke/ekstensi','file-text-o'),
			array(null,1,null,2,'Unit Kerja(Pusat/Cabang)','Referensi','filing/unit','university'),
			array(null,1,null,2,'Bidang','Referensi','filing/bidang','university'),
			
			array(null,1,null,2,'Pengarsipan',null,'filing/pengarsipan','database'),
			// array(null,1,'ADMFI',1,'Administrator','Pengarsipan',null,null),		
			array(null,1,'ARSAD',1,'Tambah Konten','Pengarsipan',null,null),			
			array(null,1,'ARSED',1,'Edit Konten','Pengarsipan',null,null),			
			array(null,1,'ARSDE',1,'Hapus Konten','Pengarsipan',null,null),			
			array(null,1,'FILDE',1,'Hapus Arsip','Pengarsipan',null,null),			
			array(null,1,'FILUP',1,'Upload Arsip','Pengarsipan',null,null),	
			array(null,1,'ASRSR',1,'Seting Kewenangan Konten Arsip','Pengarsipan',null,null),	
			// laporan		
			array(null,1,null,2,'Laporan',null,null,'file-text-o'),
			array(null,1,null,2,'Statistik Arsip','Laporan','filing/statistik','file-text'),
			array(null,1,null,2,'Laporan Pengarsipan','Laporan','filing/laporan','file-text'),
			array(null,1,null,2,'Aktivitas Pengguna (Logs)','Laporan','filing/aktivitas_pengguna','bar-chart-o')
		);
		
		/* 
			Keterangan 
			
			Indeks Array pada $tabel : 	
				'tabel' => 
					('kolom','tipe kolom','panjang tipe kolom', null/not null : TRUE/FALSE, 1:primary key)
				
			Contoh :
				'pegawai' =>
					('id_pegawai','int',11',FALSE,1),
					('nama','varchar',255,TRUE);
				
		*/

		$tabel = array(
			'arsip' => array(
				array('id_arsip','int',11,FALSE,1),
				array('id_par_arsip','int',11,TRUE),
				array('id_arsip_jenis','int',11,TRUE),
				array('op','int',11,TRUE),
				array('arsip','varchar',255,TRUE),
				array('tanggal','datetime',null,TRUE),
				array('lokasi','varchar',255,TRUE)),
			'arsip_akses' => array(
				array('id_akses','int',11,FALSE,1),
				array('id_arsip_jenis','int',11,TRUE),
				array('id_unit','int',11,FALSE),
				array('id_bidang','int',11,TRUE)),
			'arsip_kolom' => array(
				array('id_kolom','int',11,FALSE,1),
				array('id_arsip_konten','int',11,TRUE),
				array('id_arsip_kolom','int',11,TRUE),
				array('konten','text',null,TRUE)),
			'arsip_konten' => array(
				array('id_arsip_konten','int',11,FALSE,1),
				array('id_arsip_jenis','int',11,TRUE),
				array('judul','varchar',255,TRUE),
				array('penerima','varchar',100,TRUE),
				array('no_surat','varchar',50,TRUE),	
				array('tanggal','date',null,TRUE),
				array('tanggal_unggah','date',null,TRUE),
				array('op','int',11,TRUE),
				array('tipe_arsip_konten','int',1,TRUE),
				array('op_bak','varchar',64)),
			'arsip_konten_set' => array(
				array('id_arsip_set','int',11,FALSE,1),
				array('id_arsip_konten','int',11,TRUE),
				array('id_arsip','int',11,TRUE)),		
			'ref_arsip_ekstensi' => array(
				array('id_ekstensi','int',11,FALSE,1),
				array('ekstensi','varchar',10,TRUE),
				array('keterangan','varchar',128,TRUE)),
			'ref_arsip_jenis' => array(
				array('id_arsip_jenis','int',11,FALSE,1),
				array('id_par','int',11,TRUE),
				array('urut','int',1,TRUE),
				array('id_aplikasi','int',1,TRUE),
				array('kode_arsip_jenis','int',15,TRUE),
				array('arsip_jenis','varchar',255,TRUE),
				array('direktori','varchar',64,TRUE),
				array('akses_manual','int',1,TRUE),
				array('id_operator','int',11,TRUE)
				),
			// jenis arsip role
			'ref_arsip_jenis_role'=>array(
				array('id_arsip_jenis_role', 'int', 11, FALSE, 1),
				array('id_bidang', 'int', 11, TRUE),
				array('id_arsip_jenis', 'int', 11, TRUE)
				),
			// ./jenis arsip role

			// arsip konten role
			'arsip_konten_role'=>array(
				array('id', 'int', 11, FALSE, 1),
				array('id_arsip_konten', 'int', 11, TRUE),
				array('id_bidang', 'int', 11, TRUE),
				array('id_operator', 'int', 11, TRUE)
				),
			// ./arsip konten role
			
			'ref_arsip_kolom' => array(
				array('id_arsip_kolom','int',11,FALSE,1),
				array('id_arsip_jenis','int',11,TRUE),
				array('kolom','varchar',128,TRUE),
				array('tipe','int',1,TRUE)),

			'ref_unit' => array(
				array('id_unit','int',11,FALSE,1),
				array('id_par_unit','int',11,TRUE),
				array('kode_unit','varchar',20,TRUE),	
				array('unit','varchar',80,FALSE),	
				array('alamat','varchar',255,TRUE),	
				array('telp','varchar',12,TRUE),
				array('id_kepala','int',11,TRUE),		
				array('bso_uu','text',null,TRUE),
				array('aktif','int',1,TRUE),
				array('urut','int',1,TRUE)),
			'ref_bidang' => array(
				array('id_bidang','int',11,FALSE,1),
				array('id_par_bidang','int',11,TRUE),
				array('id_unit','int',11,FALSE),
				array('penetapan','date',null,TRUE),
				array('kode_bidang','varchar',20,TRUE),
				array('nama_bidang','varchar',80,FALSE),
				array('urut','int',1,FALSE),
				array('aktif','int',1,TRUE)),

			'log_users' => array(
				array('id','int',11,FALSE,1),
				array('mod_date','timestamp',null,FALSE,'CURRENT_TIMESTAMP'),
				array('id_operator','int',11,FALSE),
				array('id_modul','int',11,FALSE),
				array('act','int',1,FALSE),
				array('field','text',null,FALSE),
				array('field_alias','text',null,FALSE),
				array('data','text',null,FALSE)
				)

			);
		
		$odie = load_controller('inti','builder','process_db',$app,$tabel,$nav);
		
		if (!empty($n)) {
			$this->session->set_flashdata('ok','Aplikasi berhasil dipasang ...');
			redirect('inti/aplikasi');
		} else {
			die($odie);	
		}
	}
}

