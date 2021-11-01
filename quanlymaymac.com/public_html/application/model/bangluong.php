<?php

class bangluong extends model{
    public $table = 'bangluong';
    public static function list_all( $kho ){
        $class = new self;
                 
        $res = $class->find_all_by_kho($kho);
         
        if($res){
            foreach($res as &$r){
                $r->table = 'bangluong';
            }
            return $res;
        }
        return false;
    } 
     
    public static function get($id){
        $class = new self;
         
        $res = $class->find_by_id($id);
         
        if($res){
            $res->table = 'bangluong';
            return $res;
        }
        return false;
    } 
    public static function list_select($kho=0,$key='id',$val='ten'){
        $res = array();
        $all = self::list_all($kho);
        if($all)
        foreach($all as $v){
            $res[$v->$key] = $v->$val;
        }
        return $res;
    } 
}