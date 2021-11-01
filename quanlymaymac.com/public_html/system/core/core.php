<?php if(!defined('HTH')){die('External Access to File Denied');}

/**
 * SA Framework Core Class
 *
 * @Author          Hoang Trong Hoi
 * @Copyright       2011
 */

class core
{
    public static $theme;
    public $load;
    
	/**
	 * The nesting level of error capturing
	 *
	 * @var integer
	 */
	private static $captured_error_level = 0;

	/**
	 * A stack of regex to match errors to capture, one string per level
	 * 
	 * @var array
	 */
	private static $captured_error_regex = array();
	
	/**
	 * A stack of the types of errors to capture, one integer per level
	 * 
	 * @var array
	 */
	private static $captured_error_types = array();
	
	/**
	 * A stack of arrays of errors that have been captured, one array per level
	 * 
	 * @var array
	 */
	private static $captured_errors = array();

	/**
	 * A stack of the previous error handler, one callback per level
	 * 
	 * @var array
	 */
	private static $captured_errors_previous_handler = array();
    
    function __construct(){
        $this->load = new load;
    }
    
    /**
    * Performs a [http://php.net/call_user_func call_user_func()], while translating PHP 5.2 static callback syntax for PHP 5.1 and 5.0
    *
    * Parameters can be passed either as a single array of parameters or as
    * multiple parameters.
    *
    * {{{
    * #!php
    * // Passing multiple parameters in a normal fashion
    * core::call('Class::method', TRUE, 0, 'test');
    *
    * // Passing multiple parameters in a parameters array
    * core::call('Class::method', array(TRUE, 0, 'test'));
    * }}}
    *
    * To pass parameters by reference they must be assigned to an
    * array by reference and the function/method being called must accept those
    * parameters by reference. If either condition is not met, the parameter
    * will be passed by value.
    *
    * {{{
    * #!php
    * // Passing parameters by reference
    * core::call('Class::method', array(&$var1, &$var2));
    * }}}
    *
    * @param  callback $callback    The function or method to call
    * @param  array    $parameters  The parameters to pass to the function/method
    * @return mixed  The return value of the called function/method
    */
    public static function call($callback, $parameters=array())
    {
        // Fix PHP 5.0 and 5.1 static callback syntax
        if (is_string($callback) && strpos($callback, '::') !== FALSE) {
            $callback = explode('::', $callback);
        }
       
        $parameters = array_slice(func_get_args(), 1);
        if (sizeof($parameters) == 1 && is_array($parameters[0])) {
            $parameters = $parameters[0];
        }
       
        return call_user_func_array($callback, $parameters);
    }
   
   
    /**
    * Translates a Class::method style static method callback to array style for compatibility with PHP 5.0 and 5.1 and built-in PHP functions
    *
    * @param  callback $callback  The callback to translate
    * @return array  The translated callback
    */
    public static function callback($callback)
    {
        if (is_string($callback) && strpos($callback, '::') !== FALSE) {
            return explode('::', $callback);
        }
       
        return $callback;
    }    
    
    /**
    * Returns is the current OS is one of the OSes passed as a parameter
    *
    * Valid OS strings are:
    *  - `'linux'`
    *  - `'aix'`
    *  - `'bsd'`
    *  - `'freebsd'`
    *  - `'netbsd'`
    *  - `'openbsd'`
    *  - `'osx'`
    *  - `'solaris'`
    *  - `'windows'`
    *
    * @param  string $os  The operating system to check - see method description for valid OSes
    * @param  string ...
    * @return boolean  If the current OS is included in the list of OSes passed as parameters
    */
    public static function checkOS($os)
    {
        $oses = func_get_args();
       
        $valid_oses = array('linux', 'aix', 'bsd', 'freebsd', 'openbsd', 'netbsd', 'osx', 'solaris', 'windows');
       
        if ($invalid_oses = array_diff($oses, $valid_oses)) {
            throw new SAException(
                'One or more of the OSes specified, %$1s, is invalid. Must be one of: %2$s.',
                join(' ', $invalid_oses),
                join(', ', $valid_oses)
            );
        }
       
        $uname = php_uname('s');
       
        if (stripos($uname, 'linux') !== FALSE) {
            return in_array('linux', $oses);
       
        } elseif (stripos($uname, 'aix') !== FALSE) {
            return in_array('aix', $oses);
       
        } elseif (stripos($uname, 'netbsd') !== FALSE) {
            return in_array('netbsd', $oses) || in_array('bsd', $oses);
       
        } elseif (stripos($uname, 'openbsd') !== FALSE) {
            return in_array('openbsd', $oses) || in_array('bsd', $oses);
       
        } elseif (stripos($uname, 'freebsd') !== FALSE) {
            return in_array('freebsd', $oses) || in_array('bsd', $oses);
       
        } elseif (stripos($uname, 'solaris') !== FALSE || stripos($uname, 'sunos') !== FALSE) {
            return in_array('solaris', $oses);
       
        } elseif (stripos($uname, 'windows') !== FALSE) {
            return in_array('windows', $oses);
       
        } elseif (stripos($uname, 'darwin') !== FALSE) {
            return in_array('osx', $oses);
        }
       
        throw new SAException('Unable to determine the current OS');
    }
   
   
    /**
    * Checks to see if the running version of PHP is greater or equal to the version passed
    *
    * @return boolean  If the running version of PHP is greater or equal to the version passed
    */
    public static function checkVersion($version)
    {
        static $running_version = NULL;
       
        if ($running_version === NULL) {
            $running_version = preg_replace(
                '#^(\d+\.\d+\.\d+).*$#D',
                '\1',
                PHP_VERSION
            );
        }
       
        return version_compare($running_version, $version, '>=');
    }    
    
