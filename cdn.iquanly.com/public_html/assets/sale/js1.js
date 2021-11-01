window.name = 'sale'
    
$.ajaxSetup({   
  global: false 
});  

$( document ).ajaxSend(function( ) {
    $('.loading').show()
}) 

$( document ).ajaxComplete(function() {
  $('.loading').hide()
}); 

//12/02/2020
var _currentTime2 = {}

function _currentTime(){
    //$('.kv2sale_Info [k-ng-model="activeCart.PurchaseDate"]')
    //$('#currentTime')
    //loadObject(ct3,orders,'code')
    function re(){ console.log('currentTime currentTime')
    
        //12/02/2020
        if(typeof ct3 == 'undefined') return;
        
        if($('#currentTime').is(':checked')){
            
            //12/02/2020
            var jhg; _currentTime2={}
            
            _currentTime2[ct3]/* _pid*/ = setInterval(jhg=function(){ //console.log('jhg')
                if(!ct3 || !orders  || typeof _currentTime2[ct3]=='undefined') return;
                var ke = moment().format('DD/MM/YYYY HH:mm')
                var keke = loadObject(ct3,orders,'code')
                
                $('.kv2sale_Info [k-ng-model="activeCart.PurchaseDate"]').val(ke)
                keke.date = ke;
            },5000)
            
            //12/02/2020
            jhg()
        }else{
            //if(typeof _currentTime2[ct3]/*_pid*/ !== 'undefined'){
            //    clearInterval(_currentTime2[ct3]/*_pid*/)
                //delete _currentTime2[ct3]/*_pid*/
            //}
            for(var i in _currentTime2){
                clearInterval(_currentTime2[i])
            }
            _currentTime2={}
        }
    }
    
    //12/02/0202
    if(localStorage[ct3]){
        $('#currentTime').prop('checked',false)
    }else{
        $('#currentTime').prop('checked',true)
    }
    
    re()
    
    $('#currentTime').unbind('change')
    $('#currentTime').change(function(){
        
        //12/02/0202
        if(typeof ct3 == 'undefined') return;
        if($(this).is(':checked')){
            delete localStorage[ct3] 
        }else{
            localStorage[ct3] = 1
        }
        
        re()
    })
}


    
var fakedomain = '';
function fakeDisconnect(t){
    if(t==undefined) t = true;
    else t = !!t;
    if(t){
        fakedomain = 'https://fakedomain.com'
        Offline.options.checks.xhr.url='https://cloudflare2.com/cdn-cgi/trace'
    }else{
        fakedomain = ''
        Offline.options.checks.xhr.url='https://cloudflare.com/cdn-cgi/trace'
    }
} 
loadAjaxPrint('printinvoice')
loadAjaxPrint('printreturn')
loadAjaxPrint('printorder')
if(PrintBarCode){
    loadAjaxPrint('printbarcode',function(){
        var vvv = cache_prints.getItem('printbarcode')
        $(vvv).find('img[src]').each(function(){
            $src = $(this).attr('src')
            var myImage = new Image(100, 100);
            myImage.src = $src;
        })
    })
}
window.ononline = function(){
    //alert('zzz')
}

//https://github.hubspot.com/offline/    
Offline.options = {checks: {xhr: {url: 'https://cloudflare.com/cdn-cgi/trace'}}};    

Offline.on('up', function(){
    //alert('online')
    $('.syncStatus strong,.kv2Right').removeClass('no-wifi')
    $('.syncStatus strong')[0].childNodes[0].nodeValue = "Có Internet "
    $('[ng-hide="isOffline"]').removeClass('ng-hide')
    
    $('[ng-click="pushOrderToServer(activeCart.Id)"]').removeClass('ng-hide')
})

Offline.on('down', function(){
    //alert('offline')
    $('.syncStatus strong,.kv2Right').addClass('no-wifi')
    $('.syncStatus strong')[0].childNodes[0].nodeValue = "Mất Internet "
    $('[ng-hide="isOffline"]').addClass('ng-hide')
    
    $('[ng-click="pushOrderToServer(activeCart.Id)"]').addClass('ng-hide')
})

    
var formatCurrency,formatQuantity,number
 
accounting.settings = {
	currency: {
		symbol : "",   // default currency symbol is '$'
		format: "%s%v", // controls output: %s = symbol, %v = value/number (can be object: see below)
		decimal : ",",  // decimal point separator
		thousand: ".",  // thousands separator
		precision : 0   // decimal places
	},
	number: {
		precision : 0,  // default precision on numbers is 0
		thousand: ".",
		decimal : ","
	}
}
formatCurrency=function(e){
    return accounting.formatMoney(e)
}
formatQuantity=function(e){
    if(Math.abs(Math.round(e)-e)>0.0001) return e;
    return accounting.formatNumber(e)
}
number=function(e){
    return e
}  
     
/*var cache_users = new Cache(-1, false, new Cache.LocalStorageCacheStorage('users'));          
var cache_customers = new Cache(-1, false, new Cache.LocalStorageCacheStorage('customers'));
//var cache_suppliers = new Cache(-1, false, new Cache.LocalStorageCacheStorage('suppliers'));
var cache_orders = new Cache(-1, false, new Cache.LocalStorageCacheStorage('orders'));
var cache_invoices = new Cache(-1, false, new Cache.LocalStorageCacheStorage('invoices'));
var cache_offlines = new Cache(-1, false, new Cache.LocalStorageCacheStorage('offlines'));*/
        
var branches = cache_branches.getItem("branches");
var users = cache_users.getItem("users");

var products //= cache_products.getItem("products");
loadProductsFromCache(function(){     
    if(!products || (products && products.length==0))
        loadJsonProducts()
})
var product_categories = cache_products.getItem("categories");
var customers = cache_customers.getItem("customers");
var customer_groups = cache_customers.getItem("customer_groups");

var orders = cache_orders.getItem("orders");

var user_roles = cache_users.getItem("user_roles");

var dataItem = -1;//for branch id


if(!cb){
    document.location.href = '/';
}

var books;  //alert(cfs)
        
var offlines = cache_offlines.getItem("offlines"+cb);
if(!offlines) offlines = []
 
var cpage = 0, tpage=0, cpage2 = 0, tpage2=0;
var invoices = cache_invoices.getItem("invoices");

if(invoices==null){
    loadJsonInvoices()
}
 
loadJsonOrders()
setInterval(function(){loadJsonOrders()},3600000)

if(!user_roles) loadJsonUserRoles()

//03/19/2020
var _doSocket = function(){
    socket = io.connect(ssss+'/sale',{
        'reconnection': true,
        'reconnectionDelay': 1000,
        'reconnectionDelayMax': 5000,
        'forceNew': true
    }); //{ transports: ['websocket'] }
    socket.room = data_user.room
    socket.on('connect', function () {
        socket.emit('join', socket.room);
    });
    socket.on('connect_error', function (e) {
        console.log('connect_error',e)
    });
     
}

