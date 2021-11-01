<?php

class duan extends model{
    public $table = 'duan';
    public static function list_all( ){
        $class = new self;
 
        return  $class->all() ;
    } 
     
    public static function get($id){
        $class = new self;
         
        $res = $class->find_by_id($id);
         
        if($res){
            $res->table = 'duan';
            return $res;
        }
        return false;
    } 
    
    public static function list_page($page=1,$pp=20,$add='order by id desc',&$pages=0){         
        $class = db::query("select SQL_CALC_FOUND_ROWS duan.* FROM duan $add limit ".($page==1?'0':(($page-1)*$pp)).",$pp");
        $pages = db::query("select found_rows() as total");
        $pages = $pages[0]->total;
        $pages = ceil($pages/$pp);
        return $class;
    } 
}