<div class="card-body">
    
    <form id="editdinhmucform" method="post" class="" novalidate="novalidate">
        <input type="hidden" name="id" value="<?=$model->id?>" />  
        <div class="position-relative form-group"><label for="sanpham" class="">Sản phẩm</label><select name="sanpham" id="sanpham" class="form-control required">
            <option value="<?=$model->sanpham->id?>"><?=$model->sanpham->ten?></option>
        </select></div>
        <div class="position-relative form-group"><label for="exampleSL" class="">Số lượng</label><input name="soluong" id="exampleSL" type="text" value="<?=$model->soluong?>" class="form-control number required"></div>    
         
        <button class="mt-1 btn btn-primary" type="button" id="editdinhmuc">Sửa</button>
    </form>
</div>