if(site_type==1){
    _doSocket()
    socket.on('emit', function ( data) {
        try{
            var j = JSON.parse(data)
            console.log(j)
            switch(j.job){
                case "delivery":                
                    //loadJsonOrders(loadTabs11)
                    beep(1)
                    console.log(j.code,orders)
                    var j2 = loadObject(j.code,orders,'code'); console.log('j2',j2)
                    //var j3 = loadObject(j2.table,tables); console.log('j3',j3)
                    //toastr["info"]("Bàn "+(j3?j3.name:j.table)+" vừa có sản phẩm đã cung ứng")      
                    toastr["info"]("Bàn "+_getTableName(j.table)+" vừa có sản phẩm đã cung ứng")      
                     
                    //2019
                    loadJsonOrders(function(){
                        dochange = 1;
                        loadTabs11()
                        
                        /*setTimeout(function(){
                            //alert(loadObject(ct3,orders,'code').products[0].deliveryqty)
                            //addTabs(findOrdersByTable(ct2))
                            alert(ct3)
                            alert($('[kv-tab-id="cart.uuid"] li[data-id="'+ct3+'"]>a').length)
                            $('[kv-tab-id="cart.uuid"] li[data-id="'+ct3+'"]>a').click()     
                        },1500) */   
                        
                        setTimeout(function(){
                            delete dochange;   
                        },1500)                                                        
                    })
                                                                
                case "processing":
                    loadJsonOrders(loadTabs11)
                    beep(1)
                    console.log(j.code,orders)
                    var j2 = loadObject(j.code,orders,'code'); console.log(j2)
                    //var j3 = loadObject(j2.table,tables); console.log(j3)
                    //toastr["info"]("Bàn "+(j3?j3.name:j.table)+" vừa có sản phẩm chờ cung ứng")    
                    toastr["info"]("Bàn "+_getTableName(j.table)+" vừa có sản phẩm chờ cung ứng")                                            
                break;
                
                /*
         
                                job: 'noti',     
                                id: socket.id,
                                table: ob.table,
                                data: ob,
                                isnew: 1
                                                
                */
                case "noti":
                    if(socket.id!=j.id){//khác bản thân người gửi socket
                        if(j.isnew){
                            //trước khi add thì phải add 1 số thông tin về products đã
                            j.data.products=_addInfoToProducts(j.data)
                             
                            //add to orders
                            orders.push(j.data)     
                            toastr.info('Có đơn hàng mới từ bàn: '+_getTableName(j.table))     
                            
                            //nếu người này dang xem bàn này
                            if(_checkIn(j.table,ct2)){ //if(j.table==ct2){   
                                dochange = 1 //fixed on 02/09/2020
                                var co = findOrdersByTable(ct2)  
                                addTabs(co)
                            }                          
                        }else{
                            //update
                            var uu = loadObject(j.data.code,orders,'code')
                            //console.log('uu1:',JSON.stringify(uu))
                            
                            //add 1 số thông tin về products đã
                            j.data.products=_addInfoToProducts(j.data)
                            
                            uu = $.extend(uu,j.data)
                            if(uu.status==-2) uu.status = -1;
                            //console.log('uu2:',JSON.stringify(uu))
                            
                            toastr.info('Có thay đổi mới từ hóa đơn: '+j.data.code)
                            
                            //nếu người này đang xem đơn này
                            if(j.data.code==ct3){    
                                dochange = 1 //fixed on 02/09/2020
                                var co = findOrdersByTable(ct2)  
                                addTabs(co)
                            }  
                        }
                        saveOrders()
                    }
                break;
                
                case "notipay":
                /*
                                job: 'notipay',
                                table: oldT,
                                id: socket.id,
                                code: ob2_code  ,
                                isnew: 1                       
                */
                    if(socket.id!=j.id){//khác bản thân người gửi socket
                        if(j.isnew){
                            toastr.info('Có đơn hàng mới được thanh toán từ phòng/bàn: '+_getTableName(j.table)) 
                        }else{
                            toastr.info('Có đơn hàng ('+j.code+') được thanh toán từ phòng/bàn: '+_getTableName(j.table)) 
                            
                            //xóa nó ra khỏi orders
                            for(var ii in orders){
                                if(orders[ii].code==j.code){
                                    orders.splice(ii,1)
                                    break;
                                }
                            }                            
                            saveOrders()
                            
                            //nếu người này đang xem đơn này
                            if(j.code==ct3){                                           
                              /*  
                                //chuyen sang tab phong ban
                                if(!$('[ng-click="showTables()"]').is('.active'))
                                    $('[ng-click="showTables()"]').click() 
                                 
                                //check tabs
                                ////$('.swiper-container li[data-id="'+ct2+'"] aside>a[ng-click="itemClicked(p)"]:first-child').click()
                                if($('.swiper-container li[data-id="'+ct2+'"] aside>a[ng-click="itemClicked(p)"]:first-child').length){
                                    $('.swiper-container li[data-id="'+ct2+'"] aside>a[ng-click="itemClicked(p)"]:first-child').click()
                                }else{
                                    afterClick()                  
                                }*/
                                dochange = 1 //fixed on 02/09/2020
                                var co = findOrdersByTable(ct2)  
                                addTabs(co)
                                   
                            }else
                            //nếu người này đang ở bàn này
                            if(_checkIn(j.table,ct2)){//if(ct2==j.table){
                                dochange = 1 //fixed on 02/09/2020
                                var co = findOrdersByTable(ct2)  
                                addTabs(co)
                            }
                        }
                    }
                break;
                
                //03/18/2020
                case "notiorder":
                /*
                                job: 'notiorder'                   
                */
                    toastr.info('Có đơn đặt hàng mới') 
                    
                    //tải lại danh sách cus
                    loadJsonCustomers()
                    //tải lại danh sách order
                    _poi(function(){
                        if($('[kendo-window="orderWindow"]').is(':visible')){
                            _g2()
                        } 
                    })
                    beep(1)                       
                break;
                
                case "changeInvoice":
                /*
                            job: 'changeInvoice',     
                            id: socket.id,                                     
                            'invoices': data.invoices,
                            'fromcode': data.fromcode,
                            'tocode': data.tocode,
                            'totable': data.totable
                */
                    if(socket.id!=j.id){//khác bản thân người gửi socket
                        toastr.info('Có đơn hàng được tách tới phòng/bàn: '+_getTableName(j.totable))
                         
                        //xóa đơn tocode 
                        for(var i in orders){
                            if(orders[i].code==j.tocode){
                                orders.splice(i,1);
                                break;
                            }
                        }                         
                        //xóa đơn fromcode
                        for(var i in orders){
                            if(orders[i].code==j.fromcode){
                                orders.splice(i,1);
                                break;
                            }
                        }
                        //add fromcode và tocode
                        for(var i in j.invoices){
                            //add 1 số thông tin về products đã
                            j.invoices[i].products=_addInfoToProducts(j.invoices[i])
                            
                            orders.push(j.invoices[i])
                        }
                        saveOrders()
                        
                        //nếu người này đang xem 1 trong 2 đơn này
                        if(j.fromcode==ct3 || j.tocode==ct3){ 
                            dochange = 1 //fixed on 02/09/2020
                            var co = findOrdersByTable(ct2)  
                            addTabs(co)
                        }else
                        //nếu người này đang ở 1 trong 2 bàn (2 bàn này có thể trùng nhau)
                        {
                            for(var i in j.invoices){
                                //
                                if(_checkIn(j.invoices[i].table,ct2)){
                                    dochange = 1 //fixed on 02/09/2020
                                    var co = findOrdersByTable(ct2)  
                                    addTabs(co)
                                    break;
                                }
                            }
                        }   
                    }
                break;
                
                case "mergeInvoice":
                /*
                            job: 'mergeInvoice',     
                            id: socket.id,                                     
                            'invoice': data.invoice,
                            'fromcode': data.fromcode,
                            'tocodes': data.tocodes,
                            'totable': data.totable                        
                */
                    if(socket.id!=j.id){//khác bản thân người gửi socket
                        toastr.info('Có đơn hàng được ghép tới phòng/bàn: '+_getTableName(j.totable))
                        
                        //xóa đơn tocodes
                        for(var i in orders){
                            for(var k in j.tocodes){
                                if(orders[i].code==j.tocodes[k]){
                                    orders.splice(i,1);
                                    break;
                                }
                            }
                        }
                         
                        //xóa đơn fromcode
                        var tablesFromcode = ''
                        for(var i in orders){
                            if(orders[i].code==j.fromcode){
                                tablesFromcode = orders[i].table
                                orders.splice(i,1);                                        
                                break;
                            }
                        }
                        tablesFromcode = (tablesFromcode+'').split(',') 
                        
                        j.invoice.products=_addInfoToProducts(j.invoice)
                        //add invoice                                 
                        orders.push(j.invoice)
                         
                        saveOrders()
                        
                        //nếu người này đang xem đơn fromcode hoặc 1 trong các đơn tocodes
                        if(j.fromcode==ct3 || j.tocodes.indexOf(ct3)>=0){     
                            dochange = 1 //fixed on 02/09/2020
                            var co = findOrdersByTable(ct2)  
                            addTabs(co)
                        }else
                        //nếu người này đang ở 1 trong các bàn tablesFromcode hoặc
                        //nếu người này đang bàn totable                                                                   
                        if( tablesFromcode.indexOf(ct2)>=0 || j.totable==ct2 ){
                            dochange = 1 //fixed on 02/09/2020
                            var co = findOrdersByTable(ct2)  
                            addTabs(co)                                         
                        }                                                              
                    }
                break;
                
                case 'close':
                /*
            job: 'close',
            table: ob_table,
            id: socket.id,
            code: ii
                */
                    if(socket.id!=j.id){//khác bản thân người gửi socket
                        toastr.info('Đơn hàng '+j.code+' - phòng/bàn: '+_getTableName(j.table)+' bị hủy')
                        
                        //xóa nó (code) ra khỏi orders
                        for(var ii in orders){
                            if(orders[ii].code==j.code){
                                orders.splice(ii,1)
                                break;
                            }
                        }                        
                        saveOrders()
                        
                        //nếu người này đang xem đơn code này
                        if(ct3==j.code){
                            dochange = 1 //fixed on 02/09/2020
                            var co = findOrdersByTable(ct2)  
                            addTabs(co)
                        }else
                        //nếu người này đang ở bàn này
                        if(_checkIn(j.table,ct2)){//if(ct2==j.table){
                            var co = findOrdersByTable(ct2)  
                            addTabs(co)
                        } 
                    }
                break;
                
                case "changetable":
                    /*
                    job: 'changetable',   
                    id: socket.id,
                    table: _data.oldtable,//ob.table+'',
                    data: ob,
                    isnew: 1,
                    code: _data.code,
                    newtable: _data.newtable
                    */
                    if(socket.id!=j.id){//khác bản thân người gửi socket
                        toastr.info('Đơn hàng '+j.code+' - phòng/bàn: '+_getTableName(j.table)+' được chuyển sang phòng/bàn '+_getTableName(j.newtable))
                                
                        if(j.isnew){
                            //trước khi add thì phải add 1 số thông tin về products đã
                            j.data.products=_addInfoToProducts(j.data)
                             
                            //add to orders
                            orders.push(j.data)     
                            toastr.info('Có đơn hàng mới từ bàn: '+_getTableName(j.newtable))     
                            
                            //nếu người này dang xem bàn này
                            if(_checkIn(j.newtable,ct2)){ //if(j.table==ct2){     
                                dochange = 1 //fixed on 02/09/2020
                                var co = findOrdersByTable(ct2)  
                                addTabs(co)
                            }                          
                        }else{
                            //update
                            var uu = loadObject(j.data.code,orders,'code')
                             
                            //add 1 số thông tin về products đã
                            j.data.products=_addInfoToProducts(j.data)
                            
                            uu = $.extend(uu,j.data)
                            if(uu.status==-2) uu.status = -1;
                            //console.log('uu2:',JSON.stringify(uu))
                            
                            toastr.info('Có sự thay đổi phòng/bàn từ hóa đơn: '+j.data.code)
                            
                            //nếu người này đang xem đơn này
                            if(j.data.code==ct3){   
                                dochange = 1 //fixed on 02/09/2020
                                var co = findOrdersByTable(ct2)  
                                addTabs(co)
                            }else
                            //nếu người này đang ở bàn newtable hoặc bàn table
                            if(_checkIn(j.newtable,ct2)||_checkIn(j.table,ct2)){ 
                                dochange = 1 //fixed on 02/09/2020
                                var co = findOrdersByTable(ct2)  
                                addTabs(co)
                            }    
                        }
                        saveOrders()
                    
                    }
                break;
            }
        }catch(e){
            console.log(e)
        }
    });
}
//03/19/2020
else if(cfs.business && cfs.SellAllowOrder){ 
     
    _doSocket()
     
    socket.on('emit', function ( data) {
        try{
            var j = JSON.parse(data)
            console.log(j)
            switch(j.job){                                  
                case "notiorder":                
                    toastr.info('Có đơn đặt hàng mới')                     
                    //tải lại danh sách cus
                    loadJsonCustomers()
                    //tải lại danh sách order
                    _poi(function(){
                        if($('[kendo-window="orderWindow"]').is(':visible')){
                            _g2()
                        } 
                    })
                    beep(1)                       
                break;                  
            }
        }catch(e){
            console.log(e)
        }
    });
}

