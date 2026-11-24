<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Log_users extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        login_check($this->session->userdata('login_state'));
        // $this->load->model('bahan/Log_users_model');
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



    function list_data()
    {
        // $log_users = $this->Log_users_model->get_all();
        $log_users = $this->general_model->datagrabs(array(
            'tabel'=>'log_users',
            'select'=>'*'))->result();


        $dataview = array();
        $dataview['breadcrumb'] = array('bahan/' => 'Bahan/Log user', 'bahan/log_users' => 'Log User');
        $dataview['page_title'] = 'Log user';
        $dataview['page_des'] = '';
        $dataview['bmain_page'] = 'Log user';
        $dataview['bsub_page'] = 'Log user';
        $dataview['title'] = 'Log user List';

        $dataview['content'] = 'bahan/standart_list_datatables';

        $dataview['log_users_data'] = $log_users;
        $dataview['tabel'] = 
        '<table id="mytable" class="table table-striped table-bordered table-condensed table-hover" cellspacing="0" width="100%">
                <thead style="white-space:nowrap;">
            <tr>
                <th rowspan="2">No.</th>
                <th colspan="6">Data</th>
                <th colspan="3">Aksi</th>
            </tr>
                    <tr>
                        <th>Modified Date</th>
                        <th>Operator ID</th>
                        <th>Modul ID</th>
                        <th>Action</th>
                        <th>Field</th>
                        <th>Field (Alias)</th>
                        <th><i class="fa fa-pencil"></i></th>
                        <th><i class="fa fa-trash"></i></th>
                        <th><i class="fa fa-list"></i></th>
                      
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Modified Date</th>
                        <th>Operator ID</th>
                        <th>Modul ID</th>
                        <th>Action</th>
                        <th>Field</th>
                        <th>Field (Alias)</th>
                         <th><i class="fa fa-pencil"></i></th>
                        <th><i class="fa fa-trash"></i></th>
                        <th><i class="fa fa-list"></i></th>
                    </tr>
                </tfoot>
            </table>';
        $dataview['url_datatable'] = site_url('filing/log_users/get_data_tables');

        $dataview['script'] = "

        ";

        $dataview['include_script'] = "

        ";

        $dataview['function_script'] = "
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
            <a class="btn btn-warning dropdown-toggle" data-toggle="dropdown" href="#" style="margin: 0 0 0 5px">
                <i class="fa fa-print"></i>
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu pull-right"><li>'.anchor(site_url('bahan/log_users/pdf'), '<i class="fa fa-print"></i> Cetak / Pdf', 'target="_blank" class="btn pull-left"').'</li>
            </ul>
        </div>' : null;
        
        $dataview['tombol'] = 
        anchor(site_url('bahan/log_users/create'), '<i class="fa fa-plus"></i> Tambah', 'class="btn btn-success"')
        .$btn_cetak;


        $this->load->view('home', $dataview);
    }



    function get_data_tables(){

        $this->load->helper('datatables_helper');
        // konfigurasi
        $table = 'log_users';
        $where = null;
        $select = 'id, id_operator, id_modul, act, field, field_alias, mod_date';
        $primaryKey = 'id';
        // Array of database columns which should be read and sent back to DataTables.
        // The `db` parameter represents the column name in the database, while the `dt`
        // parameter represents the DataTables column identifier. In this case simple
        // indexes
        $columns = array(
           array(
                'db'        => 'mod_date',
                'dt'        => 1,
                'formatter' => function( $d, $row ) {
                    return date( 'jS M y', strtotime($d));
                }
            ),
            array( 'db' => 'id_operator',   'dt' => 2 ),
            array( 'db' => 'id_modul',     'dt' => 3 ),
            array( 'db' => 'act',     'dt' => 4 ),
            array( 'db' => 'field',     'dt' => 5 ),
            array( 'db' => 'field_alias',     'dt' => 6 )
            
        );

        $col_aksi = array(1,1,1); // edit, hapus, detail.

        echo json_encode(
            simple_data( $_GET, $table, $primaryKey, $columns, $where, $select, $col_aksi)
        );
    }



    // ./DATA TABLE FUNCTION CLASS

    public function read($id) 
    {
        $id = un_de($id);
        $row = $this->general_model->datagrabs(array(
            'tabel'=>'log_users',
            'select'=>'*',
            'where'=>array('id'=>$id)))->row();

        if (!empty($row)) {

        $dataview = array();
        $dataview['page_title'] = 'Log user';
        $dataview['page_des'] = '';
        $dataview['bmain_page'] = 'Log user';
        $dataview['bsub_page'] = 'Log user';
        $dataview['title'] = '<small>Detail</small> Log user';
        $dataview['back_url'] = site_url('bahan/log_users');
        $dataview['read'] = '
        <table class="table">
			<tr><td>Mod Date</td><td>'. $row->mod_date. '</td></tr>
			<tr><td>Id Operator</td><td>'. $row->id_operator. '</td></tr>
			<tr><td>Id Modul</td><td>'. $row->id_modul. '</td></tr>
			<tr><td>Act</td><td>'. $row->act. '</td></tr>
			<tr><td>Field</td><td>'. $row->field. '</td></tr>
			<tr><td>Field Alias</td><td>'. $row->field_alias. '</td></tr>
			<tr><td>Data</td><td>'. $row->data. '</td></tr>
		</table>';

        $dataview['content'] = 'bahan/standart_read';

        $this->load->view('home', $dataview);

        } else {
            $this->session->set_flashdata('fail', 'Data Tidak Ditemukan..');
            redirect(site_url('log_users'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Tambah',
            'action' => site_url('bahan/log_users/save_action'),
	    'id' => set_value('id'),
	    'mod_date' => set_value('mod_date'),
	    'id_operator' => set_value('id_operator'),
	    'id_modul' => set_value('id_modul'),
	    'act' => set_value('act'),
	    'field' => set_value('field'),
	    'field_alias' => set_value('field_alias'),
	    'data' => set_value('data'),
	);

         $dataview = array();
        $dataview['page_title'] = 'Log user';
        $dataview['page_des'] = '';
        $dataview['bmain_page'] = 'Log user';
        $dataview['bsub_page'] = 'Log user';
        $dataview['title'] = '<small>Tambah</small> Log user';
        $dataview['back_url'] = site_url('bahan/log_users');

        $dataview['content'] = 'bahan/log_users_form';
        $dataview['data'] = $data;

        $dataview['include_script'] = "
            // $('.form_datetime').datetimepicker({
            //     format: 'dd-MM-yyyy - hh:ii',
            //     autoclose: true,
            //     todayBtn: true,
            //     pickerPosition: 'bottom-left'
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
        ";

        $this->load->view('home', $dataview);
    }
    
    public function save_action($id=null) 
    {
        $id = un_de($id);
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            if (!empty($id)) {
               $this->update(in_de($id));
            }else{
               $this->create(); 
            }
            
        } else {
            $data = array(
		'mod_date' => $this->input->post('mod_date',TRUE),
		'id_operator' => currencyToNumber($this->input->post('id_operator',TRUE)),
		'id_modul' => currencyToNumber($this->input->post('id_modul',TRUE)),
		'act' => currencyToNumber($this->input->post('act',TRUE)),
		'field' => $this->input->post('field',TRUE),
		'field_alias' => $this->input->post('field_alias',TRUE),
		'data' => $this->input->post('data',TRUE),
	    );

            $this->general_model->save_data('log_users', $data, 'id', $id);
            $id = !empty($id) ? $id : $this->db->insert_id(); 
           
            $this->session->set_flashdata('ok', 'Data Berhasil Disimpan...');
            redirect(site_url('bahan/log_users'));
        }
    }
    
    public function update($id) 
    {
        $id = un_de($id);
        // $row = $this->Log_users_model->get_by_id($id);
        $row = $this->general_model->datagrabs(array(
            'tabel'=>'log_users',
            'where'=>array('id'=>$id,'(delete_id NOT IN(select id from log_delete) OR delete_id IS NULL)'=>null),
            'select'=>'*',
            ))->row();

        if ($row) {
            $data = array(
                'button' => 'Ubah',
                'action' => site_url('bahan/log_users/save_action/'.in_de($id)),
		'id' => set_value('id', $row->id),
		'mod_date' => set_value('mod_date', $row->mod_date),
		'id_operator' => set_value('id_operator', $row->id_operator),
		'id_modul' => set_value('id_modul', $row->id_modul),
		'act' => set_value('act', $row->act),
		'field' => set_value('field', $row->field),
		'field_alias' => set_value('field_alias', $row->field_alias),
		'data' => set_value('data', $row->data),
	    );

        $dataview = array();
        $dataview['page_title'] = 'Log user';
        $dataview['page_des'] = '';
        $dataview['bmain_page'] = 'Log user';
        $dataview['bsub_page'] = 'Log user';
        $dataview['title'] = '<small>Ubah</small> Log user';
        $dataview['back_url'] = site_url('bahan/log_users');

        $dataview['content'] = 'bahan/log_users_form';
        $dataview['data'] = $data;

        $dataview['include_script'] = "
            // $('.form_datetime').datetimepicker({
            //     format: 'dd-MM-yyyy - hh:ii',
            //     autoclose: true,
            //     todayBtn: true,
            //     pickerPosition: 'bottom-left'
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
        ";

        $this->load->view('home', $dataview);

        } else {
            $this->session->set_flashdata('fail', 'Data tidak ditemukan....');
            redirect(site_url('bahan/log_users'));
        }
    }
    
    public function delete($id) 
    {
        $id = un_de($id);
        // $row = $this->Log_users_model->get_by_id($id);
        $row = $this->general_model->datagrabs(array('tabel'=>'log_users','where'=>array('(delete_id NOT IN(select id from log_delete) OR delete_id IS NULL)'=>null, 'id'=>$id), 'select'=>'*'));

        if ($row->num_rows() > 0) {
            // $this->Log_users_model->delete($id);
           $this->general_model->delete_data('log_users', 'id',$id);

            // $id_del = $this->general_model->save_data('log_delete', array('mod_at'=>date('Y-m-d H:i:s')));
            // // set delete to this table:
            // $this->general_model->save_data('log_users', array('delete_id'=>$id_del), 'id', $id);

            $this->session->set_flashdata('ok', 'Hapus Data Berhasil...');
            redirect(site_url('bahan/log_users'));
        } else {
            $this->session->set_flashdata('fail', 'Data Tidak Ditemukan...');
            redirect(site_url('bahan/log_users'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('mod_date', 'mod date', 'trim|required');
	$this->form_validation->set_rules('id_operator', 'id operator', 'trim|required');
	$this->form_validation->set_rules('id_modul', 'id modul', 'trim|required');
	$this->form_validation->set_rules('act', 'act', 'trim|required');
	$this->form_validation->set_rules('field', 'field', 'trim|required');
	$this->form_validation->set_rules('field_alias', 'field alias', 'trim|required');
	$this->form_validation->set_rules('data', 'data', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span style="position:relative;" class="text-danger">', '</span>');
    }

    function pdf()
    {
        $log_users = $this->general_model->datagrabs(array(
            'tabel'=>'log_users', 
            'select'=>'*'));
        $data = array(
            'log_users_data' => $log_users,
            'h_title_left'=> 'BAHAN',
            'h_title_center'=> '<span>Laporan</span><br/> Data Log User',
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
        array('data'=>'Field Alias', 'class'=>'headerrow'),
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
    array('data'=>$row->field_alias),
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
        $html = $this->load->view('bahan/pdf_view', $data, true);
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
        $pdf->SetTitle("Log user");
        $pdf->SetAuthor("Log user");
        $pdf->SetWatermarkText("Log user");
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

