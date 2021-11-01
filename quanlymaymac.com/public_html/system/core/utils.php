<?php
/**
 * SA Framework utils Class
 *
 * @Author          Hoang Trong Hoi
 * @Copyright       2011
 */
 
class utils
{
    /** array functions */
    //1 2 3 => (1 2)(2 3)
    public function bridge($mystuff){
        $mylimit = count($mystuff); 
        $counter = 0; 
        $res = array();
        while ($counter < $mylimit-1) { 
            $res[] = array($mystuff[$counter] ,$mystuff[++$counter]);
        } 
        return $res;
    }
    //1,2,3 => (1,2),(2,3),(1,3)
    public static function combination($mystuff){
        $mylimit = count($mystuff); 
        $counter = 0; 
        $res = array();
        while ($counter < $mylimit) { 
            $intcount = $counter+1; 
            while ($intcount < $mylimit) { 
                if ($intcount!= $counter) 
                    $res[] = array($mystuff[$counter] ,$mystuff[$intcount]);
                $intcount++; 
            } 
            $counter++; 
        } 
        return $res;
    }  
      
    public static function array_flatten($a) {
        foreach($a as $k=>$v) $a[$k]=(array)$v;
        return call_user_func_array('array_merge',$a);
    } 
    
    public static function array_flatten2(array $array)
    {
    	$i = 0;
    	while ($i < count($array))
    	{
    		if (is_array($array[$i]))
    			array_splice($array,$i,1,$array[$i]);
    		else
    			++$i;
    	}
    	return $array;
    }
    
    public static function array_flatten3($array)
    {
        while (($v = array_shift($array)) !== null) {
            if (is_array($v)) {
                $array = array_merge($v, $array);
            } else {
                $tmp[] = $v;
            }
        }
        return $tmp;
    }
       
    //array(1,1,2,3) => array(  array(1,1,2,3) array(1,1,3,2) array(1,2,1,3) array(1,2,3,1)...   )
    public static function array_bubble($array){
        $res = array();
        $array2 = $array;
        if(count($array)>1){
            foreach($array as $k=>$v){
                $array3 = $array2;
                array_splice($array2,$k,1,array());
                if(count($array)>2){
                    foreach(self::array_bubble($array2) as $a){
                        $res[] = array_merge(array($v),$a);
                    }
                }else{
                    $res[] = array_merge(array($v),$array2);
                }
                $array2 = $array3;
            }
        }elseif(count($array)==1){
            $res[] = $array;
        } 
        return self::array_unique($res);
    }   

    //similar array_bubble function
    //comb(array(1, 2, 3), $r); then $r would be: (1 2 3)(1 3 2)(2 1 3)(2 3 1)(3 1 2)(3 2 1) 
    public static function comb($arr, &$rarr, $vtemp = array()){   
        foreach($arr as $key => $value){
            $vtemp2 = $vtemp;
            $vtemp2[] = $value;

            $atemp = $arr;           
            unset($atemp[$key]);
           
            if(count($atemp) > 0){
                self::comb($atemp, $rarr, $vtemp2);
            } else {
                $t = array();
           
                foreach($vtemp2 as $val){
                    $t[] = $val;
                }
               
                $rarr[] = $t;
            }
        }
    }

    //1 2 3 => () (1) (2) (1 2) (3) (1 3) (2 3) (1 2 3)
    public static function combinations($elements) {
        if (is_array($elements)) {
        /*
        I want to generate an array of combinations, i.e. an array whose elements are arrays
        composed by the elements of the starting object, combined in all possible ways.
        The empty array must be an element of the target array.
        */
        $combinations=array(array()); # don't forget the empty arrangement!
        /*
        Built the target array, the algorithm is to repeat the operations below for each object of the starting array:
        - take the object from the starting array;
        - generate all arrays given by the target array elements merged with the current object;
        - add every new combination to the target array (the array of arrays);
        - add the current object (as a vector) to the target array, as a combination of one element.
        */
        foreach ($elements as $element) {
            $new_combinations=array(); # temporary array, see below
            foreach ($combinations as $combination) {
                $new_combination=array_merge($combination,(array)$element);
                # I cannot merge directly with the main array ($combinations) because I'm in the foreach cycle
                # I use a temporary array
                array_push($new_combinations,$new_combination);
            }
            $combinations=array_merge($combinations,$new_combinations);
        }
            return $combinations;
        } else {
            return false;
        }
    }
    
