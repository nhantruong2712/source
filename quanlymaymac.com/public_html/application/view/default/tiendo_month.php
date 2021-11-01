<?php include(dirname(__FILE__).'/header.php') ?>
<?php $start_month = strtotime($year.'-'.sprintf('%02d',$month).'-01');  //echo date('d/m/Y H:i:s',$start_month);die();
$w  = date('w',$start_month); 
$me = date::endMonth($year.'-'.$month);
$mn = date::monthnum($month,$year);
//$time = time();
$time2 = strtotime(date('Y-m-d'));// $time-($time%86400);
// echo $time2;die();
$x0 = date('Y-m-d',$start_month-($w-0)*86400);
$x1 = date('Y-m-d',$start_month-($w-41)*86400);
$c = db::query("select xxx.*,duan.ten as duanten,chuyen.ten as chuyenten 
    from (select sum(ngay.soluong) as sl,ngay.chuyen,ngay.ngay,ngay.duan 
    from ngay where ngay.ngay>='$x0' and ngay.ngay<='$x1' and ngay.chuyen>0 and ngay.type=3 and ngay.kho = $kho
    group by ngay.chuyen,ngay.ngay) xxx 
    inner join chuyen on chuyen.id = xxx.chuyen
    inner join duan on duan.id = xxx.duan");
//var_dump($c);die();
if($c){
    $c2 = array();
    foreach($c as $cc){
        if(isset($c2[$cc->ngay])){
            $c2[$cc->ngay][] = $cc;//$cc->chuyen
        }else{
            $c2[$cc->ngay] = array($cc); //$cc->chuyen=>
        }
    }
    
    $ret = array();
    for($i=0;$i<42;$i++){
        $xx = date('Y-m-d',$start_month-($w-$i)*86400);
        $ret[$i] = isset($c2[$xx])?$c2[$xx]:array();
    }
    //var_dump($ret);die();
}else $ret = array();
?>


            <div class="app-main__outer bangtiendo <?=$type?>" data-id="<?=$kho?>">
                <div class="app-main__inner">
                    <div class="app-page-title">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div class="page-title-icon">
                                    <i class="pe-7s-graph2 text-success">
                                    </i>
                                </div>
                                <div>Bảng tiến độ<div class="page-title-subheading">Bảng tiến độ</div>
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
                                                <a class="nav-link" href="/tiendo/them">
                                                    <i class="nav-link-icon lnr-plus-circle"></i>
                                                    <span>
                                                        Thêm tiến độ
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
                                      <h2><?=$month?> - <?=$year?></h2>
                                    </div>
                                    <div class="fc-clear"></div>
                                  </div>
                                  <div class="fc-view-container" style="">
                                    <div class="fc-view fc-month-view fc-basic-view" style="">
                                      <table class="table-bordered">
                                        <thead class="fc-head">
                                          <tr>
                                            <td class="fc-head-container ">
                                              <div class="fc-row table-bordered">
                                                <table class="table-bordered">
                                                  <thead>
                                                    <tr>
                                                      <th class="fc-day-header  fc-sun"><span>Sun</span></th>
                                                      <th class="fc-day-header  fc-mon"><span>Mon</span></th>
                                                      <th class="fc-day-header  fc-tue"><span>Tue</span></th>
                                                      <th class="fc-day-header  fc-wed"><span>Wed</span></th>
                                                      <th class="fc-day-header  fc-thu"><span>Thu</span></th>
                                                      <th class="fc-day-header  fc-fri"><span>Fri</span></th>
                                                      <th class="fc-day-header  fc-sat"><span>Sat</span></th>
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
                                              <div class="fc-scroller fc-day-grid-container" style="overflow: hidden; height: 693px;">
                                                <div class="fc-day-grid fc-unselectable">
                                                  <div class="fc-row fc-week table-bordered fc-rigid" style="height: 115px;">
                                                    <div class="fc-bg">
                                                      <table class="table-bordered">
                                                        <tbody>
                                                          <tr>
                                                            <td class="fc-day  fc-sun <?=date('m',$start_month-($w-0)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-0)*86400<$time2?'fc-past':($start_month-($w-0)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-0)*86400)?>"></td>
                                                            <td class="fc-day  fc-mon <?=date('m',$start_month-($w-1)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-1)*86400<$time2?'fc-past':($start_month-($w-1)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-1)*86400)?>"></td>
                                                            <td class="fc-day  fc-tue <?=date('m',$start_month-($w-2)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-2)*86400<$time2?'fc-past':($start_month-($w-2)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-2)*86400)?>"></td>
                                                            <td class="fc-day  fc-wed <?=date('m',$start_month-($w-3)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-3)*86400<$time2?'fc-past':($start_month-($w-3)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-3)*86400)?>"></td>
                                                            <td class="fc-day  fc-thu <?=date('m',$start_month-($w-4)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-4)*86400<$time2?'fc-past':($start_month-($w-4)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-4)*86400)?>"></td>
                                                            <td class="fc-day  fc-fri <?=date('m',$start_month-($w-5)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-5)*86400<$time2?'fc-past':($start_month-($w-5)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-5)*86400)?>"></td>
                                                            <td class="fc-day  fc-sat <?=date('m',$start_month-($w-6)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-6)*86400<$time2?'fc-past':($start_month-($w-6)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-6)*86400)?>"></td>
                                                          </tr>
                                                        </tbody>
                                                      </table>
                                                    </div>
                                                    <div class="fc-content-skeleton">
                                                      <table>
                                                        <thead>
                                                          <tr>
                                                            <td class="fc-day-top fc-sun <?=date('m',$start_month-($w-0)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-0)*86400<$time2?'fc-past':($start_month-($w-0)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-0)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w)*86400)?></a></td>
                                                            <td class="fc-day-top fc-mon <?=date('m',$start_month-($w-1)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-1)*86400<$time2?'fc-past':($start_month-($w-1)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-1)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-1)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-1)*86400)?></a></td>
                                                            <td class="fc-day-top fc-tue <?=date('m',$start_month-($w-2)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-2)*86400<$time2?'fc-past':($start_month-($w-2)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-2)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-2)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-2)*86400)?></a></td>
                                                            <td class="fc-day-top fc-wed <?=date('m',$start_month-($w-3)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-3)*86400<$time2?'fc-past':($start_month-($w-3)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-3)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-3)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-3)*86400)?></a></td>
                                                            <td class="fc-day-top fc-thu <?=date('m',$start_month-($w-4)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-4)*86400<$time2?'fc-past':($start_month-($w-4)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-4)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-4)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-4)*86400)?></a></td>
                                                            <td class="fc-day-top fc-fri <?=date('m',$start_month-($w-5)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-5)*86400<$time2?'fc-past':($start_month-($w-5)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-5)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-5)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-5)*86400)?></a></td>
                                                            <td class="fc-day-top fc-sat <?=date('m',$start_month-($w-6)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-6)*86400<$time2?'fc-past':($start_month-($w-6)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-6)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-6)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-6)*86400)?></a></td>
                                                          </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php $max = $ret? max(array_map(function($a){return count($a);},array_slice($ret,0,7))) :0; ?>
                                                        <?php for($j=0;$j<$max;$j++){?>
                                                          <tr>
                                                            <?php for($i=0;$i<7;$i++){?>
                                                            <?php if(empty($ret[$i][$j])){?>
                                                            <td></td>
                                                            <?php }else{?>
                                                            <td class="fc-event-container"><a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end fc-draggable fc-resizable">
                                                                <div class="fc-content"><span class="fc-time"><?=$ret[$i][$j]->chuyenten.'-'.$ret[$i][$j]->duanten.": "?></span> <span class="fc-title"><?=($ret[$i][$j]->sl-0)?></span></div>
                                                                <div class="fc-resizer fc-end-resizer"></div>
                                                              </a></td>
                                                            <?php }?>   
                                                            <?php }?>
                                                          </tr>
                                                        <?php }?>  
                                                        </tbody>
                                                      </table>
                                                    </div>
                                                  </div>
                                                  <div class="fc-row fc-week table-bordered fc-rigid" style="height: 114px;">
                                                    <div class="fc-bg">
                                                      <table class="table-bordered">
                                                        <tbody>
                                                          <tr>
                                                            <td class="fc-day  fc-sun <?=date('m',$start_month-($w-7)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-7)*86400<$time2?'fc-past':($start_month-($w-7)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-7)*86400)?>"></td>
                                                            <td class="fc-day  fc-mon <?=date('m',$start_month-($w-8)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-8)*86400<$time2?'fc-past':($start_month-($w-8)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-8)*86400)?>"></td>
                                                            <td class="fc-day  fc-tue <?=date('m',$start_month-($w-9)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-9)*86400<$time2?'fc-past':($start_month-($w-9)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-9)*86400)?>"></td>
                                                            <td class="fc-day  fc-wed <?=date('m',$start_month-($w-10)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-10)*86400<$time2?'fc-past':($start_month-($w-10)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-10)*86400)?>"></td>
                                                            <td class="fc-day  fc-thu <?=date('m',$start_month-($w-11)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-11)*86400<$time2?'fc-past':($start_month-($w-11)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-11)*86400)?>"></td>
                                                            <td class="fc-day  fc-fri <?=date('m',$start_month-($w-12)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-12)*86400<$time2?'fc-past':($start_month-($w-12)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-12)*86400)?>"></td>
                                                            <td class="fc-day  fc-sat <?=date('m',$start_month-($w-13)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-13)*86400<$time2?'fc-past':($start_month-($w-13)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-13)*86400)?>"></td>
                                                          </tr>
                                                        </tbody>
                                                      </table>
                                                    </div>
                                                    <div class="fc-content-skeleton">
                                                      <table>
                                                        <thead>
                                                          <tr>
                                                            <td class="fc-day-top fc-sun <?=date('m',$start_month-($w-7)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-7)*86400<$time2?'fc-past':($start_month-($w-7)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-7)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-7)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-7)*86400)?></a></td>
                                                            <td class="fc-day-top fc-mon <?=date('m',$start_month-($w-8)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-8)*86400<$time2?'fc-past':($start_month-($w-8)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-8)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-8)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-8)*86400)?></a></td>
                                                            <td class="fc-day-top fc-tue <?=date('m',$start_month-($w-9)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-9)*86400<$time2?'fc-past':($start_month-($w-9)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-9)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-9)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-9)*86400)?></a></td>
                                                            <td class="fc-day-top fc-wed <?=date('m',$start_month-($w-10)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-10)*86400<$time2?'fc-past':($start_month-($w-10)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-10)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-10)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-10)*86400)?></a></td>
                                                            <td class="fc-day-top fc-thu <?=date('m',$start_month-($w-11)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-11)*86400<$time2?'fc-past':($start_month-($w-11)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-11)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-11)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-11)*86400)?></a></td>
                                                            <td class="fc-day-top fc-fri <?=date('m',$start_month-($w-12)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-12)*86400<$time2?'fc-past':($start_month-($w-12)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-12)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-12)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-12)*86400)?></a></td>
                                                            <td class="fc-day-top fc-sat <?=date('m',$start_month-($w-13)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-13)*86400<$time2?'fc-past':($start_month-($w-13)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-13)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-13)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-13)*86400)?></a></td>
                                                          </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php $max = $ret? max(array_map(function($a){return count($a);},array_slice($ret,7,7))):0; ?>
                                                        <?php for($j=0;$j<$max;$j++){?>
                                                          <tr>
                                                            <?php for($i=7;$i<14;$i++){?>
                                                            <?php if(empty($ret[$i][$j])){?>
                                                            <td></td>
                                                            <?php }else{?>
                                                            <td class="fc-event-container"><a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end fc-draggable fc-resizable">
                                                                <div class="fc-content"><span class="fc-time"><?=$ret[$i][$j]->chuyenten.'-'.$ret[$i][$j]->duanten.": "?></span> <span class="fc-title"><?=($ret[$i][$j]->sl-0)?></span></div>
                                                                <div class="fc-resizer fc-end-resizer"></div>
                                                              </a></td>
                                                            <?php }?>   
                                                            <?php }?>
                                                          </tr>
                                                        <?php }?>  
                                                        </tbody>
                                                      </table>
                                                    </div>
                                                  </div>
                                                  <div class="fc-row fc-week table-bordered fc-rigid" style="height: 115px;">
                                                    <div class="fc-bg">
                                                      <table class="table-bordered">
                                                        <tbody>
                                                          <tr>
                                                            <td class="fc-day  fc-sun <?=date('m',$start_month-($w-14)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-14)*86400<$time2?'fc-past':($start_month-($w-14)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-14)*86400)?>"></td>
                                                            <td class="fc-day  fc-mon <?=date('m',$start_month-($w-15)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-15)*86400<$time2?'fc-past':($start_month-($w-15)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-15)*86400)?>"></td>
                                                            <td class="fc-day  fc-tue <?=date('m',$start_month-($w-16)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-16)*86400<$time2?'fc-past':($start_month-($w-16)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-16)*86400)?>"></td>
                                                            <td class="fc-day  fc-wed <?=date('m',$start_month-($w-17)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-17)*86400<$time2?'fc-past':($start_month-($w-17)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-17)*86400)?>"></td>
                                                            <td class="fc-day  fc-thu <?=date('m',$start_month-($w-18)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-18)*86400<$time2?'fc-past':($start_month-($w-18)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-18)*86400)?>"></td>
                                                            <td class="fc-day  fc-fri <?=date('m',$start_month-($w-19)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-19)*86400<$time2?'fc-past':($start_month-($w-19)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-19)*86400)?>"></td>
                                                            <td class="fc-day  fc-sat <?=date('m',$start_month-($w-20)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-20)*86400<$time2?'fc-past':($start_month-($w-20)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-20)*86400)?>"></td>
                                                          </tr>
                                                        </tbody>
                                                      </table>
                                                    </div>
                                                    <div class="fc-content-skeleton">
                                                      <table>
                                                        <thead>
                                                          <tr>
                                                            <td class="fc-day-top fc-sun <?=date('m',$start_month-($w-14)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-14)*86400<$time2?'fc-past':($start_month-($w-14)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-14)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-14)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-14)*86400)?></a></td>
                                                            <td class="fc-day-top fc-mon <?=date('m',$start_month-($w-15)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-15)*86400<$time2?'fc-past':($start_month-($w-15)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-15)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-15)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-15)*86400)?></a></td>
                                                            <td class="fc-day-top fc-tue <?=date('m',$start_month-($w-16)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-16)*86400<$time2?'fc-past':($start_month-($w-16)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-16)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-16)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-16)*86400)?></a></td>
                                                            <td class="fc-day-top fc-wed <?=date('m',$start_month-($w-17)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-17)*86400<$time2?'fc-past':($start_month-($w-17)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-17)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-17)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-17)*86400)?></a></td>
                                                            <td class="fc-day-top fc-thu <?=date('m',$start_month-($w-18)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-18)*86400<$time2?'fc-past':($start_month-($w-18)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-18)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-18)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-18)*86400)?></a></td>
                                                            <td class="fc-day-top fc-fri <?=date('m',$start_month-($w-19)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-19)*86400<$time2?'fc-past':($start_month-($w-19)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-19)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-19)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-19)*86400)?></a></td>
                                                            <td class="fc-day-top fc-sat <?=date('m',$start_month-($w-20)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-20)*86400<$time2?'fc-past':($start_month-($w-20)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-20)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-20)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-20)*86400)?></a></td>
                                                          </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php $max =$ret? max(array_map(function($a){return count($a);},array_slice($ret,14,7))):0; ?>
                                                        <?php for($j=0;$j<$max;$j++){?>
                                                          <tr>
                                                            <?php for($i=14;$i<21;$i++){?>
                                                            <?php if(empty($ret[$i][$j])){?>
                                                            <td></td>
                                                            <?php }else{?>
                                                            <td class="fc-event-container"><a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end fc-draggable fc-resizable">
                                                                <div class="fc-content"><span class="fc-time"><?=$ret[$i][$j]->chuyenten.'-'.$ret[$i][$j]->duanten.": "?></span> <span class="fc-title"><?=($ret[$i][$j]->sl-0)?></span></div>
                                                                <div class="fc-resizer fc-end-resizer"></div>
                                                              </a></td>
                                                            <?php }?>   
                                                            <?php }?>
                                                          </tr>
                                                        <?php }?>  
                                                        </tbody>
                                                      </table>
                                                    </div>
                                                  </div>
                                                  <div class="fc-row fc-week table-bordered fc-rigid" style="height: 115px;">
                                                    <div class="fc-bg">
                                                      <table class="table-bordered">
                                                        <tbody>
                                                          <tr>
                                                            <td class="fc-day  fc-sun <?=date('m',$start_month-($w-21)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-21)*86400<$time2?'fc-past':($start_month-($w-21)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-21)*86400)?>"></td>
                                                            <td class="fc-day  fc-mon <?=date('m',$start_month-($w-22)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-22)*86400<$time2?'fc-past':($start_month-($w-22)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-22)*86400)?>"></td>
                                                            <td class="fc-day  fc-tue <?=date('m',$start_month-($w-23)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-23)*86400<$time2?'fc-past':($start_month-($w-23)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-23)*86400)?>"></td>
                                                            <td class="fc-day  fc-wed <?=date('m',$start_month-($w-24)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-24)*86400<$time2?'fc-past':($start_month-($w-24)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-24)*86400)?>"></td>
                                                            <td class="fc-day  fc-thu <?=date('m',$start_month-($w-25)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-25)*86400<$time2?'fc-past':($start_month-($w-25)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-25)*86400)?>"></td>
                                                            <td class="fc-day  fc-fri <?=date('m',$start_month-($w-26)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-26)*86400<$time2?'fc-past':($start_month-($w-26)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-26)*86400)?>"></td>
                                                            <td class="fc-day  fc-sat <?=date('m',$start_month-($w-27)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-27)*86400<$time2?'fc-past':($start_month-($w-27)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-27)*86400)?>"></td>
                                                          </tr>
                                                        </tbody>
                                                      </table>
                                                    </div>
                                                    <div class="fc-content-skeleton">
                                                      <table>
                                                        <thead>
                                                          <tr>
                                                            <td class="fc-day-top fc-sun <?=date('m',$start_month-($w-21)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-21)*86400<$time2?'fc-past':($start_month-($w-21)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-21)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-21)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-21)*86400)?></a></td>
                                                            <td class="fc-day-top fc-mon <?=date('m',$start_month-($w-22)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-22)*86400<$time2?'fc-past':($start_month-($w-22)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-22)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-22)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-22)*86400)?></a></td>
                                                            <td class="fc-day-top fc-tue <?=date('m',$start_month-($w-23)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-23)*86400<$time2?'fc-past':($start_month-($w-23)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-23)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-23)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-23)*86400)?></a></td>
                                                            <td class="fc-day-top fc-wed <?=date('m',$start_month-($w-24)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-24)*86400<$time2?'fc-past':($start_month-($w-24)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-24)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-24)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-24)*86400)?></a></td>
                                                            <td class="fc-day-top fc-thu <?=date('m',$start_month-($w-25)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-25)*86400<$time2?'fc-past':($start_month-($w-25)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-25)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-25)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-25)*86400)?></a></td>
                                                            <td class="fc-day-top fc-fri <?=date('m',$start_month-($w-26)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-26)*86400<$time2?'fc-past':($start_month-($w-26)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-26)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-26)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-26)*86400)?></a></td>
                                                            <td class="fc-day-top fc-sat <?=date('m',$start_month-($w-27)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-27)*86400<$time2?'fc-past':($start_month-($w-27)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-27)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-27)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-27)*86400)?></a></td>
                                                          </tr>
                                                        </thead>
                                                        <tbody>
                                                          <?php $max =$ret? max(array_map(function($a){return count($a);},array_slice($ret,21,7))):0; ?>
                                                        <?php for($j=0;$j<$max;$j++){?>
                                                          <tr>
                                                            <?php for($i=21;$i<28;$i++){?>
                                                            <?php if(empty($ret[$i][$j])){?>
                                                            <td></td>
                                                            <?php }else{?>
                                                            <td class="fc-event-container"><a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end fc-draggable fc-resizable">
                                                                <div class="fc-content"><span class="fc-time"><?=$ret[$i][$j]->chuyenten.'-'.$ret[$i][$j]->duanten.": "?></span> <span class="fc-title"><?=($ret[$i][$j]->sl-0)?></span></div>
                                                                <div class="fc-resizer fc-end-resizer"></div>
                                                              </a></td>
                                                            <?php }?>   
                                                            <?php }?>
                                                          </tr>
                                                        <?php }?>  
                                                        </tbody>
                                                      </table>
                                                    </div>
                                                  </div>
                                                  <div class="fc-row fc-week table-bordered fc-rigid" style="height: 114px;">
                                                    <div class="fc-bg">
                                                      <table class="table-bordered">
                                                        <tbody>
                                                          <tr>
                                                            <td class="fc-day  fc-sun <?=date('m',$start_month-($w-28)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-28)*86400<$time2?'fc-past':($start_month-($w-28)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-28)*86400)?>"></td>
                                                            <td class="fc-day  fc-mon <?=date('m',$start_month-($w-29)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-29)*86400<$time2?'fc-past':($start_month-($w-29)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-29)*86400)?>"></td>
                                                            <td class="fc-day  fc-tue <?=date('m',$start_month-($w-30)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-30)*86400<$time2?'fc-past':($start_month-($w-30)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-30)*86400)?>"></td>
                                                            <td class="fc-day  fc-wed <?=date('m',$start_month-($w-31)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-31)*86400<$time2?'fc-past':($start_month-($w-31)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-31)*86400)?>"></td>
                                                            <td class="fc-day  fc-thu <?=date('m',$start_month-($w-32)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-32)*86400<$time2?'fc-past':($start_month-($w-32)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-32)*86400)?>"></td>
                                                            <td class="fc-day  fc-fri <?=date('m',$start_month-($w-33)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-33)*86400<$time2?'fc-past':($start_month-($w-33)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-33)*86400)?>"></td>
                                                            <td class="fc-day  fc-sat <?=date('m',$start_month-($w-34)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-34)*86400<$time2?'fc-past':($start_month-($w-34)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-34)*86400)?>"></td>
                                                          </tr>
                                                        </tbody>
                                                      </table>
                                                    </div>
                                                    <div class="fc-content-skeleton">
                                                      <table>
                                                        <thead>
                                                          <tr>
                                                            <td class="fc-day-top fc-sun <?=date('m',$start_month-($w-28)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-28)*86400<$time2?'fc-past':($start_month-($w-28)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-28)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-28)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-28)*86400)?></a></td>
                                                            <td class="fc-day-top fc-mon <?=date('m',$start_month-($w-29)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-29)*86400<$time2?'fc-past':($start_month-($w-29)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-29)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-29)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-29)*86400)?></a></td>
                                                            <td class="fc-day-top fc-tue <?=date('m',$start_month-($w-30)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-30)*86400<$time2?'fc-past':($start_month-($w-30)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-30)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-30)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-30)*86400)?></a></td>
                                                            <td class="fc-day-top fc-wed <?=date('m',$start_month-($w-31)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-31)*86400<$time2?'fc-past':($start_month-($w-31)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-31)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-31)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-31)*86400)?></a></td>
                                                            <td class="fc-day-top fc-thu <?=date('m',$start_month-($w-32)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-32)*86400<$time2?'fc-past':($start_month-($w-32)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-32)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-32)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-32)*86400)?></a></td>
                                                            <td class="fc-day-top fc-fri <?=date('m',$start_month-($w-33)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-33)*86400<$time2?'fc-past':($start_month-($w-33)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-33)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-33)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-33)*86400)?></a></td>
                                                            <td class="fc-day-top fc-sat <?=date('m',$start_month-($w-34)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-34)*86400<$time2?'fc-past':($start_month-($w-34)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-34)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-34)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-34)*86400)?></a></td>
                                                          </tr>
                                                        </thead>
                                                        <tbody>
                                                          <?php $max =$ret? max(array_map(function($a){return count($a);},array_slice($ret,28,7))):0; ?>
                                                        <?php for($j=0;$j<$max;$j++){?>
                                                          <tr>
                                                            <?php for($i=28;$i<35;$i++){?>
                                                            <?php if(empty($ret[$i][$j])){?>
                                                            <td></td>
                                                            <?php }else{?>
                                                            <td class="fc-event-container"><a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end fc-draggable fc-resizable">
                                                                <div class="fc-content"><span class="fc-time"><?=$ret[$i][$j]->chuyenten.'-'.$ret[$i][$j]->duanten.": "?></span> <span class="fc-title"><?=($ret[$i][$j]->sl-0)?></span></div>
                                                                <div class="fc-resizer fc-end-resizer"></div>
                                                              </a></td>
                                                            <?php }?>   
                                                            <?php }?>
                                                          </tr>
                                                        <?php }?> 
                                                        </tbody>
                                                      </table>
                                                    </div>
                                                  </div>
                                                  <div class="fc-row fc-week table-bordered fc-rigid" style="height: 118px;">
                                                    <div class="fc-bg">
                                                      <table class="table-bordered">
                                                        <tbody>
                                                          <tr>
                                                            <td class="fc-day  fc-sun <?=date('m',$start_month-($w-35)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-35)*86400<$time2?'fc-past':($start_month-($w-35)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-35)*86400)?>"></td>
                                                            <td class="fc-day  fc-mon <?=date('m',$start_month-($w-36)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-36)*86400<$time2?'fc-past':($start_month-($w-36)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-36)*86400)?>"></td>
                                                            <td class="fc-day  fc-tue <?=date('m',$start_month-($w-37)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-37)*86400<$time2?'fc-past':($start_month-($w-37)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-36)*86400)?>"></td>
                                                            <td class="fc-day  fc-wed <?=date('m',$start_month-($w-38)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-38)*86400<$time2?'fc-past':($start_month-($w-38)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-38)*86400)?>"></td>
                                                            <td class="fc-day  fc-thu <?=date('m',$start_month-($w-39)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-39)*86400<$time2?'fc-past':($start_month-($w-39)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-39)*86400)?>"></td>
                                                            <td class="fc-day  fc-fri <?=date('m',$start_month-($w-40)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-40)*86400<$time2?'fc-past':($start_month-($w-40)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-40)*86400)?>"></td>
                                                            <td class="fc-day  fc-sat <?=date('m',$start_month-($w-41)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-41)*86400<$time2?'fc-past':($start_month-($w-41)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-41)*86400)?>"></td>
                                                          </tr>
                                                        </tbody>
                                                      </table>
                                                    </div>
                                                    <div class="fc-content-skeleton">
                                                      <table>
                                                        <thead>
                                                          <tr>
                                                            <td class="fc-day-top fc-sun <?=date('m',$start_month-($w-35)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-35)*86400<$time2?'fc-past':($start_month-($w-35)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-35)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-35)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-35)*86400)?></a></td>
                                                            <td class="fc-day-top fc-mon <?=date('m',$start_month-($w-36)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-36)*86400<$time2?'fc-past':($start_month-($w-36)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-36)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-36)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-36)*86400)?></a></td>
                                                            <td class="fc-day-top fc-tue <?=date('m',$start_month-($w-37)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-37)*86400<$time2?'fc-past':($start_month-($w-37)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-37)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-37)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-37)*86400)?></a></td>
                                                            <td class="fc-day-top fc-wed <?=date('m',$start_month-($w-38)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-38)*86400<$time2?'fc-past':($start_month-($w-38)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-38)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-38)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-38)*86400)?></a></td>
                                                            <td class="fc-day-top fc-thu <?=date('m',$start_month-($w-39)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-39)*86400<$time2?'fc-past':($start_month-($w-39)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-39)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-39)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-39)*86400)?></a></td>
                                                            <td class="fc-day-top fc-fri <?=date('m',$start_month-($w-40)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-40)*86400<$time2?'fc-past':($start_month-($w-40)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-40)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-40)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-40)*86400)?></a></td>
                                                            <td class="fc-day-top fc-sat <?=date('m',$start_month-($w-41)*86400)==$month?'':'fc-other-month'?> <?=$start_month-($w-41)*86400<$time2?'fc-past':($start_month-($w-41)*86400>$time2?'fc-future':'fc-today')?>" data-date="<?=date('Y-m-d',$start_month-($w-41)*86400)?>"><a class="fc-day-number" data-goto="{&quot;date&quot;:&quot;<?=date('Y-m-d',$start_month-($w-41)*86400)?>&quot;,&quot;type&quot;:&quot;day&quot;}"><?=date('d',$start_month-($w-41)*86400)?></a></td>
                                                          </tr>
                                                        </thead>
                                                        <tbody>
                                                          <?php $max =$ret? max(array_map(function($a){return count($a);},array_slice($ret,35,7))):0; ?>
                                                        <?php for($j=0;$j<$max;$j++){?>
                                                          <tr>
                                                            <?php for($i=35;$i<42;$i++){?>
                                                            <?php if(empty($ret[$i][$j])){?>
                                                            <td></td>
                                                            <?php }else{?>
                                                            <td class="fc-event-container"><a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end fc-draggable fc-resizable">
                                                                <div class="fc-content"><span class="fc-time"><?=$ret[$i][$j]->chuyenten.'-'.$ret[$i][$j]->duanten.": "?></span> <span class="fc-title"><?=($ret[$i][$j]->sl-0)?></span></div>
                                                                <div class="fc-resizer fc-end-resizer"></div>
                                                              </a></td>
                                                            <?php }?>   
                                                            <?php }?>
                                                          </tr>
                                                        <?php }?> 
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
/*.fc-sun{
    background-color:var(--danger)
}*/
</style>       
 
<? include(dirname(__FILE__).'/footer.php') ?>