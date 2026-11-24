<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengarsipan extends CI_Controller {

	var $unit_akar_atas = array();
	var $unit_akar_bawah = array();
	var $bidang_akar_atas = array();
	var $bidang_akar_bawah = array();
	var $par_id = array();
	var $par_active = null;
	// var $total_konten = null;

	function __construct() {
		
		parent::__construct();
		login_check($this->session->userdata('login_state'));
		
		$this->load->helper('msg_helper');
		$this->load->helper('cmd_helper');
	}

	public function index() {

		$this->list_data();
	
	}
	

	
	function set_unit_bawah($e) {
		if (!empty($e)) {
		$count_dt_ats = $this->general_model->datagrabs(array(
			'tabel' => 'ref_unit',
			'count' =>'count(*) jm',
			'where' => array('id_par_unit' => $e)
			))->row()->jm;

		$dt_ats = $this->general_model->datagrabs(array(
			'tabel' => 'ref_unit',
			'where' => array('id_par_unit' => $e)
			));

		if ($count_dt_ats > 0) {
			foreach ($dt_ats->result() as $r_u) {
				$this->unit_bawah[] = $r_u->id_unit;
				$this->set_unit_bawah($r_u->id_unit);

				// get bidang:
				$count_dt_bidang = $this->general_model->datagrabs(array(
					'tabel'=>'ref_bidang',
					'count' => 'count(*) jm',
					'where'=>array('id_unit'=>$r_u->id_unit)))->row()->jm;

				$dt_bidang = $this->general_model->datagrabs(array(
					'tabel'=>'ref_bidang',
					'where'=>array('id_unit'=>$r_u->id_unit)));
				// cek($this->db->last_query());
				if ($count_dt_bidang > 0) {
					foreach ($dt_bidang->result() as $r_b) {
						$this->unit_bidang_bawah[] = $r_b->id_bidang;

						$this->set_bidang_bawah($r_b->id_bidang);
						// cek($this->unit_bidang_bawah);

						// get operator
							$count_jabatan = $this->general_model->datagrabs(array(
								'tabel'=>'peg_jabatan',
								'count'=>'count(*) jm',
								'where'=>array('id_bidang'=>$r_b->id_bidang)))->row()->jm;

							$jabatan = $this->general_model->datagrabs(array(
								'tabel'=>'peg_jabatan',
								'where'=>array('id_bidang'=>$r_b->id_bidang)));
											// cek($this->db->last_query());

							if ($count_jabatan > 0) {
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
		$count_dt_ats = $this->general_model->datagrabs(array(
			'tabel' => 'ref_bidang',
			'count' => 'count(*) jm',
			'where' => array('id_par_bidang' => $e)))->row()->jm;

		$dt_ats = $this->general_model->datagrab(array('tabel' => 'ref_bidang','where' => array('id_par_bidang' => $e)));

		if ($count_dt_ats > 0) {
			
			foreach ($dt_ats->result() as $r_b) {
				$this->unit_bidang_bawah[] = $r_b->id_bidang;

				$this->set_bidang_bawah($r_b->id_bidang);

				// get operator
				$count_jabatan = $this->general_model->datagrabs(array(
					'tabel'=>'peg_jabatan',
					'count'=>'count(*) jm',
					'where'=>array('id_bidang'=>$r_b->id_bidang)))->row()->jm;

				$jabatan = $this->general_model->datagrabs(array(
					'tabel'=>'peg_jabatan',
					'where'=>array('id_bidang'=>$r_b->id_bidang)));
				// cek($this->db->last_query());
				if ($count_jabatan > 0) {
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


	function list_data($id_jenis=null, $search =null, $offset = null) {
		// cek($this->session->userdata);

		$this->get_par_folder(un_de($id_jenis));
		
		$get_bidang = $this->general_model->datagrabs(array(
			'tabel'=>array(
				'ref_bidang a'=>'',
				'ref_unit b'=>'b.id_unit=a.id_unit',
				),
			'where'=>array('a.id_bidang'=>$this->session->userdata('id_bidang'))))->row();

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
		
		// settingkewenangan konten arsip
		$r_setrolearsip = $this->general_model->check_role($id_peg,'ASRSR');
		// ./settingkewenangan konten arsip
		
		$offset = !empty($offset) ? $offset : null;
			
		
		// UNIT
		$this->unit_bawah = array();
		$this->unit_bidang_bawah = array();

		$this->operators_data = array();

		$this->set_unit_bawah($this->session->userdata('id_unit'));
		// cek($this->unit_bawah);
		// ./UNIT		
		
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
		$count_aj = @$this->general_model->datagrabs(array(
			'tabel' => array(
				'ref_arsip_jenis j' => '',
				'ref_arsip_jenis_role k' => 'k.id_arsip_jenis = j.id_arsip_jenis',
				'ref_bidang b' => 'b.id_bidang = k.id_bidang',
				'ref_unit u' => 'u.id_unit = b.id_unit',
				),
			'count' => 'count(*) jm',
			'where'=>array('k.id_bidang IN ('.implode(',', $where_bidang).')'=>null, 'j.id_par IS NULL'=>null),
			'order' => 'arsip_jenis ASC',
			'group_by' => 'k.id_arsip_jenis' 
		))->row()->jm;
		// cek($this->db->last_query());

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
					$count_get_arsip_konten_bebas = $this->general_model->datagrabs(array(
						'tabel'=>'arsip_konten',
						'count'=>'count(*) jm',
						'where'=>array('id_arsip_konten NOT IN (select id_arsip_konten from arsip_konten_role)'=>null)))->row()->jm;

					$get_arsip_konten_bebas = $this->general_model->datagrabs(array(
						'tabel'=>'arsip_konten',
						'where'=>array('id_arsip_konten NOT IN (select id_arsip_konten from arsip_konten_role)'=>null)));
					$kt_bebas = array();
					if ($count_get_arsip_konten_bebas > 0) {
						foreach ($get_arsip_konten_bebas->result() as $r_akt) {
							$kt_bebas[] = $r_akt->id_arsip_konten;
						}
					}


					if (count($kt_bebas) > 0)  $where_ar_kt_role = array_merge_recursive($where_ar_kt_role, $kt_bebas);

					// cek($where_ar_kt_role);

					// ./cek arsip bebas / arsip belum dapat kewenangan

					// get arsip konten in role
					$count_arsip_kt_role = @$this->general_model->datagrabs(array(
						'tabel'=>'arsip_konten_role',
						'count'=>'count(*) jm',
						'where'=>array('(id_bidang IN ('.implode(',', $where_bidang).'))'=>null),
						'group_by'=>'id_arsip_konten'))->row()->jm;

					$arsip_kt_role = $this->general_model->datagrabs(array(
						'tabel'=>'arsip_konten_role',
						'where'=>array('(id_bidang IN ('.implode(',', $where_bidang).'))'=>null),
						'group_by'=>'id_arsip_konten'));

					$kt_role = array();
					if ($count_arsip_kt_role > 0) {
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
			// cek($where_ar_kt_role);

		// arsip jenis side bar

			if ($count_aj > 0) {
				$this->tampug_aj = array();
				$r = 0;

				// TAB KIRI VERSI KOLAPS
				// menu jenis arsip
				$tab_kiri = null;

				foreach ($tb_arsip_jenis->result() as $r_kiri) { //foreach jenis arsip
					$this->tampug_aj[] = $r_kiri->id_arsip_jenis;

					$jml_konten_aj = $this->general_model->datagrabs(array(
			            'tabel'=>'arsip_konten',
			            'where'=>array('id_arsip_jenis'=>$r_kiri->id_arsip_jenis, '(id_arsip_konten IN ('.implode(',', $where_ar_kt_role).'))'=>null),
			            'select'=>'ifnull(count(id_arsip_jenis), 0) jml_konten'))->row()->jml_konten;

					$total_konten = $this->get_total_konten($r_kiri->id_arsip_jenis, $where_bidang, $where_ar_kt_role);

					$tab_aktif = ($r_kiri->id_arsip_jenis == $id_jenis) ? '#ffffff' : '#000000';
					$tab_bg_col = ($r_kiri->id_arsip_jenis == $id_jenis) ? 'bg-active link-normal':null;
					$r_kiri_aktif = $id_jenis;
					$kode_tab = '<b>'.$r_kiri->kode_arsip_jenis.'</b>';
					$nama_tab = $r_kiri->arsip_jenis;

					$count_sub_aj = $this->general_model->datagrabs(array(
					'tabel' => array(
						'ref_arsip_jenis j' => '',
						'ref_arsip_jenis_role k' => 'k.id_arsip_jenis = j.id_arsip_jenis'),
					'count' => 'count(*) jm',
					'where'=>array('id_par'=>$r_kiri->id_arsip_jenis, 'k.id_bidang IN ('.implode(',', $where_bidang).')'=>null),
					))->row()->jm;

					// cek status parent
					$tab_btn = $r_kiri->id_arsip_jenis == $id_jenis ? 'bg-folder-active' : (($count_sub_aj > 0) ? 'bg-folder-par' : 'bg-folder-child');
					$tab_stat = ($count_sub_aj > 0) ? 1 : null;
					// ./cek status parent

					$par_arr = $this->par_id;

					if (!empty($par_arr)) {
						$collapse_aktif = in_array($r_kiri->id_arsip_jenis, $par_arr) ? 'style="display:none !important;"' : null;
						// cek($collapse_aktif);
						// cek($par_arr);
					}

					if($jml_konten_aj > 0 && $count_sub_aj > 0) {
						$tampil_tot_konten = ($total_konten-$jml_konten_aj).'(+'.$jml_konten_aj.')';
					}else{
						$tampil_tot_konten = $total_konten;
					}


					$tab_kiri .= '<li  style="margin: 8px 0 4px 0">';
					$tab_kiri .= ($count_sub_aj > 0) ? '<button title="" type="button" class="btn btn-xs btn-default btn-rounded tree-toggle-tabkiri nav-header" style="color:#00a65a;"><i class="fa '.(!empty($collapse_aktif) ? 'fa-caret-down' : 'fa-caret-right').'"> </i></button>&nbsp;' : '<span style="padding-right:22px;"></span>';
					
					$tab_kiri .=anchor('filing/pengarsipan/list_data/'.in_de($r_kiri->id_arsip_jenis), '<i class="fa fa-folder" style="color:#f39c12;"></i> <b>'.strtoupper(substr($r_kiri->arsip_jenis, 0, 15)).(strlen($r_kiri->arsip_jenis) > 15 ? ' ...':null).'</b><p class="label label-danger count-file-label pull-right">'.$tampil_tot_konten.'</p>', 'class="btn btn-xs '.$tab_btn.'" title="'.strtoupper($r_kiri->arsip_jenis).'" style="color:'.$tab_aktif.'"');

					if ($count_sub_aj > 0){
						$tab_kiri .= '<ul class="nav nav-list tree" '.@$collapse_aktif.'>';
						$tab_kiri .= $this->tabel_sub_tab_kiri(2, $r_kiri->id_arsip_jenis, $r_kiri_aktif, $where_bidang, $where_ar_kt_role)['nav'];
							$r += 1;
						$tab_kiri .= '</ul>';
				}

				$tab_kiri .= '</li><li class="divider"></li>';
					
				} //end of foreach jenis arsip

				// cek($this->par_id);

				$tab_kiri = $tab_kiri;
				// ./menu jenis arsip
				// ./TAB KIRI VERSI KOLAPS

				// all jenis menu sidebar
				$jml_konten = $this->general_model->datagrabs(array(
			            'tabel'=>'arsip_konten',
			            'where'=>array('(id_arsip_jenis IN ('.implode(',', $this->tampug_aj).'))'=>null, '(id_arsip_konten IN ('.implode(',', $where_ar_kt_role).'))'=>null),
			            'select'=>'ifnull(count(id_arsip_jenis), 0) jml_konten'))->row()->jml_konten;

				$folder_aktif = ($id_jenis == 'all') ? '#00c0ef' : '#f39c12';
				$bg_col = ($id_jenis == 'all') ? 'bg-active link-normal':null;

				$row_al_jenis = array();
				$row_al_jenis[] = '<i class="fa fa-folder" style="color:'.$folder_aktif.';"></i>';
				$row_al_jenis[] = array('data'=>'<li>'.anchor('filing/pengarsipan/list_data/'.in_de('all'), 'SEMUA ARSIP <span style="font-size:12pt; color:red;">*</span> <span class="label label-primary pull-right">'.$jml_konten.'</span>','style="font-size: 0.95em;"').'</li>', 'colspan'=>7, 'class'=>$bg_col);

				$this->table->set_template(array('table_open'=>'<table class="tabel-direktori">'));
				$this->table->add_row($row_al_jenis);
				$table_jenis_all = $this->table->generate();
				// ./all jenis sidebar

			// untuk keperluan pindah arsip
				$data_nav = null;
				foreach ($tb_arsip_jenis->result() as $aj) {


					$folder_aktif = ($aj->id_arsip_jenis == $id_jenis) ? '#00c0ef' : '#f39c12';
					$bg_col = ($aj->id_arsip_jenis == $id_jenis) ? 'bg-active link-normal':null;
					$aj_aktif = $id_jenis;

				  	// data
					$rows = array();

					$kode = '<b>'.$aj->kode_arsip_jenis.'</b>';
					$nama = $aj->arsip_jenis;

					$count_sub_jenis = $this->general_model->datagrabs(array(
					'tabel' => array(
						'ref_arsip_jenis j' => '',
						'ref_arsip_jenis_role k' => 'k.id_arsip_jenis = j.id_arsip_jenis'),
					'count' => 'count(*) jm',
					'where'=>array('id_par'=>$aj->id_arsip_jenis),
					))->row()->jm;

					$sub_jenis = $this->general_model->datagrabs(array(
					'tabel' => array(
						'ref_arsip_jenis j' => '',
						'ref_arsip_jenis_role k' => 'k.id_arsip_jenis = j.id_arsip_jenis'),
					'select' => 'j.*',
					'where'=>array('id_par'=>$aj->id_arsip_jenis),
					));

					// cek status parent

					
					$par_btn = ($count_sub_jenis > 0) ? 'btn-primary' : 'btn-default';
					$par_stat = ($count_sub_jenis > 0) ? 1 : null;
					// ./cek status parent
					// cek($this->db->last_query());

					$data_nav .= '<li  style="margin: 5px 0 5px 0">';

					$data_nav .= '<input '.($aj->id_arsip_jenis == $id_jenis ? 'disabled' : null).' type="radio" name="id_pindah" id="id_pindah'.$aj->id_arsip_jenis.'" value="'.$aj->id_arsip_jenis.'" />';

					$data_nav .=' &nbsp;<button type="button" class="tree-toggle nav-header btn btn-xs btn-flat '.$par_btn.'" '.($par_stat == 1 ? null : '').'><i class="fa fa-folder" style="color:#f39c12;"></i> '.$aj->arsip_jenis.'</button>';


					if ($count_sub_jenis > 0){
						$data_nav .= '<ul class="nav nav-list tree">';
						$data_nav .= $this->tabel_sub_for_pindah(2, $aj->id_arsip_jenis, $aj_aktif);
							$r += 1;
						$data_nav .= '</ul>';
				}

				$data_nav .= '</li><li class="divider"></li>';
					
				}

				// $aj_for_pindah = $this->table->generate();
				$aj_for_pindah = $data_nav;
				// ./untuk keperluan pindah arsip
			}else{
				// $table_jenis = 'Tidak/belum ada data Jenis Arsip';
				$tab_kiri = '<ul style="margin: 5px 0 5px 0"><li class="nav nav-list">Tidak/belum ada data jenis arsip</li></ul>';
				$aj_for_pindah = 'Tidak/belum ada data Jenis Arsip';
			}
		
		
		$data['breadcrumb'] = array('' => 'Referensi','filing/pengarsipan' => 'Konten Arsip');

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

					<p style="font-size:14pt;"><i class="fa fa-arrow-right"></i> Klik ikon <i class="fa fa-caret-right" style="color:green"></i> atau <i class="fa fa-caret-down" style="color:green"></i> untuk membuka / menggulung sub folder arsip.</p>
						
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
			        

			        $config['base_url'] = base_url() . 'filing/pengarsipan/list_data/' .in_de($id_jenis).'/'. in_de($fcari);
			        $config['first_url'] = base_url() . 'filing/pengarsipan/list_data/' .in_de($id_jenis).'/'. in_de($fcari);


					$aSelect = 'a.*, b.nama pegawai, d.nama_bidang, e.unit';
					
					$config['per_page'] = 20;
			       // $config['page_query_string'] = TRUE;
			        $config['uri_segment'] = '6';

			        // cek($this->tampug_aj);

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
			        // cek($this->db->last_query());
			        


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
			            'offset'=>$start));

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

			           
			            

			        if ($config['total_rows'] > 0) {


			            $this->table->set_template(array('table_open'=>'<table class="table table-striped table-bordered table-condensed table-nonfluid">'));
			            $this->table->set_empty('&nbsp');
			            $heads = array(
						array('data'=>'<input type="checkbox" name="cek_all" id="cek_arsip_all" class="cek-arsip-all"','class'=>'center','style'=>'min-width:20px'),
			            array('data'=>'No.'),
			            array('data'=>'Arsip', 'class'=>'text-left', 'style'=>'white-space:nowrap;'),
			            array('data'=>'Nomor', 'class'=>'text-left', 'style'=>'white-space:nowrap;'),
			            array('data'=>'Tanggal', 'class'=>'text-left', 'style'=>'white-space:nowrap;'),
			            array('data'=>'Perihal/Judul', 'class'=>'text-left', 'style'=>'white-space:nowrap;')
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

			            $heads = array_merge_recursive($heads, $re_kol, array('<i>Operator</i>', '<i>Bidang</i>', '<i>Unit</i>'), ($r_editkonten || $r_delkonten) ?array(array('data'=>'Aksi', 'colspan'=>2)) : array());

			            $this->table->set_heading($heads);

			


			    if ($config['total_rows'] > 0) {

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
									
									if (!in_array($offset,array('cetak','excel'))) {
									$del_berkas = ($r_delarsip) ? anchor('#','<i style="color:red;" class="fa fa-times-circle"></i>','class="btn-delete" act="'.site_url('filing/pengarsipan/delete_berkas/'.$del_ars).'" msg="Apakah ingin menghapus Arsip ini?"') : null;
									$arsip_disp[] = '<li style="font-size: 9pt; white-space:nowrap">'.anchor(base_url().'uploads/arsip/'.$ars->direktori.'/'.$ars->arsip,' &nbsp; <i class="fa fa-file-text-o"></i> &nbsp;'.str_replace('.', '_', $ars->arsip),'class="fancybox" target="_blank"').' &nbsp; '.
									$del_berkas.'</li>';
									} else {
									$arsip_disp[] = '<li style="font-size: 9pt; white-space:nowrap">'.$ars->arsip.'</li>';
									}
							}}
				    	// ./TAMPIL ARSIP

			        	$del_ar = in_de(array(
								'id' => $row->id_arsip_konten,
								'id_jenis' => $row->id_arsip_jenis,
								'search' => in_de($fcari),
								'offset' => $offset));
						$del_msg = 'Apakah Anda ingin menghapus arsip ini?<br>Menghapus konten arsip ini akan menghapus seluruh arsip fisik di dalamnya!';

			            $cell_aksi = ($r_editkonten || $r_delkonten) ? array(
			                ($r_editkonten) ? array('data'=>anchor('filing/pengarsipan/form_data/'.in_de($row->id_arsip_jenis).'/'.$row->id_arsip_konten,'<i class="fa fa-pencil"></i>'),'class'=>'text-center', 'style'=>'') : null,

			               
			                ($r_delkonten) ? array('data'=>anchor('#','<i class="fa fa-trash"></i>','class="btn-delete" act="'.site_url('filing/pengarsipan/delete_konten/'.$del_ar).'" msg="'.$del_msg.'"'), 'class'=>'text-center', 'style'=>'') : null
			            ) : array();
			            
			          $cell = array(
			          	array('data' => '<input type="checkbox" name="cek[]" class="cek-arsip" value="'.$row->id_arsip_konten.'">','style' => 'text-align: center'),
				            array('data'=>++$start, 'style'=>'text-align:left', 'width'=>50),
				            (count($arsip_disp) > 0) ? '<ol style="margin: 0; padding: 0 10px;">'.implode(' ',$arsip_disp).'</ol>' : null,
				            $row->no_surat,
				            array('data'=>!empty($row->tanggal) ? tanggal($row->tanggal) : '', 'class'=>'text-left', 'style'=>''),
				            array('data'=>$row->judul, 'style'=>'min-width:350px;'),
				                );

			          foreach($re_kol_d->result() as $re) {
							$dkol = null;
							foreach($kol->result() as $k) {
								
								if ($k->id_arsip_kolom == $re->id_arsip_kolom AND $k->id_arsip_konten == $row->id_arsip_konten) {
									// $dkol = ($re->tipe == 3) ? tanggal($k->konten) : $k->konten;
									if ($re->tipe == 3) {
										# code...
										$dkol = tanggal($k->konten);
									} elseif($re->tipe == 4) {
										# code...
										$dkol = numberToCurrency($k->konten);
									}else{
										$dkol = $k->konten;
											}
								}
							}
							$cell[] = $dkol;
						}

						$cell_op = array(
							array('data'=>(!empty($row->pegawai) ? $row->pegawai : '') ,'style'=>'white-space:nowrap;font-style:italic;'),
							array('data'=>(!empty($row->nama_bidang) ? $row->nama_bidang : '<b>Arsip Bebas</b>') ,'style'=>'white-space:nowrap;font-style:italic;'),
							array('data'=>(!empty($row->unit) ? $row->unit : '') ,'style'=>'white-space:nowrap;font-style:italic;')
					
							
							);




				        $this->table->add_row(array_merge_recursive($cell, $cell_op, $cell_aksi));
 
			                }
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

					$tabel = '<div class="alert alert-confirm" id="load" style="display: none">Memuat ...</div>
							<div id="box_content"></div>
							<div id="box_tabel">'.form_open('filing/pengarsipan/pindah_arsip/'.in_de($id_jenis),'id="form_pindah"').$this->table->generate().'</div>'.

							'<div id="for_pindah" style="display: none;"><div id="" class="box box-solid">
						        <div class="box-header with-border">
						          <h3 class="box-title">Pilih Jenis Arsip Tujuan : </h3>
						        </div>
						        
						        <ul class="nav nav-list">'.$aj_for_pindah.'</ul>
						     </div></div>'.

							'<div class="form-group">'.anchor('#','<i class="fa fa-exchange"></i> &nbsp; Pindahkan Yang Ter-centang','class="btn btn-primary btn-centang-all btn-md btn-flat" style="display: none; margin-bottom: 10px"').'</div>'.form_close();
					

			            }else{
			        $tabel = 'Tidak/belum ada data..';
			            }
			            }else{
			        $tabel = 'Belum ada data';
			            }


			        $data['include_script'] = "
			        <script>

			            $('.combo-box').select2();
			            $('.datepicker').datepicker({
			                format: 'dd/mm/yyyy',
			                    // viewMode: \'years\', 
			                    // minViewMode: \'years\',
			              todayBtn: 'linked',
			              clearBtn: true,
			              language: 'id',
			              autoclose: true,
			              todayHighlight: true
			            });

			            $('.fancybox').fancybox();

			        </script>
			        ";

			        $data['function_script'] = "
			          function hapus(act, msg){
			        //  var msg = $(this).attr('msg');
			           $('.form-delete-msg').html(msg);
			           $('.form-delete-url').attr('href',act).children().html('<i class=\'fa fa-trash\'></i> Hapus');
			           $('#modal-delete').modal('show');
			           return false;
			            }
			        ";

			        $btn_sortir = '<div class="col-md-4"><div class="form-group"><div class="input-group"><span class="input-group-addon"><i class="fa fa-sort"></i></span>'.form_dropdown('sort_by', array('Urutkan A-Z', 'Urutkan Z-A'), @$get_sorter, 'id="sort_by" class="form-control input-sm"').'</div></div></div>';
			       
			       	$cb_field = array('Nomor', 'Tanggal');
			        $btn_order = '<div class="col-md-4"><div class="form-group"><div class="input-group"><span class="input-group-addon"><i class="fa fa-navicon"></i></span>'.form_dropdown('order_by', $cb_field, @$get_order, 'id="order_by" class="form-control input-sm"').'</div></div></div>';

			        $btn_between = '<div class="col-md-4"><div class="form-group"><div class="input-group"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>'.form_input('mulai', @$get_mulai, 'id="mulai" autocomplete="off" placeholder="Mulai" class="datepicker input-sm form-control"').'<span class="input-group-addon"><i class="fa fa-caret-right"></i></span>'.form_input('selesai', @$get_selesai, 'id="selesai" autocomplete="off" placeholder="Selesai" class="datepicker input-sm form-control"').'</div></div></div>';

			        $btn_cetak = !empty($arsip_konten) ? 
			        '<div class="btn-group"  tyle="margin-left: 5px;">
			            <a class="btn btn-flat btn-info btn-md dropdown-toggle" data-toggle="dropdown" href="#" style="margin: 0 0 0 5px">
			                <i class="fa fa-print"></i> <span class="caret"></span>
			            </a>
			            <ul class="dropdown-menu" role="menu"><li>'.anchor(site_url('filing/pengarsipan/pdf/'.$id_jenis.'/'.in_de($sc).'/'.in_de($between).'/'.in_de($this->tampug_aj).'/'.in_de($order).'/'.$offset), '<i class="fa fa-print"></i> Cetak / Pdf', 'target="_blank" class=""').'</li>
			            </ul>
			        </div>' : null;

			        $btn_set_arkot_role = '<button id="bt_set_role_konten" type="button" class="btn btn-flat btn-md btn-danger">Set Kedalam Kewenangan Bidang</button>';

			        $data['form_konten_role_modal'] = form_open('#', 'id="form_konten_role"')
			        .'<div class"form-group">'
			        .form_label('Pilih Method', 'method')
			        .form_dropdown('method', array('--Pilih--', 'Semua Arsip Konten dalam jenis ini', 'Hanya arsip bebas saja (belum/tidak memiliki kewenangan)'), null, 'class="form-control combo-box"')
			        .'</div>'
			        .form_close();

			        $sc_res = ($fcari['text'] <> '') ? anchor(site_url('filing/pengarsipan/list_data/'.in_de($id_jenis)), 'Reset', 'class="btn btn-md btn-default"') : null;

			        $data['extra_tombol'] =
			                '<ul class="unstyled inbox-pagination">'.
			                form_open(site_url('filing/pengarsipan/list_data/'.in_de($id_jenis).'/'.in_de($fcari)), 'class="form-horizontal"').
			                
			                '<div class="input-group">'.
			                form_input('q', $fcari['text'], 'placeholder="No., Tgl., Perihal, Penerima ..." class="form-control input-md"').
			                '<span class="input-group-btn">'.
			                $sc_res.
			                '<button class="btn btn-md btn-primary btn-flat" type="submit"><i class="fa fa-search"></i></button>'.
			                '</span>'.
			                '</div>'.
			                form_close().
			                '<ul>'
			                ;
			        
			        $check_as_par = $this->general_model->datagrabs(array(
					'tabel' => array(
						'ref_arsip_jenis j' => '',
						'ref_arsip_jenis_role k' => 'k.id_arsip_jenis = j.id_arsip_jenis'),
					'count' => 'count(*) jm',
					'where'=>array('id_par'=>$id_jenis, 'k.id_bidang IN ('.implode(',', $where_bidang).')'=>null),
					))->row()->jm;

			        $data['tombol'] = 
			        (($id_jenis == 'all') ? null : ( ($r_addkonten && $check_as_par==0)  ? anchor('filing/pengarsipan/form_data/'.in_de($id_jenis),'<i class="fa fa-plus"></i> &nbsp; Arsip ','class="btn btn-success"'): null))
			        .$btn_cetak.'&nbsp;'.(($id_jenis == 'all') ? null : (($r_setrolearsip) ? $btn_set_arkot_role : null)).'<div class="clearfix"></div>';

			        // extra filter
			        $data['extra_filter'] = '<div class="row col-md-12">'.form_open('#', array('id'=>'form_filter')).$btn_order.$btn_sortir.$btn_between.form_close().'</div>';
			        // ./extra filter.

			        $data['breadcrumb'] = array('filing/' => 'Data', 'filing/pengarsipan' => 'Pengarsipan');
			        $data['page_title'] = 'Pengarsipan';
			        $data['page_des'] = '';
			        $data['bmain_page'] = 'Data';
			        $data['bsub_page'] = 'Pengarsipan';
			        $data['title'] = '<small>List</small> Pengarsipan';

		// ./jika ada id_jenis
		}
		// ./KONTEN ARSIP

			// ./arsip jenis side bar
			$data['table_jenis'] = '<ul class="direktori" style="white-space:nowrap;">'.@$table_jenis_all.$tab_kiri.'</ul>';	
			$data['aj_for_pindah'] = '<ul class="direktori">'.$aj_for_pindah.'</ul>';	
			$data['tabel'] =$tabel;	
		// ./SIDE BAR ARSIP JENIS
			$data['title'] = 'Konten Arsip';
			$data['bidang'] = @$get_bidang->nama_bidang.', Unit Kerja : '.@$get_bidang->unit;
			$data['id_arsip_jenis'] = $id_jenis;
			$data['konten_role'] = in_de($where_ar_kt_role);
			$data['konten_bebas'] = in_de($kt_bebas);
			// cek($get_bidang->nama_bidang);

			
			
			$data['content'] = "filing/arsip_view";
			$this->load->view('home', $data);
	}

	function cb_bidang($id) {
		$data_comb = $this->general_model->combo_box(array(
			'tabel'=>array('ref_bidang'=>''),
			'key'=>'id_bidang',
			'val'=>array('nama_bidang'),
			'order' => 'urut',
			'where'=>array('id_unit'=>$id),
			'pilih' => '--Pilih Bidang-- '));
		$combo = array();
		foreach($data_comb as $k=>$v){
			$combo .= '<option value="'.$k.'">'.$v.'</option>';
		}
		echo $combo;
	}

	// set arsip konten berdasar arsip jenis
	function set_konten_role($id_jenis=null, $konten_role=array(), $konten_bebas=array()){

		// cek($_POST);
		$input_bidang = $this->input->post('id_bidang');
		$input_method = $this->input->post('method');
		$konten_form = $this->input->post('konten');
		// explode(delimiter, string)

		// cek($input_bidang);
		// cek($input_method);
		// cek($konten_form);
		//  die();
		$method = $this->input->post('method');

		$bidang = !empty($input_bidang) ? $input_bidang :$this->session->userdata('id_bidang');

		$operator = $this->session->userdata('id_pegawai');

		$konten_role = un_de($konten_role);
		$konten_bebas = un_de($konten_bebas);
		$konten_bebas = count($konten_bebas) > 0 ? $konten_bebas : array(0);

		$arr_ids = array();
		if (!empty($konten_form)) {
			# code...
			foreach ($konten_form as $key_kt => $val_kt) {
				# code...
				$arr_ids[] = $val_kt;
			}
		}

		$konten_dipilih = count($arr_ids) > 0 ? $arr_ids : array(0);
		// $where_konten = 
		// cek($konten_role);
		// cek($konten_dipilih); 
		// cek(implode(',', $konten_dipilih)); 
		// // cek($bidang); 
		// die();
		// cek($konten_bebas);
		// cek($konten_role);
		// cek($method);
		// cek($id_jenis);

		if ($id_jenis != 'all') {
			$get_arsip_konten = $this->general_model->datagrabs(array(
	            'tabel'=>'arsip_konten',
	            'where'=>array('id_arsip_jenis'=>$id_jenis, '(id_arsip_konten IN ('.implode(',', ($method == 1) ? $konten_bebas : $konten_dipilih).'))'=>null),
	            'select'=>'*'
	            ));
			// cek($this->db->last_query()); die();

			if ($get_arsip_konten->num_rows() > 0) {
				foreach ($get_arsip_konten->result() as $row) {

					// cek arsip konten role
					$kt_role = $this->general_model->datagrabs(array(
						'tabel'=>'arsip_konten_role',
						'where'=>array('id_arsip_konten'=>$row->id_arsip_konten)))->row();

					$id_kt_role = !empty($kt_role) ? $kt_role->id : null;

					// simpan arsip konten role:
					$this->general_model->save_data('arsip_konten_role', array('id_arsip_konten'=>$row->id_arsip_konten, 'id_bidang'=>$bidang, 'id_operator'=>$operator), 'id', $id_kt_role);

					// update tipe arsip konten (bukan lagi arsip bebas).
					$this->general_model->save_data('arsip_konten', array('tipe_arsip_konten'=>0), 'id_arsip_konten', $row->id_arsip_konten);
					// ./simpan arsip konten role
				
				} // ./foreach result()

				$st = 1;
			} // ./if > 0
			else{
				$st = 0;
			}
		} // ./if not 'all'
		else{
			$st = 0;
		}

		die(json_encode($st));
	}
	// ./set arsip konten berdasar arsip jenis

	// LAPORAN:

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
	            'order'=>$order,
	            'offset'=>$start
	            ));

	        // cek($this->db->last_query());
	        // cek($arsip_konten);

	            $data['arsip_konten_data'] = $arsip_konten;

	            $data['total'] = $config['total_rows'];

	            if ($arsip_konten->num_rows() > 0) {


	            $this->table->set_template(array('table_open'=>'<table class="bpmTopnTailC" width="100%" style="font-size: 10pt; border-collapse: collapse; " cellpadding="8">'));
	            $this->table->set_empty('');
	            $heads = array(
	            array('data'=>'No.'),
	            array('data'=>'Arsip', 'class'=>'headerrow', 'style'=>'white-space:nowrap;'),
	            array('data'=>'Nomor', 'class'=>'headerrow', 'style'=>'white-space:nowrap;text-align:left;'),
	            array('data'=>'Tanggal', 'class'=>'headerrow', 'style'=>'white-space:nowrap;text-align:left;'),
	            array('data'=>'Perihal', 'class'=>'headerrow', 'style'=>'white-space:nowrap;text-align:left;'),
	            array('data'=>'Penerima', 'class'=>'headerrow', 'style'=>'white-space:nowrap;text-align:left;')
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
									// nggo ngitung jumlah arsip
									$arsip_ids[] = $ars->id_arsip;
									// ./nggo ngitung jumlah arsip
									
									$del_ars = in_de(array(
									'arsip' => $ars->arsip,
									'id_arsip_jenis' => $ars->id_arsip_jenis,
									'id_arsip' => $ars->id_arsip,
									'direktori' => $ars->direktori
									));
									
									$arsip_disp[] = '<li style="font-size:8pt;">'.$ars->arsip.'</li>';
									
							}
						}
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
				            array('data'=>$row->penerima, 'class'=>'evenrow', 'style'=>'text-align:left;'),
				                );

			          foreach($re_kol_d->result() as $re) {
							$dkol = null;
							foreach($kol->result() as $k) {
								
								if ($k->id_arsip_kolom == $re->id_arsip_kolom AND $k->id_arsip_konten == $row->id_arsip_konten) {
									// $dkol = ($re->tipe == 3) ? tanggal($k->konten) : $k->konten;
									if ($re->tipe == 3) {
										# code...
										$dkol = tanggal($k->konten);
									} elseif($re->tipe == 4) {
										# code...
										$dkol = numberToCurrency($k->konten);
									}else{
										$dkol = $k->konten;
									}
								}
							}
							$cell[] = array('data'=>(!empty($dkol) ? $dkol : ''), 'style'=>'text-align:left;');
						}


				        $this->table->add_row($cell);
 
			                }
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
            'h_sub_center'=> 'Pelindo 1',
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
        $pdf->SetTitle("Arsip Konten");
        $pdf->SetAuthor("Arsip Konten");
        $pdf->SetWatermarkText("Arsip Konten");
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

	function ajax_get_sub_aj($id_par=null){
		$from = array('ref_arsip_jenis j' => '',
						'ref_arsip_jenis_role k' => 'k.id_arsip_jenis = j.id_arsip_jenis');


		
		$sub_jenis = $this->general_model->datagrabs(array(
			'tabel' => $from,
			'where' => array('j.id_par' => $id_par),
			'select' => 'j.*',
			'group_by' => 'j.id_arsip_jenis',
			'order' => 'j.urut, j.arsip_jenis',

		));

		$data['cb'] = '<option value="">--Pilih--</option>';
		if ($sub_jenis->num_rows() > 0 && $id_par != null) {
			foreach($sub_jenis->result() as $row) {
				$data['cb'] .= '<option value="'.$row->id_arsip_jenis.'">'.$row->arsip_jenis.'</option>';
			}
			$data['st'] = 1;
		}else{
			$data['st'] = 0;
		}
		die(json_encode($data));


	}



	function pindah_arsip($id_jenis=null){
		$id_jenis = !empty(un_de($id_jenis)) ? un_de($id_jenis) : null;
		// cek($id_jenis); die();
		$id_tujuan = $this->input->post('id_pindah');
		$konten = $this->input->post('cek');
		// cek data :
		// cek($konten); 
		// cek($id_tujuan); 
		// cek($this->input->post()); 
		// cek(count($konten)); 
// dimodarke :
		// die();

		// ./cek data
if (!empty($id_tujuan)) {
	if (count($konten > 0)) {
		$konten_ids = array();
			foreach ($konten as $kt) {
				// cek($kt);

				// arsip konten
				// update id_arsip_jenis di arsip konten ke id tujuan 

				$this->general_model->save_data('arsip_konten', array('id_arsip_jenis'=>$id_tujuan), 'id_arsip_konten', $kt);

				// ./update id_arsip_jenis di arsip konten ke id tujuan 
				// ./arsip konten
				$konten_ids[] = $kt;
			}

			// cari arsip:
					$arsip = $this->general_model->datagrabs(array(
								'tabel' => array(
									'arsip a' => '',
									'arsip_konten_set s' => 's.id_arsip = a.id_arsip',
									'ref_arsip_jenis j' => 'j.id_arsip_jenis = a.id_arsip_jenis'),
								'select' => 'a.id_arsip,a.arsip,j.direktori',
								'where' => array('s.id_arsip_konten IN ('.implode(',', $konten_ids).')' =>null)
							));

					if ($arsip->num_rows() > 0) {
						// pindah arsip fisik
						$jenis_arsip = $this->general_model->datagrabs(array(
							'tabel'=>'ref_arsip_jenis', 'where'=>array('id_arsip_jenis'=>$id_tujuan)))->row();
						$dir_baru = !empty($jenis_arsip) ? './uploads/arsip/'.$jenis_arsip->direktori : null;


						foreach ($arsip->result() as $r_kts) {
						$dir_lama = './uploads/arsip/'.$r_kts->direktori;
						
						$path = './uploads/arsip/'.$r_kts->direktori.'/'.$r_kts->arsip;
						// cek($path); die();
						// if(file_exists($path)) unlink($path);	
						// ./pindah arsip fisik

						// move files
						if (!is_dir($dir_baru)) mkdir($dir_baru,0777,TRUE);

						// $get_potongan_nama = substr($r_kts->arsip, 0, 20);
						// $new_name = rand(1,1000).'_'.date('dmY').'_'.date('His').'_'.$get_potongan_nama;

						if (file_exists($path)) {
							if ($dir_lama != $dir_baru) {
								copy($path, $dir_baru.'/'.$r_kts->arsip);
								// cek($dir_baru); die();
								unlink($path);
							}
							
						}
						
						// simpan perubahan arsip jenis:

						$this->general_model->save_data('arsip', array('id_arsip_jenis'=>$id_tujuan), 'id_arsip', $r_kts->id_arsip);
						}
						

					}
					// ./cari arsip


			// berhasil disimpan:
			$this->session->set_flashdata('ok','Arsip berhasil disimpan ...');
			redirect('filing/pengarsipan/list_data/'.in_de($id_tujuan));

		}else{
			// tidak ada konten :
			$this->session->set_flashdata('fail','tidak ada konten arsip dipindah ...');
			redirect('filing/pengarsipan/list_data/'.in_de($id_tujuan));
		} // count $konten

}else{
	// tidak ada arsip jenis tujuan : 
	$this->session->set_flashdata('fail','Tidak ada jenis arsip tujuan yang dipilih ...');
	redirect('filing/pengarsipan/list_data/'.in_de($id_jenis));
} // !empty $id_tujuan.
		


	}



	function tabel_sub_for_pindah($level,$id, $arsip_aktif=null) {
		
		$from = array('ref_arsip_jenis j' => '',
						'ref_arsip_jenis_role k' => 'k.id_arsip_jenis = j.id_arsip_jenis');


		
		$count_sub_jenis = $this->general_model->datagrabs(array(
			'tabel' => $from,
			'where' => array('j.id_par' => $id),
			'count' => 'count(*) jm',
			'group_by' => 'j.id_arsip_jenis',
			'order' => 'j.urut, j.arsip_jenis',

		))->row()->jm;

		$sub_jenis = $this->general_model->datagrabs(array(
			'tabel' => $from,
			'where' => array('j.id_par' => $id),
			'select' => 'j.*',
			'group_by' => 'j.id_arsip_jenis',
			'order' => 'j.urut, j.arsip_jenis',

		));

		$data_nav = null;
		
		if ($count_sub_jenis > 0) {


			$r = 0;
			foreach($sub_jenis->result() as $row) {

				$folder_aktif = ($row->id_arsip_jenis == $arsip_aktif) ? '#00c0ef' : '#f39c12';

				$sub_jenis2 = $this->general_model->datagrabs(array(
					'tabel' => $from, 
					'count' =>'count(*) jm',
					'where' => array('id_par' => $row->id_arsip_jenis)))->row()->jm;

				$par_btn = ($sub_jenis2 > 0) ? 'btn-primary' : 'btn-default';
					$par_stat = ($sub_jenis2 > 0) ? 1 : null;

				$act = ($row->id_arsip_jenis == $arsip_aktif) ? 'class="active"':null;
				$bg_col = ($row->id_arsip_jenis == $arsip_aktif) ? 'bg-active link-normal':null;
				$font = ($row->id_arsip_jenis == $arsip_aktif) ? 'font-weight: bold;':null;
				$p = ($row->id_arsip_jenis == $arsip_aktif) ? 'label-primary':'label-default';
				$se = in_de(array(
					  		'arsip_judul' => $row->arsip_jenis,
					  		'id_arsip_jenis' => $row->id_arsip_jenis
				  		));
				  		
				  		$jml = null;
				  		
				$data_nav .=  '<li  style="margin: 5px 0 5px 20px">';
				$data_nav .= '<input '.($row->id_arsip_jenis == $arsip_aktif ? 'disabled' : null).' type="radio" name="id_pindah" id="id_pindah'.$row->id_arsip_jenis.'" value="'.$row->id_arsip_jenis.'" />';

				$data_nav .='&nbsp;<button type="button" class="tree-toggle nav-header btn btn-xs btn-flat '.$par_btn.'" '.($par_stat == 1 ? null : '').'><i class="fa fa-folder" style="color:#f39c12;"></i> '.$row->arsip_jenis.'</button>';

				if ($sub_jenis2 > 0) $data_nav .= '<ul class="nav nav-list tree">'.$this->tabel_sub_for_pindah($level+1,$row->id_arsip_jenis, $arsip_aktif).'</ul>';
				$r+=1;

				$data_nav .= '</li><li class="divider"></li>';
				

				
			}
		}

		return $data_nav;
		
	}

	function get_total_konten($id=0, $bidangs=null, $roles=null){

		$dt_sub = $this->general_model->datagrab(array(
			'tabel'=>array(
				'ref_arsip_jenis jn'=>'',
				'ref_arsip_jenis_role r'=>'r.id_arsip_jenis=jn.id_arsip_jenis',
				),
			'where'=>array('r.id_bidang IN('.implode(',', $bidangs).')'=>null, 'jn.id_par'=>$id),
			'group_by'=>'jn.id_arsip_jenis'
			));

		$dt = $this->general_model->data_query('
			SELECT COUNT(ct.id_arsip_konten) tot_konten
				FROM 
				(
				SELECT kt.id_arsip_konten
					FROM `arsip_konten` kt
					JOIN `ref_arsip_jenis` j ON j.`id_arsip_jenis`=kt.`id_arsip_jenis`
					JOIN `ref_arsip_jenis_role`r ON `r`.`id_arsip_jenis` = `j`.`id_arsip_jenis`
					WHERE j.`id_arsip_jenis`='.$id.' AND r.id_bidang IN('.implode(',', $bidangs).') AND kt.id_arsip_konten IN('.implode(',', $roles).')
					GROUP BY kt.id_arsip_konten
					) ct')->row()->tot_konten;

			$total = $dt;


		// cek($this->db->last_query());
		// cek($dt_sub->result());

		if ($dt_sub->num_rows() > 0) {
			foreach ($dt_sub->result() as $row) {
				// if($row->id_arsip_jenis != $id)
				$total += $this->get_total_konten($row->id_arsip_jenis, $bidangs, $roles);
				// cek($row->id_arsip_jenis);
			}

		}
		

		return intval($total);

	}


	function tabel_sub_tab_kiri($level,$id, $arsip_aktif=null, $where_bidang, $where_ar_kt_role) {

		// if($id == 141) cek(implode(",", $where_ar_kt_role));
		
		$from = array('ref_arsip_jenis j' => '',
						'ref_arsip_jenis_role k' => 'k.id_arsip_jenis = j.id_arsip_jenis');


		$count_sub_jenis = @$this->general_model->datagrabs(array(
			'tabel' => $from,
			'where' => array('j.id_par' => $id, 'k.id_bidang IN ('.implode(',', $where_bidang).')'=>null),
			'count' =>'count(*) jm',
			'group_by' => 'j.id_arsip_jenis',
			'order' => 'j.urut, j.arsip_jenis',

		))->row()->jm;

		$sub_jenis = $this->general_model->datagrabs(array(
			'tabel' => $from,
			'where' => array('j.id_par' => $id, 'k.id_bidang IN ('.implode(',', $where_bidang).')'=>null),
			'select' => 'j.*',
			'group_by' => 'j.id_arsip_jenis',
			'order' => 'j.urut, j.arsip_jenis',

		));

		$data_nav = null;
		$active_par_id = null;
		if ($count_sub_jenis > 0) {
			$r = 0;
			// $total_konten = 0;
			foreach($sub_jenis->result() as $row) {
				$this->tampug_aj[] = $row->id_arsip_jenis;

				$jml_konten = $this->general_model->datagrabs(array(
			            'tabel'=>'arsip_konten',
			            'where'=>array('id_arsip_jenis'=>$row->id_arsip_jenis, '(id_arsip_konten IN ('.implode(',', $where_ar_kt_role).'))'=>null),
			            'select'=>'ifnull(count(id_arsip_jenis), 0) jml_konten'))->row()->jml_konten;

				$total_konten = $this->get_total_konten($row->id_arsip_jenis, $where_bidang, $where_ar_kt_role);

				

				// cek($total_konten);

				// cek($total_konten->row());

				// if ($id == 141) cek($this->db->last_query());

				$tab_aktif = ($row->id_arsip_jenis == $arsip_aktif) ? '#ffffff' : '#000000';
					$tab_bg_col = ($row->id_arsip_jenis == $arsip_aktif) ? 'bg-active link-normal':null;
					$row_aktif = $arsip_aktif;


				$sub_jenis2 = $this->general_model->datagrabs(array(
					'tabel' => $from,
					'count' =>'count(*) jm',
					'where' => array('id_par' => $row->id_arsip_jenis, 'k.id_bidang IN ('.implode(',', $where_bidang).')'=>null)))->row()->jm;

				// cek($this->db->last_query());

				// cek status parent
				$tab_btn = $row->id_arsip_jenis == $arsip_aktif ? 'bg-folder-active' : (($sub_jenis2 > 0) ? 'bg-folder-par' : 'bg-folder-child');
				$tab_stat = ($sub_jenis2 > 0) ? 1 : null;
					// ./cek status parent
					// cari ibu

				$par_arr = $this->par_id;
				// cek($par_arr);

				if (!empty($par_arr)) {
						$collapse_aktif = in_array($row->id_arsip_jenis, $par_arr) ? 'style="display:none !important;"' : null;
						// cek($collapse_aktif);
						// cek($par_arr);
					}
					
					// cek($collapse_aktif);

				if($jml_konten > 0 && $sub_jenis2 > 0) {
					$tampil_tot_konten = ($total_konten-$jml_konten).'(+'.$jml_konten.')';
				}else{
					$tampil_tot_konten = $total_konten;
				}

				$data_nav .=  '<li  style="margin: 8px 0 4px 15px">';
				$data_nav .= ($sub_jenis2 > 0) ? '<button title="" type="button" class="btn btn-xs btn-default btn-rounded tree-toggle-tabkiri nav-header" style="color:#00a65a;"><i class="fa '.(!empty($collapse_aktif)  ? 'fa-caret-down' : 'fa-caret-right').'"> </i></button>&nbsp;' : '<span style="padding-right:22px;"></span>';
		
				$data_nav .= '<span>'.anchor('filing/pengarsipan/list_data/'.in_de($row->id_arsip_jenis), '<i class="fa fa-folder" style="color:#f39c12;"></i> <b>'.strtoupper(substr($row->arsip_jenis, 0, 15)).(strlen($row->arsip_jenis) > 15 ? ' ...':null).'</b><p class="label label-danger count-file-label pull-right">'.$tampil_tot_konten.'</p>', 'class="btn btn-xs '.$tab_btn.'" title="'.strtoupper($row->arsip_jenis).'" style="color:'.$tab_aktif.'"').'</span>';

				if ($sub_jenis2 > 0) $data_nav .= '<ul class="nav nav-list tree" '.@$collapse_aktif.'>'.$this->tabel_sub_tab_kiri($level+1,$row->id_arsip_jenis, $row_aktif, $where_bidang, $where_ar_kt_role)['nav'].'</ul>';

				$r+=1;

				$data_nav .= '</li><li class="divider"></li>';
			}

		}

		$arr_ret = array(
			'nav'=>$data_nav,
			'tot_konten'=>null,
			);

		// return $data_nav;
		return $arr_ret;
		
	}

	function get_par_folder($id){
		//$this->par_id[] = $id;
		$q_count = $this->general_model->datagrabs(array(
			'tabel'=>'ref_arsip_jenis',
			'count'=>'count(*) jm',
			'where'=>array('id_arsip_jenis'=>$id)))->row();
		$q = $this->general_model->datagrabs(array(
			'tabel'=>'ref_arsip_jenis',
			'where'=>array('id_arsip_jenis'=>$id)));
		if ($q_count->jm > 0) {
			$this->par_id[] = $q->row()->id_par; 
			$this->get_par_folder($q->row()->id_par);
		}
	}

	// FILTER

	function set_filter(){
		$data =array();
		if (!empty($this->input->post('mulai'))) {

			$data['tgl_mulai'] = $this->input->post('mulai');
			
		}else{
			$this->session->unset_userdata('tgl_mulai');	
		}

		if (!empty($this->input->post('selesai'))) {

			$data['tgl_selesai'] = $this->input->post('selesai');
			
		}else{
			$this->session->unset_userdata('tgl_selesai');	
		}

		if (!empty($this->input->post('sort_by'))) {
			$data['sort_by'] = $this->input->post('sort_by');
		}else{
			$this->session->unset_userdata('sort_by');	
		}

		if (!empty($this->input->post('order_by'))) {
			$data['order_by'] = $this->input->post('order_by');
		}else{
			$this->session->unset_userdata('order_by');
		}


		$this->session->set_userdata($data);

		die(json_encode($data));
	}
	// ./FILTER

	

	function info($id = null) {
		
		$from = array(
			'arsip_konten k' => '',
			'ref_arsip_jenis j' => 'j.id_arsip_jenis = k.id_arsip_jenis'
		);
		
		$def = $this->general_model->datagrab(array('tabel' => $from,'where' => array('k.id_arsip_konten' => $id)))->row();
		
		$this->table->set_template(array('table_open'=>'<table class="table table-striped">'));
		$this->table->add_row(array('data' => '<h4><i class="fa fa-info-circle"></i> &nbsp; Info Arsip</h4','colspan' => 2));
		$this->table->add_row('<em>Judul</em>',$def->judul);
		$this->table->add_row('<em>Jenis Arsip</em>',$def->arsip_jenis);
		$this->table->add_row('<em>Tanggal Arsip</em>',tanggal($def->tanggal));
		$this->table->add_row('<em>Nomor Surat</em>',$def->no_surat);
		
		$from_kol = array(
			'arsip_kolom k' => '',
			'ref_arsip_kolom r' => 'r.id_arsip_kolom = k.id_arsip_kolom');
		$d_kolom = $this->general_model->datagrab(array('tabel' => $from_kol,'where' => array('id_arsip_konten' => $id)));
		foreach($d_kolom->result() as $dk) {
				$this->table->add_row('<em>'.$dk->kolom.'</em>',($dk->tipe == 3)?tanggal($dk->konten): (($dk->tipe == 4) ? numberToCurrency($dk->konten) : $dk->konten));
		}
		
		$tabel_info = $this->table->generate();
		
		$arsip = $this->general_model->datagrab(array(
			'tabel' => array(
				'arsip a' => '',
				'arsip_konten_set s' => 's.id_arsip = a.id_arsip',
				'ref_arsip_jenis j' => 'j.id_arsip_jenis = a.id_arsip_jenis'),
			'where' => array('s.id_arsip_konten' => $id)
		));
		$no = 1;
		$this->table->add_row(array('data' => '<h4><i class="fa fa-file-text-o"></i> &nbsp; Daftar Arsip</h4','colspan' => 2));
		foreach($arsip->result() as $ars) {
			$this->table->add_row(
				array('data' => $no,'width' => 25),
				anchor(base_url().'uploads/arsip/'.$ars->direktori.'/'.$ars->arsip,
		  			'<i class="fa fa-file-text-o"></i> &nbsp; '.$ars->arsip,
		  			'class="link-normal" target="_blank" title="'.$ars->arsip.'"'));	  
			$no+=1;
		}
		$tabel_arsip = $this->table->generate();
		
		$info = '
			<div class="row" style="width: 95%">
				<div class="col-lg-6">'.$tabel_info.'
				<span class="btn btn-default btn-back"><i class="fa fa-arrow-left"></i> Kembali</span>
				<script type="text/javascript">
					$(document).ready(function() {
						
						$(".btn-back").click(function() {
							$("#box_tabel,.stat-info").show();
							$("#box_content").html("").hide();
						});
					
					});
				</script>
				</div>
				<div class="col-lg-6">'.$tabel_arsip.'</div>
				
				
				</div>';
		
	
		die($info);
	
	}
	
	function cek_nosurat(){
		$nosurat = $this->input->post('nosurat');
		$id = $this->input->post('id');

		$old_nosurat = !empty($id) ? $this->general_model->datagrabs(array('tabel'=>'arsip_konten', 'where'=>array('id_arsip_konten'=>$id)))->row()->no_surat : null;

		$where = !empty($old_nosurat) ? array('no_surat != "'.$old_nosurat.'"'=>null, 'no_surat'=>$nosurat) : array('no_surat'=>$nosurat);

		$arsip_konten = $this->general_model->datagrab(array(
			'tabel'=>'arsip_konten',
			'where'=>$where
			));


		if ($arsip_konten->num_rows() > 0) {
			$data['status'] = 1; // sudah ada
			$data['nosurat'] = $nosurat;
		}else{
			$data['status'] = 0; // belum ada
			$data['nosurat'] = $nosurat;
		}
		die(json_encode($data));
	}

	function form_data($j,$id = null) {
		$j = un_de($j);
		
		$t = $this->general_model->datagrab(array('tabel' => 'ref_arsip_jenis','where' => array('id_arsip_jenis' => $j)))->row();
		// cek($this->db->last_query());
		
		$data['breadcrumb'] = array('' => 'Referensi','filing/pengarsipan/form_data' => 'Pengarsipan '.$t->arsip_jenis);
		$data['def'] = (!empty($id)) ? $this->general_model->datagrab(array('tabel' => 'arsip_konten','where' => array('id_arsip_konten' => $id)))->row() : null;
		$data['j'] = $j;
		$data['arsip_judul'] = $t->arsip_jenis;
		
		if (!empty($id)) {
		$data['arsip'] = $this->general_model->datagrab(array(
				'tabel' => array(
					'arsip a' => '',
					'arsip_konten_set s' => 's.id_arsip = a.id_arsip',
					'ref_arsip_jenis j' => 'j.id_arsip_jenis = a.id_arsip_jenis'),
				'where' => array('s.id_arsip_konten' => $id)
			));
		}
		
		$jdata = $this->general_model->datagrab(array('tabel' => 'ref_arsip_kolom','where' => array('id_arsip_jenis' => $j)));	
		$d_kolom = $this->general_model->datagrab(array('tabel' => 'arsip_kolom','where' => array('id_arsip_konten' => $id)));
			
		$formd = '
		<script type="text/javascript">
			$(document).ready(function() {
				$(".datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
			});
		</script>';	
		foreach($jdata->result() as $ja) {
			
			$konten = null;
			foreach($d_kolom->result() as $dk) {
				if ($dk->id_arsip_kolom == $ja->id_arsip_kolom) $konten = $dk->konten;
			}
			
			switch($ja->tipe) {
				case "1": $forms = form_input('konten[]',$konten,'class="form-control"'); //'1' => 'Isian teks'
				break;
				case "2": $forms = form_textarea('konten[]',$konten,'class="form-control" style="height: 60px"'); //'2' => 'Boks Teks',
				break;
				case "3": $forms = form_input('konten[]',(!empty($konten)?tanggal($konten):null),'class="form-control datemask"'); //'3' => 'Tanggal'
				break;
				case "4": $forms = form_input('konten[]',$konten,'class="form-control" onkeyup="formatNumber(this);"'); //'3' => 'Tanggal'
				break;
			}
			
			$formd.= 
				'<p>'.
				form_label($ja->kolom).
				form_hidden('tipe[]',$ja->tipe).
				form_hidden('id_form_kolom[]',$ja->id_arsip_kolom).$forms.'</p>';
			
		}
		
		$data['kolom_tambahan'] = $formd;
		// KEWENANGAN
		$id_peg = $this->session->userdata('id_pegawai');

		$r_delarsip = $this->general_model->check_role($id_peg,'FILDE');
		$r_uploadarsip = $this->general_model->check_role($id_peg,'FILUP');

		$data['del_role'] = $r_delarsip;
		$data['up_role'] = $r_uploadarsip;

		$data['title'] = !empty($id) ? 'Ubah Arsip '.$t->arsip_jenis : 'Tambah Arsip '.$t->arsip_jenis;
		
		$data['content'] = "filing/arsip_form_view";
		$this->load->view('home',$data);
	}
	
	function form_info($j, $id = null) {
		
		$jdata = $this->general_model->datagrab(array('tabel' => 'ref_arsip_kolom','where' => array('id_arsip_jenis' => $j)));
		
		if ($jdata->num_rows() > 0) {
			
		$d_kolom = $this->general_model->datagrab(array('tabel' => 'arsip_kolom','where' => array('id_arsip_konten' => $id)));
			
		$formd = '
		<script type="text/javascript">
			$(document).ready(function() {
				$(".datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
			});
		</script>';	
		foreach($jdata->result() as $ja) {
			
			$konten = null;
			foreach($d_kolom->result() as $dk) {
				if ($dk->id_arsip_kolom == $ja->id_arsip_kolom) $konten = $dk->konten;
			}
			
		/*
			'1' => 'Isian teks',
			'2' => 'Boks Teks',
			'3' => 'Tanggal'
		*/
			switch($ja->tipe) {
				
				case "1":
					$forms = form_input('konten[]',$konten,'class="form-control"');
				break;
				case "2":
					$forms = form_textarea('konten[]',$konten,'class="form-control" style="height: 60px"');
				break;
				case "3":
					$forms = form_input('konten[]',(!empty($konten)?tanggal($konten):null),'class="form-control datemask"');
				break;
				case "4": $forms = form_input('konten[]',$konten,'class="form-control" onkeyup="formatNumber(this);"'); //'3' => 'Tanggal'
				break;
				
			}
			
			
			$formd.= 
				'<p>'.
				form_label($ja->kolom).
				form_hidden('tipe[]',$ja->tipe).
				form_hidden('id_form_kolom[]',$ja->id_arsip_kolom).$forms.'</p>';
			
		}
		} else {
			$formd = '<div class="alert alert-confirm">Tanpa Info Tambahan</div>';
		}
		die($formd);
		
	}
	
	function save_arsip($id_ar=null,$id_r=null) {
		/*echo "<pre>"; 
				print_r($_POST);
				echo "</pre>"; 
				die();*/
		

		$id_peg = $this->session->userdata('id_pegawai');
		$id_bidang = $this->session->userdata('id_bidang');

		$id_ar = $this->input->post('id_arsip_konten');
		$j = $this->input->post('arsip_judul');

		$id_jenis = $this->input->post('id_arsip_jenis');
		$id_form_kolom = $this->input->post('id_form_kolom');
		$konten = $this->input->post('konten');
		$tkol = $this->input->post('tipe');
		$jmlfile = $this->input->post('jmlfile');
		$tgl = tanggal_php($this->input->post('tanggal'));
		$no_surat = $this->input->post('no_surat');
		// $penerima = $this->input->post('penerima', TRUE);
		
		$id_r_frole = $id_ar; //id konten for konten role;
		$simpan_info = array(
				'id_arsip_jenis' => $id_jenis,
				'judul' => $this->input->post('judul'),
				'tanggal' => $tgl,
				'no_surat' => $no_surat
				//'penerima' => $penerima,			
		); 

		$op = null;
		if (!empty($id_ar)) {
			$get_op  = $this->general_model->datagrabs(array(
				'tabel'=>'arsip_konten',
				'where'=>array('id_arsip_konten'=>$id_ar)))->row();

			if (!empty($get_op)) {
				$op = $get_op->op;
			}
		}

		// cek($this->db->last_query()); die();
		// cek($op); die();

		// simpan operator

		$tipe_arsip_konten = $this->input->post('tipe_arsip_konten');

		if (empty($id_ar) OR empty($op)) {
			
			if (empty($tipe_arsip_konten)) {
				$simpan_info['op'] = $id_peg;
				$simpan_info['tipe_arsip_konten'] = 0;
			}elseif (!empty($tipe_arsip_konten)){
				$simpan_info['tipe_arsip_konten'] = $tipe_arsip_konten;
				$simpan_info['op'] = 0;
			}
			
		}else{
			if (empty($tipe_arsip_konten)) {
				// $simpan_info['op'] = $id_peg;
				$simpan_info['tipe_arsip_konten'] = 0;
			}elseif (!empty($tipe_arsip_konten)){
				$simpan_info['tipe_arsip_konten'] = $tipe_arsip_konten;
				$simpan_info['op'] = 0;
			}
		}

		$log_act = !empty($id_ar) ? 2 : 1;
		//cek($simpan_info);die();
		
		$this->general_model->save_data('arsip_konten',$simpan_info,'id_arsip_konten',$id_ar);
		$id_r = (empty($id_ar)) ? $this->db->insert_id() : $id_ar;

		

		if ($id_ar) $this->general_model->delete_data('arsip_kolom','id_arsip_konten',$id_r);
		if (!empty($id_form_kolom)) {
			foreach($id_form_kolom as $k => $v) {
				$simpan_kol = array(
				'id_arsip_konten' => $id_r,
				'id_arsip_kolom' => $v,
				'konten' => ($tkol[$k] == '3') ? tanggal_php($konten[$k]) : (($tkol[$k] == '4') ? currencyToNumber($konten[$k]) : $konten[$k])
				);
				$this->general_model->save_data('arsip_kolom',$simpan_kol);
			}
		}
		
		

		$ard = $this->general_model->datagrab(array('tabel' => 'ref_arsip_jenis','where' => array('id_arsip_jenis' => $id_jenis)))->row();

		
		$path = "./uploads/arsip/".$ard->direktori;
		if (!is_dir($path)) mkdir($path,0777,TRUE);
      
		$eks_data = $this->general_model->datagrab(array('tabel' => 'ref_arsip_ekstensi'));
		$eks = array();
		foreach($eks_data->result() as $e) {
			$eks[] = $e->ekstensi;
		}
      
		$data_error = array();
		$data_file = array();
		// kebutuhan logs_user :
		$arsip_logs = array();
		// ./kebutuhan log_user.

		
		if(!empty($_FILES['userFiles']['name'])){
			$filesCount = count($_FILES['userFiles']['name']);
			for($i = 0; $i < $filesCount; $i++){
				// $_FILES['userFile']['name'] = $_FILES['userFiles']['name'][$i];
				$_FILES['userFile']['name'] = rand(1,1000).'_'.date('dmY').'_'.date('His').'_'.$_FILES['userFiles']['name'][$i];
				$_FILES['userFile']['type'] = $_FILES['userFiles']['type'][$i];
				$_FILES['userFile']['tmp_name'] = $_FILES['userFiles']['tmp_name'][$i];
				$_FILES['userFile']['error'] = $_FILES['userFiles']['error'][$i];
				$_FILES['userFile']['size'] = $_FILES['userFiles']['size'][$i];

				$uploadPath = 'uploads/arsip/'.$ard->direktori;
				$config['upload_path'] = $uploadPath;
				$config['allowed_types'] = implode('|',$eks);
				// cek($_FILES['userFile']);die();

				$this->load->library('upload', $config);
				$this->upload->initialize($config);

				if($this->upload->do_upload('userFile')){
					$fileData = $this->upload->data();
					$uploadData[$i]['id_arsip_jenis'] = $id_jenis;
					$uploadData[$i]['arsip'] = $fileData['file_name'];
					$uploadData[$i]['op'] = $this->session->userdata('id_pegawai');
					$uploadData[$i]['tanggal'] = $tgl.' '.date('H:i:s');

					$id_arsip[$i] = $this->general_model->save_data('arsip',$uploadData[$i]);

					$this->general_model->save_data('arsip_konten_set',array('id_arsip' => $id_arsip[$i],'id_arsip_konten' => $id_r));
					// kirim kedalam $arsip logs:
					$arsip_logs[] = $uploadData[$i]['arsip'];
				}
			}
				
			if(!empty($uploadData)){

			}
		}
		// ./UPLOAD FILES VER 2

		// simpan ativitas pengguna
		$arsip_jenis = $this->general_model->datagrabs(array(
			'tabel'=>'ref_arsip_jenis',
			'where'=>array('id_arsip_jenis'=>$id_jenis)
			))->row();

		$data_log = array(
			'id_operator'=>$id_peg,
			'id_modul'=>1,
			'act'=>$log_act,
			'data_value'=>array(
				'arsip_jenis' => (!empty($arsip_jenis) ? $arsip_jenis->arsip_jenis : null),
				'judul' => $this->input->post('judul'),
				'tanggal' => tanggal($tgl),
				'no_surat' => $no_surat,
				// 'penerima' => $penerima
				),
			'field_value'=>array('judul', 'tanggal', 'no_surat', 'penerima'),
			'field_alias'=>array('jenis arsip', 'perihal', 'tanggal', 'nomor surat', 'penerima')
			);

		if (!empty($arsip_logs)) {
			$data_log['data_value'][] = implode(', ', $arsip_logs);
			$data_log['field_alias'][] = 'butir arsip baru';
		}

		log_users($data_log);

		// ./simpan atifitas pengguna

		// cek arsip konten role
			$kt_role = $this->general_model->datagrabs(array(
				'tabel'=>'arsip_konten_role',
				'where'=>array('id_arsip_konten'=>$id_r)))->row();

			$id_kt_role = !empty($kt_role) ? $kt_role->id : null;

			// simpan arsip konten role:
			// if (empty($tipe_arsip_konten)){ 
 
				if (empty($id_r_frole)) { //jika id arsip konten kosong
					if (empty($tipe_arsip_konten)){
					$data_konten_role = array(
							'id_arsip_konten'=>$id_r, 
							'id_bidang'=>$id_bidang, 
							'id_operator'=>$id_peg);

					if (empty($id_kt_role))	$this->general_model->save_data('arsip_konten_role', $data_konten_role, 'id', $id_kt_role);
					}
				}else{
					if (empty($tipe_arsip_konten)){
					$data_konten_role = array(
							'id_arsip_konten'=>$id_r, 
							'id_bidang'=>$id_bidang, 
							'id_operator'=>$id_peg);
					if (empty($id_kt_role))	$this->general_model->save_data('arsip_konten_role', $data_konten_role, 'id', $id_kt_role);
					
					}elseif (!empty($tipe_arsip_konten)){
						$this->general_model->delete_data('arsip_konten_role', 'id_arsip_konten', $id_r);

					}
				}
				
				// ./simpan arsip konten role
			// }elseif (!empty($tipe_arsip_konten)){
			// 	$this->general_model->delete_data('arsip_konten_role', 'id_arsip_konten', $id_r);
			// }


			
		
		// ./cek arsip konten role
		
		if (count($data_error) > 0) $this->session->set_flashdata('fail','Kesalahan Unggah Arsip!<br>'.implode('<br>',$data_error));
		else $this->session->set_flashdata('ok','Arsip berhasil disimpan ...');
		
		
		redirect('filing/pengarsipan/list_data/'.in_de($id_jenis));
    }

	function delete_konten($par) {
		
		$o = un_de($par);
		$file_arsip = $this->general_model->datagrab(array(
			'tabel' => array(
				'arsip a' => '',
				'arsip_konten_set s' => 's.id_arsip = a.id_arsip',
				'ref_arsip_jenis j' => 'j.id_arsip_jenis = a.id_arsip_jenis'),
			'select' => 'a.id_arsip,a.arsip,j.direktori',
			'where' => array('id_arsip_konten' => $o['id'])
		));

		// simpan ativitas pengguna
		// konten arsip
		$arsip_konten = $this->general_model->datagrab(array(
			'tabel'=>array('arsip_konten a'=>'', 'ref_arsip_jenis b'=>'b.id_arsip_jenis=a.id_arsip_jenis'), 
			'where'=>array('a.id_arsip_konten'=>$o['id']),
			'select'=>'a.*, b.arsip_jenis'
			))->row();

		$butir_arsip = array();
		foreach ($file_arsip->result() as $r_ba) {
			$butir_arsip[] = $r_ba->arsip;
		}

		$arsip_logs = implode(', ', $butir_arsip);

		$data_log = array(
			'id_operator'=>$this->session->userdata('id_pegawai'),
			'id_modul'=>1,
			'act'=>3,
			'data_value'=>array($arsip_konten->arsip_jenis, $arsip_konten->judul, $arsip_logs),
			'field_value'=>array('judul', 'arsip_jenis'),
			'field_alias'=>array('jenis arsip', 'perihal', 'butir arsip terhapus')
			);

		log_users($data_log);
		// ./konten arsip
		// ./simpan ativitas pengguna

		
		foreach($file_arsip->result() as $fl) {
			$path = './uploads/arsip/'.$fl->direktori.'/'.$fl->arsip;
			if(file_exists($path)) unlink($path);		
			$this->general_model->delete_data('arsip','id_arsip',$fl->id_arsip);
		}
		
		$this->general_model->delete_data('arsip_kolom','id_arsip_konten',$o['id']);
		$this->general_model->delete_data('arsip_konten_set','id_arsip_konten',$o['id']);
		$this->general_model->delete_data('arsip_konten','id_arsip_konten',$o['id']);
		
		$this->session->set_flashdata('ok','Arsip berhasil dihapus ...');
		redirect('filing/pengarsipan/list_data/'.in_de($o['id_jenis']).'/'.$o['search'].'/'.$o['offset']);
		
	}
	
	function delete_berkas($par) {
	
		$o = un_de($par);
		
		$path = './uploads/arsip/'.$o['direktori'].'/'.$o['arsip'];
		if(file_exists($path)) unlink($path);

		// simpan ativitas pengguna
		// konten arsip
		$arsip_konten = $this->general_model->datagrab(array(
			'tabel'=>array(
				'arsip d'=>'',
				'arsip_konten_set a'=>'a.id_arsip=d.id_arsip', 
				'arsip_konten b'=>'b.id_arsip_konten=a.id_arsip_konten', 
				'ref_arsip_jenis c'=>'c.id_arsip_jenis=b.id_arsip_jenis',
				), 
			'where'=>array('a.id_arsip'=>$o['id_arsip']),
			'select'=>'a.*, b.judul, b.no_surat, c.arsip_jenis, d.arsip'
			))->row();

		// cek($this->db->last_query());die();

		$data_log = array(
			'id_operator'=>$this->session->userdata('id_pegawai'),
			'id_modul'=>2,
			'act'=>3,
			'data_value'=>array($arsip_konten->arsip_jenis, $arsip_konten->judul,$arsip_konten->no_surat, $arsip_konten->arsip),
			'field_value'=>array('arsip_jenis', 'judul', 'no_surat', 'arsip'),
			'field_alias'=>array('jenis arsip', 'perihal', 'nomor', 'butir arsip terhapus')
			);

		log_users($data_log);
		// ./konten arsip
		// ./simpan ativitas pengguna
		
		$this->general_model->delete_data('arsip_konten_set','id_arsip',$o['id_arsip']);	
		$this->general_model->delete_data('arsip','id_arsip',$o['id_arsip']);			
		$this->session->set_flashdata('ok','Arsip berhasil dihapus');
		
		redirect('filing/pengarsipan/list_data/'.in_de($o['id_arsip_jenis']).'/'.$o['fcari']);
		
	}
	
	function delete_berkas_form($par) {
	
		$o = un_de($par);
		
		$path = './uploads/arsip/'.$o['direktori'].'/'.$o['arsip'];
		if(file_exists($path)) unlink($path);

		// simpan ativitas pengguna
		// konten arsip
		$arsip_konten = $this->general_model->datagrab(array(
			'tabel'=>array(
				'arsip d'=>'',
				'arsip_konten_set a'=>'a.id_arsip=d.id_arsip', 
				'arsip_konten b'=>'b.id_arsip_konten=a.id_arsip_konten', 
				'ref_arsip_jenis c'=>'c.id_arsip_jenis=b.id_arsip_jenis',
				), 
			'where'=>array('a.id_arsip'=>$o['id_arsip']),
			'select'=>'a.*, b.judul, b.no_surat, c.arsip_jenis, d.arsip'
			))->row();

		// cek($this->db->last_query());die();

		$data_log = array(
			'id_operator'=>$this->session->userdata('id_pegawai'),
			'id_modul'=>2,
			'act'=>3,
			'data_value'=>array($arsip_konten->arsip_jenis, $arsip_konten->judul,$arsip_konten->no_surat, $arsip_konten->arsip),
			'field_value'=>array('arsip_jenis', 'judul', 'no_surat', 'arsip'),
			'field_alias'=>array('jenis arsip', 'perihal', 'nomor', 'butir arsip terhapus')
			);

		log_users($data_log);
		// ./konten arsip
		// ./simpan ativitas pengguna

		
		$this->general_model->delete_data('arsip_konten_set','id_arsip',$o['id_arsip']);	
		$this->general_model->delete_data('arsip','id_arsip',$o['id_arsip']);			
		$this->session->set_flashdata('ok','Arsip berhasil dihapus');
		
		redirect('filing/pengarsipan/form_data/'.in_de($o['id_arsip_jenis']).'/'.$o['id_arsip_konten']);
		
	}


	

}
?>