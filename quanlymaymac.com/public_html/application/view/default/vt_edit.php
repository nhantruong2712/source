<div class="card-body">
     
    <form id="editvtform" method="post" class="" novalidate="novalidate">
        <input type="hidden" name="id" value="<?=$model->id?>" />
         
        <div class="position-relative form-group"><label for="exampleName" class="">Tên</label><input name="ten" value="<?=$model->ten?>" id="exampleName" type="text" class="form-control" /></div>
         
        <div class="position-relative form-group"><label for="exampleText" class="">Ghi chú</label><textarea name="ghichu" id="exampleText" class="form-control"><?=$model->ghichu?></textarea></div>
         
        <button class="mt-1 btn btn-primary" type="button" id="editvt">Sửa</button>
    </form>
</div>