<?php

class chuyen_controller extends controller{
     
    public function them($duan){
        $message = '';
        
        if(!($employee=session::get('login'))){
            url::redirect('.');die();
        }
        
        $employee = json_decode($employee); 
        
        $login_type = (session::get('login_type'));
         
        if(!($login_type=='admin' && $employee->kho>0 || $login_type=='doitacsanxuat')){
            url::redirect($login_type); die();
        }
        
        $kho = $login_type=='doitacsanxuat'?$employee->id:$employee->kho;
        
        $duan = duan::get($duan);
        if(!$duan){
            url::redirect('.');die();
        }
        if($duan->kho != $kho){
            url::redirect('.');die();
        }
        
        $congdoan = congdoan::list_all($kho ,0);
        
        //06/03/2020
        $nhomcongdoan = nhomcongdoan::list_all($kho);
        
        //06/06/2020 tinh tong so luong cac chuyen cu
        $tong = db::query("select sum(soluong) as tong from chuyen where duan = ".$duan->id);
        if($tong) $tong = $tong[0]->tong;
        else $tong = 0;        
        $duan->soluongconlai = $duan->soluong - $tong;
        
        //06/10/2020 tinh tong so luong cac du an con
        $tong = db::query("select sum(soluong) as tong from duan where cha = ".$duan->id);
        if($tong) $tong = $tong[0]->tong;
        else $tong = 0;        
        $duan->soluongconlai = $duan->soluong - $tong;
        
        //render template
        view::render(
            'chuyen_them',             
            array(  
                 'employee'=>$employee,
                 'message'=>$message,
                 'title'=>"Administrator",
                 'h1'=>'Administrator Control',
                  
                 'kho'=>$kho,
                 'duan'=>$duan,  
                 'congdoan'=>$congdoan,
                 
                 'nhomcongdoan'=>$nhomcongdoan,//06/03/2020
            )            
        );
    } 
    public function index($duan){ 
        $message = '';
        
        if(!($employee=session::get('login'))){
            url::redirect('.');die();
        }
         
        $employee = json_decode($employee); 
        
        $login_type = (session::get('login_type'));
         
        if(!($login_type=='admin' && $employee->kho>0 || $login_type=='doitacsanxuat')){
            url::redirect($login_type); die();
        }
        
        $kho = $login_type=='doitacsanxuat'?$employee->id:$employee->kho;
        
        $duan = duan::get($duan);
        if(!$duan){
            url::redirect('.');die();
        }
         
        $admins = chuyen::list_all($duan->id);
        if(!$admins) $admins = array();
        //var_dump($admins);die(); 
        
        //06/06/2020 tinh tong so luong cac chuyen cu
        $tong = array_sum(array_map(function($a){return $a->soluong;},$admins));
        $duan->soluongconlai = $duan->soluong - $tong;
         
        //render template
        view::render(
            'chuyen',             
            array(  
                 'employee'=>$employee,
                 'message'=>$message,
                 'title'=>"Administrator",
                 'h1'=>'Administrator Control',
                   
                 'admins'=>$admins,
                 'kho'=>$kho,
                 
                 'duan'=>$duan, 
            )            
        );
    }
}