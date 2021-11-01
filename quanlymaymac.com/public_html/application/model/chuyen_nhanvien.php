<?php

class chuyen_nhanvien extends model{
    public $table = 'chuyen_nhanvien';
    public static function list_all( ){
        $class = new self;
 
        return  $class->all() ;
    } 
     
    public static function get($id){
        $class = new self;
         
        $res = $class->find_by_id($id);
         
        if($res){
            $res->table = 'chuyen_nhanvien';
            return $res;
        }
        return false;
    } 
    
}