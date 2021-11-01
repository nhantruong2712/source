<?php

class hoadon_chitiet extends model{
    public $table = 'hoadon_chitiet';
    public static function list_all( $hoadon ){
        $class = new self;
 
        return  $class->find_all_by_hoadon($hoadon) ;
    } 
     
    public static function get($id){
        $class = new self;
         
        $res = $class->find_by_id($id);
         
        if($res){
            $res->table = 'hoadon_chitiet';
            return $res;
        }
        return false;
    } 
    
}