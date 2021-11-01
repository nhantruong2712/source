<?php

class index_controller extends controller{
    //05/23/2020
    public function con($cha=0){
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
         
        if(!$cha){
            url::redirect('.');die();
        }
        $cha =duan::get($cha);
        if(!$cha){
            url::redirect('.');die();
        }
        $cha->sanpham = sanpham::get($cha->sanpham);
        $cha->khachhang = doitac::get($cha->khachhang);
         
        $cha->duancon =  db::query("select * from duan where duan.cha = ".($cha->id)."");
         
        //render template
        view::render(
            'duan_con',             
            array(  
                 'employee'=>$employee,
                 'message'=>$message,
                 'title'=>"Administrator",
                 'h1'=>'Administrator Control',
                  
                 'kho'=>$kho,
                 
                 'model'=>$cha, 
            )            
        );
    }  
    public function them($cha=0){
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
        
        //05/23/2020
        if($cha){
            $cha = duan::get($cha);
            if(!$cha){
                url::redirect('.');die();
            }
            $cha->sanpham = sanpham::get($cha->sanpham);
            //chinh lai so luong - san pham hoan thanh cua kho - san pham cua cac du an con khac
            //1: select * from hoadon where type = 5 and ma like 'SL%'
            $hoanthanh = db::query("select sum(hoadon_chitiet.soluong) as hoanthanh 
                from hoadon_chitiet 
                where hoadon_chitiet.sanpham = -".($cha->sanpham->id)." and 
                hoadon_chitiet.hoadon in 
                    (select hoadon.id from hoadon 
                    where hoadon.type = 5 and hoadon.duan = ".($cha->id)." and hoadon.ma like 'SL%') 
                    group by hoadon_chitiet.sanpham");
            if($hoanthanh) $hoanthanh = $hoanthanh[0]->hoanthanh;
            else $hoanthanh = 0;
            //2: 
            $duancon = db::query("select sum(duan.soluong) as duancon 
                from duan where duan.cha = ".($cha->id)." group by duan.cha");
            if($duancon) $duancon = $duancon[0]->duancon;
            else $duancon = 0;  
            
            $cha->soluongtru = $hoanthanh + $duancon;  
        }
        
        //render template
        view::render(
            'duan_them',             
            array(  
                 'employee'=>$employee,
                 'message'=>$message,
                 'title'=>"Administrator",
                 'h1'=>'Administrator Control',
                  
                 'kho'=>$kho,
                 
                 'cha'=>$cha, 
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
        common::vaitro($employee); 
        
        $login_type = (session::get('login_type'));
         
        if(!($login_type=='admin' && $employee->kho>0 || $login_type=='doitacsanxuat'||
            ($login_type=='nhanvien' && !empty($employee->vaitro) && (in_array('tiendo',$employee->vaitro)||in_array('duan',$employee->vaitro))))){
            url::redirect($login_type); die();
        }
        
        $kho = $login_type=='doitacsanxuat'?$employee->id:$employee->kho;
        
        $pages=0;
        
        $more = "where duan.kho = $kho";
         
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
                if($admin->trangthai){
                    $admin->duancon = db::query("select count(duan.id) as count from duan where duan.cha = ".($admin->id)."");
                    $admin->duancon = $admin->duancon?$admin->duancon[0]->count:0;
                }else $admin->duancon = 0;
            }
        }
        //var_dump($admins);die(); 
        
        unset($_GET['__hth']);
        $a = http_build_query($_GET);
        $a = urldecode($a);
        //var_dump($a);die();
         
        //render template
        view::render(
            'duan',             
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
    
    public function dinhluong($cha){
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
        
         
        $cha = duan::get($cha);
        if(!$cha){
            url::redirect('.');die();
        }
        $cha->sanpham = sanpham::get($cha->sanpham);
        
        //hoan thanh cua ca cha va con
        $hoanthanh = db::query("select sum(hoadon_chitiet.soluong) as hoanthanh 
            from hoadon_chitiet 
            where hoadon_chitiet.sanpham = -".($cha->sanpham->id)." and 
            hoadon_chitiet.hoadon in 
                (select hoadon.id from hoadon 
                where hoadon.type = 5 and hoadon.duan in 
                    (select duan.id from duan 
                    where duan.cha = ".($cha->id)." or duan.id = ".($cha->id).") 
                and hoadon.ma like 'SL%') 
                group by hoadon_chitiet.sanpham");
        if($hoanthanh) $hoanthanh = $hoanthanh[0]->hoanthanh;
        else $hoanthanh = 0;
        
        $cha->hoanthanh = $hoanthanh; //var_dump($cha);
        
        //danh sach du an con
        $duancon = db::query("select duan.* from duan where duan.cha = ".($cha->id)."");
        $cha->duancon = $duancon;
        
        //dinh luong san pham
        $nhomdinhmuc = $cha->sanpham->nhom;
        if($nhomdinhmuc){
            $nhomdinhmuc = dinhmuc::list_all($nhomdinhmuc);
            if($nhomdinhmuc){
                foreach($nhomdinhmuc as &$dinhmuc){
                    $dinhmuc->sanpham = sanpham::get($dinhmuc->sanpham);
                    
                    //bo sung 05/25/2020
                    $checksoluong = db::query("select * from sanpham where (id={$dinhmuc->sanpham->id} or (cha={$dinhmuc->sanpham->id} and ma ='')) and kho=$kho");
                    if($checksoluong){
                        $checksoluong = $checksoluong[0]->soluong;
                    }else{
                        $checksoluong = 0;
                    }
                    $dinhmuc->sanpham->soluong = $checksoluong;
                    
                    $spcha = false;
                    if($dinhmuc->sanpham->cha>0){
                        
                        /*$spcha = db::query("select * from sanpham 
                            where sanpham.nhom = 
                            (select dm2.nhom from dinhmuc dm2 
                                where dm2.sanpham = ".($dinhmuc->sanpham->cha)." and 1=
                                    (select count(dm.id) from dinhmuc dm where dm.nhom = dm2.nhom)
                            )");*/
                        $nhom = db::query("select dm2.* from dinhmuc dm2 
                                where dm2.sanpham = ".($dinhmuc->sanpham->cha)." and 1=
                                    (select count(dm.id) from dinhmuc dm where dm.nhom = dm2.nhom)");
                        if($nhom){
                            $nhom = $nhom[0];
                            $spcha = db::query("select * from sanpham 
                                where sanpham.ma!='' and sanpham.nhom = ".($nhom->nhom)."");    
                            
                            if($spcha){
                                foreach($spcha as $key=>&$sp){
                                    //neu so luong <=0 thi k xet
                                    $checksoluong = db::query("select * from sanpham where (id={$sp->id} or (cha={$sp->id} and ma = '')) and kho=$kho");
                                    if(!$checksoluong){
                                        //unset($spcha[$key]);
                                        //continue;
                                        $checksoluong = array((object)array('soluong'=>0));//06/09/2020
                                    }
                                    /* bo vi con xet ca nhap hang nua, chu k phai moi xuat hang nhu truoc: 06/09/2020
                                    if($checksoluong[0]->soluong<=0){
                                        unset($spcha[$key]);
                                        continue;
                                    }*/
                                    
                                    //bo sung 05/25/2020
                                    $sp->soluong = $checksoluong[0]->soluong;
                                    
                                    //xem co dinh luong rieng hay k
                                    $dinhluongrieng = db::query("select * from dinhmuc 
                                        where cha = ".$sp->id); //." and sanpham=".($dinhmuc->sanpham->id)
                                        
                                    if($dinhluongrieng && count($dinhluongrieng)==1 && $dinhluongrieng[0]->sanpham==$dinhmuc->sanpham->id)    
                                        $sp->dinhluong = $dinhluongrieng[0]->soluong;
                                    else    
                                        $sp->dinhluong = $nhom->soluong;
                                }
                            }
                        }                                        
                        
                    }
                    if(!$spcha){
                        $spcha = db::query("select * from sanpham 
                            where sanpham.ma!='' and sanpham.cha = ".($dinhmuc->sanpham->id)."");    
                        
                        //bo sung 05/25/2020    
                        if($spcha){
                            foreach($spcha as $key=>&$sp){
                                //neu so luong <=0 thi k xet
                                $checksoluong = db::query("select * from sanpham where (id={$sp->id} or (cha={$sp->id} and ma = '')) and kho=$kho");
                                if(!$checksoluong){
                                    //unset($spcha[$key]);
                                    //continue;
                                    $checksoluong = array((object)array('soluong'=>0));//06/09/2020
                                }
                                /* removed 06/09/2020
                                if($checksoluong[0]->soluong<=0){
                                    unset($spcha[$key]);
                                    continue;
                                }   
                                */                             
                                $sp->soluong = $checksoluong[0]->soluong;                                    
                            }
                        }                                                 
                    }
                    
                    //06/01/2020
                    if(!$spcha && $dinhmuc->sanpham->nhom){ 
                        
                        //tim dinh muc rieng cua $dinhmuc->sanpham->id
                        $nhom = db::query("select dm2.* from dinhmuc dm2 
                                where dm2.cha = ".($dinhmuc->sanpham->id)." and 1=
                                    (select count(dm.id) from dinhmuc dm where dm.cha = dm2.cha)"); //var_dump($nhom);die();
                        
                        $nhom2 = db::query("select dm2.* from dinhmuc dm2 
                                where dm2.nhom = ".($dinhmuc->sanpham->nhom)." and 1=
                                    (select count(dm.id) from dinhmuc dm where dm.nhom = dm2.nhom)"); //var_dump($dinhmuc->sanpham);die();
                        
                        if(!$nhom) $nhom = $nhom2;
                        
                        if($nhom){
                            $nhom = $nhom[0];
                            $spcha = db::query("select * from sanpham 
                                where sanpham.ma!='' and sanpham.nhom = ".($dinhmuc->sanpham->nhom)." and sanpham.id!=".$dinhmuc->sanpham->id);    //var_dump($spcha);die();
                            
                            if($spcha){
                                foreach($spcha as $key=>&$sp){
                                    //neu so luong <=0 thi k xet
                                    $checksoluong = db::query("select * from sanpham where (id={$sp->id} or (cha={$sp->id} and ma = '')) and kho=$kho");
                                    if(!$checksoluong){
                                        //unset($spcha[$key]);
                                        //continue;
                                        $checksoluong = array((object)array('soluong'=>0));//06/09/2020
                                    }
                                    /* removed /06/09/2020
                                    if($checksoluong[0]->soluong<=0){
                                        unset($spcha[$key]);
                                        continue;
                                    }
                                    */
                                     
                                    $sp->soluong = $checksoluong[0]->soluong;
                                    
                                    //xem co dinh luong rieng hay k
                                    $dinhluongrieng = db::query("select * from dinhmuc 
                                        where cha = ".$sp->id); //." and sanpham=".($dinhmuc->sanpham->id)
                                    //var_dump($dinhluongrieng);die();    
                                    if($dinhluongrieng && count($dinhluongrieng)==1) // && $dinhluongrieng[0]->sanpham==$nhom->sanpham   
                                        $sp->dinhluong = $dinhluongrieng[0]->soluong/$nhom->soluong;
                                    else    
                                        $sp->dinhluong = $nhom2[0]->soluong/$nhom->soluong;
                                }
                            }
                        }  
                    }
                    
                    //06/05/2020
                    if(!$spcha){
                        
                        $tbl = 'tbl'.time().rand(100,999);
                        /*
                        $spcha = db::query("select * from 
                            (select sanpham.ten,sanpham.ma,y.*,
                                (case when y.cha>0 then 0 else 1 end) as s, 
                                sanpham.id as sanphamid, 
                                (case when sanpham.kho=$kho then sanpham.soluong else (select sp.soluong from sanpham sp where sp.kho=$kho and sp.ma='' and sp.cha=sanpham.id) end) as slsp 
                                from (select dinhmuc.*, 
                                (case when dinhmuc.cha>0 then (select count(dm.id) from dinhmuc dm where dm.cha=dinhmuc.cha) else (select count(dm2.id) from dinhmuc dm2 where dm2.nhom=dinhmuc.nhom) end) as x 
                                from dinhmuc where dinhmuc.sanpham = ".($dinhmuc->sanpham->id)." having x=1) y 
                                inner join sanpham on (sanpham.nhom = y.nhom and y.nhom>0) or sanpham.id = y.cha order by s) j 
                                group by j.sanphamid");    */
                        
                        //changed 06/09/2020
                        db::query("CREATE TEMPORARY TABLE $tbl select sanpham.ten,sanpham.ma,y.*,
                                (case when y.cha>0 then 0 else 1 end) as s, 
                                sanpham.id as sanphamid, 
                                (case when sanpham.kho=$kho then sanpham.soluong else (select sp.soluong from sanpham sp where sp.kho=$kho and sp.ma='' and sp.cha=sanpham.id) end) as slsp 
                                from (select dinhmuc.*, 
                                (case when dinhmuc.cha>0 then (select count(dm.id) from dinhmuc dm where dm.cha=dinhmuc.cha) else (select count(dm2.id) from dinhmuc dm2 where dm2.nhom=dinhmuc.nhom) end) as x 
                                from dinhmuc where dinhmuc.sanpham = ".($dinhmuc->sanpham->id)." having x=1) y 
                                inner join sanpham on (sanpham.nhom = y.nhom and y.nhom>0) or sanpham.id = y.cha order by s");
                        $spcha = db::query("select * from $tbl group by sanphamid");        
                        
                        if($spcha){
                            foreach($spcha as $key=>&$sp){                                                                                              
                                $sp->dinhluong =  $sp->soluong;      
                                $sp->soluong = $sp->slsp;     
                                $sp->id = $sp->sanphamid;                                
                            }
                        }                                                 
                    }
                    
                    $dinhmuc->con = $spcha;
                }
            }
        }
        
        $cha->dinhmuc = $nhomdinhmuc;
        
        //var_dump($cha->dinhmuc);die();
        
        //06/09/2020
        $nhacungcap = doitac::list_all($kho,1);
         
        //render template
        view::render(
            'duan_dinhluong',             
            array(  
                 'employee'=>$employee,
                 'message'=>$message,
                 'title'=>"Administrator",
                 'h1'=>'Administrator Control',
                  
                 'kho'=>$kho,                 
                 'cha'=>$cha, 
                 
                 'nhacungcap'=>$nhacungcap, //06/09/2020
            )            
        );
    } 
}