    //similar combinations
    function combinations2($array) {
        // initialize by adding the empty set
        $results = array(array());
        foreach ($array as $element)
            foreach ($results as $combination)
                array_push($results, array_merge(array($element), $combination));
        return $results;
    }
        
    public static function array_unique($array){
        $res = array();
        $temp = array();
        foreach($array as $v){
            if(!in_array($v,$temp)){
                $res[]=$v;
            }
            $temp[] = $v;
        }
        return $res;
    }
    
    /* Ex: count_filter(list_ages, '<=1') */
    public static function count_filter($array,$condition){
        return count(
            array_filter(
                $array,
                create_function(
                    '$x',
                    'return $x'.$condition.';'
                )
            )
        );
    }
    
    /** Buble sort algorithm */
    public static function bubbleSort ($items) {  
        $size = count($items);  
        for ($i=0; $i<$size; $i++) {  
             for ($j=0; $j<$size-1-$i; $j++) {  
                  if ($items[$j+1] < $items[$j]) {  
                      self::arraySwap($items, $j, $j+1);  
                  }  
             }  
        }  
        return $items;  
    }  
    
    public static function arraySwap (&$arr, $index1, $index2) {  
        list($arr[$index1], $arr[$index2]) = array($arr[$index2], $arr[$index1]);  
    } 
    /** other functions */
    /**
     * Somewhat naive way to determine if an array is a hash.
     */
    public static function is_hash(&$array)
    {
    	if (!is_array($array))
    		return false;
    
    	$keys = array_keys($array);
         
    	return @is_string($keys[0]) ? true : false;
    }
     
    
    /**
     * Returns true if all values in $haystack === $needle
     * @param $needle
     * @param $haystack
     * @return unknown_type
     */
    public static function all($needle, array $haystack)
    {
    	foreach ($haystack as $value)
    	{
    		if ($value !== $needle)
    			return false;
    	}
    	return true;
    }
    
    /**
     * Wrap string definitions (if any) into arrays.
     */
    public static function wrap_strings_in_arrays(&$strings)
    {
    	if (!is_array($strings))
    		$strings = array(array($strings));
    	else 
    	{
    		foreach ($strings as &$str)
    		{
    			if (!is_array($str))
    				$str = array($str);
    		}
    	}
    	return $strings;
    }

	public static function extract_options($options)
	{
		return is_array(end($options)) ? end($options) : array();
	}

	public static function add_condition(&$conditions=array(), $condition, $conjuction='AND')
	{
		if (is_array($condition))
		{
			if (empty($conditions))
				$conditions = self::array_flatten($condition);
			else
			{
				$conditions[0] .= " $conjuction " . array_shift($condition);
				$conditions[] = self::array_flatten($condition);
			}
		}
		elseif (is_string($condition))
			$conditions[0] .= " $conjuction $condition";

		return $conditions;
	}

	public static function is_odd($number)
	{
		return $number & 1;
	}

	public static function is_a($type, $var)
	{
		switch($type)
		{
			case 'range':
				if (is_array($var) && (int)$var[0] < (int)$var[1])
					return true;

		}

		return false;
	}

	public static function is_blank($var)
	{
		return 0 === strlen($var);
	}

