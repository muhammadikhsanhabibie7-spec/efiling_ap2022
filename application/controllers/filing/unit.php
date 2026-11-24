<?php if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

#[AllowDynamicProperties]
class unit extends CI_Controller {

	var $app = null;
	var $dir = 'filing';

	function __construct() {
	
		parent::__construct();
		login_check($this->session->userdata('login_state'));
		
		$this->in_app = $this->general_model->datagrab(array(
			'tabel' => 'ref_aplikasi','where' => array('folder' => $this->uri->segment(1))
		))->row()->nama_aplikasi;
	
	}
	
	public function index() {
			
		$this->show_unit();
			
	}
	
	function tabel_sub($p,$level_unit,$id) {

		$from = array('ref_unit b' => '','peg_jabatan j' => array('j.id_peg_jabatan = b.id_kepala','left'),'peg_pegawai p' => array('p.id_pegawai = j.id_pegawai','left'));
		
		$sub_bid = $this->general_model->datagrab(array('tabel' => $from, 'where' => array('id_par_unit' => $id),'select' => 'b.*,p.nama'));
		
		$no_urut = 1;
		foreach ($sub_bid->result() as $row) {
			$this->general_model->save_data('ref_unit',array('urut' => $no_urut),'id_unit',$row->id_unit);
			$no_urut+=1;
		}
		
		if ($sub_bid->num_rows() > 0) {
			foreach($sub_bid->result() as $row) {
			
				$cell = array();
				if (!empty($p['ubah'])) {
						$cell[] =  array('data' => '<input type="checkbox" name="cek[]" class="cek" value="'.$row->id_unit.'">','style' => 'width: 35px; text-align: center');
				}
				$indent_cols = min((int) $level_unit, 6);
				for ($j = 0; $j < $indent_cols; $j++) {
					$cell[] = array('data' => '&nbsp;', 'style' => 'width: 24px');
				}
				
				$link1 = '<a href="#" class="btn btn-xs btn-warning btn-edit" title="Ubah Unit" act="'.site_url($this->dir.'/unit/add_unit/'.in_de(array('level_unit' => $row->level_unit,'id' => $row->id_unit))).'"><i class="fa fa-pencil"></i></a>';
				$link2 = null;
				
				$sub_bidd = $this->general_model->datagrab(array('tabel' => 'ref_unit', 'where' => array('id_par_unit' => $row->id_unit)));
				if ($sub_bidd->num_rows() == 0)  {
					$link2 = '<a href="#" class="btn btn-xs btn-danger btn-delete" title="Hapus Unit" act="'.site_url($this->dir.'/unit/removing/'.in_de(array('id' => $row->id_unit))).'" msg="Apakah unit <b>'.$row->kode_unit.' '.$row->unit.'</b> akan dihapus?"><i class="fa fa-trash"></i></a>';
				}
			
				$kode = !empty($row->kode_unit)?' <b>('.$row->kode_unit.')</b>':null;
				$nama = !empty($row->unit)?' '.$row->unit:null;
				$btn_tambah_sub = null;
				if (!empty($p['ubah'])) {
					$btn_tambah_sub = '<div class="btn-group">
						<a class="btn btn-xs btn-warning dropdown-toggle" data-toggle="dropdown" href="#" title="Opsi Sub Unit / Pindah">
						<i class="fa fa-cog"></i>
						</a>
						<ul class="dropdown-menu">
						<li><a href="#" act="'.site_url($this->dir.'/unit/add_sub_unit/'.$row->level_unit.'/'.$row->id_unit).'" class="btn-edit"><i class="fa fa-plus-square fa-btn"></i> Sub Unit Kerja (Kantor Pusat / Kantor Cabang)</a></li>
						<li><a href="#" act="'.site_url($this->dir.'/unit/pindah_unit/'.in_de(array('id' => $row->id_unit))).'" class="btn-edit"><i class="fa fa-refresh fa-btn"></i> Pindahkan ke Bidang</a></li></ul>
						</div>';
				}
				$target = !empty($p['target']) ? 'target="'.$p['target'].'"' : null;
				$sel = !empty($p['link']) ? anchor($p['link'].in_de(array('link' => $p['link'],'id' => $row->id_unit)),$nama.$kode,'class="link-normal btn-go" id="'.$row->id_unit.'" title="'.$nama.'" '.$target) : $nama.$kode;
				$cls_text = ($row->aktif == 2) ? '<span class="text-muted"><i>'.$sel.'</i></span>' : $sel;
				$cell[] = array('data' => '<i class="fa fa-level-up fa-rotate-90 text-muted"></i> '.$cls_text.(!empty($btn_tambah_sub) ? ' &nbsp; '.$btn_tambah_sub : ''), 'colspan' => 7 - $indent_cols);

				if (!empty($p['ubah'])) {
					$cell[] = array('style' => 'width: 42px; text-align: center;','data' => $link1);
					$cell[] = array('style' => 'width: 42px; text-align: center;','data' => !empty($link2) ? $link2 : '&nbsp;');
				}
			
				$this->table->add_row($cell);
				if ($sub_bidd->num_rows() > 0) $this->tabel_sub($p,$level_unit+1,$row->id_unit);
				
			}
		}
		
	}
	
