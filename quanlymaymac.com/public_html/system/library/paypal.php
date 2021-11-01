<?php if(!defined('HTH')){die('External Access to File Denied');}

/**
 * PayPal Library For SA Framework
 *
 * @author          Hoang Trong Hoi
 * @copyright       2011
 */
 
class paypal
{
	public $error = false;
	public $url = 'https://www.paypal.com/cgi-bin/webscr';
    public $item = false;
    public static $paypal_id = false;
    public static $return = false;
    public static $cancel_return = false;
    public static $notify_url = false;
	
	public $_valid = null;
	public $_data = '';
	public $_params = array();
	//public $sandbox = false;
    public static $sandbox = false;
	
	// Construct
	// ---------------------------------------------------------------------------
	public function __construct($item=false,$sandbox = false) 
	{
		//if($this->sandbox = $sandbox) $this->url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        if(self::$sandbox) $this->url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        if($item!==false){
            $this->item = $item;
    		$this->param('rm',2);
    		$this->param('cmd','_xclick');
            $this->param('no_shipping','1');
            $this->param('lc','US');
            $this->param('custom','');
            
            $this->param('item_name',$item['name']);
            $this->param('item_number',$item['number']);
            $this->param('amount',$item['amount']);
            $this->param('currency_code',$item['cc']);
        }
	}
	
	// Param
	// ---------------------------------------------------------------------------
	public function param($name,$value)
	{
		$this->_params[$name] = $value;
		return $this;
	}
	
	// Data
	// ---------------------------------------------------------------------------
	public function data($name,$value)
	{
		$this->_data .= urlencode($name).'='.urlencode($value).'&';
		return $this;
	}
	
    public function generate($formid,$input=false){
        
        if(self::$cancel_return && self::$notify_url && self::$paypal_id && self::$return){
        
            $res = '<form id="'.$formid.'" action="'.$this->url.'" method="post" style="padding: 0px; margin: 5px 0px;">
    ';
            $res .= $this->_generate();
            if($input) $ret .= '<input type="image" src="http://www.exalttrade.com/skin/frontend/default/led/images/btn_buynow_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!" style="margin: 0px; padding: 0px;">';
    		 
            $res .= '
    				</form>';
            return $res;
        }else throw new SAException('Empty one ore more required fields.');
    }
	
	// Generate
	// ---------------------------------------------------------------------------
	private function _generate()
	{
	    $this->param('return',self::$return);
        $this->param('cancel_return',self::$cancel_return);
        $this->param('notify_url',self::$notify_url);
        $this->param('business',self::$paypal_id);
		$t = '';
		
		foreach($this->_params as $key=>$val)
			$t .= "<input type=\"hidden\" name=\"$key\" value=\"$val\" />\n";
		return $t;
	}
	
	// Valid for IPN
	// ---------------------------------------------------------------------------
	public function valid()
	{
		// If already validated, just return last result
		if($this->_valid === true or $this->_valid === false) return $this->_valid;
		
		// Generate POST fields
		foreach($_POST as $key=>$val) $this->data($key,$val);
		
        $this->_data = "cmd=_notify-validate&".$this->_data;
		// Connect to PayPal
         
        if(extension_loaded('curl')){
             
    		$sh = curl_init($this->url);
    		curl_setopt($sh,CURLOPT_POST,true);
    		curl_setopt($sh,CURLOPT_POSTFIELDS,$this->_data);
            //curl_setopt($sh,CURLOPT_POSTFIELDS,"cmd=_notify-validate");
    		curl_setopt($sh,CURLOPT_RETURNTRANSFER,1);

    		// Bad Connection
    		if(!($res = curl_exec($sh)))
    		{
    			$this->error = 'Connection to PayPal failed.';
    			$this->_valid = false;
    			return false;
    		}
    		 
    		// Valid
    		if(preg_match('/VERIFIED/is',$res))
    		{
    			$this->_valid = true;
    			return true;
    		}
    		// Invalid
    		else
    		{
    			$this->error = 'Invalid transaction.';
    			$this->_valid = false;
    			return false;
    		}
        }else{
             
            $paypalurl = parse_url($this->url);
            $request = $this->_data;
            $header = "POST ".$paypalurl["path"]." HTTP/1.0\r\n";
            $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $header .= "Content-Length: ".strlen($request)."\r\n\r\n";
            $handle = fsockopen("ssl://".$paypalurl["host"], 443, $errno, $errstr, 30);
            if ($handle)
            {
            	fputs ($handle, $header.$request);
            	while (!feof($handle))
            	{
            		$result = fgets ($handle, 1024);
            	}
                  
            	if (substr(trim($result), 0, 8) == "VERIFIED")
            	{
        			$this->_valid = true;
        			return true;        	   
                } 
                else
                {
         			$this->error = 'Invalid transaction.';
        			$this->_valid = false;
        			return false;
                }   
           }
           else
           {
    			$this->error = 'Connection to PayPal failed.';
    			$this->_valid = false;
    			return false;        
           }            
        }
	}
}
$paypal_config = config::get('paypal');
paypal::$cancel_return = $paypal_config['cancel_return'];
paypal::$notify_url = $paypal_config['notify_url'];
paypal::$paypal_id = $paypal_config['paypal_id'];
paypal::$return = $paypal_config['return'];
paypal::$sandbox = $paypal_config['sandbox'];

?>