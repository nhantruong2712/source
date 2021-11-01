<?php if (!defined('HTH')){die('External Access to File Denied');}

/**
 * Memcache Library For SA Framework
 *
 * @author          Hoang Trong Hoi
 * @copyright       2016
 */

class memcache
{

    public $prefix = "sa_", $cur_key, $cur_cache;
    public $host = 'localhost', $port = '11211', $timeout = 0, $weight=array();
    public $persistent = true;

    public static function getInstance()
    {
        static $instance = null;
        if ($instance == null)
            $instance = new self();
        return $instance;
    }
    
    public static function newInstance($host,$port=11211,$weight=array())
    {
        static $newinstance = null;
        if ($newinstance == null){
            $newinstance = new self(false);
            $newinstance->host = $host;
            $newinstance->port = $port;
            $newinstance->weight = $weight;
            $newinstance->connect();
        }
            
        return $newinstance;
    }

    public function __construct($auto_connect = true)
    {
        if ($auto_connect)
            $this->connect();
    }
    
    public function addServer($host,$port=11211,$weight=1){
        return $this->memcache->addServer($host,$port,$this->persistent,$weight);
    }

    public function connect()
    {
        if (!function_exists('memcache_connect'))
        {
            throw new Exception('Memcache is not currently installed');
        } else
        {

            $this->memcache = new Memcache;
            $func = $this->persistent ? 'pconnect' : 'connect';
            if(!is_array($this->host)){
                if (!($this->timeout > 0 ? $this->memcache->{$func}($this->host, $this->port, $this->timeout) : 
                    $this->memcache->{$func}($this->host, $this->port)))
                {
                    throw new Exception('Could not connect to the Memcache host');
                }
            }else{
                foreach($this->host as $k=>$host){
                    if(is_string($host)){
                        if(strpos($host,':')>0){
                            $host = explode(':',$host);
                            $port = $host[1];
                            $host = $host[0];
                        }else{
                            if(is_array($this->port)){
                                $port = $this->port[$k];
                            }else{
                                $port = $this->port;
                            }
                        }
                        if(empty($this->weight)){
                            $weight = 1;
                        }elseif(is_array($this->weight)){
                            $weight = $this->weight[$k];
                        }else{
                            $weight = $this->weight;
                        }
                    }elseif(is_array($host)){
                        $port = $host['port'];                        
                        $weight = empty($host['weight'])?1:intval($host['weight']);
                        $host = $host['host'];
                    }
                    
                    $this->addServer($host,$port,$weight);
                }
            }
        }

    }
    
    /* New item's value will not be less than zero. */
    public function change($key, $value = 1)
    {
        if ($value < 0)
            return $this->memcache->decrement($this->prefix . $key, -$value);
        else
            return $this->memcache->increment($this->prefix . $key, $value);
    }

    public function exists($key)
    {
        if ($this->memcache->get($this->prefix . $key))
        {
            $this->cur_cache = $this->memcache->get($this->prefix . $key);
            $this->cur_key = $this->prefix . $key;
            return true;
        } else
        {
            return false;
        }

    }

    public function delete($key)
    {
        if ($this->memcache->get($this->prefix . $key))
        {
            return $this->memcache->delete($this->prefix . $key);

        } else
        {
            return false;
        }
    }

    public function flush()
    {
        $this->memcache->flush();
    }

    /**
     *@var $interval interval or expire, default is 9 minutes (interval not exceed 2592000 - 30 days)
     */
    public function update($key, $data, $interval)
    {
        $interval = (isset($interval)) ? $interval : 60 * 60 * 0.15;

        if ($this->prefix . $this->cur_key)
        {
            if (!empty($this->cur_cache))
            {
                return $this->memcache->replace($this->cur_key, $data, MEMCACHE_COMPRESSED, $interval);
            }
        } elseif ($this->memcache->get($this->prefix . $key))
        {
            return $this->memcache->replace($this->prefix . $key, $data, MEMCACHE_COMPRESSED, $interval);
        } else
        {
            return false;
        }
    }

    public function get($key)
    {
        if (($this->prefix . $key) == $this->cur_key)
        {
            return $this->cur_cache;
        } else
        {
            return $this->memcache->get($this->prefix . $key);
        }
    }

    /**
     *@var $interval interval or expire, default is 9 minutes (interval not exceed 2592000 - 30 days)
     */
    public function set($key, $data, $interval)
    {
        $interval = (isset($interval)) ? $interval : 60 * 60 * 0.15;
        return $this->memcache->set($this->prefix . $key, $data, MEMCACHE_COMPRESSED, $interval);
    }

}

/*
if($_SERVER['SERVER_ADDR']=="198.204.228.10"){
    $cache = memcache::newInstance('46.4.77.244');
}else{
    if(!isset($cache)) $cache = new memcache;
}   */