<? include(dirname(__FILE__).'/header.php') ?>



            <div class="app-main__outer">
                <div class="app-main__inner">
                    <div class="app-page-title">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div class="page-title-icon">
                                    <i class="pe-7s-albums icon-gradient bg-sunny-morning">
                                    </i>
                                </div>
                                <div>Danh mục sản phẩm
                                    <div class="page-title-subheading">Danh mục sản phẩm
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
                                                <a class="nav-link" href="#" data-toggle="modal" data-target="#exampleModalLong" data-action="addDM">
                                                    <i class="nav-link-icon lnr-plus-circle"></i>
                                                    <span>
                                                        Thêm Danh mục sản phẩm
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
                                        <div class="card-header">Danh mục sản phẩm</div>
                                        <div>
                                        <table id="treegrid" class="" style="width: 100%;outline: none;" data-source="/ajax/danhmuc/<?=empty($employee->vaitro)?$employee->kho:$employee->id?>">
                                            <colgroup>
                                            <col width="30px"></col>
                                            <col width="70px"></col>
                                            <col width="*"></col>
                                            <!--col width="50px"></col-->
                                            <col width="130px"></col>
                                            </colgroup>
                                            <thead>
                                              <tr> <th></th> <th>#</th> <th>Tên</th> <!--th>Qty</th--> <th>Thực thi</th> </tr>
                                            </thead>
                                            <!-- Optionally define a row that serves as template, when new nodes are created: -->
                                            <tbody>
                                              <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <!--td class="alignRight"></td-->
                                                <td class="alignCenter">
                                                  <!--input type="checkbox" name="like"-->
                                                    <button class="border-0 btn-transition btn btn-outline-success" data-toggle="modal" data-target="#exampleModalLong" data-action="addChildDM" data-id="1">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                    <button class="border-0 btn-transition btn btn-outline-success" data-toggle="modal" data-target="#exampleModalLong" data-action="editDM" data-id="1">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <button class="border-0 btn-transition btn btn-outline-danger" data-toggle="modal" data-target=".bd-example-modal-sm" data-action="deleteDM" data-id="1">
                                                        <i class="fa fa-trash-alt"></i>
                                                    </button>
                                                </td>
                                              </tr>
                                            </tbody>
                                          </table>
                                        </div>
                                         
                                        <div class="d-block text-right card-footer">
                                             
                                            <button class="btn btn-warning btn-lg" data-toggle="modal" data-target=".bd-example-modal-sm" data-action="deleteSelectedDM">Xóa</button>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        
                    </div>
                </div>
                    </div>
    
 

 
<? include(dirname(__FILE__).'/footer.php') ?>