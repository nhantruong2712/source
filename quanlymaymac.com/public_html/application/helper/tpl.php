<?

class tpl{
    public static function alert($message){
        $tick = strpos($message,'\'')===false?'\'':'"';
        return 'alert('.$tick.$message.$tick.')';
    }
    
    public static function jsalert($message){
        return '<script>'.self::alert($message).'</script>';
    }
    
    public static function load($location,$message){
        return view::render(
            'load',
            array(
                'location'=>$location,
                'message'=>$message,
            ) 
        );
    }
    
}