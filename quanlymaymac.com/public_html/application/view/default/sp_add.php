<div class="card-body">
     
    <form id="addspform" method="post" class="" novalidate="novalidate">
        <input type="hidden" name="kho" value="<?=empty($employee->vaitro)?$employee->kho:$employee->id?>" />
        
        <div class="position-relative form-group"><label for="cha" class="">SP cha</label><select name="cha" id="cha" class="form-control"></select></div>         
        <div class="position-relative form-group"><label for="exampleName" class="">Tên<abbr title="required">*</abbr></label><input name="ten" id="exampleName" type="text" class="form-control"></div>
        <div class="position-relative form-group"><label for="exampleMa" class="">Mã SP<abbr title="required">*</abbr></label><input name="ma" id="exampleMa" type="text" class="form-control ma required"></div>
        <div class="position-relative form-group"><label for="danhmuc" class="">Danh mục<abbr title="required">*</abbr></label><?=$danhmuc?></div>
        <div class="position-relative form-group"><label for="exampleSL" class="">Số lượng</label><input name="soluong" id="exampleSL" type="text" class="form-control number"></div>    
        <div class="position-relative form-group"><label for="exampleDV" class="">Đơn vị</label><input name="donvi" id="exampleDV" type="text" class="form-control"></div>
        <div class="position-relative form-group"><label for="examplePrice" class="">Giá</label><input name="gia" id="examplePrice" type="text" class="form-control number"></div>         
        <div class="position-relative form-group"><label for="nhom" class="">Nhóm định mức</label><select name="nhom" id="nhom" class="form-control"></select></div>
        <div class="position-relative form-group"><label for="exampleText" class="">Ghi chú</label><textarea name="ghichu" id="exampleText" class="form-control"></textarea></div>
        
        <div class="main-card mb-3 card" style="display: none;">
            <div class="card-body"><h5 class="card-title">Định mức</h5>
                <div class="table-responsive">
                    <table class="mb-0 table table-sanpham">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Sản phẩm</th>
                            <!--th>Mã</th-->
                             
                            <th class="soluong">Số lượng</th>
                             
                        </tr>
                        </thead>
                        <tbody>
                        
                        <tr>
                            <th scope="row">#1</th>
                            <td></td>
                            <!--td></td-->
                             
                            <td></td>
                             
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
         
        <button class="mt-1 btn btn-primary" type="button" id="addsp">Thêm</button>
    </form>
</div>