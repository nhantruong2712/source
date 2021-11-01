<?php if(!defined('HTH')){die('External Access to File Denied');}

/**
 * SA Framework URL Library
 *
 * @Author          Hoang Trong Hoi
 * @Copyright       2011
 */

class url
{
	// Base URL
	// ---------------------------------------------------------------------------
	public static function base($ShowIndex = FALSE)
	{
		if($ShowIndex AND !MOD_REWRITE)
		{
			// Include "index.php"
   		    if (!empty($_SERVER['HTTPS'])) {
   		       return(BASE_URL_SECURE.'index.php/'); 
            } else {
			   return(BASE_URL.'index.php/');
			}
		}
		else
		{
			// Don't include "index.php"
   		    if (!empty($_SERVER['HTTPS'])) {
   		       return(BASE_URL_SECURE); 
 		    } else {
			   return(BASE_URL);
			}
		}
	}
	
	
	// Page URL
	// ---------------------------------------------------------------------------
	public static function page($path = FALSE)
	{
		if(MOD_REWRITE)
		{
			return(url::base().$path);
		}
		else
		{
			return(url::base(TRUE).$path);
		}
	}
	
	
	// Redirect
	// ---------------------------------------------------------------------------
	public static function redirect($url = '')
	{
		header('Location: '.url::base(TRUE).$url);
		exit;
	}
    
	/**
	 * Returns the requested URL, does no include the domain name or query string
	 * 
	 * This will return the original URL requested by the user - ignores all
	 * rewrites.
	 * 
	 * @return string  The requested URL without the query string
	 */
	public static function get()
	{
		return preg_replace('#\?.*$#D', '', $_SERVER['REQUEST_URI']);
	}
	
	
	/**
	 * Returns the current domain name, with protcol prefix. Port will be included if not 80 for HTTP or 443 for HTTPS.
	 * 
	 * @return string  The current domain name, prefixed by `http://` or `https://`
	 */
	public static function getDomain()
	{
		$port = (isset($_SERVER['SERVER_PORT'])) ? $_SERVER['SERVER_PORT'] : NULL;
		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
			return 'https://' . $_SERVER['SERVER_NAME'] . ($port && $port != 443 ? ':' . $port : '');
		} else {
			return 'http://' . $_SERVER['SERVER_NAME'] . ($port && $port != 80 ? ':' . $port : '');
		}
	}
	
	
	/**
	 * Returns the current query string, does not include parameters added by rewrites
	 * 
	 * @return string  The query string
	 */
	public static function getQueryString()
	{
		return preg_replace('#^[^?]*\??#', '', $_SERVER['REQUEST_URI']);
	}
	
	
	/**
	 * Returns the current URL including query string, but without domain name - does not include query string parameters from rewrites
	 * 
	 * @return string  The URL with query string
	 */
	public static function getWithQueryString()
	{
		return $_SERVER['REQUEST_URI'];
	}
	
	
	/**
	 * Changes a string into a URL-friendly string
	 * 
	 * @param  string   $string      The string to convert
	 * @param  integer  $max_length  The maximum length of the friendly URL
	 * @param  string   $delimiter   The delimiter to use between words, defaults to `_`
	 * @param  string   |$string
	 * @param  string   |$delimiter
	 * @return string  The URL-friendly version of the string
	 */
	public static function makeFriendly($string, $max_length=NULL, $delimiter=NULL)
	{
		// This allows omitting the max length, but including a delimiter
		if ($max_length && !is_numeric($max_length)) {
			$delimiter  = $max_length;
			$max_length = NULL;
		}

		$string = html::decode(utf8::ascii($string));
		$string = strtolower(trim($string));
		$string = str_replace("'", '', $string);

		if (!strlen($delimiter)) {
			$delimiter = '_';
		}

		$delimiter_replacement = strtr($delimiter, array('\\' => '\\\\', '$' => '\\$'));
		$delimiter_regex       = preg_quote($delimiter, '#');

		$string = preg_replace('#[^a-z0-9\-_]+#', $delimiter_replacement, $string);
		$string = preg_replace('#' . $delimiter_regex . '{2,}#', $delimiter_replacement, $string);
		$string = preg_replace('#_-_#', '-', $string);
		$string = preg_replace('#(^' . $delimiter_regex . '+|' . $delimiter_regex . '+$)#D', '', $string);
		
		$length = strlen($string);
		if ($max_length && $length > $max_length) {
			$last_pos = strrpos($string, $delimiter, ($length - $max_length - 1) * -1);
			if ($last_pos < ceil($max_length / 2)) {
				$last_pos = $max_length;
			}
			$string = substr($string, 0, $last_pos);
		}
		
		return $string;
	}
    
	/**
	 * Removes one or more parameters from the query string
	 * 
	 * This method uses the query string from the original URL and will not
	 * contain any parameters that are from rewrites.
	 * 
	 * @param  string $parameter  A parameter to remove from the query string
	 * @param  string ...
	 * @return string  The query string with the parameter(s) specified removed, first character is `?`
	 */
	public static function removeFromQueryString($parameter)
	{
		$parameters = func_get_args();
		
		parse_str(self::getQueryString(), $qs_array);
		if (get_magic_quotes_gpc()) {
			$qs_array = array_map('stripslashes', $qs_array);
		}
		
		foreach ($parameters as $parameter) {
			unset($qs_array[$parameter]);
		}
		
		return '?' . http_build_query($qs_array, '', '&');
	}
	
	
	/**
	 * Replaces a value in the query string
	 * 
	 * This method uses the query string from the original URL and will not
	 * contain any parameters that are from rewrites.
	 * 
	 * @param  string|array  $parameter  The query string parameter
	 * @param  string|array  $value      The value to set the parameter to
	 * @return string  The full query string with the parameter replaced, first char is `?`
	 */
	public static function replaceInQueryString($parameter, $value)
	{
		parse_str(self::getQueryString(), $qs_array);
		if (get_magic_quotes_gpc()) {
			$qs_array = array_map('stripslashes', $qs_array);
		}
		
		settype($parameter, 'array');
		settype($value, 'array');
		
		if (sizeof($parameter) != sizeof($value)) {
			throw new SAException(
				"There are a different number of parameters and values.\nParameters:\n%1\$s\nValues\n%2\$s",
				$parameter,
				$value
			);
		}
		
		for ($i=0; $i<sizeof($parameter); $i++) {
			$qs_array[$parameter[$i]] = $value[$i];
		}
		
		return '?' . http_build_query($qs_array, '', '&');
	}
	
    public static function regexp($regexp,$uri='',&$matches=false){
        if(!$uri) $uri = $_SERVER['REQUEST_URI'];
        if(!preg_match("/^(.).*>\\1$/",$regexp)) $regexp = "/$regexp/";
        return preg_match($regexp,$uri,$matches);
    }   
}