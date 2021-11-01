<?php

class xuathang_controller extends controller{
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
            'xuathang_them',             
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
            //var_dump(input::get('filter'));die();
            if(!empty($filter['range'])){
                $range = $filter['range'];    
                $range = explode("-",$range);
                //var_dump($range);die();
                //$range = array_map('strtotime',$range);
            }
            if(!empty($filter['status'])){
                $status = $filter['status'];                     
            }
            
            if(!empty($filter['product'])){
                $product = $filter['product'];                     
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
        
        $more = "where hoadon.type=3 and hoadon.kho = $kho";
         
        if(!empty($product)){
            //$more .= " and (ten like '%$product%' or ma like '%$product%')";
            $more = "inner join hoadon_chitiet on hoadon.id=hoadon_chitiet.hoadon 
                inner join sanpham on sanpham.id=hoadon_chitiet.sanpham 
                and (sanpham.ten like '%$product%' or sanpham.ma like '%$product%') ".$more;
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
        }
        
        $admins = hoadon::list_page($page,20,"$more order by hoadon.id desc",$pages);
        if(!$admins) $admins = array();
        //var_dump($admins);die(); 
        
        unset($_GET['__hth']);
        $a = http_build_query($_GET);
        $a = urldecode($a);
        //var_dump($a);die();
         
        //render template
        view::render(
            'xuathang',             
            array(  
                 'employee'=>$employee,
                 'message'=>$message,
                 'title'=>"Administrator",
                 'h1'=>'Administrator Control',
                   
                 'admins'=>$admins,
                 'kho'=>$kho,
                 //'categories'=>empty($categories)?array():($categories),
                 'filter'=>$a,
                 
                 'page'=>$page,
                 'pages'=>$pages
            )            
        );
    }
}