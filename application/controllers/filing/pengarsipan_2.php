<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengarsipan extends CI_Controller {

	var $unit_akar_atas = array();
	var $unit_akar_bawah = array();
	var $bidang_akar_atas = array();
	var $bidang_akar_bawah = array();

	function __construct() {
	
		parent::__construct();
		login_check($this->session->userdata('login_state'));
		
	}

	public function index() {

		$this->list_data();
	
	}
	
	function set_akar_atas($e) {
		if (!empty($e)) {
		$this->unit_akar_atas[] = $e;
		$dt = $this->general_model->datagrab(array('tabel' => 'ref_unit','where' => array('id_unit' => $e)))->row();
		$dt_ats = $this->general_model->datagrab(array('tabel' => 'ref_unit','where' => array('id_unit' => $dt->id_par_unit)))->row();
		if (!empty($dt_ats)) $this->set_akar_atas($dt_ats->id_unit);
		}
	}
	
	function set_akar_bawah($e) {
		if (!empty($e)) {
		$this->unit_akar_bawah[] = $e;
		$dt_ats = $this->general_model->datagrab(array('tabel' => 'ref_unit','where' => array('id_par_unit' => $e)))->row();
		if (!empty($dt_ats)) $this->set_akar_bawah($dt_ats->id_unit);
		}
	}
	
	function set_bidang_atas($e) {
		if (!empty($e)) {
		$this->unit_bidang_atas[] = $e;
		$dt = $this->general_model->datagrab(array('tabel' => 'ref_bidang','where' => array('id_bidang' => $e)))->row();
		$dt_ats = $this->general_model->datagrab(array(
			'tabel' => 'ref_bidang','where' => array('id_bidang' => $dt->id_par_bidang)))->row();
		if (!empty($dt_ats)) $this->set_bidang_atas($dt_ats->id_bidang);
		}
	}
	
	function set_bidang_bawah($e) {
		if (!empty($e)) {
		$this->unit_bidang_bawah[] = $e;
		//cek($e);
		$dt_ats = $this->general_model->datagrab(array('tabel' => 'ref_bidang','where' => array('id_par_bidang' => $e)))->row();
		if (!empty($dt_ats)) $this->set_bidang_bawah($dt_ats->id_bidang);
		}
	}
	
	function list_data($search =null, $offset = null) {
		 $get_mulai = $this->session->userdata('tgl_mulai');
		 $get_selesai = $this->session->userdata('tgl_selesai');

		// KEWENANGAN
		$id_peg = $this->session->userdata('id_pegawai');

		$r_addkonten = $this->general_model->check_role($id_peg,'ARSAD');
		$r_editkonten = $this->general_model->check_role($id_peg,'ARSED');
		$r_delkonten = $this->general_model->check_role($id_peg,'ARSDE');
		$r_delarsip = $this->general_model->check_role($id_peg,'FILDE');
		
		$offset = !empty($offset) ? $offset : null;
		
		$key = $this->input->post('search');
		$se = array();
	
	
		$data['arsip_jenis'] = $this->general_model->datagrab(array(
			'tabel' => array(
				'ref_arsip_jenis j' => '',
				'arsip_konten k' => 'k.id_arsip_jenis = j.id_arsip_jenis'),
			'select' => 'j.arsip_jenis,count(k.id_arsip_konten) as jml, j.id_arsip_jenis',
			'group_by' => 'k.id_arsip_jenis' 
		));
		
		/* Pengelompokan arsip bebas */
		$whereis = array();
		$bebas_data = $this->general_model->datagrab(array(
			'tabel' => 'ref_arsip_jenis','where' => array('id_arsip_jenis NOT IN (select id_arsip_jenis from arsip_akses)' => null)));
		$bebas = array();
		foreach($bebas_data->result() as $d) { $bebas[] = $d->id_arsip_jenis; }
		
		/* -- NOt Root -- */

		if (!empty($this->session->userdata('id_unit'))) {

		$this->unit_akar_atas = array();
		$this->unit_akar_bawah = array();
		$this->set_akar_atas($this->session->userdata('id_unit'));
		$this->set_akar_bawah($this->session->userdata('id_unit'));
		
		$where_akar = array();
	
		if (count($this->unit_akar_atas) > 0) $where_akar = array_merge_recursive($where_akar,$this->unit_akar_atas);
		if (count($this->unit_akar_bawah) > 0) $where_akar = array_merge_recursive($where_akar,$this->unit_akar_bawah);

		if (count($where_akar) > 0) {
		$dt_akar = $this->general_model->datagrab(array(
			'tabel' => 'arsip_akses',
			'where' => array('id_unit IN ('.implode(',',$where_akar).') AND (id_bidang = 0 or id_bidang IS NULL)' => null)
		));

		foreach($dt_akar->result() as $dd) { $bebas[] = $dd->id_arsip_jenis; }
		}
		
		/* -- Untuk Bidang -- */
		
		$this->unit_bidang_atas = array();
		$this->unit_bidang_bawah = array();
		$this->set_bidang_atas($this->session->userdata('id_bidang'));
		$this->set_bidang_bawah($this->session->userdata('id_bidang'));
		
		$where_bidang = array();
		
		if (count($this->unit_bidang_atas) > 0) $where_bidang = array_merge_recursive($where_bidang,$this->unit_bidang_atas);
		if (count($this->unit_bidang_bawah) > 0) $where_bidang = array_merge_recursive($where_bidang,$this->unit_bidang_bawah);
		//	cek($where_bidang);
		if (count($where_bidang) > 0) {
				$cek_par = $this->general_model->datagrab(array(
				'tabel' => 'arsip_akses',
				'where' => array('id_par_bidang'=> $this->session->userdata('id_bidang'))
			))->num_rows();
		//cek($cek_par); tambahan fix
		if(@$cek_par <= 0){
			$where_par = array('id_bidang IN ('.implode(',',$where_bidang).')'=> null);
		}else{
			$where_par = '';
		}
		$dt_bidang = $this->general_model->datagrab(array(
			'tabel' => 'arsip_akses',
			'where' => $where_par
		));

		foreach($dt_bidang->result() as $dd) { $bebas[] = $dd->id_arsip_jenis; }
		}

		} else {

			$bid = $this->general_model->datagrab(array('tabel' => 'arsip_akses'));
			foreach($bid->result() as $b) { $bebas[] = $b->id_bidang; }

		}
		
		if (count($bebas) > 0) $whereis = array_merge_recursive($whereis,array('id_arsip_jenis IN('.implode(',',$bebas).')' => null));

		/* --- */
		
		$data['jenis'] = $this->general_model->datagrab(array(
			'tabel' => 'ref_arsip_jenis',
			'order' => 'arsip_jenis',
			'where' => $whereis));

			$search_key = $this->input->post('key');
			
			if ($search) {
			$o=un_de($search);
			$se['arsip_judul'] = @$o['arsip_judul'];
			$se['judul'] = !empty($search_key)?$search_key:(!empty($o['judul'])?$o['judul']:null);
			$se['no_surat'] = !empty($search_key)?$search_key:(!empty($o['no_surat'])?$o['no_surat']:null);
			$se['id_arsip_jenis'] = @$o['id_arsip_jenis'];

			}elseif(empty($search_key) || $search_key == '') {
			$se['id_arsip_jenis'] = @$data['jenis']->row()->id_arsip_jenis;
			$se['arsip_judul'] = @$data['jenis']->row()->arsip_jenis;

		}

		
		$data['for_link'] = $se;
		$data['arsip_aktif'] = $se['id_arsip_jenis'];
		
		$sea = array();
		if (!empty($se['judul'])) $sea['judul'] = $se['judul'];
		if (!empty($se['no_surat'])) $sea['no_surat'] = $se['no_surat'];
		
		$from = array(
			'arsip_konten k' => '',
			'arsip_kolom ko' => array('ko.id_arsip_konten = k.id_arsip_konten','left')
		);
		
		(!empty($get_mulai) or !empty($get_selesai))? $where = array('id_arsip_jenis' => $se['id_arsip_jenis'], '(tanggal BETWEEN "'.$get_mulai.'" AND "'.$get_selesai.'")' =>null)
		: $where = array('id_arsip_jenis' => $se['id_arsip_jenis']);
		$select = array('k.*, ko.id_kolom, ko.id_arsip_kolom, ko.konten');
		
		$config['base_url']	= site_url('filing/pengarsipan/list_data/'.in_de($se));
		$config['total_rows'] = $this->general_model->datagrabs(array('tabel'=> $from,'search' => !empty($sea) ? $sea : '','group_by' => 'k.id_arsip_konten','where' => $where, 'select'=>$select))->num_rows();
		$config['per_page']	= '10';
		$config['uri_segment'] = '5';
		
		$this->pagination->initialize($config);
		
		$data['links']	= $this->pagination->create_links();
		
		$offs = (!in_array($offset,array("cetak","excel"))) ?  $offset : null;
		$lim = (!in_array($offset,array("cetak","excel"))) ? $config['per_page'] : null;
		
		if ($offset == "cetak" or $offset == "excel") {

			$query = $this->general_model->datagrabs(array(
			'tabel' => $from,
			'select' => 'count(k.id_arsip_konten),k.id_arsip_konten,k.judul,k.id_arsip_jenis,k.tanggal,k.no_surat',
			//'offset' => $offs,
			//'limit' => '10',
			'order' => 'k.tanggal',
			'search' => !empty($sea) ? $sea : '',
			'where' => $where,
			'group_by' => 'k.id_arsip_konten'
		));
		//echo $this->db->last_query();
		}else{
		$query = $this->general_model->datagrabs(array(
			'tabel' => $from,
			'select' => 'count(k.id_arsip_konten),k.id_arsip_konten,k.judul,k.id_arsip_jenis,k.tanggal,k.no_surat',
			'offset' => $offs,
			'limit' => '10',
			'order' => 'k.tanggal',
			'search' => !empty($sea) ? $sea : '',
			'where' => $where,
			'group_by' => 'k.id_arsip_konten'
		));
		//echo $this->db->last_query();	
		}
// CEK JENIS	

	if ($data['jenis']->num_rows() > 0) {
		// LIST ARSIP
		if ($query->num_rows() != 0) {
			$classy = (in_array($offset,array("cetak","excel"))) ? 'class="tabel_print" border=1' : 'class="table table-striped table-bordered table-condensed table-nonfluid"';
			$this->table->set_template(array('table_open'=>'<table '.$classy.'>'));
			$heads = array('No');
			if ($r_delkonten and $r_editkonten) {
				$n_row = 2;
			}elseif($r_delkonten and !$r_editkonten){
				$n_row = 1;
			}elseif(!$r_delkonten and $r_editkonten){
				$n_row = 1;
			}else{
				$n_row = 1;
			}
			
			
			if (!in_array($offset,array('cetak','excel'))) ($r_delkonten or $r_editkonten) ? $heads[] = array('data' => 'Aksi','colspan' => $n_row) : null;
			if (!in_array($offset,array('cetak','excel'))) $heads[] = array('data' => 'Arsip');
			

			$heads = array_merge_recursive($heads,array('Judul','Tanggal Arsip', 'Nomor Surat'));
			
			$re_kol = array();
			$re_kol_d = $this->general_model->datagrab(array(
				'tabel' => 'ref_arsip_kolom k',
				'where' => array('id_arsip_jenis' => $se['id_arsip_jenis'])));
			foreach($re_kol_d->result() as $re) {
				$re_kol[] = $re->kolom;
			}
			if (!in_array($offset,array('cetak','excel'))) $re_kol[] = "Detail";
			$heads = array_merge_recursive($heads,$re_kol);
			$this->table->set_heading($heads);
		
			$no = $offset + 1;
			
			$in_d = array();
			foreach($query->result() as $q) { $in_d[] = $q->id_arsip_konten; }
			
			$file_arsip = $this->general_model->datagrab(array(
				'tabel' => array(
					'arsip a' => '',
					'arsip_konten_set s' => 's.id_arsip = a.id_arsip',
					'ref_arsip_jenis j' => 'j.id_arsip_jenis = a.id_arsip_jenis'),
				'where' => array('id_arsip_konten IN ('.implode(',',$in_d).')' => null)
			));
			
			
			$kol = $this->general_model->datagrab(array(
				'tabel' => $from,
				'where' => array('k.id_arsip_konten IN ('.implode(',',$in_d).')' => null)
			));
			foreach($query->result() as $row){
				
				$arsip_disp = array();
				foreach($file_arsip->result() as $ars) {
					
					
					if ($ars->id_arsip_konten == $row->id_arsip_konten) { 
						$del_ars = in_de(array(
						'arsip' => $ars->arsip,
						'id_arsip' => $ars->id_arsip,
						'direktori' => $ars->direktori,
						'search' => in_de($se),
						'offset' => $offset));
						
						if (!in_array($offset,array('cetak','excel'))) {
						$del_berkas = ($r_delarsip) ? anchor('#','<i class="fa fa-times-circle"></i>','class="btn-delete" act="'.site_url('filing/pengarsipan/delete_berkas/'.$del_ars).'" msg="Apakah ingin menghapus Arsip ini?"') : null;
						$arsip_disp[] = '<li style="font-size: 0.9em; white-space:nowrap">'.anchor(base_url().'uploads/arsip/'.$ars->direktori.'/'.$ars->arsip,' &nbsp; <i class="fa fa-file-text-o"></i> &nbsp;'.str_replace('.', '_', $ars->arsip),'class="fancybox" target="_blank"').' &nbsp; '.
						$del_berkas.'</li>';
						} else {
						$arsip_disp[] = '<li>'.$ars->arsip.'</li>';
						}
				}}
				
				$rows = array($no);
				if (!in_array($offset,array('cetak','excel'))) {
					if ($r_editkonten) $rows[] =  anchor('filing/pengarsipan/form_data/'.$se['id_arsip_jenis'].'/'.$row->id_arsip_konten,'<i class="fa fa-pencil"></i>');
					
					if ($del_berkas = ($r_delkonten)) {
					$del_ar = in_de(array(
						'id' => $row->id_arsip_konten,
						'search' => in_de($se),
						'offset' => $offset));
					$del_msg = 'Apakah Anda ingin menghapus arsip ini?<br>Menghapus konten arsip ini akan menghapus seluruh arsip fisik di dalamnya!';
					 if ($r_delkonten) $rows[] = anchor('#','<i class="fa fa-trash"></i>','class="btn-delete" act="'.site_url('filing/pengarsipan/delete_konten/'.$del_ar).'" msg="'.$del_msg.'"');
					}
				}
				if (!in_array($offset,array('cetak','excel'))) $rows[] = (count($arsip_disp) > 0) ? '<ol style="margin: 0; padding: 0 10px;">'.implode(' ',$arsip_disp).'</ol>' : null;
				$rows[] = $row->judul;
				$rows[] = tanggal($row->tanggal);
				$rows[] = $row->no_surat;
				
				foreach($re_kol_d->result() as $re) {
					$dkol = null;
					foreach($kol->result() as $k) {
						
						if ($k->id_arsip_kolom == $re->id_arsip_kolom AND $k->id_arsip_konten == $row->id_arsip_konten) {
							$dkol = ($re->tipe == 3) ? tanggal($k->konten) : $k->konten;
						}
					}
					$rows[] = $dkol;
				}
				
				
				if (!in_array($offset,array('cetak','excel'))) $rows[] = anchor('filing/pengarsipan/info/'.$row->id_arsip_konten,' &nbsp; &nbsp;<i class="fa fa-search"></i>','class= "oninfo link-normal"');
				
				$this->table->add_row($rows);
				$no++;
			}
	
			$tabel = '
				<div class="alert alert-confirm" id="load" style="display: none">Memuat ...</div>
				<div id="box_content"></div>
				<div id="box_tabel">'.$this->table->generate().'</div>';
			
			$data['script'] = 
				"$(document).ready(function() {
					$('.oninfo').click(function() {
						$('#load').show();
					  $.ajax({
					      url: $(this).attr('href'),
					      cache: false,
					      success: function(msg) {
					         $('#box_content').html(msg).show();
					         $('#box_tabel,.stat-info').hide();
							 $('#load').hide();
							
					      },error:function(error){
							$('#load').hide();
							$('#load-error').html('ERROR : '+error).show();
							}
					   });
						return false;
					});
				
				});
				$(function(){
					$('.fancybox').fancybox();
					})";
		}else{
			$tabel = '<div class="alert alert-confirm pull-left">Belum ada arsip</div><div class="clear"></div>';
			$data['script'] = null;
		}
		// ./LIST ARSIP
	}else{
		$tabel = '<div class="alert alert-confirm pull-left">Belum ada arsip</div><div class="clear"></div>';
		$data['script'] = null;
	}
	// ./CEK JENIS
		
		
		
		$btn_cetak = ($query->num_rows() > 0) ?
		'<div class="btn-group"  tyle="margin-left: 5px;">
		<a class="btn btn-warning dropdown-toggle" data-toggle="dropdown" href="#" style="margin: 0 0 0 5px">
		<i class="fa fa-print"></i> <span class="caret"></span>
		</a>
		<ul class="dropdown-menu pull-right">
		<li>'.anchor('filing/pengarsipan/list_data/'.in_de($se).'/cetak','<i class="fa fa-print"></i> Cetak','target="_blank"').'</li>
		<li>'.anchor('filing/pengarsipan/list_data/'.in_de($se).'/excel','<i class="fa fa-file-excel-o"></i> Ekspor Excel','target="_blank"').'</li>
		</ul>
		</div>' : null;
		
		$btn_tambah = ($data['jenis']->num_rows() > 0) ? (($r_addkonten) ? anchor('filing/pengarsipan/form_data/'.$se['id_arsip_jenis'],'<i class="fa fa-plus"></i> &nbsp; Arsip '.$se['arsip_judul'],'class="btn btn-success"') : null) : null;
		
		$data['total'] = $config['total_rows'];
		$data['tombol'] = $btn_tambah.$btn_cetak;

		$data['extra_tombol'] = 
		form_open('filing/pengarsipan/list_data/'.in_de($se),'id="form_search" role="form"').
		'<div class="input-group">
				  <input name="key" type="text" placeholder="Pencarian ..." class="form-control pull-right" value="'.@$search_key.'">
				  <div class="input-group-btn">
					<button class="btn btn-default"><i class="fa fa-search"></i></button>
				  </div>
				</div>'.
				form_close();

		// $data['extra_tombol'] = 
		// form_open('filing/pengarsipan/list_data/'.in_de($se),'id="form_search"').
		// '<div class="input-group">
  //                 <input name="search" type="text" placeholder="Pencarian ..." class="form-control pull-right" value="'.(!empty($key)?$key:(!empty($o['arsip_jenis'])?$o['arsip_jenis']:null)).'">
  //                 <div class="input-group-btn">
  //                   <button class="btn btn-default"><i class="fa fa-search"></i></button>
  //                 </div>
  //               </div>'.form_close();
		$data['tombol_filter'] = form_open('#', array('id'=>'form_filter')).
		'<div class="box">

                <div class="box-body">
                  <div class="row">
                  <div class="col-xs-3">
                  '.form_input('mulai', @$get_mulai, 'class="form-control datepicker" placeholder="Mulai"').'
                  </div>

                  <div class="col-xs-3">
                  '.form_input('selesai',@$get_selesai, 'class="form-control datepicker" placeholder="Selesai"').'
                  </div>

                  <div class="col-xs-2">
                  <button type="button" name="btn_filter" id="btn_filter" class="btn btn-default">Filter</button>
                  </div>
                   

                  </div>
                </div><!-- /.box-body -->