    /**
    * Creates a string representation of any variable using predefined strings for booleans, `NULL` and empty strings
    *
    * The string output format of this method is very similar to the output of
    * [http://php.net/print_r print_r()] except that the following values
    * are represented as special strings:
    *   
    *  - `TRUE`: `'{true}'`
    *  - `FALSE`: `'{false}'`
    *  - `NULL`: `'{null}'`
    *  - `''`: `'{empty_string}'`
    *
    * @param  mixed $data  The value to dump
    * @return string  The string representation of the value
    */
    public static function dump($data)
    {
        if (is_bool($data)) {
            return ($data) ? '{true}' : '{false}';
       
        } elseif (is_null($data)) {
            return '{null}';
       
        } elseif ($data === '') {
            return '{empty_string}';
       
        } elseif (is_array($data) || is_object($data)) {
           
            ob_start();
            var_dump($data);
            $output = ob_get_contents();
            ob_end_clean();
           
            // Make the var dump more like a print_r
            $output = preg_replace('#=>\n(  )+(?=[a-zA-Z]|&)#m', ' => ', $output);
            $output = str_replace('string(0) ""', '{empty_string}', $output);
            $output = preg_replace('#=> (&)?NULL#', '=> \1{null}', $output);
            $output = preg_replace('#=> (&)?bool\((false|true)\)#', '=> \1{\2}', $output);
            $output = preg_replace('#(?<=^|\] => )(?:float|int)\((-?\d+(?:.\d+)?)\)#', '\1', $output);
            $output = preg_replace('#string\(\d+\) "#', '', $output);
            $output = preg_replace('#"(\n(  )*)(?=\[|\})#', '\1', $output);
            $output = preg_replace('#((?:  )+)\["(.*?)"\]#', '\1[\2]', $output);
            $output = preg_replace('#(?:&)?array\(\d+\) \{\n((?:  )*)((?:  )(?=\[)|(?=\}))#', "Array\n\\1(\n\\1\\2", $output);
            $output = preg_replace('/object\((\w+)\)#\d+ \(\d+\) {\n((?:  )*)((?:  )(?=\[)|(?=\}))/', "\\1 Object\n\\2(\n\\2\\3", $output);
            $output = preg_replace('#^((?:  )+)}(?=\n|$)#m', "\\1)\n", $output);
            $output = substr($output, 0, -2) . ')';
           
            // Fix indenting issues with the var dump output
            $output_lines = explode("\n", $output);
            $new_output = array();
            $stack = 0;
            foreach ($output_lines as $line) {
                if (preg_match('#^((?:  )*)([^ ])#', $line, $match)) {
                    $spaces = strlen($match[1]);
                    if ($spaces && $match[2] == '(') {
                        $stack += 1;
                    }
                    $new_output[] = str_pad('', ($spaces)+(4*$stack)) . $line;
                    if ($spaces && $match[2] == ')') {
                        $stack -= 1;
                    }
                } else {
                    $new_output[] = str_pad('', ($spaces)+(4*$stack)) . $line;
                }
            }
           
            return join("\n", $new_output);
           
        } else {
            return (string) $data;
        }
    }

