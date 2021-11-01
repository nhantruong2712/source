<div class="card-body">
     
    <form id="editspform" method="post" class="" novalidate="novalidate">
        <input type="hidden" name="id" value="<?=$model->id?>" />
        <!--input type="hidden" name="kho" id="kho" value="<?=$model->kho?>" /-->
        <?php if($model->cha){?>
        <div class="position-relative form-group"><label class="">Sản phẩm cha:</label><a href="?filter[product]=<?=$model->cha->ma?>"><?=$model->cha->ten?></a></div>
        <?php }?> 
        <div class="position-relative form-group"><label for="exampleName" class="">Tên</label><input name="ten" value="<?=$model->ten?>" <?=empty($model->concua)?'':'readonly'?> id="exampleName" type="text" class="form-control" /></div>
        
        <div class="position-relative form-group"><label for="exampleMa" class="">Mã SP</label><input name="ma" value="<?=$model->ma?>" <?=empty($model->concua)?'':'readonly'?> id="exampleMa" type="text" class="form-control ma required"></div>
        <div class="position-relative form-group"><label for="danhmuc" class="">Danh mục</label><?=$danhmuc?></div>
        <div class="position-relative form-group"><label for="exampleSL" class="">Số lượng</label><input name="soluong" value="<?=$model->soluong?>" id="exampleSL" type="text" class="form-control number"></div>    
        <div class="position-relative form-group"><label for="exampleDV" class="">Đơn vị</label><input name="donvi" value="<?=$model->donvi?>" <?=empty($model->concua)?'':'readonly'?> id="exampleDV" type="text" class="form-control"></div>
        
        <div class="position-relative form-group"><label for="examplePrice" class="">Giá</label><input name="gia" value="<?=$model->gia?>" <?=empty($model->concua)?'':'readonly'?> id="examplePrice" type="text" class="form-control number" /></div>
        
        <div class="position-relative form-group"><label for="nhom" class="">Nhóm định mức</label><select name="nhom" id="nhom" <?=empty($model->concua)?'':'readonly'?> style="width: 100%;">
            <?php if($model->nhom){?>
            <option value="<?=$model->nhom->id?>" selected="selected"><?=$model->nhom->ten?></option>
            <?php }?>
        </select></div>
                 
        <div class="position-relative form-group"><label for="exampleText" class="">Ghi chú</label><textarea <?=empty($model->concua)?'':'readonly'?> name="ghichu" id="exampleText" class="form-control"><?=$model->ghichu?></textarea></div>
         
        <button class="mt-1 btn btn-primary" type="button" id="editsp">Sửa</button>
        
        <?php if($model->dinhmuc){?>
        <div class="main-card mb-3 card">
            <div class="card-body"><h5 class="card-title">Định mức</h5>
                <div class="table-responsive">
                    <table class="mb-0 table table-sanpham">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Sản phẩm</th>
                            <th>Mã</th>
                             
                            <th class="soluong">Số lượng</th>
                             
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($model->dinhmuc as $k=>$dm){?>
                        <tr>
                            <th scope="row">#<?=$k+1?></th>
                            <td><?=$dm->sanpham->ten?></td>
                            <td><?=$dm->sanpham->ma?></td>
                             
                            <td><?=$dm->soluong?></td>
                             
                        </tr><?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php }?>
    </form>
</div>
<style>
select[readonly] + .select2-container {
  pointer-events: none;
  touch-action: none;
}
select[readonly]+.select2-container .select2-selection--single{
    background-color: #e9ecef;
}
select[readonly]{
  pointer-events: none;
  touch-action: none;
}
</style>