	function tabel_unit($o = null) {
		$s = $this->general_model->get_param('multi_unit');
		$p = !empty($o) ? un_de($o) : array('ubah' => null,'link' => null);
		
		$root_bid = $this->general_model->datagrab(array('tabel' => 'ref_unit','where' => array("id_par_unit = '' or id_par_unit IS NULL" => null),'order' => 'unit'));
		
		$no_urut = 1;
		foreach ($root_bid->result() as $row) {
			$this->general_model->save_data('ref_unit',array('urut' => $no_urut),'id_unit',$row->id_unit);
			$no_urut+=1;
		}
		
		if ($root_bid->num_rows() > 0) {
            $this->table->set_template(array('table_open' => '<table class="table table-striped table-bordered table-hover">'));
            $this->table->set_empty("&nbsp;");	
            
			$head = array();
			if (!empty($p['ubah'])) $head[] = array('data' => '<input type="checkbox" name="cek_all" id="cek_all" class="cek-all">','class'=>'text-center','style'=>'width:35px');
            $head[] = array('data' => 'Nama / Kode Unit','colspan' => '7');
			
			if (!empty($p['ubah'])) $head[] = array('data' => 'Aksi','colspan' => 2,'class' => 'text-center','style' => 'width:84px');
			
			$this->table->set_heading($head);
			
				foreach ($root_bid->result() as $row) {
					
					$link1 = '<a href="#" class="btn btn-xs btn-warning btn-edit" title="Ubah Unit" act="'.site_url($this->dir.'/unit/add_unit/'.in_de(array('level_unit' => $row->level_unit,'id' => $row->id_unit))).'"><i class="fa fa-pencil"></i></a>';
					$link2 = null;
					
					$sub_bid = $this->general_model->datagrab(array('tabel' => 'ref_unit', 'where' => array('id_par_unit' => $row->id_unit)));
					if ($sub_bid->num_rows() == 0)  {
						$link2 = '<a href="#" class="btn btn-xs btn-danger btn-delete" title="Hapus Unit" act="'.site_url($this->dir.'/unit/removing/'.in_de(array('id' => $row->id_unit))).'" msg="Apakah unit <b>'.$row->kode_unit.' '.$row->unit.'</b> akan dihapus?"><i class="fa fa-trash"></i></a>';
					}

					$kode = !empty($row->kode_unit)?' <b>('.$row->kode_unit.')</b>':null;
					$unit = !empty($row->unit)?' '.$row->unit:null;
					$btn_tambah_sub = null;
					if (!empty($p['ubah'])) {
						
					$btn_tambah_sub = '<div class="btn-group">
						<a class="btn btn-xs btn-warning dropdown-toggle" data-toggle="dropdown" href="#" title="Opsi Sub Unit / Pindah">
						<i class="fa fa-cog"></i>
						</a>
						<ul class="dropdown-menu">
						<li><a href="#" act="'.site_url($this->dir.'/unit/add_sub_unit/'.$row->level_unit.'/'.$row->id_unit).'" class="btn-edit"><i class="fa fa-plus-square fa-btn"></i> Sub Unit Kerja (Kantor Pusat / Kantor Cabang)</a></li>
						<li><a href="#" act="'.site_url($this->dir.'/unit/pindah_unit/'.in_de(array('id' => $row->id_unit))).'" class="btn-edit"><i class="fa fa-refresh fa-btn"></i> Pindahkan ke Bidang</a></li></ul>
						</div>';

					}
					$target = !empty($p['target']) ? 'target="'.$p['target'].'"' : null;
					
					$sel = !empty($p['link']) ? anchor($p['link'].in_de(array('link' => $p['link'],'id' => $row->id_unit)),$unit.$kode,'class="link-normal btn-go" id="'.$row->id_unit.'" title="'.$unit.'" '.$target) : $unit.$kode;
				
					$rowsd = array();
					if (!empty($p['ubah'])) {
						$rowsd[] =  array('data' => '<input type="checkbox" name="cek[]" class="cek" value="'.$row->id_unit.'">','style' => 'width: 35px; text-align: center');
					}
					$cls_text = ($row->aktif == 2) ? '<span class="text-muted"><i>'.$sel.'</i></span>' : $sel;
					$rowsd[] = array('data'=> '<strong>'.$cls_text.'</strong>'.(!empty($btn_tambah_sub) ? ' &nbsp; '.$btn_tambah_sub : ''),'colspan'=> 7);
					if (!empty($p['ubah'])) {
						$rowsd[] = array('style' => 'width: 42px; text-align: center;','data' => $link1);
						$rowsd[] = array('style' => 'width: 42px; text-align: center;','data' => !empty($link2) ? $link2 : '&nbsp;');
					}
					$this->table->add_row($rowsd);
					if ($sub_bid->num_rows() > 0) $this->tabel_sub($p,1,$row->id_unit);
				}
				return $this->table->generate();
			
		} else {
		
		return  '<div class="alert">Belum ada Unit Kerja terdaftar</div>';
		
		}
		
		
	}
	
