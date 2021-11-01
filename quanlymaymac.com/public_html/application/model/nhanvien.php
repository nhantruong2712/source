<?php

class nhanvien extends model{
    public $table = 'nhanvien';
    public static function list_all( $kho = 0 ){
        $class = new self;
  
        $res = $class->find_all_by_kho($kho);
         
        if($res){
            foreach($res as &$r){
                $r->table = 'nhanvien';
            }
            return $res;
        }
        return false;
    }  
     
    public static function get($id){
        $class = new self;
         
        $res = $class->find_by_id($id);
         
        if($res){
            $res->table = 'nhanvien';
            return $res;
        }
        return false;
    } 
    public static function list_page($page=1,$pp=20,$add='order by id desc',&$pages=''){         
        $class = db::query("select SQL_CALC_FOUND_ROWS * FROM nhanvien $add limit ".($page==1?'0':(($page-1)*$pp)).",$pp");
        $pages = db::query("select found_rows() as total");
        $pages = $pages[0]->total;
        $pages = ceil($pages/$pp);
        return $class;
    } 
}