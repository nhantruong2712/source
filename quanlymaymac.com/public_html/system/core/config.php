<?php if(!defined('HTH')){die('External Access to File Denied');}

/**
 * SA Framework Config Class
 *
 * @Author          Hoang Trong Hoi
 * @Copyright       2011
 */
 
class config
{
	private static $x = array();
 
	// Set
	// ---------------------------------------------------------------------------
	public static function set($name,$val)
	{
		self::$x[$name] = $val;
	}
 
	// Get
	// ---------------------------------------------------------------------------
	public static function get($name)
	{
		if(isset(self::$x[$name]))
		{
			return(self::$x[$name]);
		}
		else
		{
			return FALSE;
		}
	}
 
	// Remove
	// ---------------------------------------------------------------------------
	public static function remove($name)
	{
		if(isset(self::$x[$name]))
		{
			unset(self::$x[$name]);
		}
	}
 
	// Rename
	// ---------------------------------------------------------------------------
	public static function rename($old,$new)
	{
		self::$x[$new] = self::$x[$old];
		unset(self::$x[$old]);
	}
}