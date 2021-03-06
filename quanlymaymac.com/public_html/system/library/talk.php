<?php if(!defined('HTH')){die('External Access to File Denied');}

/**
 * SA Talk Library For SA Framework
 *
 * @author          Hoang Trong Hoi
 * @copyright       2011
 */

class talk
{
	private static $is_server;
	
	
	// Request
	// ---------------------------------------------------------------------------
	public static function request($url,$postdata=array())
	{
		$post = '__talk=1';
		
		foreach($postdata as $field => $value)
		{
			$post .= "&$field=$value";
		}
		
		$ch = curl_init($url);
		curl_setopt($ch,CURLOPT_POST,true);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		
		return curl_exec($ch);
	}
	
	
	// Is It A Talk Request?
	// ---------------------------------------------------------------------------
	public static function is_request()
	{
		return self::$is_server;
	}
	
	
	// Respond
	// ---------------------------------------------------------------------------
	public static function respond($response)
	{
		if(self::is_request())
		{
			ob_clean();
			echo $response;
			ob_end_flush();
		}
	}
}


talk::$is_server = (input::post('__talk')) ? TRUE : FALSE;