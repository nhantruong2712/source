<div class="card-body">
    <div class="card-header">Thêm sản phẩm cho nhóm định mức: <?=$model->ten?></div> 
    <form id="addspdmform" method="post" class="" novalidate="novalidate">
        <input type="hidden" name="kho" value="<?=$model->kho?>" />
        <input type="hidden" name="nhom" value="<?=$model->id?>" /> 
         
        <div class="position-relative form-group"><label for="sanpham" class="">Sản phẩm</label><select name="sanpham" id="sanpham" class="form-control required"></select></div>
        <div class="position-relative form-group"><label for="exampleSL" class="">Số lượng</label><input name="soluong" id="exampleSL" type="text" class="form-control number required"></div>    
         
        <button class="mt-1 btn btn-primary" type="button" id="addspdm">Thêm</button>
    </form>
</div>