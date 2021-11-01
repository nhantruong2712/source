<?php

class nhanvien_controller extends controller{
    public function index(){
        $message = '';
        
        if(!($employee=session::get('login'))){
            url::redirect('.');die();
        }
        
        $employee = json_decode($employee); 
        
        $login_type = (session::get('login_type'));
        // var_dump($login_type);die();
        if($login_type!='nhanvien'){
            url::redirect($login_type); die();
        }
        //var_dump($login_type,$employee);die(); 
        
        $kho = $employee->kho;
        common::vaitro($employee);
        
        if($login_type!='nhanvien' || empty($employee->vaitro)){
            url::redirect($login_type); die();
        }
        
        if(in_array('nhanvien',$employee->vaitro)){
            url::redirect('/nhanvien/cong/'.$employee->id);
        }else{
            url::redirect('/tiendo');
        }
         
        //render template
        /*view::render(
            'nhanvien',             
            array(  
                 'employee'=>$employee,
                 'message'=>$message,
                 'title'=>"Employee",
                 'h1'=>'Employee Control',
                   
                 
            )            
        );*/
    }
    
    public function lists(){ //var_dump('a');die();
        $message = '';
        
        if(!($employee=session::get('login'))){
            url::redirect('.');die();
        }
        
        $employee = json_decode($employee);
        $login_type = (session::get('login_type'));
        common::vaitro($employee);
         
        if($login_type!='admin' && $login_type!='doitacsanxuat' && $login_type!='nhanvien' &&
            !($login_type=='nhanvien' && !empty($employee->vaitro) && in_array('nhanvien',$employee->vaitro))){
            url::redirect($login_type); die();
        }
        $kho = $login_type=='doitacsanxuat'?$employee->id:$employee->kho;
        //$admins = nhanvien::list_all($kho);
        /*$admins = db::query("select distinct(nhanvien.id), nhanvien.*,(case when isnull(nhanviec.id) then 0 else 1 end) as nhanviec 
            from nhanvien 
            left join nhanviec on nhanviec.nhanvien=nhanvien.id 
            where nhanvien.kho = $kho");*/ //changed 06/04/2020
            
        $admins = db::query("select distinct(nhanvien.id), nhanvien.*
            from nhanvien             
            where nhanvien.kho = $kho");    
            
        if(!$admins) $admins = array();
        //var_dump($admins);die(); 
         
        //render template
        view::render(
            'nhanvien_list',             
            array(  
                 'employee'=>$employee,
                 'message'=>$message,
                 'title'=>"Administrator",
                 'h1'=>'Administrator Control',
                   
                 'admins'=>$admins,
            )            
        );
    }
    
    public function sanluong($nhanvien,$year=0,$month=0,$day=0){ //mac dinh la tiendo thang hien tai
        $message = '';  
        
        if(!($employee=session::get('login'))){
            url::redirect('.');die();
        }
        
        $employee = json_decode($employee); 
        common::vaitro($employee);
        
        $login_type = (session::get('login_type'));
         
        if(!($login_type=='admin' && $employee->kho>0 || $login_type=='doitacsanxuat' ||
            ($login_type=='nhanvien' && !empty($employee->vaitro) && in_array('nhanvien',$employee->vaitro)))){
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
        
        $nhanvien = nhanvien::get($nhanvien);
        
        //render template
        view::render(
            'nhanvien_sanluong_'.$type,             
            array(  
                 'employee'=>$employee,
                 'message'=>$message,
                 'title'=>"Administrator",
                 'h1'=>'Administrator Control',
                  
                 'kho'=>$kho,    
                 
                 'year'=>$year,
                 'month'=>$month,
                 'day' => $day,
                 'type'   => $type,
                 
                 'nhanvien'=>$nhanvien,         
            )            
        );
    }
    
    public function cong($nhanvien,$year=0,$month=0){  
        $message = '';  
        
        if(!($employee=session::get('login'))){
            url::redirect('.');die();
        }
        
        $employee = json_decode($employee); 
        common::vaitro($employee);
        
        $login_type = (session::get('login_type'));
         
        if(!($login_type=='admin' && $employee->kho>0 || $login_type=='doitacsanxuat'||
            ($login_type=='nhanvien' && !empty($employee->vaitro) && in_array('nhanvien',$employee->vaitro)))){
            url::redirect($login_type); die();
        }
        
        $kho = $login_type=='doitacsanxuat'?$employee->id:$employee->kho;
         
        if(!$year) $year = date('Y');
        if(!$month) $month = date('m');
         
        $nhanvien = nhanvien::get($nhanvien);
        
        //render template
        view::render(
            'nhanvien_cong',             
            array(  
                 'employee'=>$employee,
                 'message'=>$message,
                 'title'=>"Administrator",
                 'h1'=>'Administrator Control',
                  
                 'kho'=>$kho,    
                 
                 'year'=>$year,
                 'month'=>$month,
                  
                 'nhanvien'=>$nhanvien,         
            )            
        );
    }
    
    
    public function luong($nhanvien,$year=0,$month=0){  
        $message = '';  
        
        if(!($employee=session::get('login'))){
            url::redirect('.');die();
        }
        
        $employee = json_decode($employee); 
        common::vaitro($employee);
        
        $login_type = (session::get('login_type'));
         
        if(!($login_type=='admin' && $employee->kho>0 || $login_type=='doitacsanxuat'||
            ($login_type=='nhanvien' && !empty($employee->vaitro) && in_array('nhanvien',$employee->vaitro)))){
            url::redirect($login_type); die();
        }
        
        $kho = $login_type=='doitacsanxuat'?$employee->id:$employee->kho;
         
        if(!$year) $year = date('Y');
        if(!$month) $month = date('m');
        //var_dump($type);die();
        
        $nhanvien = nhanvien::get($nhanvien);
        
        //check luong hien tai
        $luong = db::query("select * from luong where nhanvien = ".$nhanvien->id." and nam =".$year." and thang=".$month);
        if($luong) $luong = $luong[0];
        
        //render template
        view::render(
            'nhanvien_luong',             
            array(  
                 'employee'=>$employee,
                 'message'=>$message,
                 'title'=>"Administrator",
                 'h1'=>'Administrator Control',
                  
                 'kho'=>$kho,    
                 
                 'year'=>$year,
                 'month'=>$month,
                  
                 'nhanvien'=>$nhanvien,    
                 
                 'luong'=>$luong,     
            )            
        );
    }
}