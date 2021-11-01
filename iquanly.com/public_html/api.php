<?php

//var_dump($_SERVER);die();

$domain=empty($_GET['domain'])?'':$_GET['domain'];
$id=empty($_GET['id'])?'':$_GET['id']; 
$settings=empty($_GET['settings'])?'':$_GET['settings']; 
$zone=empty($_POST['zone'])?(empty($_REQUEST['query'])?'':$_REQUEST['query']):$_POST['zone']; 
$api=empty($_GET['api'])?'':$_GET['api'];

if(!$domain && !$id && !$settings && !$zone && !$api){
    die('Error');
}

date_default_timezone_set('Asia/Saigon');
error_reporting(0);

include "cf.php";

$link=mysqli_connect("$host", "$username1", "$password") or die("cannot connect to server");

mysqli_select_db($link,"qlt") or die("cannot select DB");
if (!$link) {
    die('Could not connect: '/* . mysqli_error()*/);
}

if($domain){

    $username = array();
    $sql = "select * from username where domain = '$domain' or subdomain = '$domain'";
    $result1=mysqli_query($link, $sql);
    if(mysqli_num_rows($result1)>0)
    {
        $username = mysqli_fetch_assoc($result1);
    }
 
	header('Content-type: application/json; charset=utf-8');
	echo json_encode($username);
 
 
}elseif($settings){
    $sql = "update username set settings = '".mysqli_real_escape_string($link,$settings)."' ";
     
    //echo 
    $sql .=  " where id=".$id;
    
    @mysqli_query($link,$sql);
}elseif($id){
    
    if(isset($_GET['get'])){
    
        $get = $_GET['get'];
        if(empty($get)) $get='*';
        
        $sql = "select $get from username where id=".$id;
        
        $q = mysqli_query($link,$sql);
        
        if(mysqli_num_rows($q)){
            $q = mysqli_fetch_assoc($q);
            echo json_encode($q);
        }    
        
    }else{
    
        $sql = "update username set ";
        
        $a = array();
        
        foreach($_GET as $key=>$val){
            if($key!='id'){
                $a[] = " `$key` = '".mysqli_real_escape_string($link,$val)."' ";
            }
        }
        
        $sql .= implode(",",$a)." where id=".$id;
        
        @mysqli_query($link,$sql);
    
    }
}elseif($zone){
    header( "Access-Control-Allow-Headers: origin,range,accept-encoding,referer");
    header ("Access-Control-Expose-Headers: Server,range,Content-Length,Content-Range");
    header ("Access-Control-Allow-Methods: GET, HEAD, OPTIONS");
    header ("Access-Control-Allow-Origin: *");    
     
    if(preg_match('/^([0-9\.]+),([0-9\.]+)$/',$zone,$xxx)){
        $sql = "select x.name,d2.name as province from 
        (SELECT d.id,d.name,d.parent FROM `district` d WHERE d.level=2 
        order by pow(d.`lon`-{$xxx[1]},2)+pow(d.lat-{$xxx[2]},2) asc limit 1) x 
        inner join district d2 on d2.id = x.parent";
        
        $query = @mysqli_query($link,$sql);
        $a = mysqli_fetch_assoc($query);
        $a['province'] = preg_replace('/^(Tỉnh|Thành phố|Thành Phố) /','',$a['province']);
        
        $a['data'] = $a['province'];         
        $a['value'] = $a['province'].' - '.$a['name'];
        
        echo json_encode($a);
        die();
    }
    
    /*$sql = "SELECT z.*, z2.name as province FROM `zone` z 
        inner join zone z2 on z2.id = z.parent and z.level = 2 
        where z.name like '%".$zone."%' or z2.name like '%".$zone."%' ";*/
    $sql = "SELECT z.*, z2.name as province FROM `district` z 
        inner join district z2 on z2.id = z.parent and z.level = 2 
        where z.name like '%".$zone."%' or z2.name like '%".$zone."%' limit 20";
    
    $query = @mysqli_query($link,$sql);
    
    $data = array();
    
    while($row = mysqli_fetch_assoc($query)){
        $row['province'] = preg_replace('/^(Tỉnh|Thành phố|Thành Phố) /','',$row['province']);
        $data[] = $row;
    }
     
    echo json_encode(empty($_REQUEST['query'])?array(
        'status'=>'1',
        'data'=>$data
    ):array("suggestions"=>array_slice( array_map(function($a){
        $a['province'] = preg_replace('/^(Tỉnh|Thành phố|Thành Phố) /','',$a['province']);
        
        $a['data'] = $a['province'];
        //$a['value'] = $a['province'].' - '.$a['description'].' '.$a['name'];
        $a['value'] = $a['province'].' - '.$a['name'];
        return $a;
    },$data),0,20))); 
}elseif($api){
    //SELECT `settings` FROM `username` WHERE `settings` like '%client_ClientId%'
    //"client_ClientId":"0472f3f7-62e6-466e-a948-7cd50eb085e9"
    
    if($api=='all'){
        $sql = "SELECT `settings` FROM `username` WHERE `settings` like '%\"client_ClientId\":\"%'";
    
        $query = @mysqli_query($link,$sql);
        
        $datas = array();
        if(mysqli_num_rows($query)){            
            while($data = mysqli_fetch_assoc($query)){
                $data = $data['settings'];
                try{
                    $data = json_decode($data,true);
                    if($data['client_Enabled']==1){
                        $datas[]=array(
                            'client_id'=>$data['client_ClientId'],
                            'client_secret'=>$data['client_ClientSecret'],
                            'client_name'=>$data['client_ClientName'],
                        );     
                    }else{
                          
                    }
                    
                }catch(\Exception $e){
                    
                }
            }
             
        }
        echo json_encode($datas/*array(
            'status'=>count($datas)?'1':'0',
            'data'=>$datas
        )*/); 
    }else{
    
        $sql = "SELECT `settings` FROM `username` WHERE `settings` like '%\"client_ClientId\":\"{$api}\"%'";
    
        $query = @mysqli_query($link,$sql);
        
        if(mysqli_num_rows($query)){
            $data = mysqli_fetch_assoc($query);
            $data = $data['settings'];
            try{
                $data = json_decode($data,true);
                if($data['client_Enabled']==1){
                    echo json_encode(array(
                        'status'=>'1',
                        'data'=>array(
                            'client_id'=>$data['client_ClientId'],
                            'client_secret'=>$data['client_ClientSecret'],
                            'client_name'=>$data['client_ClientName'],
                        )
                    ));die();     
                }else{
                    echo json_encode(array(
                        'status'=>'0',
                        'data'=>array()
                    )); die();    
                }
                
            }catch(\Exception $e){
                echo json_encode(array(
                    'status'=>'0',
                    'data'=>array()
                ));die(); 
            }
             
        }
        echo json_encode(array(
            'status'=>'0',
            'data'=>array()
        )); 
    
    }
}