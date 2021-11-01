<?php

class thuchi_controller  extends controller{
    public function them(){
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
        
        //render template
        view::render(
            'thuchi_them',             
            array(  
                 'employee'=>$employee,
                 'message'=>$message,
                 'title'=>"Administrator",
                 'h1'=>'Administrator Control',
                  
                 'kho'=>$kho,
                  
            )            
        );
    } 
    public function index($page=1){ //var_dump('a');die();
        $message = '';
        
        if(!($employee=session::get('login'))){
            url::redirect('.');die();
        }
        $filter = input::get('filter');
        if($filter){
             
            if(!empty($filter['range'])){
                $range = $filter['range'];    
                $range = explode("-",$range);
                 
            }
            if(!empty($filter['status'])){
                $status = $filter['status'];                     
            }
            
            if(!empty($filter['project'])){
                $product = $filter['project'];                     
            }
            if(!empty($filter['note'])){
                $note = $filter['note'];                     
            }
        }
        
        $employee = json_decode($employee); 
        
        $login_type = (session::get('login_type'));
         
        if(!($login_type=='admin' && $employee->kho>0 || $login_type=='doitacsanxuat')){
            url::redirect($login_type); die();
        }
        
        $kho = $login_type=='doitacsanxuat'?$employee->id:$employee->kho;
        
        $pages=0;
        
        //$more = "where hoadon.type in (2,4) and (hoadon.kho = $kho or hoadon.doitac = $kho)"; // 05/24/2020: bo sung or hoadon.doitac = $kho
        $more = "where hoadon.type in (2,4) and (hoadon.kho = $kho)"; //bo vao ngay 27
         
        if(!empty($product)){
            $more .= " and hoadon.duan = '$product'";             
        }
        
        if(!empty($note)){
            $more .= " and hoadon.ghichu like '%$note%'";
        }
        if(!empty($status)){
            $more .= " and hoadon.trangthai like '%$status%'";
        }
        if(!empty($range)){  
            $range2 = array_map(function($a){return date::totime($a,"/^(\d{2})\/(\d{2})\/(\d{4})$/",array(2,1,3));},$range);
            //var_dump($range2);die();
            $more .= " and hoadon.ngay >= {$range2[0]} and hoadon.ngay < ".($range2[1]+86400)."";
        } //echo $more;die();
        
        $admins = hoadon::list_page($page,20,"$more order by hoadon.id desc",$pages);
        if(!$admins) $admins = array();
        //var_dump($admins);die();
        
        //05/27/2020
        foreach($admins as &$admin){
            if($admin->cha>0){
                $cha = hoadon::get($admin->cha);
                if($cha && $cha->cha>0){
                    $admin->notAllow = 1;
                }
            }
        } 
        
        unset($_GET['__hth']);
        $a = http_build_query($_GET);
        $a = urldecode($a);
        //var_dump($a);die();
         
        //render template
        view::render(
            'thuchi',             
            array(  
                 'employee'=>$employee,
                 'message'=>$message,
                 'title'=>"Administrator",
                 'h1'=>'Administrator Control',
                   
                 'admins'=>$admins,
                 'kho'=>$kho,
                  
                 'filter'=>$a,
                 
                 'page'=>$page,
                 'pages'=>$pages
            )            
        );
    }
}