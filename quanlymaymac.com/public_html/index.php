<?php
/**
 * SA Framework Index CMS
 *
 * @Author          Hoang Trong Hoi
 * @Copyright       2011
 */
 
error_reporting(E_STRICT|E_ALL);

// Application configuration
//----------------------------------------------------------------------------------------------

// Configuration
define('CONFIGURATION','development');

// SA Location
define('SYSTEM','system');

// Application Location
define('APPLICATION','application');

// Config Directory Location (in relation to application location)
define('CONFIG','config');

// Allowed Characters in URL
define('ALLOWED_CHARS','/^[ \!\,\~\&\.\:\+\@\-_a-zA-Z0-9]+$/');

// End of configuration
//----------------------------------------------------------------------------------------------
define('HTH',1);

require_once(SYSTEM.'/core/bootstrap.php');
 
bootstrap::run();