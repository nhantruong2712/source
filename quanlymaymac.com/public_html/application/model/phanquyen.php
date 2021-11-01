<?php

class phanquyen extends model{
    public $table = 'phanquyen';
    public static function list_all( $kho, $vaitro=0 ){
        $class = new self;
        
        if($vaitro==0)                 
            $res = $class->find_all_by_kho($kho);
        else
            $res = $class->find_all_by_kho_and_vaitro($kho,$vaitro);
         
        if($res){
            foreach($res as &$r){
                $r->table = 'phanquyen';
            }
            return $res;
        }
        return false;
    } 
     
    public static function get($id){
        $class = new self;
         
        $res = $class->find_by_id($id);
         
        if($res){
            $res->table = 'phanquyen';
            return $res;
        }
        return false;
    } 
    
}