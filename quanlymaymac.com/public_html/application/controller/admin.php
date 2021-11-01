<?php

class admin_controller extends controller{
    public function index(){
        $message = '';
        
        if(!($employee=session::get('login'))){
            url::redirect('.');die();
        }
        
        $employee = json_decode($employee); 
        
        $login_type = (session::get('login_type'));
         
        if($login_type!='admin'){
            url::redirect($login_type); die();
        }
        
        $kho = $employee->kho;
        
        //var_dump($employee,$login_type);die(); 
        
        //tong so admin
        $model = new admin;
        $infos = array(
            'admin'=>$model->count(array(                 
                array('kho','=',$kho)                 
            ))
        );  
        $infos['admins'] = $model->all(array(
            'conditions'=>array(                 
                array('kho','=',$kho)                 
            ),
            'order' => array('id','desc'),
            'limit' => 5,
        ));//var_dump($infos);die(); 
        
        //tong so admin
        $model = new admin;
        $infos = array(
            'admin'=>$model->count(array(                 
                array('kho','=',$kho)                 
            ))
        );  
        $infos['admins'] = $infos['admin']?$model->all(array(
            'conditions'=>array(                 
                array('kho','=',$kho)                 
            ),
            'order' => array('id','desc'),
            'limit' => 5,
        )):array();//var_dump($infos);die(); 
        
        //tong so doi tac san xuat
        $model = new doitac;
        $infos['doitacsanxuat'] = $model->count(array(                 
            array('kho','=',$kho)  ,
            array('vaitro','=',2)               
        ));
        $infos['doitacsanxuats'] = $infos['doitacsanxuat']?$model->all(array(
            'conditions'=>array(                 
                array('kho','=',$kho) ,
                array('vaitro','=',2)                     
            ),
            'order' => array('id','desc'),
            'limit' => 5,
        )):array();
        
        if($kho>0){
            $infos['nhacungcap'] = $model->count(array(                 
                array('kho','=',$kho)  ,
                array('vaitro','=',1)               
            ));
            $infos['nhacungcaps'] = $infos['nhacungcap']?$model->all(array(
                'conditions'=>array(                 
                    array('kho','=',$kho) ,
                    array('vaitro','=',1)                     
                ),
                'order' => array('id','desc'),
                'limit' => 5,
            )):array();
            $infos['khachhang'] = $model->count(array(                 
                array('kho','=',$kho)  ,
                array('vaitro','=',3)               
            ));
            $infos['khachhangs'] = $infos['khachhang']?$model->all(array(
                'conditions'=>array(                 
                    array('kho','=',$kho) ,
                    array('vaitro','=',3)                     
                ),
                'order' => array('id','desc'),
                'limit' => 5,
            )):array();
            
            $doitac = 0;
            if($login_type=='doitacsanxuat')
                $doitac = $employee->kho;
            if($login_type=='admin' && $employee->kho>0){
                $doitac = doitac::get($employee->kho);
                $doitac = $doitac->kho;
            }
            if($doitac){
                $doitac = doitac::get($doitac); //var_dump($doitac);
                if($doitac && $kho==0) $doitac = 0;
            }
            $infos['khachhang'] += $doitac?1:0;
            
            //tong so du an dang trien khai
            $model = new duan;
            $infos['duan'] = $model->count(array(                 
                array('kho','=',$kho)  ,
                array('trangthai','=',1)               
            ));
             
            $infos['duans'] = $infos['duan']?db::query("select duan.*,sanpham.ten as sanphamten from duan 
                inner join sanpham on sanpham.id = duan.sanpham 
                where duan.kho = $kho and duan.trangthai = 1 
                order by duan.ngay desc limit 5"):array();
            
            //var_dump($infos);die(); 
        }
         
        //render template
        view::render(
            'admin',             
            array(  
                 'employee'=>$employee,
                 'message'=>$message,
                 'title'=>"Administrator",
                 'h1'=>'Administrator Control',
                   
                 'infos'=>$infos,
                 'kho'=>$kho,
            )            
        );
    }
    
    public function lists(){ //var_dump('a');die();
        $message = '';
        
        $employee = json_decode(session::get('login')); //var_dump($employee);die();
        $login_type = (session::get('login_type'));
         
        if($login_type!='admin' && $login_type!='doitacsanxuat'){
            url::redirect($login_type); die();
        }
        
        $admins = admin::list_all(empty($employee->vaitro)?$employee->kho:$employee->id);
        //var_dump($admins);die(); 
        if(!$admins) $admins = array();
         
        //render template
        view::render(
            'admin_list',             
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