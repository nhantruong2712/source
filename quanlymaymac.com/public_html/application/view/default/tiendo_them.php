<? include(dirname(__FILE__).'/header.php') ?>



            <div class="app-main__outer tiendo them" data-id="<?=$kho?>">
                <div class="app-main__inner">
                    <div class="app-page-title">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div class="page-title-icon">
                                    <i class="pe-7s-graph2 text-success">
                                    </i>
                                </div>
                                <div>Thêm bảng tiến độ<div class="page-title-subheading">Thêm bảng tiến độ</div>
                                </div>
                            </div>
                            
                        </div>
                    </div>            
                    <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                        <li class="nav-item">
                            <a role="tab" class="nav-link show active" id="tab-0" data-toggle="tab" href="#tab-content-0" aria-selected="true">
                                <span>Thông tin</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a role="tab" class="nav-link show" id="tab-1" data-toggle="tab" href="#tab-content-1" aria-selected="false">
                                <span>Nhân viên</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane tabs-animation fade active show" id="tab-content-0" role="tabpanel">
                            
                            
                            
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Thông tin</h5>
                                    <form class="">
                                         
                                        <div class="position-relative row form-group"><label for="ngay" class="col-sm-2 col-form-label">Ngày</label>
                                            <div class="col-sm-10">
                                                <input name="ngay" id="ngay" class="form-control" value="<?=date('d/m/Y')?>" data-toggle="datepicker" autocomplete="nope-password" />
                                                 
                                            </div>
                                        </div>
                                        <div class="position-relative row form-group"><label for="duan" class="col-sm-2 col-form-label">Dự án</label>
                                            <div class="col-sm-10"><select name="duan" id="duan" class="form-control">
                                                <option value="">-Chọn-</option>
                                                
                                            </select></div>
                                        </div>
                                        <div class="position-relative row form-group"><label for="chuyen" class="col-sm-2 col-form-label">Chuyền</label>
                                            <div class="col-sm-10"><select name="chuyen" id="chuyen" class="form-control">
                                            </select></div>
                                        </div>
                                        
                                        <div class="position-relative row form-group"><label for="hoanthanh" class="col-sm-2 col-form-label">SL hoàn thành</label>
                                            <div class="col-sm-10">
                                                <input name="hoanthanh" id="hoanthanh" class="form-control" />
                                                 
                                            </div>
                                        </div>
                                         
                                        <div class="position-relative row form-check">
                                            <div class="col-sm-10 offset-sm-2">
                                                <button class="btn btn-info themtiendo" type="button">Thêm</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane tabs-animation fade" id="tab-content-1" role="tabpanel">
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Danh sách nhân viên</h5>
                                    <form class="nhanvien">
                                         
                                        <div class="form-row">
                                            <div class="col-md-8">
                                                <div class="position-relative form-group">                                                     
                                                    <select name="nhanvien" id="nhanvien" class="form-control"></select>
                                                </div>
                                            </div>
                                             
                                            <div class="col-md-4">
                                                <div class="position-relative form-group">
                                                 
                                                    <input name="sanluong" id="sanluong" placeholder="Sản lượng" type="text" class="form-control" />
                                                </div>
                                            </div>
                                             
                                        </div>
                                        
                                         
                                    </form>
                                </div>
                            </div>
                            
                            <div class="main-card mb-3 card">
                                <div class="card-body"> 
                                    <form class="">
                                         
                                        <div class="position-relative row form-check">
                                            <div class="col-sm-10 offset-sm-2">
                                                <button class="btn btn-info themtiendo" type="button">Thêm</button>
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