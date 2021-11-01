 
                <div class="app-main__inner">
                     
                    <div class="tab-content">
                        <div class="tab-pane tabs-animation fade active show" id="tab-content-0" role="tabpanel">
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Thông tin</h5>
                                    <form class="">
                                        <div class="position-relative row form-group"><label for="ma" class="col-sm-3 col-form-label">Mã phiếu</label>
                                            <div class="col-sm-9"><?=$model->ma?></div>
                                        </div>
                                        <div class="position-relative row form-group"><label for="ngay" class="col-sm-3 col-form-label">Thời gian</label>
                                            <div class="col-sm-9">
                                                <input name="ngay" id="ngay" value="<?=date('d/m/Y H:i:s',$model->ngay)?>" class="form-control input-mask-trigger" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy HH:MM:ss" im-insert="false">
                                                <input type="hidden" name="id" id="id" value="<?=$model->id?>"> 
                                            </div>
                                        </div>
                                        <div class="position-relative row form-group"><label for="tu" class="col-sm-3 col-form-label">Từ</label>
                                            <div class="col-sm-9"><?=$model->tu2?> - <a href="/<?=$model->tu?>?filter[search]=<?=$model->doitac->ten?>"><?=$model->doitac->ten?></a></div>
                                        </div> 
                                        <div class="position-relative row form-group"><label for="ghichu" class="col-sm-3 col-form-label">Ghi chú</label>
                                            <div class="col-sm-9"><textarea name="ghichu" id="ghichu" class="form-control"><?=$model->ghichu?></textarea></div>
                                        </div>
                                          
                                    </form>
                                </div>
                            </div>
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Danh sách sản phẩm</h5>
                                    <div class="table-responsive">
                                        <table class="mb-0 table table-sanpham">
                                            <thead>
                                            <tr>
                                                <th></th>
                                                <th>Sản phẩm</th>
                                                <th>Mã</th>
                                                <th>Đơn giá</th>
                                                <th>Giảm giá</th>
                                                <th class="soluong">Số lượng</th>
                                                <th>Thành tiền</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach($sanpham as $key=> $sp){?>
                                            <tr>
                                                <th scope="row">#<?=$key+1?></th>
                                                <td><?=$sp->ten?></td>
                                                <td><?=$sp->ma?></td>
                                                <td><?=number::format_number($sp->gia)?></td>
                                                <td><?=number::format_number($sp->giamgia)?></td>
                                                <td>
                                                    <?=number::format_number($sp->soluong)?>
                                                </td>
                                                <td><?=number::format_number($sp->soluong*($sp->gia-$sp->giamgia))?></td>
                                            </tr>
                                            <?php }?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                             
                            <?php $dathanhtoan = 0; if($model->thanhtoan){?>
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Thanh toán/Tạm ứng</h5>
                                    <div class="table-responsive">
                                        <table class="mb-0 table table-sanpham">
                                            <thead>
                                            <tr>
                                                <th></th>                                                
                                                <th>Mã</th>
                                                <th>Tiền</th>                                                
                                                <th>Ngày</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach($model->thanhtoan as $key=> $sp){$dathanhtoan+=$sp->tong;?>
                                            <tr>
                                                <th scope="row">#<?=$key+1?></th>
                                                 
                                                <td><?=$sp->ma?></td>
                                                <td><?=number::format_number($sp->tong)?></td>                                                 
                                                <td><?=date('d/m/Y',$sp->ngay)?></td>
                                            </tr>
                                            <?php }?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php }?>
                            
                            
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Tổng quan</h5>
                                    <form class="">
                                        <div class="position-relative row form-group"><label class="col-sm-3 col-form-label">Thành tiền</label>
                                            <div class="col-sm-9"><button class="btn btn-primary" type="button" id="thanhtien"><?=number::format_number($model->gia)?></button></div>
                                        </div><div class="position-relative row form-group"><label for="giamgia" class="col-sm-3 col-form-label">Giảm giá</label>
                                            <div class="col-sm-9"><button class="btn btn-primary" type="button"><?=number::format_number($model->giamgia)?></button></div>
                                        </div><div class="position-relative row form-group"><label for="phithem" class="col-sm-3 col-form-label">Phí thêm</label>
                                            <div class="col-sm-9"><button class="btn btn-primary" type="button"><?=number::format_number($model->phithem)?></button></div>
                                        </div>
                                        <div class="position-relative row form-group"><label class="col-sm-3 col-form-label">Tổng tiền</label>
                                            <div class="col-sm-9"><button class="btn btn-primary" type="button" id="tongtien"><?=number::format_number($model->tong)?></button></div>
                                        </div>
                                        
                                        <?php if($dathanhtoan>0){?>
                                        <div class="position-relative row form-group"><label class="col-sm-3 col-form-label">Đã thanh toán</label>
                                            <div class="col-sm-9"><button class="btn btn-primary" type="button" id="tongtien"><?=number::format_number($dathanhtoan)?></button></div>
                                        </div>
                                        <div class="position-relative row form-group"><label class="col-sm-3 col-form-label">Còn lại</label>
                                            <div class="col-sm-9"><button class="btn btn-primary" type="button" id="tongtien"><?=number::format_number($model->tong-$dathanhtoan)?></button></div>
                                        </div>
                                        <?php }?>
                                         
                                        <div class="position-relative row form-check">
                                            <div class="col-sm-9 offset-sm-2">
                                                <button class="btn btn-info suadonhang" type="button">Sửa đơn hàng</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                         
                    </div>
                </div>
             
    
<style>
    .table-sanpham .soluong{
        width: 90px;
    }
    .table-sanpham [name="soluong"]{
        width: 50px;
    }
    .table-sanpham .downup{
        float: right;display: inline-grid;margin-top: -2.2em;
    }
    .table-sanpham .downup>div{
        float: left;border: 1px solid #ccc;height: 15px;width: 19px; cursor: pointer;
    }
    .table-sanpham .downup>div>i{
        position: absolute;
    }
</style> 

 