	private static $plural = array(
        '/(quiz)$/i'               => "$1zes",
        '/^(ox)$/i'                => "$1en",
        '/([m|l])ouse$/i'          => "$1ice",
        '/(matr|vert|ind)ix|ex$/i' => "$1ices",
        '/(x|ch|ss|sh)$/i'         => "$1es",
        '/([^aeiouy]|qu)y$/i'      => "$1ies",
        '/(hive)$/i'               => "$1s",
        '/(?:([^f])fe|([lr])f)$/i' => "$1$2ves",
        '/(shea|lea|loa|thie)f$/i' => "$1ves",
        '/sis$/i'                  => "ses",
        '/([ti])um$/i'             => "$1a",
        '/(tomat|potat|ech|her|vet)o$/i'=> "$1oes",
        '/(bu)s$/i'                => "$1ses",
        '/(alias)$/i'              => "$1es",
        '/(octop)us$/i'            => "$1i",
        '/(ax|test)is$/i'          => "$1es",
        '/(us)$/i'                 => "$1es",
        '/s$/i'                    => "s",
        '/$/'                      => "s"
    );

    private static $singular = array(
        '/(quiz)zes$/i'             => "$1",
        '/(matr)ices$/i'            => "$1ix",
        '/(vert|ind)ices$/i'        => "$1ex",
        '/^(ox)en$/i'               => "$1",
        '/(alias)es$/i'             => "$1",
        '/(octop|vir)i$/i'          => "$1us",
        '/(cris|ax|test)es$/i'      => "$1is",
        '/(shoe)s$/i'               => "$1",
        '/(o)es$/i'                 => "$1",
        '/(bus)es$/i'               => "$1",
        '/([m|l])ice$/i'            => "$1ouse",
        '/(x|ch|ss|sh)es$/i'        => "$1",
        '/(m)ovies$/i'              => "$1ovie",
        '/(s)eries$/i'              => "$1eries",
        '/([^aeiouy]|qu)ies$/i'     => "$1y",
        '/([lr])ves$/i'             => "$1f",
        '/(tive)s$/i'               => "$1",
        '/(hive)s$/i'               => "$1",
        '/(li|wi|kni)ves$/i'        => "$1fe",
        '/(shea|loa|lea|thie)ves$/i'=> "$1f",
        '/(^analy)ses$/i'           => "$1sis",
        '/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i'  => "$1$2sis",
        '/([ti])a$/i'               => "$1um",
        '/(n)ews$/i'                => "$1ews",
        '/(h|bl)ouses$/i'           => "$1ouse",
        '/(corpse)s$/i'             => "$1",
        '/(us)es$/i'                => "$1",
        '/(us|ss)$/i'               => "$1",
        '/s$/i'                     => ""
    );

    private static $irregular = array(
        'move'   => 'moves',
        'foot'   => 'feet',
        'goose'  => 'geese',
        'sex'    => 'sexes',
        'child'  => 'children',
        'man'    => 'men',
        'tooth'  => 'teeth',
        'person' => 'people'
    );

    private static $uncountable = array(
        'sheep',
        'fish',
        'deer',
        'series',
        'species',
        'money',
        'rice',
        'information',
        'equipment'
    );

    public static function pluralize( $string )
    {
        // save some time in the case that singular and plural are the same
        if ( in_array( strtolower( $string ), self::$uncountable ) )
            return $string;

        // check for irregular singular forms
        foreach ( self::$irregular as $pattern => $result )
        {
            $pattern = '/' . $pattern . '$/i';

            if ( preg_match( $pattern, $string ) )
                return preg_replace( $pattern, $result, $string);
        }

        // check for matches using regular expressions
        foreach ( self::$plural as $pattern => $result )
        {
            if ( preg_match( $pattern, $string ) )
                return preg_replace( $pattern, $result, $string );
        }

        return $string;
    }

