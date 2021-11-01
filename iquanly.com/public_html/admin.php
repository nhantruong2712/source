<?php 
error_reporting(0);

//ALTER TABLE  `qlt`.`username` CHANGE  `trial`  `trial` VARCHAR( 10 ) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL

include "header.php";

if(!empty($_SESSION['email'])){
    //header("Location: admin2.php");
    ?>
<script>
window.location.href='admin2.php'
</script>    
    <?php
    die();
}

$action = empty($_GET['action'])?'':$_GET['action'];
?>
          <script>
    var menu = '';
</script>
<!-- Banner -->
 
			</br>
             <div class="row" style="padding-left:15px; max-width: 600px; margin: 0 auto;">
        <form role="form" method="POST">
            <div class="col-lg-12">
                <div class="well well-sm" style="text-align: center;"><strong>
                <?=$action=='create'?'TẠO TÀI KHOẢN ADMIN':'ĐĂNG NHẬP ADMIN'?>
                </strong></div>
                	 
				<div class="form-group">
                    <label for="InputEmail">Email</label>
                    <div class="input-group">
                        <input type="email" class="form-control" id="InputEmailFirst" name="InputEmail" placeholder="Vd: admin@gmail.com" required value="<?=empty($_POST["InputEmail"])?'':$_POST["InputEmail"]?>" />
                        <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                    </div>
                </div>
				<div class="form-group">
                    <label for="InputName"> Mật khẩu</label>
                    <div class="input-group">
                        <input type="password" class="form-control" name="password1" id="password1" placeholder="Nhập mật khẩu" required>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                    </div>
				</div>
                
                <?php if($action=='create'){?> 
                <div class="form-group">
                    <label for="InputName"> Mật khẩu nhập lại</label>
                    <div class="input-group">
                        <input type="password" class="form-control" name="password2" id="password2" placeholder="Nhập mật khẩu nhập lại" required>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                    </div>
				</div>
                <?php }?>
				
                <div class="form-group">
                    <label for=""></label>
                    <div class="input-group" style="width: 100px; margin: 0 auto;">
                        <input type="submit" name="submit" id="submit" class="btn btn-info pull-right" value="<?=$action=='create'?'Tạo admin':'Đăng nhập'?>" />
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
    global $action;
	$password1= $_POST["password1"];
	 
	$fmail= $_POST["InputEmail"];
    
    if($action=='create'){
        $password2= $_POST["password2"];
    }
  
    include "cf.php";
    
    if( !$password1 || !$fmail){
        return "Bạn chưa nhập đầy đủ thông tin";
    }
    
    if($action=='create'){
        if($password1!=$password2){
            return "Mật khẩu nhập lại không khớp";
        }
    }
     
    $link=mysqli_connect("$host", "$username1", "$password") or die("cannot connect to server");
    if (!$link) {
        die('Could not connect: '/* . mysqli_error()*/);
    }
 
    mysqli_select_db($link,"qlt") or die("cannot select DB");
 
	if($action=='create'){
	    $sql12="SELECT * FROM username WHERE type=0";
        $query12=mysqli_query($link,$sql12);
        $count2=mysqli_num_rows($query12);
        if($count2){        			   
        	return 'Admin đã được tạo rồi';
        }
       
       
        $sql11="SELECT * FROM username WHERE email='$fmail'";
        $query11=mysqli_query($link,$sql11);
        $count1=mysqli_num_rows($query11);
        if($count1){        			   
        	return 'Email này đã tồn tại';
        }
        
        $sql2="insert into username(`password`,`email`,`type`,`createdate`) 
            values('".md5($password1)."','$fmail','0','".date('Y-m-d')."')";
        $query3=@mysqli_query($link,$sql2);
        
        if(!$query3){
        			   
        	return 'Không thể thêm tài khoản admin.';
        }
        $done = 1;
        //header("Location: admin.php");
    ?>
<script>
window.location.href='admin.php'
</script>    
    <?php        
        die();
    }else{
        //login
        $sql11="SELECT * FROM username WHERE email='$fmail' and password='".md5($password1)."'";
        $query11=mysqli_query($link,$sql11);
        $count1=mysqli_num_rows($query11);
        if($count1){        			   
        	$done = 1;
            $_SESSION['email'] = $fmail;
            //header("Location: admin2.php");
    ?>
<script>
window.location.href='admin2.php'
</script>    
    <?php            
            die();
        }else{
            return 'Sai mật khẩu hoặc email';
        }
    }
     
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
     
<?php 
include "footer2.php";
?>