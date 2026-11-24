<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kewenangan_jenisarsip extends CI_Controller {


	/* 
	 * Keterangan Variabel Global
	 * $app
	 *		1 = Aplikasi Tunggal
	 *		0 = Seluruh Aplikasi
	 * $dir	
	 		Pengguna Aplikasi / Folder Aplikasi
	 */

	var $app = 1; 
	var $dir = 'filing';

	function __construct() {
	
		parent::__construct();
		login_check($this->session->userdata('login_state'));
		
		$app_std = $this->general_model->datagrabs(array(
			'tabel' => 'ref_aplikasi','where' => array('folder' => $this->uri->segment(1))
		))->row();
		$this->in_app = !empty($app_std->nama_aplikasi) ? $app_std->nama_aplikasi : 'Root';
	}

	public function index() {
		$this->list_kewenangan();
	}
	
	
	function get_app() {
		
		$where = ($this->app == 1) ? 
			array('folder' => $this->dir) :
			array('id_aplikasi IN ('.$this->general_model->get_param('app_active').')' => null);
		return $this->general_model->datagrabs(array(
			'tabel' => 'ref_aplikasi','where' => $where));

	}

	function list_kewenangan($offset = null) {
	
		$data['breadcrumb'] = array('' => $this->in_app, $this->dir.'/kewenangan' => 'Kewenangan');
		
		$offset = !empty($offset) ? $offset : null;
		
		$from = array('ref_role r' => '','ref_aplikasi a' => 'a.id_aplikasi = r.id_aplikasi');
		$where = ($this->app == 1) ? 
			array('a.folder' => $this->dir) : 
			array('a.id_aplikasi IN ('.$this->general_model->get_param('app_active').')' => null);
		$config['base_url']		= site_url($this->dir.'/kewenangan_jenisarsip/list_kewenangan');
		$config['total_rows']	= $this->general_model->datagrab(array('tabel' => $from,'where' => $where,'select' => 'count(r.id_role) as jml'))->row()->jml;
		$config['per_page']		= '10';
		$config['uri_segment']	= '4';
		
		$this->pagination->initialize($config);
		
		$data['total']	= $config['total_rows'];
		$data['links']	= $this->pagination->create_links();
		
		$dtkewenangan	= $this->general_model->datagrab(array('tabel' => $from,'where' => $where,'limit' => $config['per_page'], 'offset' => $offset, 'order'=>'id_role'));
		if ($dtkewenangan->num_rows() != 0) {
		
			$this->table->set_template(array('table_open'=>'<table class="table table-striped table-bordered table-condensed table-nonfluid">'));
			
			$heads = array('No','Kewenangan');
			if ($this->app > 1) $heads[] = 'Aplikasi';
			$heads[] = array('data' => 'Aksi','colspan' => 3);
			$this->table->set_heading($heads);
			
			$no = 1+$offset;
			// $opt_set = '
			// 	msg="Apakah ingin pasang kewenangan ke seluruh operator?"
			// 	t_icn="fa fa-sign-in"
			// 	t_text="Pasang Kewenangan"
			// 	title="Pasang Kewenangan ke seluruh operator ..."';
			
			// $opt_unset = '
			// 	msg="Apakah ingin cabut kewenangan seluruh operator?" 
			// 	t_icn="fa fa-sign-out"
			// 	t_text="Cabut Kewenangan"
			// 	title="Cabut Kewenangan seluruh operator ..."';
			
			foreach($dtkewenangan->result() as $row){
				$link1 = anchor('#','<i class="fa fa-shield fa-2x"></i>','class="btn btn-flat btn-md btn-edit" act="'.site_url($this->dir.'/kewenangan_jenisarsip/add_kewenangan/'.$row->id_role).'"');
				
				$rows = array(
					array('data'=>$no,'style'=>'text-align:center'),
					'<b>'.$row->nama_role.'<b>');

				if ($this->app > 1) $rows[] = '<b>'.$row->nama_aplikasi.'</b>';
				
				$rows[] = $link1;
				// $rows[] = $link2;
				//$rows[] = $conf;
				
				$this->table->add_row($rows);
				$no++;
			}
			$tabel = $this->table->generate();
		}else{
			$tabel = '<div class="alert alert-info">Belum ada data kewenangan</div>';
		}
		
		// $data['tombol'] = anchor('#','<i class="fa fa-plus-square"></i> &nbsp; Tambah Kewenangan','class="btn-edit btn btn-success" act="'.site_url($this->dir.'/kewenangan_jenisarsip/add_kewenangan').'"');
		$data['tabel']	= $tabel;
		$data['title'] 	= 'Kewenangan';
		$data['content'] = "umum/standard_view";
		$this->load->view('home', $data);
	}
	
	function set_kewenangan($par) {
		
		$o = un_de($par);
		
		if ($o['set'] == 1) {
			
			$peg = $this->general_model->datagrabs(array('tabel' => 'peg_pegawai p'));
			
			foreach($peg->result() as $p) {
				$c = $this->general_model->datagrabs(array('tabel' => 'pegawai_role','where' => array('id_pegawai' => $p->id_pegawai,'id_role' => $o['id']),'select' => 'count(id_pegawai) as jml'))->row();
				if ($c->jml == 0) $this->general_model->save_data('pegawai_role',array('id_pegawai' => $p->id_pegawai,'id_role' => $o['id']));
			}
			
		} else {
			$this->general_model->delete_data('pegawai_role','id_role',$o['id']);
		}

		$txt = ($o['set'] == 1) ? 'Set Kewenangan seluruh pegawai berhasil dilakukan ...' : 'Pencabutan Kewenangan seluruh pegawai berhasil dilakukan ...';
		$this->session->set_flashdata('ok',$txt);
		redirect($this->dir.'/kewenangan');
	}
	
	function role_sub($app,$id = null) {
		
		$where_is = (!empty($id)) ? 'id_par = '.$id : 'id_par IS NULL';
		
		$dat = $this->general_model->datagrabs(array(
			'tabel' => 'ref_arsip_jenis',
			'where' => array($where_is=>null),
			'order' => 'urut'
		)); 
		// cek($this->db->last_query());
		return $dat;
		
	}
	
	function get_codes($id) {
		
		$c = array();
		$dat =	$this->general_model->datagrabs(array(
			'tabel' => 'ref_arsip_jenis_role',
			'where' => array('id_role' => $id)
		)); 
		// cek($this->db->last_query());
		foreach($dat->result() as $d) {
			
			$c[] = $d->id_arsip_jenis;
			
		}
		
		return $c;
		
		
	}
	
	function add_kewenangan($id_role = null){
		
		$app_ac = $this->general_model->get_param('app_active');

		$data['breadcrumb'] = array('' => $this->in_app, $this->dir.'/kewenangan_jenisarsip/add_kewenangan/'.$id_role => 'Kewenangan Jenis Arsip');
		
		$where = ($this->app == 1) ? 
			array('folder' => $this->dir) : 
			array('id_aplikasi IN ('.$app_ac.')' => null,'id_par_aplikasi' => NULL);

		$data['aplikasi'] = $this->general_model->datagrabs(array('tabel' => 'ref_aplikasi','where' => $where));

		$data['combo_aplikasi'] = ($data['aplikasi']->num_rows() > 1) ? $this->general_model->combo_box(array('tabel' => 'ref_aplikasi','key' => 'id_aplikasi','val' => array('nama_aplikasi'),'where' => $where,'order' => 'urut')) : null;
				
		$codes = $this->get_codes($id_role);
		// cek($codes);
		$app = $this->get_app();
		$app_data = array();
		$nav = array();
		
		foreach($app->result() as $ap) {
		
			$data_nav = null;
			
				
			$n1 = $this->role_sub($ap->id_aplikasi);

			foreach($n1->result() as $ne1) {
				// cek($ne1->id_arsip_jenis);
				$check_sub = (!empty($codes) and in_array($ne1->id_arsip_jenis,$codes)) ? 'checked' : null;

				$cek_anak = $this->general_model->datagrabs(array(
					'tabel'=>'ref_arsip_jenis',
					'where'=>array('id_arsip_jenis IN (select id_arsip_jenis from ref_arsip_jenis where id_par='.$ne1->id_arsip_jenis.')'=>null)));
				$par_btn = ($cek_anak->num_rows() > 0) ? 'btn-primary' : 'btn-default disabled';
				$par_stat = ($cek_anak->num_rows() > 0) ? 1 : null;

				$data_nav .='<li  style="margin: 5px 0 5px 0">
				<input '.$check_sub.' type="checkbox" class=" code_'.$ne1->id_arsip_jenis.'" name="code[]" value="'.$ne1->id_arsip_jenis.'" '.@$check_sub.' style="margin: -2px 0 0 15px">';
				if ($par_stat == 1) {
					$data_nav .='&nbsp;<button type="button" class="tree-toggle nav-header btn btn-xs '.$par_btn.'" >'.$ne1->arsip_jenis.'</button>';
				}else{
					$data_nav .='&nbsp;<button type="button" class="tree-toggle nav-header btn btn-xs '.$par_btn.'" ><b>'.$ne1->arsip_jenis.'</b></button>';
				}
				

                $data_nav .= '<ul class="nav nav-list tree">';

					$data_nav .= $this->sub_jenis($ne1->id_arsip_jenis, $id_role);

                $data_nav .='</ul></li><li class="divider"></li>';
				
			}
			
			$app_data[$ap->id_aplikasi] = $data_nav;
			
		}
		
		$data['link_save'] = $this->dir.'/kewenangan_jenisarsip/save_kewenangan';
		$data['app_data'] = $app_data;
		$data['title']	= !empty($id_role) ? 'Ubah':'Tambah';
		$data['def']	= !empty($id_role) ? $this->general_model->datagrabs(array('tabel' => 'ref_role','where' => array('id_role' => $id_role)))->row() : null;
		
		$this->load->view('filing/kewenangan_jenisarsip_form', $data);
	}

	function sub_jenis($id_par=null, $id_role=null){

		$app_ac = $this->general_model->get_param('app_active');

		$data['breadcrumb'] = array('' => $this->in_app, $this->dir.'/kewenangan_jenisarsip/add_kewenangan/'.$id_role => 'Kewenangan Jenis Arsip');
		
		$where = ($this->app == 1) ? 
			array('folder' => $this->dir) : 
			array('id_aplikasi IN ('.$app_ac.')' => null,'id_par_aplikasi' => NULL);

		$data['aplikasi'] = $this->general_model->datagrabs(array('tabel' => 'ref_aplikasi','where' => $where));

		$data['combo_aplikasi'] = ($data['aplikasi']->num_rows() > 1) ? $this->general_model->combo_box(array('tabel' => 'ref_aplikasi','key' => 'id_aplikasi','val' => array('nama_aplikasi'),'where' => $where,'order' => 'urut')) : null;
				
		$codes = $this->get_codes($id_role);
		$app = $this->get_app();
		$app_data = array();
		$nav = array();

		

			$n2 = $this->general_model->datagrabs(array(
					'tabel'=>'ref_arsip_jenis',
					'where'=>array('id_par'=>$id_par)));

					//cek($this->db->last_query());
			$data_nav = '';
					foreach ($n2->result() as $ne1) {

						$check_sub = (!empty($codes) and in_array($ne1->id_arsip_jenis,$codes)) ? 'checked' : null;

						$cek_anak = $this->general_model->datagrabs(array(
							'tabel'=>'ref_arsip_jenis',
							'where'=>array('id_arsip_jenis IN (select id_arsip_jenis from ref_arsip_jenis where id_par='.$ne1->id_arsip_jenis.')'=>null)));
						$par_btn = ($cek_anak->num_rows() > 0) ? 'btn-primary' : 'btn-default disabled';
						$par_stat = ($cek_anak->num_rows() > 0) ? 1 : null;

						$data_nav .='<li style="margin: 5px 0 5px 20px">
						<input '.$check_sub.' type="checkbox" class=" sub_code_'.$ne1->id_arsip_jenis.'" name="code[]" value="'.$ne1->id_arsip_jenis.'" '.@$check_sub.' style="margin: -2px 0 0 15px">';
						if ($par_stat == 1) {
							$data_nav .='&nbsp;<button type="button" class="tree-toggle nav-header btn btn-xs '.$par_btn.'" >'.$ne1->arsip_jenis.'</button>';
						}else{
							$data_nav .='&nbsp;<button type="button" class="tree-toggle nav-header btn btn-xs '.$par_btn.'" ><b>'.$ne1->arsip_jenis.'</b></button>';
						}
						

		                $data_nav .= '<ul class="nav nav-list tree">';

							$data_nav .= $this->sub_jenis($ne1->id_arsip_jenis, $id_role);

		                $data_nav .='</ul></li><li class="divider"></li>';
					}
			
		return $data_nav;
		
	}
	
	function save_kewenangan() {

		$id_role = $this->input->post('id_role');
		$code = $this->input->post('code');


		if (!empty($id_role)) {
			
			$this->general_model->delete_data('ref_arsip_jenis_role','id_role',$id_role);
			$id = $id_role;
			if (!empty($code)) {
				for ($i = 0; $i < count($code); $i++) {
			
				$this->general_model->save_data('ref_arsip_jenis_role',array('id_role' => $id,'id_arsip_jenis' => $code[$i]));
			}
					
		}
		
		
		$this->session->set_flashdata('ok','Data kewenangan Jenis Arsip berhasil disimpan...');
			
		}else{
		$this->session->set_flashdata('fail','Tidak dapat menyimpan kewenangan Jenis Arsip...');	
		} 

		
		
		redirect($this->dir.'/kewenangan_jenisarsip');
	}
	
	// function delete_kewenangan($id_role){
	// 	$this->general_model->delete_data('ref_role_nav','id_role',$id_role);
	// 	$this->general_model->delete_data('ref_role','id_role',$id_role);
	// 	$this->session->set_flashdata('ok','Data kewenangan berhasil dihapus...');
	// 	redirect($this->dir.'/kewenangan_jenisarsip/');
		
	// }
	
}
