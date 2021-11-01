<?php

class luong extends model{
    public $table = 'luong';
    public static function list_all( ){
        $class = new self;
 
        return  $class->all() ;
    } 
     
    public static function get($id){
        $class = new self;
         
        $res = $class->find_by_id($id);
         
        if($res){
            $res->table = 'luong';
            return $res;
        }
        return false;
    } 
    
}