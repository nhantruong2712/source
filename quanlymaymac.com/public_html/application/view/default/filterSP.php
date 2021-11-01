                              
                    <div class="tab-content">
                        <div class="tab-pane tabs-animation fade active show" id="tab-content-0" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-12">
                                    
                                    <div class="main-card mb-3 card">
                                        <div class="card-header">Chọn các danh mục sản phẩm cần hiển thị</div>
                                        <ul class="todo-list-wrapper list-group list-group-flush">
                                            
                                            
                                            <?php foreach($danhmuc as $admin){$admin=(object)$admin;?>
                                            <li class="list-group-item">
                                                <div class="todo-indicator bg-info"></div>
                                                <div class="widget-content p-0">
                                                    <div class="widget-content-wrapper">
                                                        <div class="widget-content-left mr-2">
                                                            <div class="custom-checkbox custom-control"><input type="checkbox" id="exampleCheckbox<?=$admin->id?>" class="custom-control-input" value="<?=$admin->id?>"<?=in_array($admin->id,$categories)?' checked':''?> /><label class="custom-control-label" for="exampleCheckbox<?=$admin->id?>">&nbsp;</label>
                                                            </div>
                                                        </div>
                                                        <div class="widget-content-left mr-3">
                                                            <div class="widget-content-left">
                                                                <img width="42" class="rounded" src="/assets/images/company.png" alt="" />
                                                            </div>
                                                        </div>
                                                        <div class="widget-content-left">
                                                            <div class="widget-heading"><?=$admin->title?></div>
                                                            <div class="widget-subheading"></div>
                                                        </div>
                                                         
                                                    </div>
                                                </div>
                                            </li>
                                            <?php }?>
                                            
                                        </ul> 
                                        <div class="d-block text-right card-footer">
                                            <button class="btn btn-info btn-lg selectAll">Chọn tất cả</button> 
                                            <button class="btn btn-danger btn-lg deselectAll">Bỏ chọn tất cả</button>
                                             
                                        </div>  
                                    </div>
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    
                                    <div class="mb-3">
                                        <div class="card-header">Lọc theo tên hoặc mã sản phẩm</div>
                                        <div class="mb-3">
                                            <div class="position-relative form-group"><label for="filterName" class="">Tên hoặc mã sản phẩm</label><input name="ten" id="filterName" value="<?=$product?>" type="text" class="form-control"></div>
                                        </div>
                                        <div class="d-block text-right card-footer">
                                             
                                            <button class="btn btn-warning btn-lg" id="dofiltersp">Lọc</button> 
                                        </div>  
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        
                    </div>