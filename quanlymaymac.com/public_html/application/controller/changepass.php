<?php
class changepass_controller extends controller{
    public function index(){
        $message = '';
        
        $employee = json_decode(session::get('login'));
        if(!isset($employee->avatar) || $employee->avatar=='') $employee->avatar = 'm1.png';
        
        //var_dump($employee);die();
        if($employee->role==3){
            url::redirect('employee'); die();
        }
        
        //var_dump($employee);die();
        if(!isset($employee->role)){
            url::redirect('customer'); die();
        }
        if($employee->role==3){
            url::redirect('employee'); die();
        }
        
        //tat ca employee va customer
        $employees = employee::list_all();
        
        foreach($employees as &$e){
            $e = (object) array(
                'id'=>'e'.$e->id,
                'name'=>$e->name,
                'avatar'=>$e->avatar?$e->avatar:'m1.png',
                'role'=>$e->role,
            );
            //$e->type = 'employee';             
        }
        
        $customers = customer::list_all();
        
        foreach($customers as &$e){
            $e = (object) array(
                'id'=>'c'.$e->id,
                'name'=>$e->name,
                'avatar'=>$e->avatar?$e->avatar:'m1.png',
                'role'=>-1,
            );
            //$e->type = 'customer';
        }
        
        //var_dump(array_merge($customers,$employees));die();
        $all = array();
        foreach(array_merge($customers,$employees) as $x){
            $all[$x->id] = $x;
        }
         
        //render template
        view::render(
            'changepass',             
            array(  
                 'employee'=>$employee,
                 'message'=>$message,
                 'title'=>"Quản Trị",
                 'h1'=>'Thay Đổi Mật Khẩu',
                 
                 'all'=> $all,
            )            
        );
    }
     
}