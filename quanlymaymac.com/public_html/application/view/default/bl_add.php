<div class="card-body">
     
    <form id="addblform" method="post" class="" novalidate="novalidate">
        <input type="hidden" name="kho" value="<?=empty($employee->vaitro)?$employee->kho:$employee->id?>" />
         
        <div class="position-relative form-group"><label for="exampleName" class="">Tên</label><input name="ten" id="exampleName" type="text" class="form-control"></div>
        
        <!-- luongngay luongthang ngaythang -->
        <div class="position-relative form-group"><label for="luongngay" class="">Lương ngày</label><input name="luongngay" id="luongngay" type="text" class="form-control number"></div>
        <div class="position-relative form-group"><label for="luongthang" class="">Lương tháng</label><input name="luongthang" id="luongthang" type="text" class="form-control number"></div>
        <div class="position-relative form-group"><label for="ngaythang" class="">Số ngày trong tháng</label><input name="ngaythang" value="26" id="ngaythang" type="text" class="form-control number required"></div>
         
        <div class="position-relative form-group"><label for="exampleText" class="">Ghi chú</label><textarea name="ghichu" id="exampleText" class="form-control"></textarea></div>
         
        <button class="mt-1 btn btn-primary" type="button" id="addbl">Thêm</button>
    </form>
</div>