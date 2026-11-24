<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Referensi extends CI_Controller {

		var $dir = 'filing/referensi';

		function __construct() {
		
			parent::__construct();
			login_check($this->session->userdata('login_state'));
			
		}

		public function index() {
		
		
		}
		
		function ke($tipe,$search = null,$offset = null) {

			$key = $this->input->post('search');
			
			$modex = "list_data";
			$form_id = null;
			$search_param = null;
			if (!empty($key)) {
				$search_param = $key;
				
			} else if (!empty($search)) {
				$o = un_de($search);
				$modex = !empty($o['modex'])?$o['modex']:"list_data";
				$form_id = @$o['form_id'];
				$search_param = !empty($o['search']) ? $o['search'] : null;
			} 
		
			/* -- Info -- 
			
			Tombol
				1. Tambah
				2. Normal
			
			Form
				1. Input teks
				2. Dropdown
				3. Textarea
				
			*/

			
			switch($tipe) {
				
				case "ekstensi":
				
				$param = array(
						'inti' => 'ekstensi',
						'title' => 'Ekstensi Arsip',
						'tabel' => 'ref_arsip_ekstensi',
						'kolom' => array('Ekstensi','Keterangan'),
						'kolom_tampil' => array('ekstensi','keterangan'),
						'kolom_data' => array('ekstensi','keterangan'),
						'order' => 'ekstensi',
						'id' => 'id_ekstensi',
						'tombol' => array(array('1','Ekstensi')),
						'form' => array(
							array(1,TRUE,'Ekstensi','ekstensi',null),
							array(3,FALSE,'Keterangan','keterangan',null))
					);
				break;
			}
			
			$param = array_merge_recursive(array(
					'search' => $search_param,
					'module' => $this->dir.'/ke/'
			),$param);
					
			switch($modex) {
				case "list_data" : $this->list_data($param,$offset); break;
				case "form_mode" : $this->form_data($param,$search.'~'.(isset($offset)?$offset:'0'), $form_id);break;
				case "save_mode" : $this->save_data($param); break;
				case "excel_impor" : $this->impor_data($param,$search,$offset); break;
				case "save_impor"  : $this->save_impor($param,$search,$offset); break;
				case "hapus_data"  : $this->hapus_data($param,$offset); break;
			}
			
		}
		
		function list_data($o = null, $offset = null) {
	
			$data['breadcrumb'] = array('' => 'Referensi',$o['module'].$o['inti'] => $o['title']);
			if (!empty($o['breadcrumb'])) $data['breadcrumb'] = array_merge($data['breadcrumb'],$o['breadcrumb']);
			
			$offset = !empty($offset) ? $offset : null;
			
			$search = null;
			$se = !empty($o['search']) ? in_de(array('search' => $o['search'])) : in_de(array('search' => null));
			
			if (!empty($o['search'])) {
				$search = array();
				
				if (!empty($o['kolom_cari'])) {
				foreach($o['kolom_cari'] as $ks) { $search[$ks] = $o['search']; } 
				} else {
				foreach($o['kolom_data'] as $ks) { $search[$ks] = $o['search']; } 
				}

			}
			
			$config['base_url']	= site_url($o['module'].$o['inti'].'/'.$se);
			$config['total_rows'] = $this->general_model->datagrab(array(
				'select' => 'count(*) as jml',
				'tabel'=> $o['tabel'],
				'search' => $search,
				'where' => @$o['where']))->row()->jml;
			$config['per_page']	= '10';
			$config['uri_segment'] = '6';
			
			$this->pagination->initialize($config);
			
			$data['links']	= $this->pagination->create_links();
			
			$offs = (!in_array($offset,array("cetak","excel"))) ?  $offset : null;
			$lim = (!in_array($offset,array("cetak","excel"))) ? $config['per_page'] : null;
			
			$query = $this->general_model->datagrab(array(
				'tabel' => $o['tabel'],
				'offset' => $offs,
				'limit' => $lim,
				'order' => @$o['order'],
				'select' => @$o['select'],
				'search' => $search,
				'where' => @$o['where'],
				'group_by' => @$o['group_by']
			));
			

			if ($query->num_rows() != 0) {
				$classy = (in_array($offset,array("cetak","excel"))) ? 'class="tabel_print" border=1' : 'class="table table-striped table-bordered table-condensed table-nonfluid"';
				$this->table->set_template(array('table_open'=>'<table '.$classy.'>'));
				$heads = array_merge_recursive(array('No',
				array('data'=>'<input type="checkbox" name="cek_all" id="cek_all" class="cek-all"','class'=>'center','style'=>'width:30px')
				),$o['kolom']);
				if (!in_array($offset,array('cetak','excel'))) $heads[] = array('data' => 'Aksi','colspan' => '2');
				$this->table->set_heading($heads);
			
				$no = (is_numeric($offset) ? (int)$offset : 0) + 1;
				
				foreach($query->result() as $row){
					$rows = array($no);
					$rows[] = array('data' => '<input type="checkbox" name="cek[]" class="cek" value="'.$row->{$o['id']}.'">','style' => 'text-align: center');
					foreach($o['kolom_tampil'] as $kol) {
						$rows[] = $row->$kol;
					}
					
					$tabel_del = is_array($o['tabel'])?$o['tabel_save']:$o['tabel'];
					
					$link1 = anchor('#','<i class="fa fa-pencil"></i>','class="btn btn-edit btn-xs btn-warning" act="'.site_url($o['module'].$o['inti'].'/'.in_de(array('modex' => 'form_mode','form_id' => $row->{$o['id']},'search' => (isset($o['search'])?$o['search']:null))).'/'.$offset).'"');
					$link2 = anchor('#','<i class="fa fa-trash"></i>','class="btn btn-delete btn-xs btn-danger" act="'.site_url('home/general_delete/'.in_de(array('id' => $row->{$o['id']},'redirect' => $this->dir.'/ke/'.$o['inti'].'/'.in_de(array('modex' => 'list_data','search' => (isset($o['search'])?$o['search']:null))).'/'.$offset,'tabel' => $tabel_del,'kolom' => $o['id']))).'" msg="Apakah Anda ingin menghapus data ini?"');
					
					if (!in_array($offset,array('cetak','excel'))) {
						$rows[] = $link1;
						$rows[] = $link2;
					}
					
					$this->table->add_row($rows);
					$no++;
				}
		
				$tabel = 
					form_open($this->dir.'/ke/'.$o['inti'].'/'.in_de(array('modex' => 'hapus_data','search' => (isset($o['search'])?$o['search']:null))).'/'.$offset,'id="form_delete"').
						anchor('#','<i class="fa fa-trash"></i> &nbsp; Hapus Yang Tercentang','class="btn btn-danger btn-delete-all btn-sm" style="display: none; margin-bottom: 10px"').
						$this->table->generate().
						anchor('#','<i class="fa fa-trash"></i> &nbsp; Hapus Yang Tercentang','class="btn btn-danger btn-delete-all btn-sm" style="display: none; margin-top: 10px"').
					form_close();
			} else {
				$tabel = '<div class="alert">Tidak ditemukan data ...</div>';
			}
			
			
			$btn_cetak = ($query->num_rows() > 0) ?
			'<div class="btn-group" style="margin-left: 5px;">
			<a class="btn btn-warning dropdown-toggle" data-toggle="dropdown"><i class="fa fa-print fa-btn"></i> Cetak</a>
			<a class="btn btn-warning dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
			<ul class="dropdown-menu pull-right">
			<li>'.anchor($o['module'].$o['inti'].'/'.$se.'/cetak','<i class="fa fa-print"></i> Cetak','target="_blank"').'</li>
			<li>'.anchor($o['module'].$o['inti'].'/'.$se.'/excel','<i class="fa fa-file-excel-o"></i> Ekspor Excel','target="_blank"').'</li>
			</ul>
			</div>' : null;
			
			$tombol = array(); $t_no = 1;
			foreach($o['tombol'] as $t) {
				
				switch($t[0]) {
					case "0" : $c_btn = null; $icn = null; break;
					case "1" : $c_btn = "btn-success"; $icn = '<i class="fa fa-plus"></i>';break;
				}
				$tombol[] = anchor($t_no,$icn.' &nbsp; '.$t[1],'class="btn-edit btn btn-success" act="'.site_url($o['module'].$o['inti'].'/'.in_de(array('modex' => 'form_mode'))).'"');
			}
			
			
			// -- Tombol Impor -- //
			$btn_impor = null;
			if (!empty($o['excel_impor'])) {
				$s = un_de($se); $s['modex'] = 'excel_impor';
				$btn_impor = anchor(
					$o['module'].$o['inti'].'/'.in_de($s),
					'<i class="fa fa-file-excel-o fa-btn"></i> Impor Data','class="btn btn-primary" style="margin-left: 5px"');
			}
			
			
			$data['total'] = $config['total_rows'];
			$data['tombol'] = implode(' ',$tombol).' '.$btn_cetak.' '.$btn_impor;
			
			$data['extra_tombol'] = 
			form_open($o['module'].$o['inti'],'id="form_search"').
			'<div class="input-group">
                      <input name="search" type="text" placeholder="Pencarian ..." class="form-control pull-right" value="'.@$o['search'].'">
                      <div class="input-group-btn">
                        <button class="btn btn-default"><i class="fa fa-search"></i></button>
                      </div>
                    </div>'.form_close();
                    
			$data['tabel']	= $tabel;
		
			if ($offset == "cetak") {
				$data['title'] = '<h3>'.$o['title'].'</h3>';
				$data['content'] = $tabel;
				$this->load->view('umum/print',$data);
			} else if ($offset == "excel") {
				$data['file_name'] = str_replace(" ","_",strtolower($o['title'])).'.xls';
				$data['title'] = '<h3>'.$o['title'].'</h3>';
				$data['content'] = $tabel;
				$this->load->view('umum/excel',$data);
			} else {
				if (!empty($o['urut'])) $this->urutan($o['tabel'],$o['urut'],$o['id']);
				$data['tabel'] = $tabel;
				$data['title'] = $o['title'];
				$data['content'] = "umum/standard_view";
				$this->load->view('home', $data);
			}
		}
		
		function form_data($o, $sc, $id = null) {
			
			$t = empty($id) ? 'Tambah ' : 'Ubah ';
			$data['title'] = $t.$o['title'];
			
			$data['form_link'] = $o['module'].$o['inti'].'/'.in_de(array('modex' => 'save_mode'));
			
			$def = !empty($id) ? $this->general_model->datagrab(array('tabel'=>$o['tabel'],'where'=>array($o['id']=>$id),'select' => @$o['select']))->row(): null;
			
			$se = explode('~',$sc);
			$s = un_de($se[0]); $s['modex'] = 'list_data';
			
			$data['form_data'] = 
				form_hidden('sc',in_de($s)).
				form_hidden('offs',@$se[1]).
				form_hidden($o['id'],@$def->{$o['id']});
			
			$to_datepicker = array();
			
			foreach($o['form'] as $fo) {
				$extra_form = null;
				$req = ($fo[1] == TRUE) ? 'required' : null;
				switch($fo[0]){
					case 0 :
					
					$fr = form_hidden($fo[3],!empty($def->{$fo[3]})?$def->{$fo[3]}:$fo[4]);
					break;
					
					case 1 : 
					if (!empty($fo[5])){
						switch ($fo[5]) {
							case "formatnumber":
							$form_data = !empty($def->{$fo[3]})?numberToCurrency($def->{$fo[3]}):null;
							$extra_form = ' onkeyup="formatNumber(this)"';
							break;
							case "datepicker":
							$to_datepicker[] = $fo[3];
							$form_data = !empty($def->{$fo[3]})?tanggal($def->{$fo[3]}):null;
							break;
						}
						
					} else {
						$form_data = @$def->{$fo[3]};
					}
					
					if (!empty($fo[6])) $extra_form = $fo[6];
					$fr = '<p>'.form_label($fo[2]).form_input($fo[3],$form_data,'class="'.$fo[4].' form-control" '.$req.$extra_form).'</p>'; break;
					case 2 : $fr = '<p>'.form_label($fo[2]).form_dropdown($fo[3],$fo[4],@$def->{$fo[3]},'class="'.$fo[5].' form-control" style="width: 100%" '.$req.' '.@$fo[6]).'</p>'; break;
					case 3 : $fr = '<p>'.form_label($fo[2]).form_textarea($fo[3],@$def->{$fo[3]},'class="'.$fo[4].' form-control" style="height: 75px" '.$req).'</p>'; break;
				}
				
				$data['form_data'].= $fr;
			}
			$data['script'] = null;
			if (!empty($o['form_script'])) $data['script'] = $o['form_script'];
			
			
			foreach($to_datepicker as $dt) {
				$data['script'].="
					$('.".$dt."').datepicker({
						format  : 'dd/mm/yyyy', todayBtn: 'linked', language: 'id'
					  }).on('changeDate', function(){ $('.".$dt."').datepicker('hide');
					});
				";
			}
			
			$data['def'] = $def;
			if (!empty($param['form_khusus'])) $this->load->view($param['form_khusus'],$data);
			else $this->load->view('umum/form_view',$data);
		}
		
		function save_data($o = null) {
			
			$id = $this->input->post($o['id']);
			
			$simpan = array();
			
			foreach($o['form'] as $kol) {
				if(!empty($kol[3])) {
					
				if ((!empty($o['kolom_data']) and in_array($kol[3],$o['kolom_data'])) or (empty($o['kolom_data']))) {
				$f_simpan = $this->input->post($kol[3]);
				
				if ($kol[0] == 1 and !empty($kol[5])) {
					switch($kol[5]) {
						case "datepicker": $simpan[$kol[3]] = tanggal_php($f_simpan); break;
						case "formatnumber": $simpan[$kol[3]] = currencyToNumber($f_simpan); break;
					}
				} else {	
					$simpan[$kol[3]] = $f_simpan;
				}
				}
				} 
				
			}
			
			$tbl = is_array($o['tabel'])?$o['tabel_save']:$o['tabel'];
			
			// -- Cek Duplikat --//
			
			if (isset($o['duplikat'])) {
				$cek = array();
				foreach($o['duplikat'] as $dup) {
					
					$cek[$dup] = $simpan[$dup];
					
				}
				
				if (!empty($id)) $cek[$o['id'].' <> '.$id] = null; 
				
				$cek_dup = $this->general_model->datagrab(array('tabel' => $tbl,'where' => $cek,'select' => 'count('.$o['id'].') as jml'))->row();
				if ($cek_dup->jml > 0) $this->session->set_flashdata('fail','Data duplikat ... !');
				else {
					$this->general_model->save_data($tbl,$simpan,$o['id'],$id);
					audit_log('Referensi', !empty($id) ? 'UBAH' : 'TAMBAH', $o['title'].(!empty($id) ? ' #'.$id : ''), $simpan);
					$this->session->set_flashdata('ok','Data berhasil disimpan');
				}
				
			} else {
			
				$this->general_model->save_data($tbl,$simpan,$o['id'],$id);
				audit_log('Referensi', !empty($id) ? 'UBAH' : 'TAMBAH', $o['title'].(!empty($id) ? ' #'.$id : ''), $simpan);
				
			}
			if (isset($o['urut'])) $this->urutan($o['tabel'],$o['urut'],$o['id']);
		
			redirect($o['module'].$o['inti'].'/'.$this->input->post('sc').'/'.$this->input->post('offs'));
		}
	
		function urutan($tbl,$urut,$id) {
			
			$tb = $this->general_model->datagrab(array(
				'tabel' => $tbl,
				'order' => $urut.','.$id
			)); $no = 1;
			foreach($tb->result() as $t) {
				$this->general_model->save_data($tbl,array($urut => $no),$id,$t->$id);
				$no+=1;
			}
			
		}

		function impor_data($o,$search = null,$offset = null) {
			
			$data['breadcrumb'] = array('' => 'Referensi',$o['module'].$o['inti'] => $o['title']);
			if (!empty($o['breadcrumb'])) $data['breadcrumb'] = array_merge($data['breadcrumb'],$o['breadcrumb']);
			
			$s = un_de($search); $s['modex'] = 'list_data';
			
			$data['tombol'] = anchor($o['module'].$o['inti'].'/'.in_de($s).'/'.$offset,
				'<i class="fa fa-arrow-left fa-btn"></i> Kembali',
				'class="btn btn-default"');
			
			$s = un_de($search); $s['modex'] = 'save_impor';
			
			$this->table->set_template(array('table_open'=>'<table class="tabel_print" border=1>'));
			$head = array('x');
			for($i = 0;$i < count($o['excel_impor']);$i++) { $head[] = is_array($o['excel_impor'][$i])?$o['excel_impor'][$i]['kolom']:$o['excel_impor'][$i]; }
			$this->table->set_heading($head);
			$rows = array(array('data' => 1,'class' => 'add_th'));
			for($i = 0;$i < count($o['excel_impor']);$i++) { $rows[] = array('data' => 'xxxxx','class' => 'reg'); }
			$this->table->add_row($rows);
			$rows = array(array('data' => 2,'class' => 'add_th'));
			for($i = 0;$i < count($o['excel_impor']);$i++) { $rows[] = array('data' => 'xxxxx','class' => 'reg'); }
			$this->table->add_row($rows);
			$data['tabel'] = 
			
			'
			<style>
				th, .add_th { padding: 5px 10px; background: #eee; font-size: 120%; font-weight: bold; }
				.reg { padding: 5px 10px; font-size: 120%; }
			</style>
			<div class="row">
				<div class="col-lg-7">
				<p>Format File Excel untuk impor data ini adalah Microsoft Excel 2003-2007 (.xls).<br>
				Buat format data seperti di bawah ini dengan <b>mengabaikan sel berwarna abu-abu</b>.<br>
				Pastikan urutan sudah benar akan tidak terjadi kesalahan input</p><br>
				'.
				$this->table->generate().'<br><p>
				Data yang sama untuk tiap baris akan di seleksi dan tidak akan di masukkan.</p>'
				.'</div><div class="col-lg-5">'.
				form_open_multipart($o['module'].$o['inti'].'/'.in_de($s).'/'.$offset,'id="form_impor"').
				'<p>'.form_label('Pilih File Excel').form_upload('excel_impor','class="form-control"').'</p>'.
				'<p>'.form_label('').form_submit('','Unggah Data Impor','class="btn btn-success"').'</p>'.
				form_close().
				'</div>
			</div>';
		
			$data['title'] = 'Impor Data '.$o['title'];
			$data['content'] = "umum/standard_view";
			$this->load->view('home', $data);
			
		}
		
		function save_impor($o,$search = null,$offset = null) {
		
		$ign = 0;
		$pss = 0;
		
		if (empty($_FILES['excel_impor']['name'])) {	
			$errors = 'Pilih dokumen Excel yang akan di-unggah ...';
		} else {
			$this->load->library('upload');
			$this->upload->initialize(array(
				'upload_path' => './uploads/',
				'allowed_types' => '*'));
			if (!$this->upload->do_upload('excel_impor')) {
				$en = $this->upload->display_errors();
				switch ($en) { 
					case 'The uploaded file exceeds the maximum allowed size in your PHP configuration file.':
					$errors = 'Unggah berkas lebih dari batas maksimum diperbolehkan ...';
					break;
					default: $error = $en;
				}
			} else {
				$data_up = $this->upload->data();
				$this->load->library('excel_reader');
				$this->excel_reader->setOutputEncoding('CP1251');
				$rslt_read = $this->excel_reader->read('./uploads/'.$data_up['file_name']);
				if ($rslt_read != 404) {
				$brs = $this->input->post('baris');
				$data = $this->excel_reader->sheets[0];
				
				// -- Kumpulkan Kolom Rujukan -- //
					
				for ($n = 1; $n <= $data['numRows']; $n++) {
					
						for($i = 0;$i < count($o['excel_impor']);$i++) {
					
						if (is_array($o['excel_impor'][$i])) {

								$cek = $this->general_model->datagrab(array(
									'tabel' => $o['excel_impor'][$i]['tabel'],
									'where' => array($o['excel_impor'][$i]['kolom'] => $data['cells'][$n][$i+1]),
									'select' => 'count(*) as jml,'.$o['excel_impor'][$i]['id_kolom']))->row();
								if ($cek->jml > 0) $id_s = $cek->{$o['excel_impor'][$i]['id_kolom']};
								else $id_s = $this->general_model->save_data($o['excel_impor'][$i]['tabel'],array($o['excel_impor'][$i]['kolom'] => $data['cells'][$n][$i+1]));
									
									
								}
						}
					
					$saving = array();
					for($e = 0; $e <= count($o['excel_impor'])-1; $e++) {
						
						
						if (is_array($o['excel_impor'][$e])) {
							$saving[$o['excel_impor'][$e]['id_kolom']] = $id_s;
						} else {
							$saving[$o['excel_impor'][$e]] = (isset($data['cells'][$n][$e+1])) ? $data['cells'][$n][$e+1] : null;
						}
						
					}
					
					$t_save = (isset($o['tabel_save'])?$o['tabel_save']:$o['tabel']);
					$c_save = $this->general_model->datagrab(array('tabel' =>$t_save,'where' => $saving,'select' => 'count(*) jml'))->row();
					if ($c_save->jml != NULL and $c_save->jml > 0) {
						$ign += 1;
					} else {
						
						$pss += 1;
						$this->general_model->save_data($t_save,$saving);
					}
				
				}
				
				
				
				} else {
						$errors = 'File excel tersebut tak dapat terbaca ...<br>harus File dengan ektensi <b>xls</b> atau format Ms.Excel 2003/2007';
				}
			}
		
			unlink(FCPATH.'uploads/'.$data_up['file_name']);
		}
		
		if (!empty($errors)) $this->session->set_flashdata('fail','Terjadi kesalahan<br>'.$errors);
		else $this->session->set_flashdata('ok','Impor <b>'.$pss.' data</b> berhasil disimpan,<br><b>'.$ign.' data duplikat</b> tidak ikut dimasukkan...');
	
		$s = un_de($search); $s['modex'] = 'excel_impor';
		redirect($o['module'].$o['inti'].'/'.in_de($s).'/'.$offset);
			
		}
		
		function hapus_data($o = null,$offset = null) {
			
			$cek = $this->input->post('cek');

			$tbl = !empty($o['tabel_save']) ? $o['tabel_save'] : $o['tabel'];
			
			if (is_array($cek) && count($cek) > 0) {
				foreach($cek as $c) {
					$this->general_model->delete_data($tbl,$o['id'],$c);
				}
				audit_log('Referensi', 'HAPUS', $o['title'].' ('.count($cek).' data)', 'Tabel: '.$tbl.' | ID terhapus: '.implode(', ', $cek));
			}
			$this->session->set_flashdata('ok', 'Data '.$o['title'].' berhasil dihapus ...');
			
			redirect($o['module'].$o['inti'].'/'.in_de(array('search' => $o['search'],'modex' => 'list_data')).'/'.$offset);
	
			
		}
}
?>