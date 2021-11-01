<?php

class test_controller extends controller{
    public function index(){
        //$model = model::load(array('a'=>1,'b'=>2),'sanpham');
        //unset($model->b);
        
        $model = new date(time());
        $model =$model->format('d/m/Y');
        
        // $model.'';
        
        var_dump($model);
    }
}