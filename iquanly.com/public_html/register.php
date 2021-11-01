<?php 
include "header.php";
?>
          <script>
    var menu = '';
</script>
<!-- Banner -->
<div class="banner_top wow bounceIn" data-wow-duration="2s" data-wow-delay="0s">
    <div class="container">
        <div class="row txt_banner">
            <div class="col-md-5 col-sm-7">
                <h4 class="title_banner">
                    Phần mềm quản lý bán hàng, nhà hàng
                </h4>
                <ul class="list_adv">
                    <li>
                        <a href="#">
                            <img src="home/img/clock_icon.png">
                            <span>Tiết kiệm 80% thời gian quản lý cửa hàng, nhà hàng</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="home/img/cash.png">
                            <span>Giao diện tinh giản, thân thiện người dùng</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="home/img/caculator.png">
                            <span>Chức năng tính toán chi phí, lợi nhuận</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="home/img/chart.png">
                            <span>Hệ thống báo cáo chi tiết, không lo sợ thất thoát tiền</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="home/img/thumb_up.png">
                            <span>An toàn tuyệt đối, không lo mất dữ liệu với hệ thống backup thời gian thực</span>
                        </a>
                    </li>
                </ul>
                <a href="/register.php" class="btn btn-green" target="_blank">
                    <img src="home/img/dungthu.png"> 
                    <span>Dùng thử miễn phí</span>
                </a>
            </div>
        </div>
        <a class="scrollTo bullet-down hidden-xs hidden-sm" href="javascript:void(0);">
            <i class="fa fa-arrow-circle-o-down"></i>
        </a>
    </div>
</div>
            </br>
			</br>
             <div class="row" style="padding-left:15px;">
        <form role="form" method="POST">
            <div class="col-lg-6">
                <div class="well well-sm"><strong><span class="glyphicon glyphicon-asterisk"></span>Các ô bắt buộc</strong></div>
                <div class="form-group">
                    <label for="InputName">Họ và tên</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="InputName" id="InputName1" placeholder="Vd: Nguyễn Văn A" required value="<?=empty($_POST["InputName"])?'':$_POST["InputName"]?>" />
                        <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                    </div>
                </div>
				
				<div class="form-group">
                    <label for="InputName">Địa chỉ</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="address" id="address" placeholder="Vd: số nhà 12 đường Hàm Nghi P1 Q2 TPHCM" required value="<?=empty($_POST["address"])?'':$_POST["address"]?>" />
                        <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                    </div>
                </div>
				
				<div class="form-group">
                    <label for="InputName">Số điện thoại</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="telephone" id="telephone" placeholder="Vd: 0912345678"  required value="<?=empty($_POST["telephone"])?'':$_POST["telephone"]?>" />
                        <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                    </div>
                </div>
				<div class="form-group">
                    <label for="InputName">Tên cửa hàng</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="cuahang" id="cuahang" placeholder="Vd: Cửa hàng thời trang Thanh Xuân" required value="<?=empty($_POST["cuahang"])?'':$_POST["cuahang"]?>" />
                        <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                    </div>
				</div>
				<div class="form-group">
                    <label for="InputName">Tên truy cập</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="username" id="username" placeholder="Vd: thanhxuan" required value="<?=empty($_POST["username"])?'':$_POST["username"]?>" />
                        <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                    </div>
				</div>
				<div class="form-group">
                    <label for="InputName">Tên miền con</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="subdomain" id="subdomain" placeholder="Vd: thanhxuan" required value="<?=empty($_POST["subdomain"])?'':$_POST["subdomain"]?>" />
                        <span class="input-group-addon" id="basic-addon2">.iquanly.com</span>
                        
                    </div>
				</div>
								<div class="form-group">
                    <label for="InputName">Tên miền riêng (tùy chọn)</label>
                    <div class="input-group" style="width: 100%;">
                        <input type="text" class="form-control" name="domain" id="domain" placeholder="Vd: thanhxuan.com.vn" value="<?=empty($_POST["domain"])?'':$_POST["domain"]?>" />
                         
                    </div>
				</div>
				<div class="form-group">
                    <label for="InputName"> Mật khẩu</label>
                    <div class="input-group">
                        <input type="password" class="form-control" name="password1" id="password1" placeholder="Nhập mật khẩu" required>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                    </div>
				</div>
					<div class="form-group">
                    <label for="InputName"> Nhập lại mật khẩu</label>
                    <div class="input-group">
                        <input type="password" class="form-control" name="password2" id="password2" placeholder="Nhập lại mật khẩu" required>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                    </div>
				</div>
				<div class="form-group">
                    <label for="InputName">Lĩnh vực kinh doanh</label>
                    <div class="input-group" style="width: 100%;">
					   <select name='linhvuc' class=" form-control"  >
