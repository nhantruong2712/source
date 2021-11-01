<?php

class hoadon extends model{
    public $table = 'hoadon';
    public static function list_all( ){
        $class = new self;
 
        return  $class->all() ;
    } 
     
    public static function get($id){
        $class = new self;
         
        $res = $class->find_by_id($id);
         
        if($res){
            $res->table = 'hoadon';
            return $res;
        }
        return false;
    } 
    
    public static function list_page($page=1,$pp=20,$add='order by id desc',&$pages=0){         
        $class = db::query("select SQL_CALC_FOUND_ROWS hoadon.* FROM hoadon $add limit ".($page==1?'0':(($page-1)*$pp)).",$pp");
        $pages = db::query("select found_rows() as total");
        $pages = $pages[0]->total;
        $pages = ceil($pages/$pp);
        return $class;
    } 
}