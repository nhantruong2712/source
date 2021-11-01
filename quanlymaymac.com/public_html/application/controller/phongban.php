<?php

class phongban_controller extends controller{
     
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
        
        $admins = phongban::list_all($login_type=='doitacsanxuat'?$employee->id:$employee->kho);
        if(!$admins) $admins = array();
        //var_dump($admins);die(); 
         
        //render template
        view::render(
            'phongban',             
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