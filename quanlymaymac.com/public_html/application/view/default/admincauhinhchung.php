<? include(dirname(__FILE__).'/headeradmin.php') ?>
 
    <div class="panel panel-indigo">
                        <div class="panel-heading">
                            <h4>Cấu Hình Chung</h4>
                            <div class="options">   
                                
                                
                                
                            </div>
                        </div>
                        <div class="panel-body collapse in">
                            
                            <div id="editable_wrapper" class="dataTables_wrapper" role="grid"><table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables dataTable" id="editable" aria-describedby="editable_info">
                                <thead>
                                    <tr role="row"><th class="sorting_asc" role="columnheader" tabindex="0" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 160px;">STT</th><th class="sorting" role="columnheader" tabindex="0" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 237px;">Key</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="editable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 213px;">Giá trị</th></tr>

                                </thead>
                                
                            <tbody role="alert" aria-live="polite" aria-relevant="all">
<?php foreach($configs as $k=>$config){ ?>                            
                            <tr class="gradeA odd">
                                        <td class="  sorting_1"><?=$config->id?></td>
                                        <td class=" " data-edit-event="click.textEditor"><?=$config->key?></td>
                                        <td class=" " data-edit-event="click.textEditor" data-old="<?=$config->value?>"><?=$config->value?></td>

                                        
                                        
                                    </tr>
<?php } ?>
                                    </tbody></table><div class="row"><div class="col-xs-6"></div></div></div><!--end table-->
                        </div>
                    </div>

        

<script>
$(function () {
    
    $('#editable td:last-child').editable({
        closeOnEnter : true,
        event:"click",
        touch : true,
        callback: function(data) {
             
            if( data.content!==false && data.$el.data('old')!=data.content ) {
                var id = data.$el.parent().find('td:first-child').html()
                //alert(data.content)
                //alert(data.$el.data('old'))
                
                //validate
                if(id==1){
                    if(!data.content.match(/@/)){
                        toastr.error('Not valid email')
                        data.$el.html(data.$el.data('old'))
                        return
                    }
                }
                if(id==2 || id==3){
                    if(!(data.content>0)){
                        toastr.error('Not valid number')
                        data.$el.html(data.$el.data('old'))
                        return
                    }
                }
                
                $.ajax({
                    url: 'ajax/admincauhinhchung',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        id: id,
                        value: data.content  
                    },
                    success: function(j){
                        if(j.success==1){
                            data.$el.data('old',data.content)
                            toastr.success(j.message)
                            document.location.reload();
                        }else{
                            toastr.error(j.message)
                        }
                    },
                    error: function(e){
                        toastr.error(e.message)
                    }
                })
            }
        }
    });
}); 
</script>

<? include(dirname(__FILE__).'/footeradmin.php') ?>