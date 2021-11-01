<? include(dirname(__FILE__).'/header.php') ?>

 
            <div class="app-main__outer dinhluong" data-id="<?=$kho?>" data-cha="<?=$cha?$cha->id:0?>">
                <div class="app-main__inner">
                    <div class="app-page-title">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div class="page-title-icon">
                                    <i class="pe-7s-box1 text-success">
                                    </i>
                                </div>
                                <div>Tính định lượng sản phẩm cho dự án<div class="page-title-subheading"><?='Dự án: '.$cha->ten.' <em>('.$cha->ma.')</em>'?></div>
                                </div>
                            </div>
                        </div>
                    </div>            
                    <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                        <li class="nav-item">
                            <a role="tab" class="nav-link show active" id="tab-0" data-toggle="tab" href="#tab-content-0" aria-selected="true">
                                <span>Sản phẩm</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a role="tab" class="nav-link show" id="tab-1" data-toggle="tab" href="#tab-content-1" aria-selected="false">
                                <span>Thông tin</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content" id="dinhluong">
                        
                        <div class="tab-pane tabs-animation fade active show" id="tab-content-0" role="tabpanel">
                        
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Thông tin dự án</h5>
                                     
                                    <div class="position-relative row form-group"><label for="ma" class="col-sm-2 col-form-label">Mã dự án</label>
                                        <div class="col-sm-10 mt-2"><?=$cha->ma?></div>
                                    </div>
                                    <div class="position-relative row form-group"><label for="ten" class="col-sm-2 col-form-label">Tên dự án</label>
                                        <div class="col-sm-10 mt-2"><?=$cha->ten?></div>
                                    </div>
                                    <div class="position-relative row form-group"><label for="batdau" class="col-sm-2 col-form-label">Ngày bắt đầu</label>
                                        <div class="col-sm-10 mt-2"><?=substr($cha->batdau,0,10)?></div>
                                    </div>
                                    <div class="position-relative row form-group"><label for="ketthuc" class="col-sm-2 col-form-label">Ngày kết thúc dự kiến</label>
                                        <div class="col-sm-10 mt-2">
                                            <?=$cha->ketthuc!='0000-00-00 00:00:00'?substr($cha->ketthuc,0,10):''?>
                                        </div>
                                    </div>
                                     
                                    <div class="position-relative row form-group"><label for="ghichu" class="col-sm-2 col-form-label">Ghi chú</label>
                                        <div class="col-sm-10">
                                            <textarea readonly="" class="form-control"><?=$cha->ghichu?></textarea>
                                        </div>
                                    </div>
                                         
                                     
                                </div>
                            </div>
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Sản phẩm</h5>
                                     
                                      
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group"><label for="searchProduct" class="">Sản phẩm<abbr title="required">*</abbr></label>
                                                <select name="searchProduct" id="searchProduct" class="form-control required" readonly>                                                        
                                                    <option value="<?=$cha->sanpham->id?>" data-soluong="<?=$cha->soluong-$cha->hoanthanh?>"><?=$cha->sanpham->ten?></option>                                                        
                                                </select>
                                            </div>
                                        </div>
                                         
                                        <div class="col-md-6">
                                            <div class="position-relative form-group"><label for="productQuantity" class="">Số lượng/<span><?=($cha->soluong-$cha->hoanthanh)?></span><abbr title="required">*</abbr></label><input name="productQuantity" id="productQuantity" value="1" type="text" class="form-control number required" data-max="<?=$cha->soluong-$cha->hoanthanh?>" /></div>
                                        </div>
                                    </div>
                                        
                                         
                                     
                                </div>
                            </div>
                            
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Chọn dự án con để xuất nguyên vật liệu hoặc nhà cung cấp để nhập hàng</h5>
                                     
                                      
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group"><label for="duan" class="">Dự án con</label>
                                                <select name="duan" id="duan" class="form-control">  
                                                    <option value="" data-soluong="">--Chọn dự án--</option>
                                                    <?php foreach($cha->duancon as $duan){?>                                                      
                                                    <option value="<?=$duan->id?>" data-soluong="<?=$duan->soluong?>"><?=$duan->ten?></option>
                                                    <?php }?>                                                        
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group"><label for="nhacungcap" class="">Hoặc Nhà cung cấp</label>
                                                <select name="nhacungcap" id="nhacungcap" class="form-control">  
                                                    <option value="" data-soluong="">--Chọn nhà cung cấp--</option>
                                                    <?php foreach($nhacungcap as $duan){?>                                                      
                                                    <option value="<?=$duan->id?>"><?=$duan->ten?></option>
                                                    <?php }?>                                                        
                                                </select>
                                            </div>
                                        </div>
                                         
                                    </div>
                                     
                                </div>
                            </div>
                            
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Định lượng nguyên vật liệu</h5>
                                     
                                    <?php if($cha->dinhmuc) foreach($cha->dinhmuc as $sanpham){?>  
                                    <div class="form-row">
                                        <div class="col-md-5">
                                            <div class="position-relative form-group"><label for="searchProduct<?=$sanpham->sanpham->id?>" class="">Sản phẩm<abbr title="required">*</abbr></label>
                                                <select name="searchProduct[]" id="searchProduct<?=$sanpham->sanpham->id?>" class="form-control required<?=$sanpham->con?' canChange':''?>"<?=$sanpham->con?'':' readonly'?>>                                                        
                                                    <option value="<?=$sanpham->sanpham->id?>" selected="" data-soluong="<?=$sanpham->sanpham->soluong?>" data-gia="<?=$sanpham->sanpham->gia?>"><?=$sanpham->sanpham->ten?> (<?=$sanpham->sanpham->soluong-0?>)</option> 
                                                    <?php if($sanpham->con){foreach($sanpham->con as $con){?>
                                                    <option value="<?=$con->id?>" data-dinhluong="<?=empty($con->dinhluong)?1:$con->dinhluong?>" data-soluong="<?=$con->soluong?>" data-gia="<?=$con->gia?>"><?=$con->ten?> (<?=$con->soluong-0?>)</option> 
                                                    <?php }}?>                                                       
                                                </select>
                                            </div>
                                        </div>
                                         
                                        <div class="col-md-2">
                                            <div class="position-relative form-group">
                                                <label for="productQuantity<?=$sanpham->sanpham->id?>" class="">Số lượng<span style="display: none;"><?=($sanpham->soluong)?></span><abbr title="required">*</abbr></label>
                                                <input name="productQuantity[]" id="productQuantity<?=$sanpham->sanpham->id?>" value="<?=$sanpham->soluong?>" type="text" class="form-control number required" data-max="<?=$sanpham->sanpham->soluong?>" />
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="position-relative form-group">
                                                <label for="productPrice<?=$sanpham->sanpham->id?>" class="">Giá</label>
                                                <input name="productPrice[]" id="productPrice<?=$sanpham->sanpham->id?>" value="<?=$sanpham->sanpham->gia?>" type="text" class="form-control number" />
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-1">
                                            <div class="position-relative form-group">
                                                <label for="SL1<?=$sanpham->sanpham->id?>" class="">SL Quy đổi</span></label>
                                                <input name="SL1[]" id="SL1<?=$sanpham->sanpham->id?>" value="<?=$sanpham->soluong?>" type="text" class="form-control number" readonly="" />
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="position-relative form-group">
                                                <label for="SL2<?=$sanpham->sanpham->id?>" class="">SL Yêu cầu</span></label>
                                                <input name="SL2[]" id="SL2<?=$sanpham->sanpham->id?>" value="<?=$sanpham->soluong?>" type="text" class="form-control number" readonly="" />
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <?php if($sanpham->con){?>
                                            <div class="position-relative form-group">
                                                <label class="">Thêm SPC</label>
                                                <a class="border-0 btn-transition btn btn-outline-success" href="javascript:void(0);">
                                                    <i class="fa fa-plus"></i>
                                                </a>
                                            </div>
                                            <?php }?>    
                                        </div>
                                    </div>
                                    <?php }?>    
                                         
                                     
                                </div>
                            </div>
                            <?php if(1){//$cha->duancon?>
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Tổng quan</h5>
                                    <form class="">
                                        <div class="position-relative row form-group"><label class="col-sm-2 col-form-label">Thành tiền</label>
                                            <div class="col-sm-10"><button class="btn btn-primary" type="button" id="thanhtien">0</button></div>
                                        </div>
                                        <div class="position-relative row form-group"><label for="giamgia" class="col-sm-2 col-form-label">Giảm giá</label>
                                            <div class="col-sm-10"><input name="giamgia" id="giamgia" type="text" class="form-control" value="0" /></div>
                                        </div>
                                        <div class="position-relative row form-group"><label for="phithem" class="col-sm-2 col-form-label">Phí thêm</label>
                                            <div class="col-sm-10"><input name="phithem" id="phithem" type="text" class="form-control" value="0" /></div>
                                        </div>
                                        <div class="position-relative row form-group"><label class="col-sm-2 col-form-label">Tổng tiền</label>
                                            <div class="col-sm-10"><button class="btn btn-primary" type="button" id="tongtien">0</button></div>
                                        </div>
                                        
                                        <div class="position-relative row form-group"><label for="thanhtoan" class="col-sm-2 col-form-label">Tạm ứng/Thanh toán</label>
                                            <div class="col-sm-10"><input name="thanhtoan" id="thanhtoan" value="0" type="text" class="form-control"></div>
                                        </div>
                                         
                                        <div class="position-relative row form-check">
                                            <div class="col-sm-10 offset-sm-2">
                                                <button class="btn btn-info taodonxuat" type="button">Tạo đơn xuất/nhập</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            
                            <?php }?>
                        </div>
                        
                        <div class="tab-pane tabs-animation fade" id="tab-content-1" role="tabpanel">
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Thông tin đơn xuất hàng</h5>
                                    <form class="">
                                        <div class="position-relative row form-group"><label for="ma" class="col-sm-2 col-form-label">Mã phiếu</label>
                                            <div class="col-sm-10"><input name="ma" id="ma" placeholder="XH0000001 hoặc bỏ trống" type="text" class="form-control"></div>
                                        </div>
                                        <div class="position-relative row form-group"><label for="ngay" class="col-sm-2 col-form-label">Thời gian <abbr lang="required">*</abbr></label>
                                            <div class="col-sm-10">
                                                <input name="ngay" id="ngay" class="form-control input-mask-trigger" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy HH:MM:ss" im-insert="false">
                                                 
                                            </div>
                                        </div>
                                         
                                        <div class="position-relative row form-group"><label for="ghichu" class="col-sm-2 col-form-label">Ghi chú</label>
                                            <div class="col-sm-10"><textarea name="ghichu" id="ghichu" class="form-control"></textarea></div>
                                        </div>
                                         
                                        <div class="position-relative row form-check">
                                            <div class="col-sm-10 offset-sm-2">
                                                <button class="btn btn-info taodonxuat" type="button">Tạo đơn xuất</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
    
<style>
select[readonly]{
  pointer-events: none;
  touch-action: none;
}
</style>

 
<? include(dirname(__FILE__).'/footer.php') ?>