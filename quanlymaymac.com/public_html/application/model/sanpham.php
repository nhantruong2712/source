<?php

class sanpham extends model{
    public $table = 'sanpham';
    public static function list_all( $kho = 0 ){
        $class = new self;
  
        $res = $class->find_all_by_kho($kho);
         
        if($res){
            foreach($res as &$r){
                $r->table = 'sanpham';
            }
            return $res;
        }
        return false;
    }  
     
    public static function get($id){
        $class = new self;
         
        $res = $class->find_by_id($id);
         
        if($res){
            $res->table = 'sanpham';
            return $res;
        }
        return false;
    } 
    
    public static function list_page($page=1,$pp=20,$add='order by id desc',&$pages=0){         
        $class = db::query("select SQL_CALC_FOUND_ROWS * FROM sanpham $add limit ".($page==1?'0':(($page-1)*$pp)).",$pp");
        $pages = db::query("select found_rows() as total");
        $pages = $pages[0]->total;
        $pages = ceil($pages/$pp);
        return $class;
    } 
    
    //13/05/2020
    public static function khosanphamnhanvien($kho,$sanpham,$nhanvien){
        $x = db::query("select sum(case h.type when 1 then -hc.soluong else hc.soluong end) as soluong from hoadon_chitiet hc inner join hoadon h on h.id=hc.hoadon and h.type in (1,3) and h.nhanvien=$nhanvien where hc.sanpham = $sanpham and h.kho = $kho group by hc.sanpham");
        if($x){
            return $x[0]->soluong;
        }
        return 0;
    }
    
    //06/05/2020
    public static function concua(&$model,&$modelcha = false){//var_dump($model);die();
        if(!($model instanceof model)){
            return false;
        }
        if($model->ma=='' && $model->cha>0){
            $modelcha = sanpham::get($model->cha);
            if(!$modelcha) die();
            
            $ad = $modelcha->attributes();
            $main = $model->cha;
            unset($ad['id'],$ad['soluong'],$ad['kho']);
            $model = model::extend($model,$ad);
            $model->concua = $main;
        }  
    }
    
    //06/06/2020
    public static function checksoluong($id,$kho){
        $checksoluong = db::query("select * from sanpham where (id={$id} or (ma='' and cha={$id})) and kho=$kho");
        if($checksoluong){            
            $checksoluong = $checksoluong[0]->soluong;
        }else{            
            $checksoluong = 0;
        }
        return $checksoluong;
    }
    public static function checksoluongsp(&$sp,$kho){
        if($sp->kho!=$kho)
            $sp->soluong = self::checksoluong($sp->id,$kho);
    }
    public static function checksanphamkho($id,$kho){
        $checksoluong = db::query("select * from sanpham where (id={$id} or (ma='' and cha={$id})) and kho=$kho");
        if($checksoluong){            
            return $checksoluong[0];
        }
        
        return false;
    }
    
    //06/07/2020
    public static function dinhluong(&$b){
        if($b['nhom']){                
            $dinhmuc2 = dinhmuc::list_all_cha($b['id']);
            if($dinhmuc2){
                if(count($dinhmuc2)==1){
                    $dinhmuc2 = $dinhmuc2[0]->soluong;
                }
            }else{
                $dinhmuc2 = dinhmuc::list_all($b['nhom']);
                if(count($dinhmuc2)==1){
                    $dinhmuc2 = $dinhmuc2[0]->soluong;
                }
            }
            if($dinhmuc2){
                $b['dinhluong'] = $dinhmuc2;
            }
        }
        if(empty($b['text']))
            $b['text'] = $b['ten'];
    }
}