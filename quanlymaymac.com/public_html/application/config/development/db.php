<?php if(!defined('HTH')){die('External Access to File Denied');}

/**
 * SA Framework DB Configuration File
 *
 * @Author          Hoang Trong Hoi
 * @Copyright       2011
 */

config::set('db',array(
	
	/* Default Connection */
	'default'=>array(	
		'driver'=>'mysql',       // Driver
		'host'=>'localhost',     // Host
		'username'=>'root',      // Username
		'password'=>'new-password',          // Password
		'database'=>'qlmm'       // Database
	
	)
	
));
 
db();