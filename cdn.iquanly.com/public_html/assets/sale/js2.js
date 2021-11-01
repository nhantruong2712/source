function displayProducts(ob){ 
            $('.kv2Coffee .kv2ListScroll>table>tbody').html('')
            //thongbaonhabep(ob) 
            if(ob.products.length){
                for(var kk in ob.products){
                    addInvoiceItem(ob.products[kk],ob,kk)                     
                }
            }
            
            displayPrice(ob) 
            $('.loading').hide() 
        }
        
        
        function displayPrice(ob){ //alert(displayPrice)
            console.log('displayPrice',JSON.stringify(ob))
            var tongtienhang = 0;
            if(ob.products.length){
                for(var kk in ob.products){
                    var addTop = 0;
                    if(ob.products[kk].top && JSON.stringify(ob.products[kk].top)!='{}'){
                        for(var qq in ob.products[kk].top){
                            //var productTop = loadProduct(qq)
                            //addTop += ob.products[kk].top[qq]*productTop.price
                            addTop += ob.products[kk].top[qq][0]*ob.products[kk].top[qq][1]
                        }
                    }
                    tongtienhang += (ob.products[kk].price-ob.products[kk].discount+addTop)*ob.products[kk].quantity
                }
            }
            ob.price = tongtienhang;
            ob.fee = ob.fee>0?ob.fee:0;
            ob.discount = ob.discount==undefined?0:ob.discount
            
            saveOrders()
            //tong tien hang
            $('.kv2Pay .ng-binding:nth(1), .kv2Pay .ng-binding:nth(6)').html(formatCurrency(tongtienhang))
            $('.kv2Pay .ng-binding:nth(2), .kv2Pay .ng-binding:nth(7)').html(formatCurrency(ob.discount))
            var nk = 0;if((ob.type==17&&ob.id>0)||(ob.type==1&&ob.invoice>0)) nk = ob.invoices.sum('paying');
            $('.kv2Pay .ng-binding:nth(9)').html(formatCurrency(tongtienhang-ob.discount-ob.fee-nk));//khach can tra
            
            $('[kv-popup-anchor="feeOnReturn"]').html(formatCurrency(ob.fee))
            
            _displayPrice(ob)
            
            //
            if(ob.type==11){ //for quickreturn
                $('.kv2Pay .ng-binding:nth(8)').html('Cần trả khách')
                $('.kv2Pay .ng-binding:nth(10)').html("Thanh toán khách <i>(F8)</i>  ")
            }else{
                $('.kv2Pay .ng-binding:nth(8)').html('Khách cần trả')
                $('.kv2Pay .ng-binding:nth(10)').html("Khách thanh toán <i>(F8)</i>  ")
            }  
            
            _doRunAll(1); //fixed 04/04/2019 from _doRunAll()       dont know why
        }
        function _displayPrice(ob){ console.log('_displayPrice')
            if(ob.price-ob.discount>0){
                $('.kv2Pay [ng-show="activeCart.BalanceDue > 0.000001 && !isWaiter"]').removeClass('ng-hide')
                $('.kv2Pay .payRefund').removeClass('ng-hide')
                
                if(ob.addtoaccount==undefined || !ob.addtoaccount){
                    ob.addtoaccount=0
                }else{
                    ob.addtoaccount=1
                }
                
                console.log(ob.addtoaccount)
                
                $('label[ng-class*="activeCart.addToAccount"]').removeClass('active')
                $('label[ng-class*="activeCart.addToAccount"]>input').removeAttr('checked')
                if(ob.addtoaccount){
                    $('label[ng-class*="activeCart.addToAccount"]:nth(1)').addClass('active')
                    $('label[ng-class*="activeCart.addToAccount"]:nth(1)>input').prop('checked',true)
                }else{
                    $('label[ng-class*="activeCart.addToAccount"]:nth(0)').addClass('active')
                    $('label[ng-class*="activeCart.addToAccount"]:nth(0)>input').prop('checked',true)
                }
                
                $('label[ng-class*="activeCart.addToAccount"]').unbind('click')
                $('label[ng-class*="activeCart.addToAccount"]').click(function(e){
                    //alert($(this).index())
                    ob.addtoaccount = $(this).index() ; saveOrders()                      
                })
                
                if(ob.customer && ob.customer!='0'){
                    $('.kv2Pay .payRefund [ng-show="activeCart.CustomerId"]').removeClass('ng-hide')
                }else{
                    $('.kv2Pay .payRefund [ng-show="activeCart.CustomerId"]').addClass('ng-hide')
                }
                //if(!(document.activeElement && document.activeElement.id=='payingAmt'))
                //    ob.paying = Math.round(ob.price-ob.discount-ob.fee) // ob.paying==undefined?0:ob.paying //changed 04/23/2019
                //else
                    ob.paying = ob.paying==undefined?0:ob.paying
                
                ob.fee = ob.fee==undefined?0:ob.fee
                
                //added 10/08/2019
                if(ob.extra && ob.extra.payment && ob.extra.payment.length>1){
                    $('#payingAmt').attr('disabled','disabled')
                }else{
                    $('#payingAmt').removeAttr('disabled')
                }
                
                $('#payingAmt').val(ob.paying)
                $('#payingAmt').unbind('change')
                $('#payingAmt').change(function(e){
                    var pa = $('#payingAmt').val()
                    //try{
                        pa = parseInt(pa); if(isNaN(pa)){pa = 0;$(this).val(pa)} 
                        if(pa<0){
                            pa = -pa
                            $(this).val(pa)
                        } 
                    //}catch(f){
                    //    pa = 0
                    //}    
                    ob.paying = pa; 
                    
                    //added 10/08/2019
                    if(ob.extra && ob.extra.payment && ob.extra.payment.length==1){
                        ob.extra.payment[0][1] = pa;
                    }
                    
                    saveOrders()
                    var nk=0;if((ob.type==17&&ob.id>0)||(ob.type==1&&ob.invoice>0)) nk = ob.invoices.sum('paying')
                    $('.kv2Pay .ng-binding:nth(11)').html(formatCurrency(-ob.price-(-ob.discount)-(-ob.paying)-(-ob.fee)-(-nk)));//tien thua tra khach
                })
                var nk=0;if((ob.type==17&&ob.id>0)||(ob.type==1&&ob.invoice>0)) nk = ob.invoices.sum('paying')
                $('.kv2Pay .ng-binding:nth(11)').html(formatCurrency(-ob.price-(-ob.discount)-(-ob.paying)-(-ob.fee)-(-nk)));//tien thua tra khach
                 
            }
            
            else{
                $('.kv2Pay [ng-show="activeCart.BalanceDue > 0.000001 && !isWaiter"]').addClass('ng-hide')
                $('.kv2Pay .payRefund').addClass('ng-hide')
            }
            
            thongbaonhabep(ob) 
        }
        
        
        function fixQty(item,ob,oldQ){
             
            if(ob.status<0){
                var onHand = oldQ-item.processingqty-item.deliveryqty
                if(item.deliveryqty>0){
                    item.deliveryqty = Math.min(item.deliveryqty,item.quantity)
                }
                
                if(item.processingqty>0){
                    item.processingqty = Math.min(item.processingqty,item.quantity)
                }
                 
                var onHand2 = item.quantity-item.processingqty-item.deliveryqty
                if(onHand2<0){
                    item.processingqty-=onHand2
                }else if(onHand2>onHand){
                    item.processingqty+=onHand2-onHand
                }
            }
            
        }
        
        
        

        
        /*var timeouts 
        
        function _doRun(item) {
            if(item.run==1){
                timeouts = setInterval(function(){
                    
                },7000)
            }else{
                if(timeouts!=undefined){
                    clearInterval(timeouts)
                    delete timeouts
                }
                    
            }
        }*/
        
        function _calcTime(item){ //UseBlockTimeUnit
            var a = Math.floor((item.t2)/60) - Math.floor((item.t1)/60);
            a = Math.ceil(a/BlockNumber)*BlockNumber
            a = a/60
            return Number(a).toFixed(3)             
        }
        
        var tos = {} //ct3 - i - id
        var tos2 = {} 
        var runOne = {} 
        function getHash(ob3){
            return ob3.products.map(function(v,i){return v.id}).join('-')
        }
        function _doRunAll(force){  //for always true
            //console.log('_doRunAll',force)
            var ob2 = loadObject(ct3,orders,'code')
            if(ob2.type==11) return;
            var obx = getHash(ob2)
            console.log('xxx:',force,JSON.stringify(runOne),ct3,obx)
            if(!force && runOne[ct3]!=undefined && runOne[ct3] == obx) return;
            //tos = {}
             
            runOne[ct3] = obx;
            
            for(var key1 in tos){
                if(force || tos[key1].hash!=runOne[ct3]){
                    //alert('clearInterval 3')
                    clearInterval(tos[key1].t)
                    delete tos[key1]
                }
            }
            
            for(var i in ob2.products){
                var key1 = ct3+'-'+i+'-'+ob2.products[i].id;
                if(force && ob2.products[i].time==1 && ob2.products[i].run!=1){ //update 04/04/2019
                    if($('.popover-time-counter').is(':visible')){
                        var _o = $('.popover-time-counter').data('item')
                        if(_o == key1 ){                            
                            $('.popover-time-counter .form-control-static:nth(1)').html(_calcTimeString(ob2.products[i]))
                        } 
                    }
                    ob2.products[i].quantity = _calcTime(ob2.products[i])
                                  
                    if(ob2.products[i].quantity!=$('tr[data-index="'+i+'"] [ng-model="item.quantity"]').val()){
                        saveOrders()
                        displayProducts(ob2)                        
                    } 
                }else //end update 04/04/2019
                if(ob2.products[i].time==1 && ob2.products[i].run==1){
                     
                    if(tos[key1]==undefined){
                        tos[key1] ={
                            ct3,
                            i,
                            pid: ob2.products[i].id, 
                            hash: obx,
                            f: function (){ console.log('a',this)
                            
                                /*var ob4 = loadObject(ct3,orders,'code');
                                var hhh = getHash(ob4);
                                for(var zz in tos){
                                    if(tos.hash!=hhh){
                                        clearInterval(tos[zz].t)
                                        delete tos[zz]
                                    } 
                                }*/
                                if(ct3!=this.ct3) return;
                            
                                var key1 = this.ct3+'-'+this.i+'-'+this.pid;
                                ob2.products[this.i].t2 = Math.round((new Date().getTime())/1000);// moment().format('DD/MM/YYYY HH:mm')
                                if($('.popover-time-counter').is(':visible')){
                                    var _o = $('.popover-time-counter').data('item')
                                    if(_o == key1 ){
                                        $('.popover-time-counter .ng-star-inserted span').html(moment(ob2.products[this.i].t2*1000).format('DD/MM/YYYY HH:mm'));
                                        $('.popover-time-counter .form-control-static:nth(1)').html(_calcTimeString(ob2.products[this.i]))
                                    } 
                                } 
                                ob2.products[this.i].quantity = _calcTime(ob2.products[this.i])
                                
                                if(ob2.products[this.i].quantity!=$('tr[data-index="'+this.i+'"] [ng-model="item.quantity"]').val()){
                                    
                                    var _this = this;  
                                    
                                    if($('[kendo-window="wdChangeTable"]').is(':visible')){
                                        var _o = $('[kendo-window="wdChangeTable"] input[readonly]')
                                        if(_o.length){
                                            _o.each(function(){
                                                var _o2 = $(this).parents('.row-list[data-index]')
                                                var _o3 = _o2.attr('data-index')
                                                if(_o3 == _this.i){
                                                    _o2.attr('data-quantity',ob2.products[_o3].quantity)
                                                    if(ob2.products[_o3].quantity>0){
                                                        if(_o2.find('.cell-change-price').html()==0){
                                                            $(this).val(ob2.products[_o3].quantity)
                                                        }else{
                                                            _o2.find('.cell-change-price').html(ob2.products[_o3].quantity)
                                                        }
                                                    }    
                                                }
                                            })
                                        }
                                    }
                                    
                                    saveOrders()
                                    displayProducts(ob2)
                                    console.log(ob2.products[this.i].quantity+'~'+$('tr[data-index="'+this.i+'"] [ng-model="item.quantity"]').val())
                                } 
                                
                            }
                        }
                        tos[key1].t = setInterval(tos[key1].f.bind(tos[key1]),7000)
                        
                        tos[key1].f.call(tos[key1])
                    }  
                    
                    
                }else if(tos[key1]!=undefined){   
                    //alert('clearInterval')
                    clearInterval(tos[key1].t)
                    delete tos[key1]
                }
                
            }
             
        }
        var tplAddTopping;
        function addInvoiceItem(item,ob,idx){ console.log('addInvoiceItem',idx,item)
            //thongbaonhabep(ob)
            var _productInvoiceTmpl = productInvoiceTmpl+''
            ////if(item.priceafterdiscount==undefined) 
            ////    item.priceafterdiscount= priceafterdiscount      
             
            var ret2x = {}
            var mx = _productInvoiceTmpl.match(/\{\{([^\}]+)\}\}/g)
            var dataindex = idx == undefined ? ob.products.length-1 : idx;
            
            //add 06/23/2019 fix for item booking miss maxquantity
            if(item.maxquantity==undefined){
                var _kj = loadProduct(item.id || item.product)
                if(_kj){
                    item.maxquantity = _kj.quantity
                }
            }
            
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
                //console.log(rt)
                ret2x[mx[k]] = eval(rt)
                _productInvoiceTmpl = _productInvoiceTmpl.replace(mx[k],ret2x[mx[k]]==null?'':ret2x[mx[k]])
            } 
            //console.log(ret2x)
            var ll = $(_productInvoiceTmpl)
            //ll.find('[ng-show="item.DeliveryQty"],[ng-show="item.MaxQuantity"]').addClass('ng-hide')
            ll.find('[ng-show="item.DeliveryQty"]').addClass('ng-hide')
            
            ll.find('[ng-show="item.error"]').addClass('ng-hide')
            if(item.discount<=0.00001)
                ll.find('[ng-show="item.discount> 0.00001"]').addClass('ng-hide')
            else
                ll.find('[ng-show="item.discount> 0.00001"]').removeClass('ng-hide')
            //alert(item.extra)    
            //alert(item.topping) 
            if(item.topping){
                
            }else{
                ll.find('[kv-popup-anchor="orderTemplate"] .glyphicon-paperclip').remove()
            }   
            
            if(item.top==undefined){
                item.top = {} 
                ob.products[dataindex] = item
                saveOrders()
            } 
            var cq = function(item){ //alert('cq'); 
                console.log('cq',item,ll,JSON.stringify(item.top))
                if(item.top && JSON.stringify(item.top)!='{}'){
                    var nm = []
                    for(var qq in item.top){
                        var p4 = loadProduct(qq).name
                        nm.push('<span>+'+item.top[qq][0]+' '+p4+'</span>')
                    }
                    console.log('cq2', JSON.stringify(nm))
                    ll.find('.list-topping').html(nm.join('&nbsp;,&nbsp;'))
                    ll.find('.list-topping').click(function(){
                        $(this).prev().click()
                    })
                    ll.find('.list-topping').removeClass('ng-hide')
                }else
                    ll.find('.list-topping').addClass('ng-hide')
            }
            cq(item)    
            ll.find('[kv-popup-anchor="orderTemplate"]').click(function(e){
                if(item.processingqty>0){
                    toastr.warning('Bạn đã thông báo sản phẩm này, bạn không thể sửa Ghi chú'+(item.topping?'/Món thêm':'')+' được nữa')
                    return;
                }
                if(item.topping){
                    //alert(item.topping)
                    
                    $('modal-container.topping .modal-body h4 b').html(item.name)
                    $('modal-container.topping .modal-body .cell-change-price:nth(0)').html(formatCurrency(item.price))
                    //$('modal-container .modal-body .cell-quatity:nth(0)').html(formatQuantity(item.quantity))
                    $('modal-container.topping .modal-body .cell-quatity:nth(0)').html('/1SP')
                    $('modal-container.topping .modal-body textarea').html(item.note)
                    
                    $('modal-container.topping .modal-body textarea').unbind('change')
                    $('modal-container.topping .modal-body textarea').change(function(){
                        if(item.note != $(this).val()){  
                            ////if(ob.status==0) 
                                ob.status=-2
                            item.note = $(this).val(); saveOrders(); thongbaonhabep(ob)
                            ll.find('[kv-popup-anchor="orderTemplate"] .veaM').html(strLen(item.note,40,item.topping?'Ghi chú/Món thêm':'Ghi chú...'))
                        }
                    })
                    
                    //form-note
                    if(tplAddTopping==undefined){
                        tplAddTopping = $('modal-container.topping .modal-body .form-note + div')[0].outerHTML;
                    }
                    
                    $('modal-container.topping .modal-body .form-note ~ div').remove()
                    
                    var ji = item.topping.split(',')
                    for(var ji2 in ji){
                        var ji3 = ji[ji2]
                        ji3 = loadProduct(ji3)
                        var tplAddTopping2 = tplAddTopping;
                        tplAddTopping2 = $(tplAddTopping2)
                        tplAddTopping2.attr('data-index',ji3.id)
                        tplAddTopping2.find('h4').html(ji3.name)
                        tplAddTopping2.find('.cell-change-price').html(formatCurrency(ji3.price))
                         
                        tplAddTopping2.find('.btn-icon.down').click(function(){
                            //alert(dataindex)
                            ji2 = $(this).parents('.product-cart-list').attr('data-index')
                            //alert(ji2)
                            item = ob.products[dataindex]
                            //alert(ob)
                            //alert(item)
                            item.top[ji2] = item.top[ji2]?item.top[ji2]:[0,0];//loadProduct(ji2).price
                            if(item.top[ji2][0]>=1){
                                item.top[ji2][0] = item.top[ji2][0]-1
                                //tplAddTopping2.find('input').val(item.top[ji2])
                                $(this).siblings('input').val(item.top[ji2][0])
                                
                                if(item.top[ji2][0]==0)
                                    delete item.top[ji2]
                                
                                ////if(ob.status==0){
                                    ob.status = -2
                                ////}
                                displayProducts(ob)
                                thongbaonhabep(ob)
                                //saveOrders()
                                //displayPrice(ob)
                                cq(item)    
                            }else if(item.top[ji2][0]==0) delete item.top[ji2]
                        })
                        tplAddTopping2.find('.btn-icon.up').click(function(){
                            //alert(dataindex)
                            ji2 = $(this).parents('.product-cart-list').attr('data-index')
                            //alert(ji2)
                            item = ob.products[dataindex]
                            //alert(ob)
                            //alert(item)
                            item.top[ji2] = item.top[ji2]?item.top[ji2]:[0,loadProduct(ji2).price];
                            item.top[ji2][0] = item.top[ji2][0]-(-1)
                            //tplAddTopping2.find('input').val(item.top[ji2])
                            $(this).siblings('input').val(item.top[ji2][0])
                            
                            ////if(ob.status==0){
                                ob.status = -2
                            ////}
                            displayProducts(ob)
                            thongbaonhabep(ob)
                            //saveOrders()
                            //displayPrice(ob)
                            cq(item)    
                        })
                        tplAddTopping2.find('input').val(item.top[ji3.id]?item.top[ji3.id][0]:'')
                        tplAddTopping2.find('input').change(function(){ //alert('z')
                            //alert(dataindex)
                            ji2 = $(this).parents('.product-cart-list').attr('data-index')
                            //alert(ji2)
                            item = ob.products[dataindex]
                            var v = $(this).val()
                            if(v!=''){
                                if(!v.match(/^[0-9\.]+$/)){
                                    toastr.warning('Không đúng định dạng số')
                                    $(this).val(item.top[ji2]?item.top[ji2][0]:'')
                                    return
                                }
                            }
                            item.top[ji2] = item.top[ji2]?item.top[ji2]:[0,loadProduct(ji2).price]
                            if(item.top[ji2][0] != v){
                                item.top[ji2][0] = v==0?0:v;
                                if(v==0) 
                                    delete item.top[ji2]
                                ////if(ob.status==0){
                                    ob.status = -2
                                ////}
                                displayProducts(ob)
                                thongbaonhabep(ob)
                                //saveOrders()
                                //displayPrice(ob)
                                cq(item)    
                            }else if(item.top[ji2][0]==0) delete item.top[ji2]
                        })
                        
                        $('modal-container.topping .modal-body .form-note').after(tplAddTopping2)
                    }
                     
                    $('modal-container.topping').show()
                    $('body').addClass('modal-open')
                    
                    $('modal-container.topping .modal-header .close,modal-container.topping .modal-footer .btn-default').unbind('click')
                    $('modal-container.topping .modal-header .close,modal-container.topping .modal-footer .btn-default').click(function(){
                        $('body').removeClass('modal-open')
                        $('modal-container.topping').hide()
                    })
                    
                    $('modal-container.topping .modal-body .form-note textarea').focus().select()
                    
                    return;
                }
                
                //console.log(e,$(this).offset())
                var that = $(this)
                $('#orderTemplate').addClass('popping')
                $('#orderTemplate [ng-show="showUnit"]').addClass('ng-hide')
                $('#orderTemplate .kv2Pop').css({
                    left: that.offset().left-getScrollY(1),
                    top: that.offset().top+that.height()-getScrollY()
                })
                $('#orderTemplate [ng-change="itemNoteChanged()"]').unbind('blur')
                $('#orderTemplate [ng-change="itemNoteChanged()"]').val(item.note)
                $('#orderTemplate [ng-change="itemNoteChanged()"]').focus()
                //$('#orderTemplate [ng-change="itemNoteChanged()"]').unbind('change')
                $('#orderTemplate [ng-change="itemNoteChanged()"]').blur(function(){
                    $('#orderTemplate').removeClass('popping')
                    //alert(item.id)
                    //alert($(this).val())
                    if(item.note != $(this).val()){  
                        ////if(ob.status==0) 
                            ob.status=-2
                        item.note = $(this).val(); saveOrders(); thongbaonhabep(ob)
                        that.find('.veaM').html(strLen(item.note,40,item.topping?'Ghi chú/Món thêm':'Ghi chú...'))
                    }
                })
            })
            ll.find('[ng-click="removeItem(item)"]').click(function(e){
                var pq = $(this).parents('tr[data-index]')
                var pq2 = pq.attr('data-index')+''
                //$(this).parent().parent().remove()
                
                //$(this).remove() //bo 4/30/2021
                //pq.hide('slow') //bo 4/30/2021
                
                //4/30/2021
                pq.siblings().each(function(im){
                    var pq3 = $(this).attr('data-index')-0
                    if(pq3>pq2-0) $(this).attr('data-index',pq3-1)
                })
                
                pq.remove() //them 4/30/2021
                /*for(var i in ob.products){
                    if(ob.products[i].id == item.id){                    
                        //delete ob.products[i]
                        ob.products.splice(i,1)
                        break
                    }
                }*/
                ob.products.splice(pq2,1)
                
                ////if(ob.status==0){
                    ob.status = -2
                ////}
                thongbaonhabep(ob)
                //saveOrders()
                displayPrice(ob)
                return false
            })    
            
            ll.find('[ng-model="item.quantity"]').val(item.quantity)
            if(item.run==1 && ob.type!=11){ //tru tra hang ra
                ll.find('[ng-model="item.quantity"]').attr('readonly','readonly')
            } 
            //console.log(item,'zzzzzzz')
            ll.find('.saleQty.down').click(function(e){
                if(item.run==1) return;
                var r = ll.find('[ng-model="item.quantity"]')
                var l = r.val() // parseInt(r.val())
                if(l-1>0){
                    r.val(checknum(l-1,4,0))
                    var oldQ = item.quantity-"0"
                    item.quantity=checknum(item.quantity-1,4,0) //item.quantity --
                    //saveOrders()
                    ll.find('td:last-child').html(formatCurrency(priceafterdiscount.call(item) * item.quantity))
                    ////if(ob.status==0){
                        ob.status = -2
                    ////}
                    
                    //04/16/2021
                    ob.products[dataindex] = item; console.log('idx:',dataindex)
                    
                    fixQty(item,ob,oldQ)
                    
                    displayPrice(ob)
                }
            })
            ll.find('.saleQty.up').click(function(e){
                if(item.run==1) return;
                var r = ll.find('[ng-model="item.quantity"]')
                var l = r.val()// parseInt(r.val())
                r.val(checknum(l-(-1),4,0)) //r.val(l+1)
                var oldQ = item.quantity-"0"
                item.quantity=checknum(item.quantity-(-1),4,0)//item.quantity ++
                //saveOrders()
                ll.find('td:last-child').html(formatCurrency(priceafterdiscount.call(item) * item.quantity))
                ////if(ob.status==0){
                    ob.status = -2
                ////}
                
                //04/16/2021
                ob.products[dataindex] = item; console.log('idx:',dataindex)
                
                fixQty(item,ob,oldQ)
                displayPrice(ob)
            })
            ll.find('[ng-model="item.quantity"]').unbind('change')
            ll.find('[ng-model="item.quantity"]').change(function(e){
                var tu = $(this).val()
                
                if(tu.match(/^[^\/]+(\*|\+|\-)[^\/]+$/)){
                    ob.status = -2
                    var tu2 = to3digits(checknum(tu))
                    
                    item.quantity=tu2;
                    if(!item.note)
                        item.note = tu;
                     
                    $(this).val(item.quantity)
                    
                    //04/16/2021
                    ob.products[dataindex] = item; console.log('idx:',dataindex)
                    
                    fixQty(item,ob,oldQ)
                    displayProducts(ob)
                    
                    return;
                }
                
                //try{
                    /*tu=parseInt(tu); if(isNaN(tu)){tu = 0;$(this).val(tu)} 
                    if(tu<0){
                        tu=-tu
                        $(this).val(tu)
                    } */
                    tu1=parseInt(tu); 
                    tu2=parseFloat(tu); 
                    if(isNaN(tu1)){tu = 0;$(this).val(tu)} 
                    tu = tu2
                    if(tu<0){
                        tu=-tu
                        $(this).val(tu)
                    }
                //}catch(t){
                //    tu=0
                //}
                if(item.quantity!=tu){
                    ////if(ob.status==0){
                        ob.status = -2
                    ////}
                    //var oldQ = item.quantity-0;
                    var oldQ = to3digits(item.quantity)-0;
                    
                    item.quantity=checknum(tu,4,0)
                    
                    item.quantity = to3digits(item.quantity)
                    $(this).val(item.quantity)
                    
                    //04/16/2021
                    ob.products[dataindex] = item; console.log('idx:',dataindex)
                    
                    fixQty(item,ob,oldQ)
                    displayProducts(ob)
                }
            })
            if(item.discountratio!=undefined)
                ll.find('[ng-hide="item.DiscountRatio"]').addClass('ng-hide')
            else
                ll.find('[ng-hide="item.DiscountRatio"]').removeClass('ng-hide')
            
            var th=ll.find('[ng-class="{activeRed:(item.ProcessingQty && item.ProcessingQty>0 && (item.Quantity > item.DeliveryQty)),activeBlue: (item.DeliveryQty && item.Quantity === item.DeliveryQty)}"]')
                console.log(th)
            if(item.deliveryqty && item.deliveryqty>0 && (item.quantity > item.deliveryqty))
                th.addClass('activeRed')
            else if(item.deliveryqty && item.quantity == item.deliveryqty)
                th.addClass('activeBlue')
            
            th.click(function(e){ //alert('zzz')
            
                //added 04/10/2019
                if(typeof isI!='undefined'){                    
                    return;
                }
                
                //alert($(this).parents('tr[data-index]').attr('data-index'))
                var ik = $(this).parents('tr[data-index]').attr('data-index');
                ik = ob.products[ik]
                //khong cho clone gio`
                if(ik.time==1){
                    toastr.warning('Không cho phép sao chép dịch vụ tính giờ')
                    return
                }
                
                //console.log('th.click:',JSON.stringify(ik))
                if(ik.product==undefined) ik.product = ik.id
                var ik2 = $.extend({},ik,{
                    quantity: 1,
                    processingqty: 0,
                    deliveryqty: 0,
                    note:'', //k clone note
                    discount: 0,//k clone discount
                })
                delete ik2.opid
                ik2.top = {}
                //console.log('th.click:',JSON.stringify(loadProduct(ik.product)))
                //console.log('th.click:',JSON.stringify(ik2))
                addProductInvoice(loadProduct(ik.product), ik2, void 0, !0)                
            })
            
            //added 04/10/2019
            th.find('i').click(function(){
                //alert('zzzz2')
                isI = true;
                setTimeout(function(){
                    delete isI
                },1000)
                
                //alert('zzzz2')
                var ik2 = $(this).parents('tr[data-index]').attr('data-index');
                var ik = ob.products[ik2]
                //alert(ik.id || ik.product)
                
                console.log('ik:',ik)
                
                var listUnits = [];
                var zz;
                if(ik.parent>0){
                    zz = findchild(ik.parent)
                }else{
                    zz = findchild(ik.id || ik.product)                    
                }
                console.log('zz:',zz)
                for(var i in zz){
                    if(zz[i].code != ik.code){
                        listUnits.push({
                            uname: zz[i].uname,
                            id: zz[i].id || zz[i].product
                        })
                    }
                        
                }
                
                if(listUnits.length){
                    $('kv-ddl-change-product-by-unit ul').html('')
                    $('kv-ddl-change-product-by-unit').data('productindex',ik2)
                    for(var i in listUnits){
                        var ql = $('<li data-id="'+listUnits[i].id+'"><a href="javascript:void(0);">'+listUnits[i].uname+'</a></li>')
                        ql.click(function(){
                            zz3 = $(this).attr('data-id')
                            //alert(zz3)
                            //alert(ik.id || ik.product)
                            //alert(ik2)
                             
                            /*

quantity: "-9"
deliveryqty: 0      
discount: 0
discountratio: 0  
maxquantity: "-14"                    
                            */
                            var ik2 = $('kv-ddl-change-product-by-unit').data('productindex')
                            
                            var tl = $.extend({}, loadProduct(zz3))
                             
                            tl.maxquantity = tl.quantity;
                            tl.quantity = ob.products[ik2].quantity;
                             
                            tl.discount = 0; //console.log('tl:',tl)
                            tl.discountratio = 0;
                            
                            tl.deliveryqty = 0;
                            tl.processingqty = 0;
                            
                            tl.note = ob.products[ik2].note?ob.products[ik2].note:'';
                             
                            ob.products[ik2] = tl
                            
                            //console.log('iikk2',ik2,JSON.stringify(ob.products[ik2]))
                            
                            ////if(ob.status==0) 
                                ob.status = -2
                             
                            displayProducts(ob)                             
                            thongbaonhabep(ob);
                        })
                        $('kv-ddl-change-product-by-unit ul').append(ql)                        
                    }
                    $('kv-ddl-change-product-by-unit').addClass('open').css({
                        top: $(this).offset().top,
                        left: $(this).offset().left
                    })
                }
                
            })
            
            var th2 = ll.find('[ng-show="item.DeliveryQty"]')   
            if(item.deliveryqty && item.deliveryqty>0)
                th2.removeClass('ng-hide')
            else
                th2.addClass('ng-hide') 
                
            if(item.time==1){   
                ll.find('.btn-icon.btn-time').click(function(e){
                    var x = $('.popover-time-counter')
                    if(x.is(':visible') && e.pageX != undefined){
                        x.hide()
                    } else {
                        x.show()
                        
                        x.css({
                            top: $(this).offset().top -77+59-getScrollY(),
                            left: $(this).offset().left-270 -getScrollY(1),
                            'z-index': 1111
                        })
                        
                        x.find('[data-role="datetimepicker"]:nth(0)').val(moment(item.t1*1000).format('DD/MM/YYYY HH:mm'))
                        x.find('[data-role="datetimepicker"]:nth(1)').val(moment(item.t2*1000).format('DD/MM/YYYY HH:mm'))
                        x.find('.ng-star-inserted span').html(moment(item.t2*1000).format('DD/MM/YYYY HH:mm'))
                        x.find('.form-control-static:nth(1)').html(_calcTimeString(item))
                        
                        if(item.run){
                            x.find('[ng-show="item.run"]').removeClass('ng-hide')
                            x.find('[ng-hide="item.run"]').addClass('ng-hide')
                            x.find('button').html('Dừng tính')
                        }else{
                            x.find('[ng-show="item.run"]').addClass('ng-hide')
                            x.find('[ng-hide="item.run"]').removeClass('ng-hide')
                            x.find('button').html('Tiếp tục')
                        }
                        
                        x.find('button').unbind('click')
                        x.find('button').click(function(){
                            var i = ll.attr('data-index');
                            if(ob.products[i].run == 1){
                                ob.products[i].run = 0
                                //$(this).html('Tiếp tục')                                
                                ll.find('[ng-model="item.quantity"]').removeAttr('readonly')            
                            }else{
                                ob.products[i].run = 1
                                //$(this).html('Dừng tính')
                                ll.find('[ng-model="item.quantity"]').attr('readonly','readonly')
                            }
                            ll.find('.btn-icon.btn-time').click()
                            
                            ////if(ob.status==0){
                                ob.status = -2
                            ////}
                             
                            saveOrders()
                            _doRunAll(1)
                            thongbaonhabep(ob)
                            
                        })
                        
                        x.data('item',[ct3,idx,item.id].join('-'))
                        
                        x.find('[kvkendodatetimepicker]:nth(0)').unbind('change')
                        x.find('[kvkendodatetimepicker]:nth(0)').change(function(){ 
                            var v = $(this).val()
                            var i = ll.attr('data-index'); //alert(i)
                            if(!v.match(/^\d+\/\d+\/\d+ \d+:\d+$/)){
                                toastr.warning('Sai định dạng ngày giờ')                                
                                $(this).val(moment(ob.products[i].t1*1000).format('DD/MM/YYYY HH:mm'))
                            }else{
                                ob.products[i].t1 = moment(v,'DD/MM/YYYY HH:mm').unix();
                                
                                ////if(ob.status==0){
                                    ob.status = -2
                                ////}
                                
                                saveOrders()
                                _doRunAll(1)
                                thongbaonhabep(ob)
                            }
                        })
                        x.find('[kvkendodatetimepicker]:nth(1)').unbind('change')
                        x.find('[kvkendodatetimepicker]:nth(1)').change(function(){ //alert(2)
                            var v = $(this).val()
                            var i = ll.attr('data-index');
                            if(!v.match(/^\d+\/\d+\/\d+ \d+:\d+$/)){
                                toastr.warning('Sai định dạng ngày giờ')                                
                                $(this).val(moment(ob.products[i].t2*1000).format('DD/MM/YYYY HH:mm'))
                            }else{
                                ob.products[i].t2 = moment(v,'DD/MM/YYYY HH:mm').unix();
                                
                                ////if(ob.status==0){
                                    ob.status = -2
                                ////}
                                
                                saveOrders()
                                _doRunAll(1)
                                thongbaonhabep(ob)
                            }
                        })
                    }
                }) 
                 
            } 
            
            if(item.type!=2 && ob.type!=11){ //tru tra hang ra
                ll.find('[ng-show="item.MaxQuantity"]').addClass('hth-hide')
            }
            
            ll.find('button[ng-click="selectedItemChanged(item)"]').click(function(e){
                var that = $(this)
                $('#productPrice').addClass('popping')
                //$('#productPrice .kv2Pop')
                console.log($(this).offset())
                $('#productPrice .kv2Pop').css({
                    top: $(this).offset().top-90/2+30/2-getScrollY(),
                    left: $(this).offset().left-250-10-getScrollY(1)
                })
                if(item.discountratio==undefined){
                    $('#productPrice [ng-model="DiscountType"]>a:nth(0)').addClass('active')
                    $('#productPrice [ng-model="DiscountType"]>a:nth(1)').removeClass('active')
                }else{
                    $('#productPrice [ng-model="DiscountType"]>a:nth(0)').removeClass('active')
                    $('#productPrice [ng-model="DiscountType"]>a:nth(1)').addClass('active')
                }
                
                $('#productPrice [ng-model="adjustedPrice"]').focus()
                 
                $('#productPrice [ng-model="DiscountType"]>a:nth(0)').unbind('click')
                $('#productPrice [ng-model="DiscountType"]>a:nth(1)').unbind('click')
                
                $('#productPrice [ng-model="DiscountType"]>a:nth(0)').click(function(e){
                    $('#productPrice [ng-model="DiscountType"]>a.active').removeClass('active')
                    $(this).addClass('active')
                    if(item.discountratio!=undefined){
                        delete item.discountratio
                        $('#productPrice [ng-model="DiscountValue"]').val( 
                            (item.discount>0?item.discount:'') 
                        )
                    }
                    return false
                })
                
                $('#productPrice [ng-model="DiscountType"]>a:nth(1)').click(function(e){
                    $('#productPrice [ng-model="DiscountType"]>a.active').removeClass('active')
                    $(this).addClass('active')
                    if(item.discountratio==undefined){
                        //item.discountratio = (100*item.discount/item.price).toFixed(2)
                        //item.discountratio = (100*item.discount/(item.priceafterdiscount()-(-item.discount))).toFixed(2)
                        item.discountratio = (100*item.discount/priceafter.call(item)).toFixed(2)
                        $('#productPrice [ng-model="DiscountValue"]').val( 
                            (item.discountratio>0?item.discountratio:'') 
                        )
                    }
                    return false
                })
                
                $('#productPrice [ng-model="adjustedPrice"]').val(priceafterdiscount.call(item))
                $('#productPrice [ng-model="DiscountValue"]').val(item.discountratio==undefined?
                    (item.discount>0?item.discount:''):(item.discountratio>0?item.discountratio:'')
                )
                $('#productPrice [ng-model="adjustedPrice"]').unbind('change')
                $('#productPrice [ng-model="adjustedPrice"]').change(function(e){
                    var tu = $(this).val()
                    //try{
                        tu=parseInt(tu); if(isNaN(tu)){tu = 0;$(this).val(tu)} 
                        if(tu<0){
                            tu=-tu
                            $(this).val(tu)
                        } 
                    //}catch(t){
                    //    tu=0
                    //}
                    //if(item.discount != item.price-tu){
                    if(item.discount != priceafter.call(item)-tu){    
                        //item.discount = item.price-tu
                        item.discount = priceafter.call(item)-tu
                        if(item.discountratio!=undefined){
                            //item.discountratio = (100*item.discount/item.price).toFixed(2)
                            item.discountratio = (100*item.discount/priceafter.call(item)).toFixed(2)                            
                            $('#productPrice [ng-model="DiscountValue"]').val(item.discountratio>0?item.discountratio:'')
                        }else
                            $('#productPrice [ng-model="DiscountValue"]').val(item.discount>0?item.discount:'')
                            
                        ////if(ob.status==0){
                            ob.status = -2
                        ////}
                        saveOrders()
                        displayProducts(ob)
                    }
                })
                $('#productPrice [ng-model="DiscountValue"]').unbind('change')
                $('#productPrice [ng-model="DiscountValue"]').change(function(e){
                    var tu = $(this).val()
                    //try{
                        tu=parseInt(tu); if(isNaN(tu)){tu = 0;$(this).val(tu)} 
                        if(tu<0){
                            tu=-tu
                            $(this).val(tu)
                        } 
                        if(item.discountratio!=undefined){
                            if(tu>100){
                                toastr['error']('Bạn phải nhập số từ 0-100');
                                tu = 0
                                $(this).val(tu)
                            } 
                        }
                    //}catch(t){
                    //    tu=0
                    //}
                    
                    if(item.discountratio!=undefined){
                        item.discountratio = tu
                        //var id = (item.discountratio*item.price/100).toFixed(0)
                        var id = (item.discountratio*priceafter.call(item)/100).toFixed(0)
                            
                        if(id!=item.discount){
                            item.discount = id
                            $('#productPrice [ng-model="adjustedPrice"]').val(priceafterdiscount.call(item))
                            ////if(ob.status==0){
                                ob.status = -2
                            ////}
                        }
                    }else{
                        var id = tu
                        if(id!=item.discount){
                            item.discount = id
                            $('#productPrice [ng-model="adjustedPrice"]').val(priceafterdiscount.call(item))
                            ////if(ob.status==0){
                                ob.status = -2
                            ////}
                        }
                    }
                     
                    saveOrders()
                    displayProducts(ob)
                })
                /*$('#productPrice').unbind('mouseover')
                var t_in = new Date().getTime()
                $('#productPrice').mouseover(function(e){
                    //$(this).removeClass('popping')
                    t_in = new Date().getTime()
                })*/
                //setTimeout(function(){
                    var si=setInterval(function(){
                        //if(new Date().getTime()-t_in>3000){
                        if($(document.activeElement).parents('#productPrice').length==0 && !$(document.activeElement).is('#productPrice')) {                    
                            $('#productPrice').removeClass('popping')
                            clearInterval(si)
                        }
                    },100)
                //},1500)
            })
            $('.kv2Coffee .kv2ListScroll>table>tbody').append(ll)
        }
        
        
        
        function create_new_invoice(co,prefix,iv){ //co: findOrdersByTable
            var ccc = emptyOrder(ct2,prefix,iv)
            if(!orders) orders=[]
            orders.push(ccc)
            if(co!=undefined)
                co.push(ccc)
            saveOrders()     
            ct3 = ccc.code   
            localStorage.ct3 = ct3      
            return ccc; 
        }
        
        
        function afterClick(that){ console.log('afterClick',that)
            var kk = loadObject(ct2,tables)
            var co = findOrdersByTable(ct2) 
            var ccc = null
            console.log(co)
            if(co.length>0){
                //check ton tai
                var check = false                    
                if(ct3){                          
                    for(var j in co){                             
                       if( ct3 == co[j].code)   {
                        check = true
                        break;
                       }                                                           
                    }                    
                }
                if(!check)
                for(var j in co){
                    ccc=co[j]
                    ct3 = ccc.code   
                    localStorage.ct3 = ct3         
                    break;
                }
                else
                    ccc = loadObject(ct3,orders,'code')
            }else{
                //create new invoice                     
                create_new_invoice(co)      
            }
            //co: display right panel
            console.log(co)
            if(!templ2)
                templ2 = $('[kv-tab-id="cart.uuid"] li')[0].outerHTML.replace(/class="active"/g,'')
            addTabs(co)
            ////suggest customer
            _customerItemTempl = $('#customerItemTempl').html()
            
            //
            //............
            if(site_type==1){
            
            console.log('afterClick 2',_customerItemTempl)
            
            $('.swiper-container aside.tableRed>a>span>span[ng-show="p.selected"]').addClass('ng-hide')   
            $('.swiper-container aside.tableRed>a>span>span[ng-show="!p.selected && !p.isBusy()"]').removeClass('ng-hide')  
            //$('.swiper-container [ng-show="p.isBusy() && !p.selected"]').removeClass('ng-hide') 
            $('.swiper-container aside.tableRed').removeClass('tableRed')
            //$('.swiper-container aside').removeClass('tableRed')
            if(that){
                console.log('that:',that)
                isThat = 1
                $(that).parent().addClass('tableRed')
            }
                
            //alert(isBusy(kk))
            //if(!isBusy(kk))
            $('.swiper-container aside.tableRed>a>span>span[ng-show="p.isBusy() && !p.selected"]').addClass('ng-hide') 
            $('.swiper-container aside.tableRed>a>span>span[ng-show="p.selected"]').removeClass('ng-hide')   
            $('.swiper-container aside.tableRed>a>span>span[ng-show="!p.selected && !p.isBusy()"]').addClass('ng-hide') 
            
            //tim thang busy de hien thi xanh
            $('.swiper-container ul>li[data-id]').each(function(i,v){
                var i3 = parseInt($(v).attr('data-id'))
                
                if(i3!=ct2){ //alert(i3);alert(ct2)
                    var i4 = loadObject(i3,tables)
                    if(isBusy(i4)){
                        $(v).find('a>span>span[ng-show="p.isBusy() && !p.selected"]').removeClass('ng-hide') 
                        $(v).find('a>span>span[ng-show="!p.selected && !p.isBusy()"]').addClass('ng-hide') 
                    }    
                }
                /*/add 04/23/2019
                else{
                    var i4 = loadObject(i3,tables)
                    if(isBusy(i4)){
                        $(v).find('a>span>span[ng-show="p.selected"').removeClass('ng-hide')                          
                    }  
                }
                 */
                
            });
            
            }
        }
        
        
        
        function loadTabs11(mk){
            if(mk!=undefined)
            prevnext()
            
            console.log('loadTabs11',viewType)
             
            if(viewType=='table'){
                
                if($('.swiper-container').length==0 || $('[ng-model="tableFilter"]').length==0){
                    setTimeout(function(){loadTabs11()},500);
                    console.log('setTimeout tables')
                    return
                }
                
                $('[ng-show="toggles.showCategories && !toggles.table"]').addClass('ng-hide')
                $('[ng-class="{active : toggles.showCategories}"]').removeClass('active')
                
                var sd = $('[ng-model="tableFilter"]').val()
                 
                if(!tables || orders==null){
                    setTimeout(function(){loadTabs11()},500);
                    console.log('setTimeout tables')
                    return
                }
                
                console.log(tables)
                var sw = $('.swiper-container')
                 
                var h2 = Math.max(window.orientation==0?960:618, $(window).height())-sw.offset().top; //var h2 = $(window).height()-sw.offset().top-66;//$('.kv2Pro-bottom').height()
                sw.height(h2)
                sw.find('.swiper-slide').height(h2)
                
                var w2 
                if($(window).width()<=1024)
                    w2 = $(window).width() - 10 // - $('.kv2Right').width()
                else
                    w2 = $(window).width() - 10 - $('.kv2Right').width()
                    
                sw.width(w2)
                sw.find('.swiper-slide').width(w2)
                
                //moi cai 145 110
                var z1=Math.max(Math.floor((h2-35)/110),1)
                var z2=Math.max(Math.floor((w2-35)/145),1)
                
                var tt = filterT(ct1,sd); console.log('tt,ct1,sd:',tt,ct1,typeof sd,JSON.stringify(tables))
                var pages = Math.ceil(tt.length/z1/z2)
                //if(cp1>=pages||cp1<0) cp1 = 0;
                /*if(cp1>=pages ) cp1 = 0;
                if( cp1<0) cp1 = pages-1; */
                if(cp1>=pages ) cp1 = pages-1;
                if( cp1<0) cp1 = 0;
                
                //sw.find('.swiper-slide ul').html('')
                /*if(!templ1){
                    templ1 = sw.find('.swiper-slide ul li')[2].outerHTML+""
                }*/
                //templ1 = tableListTmpl+""
                
                sw.find('.swiper-slide ul').html('')
                
                console.log(cp1*z1*z2,Math.min(tt.length,(cp1+1)*z1*z2))
                console.log('gggggggggggggg1')  
                for(var j = cp1*z1*z2 ; j< Math.min(tt.length,(cp1+1)*z1*z2);j++ ){
                    var ll = $(tt[j].id==-2?tableListTmpl3:(tt[j].id==-1?tableListTmpl2:tableListTmpl)) // $(templ1)
                    ll.find('.proName').html(tt[j].name)
                    
                    ll.find('aside>a>span>span').addClass('ng-hide')
                    
                    if(tt[j].note)
                        ll.find('aside').addClass('tableActive')
                    /*if(tt[j].id==ct2){
                        ll.find('aside').addClass('tableRed')
                        ll.find('aside>a>span>span[ng-show="p.selected"]').removeClass('ng-hide')                    
                    }                    
                    else */
                    if(isBusy(tt[j])){
                        ll.find('aside').addClass('tableGreen')    
                        if(tt[j].id!=ct2){
                            ll.find('aside>a>span>span[ng-show="p.isBusy() && !p.selected"]').removeClass('ng-hide')   
                        }else{
                            ll.find('aside>a>span>span[ng-show="p.isBusy() && !p.selected"]').addClass('ng-hide')   
                            ll.find('aside>a>span>span[ng-show="!p.selected && !p.isBusy()"]').removeClass('ng-hide')                           
                        }
                    }                    
                    else{
                        ll.find('aside>a>span>span[ng-show="!p.selected && !p.isBusy()"]').removeClass('ng-hide')    
                    }    
                    ll.attr('data-id',tt[j].id); console.log('gggggggggggggg1x',tt[j].id,tt[j].status)  
                    console.log('gggggggggggggg1xxx',ll[0])  
                    
                    sw.find('.swiper-slide ul').append(ll); 
                    
                    if(tt[j].status==-1){
                        //ll.find('.check-cf').click()
                        _go(ll.find('.check-cf'),1)
                    }
                    
                    //4/30/2021
                    if(intt[tt[j].id-0]){
                        _go(ll.find('.check-cf'),1)
                    }
                    
                } 
                console.log('gggggggggggggg2')   
                $('.kv2Pro-bottom strong').html(
                    $('.kv2Pro-bottom strong').html().replace(/\d+$/,tongban) //filterT(ct1,'').length //tt.length
                )
                $('.kv2Pro-bottom strong .txtRed').html(tongsudung)
                
                $('.swiper-container a').attr('href','javascript:void(0)')
                
                console.log('gggggggggggggg2')  
                
                $('.swiper-container a[kv-popup-anchor="tableDescription"]').unbind('click')
                $('.swiper-container a[kv-popup-anchor="tableDescription"]').click(function(e){
                    $('#tableDescription').addClass('popping')
                    $('#tableDescription textarea').focus()
                     
                    $('#tableDescription>div').css({top:$(this).offset().top-23-getScrollY(),left:$(this).offset().left+10-getScrollY(1)})
                    ce = $(this).parent().parent().data('id')
                    var kk = loadObject(ce,tables)
                    if(kk){
                        $('#tableDescription textarea').val(kk.note)
                    }
                    $('#tableDescription textarea').on('blur',function(){
                        if(kk.note!=$('#tableDescription textarea').val()){
                            console.log(kk.note,$('#tableDescription textarea').val())
                            descriptionChanged2()
                        }
                            
                        $('#tableDescription').removeClass('popping')
                    })
                })
                
                console.log('gggggggggggggg3')  
                
                $('.swiper-container aside>a[ng-click="itemClicked(p)"]').unbind('click')
                $('.swiper-container aside>a[ng-click="itemClicked(p)"]').click(function(e){ console.log('ct2::',e) 
                    autoClick = e.clientX==undefined ;  //alert(autoClick)
                    //alert('a')
                    ct2 = site_type==1?$(this).parent().parent().data('id'):1;
                    localStorage.ct2 = ct2; console.log('ct2::',ct2) 
                    
                    afterClick(this)
                     
                    if(site_type==1){               
                    //open tab products
                    //alert(autoClick + ' 111')
                    if(autoMenu()&& !autoClick)  { // 
                        $('[ng-click="showProductMenu()"]').click()
                    }     
                    }else{
                    
                    viewType = 'showCategories'; var that = this
                    showProductMenu(that )
                    }               
                })
                ////console.log(cp1*z1*z2,Math.min(tt.length,(cp1+1)*z1*z2))
                console.log('gggggggggggggg4')  
                //autoClick = true
                if(!ct2){
                    if(site_type==1 && (tables==null || tables.length==0)){
                        document.location.href = '/';
                    }
                    //$('.swiper-container li[data-id]:first-child aside>a[ng-click="itemClicked(p)"]:first-child').click()
                    //ct2 = site_type==1?(orders.length>0? parseInt(orders[0].table) : tables[0].id):1;  
                    ct2 = site_type==1?(orders.length>0? _getOneTable(orders[0].table) : tables[0].id):1;  
                    localStorage.ct2 = ct2                  
                } 
                console.log('gggggggggggggg5')  
                ////else 
                ////$('.swiper-container li[data-id="'+ct2+'"] aside>a[ng-click="itemClicked(p)"]:first-child').click()
                if($('.swiper-container li[data-id="'+ct2+'"] aside>a[ng-click="itemClicked(p)"]:first-child').length){
                    
                    $('.swiper-container li[data-id="'+ct2+'"] aside>a[ng-click="itemClicked(p)"]:first-child').click()
                }else{  
                    console.log('abc')
                    afterClick()
                    if(site_type==1){                  
                     
                    if(autoMenu()&& !autoClick)  { // 
                        $('[ng-click="showProductMenu()"]').click()
                    }     
                    }else{
                    
                    viewType = 'showCategories';  
                    showProductMenu(  )
                    }
                }
                console.log('gggggggggggggg6')  
                
                if($('.searchCustomer .paymentButton').length) $('.searchCustomer .paymentButton').remove()
            }else{
                if(!products || $('.swiper-container').length==0){
                    setTimeout(function(){loadTabs11()},500);
                    console.log('setTimeout products')
                    return
                }
                 
                //var oobb = ([1024,768].indexOf($(window).width())>=0 && ct3) ? loadObject(ct3,orders,'code') : '';
                var oobb = ($(window).width()>=640 && $(window).width()<=1024 && ct3) ? loadObject(ct3,orders,'code') : '';                
                //console.log('oobb:',oobb,$(window).width(),ct3) 
                if(oobb && oobb.type==1){
                    $('.searchCustomer').append('<div class="paymentButton" style="position: absolute;left: 440px;"><a href="javascript:void(0)" skip-disable="" class="kv2Btn kv2BtnGreen kv2BtnDbl" onclick="payForOrder(1)">Thanh toán (F9)</a></div>')
                }
                
                var sw = $('.swiper-container')
                 
                var h2 = Math.max(window.orientation==0?960:618, $(window).height())-sw.offset().top; 
                sw.height(h2)
                sw.find('.swiper-slide').height(h2)
                
                var w2 
                if($(window).width()<=1024)
                    w2 = $(window).width() - 10 // - $('.kv2Right').width()
                else
                    w2 = $(window).width() - 10 - $('.kv2Right').width()
                    
                sw.width(w2)
                sw.find('.swiper-slide').width(w2)
                
                //moi cai 250 110
                var z1=Math.max(Math.floor((h2-35)/110),1)
                var z2=Math.max(Math.floor((w2-35)/250),1)
                
                //console.log(z1,z2)
                
                var tt = filterP(catx)
                var pages = Math.ceil(tt.length/z1/z2)
                
                //console.log('pages',pages)
                //if(cp1>=pages||cp1<0) cp1 = 0;
                /*if(cp1>=pages ) cp1 = 0;
                if( cp1<0) cp1 = pages-1; */
                if(cp1>=pages ) cp1 = pages-1;
                if( cp1<0) cp1 = 0;
                
                sw.find('.swiper-slide ul').html('')
                var productListTmpl2 = productListTmpl+"";
                for(var j = cp1*z1*z2 ; j< Math.min(tt.length,(cp1+1)*z1*z2);j++ ){
                    productListTmpl2 = productListTmpl+"";
                    var ret2 = {}
                    var p = tt[j]
                    var $index = j
                    ////console.log(p)
                    var m = productListTmpl2.match(/\{\{([^\}]+)\}\}/g)
                    for(var k=0;k<m.length;k++){
                        
                        ret2[m[k]] = eval(m[k].substr(2,m[k].length-4))
                        productListTmpl2 = productListTmpl2.replace(m[k],ret2[m[k]]==null?'':ret2[m[k]])
                    } 
                    var ll = $(productListTmpl2)
                    ll.click(function(e){
                        //console.log(tt[parseInt($(this).attr('index'))])
                        
                        addOnClick(tt,this);
                        /*
                        var it = tt[parseInt($(this).attr('index'))] ,
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
                        
                        saveOrders()  */
                         
                    })
                     
                    if(oobb){
                        var check = loadObject(p.id,oobb.products)
                        if(check)
                        ll.find('[ng-click="itemClicked(p)"]').append('<div class="Q">'+check.quantity+'</div>')
                    }
                                       
                    sw.find('.swiper-slide ul').append(ll)
                }
                $('[ng-click="showCategories()"]').unbind('click')
                $('[ng-click="showCategories()"]').click(function(e){
                    $(this).toggleClass('active')
                    if($(this).is('.active')){
                        $('[ng-show="toggles.showCategories && !toggles.table"]').removeClass('ng-hide')
                    }else{
                        $('[ng-show="toggles.showCategories && !toggles.table"]').addClass('ng-hide')
                    }
                })
                
                $('[ng-show="toggles.showCategories && !toggles.table"] ul').html('')
                for(var j in product_categories){
                    var _catListTmpl = catListTmpl+''
                    var ret2 = {}
                    var cat = product_categories[j]
                    var $index = j
                    console.log(cat)
                    var m = _catListTmpl.match(/\{\{([^\}]+)\}\}/g)
                    for(var k=0;k<m.length;k++){
                        ret2[m[k]] = eval(m[k].substr(2,m[k].length-4))
                        _catListTmpl = _catListTmpl.replace(m[k],ret2[m[k]]==null?'':ret2[m[k]])
                    } 
                    var ll = $(_catListTmpl)
                    ll.click(function(e){ cp1 = 0
                        //alert($(this).attr('index'))
                        catx = product_categories[parseInt($(this).attr('index'))].id
                        loadTabs11()
                        $('[ng-show="toggles.showCategories && !toggles.table"]').addClass('ng-hide')
                    })
                    $('[ng-show="toggles.showCategories && !toggles.table"] ul').append(ll)
                }
                
                if(catx!=''){
                    $('.coffeeMenu-top .dpn').removeClass('dpn')
                    var pp = loadObject(catx,product_categories)
                    $('.coffeeMenu-top span.veaM').html(pp.name)
                }else{
                    $('.coffeeMenu-top a.icon,.coffeeMenu-top span.veaM,.coffeeMenu-top span.split').addClass('dpn')
                }
                
                $('.coffeeMenu-top a.icon').unbind('click')
                $('.coffeeMenu-top a.icon').click(function(e){
                    catx = ''
                    loadTabs11()
                })
            } 
            return true
        }
        
        
        function addProductInvoice(it, it2, _this, force){ //03/2019 add it2 04/2019 add _this ; 04/09/2019 add force
        
            hideTime()
        
            var item, ob = loadObject(ct3,orders,'code') , 
                check = force?false : loadObject(it.id,ob.products)
            if(check){
                if(check.time==1){
                    toastr.warning(check.name+' đã tồn tại trong đơn hàng')
                    return;
                }
                check.quantity++
                item =$.extend( {}, check ) //item =$.extend( {priceafterdiscount:priceafterdiscount}, check )
                
                ////if(ob.status==0) 
                    ob.status = -2
                
                displayProducts(ob)
            }else{
                
                //2019 add for returns one invoice
                if(_this!=undefined && ob.type==11 && ob.invoice>0){
                    toastr.warning('Bạn không thể thêm sản phẩm này')
                    return;
                }
                
                item = $.extend({}, it, 
                {
                    ////priceafterdiscount:priceafterdiscount,
                    discount :0,
                    discountratio:0,
                    deliveryqty:0,
                    maxquantity:0,
                    note:''
                } )
                item.maxquantity = item.quantity
                item.quantity = 1;
                
                if(item.top==undefined)
                    item.top = {}
                
                if(it2){
                    //console.log('it2:',JSON.stringify(item),it2)
                    //console.log('it2 it2:',JSON.stringify(it2.topping),JSON.stringify(it2.top))
                    
                    ////if(!force && (it2.top==undefined || JSON.stringify(it2.top)=='{}'))
                    if(it2.top==undefined || JSON.stringify(it2.top)=='{}')
                        it2.top = (it2.topping && it2.topping.slice(0,1)=='{') ? JSON.parse(it2.topping) : {};
                    //console.log('it2 it2:',JSON.stringify(it2.topping),JSON.stringify(it2.top))
                    
                    ////if(force) it2.top = {} 
                    
                    if(it2.topping && it2.topping.slice(0,1)=='{')
                        delete it2.topping;
                    
                    item = $.extend(item,it2)
                    //console.log('it22:',JSON.stringify(item))
                }
                
                if(item.time==1){
                    item.t1 = item.t2 = Math.floor((new Date().getTime())/1000) ;// moment().format('DD/MM/YYYY HH:mm');
                    item.run = 1;
                }
                 
                ob.products.push(item)  ; 
                
                ////if(ob.status==0) 
                    ob.status = -2
                
                //thongbaonhabep(ob) 
                addInvoiceItem(item,ob)
                displayPrice(ob)
                //displayProducts(ob)
            }
            
            saveOrders() 
        }
        
        function addOnClick(tt,_this){
            
            //07/17/2019 
            if($(window).width()<=1024){  //alert('1')
                var _img = $(_this).find('img');
                var __ = _img.offset();
                var __1 = $('[ng-click="showProductMenu()"]');
                var _img2 = _img.clone(); 
                _img2.css({
                    position:'absolute',
                    width:'80px',
                    'z-index':11111,
                    top: __.top ,
                    left: __.left 
                });
                $('body').append(_img2)
                
                _img2.animate({
                    top: __1.offset().top ,
                    left: __1.offset().left + __1.width()+ 50
                },'slow',function(){
                    _img2.remove()
                })
                
                if($(window).width()>=640 && $(window).width()<=1024/*[1024,768].indexOf($(window).width())>=0*/){
                    var Q = $(_this).find('.Q');
                    if(Q.length){
                        Q.html(Q.html()-(0-1))
                    }else{
                        $(_this).find('[ng-click="itemClicked(p)"]').append('<div class="Q">1</div>')                        
                    }
                    beep()
                }
            } 
            
            var it = tt[parseInt($(_this).attr('index'))] 
            addProductInvoice(it, {} ,_this)
        }
        
        
        function deleteOrderByCode(ii,ob){ 
            
            var _fzz = function(){
                var ob_table = ob.table+'' //-0;
                var ob_id = ob.id>0
                for(var kk in orders){  
                    if(orders[kk].code==ii){ 
                        orders.splice(kk,1)
                        break;
                    }
                }
                saveOrders()
                if(site_type==1){      
                //chuyen tab phong ban
                if(!$('[ng-click="showTables()"]').is('.active'))
                    $('[ng-click="showTables()"]').click() 
                
                if(ob_id){
                    //add 04/09/2019
                    socket.emit('send',JSON.stringify({
                        job: 'close',
                        table: ob_table,
                        id: socket.id
                    }),data_user.kitchen)
                     
                    socket.emit('send',JSON.stringify({
                        job: 'close',
                        table: ob_table,
                        id: socket.id,
                        code: ii
                    }),socket.room)
                    //end add 04/14/2019
                }   
                 
                }
                //check tabs
                ////$('.swiper-container li[data-id="'+ct2+'"] aside>a[ng-click="itemClicked(p)"]:first-child').click()
                if($('.swiper-container li[data-id="'+ct2+'"] aside>a[ng-click="itemClicked(p)"]:first-child').length){
                    $('.swiper-container li[data-id="'+ct2+'"] aside>a[ng-click="itemClicked(p)"]:first-child').click()
                }else{
                    afterClick()                  
                }
            }
            
            if(ob.id >0 && ob.type!=17){                          
                //neu da push vao co so du lieu thi ajax delete
                //tru truong hop type=17 tuc dat hang thì k xóa trong data
                
                $.ajax({
                    url: '/ajax.php?action=deleteOrder',
                    type: 'POST',
                    data: {id:ob.id},
                    dataType: 'json',
                    success: function(data){
                        if(data.error==''){
                            _fzz()
                            toastr.info('Xóa hóa đơn thành công')
                        }else{
                            toastr.warning(data.error)
                        }
                    },
                    error: function(jqXHR,  textStatus,  errorThrown){                                     
                        if(jqXHR.status==0){
                            if(jqXHR.abort) jqXHR.abort();
                            //dut mang 
                            Offline.state='down'   
                            toastr.warning('Bạn không thể đóng hóa đơn này khi đang ở chế độ Offline')
                        }else{
                            toastr.warning(errorThrown+'')
                        }
                    } //end error function
                })
                
                
            }else{
                _fzz() 
            }
                        
        }
        
        
        function hideTime(){
            if($('.popover-time-counter').is(':visible'))
                $('.popover-time-counter').hide()         
        }
        
        
        function addTabs(co){
            console.log('addTabs',vkl)
            
            if(vkl==null){
                setTimeout(function(){addTabs(co)},500)
                return
            }  
            
            hideTime() 
             
            $('[kv-tab-id="cart.uuid"]').html('')
            //reset pos
            $('.kv2Right kv-scroll-tabs .scroll-lane').css('transform','translateX(0px)')
            
            if(co.length==0){
                create_new_invoice(co,'DH',{                 
                    customer: 0 ,
                    user: create ,
                    fee: 0 ,
                    invoice: 0 ,
                    addtoaccount: 0,
                    status: 0 ,
                    discount: 0 ,
                    paying: 0 ,
                    price: 0
                })  
            }
            var checkCt3 = false;
            for(var ii in co){
                var tk = $(templ2)
                tk.attr('data-id',co[ii].code)
                if(co[ii].code==ct3) checkCt3 = true;
                var tb = loadObject(ct2,tables);// loadObject(co[ii].table,tables)
                tk.find('>a>span').html(co[ii].code+(site_type==1?(' - '+tb.name):''))
                
                if((co[ii].table+'').indexOf(',')==-1)
                    tk.find('.group-table-icon').addClass('ng-hide')
                
                tk.find('[ng-click="close()"]').click(function(e){                    
                    //trung voi code 1870   , da /* */ doan code nay roi  
                    //close tab                     
                    var ii = $(this).parent().data('id')
                    //alert(ii)
                    var ob = loadObject(ii,orders,'code')
                     
                    if(ob.id >0 || ob.products.length>0){
                        
                        if(ob.id >0){
                            if(Offline.state=='down'){
                                toastr.warning('Bạn không thể đóng hóa đơn này khi đang ở chế độ Offline')
                                return
                            }                            
                        }
                                          
                        //alert
                        $('.k-window-alert .k-window-title').html('Đóng hóa đơn '+ii)
                        $('.k-window-alert .confirm-message strong').html('hóa đơn '+ii)
                        $('.k-window-alert').css({
                            display:'block',
                            'z-index': 10004,
                            width: Math.min(500, $(window).width()),
                            left: ($(window).width()-Math.min(500, $(window).width()))/2
                        })
                        $('.k-overlay').css({
                            display:'block',
                            'z-index': 10003
                        })
                        $('.k-window-alert .k-i-close,.k-window-alert [ng-enter="onCancel()"]').unbind('click')
                        $('.k-window-alert .k-i-close,.k-window-alert [ng-enter="onCancel()"]').click(function(e){
                            $('.k-window-alert').css({
                                display:'none'
                            })
                            $('.k-overlay').hide()
                        })
                        $('.k-window-alert [ng-enter="onConfirm()"]').unbind('click')
                        $('.k-window-alert [ng-enter="onConfirm()"]').click(function(e){
                            
                            deleteOrderByCode(ii,ob)
                            
                            $('.k-window-alert .k-i-close').click()
                        })
                         
                    }else{
                        deleteOrderByCode(ii,ob)
                    }               
                })
                $('[kv-tab-id="cart.uuid"]').append(tk)
            } 
            console.log('co.length',co.length)
            
            if(!checkCt3) ct3=co[0].code
            
            if(co.length==1){
                $('[ng-model="activeCart.uuid"] .mainWrapper>.scroll-lane').width(
                    $('[kv-tab-id="cart.uuid"]>li').width() + 52
                )      
                $('[ng-model="activeCart.uuid"] .leftArrow,[ng-model="activeCart.uuid"] .rightArrow').addClass('ng-hide')  
                $('[ng-model="activeCart.uuid"] .li-addButton').removeClass('ng-hide')  
                $('[ng-model="activeCart.uuid"] .addButtonHolder').addClass('ng-hide')            
            }else{
                var w=0
                $('[kv-tab-id="cart.uuid"]>li').each(function(v,k){w+=$(k).width()+5})
                console.log('w:',w)
                $('[ng-model="activeCart.uuid"] .mainWrapper>.scroll-lane').width(
                    w
                ) 
                $('[ng-model="activeCart.uuid"] .leftArrow,[ng-model="activeCart.uuid"] .rightArrow').removeClass('ng-hide') 
                $('[ng-model="activeCart.uuid"] .li-addButton').addClass('ng-hide') 
                $('[ng-model="activeCart.uuid"] .addButtonHolder').removeClass('ng-hide')                                     
            }
            console.log('addTabs s1')
            
            $('[kv-tab-id="cart.uuid"] li[data-id] a').attr('href','javascript:void(0)')
            $('[kv-tab-id="cart.uuid"] li[data-id]>a').click(function(e){
                //alert(ct3)
                ct3 = $(this).parent().data('id')
                localStorage.ct3 = ct3   
                //alert(ct3)
                $('[kv-tab-id="cart.uuid"] .active').removeClass('active')
                $(this).addClass('active')
                $(this).find('>a').addClass('active')
                //load data 
                
                var cod = $('.kv2Right .kv2Coffee').attr('data-code')
                if(cod == undefined || cod != ct3 || typeof dochange != 'undefined'){
                    if(typeof dochange != 'undefined') delete dochange;
                    $('.kv2Coffee [ng-model="activeCart.Description"]').val('')
                    //change
                    $('.kv2Right .kv2Coffee').attr('data-code',ct3)
                    var ob = loadObject(ct3,orders,'code')
                    
                    $('[ng-model="tabInfo"]>div>ul>li').unbind('click')
                    $('[ng-model="tabInfo"]>div>ul>li').click(function(e){
                        var ik = $(this).index()
                        $('[ng-model="tabInfo"] [kv-tab-id]>div').addClass('ng-hide')
                        $('[ng-model="tabInfo"] [kv-tab-id="'+(ik+1)+'"]>div').removeClass('ng-hide')
                        
                        $('[ng-model="tabInfo"]>div>ul>li>a').removeClass('active')
                        $(this).find('>a').addClass('active')
                    })
                    
                    //note
                    if(ob.note)
                        $('.kv2Coffee [ng-model="activeCart.Description"]').val(ob.note)
                    //else
                    //    $('.kv2Coffee [ng-model="activeCart.Description"]').val('')
                    
                    $('.kv2Coffee [ng-model="activeCart.Description"]').unbind('blur')
                    $('.kv2Coffee [ng-model="activeCart.Description"]').blur(function(e){
                        console.log('ob:',ob)
                        var x = $(this).val(); //alert(x)
                        if(x != ob.note){
                            //ob.note = x; saveOrders();
                            ////if(ob.status==0){
                                ob.status = -2
                            ////}
                            ob.note = x; saveOrders(); thongbaonhabep(ob)
                        }
                    })
                    
                    //customer
                    if(ob.customer && ob.customer!='0'){
                        $('[ng-controller="CustomerSearchCtrl"] [ng-hide="activeCart.CustomerId"]').addClass('ng-hide') 
                        $('[ng-controller="CustomerSearchCtrl"] [ng-show="activeCart.CustomerId"]').removeClass('ng-hide')
                        var jj = loadObject(ob.customer,customers)                        
                        $('[ng-controller="CustomerSearchCtrl"] [ng-show="activeCart.CustomerId"] a.ng-binding').html(jj.name)
                        
                        //09/17/2019                         
                        $('[ng-show="activeCart.Customer.ContactNumber"]+span').html('Nợ: '+jj.debt+(cfs.RewardPoint?(' Điểm: '+jj.point):''))
                         
                         
                    }else{
                        $('[ng-controller="CustomerSearchCtrl"] [ng-hide="activeCart.CustomerId"]').removeClass('ng-hide') 
                        $('[ng-controller="CustomerSearchCtrl"] [ng-show="activeCart.CustomerId"]').addClass('ng-hide')
                    }
                    
                    if(site_type==1){
                    
                        //DateStart
                        if(!ob.datestart||ob.datestart=="0000-00-00 00:00:00"){
                            ob.datestart = moment().format('DD/MM/YYYY HH:mm')
                            saveOrders()
                        }
                        $('[k-ng-model="activeCart.DateStart"]').val(ob.datestart)
                    }
                    //PurchaseDate
                    $('[k-ng-model="activeCart.PurchaseDate"]').val(ob.date)
                    
                    $('[ng-controller="CustomerSearchCtrl"] [ng-click="removeCustomer()"]').unbind('click')
                    $('[ng-controller="CustomerSearchCtrl"] [ng-click="removeCustomer()"]').click(function(e){
                        ob.customer = 0; saveOrders() ; _displayPrice(ob)                       
                        $('[ng-controller="CustomerSearchCtrl"] [ng-hide="activeCart.CustomerId"]').removeClass('ng-hide') 
                        $('[ng-controller="CustomerSearchCtrl"] [ng-show="activeCart.CustomerId"]').addClass('ng-hide') 
                        return false;                       
                    })
                    
                    var ret = []
                    //autoComplete
                    $('#customerSearchInput').unbind('keyup')
                    $('#customerSearchInput').keyup(function(e){
                        var h = $(this).siblings('[ng-show="completing"]')
                        //console.log(e)
                        //alert($(this).val())
                        var name = $(this).val()
                        //var ret = []
                        for(var i in customers){
                            if(i==0) ret = []
                             
                            if(customers[i].name.match(new RegExp(name,'i'))){
                                //console.log(customers[i].name)
                                ret.push(customers[i])
                            }
                        }
                        
                        h.find('ul').html('')
                        if(ret.length){
                            
                            for(var i in ret){
                                var _customerItemTempl2 = _customerItemTempl+""
                                var ret2 = {}
                                var suggestion = ret[i]
                                var $index = i
                                console.log(suggestion)
                                var m = _customerItemTempl.match(/\{\{([^\}]+)\}\}/g)
                                for(var j=0;j<m.length;j++){
                                    //console.log(m[j].substr(2,m[j].length-4))
                                    ret2[m[j]] = eval(m[j].substr(2,m[j].length-4))
                                    _customerItemTempl2 = _customerItemTempl2.replace(m[j],ret2[m[j]])
                                }
                                //console.log(ret2,_customerItemTempl2)
                                var ll = $(_customerItemTempl2)
                                ll.unbind('hover')
                                ll.hover(
                                    function(e){$(this).addClass('active')},
                                    function(e){$(this).removeClass('active')}
                                )
                                ll.unbind('click')
                                ll.click(function(e){
                                    var ii = $(this).attr('index')
                                    var jj = $(this).find('>span:first-child').html()
                                    ob.customer = ret[parseInt(ii)].id; saveOrders();_displayPrice(ob)
                                    $('[ng-controller="CustomerSearchCtrl"] [ng-hide="activeCart.CustomerId"]').addClass('ng-hide') 
                                    $('[ng-controller="CustomerSearchCtrl"] [ng-show="activeCart.CustomerId"]').removeClass('ng-hide')
                                    $('[ng-controller="CustomerSearchCtrl"] [ng-show="activeCart.CustomerId"] a.ng-binding').html(jj)
                                    h.addClass('ng-hide')
                                    
                                    //11/08/2019
                                    jj = ret[parseInt(ii)]
                                    $('[ng-show="activeCart.Customer.ContactNumber"]+span').html('Nợ: '+jj.debt+(cfs.RewardPoint?(' Điểm: '+jj.point):''))
                                })
                                h.find('ul').append(ll)
                                //break;
                            }
                            
                            h.removeClass('ng-hide')
                            
                            h.find('.autoNotFound').addClass('ng-hide')
                        }else{
                            h.find('.autoNotFound').removeClass('ng-hide')
                        }
                    })
                    
                    //add customer
                    $('[ng-click="addCustomer()"]').attr('href','javascript:void(0)')
                    $('[ng-click="addCustomer()"]').unbind('click')
                    $('[ng-click="addCustomer()"]').click(function(){
                        $('.k-window-customer').css('display','block')
                        $('.k-window-customer .k-i-close,[ng-click="cancel()"]').click(function(e){
                            $('.k-window-customer').css('display','none')
                            return false
                        })
                        $('.prettyradio').unbind('click')
                        $('.prettyradio').click(function(){                             
                            var ic = $(this).find('>input').is(':checked')
                            $(this).parent().siblings('aside').find('a').removeClass('checked')
                            $(this).parent().siblings('aside').find('input').removeAttr('checked')
                            $(this).find('>a').addClass('checked')
                            $(this).find('>input').attr('checked','checked')
                            var n = $(this).find('>input').attr('ng-model')
                            if(n=='customer.Type' && !ic && $(this).find('>input').v()=='1'){
                                //console.log()
                                $('[ng-show="customer.Type== types.values.Organization"]').removeClass('ng-hide')
                            }else if(n=='customer.Type'){
                                $('[ng-show="customer.Type== types.values.Organization"]').addClass('ng-hide')
                            }
                        })
                        
                        $( '[data-role="datepicker"]:not(.hasDatepicker)+span' ).click(function(){
                            $(this).prev().focus()
                        })
                        
                        $( '[data-role="datepicker"]:not(.hasDatepicker)' ).datepicker( {
                            dateFormat: "dd/mm/yy"   ,
                            beforeShow: function() {
                                //setTimeout(function(){
                                    $('.ui-datepicker').css('z-index', 100000);
                                //}, 0);
                            }
                        }  );
                         
                        /*$('[data-role="datepicker"]').datetimepicker({
                            format: "d/m/Y",
                            //showMeridian: true,
                            autoclose: true,
                            todayBtn: true
                        });*/
                        searching = false;
                        locationItemTempl = $('kv-customer-form #locationItemTempl').html();
                        $('kv-customer-form #locationSearchInput').unbind('keyup')
                        $('kv-customer-form #locationSearchInput').keyup(function(e){
                            var name = $(this).val()
                            if(name.length>1 && !searching){
                                searching = true;
                                
                                /*
                                $.ajax('ajax.php?action=suggestzone',{
                                    data: 'kw='+name,
                                    method: "POST",
                                    dataType: 'json',
                                    success: function(d){
                                        console.log(d);
                                        if(d.status=='1'){
                                            
                                            //console.log(d);
                                            
                                            $('kv-search-location .autocomplete > div ul').html('');
                                            
                                            if(d.data.length>0){
                                                
                                                for(var i in d.data){
                                                    locationItemTempl2 = locationItemTempl;
                                                    
                                                    //console.log(d.data[i].name);
                                                    
                                                    locationItemTempl2 = locationItemTempl2.replace(/\{\{name\}\}/g,d.data[i].province+' - '+d.data[i].description+' '+d.data[i].name );
                                                    locationItemTempl2 = locationItemTempl2.replace(/\{\{\$index\}\}/g,i);
                                                    
                                                    $('kv-search-location .autocomplete > div ul').append(locationItemTempl2);
                                                }
                                                
                                                $('kv-search-location .autocomplete > div ').removeClass('ng-hide');
                                                
                                                //hover
                                                $('kv-search-location .autocomplete > div').unbind('hover')
                                                $('kv-search-location .autocomplete > div').hover(
                                                    function(){
                                                        //$('kv-search-location .autocomplete > div').removeClass('ng-hide');
                                                    },
                                                    function(){
                                                        $('kv-search-location .autocomplete > div').addClass('ng-hide');
                                                    }
                                                );
                                                $('kv-search-location .autocomplete > div ul li').unbind('hover')
                                                $('kv-search-location .autocomplete > div ul li').hover(
                                                    function(){
                                                        $(this).toggleClass('k-state-hover');
                                                    },function(){
                                                        $(this).toggleClass('k-state-hover');
                                                    }
                                                );
                                                //click
                                                $('kv-search-location .autocomplete > div ul li').unbind('click')
                                                $('kv-search-location .autocomplete > div ul li').click(
                                                    function(){
                                                         
                                                        $('#locationSearchInput').val($(this).attr('val'));
                                                         
                                                        $('kv-search-location .autocomplete > div').addClass('ng-hide');//.hide();
                                                         
                                                         
                                                    }
                                                );
                                                
                                            }
                                             
                                        }else{
                                             //alert(d.error);
                                             toastr["error"](d.error);
                                        } 
                                        searching = false;
                                    }
                                }); */
                                db.Locations.filter(function(a){return a.Name.toLowerCase().indexOf(name.toLowerCase())>=0})
                                .toArray()                                 
                                .then(function(data){ //console.log('data:',data)
                                    $('kv-customer-form kv-search-location .autocomplete > div ul').html('');
                                    if(data.length>0){
                                            
                                        for(var i in data){
                                            locationItemTempl2 = locationItemTempl;
                                            
                                            //console.log(d.data[i].name);
                                            
                                            locationItemTempl2 = locationItemTempl2.replace(/\{\{name\}\}/g,data[i].Name );
                                            locationItemTempl2 = locationItemTempl2.replace(/\{\{\$index\}\}/g,data[i].Id);
                                            
                                            $('kv-customer-form kv-search-location .autocomplete > div ul').append(locationItemTempl2);
                                            
                                            if(i>=30) break;
                                        }
                                        
                                        $('kv-customer-form kv-search-location .autocomplete > div').removeClass('ng-hide');
                                        
                                        //hover
                                        $('kv-customer-form kv-search-location .autocomplete > div').hover(
                                            function(){
                                                //$('kv-customer-form kv-search-location .autocomplete > div').removeClass('ng-hide');
                                            },
                                            function(){
                                                $('kv-customer-form kv-search-location .autocomplete > div').addClass('ng-hide');
                                            }
                                        );
                                        
                                        $('kv-customer-form kv-search-location .autocomplete > div ul li').hover(
                                            function(){
                                                $(this).toggleClass('k-state-hover');
                                            },function(){
                                                $(this).toggleClass('k-state-hover');
                                            }
                                        );
                                        $('kv-customer-form kv-search-location .autocomplete > div ul li').unbind('click')
                                        //click
                                        $('kv-customer-form kv-search-location .autocomplete > div ul li').click(
                                            function(){
                                                 
                                                $('kv-customer-form #locationSearchInput').val($(this).attr('val'));
                                                 
                                                $('kv-customer-form kv-search-location .autocomplete > div').addClass('ng-hide')//.hide(); 06-30-2017
                                                 
                                                 
                                            }
                                        );
                                        
                                    }
                                    searching = false;
                                })
                                
                                
                            }
                        })
                        
                        $('#groupSearchInput').unbind('keyup')
                        $('#groupSearchInput').keyup(function(e){
                            var name = $(this).val()
                            if(name.length>0 && !searching){
                                searching = true;
                                var d = {status:'0',data:[]}
                                for(var i in customer_groups){
                                    if(customer_groups[i].name.toLowerCase().indexOf(name.toLowerCase())>=0){
                                        d.status = '1'
                                        
                                        d.data.push(customer_groups[i])
                                    }
                                }
                                if(d.status=='0'){
                                    //d.error = 'Không có kết quả phù hợp'
                                    d.error = 'Chưa tồn tại nhóm này, sẽ chèn nhóm mới này'
                                }
                                
                                if(d.status=='1'){
                                    
                                    //console.log(d);
                                    
                                    $('kv-search-group .autocomplete > div ul').html('');
                                    
                                    if(d.data.length>0){
                                        
                                        for(var i in d.data){
                                            locationItemTempl2 = locationItemTempl;
                                            
                                            //console.log(d.data[i].name);
                                            
                                            locationItemTempl2 = locationItemTempl2.replace(/\{\{name\}\}/g,d.data[i].name );
                                            locationItemTempl2 = locationItemTempl2.replace(/\{\{\$index\}\}/g,i);
                                            
                                            $('kv-search-group .autocomplete > div ul').append(locationItemTempl2);
                                        }
                                        
                                        $('kv-search-group .autocomplete > div ').removeClass('ng-hide');
                                        
                                        //hover
                                        $('kv-search-group .autocomplete > div').unbind('hover')
                                        $('kv-search-group .autocomplete > div').hover(
                                            function(){
                                                //$('kv-search-group .autocomplete > div').removeClass('ng-hide');
                                            },
                                            function(){
                                                $('kv-search-group .autocomplete > div').addClass('ng-hide');
                                            }
                                        );
                                        
                                        $('kv-search-group .autocomplete > div ul li').unbind('hover')
                                        $('kv-search-group .autocomplete > div ul li').hover(
                                            function(){
                                                $(this).toggleClass('k-state-hover');
                                            },function(){
                                                $(this).toggleClass('k-state-hover');
                                            }
                                        );
                                        //click
                                        $('kv-search-group .autocomplete > div ul li').unbind('click')
                                        $('kv-search-group .autocomplete > div ul li').click(
                                            function(){
                                                 
                                                $('#groupSearchInput').val($(this).attr('val'));
                                                 
                                                $('kv-search-group .autocomplete > div').addClass('ng-hide');//.hide();
                                                 
                                                 
                                            }
                                        );
                                        
                                    }
                                     
                                }else{
                                     //alert(d.error);
                                     toastr["error"](d.error);
                                } 
                                searching = false;
                                   
                            }
                        })
                    })
                    //alert($('[data-role="datetimepicker"].ng-pristine').length)
                    $('[data-role="datetimepicker"].ng-pristine').datetimepicker({
                         
                        format:'d/m/Y H:i' ,
                         
                        onShow : function(){
                            console.log(arguments)
                            //alert(1)
                        },
                        
                        maxDate: moment().format('YYYY/MM/DD')
                    });
                    
                    /*$('[data-role="datetimepicker"].ng-pristine').unbind('focus')
                    $('[data-role="datetimepicker"].ng-pristine').focus(function(){
                        $(this).datetimepicker('show')
                    })*/
                    
                    $('[data-role="datetimepicker"].ng-pristine+span').unbind('click')
                    $('[data-role="datetimepicker"].ng-pristine+span').click(function(){
                        $(this).prev().focus()
                    })
                    
                    //add 12/02/2020
                    if(site_type==1){ 
                        $('[k-ng-model="activeCart.DateStart"]').unbind('change')
                        $('[k-ng-model="activeCart.DateStart"]').change(function(e){
                            ob.datestart = ( $(this).val() )//ob.datestart = xdate2( $(this).val() )                            
                            saveOrders()
                        })
                    }      
                    //fixed 12/02/2020              
                    $('[k-ng-model="activeCart.PurchaseDate"]').unbind('change')
                    $('[k-ng-model="activeCart.PurchaseDate"]').change(function(e){
                        ob.date =  ( $(this).val() )  //ob.date = xdate2( $(this).val() )  
                        saveOrders()                         
                    })
                    
                    //products
                    displayProducts(ob)
                    //button
                    thongbaonhabep(ob);
                    
                    function discountOnReturn(e){ console.log('xxx',ob)
                        $('#discountOnReturn').addClass('popping');
                         
                        $('#discountOnReturn .kv2Pop').css({
                            top: $(this).offset().top -47-getScrollY(),
                            left: $(this).offset().left-250+15-getScrollY(1)
                        })
                        
                        $('#discountOnReturn .ng-binding:nth(0)').html(formatCurrency(ob._discount))
                        $('#discountOnReturn .ng-binding:nth(1)').html(formatCurrency(ob._discount/ob._price*ob.price))
                        
                        $('#discountOnReturn .ng-binding:nth(1)').unbind('click')
                        $('#discountOnReturn .ng-binding:nth(1)').click(function(e){ //alert(1)
                            $('#discountOnReturn [ng-model="ReturnDiscount"]').val(ob._discount/ob._price*ob.price)
                        })
                        
                        $('#discountOnReturn [ng-model="ReturnDiscount"]').val(parseInt(ob.discount)==0 ?ob._discount/ob._price*ob.price : 0)
                        
                        $('#discountOnReturn [ng-model="ReturnDiscount"]').unbind('blur')
                        $('#discountOnReturn [ng-model="ReturnDiscount"]').blur(function(e){
                            var pa = $(this).val()
                         
                            pa = parseInt(pa); 
                            if(isNaN(pa)){
                                pa = 0;$(this).val(pa)
                            } 
                            if(pa<0){
                                pa = -pa
                                $(this).val(pa)
                            }
                            ob.discount = pa
                            $('.kv2Pay .ng-binding:nth(7)').html(formatCurrency(pa))   
                            
                            displayPrice(ob) 
                        })
                        
                        $('#discountOnReturn [ng-model="ReturnDiscount"]').val(ob.discount).focus()
                        
                        var si2=setInterval(function(){                         
                            if($(document.activeElement).parents('#discountOnReturn').length==0 && !$(document.activeElement).is('#discountOnReturn')) {                    
                                $('#discountOnReturn').removeClass('popping')
                                clearInterval(si2)
                            }
                        },100)
                        return false
                    }
                    
                    function discountOnOrder(e){
                        $('#discountOnOrder').addClass('popping')
                         
                        $('#discountOnOrder .kv2Pop').css({
                            top: $(this).offset().top -15-getScrollY(),
                            left: $(this).offset().left-250-10-getScrollY(1)
                        })
                        
                        $('#discountOnOrder [ng-model="DiscountValue"]').focus()
                        $('#discountOnOrder [ng-model="DiscountType"]>a.active').removeClass('active')
                        $('#discountOnOrder [ng-model="DiscountType"]>a:nth(0)').addClass('active')
                        $('#discountOnOrder [ng-model="DiscountValue"]').val(ob.discount)
                        $('#discountOnOrder [ng-model="DiscountType"]>a').unbind('click')
                        var ij = 0;
                        $('#discountOnOrder [ng-model="DiscountType"]>a').click(function(e){
                            $('#discountOnOrder [ng-model="DiscountType"]>a.active').removeClass('active')
                            $(this).addClass('active')
                            //alert($(this).index())
                            ij = $(this).index()
                            if(ij){
                                $('#discountOnOrder [ng-model="DiscountValue"]').val((ob.discount/ob.price*100).toFixed(2))
                            }else{
                                $('#discountOnOrder [ng-model="DiscountValue"]').val(ob.discount)
                            }
                            return false
                        })
                        $('#discountOnOrder [ng-model="DiscountValue"]').unbind('change')
                        $('#discountOnOrder [ng-model="DiscountValue"]').change(function(e){
                            var tu = $(this).val()
                            //try{
                                tu=parseInt(tu); if(isNaN(tu)){tu=0;$(this).val(tu)}
                                if(tu<0){
                                    tu=-tu
                                    $(this).val(tu)
                                } 
                            //}catch(t){
                            //    tu=0
                            //}                            
                            ob.discount = ij==0?tu:tu*ob.price/100
                            displayPrice(ob)
                        })
                        
                        var si=setInterval(function(){                         
                            if($(document.activeElement).parents('#discountOnOrder').length==0 && !$(document.activeElement).is('#discountOnOrder')) {                    
                                $('#discountOnOrder').removeClass('popping')
                                clearInterval(si)
                            }
                        },100)
                        return false
                    }
                    
                    function feeOnReturn(){
                        $('#feeOnReturn').addClass('popping');
                         
                        $('#feeOnReturn .kv2Pop').css({
                            top: $(this).offset().top -15-getScrollY(),
                            left: $(this).offset().left-250-10-getScrollY(1)
                        })
                        
                        $('#feeOnReturn .kv2Pop [ng-model="DiscountValue"]').focus()
                        
                        $('#feeOnReturn [ng-model="DiscountType"] a.active').removeClass('active')
                         
                        if(ob.discountratio==undefined){
                            $('#feeOnReturn [ng-model="DiscountType"]>a:nth(0)').addClass('active')                             
                        }else{                             
                            $('#feeOnReturn [ng-model="DiscountType"]>a:nth(1)').addClass('active')
                        }
                         
                        $('#feeOnReturn [ng-model="DiscountType"]>a:nth(0)').unbind('click')
                        $('#feeOnReturn [ng-model="DiscountType"]>a:nth(1)').unbind('click')
                        
                        $('#feeOnReturn [ng-model="DiscountType"]>a:nth(0)').click(function(e){
                            $('#feeOnReturn [ng-model="DiscountType"]>a.active').removeClass('active')
                            $(this).addClass('active')
                            if(ob._feeratio!=undefined){
                                delete ob._feeratio
                                $('#feeOnReturn [ng-model="DiscountValue"]').val( 
                                    (ob.fee>0?ob.fee:'') 
                                )
                            }
                            return false
                        })
                        
                        $('#feeOnReturn [ng-model="DiscountType"]>a:nth(1)').click(function(e){
                            $('#feeOnReturn [ng-model="DiscountType"]>a.active').removeClass('active')
                            $(this).addClass('active')
                            if(ob._feeratio==undefined){
                                ob._feeratio = (100*ob.fee/(ob.price-ob.discount)).toFixed(2)
                                $('#feeOnReturn [ng-model="DiscountValue"]').val( 
                                    (ob._feeratio>0?ob._feeratio:'') 
                                )
                            }
                            return false
                        })
                         
                        $('#feeOnReturn [ng-model="DiscountValue"]').val(ob._feeratio==undefined?
                            (ob.fee>0?ob.fee:''):(ob._feeratio>0?ob._feeratio:'')
                        )
                         
                        $('#feeOnReturn [ng-model="DiscountValue"]').unbind('change')
                        $('#feeOnReturn [ng-model="DiscountValue"]').change(function(e){
                            var tu = $(this).val()
                            //try{
                                tu=parseInt(tu); if(isNaN(tu)){tu = 0;$(this).val(tu)} 
                                if(tu<0){
                                    tu=-tu
                                    $(this).val(tu)
                                } 
                                if(ob._feeratio!=undefined){
                                    if(tu>100){
                                        toastr['error']('Bạn phải nhập số từ 0-100');
                                        tu = 0
                                        $(this).val(tu)
                                    } 
                                }
                            //}catch(t){
                            //    tu=0
                            //}
                            
                            if(ob._feeratio!=undefined){
                                ob._feeratio = tu
                                var id = (ob._feeratio*(ob.price-ob.discount)/100).toFixed(0)
                                if(id!=ob.fee){
                                    ob.fee = id
                                     
                                    /*if(ob.status==0){
                                        ob.status = -2
                                    }*/
                                }
                            }else{
                                var id = tu
                                if(id!=ob.fee){
                                    ob.fee = id
                                     
                                    /*if(ob.status==0){
                                        ob.status = -2
                                    }*/
                                }
                            }
                             
                            ////saveOrders()
                            ////displayProducts(ob)
                            displayPrice(ob)
                        })
                        
                        var si3=setInterval(function(){                         
                            if($(document.activeElement).parents('#feeOnReturn').length==0 && !$(document.activeElement).is('#feeOnReturn')) {                    
                                $('#feeOnReturn').removeClass('popping')
                                clearInterval(si3)
                            }
                        },100)
                        return false
                    }
                    
                    $('[kv-popup-anchor="discountOnReturn"]').unbind('click') 
                    $('[kv-popup-anchor="discountOnReturn"]').click(function(e){ //alert(ob.invoice)
                        if(ob.invoice>0){
                            discountOnReturn.call(this)
                        }else{
                            discountOnOrder.call(this)
                        }
                        return false;
                    })   
                    
                    $('[kv-popup-anchor="feeOnReturn"]').unbind('click') 
                    $('[kv-popup-anchor="feeOnReturn"]').click(function(){
                        feeOnReturn.call(this)
                        return false;
                    })    
                     
                    //kv2Pay
                    $('.kv2Pay .ng-binding:nth(2)').unbind('click')
                    $('.kv2Pay .ng-binding:nth(2)').click(discountOnOrder)
                    
                    //employee
                    $('.employeeBox select').html('')
                    
                    var isF,sim = function(){
                        $('.k-animation-container').data('item','employeeBox')
                        $('.k-animation-container ul').html('')
                        isF = false
                        for(var ki in users){
                            if(st(users[ki].id)['3.1.2']==undefined){
                                console.log('sim undefined:',vkl,users[ki].id)
                                continue
                            } 
                            
                            if(ob.user==null)
                                ob.user = create
                            
                            //if(vkl['admin']==undefined && vkl["1.1.5"]==undefined ){
                                //neu k la admin hoac quan tri chi nhanh
                                 
                                //ob.user = create
                                //if(users[ki].id!=create) continue
                            //}
                            
                            isF = true
                            
                            var ck = ((ob.user && ob.user == users[ki].id)||(ob.user==null && isF /* ki==0*/))
                            
                            $('.employeeBox select').append('<option value="'+users[ki].id+'"'+(ck?' selected':'')+'>'+users[ki].name+'</option>')
                            if(ck) ob.user = users[ki].id
                            var _t = templ3.replace("{{index}}",ki).replace("{{name}}",users[ki].name)
                            _t = $(_t)
                            _t.removeClass('k-state-selected k-state-focused')
                            _t.hover(function(e){
                                $(this).siblings('li').removeClass('k-state-hover')
                                $(this).addClass('k-state-hover')
                            })
                            _t.click(function(){
                                $('.employeeBox .k-input').html($(this).html())
                                $('[k-data-source="employees"] option[selected]').removeAttr('selected')
                                $('[k-data-source="employees"] option:nth('+$(this).attr('data-offset-index')+')').attr('selected','selected')
                                $(this).siblings('li').removeClass('k-state-selected k-state-focused')
                                $(this).addClass('k-state-selected k-state-focused')
                                
                                var no = users[$(this).attr('data-offset-index')].id
                                if(no!=ob.user){
                                    ob.user = no;
                                    ////if(ob.status==0){
                                        ob.status = -2
                                    ////}
                                    saveOrders()
                                    thongbaonhabep(ob)
                                }
                                /*$('.k-animation-container,.k-animation-container>div').css('display','none')
                                $('.k-animation-container').css({
                                    overflow: 'hidden'}
                                )
                                $('.k-animation-container>div').css({
                                    transform: 'translateY(-86px)'
                                })*/
                                $('.employeeBox').click()
                            })                        
                            $('.k-animation-container ul').append(_t)
                            
                            
                            isF = false;
                            
                        }
                        $('.k-animation-container ul [data-offset-index="'+
                            $('[k-data-source="employees"] option[selected]').index()+
                            '"]').addClass('k-state-selected k-state-focused')
                         
                    }    
                    sim()
                    $('.employeeBox .k-input').html($('.employeeBox select option[selected]').html())   
                    
                    $('.employeeBox').unbind('click')
                    $('.employeeBox').click(function(e){
                        if($('.k-animation-container').data('item')=='employeeBox' && $('.k-animation-container').is(':visible')){
                            $('.k-animation-container,.k-animation-container>div').css('display','none')
                            $('.k-animation-container').css({
                                overflow: 'hidden'}
                            )
                            $('.k-animation-container>div').css({
                                transform: 'translateY(-86px)'
                            })
                        }else{
                            sim()
                            $('.k-animation-container,.k-animation-container>div').css('display','block')
                            $('.k-animation-container>div').width(150)
                            $('.k-animation-container').css({
                                top:$('.employeeBox').offset().top+$('.employeeBox').height()-getScrollY(),
                                left:$('.employeeBox').offset().left+10-getScrollY(1),
                                overflow: 'visible'}
                            )
                            $('.k-animation-container>div').css({
                                transform: 'translateY(0px)'
                            })    
                        }
                        
                    })
                    
                    //thong bao nha bep
                    
                }else{
                    //alert('y')
                }
            })
            
            console.log('addTabs s2')
            
            /*$('[kv-tab-id="cart.uuid"] li[data-id]>span').unbind('click')
            $('[kv-tab-id="cart.uuid"] li[data-id]>span').click(function(e){
                //close tab
                var ii = $(this).parent().data('id')
                //alert(ii)
                var ob = loadObject(ii,orders,'code')
                if(ob.products.length>0){
                    //alert
                }else{
                    
                }
            })*/
            
            $('.kv2Right [ng-click="moveLeft()"]').unbind('click')
            $('.kv2Right [ng-click="moveLeft()"]').click(function(e){
                //moveLeft()
                //get current 
                var cui = 0,ts=getComputedStyle($('.kv2Right kv-scroll-tabs .scroll-lane')[0]).transform
                if(ts!='none'){
                    cui = parseInt(ts.match(/(-?\d+), 0\)/)[1])
                }
                if(cui+200<=0)
                    $('.kv2Right kv-scroll-tabs .scroll-lane').css('transform','translateX('+(cui+200)+'px)')
            })
            
            console.log('addTabs s3')
            
            $('.kv2Right [ng-click="moveRight()"]').unbind('click')
            $('.kv2Right [ng-click="moveRight()"]').click(function(e){
                //moveRight()
                //get current 
                var cui = 0,ts=getComputedStyle($('.kv2Right kv-scroll-tabs .scroll-lane')[0]).transform
                console.log(ts)
                if(ts!='none'){
                    cui = parseInt(ts.match(/(-?\d+), 0\)/)[1])
                }
                var cui3 = 0
                $('.scroll-lane li').each(function(){
                    cui3 += 3 + $(this).width()
                })
                if(cui-200+cui3>=170)
                    $('.kv2Right kv-scroll-tabs .scroll-lane').css('transform','translateX('+(cui-200)+'px)')
            })
            
            console.log('addTabs s4')
            //alert(ct3)
            //autoClick = true
            $('[kv-tab-id="cart.uuid"] li[data-id="'+ct3+'"]>a').click(); console.log('addTabs s4a')
            clicking = false; console.log('addTabs s4b')
            $('[ng-click="saveCustomer()"]').unbind('click')
            $('[ng-click="saveCustomer()"]').click(function(){
                if(clicking) return false;
                clicking = true
                var a = _serialize($('kv-customer-form'))
                var b= parse_str(a)
                
                if(b.Name == ''){
                    toastr['error']('Bạn chưa nhập tên khách hàng');
                    return false;
                }
                //delete b.WardName
                //delete b.searchParam
                
                /*
Address
Code
Comments
ContactNumber
Email
LocationName
Name
Organization
TaxCode
Type              

type=0&
code=&
name=ten&
id=&
phone=&
address=&
zone=&
birthday=20%2F06%2F2016&
email=&
group=2&
organization=&
taxcode=&
note= 
                */
                
                //console.log(b)
                
                var c = {
                    type : b.Type,
                    code : b.Code, 
                    name : b.Name, 
                    id : '', 
                    phone : b.ContactNumber, 
                    address : b.Address, 
                    zone  : b.LocationName,
                    birthday  : b.BirthDate,
                    email  : b.Email,
                    group  : b.Group,
                    organization  : b.Organization,
                    taxcode  : b.TaxCode,
                    note  : b.Comments,
                }
                if(b.Gender) c.gender = b.Gender;
                //console.log(decodeURIComponent(decodeURIComponent( http_build_query(c) )))
                
                $.ajax('ajax.php?action=customer',{
                    data: decodeURIComponent(decodeURIComponent( http_build_query(c) ))+'&branch='+cb ,
                    method: "POST",
                    dataType: 'json',
                    success: function(d){
                        console.log(d);
                        if(d.status=='1'){
                            $('.k-window-customer .k-i-close').click()
                            //cancel();
                            loadJsonCustomers(function(){
                                var ob = loadObject(ct3,orders,'code')
                                ob.customer = d.customer_id ; saveOrders(); _displayPrice(ob)
                                
                                $('[ng-controller="CustomerSearchCtrl"] [ng-hide="activeCart.CustomerId"]').addClass('ng-hide') 
                                $('[ng-controller="CustomerSearchCtrl"] [ng-show="activeCart.CustomerId"]').removeClass('ng-hide')
                                var jj = loadObject(ob.customer,customers)                        
                                $('[ng-controller="CustomerSearchCtrl"] [ng-show="activeCart.CustomerId"] a.ng-binding').html(jj.name)
                                clicking = false 
                                
                                //09/17/2019                         
                                $('[ng-show="activeCart.Customer.ContactNumber"]+span').html('Nợ: '+jj.debt+(cfs.RewardPoint?(' Điểm: '+jj.point):''))
                            });
                            loadJsonCustomerGroups();
                            
                            
                        }else{
                             //alert(d.error);
                             toastr["error"](d.error);
                        } 
                    }
                });
                
                return false;
            })
            
            console.log('addTabs s5')
            
            //2019
            //$('.kv2Right [ng-click="moveRight()"]').click()
            //$('.ovh.scroll-lane ul li>a.active').parent().index()
            var bq = $('.ovh.scroll-lane ul li>a.active')
            if(bq.length>0){
                bq = bq.parent().index()
                if(bq>0)
                {
                    while(bq-->1){
                        setTimeout(function(){
                            $('.kv2Right [ng-click="moveRight()"]').click()
                        },1000)
                    }
                }
            }
            
            //////$('.loading').hide()
            console.log('addTabs s6')
            ////lala()
            
            if(typeof _loading != 'undefined')
                clearTimeout(_loading)
                
            _currentTime()
            console.log('addTabs s7')
        }
        /*
        function moveRight(){
            
        }
        
        function moveLeft(){
            
        }*/
        
        
        function emptyOrder(_ct,prefix,iv){  //2019 add prefix,iv           
            var mk= {
                status: -1,
                table:_ct,
                type:prefix=='TH'?11:1,
                products:[],
                code:nCode(prefix),
                date:nCode2()
            }
            if(iv) //mk.invoice = iv;
                mk = $.extend(mk,iv)
            return mk;
        }
        
        function nCode(prefix){
            prefix = prefix==undefined?'DH':prefix
            //var date = new Date()
            //return prefix+date.getFullYear()+spr(date.getMonth()+1)+spr(date.getDate())+spr(date.getHours())+spr(date.getMinutes())+spr(date.getSeconds())
            return prefix + (new Date().getTime())
        }
        
        
        function _checkIn(all,one){
            return (','+all+',').indexOf(','+one+',')>=0
        }
        
        function findOrdersByTable(tab){
            var ret = []
            if(!orders || orders.length==0) return ret;
            
            for(var i in orders){                 
                if(orders[i].table && _checkIn(orders[i].table,tab)){
                    ret.push( orders[i] )
                }
            }
            return ret;
        }
        
        function swipePrev(){ console.log('swipePrev')
            cp1--
            loadTabs11()
        }
        
        function swipeNext(){ console.log('swipeNext')
            cp1++
            loadTabs11()
        }
        
        function tabstrip1(){ console.log('tabstrip1')
            $('.proTabsList ul >li').unbind('click')
            $('.proTabsList ul >li').click(function(){
                var t = $(this);
                t2 = t.data('id');
                ct1 = parseInt(t2);
                localStorage.ct1 = ct1;
                
                t3 = t.parent().find('a');
                
                t3.removeClass('active');
                 
                t.find('a').addClass('active');
                
                //loc 
                loadTabs11()
            })
            
            prevnext()
        }
        
        
        
        
        
        
        function autoMenu(){
            return $('[ng-model="toggles.autoMenu"]').is(':checked') 
        }
        
        function descriptionChanged(){}
        
        function descriptionChanged2(){
            //console.log('descriptionChanged',ce)
            var kk = loadObject(ce,tables)
            if(kk){
                kk.note = $('#tableDescription textarea').val()
                console.log('descriptionChanged:',kk)
                
                //save localStorage
                savetables()
                if(!kk.note){
                    $('.swiper-container li[data-id="'+ce+'"] aside').removeClass('tableActive')
                }else{
                    $('.swiper-container li[data-id="'+ce+'"] aside').addClass('tableActive')
                }
                
                if(ce>0)
                //save data
                $.ajax('ajax.php?action=table',{
                    data: "note="+encodeURIComponent(kk.note)+"&id="+ce+"&branch="+cb,
                    method: "POST",
                    dataType: 'json',
                    success: function(d){
                        console.log(d);
                    }
                })
            }
            //$('#tableDescription').removeClass('popping')
        }
        
        function tableFilterChanged(){ console.log('tableFilterChanged')
            loadTabs11()
        }
        
        function showProductMenu(that ){ console.log('showProductMenu:',that)
            $('body').removeClass('showRight')
            //console.log($(that).parents('ul').find('a.active'))
            //console.log(e) 
            //autoClick = e.clientX==undefined ; alert(autoClick)
            $(that).parent().parent().find('a.active').removeClass('active')
            $(that).addClass('active')
            
            $('[ng-show="viewType == \'table\'"]').addClass('ng-hide')
            $('[ng-show="viewType != \'table\'"]').removeClass('ng-hide')
            //$('[ng-show="toggles.showCategories && !toggles.table"]').removeClass('ng-hide')
            $('[ng-class="{\'kv2ProMenu\':!toggles.table}"]').addClass('kv2ProMenu')
            $('[ng-hide="!toggles.table"]').addClass('ng-hide')
            
            $('[ng-class="{\'coffeeMenu-list\':!slide.items[0].isTable}"').addClass('coffeeMenu-list')
            
            loadTabs11()
        }
        
        function showTables(that ){ console.log('showTables',that)
            $('body').removeClass('showRight')
            //console.log($(that).parent().parent().find('a.active'))
            //autoClick = e.clientX==undefined ; alert(autoClick)
            
            $(that).parent().parent().find('a.active').removeClass('active')
            $(that).addClass('active')
            
            $('[ng-show="viewType == \'table\'"]').removeClass('ng-hide')
            $('[ng-show="viewType != \'table\'"]').addClass('ng-hide')
            //$('[ng-show="toggles.showCategories && !toggles.table"]').addClass('ng-hide')
            $('[ng-class="{\'kv2ProMenu\':!toggles.table}"]').removeClass('kv2ProMenu')
            $('[ng-hide="!toggles.table"]').removeClass('ng-hide')
            
            $('[ng-class="{\'coffeeMenu-list\':!slide.items[0].isTable}"').removeClass('coffeeMenu-list')
            
            loadTabs11()
        }
        
        
        function showRight(that ){
            $(that).parent().parent().find('a.active').removeClass('active')
            $(that).addClass('active')
            
            $('body').addClass('showRight')
            
            if($('.searchCustomer .paymentButton').length) $('.searchCustomer .paymentButton').remove()
        }
        
                 
                if(!users )
                    loadJsonUsers() 
                setInterval(function(){loadJsonUsers()},3600000)     
                     
                if(!customers )    
                    loadJsonCustomers() 
                setInterval(function(){loadJsonCustomers()},3600000)             
                 
                if( table_groups===null )//!table_groups
                    loadJsonTableGroups(function(){            
                        loadTabs1()                 
                    })
                            
                else loadTabs1()
                setInterval(function(){loadJsonTableGroups()},3600000)    
                
                if(tables===null)
                    loadJsonTables(function(){            
                        loadTabs11()
                    })
        
                else{
                    filterTables();
                    loadTabs11()
                } 
                setInterval(function(){loadJsonTables()},3600000) 
                
                
