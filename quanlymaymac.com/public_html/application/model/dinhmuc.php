<?php

class dinhmuc extends model{
    public $table = 'dinhmuc';
    public static function list_all( $nhom ){
        $class = new self;
 
        return  $class->find_all_by_nhom($nhom) ;
    } 
    
    public static function list_all_cha( $cha ){
        $class = new self;
 
        return  $class->find_all_by_cha($cha) ;
    } 
     
    public static function get($id){
        $class = new self;
         
        $res = $class->find_by_id($id);
         
        if($res){
            $res->table = 'dinhmuc';
            return $res;
        }
        return false;
    } 
    
}