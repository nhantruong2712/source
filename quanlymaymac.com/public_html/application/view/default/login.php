<!doctype html>
<html lang="en">
 
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Đăng nhập</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="<?=sitename?>" />

    <!-- Disable tap highlight on IE -->
    <meta name="msapplication-tap-highlight" content="no" />

<link href="main.87c0748b313a1dda75f5.css" rel="stylesheet" /></head>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha256-3blsJd4Hli/7wCQ+bmgXfOdK7p/ZUMtPXY08jmxSSgk=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha256-ENFZrbVzylNbgnXx0n3I1g//2WeO47XxoPe0vkp3NC8=" crossorigin="anonymous" />
<body>
<div class="app-container body-tabs-shadow">
        <div class="app-container">
            <div class="h-100 bg-animation">
                <div class="d-flex h-100 justify-content-center align-items-center">
                    <div class="mx-auto app-login-box col-md-8">
                        <div class="app-logo-inverse mx-auto mb-3"></div>
                        <div class="modal-dialog w-100 mx-auto">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <div class="h5 modal-title text-center">
                                        <h4 class="mt-2">
                                            <div>Xin chào,</div>
                                            <span>Điền thông tin đăng nhập dưới đây.</span>
                                        </h4>
                                    </div>
                                    <form class="">
                                        <div class="form-row">
                                            <div class="col-md-12">
                                                <div class="position-relative form-group"><input name="email" id="exampleEmail" placeholder="Email" type="email" class="form-control"></div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="position-relative form-group"><input name="password" id="examplePassword" placeholder="Mật khẩu" type="password" class="form-control"></div>
                                            </div>
                                        </div>
                                        <div class="position-relative form-check"><input name="check" id="exampleCheck" type="checkbox" class="form-check-input"><label for="exampleCheck" class="form-check-label">Giữ đăng nhập trong 30 ngày</label></div>
                                    </form>
                                    <div class="divider"></div>
                                    <h6 class="mb-0">Bạn chưa có tài khoản? <a href="javascript:void(0);" class="text-primary">Đăng ký</a></h6>
                                </div>
                                <div class="modal-footer clearfix">
                                    <div class="float-left"><a href="javascript:void(0);" class="btn-lg btn btn-link">Quên mật khẩu</a></div>
                                    <div class="float-right">
                                        <button class="btn btn-primary btn-lg">Đăng nhập</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center text-white opacity-8 mt-3">Bản quyền © 8688.vn 2020</div>
                    </div>
                </div>
            </div>
        </div>
</div>

</body>
<style>
body{
	background: url(https://vietekcons.vn/img_data/images/877788864075_First-Team.jpg) no-repeat 50% 50%;
}
</style>
<script>
$('.btn.btn-primary.btn-lg').click(function(){
    var k = $('form').serializeArray()
    for(var i in k){
        if(k[i].value==''){
            return toastr.warning('Hãy nhập đầy đủ thông tin đăng nhập')
        }
    }
    $.ajax({
        url: '/ajax/login',
        data: $('form').serialize(),
        type: 'POST',
        dataType: 'JSON',
        success: function(data){
            if(data.message)
                toastr.warning(data.message)
            else{
                document.cookie = 'uu='+data.uu;
                document.location.href=data.redi
            }            
        },
        error: function(){
            toastr.warning('Có lỗi xảy ra khi đăng nhập')
        }
    })
})
</script>
</html>