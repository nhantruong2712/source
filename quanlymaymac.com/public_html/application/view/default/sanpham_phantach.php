<? include(dirname(__FILE__).'/header.php') ?>



<div class="app-main__outer phantach" data-id="<?=$model->id?>" data-kho="<?=$kho?>">
  <div class="app-main__inner">
    <div class="app-page-title">
      <div class="page-title-wrapper">
        <div class="page-title-heading">
          <div class="page-title-icon">
            <i class="pe-7s-usb text-success">
            </i>
          </div>
          <div>Phân tách sản phẩm<div class="page-title-subheading">Phân tách sản phẩm</div>
          </div>
        </div>
      </div>
    </div>

    <div class="tab-content" id="phantach">

      <div class="tab-pane tabs-animation fade active show" id="tab-content-0" role="tabpanel">

        <div class="main-card mb-3 card">
          <div class="card-body">
            <h5 class="card-title">Thông tin sản phẩm</h5>

            <div class="position-relative row form-group"><label class="col-sm-2 col-form-label">Mã sản phẩm</label>
              <div class="col-sm-10 mt-2"><?=$model->ma?></div>
            </div>
            <div class="position-relative row form-group"><label class="col-sm-2 col-form-label">Tên sản phẩm</label>
              <div class="col-sm-10 mt-2"><?=$model->ten?></div>
            </div>
            
            <div class="position-relative row form-group">
                <label for="soluong" class="col-sm-2 col-form-label">SL</label>
                <div class="col-sm-5">
                    <input name="soluong" id="soluong" value="<?=$model->soluong-0?>"<?=$model->soluong==1?' readonly':''?> type="text" class="form-control number" />
                </div>
                <div class="col-sm-1 mt-2">/</div>
                <div class="col-sm-4">
                    <input name="soluong2" id="soluong2" readonly="" value="<?=$model->soluong?>" type="text" class="form-control number" />
                </div>
            </div>
            <div class="position-relative row form-group"><label class="col-sm-2 col-form-label">Giá</label>
              <div class="col-sm-10 mt-2"><?=$model->gia?></div>
            </div>
            
 
            <div class="position-relative row form-group"><label for="ghichu" class="col-sm-2 col-form-label">Ghi chú</label>
              <div class="col-sm-10">
                <textarea readonly="" class="form-control"><?=$model->ghichu?></textarea>
              </div>
            </div>


          </div>
        </div>
        <div class="main-card mb-3 card">
          <div class="card-body">
            <h5 class="card-title">Định lượng</h5>


            <div class="form-row">
              <div class="col-md-6">
                <div class="position-relative form-group"><label for="searchProduct" class="">Sản phẩm<abbr title="required">*</abbr></label>
                  <select name="searchProduct" id="searchProduct" class="form-control required" readonly="">
                    <option value="<?=$model->dinhmuc->sanpham->id?>"><?=$model->dinhmuc->sanpham->ten?></option>
                  </select>
                </div>
              </div>

              <div class="col-md-3">
                <div class="position-relative form-group">
                    <label for="productQuantity" class="">Số lượng</label>
                    <input name="productQuantity" id="productQuantity" value="<?=$model->dinhmuc->soluong-0?>" type="text" class="form-control number" readonly="" />
                </div>
              </div>
              
              <div class="col-md-3">
                <div class="position-relative form-group">
                    <label for="productQuantity_2" class="">Tổng Số lượng</label>
                    <input name="productQuantity_2" id="productQuantity_2" value="<?=$model->dinhmuc->soluong-0?>" type="text" class="form-control number" readonly="" />
                </div>
              </div>
            </div>



          </div>
        </div>



        <div class="main-card mb-3 card">
          <div class="card-body">
            <h5 class="card-title">Phân tách sản phẩm theo định lượng</h5>
 
            <div class="form-row">
              <div class="col-md-6">
                <div class="position-relative form-group"><label for="searchProduct<?=$model->id?>" class="">Sản phẩm (Định lượng)</label>
                  <select name="searchProduct[]" id="searchProduct<?=$model->id?>" class="form-control required canChange">
                    <option value="0" data-dinhluong="1" data-gia="0">--Tạo sản phẩm con của (<?=$model->ten?>) mới--</option>
                    <option value="<?=$model->dinhmuc->sanpham->id?>" selected="" data-dinhluong="1" data-gia="<?=$model->dinhmuc->sanpham->gia?>"><?=$model->dinhmuc->sanpham->ten?> (1)</option>
                    
                    <?php if($model->dinhmuc->con) foreach($model->dinhmuc->con as $con){?>
                    <option value="<?=$con->id?>" data-dinhluong="<?=empty($con->dinhluong)?1:($con->dinhluong-0)?>" data-gia="<?=$con->gia?>"><?=$con->ten?> (<?=empty($con->dinhluong)?1:($con->dinhluong-0)?>)</option>
                    <?php }?>

                  </select>
                </div>
              </div>

              <div class="col-md-2">
                <div class="position-relative form-group">
                  <label for="productQuantity<?=$model->id?>" class="">Số lượng<abbr title="required">*</abbr></label>
                  <input name="productQuantity[]" id="productQuantity<?=$model->id?>" value="<?=$model->dinhmuc->soluong-0?>" type="text" class="form-control number required" data-max="0">
                </div>
              </div>
              <div class="col-md-2">
                <div class="position-relative form-group">
                  <label for="productPrice<?=$model->id?>" class="">Giá</label>
                  <input name="productPrice[]" id="productPrice<?=$model->id?>" value="<?=$model->dinhmuc->sanpham->gia?>" type="text" class="form-control number">
                </div>
              </div>

              <div class="col-md-1">
                <div class="position-relative form-group">
                  <label for="SL1<?=$model->id?>" class="">SL Quy đổi</label>
                  <input name="SL1[]" id="SL1<?=$model->id?>" value="<?=$model->dinhmuc->soluong-0?>" type="text" class="form-control number" readonly="">
                </div>
              </div>

              <div class="col-md-1">
                <div class="position-relative form-group">
                  <label class="">Thêm SP</label>
                  <a class="border-0 btn-transition btn btn-outline-success" href="javascript:void(0);">
                    <i class="fa fa-plus"></i>
                  </a>
                </div>

              </div>
            </div>



          </div>
        </div>
        
        <div class="main-card mb-3 card">
          <div class="card-body">
            <h5 class="card-title">Phần sản xuất (Tùy chọn, Nếu có nhân viên và sản lượng thì sẽ tính công)</h5>


            <div class="form-row">
              <div class="col-md-6">
                <div class="position-relative form-group"><label for="duan" class="">Dự án</label>
                  <select name="duan" id="duan" class="form-control"></select>
                </div>
              </div>     
              <div class="col-md-6">
                <div class="position-relative form-group"><label for="nhom" class="">Nhóm công đoạn</label>
                  <select name="nhom" id="nhom" class="form-control">    
                       <option value="0" selected="">--Nhóm ngoài--</option>
                        <?php foreach($nhomcongdoan as $cd){?>
                        <option value="<?=$cd->id?>"><?=str_repeat("--",$cd->cap-1)?><?=$cd->ten?></option>
                        <?php }?>               
                  </select>
                </div>
              </div>            
            </div>


            <div class="form-row">
              <div class="col-md-6">
                <div class="position-relative form-group"><label for="nhanvien" class="">Nhân viên</label>
                  <select name="nhanvien" id="nhanvien" class="form-control">                    
                  </select>
                </div>
              </div>

              <div class="col-md-6">
                <div class="position-relative form-group">
                  <label for="congdoan" class="">Công đoạn</label>
                  <select name="congdoan" id="congdoan" class="form-control">    
                    <?php foreach($congdoan as $cd){?>
                    <option value="<?=$cd->id?>"><?=$cd->ten?></option>
                    <?php }?>                
                  </select>
                </div>
              </div>
              
              
            </div>
            
            <div class="form-row">
                 
              <div class="col-md-6">
                <div class="position-relative form-group"><label for="sanluong" class="">Sản lượng tính công</label>
                  <input name="sanluong" id="sanluong" class="form-control number" value="1" />
                </div>
              </div>   
              <div class="col-md-6">
                 
              </div>           
            </div>


          </div>
        </div>
        
        <div class="main-card mb-3 card">
          <div class="card-body">
            <form class="">
 
              <div class="position-relative row form-check">
                <div class="col-sm-10 offset-sm-2">
                  <button class="btn btn-info taophantach" type="button">Phân tách</button>
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
select[readonly] {
  pointer-events: none;
  touch-action: none;
}
</style>
 
<? include(dirname(__FILE__).'/footer.php') ?>