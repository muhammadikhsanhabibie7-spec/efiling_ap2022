<?php
class Lokasi_aset extends Ci_Controller{
	function __construct(){
			parent::__construct();
	}
		
	function index(){
			$this->list_data();
	}
		
	function list_data($search = null,$offset=null){
		$data['breadcrumb'] = array('' => 'Referensi', 'referensi/lokasi_aset/list_data' => 'Referensi Lokasi_aset');
		
		$fcari = null;
		$search_key = $this->input->post('key');
		if (!empty($search_key)) {
			$fcari = array(
				'a.nama_unker' => $search_key,
				'a.nama_ruang' => $search_key,
				'b.unit' => $search_key,
			);	
			$data['for_search'] = $fcari['a.nama_unker'];
		} else if ($search) {
			$fcari=un_de($search);
			$data['for_search'] = $fcari['a.nama_unker'];
		}
		
		$param = array(
			'tabel'		=> array('ref_lokasi_aset a'=>'','ref_unit b'=>'a.id_unit=b.id_unit'),
			'search'	=> $fcari
		);
		
		$config['base_url']		= site_url('referensi/lokasi_aset/list_data/'.in_de($fcari));
		$config['total_rows']	= $this->general_model->datagrab($param)->num_rows();
		$config['uri_segment']	= '5';
		$config['per_page']		= '10';
		
		$this->pagination->initialize($config);
		
		$this->table->set_template(array('table_open'=>'<table class="table table-striped table-bordered table-condensed table-nonfluid" id="tbl_data">'));
			$this->table->set_heading(
				array('data'=>'No','class'=>'center','style'=>'width:20px'), 
				array('data'=>'Kode Lokasi_aset'),			
				array('data'=>'SKPD/Unit Kerja'),
				array('data'=>'Sub Unit Kerja'),
				array('data'=>'UPB/Ruang'),
				array('data'=>'Aksi','class'=>'center','style'=>'width:50px')
			);
			
			$param = array(
				'tabel'=>array('ref_lokasi_aset a'=>'','ref_unit b'=>'a.id_unit=b.id_unit'),
				'limit'		=> $config['per_page'],
				'offset'	=> $offset,
				'order'		=> 'a.kode_skpd asc, a.kode_unker asc, a.kode_ruang asc',
				'search'	=> $fcari
			);

			$dt = $this->general_model->datagrab($param);
			//echo $this->db->last_query();
			
			$no=!empty($offset)?$offset:0;
		
		if($dt->num_rows() <> 0){
			foreach($dt->result() as $row){
				$no++;	
				$this->table->add_row(
						array('data'=>$no,'class'=>'center'),
						$row->kode_skpd.'.'.$row->kode_unker.'.'.$row->kode_ruang,
						$row->unit,
						$row->nama_unker,
						$row->nama_ruang,
						
						array(
						'data'	=> anchor('#','<i class="fa fa-pencil"></i>','class="btn-edit" act="'.site_url('referensi/lokasi_aset/add_data/'.$row->id_lokasi).'"').'  '.
								   anchor('#','<i class="fa fa-trash"></i>','act="'.site_url('referensi/lokasi_aset/delete_data/'.$row->id_lokasi).'" class="btn-delete" msg="Apakah ingin menghapus data lokasi  kode :  <strong>'.$row->kode_skpd.'.'.$row->kode_unker.'.'.$row->kode_ruang.'</strong>"'),
						'class'=>'center no-wrap')
				);
						
			}
		}
		
		
		$data['script'] = "$('#box_delete').hide();
		
		$('.cek').click(function(){
		var ju = 0;
		$('.cek').each(function(){
			if($(this).is(':checked')) ju+=1;
		});
			
		if (ju > 0) {
			$('#box_delete').show();             
		}else{
			$('#box_delete').hide();
		}
		});	";
		
		$data['tombol'] = '<div class="pull-right">
			<form method="post" action="'.site_url('referensi/lokasi_aset/list_data').'">
			<a href="#" title="Tambah Pegawai" class="btn-edit btn btn-success" act="'.site_url('referensi/lokasi_aset/add_data').'"><i class="icon-plus-sign icon-white"></i> Tambah Lokasi</a>
			<input type="text" name="key" value="'.@$data['for_search'] .'" style="margin-top: 10px"> <button class="btn" style="padding: 6px 5px 7px 5px; margin-right: 10px"><i class="icon-search"></i></button>
			</form>
		</div>';
		
		$data['tabel'] =  $dt->num_rows() > 0 ? $this->table->generate() : '<div class="alert">Belum ada data lokasi</div>';
		$data['links']	= $this->pagination->create_links();
		$data['total']	= $config['total_rows'];
		$data['query'] 	= $this->general_model->datagrab($param);			
		$data['title'] 		= 'Referensi Lokasi Aset';
		$data['content'] 	= 'umum/standard_view';
		$this->load->view('home',$data);
	}
			
	function add_data($id = null){
			$data['title']	= !empty($id) ? 'Ubah' : 'Entri';
			$parbox = array(
				'tabel'		=> array('ref_unit'=>''),
				'key'		=> 'id_unit',
				'val'		=> array('unit'),
			);
			$param	= array(
				'tabel'		=> array('ref_lokasi_aset'=>''),
				'where'		=> array('id_lokasi'=>$id)
			);
			
			$data['combo_skpd']	= $this->general_model->combo_box($parbox);
			$data['query']	= !empty($id) ? $this->general_model->datagrab($param) : null;	
			$this->load->view('referensi/lokasi_aset_form',$data);		
		}
		
		function save_data(){
			$in = $this->input->post();
			$data = array(
				'id_unit'		=> $in['id_unit'],
				'kode_skpd'		=> $in['kode_skpd'],
				'kode_unker'	=> $in['kode_unker'],
				'kode_ruang'	=> $in['kode_ruang'],
				'nama_unker'	=> $in['nama_unker'],
				'nama_ruang'	=> $in['nama_ruang'],
			);
			
			$save = $this->general_model->save_data('ref_lokasi_aset',$data,'id_lokasi',$in['id']);
			
			if($save):
				$this->session->set_flashdata('msg','Data barhasil disimpan');
			else:
				$this->session->set_flashdata('msg','Data gagal disimpan');
			endif;
			redirect('referensi/lokasi_aset');
		}
		
		function delete_data($id = null){
			
			if($id == null):
				$cek = $_POST['cek'];
				
				foreach($cek as $id):
					$delete = $this->general_model->delete_data('ref_lokasi_aset','id_lokasi',$id);
				endforeach;
			else:
				$delete = $this->general_model->delete_data('ref_lokasi_aset','id_lokasi',$id);
			endif;
			
			if($delete):
				$this->session->set_flashdata('msg','Data barhasil dihapus');
			else:
				$this->session->set_flashdata('msg','Data gagal dihapus');
			endif;
			
			redirect('referensi/lokasi_aset');
			
		}
		
		function getSKPD($id_unit){
			$param = array(
				'tabel'	=> array('ref_unit'=>''),
				'where'	=> array('id_unit'=>$id_unit),
			);
			$q = $this->general_model->datagrab($param)->row();
			$dt['kode_skpd'] = $q->kode_skpd;
			$dt['skpd'] = $q->skpd;
			echo $dt['kode_skpd'];			
		}
	}
?>