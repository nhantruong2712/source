<? include(dirname(__FILE__).'/header.php') ?>



            <div class="app-main__outer">
                <div class="app-main__inner">
                    <div class="app-page-title">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div class="page-title-icon">
                                    <i class="pe-7s-box2 icon-gradient bg-sunny-morning">
                                    </i>
                                </div>
                                <div>Danh sách quyền
                                    <div class="page-title-subheading">Quyền quản lý các module được định sẵn, không thể thay đổi.
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                                <div class="todo-indicator bg-info"></div>
                                                <div class="widget-content p-0">
                                                    <div class="widget-content-wrapper">
                                                         
                                                        <div class="widget-content-left mr-3">
                                                            <div class="widget-content-left">
                                                                <img width="42" class="rounded" src="/assets/images/company.png" alt="" />
                                                            </div>
                                                        </div>
                                                        <div class="widget-content-left">
                                                            <div class="widget-heading"><?=$admin->ten?></div>
                                                            <div class="widget-subheading"><?=$admin->ma?></div>
                                                        </div>
                                                         
                                                    </div>
                                                </div>
                                            </li>
                                            <?php }?>
                                            
                                        </ul>
                                         
                                         
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        
                    </div>
                </div>
                    </div>
    
 

 
<? include(dirname(__FILE__).'/footer.php') ?>