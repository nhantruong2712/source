<?php if(!defined('HTH')){die('External Access to File Denied');}

/**
 * Benchmark Library For SA Framework
 *
 * @author          Hoang Trong Hoi
 * @copyright       2011
 */

class bench
{
	public static $markers = array();
	
	
	// Mark
	// ---------------------------------------------------------------------------
	public static function mark($name)
	{
		self::$markers[$name] = microtime();
	}
	
	
	// Time
	// ---------------------------------------------------------------------------
	public static function time($mark1,$mark2)
	{
		return self::$markers[$mark2] - self::$markers[$mark1];
	}
	
	
	// Clear
	// ---------------------------------------------------------------------------
	public static function clear()
	{
		self::$markers = array();
	}
}
?>