	function show_unit() {
		
		$s = $this->general_model->get_param('multi_unit');

		$data['breadcrumb'] = array('' => $this->in_app, $this->dir.'/unit' => 'Unit Kerja/SKPD');
		
		$data['title']		= 'Referensi Unit Kerja (Kantor Pusat / Kantor Cabang)';
		$data['content']	= 'umum/standard_view';
		
		$data['tabel'] = 
			form_open($this->dir.'/unit/delete_unit','id="form_delete"').
			anchor('#','<i class="fa fa-trash"></i> &nbsp; Hapus Yang Tercentang','class="btn btn-danger btn-delete-all btn-sm" style="display: none; margin-bottom: 10px"').
			$this->tabel_unit(in_de(array('ubah' => 1))).
			anchor('#','<i class="fa fa-trash"></i> &nbsp; Hapus Yang Tercentang','class="btn btn-danger btn-delete-all btn-sm" style="display: none; margin-top: -10px"').
			form_close();

			
		$data['tombol'] = 
		// ($s == 1) ? 
		anchor('#','<i class="fa fa-plus"></i> &nbsp; Unit Kerja','class="btn btn-edit btn-success" act="'.site_url($this->dir.'/unit/add_unit/'.in_de(array('level_unit' => '1'))).'"')
		 // : null
		 ;
		$data['content'] = "umum/standard_view";
		$this->load->view('home', $data);
		
	}
	
