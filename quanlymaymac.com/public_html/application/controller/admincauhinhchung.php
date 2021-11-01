<?php

class admincauhinhchung_controller extends controller{
    public function index(){
        $message = '';
        
        //var_dump(configuration::list_all());die();
        $employee = json_decode(session::get('login'));
        if(!isset($employee->avatar) || $employee->avatar=='') $employee->avatar = 'm1.png';
        
        if(!isset($employee->role)){
            url::redirect('customer'); die();
        }
        if($employee->role==3){
            url::redirect('employee'); die();
        }
        if($employee->role==2){
            url::redirect('admin'); die();
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
            'admincauhinhchung',             
            array(  
                 'configs'=>configuration::list_all(),
                 'employee'=>$employee,
                 'message'=>$message,
                 'title'=>"Adminstrator",
                 'h1'=>'Cấu hình chung',
                 
                 'all'=> $all,
            )            
        );
    }
     
}