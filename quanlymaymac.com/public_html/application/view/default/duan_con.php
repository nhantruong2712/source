<? include(dirname(__FILE__).'/header.php') ?>



            <div class="app-main__outer"> 
             
                <div class="app-main__inner">
                    
                    <div class="app-page-title">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div class="page-title-icon">
                                    <i class="pe-7s-box1 icon-gradient bg-sunny-morning">
                                    </i>
                                </div>
                                <div>Danh sách dự án con
                                    <div class="page-title-subheading">Dự án: <?=$model->ten?> <em>(<?=$model->ma?>)</em>
                                    </div>
                                </div>
                            </div>
                            <div class="page-title-actions">
                                 
                                <div class="d-inline-block dropdown">
                                    <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-shadow dropdown-toggle btn btn-info">
                                        <span class="btn-icon-wrapper pr-2 opacity-7">
                                            <i class="fa fa-business-time fa-w-20"></i>
                                        </span>
                                        Hành động
                                    </button>
                                    <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(-129px, 33px, 0px);">
                                        <ul class="nav flex-column">
                                            <li class="nav-item">
                                                <a class="nav-link" href="/duan/them/<?=$model->id?>">
                                                    <i class="nav-link-icon lnr-plus-circle"></i>
                                                    <span>
                                                        Thêm dự án con
                                                    </span>
                                                     
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link">
                                                    <i class="nav-link-icon lnr-arrow-down"></i>
                                                    <span>
                                                        Xuất Excel
                                                    </span>
                                                     
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link">
                                                    <i class="nav-link-icon lnr-arrow-up"></i>
                                                    <span>
                                                        Nhập Excel
                                                    </span>
                                                </a>
                                            </li>
                                             
                                        </ul>
                                    </div>
                                </div>
                            </div>   </div>
                    </div> 
                    
                     
                    <div class="tab-content">
                        <div class="tab-pane tabs-animation fade active show" id="tab-content-0" role="tabpanel">
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Thông tin dự án cha</h5>
                                    <form class="">
                                        <div class="position-relative row form-group"><label for="ma" class="col-sm-3 col-form-label">Mã dự án</label>
                                            <div class="col-sm-9 mt-2"><?=$model->ma?></div>
                                        </div>
                                        <div class="position-relative row form-group"><label for="ten" class="col-sm-3 col-form-label">Tên dự án</label>
                                             
                                            <div class="col-sm-9 mt-2"><?=$model->ten?></div>
                                            
                                        </div>
                                        <div class="position-relative row form-group"><label for="ngay" class="col-sm-3 col-form-label">Thời gian</label>
                                            <div class="col-sm-9 mt-2">
                                                <?=date('d/m/Y H:i:s',strtotime($model->ngay))?>
                                                <input type="hidden" name="id" id="id" value="<?=$model->id?>"> 
                                            </div>
                                        </div>
                                        <div class="position-relative row form-group"><label for="batdau" class="col-sm-3 col-form-label">Ngày bắt đầu</label>
                                            <div class="col-sm-9 mt-2">
                                                <?=date('d/m/Y',strtotime($model->batdau))?>                                                 
                                            </div>
                                        </div>
                                        <div class="position-relative row form-group"><label for="ketthuc" class="col-sm-3 col-form-label">Ngày kết thúc</label>
                                            <div class="col-sm-9 mt-2">
                                                <?=$model->ketthuc=='0000-00-00 00:00:00'?'':date('d/m/Y',strtotime($model->ketthuc))?>                                                 
                                            </div>
                                        </div>
                                        <div class="position-relative row form-group"><label for="tu" class="col-sm-3 col-form-label">Khách hàng</label>
                                            <div class="col-sm-9 mt-2"><a href="/khachhang?filter[search]=<?=$model->khachhang->ten?>"><?=$model->khachhang->ten?></a></div>
                                        </div> 
                                        <div class="position-relative row form-group"><label for="ghichu" class="col-sm-3 col-form-label">Ghi chú</label>
                                            <div class="col-sm-9 mt-2"><?=$model->ghichu?></div>
                                        </div>
                                          
                                    </form>
                                </div>
                            </div>
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Sản phẩm</h5>
                                    <div class="table-responsive">
                                        <table class="mb-0 table table-sanpham">
                                            <thead>
                                            <tr>
                                                <th></th>
                                                <th>Sản phẩm</th>
                                                <th>Mã</th>
                                                <th>Đơn giá</th>
                                                <!--th>Giảm giá</th-->
                                                <th class="soluong">Số lượng</th>
                                                <th>Thành tiền</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                             
                                            <tr>
                                                <th scope="row"></th>
                                                <td><?=$model->sanpham->ten?></td>
                                                <td><?=$model->sanpham->ma?></td>
                                                <td><?=number::format_number($model->gia)?></td>
                                                <!--td></td-->
                                                <td>
                                                    <?=number::format_number($model->soluong)?>
                                                </td>
                                                <td><?=number::format_number($model->soluong*($model->gia))?></td>
                                            </tr>
                                             
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Dự án con</h5>
                                    <div class="table-responsive">
                                        <table class="mb-0 table table-sanpham">
                                            <thead>
                                            <tr>
                                                <th></th>
                                                <th>Tên</th>
                                                <th>Mã</th>
                                                <th>Đơn giá</th>
                                                 
                                                <th class="soluong">Số lượng</th>
                                                <th>Thành tiền</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach($model->duancon as $duan){?> 
                                            <tr>
                                                <th scope="row"></th>
                                                <td><?=$duan->ten?></td>
                                                <td><?=$duan->ma?></td>
                                                <td><?=number::format_number($duan->gia)?></td>
                                                <!--td></td-->
                                                <td>
                                                    <?=number::format_number($duan->soluong)?>
                                                </td>
                                                <td><?=number::format_number($duan->soluong*($duan->gia))?></td>
                                                <td>
                                                    <button class="border-0 btn-transition btn btn-outline-success" data-toggle="modal" data-target="#exampleModalLong" data-action="viewDA" data-id="<?=$duan->id?>">
                                                        <i class="fa fa-eye"></i>
                                                    </button> 
                                                    <button class="border-0 btn-transition btn btn-outline-danger" data-toggle="modal" data-target=".bd-example-modal-sm" data-action="deleteDA" data-id="<?=$duan->id?>">
                                                        <i class="fa fa-trash-alt"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php }?> 
                                            </tbody>
                                        </table>
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
    
</style> 

</div>
    
 

 
<? include(dirname(__FILE__).'/footer.php') ?> 
