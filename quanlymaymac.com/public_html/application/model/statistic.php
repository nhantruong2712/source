<?

class statistic extends model{
    var $table='statistic';
    
    public static function get(){
        $model = new self;
        return $model->find_by_id(1);
    }
    
    public static function recalc(){
        $model = new self;
        $_model = new statistic_data;
        $model->all = $_model->count();
        $model->id = 1;
        $model->tomorrow = $_model->count(
            array(
                array('date','=',date('dmY'))
            )
        );
        $model->yesterday = $_model->count(
            array(
                array('date','=',date('dmY',time()-86400))
            )
        );
        $model->now = $_model->count(
            array(
                array('time','>=',time()-1200)//20 minutes = 1200s
            )
        );
        $model->update();
    }
    
    public static function img($count){
        $s = str_split($count);
        if(count($s)<7){
            $s = array_pad($s,-7,'0');
        }
        $s = array_map(
            create_function(
                '$x',
                'return "<img src=\"themes/default/img/counter/".$x.".png\" style=\"margin: 0pt; border: 0pt none;\" alt=\"".$x."\" title=\"Visitors Counter\"/>";'
            ),
            $s
        );
        return implode("",$s);
    }
    
    public static function now(){
        //2012-10-31 05:52
        return date('Y-m-d H:i');
    }
    
    public static function tomorrow(){
        return date('Y-m-d');
    }
    
    public static function yesterday(){
        return date('Y-m-d',time()-86400);
    }
}