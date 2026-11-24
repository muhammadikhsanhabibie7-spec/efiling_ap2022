<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
|
*/
class Uri_ret {
    var $data;
    var $c_=array('_','=');
    function add($uri){
        $this->data[]=$uri;
        return $this->get_all();
    }
    function get_dec($dt){return base64_decode(preg_replace('/'.$this->c_[0].'/',$this->c_[1],$dt));}                               
    function get_enc($dt){return preg_replace('/'.$this->c_[1].'/',$this->c_[0],base64_encode($dt));}
    function get_last($enc=0){
        if($this->data){
            $l=$this->data[count($this->data)-1];
            if($enc){
                return $this->get_enc($l);
            }else{
                return $l;
            }
        }else{
            return false;
        }        
    }
    function get_all(){
        if($this->data){
            return $this->get_enc(implode('|',$this->data));
        }else{
            return false;
        }        
    }
    function set_data($uri,$enc=1){
        if($enc){
            $tmp=$this->get_dec($uri);
        }else{
            $tmp=$uri;
        }
        $this->data=explode('|',$tmp);
    }
    function glastenc($dt_enc){
    //return last uri        
        $this->set_data($dt_enc);
        return $this->get_last();
    }
    function set_last($dt_enc,$uri){
        $this->set_data($dt_enc);
        return $this->add($uri);
    }
}
/* End of file */