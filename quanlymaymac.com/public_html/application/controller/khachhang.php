<?php

class khachhang_controller extends controller{
     
    public function index(){ //var_dump('a');die();
        $message = '';
        
        if(!($employee=session::get('login'))){
            url::redirect('.');die();
        }
        
        $employee = json_decode($employee); 
        
        $login_type = (session::get('login_type'));
         
        if(!($login_type=='admin' && $employee->kho>0 || $login_type=='doitacsanxuat')){
            url::redirect($login_type); die();
        }
        
        //05/27/2020
        $kho = $login_type=='doitacsanxuat'?$employee->id:$employee->kho; //var_dump($login_type,$employee->kho);
        $doitac = false;
        if($login_type=='doitacsanxuat')
            $doitac = $employee->kho;
        if($login_type=='admin' && $employee->kho>0){
            $doitac = doitac::get($employee->kho);
            $doitac = $doitac->kho;
        }
        if($doitac){
            $doitac = doitac::get($doitac); //var_dump($doitac);
            if($doitac && $kho==0) $doitac = false;
        }
        
        $admins = doitac::list_all($kho,3);
        if(!$admins) $admins = array();
        //var_dump($admins);die(); 
        
        //05/27/2020
        if($doitac) array_unshift($admins,$doitac); //var_dump($admins);die(); 
         
        //render template
        view::render(
            'khachhang',             
            array(  
                 'employee'=>$employee,
                 'message'=>$message,
                 'title'=>"Administrator",
                 'h1'=>'Administrator Control',
                   
                 'admins'=>$admins,
            )            
        );
    }
}