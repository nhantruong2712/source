<style>
.datepicker-container, .daterangepicker{
    z-index: 9999!important;
}
</style>                              
                    <div class="tab-content">
                        <div class="tab-pane tabs-animation fade active show" id="tab-content-0" role="tabpanel">
                              
                            <div class="row">
                                <div class="col-lg-12">
                                    
                                    <div class="mb-3">
                                        <div class="card-header">Lọc theo sản phẩm</div>
                                        <div class="mb-3">
                                            <div class="position-relative form-group"><label for="filterName" class="">Tên hoặc mã sản phẩm</label><input name="ten" id="filterName" value="<?=$product?>" type="text" class="form-control"></div>
                                        </div>                                        
                                    </div>
                                    <div class="mb-3">
                                        <div class="card-header">Lọc theo phiếu</div>
                                        <div class="mb-3">
                                             
                                            <div class="position-relative form-group"><label for="filterRange" class="">Khoảng thời gian</label><input name="range" id="filterRange" value="<?=$range?>" type="text" class="form-control"></div> 
                                            <div class="position-relative form-group"><label for="filterNote" class="">Ghi chú</label><input name="ghichu" id="filterNote" value="<?=$note?>" type="text" class="form-control"></div>
                                            <div class="position-relative form-group">
                                                <label for="filterStatus1" class="">Trạng thái</label>                                                
                                                <input name="trangthai" id="filterStatus3" value="1" type="checkbox"<?=$status&&in_array(1,$status)?' checked':''?> /> Mới
                                                <input name="trangthai" id="filterStatus1" value="2" type="checkbox"<?=$status&&in_array(2,$status)?' checked':''?> /> Hoàn thành
                                            </div>
                                        </div>
                                        <div class="d-block text-right card-footer">                                             
                                            <button class="btn btn-warning btn-lg" id="dofilterbctc">Lọc</button> 
                                        </div>  
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        
                    </div>