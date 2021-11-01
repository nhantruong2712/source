<?php

class congdoan_controller extends controller{
     
    public function index($page=1,$nhom=0){  
        $message = '';
        
        if(!($employee=session::get('login'))){
            url::redirect('.');die();
        }
        
        $employee = json_decode($employee); 
        
        $login_type = (session::get('login_type'));
         
        if(!($login_type=='admin' && $employee->kho>0 || $login_type=='doitacsanxuat')){
            url::redirect($login_type); die();
        }
        
        $kho = $login_type=='doitacsanxuat'?$employee->id:$employee->kho; //var_dump($kho);die();
        
        //$admins = congdoan::list_all($kho);
        
        //06/03/2020
        $add='order by congdoan.id desc';
        if($page=='nhom' && $nhom>0){
            $add = "where congdoan.nhom = $nhom and congdoan.kho = $kho ".$add; //edit: 06/06/2020
        }else{
            $add = "where congdoan.kho = $kho ".$add; //06/06/2020
        }
        $admins = congdoan::list_page($nhom>0?1:$page,$nhom>0?1000:20,$add,$pages);
        
        if(!$admins) $admins = array();
        //var_dump($admins);die(); 
         
        //render template
        view::render(
            'congdoan',             
            array(  
                 'employee'=>$employee,
                 'message'=>$message,
                 'title'=>"Administrator",
                 'h1'=>'Administrator Control',
                   
                 'admins'=>$admins,
                 
                 'page'=>$nhom>0?1:$page,
                 'pages'=>$nhom>0?1:$pages,
            )            
        );
    }
}