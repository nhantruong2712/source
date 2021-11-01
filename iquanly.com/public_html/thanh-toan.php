<?php 
include "header.php";

$z = empty($_GET['t'])?'tinh-nang':$_GET['t'];
?>
          <script>
    var menu = 'thanh-toan';
</script>
<div>
    <div class="container">
        <div class="row">
            <h3 style="text-align: center;">1.Thanh toán chuyển khoản ngân hàng</h3>
            <div class="col-md-10 col-xs-12" style="color:black;padding:10px 0px;margin:0px auto;float:none;">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th style="text-align:center">Ngân hàng
                            </th>
                            <th style="text-align:center">Số Tài Khoản
                            </th>
                            <th style="text-align:center">Chủ tài khoản
                            </th>
                            <th style="text-align:center">Nội dung CK
                            </th>
                        </tr>
                        <tr>
                            <td style="vertical-align:middle;text-align:center">Vietcombank - CN Hà Tĩnh
                            </td>
                            <td style="vertical-align:middle;text-align:center">0201000076042
                            </td>
                            <td rowspan="1" style="vertical-align:middle;text-align:center">Phan Thanh Long
                            </td>
                            <td rowspan="1" style="vertical-align:middle;text-align:center">
                                Gia han TK: Ten_Dang_Nhap
                                <div style="color:#808080"><i>(Thay "Ten_Dang_Nhap" bằng tài khoản đăng nhập của bạn)</i></div>
                            </td>
                        </tr>
                        
                    </tbody>
                </table>
            </div>
            <h3 style="text-align: center;">2.Thanh toán tiền mặt</h3>
            <p style="text-align: left;font-size:14px;">- Nếu bạn ở Hà Nội hoặc Hà Tĩnh xin vui lòng liên hệ với IQUANLY theo số điện thoại 0945414343

để được hỗ trợ. Nhân viên của chúng tôi sẽ qua thu tiền mặt trực tiếp.</p>
			
            
        </div>
    </div>
</div>
 
            
<?php
include "footer.php";
?>