</div><!-- /.box -->'.form_close();

	
	$data['script'] .= 
				"
			$(document).ready(function() {
					$('.datepicker').datepicker({
					format  : 'yyyy-mm-dd', 
					todayBtn: 'linked', 
					language: 'id',
                  autoclose: true,
					});
				});

			$(function(){

				$('#btn_filter').click(function() {
				
				$.ajax({
                url: '".site_url('filing/pengarsipan/set_filter')."',
                async: false,
                type: 'POST',
                data: $('#form_filter').serialize(),
                dataType: 'json',
                success: function(data) {
                	console.log(data);
                	//alert(data.get_se_filter.mulai+','+data.get_se_filter.selesai);
                	window.location.href='".site_url('filing/pengarsipan/list_data/'.in_de($se))."';
                }
            
            });
				});


			});
				
				";


		$data['tabel']	= $tabel;

		$j = 'Pengarsipan '.$se['arsip_judul'];

		// SIDE BAR JENIS ARSIP

		$tb_arsip_jenis = $this->general_model->datagrab(array(
			'tabel' => 'ref_arsip_jenis',
			'order' => 'arsip_jenis',
			'where' => array_merge_recursive(array('id_par IS NULL'=>null), $whereis)));

		$arsip_jenis = $this->general_model->datagrab(array(
			'tabel' => array(
				'ref_arsip_jenis j' => '',
				'arsip_konten k' => 'k.id_arsip_jenis = j.id_arsip_jenis'),
			'select' => 'j.arsip_jenis,count(k.id_arsip_konten) as jml, j.id_arsip_jenis',
			'group_by' => 'k.id_arsip_jenis' 
		));


		// arsip jenis side bar
			if ($tb_arsip_jenis->num_rows() > 0) {
				$this->table->set_template(array('table_open'=>'<table class="tabel-direktori">'));
				$r = 0;
				foreach ($tb_arsip_jenis->result() as $aj) {

				$act = ($aj->id_arsip_jenis == $data['arsip_aktif']) ? 'class="active"':null;
				$bg_col = ($aj->id_arsip_jenis == $data['arsip_aktif']) ? 'bg-active link-normal':null;
				$font = ($aj->id_arsip_jenis == $data['arsip_aktif']) ? 'font-weight: bold;':null;
				$p = ($aj->id_arsip_jenis == $data['arsip_aktif']) ? 'label-primary':'label-default';

					$se = in_de(array(
					  		'arsip_judul' => $aj->arsip_jenis,
					  		'id_arsip_jenis' => $aj->id_arsip_jenis
				  		));
				  		
				  		$jml = null;
				  		foreach($arsip_jenis->result() as $ars) {
				  			if ($ars->id_arsip_jenis ==  $aj->id_arsip_jenis) $jml = '<span class="label '.$p.' pull-right">'.$ars->jml.'</span>';
				  		}
				  		
				  		$btn =  '<li '.$act.'>'.anchor(
				  			'filing/pengarsipan/list_data/'.$se,
				  			$aj->arsip_jenis.$jml,'style="font-size: 0.95em; '.$font.'"').'</li>';

				  	// data
					$rows = array();

					$kode = '<b>'.$aj->kode_arsip_jenis.'</b>';
					$nama = $aj->arsip_jenis;

					$sub_jenis = $this->general_model->datagrabs(array(
					'tabel'=>'ref_arsip_jenis',
					'where'=>array('id_par'=>$aj->id_arsip_jenis)));
					$ch_count = $this->general_model->datagrabs(array(
						'tabel'=>array(
							'ref_arsip_jenis a'=>'',
							'(SELECT * 
								FROM ref_arsip_jenis 
								GROUP BY id_par
								) b'=>'b.id_arsip_jenis=a.id_arsip_jenis',
							),
						'select'=>'a.jm',
						'where'=>array('a.id_par IS NOT NULL'=>null),
						'count'=>'count(a.id_arsip_jenis) jm'))->row()->jm;
					$rows[] = '<i class="fa fa-file-o"></i>';
					$rows[] = array('data'=> $btn, 'colspan'=>7, 'class'=>$bg_col);
					$this->table->add_row($rows);

					if ($sub_jenis->num_rows() > 0){
					$this->tabel_sub(2, $aj->id_arsip_jenis, $data['arsip_aktif']);
					$r += 1;
				}
					
				}

				$table_jenis = $this->table->generate();
			}else{
				$table_jenis = 'Tidak/belum ada data Jenis Arsip';
			}
		// ./arsip jenis side bar
		$data['table_jenis'] = '<ul class="direktori">'.$table_jenis.'</ul>';	
		// ./SIDE BAR ARSIP JENIS
		
		$data['breadcrumb'] = array('' => 'Referensi','filing/pengarsipan' => $j);
		if ($offset == "cetak") {
			$data['fcetak'] = 1;
			$data['title'] = '<h3>'.$j.'</h3>';
			$data['content'] = $tabel;
			$this->load->view('umum/print',$data);
		} else if ($offset == "excel") {
			$data['file_name'] = str_replace(" ","_",strtolower('pengarsipan')).'.xls';
			$data['title'] = $j;
			$data['content'] = $tabel;
			$this->load->view('umum/excel',$data);
		} else {
			$data['tabel'] = $tabel;
			$data['title'] = $j;
			
			$data['content'] = ($data['jenis']->num_rows() > 7) ? "filing/arsip_view" : "filing/arsip_view";
			$this->load->view('home', $data);
		}
	}


		function tabel_sub($level,$id, $arsip_aktif=null, $lap=null) {
		
		$from = 'ref_arsip_jenis';


		
		$sub_jenis = $this->general_model->datagrabs(array(
			'tabel' => $from,
			'where' => array('id_par' => $id),
			'order' => 'urut, arsip_jenis',
			'select' => '*'
		));

		$arsip_jenis = $this->general_model->datagrab(array(
			'tabel' => array(
				'ref_arsip_jenis j' => '',
				'arsip_konten k' => 'k.id_arsip_jenis = j.id_arsip_jenis'),
			'select' => 'j.arsip_jenis,count(k.id_arsip_konten) as jml, j.id_arsip_jenis',
			'group_by' => 'k.id_arsip_jenis' 
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
			
				for ($j=0;$j < $level; $j++) {
					if ($j>0) $cell[] = array('data'=>'','width' => '20');
				}
				
				$btn_down = ($r+1 < $sub_jenis->num_rows()) ? anchor('filing/jenis_arsip/urutkan/'.in_de(array('id1' => $row->id_arsip_jenis,'no1' => $row->urut,'id2' => $awal_sub[$r+1][0],'no2' =>  $awal_sub[$r+1][1])),'<i class="fa fa-arrow-down"></i>','class="btn btn-xs btn-info"') : '';
				$btn_up = ($r > 0) ? anchor('filing/jenis_arsip/urutkan/'.in_de(array('id2' => $row->id_arsip_jenis,'no2' => $row->urut,'id1' =>  !empty($urutkan_sub[0])?$urutkan_sub[0]:@$awal_sub[0],'no1' => !empty($urutkan_sub[1])?$urutkan_sub[1]:@$awal_sub[1])),'<i class="fa fa-arrow-up"></i>','class="btn btn-xs btn-info"') : '';
				$urutkan_sub = array($row->id_arsip_jenis,$row->urut);
				// tekan kene
				
				$sub_jenis2 = $this->general_model->datagrabs(array('tabel' => 'ref_arsip_jenis', 'where' => array('id_par' => $row->id_arsip_jenis)));

			
				// $kode = !empty($row->kode_arsip_jenis)?'<b>'.$row->kode_arsip_jenis.'</b> |':null;
				// $nama = !empty($row->arsip_jenis)?' '.$row->arsip_jenis:null;
				// $dir = !empty($row->arsip_jenis)?' |folder: '.$row->direktori:null;

				$act = ($row->id_arsip_jenis == $arsip_aktif) ? 'class="active"':null;
				$bg_col = ($row->id_arsip_jenis == $arsip_aktif) ? 'bg-active link-normal':null;
				$font = ($row->id_arsip_jenis == $arsip_aktif) ? 'font-weight: bold;':null;
				$p = ($row->id_arsip_jenis == $arsip_aktif) ? 'label-primary':'label-default';
				$se = in_de(array(
					  		'arsip_judul' => $row->arsip_jenis,
					  		'id_arsip_jenis' => $row->id_arsip_jenis
				  		));
				  		
				  		$jml = null;
				  		foreach($arsip_jenis->result() as $ars) {
				  			if ($ars->id_arsip_jenis ==  $row->id_arsip_jenis) $jml = '<span class="badge '.$p.' pull-right">'.$ars->jml.'</span>';
				  		}
				  		
				  		$btn =  ($lap == 1) ? '<li '.$act.'>'.anchor('filing/pengarsipan/laporan/'.$se,$row->arsip_jenis.$jml,'style="font-size: 0.95em; '.$font.'"').'</li>' : '<li '.$act.'>'.anchor('filing/pengarsipan/list_data/'.$se,$row->arsip_jenis.$jml,'style="font-size: 0.95em; '.$font.'"').'</li>';
				
				$cell[] = '<i class="fa fa-file-o"></i>';
				$cell[] = array('data'=>$btn, 'colspan' => 8-$level, 'class'=>$bg_col);
				$cell = array_merge_recursive($cell);
			
				$this->table->add_row($cell);
				if ($sub_jenis2->num_rows() > 0) $this->tabel_sub($level+1,$row->id_arsip_jenis, $arsip_aktif, $lap);
				$r+=1;
			}
		}
		
	}

	function set_filter(){
		if (!empty($this->input->post('mulai') && !empty($this->input->post('selesai')))) {

			$data = array(
				'tgl_mulai'=>$this->input->post('mulai'),
				'tgl_selesai'=>$this->input->post('selesai')
				);

			$this->session->set_userdata($data);

			$data['get_se_filter']= array(
				'mulai'=>$this->session->userdata('tgl_mulai'), 
				'selesai'=>$this->session->userdata('tgl_selesai')
				);

			die(json_encode($data));
		}else{

			$this->session->unset_userdata('tgl_mulai');
			$this->session->unset_userdata('tgl_selesai');
			die(json_encode(array('status'=>'0')));
		}
	}

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
				$this->table->add_row('<em>'.$dk->kolom.'</em>',($dk->tipe == 3)?tanggal($dk->konten):$dk->konten);
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
	
	
	function form_data($j,$id = null) {
		
		$t = $this->general_model->datagrab(array('tabel' => 'ref_arsip_jenis','where' => array('id_arsip_jenis' => $j)))->row();
		
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

		$id_ar = $this->input->post('id_arsip_konten');
		$j = $this->input->post('arsip_judul');

		$id_jenis = $this->input->post('id_arsip_jenis');
		$id_form_kolom = $this->input->post('id_form_kolom');
		$konten = $this->input->post('konten');
		$tkol = $this->input->post('tipe');
		$jmlfile = $this->input->post('jmlfile');
		$tgl = tanggal_php($this->input->post('tanggal'));
		$no_surat = $this->input->post('no_surat');
		
		
		$simpan_info = array(
				'id_arsip_jenis' => $id_jenis,
				'judul' => $this->input->post('judul'),
				'tanggal' => $tgl,
				'no_surat' => $no_surat,
			
		); 

		//cek($simpan_info);die();
		$this->general_model->save_data('arsip_konten',$simpan_info,'id_arsip_konten',$id_ar);
		$id_r = (empty($id_ar)) ? $this->db->insert_id() : $id_ar;

		if ($id_ar) $this->general_model->delete_data('arsip_kolom','id_arsip_konten',$id_r);
		
		foreach($id_form_kolom as $k => $v) {
			$simpan_kol = array(
			'id_arsip_konten' => $id_r,
			'id_arsip_kolom' => $v,
			'konten' => ($tkol[$k] == '3') ? tanggal_php($konten[$k]) : $konten[$k]
			);$this->general_model->save_data('arsip_kolom',$simpan_kol);
		}
		

		$ard = $this->general_model->datagrab(array('tabel' => 'ref_arsip_jenis','where' => array('id_arsip_jenis' => $id_jenis)))->row();
		
		// Inisialisasi
		
		$path = "./uploads/arsip/".$ard->direktori;
		if (!is_dir($path)) mkdir($path,0777,TRUE);
      
		$eks_data = $this->general_model->datagrab(array('tabel' => 'ref_arsip_ekstensi'));
		$eks = array();
		foreach($eks_data->result() as $e) {
			$eks[] = $e->ekstensi;
		}
      
		$data_error = array();
		$data_file = array();
		
		for($i = 0; $i < $jmlfile; $i++){
				if (!empty($_FILES['mFile'.$i]['name'])) {
				$this->load->library('upload');
				
				$this->upload->initialize(array(
					'upload_path' => 'uploads/arsip/'.$ard->direktori,
					'allowed_types' => implode('|',$eks),
				));
				
				$this->upload->do_upload('mFile'.$i);
				$data_upload = $this->upload->data();
				
				$onerror = $this->upload->display_errors('&nbsp','&nbsp');
				if (!empty($onerror)) {
				
				$data_error[] = '<b>'.$data_upload['file_name'].'</b> : '.$onerror;

				} else {
				
				$simpan_arsip = array(
					'id_arsip_jenis' => $id_jenis,
					'op' => $this->session->userdata('id_pegawai'),
					'arsip' => $data_upload['file_name'],
					'tanggal' => $tgl.' '.date('H:i:s')
				); $id_arsip = $this->general_model->save_data('arsip',$simpan_arsip);
				
				$simpan_kait = array(
					
				); $this->general_model->save_data('arsip_konten_set',array('id_arsip' => $id_arsip,'id_arsip_konten' => $id_r));
				}
			}
		}
		
		if (count($data_error) > 0) $this->session->set_flashdata('fail','Kesalahan Unggah Arsip!<br>'.implode('<br>',$data_error));
		else $this->session->set_flashdata('ok','Arsip berhasil disimpan ...');
		
		
		redirect('filing/pengarsipan/list_data/'.in_de(array('arsip_judul' => $j,'id_arsip_jenis' => $id_jenis)));
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
		
		foreach($file_arsip->result() as $fl) {
			$path = './uploads/arsip/'.$fl->direktori.'/'.$fl->arsip;
			if(file_exists($path)) unlink($path);		
			$this->general_model->delete_data('arsip','id_arsip',$fl->id_arsip);
		}
		
		$this->general_model->delete_data('arsip_kolom','id_arsip_konten',$o['id']);
		$this->general_model->delete_data('arsip_konten_set','id_arsip_konten',$o['id']);
		$this->general_model->delete_data('arsip_konten','id_arsip_konten',$o['id']);
		
		$this->session->set_flashdata('ok','Arsip berhasil dihapus ...');
		redirect('filing/pengarsipan/list_data/'.$o['search'].'/'.$o['offset']);
		
	}
	
	function delete_berkas($par) {
	
		$o = un_de($par);
		/*cek($o['id_arsip']);
		die();*/
		if($o['id_arsip'] == 6 || $o['id_arsip'] == 7 || $o['id_arsip'] == 8 || $o['id_arsip'] == 9){
			$this->session->set_flashdata('fail', 'MAAF... khusus Arsip ini tidak bisa dihapus karena untuk contoh atau demo');
			redirect('filing/pengarsipan/list_data/'.$o['search'].'/'.$o['offset']);
		}else{
			$path = './uploads/arsip/'.$o['direktori'].'/'.$o['arsip'];
			if(file_exists($path)) unlink($path);
			
			$this->general_model->delete_data('arsip_konten_set','id_arsip',$o['id_arsip']);	
			$this->general_model->delete_data('arsip','id_arsip',$o['id_arsip']);			
			$this->session->set_flashdata('ok','Arsip berhasil dihapus');
			
			redirect('filing/pengarsipan/list_data/'.$o['search'].'/'.$o['offset']);
		}
		
	}
	
	function delete_berkas_form($par) {
	
		$o = un_de($par);
		
		$path = './uploads/arsip/'.$o['direktori'].'/'.$o['arsip'];
		if(file_exists($path)) unlink($path);
		
		$this->general_model->delete_data('arsip_konten_set','id_arsip',$o['id_arsip']);	
		$this->general_model->delete_data('arsip','id_arsip',$o['id_arsip']);			
		$this->session->set_flashdata('ok','Arsip berhasil dihapus');
		
		redirect('filing/pengarsipan/form_data/'.$o['id_arsip_konten']);
		
	}

	// LAPORAN:

	function laporan($search =null, $offset = null) {
		 $get_mulai = $this->session->userdata('tgl_mulai');
		 $get_selesai = $this->session->userdata('tgl_selesai');

		// KEWENANGAN
		$id_peg = $this->session->userdata('id_pegawai');

		$r_addkonten = $this->general_model->check_role($id_peg,'ARSAD');
		$r_editkonten = $this->general_model->check_role($id_peg,'ARSED');
		$r_delkonten = $this->general_model->check_role($id_peg,'ARSDE');
		$r_delarsip = $this->general_model->check_role($id_peg,'FILDE');
		
		$offset = !empty($offset) ? $offset : null;
		
		$key = $this->input->post('search');
		$se = array();
	
	
		$data['arsip_jenis'] = $this->general_model->datagrab(array(
			'tabel' => array(
				'ref_arsip_jenis j' => '',
				'arsip_konten k' => 'k.id_arsip_jenis = j.id_arsip_jenis'),
			'select' => 'j.arsip_jenis,count(k.id_arsip_konten) as jml, j.id_arsip_jenis',
			'group_by' => 'k.id_arsip_jenis' 
		));
		
		/* Pengelompokan arsip bebas */
		$whereis = array();
		$bebas_data = $this->general_model->datagrab(array(
			'tabel' => 'ref_arsip_jenis','where' => array('id_arsip_jenis NOT IN (select id_arsip_jenis from arsip_akses)' => null)));
		$bebas = array();
		foreach($bebas_data->result() as $d) { $bebas[] = $d->id_arsip_jenis; }
		
		/* -- NOt Root -- */

		if (!empty($this->session->userdata('id_unit'))) {

		$this->unit_akar_atas = array();
		$this->unit_akar_bawah = array();
		$this->set_akar_atas($this->session->userdata('id_unit'));
		$this->set_akar_bawah($this->session->userdata('id_unit'));
		
		$where_akar = array();
	
		if (count($this->unit_akar_atas) > 0) $where_akar = array_merge_recursive($where_akar,$this->unit_akar_atas);
		if (count($this->unit_akar_bawah) > 0) $where_akar = array_merge_recursive($where_akar,$this->unit_akar_bawah);

		if (count($where_akar) > 0) {
		$dt_akar = $this->general_model->datagrab(array(
			'tabel' => 'arsip_akses',
			'where' => array('id_unit IN ('.implode(',',$where_akar).') AND (id_bidang = 0 or id_bidang IS NULL)' => null)
		));

		foreach($dt_akar->result() as $dd) { $bebas[] = $dd->id_arsip_jenis; }
		}
		
		/* -- Untuk Bidang -- */
		
		$this->unit_bidang_atas = array();
		$this->unit_bidang_bawah = array();
		$this->set_bidang_atas($this->session->userdata('id_bidang'));
		$this->set_bidang_bawah($this->session->userdata('id_bidang'));
		
		$where_bidang = array();
	
		if (count($this->unit_bidang_atas) > 0) $where_bidang = array_merge_recursive($where_bidang,$this->unit_bidang_atas);
		if (count($this->unit_bidang_bawah) > 0) $where_bidang = array_merge_recursive($where_bidang,$this->unit_bidang_bawah);

		if (count($where_bidang) > 0) {
		$dt_bidang = $this->general_model->datagrab(array(
			'tabel' => 'arsip_akses',
			'where' => array('id_bidang IN ('.implode(',',$where_bidang).')' => null)
		));

		foreach($dt_bidang->result() as $dd) { $bebas[] = $dd->id_arsip_jenis; }
		}

		} else {

			$bid = $this->general_model->datagrab(array('tabel' => 'arsip_akses'));
			foreach($bid->result() as $b) { $bebas[] = $b->id_bidang; }

		}
		
		if (count($bebas) > 0) $whereis = array_merge_recursive($whereis,array('id_arsip_jenis IN('.implode(',',$bebas).')' => null));

		/* --- */
		
		$data['jenis'] = $this->general_model->datagrab(array(
			'tabel' => 'ref_arsip_jenis',
			'order' => 'arsip_jenis',
			'where' => $whereis));

			$search_key = $this->input->post('key');
			
			if ($search) {
			$o=un_de($search);
			$se['arsip_judul'] = @$o['arsip_judul'];
			$se['judul'] = !empty($search_key)?$search_key:(!empty($o['judul'])?$o['judul']:null);
			$se['no_surat'] = !empty($search_key)?$search_key:(!empty($o['no_surat'])?$o['no_surat']:null);
			$se['id_arsip_jenis'] = @$o['id_arsip_jenis'];

			}elseif(empty($search_key) || $search_key == '') {
			$se['id_arsip_jenis'] = @$data['jenis']->row()->id_arsip_jenis;
			$se['arsip_judul'] = @$data['jenis']->row()->arsip_jenis;

		}

		
		$data['for_link'] = $se;
		$data['arsip_aktif'] = $se['id_arsip_jenis'];
		
		$sea = array();
		if (!empty($se['judul'])) $sea['judul'] = $se['judul'];
		if (!empty($se['no_surat'])) $sea['no_surat'] = $se['no_surat'];
		
		$from = array(
			'arsip_konten k' => '',
			'arsip_kolom ko' => array('ko.id_arsip_konten = k.id_arsip_konten','left')
		);
		
		(!empty($get_mulai) or !empty($get_selesai))? $where = array('id_arsip_jenis' => $se['id_arsip_jenis'], '(tanggal BETWEEN "'.$get_mulai.'" AND "'.$get_selesai.'")' =>null)
		: $where = array('id_arsip_jenis' => $se['id_arsip_jenis']);
		$select = array('k.*, ko.id_kolom, ko.id_arsip_kolom, ko.konten');
		
		$config['base_url']	= site_url('filing/pengarsipan/list_data/'.in_de($se));
		$config['total_rows'] = $this->general_model->datagrabs(array('tabel'=> $from,'search' => !empty($sea) ? $sea : '','group_by' => 'k.id_arsip_konten','where' => $where, 'select'=>$select))->num_rows();
		$config['per_page']	= '10';
		$config['uri_segment'] = '5';
		
		$this->pagination->initialize($config);
		
		$data['links']	= $this->pagination->create_links();
		
		$offs = (!in_array($offset,array("cetak","excel"))) ?  $offset : null;
		$lim = (!in_array($offset,array("cetak","excel"))) ? $config['per_page'] : null;
		
		if ($offset == "cetak" or $offset == "excel") {

			$query = $this->general_model->datagrabs(array(
			'tabel' => $from,
			'select' => 'count(k.id_arsip_konten),k.id_arsip_konten,k.judul,k.id_arsip_jenis,k.tanggal,k.no_surat',
			//'offset' => $offs,
			//'limit' => '10',
			'order' => 'k.tanggal',
			'search' => !empty($sea) ? $sea : '',
			'where' => $where,
			'group_by' => 'k.id_arsip_konten'
		));
		//echo $this->db->last_query();
		}else{
		$query = $this->general_model->datagrabs(array(
			'tabel' => $from,
			'select' => 'count(k.id_arsip_konten),k.id_arsip_konten,k.judul,k.id_arsip_jenis,k.tanggal,k.no_surat',
			'offset' => $offs,
			'limit' => '10',
			'order' => 'k.tanggal',
			'search' => !empty($sea) ? $sea : '',
			'where' => $where,
			'group_by' => 'k.id_arsip_konten'
		));
		//echo $this->db->last_query();	
		}
// CEK JENIS	

	if ($data['jenis']->num_rows() > 0) {
		// LIST ARSIP
		if ($query->num_rows() != 0) {
			$this->table->set_template(array('table_open'=>'<table class="tabel_print" border=1>'));
			$heads = array('No');
			if ($r_delkonten and $r_editkonten) {
				$n_row = 2;
			}elseif($r_delkonten and !$r_editkonten){
				$n_row = 1;
			}elseif(!$r_delkonten and $r_editkonten){
				$n_row = 1;
			}else{
				$n_row = 1;
			}
			
			
			if (!in_array($offset,array('cetak','excel'))) $heads[] = array('data' => 'Arsip');
			

			$heads = array_merge_recursive($heads,array('Judul','Tanggal Arsip', 'Nomor Surat'));
			
			$re_kol = array();
			$re_kol_d = $this->general_model->datagrab(array(
				'tabel' => 'ref_arsip_kolom k',
				'where' => array('id_arsip_jenis' => $se['id_arsip_jenis'])));
			foreach($re_kol_d->result() as $re) {
				$re_kol[] = $re->kolom;
			}
			$heads = array_merge_recursive($heads,$re_kol);
			$this->table->set_heading($heads);
		
			$no = $offset + 1;
			
			$in_d = array();
			foreach($query->result() as $q) { $in_d[] = $q->id_arsip_konten; }
			
			$file_arsip = $this->general_model->datagrab(array(
				'tabel' => array(
					'arsip a' => '',
					'arsip_konten_set s' => 's.id_arsip = a.id_arsip',
					'ref_arsip_jenis j' => 'j.id_arsip_jenis = a.id_arsip_jenis'),
				'where' => array('id_arsip_konten IN ('.implode(',',$in_d).')' => null)
			));
			
			
			$kol = $this->general_model->datagrab(array(
				'tabel' => $from,
				'where' => array('k.id_arsip_konten IN ('.implode(',',$in_d).')' => null)
			));
			foreach($query->result() as $row){
				
				$arsip_disp = array();
				foreach($file_arsip->result() as $ars) {
					
					
					if ($ars->id_arsip_konten == $row->id_arsip_konten) { 
						$del_ars = in_de(array(
						'arsip' => $ars->arsip,
						'id_arsip' => $ars->id_arsip,
						'direktori' => $ars->direktori,
						'search' => in_de($se),
						'offset' => $offset));
						
						if (!in_array($offset,array('cetak','excel'))) {
						$arsip_disp[] = '<li style="font-size: 0.9em; white-space:nowrap">'.str_replace('.', '_', $ars->arsip).' &nbsp; '.
						'</li>';
						} else {
						$arsip_disp[] = '<li>'.$ars->arsip.'</li>';
						}
				}}
				
				$rows = array($no);
				
				if (!in_array($offset,array('cetak','excel'))) $rows[] = (count($arsip_disp) > 0) ? '<ol style="margin: 0; padding: 0 10px;">'.implode(' ',$arsip_disp).'</ol>' : null;
				$rows[] = $row->judul;
				$rows[] = tanggal($row->tanggal);
				$rows[] = $row->no_surat;
				
				foreach($re_kol_d->result() as $re) {
					$dkol = null;
					foreach($kol->result() as $k) {
						
						if ($k->id_arsip_kolom == $re->id_arsip_kolom AND $k->id_arsip_konten == $row->id_arsip_konten) {
							$dkol = ($re->tipe == 3) ? tanggal($k->konten) : $k->konten;
						}
					}
					$rows[] = $dkol;
				}
				
				
				$this->table->add_row($rows);
				$no++;
			}
	
			$tabel = $this->table->generate();
			
			$data['script'] = 
				"$(document).ready(function() {
					$('.oninfo').click(function() {
						$('#load').show();
					  $.ajax({
					      url: $(this).attr('href'),
					      cache: false,
					      success: function(msg) {
					         $('#box_content').html(msg).show();
					         $('#box_tabel,.stat-info').hide();
							 $('#load').hide();
							
					      },error:function(error){
							$('#load').hide();
							$('#load-error').html('ERROR : '+error).show();
							}
					   });
						return false;
					});
				
				});
				$(function(){
					$('.fancybox').fancybox();
					})";
		}else{
			$tabel = '<div class="alert alert-confirm pull-left">Belum ada arsip</div><div class="clear"></div>';
			$data['script'] = null;
		}
		// ./LIST ARSIP
	}else{
		$tabel = '<div class="alert alert-confirm pull-left">Belum ada arsip</div><div class="clear"></div>';
		$data['script'] = null;
	}
	// ./CEK JENIS
		
		
		
		$btn_cetak = ($query->num_rows() > 0) ?
		'<div class="btn-group"  tyle="margin-left: 5px;">
		<a class="btn btn-warning dropdown-toggle" data-toggle="dropdown" href="#" style="margin: 0 0 0 5px">
		<i class="fa fa-print"></i> <span class="caret"></span>
		</a>
		<ul class="dropdown-menu pull-right">
		<li>'.anchor('filing/pengarsipan/list_data/'.in_de($se).'/cetak','<i class="fa fa-print"></i> Cetak','target="_blank"').'</li>
		<li>'.anchor('filing/pengarsipan/list_data/'.in_de($se).'/excel','<i class="fa fa-file-excel-o"></i> Ekspor Excel','target="_blank"').'</li>
		</ul>
		</div>' : null;
		
		
		$data['total'] = $config['total_rows'];
		$data['tombol'] = $btn_cetak;

		$data['extra_tombol'] = 
		form_open('filing/pengarsipan/list_data/'.in_de($se),'id="form_search" role="form"').
		'<div class="input-group">
				  <input name="key" type="text" placeholder="Pencarian ..." class="form-control pull-right" value="'.@$search_key.'">
				  <div class="input-group-btn">
					<button class="btn btn-default"><i class="fa fa-search"></i></button>
				  </div>
				</div>'.
				form_close();

		// $data['extra_tombol'] = 
		// form_open('filing/pengarsipan/list_data/'.in_de($se),'id="form_search"').
		// '<div class="input-group">
  //                 <input name="search" type="text" placeholder="Pencarian ..." class="form-control pull-right" value="'.(!empty($key)?$key:(!empty($o['arsip_jenis'])?$o['arsip_jenis']:null)).'">
  //                 <div class="input-group-btn">
  //                   <button class="btn btn-default"><i class="fa fa-search"></i></button>
  //                 </div>
  //               </div>'.form_close();
		$data['tombol_filter'] = form_open('#', array('id'=>'form_filter')).
		'<div class="box">

                <div class="box-body">
                  <div class="row">
                  <div class="col-xs-3">
                  '.form_input('mulai', @$get_mulai, 'class="form-control datepicker" placeholder="Mulai"').'
                  </div>

                  <div class="col-xs-3">
                  '.form_input('selesai',@$get_selesai, 'class="form-control datepicker" placeholder="Selesai"').'
                  </div>

                  <div class="col-xs-2">
                  <button type="button" name="btn_filter" id="btn_filter" class="btn btn-default">Filter</button>
                  </div>
                   

                  </div>
                </div><!-- /.box-body -->
