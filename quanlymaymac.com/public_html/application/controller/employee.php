<?php

class employee_controller extends controller{
    public function index(){
        $message = '';
        
        $employee = json_decode(session::get('login'));
        if(!isset($employee->avatar) || $employee->avatar=='') $employee->avatar = 'm1.png';
        
        //var_dump($employee);die();
        if(!isset($employee->role)){
            url::redirect('customer'); die();
        }
        if($employee->role==1){
            url::redirect('admin'); die();
        }
        
        //tat ca employee  
        $employees = employee::list_all(' where role in (1,2) or id = "'.$employee->id.'" ');
        
        foreach($employees as &$e){
            $e = (object) array(
                'id'=>'e'.$e->id,
                'name'=>$e->name,
                'avatar'=>$e->avatar?$e->avatar:'m1.png',
                'role'=>$e->role,
            );
            //$e->type = 'employee';             
        }
        
        $all = array();
        foreach($employees as $x){
            $all[$x->id] = $x;
        }
        
        //danh sach don nhan hang: status 1,2
        //var_dump(db::query('select p.* from `order` o inner join `order_place` p on p.order_id = o.id where o.status=1 and o.type=0 and o.employee = '.$employee->id.' order by o.`date` desc'));die();
        
        //danh sach don dang giao status=4 
        /*var_dump(db::query('select o.status as status2, o.date2,o.phone as phone2,o.name as name2,o.address as address2, 
                    o.location as location2, p.* from `order` o 
                    inner join `order_place` p on p.order_id = o.id 
                    where o.status =4 and o.type=0 and o.employee = '.$employee->id.' 
                    order by o.`date2` desc limit 5'));die();*/
         
        //danh sach don mua ho dang di type=1; status 1,2
        /*var_dump(db::query('select o.employee, o.status as status2, o.date2,o.phone as phone2,o.name as name2,o.address as address2, 
                    o.location as location2, p.* from `order` o 
                    inner join `order_place` p on p.order_id = o.id and p.status=0
                    where o.status in (1,2) and o.type=1 and o.employee = '.$employee->id.' 
                    group by p.order_id
                    order by o.`date2` desc limit 5'));die();*/
        
        $donmuaho = db::query('select * from `order`
            where status in (1,2) and type =1 and employee = '.$employee->id.' order by `date` desc limit 5');
        foreach($donmuaho as &$order){
            $order->places = db::query("select * from `order_place`
                where order_id = '{$order->id}'");
        }  
        
        //var_dump($donmuaho);die();   
                     
        //render template
        view::render(
            'employee',             
            array(  
                 'employee'=>$employee,
                 'message'=>$message,
                 'title'=>"Nhân viên",
                 'h1'=>'Bảng Điều Khiển',
                 
                 'all'=> $all,
                 
                 'donnhan'=>db::query('select o.good, o.status as status2, o.date2,o.phone as phone2,o.name as name2,o.address as address2, 
                    o.location as location2, 
                    p.id ,p.order_id ,p.address ,p.location ,p.name ,p.phone ,p.price, p.status 
                    from `order` o 
                    inner join `order_place` p on p.order_id = o.id 
                    where o.status in (1,2) and o.type=0 and o.employee = '.$employee->id.' 
                    order by o.`date2` desc limit 5'),
                 'dondanggiao'=>db::query('select o.good, o.status as status2, o.date2,o.phone as phone2,o.name as name2,o.address as address2, 
                    o.location as location2, 
                    p.id ,p.order_id ,p.address ,p.location ,p.name ,p.phone ,p.price, p.status 
                    from `order` o 
                    inner join `order_place` p on p.order_id = o.id 
                    where o.status =4 and o.type=0 and o.employee2 = '.$employee->id.' 
                    order by o.`date2` desc limit 5'),
                  'donmuaho'=>$donmuaho,     
                 
            )            
        );
    }
     
}