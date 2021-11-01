<?php 
error_reporting(0); session_start();

if(empty($_SESSION['email'])){
    //header("Location: admin2.php");
    ?>
<script>
window.location.href='admin.php'
</script>    
    <?php
    die();
}

include "cf.php";

date_default_timezone_set('Asia/Saigon');

$link=mysqli_connect("$host", "$username1", "$password") or die("cannot connect to server");
if (!$link) {
    die('Could not connect: ' /*. mysqli_error()*/);
}

$ajax = empty($_GET['ajax'])?'':$_GET['ajax'];

if($ajax){
    //tim theo id
    $sql12="SELECT * FROM qlt.username WHERE id=".$ajax;
    $query12=mysqli_query($link,$sql12);
    $count2=mysqli_num_rows($query12);
    if($count2==0) die();
    $user = mysqli_fetch_assoc($query12);
    //DROP TABLE 
    mysqli_query($link,"DROP TABLE qlt_".$user['subdomain']);
    //delete 
    $sql12="delete FROM qlt.username WHERE id=".$ajax;
    $query12=mysqli_query($link,$sql12);
    echo 'Xóa thành công thông tin và cơ sở dữ liệu khách hàng này';
    die();
}

$b = empty($_GET['b'])?'':$_GET['b'];
if($b){
    //tim theo id
    $sql12="SELECT * FROM qlt.username WHERE id=".$b;
    $query12=mysqli_query($link,$sql12);
    $count2=mysqli_num_rows($query12);
    if($count2==0) die();
    $user = mysqli_fetch_assoc($query12);
    
    $settings = $user['settings'];   
    if($settings=='' || $settings=='{}') $settings=array();
    else{
        $settings =(array) json_decode($settings,true);
    } 
    $x = isset($settings['business']) && $settings['business'];
    
    if($x){
        unset($settings['business']);
    }else{
        $settings['business'] = 1;
    }
    
    $settings = json_encode($settings);
    if($settings=='{}') $settings='';
     
    //update 
    $sql12="update qlt.username set settings = '".$settings."' WHERE id=".$b;
    $query12=mysqli_query($link,$sql12);
    
    @file_get_contents('http"//'.$user['subdomain'].'.'.sss().'/ajax.php?action=deletecache');
    
    echo json_encode(array(
        'business'=>$x?0:1
    ));
    die();
}

include "header.php";
 
mysqli_select_db($link,"qlt") or die("cannot select DB");

$id = empty($_GET['id'])?'':$_GET['id'];
$del = empty($_GET['del'])?'':$_GET['del'];

function trial($trial,$reg){
    if(preg_match("/^\d+\-\d+\-\d+$/",$trial)){
        return date('d/m/Y',strtotime($trial));
    }elseif($trial == 1){
        return date('d/m/Y',strtotime($reg)+14*86400);
    }else {
        return 'Forever';
    }
}

function is_ssl(){
    if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
        $_SERVER['HTTPS'] = 'on';
    return isset($_SERVER['HTTPS']);
}

function sss(){
    return (in_array($_SERVER['SERVER_ADDR'],array('127.0.0.1',"::1"))?
        "quanlytot.localhost":
        ($_SERVER['SERVER_ADDR']=='103.74.117.19'?
            "quanlytot.com":
            ($_SERVER['SERVER_ADDR']=='103.92.28.200'?'ehome247.com':'iquanly.com')
        )
    );
}

if($id){
    
}elseif($del){
    
}

$page = empty($_GET['page'])?1:intval($_GET['page']);
$page = max(1,$page);
$pp = 10;
$pages = 0;

$sql12="SELECT SQL_CALC_FOUND_ROWS id, subdomain, email, phone, createdate, trial, settings 
FROM username 
WHERE type>0
LIMIT ".($page==1?'0':(($page-1)*$pp)).','.$pp;
$query12=mysqli_query($link,$sql12);
$count2=mysqli_num_rows($query12);
$data = array();
if($count2){        			   
	while($row = mysqli_fetch_assoc($query12)){
	   $data[] = $row;
	}
    
    $sql12="SELECT found_rows()";
    $query12=mysqli_query($link,$sql12);
    $total = mysqli_fetch_array($query12);
    $total = $total[0];
    //var_dump($total);die();
    $pages = ceil($total/$pp);
}
?>
 
          <script>
    var menu = '';
