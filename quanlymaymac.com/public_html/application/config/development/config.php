<?php if(!defined('HTH')){die('External Access to File Denied');}

/**
 * SA Framework Basic Configuration File
 *
 * @Author          Hoang Trong Hoi
 * @Copyright       2011
 */

// Application's Base URL
define('BASE_URL','http://'.$_SERVER['SERVER_NAME'].'/');
define('BASE_URL_SECURE','https://'.$_SERVER['SERVER_NAME'].'/');
define('WEBROOT',"/home/quanlymaymac.com/public_html");
define('DS',DIRECTORY_SEPARATOR);

// Does Application Use Mod_Rewrite URLs?
define('MOD_REWRITE',TRUE);

// Turn Debugging On?
define('DEBUG',TRUE);

// Turn Error Logging On?
define('ERROR_LOGGING',FALSE);

// Error Log File Location
define('ERROR_LOG_FILE','log.txt');

/**
 * Your Application's Default Timezone
 * Supported timezones @see http://www.php.net/manual/en/timezones.php
 */
date_default_timezone_set('Asia/Saigon');

/* Current theme */
config::set('current_theme','default');

/* Sessions */
config::set('session',array(
	'connection'=>'default',
	'table'=>'sessions',
	'cookie'=>array('path'=>'/','expire'=>'+1 months')
));

/* Notes */
config::set('notes',array('path'=>'/','expire'=>'+5 minutes'));


/* Application Folder Locations */
config::set('folder_views','view');             // Views
config::set('folder_controllers','controller'); // Controllers
config::set('folder_models','model');           // Models
config::set('folder_helpers','helper');         // Helpers
config::set('folder_languages','language');     // Languages
config::set('folder_errors','error');           // Errors
config::set('folder_orm','orm');                // ORM

/* Default Charset */
config::set('charset','UTF-8');
config::set('language','english');

// Database Connection
//config::set('connection','default');

config::set('roles',array(
    "nhanvien"=>"Nhân Viên",
    "khachhang"=>"Khách hàng",
    "doitacsanxuat"=>"Đối tác sản xuất",
    "nhacungcap"=>"Nhà cung cấp",
    
    "chuyen"=>"Chuyền",
));
