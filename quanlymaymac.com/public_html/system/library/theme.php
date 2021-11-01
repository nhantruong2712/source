<?php if(!defined('HTH')){die('External Access to File Denied');}

/**
 * Theme Library For SA Framework
 *
 * @author          Hoang Trong Hoi
 * @copyright       2011
 */

class theme
{
	private $core;
	private $current = 'default';
	
	// Construct
	// ---------------------------------------------------------------------------
	public function __construct()
	{
		$this->core = new core;
		if($current = config::get('current_theme'))
		{
			$this->current = $current;
		}
	}
	
	// Set
	// ---------------------------------------------------------------------------
	public function set($theme)
	{
		$this->current = $theme;
	}
	
	// View
	// ---------------------------------------------------------------------------
	public function view($view,$data=NULL,$return=false)
	{
		// Try to load view from theme
		if(file_exists(config::get('application')."/view/{$this->current}/$view.php"))
		{
			return $this->core->load->view("{$this->current}/$view",$data,$return);
		}
		// If view not found in theme try to load from default theme
		elseif($this->current != 'default' AND file_exists(config::get('application')."/view/default/$view.php"))
		{
			return $this->core->load->view("default/$view",$data,$return);
		}
		// Otherwise throw an error
		else
		{
			sa_error(E_USER_WARNING,'The requested theme view ('.config::get('application')."/view/{$this->current}/$view.php) could not be found.");
		}
	}
    
    public function load($view){
        return load::file(config::get('application')."/view/{$this->current}/","$view");
    }
}

function l($view){
    return core::$theme->load($view);
}