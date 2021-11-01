<div class="card-body">
    <div class="card-header">Thêm sản phẩm con: <?=$model->ten?></div> 
    <form id="addchildspform" method="post" class="" novalidate="novalidate">
        <input type="hidden" name="kho" value="<?=$model->kho?>" />
        <input type="hidden" name="id" value="<?=empty($model->concua)?$model->id:$model->concua?>" />
        
        <div class="position-relative form-group"><label for="giamtru" class="">Giảm trừ số lượng</label>
        <div>
            <div class="custom-checkbox custom-control custom-control-inline">
                <input type="checkbox" name="giamtru" id="giamtru1" class="custom-control-input" value="2">
                <label class="custom-control-label" for="giamtru1">Trừ bằng Số lượng mỗi sản phẩm</label>
            </div>
            <div class="custom-checkbox custom-control custom-control-inline">
                <input type="checkbox" name="giamtru" id="giamtru2" class="custom-control-input" value="1">
                <label class="custom-control-label" for="giamtru2">Trừ 1</label>
            </div>
        </div></div>
         
        <div class="position-relative form-group"><label for="soluongthem" class="">Số lượng thêm <abbr title="Bắt buộc">*</abbr></label><input name="soluongthem" id="soluongthem" type="text" class="form-control number required"></div>   
        <div class="position-relative form-group"><label for="ten" class="">Tên <abbr title="Bắt buộc">*</abbr></label><input name="ten" id="ten" value="<?=$model->ten?> " type="text" class="form-control required"></div>
        <div class="position-relative form-group"><label for="sososauten" class="">Số số sau tên <abbr title="Bắt buộc">*</abbr></label><input name="sososauten" id="sososauten" type="text" class="form-control number required"></div>
        <div class="position-relative form-group"><label for="sotenbatdau" class="">STT tên bắt đầu</label><input name="sotenbatdau" id="sotenbatdau" type="text" class="form-control number"></div>
        
        <div class="position-relative form-group"><label for="ma" class="">Mã SP <abbr title="Bắt buộc">*</abbr></label><input name="ma" id="ma" value="<?=$model->ma?>" type="text" class="form-control ma required"></div>
        <div class="position-relative form-group"><label for="sososauma" class="">Số số sau mã <abbr title="Bắt buộc">*</abbr></label><input name="sososauma" id="sososauma" type="text" class="form-control number required"></div>
        <div class="position-relative form-group"><label for="somabatdau" class="">STT mã bắt đầu</label><input name="somabatdau" id="somabatdau" type="text" class="form-control number"></div>
        
                                      
        <div class="position-relative form-group"><label for="soluong" class="">Số lượng mỗi SP <abbr title="Bắt buộc">*</abbr></label><input name="soluong" id="soluong" value="<?=$model->soluong?>" type="text" class="form-control number required"></div>    
         
        <button class="mt-1 btn btn-primary" type="button" id="addchildsp">Thêm</button>
    </form>
</div>