<option value='1'>Nhà hàng - Bar - Coffee</option>
<option value='2'>Bán hàng</option>
</select>            </div>
				</div>
				<div class="form-group">
                    <label for="InputEmail">Email</label>
                    <div class="input-group">
                        <input type="email" class="form-control" id="InputEmailFirst" name="InputEmail" placeholder="Vd: admin@gmail.com" required value="<?=empty($_POST["InputEmail"])?'':$_POST["InputEmail"]?>" />
                        <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for=""></label>
                    <div class="input-group">
                        <input type="submit" name="submit" id="submit" class="btn btn-info pull-right" value="Đăng ký" />
                    </div>
				</div>
				<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Thông báo</h4>
      </div>
      <div class="modal-body">
        <p>Gửi email xác thực thành công, mời quý khách vào kiểm tra hòm thư để xác thực tài khoản, lưu ý email có thể nằm trong hòm thư rác.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

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
    $name= $_POST["InputName"];
	$username= $_POST["username"];
	$cuahang= $_POST["cuahang"];
	$domain= $_POST["domain"];		
	$address= $_POST["address"];	
	$telephone= $_POST["telephone"];	
	$subdomain= $_POST["subdomain"];
	$password1= $_POST["password1"];
	$password2= $_POST["password2"];
	$linhvuc= $_POST["linhvuc"];	
	$fmail= $_POST["InputEmail"];
 
	$activation=md5(uniqid(rand()));
    
