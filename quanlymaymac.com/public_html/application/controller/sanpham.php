<?php

class sanpham_controller extends controller{
     
    public function index($page=1){ //var_dump('a');die();
        $message = '';
        
        if(!($employee=session::get('login'))){
            url::redirect('.');die();
        }
        $filter = input::get('filter');
        if($filter){
            //var_dump(input::get('filter'));die();
            if(!empty($filter['categories'])){
                $categories = $filter['categories'];    
                $categories = explode(",",$categories);
                //var_dump($categories);die();
            }
            if(!empty($filter['product'])){
                $product = $filter['product'];    
                 
            }
        }
        
        $employee = json_decode($employee); 
        
        $login_type = (session::get('login_type'));
         
        if(!($login_type=='admin' && $employee->kho>0 || $login_type=='doitacsanxuat')){
            url::redirect($login_type); die();
        }
        
        $kho = $login_type=='doitacsanxuat'?$employee->id:$employee->kho;
        
        $pages=0;
        
        $more = '';
        if(!empty($categories)){
            $cc = array();
            foreach($categories as $category){
                $cc[] = $category;
                $cc2 = danhmuc::cat_recursive($kho,$category);
                if($cc2){
                    foreach($cc2 as $ccc){
                        $cc[] = $ccc->id;
                    }
                }
            }
            $cc = array_unique($cc);
            $more = "and danhmuc in (".implode(",",$cc).")";
        }
        if(!empty($product)){
            $more .= "and ten like '%$product%' or ma like '%$product%'";
        }
        
        $admins = sanpham::list_page($page,20,"where kho = $kho $more order by id desc",$pages);
        if(!$admins) $admins = array();
        else{
            //added 05/07/2020: ap dung cho kho
            foreach($admins as $key=> &$admin){
                if($admin->ma=='' && $admin->cha>0){
                    $ad = sanpham::get($admin->cha);
                    if(!$ad) unset($admins[$key]);
                    
                    $ad = $ad->attributes();
                    unset($ad['id'],$ad['soluong'],$ad['kho']);
                    $admin = model::extend($admin,$ad);
                }
                
                //05/29/2020
                if($admin->soluong<=0){
                    $admin->fork = !!0;
                    continue;
                }
                
                //05/28/2020
                //tim dinh muc rieng
                $dinhmuc = dinhmuc::list_all_cha($admin->id);
                if($dinhmuc){
                    if(count($dinhmuc)==1){
                        
                    }else $dinhmuc = false;
                }else{
                    if($admin->nhom>0){
                        $dinhmuc = dinhmuc::list_all($admin->nhom);
                        if($dinhmuc){
                            if(count($dinhmuc)==1){
                                
                            }else $dinhmuc = false;
                        }
                    }
                }
                $admin->fork = !!$dinhmuc;
            }
        }
        //var_dump($admins);die(); 
        
        unset($_GET['__hth']);
        $a = http_build_query($_GET);
        $a = urldecode($a); 
        //var_dump($a);die();
         
        //render template
        view::render(
            'sanpham',             
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
    
    public function phantach($id){ 
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
        
        if(!$id) die();
        
        $model = sanpham::get($id); 
        
        sanpham::concua($model); //added 06/05/2020
        //var_dump($model);die();
        
        if(!$model) die();
        
        
        //tim dinh muc rieng
        $dinhmuc = dinhmuc::list_all_cha($model->id);
        if($dinhmuc){
            if(count($dinhmuc)==1){
                
            }else $dinhmuc = false;
        }else{
            
            // 06/05/2020
            if(!empty($model->concua)){
                $dinhmuc = dinhmuc::list_all_cha($model->concua);
                if($dinhmuc){
                    if(count($dinhmuc)==1){}else $dinhmuc = false;
                }
            }
            
            if(!$dinhmuc && $model->nhom>0){ // add (!$dinhmuc && ) 06/05/2020
                $dinhmuc = dinhmuc::list_all($model->nhom);
                if($dinhmuc){
                    if(count($dinhmuc)==1){
                        
                    }else $dinhmuc = false;
                }
            }
        }
        //var_dump($dinhmuc);die();
        if(!$dinhmuc){
            die();
        }
        
        /* removed 06/05/2020 vi da co ham concua roi
        if($model->kho!=$kho){
            $checksoluong = db::query("select * from sanpham where (id={$model->id} or cha={$model->id}) and kho=$kho");
            if($checksoluong){
                $checksoluong = $checksoluong[0]->soluong;
            }else{
                $checksoluong = 0;
            }
            $model->soluong = $checksoluong;
        }*/
        
        if($model->soluong<=0){
            url::redirect('/sanpham');
            die();
        }
        
        //var_dump($dinhmuc);
        $dinhmuc = $dinhmuc[0];
        //gio lay ben du an dinh luong sang: bo phan check ve so luong, vi phan tach k can xet so luong cua sp phan tach thanh
        $dinhmuc->sanpham = sanpham::get($dinhmuc->sanpham);
        //var_dump($dinhmuc);die();
        /*
        $checksoluong = db::query("select * from sanpham where (id={$dinhmuc->sanpham->id} or cha={$dinhmuc->sanpham->id}) and kho=$kho");
        if($checksoluong){
            $checksoluong = $checksoluong[0]->soluong;
        }else{
            $checksoluong = 0;
        }
        $dinhmuc->sanpham->soluong = $checksoluong; //var_dump($checksoluong);die();
        */
        
        $spcha = false;
        if($dinhmuc->sanpham->cha>0){
             
            $nhom = db::query("select dm2.* from dinhmuc dm2 
                    where dm2.sanpham = ".($dinhmuc->sanpham->cha)." and 1=
                        (select count(dm.id) from dinhmuc dm where dm.nhom = dm2.nhom)");
            if($nhom){
                $nhom = $nhom[0];
                $spcha = db::query("select * from sanpham 
                    where sanpham.ma!='' and sanpham.nhom = ".($nhom->nhom)."");    
                
                if($spcha){
                    foreach($spcha as $key=>&$sp){
                         
                        /*$checksoluong = db::query("select * from sanpham where (id={$sp->id} or cha={$sp->id}) and kho=$kho");
                        if(!$checksoluong){
                            unset($spcha[$key]);
                            continue;
                        }
                        if($checksoluong[0]->soluong<=0){
                            unset($spcha[$key]);
                            continue;
                        }
                         
                        $sp->soluong = $checksoluong[0]->soluong;*/
                        
                        //xem co dinh luong rieng hay k
                        $dinhluongrieng = db::query("select * from dinhluong 
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
                where sanpham.ma!='' and sanpham.cha = ".($dinhmuc->sanpham->id)."");     //var_dump($spcha);die();
             
            /*if($spcha){
                foreach($spcha as $key=>&$sp){                    
                    $checksoluong = db::query("select * from sanpham where (id={$sp->id} or cha={$sp->id}) and kho=$kho");
                    if(!$checksoluong){
                        unset($spcha[$key]);
                        continue;
                    }
                    if($checksoluong[0]->soluong<=0){
                        unset($spcha[$key]);
                        continue;
                    }                                
                    $sp->soluong = $checksoluong[0]->soluong;                                    
                }
            }*/
                                     
        }
        //var_dump($spcha,$dinhmuc->sanpham);die();
        
        //06/01/2020
        if(!$spcha){ 
            
            //tim dinh muc rieng cua $dinhmuc->sanpham->id
            $spcha = db::query("select dm2.*,sanpham.ten,sanpham.id from dinhmuc dm2 
                    inner join sanpham on sanpham.id = dm2.cha
                    where dm2.sanpham = ".($dinhmuc->sanpham->id)." and 1=
                        (select count(dm.id) from dinhmuc dm where dm.cha = dm2.cha) 
                        and dm2.cha!=".$model->id);  
            //var_dump($spcha);die();  
            if($spcha){
                foreach($spcha as $key=>&$sp){
                     
                    //xem co dinh luong rieng hay k
                    $dinhluongrieng = db::query("select * from dinhmuc 
                        where cha = ".$sp->id); //." and sanpham=".($dinhmuc->sanpham->id)
                    //var_dump($dinhluongrieng);die();    
                    if($dinhluongrieng && count($dinhluongrieng)==1) // && $dinhluongrieng[0]->sanpham==$nhom->sanpham   
                        $sp->dinhluong = $dinhluongrieng[0]->soluong;
                    else    
                        $sp->dinhluong = $sp->soluong;
                }
            }
             
        }
        
        //06/08/2020
        $nhom = db::query("select dm2.* from dinhmuc dm2 
                    where dm2.sanpham = ".($id)." and 1=
                        (select count(dm.id) from dinhmuc dm where dm.nhom = dm2.nhom)");      //var_dump($nhom);die();   
        if($nhom){
            $nhom = $nhom[0];
            
            $spcha2 = db::query("select * from sanpham 
                    where sanpham.ma!='' and sanpham.nhom = ".($nhom->nhom)."");   
                    
            //var_dump($spcha2);die();         
            if($spcha2){
                
                foreach($spcha2 as &$sp){
                    $dinhluongrieng = db::query("select * from dinhmuc 
                        where cha = ".$sp->id);  
                    if($dinhluongrieng && count($dinhluongrieng)==1)   
                        $sp->dinhluong = $dinhluongrieng[0]->soluong*$dinhmuc->soluong;
                    else    
                        $sp->dinhluong = $nhom->soluong*$dinhmuc->soluong;
                }
                
                if($spcha){
                    $spcha2 = model::map2($spcha2);     
                    $spcha = model::map2($spcha);   
                    $spcha = $spcha + $spcha2;
                    $spcha = array_values($spcha);  
                }else{
                    $spcha = $spcha2;
                }                           
            }
        }                        
        //var_dump($nhom);die();
        
        $dinhmuc->con = $spcha; //var_dump($spcha);die();
        
        $model->dinhmuc = $dinhmuc;
        
        //06/01/2020
        $congdoan = congdoan::list_all($kho ,0); //edit 06/03/2020
        //06/03/2020
        $nhomcongdoan = nhomcongdoan::list_all($kho);
        
        //render template
        view::render(
            'sanpham_phantach',             
            array(  
                 'employee'=>$employee,
                 'message'=>$message,
                 'title'=>"Administrator",
                 'h1'=>'Administrator Control',
                   
                 //'dinhmuc'=>$dinhmuc,
                 
                 'kho'=>$kho,
                 
                 'model'=>$model, 
                 
                 'congdoan'=>$congdoan,
                 'nhomcongdoan'=>$nhomcongdoan,//06/03/2020
            )            
        );
    }
}