<?

class users extends model{
    var $table = 'users';
    
    public static function get($id){
        if($id){
            $customer = new self;             
            $customer = $customer->find_by_user_id($id);             
            return($customer);
        }else{
            return false;
        }         
    }
    
    public static function name($id){
        $model = self::get($id);
        return $model?$model->full_name:false;
    } 
   
    public static function list_all($page=1,$pp=20,$add='',&$pages=''){
        //$class = new self;
        $class = db::query("select SQL_CALC_FOUND_ROWS * FROM users $add order by user_id desc limit ".($page==1?'0':(($page-1)*$pp)).",$pp");
        $pages = db::query("select found_rows() as total");
        $pages = $pages[0]->total;
        $pages = ceil($pages/$pp);
        return $class;
    } 
    public static function list_select($key='user_id',$val='full_name'){
        $res = array();
        foreach(self::list_all(1,1000) as $v){
            $res[$v->$key] = $v->$val;
        }
        return $res;
    }
    
    public static function login($user, $pass){
        $u = db::query("select * from users where user_name = '$user' and `password` = '".md5($pass)."'");
        if($u){
            $u = $u[0];
            session::set('user_name',$u->user_name);
            session::set('full_name',$u->full_name);
            session::set('user_id',$u->user_id);
            return true;
        }
        return false;
    }
    
    public static function is_login(){
        return session::get('user_id');
    }
    
    public static function logout(){
        session::delete('user_name');
        session::delete('full_name');
        session::delete('user_id');         
    }
}