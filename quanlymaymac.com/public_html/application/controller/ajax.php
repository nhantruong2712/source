<?php

class ajax_controller extends controller{
    /*
    public function price_string($price){
        echo mb_strtolower(vietnamese::convert_number_to_words($price));
    }    
    */
        
    public function notidelete($a){
        $file = 'nodejs/data/noti/'.$a.'.txt';
         
        @unlink($file);
        
        echo json_encode(array(
            'message'=>'',
            'success'=>1
        ));
    }
    
    public function deleteonenoti($u,$d){
        $file = 'nodejs/data/noti/'.$u.'.txt';
         
        $f = file_get_contents($file);
        try{
            $f = json_decode($f);
            foreach ($f as $type=>$date){
                if($date==$d){
                    unset($f->$type);
                    break;
                }
            }       
        }catch(Exception $e){
            $f = (object) array();
        }
        
        @file_put_contents($file,json_encode($f));
        
        echo json_encode(array(
            'message'=>'Delete one successfully',
            'success'=>1
        ));
    }
     
    public function noti($a){
        $file = 'nodejs/data/noti/'.$a.'.txt';
         
        if(file_exists($file)){            
            $a = file_get_contents($file); 
            //can phai sap xep theo time
            $a =(array) json_decode($a);
            uasort($a,function($x,$y){return $x<$y;});
            echo json_encode($a);
        }    
        else    
            echo '{}';
    }
       
    public function changepass(){
        $message = 'Error';
        $success = 0; 
        if(($p = input::post('p'))&&($n = input::post('n'))){
            
            $employee = json_decode(session::get('login'));
            $type = session::get('login_type');
            $id = session::get('login_id');
            
            if($employee->password != md5($p)){
                $message = 'Wrong password';
            }else{
            
                $model = model::load(array(
                    'id'=>$id,
                    'password'=>md5($n),                     
                ),$type);
                $model->update();
                $message = 'Change successfully';
                $success = 1; 
                
                $employee->password = $n;
                session::set('login',json_encode($employee));
                
            }
        }
        echo json_encode(array(
            'message'=>$message,
            'success'=>$success
        ));
    }
     
    public function setstatus( ){
        $message = 'Error';
        $success = 0; 
        if(($s = input::post('s')) &&($oid = input::post('oid'))){
            $a = array(
                'id'=>$oid,
                'status'=>$s,
                //'date2'=>time(),
            );
            if($s>0){                 
                $a['date'.($s+1)] = time();
            }
            $model = model::load($a,'order');
             
            $model->update();
             
            //$message = 'Successfully';
            $success = 1; 
            
            $model = order::get($oid);
            $message = $model->customer>0?$model->customer:'0';
            
        }
        echo json_encode(array(
            'message'=>$message,
            'success'=>$success
        ));
    }
             
    public function profile(){        
        $message = 'Error';
        $success = 0; 
        if(($id = input::post('id')) && ($type = input::post('type'))&&($field = input::post('field'))){
            $value = input::post('value');
            $m = array(
                'id' => $id,
                $field=>$value,                
            );
             
            $model = model::load($m,$type);
            $model->update();
            $message = 'Update successfully';
            $success = 1; 
            
            $employee = json_decode(session::get('login'));
            if($type == session::get('login_type') && $employee->id==$id){                
                $employee->$field = $value;
                session::set('login',json_encode($employee));
            }
        }
         
        echo json_encode(array(
            'message'=>$message,
            'success'=>$success
        ));
    }
     
    public function admincauhinhchung(){
        $message = 'Error';
        $success = 0; 
        if(($id = input::post('id') )&&($value = input::post('value'))){
            $model = model::load(array(
                'id'=>$id,
                'value'=>$value
            ),'configuration');
            $model->update();
            $message = 'Update successfully';
            $success = 1; 
        }
        echo json_encode(array(
            'message'=>$message,
            'success'=>$success
        ));
    }
    
    public function login(){
        $uu='';
        $message = 'Error';
        $redi = '';
        if(($name = input::post('email') )&&($pass = input::post('password'))){
            $pass = md5($pass);
            $model = new admin;
             
            $model = $model->find_all_by_email_and_password($name,$pass); //var_dump($name,$pass,$model);die();
            
            if(isset($model[0]) && $model[0] instanceof model){
                session::set('login',json_encode($model[0]->attributes()));
                //var_dump(session::get('login'));die();
                session::set('login_type','admin');                 
                session::set('login_id',$model[0]->id);
                $uu = 'a'.$model[0]->id;
                $redi = 'admin';
                $message = '';
            }else{
                $model = load::model('doitac');
                $model = $model->all(
                    array(
                        'conditions'=>array(
                            array('email','=',$name),
                            array('password','=',$pass),
                            array('vaitro','=',2),
                        )
                    )
                );
                if(isset($model[0]) && $model[0] instanceof model){
                    if($model[0]->trangthai==0){
                        $message = ___('Your account was deactived.');    
                    }else{
                        session::set('login',json_encode($model[0]->attributes()));
                        session::set('login_type','doitacsanxuat');                 
                        session::set('login_id',$model[0]->id);
                        $uu = 'd'.$model[0]->id;
                        $redi = 'doitacsanxuat';
                        $message = '';
                    }
                }else{
                    $model = load::model('nhanvien');
                    $model = $model->all(
                        array(
                            'conditions'=>array(
                                array('email','=',$name),
                                array('password','=',$pass),
                                array('vaitro','>',0),
                            )
                        )
                    );
                    if(isset($model[0]) && $model[0] instanceof model){
                        if($model[0]->trangthai==0){
                            $message = ___('Your account was deactived.');    
                        }else{
                            session::set('login',json_encode($model[0]->attributes()));
                            session::set('login_type','nhanvien');                 
                            session::set('login_id',$model[0]->id);
                            $uu = 'b'.$model[0]->id;
                            $redi = 'nhanvien';
                            $message = '';
                        }
                    }else $message = ___('Email or password is incorrect.');
                    
                }
                 
            }
        }
        echo json_encode(array(
            'message'=>$message,
            'redi'=>$redi,
            'uu'=>$uu,
        ));
    }
    public function addPB(){
        if(!($employee=session::get('login'))){
            die();
        }
         
        $employee = json_decode($employee);
         
        //render template
        view::render(
            'pb_add',             
            array(  
                'employee'=>$employee,  
                 
            )            
        );
    }
    public function addBL(){
        if(!($employee=session::get('login'))){
            die();
        }
         
        $employee = json_decode($employee);
         
        //render template
        view::render(
            'bl_add',             
            array(  
                'employee'=>$employee,  
                 
            )            
        );
    }
    public function addCD(){
        if(!($employee=session::get('login'))){
            die();
        }
         
        $employee = json_decode($employee);
        
        //06/03/2020        
        $categories = nhomcongdoan::cat_recursive(empty($employee->vaitro)?$employee->kho:$employee->id);
         
        //render template
        view::render(
            'cd_add',             
            array(  
                'employee'=>$employee,  
                'categories'=>$categories, //06/03/2020    
            )            
        );
    } 
    public function searchnhomdinhmuc($kho=0){
        $q = $_REQUEST['q'];
        $data = db::query("select * from nhomdinhmuc where ten like '%$q%' 
            and kho=$kho limit 10",'nhomdinhmuc');
        if(!$data) $data = array();
        
        echo json_encode(array_map(function($a){$b = $a->attributes();$b['text']=$b['ten'];return $b;},$data));
    }
    public function searchSP($kho=0){
        $q = empty($_REQUEST['q'])?'':$_REQUEST['q'];
        //$data = db::query("select * from sanpham where (ten like '%$q%' or ma like '%$q%') limit 10",'sanpham');
        
        if($q==''){
            echo json_encode(array());
            die();
        }
        
        $duan = empty($_GET['duan'])?0:$_GET['duan'];
        
        $xuatnhap = empty($_GET['xuatnhap'])?0:$_GET['xuatnhap']; //nhap 1, xuat 2
        
        //06/06/2020
        $doitac = empty($_REQUEST['doitac'])?'':$_REQUEST['doitac'];
        if($doitac){
            $trunggian = $doitac;
            $doitac = $kho;
            $kho = $trunggian;
        }
        
        $x = '';
        /*if($tonkho==1){
            $x = 'HAVING soluong=1';
        }elseif($tonkho==-1){
            $x = 'HAVING soluong>0';
        }*/
        
        if($duan>0){
            //$x = 'HAVING soluong=1';
            $x = 'HAVING soluong>0'; //changed 06/08/2020
            
            /*
        //soluong = 1
        //va tim cuonvai trong duan_cat where duan=duan and trangthai=0   // trangthai=1 thi soluong=0 roi
            $check = db::query("select id,cuonvai from duan_cuon where duan=$duan and trangthai=0"); //duan_cat
            if($check){
                $check = model::map('cuonvai','id');
                $check = implode(',',$check);
                
                $x = "and sp.id not in ($check) ".$x;
            }*/
            $x = "and (case 
                when isnull(sp2.id) and sp.kho = $kho then sp.id
                else sp2.id end) not in (
            select dc.cuonvai from duan_cuon dc where dc.kho=$kho and dc.trangthai=0
            ) ".$x; //sp.id
        }
                
        if($xuatnhap>0){
            $x = "and (case 
                when isnull(sp2.id) and sp.kho = $kho then sp.id
                else sp2.id end) not in (
            select dc.cuonvai from duan_cuon dc where dc.kho=$kho
            ) ";
            
            //06/06/2020
            if($xuatnhap==2 || ($xuatnhap==1 && $doitac>0)){
                $x.="having soluong>0 ";
            }
        }        
         
        //edited 05/07/2020 fix for kho
        //edited 06/05/2020 : when not isnull(sp2.id) then sp2.soluong
        $data = db::query("select sp.*, (case 
                when isnull(sp2.id) and sp.kho = $kho then sp.soluong
                when not isnull(sp2.id) then sp2.soluong
                else 0 end) as soluong from sanpham sp 
            left join sanpham sp2 on sp2.ma='' and sp2.cha=sp.id and sp2.kho = $kho
            where (sp.ten like '%$q%' or sp.ma like '%$q%') and sp.ma!='' $x limit 10",'sanpham');
        if(!$data) $data = array();
        
