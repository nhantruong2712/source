<div class="card-body">
     
    <form id="addvtform" method="post" class="" novalidate="novalidate">
        <input type="hidden" name="kho" value="<?=empty($employee->vaitro)?$employee->kho:$employee->id?>" />
         
        <div class="position-relative form-group"><label for="exampleName" class="">Tên</label><input name="ten" id="exampleName" type="text" class="form-control"></div>
         
        <div class="position-relative form-group"><label for="exampleText" class="">Ghi chú</label><textarea name="ghichu" id="exampleText" class="form-control"></textarea></div>
         
        <button class="mt-1 btn btn-primary" type="button" id="addvt">Thêm</button>
    </form>
</div>