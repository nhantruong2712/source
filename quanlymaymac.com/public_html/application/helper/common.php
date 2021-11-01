<?php
class common{
    public static function autoCode($id=1,$except=array(),$prefix='KK',$length=6,$table='hoadon'){
         
        $sql = db::query("select `ma` from `$table` where `ma` regexp '^{$prefix}[0-9]{".$length.
            "}$' order by ma desc limit 1");
        
        if($sql){    
            $num = $sql[0];
            $id = $num->ma;
            $id = substr($id,strlen($prefix));
            $id = intval($id)+1;
        }else{
            $id = 1;
        }
         
        if(is_string($except)) $except = array($except);
         
        do{
            $code = $prefix.sprintf("%0".$length."d",$id++);           
        }while(in_array($code,$except));
        
        return $code;
    }
    public static function themhoadon(array $data){
        $sanpham = $data['sanpham'];
        unset($data['sanpham']);
        
        $hoadon = model::load($data,'hoadon');
        $hoadon = $hoadon->insert(true);
        
        if(!$hoadon) return false;
        
        foreach($sanpham as $sp){
            $sp['hoadon'] = $hoadon->id;
            $sp = model::load($sp,'hoadon_chitiet');
            $sp->insert();
        }
        
        return $hoadon;
    }
    
    //05/27/2020
    public static function vaitro(&$employee,$kho=0){ 
        if(!$kho) $kho = $employee->kho;
        
        //admin k co vai tro : fixed 05/27/2020
        if(empty($employee->vaitro)) $employee->vaitro = false;
        
        $vaitro = $employee->vaitro;
        $vaitro = phanquyen::list_all($kho,$vaitro);
        //
        if($vaitro){
            foreach($vaitro as &$role){
                $role->quyen = quyen::get($role->quyen);
            }
        
            $vaitro = array_map(function($a){
                return $a->quyen->ma;
            },$vaitro);
        }
        
        //var_dump($vaitro);die();
        $employee->vaitro = $vaitro;
    }
}
?>