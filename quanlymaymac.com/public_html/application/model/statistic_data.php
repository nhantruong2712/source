<?

class statistic_data extends model{
    var $table='statistic_data';
    
    public static function add(){
        $model = new self;
        $model->time = time();
        //$check = db::query("select * from ".$model->table." where ");
        $model->date = date('dmY',$model->time);
        $model->ip = utils::ip();
        $model->md5 = md5($model->date.$model->ip);
        
        //$check = db::query("select * from ".$model->table." where ip = '".$model->ip."' and `date` = '".$model->date."'");
        //$check = db::query("select * from ".$model->table." where `md5` = '".$model->md5."'");
        $check = $model->find_by_md5($model->md5);
        if($check){
            //update
            $check->table = $model->table;
            $check->time = $model->time;
            $check->update();
        }else{
            //insert
            $model->insert();
        }
        statistic::recalc();
    } 
}