<?php if(!defined('HTH')){die('External Access to File Denied');}

/**
 * Message Library For SA Framework
 *
 * @author          Hoang Trong Hoi
 * @copyright       2011
 */

class message
{
    private static $x = array();
    public $language = false;
    
    /* Constructor */
    function __construct($language=false){
        $this->language = $language;
    }
    
	// Set
	// ---------------------------------------------------------------------------
	public static function set($message,$value)
	{
		self::$x[$message] = $value;
	}
	
	// Get
	// ---------------------------------------------------------------------------
	public static function get($message)//not support sprintf
	{
	    if(array_key_exists($message,self::$x)){
            return self::$x[$message];
        }
        else throw new SAException('Key not exists!');        
	}
    
    public static function getset(){//support sprintf
        $agrs = func_get_args();
        $message = $agrs[0];
        if(array_key_exists($message,self::$x)){
            //array_shift($agrs);
            $agrs[0]=self::$x[$message];
            //return call_user_func_array('sprintf',array_merge(array(self::$x[$message]),$agrs));
            return call_user_func_array('sprintf', $agrs);
        }elseif(count($agrs)==1){
            //get
            return $message;
        }else{
            //set
            self::$x[$message] = $agrs[1];
            return true;
        }
    }
    
    public static function lang(){
        return substr(config::get('language'),0,2);
    }
}

function ___(){
    //return $value!==false?message::set($str,$value):message::get($str);
    $agrs = func_get_args();
    return call_user_func_array(array('message','getset'),$agrs);
}