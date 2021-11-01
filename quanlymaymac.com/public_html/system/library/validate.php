<?php if(!defined('HTH')){die('External Access to File Denied');}

/**
 * SA Framework Validation Helper
 *
 * @Author          Hoang Trong Hoi
 * @Copyright       2011
 */

class validate
{
	// Username
	// ---------------------------------------------------------------------------
	public static function username($username)
	{
		return preg_match('/^([\-_ a-z0-9]+)$/is',$username);
	}
	
	
	// Name
	// ---------------------------------------------------------------------------
	public static function name($name)
	{
		return preg_match('/^([ a-z]+)$/is',$name);
	}
	
	
	// Number
	// ---------------------------------------------------------------------------
	public static function number($number)
	{
		return preg_match('/^([\.0-9]+)$/is',$number);
	}
	
	
	// Int
	// ---------------------------------------------------------------------------
	public static function int($int)
	{
		return preg_match('/^([0-9]+)$/is',$int);
	}
	
	
	// Range
	// ---------------------------------------------------------------------------
	public static function range($low,$high,$number)
	{
		return ($low <= $number AND $high >= $number);
	}
	
	
	// Email Address
	// ---------------------------------------------------------------------------
	public static function email($email)
	{
		return preg_match('/^([_\.a-z0-9]{3,})@([\-_\.a-z0-9]{3,})\.([a-z]{2,})$/is',$email);
	}

    // Length
    // ---------------------------------------------------------------------------
    public static function length($low,$high,$number)
    {
        return self::range($low,$high,strlen($number));
    }	
	
	// Phone Number
	// ---------------------------------------------------------------------------
	public static function phone($phone,$strict=false)
	{
		if(!$strict)
		{
			$phone = preg_replace('/([ \(\)\-]+)/','',$phone);
		}
		
		return preg_match('/^([0-9]{10})$/',$phone);
	}
    
	/**
	 * Checks the value for a-z & A-Z.
	 *
	 * @return	bool			true if the string is alphabetical, false if not.
	 * @param	string $value	The string to validate.
	 */
	public static function alphabetical($value)
	{
		return ctype_alpha((string) $value);
	}


	/**
	 * Checks the value for letters & numbers without spaces.
	 *
	 * @return	bool			true if the string is alphanumeric, false if not.
	 * @param	string $value	The string to validate.
	 */
	public static function alphanumeric($value)
	{
		return ctype_alnum((string) $value);
	}
	/**
	 * Checks the value for a string wihout control characters (ASCII 0 - 31), spaces are allowed.
	 *
	 * @return	bool			True if the value is a string, false if not.
	 * @param	string $value	The value to validate.
	 */
	public static function string($value)
	{
		return (bool) preg_match('/^[^\x-\x1F]+$/', (string) $value);
	}


	/**
	 * Checks the value for a valid URL.
	 *
	 * @return	bool			True if the value is a valid URL, false if not.
	 * @param	string $value	The value to validate.
	 */
	public static function url($value)
	{
		return (bool) preg_match('_^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)(?:\.(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)*(?:\.(?:[a-z\x{00a1}-\x{ffff}]{2,})))(?::\d{2,5})?(?:/[^\s]*)?$_iuS', (string) $value);
	}


	/**
	 * Validates a value against a regular expression.
	 *
	 * @return	bool				True if the given string is valid against the regular expression, false if not.
	 * @param	string $regexp		The regular expression to use.
	 * @param	string $value		The value to validate.
	 */
	public static function againstregexp($regexp, $value)
	{
		// redefine vars
		$regexp = (string) $regexp;

		// invalid regexp
		if(!self::regexp($regexp)) throw new SAException('The provided regex pattern "' . $regexp . '" is not valid');

		// validate
		return (bool) (@preg_match($regexp, (string) $value));
	}   
    
	/**
	 * Checks if the given regex statement is valid.
	 *
	 * @return	bool				True if the given string is a valid regular expression, false if not.
	 * @param	string $regexp		The value to validate.
	 */
	public static function regexp($regexp)
	{
		// dummy string
		$dummy = 'SA framework is growing every day';

		// validate
		return (@preg_match((string) $regexp, $dummy) !== false);
	}
     
}