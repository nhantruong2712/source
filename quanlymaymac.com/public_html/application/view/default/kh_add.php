<div class="card-body">
     
    <form id="addkhform" method="post" class="" novalidate="novalidate">
        <input type="hidden" name="kho" value="<?=empty($employee->vaitro)?$employee->kho:$employee->id?>" />
        <input type="hidden" name="vaitro" value="3" />
        <div class="position-relative form-group"><label for="exampleName" class="">Tên</label><input name="ten" id="exampleName" type="text" class="form-control"></div>
        <div class="position-relative form-group"><label for="exampleEmail" class="">Email</label><input name="email" id="exampleEmail" type="email" class="form-control"></div>
         
        <div class="position-relative form-group"><label for="exampleAdd" class="">Địa chỉ</label><input name="diachi" id="exampleAdd" type="text" class="form-control"></div>
        <div class="position-relative form-group"><label for="examplePhone" class="">Số điện thoại</label><input name="sdt" id="examplePhone" type="text" class="form-control"></div>
        
        <div class="position-relative form-group"><label for="exampleText" class="">Ghi chú</label><textarea name="ghichu" id="exampleText" class="form-control"></textarea></div>
         
        <button class="mt-1 btn btn-primary" type="button" id="addkh">Thêm</button>
    </form>
</div>