<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Db extends CI_Controller {

	var $init = array(
		'judul' => 'Referensi',
		'deskripsi' => 'Sistem Informasi Manajemen Referensi',
		'folder' => 'referensi',
		'warna' => '#5BBA3C',
		'kode' => 'REF'
	);

	function __construct() {
	
		parent::__construct();
		login_check($this->session->userdata('login_state'));
		
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
		
		$tabel = array();
		$nav = array(
			array(null,2,null,2,'Khusus',null,null,'cog'),
			array(null,2,null,2,'Aplikasi','Khusus','referensi/aplikasi','shield'),
			array(null,2,null,2,'Kewenangan','Khusus','referensi/kewenangan',null),
			array(null,2,null,2,'Operator','Khusus','referensi/operator',null),
			array(null,2,null,2,'Umum',null,null,'cube'),
			array(null,2,null,2,'Personal','Umum',null,'user'),
			array(null,2,null,2,'Jenis Kelamin','Personal','referensi/umum/ke/jenis_kelamin',null),
			array(null,2,null,2,'Golongan Darah','Personal','referensi/umum/ke/golongan_darah',null),
			array(null,2,null,2,'Status Keluarga','Personal','referensi/umum/ke/keluarga_status',null),
			array(null,2,null,2,'Status Anak','Personal','referensi/umum/ke/status_anak',null),
			array(null,2,null,2,'Pekerjaan','Personal','referensi/umum/ke/pekerjaan',null),
			array(null,2,null,2,'Keluarga','Personal','referensi/umum/ke/keluarga',null),
			array(null,2,null,2,'Status Kawin','Personal','referensi/umum/ke/kawin_status',null),
			array(null,2,null,2,'Agama','Personal','referensi/umum/ke/agama',null),
			array(null,2,null,2,'Administratif','Umum',null,'file-text-o'),
			array(null,2,null,2,'Penetap','Administratif','referensi/umum/ke/penetap',null),
			array(null,2,null,2,'Kementerian','Administratif','referensi/umum/ke/kementerian',null),
			array(null,2,null,2,'Jenjang Pendidikan','Administratif','referensi/umum/ke/studi_jenjang',null),
			array(null,2,null,2,'Bentuk Pendidikan','Administratif','referensi/umum/ke/studi_bentuk',null),
			array(null,2,null,2,'Lembaga','Administratif','referensi/umum/ke/studi_lembaga',null),
			array(null,2,null,2,'Fakultas','Administratif','referensi/umum/ke/fakultas',null),
			array(null,2,null,2,'Program Studi','Administratif','referensi/umum/ke/prodi',null),
			array(null,2,null,2,'Jurusan','Administratif','referensi/umum/ke/jurusan',null),
			array(null,2,null,2,'Ujian Kompetensi','Administratif','referensi/umum/ke/kompetensi',null),
			array(null,2,null,2,'Propinsi','Administratif','referensi/umum/ke/propinsi',null),
			array(null,2,null,2,'Kabupaten','Administratif','referensi/umum/ke/kabupaten',null),
			array(null,2,null,2,'Kecamatan','Administratif','referensi/umum/ke/kecamatan',null),
			array(null,2,null,2,'Kelurahan/Desa','Administratif','referensi/umum/ke/kelurahan',null));
		
		$odie = load_controller('inti','builder','process_db',$app,$tabel,$nav);
		
		if (!empty($n)) {
			$this->session->set_flashdata('ok','Aplikasi berhasil dipasang ...');
			redirect('inti/aplikasi');
		} else {
			die($odie);	
		}
		
	}
	
	function scaff_db() {
		
		return array('prefix' => 'ref_');
		
	}
	
} ?>