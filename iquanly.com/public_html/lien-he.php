<?php 
include "header.php";

$z = empty($_GET['t'])?'tinh-nang':$_GET['t'];
?>
          <script>
    var menu = 'lien-he';
</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.11.1/jquery.validate.min.js"></script>
 
<div class="list_khach hang">
    <div class="container">
        <div class="row title_list">
            <h3 class="title">Liên hệ IQUANLY!
            </h3>

            <p class="sub_des" >Tổng đài chăm sóc khách hàng <strong>0945414343</strong></p>
 
        </div>
        <div class="row lienhe">
            <div class="col-md-6 col-sm-12 gui_contact">
                <form class="form-horizontal" id="contact">
                    <div class="contact-left">
                        <div class="title_contact">Gửi yêu cầu liên hệ</div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label"><span class="lbl">Họ và Tên <span style="color: red">*</span></span></label>
                            <div class="col-sm-9">
                                <input type="text" id="ip-username" value="" required="" name="contact_name" class="name input_controll">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label"><span class="lbl">Điện thoại <span style="color: red">*</span></span></label>
                            <div class="col-sm-9">
                                <input type="text" id="ip-phone" value="" required="" name="contact_phone" class="phone input_controll">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label"><span class="lbl">Email <span style="color: red">*</span></span></label>
                            <div class="col-sm-9">
                                <input type="email" id="ip-email" value="" required="" name="contact_email" class="email input_controll">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label"><span class="lbl">Nội dung <span style="color: red">*</span></span></label>
                            <div class="col-sm-9">
                                <textarea required="" id="txt-content" name="contact_msg" class="msg input_controll"></textarea>
                            </div>
                        </div>
                        <div class="form-group form-btn-red">
                            <label class="col-sm-3 control-label"><span class="lbl">&nbsp;</span></label>
                            <div class="col-sm-9">
                                <input id="btn-submit" type="button" name="contact_submit" class="btnRed btn-contact-mb " value="Gửi liên hệ">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-6 col-sm-12 list_contact">
                <div class="contact-right">
                    <div class="title_contact">Đến với IQUANLY</div>
                    <div class="contactBox fs14 txtGay">
                        <div class="name_vp">Trụ Sở Chính</div>
                        <p>369 Hải Thượng Lãn Ông - TP Hà Tĩnh</p>

                         
                        <iframe src="https://www.google.com/maps/embed?pb=!1m10!1m8!1m3!1d7574.487231642451!2d105.8963569!3d18.3361671!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2s!4v1506274726952" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Liên hệ</h4>
            </div>
            <div class="modal-body">
                Cám ơn bạn đã gửi ý kiến. Bộ phận chăm sóc khách hàng sẽ tiếp nhận ý kiến và liên lạc lại với bạn trong thời gian sớm nhất.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Thoát</button>
            </div>
        </div>
    </div>
</div>

<script>
    /****DangCM read cookie****/
    function createCookie(name, value, minute) {
        if (minute) {
            var date = new Date();
            date.setTime(date.getTime() + (minute * 60 * 1000));
            var expires = "; expires=" + date.toGMTString();
        }
        else var expires = "";
        document.cookie = name + "=" + value + expires + "; path=/";
    };

    function readCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return '';
    };
    $('#btn-submit').click(function () {
        var vtrue = false;
        if ($('#contact').valid()) {
            vtrue = true;
            var username = readCookie("cmd_username_comment");
            if (username != '') {
                //alert('Cám ơn bạn đã gửi ý kiến cho chúng tôi. Chúng tôi sẽ liên lạc lại với bạn về ý kiến của bạn.');
                $('#myModal').modal('show');
                resetText();
            } else {
                $.ajax({
                    type: "POST",
                    url: "contact.php",
                    data: { UserName: $('#ip-username').val(), Phone: $('#ip-phone').val(), Email: $('#ip-email').val(), Content: $('#txt-content').val(), }
                }).done(function (data) {
                    if (data.result == 1) { // Thành công
                        //alert(data.message);
                        $('#myModal').modal('show');
                        createCookie("cmd_username_comment", "done_comment", 10);
                        resetText();
                    } else {
                        alert(data.message);
                    }
                }).error(function (xhr, ajaxOptions, thrownError) {
                    if (xhr.status == '500') {
                        alert("Nội dung bạn gửi không được có các thẻ html!");
                    }
                    else {
                        alert("Có lỗi xảy ra trong quá trình truyền dữ liệu lên server!");
                    }

                });;
            }
        }
        return vtrue;
    });
    function resetText() {
        $("#ip-username").val("");
        $("#txt-content").val("");
        $("#ip-phone").val("");
        $("#ip-email").val("");
    }
</script>
            
<?php
include "footer.php";
?>