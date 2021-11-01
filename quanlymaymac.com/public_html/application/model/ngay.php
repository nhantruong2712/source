<?php

class ngay extends model{
    public $table = 'ngay';
    public static function list_all( ){
        $class = new self;
 
        return  $class->all() ;
    } 
     
    public static function get($id){
        $class = new self;
         
        $res = $class->find_by_id($id);
         
        if($res){
            $res->table = 'ngay';
            return $res;
        }
        return false;
    } 
    
}