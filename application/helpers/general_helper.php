<?php
if(!function_exists('_ci')) {
	function _ci() {
		$_ci = &get_instance();
		return $_ci;
	}
}


function login_check($session) {
	if (empty($session)) redirect('login/login'); 
	get_tracking_cookie();
}

function shrot_word($string, $word_limit)
	{
		$words = explode(" ",$string);
	    return implode(" ",array_splice($words,0,$word_limit));
	}
function notif($data) {
	echo "<div class='alert alert-success'>".$data."<button type='button' class='close' data-dismiss='alert'>&times;</button></div>";
}

function get_tahun($tahun) {
		$tahun_arr = array();
		for ($i = 0; $i < 10; $i++) {
			$tahun_arr[$tahun-$i] = $tahun-$i;
		}
		return $tahun_arr;
		
}

function get_bulan($bulan) {
	$bulan_arr = array('' => ' -- Pilih -- ', 1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
	return $bulan_arr;
}
// -- Format Currency -- //

function rupiah($jumlah,$book=null) {
    if ($jumlah <> 0 or !empty($jumlah)) {
		if ($jumlah < 0) {
			$jumlah = substr($jumlah,1);
			if (isset($book) and $book == 1) $return = "<span class='pull-left'>Rp &nbsp;</span> <span class='pull-right'> -".number_format($jumlah, 2, ",", ".").'</span>';
			else if (isset($book) and $book == 2) $return = "<span class='pull-left'>Rp &nbsp;</span> <span class='pull-right'>(-".number_format($jumlah, 2, ",", ".").')</span>';
			else $return = "Rp -".number_format($jumlah, 2, ",", ".");
		} else {
			if (isset($book) and $book == 1) $return = "<span class='pull-left'>Rp &nbsp;</span> <span class='pull-right'> ".number_format($jumlah, 2, ",", ".").'</span>';
			else if (isset($book) and $book == 2) $return = "<span class='pull-left'>Rp &nbsp;</span> <span class='pull-right'>(".number_format($jumlah, 2, ",", ".").')</span>';
			else $return = "Rp ".number_format($jumlah, 2, ",", ".");   
		}
    } else {
		if (isset($book)) $return = "<span class='pull-left'>Rp &nbsp;</span><span class='pull-right'>0,00</span>";
		else $return = "Rp 0,00";
    }
    return $return;
}

function numberToCurrency($a,$tipe = null) {
	if ($a != 0 or $a != 0.00) {

	if ($a < 0) {
	    $a = substr($a,1);
	    $col = '-'.number_format($a, 2, ",", ".");
	} else {
	    $col = number_format($a, 2, ",", ".");
	}
	if (!empty($tipe)) $col = '<span class="pull-right">'.$col.'</span>';
	return str_replace(',00','',$col);
	}
}

function currencyToNumber($a){
    $b=str_ireplace(".", "", $a);
    return str_replace(",",".",$b);
}

// -- Layout -- //

function breadcrumbs($data) {
	$print= "<div class='breadcrumbs'><p>";
	$i=1;
	foreach ($data as $echo) {
		$print.= ($i>1) ? ' &raquo '. $echo : $echo;
		$i+=1;
	}
	$print.= "</p></div>";
	return $print;
}


// -- Hitungan -- //

function percentToID($data) {
	$datacent = $data/100;
	$percent = preg_replace('/[.]/', ',', $datacent);
	return $percent;
}

function percentToEN($data) {
	$percent = preg_replace('/[,]/', '.', $data);
	$datacent = $percent*100;
	return $datacent;
}

function cek($data) {
	echo "<pre>";
	print_r($data);
	echo "</pre>";
}

function tanggal($data) {
	date_default_timezone_set('Asia/Jakarta');
	if (!empty($data) and $data != "0000-00-00") return date("d/m/Y",strtotime($data));
	else return ' ';
}

function tanggal_php($data) {
	$data = trim((string) $data);
	if ($data !== '') {
		$ex = explode('/',$data);
		if (count($ex) === 3) {
			return $ex[2].'-'.$ex[1].'-'.$ex[0];
		}
		return $data;
	}
	return null;
}

function tanggal_jam_php($data) {
	date_default_timezone_set('Asia/Jakarta');
	if (!empty($data)) {
		$ex = explode('/',$data);
		return $ex[2].'-'.$ex[1].'-'.$ex[0].' '.date('H:i:s');
	}
}

function tanggal_indo($data){
	$ex_tgl = explode('-',$data);
		
	$bulan = array("01"=>"Januari","02"=>"Pebruari","03"=>"Maret","04"=>"April","05"=>"Mei","06"=>"Juni","07"=>"Juli","08"=>"Agustus","09"=>"September",
				   "10"=>"Oktober","11"=>"Nopember","12"=>"Desember");
	if($ex_tgl[0] == '0000' || $ex_tgl[1] == '00' || $ex_tgl[2] == '00') $tgl = 'Tidak tertanggal';			   
	else $tgl = $ex_tgl[2]." ".$bulan[$ex_tgl[1]]." ".$ex_tgl[0];
		
	return $tgl;
}

function tanggal_jam($data){
	$tgl_jam = explode(' ',$data);
	$tgl = explode('-', $tgl_jam[0]);
	$jam = $tgl_jam[1];
	$bulan = array("01"=>"Januari","02"=>"Pebruari","03"=>"Maret","04"=>"April","05"=>"Mei","06"=>"Juni","07"=>"Juli","08"=>"Agustus","09"=>"September",
				   "10"=>"Oktober","11"=>"Nopember","12"=>"Desember");
	if($tgl[0] == '0000' || $tgl[1] == '00' || $tgl[2] == '00') $tanggal = 'Tidak tertanggal';			   
	else $tanggal = $tgl[2]." ".$bulan[$tgl[1]]." ".$tgl[0]."<br> Jam ".$jam;
	return $tanggal;
}

function umur($tgl){
	$interval = date_diff(date_create(), date_create($tgl));
	$age = $interval->format('%Y Thn %M Bln');
	return $age;
}

function replaceBulan($data) {
	$data = preg_replace('/[0]/','', $data);
	$bulanArr=array('1'=>'Januari','2'=>'Februari','3'=>'Maret','4'=>'April','5'=>'Mei','6'=>'Juni','7'=>'Juli','8'=>'Agustus','9'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember',);
	return $bulanArr[$data];
}

function eraseChar($data) {
	$hilangslash = preg_replace('/\/+/', '_', $data);
	return preg_replace('/\ +/', '-', $hilangslash);
}

function addChar($data) {
	$plus = preg_replace('/\_+/', '/', $data);
	return preg_replace('/\-+/', ' ', $plus);
}

function in_de($data) {
	$data_on = base64_encode(serialize($data));
	return preg_replace('/\=+/', '-', $data_on);
}

function un_de($data) {
	if (empty($data)) return null;
	$on_data = preg_replace('/\-+/', '=', (string)$data);
	return @unserialize(base64_decode($on_data));
}


function konversi_tanggal($format, $tanggal="now"){
 $en=array("Sun","Mon","Tue","Wed","Thu","Fri","Sat","Jan","Feb",
 "Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");

 $id=array("Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu",
 "Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September",
 "Oktober","November","Desember");

 return str_replace($en,$id,date($format,strtotime($tanggal)));
}

function terbilang($x)
{
  $abil = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
  if ($x < 12):
    return " " . $abil[$x];
  elseif ($x < 20):
    return terbilang($x - 10) . "Belas";
  elseif ($x < 100):
    return terbilang($x / 10) . " Puluh" . terbilang($x % 10);
  elseif ($x < 200):
    return " seratus" . terbilang($x - 100);
  elseif ($x < 1000):
    return terbilang($x / 100) . " Ratus" . terbilang($x % 100);
  elseif ($x < 2000):
    return " seribu" . terbilang($x - 1000);
  elseif ($x < 1000000):
    return terbilang($x / 1000) . " Ribu" . terbilang($x % 1000);
  elseif ($x < 1000000000):
    return terbilang($x / 1000000) . " Juta" . terbilang($x % 1000000);
  elseif ($x < 1000000000000):
    return terbilang($x / 1000000000) . " Milyar" . terbilang($x % 1000000000);
  elseif ($x < 1000000000000000):
    return terbilang($x / 1000000000000) . " Trilyun" . terbilang($x % 1000000000000);
  endif;
}

function generateRM(){
	$no_urut = '000000';
	$sql="SELECT max( CAST( no_rm AS UNSIGNED ) ) no_rm
FROM (
`rs_pasien`
)
ORDER BY `no_rm` DESC ";
	_ci()->load->database();
//	_ci()->db->order_by('no_rm','desc');
//    _ci()->db->limit(1);
//    _ci()->db->select('max(CAST(no_rm  as UNSIGNED))no_rm ');
//	_ci()->db->select_max('no_rm');
    
//    $q         = _ci()->db->get('rs_data_penduduk')->row();
	$q 		= _ci()->db->query($sql)->row(); 
//    cek($q);
//    cek(_ci()->db->last_query());
//    $lastrm = (int) $q->no_rm;
	$lastrm = $q->no_rm;
	
	if($lastrm == null || $lastrm == '') $lastrm = 0;
//	$potong = strlen($no_urut) - strlen((string) $lastrm);
	$lastrm	= $lastrm + 1;
	$newrm 	= substr($no_urut.$lastrm,strlen($no_urut.$lastrm)-6,6);
//    ,-(int) $potong).(string) $lastrm;
		
	return $newrm;
}
function combo_option($sel) {
	$dis = null;
	foreach($sel as $v => $a) $dis .= '<option value="'.$v.'">'.$a.'</option>';
	return $dis;
}

function watermark($text,$configArray=array(),$filepath='uploads'){
    if(trim($text) == "")
        return "";
    $conf = array();
    $conf['background-color'] = isset($configArray['background-color']) ? $configArray['background-color'] : '#000000';
    $conf['color']            = isset($configArray['color'])            ? $configArray['color'] : '#ffffff';
    $conf['font-size']        = isset($configArray['font-size'])        ? $configArray['font-size'] : '14';
    $conf['font-file']        = isset($configArray['font-file'])        ? $configArray['font-file'] : 'bootstrap/css/bebas.otf';
    $conf['params']           = isset($configArray['params'])           ? $configArray['params'] : '';
    
    // calculate a hash out of the configuration array-> image is only generated if its not found in the filepath
    $str = $text;
    foreach($conf as $key => $val){
        $str .= $key."=".$val;
    }
    $hash = md5($str);
    $imagepath = $filepath.'/'.$hash.'.gif';
    if(!file_exists($imagepath)){
        $data = imagettfbbox($conf['font-size'], -0.2, $conf['font-file'], $text);
        $x = 0 - $data[6];
        $y = 0 - $data[7]-$data[3];
        //print_r($data);
        
        $y *= 1.1;  //dunno why - but without this line the area will be a bit too small in hight
        //echo $y;
        $res = imagecreate($data[2]*1.04, 2*$data[3] + $y);
        $r = hexdec(substr($conf['background-color'],1,2));
        $g = hexdec(substr($conf['background-color'],3,2));
        $b = hexdec(substr($conf['background-color'],5,2));
        $backgroundcolor = imagecolorallocate($res,$r,$g, $b);
        $r = hexdec(substr($conf['color'],1,2));
        $g = hexdec(substr($conf['color'],3,2));
        $b = hexdec(substr($conf['color'],5,2));
        
        $textcolor = imagecolorallocate($res,$r, $g, $b);
        imagettftext($res, $conf['font-size'], 0, 3, $conf['font-size'], $textcolor, $conf['font-file'], $text);
        
        imagegif($res, $imagepath);
    }
    return '<img src="'.base_url().$imagepath.'" border="0"/>';
    
}

/* kalender indonesia */
	function date_indo($data,$format){
		if($data != '0000-00-00' && !empty($data)){
			$ex = explode('-',$data);
			
			/* some formats 
				1 -> dd/mm/yyyy
				2 -> dd month(sort) yyyy
				3 -> dd month (full) yyyy
			*/
			
			if($format == 1){
				$tgl = $ex[2].'/'.$ex[1].'/'.$ex[0];
			}else if($format == 2){
				$bulan = array('01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'Mei','06'=>'Jun','07'=>'Jul','08'=>'Agu','09'=>'Sep','10'=>'Okt','11'=>'Nop','12'=>'Des');
				$tgl = $ex[2].' '.$bulan[$ex[1]].' '.$ex[0];
			}else if($format == 3){
				$bulan = array('01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'Nopember','12'=>'Desember');
				$tgl = $ex[2].' '.$bulan[$ex[1]].' '.$ex[0];
			}
			return $tgl;
		}
	}
	
	function date_indo2($data,$format){
		if($data != '00-00-0000' && !empty($data)){
			$ex = explode('/',$data);
			
			/* some formats 
				1 -> dd/mm/yyyy
				2 -> dd month(sort) yyyy
				3 -> dd month (full) yyyy
			*/
			
			if($format == 1){
				$tgl = $ex[0].'/'.$ex[1].'/'.$ex[2];
			}else if($format == 2){
				$bulan = array('01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'Mei','06'=>'Jun','07'=>'Jul','08'=>'Agu','09'=>'Sep','10'=>'Okt','11'=>'Nop','12'=>'Des');
				$tgl = $ex[0].' '.$bulan[$ex[1]].' '.$ex[2];
			}else if($format == 3){
				$bulan = array('01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'Nopember','12'=>'Desember');
				$tgl = $ex[0].' '.$bulan[$ex[1]].' '.$ex[2];
			}
			return $tgl;
		}
	}

	function load_controller($app,$controller, $method = 'index',$param1 = null,$param2 = null,$param3 = null) {
		
		$path = FCPATH . APPPATH . 'controllers/'.$app.'/'. $controller . '.php';
		
		if (file_exists($path)) {
			require_once($path);
			$controller = new $controller();

		return $controller->$method($param1,$param2,$param3);
		}
    }
	
// -- B: HELPER MILIK SIMAS --	
function getItemMutasi($id_ba){
	$CI =& get_instance();
	$CI->load->database();
	$CI->db->where('id_ba_mutasi',$id_ba);
	return $CI->db->get('simas_mutasi_item');
}

function getItemPenghapusan($id_penghapusan){
	$CI =& get_instance();
	$CI->load->database();
	$CI->db->where('id_penghapusan',$id_penghapusan);
	return $CI->db->get('simas_penghapusan_item');
}

function getNamaKib($kib){
	switch ($kib){
		case 'a':$kib='KIB A. Tanah';break;
		case 'b':$kib='KIB B. Peralatan Mesin';break;
		case 'c':$kib='KIB C. Gedung Bangunan';break;
		case 'd':$kib='KIB D. Jalan Irigasi Jaringan';break;
		case 'e':$kib='KIB E. Aset Tetap Lainnya';break;
		case 'f':$kib='KIB F. Konstruksi Dalam Pengerjaan';break;
	}
	return $kib;
}

function getDropTahun(){
	$thn = date('Y');
	$cbTahun = array(''=>'-- Pilih Tahun --');
	for($thn;$thn>=2010;$thn--){
		$cbTahun[$thn] = $thn;
	}
	return $cbTahun;
}

function getNamaSKPD($id=null){
	$CI =& get_instance();
	$CI->load->database();
	$CI->db->where('id_unit',$id);
	$sk = $CI->db->get('ref_unit')->row();
	return @$sk->unit;
}

function getNamaLokasi($id=null){
	$CI =& get_instance();
	$CI->load->database();
	$CI->db->where('id_bidang',$id);
	$lokasi = $CI->db->get('ref_bidang')->row();
	return @$lokasi->nama_bidang;
}

function getNamaKepala($id=null){
	$CI =& get_instance();
	$CI->load->database();
	$CI->db->select('*');
	$CI->db->from('ref_unit a');
	$CI->db->join('peg_pegawai b', 'a.id_kepala = b.id_pegawai');
	$CI->db->where('a.id_unit',$id);
	$sk = $CI->db->get()->row();
	return @$sk->nama;
}

function getNipKepala($id=null){
	$CI =& get_instance();
	$CI->load->database();
	$CI->db->select('*');
	$CI->db->from('ref_unit a');
	$CI->db->join('peg_pegawai b', 'a.id_kepala = b.id_pegawai');
	$CI->db->where('a.id_unit',$id);
	$sk = $CI->db->get()->row();
	return @$sk->nip;
}

	function reg($x){
		$format = '0000';
		$potong = strlen($format) - strlen($x);
		$reg = substr($format,0,$potong).$x;
		return $reg;	
	}
	
	function getSatker($id_lokasi=null){
		$CI = & get_instance();
		$CI->load->database();
		$CI->db->where('id_lokasi',$id_lokasi);
		$q = $CI->db->get('ref_lokasi_aset')->row();
		$lok = @$q->nama_unker;
		return $lok;
	}
	
	function getRuang($id_lokasi=null){
		$CI = & get_instance();
		$CI->load->database();
		$CI->db->where('id_lokasi',$id_lokasi);
		$q = $CI->db->get('ref_lokasi_aset')->row();
		$ruang = @$q->nama_ruang;
		return $ruang;
	}
	
	function getKodeLokasi($id_unit=null){
		$CI = & get_instance();
		$CI->load->database();
		$CI->db->from('ref_unit a');
		$CI->db->join('ref_lokasi_aset b','a.id_unit=b.id_unit','left');
		$CI->db->where('a.id_unit',$id_unit);
		$q = $CI->db->get()->row();
		$kodelokasi = $q->kode_skpd.'.'.$q->kode_unker.'.'.$q->kode_ruang;
		return $kodelokasi;
	}
// -- E: HELPER MILIK SIMAS --		

	
	function tata_jabatan($data) {
		
		$str_asli = array('Sub Bidang Sub Bidang','Sub Bagian Sub Bagian', 'Bidang Bidang','Seksi Seksi');
		$str_ganti = array('Sub Bidang','Sub Bagian','Bidang', 'Seksi');
		
		return str_replace($str_asli, $str_ganti, $data);
	}
	
	function get_role($id) {	
		
		
		
		$CI =& get_instance();
		$CI->load->database();	
			
		$CI->db->from('pegawai_role r');
		$CI->db->join('ref_role ro','ro.id_role = r.id_role','left');
		$CI->db->join('ref_aplikasi a','a.id_aplikasi = ro.id_aplikasi','left');
		$CI->db->where('r.id_pegawai',$id);
		$CI->db->where('a.aktif',1);
		$CI->db->order_by('a.urut');
		$roled = $CI->db->get();
		
		$role = array();
		$aplikasi = array();
		
		foreach($roled->result() as $r) {
			
			$CI->db->from('ref_aplikasi');
			$CI->db->where('id_par_aplikasi',$r->id_aplikasi);
			$an = $CI->db->get();
			
			$anak = array();
			foreach ($an->result() as $ap_a) {
				$app_anak[] = $ap_a->folder;
			}
			
			$aplikasi[$r->folder] = array(
				'id_role' => $r->id_role,
				'id_aplikasi' => $r->id_aplikasi,
				'aplikasi_anak' => $anak,
				'nama_role' => $r->nama_role,
				'nama_aplikasi' => $r->nama_aplikasi,
				'direktori' => $r->folder,
				'warna' => $r->warna);
			
		}
		
		$on = array(
		'aplikasi' => $aplikasi
		); return $on;

	}
	
	function get_stationer($id = null) {

		if (empty($id)) {
			$id = array(
			'aplikasi',
			'aplikasi_code',
			'aplikasi_s',
			'aplikasi_logo_ext',
			'aplikasi_logo',
			'ibukota',
			'alamat',
			'main_color',
			'pemerintah',
			'pemerintah_s',
			'pemerintah_logo',
			'pemerintah_logo_bw',
			'pemerintah_logo_ext',
			'instansi',
			'instansi_s',
			'instansi_code',
			'copyright',
			'demo');
		}
				
		$CI =& get_instance();
		$CI->load->database();	
			
		$CI->db->where("param IN ('".implode("','",$id)."')",null,false);	
		$data = $CI->db->get('parameter');
		$ret = array();
		foreach($data->result() as $d) { 
			$ret[$d->param]= $d->val;
		}
		return $ret;

	}
	
	function uris($link) {
		
		$CI =& get_instance();
		$CI->load->helpers('url');	

		if (empty($link)) return null;
		$act = 1;

		$link = explode('/',(string)$link);
		$ur = array();
		for($i = 0; $i < count($link); $i++) {
			$ur[] = $CI->uri->segment($i+1);
		}
		for($i = 0; $i < count($link); $i++) {
			
			if ($ur[$i] != $link[$i]) $act = 2;
			
		}
		return ($act == 1) ? 'active' : null;
	}
			
	function check_uris($id) {
		
		$on_active = null;
		
		$CI =& get_instance();
		$CI->load->database();	
		
		$CI->db->from('nav');
		$CI->db->where('id_par_nav', $id);
		
		$n = $CI->db->get();
		foreach($n->result() as $e) {
			
			$j = uris($e->link);
			if (!empty($j)) $on_active = 'active';
			else {
				
				$CI->db->from('nav');
				$CI->db->where('id_par_nav', $e->id_nav);
				
				$nn = $CI->db->get();
				foreach($nn->result() as $ee) {
			
					$jj = uris($ee->link);
					if (!empty($jj)) $on_active = 'active';
				}
				
				
			}
			
			
		}
		
		return $on_active;
		
	}
	
	function get_nav($id_peg,$ref,$app,$id_par = null) {
		
		$on_active = null;
		
		$CI =& get_instance();
		$CI->load->database();	
		
		$where = (!empty($id_par)) ? 'n.id_par_nav = '.$id_par : 'n.ref = '.$ref.' AND id_par_nav IS NULL AND n.id_aplikasi = '.$app;
		
		$CI->db->from('nav n');
		$CI->db->join('ref_role_nav r','r.id_nav = n.id_nav');
		$CI->db->join('pegawai_role pr', 'pr.id_role = r.id_role');
		$CI->db->where($where, null);	
		$CI->db->where('pr.id_pegawai', $id_peg);
		$CI->db->where('n.tipe', 2);
		$CI->db->where('n.aktif', 1);
		$CI->db->order_by('n.urut', 'ASC');
		
		$nav = $CI->db->get();

		foreach($nav->result() as $n) {
			
			$CI->db->from('nav n');
			$CI->db->where('id_par_nav', $n->id_nav);	
			$CI->db->where('tipe','2');
			$ch = $CI->db->get();
			
			$icon = (!empty($n->fa)) ? $n->fa :  'circle-o';
			if ($ch->num_rows() > 0) {
				$j = check_uris($n->id_nav);
			
				echo '<li class="treeview '.$j.'">
				<a href="#"><i class="fa fa-'.$icon.'"></i> <span>'.$n->judul.' </span> <i class="fa fa-angle-left pull-right"></i></a>
				<ul class="treeview-menu">';
				get_nav($id_peg,$ref,$app,$n->id_nav);
				echo '</ul>';
			} else {
				$j = uris($n->link);
				$j = !empty($j) ? 'class="active"':null;
				echo "<li ".$j.">".anchor($n->link,'<i class="fa fa-'.$icon.'"></i> <span>'.$n->judul.'</span>')."</li>";
			} 
			
		}
		
		
	}
	
	function get_ref_nav($id_peg) {
		
		$CI =& get_instance();
		$CI->load->database();	
		
		$CI->db->from('nav n');
		$CI->db->join('ref_role_nav r','r.id_nav = n.id_nav');
		$CI->db->join('pegawai_role pr', 'pr.id_role = r.id_role');
		$CI->db->where(array(
			'ref' => 2,
			'n.tipe' => 2,
			'aktif' => 1,
			'pr.id_pegawai' => $id_peg,
			'n.id_aplikasi' => 1,
			'id_par_nav IS NULL' => null
		));	
		
		$nav = $CI->db->get();

		foreach($nav->result() as $n) {
			
			$CI->db->from('nav n');
			$CI->db->where('id_par_nav', $n->id_nav);	
			$CI->db->where('tipe','2');
			$CI->db->where('aktif','1');
			$ch = $CI->db->get();
			
			$icon = (!empty($n->fa)) ? $n->fa :  'circle-o';
			if ($ch->num_rows() > 0) {
				$j = check_uris($n->id_nav);
				echo '<li class="treeview '.$j.'">
				<a href="#"><i class="fa fa-'.$icon.'"></i> <span>'.$n->judul.' </span> <i class="fa fa-angle-left pull-right"></i></a>
				<ul class="treeview-menu">';
				get_nav($id_peg,2,1,$n->id_nav);
				echo '</ul>';
			} else {
				$j = uris($n->link);
				$j = !empty($j) ? ' class="active"':null;
				echo "<li".$j.">".anchor($n->link,'<i class="fa fa-'.$icon.'"></i> <span>'.$n->judul.'</span>')."</li>";
			} 
			
		}
		
		$ro = get_role($id_peg);
		foreach($ro['aplikasi']  as $k => $v) {
			if ($v['id_aplikasi'] != 1) {
				echo '<li class="treeview">
				<a href="#"><i class="fa fa-cog"></i> <span>'.$v['nama_aplikasi'].' </span> <i class="fa fa-angle-left pull-right"></i></a>
				<ul class="treeview-menu">';
				get_nav($id_peg,2,$v['id_aplikasi']);
				echo '</ul>';
			}
		}
	}
	
	function merger($e,$b,$u) {
		
		$ub = ($u == $b) ? $u : $u.' '.$b;
		
		
		$e = explode(' ',strtoupper($e.' '.$ub));
		
		$ka = array();
		foreach($e as $ee) {
			if (!in_array(strtoupper($ee),$ka)) $ka = array_merge($ka,array(strtoupper($ee))); 
		}
		$kata = array();
		foreach($ka as $k) { $kata[] = substr(strtoupper($k),0,1).substr(strtolower($k),1); }
		return implode(' ',$kata);
		
	}

	// efiling

	// LOG USER:

function log_users($val=array()){

	// act: 1 tambah, 2 edit, 3 hapus
	// id_modul: 1 konten arsip, 2 butir arsip
	$CI =& get_instance();
	$CI->load->model('general_model');

	_ci()->load->database();

	if (!empty($val)) {

		$isi = implode('&&', $val['data_value']);
		$field = implode('&&', $val['field_value']);
		$field_alias = implode('&&', $val['field_alias']);

		$isi_ar = explode('&&', $isi);
		$field_ar = explode('&&', $field);
		$field_alias_ar = explode('&&', $field_alias);

		$data = array(
			'id_operator' => $val['id_operator'],
			'id_modul' => $val['id_modul'],
			'act' => $val['act'],
			'data' => $isi,
			'field' => $field,
			'field_alias' => $field_alias
			);
		
		// cek($isi);
		// cek($field);
		// cek($isi_ar);
		// cek($field_ar);

		// cek($data);
		// cek(_ci()->db->last_query()); die();

	   	$CI->general_model->save_data('log_users', $data);

	   	// cek(_ci()->db->last_query());

	   	return 1;
	   }else{
	   	return 0;
	   }   

}
function get_filing_notifications($id_peg = null) {
	$CI =& get_instance();
	$CI->load->database();

	// Auto-update peminjaman yang sudah melewati tanggal kembali menjadi Telat
	if ($CI->db->table_exists('arsip_peminjaman')) {
		$today = date('Y-m-d');
		$CI->db->where('status_pinjam', 'Dipinjam');
		$CI->db->where('tgl_kembali <', $today);
		$CI->db->update('arsip_peminjaman', array('status_pinjam' => 'Telat'));
	}

	$items = array();
	$pending_akun = 0;
	$pending_pinjam = 0;
	$telat_pinjam = 0;
	$unvalidated_sa = 0;
	$pending_disp = 0;

	$is_sa = (!empty($id_peg) && $CI->general_model->check_role($id_peg, 'OPRSA'));

	if ($is_sa && $CI->db->table_exists('peg_pegawai')) {
		$q_reg = $CI->db->query("SELECT COUNT(*) AS jml FROM peg_pegawai WHERE status = 0");
		if ($q_reg && $q_reg->num_rows() > 0) $pending_akun = (int) $q_reg->row()->jml;
	}

	if ($CI->db->table_exists('arsip_peminjaman')) {
		$q1 = $CI->db->query("SELECT COUNT(*) AS jml FROM arsip_peminjaman WHERE status_pinjam = 'Menunggu'");
		if ($q1 && $q1->num_rows() > 0) $pending_pinjam = (int) $q1->row()->jml;

		$q2 = $CI->db->query("SELECT COUNT(*) AS jml FROM arsip_peminjaman WHERE status_pinjam = 'Telat'");
		if ($q2 && $q2->num_rows() > 0) $telat_pinjam = (int) $q2->row()->jml;

		if ($is_sa) {
			$q3 = $CI->db->query("SELECT COUNT(*) AS jml FROM arsip_peminjaman WHERE status_pinjam IN ('Dipinjam','Ditolak') AND divalidasi_superadmin = 0");
			if ($q3 && $q3->num_rows() > 0) $unvalidated_sa = (int) $q3->row()->jml;
		}
	}

	if ($CI->db->table_exists('arsip_disposisi')) {
		$q4 = $CI->db->query("SELECT COUNT(*) AS jml FROM arsip_disposisi WHERE status_tl = 'Menunggu'");
		if ($q4 && $q4->num_rows() > 0) $pending_disp = (int) $q4->row()->jml;
	}

	if ($pending_akun > 0) {
		$items[] = array(
			'icon' => 'fa-user-plus text-orange',
			'text' => $pending_akun . ' registrasi akun baru menunggu ACC Super Admin',
			'link' => 'filing/persetujuan_akun?status=pending'
		);
	}
	if ($pending_pinjam > 0) {
		$items[] = array(
			'icon' => 'fa-clock-o text-yellow',
			'text' => $pending_pinjam . ' peminjaman arsip menunggu konfirmasi',
			'link' => 'filing/peminjaman?status=Menunggu'
		);
	}
	if ($telat_pinjam > 0) {
		$items[] = array(
			'icon' => 'fa-exclamation-triangle text-red',
			'text' => $telat_pinjam . ' peminjaman arsip terlambat dikembalikan',
			'link' => 'filing/peminjaman?status=Telat'
		);
	}
	if ($unvalidated_sa > 0) {
		$items[] = array(
			'icon' => 'fa-shield text-aqua',
			'text' => $unvalidated_sa . ' verifikasi peminjaman perlu kontrol Super Admin',
			'link' => 'filing/peminjaman'
		);
	}
	if ($pending_disp > 0) {
		$items[] = array(
			'icon' => 'fa-share-square-o text-blue',
			'text' => $pending_disp . ' disposisi menunggu tindak lanjut (TL)',
			'link' => 'filing/disposisi?status=Menunggu'
		);
	}

	return array(
		'total' => $pending_akun + $pending_pinjam + $telat_pinjam + $unvalidated_sa + $pending_disp,
		'pending_akun' => $pending_akun,
		'pending_pinjam' => $pending_pinjam,
		'telat_pinjam' => $telat_pinjam,
		'unvalidated_sa' => $unvalidated_sa,
		'pending_disp' => $pending_disp,
		'items' => $items
	);
}

function get_tracking_cookie() {
	static $cached_id = null;
	if ($cached_id !== null) {
		return $cached_id;
	}

	if (!empty($_COOKIE['efil_track_id']) && preg_match('/^DEV-[A-Za-z0-9]{12,32}$/', (string) $_COOKIE['efil_track_id'])) {
		$cached_id = (string) $_COOKIE['efil_track_id'];
		return $cached_id;
	}

	try {
		$rand = strtoupper(bin2hex(random_bytes(8)));
	} catch (Exception $e) {
		$rand = strtoupper(substr(md5(uniqid((string) mt_rand(), true)), 0, 16));
	}
	$cached_id = 'DEV-'.$rand;

	if (!headers_sent()) {
		$expire = time() + (86400 * 365);
		@setcookie('efil_track_id', $cached_id, $expire, '/', '', FALSE, TRUE);
	}
	$_COOKIE['efil_track_id'] = $cached_id;
	return $cached_id;
}

function audit_log($modul, $aksi, $objek = '', $detail = '', $override_user = null) {
	try {
		$CI =& get_instance();
		$CI->load->database();
		if (!$CI->db->table_exists('arsip_audit_log')) {
			return false;
		}

		date_default_timezone_set('Asia/Jakarta');
		$device_id = get_tracking_cookie();

		$id_peg = $CI->session->userdata('id_pegawai');
		$username = $CI->session->userdata('username');
		$nama_user = $CI->session->userdata('nama');
		$nama_role = $CI->session->userdata('nama_role');

		if ($CI->session->userdata('login_state') === 'root') {
			$id_peg = 0;
			$username = 'nimda';
			$nama_user = 'Root Administrator';
			$nama_role = 'Root';
		}

		if (is_array($override_user)) {
			if (array_key_exists('id_pegawai', $override_user)) $id_peg = $override_user['id_pegawai'];
			if (array_key_exists('username', $override_user)) $username = $override_user['username'];
			if (array_key_exists('nama_user', $override_user)) $nama_user = $override_user['nama_user'];
			if (array_key_exists('nama_role', $override_user)) $nama_role = $override_user['nama_role'];
		}

		$ip = method_exists($CI->input, 'ip_address') ? (string) $CI->input->ip_address() : (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');
		$ua = isset($_SERVER['HTTP_USER_AGENT']) ? substr((string) $_SERVER['HTTP_USER_AGENT'], 0, 250) : '';
		$url = function_exists('uri_string') ? substr((string) uri_string(), 0, 250) : '';

		if (is_array($detail)) {
			$detail = json_encode($detail, JSON_UNESCAPED_UNICODE);
		}

		$row = array(
			'waktu' => date('Y-m-d H:i:s'),
			'id_pegawai' => is_numeric($id_peg) ? (int) $id_peg : null,
			'username' => !empty($username) ? (string) $username : 'guest',
			'nama_user' => !empty($nama_user) ? trim((string) $nama_user) : (!empty($username) ? (string) $username : 'Tamu'),
			'nama_role' => !empty($nama_role) ? (string) $nama_role : '-',
			'modul' => (string) $modul,
			'aksi' => strtoupper((string) $aksi),
			'objek' => substr((string) $objek, 0, 195),
			'detail' => (string) $detail,
			'ip_address' => substr($ip, 0, 60),
			'device_cookie' => $device_id,
			'user_agent' => $ua,
			'url_path' => $url
		);

		$CI->db->insert('arsip_audit_log', $row);

		// Update jejak cookie aktivitas terakhir (sekunder; log utama permanen di DB)
		if (!headers_sent()) {
			$trace_payload = base64_encode($row['username'].'|'.$row['aksi'].'|'.$row['modul'].'|'.$row['waktu']);
			@setcookie('efil_last_trace', $trace_payload, time() + (86400 * 30), '/', '', FALSE, TRUE);
		}

		return true;
	} catch (Exception $e) {
		return false;
	}
}

	// ./efiling
	
?>