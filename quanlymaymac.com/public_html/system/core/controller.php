<?
/**
 * SA Framework Controller Class
 *
 * @Author Sans_Amour
 * @Copyright 2011
 */
 
class controller{
    public function init(){
        //load all configuration value
        configuration::run(); 
        //if(!self::is_admin()){
        //    statistic_data::add();
        //}       
        $this->_acl();
    }
    
    public function _acl(){}
    
    public function __acl(){
        $acl = acl::create('administrator');
        $acl->role('mod');
        $acl->role('admin',array('mod'));
        return $acl;
    }

    public static function getacl(){
        return acl::get('administrator');
    }

    public static function _($mod){
        return acl::get('administrator')->is_allowed(user::$_type,$mod);
    }
    
    public static function is_admin(){
        return preg_match("/^\/?admin\//",CURRENT_PAGE);
    }
}