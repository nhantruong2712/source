<? include(dirname(__FILE__).'/header.php') ?>



            <div class="app-main__outer">
                <div class="app-main__inner">
                    <div class="app-page-title">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div class="page-title-icon">
                                    <i class="pe-7s-portfolio icon-gradient bg-sunny-morning">
                                    </i>
                                </div>
                                <div>Danh sách phòng ban
                                    <div class="page-title-subheading">Danh sách phòng ban
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
                                                <a class="nav-link" href="#" data-toggle="modal" data-target="#exampleModalLong" data-action="addPB">
                                                    <i class="nav-link-icon lnr-plus-circle"></i>
                                                    <span>
                                                        Thêm phòng ban
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
                            <div class="row">
                                <div class="col-lg-12">
                                    
                                    <div class="main-card mb-3 card">
                                        <div class="card-header">Danh sách phòng ban</div>
                                        <ul class="todo-list-wrapper list-group list-group-flush">
                                            
                                            
                                            <?php foreach($admins as $admin){?>
                                            <li class="list-group-item">
                                                <div class="todo-indicator bg-info"></div>
                                                <div class="widget-content p-0">
                                                    <div class="widget-content-wrapper">
                                                        <div class="widget-content-left mr-2">
                                                            <div class="custom-checkbox custom-control"><input type="checkbox" id="exampleCustomCheckbox<?=$admin->id?>" class="custom-control-input" value="<?=$admin->id?>" /><label class="custom-control-label" for="exampleCustomCheckbox<?=$admin->id?>">&nbsp;</label>
                                                            </div>
                                                        </div>
                                                        <div class="widget-content-left mr-3">
                                                            <div class="widget-content-left">
                                                                <img width="42" class="rounded" src="/assets/images/company.png" alt="" />
                                                            </div>
                                                        </div>
                                                        <div class="widget-content-left">
                                                            <div class="widget-heading"><?=$admin->ten?></div>
                                                            <div class="widget-subheading"><?=utils::mb_substr($admin->ghichu,50)?></div>
                                                        </div>
                                                        <div class="widget-content-right widget-content-actions">
                                                            <button class="border-0 btn-transition btn btn-outline-success" data-toggle="modal" data-target="#exampleModalLong" data-action="editPB" data-id="<?=$admin->id?>">
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                            <button class="border-0 btn-transition btn btn-outline-danger" data-toggle="modal" data-target=".bd-example-modal-sm" data-action="deletePB" data-id="<?=$admin->id?>">
                                                                <i class="fa fa-trash-alt"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <?php }?>
                                            
                                        </ul>
                                        <!--div class="card-body">
                                        <nav class="pagination-rounded" aria-label="Page navigation example">
                                            <ul class="pagination">
                                                <li class="page-item"><a href="javascript:void(0);" class="page-link" aria-label="Previous"><span aria-hidden="true">«</span><span class="sr-only">Previous</span></a></li>
                                                <li class="page-item"><a href="javascript:void(0);" class="page-link">1</a></li>
                                                <li class="page-item active"><a href="javascript:void(0);" class="page-link">2</a></li>
                                                <li class="page-item"><a href="javascript:void(0);" class="page-link">3</a></li>
                                                <li class="page-item"><a href="javascript:void(0);" class="page-link">4</a></li>
                                                <li class="page-item"><a href="javascript:void(0);" class="page-link">5</a></li>
                                                <li class="page-item"><a href="javascript:void(0);" class="page-link" aria-label="Next"><span aria-hidden="true">»</span><span class="sr-only">Next</span></a></li>
                                            </ul>
                                        </nav>
                                        </div-->
                                        <div class="d-block text-right card-footer">
                                             
                                            <button class="btn btn-warning btn-lg" data-toggle="modal" data-target=".bd-example-modal-sm" data-action="deleteSelectedPB">Xóa</button>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        
                    </div>
                </div>
                    </div>
    
 

 
<? include(dirname(__FILE__).'/footer.php') ?>