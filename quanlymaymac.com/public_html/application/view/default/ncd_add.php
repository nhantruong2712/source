<div class="card-body">
     
    <form id="addncdform" method="post" class="" novalidate="novalidate">
        <input type="hidden" name="kho" value="<?=empty($employee->vaitro)?$employee->kho:$employee->id?>" />
         
        <div class="position-relative form-group"><label for="exampleName" class="">Tên</label><input name="ten" id="exampleName" type="text" class="form-control"></div>
        <div class="position-relative form-group"><label for="exampleEmail" class="">Nhóm cha</label><select name="cha" id="exampleEmail" class="form-control">
            <option value="0">--Không chọn--</option>
        <?php foreach($categories as $category){?>
            <option value="<?=$category->id?>"<?=$category->id == $id ?' selected="selected"':''?>><?=str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;",$category->cap-1)?><?=$category->ten?></option>
        <?php }?>
        </select></div>
         
        <button class="mt-1 btn btn-primary" type="button" id="addncd">Thêm</button>
    </form>
</div>