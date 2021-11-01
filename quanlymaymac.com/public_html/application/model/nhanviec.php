<?php

class nhanviec extends model{
    public $table = 'nhanviec';
    public static function list_all( $nhanvien ){
        $class = new self;
 
        return  $class->find_all_by_nhanvien($nhanvien) ;
    } 
     
    public static function get($id){
        $class = new self;
         
        $res = $class->find_by_id($id);
         
        if($res){
            $res->table = 'nhanviec';
            return $res;
        }
        return false;
    } 
    
}