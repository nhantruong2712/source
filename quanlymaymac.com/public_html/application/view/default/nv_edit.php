<div class="card-body">
     
    <form id="editnvform" method="post" class="" novalidate="novalidate">
        <input name="id" type="hidden" class="form-control" value="<?=$model->id?>">
        <div class="position-relative form-group"><label for="exampleName" class="">Tên</label><input name="ten" id="exampleName" type="text" class="form-control" value="<?=$model->ten?>"></div>
        <div class="position-relative form-group"><label for="exampleEmail" class="">Email</label><input name="email" id="exampleEmail" type="email" class="form-control" value="<?=$model->email?>"></div>
        <div class="position-relative form-group"><label for="examplePassword" class="">Mật khẩu</label><input name="password" id="examplePassword" type="password" class="form-control"></div>
        <div class="position-relative form-group"><label for="examplePassword2" class="">Nhập lại</label><input name="_password" id="examplePassword2" placeholder="Nhập lại mật khẩu" type="password" class="form-control"></div>
        
        <div class="position-relative form-group"><label for="phongban" class="">Phòng ban</label><?=$phongban?></div>
        <div class="position-relative form-group"><label for="bangluong" class="">Bảng lương</label><?=$bangluong?></div>
        <div class="position-relative form-group"><label for="vaitro" class="">Vai trò</label><?=$vaitro?></div>
        
        <div class="position-relative form-group"><label for="exampleAdd" class="">Địa chỉ</label><input name="diachi" id="exampleAdd" type="text" class="form-control" value="<?=$model->diachi?>"></div>
        <div class="position-relative form-group"><label for="examplePhone" class="">Số điện thoại</label><input name="sdt" id="examplePhone" type="text" class="form-control" value="<?=$model->sdt?>"></div>
        
        <div class="position-relative form-group"><label for="exampleText" class="">Ghi chú</label><textarea name="ghichu" id="exampleText" class="form-control"><?=$model->ghichu?></textarea></div>
         
        <button class="mt-1 btn btn-primary" type="button" id="editnv">Sửa</button>
    </form>
</div>