<?php
    class Tnde extends CI_Controller{
        var $upload_dir;
        var $menu;
        var $menu_surat;
        var $max_list=4;
        var $site_url;
    public function __construct()
    {
        parent::__construct();
        $this->benchmark->mark('mydata');
        $this->load->model('tnde/mydata');
        $this->load->helper(array('cmd','msg','form','url','html','text','general','cookie'));
        $this->upload_dir='./'.upload_folder();
//        $this->load->library('session');
        $this->load->library('uri_ret');
        setsesi('debug_mem',1);
        setsesi('debug',0);if(sesi('debug'))setsesi('sql','');
        $this->load->model('tnde/cmenu');
        $this->menu= $this->cmenu->get_menu(islogin());
//        kayaknya ini bikin lambat, coba di cek deh
        if($this->menu_surat= $this->cmenu->get_navsurat(islogin())){
//            ambil info jumlah sur masuk
            $this->menu_surat[10]=(tesnull($this->menu_surat[0])?$this->mydata->get_info_ql($this->menu_surat[0]):0);
//            ambil info jumlah keluar
            $this->menu_surat[11]=(tesnull($this->menu_surat[1])?$this->mydata->get_info_ql($this->menu_surat[1]):0);
        }
        $this->benchmark->mark('e_mydata');
//        $this->load->helper('cookie');
        $this->site_url=$this->uri->segment(1)."/".$this->uri->segment(2)."/".$this->uri->segment(3);
   }

    function index(){
        
        setsesi('uri0',uri_string());
        if(islogin()){
            $dt['c']="home";
            $this->load->view(myctrl().'sim_view',$dt);    
        }else{
            redirect('login/role_choice');//'login''
        }
      }

    function login(){
        if(islogin()){redirect('/');}else{
//          $this->load->view(myctrl().'template/login');
          redirect('/');
        }
      }

    function form($cmd,$no_listr,$where="0",$backuri="",$filter=""){
        $this->load->helper('tinymce');
        if($this->notlogin(uri_string()))redirect(myctrl()."login");
        if(empty($cmd)||empty($no_listr))redirect("/");  
        
        $pwhere="";
        if('0'!=$where)$pwhere=$this->mydata->convert_param_filter($no_listr,$this->uri_ret->get_dec($where),false);                  
        $dt['dtc']=$this->mydata->get_tbl_form($no_listr,(2 == $cmd?$pwhere:""));
        
        if((1==$cmd)&&((404==$no_listr)||(13==$no_listr))){
              //upload
               $dt['dtc']['td']=$pwhere;
               $dt['dtc']['td']['path']='';//$dt['dtc']['td']['id_surat'];
               if($pwhere)foreach ($pwhere as $x => $valu) $dt['dtc']['atr']['tf'][$x]='h';
          }elseif(2==$cmd){
                $dt['dtc']['td']=$dt['dtc']['td'][0];
          }
          
          $dt['dtc']['h2']=($cmd==1?"Tambah":"Ubah")." Data ".
              (!empty($this->mydata->da_listr($no_listr,'h2'))
              ?$this->mydata->da_listr($no_listr,'h2')
              :''.chg2_($this->mydata->da_listr($no_listr,'n')))
          ;

          $dt['dtc']['cmd']=$cmd;
          $dt['dtc']['notabel']=$no_listr;
          $dt['dtc']['mustshow']=tesnull($this->mydata->da_listr($no_listr,'mustshow'));
//          $dt['dtc']['pake_act']=$tes_act;
//          $dt['nologinp']=true;
          $dt['c']="form";
//          setsesi('uri1',uri_string());
          setsesi('validities',$this->mydata->get_formvalidities($no_listr));
          $dt['dtc']['last']=$myctrl().$this->uri_ret->glastenc($backuri);
          if($filter)$filter=$this->uri_ret->get_dec($filter);
          $dt['dtc']['filter']=$this->mydata->convert_param_filter($no_listr,$filter,0,1); 
          if((1==$cmd)&&(tesnull($filter))){
              $nod=$this->mydata->da_listr($no_listr,'t');
              $fl=$this->mydata->fl($nod);
              foreach ($dt['dtc']['filter'] as $x => $valu) {
                $dt['dtc']['atr']['tf'][$x]='h';
                $dt['dtc']['td'][$fl[$x]]=$valu;
              }
          }
          $dt['dtc']['me']=$this->uri_ret->add(uri_string());
          $this->load->view(myctrl().'sim_view',$dt);
      }

    function send($data){
        if(!$data)redirect("/");  
        switch($data){
            case'login':
                if($login=$this->mydata->get_user(retpost('username'),retpost('pass'))){
                    setsesi(array_merge_recursive($login,array('uri'=>base_url())));//todo + tahun ini
                    if(1==retpost('ajax')){echo '1';                        
                    }else{$redi='/';
                    }                    
                }else{
                    if(1==retpost('ajax')){
                        echo '0';
//                        show_cek($login);                        
                    }else{
                        setflashsesi('e', msg('elogin'));  
                        $redi='login';
                    }
                }
                break;
            case "out":
                unsesi(array('login'=>'','nip'=>'','level'=>'','sql'=>''));
                $this->session->sess_destroy();
                $redi='/';
                break;  
            case "kill":
                if(islogin()){
                    $dt['id']=$this->uri->segment(4);
                    $dt['notabel']=$this->uri->segment(3);
                    $ret['last']=$this->uri_ret->glastenc($this->uri->segment(5));
                    if($this->mydata->hapusz($dt)){
                       setflashsesi('e',sprintf(msg('greeny'),'id:'.$dt['id']));
                        if(1==retpost('ajax')){
                            echo '1';
                        }else{
                           $redi=$ret['last']; 
                        }
                    }else{
                        setflashsesi('e',sprintf("Maaf data %s tidak dapat dihapus karena telah digunakan",'no.'.$dt['id']));
                       if(1==retpost('ajax')){
                            echo '0';                        
                       }else{
                           $redi=$ret['last']; 
                       }
                    }
                    
                }
                break;  
            case "data":
                if($this->notlogin('/'))redirect(myctrl()."login");
                $ap=retpost(null);
                $retn=$ap['ret'];
                $nof=$this->mydata->find_atr_tf_da('f',$ap['notabel']);
                if (($nof)&&($_FILES[$nof]['name']!="")){
                    $cfg=array(
                        'upload_path'=> $this->upload_dir,
                        'allowed_types'=> 'pdf|gif|png|bmp|tif|jpg|jpeg',
                        );
                    if(23 == $ap['notabel']){
                        $cfg['overwrite']=true;
                        $cfg['file_name']='logo'.$ap[$ap['pk']].'.jpg';
                    }
                    
                    $this->load->library('upload', $cfg);
                        
                    if ( ! $this->upload->do_upload($nof))
                    {
                        setflashsesi('e',msg('simpannotok')." ".$this->upload->display_errors());
                        break;  
                    }
                    else
                    {
                        $data = $this->upload->data();
                        $ap[$nof]=$data['file_name'];
                    }
                }
                if($a=$this->mydata->find_atr_tf_da('a',$ap['notabel']))$ap[$a]=htmlentities($ap[$a]);
                
                $v=$this->mydata->ins_upd($ap);
                setflashsesi('e',msg('greeny'));
                $redi=$retn;
                
                break;  
            case "memo":
            //todo -o me:simpan dan update memo
                $ap=retpost(null);
                
//                show_cek(array('sisa'=>$sisa_next_bag,'ap'=>$ap));
//die();
                //simpan id_pos td utk update    
                $u=array(
                    4=>2,
                    7=>tesnull($ap['repl']),
                    date('Y-m-d'),
                );
                $this->mydata->insupdd(28,$u,2,array($ap['kod_id']));
                // new cc
                $u_cc=array();
                if(isset($ap['person_cc'])&&(is_array($ap['person_cc']))){
                foreach ($ap['person_cc'] as $x => $valu)
                    $u_cc[]=array('id_surat'=>$ap['id_surat'],'id_bag'=>$valu);
                $this->db->delete('t_carbon_copy',array('id_surat'=>$ap['id_surat']));
                $this->db->insert_batch('t_carbon_copy',$u_cc);
                }
                 // e - new cc
                if((tesnull($ap['next_bag_up'])) && (0==$ap['next_bag_up'][count($ap['next_bag_up'])-1]))unset($ap['next_bag_up'][count($ap['next_bag_up'])-1]);
                if((tesnull($ap['next_bag_dn'])) && (0==$ap['next_bag_dn'][count($ap['next_bag_dn'])-1]))unset($ap['next_bag_dn'][count($ap['next_bag_dn'])-1]);
                
                switch($ap['keputusan']){
                    case 2:
                        $ap['keputusan']=1;
                        $ap['next_bag']=(2==$ap['pil']?$ap['next_bag_dn']:$ap['next_bag_up']);
                        $ap['memo']=(1==$ap['pil']?$ap['memo_up']:'');
                        $ap['next_arah']=$ap['pil'];
                        break;
                    case 4:
                    case 5:
                        $ap['next_bag'][0]=0;
                        $ap['memo'][0]=$ap['memoputus'];
                        $ap['next_arah']=0;
                    //if(4==$ap['keputusan'])
                    //cek bidang yang laen masih ada nda
                        $sisanext=0;
                        if(
                        $sisa_next_bag=$this->db->select_sum('kode')
                            ->from('vi_last_posisi_surat_group_bag')
                            ->where(array('id_surat'=>$ap['id_surat'],'kode'=>1,'next_id_bag <>'=>(1==islogin()?$ap['sbg']:sesi('id_bag'))))
                            ->get()
                            ->row())$sisanext=$sisa_next_bag->kode;
                        //e-cek bidang yang laen masih ada nda
                        if(!$sisanext)$this->mydata->insupdd(21,array(
                            10=>sesi('id_bid'),
                            15=>$ap['keputusan'],
                        ),2,array($ap['id_surat']));
                        break;
                }
//                $nxt=array(array('next_bag_up','memo_up'),array('next_bag_dn','memo_dn'));
//                foreach ($nxt as $x0 => $valu0) {
//                  show_cek($ap);die();
              foreach ($ap['next_bag'] as $x => $valu){
                    $cek_insert=array(
                            1=>$ap['id_surat'],
                            (1==islogin()?$ap['sbg']:sesi('id_bag')),
                            (1==$ap['pil']
                                ?$ap['memo_up'][$x]
                                :(2==$ap['pil']
                                    ?implode_memo_dispo(array(
                                        'memo_dn'=>$ap['memo_dn'][$x],
                                        'pilih'=>@$ap['pilih'][$x],
                                        'memo1'=>$ap['memo1'][$x],
                                        'memo2'=>$ap['memo2'][$x],
                                    ))
                                    :$ap['memo'][$x])
                                ),
                            $ap['keputusan'],
                            $valu,
                            sesi('nip'),
                            10=>$ap['next_arah'],
                        );
                    if($r=$this->mydata->insupdd(28,$cek_insert)){
                            $ap['cekr'][]=$r;
                            $r2=$this->mydata->insupdd(37,array(
                                1=>$ap['kod_id'],
                                $r,
                                sesi('nip')
                                ),1);
                            $ap['cekr2'][]=$r2;
                    }else{
                        echo "err"; show_cek(array('ap'=>$ap,'ins'=>$cek_insert,'r'=>$r));die();
                    }
                }
//                }
//                  show_cek($ap);die();
             if(1==tesnull($ap['ajax'])){
                    echo '1';
               }else{
                   $redi=(tesnull($ap['lanjut_sk'])?myctrl().'surat_keluar/0/'.$this->uri_ret->add(myctrl().'list_surat/214011/'.(1==islogin()?$ap['sbg']:sesi('id_bag'))).'/'.(1==islogin()?$ap['sbg']:sesi('id_bag')).'/0/0/'.$ap['id_surat']:$ap['ret']); 
               }
               //todo -o me:log ?
                break;
            case "meymo":
                if(islogin()){
                    $ap=retpost(null);
                    if($ap['pesan']!=''){
                        $v=$this->mydata->ins_upd($ap);
                        setflashsesi('e',msg('greeny'));
                    }
                    $redi=$ap['ret'];
                }
                break;  
            case "nomor":
                if(islogin()){
                    $ap=retpost(null);
                    if($ap['noid']){
                        $this->mydata->insupdd(21,array(
                        3=>$ap['no_sur'],
                        15=>9,
                        ),2,array($ap['noid']));
                        if($ap['id_posisi']){
                            //update posisi
                        $this->mydata->insupdd(29,array(4=>2),2,array($ap['id_posisi']));
                            //ins posisi
                        $ap['ins']=array(
                        1=>$ap['noid'],
                        (tesnull($ap['sbg'])?$ap['sbg']:sesi('id_bag')),
                        4=>9,
                        0,
                        sesi('nip'),
                        10=>0);
                        $this->mydata->insupdd(29,$ap['ins'],1);
//                         show_cek($ap);die();
                        }
                        setflashsesi('e',msg('greeny'));
                    }
                    if(1==tesnull($ap['ajax'])){
                        echo '1';
                    }else{
                       $redi=$ap['ret']; 
                    }
                }
                break;  
            case "lev":
                if(islogin()){
                    $ap=retpost(null);
                    if(0==$ap['dn'][count($ap['dn'])-1])unset($ap['dn'][count($ap['dn'])-1]);
                    if(0==$ap['up'][count($ap['up'])-1])unset($ap['up'][count($ap['up'])-1]);
                    if(0==$ap['dnk'][count($ap['dnk'])-1])unset($ap['dnk'][count($ap['dnk'])-1]);
                    if(0==$ap['upk'][count($ap['upk'])-1])unset($ap['upk'][count($ap['upk'])-1]);
                    $ap['ser']=htmlentities(serialize(array(
                        'dn'=>$ap['dn'],
                        'up'=>$ap['up'],
                        'sm'=>$ap['buat_sm'],
                        'sk'=>$ap['buat_sk'],
                        'dnk'=>$ap['dnk'],
                        'upk'=>$ap['upk'],
                        'vm'=>$ap['vm'],
                        'nsk'=>$ap['nsk'],
                        'vsk'=>$ap['vsk'],
                        'cm'=>$ap['cm'],
                        'ck'=>$ap['ck'],
                        )));
//                    show_cek(array($ap,unserialize($t)));die();
                    $ins=array(
                                1=>$ap['lev'],
                                $ap['bid'],
                                $ap['ser'],
                                );
                    if(1==$ap['cmd']){
                        $sql="SELECT MAX(urutan)+1 FROM ref_bagian";
                        if($rr=$this->mydata->get1rowq($sql)){
                            $ins[4]=$rr[0];
                        }
                        $this->mydata->insupdd(27,$ins,1);   
                    }else{
                        $this->mydata->insupdd(27,$ins,2,array($ap['nopkid_level']));   
                    }
                    if(1==tesnull($ap['ajax'])){
                        echo '1';               
//                        show_cek(array('ap'=>$ap,'ins'=>$ins));                        
         
                    }else{
                        $redi=$ap['ret'];
                    }                    
                    
                }
                break;  
            case "sm":
                if(islogin()){
                    $ap=retpost(null);
                    if(1==$ap['cmd'])$ap['cuser']=sesi('nip');
                    if(islogin() > 1)$ap['bag']=sesi('id_bag');
                    
                    if (($_FILES)&&($_FILES['filex']['name']!="")){
                        $plus='sm'.preg_replace('/-/','',xtgl($ap['tgl_sur'],'/',0));//time();
                        $foldr=($this->upload_dir.$plus);
                        if (!file_exists($foldr))mkdir($foldr, 0777,true);
                        $cfg=array(
                            'upload_path'=> $foldr,
                            'allowed_types'=> 'pdf|gif|png|bmp|tif|jpg|jpeg',
                            );
//                         show_cek($cfg);die();
                        $this->load->library('upload', $cfg);
                        $this->upload->initialize($cfg);
                        if ( ! $this->upload->do_upload('filex'))
                        {
                            setflashsesi('e',msg('simpannotok')." ".$this->upload->display_errors());
                            echo 'errhor: '.msg('simpannotok')." ".$this->upload->display_errors()
                            .base64_encode(array('ap'=>$ap,'cfg'=>$cfg,'tes'=>is_dir($cfg['upload_path'])));die();
                            break;  
                        }
                        else
                        {
                            $data = $this->upload->data();
                            $ap['filex']=$data['file_name'];
                            $ap['path']=$plus;
                            
                        }
                    }else{
                        if(tesnull($ap['filex']))unset($ap['filex']);
                    }
                    $ap['tgl_sur']=xtgl($ap['tgl_sur'],'/');
                    $ap['tgl_terima']=xtgl($ap['tgl_terima'],'/');
                    if((tesnull($ap['next_bag_up'])) && (0==$ap['next_bag_up'][count($ap['next_bag_up'])-1]))unset($ap['next_bag_up'][count($ap['next_bag_up'])-1]);
                    $ap['next_bag']=$ap['next_bag_up'];
                    $ap['memo']=$ap['memo_up'];
//                    $ap['next_arah']=1;

//                    show_cek($ap);die();
                    $tes=$this->mydata->simpan_surat_masuk($ap);
//                    show_cek(array($ap,$tes));die();
                    setflashsesi('e',msg('greeny'));
                    if(1==retpost('ajax')){
                        echo '1';                        
                    }else{
                        $redi=retpost('ret');
                    }                    
                    setsesi('return_sm',$tes);
                }
                break;  
            case "sk":
                if(islogin()){
                    $ap=retpost(null);
                    $ap['id_surat']=$ap['noid'];
                    if(1==$ap['cmd'])$ap['cuser']=sesi('nip');
                    
                    if(islogin() > 1){
                        $ap['bag']=sesi('id_bag');   
                        $ap['id_bid']=sesi('id_bid');   
                    }
/*                  if(count(sesiaturan('upk'))>0){
                        $ap['next_bag']=$ap['next_bag_up'];
                        $ap['memo']=tesnull($ap['memo_up']);
                        $ap['next_arah']=$ap['next_arah_up'];
                    }elseif(count(sesiaturan('dnk'))>0){
                        $ap['next_bag']=$ap['next_bag_dn'];
                        $ap['memo']=tesnull($ap['memo_dn']);
                        $ap['next_arah']=$ap['next_arah_dn'];
                    }
*/           
//else{
            if(2==$ap['cmd']){
                $u=array(
                    4=>2,
                    7=>tesnull($ap['repl']),
                    date('Y-m-d'),
                );
                $this->mydata->insupdd(29,$u,2,array($ap['id_posisi']));
                
                switch($ap['keputusan']){
                    case 2:
                        $ap['keputusan']=1;
                        if(5==$ap['pil']){
                            $ap['next_bag']=$ap['next_bag_nom'];
                            $ap['memo']=$ap['memo_nom'];
                            $ap['next_arah']=1;
                        }else{
                            $ap['next_bag']=(4==$ap['pil']?$ap['next_bag_dn']:$ap['next_bag_up']);
                            $ap['memo']=(4==$ap['pil']?$ap['memo_dn']:$ap['memo_up']);
                            $ap['next_arah']=(4==$ap['pil']?2:1);
                        }
                        // new cc
                        $u_cc=array();
                        if((isset($ap['person_cc']))&&(count($ap['person_cc'])>0)){
                        foreach ($ap['person_cc'] as $x => $valu)
                            $u_cc[]=array('id_surat'=>$ap['id_surat'],'id_bag'=>$valu);
                        $this->db->delete('t_carbon_copy',array('id_surat'=>$ap['id_surat']));
                        $this->db->insert_batch('t_carbon_copy',$u_cc);
                        }
                         // e - new cc
                        break;
                    case 4:
                        $ap['next_bag'][0]=0;
                        $ap['memo'][0]=$ap['memoputus'];
                        $ap['next_arah']=0;
                        $this->mydata->insupdd(21,array(
                            15=>$ap['keputusan'],
                        ),2,array($ap['noid']));
                        break;
                }
//                  show_cek($ap);die();
              foreach ($ap['next_bag'] as $x => $valu){
                    if($r=$this->mydata->insupdd(29,array(
                            1=>$ap['noid'],
                            (1==islogin()?$ap['sbg']:sesi('id_bag')),
                            $ap['memo'][$x],
                            $ap['keputusan'],
                            $valu,
                            sesi('nip'),
                            10=>$ap['next_arah'],
                        ),1)){
                            $ap['cekr'][]=$r;
                            $r2=$this->mydata->insupdd(47,array(
                                1=>$ap['id_posisi'],
                                $r,
                                sesi('nip')
                                ),1);
                            $ap['cekr2'][]=$r2;
                    }else{
                        echo "err"; show_cek($ap);die();
                    }
                }

              }else{
                if($ap['jm_up']>0){
                            $ap['next_bag']=$ap['next_bag_up'];
                            $ap['memo']=tesnull($ap['memo_up']);
                            $ap['next_arah']=1;
                }elseif(count($ap['next_bag_nom'])>0){
                            $ap['next_bag']=$ap['next_bag_nom'];
                            $ap['memo']=tesnull($ap['memo_nom']);
                            $ap['next_arah']=1;
                }elseif($ap['jm_dn']>0){
                            $ap['next_bag']=$ap['next_bag_dn'];
                            $ap['memo']=tesnull($ap['memo_dn']);
                            $ap['next_arah']=2;
                }
              }
//                       show_cek($ap);die();
                       
//                    }

                    $ap['tgl_sur']=xtgl($ap['tgl_sur'],'/');
//new 15-1-2015                    
                $nof='lampiran';
             if ($_FILES[$nof]['name']!=""){
                    $plus='lam_sk'.preg_replace('/-/','',$ap['tgl_sur']);
                     $foldr=($this->upload_dir.$plus);
                        if (!file_exists($foldr))mkdir($foldr, 0777,true);
                    $cfg=array(
                        'upload_path'=> $foldr,
                        'allowed_types'=> 'pdf|gif|png|bmp|tif|jpg|jpeg',
                        );
                    $this->load->library('upload', $cfg);
                    $this->upload->initialize($cfg);    
                    if ( ! $this->upload->do_upload($nof))
                    {
                        setflashsesi('e',msg('simpannotok')." ".$this->upload->display_errors());
                        echo 'errhor: '.msg('simpannotok')." ".$this->upload->display_errors()
                            .base64_encode(array('ap'=>$ap,'cfg'=>$cfg,'tes'=>is_dir($cfg['upload_path'])));die();
                        break;  
                    }
                    else
                    {
                        $data = $this->upload->data();
                        $ap[$nof]=$data['file_name'];
                    }
                }
//e- new 15-1-2015

//                    show_cek($ap);die();
                    if($tes=$this->mydata->simpan_surat_keluar($ap)){
                        setflashsesi('e',msg('greeny'));
                    }
//                    show_cek(array($ap,$tes));die();
                    if(1==retpost('ajax')){
                        echo '1';
                    }else{
                        $redi=retpost('ret');
                    }                    
                    setsesi('return_sk',$tes);
                }
                break;  
            case "ajax":
                if($_POST){
                    $ap=retpost(null);
                    $nof=$this->mydata->find_atr_tf_da('f',$ap['notabel']);
                    if($nof){
                        setflashsesi('e',msg('rederr'));
                        echo '0';
                    }else{
                        $tes=$this->mydata->ins_upd($ap);
                        setflashsesi('e',msg('greeny'));
                        echo '1';
//                        show_cek(array($ap,$tes));
                    }
                   return true;  
                 }
                break;   
            default:
                setflashsesi('e',msg('1404'));
                //exit("Erorr 404, File Not Found");              
          }
          if (isset($redi)) redirect(($redi?$redi:"/"), 'refresh');    
    }
    
    function list_it($no_listr,$offset=0,$where="",$backuri=""){
        if($this->notlogin(uri_string()))redirect(myctrl()."login");
        if(empty($no_listr))redirect("/");//exit('must fill with number');
        $dt['breadcrumb'] = tesnull($this->mydata->da_listr($no_listr,'breadcrumb'));
        //start ?        
        $pstart=array($offset,$this->max_list);
        //where 
        $pwhere="";
        if($where){
            $where=$this->uri_ret->get_dec($where);
            $pwhere=$this->mydata->convert_param_filter($no_listr,$where);   
        }
        //filter
        $formatstgl = array("d-m-Y", "d/m/Y");
        if($retpost=retpost(null)){
            if(!empty($retpost['f']))setsesi('filter_list'.$no_listr,$retpost);
            if(!empty($retpost['r'])){
                $rp['f']=0;
                $rp['q']='';
                setsesi('filter_list'.$no_listr,'');
            }
        }else{
            $retpost=sesi('filter_list'.$no_listr);
        }
        $rp=$retpost;
        if(tesnull($rp['q']))
        foreach ($formatstgl as $fx =>$format)
            if($date = DateTime::createFromFormat($format, $rp['q'])){
                $rp['q']=tgl2sql($retpost['q']);
        }
        //loads
        $dt['c']="list";
        if($dt['dtc']=$this->mydata->get_ql($no_listr,$pstart,$pwhere,'',$rp)){
            //paging
            $pg=array(
                'base_url'=> site_url($this->site_url),
                'total_rows'=> tesnull($dt['dtc']['jma']),
                'per_page'=> $this->max_list,
                'uri_segment'=> '4',
                'first_url'=>0,
            );
            $this->pagination->initialize($pg);
            $dt['dtc']['paging']=$this->pagination->create_links();
            $dt['dtc']['filter_like']=$retpost;
        }
        /*$dt['noadd']=tesnull($this->mydata->da_listr($no_listr,'noadd'));
        $dt['maxql']=tesnull($this->mydata->da_listr($no_listr,'maxql'));
        $dt['dtc']['mustshow']=tesnull($this->mydata->da_listr($no_listr,'mustshow'));
        setsesi('uri9','');setsesi('uri0',uri_string());setsesi('uri1',uri_string());*/
        $dt['dtc']['listr']=tesnull($this->mydata->da_listr($no_listr));
        $dt['dtc']['notabel']=$no_listr;
        $dt['dtc']['h2']=tesnull($dt['dtc']['listr']['h2'],'List Data '.chg2_($this->mydata->da_listr_n($no_listr)));
        $dt['dtc']['startno']=$pstart[0];
        
        $var=array('nofilter','noadd','maxql','mustshow','ff','ql');
        foreach ($var as $valu) $dt['dtc']['listr'][$valu]=tesnull($dt['dtc']['listr'][$valu]);
        
        $testh=tesnull($dt['dtc']['th']);
        $dt['dtc']['fl']=(!$dt['dtc']['listr']['ql'] && $dt['dtc']['listr']['ff'] && $testh?array_replace($testh,$dt['dtc']['listr']['ff']):$testh);
        $dt['dtc']['segw']=$where;
        $dt['dtc']['psegw']=explode(':',$where);
        $dt['dtc']['last']=$this->uri_ret->glastenc($backuri);
        $dt['dtc']['me']=$this->uri_ret->add(uri_string());

        switch($no_listr){
            case 5://13
                $nambah_param="/".((count($dt['dtc']['psegw'])>1)?$this->uri_ret->get_enc("1:".$dt['dtc']['psegw'][1]):"");
                break;
            default:
                $nambah_param='';
        }
        $dt['dtc']['nambah_param']=$dt['dtc']['me'].$nambah_param;
//        if(211011==$no_listr)$dt['dtc']['listr']['lplus']['link'].= '/0/'.$dt['dtc']['me'];
        return $dt;
    }

    function lister($no_listr,$offset=0,$where="",$backuri=""){
        if($this->notlogin(uri_string()))redirect(myctrl()."login");
        if(empty($no_listr))redirect("/");//exit('must fill with number');
        $dt['breadcrumb'] = tesnull($this->mydata->da_listr($no_listr,'breadcrumb'));
        //start ?        
        $pstart=array($offset,$this->max_list);
        //where 
        $pwhere="";
        if($where){
            $where=$this->uri_ret->get_dec($where);
            $pwhere=$this->mydata->convert_param_filter($no_listr,$where);   
        }
        //filter
        $formatstgl = array("d-m-Y", "d/m/Y");
        if($retpost=retpost(null)){
            if(!empty($retpost['f']))setsesi('filter_list'.$no_listr,$retpost);
            if(!empty($retpost['r'])){
                $rp['f']=0;
                $rp['q']='';
                setsesi('filter_list'.$no_listr,'');
            }
        }else{
            $retpost=sesi('filter_list'.$no_listr);
        }
        $rp=$retpost;
        if(tesnull($rp['q']))
        foreach ($formatstgl as $fx =>$format)
            if($date = DateTime::createFromFormat($format, $rp['q'])){
                $rp['q']=tgl2sql($retpost['q']);
        }
        //loads
        $dt['c']="list";
        if($dt['dtc']=$this->mydata->get_ql($no_listr,$pstart,$pwhere,'',$rp)){
            //paging
            $pg=array(
                'base_url'=> site_url($this->site_url),
                'total_rows'=> tesnull($dt['dtc']['jma']),
                'per_page'=> $this->max_list,
                'uri_segment'=> '4',
                'first_url'=>0,
            );
            $this->pagination->initialize($pg);
            $dt['dtc']['paging']=$this->pagination->create_links();
            $dt['dtc']['filter_like']=$retpost;
        }
        /*$dt['noadd']=tesnull($this->mydata->da_listr($no_listr,'noadd'));
        $dt['maxql']=tesnull($this->mydata->da_listr($no_listr,'maxql'));
        $dt['dtc']['mustshow']=tesnull($this->mydata->da_listr($no_listr,'mustshow'));
        setsesi('uri9','');setsesi('uri0',uri_string());setsesi('uri1',uri_string());*/
        $dt['dtc']['listr']=tesnull($this->mydata->da_listr($no_listr));
        $dt['dtc']['notabel']=$no_listr;
        $dt['dtc']['h2']=tesnull($dt['dtc']['listr']['h2'],'List Data '.chg2_($this->mydata->da_listr_n($no_listr)));
        $dt['dtc']['startno']=$pstart[0];
        
        $var=array('nofilter','noadd','maxql','mustshow','ff','ql');
        foreach ($var as $valu) $dt['dtc']['listr'][$valu]=tesnull($dt['dtc']['listr'][$valu]);
        
        $testh=tesnull($dt['dtc']['th']);
        $dt['dtc']['fl']=(!$dt['dtc']['listr']['ql'] && $dt['dtc']['listr']['ff'] && $testh?array_replace($testh,$dt['dtc']['listr']['ff']):$testh);
        $dt['dtc']['segw']=$where;
        $dt['dtc']['psegw']=explode(':',$where);
        $dt['dtc']['last']=$this->uri_ret->glastenc($backuri);
        $dt['dtc']['me']=$this->uri_ret->add(uri_string());

        switch($no_listr){
            case 5://13
                $nambah_param="/".((count($dt['dtc']['psegw'])>1)?$this->uri_ret->get_enc("1:".$dt['dtc']['psegw'][1]):"");
                break;
            default:
                $nambah_param='';
        }
        $dt['dtc']['nambah_param']=$dt['dtc']['me'].$nambah_param;
//        if(211011==$no_listr)$dt['dtc']['listr']['lplus']['link'].= '/0/'.$dt['dtc']['me'];
        
        $this->load->view(myctrl().'sim_view',$dt);//        setsesi('tes','');
    }

    function list_mod($no_listr,$offset=0,$where="",$backuri=""){
        if($this->notlogin(uri_string()))redirect(myctrl()."login");
        if(empty($no_listr))redirect("/");//exit('must fill with number');
        $dt['breadcrumb'] = tesnull($this->mydata->da_listr($no_listr,'breadcrumb'));
        //start ?        
        $pstart=array($offset,$this->max_list);
        //where 
        $pwhere="";
        if($where){
            $where=$this->uri_ret->get_dec($where);
            $pwhere=$this->mydata->convert_param_filter($no_listr,$where);   
        }
        //filter
        $formatstgl = array("d-m-Y", "d/m/Y");
//        cek(array(sesi('bambang'),sesi('filter_list'.$no_listr),retpost(null)));
        if($retpost=retpost(null)){
            if(isset($retpost['f'])){
                setsesi('filter_list'.$no_listr,$retpost);
            }elseif(isset($retpost['r'])){
                $rp['f']=0;
                $rp['q']='';
                setsesi('filter_list'.$no_listr,'');
            }
        }else{
            $retpost=sesi('filter_list'.$no_listr);
        }
//        setsesi('bambang',12345780);
//        cek(array(sesi('filter_list'.$no_listr),retpost(null),$retpost));
        $rp=$retpost;
        if(tesnull($rp['q']))
        foreach ($formatstgl as $fx =>$format)
            if($date = DateTime::createFromFormat($format, $rp['q'])){
                $rp['q']=tgl2sql($retpost['q']);
        }
            
        //loads
        $dt['c']="laporanlist";
        if($dt['dtc']['tabel']=$this->mydata->get_ql($no_listr,$pstart,$pwhere,'',$rp)){
            //paging
            $pg=array(
                'base_url'=> site_url($this->site_url),
                'total_rows'=> tesnull($dt['dtc']['jma']),
                'per_page'=> $this->max_list,
                'uri_segment'=> '4',
                'first_url'=>0,
            );
            $this->pagination->initialize($pg);
            $dt['dtc']['paging']=$this->pagination->create_links();
            $dt['dtc']['filter_like']=$retpost;
        }
        /*$dt['noadd']=tesnull($this->mydata->da_listr($no_listr,'noadd'));
        $dt['maxql']=tesnull($this->mydata->da_listr($no_listr,'maxql'));
        $dt['dtc']['mustshow']=tesnull($this->mydata->da_listr($no_listr,'mustshow'));
        setsesi('uri9','');setsesi('uri0',uri_string());setsesi('uri1',uri_string());*/
        $dt['dtc']['listr']=tesnull($this->mydata->da_listr($no_listr));
        $dt['dtc']['notabel']=$no_listr;
        $dt['dtc']['h2']=tesnull($dt['dtc']['listr']['h2'],'List Data '.chg2_($this->mydata->da_listr_n($no_listr)));
        $dt['dtc']['startno']=$pstart[0];
        
        $var=array('nofilter','noadd','maxql','mustshow','ff','ql');
        foreach ($var as $valu) $dt['dtc']['listr'][$valu]=tesnull($dt['dtc']['listr'][$valu]);
        
        $testh=tesnull($dt['dtc']['tabel']['th']);
        $dt['dtc']['fl']=(!$dt['dtc']['listr']['ql'] && $dt['dtc']['listr']['ff'] && $testh?array_replace($testh,$dt['dtc']['listr']['ff']):$testh);
        $dt['dtc']['segw']=$where;
        $dt['dtc']['psegw']=explode(':',$where);
        $dt['dtc']['last']=$this->uri_ret->glastenc($backuri);
        $dt['dtc']['me']=$this->uri_ret->add(uri_string());
        

        switch($no_listr){
            case 5://13
                $nambah_param="/".((count($dt['dtc']['psegw'])>1)?$this->uri_ret->get_enc("1:".$dt['dtc']['psegw'][1]):"");
                break;
            default:
                $nambah_param='';
        }
        $dt['dtc']['nambah_param']=$dt['dtc']['me'].$nambah_param;
//        if(211011==$no_listr)$dt['dtc']['listr']['lplus']['link'].= '/0/'.$dt['dtc']['me'];

        //modul kanan atas
        $dt['dtc']['modul_kanan']=array(
            'cetak'=>myctrl().'page/modul/tombolkananatas_cetak',
            'filter_tahun'=>myctrl().'page/modul/tombolkananatas_tahun',
        );
        
        $this->load->view(myctrl().'sim_view',$dt);//        setsesi('tes','');
    }

    function json($noj='',$prm=''){
        if(!empty($noj)){
            if($dt['json']=$this->mydata->json($noj,($prm?$prm:"")))$this->load->view(myctrl().'sim_json',$dt);
        }
    }

    function rpt($nor=1){
          $dt['dtc']=$this->mydata->get_ql(26);
          $dt['dtc']['h2']=$this->mydata->da_listr(26,'n');
          $dt['c']="f_rekening";
          setsesi('uri1',uri_string());
          $this->load->view(myctrl().'sim_view',$dt);
    }

    function ajaxr(){
        if($_POST){
            $ap=retpost(null);
            switch($ap['kode']){
                case 1://undo subag 
                    if(
                        //read t_disposisi
                        $r=$this->mydata->get1row_(37,array(1=>$ap['f1']))){
                            //del t_posisi_masuk
                            foreach ($r as $x => $valu) 
                                $this->mydata->insupdd(28,0,3,array($valu[2]));
                            //del t_disposisi
                            $this->mydata->insupdd(37,0,3,array(1=>$ap['f1']));
                            //update 
                            $this->mydata->insupdd(28,array(4=>1),2,array($ap['f1']));
                            $this->mydata->insupdd(21,array(
                                10=>'',
                                15=>1,
                            ),2,array($ap['f0']));
                            
                            echo '1';
                        }else{ echo '0'; }
                        
                        //todo -o me:log ?
                    break;
                    case 2:
                       echo ($this->mydata->up_down_lev($ap['f0'],$ap['f1'])?"1":"0");                   
                       break;

                case 3://undo agend (nomorin)
                    if(
                        //read t_disposisi_sk
                        $r=$this->mydata->get1row_(47,array(1=>$ap['f1']))){
                            //del t_posisi_keluar
                            foreach ($r as $x => $valu) 
                                $this->mydata->insupdd(29,0,3,array($valu[2]));
                            //del t_disposisi
//                            $this->mydata->insupdd(47,0,3,array($ap['f1']));
                            //update 
                            $this->mydata->insupdd(29,array(4=>1),2,array($ap['f1']));
                            $this->mydata->insupdd(21,array(
                                3=>'',
                                10=>'',
                                15=>1,
                            ),2,array($ap['f0']));
                            
                            echo '1';
                        }else{ 
                            echo '0'; 
                        }
                        show_cek($ap);
                        //todo -o me:log ?
                    break;

                default:
                    echo 'E';
            }
        }
        return false;
    }
    
    function report($data,$ids,$_uri4="",$backuri=""){
        if($this->notlogin(uri_string()))redirect(myctrl()."login");
        if(empty($data))redirect('/');
        $dt['breadcrumb'] = array('' => 'Surat Tugas','report/rpt_spt/39' => 'Laporan Surat Tugas');
//new 4m diklat
        setsesi('kecetak',serialize(retpost(null)));
        $dt=$this->open_rpt($data,$ids,retpost(null),$_uri4);
        $dt['dtc']['saya']=$_uri4;
        $dt['dtc']['angka']=$this->uri->segment(6);
/*        if(!sesi('uri9')) setsesi('uri9',sesi('uri0'));
        setsesi('uri0',uri_string());
        setsesi('uri1',uri_string());*/
        $dt['last']=$this->uri_ret->glastenc($backuri);
        if(substr($dt['last'],0,6)=='report'){
            $dt['me']=$backuri;
        }else{
            $dt['me']=$this->uri_ret->add(uri_string());
        }
        $this->load->view(myctrl().'sim_view',$dt);
     }

    function exp_word($noid,$kode=1){
        if($this->notlogin(uri_string()))redirect(myctrl()."login");
        $ap=retpost(null);
//        if(!$_POST)$ap=unserialize(sesi('kecetak'));
//        $dt=$this->open_rpt($data,$ids,$poz,$_uri4);
//        $dt['dtc']['dicetak']=1;
//        $dt['dtc']['angka']=$this->uri->segment(5);
        $dt['kop']=$this->mydata->get_ntable(7,'*',array(1));
        $dt['data']=$this->mydata->get_ntable(43,'*',array($noid));
        if($dt['bag']=$this->mydata->get_ntable(32,'*'))
        $dt['track']=($track?$this->mydata->get_ntable(48,'*',array(1=>$ids)):'');
        $dt['dg_kop']=1;
        $dt['cetak']=1;
        $this->load->model('tnde/creport');
        $rpt=$this->creport->report[1];//track
        $dt['c']=$rpt['dir'].$rpt['file'];//'page/view_surat_keluar_box';
        $this->load->view(myctrl().'sim_exp_word',$dt);
     }

    function cetak($data,$ids,$other=''){
        if($this->notlogin(uri_string()))redirect(myctrl()."login");
//        $poz=retpost(null);
//        if(!$_POST)$poz=unserialize(sesi('kecetak'));
        $dt=$this->open_rpt($data,$ids);
        if(3==$data){
            $dt['bagbid']=$this->db
                ->from('vi_bagian')
                ->where('id_lev <','7')
                ->order_by('level','asc')
                ->get();
//            show_cek($this->db->last_query());
        }elseif(4==$data){
//            cek(array(sesi('bambang'),sesi('filter_list'.$ids)));
            $formatstgl = array("d-m-Y", "d/m/Y");
            if(sesi('filter_list'.$ids))$rp=sesi('filter_list'.$ids);
            if(tesnull($rp['q']))
            foreach ($formatstgl as $fx =>$format)
                if($date = DateTime::createFromFormat($format, $rp['q'])){
                    $rp['q']=tgl2sql($retpost['q']);
            }
            $dt['h2']="Laporan Surat ".(723==$ids?"Masuk":"Keluar");
            $dt['tabel']=$this->mydata->get_ql($ids,'','','',$rp);
            $dt['listr']=tesnull($this->mydata->da_listr($ids));
            $dt['startno']=0;
            $testh=tesnull($dt['tabel']['th']);
            $dt['dtc']['fl']=(!$dt['listr']['ql'] && $dt['listr']['ff'] && $testh?array_replace($testh,$dt['listr']['ff']):$testh);
//            cek($dt['tabel']);
        }
        $dt['cetak']=1;
        $this->load->view(myctrl().'sim_cetak',$dt);
     }
     
     private function open_rpt($data,$ids){
         $this->load->model('tnde/creport');
/*         $dt['idsurat']=$ids;   
         $dt['c']=$data;*/
//         $dt['list']=(($dt['c']==$this->creport->report[7])||($dt['c']==$this->mydata->report[8]));
/*        $dt['list']=0;
        if($dt['list']){
            if(($dt['c']==$this->creport->report[1]))$prm1['iddpa']=$seg4;
        }else{
            $prm1=$seg4;
        }*/
        //if(!
        $dt=$this->creport->load_report($data,$ids);
        //)redirect('/');//show_404('page');
//        $dt['dtc']['pg']=$data;
//        $dt['dtc']['pg_nil']=$prm1;
//        $dt['dtc']['kodeview']=islogin();
//        $dt['dtc']['h2']='';
/*        if($dt['list']){
            $dt['maxql']=tesnull($this->mydata->da_listr($dt['idsurat'],'maxql'));                     
            $dt['dtc']['mustshow']=false;
            $dt['dtc']['h2']=$this->mydata->da_listr($dt['idsurat'],'h2');
            if($dt['c']==$this->mydata->report[8])$dt['iddpa']=$seg4;
            $dt['isfilter']=$prm1;
        }*/
//        show_cek($dt);die();
        return $dt;
     }
     
     function export($no='',$fmt='xls'){
         if(empty($no))show_404('page');
         // Load the library file
        $this->load->library('to_excel');
        $q=$this->mydata->get_ql($no);
        if(count($q['td'])>0){
            $no1=chg2($q['th'][0],'/ /','_');
            $nomor=1;
            foreach ($q['td'] as $x => $valu) {
                $q['td'][$x][$no1]=$nomor;
                $nomor++;
            }
            $q['th'][0]='No';
        }
        $this->to_excel->create_excel($q, 'filename'); 
     }
     
    function uploaddoc($nosur,$noperson,$backuri=""){
        if($this->notlogin(uri_string()))redirect(myctrl()."login");
        if(empty($nosur)||empty($noperson))redirect("/");  
        $nod=18;//t_upload
        $fl=$this->mydata->fl($nod);
        $nol=19;//?
        $dt['dtc']['upload']=$this->mydata->get_ql($nol,'',array($fl[1]=>$nosur));
        $nod=36;//t_surat
        $dt['dtc']['surat']=$this->mydata->get_ntable($nod,'*',array(0=>$nosur));
        $nod=39;//vipersonil
        $dt['dtc']['tarif']=$this->mydata->get_ntable($nod,'*',array(0=>$nosur,10=>$noperson));
        $nod=41;//vitaritransport
        $dt['dtc']['transp']=$this->mydata->get_ntable($nod,'*',array(0=>$nosur,11=>$noperson));
        $nod=16;//t_surat
        $dt['dtc']['tsurat']=$this->mydata->get_ntable($nod,'*',array(0=>$nosur));
        
        $dt['dtc']['h2']='Data Personil';
        $dt['dtc']['mustshow']=0;
        $dt['dtc']['notabel']=18;
        $dt['dtc']['nosur']=$nosur;
        $dt['dtc']['noperson']=$noperson;
        //$dt['dtc']['pgnil']=$pgnil;
        $dt['dtc']['last']=$this->uri_ret->glastenc($backuri);
        $dt['dtc']['me']=$this->uri_ret->add(uri_string());
        $dt['c']="upload_doc";
//        $dt['c']="list";
        setsesi('uri9',sesi('uri1'));
        setsesi('uri0',uri_string());
        setsesi('uri1',uri_string());
        //$dt['dtc']['me']=chg2(uri_string(),'/\//','_');
//        $dt['nologinp']=true;
        $this->load->view(myctrl().'sim_view',$dt);
    }

    function credit() {
        $this->load->view(myctrl().'template/credit');
    }

    private function notlogin($uri){
        if(!islogin()){
            setsesi('redir_login',$uri);
            return true;
        }else{
            setsesi('redir_login','');
            return false;
        }
    }   
    
    function templates($no) {
        //master_template_surat
        if($dt=$this->mydata->get1row(20,array(0=>$no)))
        echo html_entity_decode($dt[3]);
    }
    
    function templ_js() {
        //master_template_surat
        $dt['dt']=$this->mydata->get_ntable(20,'*');
        $this->load->view(myctrl().'template_list_js',$dt);
    }
    
    function ganti_pass() {
        //t_user
        $dt=$this->mydata->get1row(6,array(0=>sesi('nip')));
        $this->load->view(myctrl().'page/ganti_password_form',$dt);
    }
    
    function bos_note($noid='') {
        //t_user
        $this->load->helper('tinymce');
        if($noid){
            $dt['data']=$this->mydata->get1row(25,array(0=>$noid));
//            show_cek($dt);die();
            $dt['cmd']=2;
        }else{
            $dt['cmd']=1;
        }
        $dt['retn']=myctrl().'lister/25';
        $this->load->view(myctrl().'page/meymo_form',$dt);
    }
    /*
    function surat_masuk($noid=0,$backuri=""){
        if($this->notlogin(uri_string()))redirect("login");
//        $this->load->helper('tinymce');
        $dt['dtc']=array(
            'h2'=>'Surat Masuk',
            'cm'=>array(
                'sifat'=>$this->mydata->get_cmtbl(23,'11'),
                'next_bag'=>$this->mydata->get_cm_disposisi_bag(),
                'ruang'=>$this->mydata->get_cmtbl(44,'11'),
                'almari'=>$this->mydata->get_cmtbl(45,'11'),
            ),
            'cmd'=>($noid?2:1),
            'noid'=>$noid,
            'last'=>$this->uri_ret->glastenc($backuri),
            'me'=>$this->uri_ret->add(uri_string()),
        );
        $dt['c']="surat_masuk_form";
        if($noid){
            $dt['dtc']['data']=$this->mydata->get_ntable(21,'*',array($noid));   
            $filter_next_bag=array(1=>$noid,4=>1);
            if(islogin()!=1)$filter_next_bag[2]=sesi('bag');
            $dt['dtc']['next_bag']=
                $this->mydata->get_ntable(28,'*',$filter_next_bag);   
            
        }else{
            $dt['dtc']['data']['no_agn']=$this->mydata->get_last_nomor_masuk();
            $dt['dtc']['data']['tgl_sur']=date("Y-m-d");
        }
        $this->load->view('sim_view',$dt);
    }
*/
    function surat_masukbox($noid=0,$backuri="",$sbg=0){
        show_cek(sesi('bag'));
        if($this->notlogin(uri_string()))redirect(myctrl()."login");
//        $this->load->helper('tinymce');
        $combo=$this->aturan_next_bag($sbg);
        $cm=array(
                'sifat'=>$this->mydata->get_cmtbl(23,'11'),
                'next_bag'=>$this->mydata->get_cm_disposisi_bag(),
                'ruang'=>$this->mydata->get_cmtbl(44,'11'),
                'almari'=>$this->mydata->get_cmtbl(45,'11'),
            );
        $dt=array(
            'h2'=>'Surat Masuk',
            'cm'=>$combo+$cm,
            'cmd'=>($noid?2:1),
            'noid'=>$noid,
            'last'=>$this->uri_ret->glastenc($backuri),
            'me'=>$this->uri_ret->add(uri_string()),
        );
        if($noid){
            $dt['data']=$this->mydata->get_ntable(21,'*',array($noid));   
            $filter_next_bag=array(1=>$noid,4=>1);
            if(1==islogin()){
                $filter_next_bag[2]=$sbg;
            }else{
                $filter_next_bag[2]=sesi('bag');   
            }
            $dt['next_bag']=
                $this->mydata->get_ntable(28,'*',$filter_next_bag);   
            $dt['cmd']=2;
        }else{
            $dt['data']['no_agn']=$this->mydata->get_last_nomor_masuk();
            $dt['data']['tgl_sur']=date("Y-m-d");
            $dt['cmd']=1;
        }
        if((1==islogin())&&($sbg)){
            $ser='';
            if($ss=$this->mydata->get1row(32,array($sbg))){
                $ser['aturan']=html_entity_decode($ss[5]);
                if(unser(tesnull($ser['aturan']),'up'))$dt['data']['next_bag']['up']=array_intersect_key($dt['cm']['next_bag'],array_flip(unser(tesnull($ser['aturan']),'up')));
            }
        }
        $dt['sbg']=$sbg;
        $this->load->view(myctrl().'page/surat_masuk_formbox',$dt);
    }

    private function aturan_next_bag($sbg=0){
        $cm_nxt_all=$this->mydata->get_cm_disposisi_bag();
//        show_cek(array($dt['cm']['next_bag'],sesiaturan(),));die();
       
        $dt['next_bag']['al']=$cm_nxt_all;
        if(1!=islogin()){
            if(sesiaturan('up'))$dt['next_bag']['up']=array_intersect_key($cm_nxt_all,array_flip(sesiaturan('up')));
            if(sesiaturan('upk'))$dt['next_bag']['upk']=array_intersect_key($cm_nxt_all,array_flip(sesiaturan('upk')));
            if(sesiaturan('dn'))$dt['next_bag']['dn']=array_intersect_key($cm_nxt_all,array_flip(sesiaturan('dn')));
            if(sesiaturan('dnk'))$dt['next_bag']['dnk']=array_intersect_key($cm_nxt_all,array_flip(sesiaturan('dnk')));
            $dt['aturan']=sesiaturan();
        }else{
           if($sbg){
            $ser='';
            if($ss=$this->mydata->get1row(32,array($sbg))){
                $ser['aturan']=html_entity_decode($ss[5]);
                $ser['is_admin']=(1==$ss[6]);
            }
            $sm=unser(tesnull($ser['aturan']),'sm');
            $sk=unser(tesnull($ser['aturan']),'sk');
            if(unser(tesnull($ser['aturan']),'up'))$dt['next_bag']['up']=array_intersect_key($cm_nxt_all,array_flip(unser(tesnull($ser['aturan']),'up')));
            if(unser(tesnull($ser['aturan']),'upk'))$dt['next_bag']['upk']=array_intersect_key($cm_nxt_all,array_flip(unser(tesnull($ser['aturan']),'upk')));
            if(unser(tesnull($ser['aturan']),'dn'))$dt['next_bag']['dn']=array_intersect_key($cm_nxt_all,array_flip(unser(tesnull($ser['aturan']),'dn')));
            if(unser(tesnull($ser['aturan']),'dnk'))$dt['next_bag']['dnk']=array_intersect_key($cm_nxt_all,array_flip(unser(tesnull($ser['aturan']),'dnk')));
            }else{
                $dt['next_bag']['up']=$dt['next_bag']['upk']=$dt['next_bag']['dn']=$dt['next_bag']['dnk']=$cm_nxt_all ;
            }
            $dt['aturan']=unser($ser['aturan']);
        }
        return $dt;
    }
    
    function memo_dispo($id_surat=0,$id_poss=0,$retn=0,$sbg=0) {
         if($this->notlogin(uri_string()))redirect(myctrl()."login");
//         $this->load->helper('tinymce');
        $pos=retpost(null);
        //master_template_surat
        $loads=array($id_poss,$id_surat);
//        if(1!=islogin())$loads[2]=sesi('bag');
        $dt['dari']=$this->mydata->get_tabel(34,'*',array('id_posisi'=>$id_poss));
        //show_cek($this->mydata->get1rowq_('select * from ( select id_bag, CONCAT(nama_lev,' - ',nama_bidang) as nama from vi_bagian) a '));
//        $d=$this->mydata->get1rowq_("select * from ( select id_bag, CONCAT(nama_lev,' - ',nama_bidang) as nama from vi_bagian) a ");
//         show_cek($d);
        $dt['person_cc']=$this->mydata->get_cmq("select * from ( select id_bag, CONCAT(nama_lev,' - ',nama_bidang) as nama from vi_bagian where (`admin`=0)) a ",1);
        
        
        $dt['cm']=$this->aturan_next_bag($sbg);
        //todo -o me:buat history dari
/*        $id_pos_prev=$id_poss;
        $dari=array();   
        while($id_pos_prev > 0){
            if($rec_p=$this->mydata->get_ntable(38,'*',array($id_pos_prev))){
                $id_pos_prev=$rec_p
         $dt['dari'][]=array()   
            }else{
                $id_pos_prev=0;
            }
            $lanjut=
        }
*/        //!28
        $dt['next_bag']=$this->mydata->get_ntable(46,'*',$loads);
        $dt['kod_id']=tesnull($id_poss);
        $dt['id_surat']=tesnull($id_surat);
        
        /*
        $dt['cm']['next_bag']['dn']=$this->mydata->get_cm_disposisi_bag(2);
        $dt['cm']['next_bag']['up']=$this->mydata->get_cm_disposisi_bag(3);
        */
        $dt['cmd']=1;//($id_poss?2:1);
        $dt['retn']=tesnull($this->uri_ret->glastenc($retn));
        //todo -o me:buat track record pada tampilan disposisi
        $dt['track']=$this->mydata->get_ntable(46,'*',array(1=>$id_surat));
        $dt['person_cc_data']=$this->mydata->get1row_(51,array(0=>$id_surat));
        $dt['bag']=$this->mydata->get_ntable(32,'*');
        $dt['sbg']=$sbg;
        $dt['memo_dispo']=expload_memo_dispo(2==$dt['cmd']?wdt($dt['dari'],'memo'):'');
        $dt['memo_dispo_ret']=expload_memo_dispo(wdt($dt['dari'],'memo'),0);
        $this->load->view(myctrl().'page/memo_form',$dt);
    }

    function surat_keluar($id_surat=0,$backuri="",$sbg=0,$id_poss=0,$edit=0,$no_sm=0){
        if($this->notlogin(uri_string()))redirect(myctrl()."login");
        $this->load->helper('tinymce');
        $dt['dtc']=array(
            'h2'=>'Surat Keluar',
            'send'=>myctrl().'send/sk',
            'cm'=>array(
                'sifat'=>$this->mydata->get_cmtbl(23,'11'),
                'next_bag'=>$this->mydata->get_cm_disposisi_bag(),
                'template'=>$this->mydata->get_cmtbl(20,'11','','Tanpa Template'),
                'kopnya'=>$this->mydata->get_cmtbl(7,'11','','- Pilih Salah Satu -'),
            ),
            'cmd'=>($id_surat?2:1),
            'noid'=>$id_surat,
            'last'=>myctrl().$this->uri_ret->glastenc($backuri),
            'me'=>$this->uri_ret->add(uri_string()),
        );
//new     
        $cm_nxt_all=$dt['dtc']['cm']['next_bag'];
        if(1!=islogin()){
            if(sesiaturan('up'))$dt['dtc']['cm']['next_bag']['up']=array_intersect_key($cm_nxt_all,array_flip(sesiaturan('up')));
            if(sesiaturan('upk'))$dt['dtc']['cm']['next_bag']['upk']=array_intersect_key($cm_nxt_all,array_flip(sesiaturan('upk')));
            if(sesiaturan('dn'))$dt['dtc']['cm']['next_bag']['dn']=array_intersect_key($cm_nxt_all,array_flip(sesiaturan('dn')));
            if(sesiaturan('dnk'))$dt['dtc']['cm']['next_bag']['dnk']=array_intersect_key($cm_nxt_all,array_flip(sesiaturan('dnk')));
        }else{
           if($sbg){
            $ser='';
            if($ss=$this->mydata->get1row(32,array($sbg))){
                $ser['aturan']=html_entity_decode($ss[5]);
                $ser['is_admin']=(1==$ss[6]);
            }
            $sm=unser(tesnull($ser['aturan']),'sm');
            $sk=unser(tesnull($ser['aturan']),'sk');
            if(unser(tesnull($ser['aturan']),'up'))$dt['dtc']['cm']['next_bag']['up']=array_intersect_key($cm_nxt_all,array_flip(unser(tesnull($ser['aturan']),'up')));
            if(unser(tesnull($ser['aturan']),'upk'))$dt['dtc']['cm']['next_bag']['upk']=array_intersect_key($cm_nxt_all,array_flip(unser(tesnull($ser['aturan']),'upk')));
            if(unser(tesnull($ser['aturan']),'dn'))$dt['dtc']['cm']['next_bag']['dn']=array_intersect_key($cm_nxt_all,array_flip(unser(tesnull($ser['aturan']),'dn')));
            if(unser(tesnull($ser['aturan']),'dnk'))$dt['dtc']['cm']['next_bag']['dnk']=array_intersect_key($cm_nxt_all,array_flip(unser(tesnull($ser['aturan']),'dnk')));
            }else{
                $dt['dtc']['cm']['next_bag']['up']=$dt['dtc']['cm']['next_bag']['upk']=$dt['dtc']['cm']['next_bag']['dn']=$dt['dtc']['cm']['next_bag']['dnk']=$cm_nxt_all ;

            }
        }
        
//e-new        
        if($id_surat){
            $dt['dtc']['data']=$this->mydata->get_ntable(43,'*',array($id_surat));   
//            $dt['berkas']=$this->mydata->get_ntable(214,'*',array(wdt($dt['data'],14)));   
            $filter_next_bag=array(1=>$id_surat,4=>1);
            if(1==islogin()){
                $filter_next_bag[2]=$sbg;
            }else{
                $filter_next_bag[2]=sesi('bag');   
            }
            $dt['dtc']['next_bag']=
                $this->mydata->get_ntable(29,'*',$filter_next_bag);   
            $dt['dtc']['kop']=$this->mydata->get_ntable(7,'*',array(1));
            $dt['dtc']['cetak']=1;
            $dt['dtc']['edit']=$edit;
            $dt['dtc']['dg_kop']=1;
            
        }else{
            $dt['dtc']['data']['no_agn']=$this->mydata->get_last_nomor_masuk(2);
            $dt['dtc']['data']['tgl_sur']=date("Y-m-d");
        }
//        todo: belum di cek untuk yg bukan admin
        if((1==islogin())&&($sbg)){
            $ser='';
            if($ss=$this->mydata->get1row(32,array($sbg))){
                $ser['aturan']=html_entity_decode($ss[5]);
                $ser['id_bidang']=$ss[4];
                if(unser(tesnull($ser['aturan']),'upk'))$dt['dtc']['data']['next_bag']['upk']=array_intersect_key($dt['dtc']['cm']['next_bag'],array_flip(unser(tesnull($ser['aturan']),'upk')));
                if(unser(tesnull($ser['aturan']),'dnk'))$dt['dtc']['data']['next_bag']['dnk']=array_intersect_key($dt['dtc']['cm']['next_bag'],array_flip(unser(tesnull($ser['aturan']),'dnk')));
            }
            $dt['dtc']['aturan']=unser(tesnull($ser['aturan']));
        }
        
        $dt['dtc']['sbg']=$sbg;
        $dt['dtc']['ser']=tesnull($ser);
        $dt['c']='surat_keluar_form';
        
//new     13-1-2015
        $dt['id_surat']=tesnull($id_surat);
        
         $dt['person_cc']=$this->mydata->get_cmq("select * from ( select id_bag, CONCAT(nama_lev,' - ',nama_bidang) as nama from vi_bagian where (`admin`=0)) a ",1);
         $dt['person_cc_data']=$this->mydata->get1row_(51,array(0=>$id_surat));
        $dt['bag']=$this->mydata->get_ntable(32,'*');
        
//e - new 13-1-2015        
//new
        $loads=array($id_poss,$id_surat);
        $dt['dtc']['dari']=$this->mydata->get_tabel(42,'*',array('id_posisi'=>$id_poss));
        $dt['dtc']['dari_next_bag']=$this->mydata->get_ntable(48,'*',$loads);
//-e new        
//        show_cek(array('dtc'=>$dt['dtc'],'sesiaturan'=>$ser['aturan']));
        $dt['dtc']['kiriman_dari']=$no_sm;
        if($no_sm)if($r_k_dari=$this->db->where('id_surat',$no_sm)->get('vi_surat_masuk'))$dt['dtc']['kiriman_dari_data']=$r_k_dari->row();

        $this->load->model('tnde/creport');
        $rpt=$this->creport->report[1];//sk_box
        $dt['dtc']['view_sk_box']=$rpt['dir'].$rpt['file'];
        $this->load->view(myctrl().'sim_view',$dt);
    }

    function user_level($idlev=0,$backuri="") {
        if($this->notlogin(uri_string()))redirect(myctrl()."login");

/*        $id_pos_prev=$id_poss;
        $dari=array();   
        while($id_pos_prev > 0){
            if($rec_p=$this->mydata->get_ntable(38,'*',array($id_pos_prev))){
                $id_pos_prev=$rec_p
         $dt['dari'][]=array()   
                
            }else{
                $id_pos_prev=0;
            }
            $lanjut=
        }
*/        

        $dt['cm']=array(
            'next_bag'  =>$this->mydata->get_cm_disposisi_bag(),
            'yt'        =>$this->mydata->get_cmtbl(8,'*'),
            'lev'        =>$this->mydata->get_cmtbl(15,'11'),
            'bid'        =>$this->mydata->get_cmtbl(10,'*'),
        );
        if($idlev){
            if($dt['data']=$this->mydata->get_ntable(27,'*',array($idlev)))
            $dt['opt']=unserialize(html_entity_decode(tesnull(wdt($dt['data'],'aturan'))));
            $dt['noid']=$idlev;
        }
//        $dt['kod_id']=tesnull($id_poss);
        $dt['id_surat']=tesnull($idlev);
        $dt['h2']=($idlev?"Edit":"Buat")." Aturan Pengguna";
        $dt['cmd']=($idlev?2:1);
        $dt['retn']=tesnull($this->uri_ret->glastenc($backuri));
        
        $this->load->view(myctrl().'page/rev_lev_form',$dt);
    }

    function formbox($cmd,$no_listr,$where="0",$backuri="",$filter=""){
        $this->load->helper('tinymce');
        if($this->notlogin(uri_string()))redirect(myctrl()."login");
        if(empty($cmd)||empty($no_listr))redirect("/");  
        
        $pwhere="";
        if('0'!=$where)$pwhere=$this->mydata->convert_param_filter($no_listr,$this->uri_ret->get_dec($where),false);                  
        $dt=$this->mydata->get_tbl_form($no_listr,(2 == $cmd?$pwhere:""));
        
        if((1==$cmd)&&((404==$no_listr)||(13==$no_listr))){
              //upload
               $dt['td']=$pwhere;
               $dt['td']['path']='';//$dt['dtc']['td']['id_surat'];
               if($pwhere)foreach ($pwhere as $x => $valu) $dt['atr']['tf'][$x]='h';
          }elseif(2==$cmd){
                $dt['td']=$dt['td'][0];
          }
          
          $dt['h2']=($cmd==1?"Tambah":"Ubah")." Data ".
              (!empty($this->mydata->da_listr($no_listr,'h2'))
              ?$this->mydata->da_listr($no_listr,'h2')
              :''.chg2_($this->mydata->da_listr($no_listr,'n')))
          ;

          $dt['cmd']=$cmd;
          $dt['notabel']=$no_listr;
          $dt['mustshow']=tesnull($this->mydata->da_listr($no_listr,'mustshow'));
//          $dt['dtc']['pake_act']=$tes_act;
//          $dt['nologinp']=true;
//          $dt['c']="form";
//          setsesi('uri1',uri_string());
          setsesi('validities',$this->mydata->get_formvalidities($no_listr));
          $dt['last']=$this->uri_ret->glastenc($backuri);
          if($filter)$filter=$this->uri_ret->get_dec($filter);
          $dt['filter']=$this->mydata->convert_param_filter($no_listr,$filter,0,1); 
          if((1==$cmd)&&(tesnull($filter))){
              $nod=$this->mydata->da_listr($no_listr,'t');
              $fl=$this->mydata->fl($nod);
              foreach ($dt['filter'] as $x => $valu) {
                $dt['atr']['tf'][$x]='h';
                $dt['td'][$fl[$x]]=$valu;
              }
          }
          $dt['me']=$this->uri_ret->add(uri_string());
          $this->load->view(myctrl().'page/formbox',$dt);
      }
      
    function track_sm($ids) {
        if($this->notlogin(uri_string()))redirect(myctrl()."login");
        if($dt['bag']=$this->mydata->get_ntable(32,'*'))
        if($dt['track']=$this->mydata->get_ntable(46,'*',array(1=>$ids)))
        $dt['data']=$this->mydata->get_ntable(35,'*',$ids);
        $dt['ids']=$ids;
        $dt['surat_masuk']=1;
        $dt['h2']='Telusur Surat Masuk';
        $this->load->model('tnde/creport');
        $rpt=$this->creport->report[2];//track
        $this->load->view(myctrl().$rpt['dir'].$rpt['file'],$dt);//'page/track'
    }

    function track_sk($ids){
        if($this->notlogin(uri_string()))redirect(myctrl()."login");
        if($dt['bag']=$this->mydata->get_ntable(32,'*'))
            if($dt['track']=$this->mydata->get_ntable(48,'*',array(1=>$ids)))
                if($dt['data']=$this->mydata->get_ntable(43,'*',$ids))
                    if(wdt($dt['data'],'ref_sm'))
                        $dt['data_ref_sm']=$this->mydata->get_ntable(211,'*',wdt($dt['data'],'ref_sm'));
        $dt['ids']=$ids;
        $dt['surat_masuk']=0;
        $dt['h2']='Telusur Surat Keluar';
        $this->load->model('tnde/creport');
        $rpt=$this->creport->report[2];//track
        $this->load->view(myctrl().$rpt['dir'].$rpt['file'],$dt);//'page/track'
    }

    function penomoran_sk($noid,$backuri="",$sebagai=0) {
        if($this->notlogin(uri_string()))redirect(myctrl()."login");
        $dt['noid']=$noid;
        $dt['cmd']=1;
        $dt['last']=$this->uri_ret->glastenc($backuri);
        $dt['me']=$this->uri_ret->add(uri_string());
        $dt['cm']['next_bag']=$this->mydata->get_cm_disposisi_bag();
        if($dt['bag']=$this->mydata->get_ntable(32,'*'))
        if($dt['data']=$this->mydata->get_ntable(43,'*',array($noid)))
        $dt['sbg']=$sebagai;
        $dt['kode_bid']=$this->mydata->get1row(10,array(wdt($dt['data'],'no_bidang')));
        $this->load->view(myctrl().'page/penomoran_sk',$dt);
    }

       
    function list_surat($no_listr,$sebagai=0,$offset=0,$backuri=""){
        if($this->notlogin(uri_string()))redirect(myctrl()."login");
//        if(empty($no_listr))redirect("/");//exit('must fill with number');
        $dt['breadcrumb'] = tesnull($this->mydata->da_listr($no_listr,'breadcrumb'));
        //start ?        
        $pstart=array($offset,$this->max_list);
        //where 
        $pwhere="";
/*        if($where){
            $where=$this->uri_ret->get_dec($where);
            $pwhere=$this->mydata->convert_param_filter($no_listr,$where);   
        }
*/        //filter
        $formatstgl = array("d-m-Y", "d/m/Y");
        if($retpost=retpost(null)){
            if(!empty($retpost['f']))setsesi('filter_list'.$no_listr,$retpost);
            if(!empty($retpost['r'])){
                $rp['f']=0;
                $rp['q']='';
                setsesi('filter_list'.$no_listr,'');
            }
        }else{
            $retpost=sesi('filter_list'.$no_listr);
        }
        $rp=$retpost;
        if(tesnull($rp['q']))
        foreach ($formatstgl as $fx =>$format)
            if($date = DateTime::createFromFormat($format, $rp['q'])){
                $rp['q']=tgl2sql($retpost['q']);
        }
        
        //loads
        $dt['c']="list_surat";
        $dt['dtc']['cm']['sbg']=$this->mydata->get_cmtbl(32,'011',array('admin'=>0),'Admin');
        //??
        
        $dt['dtc']['noadd']=1;
        if($sebagai){
            if($ss=$this->mydata->get1row(32,array($sebagai))){
                $ser['aturan']=html_entity_decode($ss[5]);
                $ser['is_admin']=(1==$ss[6]);
            }
            $sm=unser(tesnull($ser['aturan']),'sm');
            $sk=unser(tesnull($ser['aturan']),'sk');
            $nsk=unser(tesnull($ser['aturan']),'nsk');
            
            switch($no_listr){
                case '211011':
                    if($sm){
                        $ssbg='t.id_bag';
                    }else{
                        $ssbg='t.next_id_bag';
                    }
                    $dt['dtc']['noadd']=!$sm ;
                break;
                case '2110110':
                    if($sm){
                        $ssbg='t.next_id_bag';
                    }else{
                        $ssbg='t.id_bag';
                    }
                break;
                case '2110111':
                        $ssbg='t.id_bag';
                break;
                case '2140110':
                case '2140111':
                    $ssbg='t.id_bag';
                break;
                case '214011':
                    $ssbg='t.next_id_bag';
                    $dt['dtc']['noadd']=(!$sk );
                break;
            }
/*            if('2110110'==$no_listr){
                if($sm){
                    $ssbg='t.next_id_bag';
                }else{
                    $ssbg='t.id_bag';
                }
            }elseif('211011'==$no_listr){
                if($sm){
                    $ssbg='t.id_bag';
                }else{
                    $ssbg='t.next_id_bag';
                }
                $dt['dtc']['noadd']=!$sm ;
            }elseif(('2140110'==$no_listr)||('2140111'==$no_listr)){
                $ssbg='t.id_bag';
            }elseif('214011'==$no_listr){
                $ssbg='t.next_id_bag';
                $dt['dtc']['noadd']=(!$sk );
            }*/
            $pwhere=array($ssbg=>$sebagai);

        }else{
            $pwhere='';
            $sk=sesiaturan('sk');
        }

        $dt['dtc']['can_crt_sk']=$sk;
        
//        show_cek($dt['dtc']['noadd']);
        if($dt['dtc']['data']=$this->mydata->get_ql($no_listr,$pstart,$pwhere,'',$rp)){
            //paging
            $pg=array(
                'base_url'=> site_url($this->site_url."/".$this->uri->segment(4)),
                'total_rows'=> tesnull($dt['dtc']['data']['jma']),
                'per_page'=> $this->max_list,
                'uri_segment'=> '5',
                'first_url'=>0,
            );
            $this->pagination->initialize($pg);
            $dt['dtc']['paging']=$this->pagination->create_links();
            $dt['dtc']['filter_like']=$retpost;
        }
        
        $dt['dtc']['listr']=tesnull($this->mydata->da_listr($no_listr));
        $dt['dtc']['notabel']=$no_listr;
        $dt['dtc']['h2']=tesnull($dt['dtc']['listr']['h2'],'List Data '.chg2_($this->mydata->da_listr_n($no_listr)));
        $dt['dtc']['startno']=$pstart[0];
        
        $var=array('nofilter','noadd','maxql','mustshow','ff','ql');
        foreach ($var as $valu) $dt['dtc']['listr'][$valu]=tesnull($dt['dtc']['listr'][$valu]);
        
        $testh=tesnull($dt['dtc']['data']['th']);
        $dt['dtc']['fl']=(!$dt['dtc']['listr']['ql'] && $dt['dtc']['listr']['ff'] && $testh?array_replace($testh,$dt['dtc']['listr']['ff']):$testh);
        $dt['dtc']['last']=$this->uri_ret->glastenc($backuri);
        $dt['dtc']['me']=$this->uri_ret->add(uri_string());

        switch($no_listr){
            case 5://13
                $nambah_param="/".((count($dt['dtc']['psegw'])>1)?$this->uri_ret->get_enc("1:".$dt['dtc']['psegw'][1]):"");
                break;
            default:
                $nambah_param='';
        }
        $dt['dtc']['nambah_param']=$dt['dtc']['me'].$nambah_param;
        $dt['dtc']['sbg']=$sebagai;
        $dt['dtc']['nsk']=tesnull($nsk);
        
        $this->load->view(myctrl().'sim_view',$dt);
        //        setsesi('tes','');
    }

    function confirm() {
        $dt['data']='';
        $this->load->view(myctrl().'page/confirm_formbox',$dt);
    }
    
    function see_surat_keluar_box($ids,$backuri="",$track=1) {
        if($this->notlogin(uri_string()))redirect(myctrl()."login");
        if(empty($ids))redirect('/');
        $dt['h2']='Surat Keluar';
        $dt['kop']=$this->mydata->get_ntable(7,'*',array(1));
        $dt['data']=$this->mydata->get_ntable(43,'*',$ids);
        if($dt['bag']=$this->mydata->get_ntable(32,'*'))
        $dt['track']=($track?$this->mydata->get_ntable(48,'*',array(1=>$ids)):'');
        $dt['last']=$this->uri_ret->glastenc($backuri);
        $dt['dg_kop']=1;
        $dt['ids']=$ids;
        $this->load->model('tnde/creport');
        $rpt=$this->creport->report[1];//track
        $this->load->view(myctrl().$rpt['dir'].$rpt['file'],$dt);//'page/track'
//        $this->load->view('page/view_surat_keluar_box',$dt);
    }
    
    function auto_klasifikasi() {
        $q = $this->input->post('q');
    
        $res = $this->general_model->datagrab(array(
            'tabel' => 'ref_kode_surat',
            'search' => array('kode' => $q,'keterangan' => $q),
            'select' => 'id, kode, keterangan'
        ));
    
        die(json_encode($res->result()));
    }
    
    function tes(){
/*        echo "TEsss ";
        show_cek($this->ctabel->t);//$this->cmenu->menu[1]; */
    }  
    
    function cek_table() {
        $this->load->model('tnde/ctabel');
        show_cek($this->ctabel->tes_table());
    }   

    ///func plus -plus
    
    function arsip($data='') {
//        show_cek($_POST);die();
        redirect(
        'edocument/'
        .$this->uri->segment(1)
        .($data?'':'/'.$this->uri->segment(1))
        .($this->uri->segment(2)?'/'.$this->uri->segment(2):'')
        .($this->uri->segment(3)?'/'.$this->uri->segment(3):'')
        .($this->uri->segment(4)?'/'.$this->uri->segment(4):'')
        .($this->uri->segment(5)?'/'.$this->uri->segment(5):'')
        .($this->uri->segment(6)?'/'.$this->uri->segment(6):'')
        .($this->uri->segment(7)?'/'.$this->uri->segment(7):'')
        .($this->uri->segment(8)?'/'.$this->uri->segment(8):'')
        );
    
    }
    function adm_arsip($data='') {
//        show_cek($_POST);die();
        redirect(
        'edocument/'
        .$this->uri->segment(1)
        .($data?'':'/'.$this->uri->segment(1))
        .($this->uri->segment(2)?'/'.$this->uri->segment(2):'')
        .($this->uri->segment(3)?'/'.$this->uri->segment(3):'')
        .($this->uri->segment(4)?'/'.$this->uri->segment(4):'')
        .($this->uri->segment(5)?'/'.$this->uri->segment(5):'')
        .($this->uri->segment(6)?'/'.$this->uri->segment(6):'')
        .($this->uri->segment(7)?'/'.$this->uri->segment(7):'')
        .($this->uri->segment(8)?'/'.$this->uri->segment(8):'')
        );
    
    }
    function reminder_surat($n=''){
      //cari info semu user yang punya email dan info surat   
//      2110111
      $this->load->view(myctrl().'page/rpt/email_reminder');
    }
    
    function t() {
        $password=$username='admin';
        $r = $this->db->get_where('vi_user',array('user' => $username,'pass'=>$password,'aktif'=>1))->row_array();
        foreach ($r as $x =>$valu) $r[]=$valu;
        cek($r);
        return;
    }
    
  }
/**/  
?>