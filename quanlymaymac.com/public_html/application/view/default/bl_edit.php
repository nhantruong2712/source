<div class="card-body">
     
    <form id="editpbform" method="post" class="" novalidate="novalidate">
        <input type="hidden" name="id" value="<?=$model->id?>" />
         
        <div class="position-relative form-group"><label for="exampleName" class="">Tên</label><input name="ten" value="<?=$model->ten?>" id="exampleName" type="text" class="form-control" /></div>
        
        <!-- luongngay luongthang ngaythang -->
        <div class="position-relative form-group"><label for="luongngay" class="">Lương ngày</label><input name="luongngay" value="<?=$model->luongngay?>" id="luongngay" type="text" class="form-control number"></div>
        <div class="position-relative form-group"><label for="luongthang" class="">Lương tháng</label><input name="luongthang" value="<?=$model->luongthang?>" id="luongthang" type="text" class="form-control number"></div>
        <div class="position-relative form-group"><label for="ngaythang" class="">Số ngày trong tháng</label><input name="ngaythang" value="<?=$model->ngaythang?>" id="ngaythang" type="text" class="form-control number required"></div>
         
         
        <div class="position-relative form-group"><label for="exampleText" class="">Ghi chú</label><textarea name="ghichu" id="exampleText" class="form-control"><?=$model->ghichu?></textarea></div>
         
        <button class="mt-1 btn btn-primary" type="button" id="editpb">Sửa</button>
    </form>
</div>