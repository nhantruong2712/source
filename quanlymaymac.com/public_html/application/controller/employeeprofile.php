<?php

class employeeprofile_controller extends controller{
    public function index(){
        $message = '';
        
        $employee = json_decode(session::get('login'));
        if(!isset($employee->avatar) || $employee->avatar=='') $employee->avatar = 'm1.png';
        
        //var_dump($employee);die();
        if($employee->role!=3){
            url::redirect('admin'); die();
        }
        
        $x = glob('assets/demo/avatar/*');
        $x = array_map(function($a){
            $y = explode("/",$a);
            return array_pop($y);
        },$x);
        //var_dump($x);die();
        
        //tat ca employee  
        $employees = employee::list_all(' where role in (1,2) or id = "'.$employee->id.'" ');
        
        foreach($employees as &$e){
            $e = (object) array(
                'id'=>'e'.$e->id,
                'name'=>$e->name,
                'avatar'=>$e->avatar?$e->avatar:'m1.png',
                'role'=>$e->role,
            );
            //$e->type = 'employee';             
        }
        
        $all = array();
        foreach($employees as $x2){
            $all[$x2->id] = $x2;
        }
                     
        //render template
        view::render(
            'employeeprofile',             
            array(  
                 'employee'=>$employee,
                 'message'=>$message,
                 'title'=>"Nhân viên",
                 'h1'=>'Thông Tin Cá Nhân',
                 'avatars'=>$x,  
                 
                 'all'=> $all,
            )            
        );
    }
     
}