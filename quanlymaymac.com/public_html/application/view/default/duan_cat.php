<? include(dirname(__FILE__).'/header.php') ?>



            <div class="app-main__outer duancat" data-id="<?=$kho?>" data-duan="<?=$model->id?>">
                <div class="app-main__inner">
                    <div class="app-page-title">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div class="page-title-icon">
                                    <i class="pe-7s-scissors text-success">
                                    </i>
                                </div>
                                <div>Thêm bàn cắt vải<div class="page-title-subheading">Dự án: <?=$model->ten?> <em>(<?=$model->ma?>)</em></div>
                                </div>
                            </div>
                        </div>
                    </div>  
                    <div class="tab-content">
                        <div class="tab-pane tabs-animation fade active show" id="tab-content-0" role="tabpanel">
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Thông tin dự án</h5>
                                    <form class="">
                                         
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="position-relative form-group"><label for="soluong" class="">Số sản phẩm</label><input name="soluong" id="soluong" type="text" class="form-control" readonly="" value="<?=$model->soluong?>" /></div>
                                            </div>
                                             
                                            <div class="col-md-6">
                                                <div class="position-relative form-group"><label for="lopban" class="">Max lớp/bàn</label><input name="lopban" id="lopban" type="text" class="form-control" data-old="<?=$model->lopban?>" value="<?=$model->lopban?>"<?=$model->duandacat>0?' readonly':''?> /><span></span></div>
                                            </div>
                                             
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="position-relative form-group"><label for="splop" class="">Số sản phẩm/lớp</label><input name="splop" id="splop" type="text" class="form-control" data-old="<?=$model->splop?>" value="<?=$model->splop?>"<?=$model->duandacat>0?' readonly':''?> /><span></span></div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group"><label for="lop" class="">Số lớp cho dự án</label><input name="lop" id="lop" type="text" class="form-control" readonly="" value="<?=$model->splop==0?0:ceil($model->soluong/$model->splop)?>" /></div>
                                            </div> 
                                        </div> 
                                        
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="position-relative form-group"><label for="solopdacat" class="">Số lớp đã cắt</label><input name="solopdacat" id="solopdacat" type="text" class="form-control" readonly="" value="<?=$model->solopdacat?>" /><span></span></div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group"><label for="solopconlai" class="">Số lớp còn lại</label><input name="solopconlai" id="solopconlai" type="text" class="form-control" readonly="" value="<?=($model->splop==0?0:ceil($model->soluong/$model->splop))-$model->solopdacat?>" /></div>
                                            </div> 
                                        </div> 
                                        
                                        <div class="form-row">
                                             
                                            <div class="col-md-6">
                                                <div class="position-relative form-group"><label for="solopcatdotnay" class="">Số lớp cắt đợt này</label><input name="solopcatdotnay" id="solopcatdotnay" type="text" class="form-control" data-old="<?=($model->splop==0?0:ceil($model->soluong/$model->splop))-$model->solopdacat?>" value="<?=($model->splop==0?0:ceil($model->soluong/$model->splop))-$model->solopdacat?>" /></div>
                                            </div> 
                                            <div class="col-md-6">
                                                <div class="position-relative form-group"><label for="sanpham" class="">Sản phẩm may</label>
                                                    <select name="sanpham" id="sanpham" class="form-control" readonly="">
                                                        <?php if($model->sanpham){?>
                                                        <option value="<?=$model->sanpham->id?>"><?=$model->sanpham->ten?></option>
                                                        <?php }?>
                                                    </select><span></span>
                                                </div>
                                            </div>                                                                                        
                                        </div> 
                                        <h5 class="card-title">Phần tính sản lượng</h5>
                                        <div class="form-row">                                             
                                            <div class="col-md-6">
                                                <div class="position-relative form-group"><label for="nhomcongdoan" class="">Nhóm công đoạn</label>
                                                    <select name="nhomcongdoan" id="nhomcongdoan" class="form-control">
                                                        <option value="0">--Nhóm ngoài--</option>
                                                        <?php foreach($nhomcongdoan as $cd){?>
                                                        <option value="<?=$cd->id?>"<?=!empty($model->nhomcongdoan)&&$model->nhomcongdoan==$cd->id?' selected=""':''?>><?=str_repeat('--',$cd->cap-1)?><?=$cd->ten?></option>
                                                        <?php }?>
                                                    </select><span></span>
                                                </div>
                                            </div> 
                                            <div class="col-md-6">
                                                <div class="position-relative form-group"><label for="congdoan" class="">Công đoạn</label>
                                                    <select name="congdoan" id="congdoan" class="form-control">
                                                        <?php foreach($congdoan as $cd){?>
                                                        <option value="<?=$cd->id?>"<?=!empty($model->congdoan)&&$model->congdoan==$cd->id?' selected=""':''?>><?=$cd->ten?></option>
                                                        <?php }?>
                                                    </select><span></span>
                                                </div>
                                            </div>                                                                                        
                                        </div> 
                                        
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="position-relative form-group"><label for="nhanvien" class="">Nhân viên cắt</label>
                                                    <select name="nhanvien" id="nhanvien" class="form-control">
                                                        <?php if($model->nhanvien){?>
                                                        <option value="<?=$model->nhanvien->id?>"><?=$model->nhanvien->ten?></option>
                                                        <?php }?>
                                                    </select>
                                                    <span></span>
                                                </div>
                                            </div> 
                                        </div>     
                                    </form>
                                </div>
                            </div>
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Cấu hình sản phẩm</h5>
                                    <form class="">
                                         
                                        <div class="form-row">
                                             
                                            <div class="col-md-6">
                                                <div class="position-relative form-group"><label for="cuonvai" class="">Cuộn vải cắt</label>
                                                    <select name="cuonvai" id="cuonvai" class="form-control"></select>
                                                </div>
                                            </div> 
                                            
                                            <div class="col-md-6">
                                                <div class="position-relative form-group"><label for="soluongcuon" class="">Số lượng cuộn cắt</label><input name="soluongcuon" id="soluongcuon" type="text" class="form-control" value="1" /><span></span></div>
                                            </div>
                                            
                                        </div> 
                                        
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="position-relative form-group"><label for="tamvai" class="">Tấm vải</label>
                                                    <select name="tamvai" id="tamvai" class="form-control"<?=$model->duandacat>0?' readonly':''?>>
                                                        <?php if($model->tamvai){?>
                                                        <option value="<?=$model->tamvai->id?>" selected="" 
                                                            data-data="<?=htmlentities(json_encode($model->tamvai->attributes()))?>"><?=$model->tamvai->ten?></option>
                                                        <?php }?>
                                                    </select><span></span>
                                                </div>
                                            </div> 
                                            
                                            <div class="col-md-6">
                                                <div class="position-relative form-group"><label for="nhomban" class="">Sản phẩm Bàn cắt</label>
                                                    <select name="nhomban" id="nhomban" class="form-control"<?=$model->duandacat>0?' readonly':''?>>
                                                        <?php if($model->nhomban){?>
                                                        <option value="<?=$model->nhomban->id?>"><?=$model->nhomban->ten?></option>
                                                        <?php }?>
                                                    </select><span></span>
                                                </div>
                                            </div>
                                          
                                        </div>
                                        
                                    </form>
                                </div>
                            </div>
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Danh sách bàn cắt</h5>
                                    <div class="row">
                                         <div class="col-md-3">
                                            <div class="mb-3 card text-white bg-primary">
                                                <div class="card-header">Bàn 1</div>
                                                <div class="card-body"><input type="text" class="form-control" value="500" /></div>
                                                <div class="card-footer"><button type="button" class="btn btn-primary">Xong</button></div>
                                            </div>                                             
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="main-card mb-3 card">
                                <div class="card-body"> 
                                    <div class="position-relative row form-check">
                                        <div class="col-sm-10 offset-sm-2">
                                            <button class="btn btn-info hoantatcat" type="button" data-toggle="modal" data-target=".bd-example-modal-sm" data-action="hoantatcat">Hoàn tất cắt</button>
                                        </div>
                                    </div>
                                     
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
    
<style>
.loading~span:not(.select2){
     
    border-radius: 100%;
    margin: 2px;
    animation-fill-mode: both;
    border: 2px solid #3f6ad8;
    border-bottom-color: transparent;
    height: 25px;
    width: 25px;
    background: transparent !important;
    display: inline-block;
    animation: rotate 0.75s 0s linear infinite;
    
    position: absolute;
    /* margin-top: -2.1em; */
    right: 0px;
    top: 2.5em;
}

select[readonly] + .select2-container {
  pointer-events: none;
  touch-action: none;
}
select[readonly]+.select2-container .select2-selection--single{
    background-color: #e9ecef;
} 
</style> 

 
<? include(dirname(__FILE__).'/footer.php') ?>