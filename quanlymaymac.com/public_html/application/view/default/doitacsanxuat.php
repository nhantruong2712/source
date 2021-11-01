<? include(dirname(__FILE__).'/header.php') ?>



            <div class="app-main__outer">
                <div class="app-main__inner">
                    <div class="app-page-title">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div class="page-title-icon">
                                    <i class="pe-7s-graph icon-gradient bg-ripe-malin">
                                    </i>
                                </div>
                                <div>Dashboard
                                    <div class="page-title-subheading">Tổng quan
                                    </div>
                                </div>
                            </div>
                                </div>
                    </div>            
                    <div class="tabs-animation">
                        <div class="row">
                            <div class="col-md-6 col-xl-4">
                                <div class="card mb-3 widget-content bg-night-fade">
                                    <div class="widget-content-wrapper text-white">
                                        <div class="widget-content-left">
                                            <div class="widget-heading">Admin</div>
                                            <div class="widget-subheading">Tổng số admin</div>
                                        </div>
                                        <div class="widget-content-right">
                                            <div class="widget-numbers text-white"><span><?=$infos['admin']?></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-4">
                                <div class="card mb-3 widget-content bg-arielle-smile">
                                    <div class="widget-content-wrapper text-white">
                                        <div class="widget-content-left">
                                            <div class="widget-heading">Đối tác sản xuất</div>
                                            <div class="widget-subheading">Tổng số đối tác sản xuất</div>
                                        </div>
                                        <div class="widget-content-right">
                                            <div class="widget-numbers text-white"><span><?=$infos['doitacsanxuat']?></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-4">
                                <div class="card mb-3 widget-content bg-happy-green">
                                    <div class="widget-content-wrapper text-white">
                                        <div class="widget-content-left">
                                            <div class="widget-heading">Nhà cung cấp</div>
                                            <div class="widget-subheading">Tổng số nhà cung cấp</div>
                                        </div>
                                        <div class="widget-content-right">
                                            <div class="widget-numbers text-white"><span><?=$infos['nhacungcap']?></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 col-xl-4">
                                <div class="card mb-3 widget-content bg-mean-fruit">
                                    <div class="widget-content-wrapper text-white">
                                        <div class="widget-content-left">
                                            <div class="widget-heading">Khách hàng</div>
                                            <div class="widget-subheading">Tổng số Khách hàng</div>
                                        </div>
                                        <div class="widget-content-right">
                                            <div class="widget-numbers text-white"><span><?=$infos['khachhang']?></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 col-xl-4">
                                <div class="card mb-3 widget-content bg-midnight-bloom">
                                    <div class="widget-content-wrapper text-white">
                                        <div class="widget-content-left">
                                            <div class="widget-heading">Dự án</div>
                                            <div class="widget-subheading">Tổng số Dự án đang triển khai</div>
                                        </div>
                                        <div class="widget-content-right">
                                            <div class="widget-numbers text-white"><span><?=$infos['duan']?></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-header">Danh sách 5 admin mới nhất
                                        
                                    </div>
                                    <div class="table-responsive">
                                        <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>Tên</th>
                                                <th class="text-center">SĐT</th>
                                                <th class="text-center">Trạng thái</th>
                                                <th class="text-center">Email</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php if($infos['admin']) foreach($infos['admins'] as $admin){?>
                                            <tr>
                                                <td class="text-center text-muted">#<?=$admin->id?></td>
                                                <td>
                                                    <div class="widget-content p-0">
                                                        <div class="widget-content-wrapper">
                                                            <div class="widget-content-left mr-3">
                                                                <div class="widget-content-left">
                                                                    <img width="40" class="rounded-circle" src="assets/images/avatars/4.jpg" alt="">
                                                                </div>
                                                            </div>
                                                            <div class="widget-content-left flex2">
                                                                <div class="widget-heading"><?=$admin->ten?></div>
                                                                <div class="widget-subheading opacity-7"><?=vietnamese::cut($admin->ghichu,50)?></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center"><?=$admin->sdt?></td>
                                                <td class="text-center">
                                                    <div class="badge badge-<?=$admin->trangthai?'success':'danger'?>"><?=$admin->trangthai?'Hoạt động':'Bị khóa'?></div>
                                                </td>
                                                <td class="text-center" style="width: 150px;">
                                                    <?=$admin->email?>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModalLong" data-action="editAdmin" data-id="<?=$admin->id?>">Sửa</button>
                                                </td>
                                            </tr>
                                            <?php }?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-block text-center card-footer">
                                        
                                        <a class="btn-wide btn btn-success" href="/admin/lists">Xem tất cả</a>
                                    </div> 
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-header">Danh sách 5 Đối tác sản xuất mới nhất
                                        
                                    </div>
                                    <div class="table-responsive">
                                        <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>Tên</th>
                                                <th class="text-center">SĐT</th>
                                                <th class="text-center">Trạng thái</th>
                                                <th class="text-center">Email</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php if($infos['doitacsanxuat']) foreach($infos['doitacsanxuats'] as $admin){?>
                                            <tr>
                                                <td class="text-center text-muted">#<?=$admin->id?></td>
                                                <td>
                                                    <div class="widget-content p-0">
                                                        <div class="widget-content-wrapper">
                                                            <div class="widget-content-left mr-3">
                                                                <div class="widget-content-left">
                                                                    <img width="40" class="rounded-circle" src="assets/images/avatars/3.jpg" alt="">
                                                                </div>
                                                            </div>
                                                            <div class="widget-content-left flex2">
                                                                <div class="widget-heading"><?=$admin->ten?></div>
                                                                <div class="widget-subheading opacity-7"><?=vietnamese::cut($admin->ghichu,50)?></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center"><?=$admin->sdt?></td>
                                                <td class="text-center">
                                                    <div class="badge badge-<?=$admin->trangthai?'success':'danger'?>"><?=$admin->trangthai?'Hoạt động':'Bị khóa'?></div>
                                                </td>
                                                <td class="text-center" style="width: 150px;">
                                                    <?=$admin->email?>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModalLong" data-action="editDTSX" data-id="<?=$admin->id?>">Sửa</button>
                                                </td>
                                            </tr>
                                            <?php }?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-block text-center card-footer">
                                        
                                        <a class="btn-wide btn btn-success" href="/doitacsanxuat/lists">Xem tất cả</a>
                                    </div> 
                                </div>
                            </div>
                        </div> 
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-header">Danh sách 5 Nhà cung cấp mới nhất
                                        
                                    </div>
                                    <div class="table-responsive">
                                        <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>Tên</th>
                                                <th class="text-center">SĐT</th>
                                                <th class="text-center">Trạng thái</th>
                                                <th class="text-center">Email</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php if($infos['nhacungcap']) foreach($infos['nhacungcaps'] as $admin){?>
                                            <tr>
                                                <td class="text-center text-muted">#<?=$admin->id?></td>
                                                <td>
                                                    <div class="widget-content p-0">
                                                        <div class="widget-content-wrapper">
                                                            <div class="widget-content-left mr-3">
                                                                <div class="widget-content-left">
                                                                    <img width="40" class="rounded-circle" src="assets/images/avatars/2.jpg" alt="">
                                                                </div>
                                                            </div>
                                                            <div class="widget-content-left flex2">
                                                                <div class="widget-heading"><?=$admin->ten?></div>
                                                                <div class="widget-subheading opacity-7"><?=vietnamese::cut($admin->ghichu,50)?></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center"><?=$admin->sdt?></td>
                                                <td class="text-center">
                                                    <div class="badge badge-<?=$admin->trangthai?'success':'danger'?>"><?=$admin->trangthai?'Hoạt động':'Bị khóa'?></div>
                                                </td>
                                                <td class="text-center" style="width: 150px;">
                                                    <?=$admin->email?>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModalLong" data-action="editNCC" data-id="<?=$admin->id?>">Sửa</button>
                                                </td>
                                            </tr>
                                            <?php }?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-block text-center card-footer">
                                        
                                        <a class="btn-wide btn btn-success" href="/nhacungcap">Xem tất cả</a>
                                    </div> 
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-header">Danh sách 5 Khách hàng mới nhất
                                        
                                    </div>
                                    <div class="table-responsive">
                                        <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>Tên</th>
                                                <th class="text-center">SĐT</th>
                                                <th class="text-center">Trạng thái</th>
                                                <th class="text-center">Email</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php if($infos['khachhang']) foreach($infos['khachhangs'] as $admin){?>
                                            <tr>
                                                <td class="text-center text-muted">#<?=$admin->id?></td>
                                                <td>
                                                    <div class="widget-content p-0">
                                                        <div class="widget-content-wrapper">
                                                            <div class="widget-content-left mr-3">
                                                                <div class="widget-content-left">
                                                                    <img width="40" class="rounded-circle" src="assets/images/avatars/1.jpg" alt="">
                                                                </div>
                                                            </div>
                                                            <div class="widget-content-left flex2">
                                                                <div class="widget-heading"><?=$admin->ten?></div>
                                                                <div class="widget-subheading opacity-7"><?=vietnamese::cut($admin->ghichu,50)?></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center"><?=$admin->sdt?></td>
                                                <td class="text-center">
                                                    <div class="badge badge-<?=$admin->trangthai?'success':'danger'?>"><?=$admin->trangthai?'Hoạt động':'Bị khóa'?></div>
                                                </td>
                                                <td class="text-center" style="width: 150px;">
                                                    <?=$admin->email?>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModalLong" data-action="editKH" data-id="<?=$admin->id?>">Sửa</button>
                                                </td>
                                            </tr>
                                            <?php }?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-block text-center card-footer">
                                        
                                        <a class="btn-wide btn btn-success" href="/khachhang">Xem tất cả</a>
                                    </div> 
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-header">Danh sách 5 Dự án mới nhất
                                        
                                    </div>
                                    <div class="table-responsive">
                                        <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>Tên</th>
                                                <th class="text-center">Mã</th>
                                                <th class="text-center">Sản phẩm</th>
                                                <th class="text-center">Số lượng</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php if($infos['duan']) foreach($infos['duans'] as $admin){?>
                                            <tr>
                                                <td class="text-center text-muted">#<?=$admin->id?></td>
                                                <td>
                                                    <div class="widget-content p-0">
                                                        <div class="widget-content-wrapper">
                                                            
                                                            <div class="widget-content-left flex2">
                                                                <div class="widget-heading"><?=$admin->ten?></div>
                                                                <div class="widget-subheading opacity-7"><?=vietnamese::cut($admin->ghichu,50)?></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center"><?=$admin->ma?></td>
                                                <td class="text-center">
                                                    <?=$admin->sanphamten?>
                                                </td>
                                                <td class="text-center" style="width: 150px;">
                                                    <?=$admin->soluong?>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModalLong" data-action="viewDA" data-id="<?=$admin->id?>">Xem</button>
                                                </td>
                                            </tr>
                                            <?php }?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-block text-center card-footer">
                                        
                                        <a class="btn-wide btn btn-success" href="/duan">Xem tất cả</a>
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    </div>
    </div>
</div>
<div class="app-drawer-wrapper">
    <div class="drawer-nav-btn">
        <button type="button" class="hamburger hamburger--elastic is-active">
            <span class="hamburger-box"><span class="hamburger-inner"></span></span></button>
    </div>
    <div class="drawer-content-wrapper">
         
 
<? include(dirname(__FILE__).'/footer.php') ?>