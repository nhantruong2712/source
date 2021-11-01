<? include(dirname(__FILE__).'/header.php') ?>



            <div class="app-main__outer xuatnhap nhap" data-id="<?=$kho?>">
                <div class="app-main__inner">
                    <div class="app-page-title">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div class="page-title-icon">
                                    <i class="pe-7s-back-2 text-success">
                                    </i>
                                </div>
                                <div>Thêm đơn nhập hàng<div class="page-title-subheading">Thêm đơn nhập hàng</div>
                                </div>
                            </div>
                        </div>
                    </div>            <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                        <li class="nav-item">
                            <a role="tab" class="nav-link show" id="tab-0" data-toggle="tab" href="#tab-content-0" aria-selected="true">
                                <span>Sản phẩm</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a role="tab" class="nav-link show active" id="tab-1" data-toggle="tab" href="#tab-content-1" aria-selected="false">
                                <span>Thông tin</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane tabs-animation fade" id="tab-content-0" role="tabpanel">
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Tìm sản phẩm</h5>
                                    <form class="">
                                         
                                        <div class="form-row">
                                            <div class="col-md-5">
                                                <div class="position-relative form-group"><label for="searchProduct" class="">Tìm theo tên hoặc mã</label>
                                                    <select name="searchProduct" id="searchProduct" class="form-control"></select>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="position-relative form-group"><label for="addItemQuick" class="">Thêm</label>
                                                    <button class="btn btn-primary" id="addItemQuick" type="button" data-toggle="modal" data-target="#exampleModalLong" data-action="addSP" data-id="<?=$kho?>"><i class="fa fa-plus"></i></button>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="position-relative form-group"><label for="productPrice" class="">Giá</label><input name="productPrice" id="productPrice" type="text" class="form-control" data-toggle="tooltip" data-placement="top" title="" data-original-title="Nhập giảm giá bằng cách: 100000-10000 hoặc 100000-5%" /></div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="position-relative form-group"><label for="productQuantity" class="">Số lượng</label><input name="productQuantity" id="productQuantity" value="1" type="text" class="form-control"></div>
                                            </div>
                                        </div>
                                        
                                        <button class="mt-2 btn btn-primary" id="addItem" type="button">Thêm</button>
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
                                            <tr>
                                                <th scope="row"><button class="border-0 btn-transition btn btn-outline-danger" data-toggle="modal" data-target=".bd-example-modal-sm" data-action="deleteItem" data-id="3">
                                                    <i class="fa fa-trash-alt"></i>
                                                </button></th>
                                                <td>Table cell</td>
                                                <td>SP0000001</td>
                                                <td>100.000</td>
                                                <td>10.000</td>
                                                <td>
                                                    <input name="soluong" type="text" class="soluong form-control form-control-sm" value="1" />
                                                    <div class="downup">
                                                    <div class="up"><i class="fa fa-fw" aria-hidden="true" title="Tăng SL lên 1"></i></div>
                                                    <div class="down"><i class="fa fa-fw" aria-hidden="true" title="Giảm SL xuống 1"></i></div>
                                                    </div>
                                                </td>
                                                <td>900.000</td>
                                            </tr>
                                            
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div><div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Tổng quan</h5>
                                    <form class="">
                                        <div class="position-relative row form-group"><label class="col-sm-2 col-form-label">Thành tiền</label>
                                            <div class="col-sm-10"><button class="btn btn-primary" type="button" id="thanhtien">1.000.000</button></div>
                                        </div><div class="position-relative row form-group"><label for="giamgia" class="col-sm-2 col-form-label">Giảm giá</label>
                                            <div class="col-sm-10"><input name="giamgia" id="giamgia" type="text" class="form-control"></div>
                                        </div><div class="position-relative row form-group"><label for="phithem" class="col-sm-2 col-form-label">Phí thêm</label>
                                            <div class="col-sm-10"><input name="phithem" id="phithem" type="text" class="form-control"></div>
                                        </div>
                                        <div class="position-relative row form-group"><label class="col-sm-2 col-form-label">Tổng tiền</label>
                                            <div class="col-sm-10"><button class="btn btn-primary" type="button" id="tongtien">1.000.000</button></div>
                                        </div>
                                        
                                        <div class="position-relative row form-group"><label for="thanhtoan" class="col-sm-2 col-form-label">Tạm ứng/Thanh toán</label>
                                            <div class="col-sm-10"><input name="thanhtoan" id="thanhtoan" value="0" type="text" class="form-control" /></div>
                                        </div>
                                        
                                        
                                        
                                        
                                        <div class="position-relative row form-check">
                                            <div class="col-sm-10 offset-sm-2">
                                                <button class="btn btn-info themdonhang" type="button">Thêm đơn hàng</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane tabs-animation fade active show" id="tab-content-1" role="tabpanel">
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Thông tin</h5>
                                    <form class="">
                                        <div class="position-relative row form-group"><label for="ma" class="col-sm-2 col-form-label">Mã phiếu</label>
                                            <div class="col-sm-10"><input name="ma" id="ma" placeholder="NH0000001 hoặc bỏ trống" type="text" class="form-control"></div>
                                        </div>
                                        <div class="position-relative row form-group"><label for="ngay" class="col-sm-2 col-form-label">Thời gian <abbr lang="required">*</abbr></label>
                                            <div class="col-sm-10">
                                                <input name="ngay" id="ngay" class="form-control input-mask-trigger" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy HH:MM:ss" im-insert="false">
                                                 
                                            </div>
                                        </div>
                                        <div class="position-relative row form-group"><label for="tu" class="col-sm-2 col-form-label">Từ <abbr lang="required">*</abbr></label>
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
                                        
                                        <div class="position-relative row form-group"><label for="duan" class="col-sm-2 col-form-label">Dự án</label>
                                            <div class="col-sm-10"><select name="duan" id="duan" class="form-control"></select></div>
                                        </div>
                                        
                                        <div class="position-relative row form-group"><label for="ghichu" class="col-sm-2 col-form-label">Ghi chú</label>
                                            <div class="col-sm-10"><textarea name="ghichu" id="ghichu" class="form-control"></textarea></div>
                                        </div>
                                         
                                        <div class="position-relative row form-check">
                                            <div class="col-sm-10 offset-sm-2">
                                                <button class="btn btn-info themdonhang" type="button">Thêm đơn hàng</button>
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