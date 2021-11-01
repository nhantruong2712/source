<? include(dirname(__FILE__).'/header.php') ?>



            <div class="app-main__outer thuchi" data-id="<?=$kho?>">
                <div class="app-main__inner">
                    <div class="app-page-title">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div class="page-title-icon">
                                    <i class="pe-7s-credit text-success">
                                    </i>
                                </div>
                                <div>Thêm phiếu thu chi<div class="page-title-subheading">Thêm phiếu thu chi</div>
                                </div>
                            </div>
                        </div>
                    </div>            
                     
                    <div class="tab-content">
                        <div class="tab-pane tabs-animation fade active show" id="tab-content-0" role="tabpanel">
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Thông tin</h5>
                                    <form class="">
                                        <div class="position-relative row form-group"><label for="type" class="col-sm-2 col-form-label">Loại phiếu <abbr lang="required">*</abbr></label>
                                            <div class="col-sm-10"><select name="type" id="type" class="form-control">
                                                 
                                                <option value="2">Phiếu chi</option>
                                                <option value="4">Phiếu thu</option>
                                                 
                                            </select></div>
                                        </div>
                                        <div class="position-relative row form-group"><label for="ma" class="col-sm-2 col-form-label">Mã phiếu</label>
                                            <div class="col-sm-10"><input name="ma" id="ma" placeholder="PT000001 hoặc bỏ trống" type="text" class="form-control"></div>
                                        </div>
                                        <div class="position-relative row form-group"><label for="ngay" class="col-sm-2 col-form-label">Thời gian <abbr lang="required">*</abbr></label>
                                            <div class="col-sm-10">
                                                <input name="ngay" id="ngay" class="form-control input-mask-trigger" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy HH:MM:ss" im-insert="false">
                                                 
                                            </div>
                                        </div>
                                        
                                        <div class="position-relative row form-group"><label for="duan" class="col-sm-2 col-form-label">Dự án</label>
                                            <div class="col-sm-10">
                                                <select name="duan" id="duan" class="form-control"></select>
                                            </div>
                                        </div>
                                        
                                        <div class="position-relative row form-group"><label for="tu" class="col-sm-2 col-form-label">Từ/Đến <abbr lang="required">*</abbr></label>
                                            <div class="col-sm-10"><select name="tu" id="tu" class="form-control">
                                                <option value="">-Chọn-</option>
                                                <option value="nhacungcap">Nhà cung cấp</option>
                                                <option value="doitacsanxuat">Đối tác sản xuất</option>
                                                <option value="nhanvien">Nhân viên</option>
                                            </select></div>
                                        </div>
                                        <div class="position-relative row form-group"><label for="doitac" class="col-sm-2 col-form-label"></label>
                                            <div class="col-sm-10"><select name="doitac" id="doitac" class="form-control" readonly></select></div>
                                        </div>
                                        
                                        <div class="position-relative row form-group"><label for="ghichu" class="col-sm-2 col-form-label">Ghi chú</label>
                                            <div class="col-sm-10"><textarea name="ghichu" id="ghichu" class="form-control"></textarea></div>
                                        </div>
                                        
                                        <div class="position-relative row form-group"><label for="cha" class="col-sm-2 col-form-label">Hóa đơn liên quan</label>
                                            <div class="col-sm-10">
                                                <select name="cha" id="cha" class="form-control"></select>
                                            </div>
                                        </div>
                                        
                                        
                                        
                                        <div class="position-relative row form-group"><label for="gia" class="col-sm-2 col-form-label">Giá trị</label>
                                            <div class="col-sm-10"><input name="gia" id="gia" type="text" class="form-control"></div>
                                        </div>
                                         
                                    </form>
                                </div>
                            </div>
                            <div class="main-card mb-3 card">
                                <div class="card-body">
                                    <form class="">
                                        
                                        <div class="position-relative row form-check">
                                            <div class="col-sm-10 offset-sm-2">
                                                <button class="btn btn-info themthuchi" type="button">Thêm phiếu thu chi</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                         
                    </div>
                </div>
            </div>
 
<? include(dirname(__FILE__).'/footer.php') ?>