var ctg = cache_tables.getItem("ctg");//current table group
var ccat= cache_products.getItem("ccat");//current product category
var ccg = cache_customers.getItem("ccg");//current customer group

var cod = cache_orders.getItem("cod");//current order code
                        
var viewType  = 'table'
var productListTmpl , tableListTmpl , catListTmpl, productInvoiceTmpl, productItemTempl,
    tableListTmpl2, tableListTmpl3 
$.fn.v = function(val){
    
    if(val==undefined && !this.is('[value]') && this.is('[ng-value]')){
        return this.attr('ng-value')
    }    
    //console.log(this)
    if(this.is('input,select,textarea')){
        if(val==undefined)
            return this.val();
        else
            this.val(val);
    }else{
        if(val==undefined)
            return this.is('[value]')?this.attr('value'):(this.is('autocomplete')?'':this.html());
        else
            //this.html(val);
            this.is('[value]')?this.attr('value',val):(this.is('autocomplete')?this.attr('ng-value',val):this.html(val));
    }
}
        
function ts(){ console.log('ts ts ts')
    if(!(users && user_roles && branches )){
        setTimeout(function(){ts()},500)
        return
    }
    vkl = st(); console.log('hthththth22222')
    
    if(vkl['3.1.2']==undefined){
        document.location.href = '/'
        return
    }
}

ts()
        
function loadBranch(pid){
    /*
    for(var i in branches){
       if(branches[i].id == pid) return branches[i]; 
    };
    return false;
    */
    return loadObject(pid, branches);
}

function loadUser(pid){
    /*
    for(var i in users){
       if(users[i].id == pid) return users[i]; 
    };
    return false;
    */
    return loadObject(pid, users);
}

function loadTable(pid){
    return loadObject(pid, tables);
}
 
function loadProductCategory(pid){
    return loadObject(pid, product_categories);
}
 
function loadTableGroup(pid){
    return loadObject(pid, table_groups);
}

function loadCustomerGroup(pid){
    return loadObject(pid, customer_groups);
}
        
        
function loadJsonProductCategories(lo){
    $.getJSON( "ajax.php?action=productcategories", function( data ) {
          product_categories = data;  try{ 
          cache_products.setItem("categories",data,{expirationAbsolute: null,
                         expirationSliding: 3600,
                         priority: Cache.Priority.HIGH,
                         callback: function(k, v) { console.log('Cache removed: ' + k); }
                        });   }catch(e){console.log('Error save')}
           if(lo!=undefined) lo.call();
        });
} 
        
function saveCacheProductCategories(){  try{ 
    cache_products.setItem("categories",product_categories,{expirationAbsolute: null,
     expirationSliding: 3600,
     priority: Cache.Priority.HIGH,
     callback: function(k, v) { console.log('Cache removed: ' + k); }
    });  }catch(e){console.log('Error save')}
}

function IntervalProductCategories(){
    var vv = cache_products.getItem("categories")
    if(!vv){
        if(product_categories){
            saveCacheProductCategories()
        }else{
            loadJsonProductCategories()
        }
    }  
}
        
if(!product_categories) loadJsonProductCategories()
setInterval(IntervalProductCategories,60000); //1phut 1
 
