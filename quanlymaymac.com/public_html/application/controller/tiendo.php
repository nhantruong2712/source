<?php

class tiendo_controller extends controller{
     
    public function index($year=0,$month=0,$day=0){ //mac dinh la tiendo thang hien tai
        $message = '';  
        
        if(!($employee=session::get('login'))){
            url::redirect('.');die();
        }
        
        $employee = json_decode($employee); 
        common::vaitro($employee);
        
        $login_type = (session::get('login_type'));
         
        if(!($login_type=='admin' && $employee->kho>0 || $login_type=='doitacsanxuat' ||
            ($login_type=='nhanvien' && !empty($employee->vaitro) && in_array('tiendo',$employee->vaitro)))){
            url::redirect($login_type); die();
        }
        
        $kho = $login_type=='doitacsanxuat'?$employee->id:$employee->kho;
        
        if(!$year && !$month && !$day) $type = 'month';
        elseif($day) $type = 'day';
        elseif(!$month) $type = 'year';
        else $type = 'month';
        
        if(!$year) $year = date('Y');
        if($type == 'month' && !$month) $month = date('m');
        //var_dump($type);die();
         
        //render template
        view::render(
            'tiendo_'.$type,             
            array(  
                 'employee'=>$employee,
                 'message'=>$message,
                 'title'=>"Administrator",
                 'h1'=>'Administrator Control',
                  
                 'kho'=>$kho,    
                 
                 'year'=>$year,
                 'month'=>$month,
                 'day' => $day,
                 'type'   => $type         
            )            
        );
    }
    public function them($duan=0,$chuyen=0,$ngay=''){
        $message = '';
        
        if(!($employee=session::get('login'))){
            url::redirect('.');die();
        }
        
        $employee = json_decode($employee); 
        common::vaitro($employee);
        
        $login_type = (session::get('login_type'));
         
        if(!($login_type=='admin' && $employee->kho>0 || $login_type=='doitacsanxuat'||
            ($login_type=='nhanvien' && !empty($employee->vaitro) && in_array('tiendo',$employee->vaitro)))){
            url::redirect($login_type); die();
        }
        
        $kho = $login_type=='doitacsanxuat'?$employee->id:$employee->kho; //var_dump($kho);
        
        //$duan = duan::get($duan);
    
        
        //render template
        view::render(
            'tiendo_them',             
            array(  
                 'employee'=>$employee,
                 'message'=>$message,
                 'title'=>"Administrator",
                 'h1'=>'Administrator Control',
                  
                 'kho'=>$kho,
                 
                 //'duan'=>$duan, 
            )            
        );
    }
}