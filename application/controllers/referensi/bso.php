<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Bso extends CI_Controller {

		function __construct() {
		
			parent::__construct();
			login_check($this->session->userdata('login_state'));
	
		}

		public function index() {
		
			$this->list_unit();

		}
		
		function list_unit($offset = null) {

		$total_unit =  $this->general_model->datagrab(array('tabel'=>'ref_unit'));
		
		if ($total_unit->num_rows() == 1) {
	
			redirect('referensi/bso/editor/'.in_de(array('id' => $total_unit->row()->id_unit)));
		
		} else {
		
			$data['breadcrumb'] = array('' => 'Referensi', 'referensi/bso' => 'Unit Organisasi');
			$data['tabel'] = load_controller('referensi','unit','tabel_unit',in_de(array('link' => 'referensi/bso/editor/')));
			$data['title'] = 'Daftar Unit Kerja';
			$data['content'] 	= "umum/standard_view";
			$this->load->view('home', $data);
			
		}
	}
	
	function tabel_sub($level_bidang,$id) {
		
		$from = array('ref_bidang b' => '','ref_unit u' => 'u.id_unit = b.id_unit');
		
		$sub_bid = $this->general_model->datagrab(array('tabel' => $from, 'where' => array('b.id_par_bidang' => $id),'order' => 'b.urut,b.nama_bidang'));
		
		if ($sub_bid->num_rows() > 0) {

			$r = 1;
			foreach($sub_bid->result() as $row) {
			
				$cell = array();
				$cell_aksi = array(array('data' => form_hidden('id_bidang[]',$row->id_bidang).form_input('pos_x[]',$row->pos_x,'placeHolder="Pos X" style="width: 50px" id="xbox'.$row->id_bidang.'"'),'style' => 'width: 50px'));
				$cell_aksi[] = array('data' => form_input('pos_y[]',$row->pos_y,'placeHolder="Pos Y" style="width:50px" id="ybox'.$row->id_bidang.'"'),'style' => 'width: 50px');
				$cell_aksi[] = anchor('#','<i class="fa fa-clipboard"></i>','class="btn btn-sm btn-default boxon" id="'.$row->id_bidang.'" style="padding: 3px 8px"');
					
				
				for ($j=0;$j < $level_bidang; $j++) {
					if ($j>0) $cell[] = array('data'=>'','width' => '20');
				}
				
				
				$sub_bidd = $this->general_model->datagrab(array('tabel' => 'ref_bidang', 'where' => array('id_par_bidang' => $row->id_bidang)));
				
				$add_sub = array('id_unit' => $row->id_unit,'id_par_bidang' => $row->id_bidang);
				$kode = !empty($row->kode_bidang)?'<b>'.$row->kode_bidang.'</b>':null;
				$nama = !empty($row->nama_bidang)?' '.$row->nama_bidang:null;
				$cell[] = array('data'=>  $kode.$nama, 'colspan' => 8-$level_bidang);
				$cell = array_merge_recursive($cell,$cell_aksi);
			
				$this->table->add_row($cell);
				if ($sub_bidd->num_rows() > 0) $this->tabel_sub($level_bidang+1,$row->id_bidang);
				$r+=1;
			}
		}
		
	}
	
	function tabel_bidang($o = null) {
		
		$o = un_de($o);
		
		$from = array('ref_bidang b' => '','ref_unit u' => 'u.id_unit = b.id_unit');
		
		$root_bid = $this->general_model->datagrab(array('tabel' => $from,'where' => array('b.id_unit = '.$o['id'].' AND id_par_bidang IS NULL' => null),'order' => 'b.urut,b.nama_bidang'));

		if ($root_bid->num_rows() > 0) {
            $this->table->set_template(array('table_open' => '<table class="table table-striped table-bordered table-condensed gap">'));
            $this->table->set_empty("&nbsp;");	
			
				foreach ($root_bid->result() as $row) {
					$cell = array();
					
					$cell_aksi = array(array('data' => form_hidden('id_bidang[]',$row->id_bidang).form_input('pos_x[]',$row->pos_x,'placeHolder="Pos X" style="width: 50px" id="xbox'.$row->id_bidang.'"'),'style' => 'width: 50px'));
					$cell_aksi[] = array('data' => form_input('pos_y[]',$row->pos_y,'placeHolder="Pos Y" style="width:50px" id="ybox'.$row->id_bidang.'"'),'style' => 'width: 50px');
					$cell_aksi[] = array('data' => anchor('#','<i class="fa fa-clipboard"></i>','class="btn btn-sm btn-default boxon" id="'.$row->id_bidang.'" style="padding: 3px 8px"'),'style' => 'width: 30px');
					$sub_bid = $this->general_model->datagrab(array('tabel' => 'ref_bidang', 'where' => array('id_par_bidang' => $row->id_bidang)));
					$kode = !empty($row->kode_bidang)?'<b>'.$row->kode_bidang.'</b>':null;
					$nama = !empty($row->nama_bidang)?' '.$row->nama_bidang:null;
				
					$add_sub = array(
						'id_unit' => $row->id_unit,
						'id_par_bidang' => $row->id_bidang);
				
					$cell[] = array('data'=> $kode.$nama,'colspan'=> 7);

					$this->table->add_row(array_merge_recursive($cell,$cell_aksi));
					if ($sub_bid->num_rows() > 0) $this->tabel_sub(2,$row->id_bidang);

				}
				return $this->table->generate();
			
		} else {
		
		return '<div class="alert">Belum ada <strong>Unit Organisasi</strong> terdaftar </div>';
		
		}
	
		
	}
	
		
	function editor($par) {
		
		$o = un_de($par);
		
		$data['breadcrumb'] = array('' => 'Referensi', 'kepegawaian/bso/editor/'.$par => 'Editor BSO :');
		
		$data['unit'] = $this->general_model->datagrab(array('tabel' => 'ref_unit','where' => array('id_unit' => $o['id'])))->row();
		
		$data['tabel'] = $this->tabel_bidang(in_de(array('id' => $o['id'])));
		$data['box']  = $this->general_model->datagrab(array('tabel' => 'ref_bidang','where' => array('id_unit' => $o['id'])));
		
		$this->table->set_template(array('table_open' => '<table border="0" cellpadding="4" id="tabel_data" style="width: 100%; margin: 10px 5px; clear: both">'));
		$this->table->set_empty("&nbsp;");
		$no = 1;

		$eselon = $this->general_model->datagrab(array('tabel' => 'ref_eselon'));
		$this->table->add_row(
			array('data' => '&nbsp','width'=> '65%'),
			array('data' => '&nbsp','width'=> '10%'),
			array('data' => '&nbsp','width'=> '8%'),
			array('data' => '&nbsp','width'=> '8%'),
			array('data' => '&nbsp','width'=> '7%')
			);
		
		$data['title'] = 'Editor BSO/SOTK/STO';
		$data['descript'] = $data['unit']->unit;
		$data['content'] = "referensi/bso_form_view";
		$this->load->view('home', $data);
	}
		
	function detail($id) {
	
		$from = array(
			'ref_bidang b' => '',
			'view_jabatan jab' => array('jab.id_bidang = b.id_bidang','left'),
			'view_golru g' => array('g.id_pegawai = jab.id_pegawai','left'),
			'view_pegawai peg' => array('peg.id_pegawai = jab.id_pegawai','left')
		);
	
		$data['pegawai'] = $this->general_model->datagrab(array('tabel' => $from,'where' => array('b.id_bidang' => $id),'jab.tgl_selesai =< NOW()' => null))->row();

		$this->load->view('referensi/bso_content', $data);
		
		
	}
	
	function save_uu() {
		
		$id_unit = $this->input->post('id_unit');
		$this->general_model->save_data('ref_unit',array('bso_uu' => $this->input->post('bso_uu')),'id_unit',$id_unit);
		$this->session->set_flashdata('ok','Perundang-undangan berhasil disimpan ...');
		redirect('referensi/bso/editor/'.in_de(array('id' => $id_unit)));
		
		
	}
	
	function upload_bso() {
		$foto_lama = $this->input->post('foto_past');
		$status = $this->input->post('status');
		
		$id_unit = $this->input->post('id_unit');
		
		$dir_path = './uploads/kepegawaian/bso';
		if (!is_dir($dir_path)) mkdir($dir_path,0777,TRUE);
		
		if (!empty($foto_lama)) {
			$path_lama = 'uploads/kepegawaian/bso/'.$foto_lama;
			if(file_exists($path_lama)) unlink($path_lama);
		}
		
		$filenama = $_FILES['bso']['name'];
		
		$this->upload->initialize(array(
			'upload_path' => $dir_path,
			'allowed_types' => 'jpg|jpeg|png|gif',
			'file_name' => $filenama
		));
		
		$this->upload->do_upload('bso');
		$data_upload = $this->upload->data();
			
		$onerror = $this->upload->display_errors('&nbsp','&nbsp');
	
		if (!empty($onerror)) {
			$this->session->set_flashdata('fail', $onerror);
		} else {
			$this->general_model->save_data('ref_unit',array('bso_image' => $filenama),'id_unit',$id_unit);
			$this->session->set_flashdata('ok', 'Layar belakang berhasil diunggah');
		}
		
		redirect('referensi/bso/editor/'.in_de(array('id' => $id_unit)));
	
	}

	function update_box() {
		
		$id_unit = $this->input->post('id_unit');

		$id = $this->input->post('id_bidang');
		$posx = $this->input->post('pos_x');
		$posy = $this->input->post('pos_y');
   
		for($i = 0; $i < count($id); $i++) {
			$this->general_model->save_data('ref_bidang',array(
				'pos_x' => $posx[$i],
				'pos_y' => $posy[$i]
			),'id_bidang',$id[$i]);
		}
		
		$this->session->set_flashdata('ok', 'Stuktur berhasil disimpan');
		redirect('referensi/bso/editor/'.in_de(array('id' => $id_unit)));
		
	}
	

}
?>