	function add_unit($param) {
		
		$from = array(
			'peg_pegawai p' => '',
			'peg_jabatan j' => 'j.id_pegawai = p.id_pegawai'
		);
		$combo_kepala = $this->general_model->combo_box(array(
			'tabel' => $from,
			'key' => 'id_peg_jabatan',
			'val' => array('nama_pegawai'),
			'select' => 'j.id_peg_jabatan,CONCAT(p.nama," - NIP. ",IFNULL(p.nip,"-")) as nama_pegawai',
			'value' => array('nama_pegawai')
		));
		
		$o = un_de($param);
		$data['title'] = (!empty($o['id'])) ? "Ubah Unit" : "Tambah Unit";
		$data['form_link'] = $this->dir.'/unit/saving';
		$def = (!empty($o['id'])) ? $this->general_model->datagrab(array('tabel' => 'ref_unit','where' => array('id_unit' => $o['id'])))->row():null;

		$whr_unit = !empty($def)?array('id_unit <>' => $def->id_unit):null;
		$cb_unit = $this->general_model->combo_box(array(
			'tabel' => 'ref_unit',
			'key'=>'id_unit',
			'where' => $whr_unit,
			'val' => array('unit'),
			'pilih' => ' -- Tidak ada Unit di atasnya -- '));
		
		$data['form_data'] = 
			form_hidden('id_unit',@$def->id_unit).
			form_hidden('level_unit',$o['level_unit']).
			'<p>'.form_label('Unit di Atasnya').form_dropdown('id_par_unit',$cb_unit,@$def->id_par_unit,'class="form-control" style="width: 100%"').'</p>'.
			'<p>'.form_label('Kode Unit').form_input('kode_unit',@$def->kode_unit,'class="form-control"').'</p>'.
			'<p>'.form_label('Nama Unit').form_textarea('unit',@$def->unit,'class="form-control" style="height: 60px"').'</p>'.
			'<p>'.form_label('Alamat').form_textarea('alamat',@$def->alamat,'class="form-control" style="height: 60px"').'</p>'.
			'<p>'.form_label('Telp').form_input('telp',@$def->telp,'class="form-control"').'</p>'.
			'<p>'.form_label('Kepala').form_dropdown('id_kepala',$combo_kepala,@$def->id_kepala,'class="combo-box form-control" style="width: 100%"').'</p>'.
			'<p>'.form_label('Aktif').form_dropdown('aktif',array('1' => 'Aktif','2'=> 'Non Aktif'),@$def->aktif,'class="combo-box form-control" style="width: 100%"').'</p>';
		$this->load->view('umum/form_view', $data);
	
	}
	
	function pindah_unit($p) {
		
		$o = un_de($p);
		
		$data['title'] = "Pindah Unker ke Unor";
		$data['form_link'] = $this->dir.'/unit/pindah/unker';
		
		$def = $this->general_model->datagrab(array('tabel' => 'ref_unit','where' => array('id_unit' => $o['id'])))->row();	
		$combo_unit = $this->general_model->combo_box(array('tabel' => 'ref_unit','key' => 'id_unit','val' => array('unit')));
		
		$data['form_data'] = 
			form_hidden('id_unit',$def->id_unit).
			'<p>'.form_label('Nama pindah Unit Organisasi').form_input('nama',$def->unit,'class="form-control"').'</p>'.
			'<p>'.form_label('Pindahkan ke Unit Kerja').form_dropdown('unit',$combo_unit,null,'class="form-control" style="width: 100%" required').'</p>';
		
		$this->load->view('umum/form_view', $data);
	
	}
	
	function pindah($a) {
		
		if ($a == "unker") {
			
			$id = $this->input->post('id_unit');
			$un = $this->input->post('unit');
			
			$simpan = array(
				'id_unit' => $un,
				'nama_bidang' => $this->input->post('nama')
			); $this->general_model->save_data('ref_bidang',$simpan);
			
			$this->general_model->delete_data('ref_unit','id_unit',$id);
			audit_log('Referensi', 'PINDAH', 'Unit Kerja -> Bidang: '.$this->input->post('nama'), 'ID Unit lama: '.$id.' -> ID Unit tujuan: '.$un);
			$this->session->set_flashdata('balik',1);
			$this->session->set_flashdata('ok','Unit Kerja berhasil diubah ke Unit Organisasi');
			
			redirect($this->dir.'/bidang/tabel_bidang/'.in_de(array('id' => $un)));
			
		}
	}
	