//if(!products)
//    loadJsonProducts()
setInterval(function(){loadJsonProducts()},3600000)    
        
        function loadJsonUsers(lo){
            $.getJSON( "ajax.php?action=users", function( data ) {
                  users = data;  try{ 
                  cache_users.setItem("users",data,{expirationAbsolute: null,
                                 expirationSliding: 3600,
                                 priority: Cache.Priority.HIGH,
                                 callback: function(k, v) { console.log('Cache removed: ' + k); }
                                });   }catch(e){console.log('Error save')}
                   if(lo!=undefined) lo.call();
                });
        } 
        
        function loadJsonTableGroups(lo){
            if(site_type==1){
                if(cb==null) //login
                return;
            
                $.getJSON( "ajax.php?action=tablegroups&branch="+cb, function( data ) {
                  table_groups = data;   try{ 
                  cache_tables.setItem("table_groups",data,{expirationAbsolute: null,
                                 expirationSliding: 3600,
                                 priority: Cache.Priority.HIGH,
                                 callback: function(k, v) { console.log('Cache removed: ' + k); }
                                });    }catch(e){console.log('Error save')}
                   if(lo!=undefined) lo.call();
                });
                
            }
        }  
        
        loadJsonBranches(function(){
            var oq = loadBranch(cb).name
            setTitle(oq+' - Bán hàng')
            $('.switch-branch').attr('title',oq)
        })
        setInterval(function(){loadJsonBranches()},3600000)
        
        function loadJsonInvoices(lo){
            console.log('loadJsonInvoices');
            if(cb==null){
                setTimeout(function(){loadJsonInvoices(lo)},500)
                return false
            }
            $.getJSON( "ajax.php?action=invoices&branch="+cb, function( data ) {
                  invoices = data;
                  saveCacheInvoices(data)
                   if(lo!=undefined) lo.call();
                });
        } 
        
        function saveCacheInvoices(data){  try{ 
            cache_invoices.setItem("invoices",data,{expirationAbsolute: null,
             expirationSliding: 3600,
             priority: Cache.Priority.HIGH,
             callback: function(k, v) { console.log('Cache removed: ' + k); }
            });  }catch(e){console.log('Error save')}
        }
        
        function saveCacheOfflines(data){ try{ 
            cache_offlines.setItem("offlines"+cb,data,{expirationAbsolute: null,
             expirationSliding: 7*24*3600,
             priority: Cache.Priority.HIGH,
             callback: function(k, v) { console.log('Cache removed: ' + k); }
            });  }catch(e){console.log('Error save')}
        }
        
        function convertDate(d){
            //moment("03/03/2019 15:31",'DD/MM/YYYY HH:mm').format('YYYY-MM-DD HH:mm:ss')
            return moment(d,'DD/MM/YYYY HH:mm').format('YYYY-MM-DD HH:mm:ss')
        }
        
        function reconvertDate(d){             
            return moment(d,'YYYY-MM-DD HH:mm:ss').format('DD/MM/YYYY HH:mm')
        }
        
        function to3digits(n){
            n = (n-0).toFixed(3)
            return checknum(n,3,0)
        }
        
        function convertOrderToInvoice(ob){
            /*
            {
                "order":"41",
                "product":"23",
                "price":"50000",
                "quantity":"1",
                "discount":"0",
                "note":"",
                "deliveryqty":"0",
                "processingqty":"1"
            }
            
            ob.id = d.order_id
            */
            var ret = {products:[],create: create}
            for(var i in ob){
                if(i=='products'){
                    
                    for(var j in ob[i]){
                        var mmm = {
                            "order":ob.id,
                            "product":ob[i][j].id,
                            "price":ob[i][j].price,
                            "quantity":ob[i][j].quantity,
                            "discount":ob[i][j].discount,
                            "note":ob[i][j].note,
                            "deliveryqty":ob[i][j].deliveryqty,
                            "processingqty":ob[i][j].processingqty?ob[i][j].processingqty:0
                        }
                        //04/04/2019
                        if(ob[i][j].time==1){
                            mmm.t1 = ob[i][j].t1
                            mmm.t2 = ob[i][j].t2
                            mmm.run = ob[i][j].run
                        } //end //04/04/2019
                        
                        //04/11/2019: topping
                        if(ob[i][j].top){
                            mmm.topping = JSON.stringify(ob[i][j].top)                             
                        }
                        
                        ret.products.push(mmm)
                    }
                    
                    continue;
                } else if(i=='date'){
                    ret[i] = convertDate(ob[i])
                    continue;
                }
                if(i!='invoices') //add 06/28/2019 tạo invoice từ bookDone
                ret[i] = ob[i]
            }
            if(ret['customer']==undefined) ret['customer']=0;
            ret['type'] = 1; //add 06/28/2019 tạo invoice từ bookDone
            return ret;
        }
        
        function loadHash(){ console.log('loadHash')
            //https://xxx.xxx.xxx/sale.php#/?invoice=1727723 ->
            //https://xxx.xxx.xxx/sale.php#/Invoices/1727723 
            if(document.location.hash){
                var _ff = document.location.hash.match(/\#(\/[^\/]+)(?:\/(\d+))?/)
                if(_ff[1]=='/Returns'){
                    //
                    if(_ff[2]){
                        loadInvoiceForReturn(parseInt(_ff[2]))
                    }else{
                        loadQuickReturn()
                    }
                }else
                if(_ff[1]=='/Invoices'){
                    if(_ff[2]){
                        loadInvoiceForCopy(parseInt(_ff[2]))
                    }
                }else
                if(_ff[1]=='/Orders'){ //alert(_ff[2])
                    if(_ff[2]){
                        loadBook(parseInt(_ff[2]))
                    }
                }
            }
        }
     
     function loadInvoiceForCopy(id){
            
            if(invoices==null){
                setTimeout(function(){
                    loadInvoiceForCopy(id)
                },500)
                return;
            }
            
            var iv = loadObject(id,invoices)
                        
            if(!iv) return;
            
            iv = $.extend({},iv)
            _convertProducts(iv,1)
            
            //alert(iv)
            if(site_type==1){
            
            ct2 = _getOneTable(iv.table)  //parseInt(iv.table);
            localStorage.ct2 = ct2;
            
            
            cp1=0;
            //chuyen tab phong ban
             
            $('[ng-click="showTables()"]').click()
             
            var ct2table = loadTable(ct2)
            
            $('.proTabsBox li[data-id="'+ct2table.group+'"]').click()
            
            swipePrev();
            
            var lm = 1000;
            while($('kv-swiper li[data-id="'+ct2+'"]').length==0 && lm-- > 0){
                swipeNext()
            }
            
            }
            
            var co = findOrdersByTable(ct2) 
            create_new_invoice(co,'DH',{                 
                customer: iv.customer ,
                user: iv.user ,
                fee: iv.fee ,
                invoice: iv.invoice ,
                addtoaccount: iv.addtoaccount,
                status: 0 ,
                discount: iv.discount ,
                paying: iv.paying 
            })  
            dochange = 1 //fixed on 02/09/2020
            addTabs(co)
              
            for(var i in iv.products){
                var tl = $.extend({},iv.products[i])
                delete tl.maxquantity
                delete tl.deliveryqty
                delete tl.opid
                console.log('tl:',tl)
                addProductInvoice(loadProduct(iv.products[i].product), tl,void 0, !0)        
            }
             
            document.location.hash = ''
        }
        
        
        
        function loadInvoiceForReturn(id){
            
            var iv = loadObject(id,invoices)
            
            if(!iv) return;
            iv = $.extend({},iv)
            _convertProducts(iv,1)
            
            //alert(iv)
            
            if(site_type==1){
            
            ct2 = _getOneTable(iv.table) //parseInt(iv.table);
            localStorage.ct2 = ct2;
            
            
            cp1=0;
            //chuyen tab phong ban
            //ng-click="showTables()"
            //ng-click="showProductMenu()"
            
            $('[ng-click="showTables()"]').click()
             
            var ct2table = loadTable(ct2)
            
            $('.proTabsBox li[data-id="'+ct2table.group+'"]').click()
            
            swipePrev();
            
            var lm = 1000;
            while($('kv-swiper li[data-id="'+ct2+'"]').length==0 && lm-- > 0){
                swipeNext()
            }
            
            }
            
            var co = findOrdersByTable(ct2) 
            create_new_invoice(co,'TH',{
                invoice:id,
                customer: iv.customer, 
                '_discount':iv.discount,
                '_price': iv.price
            })  
            dochange = 1 //fixed on 02/09/2020
            addTabs(co)
              
            for(var i in iv.products){
                var tl = $.extend({},iv.products[i])
                delete tl.opid;
                tl.maxquantity = tl.quantity;
                tl.quantity = 1;
                tl.price = priceafterdiscount.call(tl)
                tl.discount = 0; console.log('tl:',tl)
                addProductInvoice(loadProduct(iv.products[i].product), tl,void 0, !0)        
            }
             
            document.location.hash = ''
        }
        
        
        function loadQuickReturn(){
            
            var co = findOrdersByTable(ct2) 
            create_new_invoice(co,'TH')  
            
            addTabs(co)
            
            document.location.hash = ''
        }
        
        window.onhashchange = loadHash
        
        
        
        function fu(data,lo){
                
                if(products==null){
                    setTimeout(function(){fu(data,lo)},500)
                    return false
                }
                
              //orders = data;
              
              if(orders && orders.length){
                 if(data && data.length){
                    //truong hop 1: co trong ca 2 orders va data
                    for(var i in orders){
                        for(var j in data){
                            
                            if(data[j].code==orders[i].code){ // if(data[j].opid==orders[i].opid){  
                                if(orders[i].user==create){  //sai: data[j].user==orders[i].user
                                    if(orders[i].id==undefined){
                                        orders[i].id=data[j].id
                                        //saveOrders()
                                    } 
                                    //console.log(orders[i].products,data[j].products)
                                    //ve products
                                    for(var i2 in orders[i].products){
                                        for(var j2 in data[j].products){
                                            data[j].products[j2].id = data[j].products[j2].product
                                            if(data[j].products[j2].product==orders[i].products[i2].id){ 
                                                /*var doneqty = orders[i].products[i2].quantity-orders[i].products[i2].deliveryqty-orders[i].products[i2].processingqty
                                                var doneqty2 = data[j].products[j2].quantity-data[j].products[j2].deliveryqty-data[j].products[j2].processingqty
                                                doneqty = Math.max(doneqty,doneqty2)
                                                orders[i].products[i2].deliveryqty = data[j].products[j2].deliveryqty
                                                orders[i].products[i2].processingqty = Math.max(0,orders[i].products[i2].quantity-orders[i].products[i2].deliveryqty-doneqty)
                                                */
                                                var $x1 = data[j].products[j2].quantity; //quantity
                                                var $x2 = Math.min(orders[i].products[i2].deliveryqty,$x1);//deli
                                                var $x3 = orders[i].products[i2].quantity-orders[i].products[i2].deliveryqty-orders[i].products[i2].processingqty ;//done
                                                $x3 = Math.min($x3,$x1);
                                                $x3 = Math.max($x3,0);
                                                var $x4 = $x1-$x2-$x3 ;//processingqty
                                                $x4 = Math.max($x4,0); 
                                                
                                                data[j].products[j2].quantity = $x1;
                                                data[j].products[j2].deliveryqty = $x2;
                                                data[j].products[j2].processingqty = $x4;
                                                break;
                                            }
                                        }
                                    }
                                }else{
                                    //bo sung product name tu products
                                    for(var i3 in data[j].products){
                                        var ck = loadProduct(data[j].products[i3].product)
                                        /*data[j].products[i3].name = ck.name
                                        data[j].products[i3].uname = ck.uname
                                        data[j].products[i3].id = ck.id//product
                                        */
                                        data[j].products[i3] = $.extend({},ck,data[j].products[i3])
                                    }
                            
                                    orders[i] = $.extend({},data[j])
                                }
                                break;
                            }
                            
                        }
                    }
                    //truong hop 2: co trong old(orders) ma k co trong new(data)
                    for(var i in orders){
                        var check = true
                        for(var j in data){
                            if(data[j].code==orders[i].code){//if(data[j].opid==orders[i].opid){
                                check = false
                                break;   
                            }
                        }
                        if(check){
                            if(check.id!=undefined){
                                orders.splice(i,1)
                                //saveOrders()
                            }
                                
                        }
                    }
                    //truong hop 3: co trong new(data) ma k co trong old(orders)
                    for(var j in data){
                        var check = true
                        for(var i in orders){
                            if(data[j].code==orders[i].code){//if(data[j].opid==orders[i].opid){
                                check = false
                                break;   
                            }
                        }
                        if(check){
                            //bo sung product name tu products
                            for(var i3 in data[j].products){
                                var ck = loadProduct(data[j].products[i3].product)
                                /*data[j].products[i3].name = ck.name
                                data[j].products[i3].uname = ck.uname
                                data[j].products[i3].id = ck.id//product
                                */
                                data[j].products[i3] = $.extend({},ck,data[j].products[i3])
                            }
                            
                            orders.push(data[j])
                            //saveOrders()
                        }
                    }
                 }else{
                    //truong hop 2
                    for(var i in orders){
                         
                        if(orders[i].id!=undefined){
                            orders.splice(i,1)
                            //saveOrders()
                        }                                
                         
                    }
                 }
              }else{
                //luc nay chi co truong hop 3
                
                //bo sung product name tu products
                
                for(var j in data){
                
                    for(var i3 in data[j].products){
                        var ck = $.extend({}, loadProduct(data[j].products[i3].product) )
                        data[j].products[i3].name = ck.name
                        data[j].products[i3].uname = ck.uname
                        data[j].products[i3].id = ck.id//product
                        
                        data[j].products[i3].time = ck.time
                        data[j].products[i3].type = ck.type
                        
                        if(data[j].products[i3].topping && data[j].products[i3].topping.slice(0,1)=='{'){
                            data[j].products[i3].top = JSON.parse(data[j].products[i3].topping)
                            data[j].products[i3].topping = ck.topping
                        }
                    }
                
                }
                
                orders = data;
                //saveOrders()
              }
              
              saveOrders()
              
               if(lo!=undefined) lo.call();
                
        }
        
        
        
        function loadJsonOrders(lo){
            console.log('loadJsonOrders');
            
            //phai dong bo du lieu moi (data) va du lieu cu orders
            
            //+voi nhung cai trung nhau (code)
            //bo sung id cua orders neu chua co id, products thi:
            //neu trung nhau
            //so sanh 4 thong so: orders[i].products[].quantity , data[j].products[].quantity,processingqty,deliveryqty
            //update cua orders processingqty,deliveryqty
            //luu y them:
            //neu ca 2 co .user trung nhau thi uu tien orders
            //neu user khac nhau thi lay nguyen data
            //+voi nhung cai cua cu(orders) ma khong co trong data thi:
            // -neu orders co id thi delete no trong orders luon
            // -neu orders k co id thi de nguyen (products de nguyen)
            //+voi nhung cai co trong data ma k co trong orders thi them vao orders 
            
            $.getJSON( "ajax.php?action=orders&branch="+cb, function(data){
                fu(data,lo)
            });
        }
        
        
        
        function tabstrip(){
            $('.tabstrip > ul >li').click(function(){
                var t = $(this);
                t2 = t.attr('aria-controls');
                
                t3 = t.parent().parent().find('#'+t2);
                
                //console.log(t3)
                
                t.parent().siblings().removeClass('k-state-active');
                t.parent().siblings().hide(); 
                t3.addClass('k-state-active');
                t3.show();
                
                t.parent().find('li').removeClass('k-state-active');
                t.parent().find('li').removeClass('k-tab-on-top');
                
                t.addClass('k-state-active');
                t.addClass('k-tab-on-top');
            })
        }
        
        
        
        function loadGroupForTable(){
            
            console.log('loadGroupForTable');
            
            if(table_groups){
                for(var i in tables){
                    if(tables[i].group!='0'){
                        tables[i]._group = loadTableGroup(tables[i].group);
                    }
                }
                savetables();
            }
        }
        
        /*function savecb(){
            
            console.log('savecb');
            
            //ket hop reload tables va table_groups
            loadJsonTableGroups();
            loadJsonTables(loadGroupForTable);
            
            cache_branches.setItem("cb",cb,{expirationAbsolute: null,
             expirationSliding: 3600,
             priority: Cache.Priority.HIGH,
             callback: function(k, v) { console.log('Cache removed: ' + k); }
            });
        }*/
        
        function savectg(){
            if(site_type==1){
            console.log('savectg');
            try{  
            cache_tables.setItem("ctg",ctg,{expirationAbsolute: null,
             expirationSliding: 3600,
             priority: Cache.Priority.HIGH,
             callback: function(k, v) { console.log('Cache removed: ' + k); }
            });   }catch(e){console.log('Error save')}
            }
        }
        
        
        
        function saveccg(){
            
            console.log('saveccg');
            try{  
            cache_customers.setItem("ccg",ccg,{expirationAbsolute: null,
             expirationSliding: 3600,
             priority: Cache.Priority.HIGH,
             callback: function(k, v) { console.log('Cache removed: ' + k); }
            });   }catch(e){console.log('Error save')}
        }
        
        function saveccat(){
            
            console.log('saveccat');
            try{  
            cache_products.setItem("ccat",ctg,{expirationAbsolute: null,
             expirationSliding: 3600,
             priority: Cache.Priority.HIGH,
             callback: function(k, v) { console.log('Cache removed: ' + k); }
            });   }catch(e){console.log('Error save')}
        }
        
        
        function saveProducts(){
            
            console.log('saveProducts');
            try{  
            cache_products.setItem("products",products,{expirationAbsolute: null,
             expirationSliding: 3600,
             priority: Cache.Priority.HIGH,
             callback: function(k, v) { console.log('Cache removed: ' + k); }
            });   }catch(e){console.log('Error save')}
        }
        
        
        
        
        function saveOrders(){
            
            console.log('saveOrders');
            try{ 
            cache_orders.setItem("orders",orders,{expirationAbsolute: null,
             expirationSliding: 365*86400, //3600
             priority: Cache.Priority.HIGH,
             callback: function(k, v) { console.log('Cache removed: ' + k); }
            });
            }catch(e){console.log('Error save')}
        }
        
        function saveBooks(b,cb){
            
            console.log('saveBooks',b);
             
            db['Books']  .where('branch').equals(b).delete() .then(function(){ //'Books-'+b   clear()
                // *for(var i in books){
                //    _createBook(books[i],b)
                //}* /
                _createBooks(books,b,cb)
            })
        }
                     
        
        //////
        var litab1 = '<li ng-repeat="tableGroups" class="ng-scope" data-id="{{id}}"><a href="javascript:void(0)" ng-class="{active:(g.Id == groupFilter)}" ng-click="filterByGroup(g.Id)" class="{{class}}"><span class="ng-binding">{{name}}</span></a></li>'
        var ct1 = parseInt(localStorage.ct1 ? localStorage.ct1 : 0); //current table group
        var ct2 = parseInt(localStorage.ct2 ? localStorage.ct2 : 0); //current table
        var ct3 = localStorage.ct3 ? localStorage.ct3 : ''; //current invoice code
        var cp1 = 0; //current page
        var autoClick = false;
        
        
         
        //var ct1 = 0
        function loadTabs1(){
                        
            console.log('loadTabs1')
            
            if(table_groups==null || $('.proTabsBox ul').length==0){
                setTimeout(function(){loadTabs1()},500)
                return false
            }
            
            var t = {name:"Tất cả",id:'0','class':'active'}
            var li = litab1;
            for(var j in t){
                li = li.replace(new RegExp("\{\{"+j+"\}\}",'g'),t[j])
            }
            $('.proTabsBox ul').append(li)
            for(var i in table_groups){
                t = table_groups[i]
                li = litab1;
                for(var j in t){
                    li = li.replace(new RegExp("\{\{"+j+"\}\}",'g'),t[j])
                }
                $('.proTabsBox ul').append(li)
            }   
            
            console.log('loadTabs1 s1')
              
            tabstrip1()      
            
            //2019   
            $('.proTabsBox li[data-id]>a').removeClass('active')  
            $('.proTabsBox li[data-id="'+ct1+'"]>a').addClass('active')
                 
        } 
        
        function isBusy(a){
            //id or object
            if(a.toString && (a.toString()=="[object Object]")){
                a = a.id
            }else{
                //a = loadObject(a,tables)
            }
            var check = false, os = findOrdersByTable(a)
            if(os.length==0) check = false; //tu nhien k ok
            else{
                check = false;
                for(var j in os){
                    if(os[j].products.length>0){
                        check = true
                        break;
                    }
                }                            
            }
            return check
        }
        var ret = [],tongsudung=0,tongban=0
        function filterT(gr,sd){
            var ret = []
            tongsudung=0
            tongban=0
            for(var i in tables){
                if(gr==0 || tables[i].group == gr){ tongban++
                    var check = false, os = findOrdersByTable(tables[i].id)
                    if(sd=='')
                        ret.push(tables[i])
                    else if(sd=='0'){ //chua su dung, k co invoice nao co product
                        if(os.length==0) check = true; //tu nhien ok
                        else{
                            check = true;
                            for(var j in os){
                                if(os[j].products.length>0){
                                    check = false
                                    break;
                                }
                            }                            
                        }
                        if(check) ret.push(tables[i])
                    } else{ //Dang sd, ton tai 1 invoice co pro
                        if(os.length==0) check = false; //tu nhien k ok
                        else{
                            check = false;
                            for(var j in os){
                                if(os[j].products.length>0){
                                    check = true
                                    break;
                                }
                            }                            
                        }
                        if(check) ret.push(tables[i])
                    }
                    
                    
                    for(var j in os){
                        if(os[j].products.length>0){
                            tongsudung++
                            break;
                        }
                    } 
                }
            }
            return ret
        }
        
        
        function filterP(cat){
            var ret = []
            for(var i in products){
                if(products[i].sell=='1'){
                    if(cat){
                        if(products[i].category==cat){
                            ret.push(products[i])
                        }
                    }else{
                        ret.push(products[i])
                    }    
                }
                
            }
            return ret;
        }
        var templ1 = null; var templ2=null; 
        var templ3='<li tabindex="-1" role="option" unselectable="on" class="k-item k-state-selected ng-scope k-state-focused" data-offset-index="{{index}}">{{name}}</li>';
        var ce=null;//current editting table id
        var _customerItemTempl = ''
        var catx = '';
                        
        /**** jsbook ****/
                
        function saveOffline(ob,offf){
            offf = offf==undefined?0:offf
            if(!offf){
                if(!nprint){
                    if(PrintBarCode){                        
                        printTask = [function(){},function(){inhoadonOffline(ob)}]
                        _printBarCode(ob)
                    }else                        
                        inhoadonOffline(ob)
                }
                    
            }
            
            toastr.warning('Không có internet, hóa đơn được lưu Offline')
            offlines.push(ob);
            saveCacheOfflines(offlines)
            syncCount()
            
            //luu iv de phuc vu cho return
            
            if(!offf){
                //xoa orders va close tab
                for(var i in orders){
                    if(orders[i].code==ob.code){
                        orders.splice(i,1)
                        break;
                    }
                }
                saveOrders()  
            
                if($('.swiper-container li[data-id="'+ct2+'"] aside>a[ng-click="itemClicked(p)"]:first-child').length)
                    $('.swiper-container li[data-id="'+ct2+'"] aside>a[ng-click="itemClicked(p)"]:first-child').click()
                else
                    afterClick()
            }    
        }
        
        var defaultAction = 'merge';
        var tmplProductChange;
        var tmplInvoiceMerge;
        var tmplInvoiceChangeTable;
        
        function _atLeast(a){             
            for(var i in a){
                if(a[i][1]>0){
                    return true;
                }
            }
            return false;
        }
        
        function lala(ob){ console.log('lala')
        
            if(defaultAction=='merge'){
                $('[kendo-window="wdChangeTable"] [ng-hide="merge"]').addClass('ng-hide')
                $('[kendo-window="wdChangeTable"] [ng-show="merge"]').removeClass('ng-hide')
                
                //tim cac ban co hoa don khac ct3
                var tbs = {}
                for(var i in orders){
                    
                    if(orders[i].code!=ct3){
                        var tb = (orders[i].table+'').split(',')
                        for(var j in tb){
                            if(tbs[tb[j]]==undefined){
                                tbs[tb[j]] = loadTable(tb[j]).name;
                            }
                        }
                    }
                    /*if(orders[i].code!=ct3 && tbs[orders[i].table]==undefined)  
                        tbs[orders[i].table] = loadTable(orders[i].table).name;*/
                }
                //alert(tbs); 
                console.log(tbs)
                //tbs = Object.keys(tbs)
                $('[k-on-change="selectedTableChangedMerge()"]').select3({
                    sources:tbs,
                    selected:tbs[ct2]==undefined?0:ct2,
                    //empty:'Chọn phòng/bàn'
                })
                
                $('[k-on-change="selectedTableChangedMerge()"]').unbind('change')
                
                $('[k-on-change="selectedTableChangedMerge()"]').change(function(){
                    selectedTableChangedMerge.call(this)
                })
                
                selectedTableChangedMerge.call($('[k-on-change="selectedTableChangedMerge()"]')[0])
                
                $('[kendo-window="wdChangeTable"] [ng-click="takeActionAll()"]').unbind('click')
                $('[kendo-window="wdChangeTable"] [ng-click="takeActionAll()"]').click(function(){
                    if(Offline.state=='down'){
                        toastr.warning('Bạn không thể ghép đơn khi mất mạng')
                        return
                    }
                    $('kendo-grid-list.k-grid-blue input:checkbox').prop('checked',true)
                    $(this).hide()
                    $('[kendo-window="wdChangeTable"] [ng-click="takeAction()"]').click()
                })
                
                
                //$('kendo-grid-list table tbody tr td input:checked')
                $('[kendo-window="wdChangeTable"] [ng-click="takeAction()"]').unbind('click')
                $('[kendo-window="wdChangeTable"] [ng-click="takeAction()"]').click(function(){
                    if(Offline.state=='down'){
                        toastr.warning('Bạn không thể ghép đơn khi mất mạng')
                        return
                    }
                    var that = this;
                    $(this).hide()
                    var cc = $('kendo-grid-list.k-grid-blue table tbody tr td input:checked')
                    if(cc.length==0){
                        toastr.warning('Bạn phải chọn ít nhất 1 hóa đơn để ghép đến')
                        $(this).show()
                        $('[kendo-window="wdChangeTable"] [ng-click="takeActionAll()"]').show()
                        return;
                    }
                    var cc2 = []
                    cc.each(function(){
                        cc2.push($(this).parents('[data-code]').attr('data-code'))
                    })
                    //alert(cc2)
                     
                    //mergeInvoice fromcode tocodes totable
                    var _data = {
                        fromcode: ct3,
                        tocodes: cc2,
                        totable: $('[k-on-change="selectedTableChangedMerge()"]').val()
                    }
                    
                    //toastr.info('Chức năng đang phát triển');
                    //return;
                    
                    $.ajax({
                        url: '/ajax.php?action=mergeInvoice',
                        type: 'POST',
                        global: !0,
                        dataType: 'json',
                        data:_data,
                        success: function(data){
                            /*
        'error'=>'',
        'invoice'=>$invoices[0],
        'fromcode'=>$fromcode,
        'tocodes'=>$tocodes,
        'totable'=>$totable                            
                            */
                            if(data.error!=undefined && data.error!=''){
                                toastr.warning('Có lỗi xảy ra: '+data.error)
                                $('[kendo-window="wdChangeTable"] [ng-click="takeActionAll()"]').show()
                                $(that).show()
                            }  else {
                                toastr.info('Đang xử lý ghép đơn')
                                
                                //socket, gửi đến bếp và các sale khác
                                //socket notification to kitchen
                                socket.emit('send',JSON.stringify({
                                    job: 'mergeInvoice',
                                    id: socket.id
                                }),data_user.kitchen)
                                
                                //send socket noti for sale (include me)
                                socket.emit('send',JSON.stringify({
                                    job: 'mergeInvoice',     
                                    id: socket.id,                                     
                                    'invoice': data.invoice,
                                    'fromcode': data.fromcode,
                                    'tocodes': data.tocodes,
                                    'totable': data.totable
                                }),socket.room)
                                
                                //xóa đơn fromcode
                                for(var i in orders){
                                    if(orders[i].code==data.fromcode){
                                        orders.splice(i,1);
                                        break;
                                    }
                                }
                                  
                                //xóa các đơn tocodes luôn
                                for(var i in orders){
                                    for(var j in data.tocodes){                                        
                                        if(orders[i].code==data.tocodes[j]){
                                            orders.splice(i,1);
                                            break;
                                        }
                                    }
                                }
                                 
                                //add invoice     
                                data.invoice.products=_addInfoToProducts(data.invoice)                            
                                orders.push(data.invoice)
                                
                                saveOrders()
                                
                                //chuyển ct3 thành data.invoice.code
                                ct3 = data.invoice.code
                                localStorage.ct3 = ct3;
                                 
                                //nhảy sang tab mới (cũng có thể là tab hiện tại): data.totable
                                if(ct2!=data.totable){
                                    ct2 = parseInt(data.totable);
                                    localStorage.ct2 = ct2;
                                    //chuyen tab phong ban
                                    cp1=0;
                                     
                                    $('[ng-click="showTables()"]').click()
                                     
                                    var ct2table = loadTable(ct2)
                                    
                                    $('.proTabsBox li[data-id="'+ct2table.group+'"]').click()
                                    
                                    swipePrev();
                                    
                                    var lm = 1000;
                                    while($('kv-swiper li[data-id="'+ct2+'"]').length==0 && lm-- > 0){
                                        swipeNext()
                                    }
                                }
                                else{
                                    dochange = 1 //fixed on 02/09/2020
                                    var co = findOrdersByTable(ct2)  
                                     
                                    addTabs(co)
                                }
                                
                                toastr.info('Đã xử lý xong ghép đơn')
                                
                                $('[kendo-window="wdChangeTable"]').parent().hide();
                                $('.k-overlay').hide()                                 
                            }                                                         
                        },
                        error: function(){
                            toastr.warning('Có lỗi xảy ra')
                            $('[kendo-window="wdChangeTable"] [ng-click="takeActionAll()"]').show()
                            $(that).show()
                        }
                    })                                        
                })                
            }else{
                $('[kendo-window="wdChangeTable"] [ng-hide="merge"]').removeClass('ng-hide')
                $('[kendo-window="wdChangeTable"] [ng-show="merge"]').addClass('ng-hide')
                
                //tat ca cac ban
                var tbs = {}
                for(var i in tables){                     
                    tbs[tables[i].id] = tables[i].name;
                }
                
                $('[k-on-change="selectedTableChangedChange()"]').select3({
                    sources:tbs,
                    selected:ct2,
                    //empty:'Chọn phòng/bàn'
                })
                
                $('[k-on-change="selectedInvoiceChangedChange()"]').unbind('change')
                $('[k-on-change="selectedTableChangedChange()"]').unbind('change')
                
                $('[k-on-change="selectedInvoiceChangedChange()"]').change(selectedInvoiceChangedChange)
                $('[k-on-change="selectedTableChangedChange()"]').change(selectedTableChangedChange)
                
                selectedTableChangedChange.call($('[k-on-change="selectedTableChangedChange()"]')[0])
                
                if(tmplProductChange==undefined){
                    tmplProductChange = $('kv-cashier-cart-split .product-groups>div')[0].outerHTML
                }
                $('kv-cashier-cart-split .product-groups>div').remove()
                
                console.log('lala:',typeof ob)
                
                for(var i in ob.products){
                    var tmplProductChange2 = $(tmplProductChange)
                    
                    tmplProductChange2
                        .attr('data-index',i)
                        .attr('data-quantity',ob.products[i].quantity)
                    tmplProductChange2.find('.cell-order').html(i-(-1))
                    tmplProductChange2.find('h4').html(ob.products[i].name)
                    tmplProductChange2.find('.cell-change-price').html(ob.products[i].quantity)
                    
                    if(ob.products[i].time==1){
                        tmplProductChange2.find('input').attr('readonly','readonly')
                    }else
                         
                    tmplProductChange2.find('input').change(function(e){
                        var v= $(this).val()
                        var tmplProductChange2 = $(this).parents('div.row-list[data-index]')
                        if(v!='' && !v.match(/^[0-9\.]+$/)){
                            toastr.warning('Sai định dạng số')
                            $(this).val('')
                            
                            tmplProductChange2.find('.cell-change-price')
                                .html(tmplProductChange2.attr('data-quantity'))
                            return
                        }
                        
                        //so sanh voi hien tai
                        if(v>tmplProductChange2.attr('data-quantity')){
                            v = tmplProductChange2.attr('data-quantity')
                            $(this).val(v)
                        }
                        
                        tmplProductChange2.find('.cell-change-price')
                            .html(checknum(tmplProductChange2.attr('data-quantity')-v,3))
                        
                    })
                    
                    tmplProductChange2.find('.down').click(function(e){
                        var tmplProductChange2 = $(this).parents('div.row-list[data-index]')
                        var input= $(this).find('~input'); 
                        var v=input.val(); v= v==''?0:v;
                        var qq = tmplProductChange2.attr('data-index')
                        qq = ob.products[qq]
                        if(qq.time==1){
                            input.val('').select()
                            tmplProductChange2.find('.cell-change-price')
                                    .html(tmplProductChange2.attr('data-quantity'))
                        }else{
                            if(v>=1){
                                input.val(v-1).select()
                                tmplProductChange2.find('.cell-change-price')
                                    .html(checknum(tmplProductChange2.find('.cell-change-price').html()-(-1),3))
                            }else input.focus()
                        }
                    })
                    
                    tmplProductChange2.find('.up').click(function(e){
                        var tmplProductChange2 = $(this).parents('div.row-list[data-index]')
                        var input= $(this).prev() 
                        var v=input.val(); v= v==''?0:v;
                        var qq = tmplProductChange2.attr('data-index')
                        qq = ob.products[qq]
                        if(qq.time==1){
                            input.val(tmplProductChange2.attr('data-quantity')).select()
                            tmplProductChange2.find('.cell-change-price')
                                    .html('0')
                        }else{
                            if(v<=tmplProductChange2.attr('data-quantity')-1){
                                input.val(v-(-1)).select()
                                tmplProductChange2.find('.cell-change-price')
                                    .html(checknum(tmplProductChange2.find('.cell-change-price').html()-1,3))
                            }else input.focus()
                        }
                    })
                    
                    if(ob.products[i].top && JSON.stringify(ob.products[i].top)!='{}'){ //alert('z')
                        var topping = []
                        for(var m in ob.products[i].top){
                            var m2 = ob.products[i].top[m];
                            var m3 = loadProduct(m)
                            topping.push('+'+m2[0]+' '+m3.name)
                        }
                        tmplProductChange2.find('.list-topping').html(topping.join('&nbsp;,&nbsp;'))
                    }
                     
                    $('kv-cashier-cart-split .product-groups').append(tmplProductChange2)
                }
                
                $('[kendo-window="wdChangeTable"] [ng-click="takeAction()"]').unbind('click')
                $('[kendo-window="wdChangeTable"] [ng-click="takeAction()"]').click(function(){ 
                    if(Offline.state=='down'){
                        toastr.warning('Bạn không thể ghép đơn khi mất mạng')
                        return
                    }
                    var that = this;
                    $(this).hide()
                    var cc = $('kv-cashier-cart-split input.form-control-custom');
                    var cc3 = [];
                    var cc5 = [];
                    if(cc.length>0){
                        cc.each(function(){
                            var cc2 = $(this).parents('.row-list[data-index]').attr('data-index')
                            var cc4 = $(this).val()
                            //if(cc4>0){
                                //cc3.push([ob.products[cc2].opid,cc4])
                            //}
                            var oop = ob.products[cc2].opid
                            cc3.push([oop,cc4>0?cc4:0])
                            var cc6 = $(this).parent().prev().html()
                            cc5.push([oop,cc6>0?cc6:0])
                        })
                    }
                    //alert('1')
                    //alert(cc3)
                    //alert(cc5)
                    
                    if(!_atLeast(cc3)){//if(cc3.length==0){
                        toastr.warning('Phải tách ra ít nhất 1 sản phẩm')
                        $(this).show()
                        return
                    }
                    
                    //phải để lại ít nhất 1 sản phẩm ở đơn gốc
                    if(!_atLeast(cc5)){//if(cc3.length==0){
                        toastr.warning('Phải để lại ít nhất 1 sản phẩm ở đơn gốc')
                        $(this).show()
                        return
                    }
                     
                    //changeInvoice fromcode tocode totable [{opid,quantity}]
                    var _data = {
                        fromcode: ct3,
                        tocode:  $('[k-on-change="selectedInvoiceChangedChange()"]').val(),
                        totable: $('[k-on-change="selectedTableChangedChange()"]').val(),
                        products: cc3
                    }
                    
                    //toastr.info('Chức năng đang phát triển');
                    //return;
                    
                    $.ajax({
                        url: '/ajax.php?action=changeInvoice',
                        type: 'POST',
                        global: !0,
                        dataType: 'json',
                        data:_data,
                        success: function(data){
/*
        'error'=>'',
        'invoices'=>$invoices,
        'fromcode'=>$fromcode,
        'tocode'=>$tocode,
        'totable'=>$totable
*/                          
                            if(data.error!=undefined && data.error!=''){
                                toastr.warning('Có lỗi xảy ra: '+data.error)
                                $(that).show()
                            }  else {
                                toastr.info('Đang xử lý tách đơn')
                                
                                //socket, gửi đến bếp và các sale khác
                                //socket notification to kitchen
                                socket.emit('send',JSON.stringify({
                                    job: 'changeInvoice',
                                    id: socket.id
                                }),data_user.kitchen)
                                
                                //send socket noti for sale (include me)
                                socket.emit('send',JSON.stringify({
                                    job: 'changeInvoice',     
                                    id: socket.id,                                     
                                    'invoices': data.invoices,
                                    'fromcode': data.fromcode,
                                    'tocode': data.tocode,
                                    'totable': data.totable
                                }),socket.room)
                                
                                var tocode =  $('[k-on-change="selectedInvoiceChangedChange()"]').val();
                                
                                console.log('tocode:',tocode)
                                
                                if(tocode){
                                    //xóa đơn này luôn
                                    for(var i in orders){
                                        if(orders[i].code==tocode){
                                            orders.splice(i,1);
                                            break;
                                        }
                                    }
                                }
                                //xóa đơn fromcode
                                for(var i in orders){
                                    if(orders[i].code==data.fromcode){
                                        orders.splice(i,1);
                                        break;
                                    }
                                }
                                //add fromcode và tocode
                                for(var i in data.invoices){
                                    data.invoices[i].products=_addInfoToProducts(data.invoices[i])
                                    orders.push(data.invoices[i])
                                }
                                saveOrders()
                                console.log('tocode saveOrders:')
                                
                                //chuyển ct3 thành data.tocode
                                ct3 = data.tocode
                                localStorage.ct3 = ct3;
                                 
                                //nhảy sang tab mới (cũng có thể là tab hiện tại): data.totable
                                if(ct2!=data.totable){ console.log('tocode data:',ct2,data.totable,ct3)
                                    ct2 = parseInt(data.totable);
                                    localStorage.ct2 = ct2; console.log('tocode data x:',ct2,data.totable,ct3)
                                    //chuyen tab phong ban
                                    cp1=0;
                                     
                                    $('[ng-click="showTables()"]').click()
                                     
                                    var ct2table = loadTable(ct2); console.log('tocode data loadTable:',ct2table)
                                    
                                    $('.proTabsBox li[data-id="'+ct2table.group+'"]').click()
                                    
                                    swipePrev();
                                    
                                    var lm = 1000;
                                    while($('kv-swiper li[data-id="'+ct2+'"]').length==0 && lm-- > 0){
                                        swipeNext()
                                    }
                                }
                                else{ console.log('tocode data 2:',ct2,data.totable,ct3)
                                    dochange = 1 //fixed on 02/09/2020
                                    var co = findOrdersByTable(ct2)  
                                     
                                    addTabs(co)
                                }
                                
                                toastr.info('Tách đơn thành công')
                                
                                $('[kendo-window="wdChangeTable"]').parent().hide();
                                $('.k-overlay').hide()
                            }
                            
                        },
                        error: function(){
                            toastr.warning('Có lỗi xảy ra')
                            $(that).show()
                        }
                    })                                                             
                })
            }             
        }         
        //load cac don cua ban (ct2) khac ct3
        function _loadInvoices(_ct2){
            if(_ct2==undefined) _ct2 = ct2;
            var _k = findOrdersByTable(_ct2)
            return _k.filter(function(v){
                //khong tach/ghep voi tra hang
                return v.code!=ct3 &&v.type!=11
            })
        }
        
        function selectedInvoiceChangedChange(){
            
        }
        function selectedTableChangedChange(){
            var _ct2 = $(this).val()
            //tim cac don khong trong cua ban            
            var tbs2 = _loadInvoices(_ct2)
            
            //chi dung cac don co opid
            tbs2 = tbs2.filter(function(vv){
                return _checkAllProducts(vv)
            })
            var tbs4 = {}
            for(var i in tbs2){
                tbs4[tbs2[i].code] = tbs2[i].code
            }
            //var tbs3 = Object.keys(tbs4)[0]
            
            $('[k-on-change="selectedInvoiceChangedChange()"]').select3('destroy')
            
            $('[k-on-change="selectedInvoiceChangedChange()"]').select3({
                sources:tbs4,
                //selected: tbs3,
                empty:'Tạo đơn mới',
                emptyValue: ''
            })
        }
        
        function tableChanged(){
            if(tmplInvoiceChangeTable==undefined){
                tmplInvoiceChangeTable = $('kendo-grid-list.k-invoices table tbody tr:first-child')[0].outerHTML
            }
            $('kendo-grid-list.k-invoices table tbody').html('')
            
            var kp = {}
            for(var i in hj){
                var jl = findOrdersByTable(hj[i])
                for(var j in jl){
                    if(jl[j].code==ct3) continue;
                    kp[jl[j].code] = jl[j]
                }
            }
            for(var j in kp){
                var tmplInvoiceChangeTable2 = $(tmplInvoiceChangeTable)
                
                var cu = kp[j].customer, cu2, cu3 = 0
                if(cu>0){
                    cu2 = loadCustomer(cu).name + ''
                }else{
                    cu2 = 'Khách lẻ'
                }
                
                tmplInvoiceChangeTable2.find('td:nth(0)').html(cu2)                    
                tmplInvoiceChangeTable2.find('td:nth(1)').html(kp[j].code)
                
                cu2 = 0
                for(var j2 in kp[j].products){
                    cu2-=-kp[j].products[j2].quantity
                    cu3-=-kp[j].products[j2].quantity*priceafterdiscount.call(kp[j].products[j2])
                }
                
                tmplInvoiceChangeTable2.find('td:nth(2)').html(cu2)    
                tmplInvoiceChangeTable2.find('td:nth(3)').html(formatCurrency(cu3))    
                
                $('kendo-grid-list.k-invoices table tbody').append(tmplInvoiceChangeTable2)
            }
        }
        
        function selectedTableChangedMerge(){ console.log('selectedTableChangedMerge:',this,$(this).val())
            var ff = _loadInvoices($(this).val()); 
            //var ff = _loadInvoices();//$('[k-on-change="selectedTableChangedMerge()"]').val()
             
            if(tmplInvoiceMerge==undefined){
                tmplInvoiceMerge = $('kendo-grid-list.k-grid-blue table tbody tr:first-child')[0].outerHTML
                 
            }
             
            $('kendo-grid-list.k-grid-blue table tbody').html('')
            
            for(var i in ff){
                var tmplInvoiceMerge2 = $(tmplInvoiceMerge)
                tmplInvoiceMerge2.attr('data-code',ff[i].code)
                
                if(ff[i].status==-2 || ff[i].products.length==0)//if(!(ff[i].status==0)) //if(!_checkAllProducts(ff[i]))//if(ff[i].status<0)
                    tmplInvoiceMerge2.find('td:nth(0) input').remove() 
                
                var cu = ff[i].customer, cu2, cu3 = 0
                if(cu>0){
                    cu2 = loadCustomer(cu).name + ''
                }else{
                    cu2 = 'Khách lẻ'
                }
                 
                tmplInvoiceMerge2.find('td:nth(1)').html(cu2)                    
                tmplInvoiceMerge2.find('td:nth(2)').html(ff[i].code)
                
                cu2 = 0
                for(var j in ff[i].products){
                    cu2-=-ff[i].products[j].quantity
                    cu3-=-ff[i].products[j].quantity*priceafterdiscount.call(ff[i].products[j])
                }
                
                tmplInvoiceMerge2.find('td:nth(3)').html(cu2)    
                tmplInvoiceMerge2.find('td:nth(4)').html(formatCurrency(cu3))    
                
                $('kendo-grid-list.k-grid-blue table tbody').append(tmplInvoiceMerge2)
            }            
        }
         
        //check all products have opid
        function _checkAllProducts(ob){
            var check = true;
            if(ob.products.length==0) return false;
            for(var i in ob.products){
                if(ob.products[i].opid==undefined){
                    check = false
                    break
                }
            }
            return check;
        }
        
        var hj; //current tables of current invoice (ct3)