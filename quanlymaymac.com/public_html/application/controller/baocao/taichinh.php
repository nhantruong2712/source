<?php

class taichinh_controller  extends controller{
    public function index($page=1){  
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
             
            if(!empty($filter['product'])){
                $product = $filter['product'];                     
            }
            if(!empty($filter['note'])){
                $note = $filter['note'];                     
            }
            if(!empty($filter['status'])){
                $status = $filter['status'];                     
            }
        }
        
        $employee = json_decode($employee); 
        
        $login_type = (session::get('login_type'));
         
        if(!($login_type=='admin' && $employee->kho>0 || $login_type=='doitacsanxuat')){
            url::redirect($login_type); die();
        }
        
        $kho = $login_type=='doitacsanxuat'?$employee->id:$employee->kho;
        
        $pages=0;
        
        $more = "where duan.kho = $kho and duan.trangthai in (1,2) ";
         
        if(!empty($product)){
             
            $more = "inner join sanpham on sanpham.id=duan.sanpham 
                and (sanpham.ten like '%$product%' or sanpham.ma like '%$product%') ".$more;
        }
        
        if(!empty($note)){
            $more .= " and duan.ghichu like '%$note%'";
        }
        if(!empty($status)){
            $more .= " and duan.trangthai like '%$status%'";
        } 
        if(!empty($range)){  
            $range2 = array_map(function($a){return date('Y-m-d',date::totime($a,"/^(\d{2})\/(\d{2})\/(\d{4})$/",array(2,1,3)));},$range);
            //var_dump($range2);die();
            $more .= " and duan.ngay >= '".$range2[0]." 00:00:00' and duan.ngay <= '".$range2[1]." 23:59:59'";
        }
        
        $admins = duan::list_page($page,20,"$more order by duan.id desc",$pages);
        if(!$admins) $admins = array();
        else{
            foreach($admins as &$admin){
                //thu
                
                //chi
                $thuchi = db::query("select sum(case hoadon.type where 2 then hoadon.tong else 0 end) as chi,
                    sum(case hoadon.type where 4 then hoadon.tong else 0 end) as thu
                    where hoadon_chitiet
                    inner join hoadon 
                    on hoadon.id = hoadon_chitiet.hoadon and hoadon.type in (2,4) 
                        and hoadon.duan = ".$admin->id);
                if($thuchi){
                    $admin->thu = $thuchi[0]->thu;
                    $admin->chi = $thuchi[0]->chi;
                }else{
                    $admin->thu = 0;
                    $admin->chi = 0;
                }
                 
            }
        }
        //var_dump($admins);die(); 
        
        unset($_GET['__hth']);
        $a = http_build_query($_GET);
        $a = urldecode($a);
        //var_dump($a);die();
         
        //render template
        view::render(
            'baocao_taichinh',             
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