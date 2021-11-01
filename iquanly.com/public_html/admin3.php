<?php 
error_reporting(0);
 
include "header.php";

if(empty($_SESSION['email'])){    
    ?>
<script>
window.location.href='admin.php'
</script>    
    <?php
    die();
}

$id = empty($_GET['id'])?'':$_GET['id'];

if(!$id){
?>
<script>
window.location.href='admin.php'
</script>    
    <?php
    die();    
}

include "cf.php";

$link=mysqli_connect("$host", "$username1", "$password") or die("cannot connect to server");
if (!$link) {
    die('Could not connect: ' /*. mysqli_error()*/);
}

mysqli_select_db($link,"qlt") or die("cannot select DB");

function trial($trial,$reg){
    if(preg_match("/^\d+\-\d+\-\d+$/",$trial)){
        return date('d-m-Y',strtotime($trial));
    }elseif($trial == 1){
        return date('d-m-Y',strtotime($reg)+14*86400);
    }else {
        return '';
    }
}

function cv($createdate){
    return preg_replace("/^(\d+)\-(\d+)\-(\d+)$/","$3-$2-$1",$createdate);
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

$sql12="SELECT * FROM username WHERE id=".$id;
$query12=mysqli_query($link,$sql12);
$count2=mysqli_num_rows($query12);
if($count2==0){        			   
?>
<script>
window.location.href='admin.php'
</script>    
    <?php
    die(); 
}

$user = mysqli_fetch_assoc($query12);


?>
          <script>
    var menu = '';
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datepicker/0.6.5/datepicker.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/datepicker/0.6.5/datepicker.min.js"></script>
 
			<br />
             <div class="row" style="padding-left:15px; max-width: 600px; margin: 0 auto;">
        <form role="form" method="POST">
            <div class="col-lg-12">
                <div class="well well-sm" style="text-align: center;"><strong>
                SỬA TÀI KHOẢN KHÁCH HÀNG
                </strong></div>
                	 
				<div class="form-group">
                    <label for="InputEmailFirst">Email</label>
                    <div class="input-group">
                        <input type="email" class="form-control" id="InputEmailFirst" name="email" required="" value="<?=$user['email']?>" />
                        <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                    </div>
                </div>
				<div class="form-group">
                    <label for="">Subdomain</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="subdomain" disabled="" value="<?=$user['subdomain']?>" />
                        <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Phone</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="phone" disabled="" value="<?=$user['phone']?>" />
                        <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="">Created date</label>
                    <div class="input-group">
                        <input data-toggle="datepicker" type="text" class="form-control" name="createdate" required="" value="<?=cv($user['createdate'])?>" />
                        <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                    </div>
                </div>
                
                
                <div class="form-group">
                    <label for="">Expired date</label>
                    <div class="input-group">
                        <input data-toggle="datepicker" type="text" class="form-control" name="expire" value="<?=trial($user['trial'],$user['createdate'])?>" />
                        <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                    </div>
                </div>
                 
                <div class="form-group">
                    <label for=""></label>
                    <div class="input-group" style="width: 100px; margin: 0 auto;">
                        <input type="submit" name="submit" id="submit" class="btn btn-info pull-right" value="Sửa" />
                    </div>
				</div>
			 
				
            </div>
        </form>
			
        <?php
date_default_timezone_set('Asia/Saigon');
error_reporting(0);

$mess = '';
$done = 0;
if (isset($_POST['submit']))	{
	$mess = mm($done);		 
}

function mm(&$done){
    global $id,$user,   $link;
	$createdate= $_POST["createdate"];
    if($createdate=='' || !preg_match("/^(\d+)\-(\d+)\-(\d+)$/",$createdate)){
        return 'Ngày khởi tạo sai định dạng.';
    }
    $createdate = preg_replace("/^(\d+)\-(\d+)\-(\d+)$/","$3-$2-$1",$createdate);
	
	$expire= $_POST["expire"];
    if($expire!='' && !preg_match("/^(\d+)\-(\d+)\-(\d+)$/",$expire)){
        return 'Ngày hết hạn sai định dạng.';
    }
    $expire = preg_replace("/^(\d+)\-(\d+)\-(\d+)$/","$3-$2-$1",$expire);  
    
    $email= $_POST["email"];
    if(!preg_match("/^.+\@.+$/",$email)){
        return 'Email sai định dạng.';
    }

    //var_dump($createdate,$expire,$id);die();
	     
    $sql2="update username set email = '$email', createdate='$createdate' , trial = '$expire' where id = $id";
    $query3=@mysqli_query($link,$sql2);
    
    if(!$query3){        			   
    	return 'Không thể sửa tài khoản này. Có thể do trùng Email';
    }
    @file_get_contents((is_ssl()?'https':'http')."://".$user['subdomain'].".".sss()."/ajax.php?action=deletecache");
    $done = 1;
    //header("Location: admin.php");
    ?>
<script>
window.location.href='admin2.php'
</script>    
    <?php        
        die();
    
     
}             
		
		?>
         
    </div>
    
</br>

<?php

if($mess){
?>
<script>
alert("<?=$mess?>");
</script>
<?php    
} 
?>

<script>
$('[data-toggle="datepicker"]').datepicker({
    format: 'dd-mm-yyyy'
});

</script>
     
<?php 
include "footer2.php";
?>