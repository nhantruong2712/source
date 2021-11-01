<?
mb_regex_encoding('UTF-8');
mb_internal_encoding('UTF-8');

class vietnamese{

    public static function remove_accent_vi($str) {
        if(!$str) return false;
        $utf8 = array(
                    'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ|Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
                    'd'=>'đ|Đ',
                    'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ|É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
                    'i'=>'í|ì|ỉ|ĩ|ị|Í|Ì|Ỉ|Ĩ|Ị',
                    'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ|Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
                    'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự|Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
                    'y'=>'ý|ỳ|ỷ|ỹ|ỵ|Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        );
        foreach($utf8 as $ascii=>$uni) $str = preg_replace("/($uni)/i",$ascii,$str);
        return $str;
    }

    public static function remove_accent($str,$lower = true){
        $str = self::remove_accent_vi($str);
        $str = mb_ereg_replace("[^\w]"," ",$str);
        return $lower?strtolower($str):$str;
    }

    public static function friendly($str){
        //$str = self::remove_accent_vi($str);
        $str = self::remove_accent($str);
        //$str = mb_ereg_replace("[^\w]+","-",$str);
        $str = preg_replace("/[^a-z0-9]+/","-",$str);
        return strtolower($str);
    }
    
    public static function cut($str, $len, $subfix='...'){
        $a = mb_split("\s+", $str);
        $a = array_splice($a,$len);
        return implode(" ",$a).$subfix; 
    }
}
?>