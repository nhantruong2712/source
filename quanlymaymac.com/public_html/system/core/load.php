<?php if(!defined('HTH')){die('External Access to File Denied');}

/**
 * SA Framework Load Class
 *
 * @Author          Hoang Trong Hoi
 * @Copyright       2011
 */

class load
{
	// File
	// ---------------------------------------------------------------------------
	public static function file($folder,$file,$name=false)
	{
	    if(!$name) $name = $file;
		// If file does not exist display error
		if(!file_exists("$folder/$file.php"))
		{
		    if($name != 'ORM') //add 04/12/2020
			 sa_error(E_USER_ERROR,"The requested $name ($folder/$file.php) could not be found.");
			return FALSE;
		}
		else
		{
			require_once("$folder/$file.php");
			return TRUE;
		}
	}
 
	// Controller
	// ---------------------------------------------------------------------------
	public static function controller($controller)
	{
		return self::file(APPLICATION.'/'.config::get('folder_controllers'),$controller,'controller');
	}
 
	// Parent Controller
	// ---------------------------------------------------------------------------
	public static function parent_controller($controller)
	{
		return self::controller($controller);
	}
 
	// Model
	// ---------------------------------------------------------------------------
	public static function model($model,$args=array())
	{
		// Model class
		$model_class = explode('/',$model);
		$model_class = end($model_class);//.'_model';
 
		if(!class_exists($model_class))
		{
			$path = config::get('application')."/".config::get('folder_models')."/$model.php";
			
			// If model does not exist display error
			if(!file_exists($path))
			{
				sa_error(E_USER_ERROR,"The requested model ($path) could not be found.");
				return FALSE;
			}
			else
			{
				require_once($path);
			}
		}
 
		// Return model class
		return new $model_class();
	}
	
	// Parent Model
	// ---------------------------------------------------------------------------
	public static function parent_model($model)
	{
		// Model class
		$model_class = explode('/',$model);
		$model_class = end($model_class);//.'_model';
		
		
		if(!class_exists($model_class))
		{
			$path = config::get('application')."/".config::get('folder_models')."/$model.php";
			
			// If model does not exist display error
			if(!file_exists($path))
			{
				sa_error(E_USER_ERROR,"The requested model ($path) could not be found.");
				return FALSE;
			}
			else
			{
				require_once($path);
				return TRUE;
			}
		}
	}
	
	// Error
	// ---------------------------------------------------------------------------
	public static function error($type = 'general',$title = NULL,$message = NULL)
	{
		ob_clean();
		require_once(config::get('application').'/'.config::get('folder_errors')."/$type.php");
		ob_end_flush();
		exit;
	}
	
	// Config
	// ---------------------------------------------------------------------------
	public static function config($file)
	{
		return self::file(APPLICATION.'/'.CONFIG.'/'.CONFIGURATION,$file,'configuration');
	}
	
	// Language
	// ---------------------------------------------------------------------------
	public static function language($language)
	{
	    if(!$language){
	       //throw new SAException('Language must not empty!');
           sa_error(E_USER_ERROR,'Language must not empty!');
	    }
	    self::library('message');
		return self::file(APPLICATION.'/'.config::get('folder_languages'),$language,'language');
	}
	
	
	// View
	// ---------------------------------------------------------------------------
    public static function view($view, $data = NULL, $return = FALSE) 
    {

        // If return as data, start output buffering
        if ($return == TRUE) {
            ob_start();
        }
         
        // If view does not exist display error
        if (!file_exists(config::get('application') . '/' . config::get('folder_views') . "/$view.php")) {
            sa_error(E_USER_WARNING, 'The requested view (' .config::get('application') . '/' . config::get('folder_views') . "/$view.php) could not be found.");
            return FALSE;
        } else {
            // If data is array, convert keys to variables
            if (is_array($data)) {
                extract($data, EXTR_OVERWRITE);
            }
             
            require(config::get('application') . '/' . config::get('folder_views') . "/$view.php");
            
            //If return as data, return the output buffering result
            if ($return == TRUE) {
                return ob_get_clean();
            } else {
                return FALSE;
            }
        }
    }
	
	
	// Library
	// ---------------------------------------------------------------------------
	public static function library($library)
	{
		return self::file(SYSTEM.'/library',$library,'library');
	}
	
	// Driver
	// ---------------------------------------------------------------------------
	public static function driver($library,$driver)
	{
		return self::file(SYSTEM."/driver/$library",$driver,'driver');
	}
	
	
	// Helper
	// ---------------------------------------------------------------------------
	public static function helper($helper)
	{
		return self::file(APPLICATION.'/'.config::get('folder_helpers'),$helper,'helper');
	}
	
	// ORM Class
	// ---------------------------------------------------------------------------
	public static function orm_class($orm)
	{
		return self::file(APPLICATION.'/'.config::get('folder_orm'),$orm,'ORM');
	}
	
	// ORM
	// ---------------------------------------------------------------------------
	public static function orm($orm)
	{
		self::orm_class($orm);
		
		// ORM class
		$orm_class = explode('/',$orm);
		$orm_class = end($orm_class).'_orm';
		
		return new $orm_class();
	}
	
	// Parent ORM
	// ---------------------------------------------------------------------------
	public static function parent_orm($orm)
	{
		return self::orm($orm);
	}
    
    
    public static function autoload()
    {
        spl_autoload_register('load::_autoload');
    }
    
    // Autoload libraries, helpers, models
    public static function _autoload($class)
    {
        if(!class_exists($class)){
            $file =  SYSTEM."/library/".strtolower($class).".php";
            if(file_exists($file)) include_once($file);
            else{
                $file = APPLICATION."/".config::get('folder_helpers')."/".strtolower($class).".php";
                if(file_exists($file)) include_once($file);
                else{
                    $file = APPLICATION."/classes/".strtolower($class).".php";
                    if(file_exists($file)) include_once($file);
                    else{
                        $file = APPLICATION."/".config::get('folder_models')."/".strtolower($class).".php";
                        if(file_exists($file)) include_once($file);
                        else{
                            //load from admin model
                            $file = "./admin/".APPLICATION."/".config::get('folder_models')."/".strtolower($class).".php";
                            if(file_exists($file)) include_once($file);
                            else throw new SAException('Class '.$class.' not found!');   
                        }
                        
                    }                   
                }
            }
        }
    }     
}
?>