    public static function singularize( $string )
    {
        // save some time in the case that singular and plural are the same
        if ( in_array( strtolower( $string ), self::$uncountable ) )
            return $string;

        // check for irregular plural forms
        foreach ( self::$irregular as $result => $pattern )
        {
            $pattern = '/' . $pattern . '$/i';

            if ( preg_match( $pattern, $string ) )
                return preg_replace( $pattern, $result, $string);
        }

        // check for matches using regular expressions
        foreach ( self::$singular as $pattern => $result )
        {
            if ( preg_match( $pattern, $string ) )
                return preg_replace( $pattern, $result, $string );
        }

        return $string;
    }

    public static function pluralize_if($count, $string)
    {
        if ($count == 1)
            return $string;
        else
            return self::pluralize($string);
    }

	public static function squeeze($char, $string)
	{
		return preg_replace("/$char+/",$char,$string);
	}
    
    /**
     * Convenience method for htmlspecialchars.
     *
     * @param mixed $text Text to wrap through htmlspecialchars.  Also works with arrays, and objects.
     *    Arrays will be mapped and have all their elements escaped.  Objects will be string cast if they
     *    implement a `__toString` method.  Otherwise the class name will be used.
     * @param boolean $double Encode existing html entities
     * @param string $charset Character set to use when escaping.  Defaults to config value or 'UTF-8'
     * @return string Wrapped text
     */
    public static function h($text, $double = true, $charset = null) {
    	if (is_array($text)) {
    		$texts = array();
    		foreach ($text as $k => $t) {
    			$texts[$k] = self::h($t, $double, $charset);
    		}
    		return $texts;
    	} elseif (is_object($text)) {
    		if (method_exists($text, '__toString')) {
    			$text = (string) $text;
    		} else {
    			$text = '(object)' . get_class($text);
    		}
    	}
    
    	$defaultCharset = false;
    	if ($defaultCharset === false) {
    		$defaultCharset = config::get('charset');
    		if ($defaultCharset === null) {
    			$defaultCharset = 'UTF-8';
    		}
    	}
    	if (is_string($double)) {
    		$charset = $double;
    	}
    	return htmlspecialchars($text, ENT_QUOTES, ($charset) ? $charset : $defaultCharset, $double);
    }
    
    /**
     * Gets an environment variable from available sources, and provides emulation
     * for unsupported or inconsistent environment variables (i.e. DOCUMENT_ROOT on
     * IIS, or SCRIPT_NAME in CGI mode).  Also exposes some additional custom
     * environment information.
     *
     * @param  string $key Environment variable name.
     * @return string Environment variable setting.
     */
    public static function env($key) {
    	if ($key === 'HTTPS') {
    		if (isset($_SERVER['HTTPS'])) {
    			return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    		}
    		return (strpos(self::env('SCRIPT_URI'), 'https://') === 0);
    	}
    
    	if ($key === 'SCRIPT_NAME') {
    		if (self::env('CGI_MODE') && isset($_ENV['SCRIPT_URL'])) {
    			$key = 'SCRIPT_URL';
    		}
    	}
    
    	$val = null;
    	if (isset($_SERVER[$key])) {
    		$val = $_SERVER[$key];
    	} elseif (isset($_ENV[$key])) {
    		$val = $_ENV[$key];
    	} elseif (getenv($key) !== false) {
    		$val = getenv($key);
    	}
    
    	if ($key === 'REMOTE_ADDR' && $val === self::env('SERVER_ADDR')) {
    		$addr = self::env('HTTP_PC_REMOTE_ADDR');
    		if ($addr !== null) {
    			$val = $addr;
    		}
    	}
    
    	if ($val !== null) {
    		return $val;
    	}
    
    	switch ($key) {
    		case 'SCRIPT_FILENAME':
    			if (defined('SERVER_IIS') && SERVER_IIS === true) {
    				return str_replace('\\\\', '\\', env('PATH_TRANSLATED'));
    			}
    			break;
    		case 'DOCUMENT_ROOT':
    			$name = self::env('SCRIPT_NAME');
    			$filename = self::env('SCRIPT_FILENAME');
    			$offset = 0;
    			if (!strpos($name, '.php')) {
    				$offset = 4;
    			}
    			return substr($filename, 0, strlen($filename) - (strlen($name) + $offset));
    			break;
    		case 'PHP_SELF':
    			return str_replace(self::env('DOCUMENT_ROOT'), '', self::env('SCRIPT_FILENAME'));
    			break;
    		case 'CGI_MODE':
    			return (PHP_SAPI === 'cgi');
    			break;
    		case 'HTTP_BASE':
    			$host = self::env('HTTP_HOST');
    			$parts = explode('.', $host);
    			$count = count($parts);
    
    			if ($count === 1) {
    				return '.' . $host;
    			} elseif ($count === 2) {
    				return '.' . $host;
    			} elseif ($count === 3) {
    				$gTLD = array(
    					'aero',
    					'asia',
    					'biz',
    					'cat',
    					'com',
    					'coop',
    					'edu',
    					'gov',
    					'info',
    					'int',
    					'jobs',
    					'mil',
    					'mobi',
    					'museum',
    					'name',
    					'net',
    					'org',
    					'pro',
    					'tel',
    					'travel',
    					'xxx'
    				);
    				if (in_array($parts[1], $gTLD)) {
    					return '.' . $host;
    				}
    			}
    			array_shift($parts);
    			return '.' . implode('.', $parts);
    			break;
    	}
    	return null;
    }
    
