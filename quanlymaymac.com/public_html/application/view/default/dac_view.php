 
             
                <div class="app-main__inner">
                     
                    <div class="tab-content">
                        <div class="tab-pane tabs-animation fade active show" id="tab-content-0" role="tabpanel">
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Thông tin</h5>
                                    <form class="">
                                         
                                        <div class="position-relative row form-group"><label for="ten" class="col-sm-3 col-form-label">Tên dự án</label>
                                            <div class="col-sm-9">
                                                <input name="ten" id="ten" value="<?=$model->ten?>" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="position-relative row form-group"><label for="ngay" class="col-sm-3 col-form-label">Thời gian</label>
                                            <div class="col-sm-9 mt-2">
                                                <?=date('d/m/Y H:i:s',strtotime($model->ngay))?>
                                                <input type="hidden" name="id" id="id" value="<?=$model->id?>" /> 
                                            </div>
                                        </div>
                                         
                                        <div class="position-relative row form-group"><label for="ghichu" class="col-sm-3 col-form-label">Ghi chú</label>
                                            <div class="col-sm-9"><textarea name="ghichu" id="ghichu" class="form-control"><?=$model->ghichu?></textarea></div>
                                        </div>
                                        
                                        <div class="position-relative row form-group"><label for="soluong" class="col-sm-3 col-form-label">Số lượng SP</label>
                                            <div class="col-sm-9 mt-2">
                                                <?=$model->soluong?>
                                                
                                            </div>
                                        </div>  
                                    </form>
                                </div>
                            </div>
                            <div class="main-card mb-3 card">
                                <div class="card-body"><h5 class="card-title">Nhân viên</h5>
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
                                             <?php foreach($model->nhanvien as $nhanvien){?>
                                            <tr>
                                                
                                                <th scope="row"></th>
                                                <td><?=$nhanvien->nhanvienten?></td>
                                                <td><?=$nhanvien->nhanvienemail?></td>
                                                <td><?=$nhanvien->congdoanten?></td>
                                                
                                            </tr>
                                            <?php }?> 
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div><div class="main-card mb-3 card">
                                <div class="card-body">
                                    <form class="">
                                         
                                        <div class="position-relative row form-check">
                                            <div class="col-sm-9 offset-sm-2">
                                                <button class="btn btn-info suachuyen" type="button">Sửa chuyền</button>
                                            </div>
                                        </div>
                                    </form>
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
    .table-sanpham .downup{
        float: right;display: inline-grid;margin-top: -2.2em;
    }
    .table-sanpham .downup>div{
        float: left;border: 1px solid #ccc;height: 15px;width: 19px; cursor: pointer;
    }
    .table-sanpham .downup>div>i{
        position: absolute;
    }
</style> 

 
