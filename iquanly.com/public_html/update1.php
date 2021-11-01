<?php 
 
date_default_timezone_set('Asia/Saigon');
error_reporting(0);
 
include "cf.php";
 
$link=mysqli_connect("$host", "$username1", "$password") or die("cannot connect to server");
if (!$link) {
    die('Could not connect: ' /*. mysqli_error()*/);
}

mysqli_select_db($link,"qlt") or die("cannot select DB");

$sql12="ALTER TABLE  `qlt`.`username` CHANGE  `trial`  `trial` VARCHAR( 10 ) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL";
$query12=mysqli_query($link,$sql12);
       