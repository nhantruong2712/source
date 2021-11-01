<?php

class cat_controller extends controller{
    public function sua($cuonvai){
        $message = '';
        
        if(!($employee=session::get('login'))){
            url::redirect('.');die();
        }
         
        $employee = json_decode($employee); 
        common::vaitro($employee); 
        
        $login_type = (session::get('login_type')); //var_dump($employee,$login_type);die();
         
        if(!($login_type=='admin' && $employee->kho>0 || $login_type=='doitacsanxuat'||
            ($login_type=='nhanvien' && !empty($employee->vaitro) && (in_array('tiendo',$employee->vaitro)||in_array('duan',$employee->vaitro))))){
            url::redirect($login_type); die();
        }
        
        $kho = $login_type=='doitacsanxuat'?$employee->id:$employee->kho;
        
        $cuonvai = sanpham::get($cuonvai);
        //var_dump($cuonvai);die();
        if(!$cuonvai){
            url::redirect('.');die();
        }
        
        $duan_cut = new duan_cat;
        $duan_cut = $duan_cut->find_all_by_cuonvai($cuonvai->id);
        $duan_cut = model::map2($duan_cut,'ban');
        
        //var_dump($duan_cut);
        $duan = $duan_cut[0]->duan;
        $duan = duan::get($duan);
        if(!$duan){
            url::redirect('.');die();
        }
        
        //tinh so tam da cat (cua cac cuon khac)
        $sotamdacat = db::query("SELECT sum(soluong) as soluong 
            FROM `duan_cat` 
            where trangthai = 1 and duan = {$duan->id} and cuonvai != {$cuonvai->id}");
        
        $sotamdacat = $sotamdacat?($sotamdacat[0]->soluong-0):0;
        
        $duan->solopdacat = $sotamdacat;
        
        //05/24/2020
        $duancon = db::query("select sum(duan.soluong) as duancon 
            from duan where duan.cha = ".($duan->id)." group by duan.cha");
        if($duancon) $duancon = $duancon[0]->duancon;
        else $duancon = 0;  
        $duan->solopdacat += $duancon;
        
        //load cac gia tri:tamvai nhomban nhom->nhanvien  + sanpham
        if($duan->tamvai){
            $duan->tamvai = sanpham::get($duan->tamvai);            
        }
        if($duan->sanpham){
            $duan->sanpham = sanpham::get($duan->sanpham);            
        }
        if($duan->nhomban){
            $duan->nhomban = sanpham::get($duan->nhomban);            
        }
        if($duan->nhanvien){
            $duan->nhanvien = nhanvien::get($duan->nhanvien);            
        }
        
        $duan->duan_cut = $duan_cut;
        $duan->cuonvai = $cuonvai;
        
        $duan_cuon = new duan_cuon;
        $duan_cuon = $duan_cuon->find_by_cuonvai($cuonvai->id);
        
        $duan->duan_cuon = $duan_cuon;
        
        //var_dump($duan);die();
        
        //06/04/2020
        if($duan->congdoan){
            $duan->congdoan = congdoan::get($duan->congdoan); 
            $congdoan = congdoan::list_all($kho,$duan->congdoan->nhom);
            if($duan->congdoan->nhom>0){
                $duan->nhomcongdoan = nhomcongdoan::get($duan->congdoan->nhom);
            }         
        }else{            
            $congdoan = congdoan::list_all($kho,0);
        }
        $nhomcongdoan = nhomcongdoan::list_all($kho);
         
        //render template
        view::render(
            'duan_cat_sua',             
            array(  
                 'employee'=>$employee,
                 'message'=>$message,
                 'title'=>"Administrator",
                 'h1'=>'Administrator Control',
                                     
                 'kho'=>$kho,                 
                 'model'=>$duan,
                 
                 //06/04/2020
                 'nhomcongdoan'=>$nhomcongdoan,
                 'congdoan'=>$congdoan, 
            )            
        );
    }
    public function them($duan){ 
        $message = '';
        
        if(!($employee=session::get('login'))){
            url::redirect('.');die();
        }
         
        $employee = json_decode($employee); 
        common::vaitro($employee); 
        
        $login_type = (session::get('login_type'));
         
        if(!($login_type=='admin' && $employee->kho>0 || $login_type=='doitacsanxuat'||
            ($login_type=='nhanvien' && !empty($employee->vaitro) && (in_array('tiendo',$employee->vaitro)||in_array('duan',$employee->vaitro))))){
            url::redirect($login_type); die();
        }
        
        $kho = $login_type=='doitacsanxuat'?$employee->id:$employee->kho;
        
        $duan = duan::get($duan);
        if(!$duan){
            url::redirect('.');die();
        }
        
        if($duan->kho!=$kho){
            url::redirect('.');die();
        }
        
        //tinh so tam da cat
        $sotamdacat = db::query("SELECT sum(soluong) as soluong FROM `duan_cat` where trangthai = 1 and duan = {$duan->id}");
        
        $sotamdacat = $sotamdacat?($sotamdacat[0]->soluong-0):0;
        
        $duan->duandacat = //06/07/2020
        $duan->solopdacat = $sotamdacat;
        
        //05/24/2020
        $duancon = db::query("select sum(duan.soluong) as duancon 
            from duan where duan.cha = ".($duan->id)." group by duan.cha");
        if($duancon) $duancon = $duancon[0]->duancon;
        else $duancon = 0;  
        $duan->solopdacat += $duancon;
        
        //load cac gia tri:tamvai nhomban nhom->nhanvien  + sanpham
        if($duan->tamvai){
            $duan->tamvai = sanpham::get($duan->tamvai);  
            
            //06/07/2020
            $b = $duan->tamvai->attributes();
            sanpham::dinhluong($b);
            $duan->tamvai = model::load($b,'sanpham');          
        }
        if($duan->sanpham){
            $duan->sanpham = sanpham::get($duan->sanpham);            
        }
        if($duan->nhomban){
            $duan->nhomban = sanpham::get($duan->nhomban);            
        }
        if($duan->nhanvien){
            $duan->nhanvien = nhanvien::get($duan->nhanvien);            
        }
        
        //06/04/2020
        if($duan->congdoan){
            $duan->congdoan = congdoan::get($duan->congdoan); 
            $congdoan = congdoan::list_all($kho,$duan->congdoan->nhom);
            if($duan->congdoan->nhom>0){
                $duan->nhomcongdoan = nhomcongdoan::get($duan->congdoan->nhom);
            }         
        }else{            
            $congdoan = congdoan::list_all($kho,0);
        }
        $nhomcongdoan = nhomcongdoan::list_all($kho);
        
         
        //render template
        view::render(
            'duan_cat',             
            array(  
                 'employee'=>$employee,
                 'message'=>$message,
                 'title'=>"Administrator",
                 'h1'=>'Administrator Control',
                                     
                 'kho'=>$kho,                 
                 'model'=>$duan, 
                 
                 //06/04/2020
                 'nhomcongdoan'=>$nhomcongdoan,
                 'congdoan'=>$congdoan,
            )            
        );
    } 
    public function index($duan,$page=1){ 
        $message = '';
        
        if(!($employee=session::get('login'))){
            url::redirect('.');die();
        }
         
        $employee = json_decode($employee); 
        common::vaitro($employee); 
        
        $login_type = (session::get('login_type'));
         
        if(!($login_type=='admin' && $employee->kho>0 || $login_type=='doitacsanxuat'||
            ($login_type=='nhanvien' && !empty($employee->vaitro) && (in_array('tiendo',$employee->vaitro)||in_array('duan',$employee->vaitro))))){
            url::redirect($login_type); die();
        }
        
        $kho = $login_type=='doitacsanxuat'?$employee->id:$employee->kho;
        
        $duan = duan::get($duan);
        if(!$duan){
            url::redirect('.');die();
        }
        
        if($duan->kho!=$kho){
            url::redirect('.');die();
        }
         
        $admins = duan_cuon::list_page($page,20,'inner join sanpham on sanpham.id = duan_cuon.cuonvai where duan_cuon.duan = '.$duan->id.' and duan_cuon.kho='.$kho.' order by duan_cuon.id desc',$pages);
        
        //var_dump($admins);die();
         
        //render template
        view::render(
            'duan_cuon',             
            array(  
                 'employee'=>$employee,
                 'message'=>$message,
                 'title'=>"Administrator",
                 'h1'=>'Administrator Control',
                   
                  
                 'kho'=>$kho,
                 
                 'model'=>$duan, 
                 'admins'=>$admins,
                 
                 'page'=>$page,
                 'pages'=>$pages,
            )            
        );
    }
}