        echo json_encode(array_map(function($a) use($xuatnhap,$doitac,$duan){ //06/06/2020 use($xuatnhap,$doitac)
            $b = $a->attributes();
            //$b['text']=$b['ten'];
             
            //06/06/2020
            $b['text']=$b['ten'].((($xuatnhap==2) || ($xuatnhap==1 && $doitac>0) || ($duan>0))?(' ('.($b['soluong']-0).')'):'');
       
            //06/07/2020
            sanpham::dinhluong($b);
             
            return $b;
        },$data));
    }
    public function addSP(){
        if(!($employee=session::get('login'))){
            die();
        }
        $employee = json_decode($employee);
        $login_type = (session::get('login_type'));
        
        $kho = $login_type=='doitacsanxuat'?$employee->id:$employee->kho;
         
         
        //cha,danhmuc,soluong,ngay,donvi,ma,nhom,gia
        //nhom
        //$danhmuc = danhmuc::list_select($kho); //var_dump($danhmuc);die();
        
        $danhmuc = danhmuc::fulldanhmuc($kho);
         
        //var_dump($danhmuc);die();
        $danhmuc = model::map($danhmuc,'id','title');
        //var_dump($danhmuc);die();
         
        //render template
        view::render(
            'sp_add',             
            array(  
                'employee'=>$employee,  
                
                'danhmuc'=> html::select('danhmuc',$danhmuc,array(
                    'id'=>'danhmuc',
                    'class'=>'form-control required',
                    'empty'=>'--'.___('Select').'--',
                )),
            )            
        );
    } 
    public function addVT(){
        if(!($employee=session::get('login'))){
            die();
        }
         
        $employee = json_decode($employee);
         
        //render template
        view::render(
            'vt_add',             
            array(  
                'employee'=>$employee,  
                 
            )            
        );
    }
    public function addKH(){
        if(!($employee=session::get('login'))){
            die();
        }
         
        $employee = json_decode($employee);
         
        //render template
        view::render(
            'kh_add',             
            array(  
                'employee'=>$employee,  
                 
            )            
        );
    }
    public function addNCC(){
        if(!($employee=session::get('login'))){
            die();
        }
         
        $employee = json_decode($employee);
         
        //render template
        view::render(
            'ncc_add',             
            array(  
                'employee'=>$employee,  
                 
            )            
        );
    }
    public function addNDM(){
        if(!($employee=session::get('login'))){
            die();
        }
        
        $id = input::post('id');
        //$category = danhmuc::get($id);
        
        $employee = json_decode($employee);
        $categories = nhomdinhmuc::cat_recursive(empty($employee->vaitro)?$employee->kho:$employee->id);
        //render template
        view::render(
            'ndm_add',             
            array(  
                'employee'=>$employee,  
                'categories'=>$categories,   
                'id'=>$id, 
            )            
        );
    }
    public function addDM(){
        if(!($employee=session::get('login'))){
            die();
        }
        
        $id = input::post('id');
        //$category = danhmuc::get($id);
        
        $employee = json_decode($employee);
        $categories = danhmuc::cat_recursive(empty($employee->vaitro)?$employee->kho:$employee->id);
        //render template
        view::render(
            'dm_add',             
            array(  
                'employee'=>$employee,  
                'categories'=>$categories,   
                'id'=>$id, 
            )            
        );
    }
    
    public function addDTSX(){
        if(!($employee=session::get('login'))){
            die();
        }
        
        $employee = json_decode($employee);
        
        //render template
        view::render(
            'dtsx_add',             
            array(  
                'employee'=>$employee,   
                
                 
            )            
        );
    }
    public function addNV(){
        if(!($employee=session::get('login'))){
            die();
        }
        
        $employee = json_decode($employee);
        
        
        $kho =empty($employee->vaitro)?$employee->kho:$employee->id;
        
        
        //render template
        view::render(
            'nv_add',             
            array(  
                'employee'=>$employee,  
                
                'phongban'=>html::select('phongban',phongban::list_select($kho),array(
                    'id'=>'phongban',
                    'class'=>'form-control required',
                    'empty'=>'--'.___('Select').'--',
                )),   
                
                'bangluong'=>html::select('bangluong',bangluong::list_select($kho),array(
                    'id'=>'bangluong',
                    'class'=>'form-control required',
                    'empty'=>'--'.___('Select').'--',
                )),
                
                'vaitro'=>html::select('vaitro',vaitro::list_select($kho),array(
                    'id'=>'vaitro',
                    'class'=>'form-control',
                    'empty'=>'--'.___('Select').'--',
                )),
            )            
        );
    }
    public function addAdmin(){
        if(!($employee=session::get('login'))){
            die();
        }
        
        $employee = json_decode($employee);
        //render template
        view::render(
            'admin_add',             
            array(  
                'employee'=>$employee,      
            )            
        );
    } 
    public function editNDM(){
        $id = input::post('id');
        if(!$id) die();
        
        if(!($employee=session::get('login'))){
            die();
        }
        
        $model = nhomdinhmuc::get($id);//var_dump($model);die();
        
        if(!$model) die();
        
        $employee = json_decode($employee);
        $categories = nhomdinhmuc::cat_recursive(empty($employee->vaitro)?$employee->kho:$employee->id,0,$id);
         
        //render template
        view::render(
            'ndm_edit',             
            array(  
                 'model'=>$model, 
                 'categories'=>$categories,   
                 'employee'=>$employee,  
            )            
        );                
    } 
    public function editdinhmuc(){
        $id = input::post('id');
        if(!$id) die();
        
        if(!($employee=session::get('login'))){
            die();
        }
        
        $model = dinhmuc::get($id); //var_dump($id,$model);die();
        
        if(!$model) die();
        
        $model->sanpham = sanpham::get($model->sanpham);
        
        $employee = json_decode($employee);
         
        //render template
        view::render(
            'editSPDM',             
            array(  
                 'model'=>$model, 
                  
                 'employee'=>$employee,  
            )            
        );    
    }
    public function editDM(){
        $id = input::post('id');
        if(!$id) die();
        
        if(!($employee=session::get('login'))){
            die();
        }
        
        $model = danhmuc::get($id);//var_dump($model);die();
        
        if(!$model) die();
        
        $employee = json_decode($employee);
        $categories = danhmuc::cat_recursive(empty($employee->vaitro)?$employee->kho:$employee->id,0,$id);
         
        //render template
        view::render(
            'dm_edit',             
            array(  
                 'model'=>$model, 
                 'categories'=>$categories,   
                 'employee'=>$employee,  
            )            
        );                
    } 
    public function viewNH(){
        $id = input::post('id');
        if(!$id) die();
        
        $hoadon = hoadon::get($id);
        if(!$hoadon) die();
        
        if($hoadon->cha==0) //05/27/2020
        $sanpham = db::query("select sanpham.*,hoadon_chitiet.* from hoadon_chitiet 
            inner join sanpham on sanpham.id = hoadon_chitiet.sanpham 
            and hoadon_chitiet.hoadon=$id"); //var_dump($sanpham);die();
        else //05/27/2020
        $sanpham = db::query("select sanpham.*,hoadon_chitiet.* from hoadon_chitiet 
            inner join sanpham on sanpham.id = hoadon_chitiet.sanpham 
            and hoadon_chitiet.hoadon={$hoadon->cha}"); //05/27/2020
        
        if($hoadon->nhanvien>0){
            $tu = 'nhanvien';
            $doitac = nhanvien::get($hoadon->nhanvien);
        }else{
            $doitac = doitac::get($hoadon->doitac);
            $tu = $doitac->vaitro == 3?'khachhang':$doitac->vaitro == 2?'doitacsanxuat':'nhacungcap';
            
            //05/27/2020
            if($hoadon->cha>0){ 
                $tu = ($doitac->vaitro == 3||$doitac->vaitro == 2)?'khachhang':'nhacungcap';
            }
        }
        $roles = config::get('roles');
        $hoadon->tu = $tu;
        $hoadon->doitac = $doitac;
        $hoadon->tu2 = $roles[$tu];
        
        //05/29/2020: tim cac hoa don thanh toan lien quan
        $hoadon->thanhtoan = db::query("select * from hoadon where hoadon.cha = {$hoadon->id} and type = 2");
        
        //render template
        view::render(
            'nh_view',             
            array(  
                 'model'=>$hoadon, 
                 'sanpham'=>$sanpham,   
                 //'employee'=>$employee,  
            )            
        );     
    }
    public function manageDM(){
        $id = input::post('id');
        if(!$id) die();
        
        $nhomdinhmuc = nhomdinhmuc::get($id);
        if(!$nhomdinhmuc) die();
        
        //$model = dinhmuc::list_all($id);  //$nhomdinhmuc->kho,
        $model = db::query("select dinhmuc.id, sanpham.ten, dinhmuc.soluong, dinhmuc.kho, dinhmuc.nhom from dinhmuc 
            inner join sanpham on sanpham.id = dinhmuc.sanpham where dinhmuc.nhom=$id",'dinhmuc');
        
        if(!$model) $model=array(); //die();
         
        //render template
        view::render(
            'manageDM',             
            array(  
                 'model'=>$nhomdinhmuc, 
                 'admins'=>$model,
            )            
        ); 
    }
    public function managerole($id=0){
        if($id==0)
        $id = input::post('id');
        if(!$id) die();
        
        $vaitro = vaitro::get($id);
        if(!$vaitro) die();
        
        $model = phanquyen::list_all($vaitro->kho,$id); 
        
        if(!$model) $model=array(); //die();
        else $model = model::map2($model,'quyen');
        
        $admins = quyen::list_all();
        
        if(!$admins) $admins=array(); //die();
        else{
            foreach($admins as &$admin){
                 $admin->checked = isset($model[$admin->id]);   
            }
        }
         
        //render template
        view::render(
            'managerole',             
            array(  
                 'model'=>$vaitro, 
                 'admins'=>$admins,
            )            
        );    
    }
    public function editPB(){
        $id = input::post('id');
        if(!$id) die();
        
        $model = phongban::get($id); 
        
        if(!$model) die();
         
        //render template
        view::render(
            'pb_edit',             
            array(  
                 'model'=>$model, 
            )            
        );    
    }
    public function editNCC(){
        $id = input::post('id');
        if(!$id) die();
        
        $model = doitac::get($id); 
        
        if(!$model) die();
        if($model->vaitro!=1) die();
        
        //render template
        view::render(
            'ncc_edit',             
            array(  
                 'model'=>$model, 
            )            
        );    
    }
    public function editCD(){
        $id = input::post('id');
        if(!$id) die();
        
        $model = congdoan::get($id); 
        
        if(!$model) die();
        
        //06/03/2020
        if(!($employee=session::get('login'))){
            die();
        }
        $employee = json_decode($employee);
        $categories = nhomcongdoan::cat_recursive(empty($employee->vaitro)?$employee->kho:$employee->id);
         
        //render template
        view::render(
            'cd_edit',             
            array(  
                 'model'=>$model, 
                 'categories'=>$categories,   //06/03/2020
            )            
        );    
    }
    
    public function editBL(){
        $id = input::post('id');
        if(!$id) die();
        
        $model = bangluong::get($id); 
        
        if(!$model) die();
         
        //render template
        view::render(
            'bl_edit',             
            array(  
                 'model'=>$model, 
            )            
        );    
    }
    public function filterNH(){
        $id = input::post('id');
        if(!$id) die();
        
        if(!($employee=session::get('login'))){
            die();
        }
        
        $employee = json_decode($employee); 
        
        $login_type = (session::get('login_type'));
         
        if(!($login_type=='admin' && $employee->kho>0 || $login_type=='doitacsanxuat')){
            die();
        }
        
        $filter = input::post('filter');
        if($filter){
             
            if(!empty($filter['product'])){
                $product = $filter['product'];                     
            }
            if(!empty($filter['note'])){
                $note = $filter['note'];                     
            }
            if(!empty($filter['status'])){
                $status = $filter['status'];         
                $status = explode(",",$status);            
            }
            if(!empty($filter['range'])){
                $range = $filter['range'];                                    
            }
        }
         
        //render template
        view::render(
            'filterNH',             
            array(  
                 'id'=>$id, 
                  
                 'product'=>empty($product)?'':($product),
                 'note'=>empty($note)?'':($note),
                 'status'=>empty($status)?'':($status),
                 'range'=>empty($range)?'':($range),
            )            
        );  
    }
    public function filterXH(){
        $id = input::post('id');
        if(!$id) die();
        
        if(!($employee=session::get('login'))){
            die();
        }
        
        $employee = json_decode($employee); 
        
        $login_type = (session::get('login_type'));
         
        if(!($login_type=='admin' && $employee->kho>0 || $login_type=='doitacsanxuat')){
            die();
        }
        
        $filter = input::post('filter');
        if($filter){
             
            if(!empty($filter['product'])){
                $product = $filter['product'];                     
            }
            if(!empty($filter['note'])){
                $note = $filter['note'];                     
            }
            if(!empty($filter['status'])){
                $status = $filter['status'];         
                $status = explode(",",$status);            
            }
            if(!empty($filter['range'])){
                $range = $filter['range'];                                    
            }
        }
         
        //render template
        view::render(
            'filterXH',             
            array(  
                 'id'=>$id, 
                  
                 'product'=>empty($product)?'':($product),
                 'note'=>empty($note)?'':($note),
                 'status'=>empty($status)?'':($status),
                 'range'=>empty($range)?'':($range),
            )            
        );  
    }
    public function filterSP(){
        $id = input::post('id');
        if(!$id) die();
        
        if(!($employee=session::get('login'))){
            die();
        }
        
        $employee = json_decode($employee); 
        
        $login_type = (session::get('login_type'));
         
        if(!($login_type=='admin' && $employee->kho>0 || $login_type=='doitacsanxuat')){
            die();
        }
        
        $filter = input::post('filter');
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
        
        //$a = http_build_query($_POST);
        //$a = urldecode($a);
        //var_dump($a);die();
        //var_dump($_GET);die();
        
        $danhmuc = danhmuc::fulldanhmuc($id); //var_dump($danhmuc);
        
        //render template
        view::render(
            'filterSP',             
            array(  
                 'id'=>$id, 
                 'danhmuc'=> $danhmuc,
                 'categories'=>empty($categories)?array():($categories),
                 //'filter'=>$a,
                 'product'=>empty($product)?'':($product),
            )            
        );  
    }
    public function forkSP(){
        $id = input::post('id');
        if(!$id) die();
        
        $model = sanpham::get($id); 
        
        if(!$model) die();
        
        if(!($employee=session::get('login'))){
            die();
        }
        $employee = json_decode($employee);
        $login_type = (session::get('login_type'));
        
        $kho = $login_type=='doitacsanxuat'?$employee->id:$employee->kho;
        if($kho!=$model->kho) die();
        
        //tim dinh muc rieng
        $dinhmuc = dinhmuc::list_all_cha($model->id);
        if($dinhmuc){
            if(count($dinhmuc)==1){
                
            }else $dinhmuc = false;
        }else{
            if($model->nhom>0){
                $dinhmuc = dinhmuc::list_all($model->nhom);
                if($dinhmuc){
                    if(count($dinhmuc)==1){
                        
                    }else $dinhmuc = false;
                }
            }
        }
        
        if(!$dinhmuc){
            die();
        }
        
        //var_dump($dinhmuc);
        $dinhmuc = $dinhmuc[0];
        //gio lay ben du an dinh luong sang: bo phan check ve so luong
        $dinhmuc->sanpham = sanpham::get($dinhmuc->sanpham);
        
        /*$checksoluong = db::query("select * from sanpham where (id={$dinhmuc->sanpham->id} or cha={$dinhmuc->sanpham->id}) and kho=$kho");
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
        $dinhmuc->con = $spcha;
        
        var_dump($dinhmuc);
    }
    public function editSP(){
        $id = input::post('id');
        if(!$id) die();
        
        $model = sanpham::get($id); //var_dump($model);die();
        
        if(!$model) die();
        
        if(!($employee=session::get('login'))){
            die();
        }
        $employee = json_decode($employee);
        $login_type = (session::get('login_type'));
        
        $kho = $login_type=='doitacsanxuat'?$employee->id:$employee->kho;
        if($kho!=$model->kho) die();
          
        //$danhmuc = danhmuc::list_select($kho);
        
        $danhmuc = danhmuc::fulldanhmuc($kho);
         
        //var_dump($danhmuc);die();
        $danhmuc = model::map($danhmuc,'id','title');
        //var_dump($danhmuc);die();
        
        //added 05/07/2020: ap dung cho kho
        sanpham::concua($model,$modelcha); //06/05/2020 -> cho vao model
             
        //added 05/08/2020
        if($model->cha>0){
            $model->cha = $modelcha?$modelcha:sanpham::get($model->cha); //06/05/2020: bs $modelcha
        }
        
        //var_dump($model);die(); 
        if($model->nhom>0) $model->nhom = nhomdinhmuc::get($model->nhom);
         
        $a =  array(
            'id'=>'danhmuc',
            'class'=>'form-control',
            'value'=>$model->danhmuc,
            'empty'=>false,
        );
        if(!empty($model->concua)){
            $a['readonly'] = 'readonly';
            
            //06/06/2020
            $modeldanhmuc = danhmuc::get($model->danhmuc);
            $danhmuc = array($modeldanhmuc->id => $modeldanhmuc->ten);
        }    
        //added 05/27/2020
        $model->dinhmuc = dinhmuc::list_all_cha($id);    
        if($model->dinhmuc){
            foreach($model->dinhmuc as &$dm){
                $dm->sanpham = sanpham::get($dm->sanpham);
            }
        }
           
        //render template
        view::render(
            'sp_edit',             
            array(  
                 'model'=>$model, 
                 'danhmuc'=> html::select('danhmuc',$danhmuc,$a),
            )            
        );    
    }
    public function editVT(){
        $id = input::post('id');
        if(!$id) die();
        
        $model = vaitro::get($id); 
        
        if(!$model) die();
         
        //render template
        view::render(
            'vt_edit',             
            array(  
                 'model'=>$model, 
            )            
        );    
    }
    public function addSPDM(){
        $id = input::post('id');
        if(!$id) die();
        
        $nhomdinhmuc = nhomdinhmuc::get($id);
        
        //var_dump($nhomdinhmuc);die();
        
        //select2 san pham
        //so luong
        //render template
        view::render(
            'addSPDM',             
            array(  
                 'model'=>$nhomdinhmuc, 
            )            
        );  
    }
    public function editKH(){
        $id = input::post('id');
        if(!$id) die();
        
        $model = doitac::get($id); 
        
        if(!$model) die();
        if($model->vaitro!=3 && $model->vaitro!=2) die(); //change 05/27/2020  add && $model->vaitro!=2
        
        //render template
        view::render(
            'kh_edit',             
            array(  
                 'model'=>$model, 
            )            
        );    
    }
    
    public function editDTSX(){
        $id = input::post('id');
        if(!$id) die();
        
        $model = doitac::get($id);//var_dump($model);die();
        
        if(!$model) die();
        if($model->vaitro!=2) die();
        
        //render template
        view::render(
            'doitacsanxuat_edit',             
            array(  
                 'model'=>$model, 
            )            
        );                
    } 
    
    public function editNV(){
        $id = input::post('id');
        if(!$id) die();
        
        if(!($employee=session::get('login'))){
            die();
        }
        
        $model = nhanvien::get($id);//var_dump($model);die();
        
        if(!$model) die();
        
        $employee = json_decode($employee);
                
        $kho =empty($employee->vaitro)?$employee->kho:$employee->id;        
         
        //render template
        view::render(
            'nv_edit',             
            array(  
                 'model'=>$model, 
                 
                 'phongban'=>html::select('phongban',phongban::list_select($kho),array(
                    'id'=>'phongban',
                    'class'=>'form-control required',
                    'empty'=>false,
                    'value'=>$model->phongban
                 )),   
                
                 'bangluong'=>html::select('bangluong',bangluong::list_select($kho),array(
                    'id'=>'bangluong',
                    'class'=>'form-control required',
                    'empty'=>false,
                    'value'=>$model->bangluong
                 )),
                 
                 'vaitro'=>html::select('vaitro',vaitro::list_select($kho),array(
                    'id'=>'vaitro',
                    'class'=>'form-control',
                    'empty'=>'--'.___('Select').'--',
                    'value'=>$model->vaitro
                 )),
            )            
        );                
    } 
    
    public function editAdmin(){
        $id = input::post('id');
        if(!$id) die();
        
        $model = admin::get($id);
        
        if(!$model) die();
        
        //render template
        view::render(
            'admin_edit',             
            array(  
                 'model'=>$model, 
            )            
        );                
    } 
    public function addChildSP(){
        $id = input::post('id');
        if(!$id) die();
        
        $model = sanpham::get($id);
        
        if(!$model) die();
        
        sanpham::concua($model);
        
        //render template
        view::render(
            'addChildSP',             
            array(  
                 'model'=>$model, 
            )            
        );   
    }
    public function doaddChildSP(){
        $id = input::post('id');
        
        //var_dump($_POST);die();
/*
array(10) {
  ["kho"]=>
  string(1) "1"
  ["id"]=>
  string(1) "9"
  ["soluongthem"]=>
  string(1) "1"
  ["ten"]=>
  string(12) "Tấm đôi "
  ["sososauten"]=>
  string(1) "5"
  ["sotenbatdau"]=>
  string(0) ""
  ["ma"]=>
  string(6) "tamdoi"
  ["sososauma"]=>
  string(1) "5"
  ["somabatdau"]=>
  string(0) ""
  ["soluong"]=>
  string(1) "0"
}

["giamtru"]=>
  string(1) "1"
*/        
        
        if(!$id) die();
        
        $model = sanpham::get($id);
        
        if(!$model) die();
        
        //sanpham::concua($model); //added 06/05/2020 removed 06/06/2020 vi da la san pham goc roi
        $kho = $_POST['kho'];
        
        sanpham::checksoluongsp($model,$kho); //06/06/2020 vi da la san pham goc roi nen can tim soluong san pham
        
        $codes = array();
        if(!($_POST['somabatdau']>0)){ 
            $uu = 1;
             
            $n = db::query("SELECT max(substring(ma,CHAR_LENGTH('".$_POST['ma'].
                "')+1)) as c FROM `sanpham` WHERE ma regexp '^".$_POST['ma'].
                "[0-9]{".$_POST['sososauma']."}$'");
            if($n){
                $n = $n[0]; // var_dump($n);   
                $_POST['somabatdau'] = $n->c-0+1;
            }else{
                $_POST['somabatdau'] = 1;
            }
        } 
        //var_dump($_POST['somabatdau']);
        
        if(!($_POST['sotenbatdau']>0)){
         
            $n = db::query("SELECT max(substring(ten,CHAR_LENGTH('".$_POST['ten'].
                "')+1)) as c FROM `sanpham` WHERE ten regexp '^".$_POST['ten'].
                //"')+2)) as c FROM `sanpham` WHERE ten regexp '^".$_POST['ten'].
                "[0-9]{".$_POST['sososauten']."}$'");
                //" [0-9]{".$_POST['sososauten']."}$'");
            if($n){
                $n = $n[0]; // var_dump($n);  
                $_POST['sotenbatdau'] = $n->c-0+1;
            }else{
                $_POST['sotenbatdau'] = 1;
            }    
        }
        //var_dump($_POST['sotenbatdau']);
        
        for($i=1;$i<=$_POST['soluongthem'];$i++){
            $codes[] = $_POST['ma'].sprintf('%0'.$_POST['sososauma'].'d',$_POST['somabatdau']+$i-1);
        }
        
        if(empty($uu)){
            $check = db::query("select ma from sanpham where ma in (".
                implode(",",array_map(function($a){return "'".$a."'";},$codes)).")");
            if($check){
                echo json_encode(array(
                    'error'=>'Lỗi trùng các mã sản phẩm: '.
                        implode(",",array_map(function($a){return $a->ma;},$check))
                ));
                die();
            }
        }
        /*
        if($_POST['id']>0){
            $p = sanpham::get($_POST['id']);
            if(!$p){
                echo json_encode(array(
                    'error'=>'Không tồn tại sản phẩm gốc ID: '.$_POST['id']
                ));
                die();
            }
        }*/
        
        $giamtru = empty($_POST['giamtru'])?0:$_POST['giamtru'];
        
        $nguoitao = session::get('login_id');
        $login_type = session::get('login_type');        
        $nguoitao = $login_type[0].$nguoitao;
        
        if($giamtru){                                
            //$model   
            if($giamtru==2){
                $giamtru = $model->soluong;    
            }
             
            $data = common::themhoadon(array(            
                'ma'=>common::autoCode(1,array(),'PH',6,'hoadon'),
                'ngay'=>time(),
                'ghichu'=>"Giảm số lượng khi phân tách sản phẩm",
                'gia'=>$giamtru*$model->gia,
                'tong'=>$giamtru*$model->gia,
                'sanpham'=>array(array(
                    'sanpham'=>$id,
                    'ghichu'=>'',
                    'soluong'=>$giamtru,
                    'gia'=>$model->gia
                )),
                'trangthai'=>1,
                'type'=>7,
                "nguoitao"=>$nguoitao,
                'kho'=>$kho 
            ));  
            
            $model->soluong -= $giamtru;
            
            //chua giam tru so luong: bo sung: 06/05/2020
            db::query("update sanpham set soluong = {$model->soluong} 
                where ((id = $id) or (ma='' and cha=$id)) and kho = $kho");
        }
        
        $products = array();
        $list_done = array();
        for($i=1;$i<=$_POST['soluongthem'];$i++){
            $products[$i-1] = array(
                'ten'=>$_POST['ten'].sprintf('%0'.$_POST['sososauten'].'d',$_POST['sotenbatdau']+$i-1),                 
                'ma'=>$codes[$i-1],   
                'cha'=>$id,       
                "soluong"  => $_POST['soluong'], //06/05/2020     
                'kho' => $kho,                   //06/06/2020    
            );
            
            $products[$i-1] = model::extend($model,$products[$i-1]); 
            
            unset($products[$i-1]->ngay,$products[$i-1]->id);
            
            //var_dump($products[$i-1]);
                
            $products[$i-1] = $products[$i-1]->insert(true); 
            
            if(!$products[$i-1]) continue;
            
            $product_id = $products[$i-1]->id;
            
            //if(!$product_id) continue;
            
            $list_done[] = $product_id;
            
            if($model->soluong){ 
                //tự động thêm phiếu kiểm kho
                $ff = array(
                    "type" =>6,
                    "sanpham"=>
                    array( array( 
                      "sanpham"=> $product_id,
                      "gia"=> $products[$i-1]->gia, 
                      "soluong" => $_POST['soluong'], //edited 06/05/2020    $model->soluong,                         
                      "soluongcu"=> 0,
                    )),           
                    "trangthai"=>1,
                    "nguoitao"=> $nguoitao,
                    "ngay"=> time(),
                    "ghichu"=> "Phiếu kiểm kho được tạo tự động khi phân tách Hàng hóa",
                    'ma'=>common::autoCode(1,array(),'KK',6,'hoadon'),
                    "kho"  => $kho,
                    'tong' => $_POST['soluong']*$products[$i-1]->gia,  //$model->soluong*$products[$i-1]->gia
                );
                common::themhoadon($ff);
            }     
        }
        
        if($list_done)
            echo json_encode(array(
                'error'=>'',
                'message'=>'Phân tách sản phẩm thành công',
                'ids'=>implode(',',$list_done),             
            ));
        else
            echo json_encode(array(
                'error'=>'Không thể Phân tách sản phẩm',
                          
            ));
    }
    
    public function doeditNDM(){
         
        $model = model::load($_POST,'nhomdinhmuc');
        //var_dump($model);die(); 
        
        $oldModel = nhomdinhmuc::get(input::post('id'));
        
        if($oldModel->cha!=$model->cha){
        
            if($model->cha>0){
                $cha = nhomdinhmuc::get($model->cha);
                if(!$cha){
                    echo json_encode(array('error'=>___('Invalid parent category')));
                    die();
                }
                $model->cap = $cha->cap + 1;
            }else $model->cap = 1;
            
            //find child
            $children = nhomdinhmuc::cat_recursive($oldModel->kho,$model->id);
            
            if($children){
                foreach($children as $child){
                    $child->cap += $model->cap - $oldModel->cap;
                    db('nhomdinhmuc')->
                        update(array('cap'=>$child->cap))->
                        where('id','=',$child->id)->
                        execute();                     
                }
            }
        
        }
        
        $model->update();
        echo json_encode(array('message'=>___('Update successfully')));        
    } 
    public function doeditDM(){
         
        $model = model::load($_POST,'danhmuc');
        //var_dump($model);die(); 
        $oldModel = danhmuc::get(input::post('id'));
        
        if($oldModel->cha!=$model->cha){
        
            if($model->cha>0){
                $cha = damhmuc::get($model->cha);
                if(!$cha){
                    echo json_encode(array('error'=>___('Invalid parent category')));
                    die();
                }
                $model->cap = $cha->cap + 1;
            }else $model->cap = 1;
            
            //find child
            $children = danhmuc::cat_recursive($oldModel->kho,$model->id);
            
            if($children){
                foreach($children as $child){
                    $child->cap += $model->cap - $oldModel->cap;
                    db('danhmuc')->
                        update(array('cap'=>$child->cap))->
                        where('id','=',$child->id)->
                        execute();                     
                }
            }
        
        }
        
        $model->update();
        echo json_encode(array('message'=>___('Update successfully')));        
    } 
    public function doaddndm(){
        $model = model::load($_POST,'nhomdinhmuc');
        if($model->cha>0){
            $cha = nhomdinhmuc::get($model->cha);
            if(!$cha){
                echo json_encode(array('error'=>___('Invalid parent group')));
                die();
            }
            $model->cap = $cha->cap + 1;
        }else $model->cap = 1;
        $model->insert();
        echo json_encode(array('message'=>___('Insert successfully')));
    }
    public function doadddm(){
        $model = model::load($_POST,'danhmuc');
        if($model->cha>0){
            $cha = danhmuc::get($model->cha);
            if(!$cha){
                echo json_encode(array('error'=>___('Invalid parent category')));
                die();
            }
            $model->cap = $cha->cap + 1;
        }else $model->cap = 1;
        $model->insert();
        echo json_encode(array('message'=>___('Insert successfully')));
    }
     
    public function doaddDTSX(){
        
        $email = input::post('email');
        $model = new doitac;
        $model = $model->find_by_email($email);
        if($model){
            echo json_encode(array('error'=>___('Duplicate email')));
        }else{
            $model = new admin;
            $model = $model->find_by_email($email);
            if($model){
                echo json_encode(array('error'=>___('Duplicate email')));
            }else{
                $model = model::load($_POST,'doitac');
                $model->password = md5($model->password);
                //var_dump($model);die();
                $model->insert();
                echo json_encode(array('message'=>___('Insert successfully')));
            }
        }
    }
    public function doaddNV(){
        
        $email = input::post('email');
        $model = new nhanvien;
        $model = $model->find_by_email($email);
        if($model){
            echo json_encode(array('error'=>___('Duplicate email')));
        }else{
            $model = model::load($_POST,'nhanvien');
            $model->password = md5($model->password);
            //var_dump($model);die();
            $model->insert();
            echo json_encode(array('message'=>___('Insert successfully')));
        }
    } 
    
    public function doaddAdmin(){
        /*
ten: Trường Trung học phổ thông Cẩm Xuyên
email: hoidhxd@gmail.com
password: 123456
_password: 123456
diachi: 
sdt: 
        
        */
        $email = input::post('email');
        $model = new admin;
        $model = $model->find_by_email($email);
        if($model){
            echo json_encode(array('error'=>___('Duplicate email')));
        }else{
            
            //tim trong doi tac
            $model = new doitac;
            $model = $model->find_by_email($email);
            if($model){
                echo json_encode(array('error'=>___('Duplicate email')));
            }else{
            
                $model = model::load($_POST,'admin');
                $model->password = md5($model->password);
                //var_dump($model);
                $model->insert();
                echo json_encode(array('message'=>___('Insert successfully')));
            }
        }
    }
    public function doeditCD(){
         
        $model = model::load($_POST,'congdoan');
                 
        $model->update();
        echo json_encode(array('message'=>___('Update successfully')));
         
    }
    public function doeditBL(){
         
        $model = model::load($_POST,'bangluong');
                 
        $model->update();
        echo json_encode(array('message'=>___('Update successfully')));
         
    }
    public function doeditPB(){
         
        $model = model::load($_POST,'phongban');
                 
        $model->update();
        echo json_encode(array('message'=>___('Update successfully')));
         
    } 
    public function doeditdinhmuc(){
        $model = model::load($_POST,'dinhmuc');
                 
        $model->update();
        echo json_encode(array('message'=>___('Update successfully')));
    }
    public function doeditVT(){
        //$email = input::post('email');
        //$id = input::post('id');
         
         
        $model = model::load($_POST,'vaitro');
                 
        $model->update();
        echo json_encode(array('message'=>___('Update successfully')));
         
    } 
    public function doeditSP(){
        
        $id = input::post('id');
        if(!$id) {
            echo json_encode(array('error'=>___('Empty id')));
            die();
        }
        $old = sanpham::get($id);
        if(!$id) {
            echo json_encode(array('error'=>___('Empty product')));
            die();
        }
        
        sanpham::concua($old); //06/05/2020 -> cho vao model
        
        if(!empty($old->concua)){
            $model = model::load(array(
                'soluong'=>$_POST['soluong'],
                'id'=>$_POST['id'],
                'kho'=>$_POST['kho'],
            ),'sanpham');
        }else{
         
            $model = model::load($_POST,'sanpham');
            
            //check trung ma~
            $check = db::query("select * from sanpham where ma='".$model->ma."' and id != ".$model->id."");
            if($check){
                echo json_encode(array('error'=>___('Duplicate product code')));
                die();
            }
        
        }         
        $model->update();
        
        if($model->soluong != $old->soluong){
            $nguoitao = session::get('login_id');
            $login_type = session::get('login_type');            
            $nguoitao = $login_type[0].$nguoitao;
            //tự động thêm phiếu kiểm kho
            $ff = array(
                "type" =>6,
                "sanpham"=>
                array( array( 
                  "sanpham"=> $model->id,
                  "gia"=> $model->gia, 
                  "soluong" => $model->soluong,  
                   
                  "soluongcu"=> $old->soluong,
                )),
       
                "trangthai"=>1,
                "nguoitao"=> $nguoitao,
                "ngay"=> time(),
                "ghichu"=> "Phiếu kiểm kho được tạo tự động khi sửa Hàng hóa",
                'ma'=>common::autoCode(1,array(),'KK',6,'hoadon'),
                "kho"  => $model->kho,
                'tong' => ($model->soluong-$old->soluong)*$model->gia, 
            );
            common::themhoadon($ff);
        }
        
        echo json_encode(array('message'=>___('Update successfully')));
         
    } 
    public function doeditKH(){
        $email = input::post('email');
        $id = input::post('id');
        //find email where id <> id
        $model = new doitac;
        $model = $model->first(
            array(
                'conditions'=>array(
                    array('email','=',$email),
                    array('id','!=',$id),
                     
                )
            )
        );
        if($model){
            echo json_encode(array('error'=>___('Duplicate email')));
        }else{
            if(input::post('password')=='') unset($_POST['password']);
            $model = model::load($_POST,'doitac');
                     
            $model->update();
            echo json_encode(array('message'=>___('Update successfully')));
        }
    } 
    public function doeditNCC(){
        $email = input::post('email');
        $id = input::post('id');
        //find email where id <> id
        $model = new doitac;
        $model = $model->first(
            array(
                'conditions'=>array(
                    array('email','=',$email),
                    array('id','!=',$id),
                     
                )
            )
        );
        if($model){
            echo json_encode(array('error'=>___('Duplicate email')));
        }else{
            if(input::post('password')=='') unset($_POST['password']);
            $model = model::load($_POST,'doitac');
                     
            $model->update();
            echo json_encode(array('message'=>___('Update successfully')));
        }
    } 
    public function doeditDTSX(){
        $email = input::post('email');
        $id = input::post('id');
        //find email where id <> id
        $model = new doitac;
        $model = $model->first(
            array(
                'conditions'=>array(
                    array('email','=',$email),
                    array('id','!=',$id),
                    //array('vaitro','=',2),
                )
            )
        );
        if($model){
            echo json_encode(array('error'=>___('Duplicate email')));
        }else{
            $model = new admin;
            $model = $model->first(
                array(
                    'conditions'=>array(
                        array('email','=',$email),                         
                    )
                )
            );
            if($model){
                echo json_encode(array('error'=>___('Duplicate email')));
            }else{
                if(input::post('password')=='') unset($_POST['password']);
                $model = model::load($_POST,'doitac');
                if(!empty($model->password)) $model->password = md5($model->password);            
                $model->update();
                echo json_encode(array('message'=>___('Update successfully')));
            }
        }
    } 
    
    public function doeditNV(){
        $email = input::post('email');
        $id = input::post('id');
        //find email where id <> id
        $model = new nhanvien;
        $model = $model->first(
            array(
                'conditions'=>array(
                    array('email','=',$email),
                    array('id','!=',$id),
                     
                )
            )
        );
        if($model){
            echo json_encode(array('error'=>___('Duplicate email')));
        }else{
            if(input::post('password')=='') unset($_POST['password']);
            $model = model::load($_POST,'nhanvien');
            if(!empty($model->password)) $model->password = md5($model->password);            
            $model->update();
            echo json_encode(array('message'=>___('Update successfully')));
        }
    } 
    
    public function doeditAdmin(){
        $email = input::post('email');
        $id = input::post('id');
        //find email where id <> id
        $model = new admin;
        $model = $model->first(
            array(
                'conditions'=>array(
                    array('email','=',$email),
                    array('id','!=',$id)
                )
            )
        );
        if($model){
            echo json_encode(array('error'=>___('Duplicate email')));
        }else{
            if(input::post('password')=='') unset($_POST['password']);
            $model = model::load($_POST,'admin');
            if(!empty($model->password)) $model->password = md5($model->password);            
            $model->update();
            echo json_encode(array('message'=>___('Update successfully')));
        }
    } 
    public function deletedinhmuc(){
        $id = input::post('id');
        if(!$id){
            echo json_encode(array('error'=>___('Empty norm')));
            die();
        } 
        db::query("delete from dinhmuc where id = $id");
        echo json_encode(array('message'=>___('Delete successfully'),'closeModal'=>1));
    }
    public function deleteNDM(){
        $id = input::post('id');
        if(!$id){
            echo json_encode(array('error'=>___('Empty group')));
            die();
        } 
        db::query("delete from nhomdinhmuc where id = $id");
        echo json_encode(array('message'=>___('Delete successfully')));
    }
    public function deleteDM(){
        $id = input::post('id');
        if(!$id){
            echo json_encode(array('error'=>___('Empty category')));
            die();
        } 
        db::query("delete from danhmuc where id = $id");
        echo json_encode(array('message'=>___('Delete successfully')));
    }
    public function deleteSP(){
        $id = input::post('id');
        if(!$id){
            echo json_encode(array('error'=>___('Empty product')));
            die();
        } 
        //db::query("delete from sanpham where id = $id");
        db::query("delete from sanpham where (id = $id) or (cha = $id and ma = '')");
        
        //xoa cac phieu kiem kho lien quan
        
        echo json_encode(array('message'=>___('Delete successfully')));
    }
    public function deleteVT(){
        $id = input::post('id');
        if(!$id){
            echo json_encode(array('error'=>___('Empty role')));
            die();
        } 
        db::query("delete from vaitro where id = $id");
        echo json_encode(array('message'=>___('Delete successfully')));
    }
    public function deletePB(){
        $id = input::post('id');
        if(!$id){
            echo json_encode(array('error'=>___('Empty deparment')));
            die();
        } 
        db::query("delete from phongban where id = $id");
        echo json_encode(array('message'=>___('Delete successfully')));
    }
    public function deleteBL(){
        $id = input::post('id');
        if(!$id){
            echo json_encode(array('error'=>___('Empty pay-sheet')));
            die();
        } 
        db::query("delete from bangluong where id = $id");
        echo json_encode(array('message'=>___('Delete successfully')));
    }
    public function deleteCD(){
        $id = input::post('id');
        if(!$id){
            echo json_encode(array('error'=>___('Empty stage')));
            die();
        } 
        db::query("delete from congdoan where id = $id");
        echo json_encode(array('message'=>___('Delete successfully')));
    }
    public function deleteKH(){
        $id = input::post('id');
        if(!$id){
            echo json_encode(array('error'=>___('Empty customer')));
            die();
        } 
        db::query("delete from doitac where id = $id");
        echo json_encode(array('message'=>___('Delete successfully')));
    }
    public function deleteNCC(){
        $id = input::post('id');
        if(!$id){
            echo json_encode(array('error'=>___('Empty supplier')));
            die();
        } 
        db::query("delete from doitac where id = $id");
        echo json_encode(array('message'=>___('Delete successfully')));
    }
    public function deleteDTSX(){
        $id = input::post('id');
        if(!$id){
            echo json_encode(array('error'=>___('Empty partner')));
            die();
        } 
        db::query("delete from doitac where id = $id");
        echo json_encode(array('message'=>___('Delete successfully')));
    }
    public function deleteNV(){
        $id = input::post('id');
        if(!$id){
            echo json_encode(array('error'=>___('Empty employee')));
            die();
        } 
        db::query("delete from nhanvien where id = $id");
        echo json_encode(array('message'=>___('Delete successfully')));
    }
    public function deleteAdmin(){
        $id = input::post('id');
        if(!$id){
            echo json_encode(array('error'=>___('Empty admin')));
            die();
        } 
        db::query("delete from admin where id = $id");
        echo json_encode(array('message'=>___('Delete successfully')));
    }
    public function deleteSelectedVT(){
        $ids = input::post('ids');
        if(!$ids){
            echo json_encode(array('error'=>___('Empty list role')));
            die();
        } 
        db::query("delete from vaitro where id IN (".implode(",",$ids).")");
        echo json_encode(array('message'=>___('Delete successfully')));
    }
    public function deleteSelectedPB(){
        $ids = input::post('ids');
        if(!$ids){
            echo json_encode(array('error'=>___('Empty list department')));
            die();
        } 
        db::query("delete from phongban where id IN (".implode(",",$ids).")");
        echo json_encode(array('message'=>___('Delete successfully')));
    }
    public function deleteSelectedBL(){
        $ids = input::post('ids');
        if(!$ids){
            echo json_encode(array('error'=>___('Empty list pay-sheet')));
            die();
        } 
        db::query("delete from bangluong where id IN (".implode(",",$ids).")");
        echo json_encode(array('message'=>___('Delete successfully')));
    }
    public function deleteSelectedDTSX(){
        $ids = input::post('ids');
        if(!$ids){
            echo json_encode(array('error'=>___('Empty list partner')));
            die();
        } 
        db::query("delete from doitac where id IN (".implode(",",$ids).")");
        echo json_encode(array('message'=>___('Delete successfully')));
    }
    
    public function deleteSelectedNV(){
        $ids = input::post('ids');
        if(!$ids){
            echo json_encode(array('error'=>___('Empty list employee')));
            die();
        } 
        db::query("delete from nhanvien where id IN (".implode(",",$ids).")");
        echo json_encode(array('message'=>___('Delete successfully')));
    }
    
    public function deleteSelectedNCC(){
        $ids = input::post('ids');
        if(!$ids){
            echo json_encode(array('error'=>___('Empty list supplier')));
            die();
        } 
        db::query("delete from doitac where id IN (".implode(",",$ids).")");
        echo json_encode(array('message'=>___('Delete successfully')));
    }
    public function deleteSelectedCD(){
        $ids = input::post('ids');
        if(!$ids){
            echo json_encode(array('error'=>___('Empty list stage')));
            die();
        } 
        db::query("delete from congdoan where id IN (".implode(",",$ids).")");
        echo json_encode(array('message'=>___('Delete successfully')));
    }
    public function deleteSelectedKH(){
        $ids = input::post('ids');
        if(!$ids){
            echo json_encode(array('error'=>___('Empty list customer')));
            die();
        } 
        db::query("delete from doitac where id IN (".implode(",",$ids).")");
        echo json_encode(array('message'=>___('Delete successfully')));
    }
    
    public function deleteSelectedAdmin(){
        $ids = input::post('ids');
        if(!$ids){
            echo json_encode(array('error'=>___('Empty list admin')));
            die();
        } 
        db::query("delete from admin where id IN (".implode(",",$ids).")");
        echo json_encode(array('message'=>___('Delete successfully')));
    }
    
    public function toggleActiveSelectedNV(){
        $ids = input::post('ids');
        if(!$ids){
            echo json_encode(array('error'=>___('Empty list employee')));
            die();
        } 
        db::query("update nhanvien set trangthai = (case trangthai when 1 then 0 else 1 end) where id IN (".implode(",",$ids).")");
        echo json_encode(array('message'=>___('Toggle active selected partner successfully')));
    }
    
    public function toggleActiveSelectedDTSX(){
        $ids = input::post('ids');
        if(!$ids){
            echo json_encode(array('error'=>___('Empty list partner')));
            die();
        } 
        db::query("update doitac set trangthai = (case trangthai when 1 then 0 else 1 end) where id IN (".implode(",",$ids).")");
        echo json_encode(array('message'=>___('Toggle active selected partner successfully')));
    }
    
    public function toggleActiveSelectedNCC(){
        $ids = input::post('ids');
        if(!$ids){
            echo json_encode(array('error'=>___('Empty list supplier')));
            die();
        } 
        db::query("update doitac set trangthai = (case trangthai when 1 then 0 else 1 end) where id IN (".implode(",",$ids).")");
        echo json_encode(array('message'=>___('Toggle active selected supplier successfully')));
    }
    
    public function toggleActiveSelectedKH(){
        $ids = input::post('ids');
        if(!$ids){
            echo json_encode(array('error'=>___('Empty list customer')));
            die();
        } 
        db::query("update doitac set trangthai = (case trangthai when 1 then 0 else 1 end) where id IN (".implode(",",$ids).")");
        echo json_encode(array('message'=>___('Toggle active selected customer successfully')));
    }
    
    public function toggleActiveSelectedAdmin(){
        $ids = input::post('ids');
        if(!$ids){
            echo json_encode(array('error'=>___('Empty list admin')));
            die();
        } 
        db::query("update admin set trangthai = (case trangthai when 1 then 0 else 1 end) where id IN (".implode(",",$ids).")");
        echo json_encode(array('message'=>___('Toggle active selected admin successfully')));
    }
    
    public function danhmuc($kho=0,$full=1){
        $a = danhmuc::_danhmuc($kho,$full);
         
        echo json_encode($a);
    }
    
    public function nhomdinhmuc($kho=0){
        $a = nhomdinhmuc::children($kho,0);
         
        echo json_encode($a);
    }
    
    public function editTitleDM(){
        $id = input::post('id');
        $ten = input::post('ten');
        
        if(!$id || !$ten) die();
        
        $model = danhmuc::get($id);
        if(!$model) die();
        
        $model->ten = $ten;
        $model->update();
    }
    public function editTitleNDM(){
        $id = input::post('id');
        $ten = input::post('ten');
        
        if(!$id || !$ten) die();
        
        $model = nhomdinhmuc::get($id);
        if(!$model) die();
        
        $model->ten = $ten;
        $model->update();
    }
    public function deleteSelecteddinhmuc(){
        $ids = input::post('ids');
        if(!$ids){
            echo json_encode(array('error'=>___('Empty list norm')));
            die();
        } 
        db::query("delete from dinhmuc where id IN (".implode(",",$ids).")");
        echo json_encode(array('message'=>___('Delete successfully'),'closeModal'=>1));
    }
    public function deleteSelectedDM(){
        $ids = input::post('ids');
        if(!$ids){
            echo json_encode(array('error'=>___('Empty list category')));
            die();
        } 
        db::query("delete from danhmuc where id IN (".implode(",",$ids).")");
        echo json_encode(array('message'=>___('Delete successfully')));
    }
    public function deleteSelectedNDM(){
        $ids = input::post('ids');
        if(!$ids){
            echo json_encode(array('error'=>___('Empty list group')));
            die();
        } 
        db::query("delete from nhomdinhmuc where id IN (".implode(",",$ids).")");
        echo json_encode(array('message'=>___('Delete successfully')));
    }
    public function deleteSelectedSP(){
        $ids = input::post('ids');
        if(!$ids){
            echo json_encode(array('error'=>___('Empty list product')));
            die();
        } 
        //db::query("delete from sanpham where id IN (".implode(",",$ids).")");
        db::query("delete from sanpham where (id IN (".implode(",",$ids).")) or (cha IN (".implode(",",$ids).") and ma = '')");
        
        //xoa cac phieu kiem kho lien quan
        
        echo json_encode(array('message'=>___('Delete successfully')));
    }
    
    public function doaddNCC(){
         
        $email = input::post('email');
        $model = new admin;
        $model = $model->find_by_email($email);
        if($model){
            echo json_encode(array('error'=>___('Duplicate email')));
        }else{
            
            //tim trong doi tac
            $model = new doitac;
            $model = $model->find_by_email($email);
            if($model){
                echo json_encode(array('error'=>___('Duplicate email')));
            }else{
            
                $model = model::load($_POST,'doitac');
                 
                //var_dump($model);
                $model->insert();
                echo json_encode(array('message'=>___('Insert successfully')));
            }
        }
    }
    public function doaddKH(){
         
        $email = input::post('email');
        $model = new admin;
        $model = $model->find_by_email($email);
        if($model){
            echo json_encode(array('error'=>___('Duplicate email')));
        }else{
            
            //tim trong doi tac
            $model = new doitac;
            $model = $model->find_by_email($email);
            if($model){
                echo json_encode(array('error'=>___('Duplicate email')));
            }else{
            
                $model = model::load($_POST,'doitac');
                 
                //var_dump($model);
                $model->insert();
                echo json_encode(array('message'=>___('Insert successfully')));
            }
        }
    }
    public function doaddSPDM(){
        $model = model::load($_POST,'dinhmuc');
         
        //var_dump($model);
        $model->insert();
        echo json_encode(array('message'=>___('Insert successfully')));
    }
    public function doaddpb(){
                           
        $model = model::load($_POST,'phongban');
         
        //var_dump($model);
        $model->insert();
        echo json_encode(array('message'=>___('Insert successfully')));
             
    }
    public function doaddbl(){
                           
        $model = model::load($_POST,'bangluong');
         
        //var_dump($model);
        $model->insert();
        echo json_encode(array('message'=>___('Insert successfully')));
             
    }
    public function doaddvt(){
                           
        $model = model::load($_POST,'vaitro');
         
        //var_dump($model);
        $model->insert();
        echo json_encode(array('message'=>___('Insert successfully')));
             
    }
    public function doaddcd(){
                           
        $model = model::load($_POST,'congdoan');
         
        //var_dump($model);
        $model->insert();
        echo json_encode(array('message'=>___('Insert successfully')));
             
    }
    
    public function doaddsp(){
        
        //06/01/2020
        //nhom_soluong nhom_sanpham
        if(input::post('nhom_soluong')){
            if($_POST['nhom_soluong']!=$_POST['nhom_soluongcu']){
                $nhom_soluong = input::post('nhom_soluong');
                $nhom_sanpham = input::post('nhom_sanpham');
            }
            unset($_POST['nhom_sanpham'],$_POST['nhom_soluong'],$_POST['nhom_soluongcu']);            
        }
                                   
        $model = model::load($_POST,'sanpham');
         
        //var_dump($model);die();
        
        //check trung ma~
        $check = db::query("select * from sanpham where ma='".$model->ma."'");
        if($check){
            echo json_encode(array('error'=>___('Duplicate product code')));
            die();
        }
        
        if($model=$model->insert(true)){
            
            if($model->soluong>0){
                $nguoitao = session::get('login_id');
                $login_type = session::get('login_type');
                
                $nguoitao = $login_type[0].$nguoitao;
                //tự động thêm phiếu kiểm kho
                $ff = array(
                    "type" =>6,
                    "sanpham"=>
                    array( array( 
                      "sanpham"=> $model->id,
                      "gia"=> $model->gia, 
                      "soluong" => $model->soluong,  
                       
                      "soluongcu"=> 0,
                    )),
           
                    "trangthai"=>1,
                    "nguoitao"=> $nguoitao,
                    "ngay"=> time(),
                    "ghichu"=> "Phiếu kiểm kho được tạo tự động khi thêm mới Hàng hóa",
                    'ma'=>common::autoCode(1,array(),'KK',6,'hoadon'),
                    "kho"  => $model->kho,
                    'tong' => $model->soluong*$model->gia, 
                );
                common::themhoadon($ff);
            }
            
            //06/01/2020
            if(!empty($nhom_sanpham)){
                $dinhmuc = model::load(array(
                    'sanpham'=>$nhom_sanpham,
                    'cha'=>$model->id, 
                    'soluong'=>$nhom_soluong, 
                    'kho'=>input::post('kho')
                ),'dinhmuc');
                $dinhmuc->insert();
            }
            
            echo json_encode(array(
                'message'=>___('Insert successfully'),
                'sanpham'=>$model->attributes(), //bo sung 06/05/2020
            ));
        }
            
        else
            echo json_encode(array('error'=>___('Can\'t insert')));
             
    }
    
    public function saveRole(){
        $ids = input::post('ids');
        $id = input::post('id');
        if(!$id){
            echo json_encode(array('error'=>___('Empty role')));
            die();
        } 
        
        $vaitro = vaitro::get($id);
        if(!$vaitro){
            echo json_encode(array('error'=>___('Empty role')));
            die();
        }
                
        db::query("delete from phanquyen where vaitro=$id and kho=".$vaitro->kho);
        
        if($ids){
            foreach($ids as $i){
                $model = model::load(array(
                    'vaitro'=>$id,
                    'kho'=>$vaitro->kho,
                    'quyen'=>$i,
                ),'phanquyen');
                $model->insert();
            }
        }
        
        echo json_encode(array('message'=>___('Update successfully'),'notreload'=>1));
    }
    public function searchnhanvien($kho=0){
        $q = $_REQUEST['q'];
        $data = db::query("select * from nhanvien where (ten like '%$q%' or email like '%$q%') 
            and trangthai=1 and kho=$kho limit 10",'nhanvien');
        if(!$data) $data = array();
        
        echo json_encode(array_map(function($a){$b = $a->attributes();$b['text']=$b['ten'];return $b;},$data));
    }
    public function searchdoitacsanxuat($kho=0){
        $q = $_REQUEST['q'];
        $data = db::query("select * from doitac where (ten like '%$q%' or email like '%$q%') 
            and vaitro=2 and trangthai=1 and kho=$kho limit 10",'doitac');
        if(!$data) $data = array();
        
        echo json_encode(array_map(function($a){$b = $a->attributes();$b['text']=$b['ten'];return $b;},$data));
    }
    public function searchnhacungcap($kho=0){
        $q = $_REQUEST['q'];
        $data = db::query("select * from doitac where (ten like '%$q%' or email like '%$q%') 
            and vaitro=1 and trangthai=1 and kho=$kho limit 10",'doitac');
        if(!$data) $data = array();
        
        echo json_encode(array_map(function($a){$b = $a->attributes();$b['text']=$b['ten'];return $b;},$data));
    }
    public function searchkhachhang($kho=0){
        $q = $_REQUEST['q'];
        $data = db::query("select * from doitac where (ten like '%$q%' or email like '%$q%') 
            and vaitro=3 and trangthai=1 and kho=$kho limit 10",'doitac');
        if(!$data) $data = array();
         
        echo json_encode(array_map(function($a){$b = $a->attributes();$b['text']=$b['ten'];return $b;},$data));
    }
    
    public function themdonhang(){
        //var_dump($_POST);
        /*
array(9) {
  ["ma"]=>
  string(0) ""
  ["giamgia"]=>
  string(1) "0"
  ["phithem"]=>
  string(1) "0"
  ["ngay"]=>
  string(19) "04/05/2020 22:08:08"
  ["doitac"]=>
  string(1) "3"   
  ["ghichu"]=>
  string(0) ""
  ["sanpham"]=>
  array(1) {
    [0]=>
    array(17) {
      ["selected"]=>
      string(5) "false"
      ["disabled"]=>
      string(5) "false"
      ["text"]=>
      string(11) "Cúc type 1"
      ["id"]=>
      string(1) "3"
      ["ten"]=>
      string(11) "Cúc type 1"
      ["cha"]=>
      string(1) "0"
      ["danhmuc"]=>
      string(1) "6"
      ["soluong"]=>
      string(2) "10"
      ["ngay"]=>
      string(19) "2020-04-28 11:41:42"
      ["donvi"]=>
      string(3) "cai"
      ["ma"]=>
      string(4) "cuc1"
      ["ghichu"]=>
      string(0) ""
      ["gia"]=>
      string(3) "500"
      ["kho"]=>
      string(1) "1"
      ["nhom"]=>
      string(1) "0"
      ["_resultId"]=>
      string(35) "select2-searchProduct-result-s829-3"
      ["giamgia"]=>
      string(1) "0"
    }
  }
  ["tu"]=>
  string(10) "nhacungcap"
  kho
  
  moi bo sung thanhtoan,duan
}        
        */
        $tu = input::post('tu');
        if($tu=='nhanvien'){
            $_POST['nhanvien'] = input::post('doitac');
            $_POST['doitac'] = 0;
        }
        
        $sampham = input::post('sanpham');
        $thanhtoan = input::post('thanhtoan');
        
        unset($_POST['tu'],$_POST['sanpham'],$_POST['thanhtoan']);
        
        $model = model::load($_POST,'hoadon');
        $model->ngay = time::totime($model->ngay,"/^(\d+)\/(\d+)\/(\d+) (\d+)\:(\d+)\:(\d+)$/",array(4,5,6,2,1,3));//strtotime($model->ngay);
        
        if(empty($model->type))
            $model->type = 1;
        
        if(!$model->ma){
            $model->ma = common::autoCode(0,array(),$model->type == 1?'NH':'XH');
        }
        
        $nguoitao = session::get('login_id');
        $login_type = session::get('login_type');        
        $model->nguoitao = $login_type[0].$nguoitao;
        
        @file_put_contents('log.txt',var_export($model->attributes(),true),FILE_APPEND);
        
        $model = $model->insert(true);
        
        @file_put_contents('log.txt',var_export($model->attributes(),true),FILE_APPEND);
        
        if(!$model){
            echo json_encode(array('error'=>___('Can\'t insert invoice! Maybe duplicate code of invoice')));
            die();
        }
        
        if($tu=='doitacsanxuat'){
            //voi PA2
            $model2 = clone $model;
            unset($model2->id);
            $model2->kho = $model->doitac;
            $model2->doitac = $model->kho;
            $model2->ma = common::autoCode(0,array(),$model->type == 3?'NH':'XH');  
            $model2->type = $model->type == 3?1:3; //05/27/2020 
            $model2->cha = $model->id; //bo sung 05/26/2020   
            $model2->table = 'hoadon';     //bo sung 05/26/2020             
            $model2 = $model2->insert(true);
        }
        
        if($thanhtoan>0){
            
            //tao phieu
            //tao phieu chi type=2 cho kho
            //tao phieu thu type=4 cho kho
            //$nguoitao = session::get('login_id');
            //$login_type = session::get('login_type');        
            $nguoitao = $login_type[0].$nguoitao;
            
            $cc = array(
                'ma'=>common::autoCode(0,array(),$model->type==1?'PC':'PT'),
                'ghichu'=>'',
                'nguoitao'=>$nguoitao, 
                'tong'=>$thanhtoan,         
                 
                'type'=>$model->type+1,
                'kho'=>$model->kho,
                'trangthai'=>1,
                'gia'=>$thanhtoan,  
                'ngay'=>time(),
                
                'cha'=>$model->id,
                'duan'=>$_POST['duan'],
            );
            if($tu=='nhanvien'){
                $cc['nhanvien'] = $_POST['nhanvien'];                
            }else{
                $cc['doitac'] = $_POST['doitac'];  
            }            
             
            $hoadon = model::load($cc,'hoadon');
            $hoadon->insert();
            
            //PA2
            if($tu=='doitacsanxuat'){
                
                $hoadon = model::load(array(
                    'ma'=>common::autoCode(0,array(),$model->type==1?'PT':'PC'),
                    'ghichu'=>'',
                    'nguoitao'=>$nguoitao, 
                    'tong'=>$thanhtoan,         
                     
                    'type'=>$model->type==1?4:2,
                    'kho'=>input::post('doitac'),
                    'trangthai'=>1,
                    'gia'=>$thanhtoan,  
                    'ngay'=>time(),
                    
                    'cha'=>$model2->id,//$model->id,
                    
                    'doitac'=>$model->kho,
                    'duan'=>$_POST['duan'],
                ),'hoadon');
                $hoadon->insert();
            }     
        }
        
        foreach($sampham as $sp){
            //id->sanpham soluong gia giamgia (hoadon)
            $model2 = model::load(array(
                'sanpham'=>$sp["id"],
                'soluong'=>$sp["soluong"],
                'gia'=>$sp["gia"],
                'giamgia'=>$sp["giamgia"],
                'hoadon'=>$model->id,
            ),'hoadon_chitiet');
            $model2->insert();
            
            if($model->type==1){ // phieu nhap
                if($model->kho == $sp['kho']){
                    db::query("update sanpham set gia = '".$sp["gia"]."', soluong = soluong + '".
                    $sp["soluong"]."' where id='".$sp["id"]."'");
                }else{
                    $sp2 = db::query("select * from sanpham where kho=".$model->kho." and ma='' and cha=".$sp["id"],'sanpham');
                    
                    if($sp2){
                        $sp2 = $sp2[0];
                        $sp2->soluong += $sp["soluong"];
                        $sp2->update();
                    }else{
                        $model2 = model::load(array(
                            'cha'=>$sp["id"],
                            'soluong'=>$sp["soluong"],                             
                            'kho'=>$model->kho,
                        ),'sanpham');
                        $model2->insert();
                    }
                }
                
                if($tu=='doitacsanxuat'){
                    if($model->doitac == $sp['kho']){
                        db::query("update sanpham set gia = '".$sp["gia"]."', soluong = soluong - '".
                        $sp["soluong"]."' where id='".$sp["id"]."'");
                    }else{
                        $sp2 = db::query("select * from sanpham where kho=".$model->doitac." and ma='' and cha=".$sp["id"],'sanpham');
                        
                        if($sp2){
                            $sp2 = $sp2[0];
                            $sp2->soluong += $sp["soluong"];
                            $sp2->update();
                        }else{
                            $model2 = model::load(array(
                                'cha'=>$sp["id"],
                                'soluong'=>$sp["soluong"],                             
                                'kho'=>$model->doitac,
                            ),'sanpham');
                            $model2->insert();
                        }
                    }    
                }
            }else{ //phieu xuat (nguoc voi nhap, doi 2 dau cong va dau tru thoi)
                if($model->kho == $sp['kho']){
                    db::query("update sanpham set gia = '".$sp["gia"]."', soluong = soluong - '".
                    $sp["soluong"]."' where id='".$sp["id"]."'");
                }else{
                    $sp2 = db::query("select * from sanpham where kho=".$model->kho." and ma='' and cha=".$sp["id"],'sanpham');
                    
                    if($sp2){
                        $sp2 = $sp2[0];
                        $sp2->soluong += $sp["soluong"];
                        $sp2->update();
                    }else{
                        $model2 = model::load(array(
                            'cha'=>$sp["id"],
                            'soluong'=>$sp["soluong"],                             
                            'kho'=>$model->kho,
                        ),'sanpham');
                        $model2->insert();
                    }
                }
                
                if($tu=='doitacsanxuat'){
                    if($model->doitac == $sp['kho']){
                        db::query("update sanpham set gia = '".$sp["gia"]."', soluong = soluong + '".
                        $sp["soluong"]."' where id='".$sp["id"]."'");
                    }else{
                        $sp2 = db::query("select * from sanpham where kho=".$model->doitac." and ma='' and cha=".$sp["id"],'sanpham');
                        
                        if($sp2){
                            $sp2 = $sp2[0];
                            $sp2->soluong += $sp["soluong"];
                            $sp2->update();
                        }else{
                            $model2 = model::load(array(
                                'cha'=>$sp["id"],
                                'soluong'=>$sp["soluong"],                             
                                'kho'=>$model->doitac,
                            ),'sanpham');
                            $model2->insert();
                        }
                    }    
                }    
            }    
        }
        
        echo json_encode(array('message'=>___('Insert successfully')));
    }
    
    public function deleteNH(){
        $id = input::post('id');
        if(!$id){
            echo json_encode(array('error'=>___('Empty id of invoice')));
            die();
        } 
        $old = hoadon::get($id);
        if(!$old || $old->type!=1){
            echo json_encode(array('error'=>___('You can\'t delete this invoice')));
            die();
        } 
        $old_products = hoadon_chitiet::list_all($id);
        //giam tru so luong cac san pham
        if($old->trangthai==1){
            
            if($old->nhanvien>0){
                $tu = 'nhanvien';
                //$doitac = nhanvien::get($hoadon->nhanvien);
            }else{
                $doitac = doitac::get($old->doitac);
                $tu = $doitac->vaitro == 3?'khachhang':$doitac->vaitro == 2?'doitacsanxuat':'nhacungcap';
            }
            
            foreach($old_products as $sp){ 
                $sp = $sp->attributes();
                //db::query("update sanpham set soluong = soluong - '".
                //    $sp["soluong"]."' where id='".$sp["sanpham"]."'");
                
                $model = sanpham::get($sp["sanpham"]);    
                    
                if($model->kho == $old->kho){
                    db::query("update sanpham set soluong = soluong - '".
                    $sp["soluong"]."' where id='".$sp["sanpham"]."'");
                }else{
                    $sp2 = db::query("select * from sanpham where kho=".$old->kho." and ma='' and cha=".$sp["sanpham"],'sanpham');
                    
                    if($sp2){
                        $sp2 = $sp2[0];
                        $sp2->soluong -= $sp["soluong"];
                        $sp2->update();
                    }else{ //chac k xay ra nhung van lam
                        $model2 = model::load(array(
                            'cha'=>$sp["sanpham"],
                            'soluong'=>-$sp["soluong"],                             
                            'kho'=>$old->kho,
                        ),'sanpham');
                        $model2->insert();
                    }
                }
                 
                if($tu=='doitacsanxuat'){
                    if($old->doitac == $model->kho){
                        db::query("update sanpham set soluong = soluong + '".
                        $sp["soluong"]."' where id='".$sp["sanpham"]."'");
                    }else{
                        $sp2 = db::query("select * from sanpham where kho=".$old->doitac." and ma='' and cha=".$sp["sanpham"],'sanpham');
                        
                        if($sp2){
                            $sp2 = $sp2[0];
                            $sp2->soluong += $sp["soluong"];
                            $sp2->update();
                        }else{ //chac k xay ra nhung van lam
                            $model2 = model::load(array(
                                'cha'=>$sp["sanpham"],
                                'soluong'=>+$sp["soluong"],                             
                                'kho'=>$old->doitac,
                            ),'sanpham');
                            $model2->insert();
                        }
                    }    
                }
                  
            }
        }
        
        //added 05/26/2020
        if($old->doitac>0){
            $clone = db::query("select * from hoadon where cha = $id and `type` in (1,3)");
            if($clone){
                $clone = $clone[0]->id;
                db::query("delete from hoadon_chitiet where hoadon = $clone");
                db::query("delete from hoadon where id = $clone or cha = $clone");
            }
        }
        
        //xoa trong hoadon_chitiet
        db::query("delete from hoadon_chitiet where hoadon = $id");
        
        //xoa trong hoadon
        //db::query("delete from hoadon where id = $id");
        db::query("delete from hoadon where id = $id or cha = $id");
         
        echo json_encode(array('message'=>___('Delete successfully')));
    } 
    public function suadonhang(){
        $id = input::post('id');
        if(!$id){
            echo json_encode(array('error'=>___('Empty id of invoice')));
            die();
        } 
        $hoadon = model::load($_POST,'hoadon');
        //07/05/2020 09:01:17
        $hoadon->ngay=time::totime($hoadon->ngay,"/^(\d+)\/(\d+)\/(\d+) (\d+)\:(\d+)\:(\d+)$/",array(4,5,6,2,1,3));
        $hoadon->update();
        
        echo json_encode(array('message'=>___('Update successfully')));
    }
    
    public function trangthaiNH(){
        $id = input::post('id');
        if(!$id){
            echo json_encode(array('error'=>___('Empty id of invoice')));
            die();
        } 
        $hoadon = hoadon::get($id);
        
        if(!$hoadon || $hoadon->type!=1){
            echo json_encode(array('error'=>___('You can\'t deactive/active this invoice')));
            die();
        } 
        
        $old_products = hoadon_chitiet::list_all($id);
        
        if($hoadon->trangthai==1){
            
            if($hoadon->nhanvien>0){
                $tu = 'nhanvien';
                //$doitac = nhanvien::get($hoadon->nhanvien);
            }else{
                $doitac = doitac::get($hoadon->doitac);
                $tu = $doitac->vaitro == 3?'khachhang':$doitac->vaitro == 2?'doitacsanxuat':'nhacungcap';
            }
            
            //giam tru so luong cac san pham
            foreach($old_products as $sp){ 
                $sp = $sp->attributes();
                //db::query("update sanpham set soluong = soluong - '".
                //$sp["soluong"]."' where id='".$sp["sanpham"]."'");
                
                $model = sanpham::get($sp["sanpham"]);    
                    
                if($model->kho == $hoadon->kho){
                    db::query("update sanpham set soluong = soluong - '".
                    $sp["soluong"]."' where id='".$sp["sanpham"]."'");
                }else{
                    $sp2 = db::query("select * from sanpham where kho=".$hoadon->kho." and ma='' and cha=".$sp["sanpham"],'sanpham');
                    
                    if($sp2){
                        $sp2 = $sp2[0];
                        $sp2->soluong -= $sp["soluong"];
                        $sp2->update();
                    }else{ //chac k xay ra nhung van lam
                        $model2 = model::load(array(
                            'cha'=>$sp["sanpham"],
                            'soluong'=>-$sp["soluong"],                             
                            'kho'=>$hoadon->kho,
                        ),'sanpham');
                        $model2->insert();
                    }
                }
                 
                if($tu=='doitacsanxuat'){
                    if($hoadon->doitac == $model->kho){
                        db::query("update sanpham set soluong = soluong + '".
                        $sp["soluong"]."' where id='".$sp["sanpham"]."'");
                    }else{
                        $sp2 = db::query("select * from sanpham where kho=".$hoadon->doitac." and ma='' and cha=".$sp["sanpham"],'sanpham');
                        
                        if($sp2){
                            $sp2 = $sp2[0];
                            $sp2->soluong += $sp["soluong"];
                            $sp2->update();
                        }else{ //chac k xay ra nhung van lam
                            $model2 = model::load(array(
                                'cha'=>$sp["sanpham"],
                                'soluong'=>+$sp["soluong"],                             
                                'kho'=>$hoadon->doitac,
                            ),'sanpham');
                            $model2->insert();
                        }
                    }    
                }
            }
        }else{
            //tang so luong
            foreach($old_products as $sp){ 
                $sp = $sp->attributes();
                //db::query("update sanpham set soluong = soluong + '".
                //$sp["soluong"]."' where id='".$sp["sanpham"]."'");
                
                $model = sanpham::get($sp["sanpham"]);    
                    
                if($model->kho == $hoadon->kho){
                    db::query("update sanpham set soluong = soluong + '".
                    $sp["soluong"]."' where id='".$sp["sanpham"]."'");
                }else{
                    $sp2 = db::query("select * from sanpham where kho=".$hoadon->kho." and ma='' and cha=".$sp["sanpham"],'sanpham');
                    
                    if($sp2){
                        $sp2 = $sp2[0];
                        $sp2->soluong += $sp["soluong"];
                        $sp2->update();
                    }else{ 
                        $model2 = model::load(array(
                            'cha'=>$sp["sanpham"],
                            'soluong'=>+$sp["soluong"],                             
                            'kho'=>$hoadon->kho,
                        ),'sanpham');
                        $model2->insert();
                    }
                }
                 
                if($tu=='doitacsanxuat'){
                    if($hoadon->doitac == $model->kho){
                        db::query("update sanpham set soluong = soluong - '".
                        $sp["soluong"]."' where id='".$sp["sanpham"]."'");
                    }else{
                        $sp2 = db::query("select * from sanpham where kho=".$hoadon->doitac." and ma='' and cha=".$sp["sanpham"],'sanpham');
                        
                        if($sp2){
                            $sp2 = $sp2[0];
                            $sp2->soluong -= $sp["soluong"];
                            $sp2->update();
                        }else{  
                            $model2 = model::load(array(
                                'cha'=>$sp["sanpham"],
                                'soluong'=>-$sp["soluong"],                             
                                'kho'=>$hoadon->doitac,
                            ),'sanpham');
                            $model2->insert();
                        }
                    }    
                }
            }
        }
        
        //added 05/26/2020
        if($hoadon->doitac>0){
            $clone = db::query("select * from hoadon where cha = $id and `type` in (1,3)");
            if($clone){
                $clone = $clone[0]->id;                
                db::query("update hoadon set trangthai = ".($hoadon->trangthai==1?2:1)." where id = $clone");
                db::query("update hoadon set trangthai = ".($hoadon->trangthai==1?0:1)." where cha = $clone and `type` in (2,4)");
            }
        }
        
        //update trang thai
        db::query("update hoadon set trangthai = ".($hoadon->trangthai==1?2:1)." where id = $id");
        //db::query("update hoadon set trangthai = ".($hoadon->trangthai==1?0:1)." where cha = $id");
        db::query("update hoadon set trangthai = ".($hoadon->trangthai==1?0:1)." where cha = $id and `type` in (2,4)"); //05/26/2020
         
        echo json_encode(array('message'=>___('Change status successfully')));
    }
    
    public function deleteSelectedNH(){
        $ids = input::post('ids');
        if(!$ids){
            echo json_encode(array('error'=>___('Empty list invoice')));
            die();
        } 
        
        foreach($ids as $id){
            $old = hoadon::get($id);
            if(!$old || $old->type!=1){
                echo json_encode(array('error'=>___('You can\'t delete this invoice')));
                die();
            } 
            $old_products = hoadon_chitiet::list_all($id);
            //giam tru so luong cac san pham
            if($old->trangthai==1){
                
                if($old->nhanvien>0){
                    $tu = 'nhanvien';
                    //$doitac = nhanvien::get($hoadon->nhanvien);
                }else{
                    $doitac = doitac::get($old->doitac);
                    $tu = $doitac->vaitro == 3?'khachhang':$doitac->vaitro == 2?'doitacsanxuat':'nhacungcap';
                }
                
                foreach($old_products as $sp){ 
                    $sp = $sp->attributes();
                    //db::query("update sanpham set soluong = soluong - '".
                    //$sp["soluong"]."' where id='".$sp["sanpham"]."'");
                    
                    $model = sanpham::get($sp["sanpham"]);    
                    
                    if($model->kho == $old->kho){
                        db::query("update sanpham set soluong = soluong - '".
                        $sp["soluong"]."' where id='".$sp["sanpham"]."'");
                    }else{
                        $sp2 = db::query("select * from sanpham where kho=".$old->kho." and ma='' and cha=".$sp["sanpham"],'sanpham');
                        
                        if($sp2){
                            $sp2 = $sp2[0];
                            $sp2->soluong -= $sp["soluong"];
                            $sp2->update();
                        }else{ //chac k xay ra nhung van lam
                            $model2 = model::load(array(
                                'cha'=>$sp["sanpham"],
                                'soluong'=>-$sp["soluong"],                             
                                'kho'=>$old->kho,
                            ),'sanpham');
                            $model2->insert();
                        }
                    }
                     
                    if($tu=='doitacsanxuat'){
                        if($old->doitac == $model->kho){
                            db::query("update sanpham set soluong = soluong + '".
                            $sp["soluong"]."' where id='".$sp["sanpham"]."'");
                        }else{
                            $sp2 = db::query("select * from sanpham where kho=".$old->doitac." and ma='' and cha=".$sp["sanpham"],'sanpham');
                            
                            if($sp2){
                                $sp2 = $sp2[0];
                                $sp2->soluong += $sp["soluong"];
                                $sp2->update();
                            }else{ //chac k xay ra nhung van lam
                                $model2 = model::load(array(
                                    'cha'=>$sp["sanpham"],
                                    'soluong'=>+$sp["soluong"],                             
                                    'kho'=>$old->doitac,
                                ),'sanpham');
                                $model2->insert();
                            }
                        }    
                    }
                }
            }
            
            //added 05/26/2020
            if($old->doitac>0){
                $clone = db::query("select * from hoadon where cha = $id and `type` in (1,3)");
                if($clone){
                    $clone = $clone[0]->id;
                    db::query("delete from hoadon_chitiet where hoadon = $clone");
                    db::query("delete from hoadon where id = $clone or cha = $clone");
                }
            }
            
            //xoa trong hoadon_chitiet
            db::query("delete from hoadon_chitiet where hoadon = $id");
        }
        
        //xoa trong hoadon
        //db::query("delete from hoadon where id IN (".implode(",",$ids).")");
        db::query("delete from hoadon where id IN (".implode(",",$ids).") or cha IN (".implode(",",$ids).")");
        
        echo json_encode(array('message'=>___('Delete successfully')));
    }
    
    public function deleteXH(){
        $id = input::post('id');
        if(!$id){
            echo json_encode(array('error'=>___('Empty id of invoice')));
            die();
        } 
        $old = hoadon::get($id);
        if(!$old || $old->type!=3){
            echo json_encode(array('error'=>___('You can\'t delete this invoice')));
            die();
        } 
        $old_products = hoadon_chitiet::list_all($id);
        //cong lai so luong cac san pham
        if($old->trangthai==1){
            
            if($old->nhanvien>0){
                $tu = 'nhanvien';
                //$doitac = nhanvien::get($hoadon->nhanvien);
            }else{
                $doitac = doitac::get($old->doitac);
                $tu = $doitac->vaitro == 3?'khachhang':$doitac->vaitro == 2?'doitacsanxuat':'nhacungcap';
            }
            
            foreach($old_products as $sp){ 
                $sp = $sp->attributes();
                //db::query("update sanpham set soluong = soluong + '".
                //    $sp["soluong"]."' where id='".$sp["sanpham"]."'");
                
                $model = sanpham::get($sp["sanpham"]);    
                    
                if($model->kho == $old->kho){
                    db::query("update sanpham set soluong = soluong + '".
                    $sp["soluong"]."' where id='".$sp["sanpham"]."'");
                }else{
                    $sp2 = db::query("select * from sanpham where kho=".$old->kho." and ma='' and cha=".$sp["sanpham"],'sanpham');
                    
                    if($sp2){
                        $sp2 = $sp2[0];
                        $sp2->soluong += $sp["soluong"];
                        $sp2->update();
                    }else{ //chac k xay ra nhung van lam
                        $model2 = model::load(array(
                            'cha'=>$sp["sanpham"],
                            'soluong'=>+$sp["soluong"],                             
                            'kho'=>$old->kho,
                        ),'sanpham');
                        $model2->insert();
                    }
                }
                 
                if($tu=='doitacsanxuat'){
                    if($old->doitac == $model->kho){  
                        db::query("update sanpham set soluong = soluong - '".
                        $sp["soluong"]."' where id='".$sp["sanpham"]."'");
                    }else{
                        $sp2 = db::query("select * from sanpham where kho=".$old->doitac." and ma='' and cha=".$sp["sanpham"],'sanpham');
                        
                        if($sp2){
                            $sp2 = $sp2[0];
                            $sp2->soluong -= $sp["soluong"];
                            $sp2->update();
                        }else{ //chac k xay ra nhung van lam
                            $model2 = model::load(array(
                                'cha'=>$sp["sanpham"],
                                'soluong'=>-$sp["soluong"],                             
                                'kho'=>$old->doitac,
                            ),'sanpham');
                            $model2->insert();
                        }
                    }    
                }
                  
            }
        }
        
        //added 05/26/2020
        if($old->doitac>0){
            $clone = db::query("select * from hoadon where cha = $id and `type` in (1,3)");
            if($clone){
                $clone = $clone[0]->id;
                db::query("delete from hoadon_chitiet where hoadon = $clone");
                db::query("delete from hoadon where id = $clone or cha = $clone");
            }
        }
        
        //xoa trong hoadon_chitiet
        db::query("delete from hoadon_chitiet where hoadon = $id");
        
        //xoa trong hoadon
        //db::query("delete from hoadon where id = $id");
        db::query("delete from hoadon where id = $id or cha = $id");
        
        echo json_encode(array('message'=>___('Delete successfully')));
    } 
    
    public function deleteSelectedXH(){
        $ids = input::post('ids');
        if(!$ids){
            echo json_encode(array('error'=>___('Empty list invoice')));
            die();
        } 
        
        foreach($ids as $id){
            $old = hoadon::get($id);
            if(!$old || $old->type!=3){
                echo json_encode(array('error'=>___('You can\'t delete this invoice')));
                die();
            } 
            $old_products = hoadon_chitiet::list_all($id);
            //tra lai so luong cac san pham
            if($old->trangthai==1){
                
                if($old->nhanvien>0){
                    $tu = 'nhanvien';
                    //$doitac = nhanvien::get($hoadon->nhanvien);
                }else{
                    $doitac = doitac::get($old->doitac);
                    $tu = $doitac->vaitro == 3?'khachhang':$doitac->vaitro == 2?'doitacsanxuat':'nhacungcap';
                }
                
                foreach($old_products as $sp){ 
                    $sp = $sp->attributes();
                    //db::query("update sanpham set soluong = soluong - '".
                    //$sp["soluong"]."' where id='".$sp["sanpham"]."'");
                    
                    $model = sanpham::get($sp["sanpham"]);    
                    
                    if($model->kho == $old->kho){
                        db::query("update sanpham set soluong = soluong + '".
                        $sp["soluong"]."' where id='".$sp["sanpham"]."'");
                    }else{
                        $sp2 = db::query("select * from sanpham where kho=".$old->kho." and ma='' and cha=".$sp["sanpham"],'sanpham');
                        
                        if($sp2){
                            $sp2 = $sp2[0];
                            $sp2->soluong += $sp["soluong"];
                            $sp2->update();
                        }else{ //chac k xay ra nhung van lam
                            $model2 = model::load(array(
                                'cha'=>$sp["sanpham"],
                                'soluong'=>+$sp["soluong"],                             
                                'kho'=>$old->kho,
                            ),'sanpham');
                            $model2->insert();
                        }
                    }
                     
                    if($tu=='doitacsanxuat'){
                        if($old->doitac == $model->kho){
                            db::query("update sanpham set soluong = soluong - '".
                            $sp["soluong"]."' where id='".$sp["sanpham"]."'");
                        }else{
                            $sp2 = db::query("select * from sanpham where kho=".$old->doitac." and ma='' and cha=".$sp["sanpham"],'sanpham');
                            
                            if($sp2){
                                $sp2 = $sp2[0];
                                $sp2->soluong -= $sp["soluong"];
                                $sp2->update();
                            }else{ //chac k xay ra nhung van lam
                                $model2 = model::load(array(
                                    'cha'=>$sp["sanpham"],
                                    'soluong'=>-$sp["soluong"],                             
                                    'kho'=>$old->doitac,
                                ),'sanpham');
                                $model2->insert();
                            }
                        }    
                    }
                }
            }
            
            //added 05/26/2020
            if($old->doitac>0){
                $clone = db::query("select * from hoadon where cha = $id and `type` in (1,3)");
                if($clone){
                    $clone = $clone[0]->id;
                    db::query("delete from hoadon_chitiet where hoadon = $clone");
                    db::query("delete from hoadon where id = $clone or cha = $clone");
                }
            }
            
            //xoa trong hoadon_chitiet
            db::query("delete from hoadon_chitiet where hoadon = $id");
        }
        
        //xoa trong hoadon
        //db::query("delete from hoadon where id IN (".implode(",",$ids).")");
        db::query("delete from hoadon where id IN (".implode(",",$ids).") or cha IN (".implode(",",$ids).")");
        
        echo json_encode(array('message'=>___('Delete successfully')));
    }
    
    public function trangthaiXH(){
        $id = input::post('id');
        if(!$id){
            echo json_encode(array('error'=>___('Empty id of invoice')));
            die();
        } 
        $hoadon = hoadon::get($id);
        
        if(!$hoadon || $hoadon->type!=3){
            echo json_encode(array('error'=>___('You can\'t deactive/active this invoice')));
            die();
        } 
        
        $old_products = hoadon_chitiet::list_all($id);
        
        if($hoadon->trangthai==1){
            
            if($hoadon->nhanvien>0){
                $tu = 'nhanvien';
                //$doitac = nhanvien::get($hoadon->nhanvien);
            }else{
                $doitac = doitac::get($hoadon->doitac);
                $tu = $doitac->vaitro == 3?'khachhang':$doitac->vaitro == 2?'doitacsanxuat':'nhacungcap';
            }
            
            //tra lai so luong cac san pham
            foreach($old_products as $sp){ 
                $sp = $sp->attributes();
                //db::query("update sanpham set soluong = soluong + '".
                //$sp["soluong"]."' where id='".$sp["sanpham"]."'");
                
                $model = sanpham::get($sp["sanpham"]);    
                    
                if($model->kho == $hoadon->kho){
                    db::query("update sanpham set soluong = soluong + '".
                    $sp["soluong"]."' where id='".$sp["sanpham"]."'");
                }else{
                    $sp2 = db::query("select * from sanpham where kho=".$hoadon->kho." and ma='' and cha=".$sp["sanpham"],'sanpham');
                    
                    if($sp2){
                        $sp2 = $sp2[0];
                        $sp2->soluong += $sp["soluong"];
                        $sp2->update();
                    }else{ //chac k xay ra nhung van lam
                        $model2 = model::load(array(
                            'cha'=>$sp["sanpham"],
                            'soluong'=>+$sp["soluong"],                             
                            'kho'=>$hoadon->kho,
                        ),'sanpham');
                        $model2->insert();
                    }
                }
                 
                if($tu=='doitacsanxuat'){
                    if($hoadon->doitac == $model->kho){
                        db::query("update sanpham set soluong = soluong - '".
                        $sp["soluong"]."' where id='".$sp["sanpham"]."'");
                    }else{
                        $sp2 = db::query("select * from sanpham where kho=".$hoadon->doitac." and ma='' and cha=".$sp["sanpham"],'sanpham');
                        
                        if($sp2){
                            $sp2 = $sp2[0];
                            $sp2->soluong -= $sp["soluong"];
                            $sp2->update();
                        }else{ //chac k xay ra nhung van lam
                            $model2 = model::load(array(
                                'cha'=>$sp["sanpham"],
                                'soluong'=>-$sp["soluong"],                             
                                'kho'=>$hoadon->doitac,
                            ),'sanpham');
                            $model2->insert();
                        }
                    }    
                }
            }
        }else{
            //tang so luong
            foreach($old_products as $sp){ 
                $sp = $sp->attributes();
                //db::query("update sanpham set soluong = soluong + '".
                //$sp["soluong"]."' where id='".$sp["sanpham"]."'");
                
                $model = sanpham::get($sp["sanpham"]);    
                    
                if($model->kho == $hoadon->kho){
                    db::query("update sanpham set soluong = soluong + '".
                    $sp["soluong"]."' where id='".$sp["sanpham"]."'");
                }else{
                    $sp2 = db::query("select * from sanpham where kho=".$hoadon->kho." and ma='' and cha=".$sp["sanpham"],'sanpham');
                    
                    if($sp2){
                        $sp2 = $sp2[0];
                        $sp2->soluong += $sp["soluong"];
                        $sp2->update();
                    }else{ 
                        $model2 = model::load(array(
                            'cha'=>$sp["sanpham"],
                            'soluong'=>+$sp["soluong"],                             
                            'kho'=>$hoadon->kho,
                        ),'sanpham');
                        $model2->insert();
                    }
                }
                 
                if($tu=='doitacsanxuat'){
                    if($hoadon->doitac == $model->kho){
                        db::query("update sanpham set soluong = soluong - '".
                        $sp["soluong"]."' where id='".$sp["sanpham"]."'");
                    }else{
                        $sp2 = db::query("select * from sanpham where kho=".$hoadon->doitac." and ma='' and cha=".$sp["sanpham"],'sanpham');
                        
                        if($sp2){
                            $sp2 = $sp2[0];
                            $sp2->soluong -= $sp["soluong"];
                            $sp2->update();
                        }else{  
                            $model2 = model::load(array(
                                'cha'=>$sp["sanpham"],
                                'soluong'=>-$sp["soluong"],                             
                                'kho'=>$hoadon->doitac,
                            ),'sanpham');
                            $model2->insert();
                        }
                    }    
                }
            }
        }
        
        //added 05/26/2020
        if($hoadon->doitac>0){
            $clone = db::query("select * from hoadon where cha = $id and `type` in (1,3)");
            if($clone){
                $clone = $clone[0]->id;                
                db::query("update hoadon set trangthai = ".($hoadon->trangthai==1?2:1)." where id = $clone");
                db::query("update hoadon set trangthai = ".($hoadon->trangthai==1?0:1)." where cha = $clone and `type` in (2,4)");
            }
        }
        
        //update trang thai
        db::query("update hoadon set trangthai = ".($hoadon->trangthai==1?2:1)." where id = $id");
        //db::query("update hoadon set trangthai = ".($hoadon->trangthai==1?0:1)." where cha = $id");
        db::query("update hoadon set trangthai = ".($hoadon->trangthai==1?0:1)." where cha = $id and `type` in (2,4)"); //05/26/2020
         
        echo json_encode(array('message'=>___('Change status successfully')));
    }
    public function viewDA(){
        $id = input::post('id');
        if(!$id) die();
        
        $hoadon = duan::get($id);
        if(!$hoadon) die();
        
        $sanpham = sanpham::get($hoadon->sanpham);  
        
        //06/10/2020
        if(!($employee=session::get('login'))){
            die();
        }        
        $employee = json_decode($employee);         
        $login_type = (session::get('login_type'));        
        $kho = $login_type=='doitacsanxuat'?$employee->id:$employee->kho;            
        if($hoadon->cha>0 && $hoadon->khachhang==$kho)  {
            $khachhang = doitac::get($hoadon->kho);
        }else
               
        $khachhang = doitac::get($hoadon->khachhang);
          
        $hoadon->khachhang = $khachhang;
        $hoadon->sanpham = $sanpham;
         
        //render template
        view::render(
            'da_view',             
            array(  
                 'model'=>$hoadon, 
                  
                 //'employee'=>$employee,  
            )            
        );  
    }
    public function viewXH(){
        $id = input::post('id');
        if(!$id) die();
        
        $hoadon = hoadon::get($id);
        if(!$hoadon) die();
        
        if($hoadon->cha==0) //05/27/2020
        $sanpham = db::query("select sanpham.*,hoadon_chitiet.* from hoadon_chitiet 
            inner join sanpham on sanpham.id = hoadon_chitiet.sanpham 
            and hoadon_chitiet.hoadon=$id"); //var_dump($sanpham);die();
        else //05/27/2020
        $sanpham = db::query("select sanpham.*,hoadon_chitiet.* from hoadon_chitiet 
            inner join sanpham on sanpham.id = hoadon_chitiet.sanpham 
            and hoadon_chitiet.hoadon={$hoadon->cha}"); //05/27/2020
        
        if($hoadon->nhanvien>0){
            $tu = 'nhanvien';
            $doitac = nhanvien::get($hoadon->nhanvien);
        }else{
            $doitac = doitac::get($hoadon->doitac);
            $tu = $doitac->vaitro == 3?'khachhang':$doitac->vaitro == 2?'doitacsanxuat':'nhacungcap';
            
            //05/27/2020
            if($hoadon->cha>0){ 
                $tu = ($doitac->vaitro == 3||$doitac->vaitro == 2)?'khachhang':'nhacungcap';
            }
        }
        $roles = config::get('roles');
        $hoadon->tu = $tu;
        $hoadon->doitac = $doitac;
        $hoadon->tu2 = $roles[$tu];
        
        //05/29/2020: tim cac hoa don thanh toan lien quan
        $hoadon->thanhtoan = db::query("select * from hoadon where hoadon.cha = {$hoadon->id} and type = 4");
        
        //render template
        view::render(
            'xh_view',             
            array(  
                 'model'=>$hoadon, 
                 'sanpham'=>$sanpham,   
                 //'employee'=>$employee,  
            )            
        );     
    }
    
    public function themduan($kho){
        //var_dump($_POST);
        /*
array(8) {
  ["ma"]=>
  string(0) ""
  ["batdau"]=>
  string(10) "09/05/2020"
  ["ketthuc"]=>
  string(0) ""
  ["khachhang"]=>
  string(1) "4"
  ["ghichu"]=>
  string(0) ""
  ["searchProduct"]=>  sanpham
  string(1) "6"
  ["productPrice"]=>  gia
  string(5) "30000"
  ["productQuantity"]=> soluong
  string(4) "1000"
  
  cha
}        
        */
        
        
        $cha = input::post('cha'); //05/23/2020
        $khachhang = input::post('khachhang'); //05/23/2020
        if($cha>0) //05/23/2020        
        {
            $_POST['kho'] = $khachhang; //05/23/2020
            $_POST['khachhang'] = $kho; //05/26/2020
        }
        else //05/23/2020
        $_POST['kho'] = $kho;
        $_POST['sanpham'] = $_POST['searchProduct'];
        
        $_POST['gia'] = $_POST['productPrice'];
        $_POST['soluong'] = $_POST['productQuantity'];
        
        unset($_POST['searchProduct'],$_POST['productPrice'],$_POST['productQuantity']);
         
        $model = model::load($_POST,'duan');
        
        $model->batdau = date('Y-m-d \0\0:\0\0:\0\0',date::totime($model->batdau,"/^(\d{2})\/(\d{2})\/(\d{4})$/",array(2,1,3)));
        if($model->ketthuc!=''){
            $model->ketthuc = date('Y-m-d \0\0:\0\0:\0\0',date::totime($model->ketthuc,"/^(\d{2})\/(\d{2})\/(\d{4})$/",array(2,1,3)));
        }else $model->ketthuc = '0000-00-00 00:00:00';
        
        if($model->ma==''){
            $model->ma = common::autoCode(0,array(),'DA',6,'duan');
        }
        
        //var_dump($model);
        if($model->insert(true)){
            echo json_encode(array('message'=>___('Insert successfully')));
        }else{
            echo json_encode(array('error'=>___('Can\'t insert project')));
        }
    }
    
    public function filterDA(){
        $id = input::post('id');
        if(!$id) die();
        
        if(!($employee=session::get('login'))){
            die();
        }
        
        $employee = json_decode($employee); 
        common::vaitro($employee);
        
        $login_type = (session::get('login_type'));
         
        if(!($login_type=='admin' && $employee->kho>0 || $login_type=='doitacsanxuat'||
            ($login_type=='nhanvien' && !empty($employee->vaitro) && (in_array('tiendo',$employee->vaitro)||in_array('duan',$employee->vaitro))))){
            die();
        }
        
        $filter = input::post('filter');
        if($filter){
             
            if(!empty($filter['product'])){
                $product = $filter['product'];                     
            }
            if(!empty($filter['note'])){
                $note = $filter['note'];                     
            }
            if(!empty($filter['status'])){
                $status = $filter['status'];         
                $status = explode(",",$status);            
            }
            if(!empty($filter['range'])){
                $range = $filter['range'];                                    
            }
        }
         
        //render template
        view::render(
            'filterDA',             
            array(  
                 'id'=>$id, 
                  
                 'product'=>empty($product)?'':($product),
                 'note'=>empty($note)?'':($note),
                 'status'=>empty($status)?'':($status),
                 'range'=>empty($range)?'':($range),
            )            
        );  
    }
    public function suaduan(){
        $id = input::post('id');
        if(!$id){
            echo json_encode(array('error'=>___('Empty id of project')));
            die();
        } 
        $hoadon = model::load($_POST,'duan');
         
        $hoadon->ngay=date("Y-m-d H:i:s",time::totime($hoadon->ngay,"/^(\d+)\/(\d+)\/(\d+) (\d+)\:(\d+)\:(\d+)$/",array(4,5,6,2,1,3)));
        
        $hoadon->batdau=date("Y-m-d H:i:s",date::totime($hoadon->batdau,"/^(\d{2})\/(\d{2})\/(\d{4})$/",array(2,1,3)));
        if($hoadon->ketthuc!=''){
            $hoadon->ketthuc=date("Y-m-d H:i:s",date::totime($hoadon->ketthuc,"/^(\d{2})\/(\d{2})\/(\d{4})$/",array(2,1,3)));
        }else{
            $hoadon->ketthuc='0000-00-00 00:00:00';
        }
        
        $hoadon->update();
        
        echo json_encode(array('message'=>___('Update successfully')));
    }
    public function deleteDA(){
        $id = input::post('id');
        if(!$id){
            echo json_encode(array('error'=>___('Empty id of project')));
            die();
        } 
        $old = duan::get($id);
        if(!$old){
            echo json_encode(array('error'=>___('You can\'t delete this project')));
            die();
        } 
         
        //xoa trong duan
        db::query("delete from duan where id = $id");
        echo json_encode(array('message'=>___('Delete successfully')));
    } 
    
    public function deleteSelectedDA(){
        $ids = input::post('ids');
        if(!$ids){
            echo json_encode(array('error'=>___('Empty list project')));
            die();
        } 
        
        foreach($ids as $id){
            $old = duan::get($id);
            if(!$old){
                echo json_encode(array('error'=>___('You can\'t delete this project')));
                die();
            }               
        }
        
        //xoa trong duan
        db::query("delete from duan where id IN (".implode(",",$ids).")");
        echo json_encode(array('message'=>___('Delete successfully')));
    }
    
    public function trangthaiDA(){
        $id = input::post('id');
        if(!$id){
            echo json_encode(array('error'=>___('Empty id of project')));
            die();
        } 
        $hoadon = duan::get($id);
        
        if(!$hoadon){
            echo json_encode(array('error'=>___('You can\'t deactive/active this project')));
            die();
        } 
         
        //update trang thai
        db::query("update duan set trangthai = ".($hoadon->trangthai==1?0:1)." where id = $id");
         
        echo json_encode(array('message'=>___('Change status successfully')));
    }
    
    public function themchuyen($duan){
        //var_dump($_POST);
        /*
array(6) {
  ["kho"]=>
  string(1) "1"
  ["ten"]=>
  string(6) "Line 1"
  ["soluong"]=>
  string(3) "500"
  ["ghichu"]=>
  string(0) ""
  ["nhanvien"]=>
  array(4) {
    [0]=>
    array(19) {
      ["selected"]=>
      string(5) "false"
      ["disabled"]=>
      string(5) "false"
      ["text"]=>
      string(13) "Công nhân 1"
      ["id"]=>
      string(1) "1"
      ["ten"]=>
      string(13) "Công nhân 1"
      ["diachi"]=>
      string(2) "HT"
      ["sdt"]=>
      string(0) ""
      ["email"]=>
      string(19) "congnhan1@gmail.com"
      ["password"]=>
      string(32) "e10adc3949ba59abbe56e057f20f883e"
      ["vaitro"]=>
      string(1) "5"
      ["ghichu"]=>
      string(0) ""
      ["phongban"]=>
      string(1) "2"
      ["kho"]=>
      string(1) "1"
      ["ma"]=>
      string(0) ""
      ["bangluong"]=>
      string(1) "1"
      ["trangthai"]=>
      string(1) "1"
      ["_resultId"]=>
      string(30) "select2-searchNV-result-rood-1"
      ["congdoan"]=>
      string(1) "2"
      ["congdoanten"]=>
      string(19) "Công đoạn cắt"
    }
    [1]=>
    array(19) {
      ["selected"]=>
      string(5) "false"
      ["disabled"]=>
      string(5) "false"
      ["text"]=>
      string(13) "Công nhân 2"
      ["id"]=>
      string(1) "2"
      ["ten"]=>
      string(13) "Công nhân 2"
      ["diachi"]=>
      string(0) ""
      ["sdt"]=>
      string(0) ""
      ["email"]=>
      string(19) "congnhan2@gmail.com"
      ["password"]=>
      string(32) "e10adc3949ba59abbe56e057f20f883e"
      ["vaitro"]=>
      string(1) "5"
      ["ghichu"]=>
      string(0) ""
      ["phongban"]=>
      string(1) "2"
      ["kho"]=>
      string(1) "1"
      ["ma"]=>
      string(0) ""
      ["bangluong"]=>
      string(1) "1"
      ["trangthai"]=>
      string(1) "1"
      ["_resultId"]=>
      string(30) "select2-searchNV-result-8qj5-2"
      ["congdoan"]=>
      string(1) "3"
      ["congdoanten"]=>
      string(19) "Công đoạn may 1"
    }
    [2]=>
    array(19) {
      ["selected"]=>
      string(5) "false"
      ["disabled"]=>
      string(5) "false"
      ["text"]=>
      string(13) "Công nhân 3"
      ["id"]=>
      string(1) "3"
      ["ten"]=>
      string(13) "Công nhân 3"
      ["diachi"]=>
      string(0) ""
      ["sdt"]=>
      string(0) ""
      ["email"]=>
      string(19) "congnhan3@gmail.com"
      ["password"]=>
      string(32) "e10adc3949ba59abbe56e057f20f883e"
      ["vaitro"]=>
      string(1) "5"
      ["ghichu"]=>
      string(0) ""
      ["phongban"]=>
      string(1) "2"
      ["kho"]=>
      string(1) "1"
      ["ma"]=>
      string(0) ""
      ["bangluong"]=>
      string(1) "1"
      ["trangthai"]=>
      string(1) "1"
      ["_resultId"]=>
      string(30) "select2-searchNV-result-63rj-3"
      ["congdoan"]=>
      string(1) "4"
      ["congdoanten"]=>
      string(19) "Công đoạn may 2"
    }
    [3]=>
    array(19) {
      ["selected"]=>
      string(5) "false"
      ["disabled"]=>
      string(5) "false"
      ["text"]=>
      string(13) "Công nhân 3"
      ["id"]=>
      string(1) "3"
      ["ten"]=>
      string(13) "Công nhân 3"
      ["diachi"]=>
      string(0) ""
      ["sdt"]=>
      string(0) ""
      ["email"]=>
      string(19) "congnhan3@gmail.com"
      ["password"]=>
      string(32) "e10adc3949ba59abbe56e057f20f883e"
      ["vaitro"]=>
      string(1) "5"
      ["ghichu"]=>
      string(0) ""
      ["phongban"]=>
      string(1) "2"
      ["kho"]=>
      string(1) "1"
      ["ma"]=>
      string(0) ""
      ["bangluong"]=>
      string(1) "1"
      ["trangthai"]=>
      string(1) "1"
      ["_resultId"]=>
      string(30) "select2-searchNV-result-63rj-3"
      ["congdoan"]=>
      string(1) "5"
      ["congdoanten"]=>
      string(19) "Công đoạn may 3"
    }
  }
  ["duan"]=>
  string(1) "1"
}        
        */
        $nhanvien = input::post('nhanvien');
        
        unset($_POST['nhanvien']);
        
        $model = model::load($_POST,'chuyen');
        
        $model = $model->insert(true);
        
        if(!$model){
            echo json_encode(array('error'=>___('Can\'t insert')));
            die();
        }
        
        foreach($nhanvien as $nv){
            $model2 = model::load(array(
                'chuyen'=>$model->id,
                'nhanvien'=>$nv['id'],
                'kho'=>$model->kho,
                'congdoan'=>$nv['congdoan'],
            ),'chuyen_nhanvien');
            $model2->insert();
        }
        
        echo json_encode(array('message'=>___('Insert successfully')));
    }
    
    public function suachuyen(){
        $id = input::post('id');
        if(!$id){
            echo json_encode(array('error'=>___('Empty id of line')));
            die();
        } 
        $hoadon = model::load($_POST,'chuyen');
          
        $hoadon->update();
        
        echo json_encode(array('message'=>___('Update successfully')));
    }
    public function deleteDAC(){
        $id = input::post('id');
        if(!$id){
            echo json_encode(array('error'=>___('Empty id of line')));
            die();
        } 
        $old = chuyen::get($id);
        if(!$old){
            echo json_encode(array('error'=>___('You can\'t delete this line')));
            die();
        } 
         
        //xoa trong chuyen
        db::query("delete from chuyen where id = $id");
        
        //xoa trong chuyen_nhanvien
        db::query("delete from chuyen_nhanvien where chuyen = $id");
        
        echo json_encode(array('message'=>___('Delete successfully')));
    } 
    
    public function deleteSelectedDAC(){
        $ids = input::post('ids');
        if(!$ids){
            echo json_encode(array('error'=>___('Empty list line')));
            die();
        } 
        
        foreach($ids as $id){
            $old = chuyen::get($id);
            if(!$old){
                echo json_encode(array('error'=>___('You can\'t delete this line')));
                die();
            }               
        }
        
        //xoa trong chuyen
        db::query("delete from chuyen where id IN (".implode(",",$ids).")");
        
        //xoa trong chuyen_nhanvien
        db::query("delete from chuyen_nhanvien where chuyen = IN (".implode(",",$ids).")");
        
        echo json_encode(array('message'=>___('Delete successfully')));
    }
    
    public function viewDAC(){
        $id = input::post('id');
        if(!$id) die();
        
        $hoadon = chuyen::get($id);
        if(!$hoadon) die();
        
        $nhanvien = db::query("select chuyen_nhanvien.*, nhanvien.ten as nhanvienten, nhanvien.email as nhanvienemail,congdoan.ten as congdoanten
            from chuyen_nhanvien 
            inner join nhanvien on nhanvien.id = chuyen_nhanvien.nhanvien
            inner join congdoan on congdoan.id = chuyen_nhanvien.congdoan
            where chuyen_nhanvien.chuyen = $id");
         
        $hoadon->nhanvien = $nhanvien;
         
        //render template
        view::render(
            'dac_view',             
            array(  
                 'model'=>$hoadon, 
                  
                 //'employee'=>$employee,  
            )            
        );  
    }
    
    public function xongbancat($duanid){
        //kiem tra xem duan_cuon da co cuonvai = ? chua
        $cuonvai = input::post('cuonvai');
        $soluong = input::post('soluong');
        
        $duan_cuon = new duan_cuon;
        $duan_cuon = $duan_cuon->find_by_duan_and_cuonvai($duanid,$cuonvai);
        
        $nhanvien = input::post('nhanvien');
        
        //neu chua co insert
        if(!$duan_cuon){
            $duan_cuon = model::load(array(
                'cuonvai'=>$cuonvai,
                'kho'=>input::post('kho'),
                'duan'=>input::post('duan'),
                'solopcat'=>input::post('solopcat'),
                'trangthai'=>0,
                'nhanvien'=>$nhanvien,
                                
                'soluongcuon'=>input::post('soluongcuon'), //06/09/2020
            ),'duan_cuon');
            $duan_cuon->insert();
        } //
        else{
            if($duan_cuon->solopcat != input::post('solopcat') || $duan_cuon->soluongcuon != input::post('soluongcuon')){ //06/09/2020 add more
                //update solopcat
                $duan_cuon->table = 'duan_cuon';
                $duan_cuon->solopcat = input::post('solopcat');
                
                $duan_cuon->soluongcuon = input::post('soluongcuon'); //06/09/2020
                
                $duan_cuon->update();
            }
        }
        
        //insert into duan_cat
        $duan_cat = model::load(array(
         
            //'ngay'=>date('Y-m-d H:i:s'),
             
            'cuonvai'=>$cuonvai,
            'kho'=>input::post('kho'),
            'duan'=>input::post('duan'),             
            'trangthai'=>input::post('trangthai'),
            
            'ban'=>input::post('ban'),
            
            'soluong'=>$soluong,
            
        ),'duan_cat');
        $duan_cat = $duan_cat->insert(true);
        
        $nhomban = input::post('nhomban');
        $nhomban = sanpham::get($nhomban);
        
        unset($nhomban->id,$nhomban->ghichu);
        
        $nhom = $nhomban->nhom;
        //$nhom = nhomdinhmuc::get($nhom);
        
        $dinhmuc = $nhom ? dinhmuc::list_all($nhom) : false;
        
        if($dinhmuc){
            $dinhmuc = $dinhmuc[0];
        }
        
        //xac dinh ten bat dau va ma bat dau cua bancat
        $n = db::query("SELECT max(substring(ma,CHAR_LENGTH('".$nhomban->ma.
            "')+1)) as c FROM `sanpham` WHERE ma regexp '^".$nhomban->ma.
            "[0-9]{5}$'");
        if($n){
            $n = $n[0]; // var_dump($n);   
            $somabatdau = $n->c-0+1;
        }else{
            $somabatdau = 1;
        }
        
        $n = db::query("SELECT max(substring(ten,CHAR_LENGTH('".$nhomban->ten.             
            "')+2)) as c FROM `sanpham` WHERE ten regexp '^".$nhomban->ten.             
            " [0-9]{5}$'");
        if($n){
            $n = $n[0]; // var_dump($n);  
            $sotenbatdau = $n->c-0+1;
        }else{
            $sotenbatdau = 1;
        } 
        
        $nhomban->ten .= ' '.sprintf('%05d',$sotenbatdau);  
        $nhomban->ma .= ''.sprintf('%05d',$somabatdau);
        
        //tao sanpham ban cat 
        $bancat = model::extend($nhomban, array(
            //ten cha danhmuc soluong 1 donvi ma gia kho  nhom
            //ten cha   soluong 1   ma     
            // cha   soluong 1  
            'cha'=>$cuonvai,
            'soluong'=>1,
            'ngay'=>date('Y-m-d H:i:s'),   
            'nhom'=>($dinhmuc)?$nhomban->nhom:0,   //($dinhmuc && $dinhmuc->soluong==$soluong)?$nhomban->nhom:0,  :change 05/25/2020 
            'kho'=>input::post('kho'),
        ),'sanpham');        
        $bancat = $bancat->insert(true);
        
        $duan_cat->sanpham = $bancat->id;
        //@file_put_contents('log.txt','duan_cat: '.$duan_cat->table."\r\n",FILE_APPEND);
        $duan_cat->table = 'duan_cat';
        $duan_cat->update();
        
        //dinhmuc: sanpham cha soluong kho
        //if(!$dinhmuc){ //neu k co thi moi them fixed 05/16/2020  :change 05/25/2020
        /*if(!$dinhmuc || $dinhmuc->soluong!=$soluong){     
            $dinhmuc = model::load(array(
                'sanpham'=>$bancat->id,
                'cha'=>$cuonvai, 
                'soluong'=>$soluong, 
                'kho'=>$nhomban->kho
            ),'dinhmuc');
            $dinhmuc->insert();             
        }*/  //cai nay loi, them nua neu k co dinhmuc thi k biet dc sanpham con -> bat buoc fai co dinhmuc : 05/27/2020        
        if($dinhmuc && $dinhmuc->soluong!=$soluong){     
            $dinhmuc = model::load(array(
                'sanpham'=>$dinhmuc->sanpham,
                'cha'=>$bancat->id, 
                'soluong'=>$soluong, 
                'kho'=>$nhomban->kho
            ),'dinhmuc');
            $dinhmuc->insert();             
        }
              
        //update cuonvai cua duan: k can nua
        
        //them sanluong ngay: bo sung 16/05
        /*
        $nhanviec = nhanviec::list_all($nhanvien);
        if($nhanviec){
            $nhanviec = $nhanviec[0];    
            
            $congdoan = congdoan::get($nhanviec->congdoan);
            
            //ngay: doituong,soluong,type:3,congdoan,gia,kho,chuyen,duan,ngay
            $hoadon = model::load(array(                
                //'ghichu'=>'Tự động tạo khi xong bàn cắt',                 
                'duan'=>input::post('duan'),
                //'chuyen'=>$chuyen,
                
                'doituong'=>$nhanvien,
                'soluong'=>$soluong,
                'type'=>3,
                'congdoan'=>$congdoan->id,
                'gia'=>$congdoan->dongia,
                                 
                'kho'=>input::post('kho'),                 
                'ngay'=>date('Y-m-d'),
            ),'ngay');
            $hoadon->insert();      
        }    */   
        
        //thay doi cach tinh cong: 06/04/2020 
        $congdoan = input::post('congdoan');
        $congdoan = congdoan::get($congdoan);        
        $hoadon = model::load(array(                                    
            'duan'=>input::post('duan'),                        
            'doituong'=>$nhanvien,
            'soluong'=>$soluong,
            'type'=>3,
            'congdoan'=>$congdoan->id,
            'gia'=>$congdoan->dongia,                             
            'kho'=>input::post('kho'),                 
            'ngay'=>date('Y-m-d'),
        ),'ngay');
        $hoadon->insert(); 
            
        echo json_encode(array('message'=>___('Insert successfully')));
    }
    
    public function updateDuan($duan){
        
        if(!$duan){
            echo json_encode(array('error'=>___('Empty id of project')));
            die();
        } 
        
        $hoadon = duan::get($duan);
        
        if(!$hoadon){
            echo json_encode(array('error'=>___('Can\'t find project')));
            die();
        } 
        
        //var_dump($_POST);
        $model = model::load($_POST,'duan');
        $model->id = $duan;
        $model->update();
        
        echo json_encode(array('message'=>___('Update project successfully')));
    }
    
    public function hoantatcat2(){
        $cuonvai = input::post('cuonvai');
        
        $hoadon = sanpham::get($cuonvai);
                
        //render template
        view::render(
            'hoantatcat2',             
            array(  
                 'model'=>$hoadon, 
                  
                 //'employee'=>$employee,  
            )            
        );  
    }
    
    public function hoantatcat(){
        //kho: aid,cuonvai: cuonvai,duanid: duanid,nhanvien: nhanvien
        //var_dump($_POST);
        $duanid = input::post('duanid');
        $kho = input::post('kho');
        $cuonvai = input::post('cuonvai');
        $nhanvien = input::post('nhanvien');
        
        //06/09/2020        
        $soluongcuon = input::post('soluongcuon');
        
        if(!$cuonvai){
            echo json_encode(array('error'=>___('Can\'t find roll of cloth')));
            die();
        }
        
        //update duan_cuon set trangthai=1 where duan=duanid and cuonvai = cuonvai
        db::query("update duan_cuon set trangthai=1 where duan=$duanid and cuonvai = $cuonvai");
        
        //06/09/2020
        //tim so luong cuonvai hien tai, neu = thi k tao them, neu != thi tao them sanpham cuonvai moi voi cha la cuon vai cu
        $checksoluong = db::query("select * from sanpham where kho=$kho and (id=$cuonvai or (cha=$cuonvai and ma=''))");
        $checksoluong = $checksoluong[0];
        if($checksoluong->soluong != $soluongcuon){
            //tao sp moi
            $cuonvaiModel = sanpham::get($cuonvai);
            $cuonvaiModel->kho = $kho;
            $cuonvaiModel->soluong = $checksoluong->soluong - $soluongcuon;
            unset($cuonvaiModel->id);
            
            //xac dinh ten bat dau va ma bat dau cua bancat
            $n = db::query("SELECT max(substring(ma,CHAR_LENGTH('".$cuonvaiModel->ma.
                "')+1)) as c FROM `sanpham` WHERE ma regexp '^".$cuonvaiModel->ma.
                "[0-9]{5}$'");
            if($n){
                $n = $n[0]; // var_dump($n);   
                $somabatdau = $n->c-0+1;
            }else{
                $somabatdau = 1;
            }
            
            $n = db::query("SELECT max(substring(ten,CHAR_LENGTH('".$cuonvaiModel->ten.             
                "')+2)) as c FROM `sanpham` WHERE ten regexp '^".$cuonvaiModel->ten.             
                " [0-9]{5}$'");
            if($n){
                $n = $n[0]; // var_dump($n);  
                $sotenbatdau = $n->c-0+1;
            }else{
                $sotenbatdau = 1;
            }
            $cuonvaiModel->ten .= ' '.sprintf('%05d',$sotenbatdau);  
            $cuonvaiModel->ma .= ''.sprintf('%05d',$somabatdau);
            $cuonvaiModel->cha = $cuonvai;
            $cuonvaiModel = $cuonvaiModel->insert(true);               
        }else $cuonvaiModel = 0;
        
        //cho so luong ve 0: update sanpham set soluong=0 where kho=kho...
        //tim xem co san pham chinh k da
        //select * from sanpham where kho=$kho and id=$cuonvai
        //neu k ton tai
        //select * from sanpham where cha=$cuonvai and kho=$kho and ma=''
        //update ve 0
        db::query("update sanpham set soluong=0 where kho=$kho and (id=$cuonvai or (cha=$cuonvai and ma=''))");
        
        //tao phieu san xuat hoadon
        /* 
ma autoCode
ghichu 'phieu san xuat tu dong tao khi cat cuon vai
nguoitao
tong
duan
nhanvien
type 5
//kho
trangthai 1
//gia          
        */
        
        $nguoitao = session::get('login_id');
        $login_type = session::get('login_type');        
        $nguoitao = $login_type[0].$nguoitao;
        
        $hoadon = model::load(array(
            'ma'=>common::autoCode(0,array(),'SX'),
            'ghichu'=>'Tự động tạo khi cắt cuộn vải',
            'nguoitao'=>$nguoitao,//session::get('login_id'),
            //'tong'=>
            'duan'=>$duanid,
            'nhanvien'=>$nhanvien,
            'type'=>5,
            'kho'=>$kho,
            'trangthai'=>1,
            //gia  
            'ngay'=>time(),
        ),'hoadon');
        $hoadon = $hoadon->insert(true);
        //hoadon_chitiet
        /*
hoadon
sanpham cuonvai
soluong 1
//gia         
        */
        $hoadon_chitiet = model::load(array(
            'hoadon'=>$hoadon->id,
            'sanpham'=>$cuonvai,
            'soluong'=>$soluongcuon, //1 : changed 06/09/2020
        ),'hoadon_chitiet');
        $hoadon_chitiet->insert();
        
        //select sanpham from duan_cat where cuonvai = $cuonvai : tao roi go bo nhung duoc mo lai vao ngay 16/05
        $duan_cat = new duan_cat;
        $duan_cat = $duan_cat->find_all_by_cuonvai($cuonvai);
        if($duan_cat){
            foreach($duan_cat as $sp){
                $sp = $sp->sanpham;
                $hoadon_chitiet = model::load(array(
                    'hoadon'=>$hoadon->id,
                    'sanpham'=>-$sp,
                    'soluong'=>1,
                ),'hoadon_chitiet');
                $hoadon_chitiet->insert();
            }
        }
        
        //06/09/2020
        if($cuonvaiModel){
            $hoadon_chitiet = model::load(array(
                'hoadon'=>$hoadon->id,
                'sanpham'=>-$cuonvaiModel->id,
                'soluong'=>$cuonvaiModel->soluong, 
            ),'hoadon_chitiet');
            $hoadon_chitiet->insert();
        }
        
        echo json_encode(array('message'=>___('Done')));
    }
    
    public function dohoantatcat(){
        //kho: aid,cuonvai: cuonvai,duanid: duanid,nhanvien: nhanvien,ghichu: sodu
        $duanid = input::post('duanid');
        $kho = input::post('kho');
        $cuonvai = input::post('cuonvai');
        $nhanvien = input::post('nhanvien');
        $ghichu = input::post('ghichu');
        
        $cuonvai = sanpham::get($cuonvai);
        if(!$cuonvai){
            echo json_encode(array('error'=>___('Can\'t find product')));
            die();
        }
        //xac dinh ten bat dau va ma bat dau cua bancat
        $n = db::query("SELECT max(substring(ma,CHAR_LENGTH('".$cuonvai->ma.
            "')+1)) as c FROM `sanpham` WHERE ma regexp '^".$cuonvai->ma.
            "[0-9]{5}$'");
        if($n){
            $n = $n[0]; // var_dump($n);   
            $somabatdau = $n->c-0+1;
        }else{
            $somabatdau = 1;
        }
        
        $n = db::query("SELECT max(substring(ten,CHAR_LENGTH('".$cuonvai->ten.             
            "')+2)) as c FROM `sanpham` WHERE ten regexp '^".$cuonvai->ten.             
            " [0-9]{5}$'");
        if($n){
            $n = $n[0]; // var_dump($n);  
            $sotenbatdau = $n->c-0+1;
        }else{
            $sotenbatdau = 1;
        }
        
        $cuonvai2 = clone $cuonvai; //06/09/2020 add clone 
        
        $cuonvai2->ten .= ' '.sprintf('%05d',$sotenbatdau);  
        $cuonvai2->ma .= ''.sprintf('%05d',$somabatdau);
        
        unset($cuonvai2->id);
        
        //tao sanpham ban cat 
        $cuonvai2 = model::extend($cuonvai2, array(
            'ghichu'=>$ghichu,
            'cha'=>$cuonvai->id,
            'soluong'=>1,
            //'ngay'=>date('Y-m-d H:i:s'),   
            'kho'=>$kho, 
        ),'sanpham');        
        $cuonvai2 = $cuonvai2->insert(true);
        //$cuonvai2->insert();
        
        if($cuonvai2){
            //them cuon vai nay vao phieu sx
            $hoadon_chitiet = new hoadon_chitiet;
            $hoadon_chitiet = $hoadon_chitiet->find_by_cuonvai($cuonvai->id);
            if($hoadon_chitiet){
                $hoadon_chitiet = $hoadon_chitiet->hoadon;
                
                $hoadon_chitiet = model::load(array(
                    'hoadon'=>$hoadon_chitiet,
                    'sanpham'=>-$cuonvai2->id,
                    'soluong'=>1,
                ),'hoadon_chitiet');
                $hoadon_chitiet->insert();
            }
            
            echo json_encode(array('message'=>___('Done')));
        }else echo json_encode(array('error'=>___('Can\'t insert product')));
    }
    public function searchduan($kho=0){
        $q = $_REQUEST['q'];
        
        $more = '';
        $trangthai = empty($_REQUEST['trangthai'])?'':$_REQUEST['trangthai'];
        if($trangthai!=''){
            $more = 'and trangthai in ('.$trangthai.')';
        }
        
        $data = db::query("select * from duan where (ten like '%$q%' or ma like '%$q%') 
            and kho=$kho $more limit 10",'nhanvien');
        if(!$data) $data = array();
        
        echo json_encode(array_map(function($a){$b = $a->attributes();$b['text']=$b['ten'];return $b;},$data));
    }
    
    public function searchchuyen($duan){
        $data = db::query("select * from chuyen where duan=$duan",'chuyen');
        if(!$data) $data = array();
        else{
            foreach($data as &$chuyen){
                $sum = db::query("select sum(soluong) as tong 
                    from ngay where chuyen = ".$chuyen->id.' and duan = '.$duan.' group by chuyen');
                if($sum){
                    $sum = $sum[0]->tong;
                }else $sum = 0;
                $chuyen->tong = $sum;
            }
        }
        
        echo json_encode(array_map(function($a){$b = $a->attributes();$b['text']=$b['ten'];return $b;},$data));
    }
    public function searchchuyennhanvien($chuyen){
        $data = db::query("select chuyen_nhanvien.*,nhanvien.*,
            congdoan.ten as congdoanten,congdoan.dongia as congdoangia
            from chuyen_nhanvien 
            inner join nhanvien on nhanvien.id = chuyen_nhanvien.nhanvien 
            inner join congdoan on congdoan.id = chuyen_nhanvien.congdoan 
            where chuyen_nhanvien.chuyen=$chuyen",'chuyen_nhanvien');
        if(!$data) $data = array();
        
        echo json_encode(array_map(function($a){$b = $a->attributes();$b['text']=$b['ten'];return $b;},$data));
    }
    
    public function themtiendo($kho){
        /*
        duan: duan,
		chuyen: chuyen,
		hoanthanh: hoanthanh,
		ngay: ngay,
		
		sanluong: nhanvien {nhanvien, sanluong}        phai bo sung them congdoan congdoanten congdoangia
        */
        $duan = input::post('duan');
        $chuyen = input::post('chuyen');
        $hoanthanh = input::post('hoanthanh');
        $ngay = input::post('ngay');
        $sanluong = input::post('sanluong');
        
        $duan = duan::get($duan);
        if(!$duan){
            echo json_encode(array('error'=>___('Can\'t find project')));
            die();
        }
        $sanpham = $duan->sanpham;
        $sanpham = sanpham::get($sanpham);
        if(!$sanpham){
            echo json_encode(array('error'=>___('Can\'t find product')));
            die();
        }
        $nhomdinhmuc = $sanpham->nhom;
        if(!$nhomdinhmuc){
            echo json_encode(array('error'=>___('Can\'t find group of norm of product')));
            die();
        }
        $dinhmuc = dinhmuc::list_all($nhomdinhmuc);
        
        $nguoitao = session::get('login_id');
        $login_type = session::get('login_type');        
        $nguoitao = $login_type[0].$nguoitao;
        
        //tao phieu san xuat
        $hoadon = model::load(array(
            'ma'=>common::autoCode(0,array(),'SL'), //SX
            'ghichu'=>'Tự động tạo khi thêm sản lượng chuyền',
            'nguoitao'=>$nguoitao, 
            //'tong'=>
            'duan'=>$duan->id,
            'nhanvien'=>$chuyen,
            'type'=>5,
            'kho'=>$kho,
            'trangthai'=>1,
            //gia  
            'ngay'=>date::totime($ngay,"/^(\d{2})\/(\d{2})\/(\d{4})$/",array(2,1,3)),
        ),'hoadon');
        $hoadon = $hoadon->insert(true);
        //hoadon_chitiet
         
        $hoadon_chitiet = model::load(array(
            'hoadon'=>$hoadon->id,
            'sanpham'=>-$sanpham->id,
            'soluong'=>$hoanthanh,
        ),'hoadon_chitiet');
        $hoadon_chitiet->insert();
        
        //tang so luong cua san pham nay
        if($sanpham->kho == $kho){
            db::query("update sanpham set soluong = soluong + '".
            $hoanthanh."' where id='".($sanpham->id)."'");
        }else{
            $sp2 = db::query("select * from sanpham where kho=".$kho." and ma='' and cha=".($sanpham->id),'sanpham');
            
            if($sp2){
                $sp2 = $sp2[0];
                $sp2->soluong += $hoanthanh;
                $sp2->update();
            }else{  
                $model2 = model::load(array(
                    'cha'=>$sanpham->id,
                    'soluong'=>+$hoanthanh,                             
                    'kho'=>$kho,
                ),'sanpham');
                $model2->insert();
            }
        }
        
        foreach($dinhmuc as $dm){
            $hoadon_chitiet = model::load(array(
                'hoadon'=>$hoadon->id,
                'sanpham'=>$dm->sanpham,
                'soluong'=>$dm->soluong*$hoanthanh,
            ),'hoadon_chitiet');
            $hoadon_chitiet->insert();  
            
            //giam so luong cua san pham nay  
            $sp = sanpham::get($dm->sanpham);
            if($sp->kho == $kho){
                db::query("update sanpham set soluong = soluong - '".
                $hoanthanh."' where id='".($sp->id)."'");
            }else{
                $sp2 = db::query("select * from sanpham where kho=".$kho." and ma='' and cha=".($sp->id),'sanpham');
                
                if($sp2){
                    $sp2 = $sp2[0];
                    $sp2->soluong -= $hoanthanh;
                    $sp2->update();
                }else{  
                    $model2 = model::load(array(
                        'cha'=>$sp->id,
                        'soluong'=>-$hoanthanh,                             
                        'kho'=>$kho,
                    ),'sanpham');
                    $model2->insert();
                }
            }
        }
        
        foreach($sanluong as $sl){ 
            @file_put_contents('log.txt',var_export($sl,true),FILE_APPEND);
            $sl = (array) $sl;
             
            //them sanluong ngay
            //ngay: doituong,soluong,type:3,congdoan,gia,kho,chuyen,duan,ngay
            $hoadon = model::load(array(                
                //'ghichu'=>'Tự động tạo khi thêm sản lượng chuyền',                 
                'duan'=>$duan->id,
                'chuyen'=>$chuyen,
                
                'doituong'=>$sl['nhanvien'],
                'soluong'=>$sl['sanluong'],
                'type'=>3,
                'congdoan'=>$sl['congdoan'],
                'gia'=>$sl['congdoangia'],
                                 
                'kho'=>$kho,                 
                'ngay'=>date('Y-m-d',date::totime($ngay,"/^(\d{2})\/(\d{2})\/(\d{4})$/",array(2,1,3))),
            ),'ngay');
            $hoadon->insert();
        }
        
        echo json_encode(array('message'=>___('Done')));
    }
    public function giaoviecNV($nhanvien){        
        $nhanviec = nhanviec::list_all($nhanvien);
        if($nhanviec){
            $nhanviec = $nhanviec[0];            
        }
        $nhanvien = nhanvien::get($nhanvien);
        $congdoan = congdoan::list_all($nhanvien->kho);
        foreach($congdoan as &$cd){
            if($nhanviec && $cd->id==$nhanviec->congdoan){
                $cd->nhanviec = 1;
            }
        }
        
        view::render(
            'giaoviec',             
            array(  
                'congdoan'=>$congdoan,  
                'nhanvien'=>$nhanvien, 
            )            
        );
    }
    public function chongiaoviec(){    
        
        $nhanvien = input::post('nhanvien');
        $congdoan = input::post('congdoan');
        
        $nhanvien = nhanvien::get($nhanvien);
        $kho = $nhanvien->kho;
        
        db::query("delete from nhanviec where kho = $kho and nhanvien=$nhanvien");
        
        $model = model::load(array(
            'nhanvien'=>$nhanvien->id,
            'congdoan'=>$congdoan,
            'kho'=>$kho,
        ),'nhanviec');
        $model->insert();
        
        echo json_encode(array('message'=>___('Done')));
    }
    public function filterSX(){
        $id = input::post('id');
        if(!$id) die();
        
        if(!($employee=session::get('login'))){
            die();
        }
        
        $employee = json_decode($employee); 
        
        $login_type = (session::get('login_type'));
         
        if(!($login_type=='admin' && $employee->kho>0 || $login_type=='doitacsanxuat')){
            die();
        }
        
        $filter = input::post('filter');
        if($filter){
             
            if(!empty($filter['product'])){
                $product = $filter['product'];                     
            }
            if(!empty($filter['note'])){
                $note = $filter['note'];                     
            }
            if(!empty($filter['status'])){
                $status = $filter['status'];         
                $status = explode(",",$status);            
            }
            if(!empty($filter['range'])){
                $range = $filter['range'];                                    
            }
        }
         
        //render template
        view::render(
            'filterSX',             
            array(  
                 'id'=>$id, 
                  
                 'product'=>empty($product)?'':($product),
                 'note'=>empty($note)?'':($note),
                 'status'=>empty($status)?'':($status),
                 'range'=>empty($range)?'':($range),
            )            
        );  
    }
    public function viewSX(){
        $id = input::post('id');
        if(!$id) die();
        
        $hoadon = hoadon::get($id);
        if(!$hoadon) die();
        
        $sanpham = db::query("select sanpham.*,hoadon_chitiet.* from hoadon_chitiet 
            inner join sanpham on sanpham.id = abs( hoadon_chitiet.sanpham )
            and hoadon_chitiet.hoadon=$id"); //var_dump($sanpham);die();
        
        $tu = 'nhanvien';
        if(substr($hoadon->ma,0,2)=='SL') $tu = 'chuyen';
        
        if($tu == 'nhanvien'){            
            $doitac = nhanvien::get($hoadon->nhanvien);
        }else{
            $doitac = chuyen::get($hoadon->nhanvien);
        }
        $roles = config::get('roles');
        $hoadon->tu = $tu;
        $hoadon->doitac = $doitac;
        $hoadon->tu2 = $roles[$tu];
        
        $hoadon->duan = duan::get($hoadon->duan);
        
        //tach ra san pham nguyen lieu va thanh pham
        $sanpham2 = array_filter($sanpham,function($a){return $a->sanpham>0;});
        $sanpham = array_filter($sanpham,function($a){return $a->sanpham<0;});
        //render template
        view::render(
            'sx_view',             
            array(  
                 'model'=>$hoadon, 
                 'sanpham'=>$sanpham,   
                 //'employee'=>$employee,  
                 'sanpham2'=>$sanpham2,   
            )            
        );     
    }
    
    public function traluong($nhanvien){
        /*
thuong : t('#thuong').val(),
luong: t('#luong').html(),
thang: q[0],
nam: q[1],
id: d        
        */
        $thuong = input::post('thuong');
        $luong2 = input::post('luong');
        $thang = input::post('thang');
        $nam = input::post('nam');
        $id = input::post('id');
        
        $luong = luong::get($id);
        if(!$luong || $luong->luong!=$luong2 || $luong->thang!=$thang || $luong->nam!=$nam || $luong->thuong!=$thuong || $luong->tra){
            echo json_encode(array('error'=>___('You are a hacker?')));
            die();            
        }
        $luong->tra = 1;
        $luong->update();
        
        $nguoitao = session::get('login_id');
        $login_type = session::get('login_type');        
        $nguoitao = $login_type[0].$nguoitao;
        
        $nhanvien = nhanvien::get($nhanvien);
        
        $hoadon = model::load(array(
            'ma'=>common::autoCode(0,array(),'TL'),
            'ghichu'=>'Trả lương '.$thang.'/'.$nam.' cho nhân viên '.$nhanvien->ten,
            'nguoitao'=>$nguoitao, 
            'tong'=>$luong2+$thuong,         
            'nhanvien'=>$nhanvien->id,
            'type'=>2,
            'kho'=>$luong->kho,
            'trangthai'=>1,
            'gia'=>$luong2+$thuong,  
            'ngay'=>time(),
        ),'hoadon');
        $hoadon = $hoadon->insert(true);
         
        echo json_encode(array(
            'message'=>___('Done'),
            'ma'=>$hoadon->ma,
        ));
    }
    
    public function sualuong($nhanvien){
        /*
url: '/ajax/sualuong/'+nv,
data: {thuong : t('#thuong').val(),luong: t('#luong').html()},       thang nam   
        */
        $thuong = input::post('thuong');
        $luong = input::post('luong');
        $thang = input::post('thang');
        $nam = input::post('nam');
        
        $nhanvien = nhanvien::get($nhanvien);
        
        //check luong hien tai
        $luong = db::query("select * from luong where nhanvien = ".$nhanvien->id." and nam =".$nam." and thang=".$thang,'luong');
        if($luong){
            $luong = $luong[0];    
            $luong->thuong = $thuong;
            $luong->luong = $luong;
            $luong->update();
             
        } else {
            $luong = model::load($_POST,'luong');
            $luong->kho = $nhanvien->kho;
            $luong->nhanvien = $nhanvien->id;
            $luong = $luong->insert(true);            
        }
        echo json_encode(array(
            'message'=>___('Done'),
            'id'=>$luong->id,
        ));
    }
    
    public function chamcong($nhanvien){
        /*
url: '/ajax/chamcong/'+nv,
data: {date: r2, soluong: r-0.5},       
        */
        $date = input::post('date');
        $soluong = input::post('soluong');
        
        $cong = db::query("select * from ngay where doituong='$nhanvien' and type=1 and ngay='$date'");
        if($cong)        
            db::query("update ngay set soluong = '$soluong' where doituong='$nhanvien' and type=1 and ngay='$date'");
        else{
            $nhanvien = nhanvien::get($nhanvien);
            $bangluong = $nhanvien->bangluong;
            $luong = 0;
            if($bangluong){
                $bangluong = bangluong::get($bangluong);
                /*
luongngay
luongthang
ngaythang                
                */
                if($bangluong->luongngay){
                    $luong = $bangluong->luongngay;
                }else{
                    $luong = $bangluong->luongthang/$bangluong->ngaythang;
                }
            }
            $model = model::load(array(
                'soluong' => $soluong,
                'doituong'=>$nhanvien->id,
                'type'=>1 ,
                'ngay'=>$date,
                'kho'=>$nhanvien->kho,
                'gia'=>$luong,
            ),'ngay');
            $model->insert();
        }
        echo json_encode(array('message'=>___('Done')));
    }
    
    public function filterTC(){
        $id = input::post('id');
        if(!$id) die();
        
        if(!($employee=session::get('login'))){
            die();
        }
        
        $employee = json_decode($employee); 
        
        $login_type = (session::get('login_type'));
         
        if(!($login_type=='admin' && $employee->kho>0 || $login_type=='doitacsanxuat')){
            die();
        }
        
        $filter = input::post('filter');
        if($filter){
             
            if(!empty($filter['project'])){
                $product = $filter['project'];     
                $product = duan::get($product);                          
            }
            if(!empty($filter['note'])){
                $note = $filter['note'];                     
            }
            if(!empty($filter['status'])){
                $status = $filter['status'];         
                $status = explode(",",$status);            
            }
            if(!empty($filter['range'])){
                $range = $filter['range'];                                    
            }
        }
         
        //render template
        view::render(
            'filterTC',             
            array(  
                 'id'=>$id, 
                  
                 'product'=>empty($product)?'':($product),
                 'note'=>empty($note)?'':($note),
                 'status'=>empty($status)?'':($status),
                 'range'=>empty($range)?'':($range),
            )            
        );  
    }
    
    public function deleteTC(){
        $id = input::post('id');
        if(!$id){
            echo json_encode(array('error'=>___('Empty id of bill')));
            die();
        } 
        $old = hoadon::get($id);
        if(!$old || ($old->type!=2 && $old->type!=4)){
            echo json_encode(array('error'=>___('You can\'t delete this bill')));
            die();
        } 
        
        //added 05/26/2020
        //tim hoa don cha 1,3
        if($old->cha>0){
            $phieuxuatnhap = db::query("select * from hoadon where (id = {$old->cha} or cha = {$old->cha}) and `type` in (1,3)");
            if($phieuxuatnhap){
                $phieuxuatnhap = model::map($phieuxuatnhap,'id');
                $phieuxuatnhap = implode(",",$phieuxuatnhap);
                db::query("delete from hoadon where id in ($phieuxuatnhap) and `type` in (2,4)");
            }
        }
         
        //xoa trong hoadon
        //db::query("delete from hoadon where id = $id");
        db::query("delete from hoadon where id = $id");
        
        echo json_encode(array('message'=>___('Delete successfully')));
    } 
    
    public function deleteSelectedTC(){
        $ids = input::post('ids');
        if(!$ids){
            echo json_encode(array('error'=>___('Empty list bill')));
            die();
        } 
        
        foreach($ids as $id){
            $old = hoadon::get($id);
            if(!$old || ($old->type!=2 && $old->type!=4)){
                echo json_encode(array('error'=>___('You can\'t delete this bill')));
                die();
            } 
            
            //added 05/26/2020
            //tim hoa don cha 1,3
            if($old->cha>0){
                $phieuxuatnhap = db::query("select * from hoadon where (id = {$old->cha} or cha = {$old->cha}) and `type` in (1,3)");
                if($phieuxuatnhap){
                    $phieuxuatnhap = model::map($phieuxuatnhap,'id');
                    $phieuxuatnhap = implode(",",$phieuxuatnhap);
                    db::query("delete from hoadon where id in ($phieuxuatnhap) and `type` in (2,4)");
                }
            }             
        }
        
        //xoa trong hoadon
        //db::query("delete from hoadon where id IN (".implode(",",$ids).")");
        db::query("delete from hoadon where id IN (".implode(",",$ids).")");
        
        echo json_encode(array('message'=>___('Delete successfully')));
    }
    
    public function trangthaiTC(){
        $id = input::post('id');
        if(!$id){
            echo json_encode(array('error'=>___('Empty id of bill')));
            die();
        } 
        $hoadon = hoadon::get($id);
        
        if(!$hoadon || ($hoadon->type!=2 && $hoadon->type!=4)){
            echo json_encode(array('error'=>___('You can\'t deactive/active this bill')));
            die();
        } 
        
        //added 05/26/2020
        //tim hoa don cha 1,3
        if($hoadon->cha>0){
            $phieuxuatnhap = db::query("select * from hoadon where (id = {$hoadon->cha} or cha = {$hoadon->cha}) and `type` in (1,3)");
            if($phieuxuatnhap){
                $phieuxuatnhap = model::map($phieuxuatnhap,'id');
                $phieuxuatnhap = implode(",",$phieuxuatnhap);
                db::query("update hoadon set trangthai = ".($hoadon->trangthai==1?2:1)." where id in ($phieuxuatnhap) and `type` in (2,4)");
            }
        }   
         
        //update trang thai
        db::query("update hoadon set trangthai = ".($hoadon->trangthai==1?2:1)." where id = $id");
         
        echo json_encode(array('message'=>___('Change status successfully')));
    }
    
    public function viewTC(){
        $id = input::post('id');
        if(!$id) die();
        
        $hoadon = hoadon::get($id);
        if(!$hoadon) die();
        
        //bo sung 05/24/2020
        if(!($employee=session::get('login'))){
            die('');die();
        }
        $employee = json_decode($employee);         
        $login_type = (session::get('login_type'));        
        $kho = $login_type=='doitacsanxuat'?$employee->id:$employee->kho;
         
        if($hoadon->nhanvien>0){
            $tu = 'nhanvien';
            $doitac = nhanvien::get($hoadon->nhanvien);
        }elseif($hoadon->doitac){
            $doitac = doitac::get($hoadon->doitac);
            $tu = $doitac->vaitro == 3?'khachhang':$doitac->vaitro == 2?'doitacsanxuat':'nhacungcap';
            
            //05/27/2020
            if($hoadon->cha>0){ 
                $cha = hoadon::get($hoadon->cha);
                if($cha && $cha->cha>0)
                $tu = ($doitac->vaitro == 3||$doitac->vaitro == 2)?'khachhang':'nhacungcap';
            }
        }else{
            $tu = '';
            $doitac = false;
        }
        
        $roles = config::get('roles');
        $hoadon->tu = $tu;
        $hoadon->doitac = $doitac;
        $hoadon->tu2 = $roles[$tu];
        //render template
        view::render(
            'tc_view',             
            array(  
                 'model'=>$hoadon, 
                 
                 //'employee'=>$employee,  
                 'kho'=>$kho,
            )            
        );     
    }
     
    public function searchxuatnhap($kho=0){
        $q = $_REQUEST['q'];
        
        $more = '';
        $trangthai = empty($_REQUEST['trangthai'])?'':$_REQUEST['trangthai'];
        if($trangthai!=''){
            $more .= ' and trangthai in ('.$trangthai.') ';
        }
        $duan = empty($_REQUEST['duan'])?'':$_REQUEST['duan'];
        if($duan){
            $more .= ' and duan = '.$duan.' ';
        }
        
        $data = db::query("select * from hoadon where (ma like '%$q%') 
            and kho=$kho and type in (1,3) $more limit 10",'hoadon');
        if(!$data) $data = array();
        
        echo json_encode(array_map(function($a){$b = $a->attributes();$b['text']=$b['ma'].' '.date('d/m/Y',$b['ngay']);return $b;},$data));
    }
    
    public function themthuchi(){
        /*
type: 2
kho: 1
ma: 
ngay: 23/05/2020 16:56:49
doitac: 1
ghichu: thưởng dự án
tu: nhanvien
gia: 100000
tong: 0
duan: 1
cha        
        */
        $_POST['tong'] = $_POST['gia'];
        $tu = input::post('tu');
        unset($_POST['tu']);
        if($tu=='nhanvien'){
            $_POST['nhanvien'] = $_POST['doitac']; 
            $_POST['doitac'] = 0;
        }
        
        $hoadon = model::load($_POST,'hoadon');
         
        $hoadon->ngay=time::totime($hoadon->ngay,"/^(\d+)\/(\d+)\/(\d+) (\d+)\:(\d+)\:(\d+)$/",array(4,5,6,2,1,3));
        
        if(!$hoadon->ma){
            $hoadon->ma = common::autoCode(0,array(),$hoadon->type == 2?'PC':'PT');
        }
        
        $nguoitao = session::get('login_id');
        $login_type = session::get('login_type');        
        $hoadon->nguoitao = $login_type[0].$nguoitao;
        //var_dump($hoadon);die();
        $hoadon = $hoadon->insert(true);
        
        if(!$hoadon){
            echo json_encode(array('error'=>___('Can\'t insert invoice! Maybe duplicate code of invoice')));
            die();
        }
        //var_dump($$hoadon);die();
        
        //bo sung 05/26/2020
        if($tu=='doitacsanxuat'){
            unset($hoadon->id);
            $hoadon->type = $hoadon->type==2?4:2;
            $hoadon->ma = common::autoCode(0,array(),$hoadon->type == 2?'PC':'PT');
            $trunggian = $hoadon->doitac;
            $hoadon->doitac = $hoadon->kho;
            $hoadon->kho = $trunggian;            
            $hoadon->insert();
        }
        
        echo json_encode(array('message'=>___('Insert successfully')));
    }
    
    public function filterBCTD(){
        $id = input::post('id');
        if(!$id) die();
        
        if(!($employee=session::get('login'))){
            die();
        }
        
        $employee = json_decode($employee); 
        
        $login_type = (session::get('login_type'));
         
        if(!($login_type=='admin' && $employee->kho>0 || $login_type=='doitacsanxuat')){
            die();
        }
        
        $filter = input::post('filter');
        if($filter){
             
            if(!empty($filter['product'])){
                $product = $filter['product'];                     
            }
            if(!empty($filter['note'])){
                $note = $filter['note'];                     
            }
            
            if(!empty($filter['range'])){
                $range = $filter['range'];                                    
            }
        }
         
        //render template
        view::render(
            'filterBCTD',             
            array(  
                 'id'=>$id, 
                  
                 'product'=>empty($product)?'':($product),
                 'note'=>empty($note)?'':($note),
                 
                 'range'=>empty($range)?'':($range),
            )            
        );  
    }
    public function filterBCTC(){
        $id = input::post('id');
        if(!$id) die();
        
        if(!($employee=session::get('login'))){
            die();
        }
        
        $employee = json_decode($employee); 
        
        $login_type = (session::get('login_type'));
         
        if(!($login_type=='admin' && $employee->kho>0 || $login_type=='doitacsanxuat')){
            die();
        }
        
        $filter = input::post('filter');
        if($filter){
             
            if(!empty($filter['product'])){
                $product = $filter['product'];                     
            }
            if(!empty($filter['note'])){
                $note = $filter['note'];                     
            }
            if(!empty($filter['status'])){
                $status = $filter['status'];         
                $status = explode(",",$status);            
            }
            if(!empty($filter['range'])){
                $range = $filter['range'];                                    
            }
        }
         
        //render template
        view::render(
            'filterBCTC',             
            array(  
                 'id'=>$id, 
                  
                 'product'=>empty($product)?'':($product),
                 'note'=>empty($note)?'':($note),
                 'status'=>empty($status)?'':($status),
                 'range'=>empty($range)?'':($range),
            )            
        );  
    }
    public function taodonxuat($duancha){ //06/09/2020 add $duancha
        //var_dump($_POST);die();
        /*
duan: 2
sanpham[3][soluong]: 6000.000
sanpham[3][gia]: 500
sanpham[3][id]: 3
sanpham[4][soluong]: 1000.000
sanpham[4][gia]: 100
sanpham[4][id]: 4
sanpham[5][soluong]: 1000.000
sanpham[5][gia]: 1000
sanpham[5][id]: 5
sanpham[24][soluong]: 1.000
sanpham[24][gia]: 0
sanpham[24][id]: 24
giamgia: 0
phithem: 0
thanhtoan: 4100000
ma: 
ngay: 26/05/2020 08:20:41
ghichu:
        */
        
        $idduancon = $duan = input::post('duan');
        
        if(!$duan){
            
            //06/09/2020
            $nhacungcap = input::post('nhacungcap');
            if(!$nhacungcap)
            
            echo json_encode(array('error'=>___('Empty project')));
            
            //06/09/2020
            else{
                $duan = duan::get($duancha);
                
                if(!$duan){
                    echo json_encode(array('error'=>___('Empty project')));
                    die();
                }
             
                $sanpham = input::post('sanpham');
        
                $thanhtoan = input::post('thanhtoan');
                
                unset($_POST['sanpham'],$_POST['duan'],$_POST['thanhtoan']  ,$_POST['nhacungcap']);  
                
                $model = model::load($_POST,'hoadon');
                
                $model->ngay = time::totime($model->ngay,"/^(\d+)\/(\d+)\/(\d+) (\d+)\:(\d+)\:(\d+)$/",array(4,5,6,2,1,3));//strtotime($model->ngay);
                 
                $model->type = 1;
                
                if(!$model->ma){
                    $model->ma = common::autoCode(0,array(),'NH');
                }
                
                $nguoitao = session::get('login_id');
                $login_type = session::get('login_type');        
                $model->nguoitao = $login_type[0].$nguoitao;
                
                $model->doitac = $nhacungcap;
                
                $gia = 0;
                
                foreach($sanpham as $id=>$sp){
                    $gia += $sp['soluong']*$sp['gia'];
                }
                
                $model->gia = $gia;
                $model->tong = $model->gia - $model->giamgia + $model->phithem;
                
                $model->kho = $duan->kho;
                
                $model->duan = $duan->id; 
                
                $model = $model->insert(true);
                 
                if(!$model){
                    echo json_encode(array('error'=>___('Can\'t insert invoice! Maybe duplicate code of invoice')));
                    die();
                }
                
                if($thanhtoan>0){
            
                    //tao phieu
                    //tao phieu chi type=2 cho kho
                       
                    $nguoitao = $login_type[0].$nguoitao;
                     
                    $hoadon = model::load(array(
                        'ma'=>common::autoCode(0,array(),'PC'),
                        'ghichu'=>'',
                        'nguoitao'=>$nguoitao, 
                        'tong'=>$thanhtoan,         
                         
                        'type'=>2,
                        'kho'=>$model->kho,
                        'trangthai'=>1,
                        'gia'=>$thanhtoan,  
                        'ngay'=>time(),
                        
                        'cha'=>$model->id,
                        
                        'doitac'=>$nhacungcap,
                        'duan'=>$duan->id,
                    ),'hoadon');
                    $hoadon->insert();
                          
                }
                 
                foreach($sanpham as $sp){
                    //id->sanpham soluong gia   (hoadon)
                    $model2 = model::load(array(
                        'sanpham'=>$sp["id"],
                        'soluong'=>$sp["soluong"],
                        'gia'=>$sp["gia"],
                         
                        'hoadon'=>$model->id,
                    ),'hoadon_chitiet');
                    $model2->insert();
                    
                    //cong kho cua kho 
                    $sp2 = db::query("select * from sanpham where kho=".$model->kho." and (id=".$sp["id"]." or (ma='' and cha=".$sp["id"]."))",'sanpham');              
                    if($sp2){
                        $sp2 = $sp2[0];
                        $sp2->soluong += $sp["soluong"];
                        $sp2->update();
                    }else{
                        $model2 = model::load(array(
                            'cha'=>$sp["id"],
                            'soluong'=>+$sp["soluong"],                             
                            'kho'=>$model->kho,
                        ),'sanpham');
                        $model2->insert();
                    }                      
                }
                
                echo json_encode(array('message'=>___('Insert successfully')));
            }
            
            die();
        }
        
        $duan = duan::get($duan);
        
        if(!$duan){
            echo json_encode(array('error'=>___('Empty project')));
            die();
        }
        
        //$doitac = $duan->khachhang; //var_dump($doitac);die();
        $doitac = $duan->kho; //kho con
        
        //$doitac = doitac::get($doitac);
        
        $duan = $duan->cha;
        
        if(!$duan){
            echo json_encode(array('error'=>___('Empty project')));
            die();
        }
        
        $duan = duan::get($duan);
        
        if(!$duan){
            echo json_encode(array('error'=>___('Empty project')));
            die();
        }
        
        $sanpham = input::post('sanpham');
        
        $thanhtoan = input::post('thanhtoan');
        
        unset($_POST['sanpham'],$_POST['duan'],$_POST['thanhtoan']  ,$_POST['nhacungcap']); //06/09/2020 add ,$_POST['nhacungcap']
        
        $model = model::load($_POST,'hoadon');
        
        $model->ngay = time::totime($model->ngay,"/^(\d+)\/(\d+)\/(\d+) (\d+)\:(\d+)\:(\d+)$/",array(4,5,6,2,1,3));//strtotime($model->ngay);
         
        $model->type = 3;
        
        if(!$model->ma){
            $model->ma = common::autoCode(0,array(),'XH');
        }
        
        $nguoitao = session::get('login_id');
        $login_type = session::get('login_type');        
        $model->nguoitao = $login_type[0].$nguoitao;
        
        $model->doitac = $doitac;
        
        $gia = 0;
        
        foreach($sanpham as $id=>$sp){
            $gia += $sp['soluong']*$sp['gia'];
        }
        
        $model->gia = $gia;
        $model->tong = $model->gia - $model->giamgia + $model->phithem;
        
        $model->kho = $duan->kho;
        
        $model->duan = $duan->id; //06/09/2020
        
        //var_dump($model);
        
        //@file_put_contents('log.txt',var_export($model->attributes(),true),FILE_APPEND);
        
        $model = $model->insert(true);
        
        //@file_put_contents('log.txt',var_export($model->attributes(),true),FILE_APPEND);
        
        if(!$model){
            echo json_encode(array('error'=>___('Can\'t insert invoice! Maybe duplicate code of invoice')));
            die();
        }
        
        //voi PA2
        $model2 = clone $model;
        unset($model2->id);
        $model2->kho = $model->doitac;
        $model2->doitac = $model->kho;
        $model2->ma = common::autoCode(0,array(),'NH');
        $model2->duan = $idduancon;
        $model2->type = 1; //05/27/2020
        $model2->cha = $model->id; //bo sung 05/26/2020

        //@file_put_contents('log.txt',var_export($model2->attributes(),true),FILE_APPEND);
        
        $model2->table = 'hoadon';
        $model2 = $model2->insert(true);
        
        //@file_put_contents('log.txt',var_export($model2->attributes(),true),FILE_APPEND);
        
        if($thanhtoan>0){
            
            //tao phieu
            //tao phieu chi type=2 cho kho
            //tao phieu thu type=4 cho kho
            //$nguoitao = session::get('login_id');
            //$login_type = session::get('login_type');        
            $nguoitao = $login_type[0].$nguoitao;
            
            $cc = array(
                'ma'=>common::autoCode(0,array(),'PT'),
                'ghichu'=>'',
                'nguoitao'=>$nguoitao, 
                'tong'=>$thanhtoan,         
                 
                'type'=>4,
                'kho'=>$model->kho,
                'trangthai'=>1,
                'gia'=>$thanhtoan,  
                'ngay'=>time(),
                
                'cha'=>$model->id,
                'duan'=>$duan->id,
                
                'doitac' => $doitac,
            );            
                                    
            $hoadon = model::load($cc,'hoadon');
            $hoadon->insert();
            
            
            $hoadon = model::load(array(
                'ma'=>common::autoCode(0,array(),'PC'),
                'ghichu'=>'',
                'nguoitao'=>$nguoitao, 
                'tong'=>$thanhtoan,         
                 
                'type'=>2,
                'kho'=>$doitac,
                'trangthai'=>1,
                'gia'=>$thanhtoan,  
                'ngay'=>time(),
                
                'cha'=>$model2->id,//$model->id,
                
                'doitac'=>$model->kho,
                'duan'=>$idduancon,
            ),'hoadon');
            $hoadon->insert();
                  
        }
        
        
        foreach($sanpham as $sp){
            //id->sanpham soluong gia   (hoadon)
            $model2 = model::load(array(
                'sanpham'=>$sp["id"],
                'soluong'=>$sp["soluong"],
                'gia'=>$sp["gia"],
                 
                'hoadon'=>$model->id,
            ),'hoadon_chitiet');
            $model2->insert();
            
            //tru kho cua kho va cong kho cua dt
            $sp2 = db::query("select * from sanpham where kho=".$model->kho." and (id=".$sp["id"]." or (ma='' and cha=".$sp["id"]."))",'sanpham');              
            if($sp2){
                $sp2 = $sp2[0];
                $sp2->soluong -= $sp["soluong"];
                $sp2->update();
            }else{
                $model2 = model::load(array(
                    'cha'=>$sp["id"],
                    'soluong'=>-$sp["soluong"],                             
                    'kho'=>$model->kho,
                ),'sanpham');
                $model2->insert();
            }
             
            $sp2 = db::query("select * from sanpham where kho=".$model->doitac." and (id=".$sp["id"]." or (ma='' and cha=".$sp["id"]."))",'sanpham');            
            if($sp2){
                $sp2 = $sp2[0];
                $sp2->soluong += $sp["soluong"];
                $sp2->update();
            }else{
                $model2 = model::load(array(
                    'cha'=>$sp["id"],
                    'soluong'=>$sp["soluong"],                             
                    'kho'=>$model->doitac,
                ),'sanpham');
                $model2->insert();
            }
                
        }
        
        echo json_encode(array('message'=>___('Insert successfully')));
    }
    
    public function taophantach($kho){ //var_dump($_POST);die();
        /*
id: 39
sanpham[q1590683525256304][soluong]: 1
sanpham[q1590683525256304][gia]: 0
sanpham[q1590683525256304][id]: 0
sanpham[q1590683525256304][dinhluong]: 100.000
sanpham[q1590683525256866][soluong]: 1
sanpham[q1590683525256866][gia]: 0
sanpham[q1590683525256866][id]: 0
sanpham[q1590683525256866][dinhluong]: 100.000
soluong: 1     
sanphamcon: 9   

//bosung 06/01/2020
duan
nhanvien
congdoan
sanluong
        */
        
        $id = input::post('id');
        $sanpham = sanpham::get($id);
        
        if(!$sanpham){
            echo json_encode(array('error'=>___('Empty product')));
            die();
        }
        
        sanpham::concua($sanpham);//added 06/06/2020        
        
        $sanphamcon = input::post('sanphamcon');
        $sanphamcon = sanpham::get($sanphamcon);
        
        if(!$sanphamcon){
            echo json_encode(array('error'=>___('Empty product')));
            die();
        }
        
        $nguoitao = session::get('login_id');
        $login_type = session::get('login_type');        
        $nguoitao = $login_type[0].$nguoitao;
        
        //xac dinh ten bat dau va ma bat dau cua bancat
        $n = db::query("SELECT max(substring(ma,CHAR_LENGTH('".$sanphamcon->ma.
            "')+1)) as c FROM `sanpham` WHERE ma regexp '^".$sanphamcon->ma.
            "[0-9]{5}$'");
        if($n){
            $n = $n[0]; // var_dump($n);   
            $somabatdau = $n->c-0+1;
        }else{
            $somabatdau = 1;
        }
        
        $n = db::query("SELECT max(substring(ten,CHAR_LENGTH('".$sanphamcon->ten.             
            "')+2)) as c FROM `sanpham` WHERE ten regexp '^".$sanphamcon->ten.             
            " [0-9]{5}$'");
        if($n){
            $n = $n[0]; // var_dump($n);  
            $sotenbatdau = $n->c-0+1;
        }else{
            $sotenbatdau = 1;
        } 
        
        //var_dump($somabatdau,$sotenbatdau);
        
        $sp = input::post('sanpham');
        
        foreach($sp as &$sp2){ //$sp2: soluong gia id dinhluong   ; add & : 06/01/2020
            if($sp2['id']>0){
                //tang so luong san pham o kho
                $checksoluong = db::query("select * from sanpham where (id={$sp2['id']} or (ma='' and cha={$sp2['id']})) and kho=$kho");
                if($checksoluong){
                    //update
                     
                    db::query("update sanpham set soluong = soluong + {$sp2['soluong']} 
                        where (id={$sp2['id']} or cha={$sp2['id']}) and kho=$kho");
                        
                    $checksoluong = $checksoluong[0]->soluong + $sp2['soluong'];
                }else{
                    //insert
                    $model2 = model::load(array(
                        'cha'=>$sp2["id"],
                        'soluong'=>$sp2["soluong"],                             
                        'kho'=>$kho,
                    ),'sanpham');
                    $model2->insert();
                    
                    $checksoluong = $sp2['soluong'];
                }
                
                //tự động thêm phiếu kiểm kho                
                if(!(($nhanvien=input::post('nhanvien')) && ($sanluong=input::post('sanluong')))){ //khi k co san xuat: 06/06/2020
                    $ff = array(
                        "type" =>6,
                        "sanpham"=>
                        array( array( 
                          "sanpham"=> $sp2["id"],
                          "gia"=> $sp2["gia"], 
                          "soluong" => $checksoluong,                         
                          "soluongcu"=> $checksoluong - $sp2["soluong"],
                        )),
               
                        "trangthai"=>1,
                        "nguoitao"=> $nguoitao,
                        "ngay"=> time(),
                        "ghichu"=> "Phiếu kiểm kho được tạo tự động khi phân tách Sản phẩm",
                        'ma'=>common::autoCode(1,array(),'KK',6,'hoadon'),
                        "kho"  => $kho,
                        'tong' => $sp2["soluong"]*$sp2["gia"],
                    );
                    common::themhoadon($ff);
                }
            }else{
                //tao san pham moi: can cu theo $sanpham va $sp2 (soluong gia)   
                /* id
ten
cha
danhmuc
soluong
ngay   2020-05-08 19:28:00
donvi
ma
ghichu
gia
kho
nhom                
                */
                $newsanpham = clone $sanpham;
                unset($newsanpham->id);
                $newsanpham = model::extend($newsanpham,array(
                    'soluong' => $sp2['soluong'],
                    'gia' => $sp2['gia'],
                    
                    'ngay' => date('Y-m-d H:i:s'),
                    'cha' =>empty($sanpham->concua)?$sanpham->id:$sanpham->concua, //06/06/2020 thay cho $sanpham->id
                    'kho' =>$kho, //06/06/2020
                ));
                
                $newsanpham->ten .= ' '.sprintf('%05d',$sotenbatdau++);  
                $newsanpham->ma .= ''.sprintf('%05d',$somabatdau++);
                //var_dump($newsanpham);die();
                $newsanpham = $newsanpham->insert(true);
                
                //dinhluong rieng: tinh theo $sanphamcon
                if($newsanpham){
                    $dinhmuc = model::load(array(
                        'sanpham'=>$sanphamcon->id,
                        'cha'=>$newsanpham->id, 
                        'soluong'=>$sp2['dinhluong'], 
                        'kho'=>$kho
                    ),'dinhmuc');
                    $dinhmuc->insert();   
                    
                    //06/01/2020
                    $sp2['id'] = $newsanpham->id;
                    
                    //khi k co san xuat: 06/06/2020
                    if(!(($nhanvien=input::post('nhanvien')) && ($sanluong=input::post('sanluong')))){ 
                        $ff = array(
                            "type" =>6,
                            "sanpham"=>
                            array( array( 
                              "sanpham"=> $sp2["id"],
                              "gia"=> $sp2["gia"], 
                              "soluong" => $sp2["soluong"],                         
                              "soluongcu"=> 0,
                            )),
                   
                            "trangthai"=>1,
                            "nguoitao"=> $nguoitao,
                            "ngay"=> time(),
                            "ghichu"=> "Phiếu kiểm kho được tạo tự động khi phân tách Sản phẩm",
                            'ma'=>common::autoCode(1,array(),'KK',6,'hoadon'),
                            "kho"  => $kho,
                            'tong' => $sp2["soluong"]*$sp2["gia"],
                        );
                        common::themhoadon($ff);
                    }
                    
                }else{
                    @file_put_contents('log.txt','error insert $newsanpham',FILE_APPEND);
                }
            }
        }
        
        //giam so luong san pham goc o kho
        $soluong = input::post('soluong');
        $checksoluong = db::query("select * from sanpham where (id={$sanpham->id} or (ma='' and cha={$sanpham->id})) and kho=$kho");
        if($checksoluong){
            //update             
            db::query("update sanpham set soluong = soluong - {$soluong} 
                where (id={$sanpham->id} or (ma='' and cha={$sanpham->id})) and kho=$kho");
                
            $checksoluong = $checksoluong[0]->soluong - $soluong;
        }else{
            //insert
            $model2 = model::load(array(
                'cha'=>$sanpham->id,
                'soluong'=>-$soluong,                             
                'kho'=>$kho,
            ),'sanpham');
            $model2->insert();
            
            $checksoluong = -$soluong;//$soluong; fixed 06/06/2020
        }
        
        //tự động thêm phiếu kiểm kho        
        if(!(($nhanvien=input::post('nhanvien')) && ($sanluong=input::post('sanluong')))){  //khi k co san xuat: 06/06/2020
            /*$ff = array(
                "type" =>6,
                "sanpham"=>
                array( array( 
                  "sanpham"=> $sanpham->id,
                  "gia"=> $sanpham->gia, 
                  "soluong" => $checksoluong,                         
                  "soluongcu"=> $checksoluong + $soluong,
                )),
       
                "trangthai"=>1,
                "nguoitao"=> $nguoitao,
                "ngay"=> time(),
                "ghichu"=> "Phiếu kiểm kho được tạo tự động khi phân tách Sản phẩm",
                'ma'=>common::autoCode(1,array(),'KK',6,'hoadon'),
                "kho"  => $kho,
                'tong' => -$soluong*$sanpham->gia,
            );
            common::themhoadon($ff);*/
            
            //06/06/2020: doi phieu KK thanh PH
            common::themhoadon(array(            
                'ma'=>common::autoCode(1,array(),'PH',6,'hoadon'),
                'ngay'=>time(),
                'ghichu'=>"Giảm số lượng khi phân tách sản phẩm",
                'gia'=>$soluong*$sanpham->gia,
                'tong'=>$soluong*$sanpham->gia,
                'sanpham'=>array(array(
                    'sanpham'=>$id,
                    'ghichu'=>'',
                    'soluong'=>$soluong,
                    'gia'=>$sanpham->gia
                )),
                'trangthai'=>1,
                'type'=>7,
                "nguoitao"=>$nguoitao,
                'kho'=>$kho 
            ));  
        }
        
        //06/01/2020  
        if(($nhanvien=input::post('nhanvien')) && ($sanluong=input::post('sanluong'))){     
            $duan=input::post('duan');
            $hoadon = model::load(array(
                'ma'=>common::autoCode(0,array(),'TS'),
                'ghichu'=>'Tự động tạo khi phân tách sản phẩm',
                'nguoitao'=>$nguoitao,
                //'tong'=>
                'duan'=>$duan,
                'nhanvien'=>$nhanvien,
                'type'=>5,
                'kho'=>$kho,
                'trangthai'=>1,
                //gia  
                'ngay'=>time(),
            ),'hoadon');
            $hoadon = $hoadon->insert(true);    
            //thanh pham - 
            foreach($sp as &$sp2){ 
                if(!$sp2['id']) continue;
                $hoadon_chitiet = model::load(array(
                    'hoadon'=>$hoadon->id,
                    'sanpham'=>-$sp2['id'],
                    'soluong'=>$sp2['soluong'],
                ),'hoadon_chitiet');
                $hoadon_chitiet->insert();
            }
            //nguyen lieu +
            $hoadon_chitiet = model::load(array(
                'hoadon'=>$hoadon->id,
                'sanpham'=>$id,
                'soluong'=>$soluong,
            ),'hoadon_chitiet');
            $hoadon_chitiet->insert();
            //cham cong san luong
            $congdoan = input::post('congdoan');
            $congdoan = congdoan::get($congdoan);
            $hoadon = model::load(array(                                     
                'duan'=>$duan,                 
                'doituong'=>$nhanvien,
                'soluong'=>$sanluong,
                'type'=>3,
                'congdoan'=>$congdoan->id,
                'gia'=>$congdoan->dongia,                                 
                'kho'=>$kho,                 
                'ngay'=>date('Y-m-d'),
            ),'ngay');
            $hoadon->insert();
        }
        
        echo json_encode(array('message'=>___('Split product successfully'))); 
    }
    
    public function getNhomdinhmuc($id,$kho=0){
        $dinhmuc = db::query("select dinhmuc.*, sanpham.ten, sanpham.ma from dinhmuc 
            inner join sanpham on dinhmuc.sanpham = sanpham.id and dinhmuc.nhom = $id");
        //return $dinhmuc;
        if(!$dinhmuc) $dinhmuc = array();
        
        echo json_encode(array_map(function($a){return $a->attributes();},$dinhmuc));
    }
    
    public function nhomcongdoan($kho=0){
        $a = nhomcongdoan::children($kho,0);
        //$a = array_map(function($b){return $b->attributes();},$a); 
        echo json_encode($a);
    }
    
    public function deleteSelectedNCD(){
        $ids = input::post('ids');
        if(!$ids){
            echo json_encode(array('error'=>___('Empty list group')));
            die();
        } 
        db::query("delete from nhomcongdoan where id IN (".implode(",",$ids).")");
        echo json_encode(array('message'=>___('Delete successfully')));
    }
    
    public function editTitleNCD(){
        $id = input::post('id');
        $ten = input::post('ten');
        
        if(!$id || !$ten) die();
        
        $model = nhomcongdoan::get($id);
        if(!$model) die();
        
        $model->ten = $ten;
        $model->update();
    }
    public function deleteNCD(){
        $id = input::post('id');
        if(!$id){
            echo json_encode(array('error'=>___('Empty group')));
            die();
        } 
        db::query("delete from nhomcongdoan where id = $id");
        echo json_encode(array('message'=>___('Delete successfully')));
    }
    public function doaddncd(){
        $model = model::load($_POST,'nhomcongdoan');
        if($model->cha>0){
            $cha = nhomcongdoan::get($model->cha);
            if(!$cha){
                echo json_encode(array('error'=>___('Invalid parent group')));
                die();
            }
            $model->cap = $cha->cap + 1;
        }else $model->cap = 1;
        $model->insert();
        echo json_encode(array('message'=>___('Insert successfully')));
    }
    public function doeditNCD(){
         
        $model = model::load($_POST,'nhomcongdoan');
        //var_dump($model);die(); 
        
        $oldModel = nhomcongdoan::get(input::post('id'));
        
        if($oldModel->cha!=$model->cha){
        
            if($model->cha>0){
                $cha = nhomcongdoan::get($model->cha);
                if(!$cha){
                    echo json_encode(array('error'=>___('Invalid parent category')));
                    die();
                }
                $model->cap = $cha->cap + 1;
            }else $model->cap = 1;
            
            //find child
            $children = nhomcongdoan::cat_recursive($oldModel->kho,$model->id);
            
            if($children){
                foreach($children as $child){
                    $child->cap += $model->cap - $oldModel->cap;
                    db('nhomcongdoan')->
                        update(array('cap'=>$child->cap))->
                        where('id','=',$child->id)->
                        execute();                     
                }
            }
        
        }
        
        $model->update();
        echo json_encode(array('message'=>___('Update successfully')));        
    } 
    public function editNCD(){
        $id = input::post('id');
        if(!$id) die();
        
        if(!($employee=session::get('login'))){
            die();
        }
        
        $model = nhomcongdoan::get($id);//var_dump($model);die();
        
        if(!$model) die();
        
        $employee = json_decode($employee);
        $categories = nhomcongdoan::cat_recursive(empty($employee->vaitro)?$employee->kho:$employee->id,0,$id);
         
        //render template
        view::render(
            'ncd_edit',             
            array(  
                 'model'=>$model, 
                 'categories'=>$categories,   
                 'employee'=>$employee,  
            )            
        );                
    } 
    public function addNCD(){
        if(!($employee=session::get('login'))){
            die();
        }
        
        $id = input::post('id');
        //$category = danhmuc::get($id);
        
        $employee = json_decode($employee);
        $categories = nhomcongdoan::cat_recursive(empty($employee->vaitro)?$employee->kho:$employee->id);
        //render template
        view::render(
            'ncd_add',             
            array(  
                'employee'=>$employee,  
                'categories'=>$categories,   
                'id'=>$id, 
            )            
        );
    }
    
    public function congdoan($nhom){
        $a = congdoan::list_all(0,$nhom);
        $a = array_map(function($b){return $b->attributes();},$a);  
        echo json_encode($a);
    }
    
}