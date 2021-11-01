<?php include(dirname(__FILE__).'/header.php') ?>
<?php
$hanche = 0;
if( !empty($employee) && !empty($employee->vaitro) && !in_array('duan',$employee->vaitro) && in_array('tiendo',$employee->vaitro) )
    $hanche = 1;
?>

            <div class="app-main__outer">
                <div class="app-main__inner">
                    <div class="app-page-title">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div class="page-title-icon">
                                    <i class="pe-7s-box1 icon-gradient bg-sunny-morning">
                                    </i>
                                </div>
                                <div>Danh sách dự án
                                    <div class="page-title-subheading">Danh sách dự án
                                    </div>
                                </div>
                            </div>
                            <?php if(!$hanche){?>
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
                                                <a class="nav-link" href="/duan/them">
                                                    <i class="nav-link-icon lnr-plus-circle"></i>
                                                    <span>
                                                        Thêm dự án
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
                            </div>   
                            <?php }?>
                            </div>
                    </div>            
                    <div class="tab-content">
                        <div class="tab-pane tabs-animation fade active show" id="tab-content-0" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-12">
                                    
                                    <div class="main-card mb-3 card">
                                        <div class="card-header">Danh sách dự án</div>
                                        <ul class="todo-list-wrapper list-group list-group-flush">
                                            
                                            
                                            <?php foreach($admins as $admin){?>
                                            <li class="list-group-item">
                                                <div class="todo-indicator bg-info"></div>
                                                <div class="widget-content p-0">
                                                    <div class="widget-content-wrapper">
                                                        <div class="widget-content-left mr-2">
                                                            <div class="custom-checkbox custom-control"><input type="checkbox" id="exampleCustomCheckbox<?=$admin->id?>" class="custom-control-input" value="<?=$admin->id?>"<?=$hanche?' disabled':''?> /><label class="custom-control-label" for="exampleCustomCheckbox<?=$admin->id?>">&nbsp;</label>
                                                            </div>
                                                        </div>
                                                         
                                                        <div class="widget-content-left flex2">
                                                            <div class="widget-heading"<?=$admin->trangthai==0?' style="text-decoration: line-through;"':''?>><?=$admin->trangthai==0||$admin->duancon==0||$hanche?'':('<a href="/duan/con/'.($admin->id).'">')?><?=$admin->ten?><?=$admin->trangthai==0||$admin->duancon==0||$hanche?'':'</a>'?></div>
                                                            <div class="widget-subheading"><?=utils::mb_substr($admin->ghichu,50)?></div>
                                                        </div>
                                                        
                                                        <div class="widget-content-right" style="width: 150px;">
                                                            <div class="widget-heading"><?=$admin->ma?></div>
                                                            <div class="widget-subheading"><?=date('d/m/Y H:i:s',strtotime($admin->ngay))?></div>
                                                        </div>
                                                        <div class="widget-content-right"> 
                                                            <?php if(!$hanche){?>
                                                            <a class="border-0 btn-transition btn btn-outline-success" href="/duan/dinhluong/<?=$admin->id?>">
                                                                <i class="fa fa-fw" aria-hidden="true" title=""></i>
                                                            </a> 
                                                            <a class="border-0 btn-transition btn btn-outline-success" href="/duan/them/<?=$admin->id?>">
                                                                <i class="fa fa-plus"></i>
                                                            </a> 
                                                            <a class="border-0 btn-transition btn btn-outline-success" href="/duan/chuyen/<?=$admin->id?>">
                                                                <i class="fa fa-users"></i>
                                                            </a> 
                                                            <?php }?>
                                                            <a class="border-0 btn-transition btn btn-outline-success" href="/duan/cat/<?=$admin->id?>">
                                                                <i class="fa fa-cut"></i>
                                                            </a> 
                                                            <button class="border-0 btn-transition btn btn-outline-success" data-toggle="modal" data-target="#exampleModalLong" data-action="viewDA" data-id="<?=$admin->id?>">
                                                                <i class="fa fa-eye"></i>
                                                            </button> 
                                                            <?php if(!$hanche){?>
                                                            <button class="border-0 btn-transition btn btn-outline-<?=$admin->trangthai==0?'danger':'success'?>" data-toggle="modal" data-target=".bd-example-modal-sm" data-action="trangthaiDA" data-id="<?=$admin->id?>">
                                                                <i class="fa fa-<?=$admin->trangthai==0?'ban':'check'?>"></i>
                                                            </button>
                                                            <button class="border-0 btn-transition btn btn-outline-success" data-toggle="modal" data-target=".bd-example-modal-sm" data-action="cloneDA" data-id="<?=$admin->id?>">
                                                                <i class="fa fa-copy"></i>
                                                            </button>
                                                            <button class="border-0 btn-transition btn btn-outline-danger" data-toggle="modal" data-target=".bd-example-modal-sm" data-action="deleteDA" data-id="<?=$admin->id?>">
                                                                <i class="fa fa-trash-alt"></i>
                                                            </button>
                                                            <?php }?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <?php }?>
                                            
                                        </ul>
                                        <div class="card-body">
                                        <nav class="pagination-rounded" aria-label="Page navigation example">
                                            <ul class="pagination">
                                                <?php if($page>1){?>
                                                <li class="page-item"><a href="/duan/<?=$page-1?><?=$filter?('?'.$filter):''?>" class="page-link" aria-label="Previous"><span aria-hidden="true">«</span><span class="sr-only">Previous</span></a></li>
                                                <?php for($i=max(1,$page-5);$i<$page;$i++){?>
                                                <li class="page-item"><a href="/duan/<?=$i?><?=$filter?('?'.$filter):''?>" class="page-link"><?=$i?></a></li>
                                                <?php }}?>
                                                <li class="page-item active"><a href="javascript:void(0);" class="page-link"><?=$page?></a></li>
                                                <?php if($page<$pages){?>
                                                <?php for($i=$page+1;$i<=min($pages,$page+5);$i++){?>
                                                <li class="page-item"><a href="/duan/<?=$i?><?=$filter?('?'.$filter):''?>" class="page-link"><?=$i?></a></li>
                                                <?php }?>
                                                <li class="page-item"><a href="/duan/<?=$page+1?><?=$filter?('?'.$filter):''?>" class="page-link" aria-label="Next"><span aria-hidden="true">»</span><span class="sr-only">Next</span></a></li>
                                                <?php }?>
                                            </ul>
                                        </nav>
                                        </div>
                                        <div class="d-block text-right card-footer">
                                             
                                            <button class="border-0 btn-transition btn btn-outline-success" data-toggle="modal" data-target="#exampleModalLong" data-action="filterDA" data-id="<?=$kho?>" data-filter="<?=$filter?>"> 
                                                Bộ lọc <i class="fa fa-filter"></i>
                                            </button>
                                            <button class="btn btn-warning btn-lg" data-toggle="modal" data-target=".bd-example-modal-sm" data-action="deleteSelectedDA">Xóa</button>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        
                    </div>
                </div>
                    </div>
    
 

 
<? include(dirname(__FILE__).'/footer.php') ?>