<?php

class chuyen extends model{
    public $table = 'chuyen';
    public static function list_all( $duan ){
        $class = new self;
 
        return  $class->find_all_by_duan($duan) ;
    } 
     
    public static function get($id){
        $class = new self;
         
        $res = $class->find_by_id($id);
         
        if($res){
            $res->table = 'chuyen';
            return $res;
        }
        return false;
    } 
    
}