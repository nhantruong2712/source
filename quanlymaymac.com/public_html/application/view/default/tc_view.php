 
                <div class="app-main__inner">
                     
                    <div class="tab-content">
                        <div class="tab-pane tabs-animation fade active show" id="tab-content-0" role="tabpanel">
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Thông tin</h5>
                                    <form class="">
                                        <div class="position-relative row form-group"><label for="ma" class="col-sm-3 col-form-label">Mã phiếu</label>
                                            <div class="col-sm-9 mt-2"><?=$model->ma?></div>
                                        </div>
                                        <div class="position-relative row form-group"><label for="loai" class="col-sm-3 col-form-label">Loại phiếu</label>
                                            <div class="col-sm-9 mt-2"><?=/*($model->type==2&&$model->kho==$kho)||($model->type==4&&$model->kho!=$kho)*/$model->type==2?'Phiếu chi':'Phiếu thu'?></div>
                                        </div>
                                        <div class="position-relative row form-group"><label for="ngay" class="col-sm-3 col-form-label">Thời gian</label>
                                            <div class="col-sm-9">
                                                <input name="ngay" id="ngay" value="<?=date('d/m/Y H:i:s',$model->ngay)?>" class="form-control input-mask-trigger" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy HH:MM:ss" im-insert="false">
                                                <input type="hidden" name="id" id="id" value="<?=$model->id?>"> 
                                            </div>
                                        </div>
                                        <div class="position-relative row form-group"><label for="tu" class="col-sm-3 col-form-label">Từ/Đến</label>
                                            <div class="col-sm-9 mt-2"><?=$model->tu2?> - <a href="/<?=$model->tu?>?filter[search]=<?=$model->doitac->ten?>"><?=$model->doitac->ten?></a></div>
                                        </div> 
                                        <div class="position-relative row form-group"><label for="ghichu" class="col-sm-3 col-form-label">Ghi chú</label>
                                            <div class="col-sm-9"><textarea name="ghichu" id="ghichu" class="form-control"><?=$model->ghichu?></textarea></div>
                                        </div>
                                          
                                    </form>
                                </div>
                            </div>
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Tổng quan</h5>
                                    <form class="">
                                         
                                        <div class="position-relative row form-group"><label class="col-sm-3 col-form-label">Tổng tiền</label>
                                            <div class="col-sm-9"><button class="btn btn-primary" type="button" id="tongtien"><?=number::format_number($model->tong)?></button></div>
                                        </div>
                                         
                                        <div class="position-relative row form-check">
                                            <div class="col-sm-9 offset-sm-2">
                                                <button class="btn btn-info suadonhang" type="button">Sửa phiếu</button>
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

 
