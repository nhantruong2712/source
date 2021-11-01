<?php

class nhomcongdoan_controller extends controller{
     
    public function index(){ //var_dump('a');die();
        $message = '';
        
        if(!($employee=session::get('login'))){
            url::redirect('.');die();
        }
        
        $employee = json_decode($employee); 
        
        $login_type = (session::get('login_type'));
         
        //if($login_type!='admin' && $login_type!='doitacsanxuat'){
        if(!(($login_type=='admin'&&$employee->kho>0) || $login_type=='doitacsanxuat')){    
            url::redirect($login_type); die();
        }
        
        //$admins = nhomcongdoan::list_all($employee->kho);
        //var_dump($admins);die(); 
         
        //render template
        view::render(
            'nhomcongdoan',             
            array(  
                 'employee'=>$employee,
                 'message'=>$message,
                 'title'=>"Administrator",
                 'h1'=>'Administrator Control',
                   
                 //'admins'=>$admins,
            )            
        );
    }
}