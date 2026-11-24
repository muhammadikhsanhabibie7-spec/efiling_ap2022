<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jenis_arsip extends CI_Controller {

	// var $fitur =  null; // 1/null 
	var $fitur =  1; // 1/null 

	function __construct() {
	
		parent::__construct();
		login_check($this->session->userdata('login_state'));
		$this->session->set_userdata(array('cut' => 1));
		
	}

	public function index() {

		$this->list_data();
	
	}
	
	function cr($e) {
	    return $this->general_model->check_role($this->session->userdata('id_pegawai'),$e);
    }
	
	function list_data($search = null, $offset = null) {
		
		$data['breadcrumb'] = array('' => 'Referensi','filing/jenis_arsip' => 'Jenis Dokumen');
		
		$offset = !empty($offset) ? $offset : null;
		
		$key = $this->input->post('search');
		$se = array();
		
		if (!empty($key)) {
			$se['arsip_jenis'] = $key;
			$se['kode_arsip_jenis'] = $key;
		} else if ($search) {
			$o = un_de($search);
			$se['arsip_jenis'] = @$o['arsip_jenis'];
			$se['kode_arsip_jenis'] = @$o['kode_arsip_jenis'];
		} 
		
		$config['base_url']	= site_url('filing/jenis_arsip/list_data/'.in_de($se));
		$config['total_rows'] = $this->general_model->datagrabs(array('tabel'=> 'ref_arsip_jenis','where'=>array('(id_par IS NULL OR id_par=0)'=>null), 'search' => $se))->num_rows();
		$config['per_page']	= 10;
		$config['uri_segment'] = 5;
		
		$this->pagination->initialize($config);
		
		$data['links']	= $this->pagination->create_links();
		
		$offs = (!in_array($offset,array("cetak","excel"))) ?  $offset : null;
		$lim = (!in_array($offset,array("cetak","excel"))) ? $config['per_page'] : null;
		
		$query = $this->general_model->datagrabs(array(
			'tabel' => 'ref_arsip_jenis',
			'offset' => $offs,
			'limit' => $config['per_page'],
			'order' => 'urut',
			'where' => array('(id_par IS NULL OR id_par=0)'=>null),
			'search' => $se
		));

		$no_urut = 1;
		foreach ($query->result() as $row) {
			// cek($this->db->last_query());
			if (empty($row->urut)) {
			$this->general_model->save_data('ref_arsip_jenis',array('urut' => $no_urut),'id_arsip_jenis',$row->id_arsip_jenis);
			$no_urut+=1;
			}
			
		}

		// tampilkan data
		$awal= array();
		$urutkan= array();

		foreach ($query->result() as $r_awal) {
			$awal[] = array($r_awal->id_arsip_jenis, $r_awal->urut);
		}



		if ($query->num_rows() != 0) {

			$classy = (in_array($offset,array("cetak","excel"))) ? 'class="tabel_print" border=1' : 'class="table table-striped table-bordered table-condensed table-nonfluid"';
			$this->table->set_template(array('table_open'=>'<table '.$classy.'>'));
			$heads = array(''); //kolom
			// if (!in_array($offset,array('cetak','excel')) and !$this->cr('DMKPE')) $heads[] = 'Kolom';
			// $heads = array_merge_recursive($heads,array('Jenis Arsip'));
			$heads[] = array('data' => 'Jenis Arsip','colspan' => '7');
			// if (!in_array($offset,array('cetak','excel'))) $heads[] = array('data' => 'Aksi','colspan' => '5');
			$heads[] = 'Urut';
			$heads[] = array('data' => 'Aksi','colspan' => 2);
			$this->table->set_heading($heads);
		
			//$no = $offset + 1;
			$r=0;
			foreach($query->result() as $row){
				$rows = array();

				$btn_down = ($r+1 < $query->num_rows()) ? anchor('filing/jenis_arsip/urutkan/'.in_de(array('id1' => $row->id_arsip_jenis,'no1' => $row->urut,'id2' => $awal[$r+1][0],'no2' =>  $awal[$r+1][1])),'<i class="fa fa-arrow-down"></i>','class="btn btn-xs btn-info"') : '';
				$btn_up = ($r > 0) ? anchor('filing/jenis_arsip/urutkan/'.in_de(array('id2' => $row->id_arsip_jenis,'no2' => $row->urut,'id1' =>  !empty($urutkan[0])?$urutkan[0]:@$awal[$r+1][0],'no1' => !empty($urutkan[1])?$urutkan[1]:@$awal[$r+1][1])),'<i class="fa fa-arrow-up"></i>','class="btn btn-xs btn-info"') : '';
				
				$urutkan = array($row->id_arsip_jenis,$row->urut);
				$cell_aksi = array(array('class' => 'text-center no-wrap','data' => $btn_down.$btn_up));
				
				$cell_aksi[] = array('class' => 'text-center','data' => '
					<a href="#" class="btn-edit btn btn-xs btn-warning" act="'.site_url('filing/jenis_arsip/form_data/'.in_de(array('id' => $row->id_arsip_jenis))).'"><i class="fa fa-pencil"></i></a>');


				// if (!in_array($offset,array('cetak','excel')) and !$this->cr(null) ) {
					//$rows[] = anchor('filing/jenis_arsip/kolom/'.$row->id_arsip_jenis,'<i class="fa fa-table"></i>','class="btn btn-primary btn-xs"');
				// }
				$k = !empty($row->kode_arsip_jenis)?' ('.$row->kode_arsip_jenis.')':null;
				
				// $rows[] = array('data'=>$row->arsip_jenis.$k, 'colspan' => 7);
				// $rows[] = $row->direktori;

				$rows[] = anchor('filing/jenis_arsip/kolom/'.$row->id_arsip_jenis,'<i class="fa fa-table"></i> FIELD','class="btn btn-primary btn-xs" title="Manajemen field jenis arsip"');				
				$kode = !empty($row->kode_arsip_jenis)?' <b>'.$row->kode_arsip_jenis.'</b> |':null;
				$nama = !empty($row->arsip_jenis)?' '.$row->arsip_jenis:null;
				$dir = null;//!empty($row->arsip_jenis)?' |folder: '.$row->direktori:null;
			
				// if (!in_array($offset,array('cetak','excel'))) {
				// 	$rows[] = anchor('#','<i class="fa fa-pencil"></i>','class="btn-edit btn btn-xs btn-warning" act="'.site_url('filing/jenis_arsip/form_data/'.$row->id_arsip_jenis).'"');
				// 	$rows[] = anchor('#','<i class="fa fa-trash"></i>','class="btn-delete btn btn-xs btn-danger" act="'.site_url('home/general_delete/'.in_de(array('id' => $row->id_arsip_jenis,'redirect' => 'filing/jenis_arsip','tabel' => 'ref_arsip_jenis','kolom' => 'id_arsip_jenis'))).'" msg="Apakah Anda ingin menghapus data ini?"');
				// }

					


				$sub_jenis = $this->general_model->datagrabs(array(
					'tabel'=>'ref_arsip_jenis',
					'where'=>array('id_par'=>$row->id_arsip_jenis)));
				if ($sub_jenis->num_rows() == 0) {
					$cell_aksi[] = array('class'=>'text-center', 'data'=>'<a href="#" class="btn-delete btn btn-xs btn-danger" act="'.site_url('filing/jenis_arsip/removing/'.in_de(array('id'=>$row->id_arsip_jenis))).'" msg="Apakah Jenis Arsip ini akan dihapus ?"><i class="fa fa-trash"></i></a>');

				}

				$rows[] = array('data'=> @$kolom.$kode.$nama.$dir.' &nbsp; ','colspan'=> 7);
				$this->table->add_row(array_merge_recursive($rows, $cell_aksi));
				if ($sub_jenis->num_rows() > 0){
					$this->tabel_sub(2, $row->id_arsip_jenis, $offset);
				}

				$r += 1;
			}
	
			$tabel = $this->table->generate();
		}else{
			$tabel = '<div class="alert alert-confirm pull-left">Belum ada data</div><div class="clear"></div>';
		}
		
		
		$btn_cetak = ($query->num_rows() > 0) ?
		'<div class="btn-group"  tyle="margin-left: 5px;">
		<a class="btn btn-warning dropdown-toggle" data-toggle="dropdown" href="#" style="margin: 0 0 0 5px">
		<i class="fa fa-print"></i> <span class="caret"></span>
		</a>
		<ul class="dropdown-menu pull-right">
		<li>'.anchor('filing/jenis_arsip/list_data/'.in_de($se).'/cetak','<i class="fa fa-print"></i> Cetak','target="_blank"').'</li>
		<li>'.anchor('filing/jenis_arsip/list_data/'.in_de($se).'/excel','<i class="fa fa-file-excel-o"></i> Ekspor Excel','target="_blank"').'</li>
		</ul>
		</div>' : null;
		
		$data['tombol'] = anchor('#','<i class="fa fa-plus"></i> Jenis Arsip','class="btn-edit btn btn-success" act="'.site_url('filing/jenis_arsip/form_data').'"').$btn_cetak;
		
		$data['extra_tombol'] = 
		form_open('filing/jenis_arsip','id="form_search"').
		'<div class="input-group">
                  <input name="search" type="text" placeholder="Pencarian ..." class="form-control pull-right" value="'.(!empty($key)?$key:(!empty($o['arsip_jenis'])?$o['arsip_jenis']:null)).'">
                  <div class="input-group-btn">
                    <button class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>'.form_close();

		$data['tabel']	= $tabel;
	
		if ($offset == "cetak") {
			$data['title'] = '<h3>Jenis Arsip</h3>';
			$data['content'] = $tabel;
			$this->load->view('umum/print',$data);
		} else if ($offset == "excel") {
			$data['file_name'] = str_replace(" ","_",strtolower('jenis_arsip')).'.xls';
			$data['title'] = '<h3>Jenis Arsip</h3>';
			$data['content'] = $tabel;
			$this->load->view('umum/excel',$data);
		} else {
			$data['total'] = $config['total_rows'];
			$data['tabel'] = $tabel;
			$data['title'] = 'Jenis Arsip';
			$data['content'] = "umum/standard_view";
			$this->load->view('home', $data);
		}
	}

	function tabel_sub($level,$id, $offset=null) {
		
		$from = 'ref_arsip_jenis';
		
		$sub_jenis = $this->general_model->datagrabs(array(
			'tabel' => $from,
			'where' => array('id_par' => $id),
			'order' => 'urut, arsip_jenis',
			'select' => '*'
		));
		
		$urutkan_sub = array();
		$awal_sub = array();
		
		if ($sub_jenis->num_rows() > 0) {
			foreach ($sub_jenis->result() as $row) {
				$awal_sub[] = array($row->id_arsip_jenis,$row->urut);
			}

			$r = 0;
			foreach($sub_jenis->result() as $row) {
				$cell = array();
				
				// if (!in_array($offset,array('cetak','excel')) and !$this->cr('DMKPE')) {
				// 	$cell[] = anchor('filing/jenis_arsip/kolom/'.$row->id_arsip_jenis,'<i class="fa fa-table"></i>','class="btn btn-primary btn-xs"');
				// }
				
				
			
				for ($j=0;$j < $level; $j++) {
					if ($j>0) $cell[] = array('data'=>'','width' => '30');
				}
				
				$btn_down = ($r+1 < $sub_jenis->num_rows()) ? anchor('filing/jenis_arsip/urutkan/'.in_de(array('id1' => $row->id_arsip_jenis,'no1' => $row->urut,'id2' => $awal_sub[$r+1][0],'no2' =>  $awal_sub[$r+1][1])),'<i class="fa fa-arrow-down"></i>','class="btn btn-xs btn-info"') : '';
				$btn_up = ($r > 0) ? anchor('filing/jenis_arsip/urutkan/'.in_de(array('id2' => $row->id_arsip_jenis,'no2' => $row->urut,'id1' =>  !empty($urutkan_sub[0])?$urutkan_sub[0]:@$awal_sub[0],'no1' => !empty($urutkan_sub[1])?$urutkan_sub[1]:@$awal_sub[1])),'<i class="fa fa-arrow-up"></i>','class="btn btn-xs btn-info"') : '';
				$urutkan_sub = array($row->id_arsip_jenis,$row->urut);
				// tekan kene
				$cell_aksi = array(array('class' => 'text-center no-wrap','data' => $btn_down.$btn_up));
				$cell_aksi[] = array('class' => 'text-center','data' => '<a href="#" class="btn-edit btn btn-xs btn-warning" act="'.site_url('filing/jenis_arsip/form_data/'.in_de(array('id_par' => $row->id_par,'id' => $row->id_arsip_jenis))).'"><i class="fa fa-pencil"></i></a>');
				
				$sub_jenis2 = $this->general_model->datagrabs(array('tabel' => 'ref_arsip_jenis', 'where' => array('id_par' => $row->id_arsip_jenis)));
				if ($sub_jenis2->num_rows() == 0)  {
					$cell_aksi[] = array('class' => 'text-center','data' => '<a href="#" class="btn-delete btn btn-xs btn-danger" act="'.site_url('filing/jenis_arsip/removing/'.in_de(array('id' => $row->id_arsip_jenis))).'" msg="Apakah  <b>'.$row->kode_arsip_jenis.' '.$row->arsip_jenis.'</b> akan dihapus?"><i class="fa fa-trash"></i></a>');
				}

				$cell[] = anchor('filing/jenis_arsip/kolom/'.$row->id_arsip_jenis,'<i class="fa fa-table"></i> FIELD','class="btn btn-primary btn-xs" title="Manajemen field jenis arsip"');

			
				$kode = !empty($row->kode_arsip_jenis)?'<b>'.$row->kode_arsip_jenis.'</b> |':null;
				$nama = !empty($row->arsip_jenis)?' '.$row->arsip_jenis:null;
				$dir = null;//!empty($row->arsip_jenis)?' |folder: '.$row->direktori:null;
				
				$cell[] = array('data'=>  $kode.$nama.$dir.' &nbsp; ', 'colspan' => 8-$level);
				$cell = array_merge_recursive($cell,$cell_aksi);
			
				$this->table->add_row($cell);
				if ($sub_jenis2->num_rows() > 0) $this->tabel_sub($level+1,$row->id_arsip_jenis, $offset);
				$r+=1;
			}
		}
		
	}

	function form_data($id = null) {
		
		$get = un_de($id);
		$data['title'] = !empty($get['id']) ? 'Ubah Jenis Arsip' : 'Tambah Jenis Arsip';
		$data['form_link'] = 'filing/jenis_arsip/save_dok';
		$def = !empty($id) ? $this->general_model->datagrab(array('tabel' => 'ref_arsip_jenis','where' => array('id_arsip_jenis' => $get['id'])))->row() : null;
		$combo_par = $this->general_model->combo_box(array(
			'tabel'=>'ref_arsip_jenis',
			'key'=>'id_arsip_jenis',
			'select' => 'id_arsip_jenis,CONCAT(IF(kode_arsip_jenis != "",CONCAT(kode_arsip_jenis," | "),""),arsip_jenis) arsip_jenis',
			'val'=>array('arsip_jenis')
		));

		if (empty($def->id_par) || ($def->id_par == 0)) {
			$hide_par = null;
		}else{
			$hide_par = 'hidden';
		}
		// cek($hide_par);
		$data['load_script'] = '<link href="'.base_url().'assets/plugins/iCheck/all.css'.'" rel="stylesheet" type="text/css" />
			<script type="text/javascript" src="'.base_url().'assets/plugins/iCheck/icheck.min.js'.'"></script>';
		$data['script'] = '$(\'input[type="checkbox"].incheck\').iCheck({
			checkboxClass: \'icheckbox_minimal-blue\',
			radioClass: \'iradio_minimal-blue\'
		});';
		$data['form_data'] = 
			form_hidden('id_arsip_jenis',@$def->id_arsip_jenis).
			form_hidden('id_aplikasi',3).'
			<p>'.form_label('Kode').form_input('kode_arsip_jenis',@$def->kode_arsip_jenis,'class=" form-control"').'<p>'.
			($this->fitur !== NULL?'<p>'.form_label('Jenis Arsip Diatasnya').form_dropdown('id_par', $combo_par, @$def->id_par, 'class="combo-box" style="width: 100%"'):null).'
			<p>'.form_label('Jenis Arsip').form_input('arsip_jenis',@$def->arsip_jenis,'class="form-control" required').'<p>
			<p>'.form_label('Direktori Fisik').form_input('direktori',@$def->direktori,'class="form-control"'.(!empty($get['id'])?' readonly ':null)).'</p>'
			.'<p> <div class="form-group">
                <label>
                <div class="input-group">
                 
                  <input value="1" name="akses_manual" type="checkbox" class="incheck" '.((!empty($def->akses_manual) && ($def->akses_manual == 1)) ? 'checked': null).'>
                   &nbsp; Akses Manual
                  </div>
                </label>
               
              </div></p>'

			;
		
		$this->load->view('umum/form_view',$data);
	}

	function removing($id){	
		$o = un_de($id);
		// hapus jenis arsip + arsip konten:
		$arsip_jenis = $this->general_model->datagrabs(array(
			'tabel'=>'ref_arsip_jenis',
			'where'=>array('id_arsip_jenis'=>$o['id']),
			));

	if ($arsip_jenis->num_rows() > 0) {
			$aj_row = $arsip_jenis->row();

			$arsip_konten = $this->general_model->datagrabs(array(
				'tabel'=>'arsip_konten',
				'where'=>array('id_arsip_jenis'=>$o['id'])
				));
			$jml_konten_del = $arsip_konten->num_rows();

			if ($arsip_konten->num_rows() > 0) {

			foreach ($arsip_konten->result() as $r_kt) { 

					$file_arsip = $this->general_model->datagrab(array(
							'tabel' => array(
								'arsip a' => '',
								'arsip_konten_set s' => 's.id_arsip = a.id_arsip',
								'ref_arsip_jenis j' => 'j.id_arsip_jenis = a.id_arsip_jenis'),
							'select' => 'a.id_arsip,a.arsip,j.direktori',
							'where' => array('id_arsip_konten' => $r_kt->id_arsip_konten)
						));

					

					// delete arsip:
					foreach($file_arsip->result() as $fl) { 
						$path = './uploads/arsip/'.$fl->direktori.'/'.$fl->arsip;
						if(file_exists($path)) unlink($path);		
						$this->general_model->delete_data('arsip','id_arsip',$fl->id_arsip);
					}

					$this->general_model->delete_data('arsip_kolom','id_arsip_konten',$r_kt->id_arsip_konten);
					$this->general_model->delete_data('arsip_konten_set','id_arsip_konten',$r_kt->id_arsip_konten);
					$this->general_model->delete_data('arsip_konten','id_arsip_konten',$r_kt->id_arsip_konten);
			}
		}

		$this->general_model->delete_data('ref_arsip_jenis','id_arsip_jenis',$o['id']);
		audit_log('Referensi', 'HAPUS', 'Jenis Arsip: '.$aj_row->arsip_jenis, 'ID: '.$o['id'].' | Kode: '.$aj_row->kode_arsip_jenis.' | Konten arsip ikut terhapus: '.$jml_konten_del);
		$this->session->set_flashdata('ok', 'Jenis Arsip Berhasil Dihapus');
		redirect('filing/jenis_arsip');

			}else{
				$this->session->set_flashdata('fail', 'Tidak Ada Data Jenis Arsip');
				redirect('filing/jenis_arsip');
			}
			

		// start
		
		
		
		
		
		// end
		
		
	}
		
	function save_dok() {
		$operator = $this->session->userdata('id_pegawai');
		$id_bidang = $this->session->userdata('id_bidang');
		$id = $this->input->post('id_arsip_jenis');
		$akses_manual = $this->input->post('akses_manual');
		$simpan = array(
			'id_aplikasi' => $this->input->post('id_aplikasi'),
			'id_aplikasi' => $this->input->post('id_aplikasi'),
			'kode_arsip_jenis' => $this->input->post('kode_arsip_jenis'),
			'arsip_jenis' => $this->input->post('arsip_jenis'),
			'akses_manual' => !empty($this->input->post('akses_manual'))? $this->input->post('akses_manual') : 0,
			'direktori' => str_replace(" ","_",strtolower($this->input->post('direktori')))
		);

		if (empty($id)) {
			$simpan['id_operator'] = $operator;
		}
		
		if ($this->fitur !== NULL) {
		$par = $this->input->post('id_par');
		if (!empty($par)) $simpan['id_par'] = $par;
		}
		
		if (empty($id)) {
			$whereis = (!empty($par)) ? array('id_par = '.$par => null) : array('id_par IS NULL' => null); 
			$num = $this->general_model->datagrab(array('tabel' => 'ref_arsip_jenis','where' => $whereis,'select' => 'MAX(urut) as urutan'))->row();
			$simpan['urut'] = ($num->urutan+1);
		}

		
		 $this->general_model->save_data('ref_arsip_jenis',$simpan,'id_arsip_jenis',$id);
		 $id_arsip_jenis = !empty($id) ? $id : $this->db->insert_id();

		 // cek arsip jenis role
		 $arsip_jenis_role = $this->general_model->datagrabs(array(
		 	'tabel'=>'ref_arsip_jenis_role',
		 	'where'=>array('id_arsip_jenis'=>$id_arsip_jenis, 'id_bidang'=>$id_bidang)))->row();

		 $id_aj_role = !empty($arsip_jenis_role) ? $arsip_jenis_role->id_arsip_jenis_role : null; 
		 // ./cek arsip jenis role
		 // simpan arsip jenis role:
		 if (empty($akses_manual)) {
		 	$bidang = $this->general_model->datagrabs(array(
		 		'tabel'=>array(
		 			'ref_bidang a'=>'',
		 			'ref_unit b'=>array('b.id_unit=a.id_unit', 'left'),
		 			)
		 		));
		 	if ($bidang->num_rows() > 0) {
		 		foreach ($bidang->result() as $r_bid) {
		 			$cek_role_bid = $this->db->get_where('ref_arsip_jenis_role', array('id_arsip_jenis'=>$id_arsip_jenis, 'id_bidang'=>$r_bid->id_bidang));
		 			if ($cek_role_bid->num_rows() == 0) {
		 				$this->general_model->save_data('ref_arsip_jenis_role', array('id_arsip_jenis'=>$id_arsip_jenis, 'id_bidang'=>$r_bid->id_bidang));
		 			}
		 		}
		 	}
		 	

		 }
		 
		// simpan urut:
		$this->urut_nomor(in_de(array('par' => !empty($par) ? $par : null)));
		audit_log('Referensi', !empty($id) ? 'UBAH' : 'TAMBAH', 'Jenis Arsip: '.$simpan['arsip_jenis'], 'Kode: '.$simpan['kode_arsip_jenis'].' | Direktori: '.$simpan['direktori']);

		$this->session->set_flashdata('ok','Jenis Arsip berhasil disimpan ...');
		redirect('filing/jenis_arsip');
		
	}

	function urut_nomor($par) {
		$o = un_de($par);
		$whereis = (!empty($o['par'])) ? array('id_par = '.$o['par'] => null) : array('id_par IS NULL' => null); 
		$num = $this->general_model->datagrab(array('tabel' => 'ref_arsip_jenis','where' => $whereis,'order' => 'urut,arsip_jenis'));

		$j = 1;
		foreach($num->result() as $n) {
			$this->general_model->save_data('ref_arsip_jenis',array('urut' => $j),'id_arsip_jenis',$n->id_arsip_jenis);
			$j+=1;
		}
		
	}

	// btn up down :

	function urutkan($par) {
		$o = un_de($par);
		$this->general_model->save_data('ref_arsip_jenis',array('urut' => $o['no2']),'id_arsip_jenis',$o['id1']);
		$this->general_model->save_data('ref_arsip_jenis',array('urut' => $o['no1']),'id_arsip_jenis',$o['id2']);
		redirect('filing/jenis_arsip');
	}

	
	function kolom($id,$search = null,$offset = null) {
		
		$data['breadcrumb'] = array('' => 'Referensi','filing/jenis_arsip' => 'Jenis Dokumen','filing/jenis_arsip/kolom/'.$id => 'Kolom');
		
		$offset = !empty($offset) ? $offset : null;
		
		$key = $this->input->post('search');
		$se = array();
		
		if (!empty($key)) {
			$se['kolom'] = $key;
		} else if ($search) {
			$o = un_de($search);
			$se['kolom'] = @$o['kolom'];
		} 
		
		$config['base_url']	= site_url('filing/jenis_arsip/kolom/'.$id.'/'.in_de($se));
		$config['total_rows'] = $this->general_model->datagrab(array('tabel'=> 'ref_arsip_kolom','where' => array('id_arsip_jenis' => $id),'search' => $se))->num_rows();
		$config['per_page']	= '10';
		$config['uri_segment'] = '6';
		
		$this->pagination->initialize($config);
		
		$data['links']	= $this->pagination->create_links();
		
		$offs = (!in_array($offset,array("cetak","excel"))) ?  $offset : null;
		$lim = (!in_array($offset,array("cetak","excel"))) ? $config['per_page'] : null;
		
		$query = $this->general_model->datagrab(array(
			'tabel' => 'ref_arsip_kolom',
			'offset' => $offs,
			'limit' => '10',
			'order' => 'kolom',
			'where' => array('id_arsip_jenis' => $id),
			'search' => $se
		));
		

		if ($query->num_rows() != 0) {
			$classy = (in_array($offset,array("cetak","excel"))) ? 'class="tabel_print" border=1' : 'class="table table-striped table-bordered table-condensed table-nonfluid"';
			$this->table->set_template(array('table_open'=>'<table '.$classy.'>'));
			$heads = array('No','Kolom','Tipe');
			if (!in_array($offset,array('cetak','excel'))) $heads[] = array('data' => 'Aksi','colspan' => '3');
			$this->table->set_heading($heads);
		
			$no = (is_numeric($offset) ? (int)$offset : 0) + 1;
			foreach($query->result() as $row){
				$rows = array($no);
				
				
				$c = array(
			'' => ' -- Pilih Jenis Field -- ',
			'1' => 'Teks & Karakter',
			'2' => 'Text Area',
			'3' => 'Tanggal',
			'4' => 'Numerik'
		);
				$rows[] = $row->kolom;
				$rows[] = $c[$row->tipe];
			
				if (!in_array($offset,array('cetak','excel'))) {
					$rows[] = anchor('#','<i class="fa fa-pencil"></i>','class="btn-edit" act="'.site_url('filing/jenis_arsip/form_kolom/'.$id.'/'.$row->id_arsip_kolom).'"');
					$rows[] = anchor('#','<i class="fa fa-trash"></i>','class="btn-delete" act="'.site_url('home/general_delete/'.in_de(array('id' => $row->id_arsip_kolom,'redirect' => 'filing/jenis_arsip/kolom/'.$id,'tabel' => 'ref_arsip_kolom','kolom' => 'id_arsip_kolom'))).'" msg="Apakah Anda ingin menghapus kolom ini?<br>Menghapus kolom akan menghapus semua data di dokumen terhubung dengan kolom ini!"');
				}
				
				$this->table->add_row($rows);
				$no++;
			}
	
			$tabel = $this->table->generate();
		}else{
			$tabel = '<div class="alert alert-confirm pull-left">Belum ada data</div><div class="clear"></div>';
		}
		
		$btn_cetak = ($query->num_rows() > 0) ? 
		'<div class="btn-group"  tyle="margin-left: 5px;">
		<a class="btn btn-warning dropdown-toggle" data-toggle="dropdown" href="#" style="margin: 0 0 0 5px">
		<i class="fa fa-print"></i> <span class="caret"></span>
		</a>
		<ul class="dropdown-menu pull-right">
		<li>'.anchor('filing/jenis_arsip/kolom/'.$id.'/'.in_de($se).'/cetak','<i class="fa fa-print"></i> Cetak','target="_blank"').'</li>
		<li>'.anchor('filing/jenis_arsip/kolom/'.$id.'/'.in_de($se).'/excel','<i class="fa fa-file-excel-o"></i> Ekspor Excel','target="_blank"').'</li>
		</ul>
		</div>' : null;
		
		$data['total'] = $config['total_rows'];
		$data['tombol'] = 
			anchor('filing/jenis_arsip','<i class="fa fa-arrow-left"></i>','class="btn btn-default"').' &nbsp; '.
			anchor('#','<i class="fa fa-plus"></i> &nbsp; Field Arsip','class="btn-edit btn btn-success" act="'.site_url('filing/jenis_arsip/form_kolom/'.$id).'"').
			$btn_cetak;
		
		$data['extra_tombol'] = 
		form_open('filing/jenis_arsip/kolom/'.$id,'id="form_search"').
		'<div class="input-group">
                  <input name="search" type="text" placeholder="Pencarian ..." class="form-control pull-right" value="'.(!empty($key)?$key:(!empty($o['arsip_jenis'])?$o['arsip_jenis']:null)).'">
                  <div class="input-group-btn">
                    <button class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>'.form_close();

		$data['tabel']	= $tabel;
	
		if ($offset == "cetak") {
			$data['title'] = '<h3>Field Arsip</h3>';
			$data['content'] = $tabel;
			$this->load->view('umum/print',$data);
		} else if ($offset == "excel") {
			$data['file_name'] = str_replace(" ","_",strtolower('kolom_arsip')).'.xls';
			$data['title'] = '<h3>Field Arsip</h3>';
			$data['content'] = $tabel;
			$this->load->view('umum/excel',$data);
		} else {
			$data['tabel'] = $tabel;
			$data['title'] = 'Field Arsip';
			$data['content'] = "umum/standard_view";
			$this->load->view('home', $data);
		}
	}
	
	function form_kolom($id_arsip,$id = null) {
		
		$data['title'] = !empty($id) ? 'Ubah Field Arsip' : 'Tambah Field Arsip';
		$data['form_link'] = 'filing/jenis_arsip/save_kolom';
		$def = !empty($id) ? $this->general_model->datagrab(array('tabel' => 'ref_arsip_kolom','where' => array('id_arsip_kolom' => $id)))->row() : null;
		
		$c = array(
			'' => ' -- Pilih Jenis Field -- ',
			'1' => 'Teks & Karakter',
			'2' => 'Text Area',
			'3' => 'Tanggal',
			'4' => 'Numerik'
		);
		
		$data['form_data'] = 
			form_hidden('id_arsip_kolom',@$def->id_arsip_kolom).
			form_hidden('id_arsip_jenis',$id_arsip).'
			<p>'.form_label('Judul Field').form_input('kolom',@$def->kolom,'class="form-control" required').'<p>
			<p>'.form_label('Jenis Field').form_dropdown('tipe',$c,@$def->tipe,'class="form-control combo-box" style="width: 100%" required').'<p>';
		
		$this->load->view('umum/form_view',$data);
	}
		
	function save_kolom() {
		
		$id = $this->input->post('id_arsip_kolom');
		$id_arsip = $this->input->post('id_arsip_jenis');
		$simpan = array(
			'id_arsip_jenis' => $id_arsip,
			'kolom' => $this->input->post('kolom'),
			'tipe' => $this->input->post('tipe')
		);
		
		$this->general_model->save_data('ref_arsip_kolom',$simpan,'id_arsip_kolom',$id);
		audit_log('Referensi', !empty($id) ? 'UBAH' : 'TAMBAH', 'Field Arsip: '.$simpan['kolom'], 'ID Jenis Arsip: '.$id_arsip.' | Tipe: '.$simpan['tipe']);
		$this->session->set_flashdata('ok','Field arsip berhasil disimpan ...');
		redirect('filing/jenis_arsip/kolom/'.$id_arsip);
		
	}

}
?>