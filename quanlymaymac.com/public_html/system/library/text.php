<?php if(!defined('HTH')){die('External Access to File Denied');}

/**
 * SA Text Class
 *
 * @Author          Hoang Trong Hoi
 * @Copyright       2011
 */

class text
{
 
	/**
	 * Callbacks for when messages are composed
	 * 
	 * @var array
	 */
	private static $compose_callbacks = array(
		'pre'  => array(),
		'post' => array()
	);
	
	
	/**
	 * Performs an [http://php.net/sprintf sprintf()] on a string and provides a hook for modifications such as internationalization
	 * 
	 * @param  string  $message    A message to compose
	 * @param  mixed   $component  A string or number to insert into the message
	 * @param  mixed   ...
	 * @return string  The composed message
	 */
	public static function compose($message)
	{
		if (self::$compose_callbacks) {
			foreach (self::$compose_callbacks['pre'] as $callback) {
				$message = call_user_func($callback, $message);
			}
		}
		
		$components = array_slice(func_get_args(), 1);
		
		// Handles components passed as an array
		if (sizeof($components) == 1 && is_array($components[0])) {
			$components = $components[0];	
		}
		
		$message = vsprintf($message, $components);
		
		if (self::$compose_callbacks) {
			foreach (self::$compose_callbacks['post'] as $callback) {
				$message = call_user_func($callback, $message);
			}
		}
		
		return $message;
	}
	
	
	/**
	 * Adds a callback for when a message is created using ::compose()
	 * 
	 * The primary purpose of these callbacks is for internationalization of
	 * error messaging in Flourish. The callback should accept a single
	 * parameter, the message being composed and should return the message
	 * with any modifications.
	 * 
	 * The timing parameter controls if the callback happens before or after
	 * the actual composition takes place, which is simply a call to
	 * [http://php.net/sprintf sprintf()]. Thus the message passed `'pre'`
	 * will always be exactly the same, while the message `'post'` will include
	 * the interpolated variables. Because of this, most of the time the `'pre'`
	 * timing should be chosen.
	 * 
	 * @param  string   $timing    When the callback should be executed - `'pre'` or `'post'` performing the actual composition
	 * @param  callback $callback  The callback
	 * @return void
	 */
	public static function registerComposeCallback($timing, $callback)
	{
		$valid_timings = array('pre', 'post');
		if (!in_array($timing, $valid_timings)) {
			throw new SAException(
				'The timing specified, %1$s, is not a valid timing. Must be one of: %2$s.',
				$timing,
				join(', ', $valid_timings)	
			);
		}
		
		if (is_string($callback) && strpos($callback, '::') !== FALSE) {
			$callback = explode('::', $callback);	
		}
		
		self::$compose_callbacks[$timing][] = $callback;
	}
	
	
	/**
	 * Resets the configuration of the class
	 * 
	 * @internal
	 * 
	 * @return void
	 */
	public static function reset()
	{
		self::$compose_callbacks = array(
			'pre'  => array(),
			'post' => array()
		);
	}
}