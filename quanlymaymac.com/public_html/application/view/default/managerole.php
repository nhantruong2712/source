<div class="app-page-title">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div class="page-title-icon">
                                    <i class="pe-7s-box2 icon-gradient bg-sunny-morning">
                                    </i>
                                </div>
                                <div>Danh sách quyền
                                    <div class="page-title-subheading">Của vai trò: <?=$model->ten?>
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
                                        <div class="card-header">Danh sách quyền</div>
                                        <ul class="todo-list-wrapper list-group list-group-flush">
                                            
                                            
                                            <?php foreach($admins as $admin){?>
                                            <li class="list-group-item">
                                                <div class="todo-indicator bg-<?=$admin->checked?'info':'warning'?>"></div>
                                                <div class="widget-content p-0">
                                                    <div class="widget-content-wrapper">
                                                        <div class="widget-content-left mr-2">
                                                            <div class="custom-checkbox custom-control"><input type="checkbox" id="exampleCheckbox<?=$admin->id?>" class="custom-control-input" value="<?=$admin->id?>"<?=$admin->checked?' checked':''?> /><label class="custom-control-label" for="exampleCheckbox<?=$admin->id?>">&nbsp;</label>
                                                            </div>
                                                        </div>
                                                        <div class="widget-content-left mr-3">
                                                            <div class="widget-content-left">
                                                                <img width="42" class="rounded" src="/assets/images/company.png" alt="" />
                                                            </div>
                                                        </div>
                                                        <div class="widget-content-left">
                                                            <div class="widget-heading"<?=$admin->checked?'':' style="text-decoration: line-through;"'?>><?=$admin->ten?></div>
                                                            <div class="widget-subheading"></div>
                                                        </div>
                                                         
                                                    </div>
                                                </div>
                                            </li>
                                            <?php }?>
                                            
                                        </ul>
                                        <div class="d-block text-right card-footer">
                                            <button class="btn btn-info btn-lg selectAll">Chọn tất cả</button> 
                                            <button class="btn btn-warning btn-lg deselectAll">Bỏ chọn tất cả</button>
                                            <button class="btn btn-danger btn-lg" data-toggle="modal" data-target=".bd-example-modal-sm" data-action="saveRole" data-id="<?=$model->id?>">Lưu quyền</button>
                                             
                                        </div>  
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        
                    </div>