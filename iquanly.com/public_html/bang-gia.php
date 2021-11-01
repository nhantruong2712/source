<?php 
include "header.php";

$z = empty($_GET['t'])?'tinh-nang':$_GET['t'];
?>
          <script>
    var menu = 'bang-gia';
</script>
<div class="phidichvu">
    <div class="container">
        <div class="row dv_up">
            <div class="col-md-6 col-xs-12 pull-right">
                <p class="p_chiphi">Chi phí quản lý</p>
                <p class="per_day"><label>3000đ</label> <span>/ngày</span></p>
            </div>
        </div>
        <div class="row dv_bottom">
            <div class="col-xs-4">
                <div class="in_col">
                    <p class="type_pack">
                        Gói 1 năm
                    </p>
                    <p class="price_pack"><span style="color:black"> 1.140.000 đ</span><br><span style="font-size:15px;color:black"> Tức 95k/tháng </span></p>
                    <p class="p_button">
                        <a class="btn btn-sm-orange" href="/thanh-toan.php">Mua</a>
                    </p>
                </div>
            </div>
            <div class="col-xs-4">
                <div class="in_col">
                    
                    <p class="type_pack">
                        Gói 2 Năm
                    </p>
                    <p class="price_pack"><span style="color:black">2.280.000 đ</span><br><span style="font-size:15px; color:red"> Tặng 6 tháng sử dụng </span></p>
                    <p class="p_button">
                        <a class="btn btn-sm-orange" href="/thanh-toan.php">Mua</a>
                    </p>
                </div>
            </div>
            <div class="col-xs-4">
                <div class="in_col">
                    <p class="type_pack">
                        Gói 3 Năm
                    </p>
                    <p class="price_pack"><span style="color:black">3.420.000 đ</span><br><span style="font-size:15px; color:red"> Tặng 12 tháng sử dụng</span></p>
                    <p class="p_button">
                        <a class="btn btn-sm-orange" href="/thanh-toan.php">Mua</a>
                    </p>
                </div>
            </div>
            
        </div>
    </div>
</div>
 
            
<?php
include "footer.php";
?>