    public static function ip(){
        if ( isset($_SERVER["REMOTE_ADDR"]) )    {
            return '' . $_SERVER["REMOTE_ADDR"] . '';
        } else if ( isset($_SERVER["HTTP_X_FORWARDED_FOR"]) )    {
            return '' . $_SERVER["HTTP_X_FORWARDED_FOR"] . '';
        } else if ( isset($_SERVER["HTTP_CLIENT_IP"]) )    {
            return '' . $_SERVER["HTTP_CLIENT_IP"] . '';
        } 
        return false;
    }
    
    public static function rand($num=10){
    	$characters = array(
    	"A","B","C","D","E","F","G","H","J","K","L","M",
    	"N","P","Q","R","S","T","U","V","W","X","Y","Z",
    	"1","2","3","4","5","6","7","8","9");
 
    	$keys = array();
    	 
    	while(count($keys) < $num) {
    	    $x = mt_rand(0, count($characters)-1);
    	    if(!in_array($x, $keys)) {
    	       $keys[] = $x;
    	    }
    	}
    	$random_chars = '';
    	foreach($keys as $key){
    	   $random_chars .= $characters[$key];
    	}
    	return $random_chars;
    }
    
    /* MySQL function */
    public static function dirty($sql){
        return str_replace("'","''",$sql);
    }
    
    public static function isLink($l){
        return strpos($l,'http')===0;
    } 
    
    public static function auto_increment($table_name, $field='id'){
        $result = db::query("SELECT MAX($field) as max FROM `$table_name`");
        return intval($result[0]->max)+1;
    }
    
    public static function auto_code($prefix,$id=1,$num=8){
        return $prefix.str_repeat("0",$num-strlen($id)).$id;
    }
    
    public static function check_double($x){
        $y = array_count_values($x);        
        foreach($y as $k=>$v){
            if($v>1){
                return true;
                break;
            }
        }        
        return false;
    } 
    
    public static function is_ssl($cf=false){
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'){
            if($cf) return true;
            $_SERVER['HTTPS'] = 'on';
        }
        if($cf) return false;
        return isset($_SERVER['HTTPS']);
    }
    
    public static function https(){
        return self::is_ssl()?'https':'http';
    }
    
    public static function is_ajax ()
    {
        $header = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? $_SERVER['HTTP_X_REQUESTED_WITH'] : null;
        return (strtolower($header) === strtolower('XMLHttpRequest'));
    }
    
    public static function mb_substr($string,$length){
        if(mb_strlen($string)<=$length)
            return $string;
            
        return mb_substr($string,0,$length).'...';
    }
}