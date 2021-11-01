<?php

//var_dump($_POST);

/*

UserName:a
Phone:b
Email:admin@yahoo.com
Content:d

*/

$UserName=empty($_POST['UserName'])?'':$_POST['UserName'];
$Phone=empty($_POST['Phone'])?'':$_POST['Phone'];
$Email=empty($_POST['Email'])?'':$_POST['Email'];
$Content=empty($_POST['Content'])?'':$_POST['Content'];

if(!$UserName || !$gianhang){
    echo json_encode(array('message'=>'Thiếu thông số'));
    die();
}
date_default_timezone_set('Asia/Saigon');
error_reporting(0);

include "cf.php";

$link=mysqli_connect("$host", "$username1", "$password")or die("cannot connect to server");
if (!$link) {
    die('Could not connect: ' /*. mysqli_error()*/);
}
 
mysqli_select_db($link,"qlt") or die("cannot select DB");
$sql1="insert into contact values(null,'".mysqli_real_escape_string($link,$UserName)."','".mysqli_real_escape_string($link,$Phone).
    "','".mysqli_real_escape_string($link,$Email)."','".mysqli_real_escape_string($link,$Content)."',now())";
mysqli_query($link,$sql1);

echo json_encode(array('result'=>'1'));