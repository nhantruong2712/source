<?php

class configuration extends model{
    var $table = 'configuration';
    
    //preload
    public static function run(){
        $model = new self;
        $all = $model->all();
        foreach($all as $a){
            if(!defined($a->key)) define($a->key,$a->value);
        }    
    }
    
    public static function read($key){
        $model = new self;
        $model = $model->find_by_key($key);
        return $model->value;
    }
    
    public static function list_all(){
        $class = new self;
 
        return $class->all();
    } 
}