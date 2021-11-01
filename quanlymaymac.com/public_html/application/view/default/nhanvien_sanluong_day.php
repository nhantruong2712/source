<?php include(dirname(__FILE__).'/header.php') ?>
<?php
$x0 = $year.'-'.sprintf('%02d',$month).'-'.sprintf('%02d',$day);
$time = strtotime($x0);

$c = db::query("select xxx.*,duan.ten as duanten,congdoan.ten as congdoanten 
    from (select ngay.chuyen,ngay.ngay,ngay.duan ,ngay.doituong as nhanvien, ngay.congdoan,ngay.soluong
    from ngay where ngay.ngay='$x0' and ngay.type=3) xxx 
    inner join congdoan on congdoan.id = xxx.congdoan
    inner join duan on duan.id = xxx.duan
    where xxx.nhanvien =".$nhanvien->id);
    
if($c){
    $c2 = array();
    foreach($c as $cc){         
        $c2[] = $cc;         
    }     
    //var_dump($ret);die();
}else $c2 = array();   

$cd = date('Y-m-d'); //var_dump($cd,$x0);die();
$class =  $cd==$x0?'today':($cd>$x0?'past':'future');
?>
 
            <div class="app-main__outer bangtiendo sanluong <?=$type?>" data-id="<?=$nhanvien->id?>">
                <div class="app-main__inner">
                    <div class="app-page-title">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div class="page-title-icon">
                                    <i class="pe-7s-graph2 text-success">
                                    </i>
                                </div>
                                <div>Sản lượng nhân viên<div class="page-title-subheading"><?=$nhanvien->ten?></div>
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
                            </div>
                        </div>
                    </div>            
                     
                    <div class="tab-content">
                          
                        <div class="tab-pane tabs-animation fade active show" id="tab-content-0" role="tabpanel">
                            
                            <div class="main-card mb-3 card">
                              <div class="card-body">
                                 
                                <div id="calendar2" class="fc fc-bootstrap4 fc-ltr" data-view="<?=$type?>">
                                  <div class="fc-toolbar fc-header-toolbar">
                                    <div class="fc-left">
                                      <div class="btn-group"><button type="button" class="fc-prev-button btn btn-primary" aria-label="prev"><span class="fa fa-chevron-left"></span></button><button type="button" class="fc-next-button btn btn-primary" aria-label="next"><span class="fa fa-chevron-right"></span></button></div><button type="button" class="fc-today-button btn btn-primary">today</button>
                                    </div>
                                    <div class="fc-right">
                                      <div class="btn-group">
                                        <button type="button" class="fc-month-button btn btn-primary<?=$type=='month'?' active':''?>">month</button>
                                        <button type="button" class="fc-basicDay-button btn btn-primary<?=$type=='day'?' active':''?>">day</button>
                                      </div>
                                    </div>
                                    <div class="fc-center">
                                      <h2><?=$day?> - <?=$month?> - <?=$year?></h2>
                                    </div>
                                    <div class="fc-clear"></div>
                                  </div>
                                  <div class="fc-view-container" style="">
                                      <div class="fc-view fc-basicDay-view fc-basic-view" style="">
                                        <table class="table-bordered">
                                          <thead class="fc-head">
                                            <tr>
                                              <td class="fc-head-container ">
                                                <div class="fc-row table-bordered">
                                                  <table class="table-bordered">
                                                    <thead>
                                                      <tr>
                                                        <th class="fc-day-header  fc-<?=strtolower(date('D',$time))?> fc-<?=$class?>" data-date="<?=$year?>-<?=$month?>-<?=$day?>"><span><?=date('l',$time)?></span></th>
                                                      </tr>
                                                    </thead>
                                                  </table>
                                                </div>
                                              </td>
                                            </tr>
                                          </thead>
                                          <tbody class="fc-body">
                                            <tr>
                                              <td class="">
                                                <div class="fc-scroller fc-day-grid-container" style="overflow: hidden; height: 693.313px;">
                                                  <div class="fc-day-grid fc-unselectable">
                                                    <div class="fc-row fc-week table-bordered fc-rigid" style="height: 693px;">
                                                      <div class="fc-bg">
                                                        <table class="table-bordered">
                                                          <tbody>
                                                            <tr>
                                                              <td class="fc-day fc-<?=strtolower(date('D',$time))?> fc-<?=$class?>" data-date="<?=$year?>-<?=$month?>-<?=$day?>"></td>
                                                            </tr>
                                                          </tbody>
                                                        </table>
                                                      </div>
                                                      <div class="fc-content-skeleton">
                                                        <table>
                                                          <tbody>
                                                            <?php foreach($c2 as $ret){?> 
                                                            <tr>
                                                              <td class="fc-event-container"><a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end fc-draggable">
                                                                  <div class="fc-content"><span class="fc-time"><?=$ret->congdoanten.'-'.$ret->duanten.": "?></span> <span class="fc-title"><?=($ret->soluong-0)?></span></div>
                                                                </a></td>
                                                            </tr>
                                                            <?php }?> 
                                                            </tr>
                                                          </tbody>
                                                        </table>
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>
                                              </td>
                                            </tr>
                                          </tbody>
                                        </table>
                                      </div>
                                    </div>

                                  
                                  
                                </div>
                              </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
<style>
.fc-day-grid-event .fc-content {
    white-space: break-spaces;    
}
</style>       
 
<? include(dirname(__FILE__).'/footer.php') ?>