<?php

class profile_controller extends controller{
    public function index(){
        $message = '';
        
        if(!($employee=session::get('login'))){
            url::redirect('.');die();
        }
        
        $employee = json_decode($employee); //var_dump($employee);die();
        $login_type = (session::get('login_type'));
        if($login_type!='admin' && $login_type!='doitacsanxuat'){
            url::redirect($login_type); die();
        }
        
        if(input::post('email')){
            //var_dump($_POST);die();
            if(($in = input::post('password'))){
                $_POST['password'] = md5($_POST['password']);
            }else{
                unset($_POST['password']);
            }
            $model = model::load($_POST,$login_type=='admin'?'admin':'doitac');
            $model->id = session::get('login_id');
            //var_dump($model);die();
            $employee = model::extend($employee, $model);
            session::set('login',json_encode($employee->attributes()));
            $model->update();
        }
                     
        //render template
        view::render(
            'profile',             
            array(  
                 'employee'=>$employee,
                 'message'=>$message,
                 'title'=>"Administrator",
                 'h1'=>'Thông Tin Cá Nhân',
                  
            )            
        );
    }
     
}