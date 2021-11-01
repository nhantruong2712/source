<?php

class tiendo_controller extends controller{
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
        }
        
        $employee = json_decode($employee); 
        
        $login_type = (session::get('login_type'));
         
        if(!($login_type=='admin' && $employee->kho>0 || $login_type=='doitacsanxuat')){
            url::redirect($login_type); die();
        }
        
        $kho = $login_type=='doitacsanxuat'?$employee->id:$employee->kho;
        
        $pages=0;
        
        $more = "where duan.kho = $kho and duan.trangthai = 1 ";
         
        if(!empty($product)){
             
            $more = "inner join sanpham on sanpham.id=duan.sanpham 
                and (sanpham.ten like '%$product%' or sanpham.ma like '%$product%') ".$more;
        }
        
        if(!empty($note)){
            $more .= " and duan.ghichu like '%$note%'";
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
                //hoan thanh cua ca cha va con
                $hoanthanh = db::query("select sum(hoadon_chitiet.soluong) as hoanthanh 
                    from hoadon_chitiet 
                    where hoadon_chitiet.sanpham = -".($admin->sanpham)." and 
                    hoadon_chitiet.hoadon in 
                        (select hoadon.id from hoadon 
                        where hoadon.type = 5 and hoadon.duan in 
                            (select duan.id from duan 
                            where duan.cha = ".($admin->id)." or duan.id = ".($admin->id).") 
                        and hoadon.ma like 'SL%') 
                        group by hoadon_chitiet.sanpham");
                if($hoanthanh) $hoanthanh = $hoanthanh[0]->hoanthanh;
                else $hoanthanh = 0;
                
                $admin->hoanthanh = $hoanthanh;
            }
        }
        //var_dump($admins);die(); 
        
        unset($_GET['__hth']);
        $a = http_build_query($_GET);
        $a = urldecode($a);
        //var_dump($a);die();
         
        //render template
        view::render(
            'baocao_tiendo',             
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