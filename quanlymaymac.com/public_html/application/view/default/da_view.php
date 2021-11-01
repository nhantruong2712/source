 
             
                <div class="app-main__inner">
                     
                    <div class="tab-content">
                        <div class="tab-pane tabs-animation fade active show" id="tab-content-0" role="tabpanel">
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Thông tin</h5>
                                    <form class="">
                                        <div class="position-relative row form-group"><label for="ma" class="col-sm-3 col-form-label">Mã dự án</label>
                                            <div class="col-sm-9 mt-2"><?=$model->ma?></div>
                                        </div>
                                        <div class="position-relative row form-group"><label for="ten" class="col-sm-3 col-form-label">Tên dự án</label>
                                            <div class="col-sm-9">
                                                <input name="ten" id="ten" value="<?=$model->ten?>" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="position-relative row form-group"><label for="ngay" class="col-sm-3 col-form-label">Thời gian</label>
                                            <div class="col-sm-9">
                                                <input name="ngay" id="ngay" value="<?=date('d/m/Y H:i:s',strtotime($model->ngay))?>" class="form-control input-mask-trigger" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy HH:MM:ss" im-insert="false">
                                                <input type="hidden" name="id" id="id" value="<?=$model->id?>"> 
                                            </div>
                                        </div>
                                        <div class="position-relative row form-group"><label for="batdau" class="col-sm-3 col-form-label">Ngày bắt đầu</label>
                                            <div class="col-sm-9">
                                                <input name="batdau" id="batdau" value="<?=date('d/m/Y',strtotime($model->batdau))?>" class="form-control input-mask-trigger" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" im-insert="false">                                                 
                                            </div>
                                        </div>
                                        <div class="position-relative row form-group"><label for="ketthuc" class="col-sm-3 col-form-label">Ngày kết thúc</label>
                                            <div class="col-sm-9">
                                                <input name="ketthuc" id="ketthuc" value="<?=$model->ketthuc=='0000-00-00 00:00:00'?'':date('d/m/Y',strtotime($model->ketthuc))?>" class="form-control input-mask-trigger" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" im-insert="false">                                                 
                                            </div>
                                        </div>
                                        <div class="position-relative row form-group"><label for="tu" class="col-sm-3 col-form-label"><?=$model->cha?'Đối tác sản xuất':'Khách hàng'?></label>
                                            <div class="col-sm-9 mt-2"><a href="/<?=$model->cha?'doitacsanxuat':'khachhang'?>?filter[search]=<?=$model->khachhang->ten?>"><?=$model->khachhang->ten?></a></div>
                                        </div> 
                                        <div class="position-relative row form-group"><label for="ghichu" class="col-sm-3 col-form-label">Ghi chú</label>
                                            <div class="col-sm-9"><textarea name="ghichu" id="ghichu" class="form-control"><?=$model->ghichu?></textarea></div>
                                        </div>
                                          
                                    </form>
                                </div>
                            </div>
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Sản phẩm</h5>
                                    <div class="table-responsive">
                                        <table class="mb-0 table table-sanpham">
                                            <thead>
                                            <tr>
                                                <th></th>
                                                <th>Sản phẩm</th>
                                                <th>Mã</th>
                                                <th>Đơn giá</th>
                                                <!--th>Giảm giá</th-->
                                                <th class="soluong">Số lượng</th>
                                                <th>Thành tiền</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                             
                                            <tr>
                                                <th scope="row"></th>
                                                <td><?=$model->sanpham->ten?></td>
                                                <td><?=$model->sanpham->ma?></td>
                                                <td><?=number::format_number($model->gia)?></td>
                                                <!--td></td-->
                                                <td>
                                                    <?=number::format_number($model->soluong)?>
                                                </td>
                                                <td><?=number::format_number($model->soluong*($model->gia))?></td>
                                            </tr>
                                             
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div><div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Tổng quan</h5>
                                    <form class="">
                                        <div class="position-relative row form-group"><label class="col-sm-3 col-form-label">Thành tiền</label>
                                            <div class="col-sm-9"><button class="btn btn-primary" type="button" id="thanhtien"><?=number::format_number($model->soluong*($model->gia))?></button></div>
                                        </div> 
                                         
                                        <div class="position-relative row form-check">
                                            <div class="col-sm-9 offset-sm-2">
                                                <button class="btn btn-info suaduan" type="button">Sửa dự án</button>
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

 
