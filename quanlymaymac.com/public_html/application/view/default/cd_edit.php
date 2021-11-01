<div class="card-body">
     
    <form id="editcdform" method="post" class="" novalidate="novalidate">
        <input type="hidden" name="id" value="<?=$model->id?>" />
         
        <div class="position-relative form-group"><label for="exampleName" class="">Tên</label><input name="ten" value="<?=$model->ten?>" id="exampleName" type="text" class="form-control" /></div>
        <div class="position-relative form-group"><label for="examplePrice" class="">Đơn giá</label><input name="dongia" value="<?=$model->dongia?>" id="examplePrice" type="text" class="form-control number" /></div>         
        <div class="position-relative form-group"><label for="exampleText" class="">Ghi chú</label><textarea name="ghichu" id="exampleText" class="form-control"><?=$model->ghichu?></textarea></div>
        
         <div class="position-relative form-group"><label for="exampleEmail" class="">Nhóm</label><select name="nhom" id="exampleEmail" class="form-control">
            <option value="0">--Không chọn--</option>
            <?php foreach($categories as $category){?>
            <option value="<?=$category->id?>"<?=$category->id == $model->nhom ?' selected="selected"':''?>><?=str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;",$category->cap-1)?><?=$category->ten?></option>
            <?php }?>
        </select></div>
         
        <button class="mt-1 btn btn-primary" type="button" id="editcd">Sửa</button>
    </form>
</div>