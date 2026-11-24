<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	function __construct() {
	
		parent::__construct();
		login_check($this->session->userdata('login_state'));
		
	}

	public function index() {

		$this->home();
		
	}

	function statistik($tabel) {
		
		$total = $this->general_model->datagrab(array('tabel' => $tabel,'select' => 'count(*) as jumlah'))->row();
		return $total->jumlah;
	}
	
	function home() {
	
		$data['title'] = 'Dasbor Referensi';
	
		$data['content'] = "referensi/dashboard_view";
		$data['kewenangan'] = $this->statistik('ref_role');
		$data['operator'] = $this->statistik('peg_pegawai');
		$data['penetap'] = $this->statistik('ref_penetap');
		//$data['kop'] = $this->statistik('ref_kop');
		$data['propinsi'] = $this->statistik('ref_propinsi');
		$data['kabupaten'] = $this->statistik('ref_kabupaten');
		$data['kecamatan'] = $this->statistik('ref_kecamatan');
		$data['kelurahan'] = $this->statistik('ref_kelurahan');
		//$data['suku'] = $this->statistik('ref_suku');
		$data['kementerian'] = $this->statistik('ref_kementerian');
		$data['unit'] = $this->statistik('ref_unit');
		$data['bidang'] = $this->statistik('ref_bidang');
		$data['jkel'] = $this->statistik('ref_jenis_kelamin');
		$data['gol'] = $this->statistik('ref_gol_darah');
		$data['golru'] = $this->statistik('ref_golru');
		$data['jabatan'] = $this->statistik('ref_jabatan');
		//$data['kel'] = $this->statistik('ref_keluarga');
		//$data['status_anak'] = $this->statistik('ref_status_anak');
		//$data['status_kel'] = $this->statistik('ref_status_keluarga');
		//$data['pekerjaan'] = $this->statistik('ref_pekerjaan');
		
		$this->load->view('home', $data);
	}
	
    function unde($data) {
       
        cek(un_de($data));
        
    }
    function inde($data) {
        cek(in_de($data));
    }
	
}
