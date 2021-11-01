<?php 
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: origin, x-requested-with, content-type');
header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS'); 
date_default_timezone_set('Asia/Saigon');
error_reporting(0);

function sss(){
    return (in_array($_SERVER['SERVER_ADDR'],array('127.0.0.1',"::1"))?
        "quanlytot.localhost":
        ($_SERVER['SERVER_ADDR']=='103.74.117.19'?
            "quanlytot.com":
            ($_SERVER['SERVER_ADDR']=='103.92.28.200'?'ehome247.com':'kiotvietnam.com')
        )
    );
}
function ssssss(){
    return (in_array($_SERVER['SERVER_ADDR'],array('127.0.0.1',"::1"))?
        "http":"https"
    );
}

if(isset($_GET['action'])){
    $action = $_GET['action'];
    
    switch($action){
        ////////////////////////////////////////////////////////////////////////////////////////
           
        case "uploadimage":
            if(isset($_FILES['files']) && $_FILES['files']['type'] && substr($_FILES['files']['type'],0,5)=='image'){
     
                $time = time();
                $Y = date('Y',$time);
                if(!is_dir("images/".$Y)){
                    //$m = umask(0);
                    mkdir("images/".$Y/*,777*/);
                    //umask($m);
                }
                $M = date('m',$time);
                if(!is_dir("images/".$Y."/".$M)){
                    //$m = umask(0);
                    mkdir("images/".$Y."/".$M/*,777*/);
                    //umask($m);
                }
                
                move_uploaded_file($_FILES['files']['tmp_name'],'images/'.$Y."/".$M."/".$time.'-'.$_FILES['files']['name']);
             
                echo json_encode(array('error'=>'','image'=>"https://".$_SERVER["HTTP_HOST"]."/images/".$Y."/".$M."/".$time.'-'.$_FILES['files']['name']));   
            }else echo json_encode(array('error'=>'Can\'t upload image'));
        break;    
            
        case "remoteuploadfile":
            //link & branch & filename
            $link = $_REQUEST['link'];
            $branch = $_REQUEST['branch'];
            $filename = $_REQUEST['filename'];
            
            if(!is_dir('files/'.$branch)) mkdir('files/'.$branch);
            
            $path = 'files/'.$branch.'/'.$filename;
            @copy($link,$path);
            echo json_encode(array(
                'link'=>ssssss().'://cdn.'.sss().'/'.$path
            ));
        break;    
    }
}

 