 
          <!-- Footer -->
          <div class="footer">
				<!--
                <div class="container">
                    <div class="row menu_footer">
                        <div class="col-md-2 col-xs-6">
                            <h3 class="h3_main"><a href="home/img/index.html">Trang chủ</a></h3>
                            <ul class="ul_footer">
                                <li>
                                    <a href="tinh-nang.html">Tính năng</a>
                                </li>
                                <li>
                                    <a href="khach-hang.html">Khách hàng</a>
                                </li>
                                <li>
                                    <a href="bang-gia.html">Bảng giá</a>
                                </li>
                                <li>
                                    <a href="lien-he.html">Liên hệ</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-4 nghanh  col-xs-12">
                            <h3 class="h3_main"><a href="#">Hỗ trợ</a></h3>
                            <ul class="ul_footer">
                                <li>
                                    <a href="tinh-nang.html?t=camdo">Cầm đồ</a>
                                </li>
                                <li>
                                    <a href="tinh-nang.html?t=vayho">Bát họ</a>
                                </li>
                                <li>
                                    <a href="tinh-nang.html?t=khachhang">Khách hàng</a>
                                </li>
                                <li>
                                    <a href="tinh-nang.html?t=cuahang">Cửa hàng</a>
                                </li>
                                <li>
                                    <a href="tinh-nang.html?t=thuchi">Thu chi</a>
                                </li>
                                <li>
                                    <a href="tinh-nang.html?t=nguonvon">Nguồn vốn</a>
                                </li>
                                <li>
                                    <a href="tinh-nang.html?t=nhanvien">Nhân viên</a>
                                </li>
                                <li>
                                    <a href="tinh-nang.html?t=baocao">Báo cáo</a>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="col-md-6 col-xs-12">
                            <div class="col-md-6 col-xs-6">
                                <h3 class="h3_main"><a href="#">Liên hệ</a></h3>
                                <ul class="ul_footer">
                                    <li>
                                        <a href="#">Hotline : 091.234.222</a>
                                    </li>
                                    <li>
                                        Email :
                                        <a class="active">quanlytiemcamdo.com@gmail.com</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6 col-xs-6">
                                <h3 class="h3_main"><a href="#">Mạng xã hội</a></h3>
                                <div class="social">
                                    <a href="#" class="a_social fa fa-facebook"></a>
                                    <a href="#" class="a_social fa fa-google-plus"></a>
                                    <a href="#" class="a_social fa fa-youtube"></a>
                                </div>
                            </div>
                            <div class="col-md-12 col-xs-12">
                                <h3 class="h3_main"><a href="#">Địa chỉ</a></h3>
                                <ul class="ul_footer">
                                    <li>
                                        Tầng 1, Trung tâm Thương mại Lotte, 54 Liễu Giai, Ba Đình, Hà Nội
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                    </div>
                </div>-->
                <div class="copyRight">
                    <div class="container">
                         © Copyright 2015 - 2019. IQUANLY là sản phẩm của Công ty Cổ phần Công nghệ 8688.vn<br />
                         Địa chỉ: 369 Hải Thượng Lãn Ông - TP Hà Tĩnh<br />
                         Hotline: <font color="red">0945414343<br /><br /><br /><br /></font>
                    </div>
                </div>
            </div>      
        </div>
    </div>
     
    <script>
        $("#MenuTinhNang li a").each(function () {
            //alert(subMenu);
            //alert(this.id);
            if (typeof subMenu !== 'undefined' && this.id != '' && this.id == subMenu) {
                $(this).addClass("active");
            } else {
                $(this).removeClass("active");
            }
        });
    </script>
    <script>
        $('.scrollTo').click(function () {
            var topContent = $('#list_why_like').offset().top - 60;
            $("body,html").animate({
                scrollTop: topContent
            }, "normal");
            $("#page").animate({
                scrollTop: topContent
            }, "normal");
            return !1
        })

        $(window).scroll(function () {
            if ($(window).scrollTop() >= 200) {
                $('.go_top').css({ 'opacity': '1' });
            } else {
                $('.go_top').css({ 'opacity': '0' });
            }
        });

        $('.nav-mobile-top > .fa').click(function () {
            $('.nav-mobile').toggleClass('mnopen');
        })

        $(document).ready(function () {

   wow = new WOW(
                      {
                      boxClass:     'wow',      // default
                      animateClass: 'animated', // default
                      offset:       0,          // default
                      mobile:       true,       // default
                      live:         true        // default
                    }
                    )
                    wow.init();


            $("ul.nav-mecash>li").each(function ()
            {
                if (typeof menu !== 'undefined' && this.id != '' && this.id == menu) {
                    $(this).addClass("active");
                } else {
                    $(this).removeClass("active");
                }
            });

            $("ul.nav-mecash>li").hover(
              function () {
                  if (typeof menu !== 'undefined' && this.id != '' && this.id != menu) {
                      $(this).addClass("active");
                  } 
              }, function () {
                  if (typeof menu !== 'undefined' && this.id != '' && this.id != menu) {
                      $(this).removeClass("active");
                  }
              }
            );
           
        });
    </script>
     
<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/566e7bc3373990810a7653f4/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
</body></html>