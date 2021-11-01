//move from index: 06/03/2019
var cache_impexp = new Cache(-1, false, new Cache.LocalStorageCacheStorage('impexp'));
var impexp = cache_impexp.getItem("impexp");
if(!impexp) impexp = []

//code, type, file, content, status, link
function addItemImpExp(code, type, file, content, status, link){
    impexp.push({
        code, type, file, content, status, link
    })
    
    saveimpexp()
    
    displayImpExp()
}
function saveimpexp(){            
    console.log('saveimpexp');
     
    cache_impexp.setItem("impexp",impexp);
}

function clearimpexp(){
    console.log('clearimpexp');
     
    cache_impexp.removeItem("impexp");
}

function changeimpexp(newimpexp){
    var t = loadObject(newimpexp.code,impexp,'code')
    if(t){
        t = $.extend(t,newimpexp)
    }
    
    saveimpexp()
    displayImpExp()
}

function downloadFileExport(code){
    var z = loadObject(code,impexp,'code')
    if(!z) return;
    if(z.type==1) return window.close();
    var d= {
        code: code,
        status: 2,
        content: 'Đã tải xuống'
    }
    changeimpexp(d)
}

////var showImportExportBoard = !!localStorage.showImportExportBoard

function openImpExp(){
    $('.notificationImpExp').removeClass('ng-hide')
    ////showImportExportBoard = true
    ////localStorage.showImportExportBoard = 1
}

/**if(showImportExportBoard){
    $('.notificationImpExp').removeClass('ng-hide')
}else{
    $('.notificationImpExp').addClass('ng-hide')
} **/
 
function addOneRowImpExp(item){
    var impexpItem2 = impexpItem;
    var item2 = $.extend({},item)
    
    item2['li-class'] = item2.type == 1? 'iconKvImport' : 'iconKvExport';
    item2['fa-class'] = item2.type == 1? 'fa-sign-in' : 'fa-sign-out';
    
    if(item2.status==-1) //error
        item2['span-class'] = 'txtRed'
    else if(item2.status==0) //start loading
        item2['span-class'] = 'loading-icon'
    else if(item2.status==1) //processed
        item2['span-class'] = 'txtBlue'
    //2 : clicked
    else 
        item2['span-class'] = ''
        
    if(item2.status==1){
        item2['span-class'] += ' ng-hide'
        item2['a-class'] = ''
    } 
    else {
        item2['a-class'] = 'ng-hide'
    }
    
    if(item2.status!=1) item2['i1-class'] = 'ng-hide'
    else item2['i1-class'] = ''
    
    if(item2.status!=-1) item2['i2-class'] = 'ng-hide'
    else item2['i2-class'] = ''
    
    console.log('item2:',item2)
    
    for(var i in item2){
        impexpItem2 = impexpItem2.replace(new RegExp("\{\{"+i+"\}\}",'g'), item2[i])
    }
    
    $('#importExportContent ul').prepend(impexpItem2)
}

function displayImpExp(){
    $('#importExportContent ul').html('')
    for(var i in impexp){
        addOneRowImpExp(impexp[i])
    }
    /*
    if(impexp.length==0){
        $('#importExportContent ul').addClass('ng-hide')
    }else{
        $('#importExportContent ul').removeClass('ng-hide')
    }
    */
    if(impexp.length==0)
        $('.notificationImpExp').addClass('ng-hide')
    else
        $('.notificationImpExp').removeClass('ng-hide')        
}

function getCodeImpExp(){
    //return new Date().getTime() + ''
    return moment().format('YYYYMMDDHHmmss');
}
var impexpItem; 
$(document).ready(function(){
    impexpItem = $('#impexpItem').html()
    
    $('.notificationImpExp .minmax').click(function(e){
         
        if($('#importExportContent').hasClass('ng-hide')){
            $(this).find('.fa').addClass('fa-angle-down').removeClass('fa-angle-up')
            $('#importExportContent').removeClass('ng-hide')
        }else{
            $(this).find('.fa').removeClass('fa-angle-down').addClass('fa-angle-up')
            $('#importExportContent').addClass('ng-hide')
        }
        
        if(e.pageX!=undefined)
        
        if($('.notificationImpExp').hasClass('notificationImpExpExpand')){
            $('.notificationImpExp .expcomp').click()
        }
    })
    
    $('.notificationImpExp .expcomp').click(function(e){
        if($('.notificationImpExp').hasClass('notificationImpExpExpand')){
            
            $(this).find('.kv-icon').addClass('kv-expand').removeClass('kv-compress')
            $('.notificationImpExp').removeClass('notificationImpExpExpand')
        }else{
            $(this).find('.kv-icon').removeClass('kv-expand').addClass('kv-compress')
            
            $('.notificationImpExp').addClass('notificationImpExpExpand')
        }
        
        if(e.pageX!=undefined)
        if($('#importExportContent').hasClass('ng-hide')){
            $('.notificationImpExp .minmax').click()
        } 
    })
    
    $('.notificationImpExp .dele').click(function(e){
        
        $('.notificationImpExp').addClass('ng-hide')
        
        clearimpexp()
        impexp = []
        
        $('#importExportContent ul').html('')
        
        ////showImportExportBoard = false
        ////delete localStorage.showImportExportBoard  
    })
    
    displayImpExp()
})