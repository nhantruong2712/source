<div class="card-body">
     
    <form id="editnccform" method="post" class="" novalidate="novalidate">
        <input type="hidden" name="id" value="<?=$model->id?>" />
         
        <div class="position-relative form-group"><label for="exampleName" class="">Tên</label><input name="ten" value="<?=$model->ten?>" id="exampleName" type="text" class="form-control" /></div>
        <div class="position-relative form-group"><label for="exampleEmail" class="">Email</label><input name="email" value="<?=$model->email?>" id="exampleEmail" type="email" class="form-control" /></div>
         
        <div class="position-relative form-group"><label for="exampleAdd" class="">Địa chỉ</label><input name="diachi" value="<?=$model->diachi?>" id="exampleAdd" type="text" class="form-control" /></div>
        <div class="position-relative form-group"><label for="examplePhone" class="">Số điện thoại</label><input name="sdt" value="<?=$model->sdt?>" id="examplePhone" type="text" class="form-control" /></div>
        
        <div class="position-relative form-group"><label for="exampleText" class="">Ghi chú</label><textarea name="ghichu" id="exampleText" class="form-control"><?=$model->ghichu?></textarea></div>
         
        <button class="mt-1 btn btn-primary" type="button" id="editncc">Sửa</button>
    </form>
</div>