var jh = false
    function camera(){
        if(document.location.protocol!='https:') {
            toastr.warning('Để sử dụng chức năng này bạn phải sử dụng giao thức https')
            return
        }
        var ty = Math.min($(window).width(),460)
        $('.k-window-camera').css({
            width: ty,
            'left': ($(window).width()-ty)/2 ,
            'display': 'block'
        })
        $('.k-overlay').css({
            'display': 'block',
            'z-index': 10006
        })
        $('.k-window-camera .k-i-close,.k-window-camera [ng-click="cancel()"]').unbind('click')
        $('.k-window-camera .k-i-close,.k-window-camera [ng-click="cancel()"]').click(function(e){
            $('.k-window-camera').hide()
            $('.k-overlay').css({
                 
                'z-index': -6 // 10004
            })
            return false
        })
        
        if(!jh){
            var lastResult = null
        	//var streamLabel = Quagga.CameraAccess.getActiveStreamLabel();
        	var deviceId = null;
        	var cc = 0;
        	var ii = function(){
        		Quagga.init({
        			inputStream : {
        			  name : "Live",
        			  type : "LiveStream",
        			  //target: document.querySelector('#interactive')    ,
        			  constraints: {
        					width: {min: 640},
        					height: {min: 480},
        					aspectRatio: {min: 1, max: 100},
        					facingMode: "environment", // or user
        					deviceId: deviceId
        				}
        			},
        			decoder : {
        			  readers : ["code_128_reader", "ean_reader"]
        			},
        			locator: {
        				patchSize: "medium",
        				halfSample: true
        			},
        			numOfWorkers: 4,
        			frequency: 10,
        			 
        			locate: true
        		  }, function(err) {
        			  if (err) {
        				  console.log(err);
        				  return
        			  }
        			  console.log("Initialization finished. Ready to start");
        			  Quagga.start();
        		  });
        	}
        	Quagga.CameraAccess.enumerateVideoDevices()
        	.then(function(devices) {		 
        		devices.forEach(function(device) {			
        			if( device.label.indexOf("back")>-1 ){
        				deviceId = device.deviceId || device.id;	
        				
        				ii()
        				
        			}		
        		});
        		if(deviceId==null){
        			devices.forEach(function(device) {			
        			if( cc++ == 0 /*|| streamLabel === device.label*/ ){
        				deviceId = device.deviceId || device.id;	
        				
        				ii()
        				
        			}		
        		});
        		}
        	});
        	
            //App.init();
        	 
            Quagga.onProcessed(function(result) {
                var drawingCtx = Quagga.canvas.ctx.overlay,
                    drawingCanvas = Quagga.canvas.dom.overlay;
        
                if (result) {
                    if (result.boxes) {
                        drawingCtx.clearRect(0, 0, parseInt(drawingCanvas.getAttribute("width")), parseInt(drawingCanvas.getAttribute("height")));
                        result.boxes.filter(function (box) {
                            return box !== result.box;
                        }).forEach(function (box) {
                            Quagga.ImageDebug.drawPath(box, {x: 0, y: 1}, drawingCtx, {color: "green", lineWidth: 2});
                        });
                    }
        
                    if (result.box) {
                        Quagga.ImageDebug.drawPath(result.box, {x: 0, y: 1}, drawingCtx, {color: "#00F", lineWidth: 2});
                    }
        
                    if (result.codeResult && result.codeResult.code) {
                        Quagga.ImageDebug.drawPath(result.line, {x: 'x', y: 'y'}, drawingCtx, {color: 'red', lineWidth: 3});
                    }
        			
        			if (result.codeResult) {
                        console.log(result.codeResult)
                    }
                }
            });

            Quagga.onDetected(function(result) {
                var code = result.codeResult.code;
        
                //if (lastResult !== code) {
                    lastResult = code;
                    /*var $node = null, canvas = Quagga.canvas.dom.image;
        
                    $node = $('<li><div class="thumbnail"><div class="imgWrapper"><img /></div><div class="caption"><h4 class="code"></h4></div></div></li>');
                    $node.find("img").attr("src", canvas.toDataURL());
                    $node.find("h4.code").html(code);
                    $("#result_strip ul.thumbnails").prepend($node);*/
                    //alert(code)
                    
                    $('#productSearchInput').val(code)
                    
                    $('.k-window-camera .k-i-close').click()
                    
                    $('#productSearchInput').keyup()
                    
                //}
            });
            jh=true
        }
    }
    
    
    var xxx,filter = {
        invoiceCode: '',
        customerSearchText: '',
        productSearchText: '',
        fromDate: '',
        toDate: '',
    }
    
    if(localStorage.record == undefined){
        localStorage.record = 10;
    }
    var record = localStorage.record;
    
    var records = [10,15,20,25,30];
    
    function validDate(d){
        //return moment(d,'DD/MM/YYYY').isAfter(moment('1970-01-01'))
        if(!d.match(/^\d{2}\/\d{2}\/\d{4}$/)) return false;
        
        d = d.replace(/^(\d{2})\/(\d{2})\/(\d{4})$/,"$3-$2-$1")
        
        return !(new Date(d) == 'Invalid Date')
    }
    
    function makeRefund(){
        xxx = $('[kendo-window="refundWindow"]').parent();
        
        xxx.width(Math.min(1050,$(document).width()))
        
        $('.k-overlay').css({
            display:'block',
            'z-index': 10000
        })
        
        xxx.css({
            top: 0,
            left: ($(document).width()-xxx.width())/2
        }).show()
        
        xxx.find('.k-header .k-window-title').css({    
            'padding-top': '0px'
        }).html('Chọn hóa đơn trả hàng') 
        
        xxx.find('.k-header .k-i-close').unbind('click')
        xxx.find('.k-header .k-i-close').click(function(){
            xxx.hide()
            $('.k-overlay').hide()
        })
         
        xxx.find('[data-role="datepicker"]:not(.hasDatepicker)').datepicker({
            maxDate: new Date(),
            onSelect: function(val,$target){ //vvkkll = $target;
                //console.log(val,$target)
                if($target.input.attr('k-ng-model') == "dateRange.fromDate"){
                    xxx.find('[data-role="datepicker"]:nth(1)').datepicker(
                        'option', 'minDate', xxx.find("[data-role='datepicker']:nth(0)").datepicker('getDate')
                    );
                     
                    //$target.input.off('focus')
                    //$target.input.val(val)
                    //xxx.find('[data-role="datepicker"]:nth(1)').focus()
                    
                    ////console.log('1:',$target.input.datepicker('getDate'))
                    ////filter.fromDate = val  
                    ////_g()
                }else{
                    ////console.log('2:',$target.input.datepicker('getDate'))
                    ////filter.toDate = val // $.datepicker.formatDate('dd/mm/yy', $target.input.datepicker('getDate') )
                    ////_g()
                }
            },
            onClose: function(dateText, inst){ console.log('onClose:',dateText, inst)                 
                if(dateText=='' || validDate( dateText )) {
                    if(inst.input.attr('k-ng-model') == "dateRange.fromDate")
                        filter.fromDate = dateText  
                    else
                        filter.toDate = dateText  
                    _g()
                }   else {
                    $(this).val(inst.lastVal)
                    toastr.warning("The selected day is invalid: "+ dateText)
                }
            }    
        })
        
        xxx.find('[data-role="datepicker"]:nth(0)').unbind('change')
        xxx.find('[data-role="datepicker"]:nth(0)').change(function(){ //alert('changed')
            //xxx.find('[data-role="datepicker"]:nth(1)').focus()
            console.log('1:',$(this).datepicker('getDate'))
            ////filter.fromDate = $.datepicker.formatDate('dd/mm/yyyy', $(this).datepicker('getDate') )
        }) 
        
        xxx.find('[data-role="datepicker"]:nth(1)').unbind('change')
        xxx.find('[data-role="datepicker"]:nth(1)').change(function(){ //alert('changed 2')
            console.log('2:',$(this).datepicker('getDate'))
            ////filter.toDate = $.datepicker.formatDate('dd/mm/yyyy', $(this).datepicker('getDate') )
        }) 
        
        xxx.find('[ng-model="invoiceCode"]').unbind('change')
        xxx.find('[ng-model="invoiceCode"]').change(function(){
            filter.invoiceCode = $(this).val()
            _g()
        }) 
        
        xxx.find('[ng-model="customerSearchText"]').unbind('change')
        xxx.find('[ng-model="customerSearchText"]').change(function(){
            filter.customerSearchText = $(this).val()
            _g()
        })
        
        xxx.find('[ng-model="productSearchText"]').unbind('change')
        xxx.find('[ng-model="productSearchText"]').change(function(){
            filter.productSearchText = $(this).val()
            _g()
        })
        
        xxx.find('[ng-click="quickRefund()"]').unbind('click')
        xxx.find('[ng-click="quickRefund()"]').click(function(){
            quickRefund()
        })
        
        _g()
    }
    
    
    function chooseInvoiceToReturn(id){
        //alert(id)
        document.location.hash = "#/Returns/"+id
        
        xxx.find('.k-header .k-i-close').click()
    }
    
    var returnTpl, countInvoices = 0 //, countInvoicesPaging = 0;
    var offlineTpl, countInvoicesOfline = 0 ;
    
    var filter3 = {
        Type: ''
    }, cpage2 = 0, tpage2 = 0;
    
    
    function loadOfflines(){ //alert(offlines)
        if(offlines == null){
            
            
            return;
        }
        
        if(offlineTpl==undefined) offlineTpl = $('#offlineTpl').html();
         
        $('#grdPendingSync tbody').html('')
        countInvoicesOfline = 0; //alert(offlines)
        
        for(var i in offlines){
            
            if(filter3.Type && offlines[i].type != filter3.Type) continue;
                
            countInvoicesOfline++;
             
            if(countInvoicesOfline > cpage2*record && countInvoicesOfline<= (cpage2+1)*record){
                
                var returnTpl2 = offlineTpl
                
                returnTpl2 = returnTpl2.replace(/\{\{alt\}\}/g,i%2==1 ? ' k-alt':'')
                
                for(var j in offlines[i]){
                    if(j=='products'||j=='customer') continue;
                    if(j=='date'){
                        var t9 = offlines[i][j];// moment(new Date(offlines[i][j])).format('DD/MM/YYYY HH:mm')
                        returnTpl2 = returnTpl2.replace(new RegExp('\{\{'+j+'\}\}','g'), t9)
                    }else if(j=='type'){                                              
                        returnTpl2 = returnTpl2.replace(new RegExp('\{\{'+j+'\}\}','g'), offlines[i][j]==1?'Hóa đơn':'Trả hàng')
                    }else{
                        returnTpl2 = returnTpl2.replace(new RegExp('\{\{'+j+'\}\}','g'), offlines[i][j])
                    }
                }
                
                $('#grdPendingSync tbody').append(returnTpl2)
            
            }
            
        }
        //alert(countInvoicesOfline)
        if(countInvoicesOfline==0){
            $('#grdPendingSync .k-grid-header').addClass('hth-hide')
            $('#grdPendingSync .k-grid-content table').addClass('hth-hide')
            $('#grdPendingSync .k-grid-content .empty-grid').removeClass('hth-hide')
            
            $('[ng-click="syncNow()"]').addClass('hth-hide')
        }else{
            $('#grdPendingSync .k-grid-header').removeClass('hth-hide')
            $('#grdPendingSync .k-grid-content table').removeClass('hth-hide')
            $('#grdPendingSync .k-grid-content .empty-grid').addClass('hth-hide')
            
            $('[ng-click="syncNow()"]').removeClass('hth-hide')
        }
         
        $('#grdPendingSync .k-pager-numbers li span').html(cpage2+1)
        
        tpage2 = Math.ceil(countInvoicesOfline/record)
        
        if(tpage2>1){ 
            $('#grdPendingSync .paging-box').show()
        
            if(cpage2==0){
                $('#grdPendingSync .k-pager-first,.k-pager-first+.k-pager-nav').addClass('k-state-disabled').unbind('click')
            }else{
                $('#grdPendingSync .k-pager-first,.k-pager-first+.k-pager-nav').removeClass('k-state-disabled').unbind('click')
                $('#grdPendingSync .k-pager-first').click(function(){
                    cpage2=0
                    loadOfflines()
                })
                $('#grdPendingSync.k-pager-first+.k-pager-nav').click(function(){
                    cpage2--
                    loadOfflines()
                })
            }
            
            if(cpage2==tpage2-1 || tpage2==0){
                $('#grdPendingSync .k-pager-last,ul+.k-pager-nav').addClass('k-state-disabled').unbind('click')
            }else{
                $('#grdPendingSync .k-pager-last,ul+.k-pager-nav').removeClass('k-state-disabled').unbind('click')
                $('#grdPendingSync .k-pager-last').click(function(){
                    cpage2=tpage2-1
                    loadOfflines()
                })
                $('#grdPendingSync ul+.k-pager-nav').click(function(){
                    cpage2++;
                    loadOfflines()
                })
            }
            
            $("#grdPendingSync .k-pager-info.k-label").html("Hiển thị "+(cpage2*record+1)+" - "+Math.min(countInvoicesOfline,(cpage2+1)*record)+" trên tổng số "+countInvoicesOfline+" phiếu");
        }else{
            $('#grdPendingSync .paging-box').hide()
        }
    }
    
    
    function chooseInvoiceToOffline(id){
        if(Offline.state=='down'){
            toastr.warning('Bạn không thể thực hiện chức năng này ở chế độ Offline')
            return;
        } 
        $('#grdPendingSync tr[data-uid="'+id+'"]').remove()
        
        var coo = loadObject(id,offlines,'code')
        if(!coo) return;
        
        coo = $.extend({},coo)
        //remove it to offlines
        for(var i in offlines){
            if(offlines[i].code==coo.code){
                offlines.splice(i,1)
                break;
            }
        }
        saveCacheOfflines(offlines) 
        syncCount() 
        
        var oldT = coo.table+'' //-0;
        var oldS = coo.status-0;
        
        coo.status = 1;
        if(coo.type==1){
            if(coo.id==undefined || !coo.id){//ob.status==-1                 
                //hoa don nay chua dc submit vao db lan nao ca, nen insert
                 
                $.ajax(fakedomain+'/ajax.php?action=invoice',{
                    data: 'data='+encodeURIComponent(JSON.stringify($.extend(coo,{branch:cb}))),
                    method: "POST",
                    dataType: 'json',
                    success: function(d){
                         if(d.error){
                            if(d.error.indexOf('Duplicate')>=0){
                                
                            }else{
                                alert(d.error)
                                coo.status=-1
                            }
                         }else{
                            //2019 phuc vu cho return
                            var ob2 = $.extend({id:d.order_id},coo)
                            
                            var newiv = convertOrderToInvoice(ob2);
                            invoices.unshift(newiv);
                            saveCacheInvoices(invoices);
                            
                            //inhoadon(ob)
                            //delete coo, k can nua
                             
                            if($('.swiper-container li[data-id="'+ct2+'"] aside>a[ng-click="itemClicked(p)"]:first-child').length)
                                $('.swiper-container li[data-id="'+ct2+'"] aside>a[ng-click="itemClicked(p)"]:first-child').click()
                            else
                                afterClick()
                            //reload lai products
                            loadJsonProducts()
                            if(0 && site_type==1){
                            //socket notification to kitchen
                            socket.emit('send',JSON.stringify({
                                job: 'pay',
                                table: oldT,
                                id: socket.id
                            }),data_user.kitchen)
                            }
                            
                            //inhoadon(ob2); console.log(ob2)
                            //toastr.success('Hóa đơn được thêm thành công!')
                         }
                    },
                    error:function(jqXHR,  textStatus,  errorThrown){
                        //console.log(jqXHR)
                        coo.status=-1
                        if(jqXHR.status==0){
                            if(jqXHR.abort) jqXHR.abort();
                            //dut mang
                            //hoa don ban hang moi
                            if(!nprint){
                                if(PrintBarCode){                                    
                                    printTask = [function(){},function(){inhoadonOffline(coo)}]
                                    _printBarCode(coo)
                                }else
                                    
                                inhoadonOffline(coo)
                            }
                                
                            
                            //toastr.warning('Không có internet, hóa đơn được lưu Offline')
                            offlines.push(coo);
                            saveCacheOfflines(offlines)
                            syncCount()
                            //luu iv de phuc vu cho return
                            
                            //xoa orders va close tab
                            for(var i in orders){
                                if(orders[i].code==coo.code){
                                    orders.splice(i,1)
                                    break;
                                }
                            }
                            saveOrders()  
                            if($('.swiper-container li[data-id="'+ct2+'"] aside>a[ng-click="itemClicked(p)"]:first-child').length)
                                $('.swiper-container li[data-id="'+ct2+'"] aside>a[ng-click="itemClicked(p)"]:first-child').click()
                            else
                                afterClick()
                        }else{
                            ////coo.status=-1
                        }
                    }//end error function
                })
            }else{                 
                //hoa don nay da co trong db roi, update thoi
                coo.status=1
                $.ajax(fakedomain+'/ajax.php?action=updateinvoice',{
                    data: 'data='+encodeURIComponent(JSON.stringify($.extend(coo,{branch:cb}))),
                    method: "POST",
                    dataType: 'json',
                    success: function(d){
                         if(d.error){
                            alert(d.error)
                            coo.status=oldS
                         }else{
                            var ob2 = $.extend({},coo)
                            
                            //2019 phuc vu cho return                             
                            var newiv = convertOrderToInvoice(ob2);
                            invoices.unshift(newiv);
                            saveCacheInvoices(invoices);
                            
                            //inhoadon(ob)
                            //delete coo, k can nua
                             
                            if($('.swiper-container li[data-id="'+ct2+'"] aside>a[ng-click="itemClicked(p)"]:first-child').length)
                                $('.swiper-container li[data-id="'+ct2+'"] aside>a[ng-click="itemClicked(p)"]:first-child').click()
                            else
                                afterClick()
                            //reload lai products
                            loadJsonProducts()
                            if(0&& site_type==1){
                            //socket notification to kitchen
                            socket.emit('send',JSON.stringify({
                                job: 'pay',
                                table: oldT,
                                id: socket.id
                            }),data_user.kitchen)
                            }
                            //inhoadon(ob2) 
                            //toastr.success('Hóa đơn được cập nhật thành công!')

                         }
                    },
                    error:function(jqXHR,  textStatus,  errorThrown){
                        //console.log(jqXHR)
                        coo.status=oldS
                        if(jqXHR.status==0){
                            if(jqXHR.abort) jqXHR.abort();
                            //dut mang    
                            //hoa don ban hang update(chi co voi nha hang, an thong bao nha bep toi thieu 1 lan roi)                                 
                            saveOffline(ob,1)
                        }else{
                            coo.status=oldS
                        }
                    } //end error function
                })
            }
        }else if(coo.type==11){
            if(coo.id==undefined || !coo.id){                  
                //insert
                coo.status=1
                $.ajax(fakedomain+'/ajax.php?action=invoice',{
                    data: 'data='+encodeURIComponent(JSON.stringify($.extend(coo,{branch:cb}))),
                    method: "POST",
                    dataType: 'json',
                    success: function(d){
                         if(d.error){
                            if(d.error.indexOf('Duplicate')>=0){
                                
                            }else{
                                alert(d.error)
                                coo.status=-1
                            }
                         }else{
                             
                            var ob2 = $.extend({id:d.order_id},coo)
                             
                            //inhoadon(ob)
                            //delete coo, k can nua
                            
                            if($('.swiper-container li[data-id="'+ct2+'"] aside>a[ng-click="itemClicked(p)"]:first-child').length)
                                $('.swiper-container li[data-id="'+ct2+'"] aside>a[ng-click="itemClicked(p)"]:first-child').click()
                            else
                                afterClick()
                            //reload lai products
                            loadJsonProducts()
                             
                            //inhoadon(ob2); console.log(ob2)
                            //toastr.success('Hóa đơn trả hàng được thêm thành công!')
                         }
                    },
                    error:function(jqXHR,  textStatus,  errorThrown){
                        //console.log(jqXHR)
                        coo.status=-1
                        if(jqXHR.status==0){
                            if(jqXHR.abort) jqXHR.abort();
                            //dut mang
                            //tra hang
                            saveOffline(ob,1)
                        }else{
                            coo.status=-1
                        }
                    } //end error function
                })
            }
        }     
    }                
    
    
    function _g(){
        if(invoices == null){
            setTimeout(function(){
                _g()
            },500)
            return;
        }
        
        if(returnTpl==undefined) returnTpl = $('#returnTpl').html()
        
        xxx.find('#grdOrders tbody').html('')
        countInvoices = 0;
        //countInvoicesPaging = 0;
        
        for(var i in invoices){
            
            if(filter.invoiceCode && 
                invoices[i].code.toLowerCase().indexOf(filter.invoiceCode.toLowerCase())==-1) continue;
            
            if(filter.fromDate){
                var m1 = moment(filter.fromDate,'DD/MM/YYYY')
                var m2 = moment($.datepicker.formatDate('yy-mm-dd',new Date(invoices[i].date)))
                if(m1.isAfter(m2)) continue;
            }
            
            if(filter.toDate){
                var m1 = moment(filter.toDate,'DD/MM/YYYY')
                var m2 = moment($.datepicker.formatDate('yy-mm-dd',new Date(invoices[i].date)))
                if(m1.isBefore(m2)) continue;
            }
            
            if(invoices[i].customer>0){
                var customerObject = loadCustomer(invoices[i].customer);
            }else{
                var customerObject = {
                    name: '',
                    phone: ''
                }
            }
            
            if(filter.customerSearchText && (
                customerObject.name.toLowerCase().indexOf(filter.customerSearchText.toLowerCase())==-1 &&
                customerObject.phone.toLowerCase().indexOf(filter.customerSearchText.toLowerCase())==-1
            )) continue;
            
            if(filter.productSearchText){
                var px = invoices[i].products, ck = false;
                for(var j in px){
                    var px2 = loadProduct(px[j].product)
                    if(px2.code.toLowerCase().indexOf(filter.productSearchText.toLowerCase())>-1 ||
                        px2.name.toLowerCase().indexOf(filter.productSearchText.toLowerCase())>-1){
                        ck = true;
                        break;    
                    }
                }
                if(!ck) continue;
            }
            
            countInvoices++;
            
            //if(countInvoicesPaging++ >= cpage*record && countInvoicesPaging< (cpage+1)*record){
            if(countInvoices > cpage*record && countInvoices<= (cpage+1)*record){
                
                var returnTpl2 = returnTpl
                
                returnTpl2 = returnTpl2.replace(/\{\{alt\}\}/g,i%2==1 ? ' k-alt':'')
                
                for(var j in invoices[i]){
                    if(j=='products') continue;
                    if(j=='date'){
                        var t9 = moment(new Date(invoices[i][j])).format('DD/MM/YYYY HH:mm')
                        returnTpl2 = returnTpl2.replace(new RegExp('\{\{'+j+'\}\}','g'), t9)
                    }else if(j=='customer'){                    
                        if(invoices[i][j]>0){
                            var t9 = customerObject // loadCustomer(invoices[i][j])   
                            t9 = t9? t9.name : 'Khách lẻ'
                        } else {
                            var t9 = 'Khách lẻ'
                        }
                        returnTpl2 = returnTpl2.replace(new RegExp('\{\{'+j+'\}\}','g'), t9)
                    }else if(j=='create'){                     
                        if(invoices[i][j]>0){
                            var t9 = loadUser(invoices[i][j])   
                            t9 = t9? t9.name : ''
                        } else {
                            var t9 = ''
                        }
                        returnTpl2 = returnTpl2.replace(new RegExp('\{\{'+j+'\}\}','g'), t9)
                    }else if(j=='table'){
                        if(invoices[i][j]>0){
                            var t9 = loadTable(invoices[i][j])   
                            t9 = t9? t9.name : ''
                        } else {
                            var t9 = ''
                        }
                        returnTpl2 = returnTpl2.replace(new RegExp('\{\{'+j+'\}\}','g'), t9)
                    }else{
                        returnTpl2 = returnTpl2.replace(new RegExp('\{\{'+j+'\}\}','g'), invoices[i][j])
                    }
                }
                
                xxx.find('#grdOrders tbody').append(returnTpl2)
            
            }
            
        }
        
        
        xxx.find('.k-pager-numbers li span').html(cpage+1)
        
        tpage = Math.ceil(countInvoices/record)
        
        if(cpage==0){
            xxx.find('.k-pager-first,.k-pager-first+.k-pager-nav').addClass('k-state-disabled').unbind('click')
        }else{
            xxx.find('.k-pager-first,.k-pager-first+.k-pager-nav').removeClass('k-state-disabled').unbind('click')
            xxx.find('.k-pager-first').click(function(){
                cpage=0
                _g()
            })
            xxx.find('.k-pager-first+.k-pager-nav').click(function(){
                cpage--
                _g()
            })
        }
        
        if(cpage==tpage-1 || tpage==0){
            xxx.find('.k-pager-last,ul+.k-pager-nav').addClass('k-state-disabled').unbind('click')
        }else{
            xxx.find('.k-pager-last,ul+.k-pager-nav').removeClass('k-state-disabled').unbind('click')
            xxx.find('.k-pager-last').click(function(){
                cpage=tpage-1
                _g()
            })
            xxx.find('ul+.k-pager-nav').click(function(){
                cpage++;
                _g()
            })
        }
        
        xxx.find(".k-pager-info.k-label").html("Hiển thị "+(cpage*record+1)+" - "+Math.min(countInvoices,(cpage+1)*record)+" trên tổng số "+countInvoices+" hóa đơn");
        
    }
    
    function quickRefund(){
        document.location.hash = "#/Returns"
        xxx.find('.k-header .k-i-close').click()
        loadQuickReturn()
        return false;
    }