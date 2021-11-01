<?php

class quyen extends model{
    public $table = 'quyen';
    public static function list_all( ){
        $class = new self;
 
        return  $class->all() ;
    } 
     
    public static function get($id){
        $class = new self;
         
        $res = $class->find_by_id($id);
         
        if($res){
            $res->table = 'quyen';
            return $res;
        }
        return false;
    } 
    
}