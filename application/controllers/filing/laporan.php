<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan extends CI_Controller {


	var $unit_akar_atas = array();
	var $unit_akar_bawah = array();
	var $bidang_akar_atas = array();
	var $bidang_akar_bawah = array();

	function __construct() {
	
		parent::__construct();
		login_check($this->session->userdata('login_state'));
		
	}

	public function index() {

		$this->list_data(in_de('all'));
	
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

	
	function statistik($search = null,$offset = null) {
		
		
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

				$this->table->set_template(array('table_open'=>'<table class="tabel_print" border=1">'));
				$r = 0;
				$num = 1;
				$this->jm_jenis = 0;
				$this->jm_konten = 0;
				$this->jm_butir = 0;

				$heads = array(
	            array('data'=>'No.', 'colspan'=>''),
	            array('data'=>'Jenis Arsip', 'colspan'=>'7', 'style'=>'white-space:nowrap;'),
	            array('data'=>'Konten', 'class'=>'', 'style'=>'width:8px'),
	            array('data'=>'Butir Arsip', 'class'=>'', 'style'=>'width:80px')
	           
	           );

				if (!in_array($offset,array("cetak","excel")));
			$classy = (in_array($offset,array("cetak","excel"))) ? 'class="tabel_print" border=1' : 'class="table table-striped table-bordered table-condensed table-nonfluid"';
			
			$this->table->set_template(array('table_open'=>'<table '.$classy.'>'));
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

					$folder_aktif = ($aj->id_arsip_jenis == $id_jenis) ? '#00c0ef' : '#d2d6de';
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
					'where'=>array('id_par'=>$aj->id_arsip_jenis),
					));
					// cek($this->db->last_query());

					$btn = '<li>'.anchor('filing/pengarsipan/list_data/'.in_de($aj->id_arsip_jenis), $aj->arsip_jenis,'style="font-size: 0.95em;"').'</li>';
					$rows[] = array('data'=>++$no, 'width'=>'5px');

					$rows[] = array('data'=>$btn, 'colspan'=>7, 'class'=>$bg_col);
					$rows[] = array('data'=>$jml_konten, 'class'=>'text-center');
					$rows[] = array('data'=>$jml_arsip, 'class'=>'text-center');
					//++ $this->jm_jenis;
					$num;
					$this->jm_jenis +=  $num;
					$this->jm_konten += $jml_konten;
					$this->jm_butir += $jml_arsip;

					$this->table->add_row($rows);

					if ($sub_jenis->num_rows() > 0){
					$this->tabel_sub_lap(2, $aj->id_arsip_jenis, $aj_aktif, $where_ar_kt_role);
					$r += 1;
				}
					
				}
			/*	$this->table->add_row(
					array('data'=>'<b>TOTAL : </b>', 'colspan'=>2, 'class'=>'label-default', 'style'=>'white-space:nowrap;'),
					array('data'=>'<b>'.$this->jm_jenis.'</b>', 'colspan'=>7, 'class'=>'text-center label-default'),
					array('data'=>'<b>'.$this->jm_konten.'</b>', 'class'=>'text-center label-default'),
					array('data'=>'<b>'.$this->jm_butir.'</b>', 'class'=>'text-center label-default')
					);*/

				
				$table_jenis = $this->table->generate();


			}else{
				$table_jenis = 'Tidak/belum ada data Jenis Arsip';
			}

		$key = $this->input->post('search');
		$se = array();
		
		if (!empty($key)) {
			$se['judul'] = $key;
		} else if ($search) {
			$o = un_de($search);
			$se['judul'] = @$o['judul'];
		} 
		

		$qe = $this->general_model->datagrab(array(
			'tabel' => array(
				'arsip a' => '',
				'arsip_konten_set k' => 'a.id_arsip = k.id_arsip',
				'arsip_konten s' => 's.id_arsip_konten = k.id_arsip_konten'),
			'select' => 'count(a.id_arsip) as arsip, count(k.id_arsip_konten) as info,s.id_arsip_jenis',
			'group_by' => 'k.id_arsip_konten,s.id_arsip_jenis'
		));
		
		$sm = $this->general_model->datagrab(array(
			'tabel' => 'arsip_konten',
			'select' => 'count(id_arsip_konten) as info, id_arsip_jenis',
			'group_by' => 'id_arsip_jenis'
		));
		
		$je = $this->general_model->datagrab(array(
			'tabel' => 'ref_arsip_jenis'
		));
			
		$this->table->set_template(array('table_open'=>'<table class="tabel_print" style="padding:10px !important;" border=1>'));
		$this->table->set_heading('No','Jenis Arsip','Konten','Butir Arsip');
		$no = 1;
		foreach($je->result() as $j) {
			$jns = 0;
			$btr = 0;
			foreach($qe->result() as $q) {
				if ($q->id_arsip_jenis == $j->id_arsip_jenis) {
					$btr = $q->arsip;
				}
			}
			foreach($sm->result() as $s) {
				if ($s->id_arsip_jenis == $j->id_arsip_jenis) {
					$jns = $s->info;
				}
			}
			$this->table->add_row($no,$j->arsip_jenis,$jns,$btr);
			$no+=1;
		}
		
		$btn_cetak = ($je->num_rows() > 0) ?
		'<div class="btn-group pull-right"">
		<a class="btn btn-warning dropdown-toggle" data-toggle="dropdown" href="#" style="margin: 0 0 0 5px">
		<i class="fa fa-print"></i> <span class="caret"></span>
		</a>
		<ul class="dropdown-menu pull-right">
		<li>'.anchor('filing/laporan/statistik/'.in_de($se).'/cetak','<i class="fa fa-print"></i> Cetak','target="_blank"').'</li>
		<li>'.anchor('filing/laporan/statistik/'.in_de($se).'/excel','<i class="fa fa-file-excel-o"></i> Ekspor Excel','target="_blank"').'</li>
		</ul>
		</div><div class="clear"></div>' : null;
		
		$data['extra_tombol'] = $btn_cetak;
		$data['css_load']= '<style>
		.direktori{
		    list-style: none !important;
		    margin: 0;
		    padding: 5px;
		}
		a{
			padding: 10px;

     text-decoration: none !important;
		}
		</style>';
			$data['tabel'] = '<ul class="direktori">'.$table_jenis.'</ul>';	
		$title = "Laporan Statistik Arsip";
		if ($offset == "cetak") {
		$data['title'] = '<h3>'.$title.'</h3>';
		$data['content'] = $table_jenis;
		$this->load->view('umum/print',$data);
		
		} else if ($offset == "excel") {
		$data['file_name'] = 'laporan_statistik_arsip.xls';
		$data['title'] = '<h3>'.$title.'</h3>';
		$data['content'] = $table_jenis;
		$this->load->view('umum/excel',$data);
		
		} else {
			
		$data['tabel'] = $table_jenis;
		$data['title'] 	= $title; 
		$data['content'] = 'umum/standard_view';
		$this->load->view('home', $data);
		
		}
	}


	function tabel_sub_lap($level,$id, $arsip_aktif=null, $where_ar_kt_role=array(0)) {


		
		$from = array('ref_arsip_jenis j' => '',
						'ref_arsip_jenis_role k' => 'k.id_arsip_jenis = j.id_arsip_jenis');


		
		$sub_jenis = $this->general_model->datagrabs(array(
			'tabel' => $from,
			'where' => array('j.id_par' => $id),
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

				$folder_aktif = ($row->id_arsip_jenis == $arsip_aktif) ? '#00c0ef' : '#d2d6de';

				$cell = array();
			
				for ($j=0;$j < $level; $j++) {
					if ($j>0) $cell[] = array('data'=>'','width' => '20');
				}
				
				
				
				$sub_jenis2 = $this->general_model->datagrabs(array('tabel' => $from, 'where' => array('id_par' => $row->id_arsip_jenis)));

			

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
				$cell[] = array('data'=>$btn, 'colspan' => 8-$level, 'class'=>$bg_col);
				$cell[] = array('data'=>$jml_konten, 'class'=>'text-center');
				$cell[] = array('data'=>$jml_arsip, 'class'=>'text-center');
				$cell = array_merge_recursive($cell);

				$num;
				$this->jm_jenis += $num;
				$this->jm_konten += $jml_konten;
				$this->jm_butir += $jml_arsip;
				$this->table->add_row($cell);
				if ($sub_jenis2->num_rows() > 0) $this->tabel_sub_lap($level+1,$row->id_arsip_jenis, $arsip_aktif, $where_ar_kt_role);
				$r+=1;
			}
		}
		
	}
	
	
	function list_data($id_jenis=null, $search =null, $offset = null) {

		// cek($this->session->userdata('login_state'));
		// cek($this->session->userdata('id_pegawai'));
		// cek($this->session->userdata('id_unit'));
		// cek($this->session->userdata('id_bidang'));
		$id_jenis = un_de($id_jenis);

		// cek($this->session->userdata('id_unit'));
		// cek($this->session->userdata('id_bidang'));
		 $get_mulai = $this->session->userdata('tgl_mulai');
		 $get_selesai = $this->session->userdata('tgl_selesai');
		 $get_sorter = $this->session->userdata('sort_by');
		 $get_order = $this->session->userdata('order_by');

		 // cek($get_mulai);
		 // cek($get_selesai);
		 // cek($get_sorter);
		 // cek($get_order);

		 // untuk keperluan cetak:
		 $between = (!empty($get_mulai) && !empty($get_selesai)) ? array('mulai'=>$get_mulai, 'selesai'=>$get_selesai) : null;
		 // ./untuk keperluan cetak

		// KEWENANGAN
		$id_peg = $this->session->userdata('id_pegawai');

		$r_addkonten = $this->general_model->check_role($id_peg,'ARSAD');
		$r_editkonten = $this->general_model->check_role($id_peg,'ARSED');
		$r_delkonten = $this->general_model->check_role($id_peg,'ARSDE');
		$r_delarsip = $this->general_model->check_role($id_peg,'FILDE');
		
		$offset = !empty($offset) ? $offset : null;
			
		
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
				$this->tampug_aj = array();

				$this->table->set_template(array('table_open'=>'<table class="tabel-direktori">'));
				$r = 0;

				
				foreach ($tb_arsip_jenis->result() as $aj) {
					$this->tampug_aj[] = $aj->id_arsip_jenis;

					$jml_konten = $this->general_model->datagrabs(array(
			            'tabel'=>'arsip_konten',
			            'where'=>array('id_arsip_jenis'=>$aj->id_arsip_jenis, '(id_arsip_konten IN ('.implode(',', $where_ar_kt_role).'))'=>null),
			            'select'=>'ifnull(count(id_arsip_jenis), 0) jml_konten'))->row()->jml_konten;

					$folder_aktif = ($aj->id_arsip_jenis == $id_jenis) ? '#00c0ef' : '#d2d6de';
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
					'where'=>array('id_par'=>$aj->id_arsip_jenis),
					));
					// cek($this->db->last_query());

					$btn = '<li>'.anchor('filing/laporan/list_data/'.in_de($aj->id_arsip_jenis), $aj->arsip_jenis.' <span class="label label-default pull-right">'.$jml_konten.'</span>','style="font-size: 0.95em;"').'</li>';

					$rows[] = '<i class="fa fa-folder" style="color:'.$folder_aktif.';"></i>';
					$rows[] = array('data'=>$btn, 'colspan'=>7, 'class'=>$bg_col);

					$this->table->add_row($rows);

					if ($sub_jenis->num_rows() > 0){
					$this->tabel_sub(2, $aj->id_arsip_jenis, $aj_aktif, $where_ar_kt_role);
					$r += 1;
				}
					
				}

				$table_jenis = $this->table->generate();

				// all jenis menu sidebar
				$jml_konten = $this->general_model->datagrabs(array(
			            'tabel'=>'arsip_konten',
			            'where'=>array('(id_arsip_jenis IN ('.implode(',', $this->tampug_aj).'))'=>null, '(id_arsip_konten IN ('.implode(',', $where_ar_kt_role).'))'=>null),
			            'select'=>'ifnull(count(id_arsip_jenis), 0) jml_konten'))->row()->jml_konten;

				$folder_aktif = ($id_jenis == 'all') ? '#00c0ef' : '#d2d6de';
				$bg_col = ($id_jenis == 'all') ? 'bg-active link-normal':null;

				$row_al_jenis = array();
				$row_al_jenis[] = '<i class="fa fa-folder" style="color:'.$folder_aktif.';"></i>';
				$row_al_jenis[] = array('data'=>'<li>'.anchor('filing/laporan/list_data/'.in_de('all'), 'SEMUA ARSIP <span style="font-size:12pt; color:red;">*</span> <span class="label label-default pull-right">'.$jml_konten.'</span>','style="font-size: 0.95em;"').'</li>', 'colspan'=>7, 'class'=>$bg_col);

				$this->table->set_template(array('table_open'=>'<table class="tabel-direktori">'));
				$this->table->add_row($row_al_jenis);
				$table_jenis_all = $this->table->generate();
				// ./all jenis sidebar

				// untuk keperluan pindah arsip

			}else{
				$table_jenis = 'Tidak/belum ada data Jenis Arsip';
			}
		
		
		$data['breadcrumb'] = array('' => 'Referensi','filing/laporan' => 'Konten Arsip');

		// KONTEN ARSIP
		if (empty($id_jenis)) {
			$tabel = '
				<div class="col-md-12">
					<div class="row jumbotron">
					<style>
					.label-pilih-jenis .text {
						font-size:25pt;
					}
					</style>
						<div class="col-md-8 col-md-offset-2">
						<div class="label-pilih-jenis">
							<p><i style="color:#3c8dbc;" class="fa fa-hand-o-left fa-2x"></i> <font class="text">Pilih <b style="color:#000000;">Jenis Arsip</b>.</font></p>
						</div>
							
						</div>
						

					</div>
				</div>
				';
		}else{
			 //  $q = urldecode($this->input->post('q', TRUE));
			        $start = !empty($offset)? intval($offset) :0;

			    // konfigurasi cari + offset 
			        $q = $this->input->post('q');
			        if ($q <> '') {
			           $fcari = array(
			            'text'=>$q
			            );
			        }elseif($search){
			            $o = un_de($search);
			            $fcari = array(
			            'text'=>$o['text']
			                );
			        }else{
			            $fcari = array(
			            'text'=>null
			                ); 
			        }

			        if($q<> ''){
			            $sc = array(
			                
						'id_arsip_jenis'=>$q,
						'judul'=>$q,
						'no_surat'=>$q,
						'tanggal'=>$q,
						'tanggal_unggah'=>$q,
						'op'=>$q,
						'op_bak'=>$q,
						'judul'=>$q,
						'penerima'=>$q,
						);

			        }elseif($search){
			            $so = un_de($search);
			            $sc = array(
			                
						'id_arsip_jenis'=>$so['text'],
						'judul'=>$so['text'],
						'no_surat'=>$so['text'],
						'tanggal'=>$so['text'],
						'tanggal_unggah'=>$so['text'],
						'op'=>$so['text'],
						'op_bak'=>$so['text'],
						'judul'=>$so['text'],
						'penerima'=>$so['text'],
						);
			        }else{
			            $sc = null;
			        }

			   // ./ konfigurasi cari + offset 
			        

			        $config['base_url'] = base_url() . 'filing/laporan/list_data/' .in_de($id_jenis).'/'. in_de($fcari);
			        $config['first_url'] = base_url() . 'filing/laporan/list_data/' .in_de($id_jenis).'/'. in_de($fcari);


					$aSelect = 'a.*, b.nama pegawai, d.nama_bidang, e.unit';
					
					$config['per_page'] = 20;
			       // $config['page_query_string'] = TRUE;
			        $config['uri_segment'] = '6';

			        // cek($this->tampug_aj);

			        $this->tampug_aj = !empty($this->tampug_aj) ? $this->tampug_aj : array(0);

			        $where_jenis = ($id_jenis == 'all') ? array('(id_arsip_jenis IN ('.implode(',', $this->tampug_aj).'))'=>null) : array('id_arsip_jenis'=>$id_jenis);



			        $where = (!empty($get_mulai) and !empty($get_selesai)) ? array_merge_recursive($where_jenis, array('(a.id_arsip_konten IN ('.implode(',', $where_ar_kt_role).'))'=>null, '(tanggal BETWEEN "'.tanggal_php($get_mulai).'" AND "'.tanggal_php($get_selesai).'")'=>null)) : array_merge_recursive($where_jenis, array('(a.id_arsip_konten IN ('.implode(',', $where_ar_kt_role).'))'=>null));
			        // order
			        $val_field = array('no_surat', 'tanggal', 'judul');
			        $val_sorter = array('ASC', 'DESC');
			        $order_by = !empty($get_order) ? $val_field[$get_order] : $val_field[0];
			        $sorter_by = !empty($get_sorter) ? $val_sorter[$get_sorter] : $val_sorter[0];
			        $order = $order_by.' '.$sorter_by;
			        // ./order

			        // cek($where);
			        $config['total_rows'] = $this->general_model->datagrabs(array(
			            'tabel'=>array(
			            	'arsip_konten a'=>'',
			            	'peg_pegawai b'=>array('b.id_pegawai=a.op', 'left'),
			            	'peg_jabatan c'=>array('c.id_pegawai=b.id_pegawai', 'left'),
			            	'arsip_konten_role ar'=>array('ar.id_arsip_konten=a.id_arsip_konten', 'left'),
			            	'ref_bidang d'=>array('d.id_bidang=ar.id_bidang', 'left'),
			            	'ref_unit e'=>array('e.id_unit=d.id_unit', 'left')
			            	),
			            'where'=>$where,
			            'select'=>$aSelect,
			            'count'=>'count(*) jml',
			            'search'=>$sc))->row()->jml;
			        


			        $arsip_konten = $this->general_model->datagrabs(array(
			            'tabel'=>array(
			            	'arsip_konten a'=>'',
			            	'peg_pegawai b'=>array('b.id_pegawai=a.op', 'left'),
			            	'peg_jabatan c'=>array('c.id_pegawai=b.id_pegawai', 'left'),
			            	'arsip_konten_role ar'=>array('ar.id_arsip_konten=a.id_arsip_konten', 'left'),
			            	'ref_bidang d'=>array('d.id_bidang=ar.id_bidang', 'left'),
			            	'ref_unit e'=>array('e.id_unit=d.id_unit', 'left')
			            	),
			            'where'=>$where,
			            'select'=>$aSelect,
			            'search'=>$sc,
			            'order'=>$order,
			            'limit'=>$config['per_page'],
			            'offset'=>$start
			            ));

			        // cek($this->db->last_query());
			        // cek($arsip_konten);
			        $this->load->library('pagination');
			        $this->pagination->initialize($config);

			            $data['arsip_konten_data'] = $arsip_konten;
			            $data['q'] = $fcari['text'];
			            $data['search'] = in_de($fcari);
			            $data['links'] = $this->pagination->create_links();
			            $data['total'] = $config['total_rows'];
			            $data['start'] = $start;
			            $data['content'] = 'filing/arsip_view';

			           
			            

			            if ($arsip_konten->num_rows() > 0) {


			            $this->table->set_template(array('table_open'=>'<table class="table table-striped table-bordered table-condensed table-nonfluid">'));
			            $this->table->set_empty('&nbsp');
			            $heads = array(
			            array('data'=>'No.'),
			            array('data'=>'Arsip', 'class'=>'text-left', 'style'=>'white-space:nowrap;'),
			            array('data'=>'Nomor', 'class'=>'text-left', 'style'=>'white-space:nowrap;'),
			            array('data'=>'Tanggal', 'class'=>'text-left', 'style'=>'white-space:nowrap;'),
			            array('data'=>'Perihal', 'class'=>'text-left', 'style'=>'white-space:nowrap;')
			           );

			            // kolom header
			            $re_kol = array();
						$re_kol_d = $this->general_model->datagrab(array(
							'tabel' => 'ref_arsip_kolom k',
							'where' => array('id_arsip_jenis' => $id_jenis)));
						foreach($re_kol_d->result() as $re) {
							$re_kol[] = $re->kolom;
						}
			            // ./kolom header

			            $heads = array_merge_recursive($heads, $re_kol, array('<i>Operator</i>', '<i>Bidang</i>', '<i>Unit</i>'));

			            $this->table->set_heading($heads);

			


			    if ($arsip_konten->num_rows() > 0) {

			    	 // DATA ARSIP
			            $in_d = array();
							foreach($arsip_konten->result() as $q) { $in_d[] = $q->id_arsip_konten; }
							
							$file_arsip = $this->general_model->datagrab(array(
								'tabel' => array(
									'arsip a' => '',
									'arsip_konten_set s' => 's.id_arsip = a.id_arsip',
									'ref_arsip_jenis j' => 'j.id_arsip_jenis = a.id_arsip_jenis'),
								'where' => array('id_arsip_konten IN ('.implode(',',$in_d).')' => null)
							));

			            // ./DATA ARSIP


			    	// ISI KOLOM TAMBAHAN
			    	$from_kol = array(
						'arsip_konten k' => '',
						'arsip_kolom ko' => array('ko.id_arsip_konten = k.id_arsip_konten','left')
						);

					$in_d = array();
					foreach($arsip_konten->result() as $q) { $in_d[] = $q->id_arsip_konten; }
					        	$kol = $this->general_model->datagrab(array(
								'tabel' => $from_kol,
								'where' => array('k.id_arsip_konten IN ('.implode(',',$in_d).')' => null)
							));

			    	// ./ISI KOLOM TAMBAHAN
					$arsip_ids = array();
			        foreach ($arsip_konten->result() as $row) {
			        	// cek('tes iki:'.$row->op);
			        	// TAMPIL ARSIP
			    		$arsip_disp = array();
							foreach($file_arsip->result() as $ars) {
								
								
								if ($ars->id_arsip_konten == $row->id_arsip_konten) { 
									// nggo ngitung jumlah arsip
									$arsip_ids[] = $ars->id_arsip;
									// ./nggo ngitung jumlah arsip

									$del_ars = in_de(array(
									'arsip' => $ars->arsip,
									'id_arsip_jenis' => $ars->id_arsip_jenis,
									'id_arsip' => $ars->id_arsip,
									'direktori' => $ars->direktori,
									'search' => in_de($fcari),
									'offset' => $offset));
									
									$arsip_disp[] = '<li style="font-size: 9pt; white-space:nowrap">'.$ars->arsip.'</li>';
							}}
				    	// ./TAMPIL ARSIP


			            
			          $cell = array(
				            array('data'=>++$start, 'style'=>'text-align:left', 'width'=>50),
				            (count($arsip_disp) > 0) ? '<ol style="margin: 0; padding: 0 10px;">'.implode(' ',$arsip_disp).'</ol>' : null,
				            $row->no_surat,
				            array('data'=>!empty($row->tanggal) ? tanggal($row->tanggal) : '', 'class'=>'text-left', 'style'=>''),
				            array('data'=>$row->judul, 'style'=>'min-width:350px;'),
				            $row->penerima,
				                );

			          foreach($re_kol_d->result() as $re) {
							$dkol = null;
							foreach($kol->result() as $k) {
								
								if ($k->id_arsip_kolom == $re->id_arsip_kolom AND $k->id_arsip_konten == $row->id_arsip_konten) {
									$dkol = ($re->tipe == 3) ? tanggal($k->konten) : $k->konten;
								}
							}
							$cell[] = $dkol;
						}

						$cell_op = array(
							array('data'=>(!empty($row->pegawai) ? $row->pegawai : '') ,'style'=>'white-space:nowrap;font-style:italic;'),
							array('data'=>(!empty($row->nama_bidang) ? $row->nama_bidang : '<b>Arsip Bebas</b>') ,'style'=>'white-space:nowrap;font-style:italic;'),
							array('data'=>(!empty($row->unit) ? $row->unit : '') ,'style'=>'white-space:nowrap;font-style:italic;')
					
							
							);




				        $this->table->add_row(array_merge_recursive($cell, $cell_op));
 
			                }

			         // total bawah  
			         $this->table->add_row(array(
			         	array('data'=>'<b>TOTAL : '.$config['total_rows'].' Konten Arsip, '.count($arsip_ids).' Butir Arsip.</b>', 'colspan'=>2, 'class'=>'label-default', 'style'=>'white-space:nowrap; text-align:left;'),
			         	));  
			         // ./total bawah    
			        $data['cb_arsip_jenis'] = $this->general_model->combo_box(array(
							'tabel' => array(
								'ref_arsip_jenis j' => '',
								'ref_arsip_jenis_role k' => 'k.id_arsip_jenis = j.id_arsip_jenis',
								'ref_bidang b' => 'b.id_bidang = k.id_bidang',
								'ref_unit u' => 'u.id_unit = b.id_unit',
								),
							'key'=>'id_arsip_jenis',
							'val'=>array('arsip_jenis'),
							'select' => 'j.*',
							'where'=>array('k.id_bidang IN ('.implode(',', $where_bidang).')'=>null, 'j.id_par IS NULL'=>null),
							'order' => 'arsip_jenis ASC',
							'group_by' => 'k.id_arsip_jenis' 
						));

					$tabel = $this->table->generate();

					

			            }else{
			        $tabel = 'Tidak/belum ada data..';
			            }
			            }else{
			        $tabel = 'Belum ada data';
			            }



			        $btn_cetak = !empty($arsip_konten) ? 
			        '<div class="btn-group"  tyle="margin-left: 5px;">
			            <a class="btn btn-flat btn-info btn-md dropdown-toggle" data-toggle="dropdown" href="#" style="margin: 0 0 0 5px">
			                <i class="fa fa-print"></i> <span class="caret"></span>
			            </a>
			            <ul class="dropdown-menu" role="menu">
			                <li>'.anchor(site_url('filing/laporan/agenda'), '<i class="fa fa-book"></i> Buka Buku Agenda (Print &amp; Excel)').'</li>
			                <li>'.anchor(site_url('filing/laporan/pdf/'.$id_jenis.'/'.in_de($sc).'/'.in_de($between).'/'.in_de($this->tampug_aj).'/'.in_de($order).'/'.$offset), '<i class="fa fa-print"></i> Cetak / Pdf', 'target="_blank" class=""').'</li>
			            </ul>
			        </div>' : null;

			        
			        $data['tombol'] = $btn_cetak;

			        $data['breadcrumb'] = array('filing/' => 'Data', 'filing/laporan' => 'Pengarsipan');
			        $data['page_title'] = 'Pengarsipan';
			        $data['page_des'] = '';
			        $data['bmain_page'] = 'Data';
			        $data['bsub_page'] = 'Pengarsipan';
			        $data['title'] = '<small>List</small> Pengarsipan';

		// ./jika ada id_jenis
		}
		// ./KONTEN ARSIP

			// ./arsip jenis side bar
			$data['table_jenis'] = '<ul class="direktori">'.@$table_jenis_all.$table_jenis.'</ul>';	
			$data['tabel'] =$tabel;	
		// ./SIDE BAR ARSIP JENIS
			$data['title'] = 'Konten Arsip';

			
			
			$data['content'] = "filing/laporan_view";
			$this->load->view('home', $data);
	}

	function tabel_sub($level,$id, $arsip_aktif=null, $where_ar_kt_role=array(0)) {


		
		$from = array('ref_arsip_jenis j' => '',
						'ref_arsip_jenis_role k' => 'k.id_arsip_jenis = j.id_arsip_jenis');


		
		$sub_jenis = $this->general_model->datagrabs(array(
			'tabel' => $from,
			'where' => array('j.id_par' => $id),
			'select' => 'j.*',
			'group_by' => 'j.id_arsip_jenis',
			'order' => 'j.urut, j.arsip_jenis',

		));

		$awal_sub = array();
		
		if ($sub_jenis->num_rows() > 0) {
			foreach ($sub_jenis->result() as $row) {
				$awal_sub[] = array($row->id_arsip_jenis,$row->urut);
			}

			$r = 0;
			foreach($sub_jenis->result() as $row) {
				$this->tampug_aj[] = $row->id_arsip_jenis;
				$jml_konten = $this->general_model->datagrabs(array(
			            'tabel'=>'arsip_konten',
			            'where'=>array('id_arsip_jenis'=>$row->id_arsip_jenis, '(id_arsip_konten IN ('.implode(',', $where_ar_kt_role).'))'=>null),
			            'select'=>'ifnull(count(id_arsip_jenis), 0) jml_konten'))->row()->jml_konten;

				$folder_aktif = ($row->id_arsip_jenis == $arsip_aktif) ? '#00c0ef' : '#d2d6de';

				$cell = array();
			
				for ($j=0;$j < $level; $j++) {
					if ($j>0) $cell[] = array('data'=>'','width' => '20');
				}
				
				
				
				$sub_jenis2 = $this->general_model->datagrabs(array('tabel' => $from, 'where' => array('id_par' => $row->id_arsip_jenis)));

			

				$act = ($row->id_arsip_jenis == $arsip_aktif) ? 'class="active"':null;
				$bg_col = ($row->id_arsip_jenis == $arsip_aktif) ? 'bg-active link-normal':null;
				$font = ($row->id_arsip_jenis == $arsip_aktif) ? 'font-weight: bold;':null;
				$p = ($row->id_arsip_jenis == $arsip_aktif) ? 'label-primary':'label-default';
				$se = in_de(array(
					  		'arsip_judul' => $row->arsip_jenis,
					  		'id_arsip_jenis' => $row->id_arsip_jenis
				  		));
				  		
				  		$jml = null;

				  		
				  		$btn =  '<li '.$act.'>'.anchor('filing/laporan/list_data/'.
				  			in_de($row->id_arsip_jenis),$row->arsip_jenis.' <span class="label label-default pull-right">'.$jml_konten.'</span>','style="font-size: 0.95em; '.$font.'"').'</li>';
				
				$cell[] = array('data'=>'<i class="fa fa-folder" style="color:'.$folder_aktif.';"></i>', 'style'=>'width:5px');
				$cell[] = array('data'=>$btn, 'colspan' => 8-$level, 'class'=>$bg_col);
				$cell = array_merge_recursive($cell);
			
				$this->table->add_row($cell);
				if ($sub_jenis2->num_rows() > 0) $this->tabel_sub($level+1,$row->id_arsip_jenis, $arsip_aktif, $where_ar_kt_role);
				$r+=1;
			}
		}
		
	}

	function pdf($id_jenis=null, $sc =null, $between=null, $tampug_aj=null, $order=null, $offset=0) {
		// SET ARSIP BY ROLE
		$start = !empty($offset)? intval($offset) :0;
		$tampug_aj = un_de($tampug_aj);
		$sc = un_de($sc);
		$between = un_de($between);

		$order = un_de($order);

		$order = !empty($order) ? $order : 'no_surat ASC';

		if (!empty($between)) {
			$get_mulai = $between['mulai'];
			$get_selesai = $between['selesai'];
		}else{
			$get_mulai = null;
			$get_selesai = null;
		}

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
		$this->unit_bidang_bawah[] = $this->session->userdata('id_bidang');
		$this->operators_data[] = $this->session->userdata('id_pegawai');

		$where_bidang = array();
		if (count($this->unit_bidang_bawah) > 0) $where_bidang = array_merge_recursive($where_bidang,$this->unit_bidang_bawah);

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

		// data:
			$aSelect = '*';
			
			$where_jenis = ($id_jenis == 'all') ? array('(id_arsip_jenis IN ('.implode(',', $tampug_aj).'))'=>null) : array('id_arsip_jenis'=>$id_jenis);

	        $where = (!empty($get_mulai) and !empty($get_selesai)) ? array_merge_recursive($where_jenis, array('(id_arsip_konten IN ('.implode(',', $where_ar_kt_role).'))'=>null, '(tanggal BETWEEN "'.tanggal_php($get_mulai).'" AND "'.tanggal_php($get_selesai).'")'=>null)) : array_merge_recursive($where_jenis, array('(id_arsip_konten IN ('.implode(',', $where_ar_kt_role).'))'=>null));


	        // cek($where);
	        $config['total_rows'] = $this->general_model->datagrabs(array(
	            'tabel'=>'arsip_konten',
	            'where'=>$where,
	            'select'=>$aSelect,
			    'count'=>'count(id_arsip_konten) jml',
	            'search'=>$sc))->row()->jml;
	        


	        $arsip_konten = $this->general_model->datagrabs(array(
	            'tabel'=>'arsip_konten',
	            'where'=>$where,
	            'select'=>$aSelect,
	            'search'=>$sc,
	            'limit'=>20,
	            'offset'=>$offset,
	            'order'=>$order
	            ));

	        // cek($this->db->last_query());
	        // cek($arsip_konten);

	            $data['arsip_konten_data'] = $arsip_konten;

	            $data['total'] = null;

	            if ($arsip_konten->num_rows() > 0) {


	            $this->table->set_template(array('table_open'=>'<table class="bpmTopnTailC" width="100%" style="font-size: 10pt; border-collapse: collapse; " cellpadding="8">'));
	            $this->table->set_empty('');
	            $heads = array(
	            array('data'=>'No.'),
	            array('data'=>'Arsip', 'class'=>'headerrow', 'style'=>'white-space:nowrap;'),
	            array('data'=>'Nomor', 'class'=>'headerrow', 'style'=>'white-space:nowrap;text-align:left;'),
	            array('data'=>'Tanggal', 'class'=>'headerrow', 'style'=>'white-space:nowrap;text-align:left;'),
	            array('data'=>'Perihal', 'class'=>'headerrow', 'style'=>'white-space:nowrap;text-align:left;')
	           );

	            // kolom header
	            $re_kol = array();
				$re_kol_d = $this->general_model->datagrab(array(
					'tabel' => 'ref_arsip_kolom k',
					'where' => array('id_arsip_jenis' => $id_jenis)));
				foreach($re_kol_d->result() as $re) {
					$re_kol[] = array('data'=>(!empty($re->kolom) ? $re->kolom : ''), 'style'=>'text-align:left;');
				}
	            // ./kolom header
	            $heads = array_merge_recursive($heads, $re_kol);

	            $this->table->set_heading($heads);

			

	            $start = 0;
			    if ($arsip_konten->num_rows() > 0) {

			    	 // DATA ARSIP
			            $in_d = array();
							foreach($arsip_konten->result() as $q) { $in_d[] = $q->id_arsip_konten; }
							
							$file_arsip = $this->general_model->datagrab(array(
								'tabel' => array(
									'arsip a' => '',
									'arsip_konten_set s' => 's.id_arsip = a.id_arsip',
									'ref_arsip_jenis j' => 'j.id_arsip_jenis = a.id_arsip_jenis'),
								'where' => array('id_arsip_konten IN ('.implode(',',$in_d).')' => null)
							));

			            // ./DATA ARSIP


			    	// ISI KOLOM TAMBAHAN
			    	$from_kol = array(
						'arsip_konten k' => '',
						'arsip_kolom ko' => array('ko.id_arsip_konten = k.id_arsip_konten','left')
						);

					$in_d = array();
					foreach($arsip_konten->result() as $q) { $in_d[] = $q->id_arsip_konten; }
					        	$kol = $this->general_model->datagrab(array(
								'tabel' => $from_kol,
								'where' => array('k.id_arsip_konten IN ('.implode(',',$in_d).')' => null)
							));

			    	// ./ISI KOLOM TAMBAHAN
					$arsip_ids = array();
			        foreach ($arsip_konten->result() as $row) {
			        	// cek('tes iki:'.$row->op);
			        	// TAMPIL ARSIP
			    		$arsip_disp = array();
							foreach($file_arsip->result() as $ars) {
								
								
								if ($ars->id_arsip_konten == $row->id_arsip_konten) { 
									$arsip_ids[] = $ars->id_arsip;
									$del_ars = in_de(array(
									'arsip' => $ars->arsip,
									'id_arsip_jenis' => $ars->id_arsip_jenis,
									'id_arsip' => $ars->id_arsip,
									'direktori' => $ars->direktori
									));
									
									$arsip_disp[] = '<li style="font-size:8pt;">'.$ars->arsip.'</li>';
									
							}}
				    	// ./TAMPIL ARSIP

			        	 $del_ar = in_de(array(
								'id' => $row->id_arsip_konten,
								'id_jenis' => $row->id_arsip_jenis
								));
						$del_msg = 'Apakah Anda ingin menghapus arsip ini?<br>Menghapus konten arsip ini akan menghapus seluruh arsip fisik di dalamnya!';
			            
			          $cell = array(
				            array('data'=>++$start.')', 'style'=>'text-align:left', 'width'=>50),
				            array('data'=>(count($arsip_disp) > 0) ? '<ol style="margin: 0; padding: 0 10px;">'.implode(' ',$arsip_disp).'</ol>' : '', 'style'=>'text-align:left;'),
				            array('data'=>$row->no_surat, 'class'=>'evenrow', 'style'=>'text-align:left;'),
				            array('data'=>!empty($row->tanggal) ? tanggal($row->tanggal) : '', 'class'=>'text-left evenrow', 'style'=>'text-align:left;'),
				            array('data'=>$row->judul, 'class'=>'evenrow', 'style'=>'min-width:350px; text-align:left;'),
				                );

			          foreach($re_kol_d->result() as $re) {
							$dkol = null;
							foreach($kol->result() as $k) {
								
								if ($k->id_arsip_kolom == $re->id_arsip_kolom AND $k->id_arsip_konten == $row->id_arsip_konten) {
									$dkol = ($re->tipe == 3) ? tanggal($k->konten) : $k->konten;
								}
							}
							$cell[] = array('data'=>(!empty($dkol) ? $dkol : ''), 'style'=>'text-align:left;');
						}


				        $this->table->add_row($cell);
 
			                }

			        // total bawah  
			         $this->table->add_row(array(
			         	array('data'=>'<b>TOTAL : '.$config['total_rows'].' Konten Arsip, '.count($arsip_ids).' Butir Arsip.</b>', 'colspan'=>2, 'class'=>'label-default', 'style'=>'white-space:nowrap; text-align:left;'),
			         	));  
			         // ./total bawah 


			        $data['cb_arsip_jenis'] = $this->general_model->combo_box(array(
							'tabel' => array(
								'ref_arsip_jenis j' => '',
								'ref_arsip_jenis_role k' => 'k.id_arsip_jenis = j.id_arsip_jenis',
								'ref_bidang b' => 'b.id_bidang = k.id_bidang',
								'ref_unit u' => 'u.id_unit = b.id_unit',
								),
							'key'=>'id_arsip_jenis',
							'val'=>array('arsip_jenis'),
							'select' => 'j.*',
							'where'=>array('k.id_bidang IN ('.implode(',', $where_bidang).')'=>null, 'j.id_par IS NULL'=>null),
							'order' => 'arsip_jenis ASC',
							'group_by' => 'k.id_arsip_jenis' 
						));

					$tabel = $this->table->generate();

					
			            }else{
			        $tabel = 'Tidak/belum ada data..';
			            }
			            }else{
			        $tabel = 'Belum ada data';
			            }


		$st = get_stationer();
		$path_inst_logo = !empty($st['pemerintah_logo']) ? FCPATH.'logo/'.$st['pemerintah_logo'] : null;
		$logo_instansi = (file_exists($path_inst_logo) and !empty($st['pemerintah_logo'])) ? base_url().'logo/'.$st['pemerintah_logo'] : base_url().'assets/logo/brand.png';

		$data = array(
            'h_title_left'=> 'Efiling',
            'h_title_center'=> '<span>Laporan</span><br/> Data Konten Arsip',
            'h_sub_center'=> '',
            'tgl_cetak'=> date('d-M-Y'),
            'company_address'=>'',        
            'company_country'=>'',        
            'company_logo'=>$logo_instansi,        
           // 'company_logo'=>$logo_instansi      
            );

		$data['table'] = $tabel;
		$data['title'] = 'Laporan Konten Arsip';
		// $data['content'] = 'filing/pdf_view';
		// $this->load->view('home', $data);
		 ini_set('memory_limit', '512M');
        $html = $this->load->view('filing/pdf_view', $data, true);
        $this->load->library('pdf');
        $parameters= array(
                'mode' => 'utf-8',
                'format' => 'A4-L',    // A4 for portrait
                'default_font_size' => '14',
                'default_font' => '',
                'margin_left' => 20,
                'margin_right' => 15,
                'margin_top' => 48,
                'margin_bottom' => 10,
                'margin_header' => 20,
                'margin_footer' => 10,
                'orientation' => 'L' // For some reason setting orientation to "L" alone doesn't work (it should), you need to also set format to "A4-L" for landscape
            );

        $pdf = $this->pdf->load($parameters);
        // setting
        $pdf->SetProtection(array('print'));
        $pdf->SetTitle("");
        $pdf->SetAuthor("");
        $pdf->SetWatermarkText("");
        $pdf->showWatermarkText = true;
        $pdf->watermark_font = 'dejavusans';
        $pdf->watermarkTextAlpha = 0.1;
        $pdf->SetDisplayMode('fullpage');
        // .setting
        $stylesheet = file_get_contents(base_url('assets/plugins/mpdf/mpdfstyletables.css'));
        $pdf->WriteHTML($stylesheet,1);
        $pdf->WriteHTML($html);
        $pdf->Output('arsip_konten.pdf', 'I'); 
        // $pdf->Output(); 
		// ./data
	}


	/**
	 * Laporan Buku Agenda Tunggal (Surat Masuk & Keluar)
	 * Kolom: No | Nomor Surat | Tanggal Surat | M/K | Dari / Kepada | Perihal | Keterangan
	 * Mendukung Filter Periode, Jenis Surat (M/K), Jenis Arsip, Cetak (Print), dan Export Excel (.xls)
	 */
	public function agenda($mode = null) {
		$tgl_mulai = trim((string) $this->input->get('tgl_mulai'));
		$tgl_selesai = trim((string) $this->input->get('tgl_selesai'));
		$jenis_surat = trim((string) $this->input->get('jenis_surat'));
		$id_arsip_jenis = (int) $this->input->get('id_arsip_jenis');
		$q = trim((string) $this->input->get('q'));

		$sql = "SELECT k.*, j.arsip_jenis, p.nama AS nama_operator
				FROM arsip_konten k
				LEFT JOIN ref_arsip_jenis j ON j.id_arsip_jenis = k.id_arsip_jenis
				LEFT JOIN peg_pegawai p ON p.id_pegawai = k.op
				WHERE 1=1";
		$binds = array();

		if ($tgl_mulai !== '' && $tgl_selesai !== '') {
			$d1 = (strpos($tgl_mulai, '/') !== FALSE) ? tanggal_php($tgl_mulai) : $tgl_mulai;
			$d2 = (strpos($tgl_selesai, '/') !== FALSE) ? tanggal_php($tgl_selesai) : $tgl_selesai;
			$sql .= " AND k.tanggal BETWEEN ? AND ?";
			$binds[] = $d1;
			$binds[] = $d2;
		} elseif ($tgl_mulai !== '') {
			$d1 = (strpos($tgl_mulai, '/') !== FALSE) ? tanggal_php($tgl_mulai) : $tgl_mulai;
			$sql .= " AND k.tanggal >= ?";
			$binds[] = $d1;
		} elseif ($tgl_selesai !== '') {
			$d2 = (strpos($tgl_selesai, '/') !== FALSE) ? tanggal_php($tgl_selesai) : $tgl_selesai;
			$sql .= " AND k.tanggal <= ?";
			$binds[] = $d2;
		}

		if (in_array($jenis_surat, array('M', 'K'))) {
			$sql .= " AND k.jenis_surat = ?";
			$binds[] = $jenis_surat;
		}
		if ($id_arsip_jenis > 0) {
			$sql .= " AND k.id_arsip_jenis = ?";
			$binds[] = $id_arsip_jenis;
		}
		if ($q !== '') {
			$like = '%'.$q.'%';
			$sql .= " AND (k.no_surat LIKE ? OR k.judul LIKE ? OR k.pengirim LIKE ? OR k.penerima LIKE ? OR k.keterangan LIKE ?)";
			$binds = array_merge($binds, array($like, $like, $like, $like, $like));
		}
		$sql .= " ORDER BY k.tanggal DESC, k.id_arsip_konten DESC";

		$rows = !empty($binds) ? $this->db->query($sql, $binds)->result() : $this->db->query($sql)->result();
		$jenis_list = $this->db->query("SELECT id_arsip_jenis, arsip_jenis FROM ref_arsip_jenis ORDER BY arsip_jenis ASC")->result();

		$st = get_stationer();
		$judul_agenda = 'BUKU AGENDA SURAT MASUK DAN KELUAR';
		if ($jenis_surat == 'M') $judul_agenda = 'BUKU AGENDA SURAT MASUK (M)';
		elseif ($jenis_surat == 'K') $judul_agenda = 'BUKU AGENDA SURAT KELUAR (K)';

		$periode_label = 'Semua Periode';
		if ($tgl_mulai !== '' && $tgl_selesai !== '') {
			$periode_label = $tgl_mulai.' s/d '.$tgl_selesai;
		} elseif ($tgl_mulai !== '') {
			$periode_label = 'Mulai '.$tgl_mulai;
		} elseif ($tgl_selesai !== '') {
			$periode_label = 'Sampai '.$tgl_selesai;
		}

		$data = array(
			'rows' => $rows,
			'jenis_list' => $jenis_list,
			'st' => $st,
			'judul_agenda' => $judul_agenda,
			'periode_label' => $periode_label,
			'filter_mulai' => $tgl_mulai,
			'filter_selesai' => $tgl_selesai,
			'filter_jenis_surat' => $jenis_surat,
			'filter_id_jenis' => $id_arsip_jenis,
			'filter_q' => $q,
			'mode' => $mode
		);

		if ($mode === 'excel') {
			$filename = 'buku_agenda_'.date('Ymd_His').'.xls';
			header('Content-Type: application/vnd.ms-excel; charset=utf-8');
			header('Content-Disposition: attachment; filename="'.$filename.'"');
			$this->load->view('filing/buku_agenda_cetak', $data);
			return;
		} elseif ($mode === 'cetak') {
			$this->load->view('filing/buku_agenda_cetak', $data);
			return;
		}

		$data['title'] = 'Laporan Buku Agenda Tunggal (M/K)';
		$data['descript'] = 'Rekapitulasi surat masuk &amp; keluar per periode dengan fasilitas Cetak &amp; Ekspor Excel';
		$data['content'] = 'filing/buku_agenda_view';
		$this->load->view('home', $data);
	}

}
?>