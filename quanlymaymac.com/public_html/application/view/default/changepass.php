<? include(dirname(__FILE__).'/headeradmin.php') ?>

    <div class="panel panel-primary">
		<div class="panel-body" style="border-radius: 0px;">
			<h4 class="text-center" style="margin-bottom: 25px;">Đổi Mật Khẩu</h4>
				<form id="changepass" onsubmit="changepass();return false;" action="" class="form-horizontal" style="margin-bottom: 0px !important;" method="POST">
						
						<div class="form-group">
							
<div class="col-sm-12">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-envelope-o"></i></span>
									<input type="password" class="form-control" name="pass" id="pass" placeholder="Mật Khẩu Cũ">
								</div>
    <br>
    <div class="input-group">
									<span class="input-group-addon"><i class="fa fa-envelope-o"></i></span>
									<input type="password" class="form-control" name="newpass" id="newpass" placeholder="Mật Khẩu Mới">
								</div>
    <br>
    <div class="input-group">
									<span class="input-group-addon"><i class="fa fa-envelope-o"></i></span>
									<input type="password" class="form-control" name="repass" id="repass" placeholder="Nhập Lại Mật Khẩu Mới">
								</div>
							</div>
						</div>
					</form>
					
		</div>
		<div class="panel-footer">
			
			<div class="pull-right">
				<a href="javascript:changepass()" class="btn btn-success">Thay Đổi  <i class="fa fa-arrow-right"></i></a>
			</div>
		</div>
	</div>

<script>
function changepass(){
    var p = $('#pass').val(), n=$('#newpass').val(), r = $('#repass').val()
    if(p==''||n==''||r==''){
        toastr.warning("Hãy điền form đầy đủ")
        return
    }
    if(n!=r){
        toastr.warning("Mật khẩu nhập lại không khớp")
        return
    }
    if(n==p){
        toastr.warning("Mật khẩu cũ phải khác mật khẩu mới")
        return
    }
    $.ajax({
        url: 'ajax/changepass',
        type: 'POST',
        dataType: 'JSON',
        data: {
            p: p,
            n: n,
        },
        success: function(j){
            if(j.success==1){
                toastr.success(j.message)
            }else{
                toastr.error(j.message)
            }
        },
        error: function(e){
            toastr.error(e.message)
        }
    })
}
</script>
            
<? include(dirname(__FILE__).'/footeradmin.php') ?>