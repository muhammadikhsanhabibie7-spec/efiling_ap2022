<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Aktivitas_pengguna extends CI_Controller
{
	var $act = array(
		1=>'<i class="fa  fa-plus-square-o"></i> Tambah Data', 
		2=>'<i class="fa fa-pencil"></i> Ubah Data', 
		3=>'<i class="fa fa-trash-o"></i> Hapus Data'
		);
	var $modul = array(
		1=>'<i class="fa fa-list"></i> Konten Arsip', 
		2=>'<i class="fa fa-files-o"></i> Butir Arsip'
		);

	var $act_color = array(
		1=>'#00a65a;',
		2=>'#ff6a0f;',
		3=>'#ff0f2c;',
		);

    function __construct()
    {
        parent::__construct();
        login_check($this->session->userdata('login_state'));
        // $this->load->model('filing/Log_users_model');
        $this->load->model('general_model');
        $this->load->library('form_validation');
        /* if (!$this->ion_auth->logged_in())
        {
            // redirect them to the login page
            redirect('admin/auth/login', 'refresh');
        } */
    }


 public function index(){
    $this->list_data();
}



 function list_data($search=null, $start=null)
    {
    //  $q = urldecode($this->input->post('q', TRUE));
        $start = !empty($start)? intval($start) :0;
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
                
			'mod_date'=>$q,
			'nama'=>$q,
			'id_modul'=>$q,
			'act'=>$q,
			'field'=>$q,
			'data'=>$q,
			);

        }elseif($search){
            $so = un_de($search);
            $sc = array(
                
			'mod_date'=>$so['text'],
			'nama'=>$so['text'],
			'id_modul'=>$so['text'],
			'act'=>$so['text'],
			'field'=>$so['text'],
			'data'=>$so['text'],
			);
        }else{
            $sc = null;
        }

   // ./ konfigurasi cari + offset 
        

        $config['base_url'] = base_url() . 'filing/aktivitas_pengguna/list_data/' . in_de($fcari);
        $config['first_url'] = base_url() . 'filing/aktivitas_pengguna/list_data/' . in_de($fcari);


		$aSelect = 'a.*, b.nama';

        $config['per_page'] = 10;
       // $config['page_query_string'] = TRUE;
        $config['uri_segment'] = '5';


        
        $config['total_rows'] = $this->general_model->datagrabs(array(
            'tabel'=>array(
            	'log_users a'=>'', 
            	'peg_pegawai b'=>'b.id_pegawai=a.id_operator'
            	),
            'count'=>'count(id) jml',
            'select'=>$aSelect,
            'search'=>$sc))->row()->jml;
        


        $log_users = $this->general_model->datagrabs(array(
            'tabel'=>array(
            	'log_users a'=>'', 
            	'peg_pegawai b'=>'b.id_pegawai=a.id_operator'
            	),
            'select'=>$aSelect,
            'search'=>$sc,
            'order'=>'id DESC',
            'limit'=>$config['per_page'],
            'offset'=>$start));


        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'log_users_data' => $log_users,
            'q' => $fcari['text'],
            'search' => in_de($fcari),
            'links' => $this->pagination->create_links(),
            'total' => $config['total_rows'],
            'start' => $start,
            'content'=>'umum/standard_view'
        );
            if ($log_users->num_rows() > 0) {

            $this->table->set_template(array('table_open'=>'<table class="table table-bordered table-condensed table-hover">'));
            $this->table->set_empty('&nbsp');
            $this->table->set_heading(
            array('data'=>'No.'),
            array('data'=>'Waktu', 'class'=>'text-left', 'style'=>'white-space:nowrap;'),
            array('data'=>'Operator', 'class'=>'text-left', 'style'=>'white-space:nowrap;'),
            array('data'=>'Modul', 'class'=>'text-left', 'style'=>'white-space:nowrap;'),
            array('data'=>'Aktivitas', 'class'=>'text-left', 'style'=>'white-space:nowrap;'),
            array('data'=>'Data Record', 'class'=>'text-left', 'style'=>'white-space:nowrap;')
            );

    if ($log_users->num_rows() > 0) {
        foreach ($log_users->result() as $row) {



        $field = explode('&&', $row->field);
        $field_alias = explode('&&', $row->field_alias);
        $isi = explode('&&', $row->data);
        $data_logs = null;

        if (count($field_alias) > 0) {
        	foreach ($field_alias as $k=>$r_field) {
        		$data_logs .= '<p style="font-size:8pt;">'.ucfirst($r_field).' : <b>'.(!empty($isi[$k]) ? $isi[$k] : '').'</b></p>';
        	}
        }
            
        $cell = array(
            array('data'=>++$start, 'style'=>'text-align:left', 'width'=>50),
            array('data'=>'<i class="fa fa-calendar"></i> <b>'.date('d/m/Y', strtotime($row->mod_date)).'</b> &nbsp; <i class="fa fa-clock-o"></i> <span style="color:#00c0ef;">'.date('H:i:s', strtotime($row->mod_date)).'</span>', 'style'=>'white-space:nowrap;'),
            array('data'=>'<i class="fa fa-user"> '.$row->nama.'</i>', 'style'=>'white-space:nowrap;'),
            array('data'=>$this->modul[$row->id_modul],'style'=>'white-space:nowrap;'),
            array('data'=>'<span style="color:'.$this->act_color[$row->act].'"><b>'.$this->act[$row->act].'</b></span>', 'style'=>'white-space:nowrap;'),
            $data_logs
                );
        $this->table->add_row($cell);
                }
        $data['tabel'] = '<div class="table-responsive no-padding">'.$this->table->generate().'</div>';
            }else{
        $data['tabel'] = 'Tidak/belum ada data..';
            }
            }else{
        $data['tabel'] = 'Belum ada data';
            }


        $data['include_script'] = "
        <script>
            // $('.form_datetime').datetimepicker({
            // format: 'dd-MM-yyyy - hh:ii',
            // autoclose: true,
            // todayBtn: true,
            // pickerPosition: 'bottom-left'
            //   });
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

        $btn_cetak = !empty($log_users) ? 
        '<div class="btn-group"  tyle="margin-left: 5px;">
            <a class="btn btn-flat btn-info btn-md dropdown-toggle" data-toggle="dropdown" href="#" style="margin: 0 0 0 5px">
                <i class="fa fa-print"></i> <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu"><li>'.anchor(site_url('filing/aktivitas_pengguna/pdf'), '<i class="fa fa-print"></i> Cetak / Pdf', 'target="_blank" class=""').'</li>
            </ul>
        </div>' : null;

        $sc_res = ($fcari['text'] <> '') ? anchor(site_url('filing/aktivitas_pengguna'), 'Reset', 'class="btn btn-md btn-default"') : null;

        $data['extra_tombol'] =
                '<ul class="unstyled inbox-pagination">'.
                form_open(site_url('filing/aktivitas_pengguna/list_data/'.in_de($fcari)), 'class="form-horizontal"').
                
                '<div class="input-group">'.
                form_input('q', $fcari['text'], 'placeholder="Operator ..." class="form-control input-md"').
                '<span class="input-group-btn">'.
                $sc_res.
                '<button class="btn btn-md btn-primary btn-flat" type="submit"><i class="fa fa-search"></i></button>'.
                '</span>'.
                '</div>'.
                form_close().
                '<ul>'
                ;
        
        // $data['tombol'] = $btn_cetak;
        $data['tombol'] = null;

        $data['breadcrumb'] = array('filing/' => 'Bahan/Laporan', 'filing/aktivitas_pengguna' => 'Aktivitas Pengguna');
        $data['page_title'] = 'Aktivitas Pengguna';
        $data['page_des'] = '';
        $data['bmain_page'] = 'Laporan';
        $data['bsub_page'] = 'Aktivitas Pengguna';
        $data['title'] = '<small>List</small> Aktivitas Pengguna';

        $this->load->view('home', $data);
    }

    public function read($id) 
    {
        $id = un_de($id);
        $row = $this->general_model->datagrabs(array(
            'tabel'=>'log_users',
            'select'=>'*',
            'where'=>array('id'=>$id)))->row();

        if (!empty($row)) {

        $dataview = array();
        $dataview['page_title'] = 'Aktivitas Pengguna';
        $dataview['page_des'] = '';
        $dataview['bmain_page'] = 'Laporan';
        $dataview['bsub_page'] = 'Aktivitas Pengguna';
        $dataview['title'] = '<small>Detail</small> Aktivitas Pengguna';
        $dataview['back_url'] = site_url('filing/aktivitas_pengguna');
        $dataview['read'] = '
        <table class="table">
			<tr><td>Mod Date</td><td>'. $row->mod_date. '</td></tr>
			<tr><td>Id Operator</td><td>'. $row->id_operator. '</td></tr>
			<tr><td>Id Modul</td><td>'. $row->id_modul. '</td></tr>
			<tr><td>Act</td><td>'. $row->act. '</td></tr>
			<tr><td>Field</td><td>'. $row->field. '</td></tr>
			<tr><td>Data</td><td>'. $row->data. '</td></tr>
		</table>';

        $dataview['content'] = 'filing/standart_read';

        $this->load->view('home', $dataview);

        } else {
            $this->session->set_flashdata('fail', 'Data Tidak Ditemukan..');
            redirect(site_url('log_users'));
        }
    }

  
    function pdf()
    {
        $log_users = $this->general_model->datagrabs(array(
            'tabel'=>'log_users', 
            'select'=>'*'));
        $data = array(
            'log_users_data' => $log_users,
            'h_title_left'=> 'BAHAN',
            'h_title_center'=> '<span>Laporan</span><br/> Data Aktivitas Pengguna',
            'h_sub_center'=> '',
            'tgl_cetak'=> date('d-M-Y'),
            'company_address'=>'CV. MEDIA MULTI KARYATAMA',        
            'company_country'=>'Yogyakarta - Indonesia',        
            'company_logo'=>base_url('assets/logo/brand.png'),        
           // 'company_logo'=>$logo_instansi      
            );
        if ($log_users->num_rows() > 0) {

        $this->table->set_template(array('table_open'=>'<table class="bpmTopnTailC" width="100%">'));
        $this->table->set_empty('');
        $this->table->set_heading(
        array('data'=>'No.', 'class'=>'headerrow'),
        array('data'=>'Mod Date', 'class'=>'headerrow'),
        array('data'=>'Id Operator', 'class'=>'headerrow'),
        array('data'=>'Id Modul', 'class'=>'headerrow'),
        array('data'=>'Act', 'class'=>'headerrow'),
        array('data'=>'Field', 'class'=>'headerrow'),
        array('data'=>'Data', 'class'=>'headerrow'),
        array('data'=>''));

if ($log_users->num_rows() > 0) {
    foreach ($log_users->result() as $row) {
      
        
    $this->table->add_row(
        array('data'=>++$start),
    array('data'=>$row->mod_date),
    array('data'=>$row->id_operator),
    array('data'=>$row->id_modul),
    array('data'=>$row->act),
    array('data'=>$row->field),
    array('data'=>$row->data),
    array('data'=>''));
            }
    $data['table'] = $this->table->generate();
        }else{
    $data['table'] = 'Tidak/belum ada data..';
        }
        }else{
    $data['table'] = 'Belum ada data';
        }
    $data['title'] = 'LOG_USERS';
    
		 ini_set('memory_limit', '512M');
       // $html = $this->load->view('log_users_pdf', $data, true);
        $html = $this->load->view('filing/pdf_view', $data, true);
        $this->load->library('pdf');
        $parameters= array(
                'mode' => 'utf-8',
                'format' => 'A4',    // A4 for portrait
                'default_font_size' => '12',
                'default_font' => '',
                'margin_left' => 20,
                'margin_right' => 15,
                'margin_top' => 48,
                'margin_bottom' => 10,
                'margin_header' => 20,
                'margin_footer' => 10,
                'orientation' => 'P' // For some reason setting orientation to "L" alone doesn't work (it should), you need to also set format to "A4-L" for landscape
            );

        $pdf = $this->pdf->load($parameters);
        // setting
        $pdf->SetProtection(array('print'));
        $pdf->SetTitle("Aktivitas Pengguna");
        $pdf->SetAuthor("Aktivitas Pengguna");
        $pdf->SetWatermarkText("Aktivitas Pengguna");
        $pdf->showWatermarkText = true;
        $pdf->watermark_font = 'DejaVuSansCondensed';
        $pdf->watermarkTextAlpha = 0.1;
        $pdf->SetDisplayMode('fullpage');
        // .setting

        $stylesheet = file_get_contents(base_url('assets/plugins/mpdf/mpdfstyletables.css'));
        $pdf->WriteHTML($stylesheet,1);

        $pdf->WriteHTML($html);
        $pdf->Output('log_users.pdf', 'I'); 
        // $pdf->Output(); 

    }

}

/* 
End of file log_users.php */