	/**
	 * Temporarily enables capturing error messages 
	 * 
	 * @param  integer $types  The error types to capture - this should be as specific as possible - defaults to all (E_ALL | E_STRICT)
	 * @param  string  $regex  A PCRE regex to match against the error message
	 * @return void
	 */
	static public function startErrorCapture($types=NULL, $regex=NULL)
	{
		if ($types === NULL) {
			$types = E_ALL | E_STRICT;
		}

		self::$captured_error_level++;

		self::$captured_error_regex[self::$captured_error_level]             = $regex;
		self::$captured_error_types[self::$captured_error_level]             = $types;
		self::$captured_errors[self::$captured_error_level]                  = array();
		self::$captured_errors_previous_handler[self::$captured_error_level] = set_error_handler(self::callback(self::handleError));
	}
	
	
	/**
	 * Stops capturing error messages, returning all that have been captured
	 * 
	 * @param  string $regex  A PCRE regex to filter messages by
	 * @return array  The captured error messages
	 */
	static public function stopErrorCapture($regex=NULL)
	{
		$captures = self::$captured_errors[self::$captured_error_level];

		self::$captured_error_level--;

		self::$captured_error_regex             = array_slice(self::$captured_error_regex,             0, self::$captured_error_level, TRUE);
		self::$captured_error_types             = array_slice(self::$captured_error_types,             0, self::$captured_error_level, TRUE);
		self::$captured_errors                  = array_slice(self::$captured_errors,                  0, self::$captured_error_level, TRUE);
		self::$captured_errors_previous_handler = array_slice(self::$captured_errors_previous_handler, 0, self::$captured_error_level, TRUE);
		
		restore_error_handler();
		
		if ($regex) {
			$new_captures = array();
			foreach ($captures as $capture) {
				if (!preg_match($regex, $capture['string'])) { continue; }
				$new_captures[] = $capture;
			}
			$captures = $new_captures;
		}
		
		return $captures;
	}
	/**
	 * Creates a nicely formatted backtrace to the the point where this method is called
	 * 
	 * @param  integer $remove_lines  The number of trailing lines to remove from the backtrace
	 * @param  array   $backtrace     A backtrace from [http://php.net/backtrace `debug_backtrace()`] to format - this is not usually required or desired
	 * @return string  The formatted backtrace
	 */
	public static function backtrace($remove_lines=0, $backtrace=NULL)
	{
		if ($remove_lines !== NULL && !is_numeric($remove_lines)) {
			$remove_lines = 0;
		}
		
		settype($remove_lines, 'integer');
		
		$doc_root  = realpath($_SERVER['DOCUMENT_ROOT']);
		$doc_root .= (substr($doc_root, -1) != DIRECTORY_SEPARATOR) ? DIRECTORY_SEPARATOR : '';
		
		if ($backtrace === NULL) {
			$backtrace = debug_backtrace();
		}
		
		while ($remove_lines > 0) {
			array_shift($backtrace);
			$remove_lines--;
		}
		
		$backtrace = array_reverse($backtrace);
		
		$bt_string = '';
		$i = 0;
		foreach ($backtrace as $call) {
			if ($i) {
				$bt_string .= "\n";
			}
			if (isset($call['file'])) {
				$bt_string .= str_replace($doc_root, '{doc_root}' . DIRECTORY_SEPARATOR, $call['file']) . '(' . $call['line'] . '): ';
			} else {
				$bt_string .= '[internal function]: ';
			}
			if (isset($call['class'])) {
				$bt_string .= $call['class'] . $call['type'];
			}
			if (isset($call['class']) || isset($call['function'])) {
				$bt_string .= $call['function'] . '(';
					$j = 0;
					if (!isset($call['args'])) {
						$call['args'] = array();
					}
					foreach ($call['args'] as $arg) {
						if ($j) {
							$bt_string .= ', ';
						}
						if (is_bool($arg)) {
							$bt_string .= ($arg) ? 'true' : 'false';
						} elseif (is_null($arg)) {
							$bt_string .= 'NULL';
						} elseif (is_array($arg)) {
							$bt_string .= 'Array';
						} elseif (is_object($arg)) {
							$bt_string .= 'Object(' . get_class($arg) . ')';
						} elseif (is_string($arg)) {
							// Shorten the UTF-8 string if it is too long
							if (strlen(utf8_decode($arg)) > 18) {
								// If we can't match as unicode, try single byte
								if (!preg_match('#^(.{0,15})#us', $arg, $short_arg)) {
									preg_match('#^(.{0,15})#s', $arg, $short_arg);
								}
								$arg  = $short_arg[0] . '...';
							}
							$bt_string .= "'" . $arg . "'";
						} else {
							$bt_string .= (string) $arg;
						}
						$j++;
					}
				$bt_string .= ')';
			}
			$i++;
		}
		
		return $bt_string;
	}        
}
?>