	function add_sub_unit($level_unit,$id_par){
	
		$data['title'] = "Tambah Unit Bawahan";
		$data['form_link'] = $this->dir.'/unit/saving_sub';

		$par = $this->general_model->datagrab(array('tabel' => 'ref_unit','where' => array('id_unit' => $id_par)))->row();

		$from = array(
			'peg_pegawai p' => '',
			'peg_jabatan j' => 'j.id_pegawai = p.id_pegawai'
		);
		
		$combo_kepala = $this->general_model->combo_box(array(
			'tabel' => $from,
			'key' => 'id_peg_jabatan',
			'select' => 'j.id_peg_jabatan,CONCAT(p.nama," - NIP. ",IFNULL(p.nip,"-")) as nama_pegawai',
			'val' => array('nama_pegawai'),
			'value' => array('nama_pegawai')
		));
		$kode_unit = !empty($par->kode_unit)?' ('.$par->kode_unit.')':null;
		$data['form_data'] = 
			form_hidden('id_par_unit',$id_par).
			form_hidden('level_unit',($level_unit+1)).
			'<p style="margin: 0 0 10px 0; padding: 0 0 5px 0; border-bottom: 1px dotted #ccc">'.
				form_label('Unit di Atasnya').form_textarea('ur',$par->unit.$kode_unit,'readonly class="form-control" style="height: 60px"').
			'</p>'.
			'<p>'.form_label('Kode Unit').form_input('kode_unit',null,'class="form-control"').'</p>'.
			'<p>'.form_label('Nama Unit').form_textarea('unit',null,'class="form-control" style="height: 60px"').'</p>'.
			'<p>'.form_label('Alamat').form_textarea('alamat',null,'class="form-control" style="height: 60px"').'</p>'.
			'<p>'.form_label('Telp').form_input('telp',null,'class="form-control"').'</p>'.
			'<p>'.form_label('Kepala Unit').form_dropdown('id_kepala',$combo_kepala,null,'class="combo-box form-control" style="width: 100%"').'</p>';
			
		$this->load->view('umum/form_view', $data);
		
	}
	
	function saving() {
		
		$id = $this->input->post('id_unit');
		$par = $this->input->post('id_par_unit');
		
		$simpan = array(
			'kode_unit' => $this->input->post('kode_unit'),
			'unit' => $this->input->post('unit'),
			'alamat' => $this->input->post('alamat'),
			'telp' => $this->input->post('telp'),
			'level_unit' => $this->input->post('level_unit'),
			'id_kepala' => $this->input->post('id_kepala'),
			'aktif' => $this->input->post('aktif')
		);
		
		$simpan['id_par_unit'] = !empty($par) ? $par : null;
		
		$this->general_model->save_data('ref_unit',$simpan,'id_unit',$id);
		audit_log('Referensi', !empty($id) ? 'UBAH' : 'TAMBAH', 'Unit Kerja: '.$simpan['unit'], 'Kode: '.$simpan['kode_unit']);
		$this->session->set_flashdata('ok', 'Data berhasil disimpan');
		redirect($this->dir.'/unit');
		
	}
	
	function saving_sub(){
		
		$simpan = array(
			'id_par_unit' => $this->input->post('id_par_unit'),
			'kode_unit' => $this->input->post('kode_unit'),
			'unit' => $this->input->post('unit'),
			'alamat' => $this->input->post('alamat'),
			'telp' => $this->input->post('telp'),
			'level_unit' => $this->input->post('level_unit'),
			'id_kepala' => $this->input->post('id_kepala'),
			'aktif' => 1
		);
		$this->general_model->save_data('ref_unit',$simpan);
		audit_log('Referensi', 'TAMBAH', 'Sub Unit Kerja: '.$simpan['unit'], 'Kode: '.$simpan['kode_unit'].' | Induk ID: '.$simpan['id_par_unit']);
		$this->session->set_flashdata('ok', 'Data berhasil disimpan');
		redirect($this->dir.'/unit');

	}
	
	function removing($par){
	
		$o = un_de($par);
		$old = $this->db->get_where('ref_unit', array('id_unit' => $o['id']))->row();
		$this->general_model->delete_data('ref_unit','id_unit',$o['id']);
		audit_log('Referensi', 'HAPUS', 'Unit Kerja: '.(!empty($old) ? $old->unit : '#'.$o['id']), 'ID Unit: '.$o['id']);
		$this->session->set_flashdata('ok', 'unit berhasil dihapus');
		redirect($this->dir.'/unit/show_unit');
		
	}
	
	function delete_unit(){
	
		$cek = $this->input->post('cek');	
	
		if (is_array($cek) && count($cek) > 0) {
			foreach($cek as $c) {
				$this->general_model->delete_data('ref_unit','id_unit',$c);
			}
			audit_log('Referensi', 'HAPUS', 'Unit Kerja ('.count($cek).' data)', 'ID Unit terhapus: '.implode(', ', $cek));
		}
		$this->session->set_flashdata('ok', 'Unit Kerja berhasil dihapus');
		redirect($this->dir.'/unit');
		
	}
	
}