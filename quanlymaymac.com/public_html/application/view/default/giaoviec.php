<div class="main-card mb-3 card">
    <div class="card-header">Danh sách nhân viên</div>
    <ul class="todo-list-wrapper list-group list-group-flush">
        <?php foreach($congdoan as $cd){?> 
        <li class="list-group-item">
            <div class="todo-indicator bg-<?=empty($cd->nhanviec)?'danger':'info'?>"></div>
            <div class="widget-content p-0">
                <div class="widget-content-wrapper">
                    <div class="widget-content-left mr-2">
                        <div class="custom-checkbox custom-control"><input type="radio" name="exampleCustomCheckbox" id="exampleCustom<?=$cd->id?>" class="custom-control-input" value="<?=$cd->id?>"<?=empty($cd->nhanviec)?'':' checked'?>><label class="custom-control-label" for="exampleCustom<?=$cd->id?>">&nbsp;</label>
                        </div>
                    </div>
                     
                    <div class="widget-content-left">
                        <div class="widget-heading"><?=$cd->ten?></div>
                         
                    </div>
                    <div class="widget-content-right">                           
                        <button class="border-0 btn-transition btn btn-outline-<?=empty($cd->nhanviec)?'danger':'success'?>" data-toggle="modal" data-target=".bd-example-modal-sm" data-action="chongiaoviec" data-id="<?=$cd->id?>" data-nhanvien="<?=$nhanvien->id?>">
                            <i class="fa fa-check"></i>
                        </button>
                    </div>
                </div>
            </div>
        </li>
        <?php }?>                                            
    </ul>
     
</div>