</div><!-- /.box -->'.form_close();

	
	$data['script'] .= 
				"
			$(document).ready(function() {
					$('.datepicker').datepicker({
					format  : 'yyyy-mm-dd', 
					todayBtn: 'linked', 
					language: 'id',
                  autoclose: true,
					});
				});

			$(function(){

				$('#btn_filter').click(function() {
				
				$.ajax({
                url: '".site_url('filing/pengarsipan/set_filter')."',
                async: false,
                type: 'POST',
                data: $('#form_filter').serialize(),
                dataType: 'json',
                success: function(data) {
                	console.log(data);
                	//alert(data.get_se_filter.mulai+','+data.get_se_filter.selesai);
                	window.location.href='".site_url('filing/pengarsipan/laporan/'.in_de($se))."';
                }
            
            });
				});


			});
				
				";


		$data['tabel']	= $tabel;

		$j = 'Pengarsipan '.$se['arsip_judul'];

		// SIDE BAR JENIS ARSIP

		$tb_arsip_jenis = $this->general_model->datagrab(array(
			'tabel' => 'ref_arsip_jenis',
			'order' => 'arsip_jenis',
			'where' => array_merge_recursive(array('id_par IS NULL'=>null), $whereis)));

		$arsip_jenis = $this->general_model->datagrab(array(
			'tabel' => array(
				'ref_arsip_jenis j' => '',
				'arsip_konten k' => 'k.id_arsip_jenis = j.id_arsip_jenis'),
			'select' => 'j.arsip_jenis,count(k.id_arsip_konten) as jml, j.id_arsip_jenis',
			'group_by' => 'k.id_arsip_jenis' 
		));


		// arsip jenis side bar
			if ($tb_arsip_jenis->num_rows() > 0) {
				$this->table->set_template(array('table_open'=>'<table class="tabel-direktori">'));
				$r = 0;
				foreach ($tb_arsip_jenis->result() as $aj) {

				$act = ($aj->id_arsip_jenis == $data['arsip_aktif']) ? 'class="active"':null;
				$bg_col = ($aj->id_arsip_jenis == $data['arsip_aktif']) ? 'bg-active link-normal':null;
				$font = ($aj->id_arsip_jenis == $data['arsip_aktif']) ? 'font-weight: bold;':null;
				$p = ($aj->id_arsip_jenis == $data['arsip_aktif']) ? 'label-primary':'label-default';

					$se = in_de(array(
					  		'arsip_judul' => $aj->arsip_jenis,
					  		'id_arsip_jenis' => $aj->id_arsip_jenis
				  		));
				  		
				  		$jml = null;
				  		foreach($arsip_jenis->result() as $ars) {
				  			if ($ars->id_arsip_jenis ==  $aj->id_arsip_jenis) $jml = '<span class="label '.$p.' pull-right">'.$ars->jml.'</span>';
				  		}
				  		
				  		$btn =  '<li '.$act.'>'.anchor(
				  			'filing/pengarsipan/laporan/'.$se,
				  			$aj->arsip_jenis.$jml,'style="font-size: 0.95em; '.$font.'"').'</li>';

				  	// data
					$rows = array();

					$kode = '<b>'.$aj->kode_arsip_jenis.'</b>';
					$nama = $aj->arsip_jenis;

					$sub_jenis = $this->general_model->datagrabs(array(
					'tabel'=>'ref_arsip_jenis',
					'where'=>array('id_par'=>$aj->id_arsip_jenis)));
					$ch_count = $this->general_model->datagrabs(array(
						'tabel'=>array(
							'ref_arsip_jenis a'=>'',
							'(SELECT * 
								FROM ref_arsip_jenis 
								GROUP BY id_par
								) b'=>'b.id_arsip_jenis=a.id_arsip_jenis',
							),
						'select'=>'a.jm',
						'where'=>array('a.id_par IS NOT NULL'=>null),
						'count'=>'count(a.id_arsip_jenis) jm'))->row()->jm;
					$rows[] = '<i class="fa fa-file-o"></i>';
					$rows[] = array('data'=> $btn, 'colspan'=>7, 'class'=>$bg_col);
					$this->table->add_row($rows);

					if ($sub_jenis->num_rows() > 0){
					$this->tabel_sub(2, $aj->id_arsip_jenis, $data['arsip_aktif'], 1);
					$r += 1;
				}
					
				}

				$table_jenis = $this->table->generate();
			}else{
				$table_jenis = 'Tidak/belum ada data Jenis Arsip';
			}
		// ./arsip jenis side bar
		$data['table_jenis'] = '<ul class="direktori">'.$table_jenis.'</ul>';	
		// ./SIDE BAR ARSIP JENIS
		
		$data['breadcrumb'] = array('' => 'Referensi','filing/pengarsipan' => $j);
		if ($offset == "cetak") {
			$data['fcetak'] = 1;
			$data['title'] = '<h3>'.$j.'</h3>';
			$data['content'] = $tabel;
			$this->load->view('umum/print',$data);
		} else if ($offset == "excel") {
			$data['file_name'] = str_replace(" ","_",strtolower('pengarsipan')).'.xls';
			$data['title'] = $j;
			$data['content'] = $tabel;
			$this->load->view('umum/excel',$data);
		} else {
			$data['tabel'] = $tabel;
			$data['title'] = $j;
			
			$data['content'] = ($data['jenis']->num_rows() > 7) ? "filing/arsip_view" : "filing/arsip_view";
			$this->load->view('home', $data);
		}
	}

}
?>