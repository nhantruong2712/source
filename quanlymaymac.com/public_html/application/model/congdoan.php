<?php

class congdoan extends model{
    public $table = 'congdoan';
    public static function list_all( $kho=0 , $nhom = false ){ //add nhom 06/03/2020
        $class = new self;
        
        if($kho==0 && $nhom===false)        
            return  $class->all() ;        
        else if($nhom === false)         
            return $class->find_all_by_kho($kho);
        else
            return $class->find_all_by_nhom($nhom);
    } 
     
    public static function get($id){
        $class = new self;
         
        $res = $class->find_by_id($id);
         
        if($res){
            $res->table = 'congdoan';
            return $res;
        }
        return false;
    } 
    public static function list_page($page=1,$pp=20,$add='order by congdoan.id desc',&$pages=0){         
        $class = db::query("select SQL_CALC_FOUND_ROWS congdoan.*, nhomcongdoan.ten as nhomcongdoanten FROM congdoan 
            left join nhomcongdoan on congdoan.nhom = nhomcongdoan.id 
            $add limit ".($page==1?'0':(($page-1)*$pp)).",$pp"); 
        $pages = db::query("select found_rows() as total");
        $pages = $pages[0]->total;
        $pages = ceil($pages/$pp);
        return $class;
    } 
}