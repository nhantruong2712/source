<? include(dirname(__FILE__).'/header.php') ?>



            <div class="app-main__outer">
                <div class="app-main__inner">
                    <div class="app-page-title">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div class="page-title-icon">
                                    <i class="pe-7s-user icon-gradient bg-sunny-morning">
                                    </i>
                                </div>
                                <div>THÔNG TIN CÁN NHÂN
                                    <div class="page-title-subheading">Thông tin cá nhân
                                    </div>
                                </div>
                            </div>
                            </div>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="main-card mb-3 card">
                                        <div class="card-body"><h5 class="card-title">Thông tin cá nhân</h5>
                                            <form id="editprofileform" method="post" class="">
                                                <div class="position-relative form-group"><label for="exampleName" class="">Tên</label><input name="ten" id="exampleName" type="text" class="form-control" value="<?=$employee->ten?>"></div>
                                                <div class="position-relative form-group"><label for="exampleEmail" class="">Email</label><input name="email" id="exampleEmail" type="email" class="form-control" value="<?=$employee->email?>"></div>
                                                <div class="position-relative form-group"><label for="examplePassword" class="">Mật khẩu</label><input name="password" id="examplePassword" type="password" class="form-control"></div>
                                                <div class="position-relative form-group"><label for="examplePassword2" class="">Nhập lại</label><input name="_password" id="examplePassword2" placeholder="Nhập lại mật khẩu" type="password" class="form-control"></div>
                                                
                                                <div class="position-relative form-group"><label for="exampleAdd" class="">Địa chỉ</label><input name="diachi" id="exampleAdd" type="text" class="form-control" value="<?=$employee->diachi?>"></div>
                                                <div class="position-relative form-group"><label for="examplePhone" class="">Số điện thoại</label><input name="sdt" id="examplePhone" type="text" class="form-control" value="<?=$employee->sdt?>"></div>
                                                
                                                <div class="position-relative form-group"><label for="exampleText" class="">Ghi chú</label><textarea name="ghichu" id="exampleText" class="form-control"><?=$employee->ghichu?></textarea></div>
                                                 
                                                <button class="mt-1 btn btn-primary" type="button" id="editprofile">Sửa</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                         
                    </div>
                </div>
                </div>
    
 
 
<? include(dirname(__FILE__).'/footer.php') ?>