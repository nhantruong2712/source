<?php if(!defined('HTH')){die('External Access to File Denied');}

/**
 * Input Library For SA Framework
 *
 * @Author          Hoang Trong Hoi
 * @Copyright       2011
 */

class input
{
	// Post
	// ---------------------------------------------------------------------------
	public static function post($field)
	{
		return (isset($_POST[$field])) ? $_POST[$field] : false;
	}
 
	// Get
	// ---------------------------------------------------------------------------
	public static function get($field)
	{
		return (isset($_GET[$field])) ? $_GET[$field] : false;
	}
 
	// Cookie
	// ---------------------------------------------------------------------------
	public static function cookie($field)
	{
		return (isset($_COOKIE[$field])) ? $_COOKIE[$field] : false;
	}
 
	// Files
	// ---------------------------------------------------------------------------
	public static function files($field)
	{
		return (isset($_FILES[$field])) ? $_FILES[$field] : false;
	}
 
	// Request
	// ---------------------------------------------------------------------------
	public static function request($field)
	{
		return (isset($_REQUEST[$field])) ? $_REQUEST[$field] : false;
	}

	// Server
	// ---------------------------------------------------------------------------
	public static function server($field)
	{
		return (isset($_SERVER[$field])) ? $_SERVER[$field] : false;
	}
    
	/**
	 * Retrieve the desired value from an array of allowed values.
	 *
	 * @return	mixed							The validated value or the default when the value wasn't found.
	 * @param	string $variable				The variable that should be validated.
	 * @param	array[optional] $values			The possible values. If the value isn't present the default will be returned.
	 * @param	mixed $defaultValue				The default-value.
	 * @param	string[optional] $returnType	The type that should be returned.
	 */
	public static function getValue($variable, array $values = null, $defaultValue, $returnType = 'string')
	{
		// redefine arguments
		$variable = !is_array($variable) ? (string) $variable : $variable;
		$defaultValue = !is_array($defaultValue) ? (string) $defaultValue : $defaultValue;
		$returnType = (string) $returnType;

		// default value
		$value = $defaultValue;

		// variable is an array
		if(is_array($variable) && !empty($variable))
		{
			// no values
			if($values === null) $values = array();

			// fetch difference between the 2 arrays
			$differences = array_diff($variable, $values);

			// set value
			if(count($variable) != count($differences)) $value = array_intersect($variable, $values);

			// values was empty
			elseif(empty($values)) $value = $variable;
		}

		// provided values
		elseif($values !== null && in_array($variable, $values)) $value = $variable;

		// no values
		elseif($values === null && $variable != '') $value = $variable;

		/**
		 * We have to define the return type. Too bad we cant force it within
		 * a certain list of types, since that's what this method actually does.
		 */
		switch($returnType)
		{
			// array
			case 'array':
				$value = ($value == '') ? array() : (array) $value;
			break;

			// bool
			case 'bool':
				$value = (bool) $value;
			break;

			// double/float
			case 'double':
			case 'float':
				$value = (float) $value;
			break;

			// int
			case 'int':
				$value = (int) $value;
			break;

			// string
			case 'string':
				$value = (string) $value;
			break;
		}

		return $value;
	}
    
    /* Static functions */
    public static function trim()
    {
        $_POST = array_map(create_function('$a', 'return is_string($a)?trim($a):$a;'), $_POST);
    }
    
    public static function strip()
    {
        $_POST = array_map('stripslashes', $_POST);
    }
    
    public static function escape()
    {
        $_POST = array_map('mysql_real_escape_string', $_POST);
    } 
    
    //set field from $_POST or from model
    public static function model(&$model,$field){
        if(input::post($field)) $model->$field = input::post($field);
    }
    
    //new parse_str function in case of double fields
    public static function parse_str($query_string){
        //$query_string = urldecode($query_string);
        $res = array();//array keys
        $res2 = array();//array values
        //abc=1&c=2
        $temp = explode('&',$query_string);
        //filter valid values
        $temp = array_filter(
            $temp,
            create_function(
                '$x',
                'return trim($x)<>"" && preg_match("/^\w[\w\d\[\]]*?=?/",$x);'
            )
        );
        foreach($temp as $k=>$t){
            if(substr_count($t,'=')==0){
                $res[$k]=$t;
                $res2[$k]="";
            }else{
                $temp2 = explode('=',$t,2);
                $res[$k]=$temp2[0];
                $res2[$k]=$temp2[1];
            }
        }
        $res3 = array();
        $key_doubles = array();
        foreach(array_count_values($res) as $v=>$c){
            if($c>1){
                $key_doubles[]=$v;
            }
        }
        foreach($res as $k=>$v){
            if(in_array($v,$key_doubles)){
                $res3[$v][] = $res2[$k];
            }else{
                $res3[$v] = $res2[$k];
            }
        }
        return $res3;
    }       
    
    public static function stripslashes($str){
        return str_replace('\/','/', stripslashes($str));
    }
}