<?php

class admin extends model{
    public $table = 'admin';
    public static function list_all( $kho = 0 ){
        $class = new self;
  
        $res = $class->find_all_by_kho($kho);
         
        if($res){
            foreach($res as &$r){
                $r->table = 'admin';
            }
            return $res;
        }
        return false;
    } 
     
    public static function get($id){
        $class = new self;
         
        $res = $class->find_by_id($id);
         
        if($res){
            $res->table = 'admin';
            return $res;
        }
        return false;
    } 
    
}