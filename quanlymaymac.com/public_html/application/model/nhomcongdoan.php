<?php

class nhomcongdoan extends model{
    public $table = 'nhomcongdoan';
    public static function list_all( $kho = 0 ){
        $class = new self;
 
        //return  $class->all() ;
        $res = $class->find_all_by_kho($kho);
         
        if($res){
            foreach($res as &$r){
                $r->table = 'nhomcongdoan';
            }
            return $res;
        }
        return false;
    } 
     
    public static function get($id){
        $class = new self;
         
        $res = $class->find_by_id($id);
         
        if($res){
            $res->table = 'nhomcongdoan';
            return $res;
        }
        return false;
    } 
    public static function child($kho=0,$parent=0,$except=false){
        $class = new self;  
        $return = $class->all(
            array(
                'conditions'=>array(
                    array('cha','=',$parent),
                    array('kho','=',$kho)
                )
            )
        );
        if($return && $except===false){
            foreach($return as $k=>$r){
                $return[$k]->tren = ($k == 0); 
                $return[$k]->duoi = ($k==count($return)-1); 
            }
        }elseif($except!==false){
            $return = array_filter(
                $return,
                create_function('$x','return $x->id!='.$except.';')
            );
        }
        return $return;
    } 
    //only for fancytree
    public static function children($kho=0,$parent=0)
    {
        $ret = array();
        $count = 0;
        foreach(self::child($kho,$parent) as $child){
             
            $ret[$count] = $child->attributes();
            $ret[$count]['folder'] = true;
            $ret[$count]['expanded'] = true;
             
            $ret[$count]['title'] = $ret[$count]['ten'];
            unset($ret[$count]['ten'],$ret[$count]['tren'],$ret[$count]['duoi']
            ,$ret[$count]['cap']); //,$ret[$count]['kho']
            $child2 = self::children($kho,$ret[$count]['id']);
            if($child2){
                $ret[$count]['children'] = $child2;                 
            } 
            $count++;                          
        }
        return $ret;
    }
    public static function cat_recursive($kho=0,$parent=0,$except=false){
        $ret = array();
        $count = 0;
        foreach(self::child($kho,$parent,$except) as $child){
            if($except===false ||($except!==false && $child->id <> $except)){
                $ret[$count] = $child;
                $child2 = self::cat_recursive($kho,$child->id,$except);
                if($child2){
                    $ret = array_merge($ret,$child2);
                    //$ret += $child2;
                    $ret[$count]->con = count($child2);
                    $count += 1+ count($child2);
                }else{
                    $ret[$count]->con = 0;
                    $count++;
                }
            }
        }
        return $ret;
    }
    public static function list_select($kho=0,$parent=0,$except=false,$key='id',$val='ten'){
        $res = array();
        foreach(self::cat_recursive($kho=0,$parent,$except) as $v){
            $res[$v->$key] = str_repeat('--',$v->level).$v->$val;
        }
        return $res;
    }  
    public static function ten($id){
        $class = new self;
        $res = $class->find_by_id($id);
        if($res) return $res->ten;
        else return false;
    } 
}