</script>
<div class="container">
<div class="row col-md-12">
<h2>Danh sách Khách hàng</h2>
    <table class="table table-striped custab">
    <thead>
     
        <tr>
            <th>ID</th>
            <th>Subdomain</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Created Date</th>
            <th>Expired Date</th>
            <th class="text-center">Branches</th>
            <th class="text-center">Business</th>
            <th class="text-center">Action</th>
        </tr>
    </thead> <?php foreach($data as $d){
    $settings = $d['settings'];   
    if($settings=='' || $settings=='{}') $settings=array();
    else{
        $settings =(array) json_decode($settings,true);
    } 
    $x = isset($settings['business']) && $settings['business'];
        ?>
            <tr>
                <td><?=$d['id']?></td>
                <td><?=$d['subdomain']?>.<?=sss()?></td>
                <td><?=$d['email']?></td>
                <td><?=$d['phone']?></td>
                <td><?=date('d/m/Y',strtotime($d['createdate']))?></td>
                <td><?=trial($d['trial'],$d['createdate'])?></td>
                <td class="text-center">
                    <a class='btn btn-info btn-xs' href="#" onclick="show(this,'<?=$d['subdomain']?>')"><span class="glyphicon glyphicon-info-signt"></span> Show</a>  
                </td>
                <td class="text-center">
                    <a class='btn btn-info btn-xs<?=$x?'':' btn-danger'?>' href="#" onclick="business(this,'<?=$d['id']?>')"><span class="glyphicon glyphicon-<?=$x?'ok':'remove'?>"></span> <?=$x?'Enabled':'Disabled'?></a>  
                </td>
                <td class="text-center">
                    <a class='btn btn-info btn-xs' href="/admin3.php?id=<?=$d['id']?>"><span class="glyphicon glyphicon-edit"></span> Edit</a> 
                    <a href="#" class="btn btn-danger btn-xs" onclick="del(this,'<?=$d['id']?>')"><span class="glyphicon glyphicon-remove"></span> Del</a></td>
            </tr>
     <?php }?>        
    </table>
    
<nav aria-label="Page navigation example">
  <ul class="pagination">
    <?php if($page>1){?>
    <li class="page-item">
      <a class="page-link" href="?page=<?=$page-1?>" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
        <span class="sr-only">Previous</span>
      </a>
    </li>
    <?php for($i=max(1,$page-5);$i<$page;$i++){?>
    <li class="page-item"><a class="page-link" href="?page=<?=$i?>"><?=$i?></a></li>
    <?php }?>
    <?php }?>
    <li class="page-item"><a class="page-link" href="#"><?=$page?></a></li>
    <?php if($page<$pages){?>
    <?php for($i=$page+1;$i<=min($page+5,$pages);$i++){?>
    <li class="page-item"><a class="page-link" href="?page=<?=$i?>"><?=$i?></a></li>
    <?php }?>
    <li class="page-item">
      <a class="page-link" href="#" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
        <span class="sr-only">Next</span>
      </a>
    </li>
    <?php }?>
  </ul>
</nav>    
    
    </div>
</div> 
<script>
function show(that,subdomain){
    $(that).html('Loading...')
    $(that).attr('onclick','')
    $.ajax({
        url: '<?=is_ssl()?'https':'http'?>://'+subdomain+'.<?=sss()?>/ajax.php?action=branches',
        dataType: 'JSON',
        success: function(data){
            $(that).html(data.length)
        }
    })
}

function business(that,id){
    //document.querySelector('.btn-danger').childNodes[1].nodeValue
    //$(that).find('span').html('Loading...')
    that.childNodes[1].nodeValue = ' Loading...'
    
    $(that).attr('onclick','')
    $.ajax({
        url: '/admin2.php?b='+id,
        dataType: 'JSON',
        success: function(data){
            //$(that).find('span').html(data.business==1?'Enabled':'Disabled')
            that.childNodes[1].nodeValue = data.business==1?' Enabled':' Disabled'
            if(data.business==1){
                $(that).removeClass('btn-danger')
                $(that).find('span').removeClass('glyphicon-remove').addClass('glyphicon-ok')
            }else{
                $(that).addClass('btn-danger')
                $(that).find('span').addClass('glyphicon-remove').removeClass('glyphicon-ok')
            }
        }
    })
}
 
function del(that,id){
    if(confirm('Bạn có chắc chắn xóa tài khoản này, thông tin tài khoản này và cơ sở dữ liệu của nó đều bị xóa!')){
        $(that).parent().remove()
        $.ajax({
            url: '/admin2.php?ajax='+id,            
            type: 'POST',
            success: function(data){
                alert(data)
            },
            error: function(e){
                alert('Xóa thất bại')
            }
        })
    }
}
</script>   
 <?php 
include "footer2.php";
?>   