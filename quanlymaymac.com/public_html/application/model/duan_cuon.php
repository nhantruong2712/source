<?php

class duan_cuon extends model{
    public $table = 'duan_cuon';
    public static function list_all( $duan ){
        $class = new self;
 
        return  $class->find_all_by_duan($duan) ;
    } 
     
    public static function get($id){
        $class = new self;
         
        $res = $class->find_by_id($id);
         
        if($res){
            $res->table = 'duan_cuon';
            return $res;
        }
        return false;
    } 
    
    public static function list_page($page=1,$pp=20,$add='order by id desc',&$pages=0){         
        $class = db::query("select SQL_CALC_FOUND_ROWS * FROM duan_cuon $add limit ".($page==1?'0':(($page-1)*$pp)).",$pp",'duan_cuon');
        $pages = db::query("select found_rows() as total");
        $pages = $pages[0]->total;
        $pages = ceil($pages/$pp);
        return $class;
    } 
}