include "cf.php";
    
    if(!$name || !$username || !$cuahang || !$subdomain || !$address || !$telephone || !$password1 || !$password2 || !$fmail){
        return "Bạn chưa nhập đầy đủ thông tin";
    }
    
    if(!preg_match('/^[a-z0-9]+$/', $username) ) {
        return 'Tên truy cập sai định dạng';         
    } 
    if(!preg_match('/^[a-z0-9]+$/', $subdomain) ) {
        return 'Subdomain cập sai định dạng';         
    } 
			
	if ($password1!=$password2)
    {
        return "Đề nghị nhập lại password";
         
    }
	if(!preg_match('/^[0-9-+ ]+$/', $telephone) ) {
        return 'Sai định dạng số điện thoai';         
    } 	
    
    /*if($domain){
        if(!filter_var($domain,FILTER_VALIDATE_URL)){
            return 'Sai định dạng tên miền';   
        }
    } */
	 
    
    $link=mysqli_connect("$host", "$username1", "$password") or die("cannot connect to server");
    //if (!$link) {
    //    die('Could not connect: ' /*. mysqli_error()*/);
    //}
 
    mysqli_select_db($link,"qlt") or die("cannot select DB");
 
	
    $sql11="SELECT * FROM username WHERE namelogin='$username'";
    $query11=mysqli_query($link,$sql11);
    $count1=mysqli_num_rows($query11);
    if($count1){
    			   
    				return 'Username này đã tồn tại';
    }
    if($domain){
        $sql12="SELECT * FROM username WHERE domain='$domain'";
        $query12=mysqli_query($link,$sql12);
        $count2=mysqli_num_rows($query12);
        if($count2){
        			   
        				return 'Domain này đã tồn tại';
        }
    }
    $sql13="SELECT * FROM username WHERE subdomain='$subdomain'";
    $query13=mysqli_query($link,$sql13);
    $count3=mysqli_num_rows($query13);
    if($count3){
    			   
    				return 'SubDomain này đã tồn tại';
    }
    $sql14="SELECT * FROM username WHERE email='$fmail'";
    $query14=mysqli_query($link,$sql14);
    $count4=mysqli_num_rows($query14);
    if($count4){
    			   
    				return 'Email này đã tồn tại';
    }


	$sql2="insert into username(name,namelogin,namesite,password,domain,subdomain,email,phone,activation,address,`type`,createdate,trial) 
        values('$name','$username','$cuahang','".md5($password1)."','$domain','$subdomain','$fmail','$telephone','$activation',
               '$address','$linhvuc','".date('Y-m-d')."',1)";
    $query3=@mysqli_query($link,$sql2);
    
    if(!$query3){
    			   
    				return 'Không thể thêm tài khoản người dùng.';
    }
    
    //Chức năng mail
  
    //include('class.smtp.php');
    //include "class.phpmailer.php";
    include_once "phpmailer/class.phpmailer.php";
    $nFrom = "I Quản Lý";    //mail duoc gui tu dau, thuong de ten cong ty ban
     
    $mFrom = 'ee1a1f36541ff399b37ad4e0ebce460a';  //dia chi email cua ban 
    $mPass = '1ddcc9937fc2af81dd93eaa995941025';       //mat khau email cua ban
    $nTo = $fmail;// 'Huudepzai'; //Ten nguoi nhan
    
    $mail             = new PHPMailer();
    $body             = $nFrom.' kính chào quý khách<br /><br />Quý khách nhấp vào link sau để kích hoạt tài khoản: <a href="https://iquanly.com/xacnhan.php?ma='.$activation.'&name='.$subdomain.'">https://iquanly.com/xacnhan.php?ma='.$activation.'&name='.$subdomain.'</a>';	// Noi dung email
    $title = 'Đăng ký sử dụng phần mềm quản lý nhà hàng, cửa hàng của '.$nFrom;   //Tieu de gui mail
    $mail->IsSMTP();             
    $mail->CharSet  = "utf-8";
    $mail->SMTPDebug  = 0;   // enables SMTP debug information (for testing)
    $mail->SMTPAuth   = true;    // enable SMTP authentication
    //$mail->SMTPSecure = "ssl";   // sets the prefix to the servier
    $mail->Host       = "in-v3.mailjet.com";    // sever gui mail.
    $mail->Port       = 587;         // cong gui mail de nguyen
    // xong phan cau hinh bat dau phan gui mail
    $mail->Username   = $mFrom;  // khai bao dia chi email
    $mail->Password   = $mPass;              // khai bao mat khau
    $mail->SetFrom('longpt.8688@gmail.com', $nFrom);
    $mail->AddReplyTo('longpt.8688@gmail.com', $nFrom); //khi nguoi dung phan hoi se duoc gui den email nay
    $mail->Subject    = $title;// tieu de email 
    $mail->MsgHTML($body);// noi dung chinh cua mail se nam o day.
    $mail->AddAddress($fmail, $nTo);
	

    // thuc thi lenh gui mail 
    if(!$mail->Send()) {
        return 'Gửi email xác thực thất bại!';
         
    } else {
        $done = 1; 
        return 'Gửi email xác thực thành công, mời quý khách vào kiểm tra hòm thư để xác thực tài khoản, lưu ý email có thể nằm trong hòm thư rác.';
		
    }
}             
		
		?>
        <div class="col-lg-5 col-md-push-1">
        <?php if($mess){?>
            <div class="col-md-12">
                <div class="alert alert-success" <?php if($done){?>id="messs"<?php }?>>
                    <?=$mess?>
                </div>
            
            </div> <?php } ?>
            
            <div class="col-md-12">
                <div class="alert alert-success">
                    Ghi chú:
                    <ul>
                        <li>Số điện thoại: chỉ bao gồm 0 đến 9 hoặc thêm dấu +</li>
                        <li>Tên truy cập: viết không dấu in thường và không có khoảng trống, ví dụ thanhxuan1102</li>
                         <li>Tên miền con: viết không dấu in thường và không có khoảng trống, ví dụ thanhxuan thì bạn sẽ có địa chỉ truy cập là: http://thanhxuan.iquanly.com</li>
                    </ul>
                </div>
            
            </div>
        </div>
    </div>
    
</br>
</br>
<script> 

        $(document).ready(function () {
            setTimeout(function(){
                window.scrollTo(0,$('.wrap-ct>.row').offset().top-100)
            },1000)
            
            $('#username').keyup(function(e){
                $('#subdomain').val($(this).val())
            })
            
            if($('#messs').length){
                $('#myModal').on('hidden.bs.modal', function (e) {
                  document.location.href = "http://iquanly.com"
                })
                $('#myModal').modal()
            }
 
        });
    </script>
     
<?php 
include "footer2.php";
?>