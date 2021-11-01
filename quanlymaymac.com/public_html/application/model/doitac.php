<?php

class doitac extends model{
    public $table = 'doitac';
    public static function list_all( $kho = 0, $vaitro = 0 ){
        $class = new self;
        
        if($vaitro==0)
            $res = $class->find_all_by_kho($kho);
        else
            $res = $class->all(array(
                'conditions'=>array(
                    array('kho','=',$kho),
                    array('vaitro','=',$vaitro)
                )
            ));
         
        if($res){
            foreach($res as &$r){
                $r->table = 'doitac';
            }
            return $res;
        }
        return false;
    } 
     
    public static function get($id){
        $class = new self;
         
        $res = $class->find_by_id($id);
         
        if($res){
            $res->table = 'doitac';
            return $res;
        }
        return false;
    } 
    public static function list_page($page=1,$pp=20,$add='order by id desc',&$pages=''){         
        $class = db::query("select SQL_CALC_FOUND_ROWS * FROM doitac $add limit ".($page==1?'0':(($page-1)*$pp)).",$pp");
        $pages = db::query("select found_rows() as total");
        $pages = $pages[0]->total;
        $pages = ceil($pages/$pp);
        return $class;
    } 
}