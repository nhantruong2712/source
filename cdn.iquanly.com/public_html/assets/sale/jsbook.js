
    
            function book(ob){
            //save database set status=0
            if(ob.status<0){
                $.ajax({
                    url: '/ajax.php?action=book',
                    type: 'POST',
                    global: !0,
                    dataType: 'json',
                    data: 'data='+encodeURIComponent(JSON.stringify($.extend({},ob,{status: 0,branch:cb}))), 
                    success: function(data){
                        /*
        'status'=>'1',
        'order_id'=>$order_id  
        //cần thêm invoices để add thêm  
        'invoices'=>$invoices                  
                        */
                        if(data.error!=undefined && data.error!=''){
                            toastr.warning('Có lỗi xảy ra: '+data.error)                            
                        }  else {
                            //inhoadon
                            inhoadon(ob)
                            
                            var ob2 = $.extend({id: data.order_id,invoices: data.invoices},ob,{status: 0,branch:cb})
                            
                            //xóa đơn khỏi orders
                            for(var i in orders){
                                if(orders[i].code==ct3){
                                    orders.splice(i,1);
                                    break;
                                }
                            }
                             
                            //add book     
                                                  
                            books.push(ob2)                            
                            //saveBooks(cb)
                            db.table("Books").add(ob2)// db['Books-'+cb] "Books-"+cb
                             
                            var co = findOrdersByTable(ct2)                               
                            addTabs(co)
                             
                            toastr.info('Đã lưu thành công đơn đặt hàng')
                            //thay đổi count
                            bookCount() 
                            
                            //socket emit
                            //...
                            
                        }                            
                         
                    },
                    error: function(jqXHR,  textStatus,  errorThrown){
                        toastr.warning('Có lỗi xảy ra')
                        /*ob.status=-1
                        if(jqXHR.status==0){
                            if(jqXHR.abort) jqXHR.abort();
                            //dut mang
                            //tra hang
                            saveOffline(ob)
                        }else{
                            ob.status=-1
                        }*/
                    }
                })                
            }else{
                toastr.warning('Đơn này đã đặt rồi')
            }
        }
        function bookSave(ob){
            //save database, set Status = 0
            if(ob.id>0 && ob.status<0){
                $.ajax({
                    url: '/ajax.php?action=bookSave',
                    type: 'POST',
                    global: !0,
                    dataType: 'json',
                    data: 'data='+encodeURIComponent(JSON.stringify($.extend({},ob,{status: 0,branch:cb}))), 
                    success: function(data){
                        /*
                         
                        */
                        if(data.error!=undefined && data.error!=''){
                            toastr.warning('Có lỗi xảy ra: '+data.error)     
                            if(data.close==1){
                                //lỗi đã lưu rồi, close hết: có 2 trường hợp xảy ra (nhưng hình như là 1)
                                
                                //xóa đơn khỏi orders
                                for(var i in orders){
                                    if(orders[i].code==ct3){
                                        orders.splice(i,1);
                                        break;
                                    }
                                }
                                //kiểm tra xem có order nào type=1 và invoice=ob.id
                                var check = false;
                                for(var i in orders){
                                    if(orders[i].type==1 && orders[i].invoice==ob.id){
                                        check = orders[i];
                                        
                                        for(var j in orders){
                                            if(orders[j].code==check.code){
                                                orders.splice(j,1);
                                                break;
                                            }
                                        }
                                        
                                        toastr.info('Hóa đơn được tạo từ đơn đặt hàng này đã bị đóng');
                                        break;
                                    }
                                } 
                                var co = findOrdersByTable(ct2)                               
                                addTabs(co)
                            }                       
                        }  else {
                            //inhoadon
                            inhoadon(ob)
                            
                            var ob2 = $.extend({invoices: data.invoices},ob,{status: 0,branch:cb})
                            
                            //xóa đơn khỏi orders
                            for(var i in orders){
                                if(orders[i].code==ct3){
                                    orders.splice(i,1);
                                    break;
                                }
                            }
                            
                            //kiểm tra xem có order nào type=1 và invoice=ob.id
                            var check = false;
                            for(var i in orders){
                                if(orders[i].type==1 && orders[i].invoice==ob.id){
                                    check = orders[i];
                                    
                                    for(var j in orders){
                                        if(orders[j].code==check.code){
                                            orders.splice(j,1);
                                            break;
                                        }
                                    }
                                    
                                    toastr.info('Hóa đơn được tạo từ đơn đặt hàng này đã bị đóng');
                                    break;
                                }
                            } 
                            
                            //update book 
                            for(var i in books){
                                if(books[i].code == ob2.code){
                                    books[i] = ob2;
                                    console.log('update book after save ok')
                                    break;
                                }
                            }
                                                                                                              
                            db.table("Books").where('id').equals(ob2.id).modify(ob2).then(function(){ //"Books-"+cb
                                console.log('update book indexeddb after save ok')
                            }) 
                             
                            var co = findOrdersByTable(ct2)                               
                            addTabs(co)
                             
                            toastr.info('Đã lưu thành công đơn đặt hàng')
                            //k cần thay đổi count
                            //bookCount() 
                            
                        }                                                     
                    },
                    error: function(jqXHR,  textStatus,  errorThrown){
                        toastr.warning('Có lỗi xảy ra')
                        /*ob.status=-1
                        if(jqXHR.status==0){
                            if(jqXHR.abort) jqXHR.abort();
                            //dut mang
                            //tra hang
                            saveOffline(ob)
                        }else{
                            ob.status=-1
                        }*/
                    }
                })
            }else{
                toastr.warning('Đơn đặt hàng này chưa thay đổi')
            }
        }
        function bookDone(ob){
            //tạo hóa đơn bán hàng mới trong data luôn (kèm cả phiếu thu)
            //toastr.info('Chức năng đang phát triển')
            if(ob.id>0){
                newcode = nCode()
                $.ajax({
                    url: '/ajax.php?action=bookDone',
                    type: 'POST',
                    global: !0,
                    dataType: 'json',
                    data: 'data='+encodeURIComponent(JSON.stringify($.extend({},ob,{status: 1,branch:cb,newcode: newcode}))), 
                    success: function(data){
                        /*
                        order_id: 215
                        status: "1" 
                        */
                        if(data.error!=undefined && data.error!=''){
                            toastr.warning('Có lỗi xảy ra: '+data.error)         
                            if(data.close==1){
                                //lỗi đã lưu rồi, close hết: có 2 trường hợp xảy ra (nhưng hình như là 1)
                                
                                //xóa đơn khỏi orders
                                for(var i in orders){
                                    if(orders[i].code==ct3){
                                        orders.splice(i,1);
                                        break;
                                    }
                                }
                                //kiểm tra xem có order nào type=1 và invoice=ob.id
                                var check = false;
                                for(var i in orders){
                                    if(orders[i].type==1 && orders[i].invoice==ob.id){
                                        check = orders[i];
                                        
                                        for(var j in orders){
                                            if(orders[j].code==check.code){
                                                orders.splice(j,1);
                                                break;
                                            }
                                        }
                                        
                                        toastr.info('Hóa đơn được tạo từ đơn đặt hàng này đã bị đóng');
                                        break;
                                    }
                                } 
                                var co = findOrdersByTable(ct2)                               
                                addTabs(co)
                            }                    
                        }  else {
                            //inhoadon
                            inhoadon(ob)
                            
                            var ob2 = $.extend({invoices: data.invoices},ob,{status: 1,branch:cb})
                            
                            //tạo invoices mới phục vụ cho việc trả hàng
                            // thay code, update id
                            var newiv = convertOrderToInvoice($.extend({},ob2,{code: newcode,id: data.order_id}));
                            invoices.unshift(newiv);
                            saveCacheInvoices(invoices);
                            
                            //xóa đơn đặt khỏi orders
                            for(var i in orders){
                                if(orders[i].code==ct3){
                                    orders.splice(i,1);
                                    break;
                                }
                            }
                            
                            //kiểm tra xem có order nào type=1 và invoice=ob.id
                            var check = false;
                            for(var i in orders){
                                if(orders[i].type==1 && orders[i].invoice==ob.id){
                                    check = orders[i];
                                    
                                    for(var j in orders){
                                        if(orders[j].code==check.code){
                                            orders.splice(j,1);
                                            break;
                                        }
                                    }
                                    
                                    toastr.info('Hóa đơn được tạo từ đơn đặt hàng này đã bị đóng');
                                    break;
                                }
                            } 
                            
                            //delete book 
                            for(var i in books){
                                if(books[i].code == ob2.code){
                                    books.splice(i,1)
                                    console.log('delete book after bookDone ok')
                                    break;
                                }
                            }
                                                                                                              
                            db.table("Books").where('id').equals(ob2.id).delete().then(function(){ //"Books-"+cb
                                console.log('delete book indexeddb after bookDone ok')
                            }) 
                             
                            var co = findOrdersByTable(ct2)                               
                            addTabs(co)
                             
                            toastr.info('Đã hoàn thành thành công đơn đặt hàng')
                            //thay đổi count
                            bookCount() 
                            
                        }                                                     
                    },
                    error: function(jqXHR,  textStatus,  errorThrown){
                        toastr.warning('Có lỗi xảy ra')
                        /*ob.status=-1
                        if(jqXHR.status==0){
                            if(jqXHR.abort) jqXHR.abort();
                            //dut mang
                            //tra hang
                            saveOffline(ob)
                        }else{
                            ob.status=-1
                        }*/
                    }
                })
            }
        }
        function bookCreateInvoice(ob){
            
            
            //add vào orders 1 invoice mới với code mới, kế thừa từ ob (trừ code)
            //k ảnh hưởng gì tới database, tức k cần ajax
            
            //ct2 = localStorage.ct2 = _getOneTable(ob.table)
            var co = findOrdersByTable(ct2) 
            
            //kiểm tra orders : type=1 invoice=ob.id thì k tạo thêm nữa mà switch sang đó luôn
            var check = false;
            for(var i in orders){
                if(orders[i].type==1 && orders[i].invoice==ob.id){
                    check = orders[i]
                    ct3 = check.code
                    addTabs(co)
                    return;
                }
            }
            
            var ccc = $.extend({},ob)
            delete ccc.code
            if(ccc.datestart.match(/^\d+\-\d+\-\d+ \d+\:\d+\:\d+$/)){
                ccc.datestart = reconvertDate(ccc.datestart)
            }
            //xóa id và opid trong products
            delete ccc.id;
            for(var i in ccc.products){
                delete ccc.products[i].opid;
                //update lại maxquantity
                var tl = $.extend({},ccc.products[i])                 
                ccc.products[i].maxquantity = tl.quantity;
            }
            ccc.type = 1; //đổi từ 17 sang 1
            ccc.invoice = ob.id;
            ccc.status = 0;
            create_new_invoice(co,'DH',ccc)  
             
            addTabs(co)
            
        }
        
        
        
        
        
        

    var xxx3;
    function showPaidAmount(ob){ console.log('obob',ob)
        xxx3 = $('[kendo-window="paidAmountWindow"]').parent();
        
        xxx3.width(Math.min(640,$(document).width()))
        
        $('.k-overlay').css({
            display:'block',
            'z-index': 10000
        })
        
        xxx3.css({
            top: 0,
            left: ($(document).width()-xxx3.width())/2
        }).show()
        
        xxx3.find('.k-header .k-window-title').css({    
            'padding-top': '0px'
        }).html('Thanh toán trước cho đơn hàng '+ob.code) 
        
        xxx3.find('.k-header .k-i-close').unbind('click')
        xxx3.find('.k-header .k-i-close').click(function(){
            xxx3.hide()
            $('.k-overlay').hide()
        })
        
        var t1 = $('.paidAmountWindow table thead tr')[0].outerHTML.replace(/<th/g,'<td').replace(/<\/th/g,'</td');
        $('.paidAmountWindow table tbody').html('')
        for(var i in ob.invoices){
            var t2 = $(t1)
            t2.find('td:nth(0)').html(ob.invoices[i].code)
            t2.find('td:nth(1)').html(ob.invoices[i].date)
            t2.find('td:nth(2)').html(formatCurrency(ob.invoices[i].paying))
            t2.find('td:nth(3)').html('Tiền mặt')
            
            $('.paidAmountWindow table tbody').append(t2)
        }
    }
    
    function bookProcess(){
        xxx2 = $('[kendo-window="orderWindow"]').parent();
        
        xxx2.width(Math.min(1050,$(document).width()))
        
        $('.k-overlay').css({
            display:'block',
            'z-index': 10000
        })
        
        xxx2.css({
            top: 0,
            left: ($(document).width()-xxx2.width())/2
        }).show()
        
        xxx2.find('.k-header .k-window-title').css({    
            'padding-top': '0px'
        }).html('Xử lý đặt hàng') 
        
        xxx2.find('.k-header .k-i-close').unbind('click')
        xxx2.find('.k-header .k-i-close').click(function(){
            xxx2.hide()
            $('.k-overlay').hide()
        })
         
        xxx2.find('[data-role="datepicker"]:not(.hasDatepicker)').datepicker({
            maxDate: new Date(),
            onSelect: function(val,$target){ //vvkkll = $target;
                //console.log(val,$target) orderFilter.toDate
                if($target.input.attr('k-ng-model') == "orderFilter.fromDate"){
                    xxx2.find('[data-role="datepicker"]:nth(1)').datepicker(
                        'option', 'minDate', xxx2.find("[data-role='datepicker']:nth(0)").datepicker('getDate')
                    );
                     
                }else{
                     
                }
            },
            onClose: function(dateText, inst){ console.log('onClose:',dateText, inst)                 
                if(dateText=='' || validDate( dateText )) {
                    if(inst.input.attr('k-ng-model') == "orderFilter.fromDate")
                        filter2.fromDate = dateText  
                    else
                        filter2.toDate = dateText  
                    _g2()
                }   else {
                    $(this).val(inst.lastVal)
                    toastr.warning("The selected day is invalid: "+ dateText)
                }
            }    
        })
        
        xxx2.find('[data-role="datepicker"]:nth(0)').unbind('change')
        xxx2.find('[data-role="datepicker"]:nth(0)').change(function(){ //alert('changed')
             
            console.log('1:',$(this).datepicker('getDate'))
             
        }) 
        
        xxx2.find('[data-role="datepicker"]:nth(1)').unbind('change')
        xxx2.find('[data-role="datepicker"]:nth(1)').change(function(){ //alert('changed 2')
            console.log('2:',$(this).datepicker('getDate'))
             
        }) 
        
        xxx2.find('[ng-model="orderFilter.Code"]').unbind('change')
        xxx2.find('[ng-model="orderFilter.Code"]').change(function(){
            filter2.Code = $(this).val()
            _g2()
        }) 
        
        xxx2.find('[ng-model="orderFilter.CustomerName"]').unbind('change')
        xxx2.find('[ng-model="orderFilter.CustomerName"]').change(function(){
            filter2.CustomerName = $(this).val()
            _g2()
        })
        
        xxx2.find('[ng-model="orderFilter.Note"]').unbind('change')
        xxx2.find('[ng-model="orderFilter.Note"]').change(function(){
            filter2.Note = $(this).val()
            _g2()
        })
         
        _g2()
    }
    
    var bookTpl,countInvoices2=0;
    function _g2(){
        console.log('_g2')
        if(books == null || !_lb){ console.log('_g2 2')
            setTimeout(function(){
                _g2()
            },500)
            return;
        }
        
        if(bookTpl==undefined) bookTpl = $('#bookTpl').html()
        
        xxx2.find('#grdOrders tbody').html('')
        countInvoices2 = 0;
        //countInvoicesPaging = 0;
        
        console.log('_g2 3',books.length)
        
        if(books.length){
            xxx2.find('.empty-grid').hide()
            xxx2.find('table[role="grid"]').show()
        }else{
            xxx2.find('.empty-grid').show()
            xxx2.find('table[role="grid"]').hide()
        }
        //console.log('_g2 4',books.length)
        for(var i in books){
            //console.log('_g2 5')
            if(filter2.Code && 
                books[i].code.toLowerCase().indexOf(filter2.Code.toLowerCase())==-1) continue;
            
            if(filter2.fromDate){
                var m1 = moment(filter2.fromDate,'DD/MM/YYYY')
                var m2 = moment($.datepicker.formatDate('yy-mm-dd',new Date(books[i].date)))
                if(m1.isAfter(m2)) continue;
            }
            //console.log('_g2 6')
            if(filter2.toDate){
                var m1 = moment(filter2.toDate,'DD/MM/YYYY')
                var m2 = moment($.datepicker.formatDate('yy-mm-dd',new Date(books[i].date)))
                if(m1.isBefore(m2)) continue;
            }
            //console.log('_g2 7')
            if(books[i].customer>0){
                var customerObject = loadCustomer(books[i].customer);
            }else{
                var customerObject = {
                    name: '',
                    phone: ''
                }
            }
            //console.log('_g2 8')
            if(filter2.CustomerName && (
                customerObject.name.toLowerCase().indexOf(filter2.CustomerName.toLowerCase())==-1 &&
                customerObject.phone.toLowerCase().indexOf(filter2.CustomerName.toLowerCase())==-1
            )) continue;
             
            countInvoices2++;
            //onsole.log('_g2 9')
            //if(countInvoicesPaging++ >= cpage*record && countInvoicesPaging< (cpage+1)*record){
            if(countInvoices2 > cpage*record && countInvoices2<= (cpage+1)*record){
                
                var returnTpl2 = bookTpl
                
                returnTpl2 = returnTpl2.replace(/\{\{alt\}\}/g,i%2==1 ? ' k-alt':'')
                //console.log('_g2 10')
                for(var j in books[i]){ //console.log('_g2 10a',j)
                    if(j=='products') continue;
                    if(j=='date'){
                        var t9 = moment(new Date(books[i][j])).format('DD/MM/YYYY HH:mm')
                        returnTpl2 = returnTpl2.replace(new RegExp('\{\{'+j+'\}\}','g'), t9)
                    }else if(j=='customer'){                    
                        if(books[i][j]>0){
                            var t9 = customerObject // loadCustomer(books[i][j])   
                            t9 = t9? t9.name : 'Khách lẻ'
                        } else {
                            var t9 = 'Khách lẻ'
                        }
                        returnTpl2 = returnTpl2.replace(new RegExp('\{\{'+j+'\}\}','g'), t9)
                    }else if(j=='create'){                     
                        if(books[i][j]>0){
                            var t9 = loadUser(books[i][j])   
                            t9 = t9? t9.name : ''
                        } else {
                            var t9 = ''
                        }
                        returnTpl2 = returnTpl2.replace(new RegExp('\{\{'+j+'\}\}','g'), t9)
                    }else if(j=='table'){
                        if(books[i][j]>0){
                            var t9 = loadTable(books[i][j])   
                            t9 = t9? t9.name : ''
                        } else {
                            var t9 = ''
                        }
                        returnTpl2 = returnTpl2.replace(new RegExp('\{\{'+j+'\}\}','g'), t9)
                    }else if(j=='status'){
                        var t9 = 'Phiếu tạm'
                        returnTpl2 = returnTpl2.replace(new RegExp('\{\{'+j+'\}\}','g'), t9)
                    }else{
                        returnTpl2 = returnTpl2.replace(new RegExp('\{\{'+j+'\}\}','g'), books[i][j])
                    }
                }
                console.log('_g2 11')
                xxx2.find('#grdOrders tbody').append(returnTpl2)
                console.log('_g2 12')
            }
            
        }
        
        
        xxx2.find('.k-pager-numbers li span').html(cpage+1)
        
        tpage = Math.ceil(countInvoices/record)
        
        if(tpage>1){
            xxx2.find('.paging-box').show()
        }else{
            xxx2.find('.paging-box').hide()
        }
        
        if(cpage==0){
            xxx2.find('.k-pager-first,.k-pager-first+.k-pager-nav').addClass('k-state-disabled').unbind('click')
        }else{
            xxx2.find('.k-pager-first,.k-pager-first+.k-pager-nav').removeClass('k-state-disabled').unbind('click')
            xxx2.find('.k-pager-first').click(function(){
                cpage=0
                _g2()
            })
            xxx2.find('.k-pager-first+.k-pager-nav').click(function(){
                cpage--
                _g2()
            })
        }
        
        if(cpage==tpage-1 || tpage==0){
            xxx2.find('.k-pager-last,ul+.k-pager-nav').addClass('k-state-disabled').unbind('click')
        }else{
            xxx2.find('.k-pager-last,ul+.k-pager-nav').removeClass('k-state-disabled').unbind('click')
            xxx2.find('.k-pager-last').click(function(){
                cpage=tpage-1
                _g2()
            })
            xxx2.find('ul+.k-pager-nav').click(function(){
                cpage++;
                _g2()
            })
        }
        
        xxx2.find(".k-pager-info.k-label").html("Hiển thị "+(cpage*record+1)+" - "+Math.min(countInvoices,(cpage+1)*record)+" trên tổng số "+countInvoices2+" đơn đặt hàng");
        
    }
    function chooseBook(id){
        
        document.location.hash = '#/Orders/'+id
    }
    
    function loadBook(id){ //alert(id)
        console.log('loadBook')
        if(books==undefined){
            setTimeout(function(){
                loadBook(id)
            },500)
            return;
        }
        if(xxx2)
        xxx2.find('tr[data-uid="'+id+'"]').remove()
        
        var iv = loadObject(id,books,'id')
        console.log('iv:',iv)
        
        ct2 = localStorage.ct2 = _getOneTable(iv.table)
        var co = findOrdersByTable(ct2) 
         
        var ccc = $.extend({},iv)
        
        //check exists
        var ce = loadObject(ccc.code,orders,'code')
        
        if(!ce){ 
            if(!orders) orders=[]
            if(ccc.datestart.match(/^\d+\-\d+\-\d+ \d+\:\d+\:\d+$/)){
                ccc.datestart = reconvertDate(ccc.datestart)
            }
            ccc.paying = 0
            orders.push(ccc)
            if(!isArray(co)) co = []
            co.push(ccc)        
            saveOrders()  
        }   
        ct3 = ccc.code   
        localStorage.ct3 = ct3       
        
        addTabs(co)
        
        //dont need remove from books
        /*db["Books-"+cb].where('id').equals(ccc.id).delete()
        for(var i in books){
            if(books[i].code==ccc.code){
                books.splice(i,1)
                break;
            }
        }*/
          
        document.location.hash = ''
        if(xxx2) 
        xxx2.find('.k-header .k-i-close').click()
    }        