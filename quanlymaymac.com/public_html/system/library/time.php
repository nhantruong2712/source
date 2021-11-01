<?php if(!defined('HTH')){die('External Access to File Denied');}

/**
 * SA Time Class
 *
 * @Author          Hoang Trong Hoi
 * @Copyright       2011
 */

class time
{
	/**
	 * Composes text using text if loaded
	 * 
	 * @param  string  $message    The message to compose
	 * @param  mixed   $component  A string or number to insert into the message
	 * @param  mixed   ...
	 * @return string  The composed and possible translated message
	 */
	protected static function compose($message)
	{
		$args = array_slice(func_get_args(), 1);
		
		if (class_exists('text', FALSE)) {
			return call_user_func_array(
				array('text', 'compose'),
				array($message, $args)
			);
		} else {
			return vsprintf($message, $args);
		}
	}
	
	
	/**
	 * A timestamp of the time
	 * 
	 * @var integer
	 */
	protected $time;
	
	
	/**
	 * Creates the time to represent, no timezone is allowed since times don't have timezones
	 * 
	 * @throws Exception  When `$time` is not a valid time
	 * 
	 * @param  time|object|string|integer $time  The time to represent, `NULL` is interpreted as now
	 * @return time
	 */
	public function __construct($time=NULL)
	{
		if ($time === NULL) {
			$timestamp = time();
		} elseif (is_numeric($time) && preg_match('#^-?\d+$#D', $time)) {
			$timestamp = (int) $time;
		} elseif (is_string($time) && in_array(strtoupper($time), array('CURRENT_TIMESTAMP', 'CURRENT_TIME'))) {
			$timestamp = time();
		} else {
			if (is_object($time) && is_callable(array($time, '__toString'))) {
				$time = $time->__toString();	
			} elseif (is_numeric($time) || is_object($time)) {
				$time = (string) $time;	
			}
			
			$time = timestamp::callUnformatCallback($time);
			
			$timestamp = strtotime($time);
		}
		
		$is_51    = core::checkVersion('5.1');
		$is_valid = ($is_51 && $timestamp !== FALSE) || (!$is_51 && $timestamp !== -1);
		
		if (!$is_valid) {
			throw new SAException(
				'The time specified, %s, does not appear to be a valid time',
				$time
			);
		}
		
		$this->time = strtotime(date('1970-01-01 H:i:s', $timestamp));
	}
	
	
	/**
	 * All requests that hit this method should be requests for callbacks
	 * 
	 * @internal
	 * 
	 * @param  string $method  The method to create a callback for
	 * @return callback  The callback for the method requested
	 */
	public function __get($method)
	{
		return array($this, $method);		
	}
	
	
	/**
	 * Returns this time in `'H:i:s'` format
	 * 
	 * @return string  The `'H:i:s'` format of this time
	 */
	public function __toString()
	{
		return date('H:i:s', $this->time);
	}
	
	
	/**
	 * Changes the time by the adjustment specified, only adjustments of `'hours'`, `'minutes'`, and `'seconds'` are allowed
	 * 
	 * @throws Exception  When `$adjustment` is not a valid relative time measurement
	 * 
	 * @param  string $adjustment  The adjustment to make
	 * @return time  The adjusted time
	 */
	public function adjust($adjustment)
	{
		$timestamp = strtotime($adjustment, $this->time);
		
		if ($timestamp === FALSE || $timestamp === -1) {
			throw new SAException(
				'The adjustment specified, %s, does not appear to be a valid relative time measurement',
				$adjustment
			);
		}
		
		return new time($timestamp);
	}
	
	
	/**
	 * If this time is equal to the time passed
	 * 
	 * @param  time|object|string|integer $other_time  The time to compare with, `NULL` is interpreted as today
	 * @return boolean  If this time is equal to the one passed
	 */
	public function eq($other_time=NULL)
	{
		$other_time = new time($other_time);
		return $this->time == $other_time->time;
	}
	
	
	/**
	 * Formats the time
	 * 
	 * @throws Exception  When a non-time formatting character is included in `$format`
	 * 
	 * @param  string $format  The [http://php.net/date date()] function compatible formatting string, or a format name from fTimestamp::defineFormat()
	 * @return string  The formatted time
	 */
	public function format($format)
	{
		$format = timestamp::translateFormat($format);
		
		$restricted_formats = 'cdDeFIjlLmMnNoOPrStTUwWyYzZ';
		if (preg_match('#(?!\\\\).[' . $restricted_formats . ']#', $format)) {
			throw new SAException(
				'The formatting string, %1$s, contains one of the following non-time formatting characters: %2$s',
				$format,
				join(', ', str_split($restricted_formats))
			);
		}
		
		return timestamp::callFormatCallback(date($format, $this->time));
	}
	
	
	/**
	 * Returns the approximate difference in time, discarding any unit of measure but the least specific.
	 * 
	 * The output will read like:
	 * 
	 *  - "This time is `{return value}` the provided one" when a time it passed
	 *  - "This time is `{return value}`" when no time is passed and comparing with the current time
	 * 
	 * Examples of output for a time passed might be:
	 * 
	 *  - `'5 minutes after'`
	 *  - `'2 hours before'`
	 *  - `'at the same time'`
	 * 
	 * Examples of output for no time passed might be:
	 * 
	 *  - `'5 minutes ago'`
	 *  - `'2 hours ago'`
	 *  - `'right now'`
	 * 
	 * You would never get the following output since it includes more than one unit of time measurement:
	 * 
	 *  - `'5 minutes and 28 seconds'`
	 *  - `'1 hour, 15 minutes'`
	 * 
	 * Values that are close to the next largest unit of measure will be rounded up:
	 * 
	 *  - `'55 minutes'` would be represented as `'1 hour'`, however `'45 minutes'` would not
	 * 
	 * @param  time|object|string|integer $other_time  The time to create the difference with, `NULL` is interpreted as now
	 * @param  boolean                     $simple      When `TRUE`, the returned value will only include the difference in the two times, but not `from now`, `ago`, `after` or `before`
	 * @param  boolean                     |$simple
	 * @return string  The fuzzy difference in time between the this time and the one provided
	 */
	public function getFuzzyDifference($other_time=NULL, $simple=FALSE)
	{
		if (is_bool($other_time)) {
			$simple     = $other_time;
			$other_time = NULL;
		}
		
		$relative_to_now = FALSE;
		if ($other_time === NULL) {
			$relative_to_now = TRUE;
		}
		$other_time = new fTime($other_time);
		
		$diff = $this->time - $other_time->time;
		
		if (abs($diff) < 10) {
			if ($relative_to_now) {
				return self::compose('right now');
			}
			return self::compose('at the same time');
		}
		
		static $break_points = array();
		if (!$break_points) {
			$break_points = array(
				/* 45 seconds  */
				45     => array(1,     self::compose('second'), self::compose('seconds')),
				/* 45 minutes  */
				2700   => array(60,    self::compose('minute'), self::compose('minutes')),
				/* 18 hours    */
				64800  => array(3600,  self::compose('hour'),   self::compose('hours')),
				/* 5 days      */
				432000 => array(86400, self::compose('day'),    self::compose('days'))
			);
		}
		
		foreach ($break_points as $break_point => $unit_info) {
			if (abs($diff) > $break_point) { continue; }
			
			$unit_diff = round(abs($diff)/$unit_info[0]);
			$units     = fGrammar::inflectOnQuantity($unit_diff, $unit_info[1], $unit_info[2]);
			break;
		}
		
		if ($simple) {
			return self::compose('%1$s %2$s', $unit_diff, $units);
		}
		
		if ($relative_to_now) {
			if ($diff > 0) {
				return self::compose('%1$s %2$s from now', $unit_diff, $units);
			}
			
			return self::compose('%1$s %2$s ago', $unit_diff, $units);
		}
		
		
		if ($diff > 0) {
			return self::compose('%1$s %2$s after', $unit_diff, $units);
		}
		
		return self::compose('%1$s %2$s before', $unit_diff, $units);
	}
	
	
	/**
	 * If this time is greater than the time passed
	 * 
	 * @param  time|object|string|integer $other_time  The time to compare with, `NULL` is interpreted as now
	 * @return boolean  If this time is greater than the one passed
	 */
	public function gt($other_time=NULL)
	{
		$other_time = new time($other_time);
		return $this->time > $other_time->time;
	}
	
	
	/**
	 * If this time is greater than or equal to the time passed
	 * 
	 * @param  time|object|string|integer $other_time  The time to compare with, `NULL` is interpreted as now
	 * @return boolean  If this time is greater than or equal to the one passed
	 */
	public function gte($other_time=NULL)
	{
		$other_time = new time($other_time);
		return $this->time >= $other_time->time;
	}
	
	
	/**
	 * If this time is less than the time passed
	 * 
	 * @param  time|object|string|integer $other_time  The time to compare with, `NULL` is interpreted as today
	 * @return boolean  If this time is less than the one passed
	 */
	public function lt($other_time=NULL)
	{
		$other_time = new time($other_time);
		return $this->time < $other_time->time;
	}
	
	
	/**
	 * If this time is less than or equal to the time passed
	 * 
	 * @param  time|object|string|integer $other_time  The time to compare with, `NULL` is interpreted as today
	 * @return boolean  If this time is less than or equal to the one passed
	 */
	public function lte($other_time=NULL)
	{
		$other_time = new time($other_time);
		return $this->time <= $other_time->time;
	}
	
	
	/**
	 * Modifies the current time, creating a new time object
	 * 
	 * The purpose of this method is to allow for easy creation of a time
	 * based on this time. Below are some examples of formats to
	 * modify the current time:
	 * 
	 *  - `'17:i:s'` to set the hour of the time to 5 PM
	 *  - 'H:00:00'` to set the time to the beginning of the current hour
	 * 
	 * @param  string $format  The current time will be formatted with this string, and the output used to create a new object
	 * @return time  The new time
	 */
	public function modify($format)
	{
	   return new time($this->format($format));
	}
    
    public static function totime($date,$pattern="/^(\d+)\/(\d+)\/(\d+) (\d+)\:(\d+)$/",$mktime=array(4,5,-1,2,1,3)){
        //vsprintf("%4\$d/%3\$d/%2\$d %5\$d:%6\$d",$date2);
        return preg_replace_callback(
            $pattern,
            create_function(
                '$x',
                'return mktime('.$mktime[0].'>0?$x['.$mktime[0].']:0,'.$mktime[1].'>0?$x['.$mktime[1].']:0,'.$mktime[2].'>0?$x['.$mktime[2].']:0,'.$mktime[3].'>0?$x['.$mktime[3].']:0,'.$mktime[4].'>0?$x['.$mktime[4].']:0,'.$mktime[5].'>0?$x['.$mktime[5].']:0);' //h i s m d y
            ),
            $date
        );
    }
}
//var_dump(time::totime('12/12/2020 12:12'));