<? include(dirname(__FILE__).'/header.php') ?>

 
            <div class="app-main__outer duan" data-id="<?=$kho?>" data-cha="<?=$cha?$cha->id:0?>">
                <div class="app-main__inner">
                    <div class="app-page-title">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div class="page-title-icon">
                                    <i class="pe-7s-box1 text-success">
                                    </i>
                                </div>
                                <div>Thêm dự án<?=$cha?' con':''?><div class="page-title-subheading"><?=$cha?'Dự án: '.$cha->ten.' <em>('.$cha->ma.')</em>':'Thêm dự án'?></div>
                                </div>
                            </div>
                        </div>
                    </div>            
                     
                    <div class="tab-content">
                        <form class="" id="duan">
                        <div class="tab-pane tabs-animation fade active show" id="tab-content-0" role="tabpanel">
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Thông tin</h5>
                                     
                                        <div class="position-relative row form-group"><label for="ma" class="col-sm-2 col-form-label">Mã dự án</label>
                                            <div class="col-sm-10"><input name="ma" id="ma" placeholder="DA0000001 hoặc bỏ trống" type="text" class="form-control ma"></div>
                                        </div>
                                        <div class="position-relative row form-group"><label for="ten" class="col-sm-2 col-form-label">Tên dự án<abbr title="required">*</abbr></label>
                                            <div class="col-sm-10"><input name="ten" id="ten" type="text" class="form-control required" /></div>
                                        </div>
                                        <div class="position-relative row form-group"><label for="batdau" class="col-sm-2 col-form-label">Ngày bắt đầu<abbr title="required">*</abbr></label>
                                            <div class="col-sm-10">
                                                <input name="batdau" id="batdau" class="form-control required ngaythangnam" data-date="<?=$cha?substr($cha->batdau,0,10):''?>" data-toggle="datepicker" />
                                                 
                                            </div>
                                        </div>
                                        <div class="position-relative row form-group"><label for="ketthuc" class="col-sm-2 col-form-label">Ngày kết thúc dự kiến</label>
                                            <div class="col-sm-10">
                                                <input name="ketthuc" id="ketthuc" class="form-control ngaythangnam" data-date="<?=$cha&&$cha->ketthuc!='0000-00-00 00:00:00'?substr($cha->ketthuc,0,10):''?>" data-toggle="datepicker" />
                                                 
                                            </div>
                                        </div>
                                        <div class="position-relative row form-group"><label for="khachhang" class="col-sm-2 col-form-label"><?=$cha?'Đối tác sản xuất':'Khách hàng'?><abbr title="required">*</abbr></label>
                                            <div class="col-sm-10"><select name="khachhang" id="khachhang" class="form-control required" ></select></div>
                                        </div>
                                        
                                        <div class="position-relative row form-group"><label for="ghichu" class="col-sm-2 col-form-label">Ghi chú</label>
                                            <div class="col-sm-10"><textarea name="ghichu" id="ghichu" class="form-control"></textarea></div>
                                        </div>
                                         
                                     
                                </div>
                            </div>
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Sản phẩm</h5>
                                     
                                      
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="position-relative form-group"><label for="searchProduct" class=""><?=$cha?'Sản phẩm':'Tìm theo tên hoặc mã'?><abbr title="required">*</abbr></label>
                                                    <select name="searchProduct" id="searchProduct" class="form-control required">
                                                        <?php if($cha){?>
                                                        <option value="<?=$cha->sanpham->id?>"><?=$cha->sanpham->ten?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="position-relative form-group"><label for="productPrice" class="">Giá<abbr title="required">*</abbr></label><input name="productPrice" id="productPrice" type="text" class="form-control number required" value="<?=$cha?$cha->gia:''?>" /></div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="position-relative form-group"><label for="productQuantity" class="">Số lượng<?=$cha?"/".($cha->soluong-$cha->soluongtru):''?><abbr title="required">*</abbr></label><input name="productQuantity" id="productQuantity" value="1" type="text" class="form-control number required" data-max="<?=$cha?($cha->soluong-$cha->soluongtru):-1?>" /></div>
                                            </div>
                                        </div>
                                        
                                         
                                     
                                </div>
                            </div>
                            
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Tổng quan</h5>
                                    
                                         
                                        <div class="position-relative row form-group"><label class="col-sm-2 col-form-label">Tổng tiền</label>
                                            <div class="col-sm-10"><button class="btn btn-primary" type="button" id="tongtien">0</button></div>
                                        </div>
                                         
                                        <div class="position-relative row form-check">
                                            <div class="col-sm-10 offset-sm-2">
                                                <input type="hidden" name="cha" value="<?=$cha?$cha->id:0?>" />
                                                <button class="btn btn-info themduan" type="button">Thêm dự án</button>
                                            </div>
                                        </div>
                                     
                                </div>
                            </div>
                        </div>
                        </form> 
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

 
<? include(dirname(__FILE__).'/footer.php') ?>