<?php 
include "header.php";

$z = empty($_GET['t'])?'tinh-nang':$_GET['t'];
?>
          <script>
    var menu = 'tinh-nang';
</script>
<div class="sub_menu">
    <div class="clb nav-second">
        <div class="container">
            <ul id="MenuTinhNang">
                <li><a href="/tinh-nang.php" id="tinh-nang" class="active"><span>Tổng hợp tính năng</span></a></li>
                <li><a href="?t=thietlap" id="thietlap"><span>Thiết lập</span></a></li>                 
                <li><a href="?t=doitac" id="doitac"><span>Đối tác</span></a></li>
                <li><a href="?t=hanghoa" id="hanghoa"><span>Hàng hóa</span></a></li>
                <li><a href="?t=giaodich" id="giaodich"><span>Giao dịch</span></a></li>
                <li><a href="?t=soquy" id="soquy"><span>Sổ quỹ</span></a></li>
                <li><a href="?t=baocao" id="baocao"><span>Báo cáo</span></a></li>
                <li><a href="?t=thungan" id="thungan"><span>Thu ngân</span></a></li>
                <li><a href="?t=nhabep" id="nhabep"><span>Nhà bếp</span></a></li>
                <li><a href="?t=phongban" id="phongban"><span>Phòng/bàn</span></a></li>
            </ul>
        </div>
    </div>
                
    <div class="nav-second-mobile hidden-lg hidden-md">
        <div class="wrapper-container">
            <div class="nav-mobile-top">
                <i class="fa fa-angle-double-down"></i>
                <div class="title-tn"><span>Tổng hợp tính năng</span></div>
            </div>
            <ul class="nav-mobile">
                <li class="lt"><a class="active" href="/tinh-nang.php"><span>Tổng hợp tính năng</span></a></li>
                <li><a href="?t=thietlap" id="thietlap"><span>Thiết lập</span></a></li>                 
                <li><a href="?t=doitac" id="doitac"><span>Đối tác</span></a></li>
                <li><a href="?t=hanghoa" id="hanghoa"><span>Hàng hóa</span></a></li>
                <li><a href="?t=giaodich" id="giaodich"><span>Giao dịch</span></a></li>
                <li><a href="?t=soquy" id="soquy"><span>Sổ quỹ</span></a></li>
                <li><a href="?t=baocao" id="baocao"><span>Báo cáo</span></a></li>
                <li><a href="?t=thungan" id="thungan"><span>Thu ngân</span></a></li>
                <li><a href="?t=nhabep" id="nhabep"><span>Nhà bếp</span></a></li>
                <li><a href="?t=phongban" id="phongban"><span>Phòng/bàn</span></a></li>
            </ul>
        </div>
    </div>
</div>

<script>
    var subMenu = '<?=$z?>';
</script>
<div class="list_tinhnang">
    <div class="container">
        <?php include "tinhnang/".$z.".php"; ?>
    </div>
</div>
            
<?php
include "footer.php";
?>