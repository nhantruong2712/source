<?php if(!defined('HTH')){die('External Access to File Denied');}

/**
 * SA Framework Router Class
 *
 * @Author          Hoang Trong Hoi
 * @Copyright       2011
 */

class route
{
	private static $route = array();
	private static $current = array();
	
	
	// Validate
	// ---------------------------------------------------------------------------
	public static function valid($r)
	{
		foreach($r['segments'] as $segment)
		{
			if(!preg_match(ALLOWED_CHARS,$segment))
			{
				return FALSE;
			}
		}
		
		return TRUE;
	}
	
	
	// Set
	// ---------------------------------------------------------------------------
	public static function set($url,$r)
	{
		self::$route[$url] = $r;
	}
	
	
	// Get
	// ---------------------------------------------------------------------------
	public static function get($url)
	{
		$url = preg_replace('/^(\/)/','',$url);
		$new_url = $url;
		
		// Static routes
		if(!empty(self::$route[$url]))
		{
			$new_url = self::$route[$url];
		}
		
		// Regex routes
		$route_keys = array_keys(self::$route);
		
		foreach($route_keys as $okey)
		{

			$key = ('/^'.str_replace('/','\\/',$okey).'$/');
			
			if(preg_match($key,$url))
			{

				if(!is_array(self::$route[$okey]))
				{
					$new_url = preg_replace($key,self::$route[$okey],$url);
				}
				else
				{
					/* Run regex replace on keys */
					$new_url = self::$route[$okey];
					
					// Controller
					if(isset($new_url['controller']))
					{
						$new_url['controller'] = preg_replace($key,$new_url['controller'],$url);
					}
					
					// Function
					if(isset($new_url['function']))
					{
						$new_url['function'] = preg_replace($key,$new_url['function'],$url);
					}
					
					// Arguments
					if(isset($new_url['arguments']))
					{
						$x = 0;
						while(isset($new_url['arguments'][$x]))
						{
							$new_url['arguments'][$x] = preg_replace($key,$new_url['arguments'][$x],$url);
							$x += 1;
						}
					}
				}
			}
			
		}
		
		// If URL is empty use default route
		if(empty($new_url) OR $new_url == '/')
		{
			$new_url = self::$route['default_route'];
		}
        
        // Turn into array
        if (!is_array($new_url)) {
            // Remove the /index.php/ at the beginning
            //$new_url = preg_replace('/^(\/)/','',$url);
            $tmp_url = explode('/', $new_url);

            // Controller
            $directory = array();
            //Base controller directory
            $controller = APPLICATION . '/' . config::get('folder_controllers');
            
            //Check whether segment is a sub directory or a controller
            //For example http://localhost/framework/subfolder1/subfolder11/anual/recap/2011/
            //http://localhost/framework = root url, anual = controller file, recap = function, 2011 = argument
            for ($i = 0; $i < $segment = count($tmp_url); $i++) {
                $controller .= '/' . $tmp_url[$i];
                if (is_dir($controller)) {
                    $directory[] = $tmp_url[$i];
                } else {
                    //Break soon if it's not directory. We're sure the segment is controller.
                    break;
                }
            }
            
            if (!empty($directory)) {
                $directory[] = isset($tmp_url[$i])?$tmp_url[$i]:false; //Add directory with controller name
                $controller = implode('/', $directory);
            } else {
                $controller = $tmp_url[0];
            }
            
            $controller = preg_replace("/\/$/","/index",$controller);
            
            $function = 'index'; 
             
            if(!file_exists(APPLICATION . '/' . config::get('folder_controllers').'/'.$controller.".php")){
                $function = preg_replace("/^.*?\/([^\/]+)\/?$/","$1",$controller); 
                $controller = preg_replace("/\/[^\/]+\/?$/","/index",$controller);
                
                
            }
 
            $new_url = array('controller' => $controller, 'function' => $function, 'arguments' => array(), 'string' => $new_url, 'segments' => $tmp_url);
    
            // Function
            $controller_class = explode('/',$new_url['controller']);
		    $controller_class = end($controller_class)."_controller";
            load::controller($controller);
            $method_exists = false;
            $count = 1;
 
            if (!empty($tmp_url[$i+1]) && $function == 'index' && ($method_exists = method_exists(new $controller_class,$tmp_url[$i+1]))) {
                $new_url['function'] = $tmp_url[$i+1];
                $count = 2;
            }elseif(empty($tmp_url[$i]) && $function <> 'index' && !method_exists(new $controller_class,$tmp_url[$i]) ){
                $function = $new_url['function'] = 'index';
            }elseif(isset($tmp_url[$i]) && !method_exists(new $controller_class,$tmp_url[$i]) && $function != 'index'){
                $function = $new_url['function'] = 'index';
                $count = 0;
            }
 
            $x = $i+$count;
            while (isset($tmp_url[$x])) {
                $new_url['arguments'][] = $tmp_url[$x];
                $x += 1;
            }
             
        }
		
		// If already array
		else
		{
			// Add missing keys
			if(!isset($new_url['function']))
			{
				$new_url['function'] = 'index';
			}
			
			if(!isset($new_url['arguments']))
			{
				$new_url['arguments'] = array();
			}
 
			// Build string key for URL array
			// Controller
			$s = $new_url['controller'];
			
			// Function
			if(isset($new_url['function']))
			{
				$s .= "/{$new_url['function']}";
			}
			
			// Arguments
			foreach($new_url['arguments'] as $arg)
			{
				$s .= "/$arg";
			}
			
			$new_url['string'] = $s;
 
			// Add segments key
			$new_url['segments'] = explode('/',$new_url['string']);
		}
 
		// Controller class
		$new_url['controller_class'] = explode('/',$new_url['controller']);
		$new_url['controller_class'] = end($new_url['controller_class']);
 
		self::$current = $new_url;
 
		return $new_url;
	}
 
	// Controller
	// ---------------------------------------------------------------------------
	public static function controller($path=FALSE)
	{
		return ($path) ? self::$current['controller'] : self::$current['controller_class'];
	}
    
    public static function folder_controller(){
        $res = preg_replace("/^([\w\_\-]+)\/.*?$/","$1",self::controller(true));
        return ($res=='index')?'.':$res;
    }
 
	// Method
	// ---------------------------------------------------------------------------
	public static function method()
	{
		return self::$current['function'];
	}
 
	// Arguments
	// ---------------------------------------------------------------------------
	public static function arguments()
	{
		return self::$current['arguments'];
	}
 
    /**
     * Convert array (controller, function, arguments, string, segments, controler_class) to SA-URL
     * or convert SA-URL
     * or convert title to SA-URL
     */
	public static function url($url = null, $full = false) {
		 if(is_array($url)){
		      $res = $url['controller']."/".$url['function']."/".implode("/",$url['arguments'])."/";
		 }elseif(preg_match("/\/$/",$url)){
		      $res = $url;
		 }else{
		      $res = grammar::humanize($url)."/";
		 }
         return ($full?((!empty($_SERVER['HTTPS']))?BASE_URL_SECURE:BASE_URL):"").$res;
	}    
}