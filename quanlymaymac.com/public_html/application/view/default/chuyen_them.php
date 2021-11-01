<? include(dirname(__FILE__).'/header.php') ?>



            <div class="app-main__outer chuyen" data-id="<?=$kho?>" data-duan="<?=$duan->id?>">
                <div class="app-main__inner">
                    <div class="app-page-title">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div class="page-title-icon">
                                    <i class="pe-7s-users text-success">
                                    </i>
                                </div>
                                <div>Thêm chuyền<div class="page-title-subheading">Cho dự án: <?=$duan->ten?></div>
                                </div>
                            </div>
                        </div>
                    </div>            
                    <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                        <li class="nav-item">
                            <a role="tab" class="nav-link show active" id="tab-0" data-toggle="tab" href="#tab-content-0" aria-selected="true">
                                <span>Nhân viên</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a role="tab" class="nav-link show" id="tab-1" data-toggle="tab" href="#tab-content-1" aria-selected="false">
                                <span>Thông tin</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane tabs-animation fade active show" id="tab-content-0" role="tabpanel">
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Tìm nhân viên</h5>
                                    <form class="">
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group"><label for="searchNCD" class="">Nhóm công đoạn</label>
                                                    <select name="searchNCD" id="searchNCD" class="form-control">
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
                                                <div class="position-relative form-group"><label for="searchNV" class="">Tìm theo tên hoặc email</label>
                                                    <select name="searchNV" id="searchNV" class="form-control"></select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group"><label for="searchCD" class="">Công đoạn</label>
                                                    <select name="searchCD" id="searchCD" class="form-control">
                                                        <?php foreach($congdoan as $cd){?>
                                                        <option value="<?=$cd->id?>"><?=$cd->ten?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            </div>
                                             
                                        </div>
                                        
                                        <button class="mt-2 btn btn-primary" id="addItem" type="button">Thêm</button>
                                    </form>
                                </div>
                            </div>
                            
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Danh sách nhân viên</h5>
                                    <div class="table-responsive">
                                        <table class="mb-0 table table-sanpham">
                                            <thead>
                                            <tr>
                                                <th></th>
                                                <th>Nhân viên</th>
                                                <th>Email</th>
                                                <th>Công đoạn</th>
                                                  
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <th scope="row"><button class="border-0 btn-transition btn btn-outline-danger" data-toggle="modal" data-target=".bd-example-modal-sm" data-action="deleteItemC" data-id="3">
                                                    <i class="fa fa-trash-alt"></i>
                                                </button></th>
                                                <td>Nhan vien 1</td>
                                                <td>nhanvien@gmail.com</td>
                                                <td></td>
                                                 
                                            </tr>
                                            
                                            </tbody>
                                        </table>
                                        
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="main-card mb-3 card">
                                <div class="card-body"> 
                                    <form class="">
                                         
                                        <div class="position-relative row form-check">
                                            <div class="col-sm-10 offset-sm-2">
                                                <button class="btn btn-info themchuyen" type="button">Thêm chuyền</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane tabs-animation fade" id="tab-content-1" role="tabpanel">
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Thông tin</h5>
                                    <form class="">
                                        <div class="position-relative row form-group"><label for="ten" class="col-sm-2 col-form-label">Tên chuyền</label>
                                            <div class="col-sm-10"><input name="ten" id="ten" type="text" class="form-control" /></div>
                                        </div>
                                        <div class="position-relative row form-group"><label for="soluong" class="col-sm-2 col-form-label">Số lượng sản phẩm</label>
                                            <div class="col-sm-5">
                                                <input name="soluong" id="soluong" class="form-control" />                                                 
                                            </div>
                                            <div class="col-sm-1 mt-2">
                                                /                                      
                                            </div>
                                            <div class="col-sm-4">
                                                <input name="soluong2" id="soluong2" readonly="" value="<?=$duan->soluongconlai?>" class="form-control" />                                                 
                                            </div>
                                            
                                        </div>
                                         
                                        <div class="position-relative row form-group"><label for="ghichu" class="col-sm-2 col-form-label">Ghi chú</label>
                                            <div class="col-sm-10"><textarea name="ghichu" id="ghichu" class="form-control"></textarea></div>
                                        </div>
                                         
                                        <div class="position-relative row form-check">
                                            <div class="col-sm-10 offset-sm-2">
                                                <button class="btn btn-info themchuyen" type="button">Thêm chuyền</button>
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