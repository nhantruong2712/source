
    function showPendingSync(){
        var zz = $('[kendo-window="syncWindow"]').parent()
        zz.find('.k-window-titlebar .k-window-title').html('Đồng bộ phiếu').css({
            'padding-top': '3px'
        })
        if(zz.is(':visible')){
            zz.hide();
            $('.k-overlay').hide()
        }else{
            $('.k-overlay').css({
                display:'block',
                'z-index': 10000
            })
            zz.show();
            zz.width(Math.min(680,$(window).width()));
            zz.css({
                left: ($(window).width()-zz.width())/2
            });
            zz.find('.k-window-titlebar .k-window-action').unbind('click')
            zz.find('.k-window-titlebar .k-window-action').click(function(){
                $('.k-overlay').hide()
                zz.hide()
            })
             
            zz.find('[ng-model="filter.Type"]').unbind('change')
            zz.find('[ng-model="filter.Type"]').change(function(){
                filter3.Type = $(this).val()
                loadOfflines()
            })
            
            $('[ng-click="syncNow()"]').unbind('click')
            $('[ng-click="syncNow()"]').click(function(){
                syncNow()
            })
            
            $('[ng-click="syncProducts()"]').unbind('click')
            $('[ng-click="syncProducts()"]').click(function(){
                syncProducts()
            })
            
            $('[ng-click="syncProductCategories()"]').unbind('click')
            $('[ng-click="syncProductCategories()"]').click(function(){
                syncProductCategories()
            })
            
            if(site_type==1){
                
            $('[ng-click="syncTables()"]').unbind('click')
            $('[ng-click="syncTables()"]').click(function(){
                syncTables()
            })
            
            $('[ng-click="syncTableCategories()"]').unbind('click')
            $('[ng-click="syncTableCategories()"]').click(function(){
                syncTableCategories()
            })
            
            }
            
            loadOfflines()
        }
    }
    
    ////if(sitetype==1)
    function syncTables(){
        toastr.info('Bắt đầu đồng bộ phòng/bàn')
        loadJsonTables(function(){
            toastr.info('Hoàn thành đồng bộ phòng/bàn')
            $('[ng-click="showTables()"]').click()
        })
    }
    
    function syncTableCategories(){
        toastr.info('Bắt đầu đồng bộ danh mục phòng/bàn')
        loadJsonTableGroups(function(){
            toastr.info('Hoàn thành đồng bộ danh mục phòng/bàn')
            $('[ng-click="showTables()"]').click()
        })
    }
    ////
    
     
    function syncProducts(){
        toastr.info('Bắt đầu đồng bộ sản phẩm')
        loadJsonProducts(function(){
            toastr.info('Hoàn thành đồng bộ sản phẩm')
        })
    }
    
    function syncProductCategories(){
        toastr.info('Bắt đầu đồng bộ danh mục sản phẩm')
        loadJsonProductCategories(function(){
            toastr.info('Hoàn thành đồng bộ danh mục sản phẩm')
        })
    }
    
    function syncNow(){
        var big = 0;
        $('#grdPendingSync tr[data-uid]').each(function(){
            var trr = $(this), trrid = (trr.attr('data-uid')); //parseInt
            setTimeout(function(){
                chooseInvoiceToOffline(trrid)
            },1000*(++big))
        })
    }
    
    function syncCount(){
        if(offlines.length){
            $('.syncCount').removeClass('ng-hide')
            $('.syncCount').html(offlines.length)
        }else{
            $('.syncCount').addClass('ng-hide')
        }
    }
    
    function bookCount(){
        if(books.length){
            $('.bookCount').removeClass('ng-hide')
            $('.bookCount').html(books.length)
        }else{
            $('.bookCount').addClass('ng-hide')
        }
    }
    
    function savecb(lo){
            
        console.log('savecb');
        
        if(lo!=undefined && lo.call) lo.call()
        
        cache_branches.setItem("cb",cb,{expirationAbsolute: null,
         expirationSliding: 365*24*3600,
         priority: Cache.Priority.HIGH,
         callback: function(k, v) { console.log('Cache removed: ' + k); }
        });
    }
    
    var nprint = !!localStorage.nprint 
    function quickPrint(){
        if(nprint){
            $('.salePrint,#salePrintHelp .toogle').removeClass('active')
        }else{
            $('.salePrint,#salePrintHelp .toogle').addClass('active')
        }
    }
     
        (function () {
            
            setTimeout(function(){
                loadHash()
            },1000)
             
            var spinner = null;
            var isiPad = navigator.userAgent.match(/iPad/i) != null;
            
            /*window.KvSpinner = {
                start: function () {
                    spinner = spinner || $('.kv-loading');
                    if (spinner == null || spinner.length == 0) {
                        spinner = $('<div class="kv-loading kv-loading-active"><div class="kv-pie" data-percent="91"><div class="kv-progress"><div class="kv-progress-fill"></div></div><div class="kv-percents"><div class="kv-percents-wrapper"><span>%</span></div></div><div class="kv-percents-txt">đang tải...</div></div></div>');
                        $(document.body).append(spinner);
                    }
                    else {
                        spinner.addClass('kv-loading-active');
                    }
                    spinner.find('.kv-pie').removeClass('kv-50');
                    var $ppc = $('.kv-pie');
                    percent = parseInt($ppc.data('percent'));
                    $({ countNum: 0 }).animate({ countNum: percent }, {
                        duration: 18000,
                        easing: 'swing',
                        step: function () {
                            var pct = Math.floor(this.countNum);
                            deg = 360 * pct / 100;
                            if (pct > 50) $ppc.addClass('kv-50');
                            $('.kv-progress-fill').css('transform', 'rotate(' + deg + 'deg)');
                            $('.kv-percents span').html(pct + '%');
                        }
                    });
                },
                stop: function () {
                    if (spinner) {
                        spinner.removeClass('kv-loading-active').find('.kv-pie').removeClass('kv-50');
                    }

                    if(!isiPad)
                        $('#productSearchInput').focus();
                }
            }*/

            function getMobileOperatingSystem() {
                var userAgent = navigator.userAgent || navigator.vendor || window.opera;

                // Windows Phone must come first because its UA also contains "Android"
                if (/windows phone/i.test(userAgent)) {
                    $(".box-introduce-app").hide();
                    $('body').removeClass('hasIosAndroid');
                }

                if (/android/i.test(userAgent)) {
                    $(".box-introduce-android").show();
                    $('body').addClass('hasIosAndroid');
                    //alert("android");
                }

                // iOS detection
                if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
                    $(".box-introduce-ios").show();
                    $('body').addClass('hasIosAndroid');
                    //alert("iOS");
                }
            }
            console.log('vvvv')
            $(document).ready(zq = function () {
                console.log('vvvv2222222',JSON.stringify(vkl))
                
                if($('.loading').length==0){
                    $('body').prepend('<div class="loading"></div>')
                    
                    _loading = setTimeout(function(){   
                        if(typeof _okl!='undefined')
                            clearTimeout(_okl)
                        if($('.loading').length>0)
                            loadTabs11()
                        delete _loading
                        if($('.loading').length>0)
                            $('.loading').hide()
                    },10000)
                }
                
                if(!vkl){
                    setTimeout(zq,500)
                    return;
                }
                
                
                //alert(vkl)
                if(!vkl.admin && vkl['3.5.2']==undefined){ //alert(1)
                    $('[ng-show="rights.createReturn"]').addClass('hth-hide')
                }else{ //alert(2)
                    $('[ng-show="rights.createReturn"]').click(function(e){
                        makeRefund()
                    })
                }
                
                if(!$.fullscreen.isNativelySupported())                 
                    $('[ng-if="screenfull.enabled"]').remove()
                else{ //alert(1)
                    var ab = function(){ console.log('ab')
                        if($.fullscreen.isFullScreen()){
                            $('[ng-show="screenfull.isFullscreen"]').removeClass('ng-hide')
                            $('[ng-if="screenfull.enabled"]').attr('title','Thoát chế độ toàn màn hình')
                            $('[ng-if="screenfull.enabled"] i').attr('class','icon-18 icon-fullscreen-invert')
                        }else{
                            $('[ng-show="screenfull.isFullscreen"]').addClass('ng-hide')
                            $('[ng-if="screenfull.enabled"]').attr('title','Bật chế độ toàn màn hình')
                            $('[ng-if="screenfull.enabled"] i').attr('class','glyphicon glyphicon-fullscreen')                             
                        }                	 
                    }
                    
                    ab()
                    
                    //screenfull.on('change', ab);
                    $(document).on('fscreenchange',ab)
                    
                    var cd = function(){ //console.log(typeof window.screenfull.toogle); alert(window.screenfull.toogle)
                        //window.screenfull.toogle()
                        if($.fullscreen.isFullScreen()){
                            $.fullscreen.exit()
                        }else{
                            $('body').fullscreen();
                        }
                    }
                    
                    $('.screenfull').click(cd)
                }
                $('.saleSync').click(function(){
                    showPendingSync()
                })
                
                syncCount()
                
                if(SellAllowOrder){
                $('[ng-click="bookProcess()"]').click(function(){
                    bookProcess()
                })
                }
                
                $('.k-widget.k-window[data-role="draggable"]').draggable({
                    handle: '.k-window-titlebar.k-header'
                })
                
                
                
                /*$('.k-widget.k-window[data-role="draggable"]').each(function(){
                    var t = $(this)
                    t.draggable({
                        handle: t.find('.k-window-titlebar.k-header')
                    })
                })*/
                 
                $('.listShortcutKey').click(function(){
                    if(!$('[uib-popover-template-popup]').is('.show')){
                        $('[uib-popover-template-popup]').addClass('show')                        
                    }else{
                        $('[uib-popover-template-popup]').removeClass('show')
                    }
                })
                
                $('[kv-popup-anchor="salePrintHelp"]').click(function(e){
                    
                    if($('#salePrintHelp .kv2Pop').is(':visible')){
                        $('#salePrintHelp').removeClass('popping')
                        return
                    }
                    
                    //console.log(e,$(this).offset())
                    var that = $(this)
                    $('#salePrintHelp').addClass('popping')
                    
                    $('#salePrintHelp .kv2Pop').css({
                        left: that.offset().left-getScrollY(1),
                        top: that.offset().top+that.height()-getScrollY()+5
                    })
                   
                })
                
                $('#salePrintHelp .toogle').click(function(){
                    $('.salePrint,#salePrintHelp .toogle').toggleClass('active')
                    if(nprint){                        
                        delete localStorage.nprint
                    }else{
                        localStorage.nprint = 1;
                    }
                    nprint = !nprint
                })
                
                quickPrint()
                
                //06/20/2019
                $('.k-picker-wrap span[role="button"]').click(function(){
                    $(this).prev().focus()
                })  
                
                
                
                //06/29/2019
                //branch-dropdown   
                $('.switch-branch').click(function(){
                    if($('.k-animation-container').is(':visible')){
                        $('.k-animation-container,.k-animation-container>div').css('display','none')
                        $('.k-animation-container').data('item',null)
                        $('.k-animation-container ul').html('')
                    }else{
                         
                        $('.k-animation-container').data('item',this)
                        $('.k-animation-container ul').html('')
                        
                        $('.k-animation-container,.k-animation-container>div').css('display','block')
                        $('.k-animation-container').css({
                            top:$(this).offset().top+$(this).height()-getScrollY(),
                            left:$(this).offset().left+10-getScrollY(1)-40,
                            overflow: 'visible'}
                        )
                        $('.k-animation-container>div').css({
                            transform: 'translateY(0px)'
                        })  
                        
                        var g1_x= function(e){
                            var ii = $(this).attr('data-offset-index')
                            //alert(ii);
                            if(ii!=cb){
                                $('.loading').show()
                                removeCacheWhenChangeBranch()
                                delete localStorage.ct1
                                delete localStorage.ct2
                                delete localStorage.ct3
                                cb=ii
                                savecb(function(){
                                    document.location.reload()
                                })
                            }
                            
                            $('.k-animation-container ul li.k-state-selected').removeClass('k-state-selected')
                            $('.k-animation-container ul li[data-offset-index="'+ii+'"]').addClass('k-state-selected')
                             
                            $('.k-animation-container').hide() 
                             
                        }
                        
                        var sources = loadAllBranches() 
                        for(var i in sources){    
                            var ll = $('<li tabindex="-1" role="option" unselectable="on" class="k-item ng-scope" data-offset-index="'+sources[i].id+'">'+sources[i].name+'</li>')
                            if(sources[i].id==cb) 
                                ll.addClass('k-state-selected')
                            ll.hover(function(e){
                                $(this).addClass('k-state-hover')
                            },function(e){
                                $(this).removeClass('k-state-hover')
                            })                
                            $('.k-animation-container ul').append(ll)
                            ll.click(g1_x)
                        }
                        
                        $('.k-animation-container ul').unbind('hover')
                        $('.k-animation-container ul').hover(function(e){
                                
                        },function(e){
                            $('.k-animation-container').hide() 
                        })
                    }
                })             
                
                //$('.popPoint').removeClass('dpn');
                $('.saleNavIcon').click(function (e) {
                    e.stopPropagation();
                    $('.saleNav ul').toggle();
                    $(this).toggleClass('active');
                });
                
                
                $('body').click(function (e) {
                    if (e.target.className !== "saleNavIcon") {
                        $('.saleNav ul').hide();
                        $('.saleNavIcon').removeClass('active');
                    }
                });
                /*$('body').keyup(function(e){
                    
                     
                })*/
                $('body').keydown(function(e){
                    if(e.which==119){ //f8
                        //console.log(e)
                        e.preventDefault()
                        $('#payingAmt').select()
                        if(e.ctrlKey){
                            $('#payingAmt').val($('#payingAmt').closest('tr').prev().find('strong').html().replace(/\./g,'')).change()
                        }
                    }else //alert(119)
                    if(e.which==114){ //f3
                        e.preventDefault()
                        //preventDefault()
                        if($('#productSearchInput').val())
                            $('#productSearchInput').select()
                        else
                            $('#productSearchInput').focus()
                    }else
                    if(e.which==120){ //f9
                        e.preventDefault()
                        if(!$('.kv2CoffeePay [ng-click="payForOrder()"]').hasClass('ng-hide'))
                            $('.kv2CoffeePay [ng-click="payForOrder()"]').click()
                        else if(!$('.kv2CoffeePay [ng-click="saveRefund()"]').hasClass('ng-hide'))
                            $('.kv2CoffeePay [ng-click="saveRefund()"]').click()  
                        else if(SellAllowOrder){
                        if(!$('.kv2CoffeePay [ng-click="book()"]').hasClass('ng-hide'))
                            $('.kv2CoffeePay [ng-click="book()"]').click()      
                        else if(!$('.kv2CoffeePay [ng-click="bookSave()"]').hasClass('ng-hide'))
                            $('.kv2CoffeePay [ng-click="bookSave()"]').click()
                        }
                    }else
                    if(e.which==115){ //f4
                        e.preventDefault()
                        if(!$('.tabBut_1').hasClass('active')){
                            $('.tabBut_1').click()
                        }
                        $('#customerSearchInput').focus()
                    }else if(e.which==122){ //f11
                        e.preventDefault()
                        $('.screenfull').click()
                    }else if(e.which==112){ //f1 
                        e.preventDefault()
                        $('[ng-click="add()"]:visible').click()
                    }else if(e.which==9){
                        //tab
                        console.log('tab:',e)
                        
                        if(e.shiftKey){
                            var zz = $('.kv2Right .kvTabs:eq(0) .mr-bg li a.active')
                            if(zz.length){
                                var zz2 = zz.parent().next()
                                if(zz2.length && zz2.is(":visible")){
                                    zz2.find('>a').click()                                     
                                }else{
                                    zz.parent().siblings(":first-child").find('>a').click()                                    
                                }
                            }
                            e.preventDefault()
                            return;
                        }
                        
                        var zz = $('ul.headNav>li>.active')
                        var zz2 = zz.parent().next()
                        if(zz2.length && zz2.is(":visible")){
                            zz2.find('>a').click()
                            e.preventDefault()
                        }else{
                            zz.parent().siblings(":first-child").find('>a').click()
                            e.preventDefault()
                        }
                    }else if(e.which == 38)  {
                        //up
                        if($('modal-container.topping').is(":visible")){
                            var vv
                            if($(document.activeElement).is('input')){
                                vv = $(document.activeElement)
                            }else{
                                vv = $('modal-container.topping .modal-body input:nth(0)')
                            }
                            if(vv.length){
                                vv.next().click()
                                e.preventDefault()
                            }
                        }else
                        if($('[kendo-window="wdChangeTable"]').is(':visible')){ //alert(1)
                            var vv
                            if($(document.activeElement).is('input.form-control-custom')){
                                vv = $(document.activeElement)
                            }else{
                                vv = $('kv-cashier-cart-split input.form-control-custom:nth(0)')
                            }
                            if(vv.length){
                                vv.next().click()
                                e.preventDefault()
                            }
                            
                        }else
                        
                        
                        if($(document.activeElement).is('[ng-model="item.quantity"]')){
                            $(document.activeElement).next().click()
                        }
                    }else if(e.which == 40)  {
                        //down
                        if($('modal-container.topping').is(":visible")){
                            var vv
                            if($(document.activeElement).is('input')){
                                vv = $(document.activeElement)
                            }else{
                                vv = $('modal-container.topping .modal-body input:nth(0)')
                            }
                            if(vv.length){
                                vv.prev().click()
                                e.preventDefault()
                            }
                        }else
                        if($('[kendo-window="wdChangeTable"]').is(':visible')){ //alert(2)
                            var vv
                            if($(document.activeElement).is('input.form-control-custom')){
                                vv = $(document.activeElement)
                            }else{
                                vv = $('kv-cashier-cart-split input.form-control-custom:nth(0)')
                            }
                            if(vv.length){
                                vv.prev().click()
                                e.preventDefault()
                            }
                        }else
                        
                        
                        if($(document.activeElement).is('[ng-model="item.quantity"]')){
                            $(document.activeElement).prev().click()
                        }
                    }else if(e.which == 13 && !e.shiftKey)  {  
                        //enter
                        
                        if($('modal-container.topping').is(":visible")){
                            var vv,zz
                            if($(document.activeElement).is('.form-note textarea')){
                                zz = $('modal-container.topping .modal-body input:nth(0)')
                            }else{
                            
                                if($(document.activeElement).is('input')){
                                    vv = $(document.activeElement)
                                }else{
                                    vv = $('modal-container.topping .modal-body input:nth(0)')
                                }
                                zz = $(vv).parents('.product-cart-list').next().find('input')
                            }
                        
                            if(zz.length){
                                zz.focus().select()
                                e.preventDefault()
                            }
                        }else
                        if($('[kendo-window="wdChangeTable"]').is(':visible')){ //alert(1)
                            var vv,zz
                             
                            if($(document.activeElement).is('input.form-control-custom')){
                                vv = $(document.activeElement)
                            }else{
                                vv = $('kv-cashier-cart-split input.form-control-custom:nth(0)')
                            }
                            zz = $(vv).parents('.row-list').next().find('input.form-control-custom')
                             
                            if(zz.length){
                                zz.focus().select()
                                e.preventDefault()
                            }
                        }else
                        
                        
                        if($(document.activeElement).parents('tr[data-index]').length){
                            var zz = $(document.activeElement).parents('tr[data-index]').next().find('[ng-model="item.quantity"]')
                            if(zz.length){
                                zz.focus().select()
                                e.preventDefault()
                            }
                        }
                        else if(!$(document.activeElement).is('#productSearch #productSearchInput'))
                        {
                            var zz = $('.kv2Coffee tr[data-index]:first-child [ng-model="item.quantity"]')
                            if(zz.length){
                                zz.focus().select()
                                e.preventDefault()
                            }
                        }
                    }else if(/*e.which == 16*/e.which == 13 && e.shiftKey)  { //shift, previous product -> shift enter
                        if($('modal-container.topping').is(":visible")){
                            var vv,zz
                            if($(document.activeElement).is('.form-note textarea')){
                                zz = $('modal-container.topping .modal-body input:nth(0)')
                            }else{
                            
                                if($(document.activeElement).is('input')){
                                    vv = $(document.activeElement)
                                }else{
                                    vv = $('modal-container.topping .modal-body input:nth(0)')
                                }
                                zz = $(vv).parents('.product-cart-list').prev().find('input')
                            }
                        
                            if(zz.length){
                                zz.focus().select()
                                e.preventDefault()
                            }
                        }else
                        if($('[kendo-window="wdChangeTable"]').is(':visible')){ //alert(1)
                            var vv,zz
                             
                            if($(document.activeElement).is('input.form-control-custom')){
                                vv = $(document.activeElement)
                            }else{
                                vv = $('kv-cashier-cart-split input.form-control-custom:nth(0)')
                            }
                            zz = $(vv).parents('.row-list').prev().find('input.form-control-custom')
                             
                            if(zz.length){
                                zz.focus().select()
                                e.preventDefault()
                            }
                        }else
                        if($(document.activeElement).parents('tr[data-index]').length){
                            var zz = $(document.activeElement).parents('tr[data-index]').prev().find('[ng-model="item.quantity"]')
                            if(zz.length){
                                zz.focus().select()
                                e.preventDefault()
                            }
                        }
                    }
                })
                
                
                
                productListTmpl = $('#productListTmpl').html()
                tableListTmpl = $('#tableListTmpl').html()
                tableListTmpl2 = $('#tableListTmpl2').html()
                tableListTmpl3 = $('#tableListTmpl3').html()
                catListTmpl = $('#catListTmpl').html()
                productInvoiceTmpl = $('#productInvoiceTmpl').html()
                productItemTempl = $('#productItemTempl').html()
                 
                /*$(window).resize(function(){
                    loadTabs11()
                })*/
                
                // .mainWrapper>.scroll-lane 
                //$('[ng-model="activeCart.uuid"] [ng-click="add()"]').attr('href','javascript:void(0)')
                $('[ng-model="activeCart.uuid"] [ng-click="add()"]').unbind('click')
                $('[ng-model="activeCart.uuid"] [ng-click="add()"]').click(
                    function(e){
                        //alert(nCode())
                        var o22 = emptyOrder(ct2)
                        if(!orders) orders=[]
                        orders.push(o22)
                        saveOrders()     
                        ct3 = o22.code   
                        localStorage.ct3 = ct3   
                        var co = findOrdersByTable(ct2)  
                        //co.push(o22)
                        addTabs(co)
                        return false
                    }
                ) 
                
                $('[ng-click="showProductMenu()"]').unbind('click')
                $('[ng-click="showProductMenu()"]').click(function(e){
                    viewType = 'showCategories'; var that = this
                    showProductMenu(that )
                })
                
                $('[ng-click="showTables()"]').unbind('click')
                $('[ng-click="showTables()"]').click(function(e){
                    viewType = 'table'; var that = this
                    showTables(that )
                })
                
                $('[ng-click="showRight()"]').unbind('click')
                $('[ng-click="showRight()"]').click(function(e){
                    var that = this
                    showRight(that)
                })
                
                $('[ng-click="logout()"]').click(function(e){
                    delete localStorage['kvSession']
                    document.location.href = '/logout.php'
                    return false
                })
                
                $.datepicker.setDefaults( $.datepicker.regional[ "vi-VN" ] );
                //$( "[data-role="datepicker"]" ).datepicker( $.datepicker.regional[ "vi-VN" ] );
                
                /** ----------------start */
                $('[ng-click="QuickAddProduct()"]').click(function(e){
                    $('.k-window-quickaddProduct [ng-model],.k-window-quickaddProduct [k-ng-model]').val('')
                    var ty = Math.min($(document).width(),550)
                    $('.k-window-quickaddProduct').css({
                        display: 'block',
                        width: ty,
                        'left': ($(document).width()-ty)/2 ,
                    })
                    $('.k-overlay').css({
                        display:'block',
                        'z-index': 10004
                    })
                    $('.k-window-quickaddProduct .k-i-close,.k-window-quickaddProduct [ng-click="cancel()"]').unbind('click')
                    $('.k-window-quickaddProduct .k-i-close,.k-window-quickaddProduct [ng-click="cancel()"]').click(function(e){
                        $('.k-window-quickaddProduct').hide()
                        $('.k-overlay').hide()
                        //$('.k-window-categoryProduct .k-i-close').click()
                        $('.k-animation-container').hide()
                        return false
                    })
                    ///////////////
                    $('#idDropdownlistCat').html('<option value="0" selected="selected">---Lựa chọn---</option>')
                    var ll0 = $('<li tabindex="-1" role="option" unselectable="on" class="k-item ng-scope k-state-selected k-state-focused" data-offset-index="0">---Lựa chọn---</li>')
                    var g1 = function(e){
                        var ii = parseInt($(this).attr('data-offset-index'))
                        //console.log(product_categories[ii])
                        
                        if(ii>0){
                            //$('#idDropdownlistCat').val(product_categories[ii].id)
                            //$('.k-window-quickaddProduct .k-dropdown .k-input').html(product_categories[ii].name)
                            $('#idDropdownlistCat option').removeAttr('selected')
                            $('#idDropdownlistCat option[value="'+product_categories[ii-1].id+'"]').attr('selected','selected')
                            $('.k-window-quickaddProduct .k-dropdown .k-input').html(product_categories[ii-1].name)
                        }else{
                            $('#idDropdownlistCat option').removeAttr('selected')
                            $('#idDropdownlistCat option[value="0"]').attr('selected','selected')
                            $('.k-window-quickaddProduct .k-dropdown .k-input').html($('#idDropdownlistCat option[value="0"]').html())
                        }
                        $('.k-animation-container').hide() 
                    }
                    ll0.click(g1)
                    ////$('.k-animation-container').data('item','idDropdownlistCat')
                    ////$('.k-animation-container ul').html(ll0)
                    $('.k-window-quickaddProduct .k-dropdown .k-input').html($('#idDropdownlistCat option[value="0"]').html())
                    
                    var gg3 = function(i){
                        var ll = $('<li tabindex="-1" role="option" unselectable="on" class="k-item ng-scope" data-offset-index="'+(parseInt(i)+1)+'">'+product_categories[i].name+'</li>')
                        ll.hover(function(e){
                            $(this).addClass('k-state-hover')
                        },function(e){
                            $(this).removeClass('k-state-hover')
                        })
                        ll.click(g1)
                        $('.k-animation-container ul').append(ll)
                    }
                    
                    for(var i in product_categories){
                        $('#idDropdownlistCat').append('<option value="'+product_categories[i].id+'">'+product_categories[i].name+'</option>')
                        ////gg3(i)
                    }
                    $('.k-window-quickaddProduct .k-dropdown').unbind('click')
                    $('.k-window-quickaddProduct .k-dropdown').click(function(e){
                        if($('.k-animation-container').data('item')=='idDropdownlistCat'&&$('.k-animation-container').is(":visible")){
                            $('.k-animation-container').hide();
                            return;
                        }
                        
                        $('.k-animation-container').data('item','idDropdownlistCat')
                        $('.k-animation-container ul').html(ll0)
                        for(var i in product_categories){                            
                            gg3(i)
                        }
                        
                        var that = $(this)
                        $('.k-animation-container').css({
                            display: 'block',
                            top: that.offset().top+that.height(),
                            left: that.offset().left,
                        })
                        
                        $('.k-animation-container>div').css({
                            display: 'block',
                            transform: 'translateY(0px)'
                        })
                    })
                    $('.k-animation-container ul').unbind('hover')
                    $('.k-animation-container ul').hover(function(e){
                            
                    },function(e){
                        $('.k-animation-container').hide() 
                    })
                    ////////////
                    
                    $('.k-window-quickaddProduct [ng-model="product.Code"]').unbind('blur')
                    $('.k-window-quickaddProduct [ng-model="product.Code"]').blur(function(e){
                        if($(this).val()){
                            for(var i in products){
                                if(products[i].code.toLowerCase()==$(this).val().toLowerCase()){
                                    toastr.warning('Trùng mã sản phẩm')
                                    $(this).val('')
                                    return false;
                                }
                            }
                        }
                        return false
                    })
                    
                    $('.k-window-quickaddProduct [ng-model="product.BasePrice"]').unbind('blur')
                    $('.k-window-quickaddProduct [ng-model="product.BasePrice"]').blur(function(e){
                        if($(this).val()){
                            $(this).val($(this).val().replace(/[\.,]+/g,''))  
                            if(!$(this).val().match(/^\d+$/)){
                                toastr.warning('Sai định dạng số')
                                $(this).val('')
                                return false;
                            }
                        }
                        return false
                    })
                    
                    $('.k-window-quickaddProduct [ng-click="SaveProduct()"]').unbind('click')
                    $('.k-window-quickaddProduct [ng-click="SaveProduct()"]').click(function(e){
                        //_serialize('.k-window-quickaddProduct',[1],2)
                        //"code=&name=abc&categoryid=0&baseprice=100000&unit=kg&islotserialcontrol=on"
                        var x = _serialize('.k-window-quickaddProduct',[1],2)
                        if(x==''){
                            toastr.warning('Bạn chưa đặt tên hàng hóa')
                            return false
                        }
                        var x2 = parse_str(x)
                        var y = {
                            code: decodeURIComponent(x2.code),
                            name: decodeURIComponent(x2.name),
                            category: x2.categoryid,
                            price: x2.baseprice,
                            uname: decodeURIComponent(x2.unit),
                            branch: cb
                        }
                        //ajax submit
                        $.ajax('ajax.php?action=quickproduct',{
                            data: 'data='+encodeURIComponent(JSON.stringify(y)),
                            method: "POST",
                            dataType: 'json',
                            success: function(d){
                                 //console.log(d)
                                 if(d.error){
                                     toastr.warning('Có lỗi xảy ra')
                                 }else{
                                     $('.k-window-quickaddProduct .k-i-close').click()
                                     var ob = {
                                        category:x2.categoryid,
                                        code:d.code,
                                        description:"",
                                        formula:[],
                                        id:d.id,
                                        image:"",
                                        main:"1",
                                        name:y.name,
                                        parent:"0",
                                        price:y.price,
                                        price2:null,
                                        pricelast:null,
                                        quantity:null,
                                        sell:"1",
                                        status:"1",
                                        type:"3",
                                        uname:y.uname,
                                        unit:d.unit,
                                        uvalue:y.uname?1:null,
                                     }
                                     products.push(ob)
                                     saveProducts()
                                     
                                     //loadJsonProducts(function(){
                                         $('#productSearchInput').focus()
                                         $('#productSearchInput').val(d.code)
                                     //})
                                     addProductInvoice(ob)
                                     //load lai trang san pham
                                     if(viewType=="showCategories")
                                        loadTabs11()
                                 }
                            },
                            error:function(e){
                                console.log(e)
                                toastr.warning('Có lỗi xảy ra')
                            }
                        })
                        return false;
                    })
                    
                    $('.k-window-quickaddProduct [ng-click="EditCategory(0)"]').unbind('click')
                    $('.k-window-quickaddProduct [ng-click="EditCategory(0)"]').click(function(e){
                        $('.k-window-categoryProduct [ng-model],.k-window-categoryProduct [k-ng-model]').val('') 
                        $('.k-window-categoryProduct').show()
                        $('.k-overlay').css({                             
                            'z-index': 10006
                        })
                        $('.k-window-categoryProduct .k-i-close,.k-window-categoryProduct [ng-click="cancel()"]').unbind('click')
                        $('.k-window-categoryProduct .k-i-close,.k-window-categoryProduct [ng-click="cancel()"]').click(function(e){
                            $('.k-window-categoryProduct').hide()
                            $('.k-overlay').css({                                 
                                'z-index': 10004
                            })
                            $('.k-animation-container').hide()
                            return false
                        })
                        
                        $('.k-window-categoryProduct [k-ng-model="category.ParentId"]').html('<option value="0" selected="selected">---Lựa chọn---</option>')
                        var ll0_ = $('<li tabindex="-1" role="option" unselectable="on" class="k-item ng-scope k-state-selected k-state-focused" data-offset-index="0">---Lựa chọn---</li>')
                        var g1_ = function(e){
                            var ii = parseInt($(this).attr('data-offset-index'))
                            //console.log(product_categories[ii])
                            
                            if(ii>0){
                                 
                                $('.k-window-categoryProduct [k-ng-model="category.ParentId"] option').removeAttr('selected')
                                $('.k-window-categoryProduct [k-ng-model="category.ParentId"] option[value="'+product_categories[ii-1].id+'"]').attr('selected','selected')
                                $('.k-window-categoryProduct .k-dropdown .k-input').html(product_categories[ii-1].name)
                            }else{
                                $('.k-window-categoryProduct [k-ng-model="category.ParentId"] option').removeAttr('selected')
                                $('.k-window-categoryProduct [k-ng-model="category.ParentId"] option[value="0"]').attr('selected','selected')
                                $('.k-window-categoryProduct .k-dropdown .k-input').html($('.k-window-categoryProduct [k-ng-model="category.ParentId"] option[value="0"]').html())
                            }
                            $('.k-animation-container').hide() 
                        }
                        ll0_.click(g1_)
                        $('.k-animation-container').data('item','categoryProduct')
                        $('.k-animation-container ul').html(ll0_)
                        $('.k-window-categoryProduct .k-dropdown .k-input').html($('.k-window-categoryProduct [k-ng-model="category.ParentId"] option[value="0"]').html())
                        for(var i in product_categories){
                            $('.k-window-categoryProduct [k-ng-model="category.ParentId"]').append('<option value="'+product_categories[i].id+'">'+product_categories[i].name+'</option>')
                            var ll = $('<li tabindex="-1" role="option" unselectable="on" class="k-item ng-scope" data-offset-index="'+(parseInt(i)+1)+'">'+product_categories[i].name+'</li>')
                            ll.hover(function(e){
                                $(this).addClass('k-state-hover')
                            },function(e){
                                $(this).removeClass('k-state-hover')
                            })
                            ll.click(g1_)
                            $('.k-animation-container ul').append(ll)
                        }
                        $('.k-window-categoryProduct .k-dropdown').unbind('click')
                        $('.k-window-categoryProduct .k-dropdown').click(function(e){
                            if($('.k-animation-container').data('item')=='categoryProduct'&&$('.k-animation-container').is(':visible')){
                                $('.k-animation-container').hide()
                                return;
                            }
                            var that = $(this)
                            $('.k-animation-container').css({
                                display: 'block',
                                top: that.offset().top+that.height(),
                                left: that.offset().left,
                            })
                            
                            $('.k-animation-container>div').css({
                                display: 'block',
                                transform: 'translateY(0px)'
                            })
                        })
                        $('.k-animation-container ul').unbind('hover')
                        $('.k-animation-container ul').hover(function(e){
                                
                        },function(e){
                            $('.k-animation-container').hide() 
                        })
                        
                        $('.k-window-categoryProduct [ng-click="saveCategory()"]').unbind('click')
                        $('.k-window-categoryProduct [ng-click="saveCategory()"]').click(function(e){
                            //alert('t')
                            //_serialize('.k-window-categoryProduct',[0],2)
                            //"name=nhom%201&parentid=0"
                            var x = _serialize('.k-window-categoryProduct',[0],2)
                            if(x==''){
                                toastr.warning('Bạn chưa đặt tên nhóm hàng hóa')
                                return false
                            }
                            var x2 = parse_str(x)
                            var y = {                             
                                name: decodeURIComponent(x2.name),                             
                                parent_id: decodeURIComponent(x2.parentid),                             
                            }
                            //ajax submit
                            $.ajax('ajax.php?action=productcategory',{
                                data: y,
                                method: "POST",
                                dataType: 'json',
                                success: function(d){
                                     //console.log(d)
                                     if(d.error){
                                         toastr.warning('Có lỗi xảy ra')
                                     }else{
                                         $('.k-window-categoryProduct .k-i-close').click()
                                         var ob = {                                         
                                            id:d.id,
                                            name:y.name,
                                            parent_id:y.parent_id,
                                            level: d.level,
                                         }
                                         product_categories.push(ob)
                                         saveCacheProductCategories()
                                         
                                         $('#idDropdownlistCat').html('<option value="0" >---Lựa chọn---</option>')
                                         
                                         $('.k-animation-container ul').html(ll0)
                                         
                                         for(var i in product_categories){
                                            $('#idDropdownlistCat').append('<option '+(ob.id==product_categories[i].id?' selected="selected"':'')+
                                                ' value="'+product_categories[i].id+'">'+product_categories[i].name+'</option>')  
                                            gg3(i)                                  
                                         }
                                          
                                         $('.k-window-quickaddProduct .k-dropdown .k-input').html(ob.name)
                                        
                                     }
                                },
                                error:function(e){
                                    console.log(e)
                                    toastr.warning('Có lỗi xảy ra')
                                }
                            })
                            
                            return false
                        })
                    })
                     
                    return false
                })
                /** ----------------end */
                
                $('#productSearchInput').keyup(function(e){
                    //alert($(this).val())
                    var name = $(this).val().toLowerCase()
                    if(name.length>=1){
                        var ret = []
                        for(var i in products){
                            if(products[i].name.toLowerCase().indexOf(name)>-1 || products[i].code.toLowerCase().indexOf(name)==0){
                                ret.push(products[i])
                                if(ret.length>20) break;
                            }
                        }
                        var next = $(this).next()
                        next.removeClass('ng-hide')
                        if(ret.length){  
                            var ul = next.find('ul')
                            ul.removeClass('ng-hide')
                            ul.html('')                    
                            $(this).next().find('.autoNotFound').addClass('ng-hide')
                            
                            ul.hover(function(e){},function(e){
                                $(this).addClass('ng-hide')
                            })
                            
                            for(var i in ret){
                                var _productItemTempl = productItemTempl+''
                                var ret2x = {}
                                var $index = i
                                var suggestion = ret[i]
                                var mx = _productItemTempl.match(/\{\{([^\}]+)\}\}/g)
                                for(var k=0;k<mx.length;k++){
                                     
                                    var rt = mx[k].substr(2,mx[k].length-4)
                                    if(rt.match(/\|/) && !rt.match(/\|\|/)){
                                        var tr = rt.split('|')
                                        if(tr[1].match(/\:/)){
                                            var tr1 = tr[1].split(':')
                                            var tr11 = tr1.shift()
                                            rt = tr11+"("+tr[0]+","+tr1.join(',')+")"
                                        }else{
                                            rt = tr[1]+"("+tr[0]+")"
                                        }
                                    }
                                    console.log(rt)
                                    ret2x[mx[k]] = eval(rt)
                                    _productItemTempl = _productItemTempl.replace(mx[k],ret2x[mx[k]]==null?'':ret2x[mx[k]])
                                } 
                                //console.log(ret2x)
                                var ll = $(_productItemTempl)
                                ll.hover(function(e){
                                    $(this).siblings().removeClass('active')
                                    $(this).addClass('active')
                                })
                                ll.click(function(e){ 
                                    next.addClass('ng-hide')
                                    
                                    //giong 1434
                                    addOnClick(ret,this);
                                    /*
                                    //console.log(tt[parseInt($(this).attr('index'))])
                                    var it = ret[parseInt($(this).attr('index'))] ,
                                        ob = loadObject(ct3,orders,'code') ,
                                        check = loadObject(it.id,ob.products), item
                                    if(check){
                                        check.quantity++
                                        item =$.extend( {priceafterdiscount:function(){return this.price-this.discount}}, check )
                                        
                                        displayProducts(ob)
                                    }else{
                                        item = $.extend( it, 
                                        {
                                            priceafterdiscount:function(){return this.price-this.discount},
                                            discount :0,
                                            discountratio:0,
                                            deliveryqty:0,
                                            maxquantity:0,
                                            note:''
                                        } )
                                        item.maxquantity = item.quantity
                                        item.quantity = 1
                                        ob.products.push(item)  ; 
                                        //thongbaonhabep(ob) 
                                        addInvoiceItem(item,ob)
                                        displayPrice(ob)
                                        //displayProducts(ob)
                                    }
                                    
                                    saveOrders() */ 
                                    
                                })
                                ul.append(ll)
                                if(e.which==13){
                                    ll.click()
                                    $('#productSearchInput').val('')
                                    break
                                }
                            }
                             
                        }else{
                            $(this).next().find('.autoNotFound').removeClass('ng-hide')
                        }
                    }
                })
                
                
                
                $("#wrapper").removeAttr("style");
                updateWindow();
                $(window).resize(function () {
                    
                    var fm = $('.k-overlay:visible~.k-window:visible')
                    if(fm.length==1 && fm.css('position') == "absolute"){
                        fm.css({
                            left: ($(window).width()-fm.width())/2
                        })
                    }
                    
                    updateWindow();
                    
                    loadTabs11(1)
                });   
                
                console.log('abc1');
                
                getMobileOperatingSystem();

                $(".btn-close-app").click(function () {
                    $(".box-introduce-app").hide();
                    $('body').removeClass('hasIosAndroid');
                });

                if (navigator.userAgent.match(/Android/i)) {
                    window.scrollTo(0, 1);
                }
                //KvSpinner.start();

                //window.hidePopCtrl = function () {
                //    $('.popPoint').remove();
                //};
                //$('.pointContent a').click(function () {
                //    hidePopCtrl.call($(this));
                //    $('.popPoint').remove();
                //    localStorage['kv_hidePoint'] = false;
                //});

                //if (localStorage['kv_hidePoint'] && JSON.parse(localStorage['kv_hidePoint']) === false) {
                //    window.hidePopCtrl();
                //}

                var currentTheme;
                if (localStorage['kvsale_theme'])
                    currentTheme = localStorage['kvsale_theme'];
                //else
                //    currentTheme = JSON.parse(localStorage['kvsale_session']).user.Theme;

                if (currentTheme) {
                    var setTheme;
                    switch (currentTheme) {
                        case "color-df":
                            setTheme = 'theme-color-df'
                            break;
                        case "color1":
                            setTheme = 'theme-color1'
                            break;
                        case "color2":
                            setTheme = 'theme-color2'
                            break;
                        case "color3":
                            setTheme = 'theme-color3'
                            break;
                        case "color4":
                            setTheme = 'theme-color4'
                            break;
                        case "color5":
                            setTheme = 'theme-color5'
                            break;
                        case "color6":
                            setTheme = 'theme-color6'
                            break;
                        case "color7":
                            setTheme = 'theme-color7'
                            break;
                        case "color8":
                            setTheme = 'theme-color8'
                            break;
                    }
                    if (setTheme) {
                        $('body').addClass(setTheme);
                    }
                }
                console.log('abc2');
                var okl = function(){
                    console.log('okl');
                    if(cfs.type!=1 || typeof isThat!='undefined'){
                        $('.loading').hide()    
                    }else{
                        _okl = setTimeout(function(){
                            okl()
                        },1000)
                    }
                }
                okl()
                
                $('.searchCustomer #productSearchInput').focus()
                
            })
            
            
            
        })();