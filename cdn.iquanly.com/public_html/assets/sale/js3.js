
function loadAllBranches(){
    var mm = loadUser(create)
    if(mm.admin){
        return branches
    }else{
        return mm.permissions.map(function(a){return loadBranch(a.branch)})
    }
}

//var sh = $.extend({},schema1); sh['Books-'+cb] = 'id,code'; //alert(sh)
schema1['Books'] = 'id,code,branch';  //'Books-'+cb id,code
function qui(){
    if(!do1 || !do2){
        setTimeout(function(){
            qui()
        },500)
        return
    }
    try{ //db["Books-"+cb].toArray().then().catch()
        console.log('db.tables 2',JSON.stringify(db.tables))
         
        if(db.tables.length<=2){
            schema1 = {Books:schema1['Books']}
            db.close();  
            db.version(2).stores(schema1)
            _poi()  
        }else{
            _poi()   
        }
        
        //if(db.tables.length<=2){
//            db.close();  
//            db.version(dbver==1?(dbver=2,2):(dbver)).stores(schema1)
//            console.log('then')           
//            _poi()  
        /*}else{
         
            db.table("Books-"+cb).count().then(function(){
                _poi()    
            }).catch(function(e){ console.log(e.name)
                //name: "NotFoundError"
                
                db.close();  
                db.version(dbver==1?(dbver=2,2):(dbver=dbver+1,dbver)).stores(schema1)
                console.log('then')
                
                _poi()    
            })
        
        }*/
         
    }catch(e){
        console.log('EEroor',e.name,dbver,schema1)
        //name: "OpenFailedError"
        //if(e.name== "InvalidTableError"){
            db.close();  
            db.version(dbver==1?(dbver=2,2):(dbver=dbver+1,dbver)).stores(schema1)
            console.log('then',dbver)
            
            _poi() 
            return;       
        //}
        /*
        var ukk = 1,umm = function(){
            setTimeout(function(){
                try{
                     
                    console.log('then '+ukk)
                    
                    db.table("Books-"+cb).count().then(function(){
                        _poi()    
                    }).catch(function(e){ console.log(e.name)
                        //name: "NotFoundError"
                        
                        db.close();  
                        db.version(dbver==1?(dbver=2,2):(dbver=dbver+1,dbver)).stores(schema1)
                        console.log('then')
                        
                        _poi()    
                    }) 
                }catch(e){
                    console.log('EEroor '+ukk,e)
                    
                    if(e.name== "InvalidTableError"){
                        db.close();  
                        db.version(dbver==1?(dbver=2,2):(dbver=dbver+1,dbver)).stores(schema1)
                        console.log('then')
                        
                        _poi()        
                    }else
                    
                    umm()
                }
            },(ukk++)*1000)
        }
        umm()*/
    };
    //alert('sh')
}
qui()

var _lb = false;
function _poi(lo){ console.log('_poi',lo)
    db.open().then(function(){
        db.table("Books") .where('branch').equals(cb) .toArray().then(function(x){ //"Books-"+cb
            books = x.filter(function(a){return a.status<=0;}); console.log('xxx:',x)
            bookCount()
        }).catch(function(){
            books = [];
        }).finally(function(){ console.log('finally')
            //ajax load current books from databases and merge
            $.ajax({
                url: '/ajax.php?action=books&branch='+cb,
                dataType: 'JSON',
                success: function(data){
                    if(books && books.length){
                         if(data && data.length){
                            //truong hop 1: co trong ca 2 books va data
                            for(var i in books){
                                for(var j in data){
                                    
                                    if(data[j].code==books[i].code){    
                                        if(books[i].user==create){
                                            _addInfoToProducts(data[j])
                                            books[i] = $.extend({},data[j])
                                        }else{
                                            //bo sung product name tu products
                                            /*for(var i3 in data[j].products){
                                                var ck = loadProduct(data[j].products[i3].product)
                                                
                                                data[j].products[i3] = $.extend({},ck,data[j].products[i3])
                                            }*/
                                            _addInfoToProducts(data[j])
                                    
                                            books[i] = $.extend({},data[j])
                                        }
                                        db.table("Books").where('id').equals(data[j].id).modify(books[i]) //"Books-"+cb
                                        break;
                                    }
                                    
                                }
                            }
                            //truong hop 2: co trong old(books) ma k co trong new(data)
                            for(var i in books){
                                var check = true
                                for(var j in data){
                                    if(data[j].code==books[i].code){ 
                                        check = false
                                        break;   
                                    }
                                }
                                if(check){
                                    
                                    if(check.id!=undefined){
                                        db.table("Books").where('id').equals(check.id).delete() //"Books-"+cb
                                        books.splice(i,1)                                         
                                    }
                                        
                                }
                            }
                            //truong hop 3: co trong new(data) ma k co trong old(books)
                            for(var j in data){
                                var check = true
                                for(var i in books){
                                    if(data[j].code==books[i].code){ 
                                        check = false
                                        break;   
                                    }
                                }
                                if(check){
                                    //bo sung product name tu products
                                    /*for(var i3 in data[j].products){
                                        var ck = loadProduct(data[j].products[i3].product)
                                         
                                        data[j].products[i3] = $.extend({},ck,data[j].products[i3])
                                    }*/
                                    _addInfoToProducts(data[j])
                                    
                                    books.push(data[j])
                                    db.table("Books").add(data[j]).then(function(a){ //"Books-"+cb
                                        console.log('add1:',a)
                                    }).catch(function(a){
                                        console.log('add1 e:',a)
                                    }) 
                                }
                            }
                         }else{
                            //truong hop 2
                            for(var i in books){
                                 
                                if(books[i].id!=undefined){
                                    db.table("Books").where('id').equals(books[i].id).delete() //"Books-"+cb
                                    books.splice(i,1)                                    
                                }                                
                                 
                            }
                         }
                      }else{
                        //luc nay chi co truong hop 3
                        
                        //bo sung product name tu products
                        
                        for(var j in data){
                        
                            /*for(var i3 in data[j].products){
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
                            }*/
                            _addInfoToProducts(data[j])
                            
                            db.table("Books").add(data[j]).then(function(a){ //"Books-"+cb
                                console.log('add2:',a)
                            }).catch(function(a){
                                console.log('add2 e:',a)
                            })  
                        }
                        
                        books = data;
                         
                      }
                      _lb = true
                      
                      bookCount()
					  
					  //03/18/2020
					  lo && lo.call && lo.call(null)
                },
                error: function(){
                    _lb = true
                }
            })
        }) 
    })
} 

//fixed 03/28/2020
if(customer_groups==null){
    loadJsonCustomerGroups()
}