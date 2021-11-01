var cache_prints = new Cache(-1, false, new Cache.LocalStorageCacheStorage('prints'));
var cache_branches = new Cache(-1, false, new Cache.LocalStorageCacheStorage('branches'));
var cache_tables = new Cache(-1, false, new Cache.LocalStorageCacheStorage('tables'));
var cache_products = new Cache(-1, false, new Cache.LocalStorageCacheStorage('products'));

var cache_users = new Cache(-1, false, new Cache.LocalStorageCacheStorage('users'));         
var cache_customers = new Cache(-1, false, new Cache.LocalStorageCacheStorage('customers'));
var cache_suppliers = new Cache(-1, false, new Cache.LocalStorageCacheStorage('suppliers'));
var cache = new Cache(-1, false, new Cache.LocalStorageCacheStorage('cache'));
var cache_orders = new Cache(-1, false, new Cache.LocalStorageCacheStorage('orders'));
var cache_invoices = new Cache(-1, false, new Cache.LocalStorageCacheStorage('invoices'));        
var cache_kitchen = new Cache(-1, false, new Cache.LocalStorageCacheStorage('kitchen'));
var cache_offlines = new Cache(-1, false, new Cache.LocalStorageCacheStorage('offlines'));

var bankaccounts = cache.getItem("bankaccounts");

function saveCachePrints(name,data){
    var tim = (name=='printinvoice'||name=='printreturn'||name=='printorder')?(365*24*3600):(24*3600);
    cache_prints.setItem(name,data,{expirationAbsolute: null,
     expirationSliding: 24*3600,
     priority: Cache.Priority.HIGH,
     callback: function(k, v) { console.log('Cache removed: ' + k); }
    });
}

function _ssl(){
    $.ajax('https://'+document.location.hostname+'/check.php',{             
        method: "GET",
        dataType: 'json',
        success: function(d){
            document.location.href=document.location.href.replace(/http\:/,'https:')
        },
        error: function(e){
             
        }
    }); 
}

if(document.location.protocol == "http:" && 
    !document.location.host.match(/quanlytot\.localhost/) &&
    !document.location.host.match(/quanlytot\.com/)
){
    _ssl()
    setInterval(function(){
        _ssl()
    },60000)
}

if(document.location.protocol == "https:" &&     
    document.location.host.match(/quanlytot\.com/)
){
    document.location.href=document.location.href.replace(/https\:/,'http:')
}   

function loadObject(pid, o, o2){
    o2 = o2==undefined?'id':o2;
    for(var i in o){
       if(o[i][o2] == pid) return o[i]; 
    };
    return false;
}

function loadProduct(pid){
    return loadObject(pid, products);
}
function loadCustomer(pid){
    return loadObject(pid, customers);
}
        

//  !function(){!function t(){try{!function c(t){(1!==(""+t/t).length||t%20===0)&&function(){}.constructor("debugger")(),c(++t)}(0)}catch(n){setTimeout(t,1e3)}}()}();
    
//  (function(x) {(function(f){(function a(){try {function b(i) {if((''+(i/i)).length !== 1 ||i % 20 === 0) {(function(){}).constructor('debugger')();} else {debugger;}b(++i);}b(0);} catch(e) {f.setTimeout(a, x)}})()})(document.body.appendChild(document.createElement('frame')).contentWindow);})();

$.datepicker.regional[ "vi" ] = {
    closeText: "Xong",
    prevText: "Trước",
    nextText: "Sau",
    currentText: "Hôm nay",
    monthNames: ["Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6", "Tháng 7", "Tháng 8", "Tháng 9", "Tháng 10", "Tháng 11", "Tháng 12"],
    monthNamesShort: ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"],
    dayNames: ["Chủ nhật", "Thứ 2", "Thứ 3", "Thứ 4", "Thứ 5", "Thứ 6", "Thứ 7"],
    dayNamesShort: ["CN", "T2", "T3", "T4", "T5", "T6", "T7"],
    dayNamesMin: ["CN", "T2", "T3", "T4", "T5", "T6", "T7"],
    weekHeader: "Wk",
    dateFormat: "dd/mm/yy",
    firstDay: 0,
    isRTL: !1,
    showMonthAfterYear: !1,
    yearSuffix: ""
}
$.datepicker.setDefaults( $.datepicker.regional[ "vi" ] );

$.datetimepicker.setLocale('vi');

/*
$( ".selector" ).datepicker({
  dateFormat: "dd/mm/yy 00:00"
});
*/

/*
http://stackoverflow.com/questions/118241/calculate-text-width-with-javascript
http://stackoverflow.com/questions/2057682/determine-pixel-length-of-string-in-javascript-jquery
*/
String.prototype.width = function(font) {
  var f = font || '12px arial',
      o = $('<div>' + this + '</div>')
            .css({'position': 'absolute', 'float': 'left', 'white-space': 'nowrap', 'visibility': 'hidden', 'font': f})
            .appendTo($('body')),
      w = o.width();

  o.remove();

  return w;
}
 
function array_count_values (array) {
    // http://jsphp.co/jsphp/fn/view/array_count_values
    // +   original by: Ates Goral (http://magnetiq.com)
    // + namespaced by: Michael White (http://getsprink.com)
    // +      input by: sankai
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   input by: Shingo
    // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
    // *     example 1: array_count_values([ 3, 5, 3, "foo", "bar", "foo" ]);
    // *     returns 1: {3:2, 5:1, "foo":2, "bar":1}
    // *     example 2: array_count_values({ p1: 3, p2: 5, p3: 3, p4: "foo", p5: "bar", p6: "foo" });
    // *     returns 2: {3:2, 5:1, "foo":2, "bar":1}
    // *     example 3: array_count_values([ true, 4.2, 42, "fubar" ]);
    // *     returns 3: {42:1, "fubar":1}
    var tmp_arr = {},
        key = '',
        t = '';

    var __getType = function (obj) {
        // Objects are php associative arrays.
        var t = typeof obj;
        t = t.toLowerCase();
        if (t === "object") {
            t = "array";
        }
        return t;
    };

    var __countValue = function (value) {
        switch (typeof(value)) {
        case "number":
            if (Math.floor(value) !== value) {
                return;
            }
            // Fall-through
        case "string":
            if (value in this && this.hasOwnProperty(value)) {
                ++this[value];
            } else {
                this[value] = 1;
            }
        }
    };

    t = __getType(array);
    if (t === 'array') {
        for (key in array) {
            if (array.hasOwnProperty(key)) {
                __countValue.call(tmp_arr, array[key]);
            }
        }
    }
    return tmp_arr;
}

function substr_count (haystack, needle, offset, length) {
    // http://jsphp.co/jsphp/fn/view/substr_count
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfixed by: Onno Marsman
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // *     example 1: substr_count('Kevin van Zonneveld', 'e');
    // *     returns 1: 3
    // *     example 2: substr_count('Kevin van Zonneveld', 'K', 1);
    // *     returns 2: 0
    // *     example 3: substr_count('Kevin van Zonneveld', 'Z', 0, 10);
    // *     returns 3: false
    var pos = 0,
        cnt = 0;

    haystack += '';
    needle += '';
    if (isNaN(offset)) {
        offset = 0;
    }
    if (isNaN(length)) {
        length = 0;
    }
    offset--;

    while ((offset = haystack.indexOf(needle, offset + 1)) != -1) {
        if (length > 0 && (offset + needle.length) > length) {
            return false;
        }
        cnt++;
    }

    return cnt;
}

function in_array (needle, haystack, argStrict) {
    // http://jsphp.co/jsphp/fn/view/in_array
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: vlado houba
    // +   input by: Billy
    // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
    // *     example 1: in_array('van', ['Kevin', 'van', 'Zonneveld']);
    // *     returns 1: true
    // *     example 2: in_array('vlado', {0: 'Kevin', vlado: 'van', 1: 'Zonneveld'});
    // *     returns 2: false
    // *     example 3: in_array(1, ['1', '2', '3']);
    // *     returns 3: true
    // *     example 3: in_array(1, ['1', '2', '3'], false);
    // *     returns 3: true
    // *     example 4: in_array(1, ['1', '2', '3'], true);
    // *     returns 4: false
    var key = '',
        strict = !! argStrict;

    if (strict) {
        for (key in haystack) {
            if (haystack[key] === needle) {
                return true;
            }
        }
    } else {
        for (key in haystack) {
            if (haystack[key] == needle) {
                return true;
            }
        }
    }

    return false;
}
ARRAY_FILTER_USE_KEY = 1;
ARRAY_FILTER_USE_BOTH = 2;
function array_filter (arr, func, flag) {
    // http://jsphp.co/jsphp/fn/view/array_filter
    // +   original by: Brett Zamir (http://brett-zamir.me)
    // +   input by: max4ever
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +   improved by: Tutorialspots (http://tutorialspots.com)
    // %        note 1: Takes a function as an argument, not a function's name
    // *     example 1: var odd = function (num) {return (num & 1);}; 
    // *     example 1: array_filter({"a": 1, "b": 2, "c": 3, "d": 4, "e": 5}, odd);
    // *     returns 1: {"a": 1, "c": 3, "e": 5}
    // *     example 2: var even = function (num) {return (!(num & 1));}
    // *     example 2: array_filter([6, 7, 8, 9, 10, 11, 12], even);
    // *     returns 2: {0: 6, 2: 8, 4: 10, 6: 12} 
    // *     example 3: var arr = array_filter({"a": 1, "b": false, "c": -1, "d": 0, "e": null, "f":'', "g":undefined});
    // *     returns 3: {"a":1, "c":-1};
    // *     example 4: var arr = array_filter({"a": 1, "b": 2, "c": 3, "d": 4, "e": 5}, function(x){return x!='b'}, ARRAY_FILTER_USE_KEY);
    // *     returns 4: {a: 1, c: 3, d: 4, e: 5}
    // *     example 5: var arr = array_filter({"a": 1, "b": 2, "c": 3, "d": 4, "e": 5}, function(x,k){return x==3||k=='a'}, ARRAY_FILTER_USE_BOTH);
    // *     returns 5: {a: 1, c: 3}
    
    var retObj = {},
        k;
        
    func = func || function (v) {return v;};
     
    for (k in arr) {
         
        if ((flag==undefined && func(arr[k]))||
            (flag==ARRAY_FILTER_USE_KEY && func(k))||
            (flag==ARRAY_FILTER_USE_BOTH && (func(arr[k], k)))) {
            retObj[k] = arr[k];
        }    
    }

    return retObj;
}

function explode (delimiter, string, limit) {
    // http://jsphp.co/jsphp/fn/view/explode
    // +     original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +     improved by: kenneth
    // +     improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +     improved by: d3x
    // +     bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // *     example 1: explode(' ', 'Kevin van Zonneveld');
    // *     returns 1: {0: 'Kevin', 1: 'van', 2: 'Zonneveld'}
    // *     example 2: explode('=', 'a=bc=d', 2);
    // *     returns 2: ['a', 'bc=d']
    var emptyArray = {
        0: ''
    };

    // third argument is not required
    if (arguments.length < 2 || typeof arguments[0] == 'undefined' || typeof arguments[1] == 'undefined') {
        return null;
    }

    if (delimiter === '' || delimiter === false || delimiter === null) {
        return false;
    }

    if (typeof delimiter == 'function' || typeof delimiter == 'object' || typeof string == 'function' || typeof string == 'object') {
        return emptyArray;
    }

    if (delimiter === true) {
        delimiter = '1';
    }

    if (!limit) {
        return string.toString().split(delimiter.toString());
    }
    // support for limit argument
    var splitted = string.toString().split(delimiter.toString());
    var partA = splitted.splice(0, limit - 1);
    var partB = splitted.join(delimiter.toString());
    partA.push(partB);
    return partA;
}

function trim (str, charlist) {
    // http://jsphp.co/jsphp/fn/view/trim
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: mdsjack (http://www.mdsjack.bo.it)
    // +   improved by: Alexander Ermolaev (http://snippets.dzone.com/user/AlexanderErmolaev)
    // +      input by: Erkekjetter
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +      input by: DxGx
    // +   improved by: Steven Levithan (http://blog.stevenlevithan.com)
    // +    tweaked by: Jack
    // +   bugfixed by: Onno Marsman
    // *     example 1: trim('    Kevin van Zonneveld    ');
    // *     returns 1: 'Kevin van Zonneveld'
    // *     example 2: trim('Hello World', 'Hdle');
    // *     returns 2: 'o Wor'
    // *     example 3: trim(16, 1);
    // *     returns 3: 6
    var whitespace, l = 0,
        i = 0;
    str += '';

    if (!charlist) {
        // default list
        whitespace = " \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000";
    } else {
        // preg_quote custom list
        charlist += '';
        whitespace = charlist.replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '$1');
    }

    l = str.length;
    for (i = 0; i < l; i++) {
        if (whitespace.indexOf(str.charAt(i)) === -1) {
            str = str.substring(i);
            break;
        }
    }

    l = str.length;
    for (i = l - 1; i >= 0; i--) {
        if (whitespace.indexOf(str.charAt(i)) === -1) {
            str = str.substring(0, i + 1);
            break;
        }
    }

    return whitespace.indexOf(str.charAt(0)) === -1 ? str : '';
}

function http_build_query (formdata, numeric_prefix, arg_separator) {
    // http://jsphp.co/jsphp/fn/view/http_build_query
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Legaev Andrey
    // +   improved by: Michael White (http://getsprink.com)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +    revised by: stag019
    // +   input by: Dreamer
    // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
    // -    depends on: urlencode
    // *     example 1: http_build_query({foo: 'bar', php: 'hypertext processor', baz: 'boom', cow: 'milk'}, '', '&amp;');
    // *     returns 1: 'foo=bar&amp;php=hypertext+processor&amp;baz=boom&amp;cow=milk'
    // *     example 2: http_build_query({'php': 'hypertext processor', 0: 'foo', 1: 'bar', 2: 'baz', 3: 'boom', 'cow': 'milk'}, 'myvar_');
    // *     returns 2: 'php=hypertext+processor&myvar_0=foo&myvar_1=bar&myvar_2=baz&myvar_3=boom&cow=milk'
    var value, key, tmp = [],
        that = this;

    var _http_build_query_helper = function (key, val, arg_separator) {
        var k, tmp = [];
        if (val === true) {
            val = "1";
        } else if (val === false) {
            val = "0";
        }
        if (val !== null && typeof(val) === "object") {
            for (k in val) {
                if (val[k] !== null) {
                    tmp.push(_http_build_query_helper(key + "[" + k + "]", val[k], arg_separator));
                }
            }
            return tmp.join(arg_separator);
        } else if (typeof(val) !== "function") {
            return urlencode(key) + "=" + urlencode(val);
        } else {
            throw new Error('There was an error processing for http_build_query().');
        }
    };

    if (!arg_separator) {
        arg_separator = "&";
    }
    for (key in formdata) {
        value = formdata[key];
        if (numeric_prefix && !isNaN(key)) {
            key = String(numeric_prefix) + key;
        }
        tmp.push(_http_build_query_helper(key, value, arg_separator));
    }

    return tmp.join(arg_separator);
}
function urlencode (str) {
    // http://jsphp.co/jsphp/fn/view/urlencode
    // +   original by: Philip Peterson
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +      input by: AJ
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +      input by: travc
    // +      input by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Lars Fischer
    // +      input by: Ratheous
    // +      reimplemented by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Joris
    // +      reimplemented by: Brett Zamir (http://brett-zamir.me)
    // %          note 1: This reflects PHP 5.3/6.0+ behavior
    // %        note 2: Please be aware that this function expects to encode into UTF-8 encoded strings, as found on
    // %        note 2: pages served as UTF-8
    // *     example 1: urlencode('Kevin van Zonneveld!');
    // *     returns 1: 'Kevin+van+Zonneveld%21'
    // *     example 2: urlencode('http://kevin.vanzonneveld.net/');
    // *     returns 2: 'http%3A%2F%2Fkevin.vanzonneveld.net%2F'
    // *     example 3: urlencode('http://www.google.nl/search?q=php.js&ie=utf-8&oe=utf-8&aq=t&rls=com.ubuntu:en-US:unofficial&client=firefox-a');
    // *     returns 3: 'http%3A%2F%2Fwww.google.nl%2Fsearch%3Fq%3Dphp.js%26ie%3Dutf-8%26oe%3Dutf-8%26aq%3Dt%26rls%3Dcom.ubuntu%3Aen-US%3Aunofficial%26client%3Dfirefox-a'
    str = (str + '').toString();

    // Tilde should be allowed unescaped in future versions of PHP (as reflected below), but if you want to reflect current
    // PHP behavior, you would need to add ".replace(/~/g, '%7E');" to the following.
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').
        replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}
function urldecode (str) {
    // http://jsphp.co/jsphp/fn/view/urldecode
    // +   original by: Philip Peterson
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +      input by: AJ
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +      input by: travc
    // +      input by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Lars Fischer
    // +      input by: Ratheous
    // +   improved by: Orlando
    // +      reimplemented by: Brett Zamir (http://brett-zamir.me)
    // +      bugfixed by: Rob
    // +      input by: e-mike
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // %        note 1: info on what encoding functions to use from: http://xkr.us/articles/javascript/encode-compare/
    // %        note 2: Please be aware that this function expects to decode from UTF-8 encoded strings, as found on
    // %        note 2: pages served as UTF-8
    // *     example 1: urldecode('Kevin+van+Zonneveld%21');
    // *     returns 1: 'Kevin van Zonneveld!'
    // *     example 2: urldecode('http%3A%2F%2Fkevin.vanzonneveld.net%2F');
    // *     returns 2: 'http://kevin.vanzonneveld.net/'
    // *     example 3: urldecode('http%3A%2F%2Fwww.google.nl%2Fsearch%3Fq%3Dphp.js%26ie%3Dutf-8%26oe%3Dutf-8%26aq%3Dt%26rls%3Dcom.ubuntu%3Aen-US%3Aunofficial%26client%3Dfirefox-a');
    // *     returns 3: 'http://www.google.nl/search?q=php.js&ie=utf-8&oe=utf-8&aq=t&rls=com.ubuntu:en-US:unofficial&client=firefox-a'
    return decodeURIComponent((str + '').replace(/\+/g, '%20'));
}  

function rtrim (str, charlist) {
    // http://jsphp.co/jsphp/fn/view/rtrim
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +      input by: Erkekjetter
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfixed by: Onno Marsman
    // +   input by: rem
    // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
    // *     example 1: rtrim('    Kevin van Zonneveld    ');
    // *     returns 1: '    Kevin van Zonneveld'
    charlist = !charlist ? ' \\s\u00A0' : (charlist + '').replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '\\$1');
    var re = new RegExp('[' + charlist + ']+$', 'g');
    return (str + '').replace(re, '');
}      
function ltrim (str, charlist) {
    // http://jsphp.co/jsphp/fn/view/ltrim
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +      input by: Erkekjetter
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfixed by: Onno Marsman
    // *     example 1: ltrim('    Kevin van Zonneveld    ');
    // *     returns 1: 'Kevin van Zonneveld    '
    charlist = !charlist ? ' \\s\u00A0' : (charlist + '').replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '$1');
    var re = new RegExp('^[' + charlist + ']+', 'g');
    return (str + '').replace(re, '');
}

function parse_str($query_string){
    //$query_string = urldecode($query_string);
    $res = {};//array keys
    $res2 = {};//array values
    //abc=1&c=2
    $temp = explode('&',$query_string);
    //filter valid values
    $temp = array_filter(
        $temp,
        function($x){
            return trim($x)!="" && $x.match(/^\w[\w\d\[\]]*?=?/);
        }
    );
    for (var $k in $temp){
        $t = $temp[$k];
        if(substr_count($t,'=')==0){
            $res[$k]=$t;
            $res2[$k]="";
        }else{
            $temp2 = explode('=',$t,2);
            $res[$k]=$temp2[0];
            $res2[$k]=$temp2[1];
        }
    }
    $res3 = {};
    $key_doubles = [];
    $array_count_values = array_count_values($res);
    for(var $v in $array_count_values ){
        $c = $array_count_values[$v];
        if($c>1){
            $key_doubles.push($v);
        }
    }
    for(var $k in $res ){
        $v = $res[$k];
        if(in_array($v,$key_doubles)){
            if($res3[$v]==undefined) $res3[$v] = [];
            $res3[$v].push($res2[$k]);
        }else{
            $res3[$v] = $res2[$k];
        }
    }
    return $res3;
}   /*
var sound,au
function sound_play(){
    if(sound==undefined){
        au = 'data:audio/wav;base64,UklGRogtAABXQVZFZm10IBAAAAABAAEAESsAABErAAABAAgAZGF0YWQtAACAgH+Af4CAf3+AgH+Bf4B/gH+Af3+AgH9/gH6Af39/gH9/gH+Af4B/gIB/f4CAf4B/gH+Af4B/f3+AgH+Af4CAgH+Af4CAf3+AgH+Af4B/gH+Af4CAgH9/gICAf4B/gIB/f4CAfoF/gH+Af3+Af4B/gH+Bf4B/f3+AgH+AfoB/gX+Af4CAgH9/gH+Af4B+gH9/gIB/f4F/gH+Af4B/f4B/gICAf3+Af4B/gH9/gIB/gH9/gp1VcoLNXHRtY66RMPJEc4edYIKJlGRg2UdkdPYRi7tHdLuSSWxv91Eto5R2nIFbgm/TYSHBolB3q2ZxhqKKQ5CXimhukHV6pos3lq2WOmzFYHaLfmCeh5ZbXsxmbnKraF6kmFZdzHdocKxraal1WWzNa2JzrYpVnINoarh0UIqifV6Qmm5pnYtPi5+HWGyok1OHvzN9unNjZpOuWlnHX3KriFhgq6VPVbmBaYSkXlvAh2RlkJ5tWLB6R7SbXHCMqXNCsIVFuIVceZKrYlCrmFp5lnhelq1ZYJ6mY1uri0uTq0trmKZzS6OrUHi0Xl6Oq3I6q7JUdZx+ZIC5Y0KmmH9TiLVXb7tvVJKahkp7vlhsrINifKCZQXe2aGiLjHVtpKE4grpieHOTkEiWqkeAt3RpbbWGN5uoU2G0kVFwwoJEm5RuWZivRlbRjT6OnpBNh7tHXMB/ToOamVCAuGRpjJpqU6WaTX6rdXKAqWlQrpJZcJORc2OqhFOkk2ZknZZfXKuPTpSWgWCQp19gnY9ZepuSR4vHVWGapGBdoZtCh7VXb4+qa0y1nkxrrnpfe69wUbSMaW6fi1Z2sm9QpoOKYYKrX3GifViUjIRThbhbcpSQa3mVhVmBqF95hJ5uZqiLVXyqb2x3n35QsodXhaR9Y3KvfkSmimxxlZpXeq54U5mNelqQq0uAn4ZrdpaJW4emVXqUjG9moZJeeKduc4OPdmGeiWx5lohteZ59T6GGcnaBnGpwqnZZpoNzb4KhYnKcf2uKkXtpjZ9Wd5t8bXefgGCRnWR8ioJ4bJqBX4uadG+Dm3tdmYlrfIqHaH6hdGuPjX9ogZ9kbZx+cYKJj2SEn2RymX1ydo6SX4SYdHqEinZwlIhYlItxgXiddWqag2SPgXd/dJ5vb5yEbn2OgnNznG56jHmJbJGLbnmZdXWBe5lZipdvfYiFfXt6nFiHknV+b6J2b4eVaYCEfoharHNolIN/d4OKh1indW2QdI9qjId/bZt6bY9unFmTiW2Hg4Zxi3ScV5GRXpNxkHWBe5ljh5dgmV+gcXOLhIJvlnSSWqR2aZlonmGJioRjmoNpmF+uWIOPeHx6lWqSZqxefZZolVuicn55l3F5l2KiUaR5bouBhGyaZqNUn4JgomaYYJR6i2mOj2GiWqlbhJJtf4GUYpxfrl53pVmaY51sh3SWdGyrWaFYonxpjX2HZ51lo1maiFujY5tfkHuRZYeeU6hYpGp7knGCd5tem1+mbm2lYplgl3KHb5Z4ZbVTol2UiGWJgIxioGSeZIuVVZ9xk2GSfItogqBTpl+fb3qUb4B1n1qXa5x6ZKdnj2uQc4h1jIJjq16ZZY6Ka4d+kGKaZ5huf5panXSMbISBim17oF+aaZV4c5F3enefYo5ylYBepHKGcI99fHmKgmKkcIVxjY9qfoeKZJNyjXZ/mGGPgIZugoeDc3qbaIp4iIF0jX14gI9shHiOf2eYfYJ4g4N6e4d/bpR9f3iEkmx5j4Fyg4KHcoGQbIKLgHR8kIRof5V0d4WHf3OQhWmJiXpzgZN4bI+Nb36Bi3Z3k3Z2iYhzfIORcXCceH90iYVxg4d7dJZ4f3SOi2GLhYNwhoWDcYePYZV6h2+BmHBzg5pjh32PdnKgZoJ9k214ipBzZqxrg26UhGSQhn5lpm9+c5CUT5mDiWOMiXtzhJ1Qn3qKa3uiaHh9o1qOeZF6Yq9fhniYbXiLi3pat2KIb5CLYJZ/g1+raoF1ip5FpH2HY5GIdXmApUSmeYVueqdhe4CoTpJ/iXpevVeDgJtndZGHfFLFXX96kItWm4SAV7RsfHmGozqng4JhlItzeX2tO6WAfnV1qWR4galKk4SCf1y9Wn2DlmZ6koGBU8ZbeIWHiVqchXxcuWhzhoGeP6aLdGaehWx+f6s4pI1xd3ylYXSKp0STknl+YLteb46YYHyVg31UxWFrjoqCXpiMd1q3bWyMgJpLm5RtZ6CAbn+Bp0OclGl4hJZodIyhR5SWb39srGdpl5RahZR7eGO7ZWSbjHJtk4xuYblrZpWGiVaVmmNnq3tqgYyaSZSeZHGTjG9wk5xHkJpvdnuhcmSYllWHkH10bq5wYpqPZ3qJj25osm1qkYl7aoyZZWuucW6CjYpYkJ1lcaF8dHGWkEyVmW5vjpJ0Y5uWTZGQfm18pG9gmpZahIqSaGyvbWeNlG51iJxnZ7JscX2Tg2GLnWxopXN7b5SPU5SUc2mVhHxmlphPmId/bIGYcmeVmVSRg41pcapqboyXZIGFlWdptWV2f5h6Z4uXbWOvbH10lo1SlZFzY56AfWmVm0qYhoFohpZ2aZGeTpKAjm1vqm5xh5hig3+TcGW1Z3p9k3lshpN0Y7BqgnaNjFeUjnZmoXp+bouaTJqHfm6KkHVrjJ5MlYOJcXGobmyIml2Gg5F0YrVpcoGTeW6Fl3Zer2x7eIuQWo6Vd2OeeX5uh6BQk458b4WNe2mJolGRh4R2bqB3aYqaYIeCjXdgrnFsiJJ4dIGVdV2ucXOBio5hhZtxY6B7e3OJnlOHmHRsi4t/ZoykUYaRfXVznYJfj51egIWNdmOogWCMlHJ2e5t0Xat+aoOLiGd4pXBioYB3dImXXHmmcGqThoRhjZ9Vfpt6cX+TiVWSn1l+jotxbZ+LUZGZZ3x9oW1iqIZbhZZ7cXKwbV2mgm10ko5lb7RuYJyCg1yUnFx1p3pijYeQTZWkVX2VjGZ3k5RJk6RcgX+hZWeijlGHpWl9brJnWqyBZ3WhfW9rt2tTrH5+X5+TYG+ud1Oifo9NnKdOfJqMVoqMlkmRsUuEhJ9bcqCRTYa0VINvsGVasYNjdLFpdWy2b0i8enhipYhgca97RrV5iFOdo0l+oIxHoIePTo64O4qNmVOAoYlQgcFBh32nY1+2fV11vlV6eK5yQ8h1bWeue2J8qYA6xXV4X56fRoSjjDyvg4Bai7s0j5eSS4yfe1l+zDWKjplhZLh0XnfHR3yLnnJCzXFlcLdtY4ycgDPQdGhwoZdEj5+HNbqFbW6LtzKSnYZFmp9raH/ML4mbh2BwuGpmfMk+eJ6Jc0zNb198uGFhm42AOtJ2WoGiiUSZmIM4wIlagYqqNJSje0eloVp3gMAwh6p3X3+2Xm2BwTt2sXdxXMhrW4ezWWKtfnxLzHdNj6F5S6SKiE+sh0uXiJBOmpl8UZ+mSIZ8o1CGo21wjaNUgIalSHm2ZXdrt2lfladbZ7Ruel2wfE6YoHRZrIR5WKKSR5SPhliZl3Jgm5xMkIGYWXyrY3COmWCEh5pcc7RacoabanOTl2drtWJwgJxzXZ+SbWOsfGx3noVMoo5uZ5aTZ2umk0eZkHtkfadoZ5uaVoiTg2dwsWVnkpdubJePZ3Cqb26GkX9bmJJjc6R7cICPjlKSk2N3lIhsfpSOVY6aZ3WGlmlynIdeh5p0b3+mZ2megmp0moNofKlxYp2CdWaVk156ooFhj4x+Yo2bXXyUimR+l4JhiaBhgYWRbWqhe2aHnWx8g5VzXqd4bIGVe3KFlHhepnpxeZGMX4mSfF+bhnN3ipxSiZN7Z4aXd26FqFWCj39zcKJ1boKmY3WPhH5dqXlvf5R7Z46Hg1ynfnCBhY9bi4qCYpuGcYZ4n1SLj3hvh5VtgXiiWIWVc390oWp4gJtieZx1h2OscGyHjHZpnHmJXqt9X5B/jVyShoRhmoteknWeWIuSeXCCmGCKcqRgf51uhm2eaX17mG1zo2qQYp92cIiIfmiia45im4JhmXiQYZl8f2uOjlmecZphipJvd4GZWZVznGd5pGOGcqBlg3+Tc2erX49onHxtkIaBYKRnjWaTjludeI9hlXqDcISbU6Fvk2mBk3F/eqBZl3KOc2+kY4p1nmiFgIZ8Ya5djnKUfmySfIVfqmeHdoiSVpx3iWSYfX18fp9NnnSJb4CXb4N3o1WSeod9Z6pmh3aYaoCEgodcsGWHd4mDao98jV2pboF+eZdYmHmKapWBd4RyoVOYfYF5fpRshHOiWI+Ge4ZopWaBeZZrfZB5jV2ranmBh4NplH2MXaN1dIZ5mVqVgoVoj4ZviHGkWY+Jenp3lW2EdKFihY91h2afbn18lHJ4lHKNX6N1comEhWuTeIhinH9qkXqUYo2EfWuPi2ePdpxfhZFzeX6XaoV7mmV5mm6EcJx1doSRcG+ZcodnnYFqjIaAaJN8g2mVi2ORfIxnh4x6cIuRZYx5kWl9mHJ6g5RrgX2QbXOgbYF8lHdxiIxyb59yf3eSg2KQiXdul4B7c5CLWpKGf26JknRzjJFdiYeEcXqgcHOIkGp8iYlzcKRxdIONeW+Ni3Zvn3dzgIiGY5CNdXSTgnF8iYxejo90d4ePbnaMjmCIk3d5eptscJGLa3yWfnVzn3Fpkod4cJWIcHKdemaOhoRlkZJrdZOGZoaJi2KJmWd5h5BrepCLZ4Gbanp9lXFul4dveZpzd3mYeWSdgXdxlIFtfJaAYJuDfGyNkWN+kodhkop/aYWeXH+MjWeAln5qfKVgfIiQcmygfWx3om50hZJ9XqN9cHSYgGmGkIZZn4FzdIqTYIaPiF2TinR2gKFahY+GZoGWc3Z6p11/kYNzbaJ0cnqkanOTg35eqHptfZp8ZZKGg1elhGmAjJJZjYqEW5aRZ4KBolaEkn9ngp1pf3umXHqZenZvpm54fKBpbJ13gmKoeW2Ek3pgnHuEXqSGY4uGi1mVhYBil5RcjX2YV4mVd22GoVyGfJxdeaNweXenZXiBmmloqXCBaqd1aIqQel2od4JmnohZkYWJWpuFfGqRlVKTf5JdjJdyc4OfVI1/kmZ5p2h8eaJgfYiPcWqvZ35znnNqk4l7Ya5venGWh1ebhYReoIFxdYyXTZqFiGOMlml4g6BSkIiIbHenYnx+oGB/kYV2Zq9ke32XdWyagXxhrG51f4+IW56BfmOff2yDh5ZSnYV7aoyUZIOEnFWRjHd0eaNgf4aaYICWd3prrWV4iJFzbJx5e2ercW+MiYVbnIB4ap6BaI2DkFWYiHNyjZNjiYKVWI2Sbnx9n2OAh49jfppvgHOlaXaOhnFwnnN9caRzapSAf2Scf3Z1moBilH6IX5WNbXqOjV+Lgoxhi5hpgYGWY3+LiGh+n2uBeZptcZOCc3Wecnx3mHZnmH99bpl/dHmRgGKYf4FtkY1pfouHY46Fg26Hl2SBhotogo6CcH+dZn6BjnNzl390eZpvd4GOe2ibgXd2kn1wgI2BZZmCeXWJjGiAjIZojoh6dYCVZoCMhXCCj3tzfJhqfIqFeXWUfHJ8lXF2ioZ/a5eAcH6NfnGIiYFpk4Rvf4eKbIOOgWuKiXJ9gJJsfpJ9cn+Pdnh/lHF3kn16dpB9coKRd3OPf31ukIVthIqBcYmDfm6MimqGhYhxgIx7cYaOboOCi3J5k3d2gJB1fIONdnGVdnt7jX9zh4p6bpN8e3eKimuIh35vioN7d4eRZ4iEgnJ+j3l4gpNsg4OCeXSWdnmBkHV5hYR9bZh4e4CJgW+IhH9slX55gIKNZ4iEf3GLh3eAf5Jkh4d+eH6Rdn59kmqBiXyAc5l1eoCNdXaMfoRsmnp2g4SCbY2Bg2yVgXGEfI9nioWBc4iKb4V5lGeEjXt8epJwgHqTbn2Rd4Rwl3N5gIx4c5R4iGqVfHGIgYVskn2DbI+Faot7kGmLhn1zhI5oi3iUaoKRdXx4lW2De5Jzd5dwhHCXdXiEjHxtmHOGbJOBboyBh2iTeoFvi41lkHuPaYeHeneClGSPe49sfJRyfnmZa4V+jHVwnG2EdJd3doiGfWedcYRzkIVpj3+EZpd7fnaJkl+RfIlqiIp3fICYX41+iHF5mXCAeplogoKGem2hbYJ5lHVyi4KBZaJzf3qKhmWRf4Rnmn54fIOSXJKAhW2KjXGAfZhcjYWBdnqcbH57l2aAi39/bqNre36RdHGSf4JnonN2gIeFZZWAgmmZf26DgJNdkoZ/cImNaoN9l16MjXp4eZppfn+WaH+Td39uoGx3ho51cZh6f2mfdnCKhoRnmH98a5iBaIyBj2CSiHZzio5miYGSYYiScHl+mGiAhZFpfJhvfnSdbnaNi3Nwm3V9cJx6bJKEfmmXfndyloRkkoKGZI+KcXeNjmONg4llhpdrfISVaICJiGt7nGt9f5dvc5GFcHOec3p6l3pnlYF4bpl+c3yTg1+Ug31sj41ufY6JYI6Hf2yHmWh9iY5mgY2BcH2fanuHjW9zlYFweJ9ydoOOeWiYgnN3mXxugo6AYZeHdXaPimmAjoNikI12dYiVZXuPhmiEknp0gJpnd4+GcHeZfnB9mm9xjYd4bpmDbX2VeGuKi31ol4lsfI2FaIWPf2iQj2t6h49nfJWAbYaTcXWEk2p2mH9wfZZ3b4OVcXCWgnd0lYBohJJ5a5KKd2+SiWWDjoBsiZB3bY2QZYCMiGx+mHdvhZNreouLcHadd2+Ak3Vwi451b5p8cnuRf2iMj3hslYRxeI2KY4qPe26KjW92i5BjhZF/cH6WcXOIkmp9kYFzdZxzcIeRdXGShXVvm3puh42AaZGJdG6VhGyDiopkjIx0couNaoCKjmSEknR2f5ZueouNa3uVdnh4m3JyjYt0cJV8eHOZe2yQhX1plIR0dJSGaIyEhWaMjXB5jI5mh4aIZoSWbnyBlWt9ioZteptvfX2XcnGQg3VymnZ8epR7aJR/fW2XgXV8joZikn+CbI2NboCIi2KLhYNug5hqgYGQaYCLgHR6nmmAf49ycpR+enOdcnp/i31omH19cpd+coCIh2GXfoB0i4tphIOMYpCGfXd/l2WDgoxrg457e3icZoCDinZ0l3l+cppveYaEgmmbenx0knxwiIKKZJh+eniGimmKgYxlkId1fXuWZYeDim+Cj3KAdZpogImFenSWdH9zmHF5jX6Eapl3e3iQfm+PfItllH91f4SKao5/iWiMiHCDe5VpiIOFcYCQbYZ2mGx/i318c5ZygneUdnaQeIRslnh7fI2BbJF3iWmQgnSEgotpjnyGbYiNboh7kWuFg4F2fpNsiXiSb3qNen50l3KEeY16cJN2hHGTe3p/h4Jqk3eHcIyGcYV/iWmPfYR0hJFriHqNboOGf3t8lGuHeox1eI97gHaUcYF8iH9vlXeEdY98doGDhmqUe4R2hodvhX+JbI6AgHh+kWqHfYpzgol8fXiUbIR/h3t3kXp+dpF0foGEg2+UeX53i351hYOIa5F9fXqCim+GgohuioN6fHyRbYSDh3V/iXl+eJRvgYaDfHSPenx4kXh6h4GDbpB9enyKgXSHgYVrjYJ4foKLcYWDhG+GiHaAfZFwf4eBdn2MeH98knN6iX98dY98e3yPenSKfoFwjoJ2gImBcIeCgm+KiXSBg4dwgYaBcoWNc4B/jHF6i393fpB3fX6MdnSPfnt6j392fot7cI9+fnWNhXGCh39vioV/dYaMb4GFhHKBjH12g49vfoSGdniSfXd/j3V4hYd6cpV9eHyLfnKEiH5vkoF4eoiGbIWJf3CMh3h4hI1rgoqBdIKOd3eCj258jIJ5eJR5c4KOdHeNg3xylHxygYp/b4uHfW+Rgm+ChoZsiYt8cYmKcH6EjWuDj3t0go5yeoWOb3yTenh3lHd1h4x2dZR9eXSTfm6JiX1vj4N5cY+FaomGhWuKi3Z0iY5phoaKbIGScniCkW5/iIpveJZ0eXyUdXWMiHVxmHd6eZN/a4+FfG2TgHZ5j4hmj4R/bIuLcnmKj2SKhYJugpVufYWSaYCLgXJ4nG98gZFzc4+CdnGddHmAkHxok4F6b5h+dICKhmGShHtwjoxtgIiLYYyIfHSDlmp+hY5mgo97d3qeanuFi3F0lXx5dZ5xdYaJfGmYf3l0mHxuh4WFYpeFdnaOiWeGhYpij4x1eoKUZYGIimiElHR7eptne4uHcniadXp2m25yj4N8bZt8dneVe2qQgoRnmINxeo2HZY2EiGaQjWx+g5Fkh4qFa4WVa359lml9kIJzeZlve3uXcHKWfntvmnd1fZJ8apd+gGuVgm2BjIVlk4KBao6NaISGjWaKin5uhJZmg4KRa32TenR7mmt+gpFycph4eXSZdXaEjntpmnl7cpSCbIiKg2WUgHpyjI5miYeHZouJeHSFlmSGhYlsfpN1eH6Zan+HiHNymnV4fJd0dYmHemubeXh6koBri4V/Z5eAdnuKjGSKhoFrjIt0fIWSY4WIgXCBlHN7gZRofouBd3aadHmAkXJ0joF7b5t6dYGMfWyOg31uloFygYaHZ4yHfXCNim6Ag41mhox7doORcHyDjmt/kHx5epZyd4SMc3aSfXx0lnhxh4h8cJGCe3ORgG2GhYNtjIh5dYuHbISEiGyGjnZ4g41uf4aJcH+Sdnp9kHR3iYh1eJJ5enmQenGLhntzkH93eY2CbYuFgHKJh3R6iYZth4aCcoONc3qFinGBiIN0fJFze4KLd3mMg3Z4kXh5gYp9c42DeHaNf3Z/ioNvi4R6doeGc3+Ihm+Gh3t4gY1zfYiGc3+JfXh9kHV7h4Z4eIt/eHqPeniHhH5yi4J3e4uBdYWFgXCIhXd8hod0gYaCcYSJd32BjHV9iIF2fYt5fX+NeHiJgHp5i356fox8dYl/f3WKg3eAh4Jyh4GBdIaJdYGDhnOBhYB2gox1gYCIdnuIf3p9jnh/f4h6dYx9fnqMfnmAhX9yjH5/eYiEdYGDg3GJgYB5g4txgoCFdIKGfnx+jnKAgIV6eot9fnuNdn2BhH90j3x/eYp9d4KBhHGOf357g4ZyhIGGcomDfHx9jHGDgYZ2goh6fnmPcYCDhH54jXp+eI14e4aBg3KOe3x6iIB2hoCHcIt/en6AiXGHgIdxhYV4gHuPcoODhHd+ineBeJF0f4aBfnWOeIB5jXt4iX2EcI59fHyIg3OJfIZvioJ4gIGLcId/hXGEiXWDe49xgYOBeHyOdYN6j3V6iH5+dZB5gHqMe3OLe4Rxj396foaEbot8hnGJh3WCgIluh4GEdIOOcoN7jXCAhoB6e5JygnqMdneNfX92kXh8fIl+cJB8gnOPf3Z/hYRsjn6DdIeIcIOAiW2IhYF3gI9tg36LcoCLfnp6km+Af4p4dpF7fXWRdnqBh4Bvk3x+dYyAc4SFhWyQgH12holuhYOIbYmGenh/kWyDhIhzf4x4enuTb3+Gh3l1kXl7eZJ3eYeFf26Te3l6jYFxiISDa4+Bd3yGim2HhYNtiIh2fIGRbIOHgnJ/j3V8f5NvfIqBeHaTeHl+kXd2i4B8cZR9doCNf2+Lgn9ukIRzgYeHa4eGfnCKi3GAg4xrgYp+dIKQc32Cjm57j314epR3eIKMdXSQfnt2k31yhIp7b46CfHSQhG+Eh4Jtioh7dIuLbIGFh26Cjnl3hY5vfYaJcHyTend/kXR3hol1dpR8eHuQe3CIiXpykYJ3eY2CbIeIfXGMiHZ3iohrhImCcYSPdHeGjG1/i4NzfZN2dYONdHeMhXV4lHl0gY16cY2HeHSRf3J/i4Fti4l4dIuHcXyLhmyGjHp0hYxyeoqIboGOfHR+knN2iYl0eY9/dHuSeHKJiXlzjoRzeZB+cYWJf2+LiHN4jIVvgouCboaNdHiGinF9jYNvgY92doOPdHeNhHN6kHp1gI95co6Ed3WOgnJ/jn9wi4Z5c4uIcH6Mg2+GiXxxho1wfYmHcX+NfHKBkXN6h4p1d49+dH6QeHaGi3hxkIB1eo+AcoWKfW6NhHZ5iohug4p/b4eJd3iGjW6AiYJxgY54d4ORcHuJg3Z4kXp3gY93doqEeXOSf3WAjX5xiIZ8cY+DdH+JhWyGiH1xiYp0foWKbYGKfXWCj3V7g4xvfIx+eHuSeHiCi3V2jn97dpJ9dIOIfXGMgnx1j4Jxg4WCbomHfHWJiW+Cg4duhIx6eYONcH6EiHJ9kHp7fY91eYWHd3iRe3x5j3tzh4V9c5B/e3iLgm+Ig4Jxi4V5eYaHboaDhXKFi3Z7goxvgoWFdn2QdX1+jHV7iIR6eJF3fHyLe3WKg350j3t6fIiCb4yCgHOKg3Z+hIhuiYOBdYOKdH6Ci3CEhYB4fI9zf4CLdn2Jfnt3kXZ8gIh9dYt+fXWOe3qBhYNwjH9+domDdoKCiG+Jgn15gopzgoKJcYOGfHx8jnSAgYd2fIl7fniQd32DhH11i3x/eI19eYWBg3GLf316iIR1hH+GcIiDfH2CiXODf4Zzgoh6gHyNdX+Cg3l8i3qBeo14e4SBf3aMfIB6in12hn6DdIqAfXyEg3SFfoV0hoV6f3+Hc4OAhXeBiXiBe4p2f4OCfHyLeYB6iXp5hoCAeIp8fnqGf3aIfoR2iIB7fYKFdId/hXeDhXiAf4d2g4KDeX6Jd4B8iHl+hIF9eop4gHyIfnmHf4B3iH19fYWDdoh/gXeFgnp+goZ1hn+CeICHeIGAiHeBgoB7e4p4gH+He3yEf315inp/f4Z/d4Z/f3iIf3yAg4N0hoB/eYSFeoCChXSDgoB7gIl5f4GGdn+Ef318jHp+gIV7eoZ/fnqLfXuBg392hoF+eoiBeYCCg3SGg357hIZ3f4GFdYKGfX1/iHh9goR4foh+fXyKenqDhHx6iIB9e4l9d4ODgHeHg3t7hoJ2goOCdoWGenuDhXaAhIR3gYh6fH+IeHyGg3l9int7foh7eIeDfHqJf3p9iH92h4N+d4eDeH2Gg3WFhIB3g4d2fYSFdoCHgHd/inh8god4fIiBeHyLenqBiH13ioF6eol/d4GHgHSIg3p5h4R2gIeDc4WFe3mDiXR/hoR1gIh8eYCMdnyGhHh7in14fox6eYaFe3aLf3l8i392hYV+c4qDd32HhXODhoBzhod3fIWJc4CHgXSBi3h8gYx0fImAeHuNe3mBjHl2iYF7d41/d4CKfXOIg310jIR1gIeDcYaFfnWHiXN/hIZxgYl9doKNdX2DiHN8jH15fo93eoKJd3aOfnt6jn11g4d8c4yCe3iMg3GDhoFxiYV7eIeIb4KFhHGDi3p5g4xxfoWFdX2Penl/jXV6hoZ4d5F8enyMe3SHhXxzkH94e4qCcIeGf3OLhXd7h4duhYaBc4WKdXuDi2+BiIF2fo91eoGMdHuKgnl4kXh4gIt6dYuDe3WQfXaAiIFwi4R8dIuDc4CGhm6Ih3x1hYlzfoWJb4OKfHd/jnN8hIpzfI18eXqRd3iFiHl2jX55eJB8dYWHf3GMgXl3jIJyhYWDb4mGeHiGiXGChYVwg4p4eoKNcn6GhnN9jnl7fY92eYiEeHePe3p8jnx0iYN8c42AeHyMgXGIg4BxioZ2fYeHb4SFgXKEi3V9hIpxf4eBdX+PdnyBi3V5ioF4epB5en+LenSLgXt3jn93gIh/cYqDfXaKhXR/hoNwh4V+doWKc3+EhnGBiX54gI50fYOHdnuMfnl9jnd6god6do2Ae3qMfXaDhn5yjIJ7eoiDc4KFgXKIhXt6hIhygIWDdIOJe3qAi3N+hoR4fYx7en6Ld3qGhHt5jH15fYp8d4aEfnWLgHh9h4J0hYWAdYiEd32EhnOCh4B2g4d4fIKIdX+IgHh+inl7gYl4e4mAe3qKfHmBiHx4iIJ8eImAd4GGgHaGg313h4R2gISEdYKGfHmDh3d/hIV3fod9eoCIenyDhnl7iH57fYl9eoOFfHiIf3x8h4F3g4R+d4WDe3uGhHeCg4F3goV7e4OGeICDgnl+iHt8gYd6fISCenyIfXyAhn16g4J8eod/fH+FgXeDgn56hYN7foODd4GDfnuBhnt9goR4f4R/fH+IfH2BhHt8hYB9fIh+e4GDfnqEgX18hoB6gIKAeIODfnyEg3p/gYJ4gYR9foCFen6Cg3p/hX5+foV8fIKCfH2FgH58hX56goF/eoWBfX2EgHmCgYJ6g4N8fYGDeoCBg3qBhXx+f4R7f4KCfX6GfH59hX18g4F+fIV+fn2DgHqDgYB6g4F8foKCeYOAgnuAg3t+gIV6gYKBfH2Ge39/hXx/g4F9e4Z8f36Ef3uEgH95hX9+foOCeYSAgHqDgX1+goR4g4CAe3+FfICAhnmAgoB9fId7f3+FfHyDgH96iH1/f4N/eYR/gHmGgH1/gYN3hICAe4OEe4CAhHeCgoB8f4d7f3+FeX+Df358iHt+f4R8e4V/gHqIfXyAgoB4hYCAeoaAe4CBg3eEgoB7g4R5gICFd4KEfn5+h3l/gIV6fod+fnyIen2BhH17h35/eYh9eoKCgXeHgH56hYJ4goGDd4WDfXuChXeBgYV3goV8fH6IeH+ChXp9iHt9fIl7fIOEfHqIfX17iH55hYKAd4eAfHuGgneEgoJ2hYR7fIOGdoKCgneBh3p9gYh4f4OCeX2Jen1+iXp7hYJ7eYp8fH6IfneGgX54iIB7foaCdYWCf3eGhHl+hIV0g4OAeIGIeH6ChnZ/hYB5fot5fYGHeXuHgHt7i3x7gIZ9doiBfHqKgHiAhYB0hoN9eYeEd3+Eg3SEhX56g4h2foOEdYCHfnt/ind8g4V4e4l/en2LenmDhXx4ioF7fIl+d4OEf3WJg3p7hoN1gYWBdYWGenuEhnV/hoJ2gol7eoGJdnyGg3l9inx5f4l6eYeEe3qKf3h+iX13hoR9d4mCd32HgXWEhn92hYZ3fYWEdYGIf3eCiHh7hId3fYmAeH6KenmCiHl6iYF5e4p+d4KIfXeIg3p6iIF2gYh/doWFe3iGhXV/h4J2goh7eIOId32HhHd+iX14gYl5e4aFeHqKf3h/iX14hYZ7eIiBeH2IgXaDh3x3hoV4fIeEdYGHfnaDh3l6hYd2foeAd3+Ke3mEh3l6h4F4fIp+eIOIfHeGg3l6iYF3gYd/dYWFenmHhXd/hoF1god7eYSHeHyGg3Z/iX15gYl6eoWEeHuJf3l/in13hYR6eYiCeX6JgHaDhX13hoV5fIeDdYGFf3eDiHl8hYV2foeAeICKe3uDhnh6h4F5fYp9eoGHe3eHgnt7iYB4gIZ/dYaEfHqHhHd/hYF1g4Z9eoSHd32Eg3aAiH56gIl4fISEeXyJf3p+iXp5g4V8eYmBe32IfniDhH93iIN7fIWCdoGEgXeFhXp8g4V2gIWCeIGHe3uAh3d+hYJ6fYl8e3+IenqGgn16iX56fod+eIaDfniIgXl+hYJ3hIN/eIWEeH6DhXaChX95gYd5foGGeH+Gf3t+iHp9gYd7e4d/fHuIfXuBhX55h4B9eoeAeYGEgXeFgX56hIR4gYKEd4KEfXuBh3iAgoR5foZ9fH6Ien6ChHt7h319fYh9fIKDfnmHfn58hoF5g4KAeIWBfX2EhHiCgYF4goR9fYGGeIGBgnp/hnx+f4d6foKBfHuIfX5+hn17g4F+eod+fn6EgHmDgIB5hYF9foKDd4OBgXqChHx/gIR4gYGBfH+GfH9/hXp+g4B+fId8fn6EfXuEgIB7hn59f4KAeoSAgHuEgHx/gIN5g4GAfIGDe39/hHqBg4B+foV7f3+EfH6Ef398hXx+f4N/fIR/gHuFf3yAgYJ6g4CAe4KBfICAg3qCgX98gIN7gH+Ee4CCfn59hXx/gIN9fYN+f3yEfn6Agn98g39/fISAfIGBgnqCgH98goN8gYCCe4CCfn2AhHyAgIN8foN+f36FfX6Agn18hH9/fYSAfIGBf3uDgH99g4F7gIGBe4GBf32Bg3uAgIJ7f4N+fn+EfH6Agn19hH9+foR+fYGCfnyEgH59g4B7gYGAe4OBfn2BgnqAgoF8gYN+fYCDe3+CgXx/hH59f4R8fYKCfn2Ffn1/g358goJ/fISAfH6DgHuCg397g4J8foKDeoGDgHyAhHx9gYR7f4SAfH6FfXyBhH19hIF9fIV+e4CEf3uEgX17hIF6gIOBeoKDfXuCg3p/g4J6gIR9e4CFe36Cg3t+hX58f4Z9fIODfHuFf3x9hX96g4N+eoSBfH2FgnmCg4B5g4N8fYOEeYCDgHmAhnx9goV6foSBen6HfXyAhnx7hIF8e4d+fICFf3mEgn16hoF7f4WBd4ODfnqEhHp+g4N3gYR/eoGHen6ChHh+hX97f4h7fIKEe3uGgHx9iH57gYR9eIaBfHyHgXmBg4B3hYN9fISEeH+DgneChX18goZ4foODeH+HfXx/iHl8hIN7fIh/fH6IfHmEg315iIF7fYZ/d4ODf3iGg3p9hIN2goSBeISGenyChXd/hYF5gIh6fICHeH2Ggnt9iXx7f4d7eYeCfXqIf3l/hn93hoN+eYaCeH+FgnaEhH54hIV3foOFd4GGfnmAiHh9goZ5fYd/en2JenuChnt6iH97e4h+eYKFfneHgXx6h4F3goWBdoWDfHqEhXeBhIJ2goZ8e4KId3+Eg3h+iHx7gIl6fISDenqJfnt+iH15hIN9eIiAe32HgXeEg353hoN6fYWEdoKEf3eDhnp9g4Z2f4WAeX+Ie3yBh3h8hYB7fIl9e4GHe3mGgXx6iX96gIV/d4WCfXmHgnl/hIJ2g4R+eoSGeX6DhHaAhn56gYh5fYKFeH2Hf3t+iXt7goR7eoeAfH2IfnmChH54hoJ8fIaBeIGDgHeFhHx8hIR3gIOCeIKGfHyBhnh9hIN5f4d9fH+HenuEg3t8iH57foZ9eYSDfXqHgXp+hYB4g4R/eYSDen2EgniChIB5goV6fYKEeX+FgHp/hnt8gYV7fYWBe32HfXuAhX16hYF8e4Z/eoCEgHmEg317hIJ6f4SBeYKEfXuChHp+g4N6gIV+e4CGe32Dg3t9hX97foZ9fIODfXuFgHt+hX97goN/eoSCfH2DgnqBg4B6goN8fYKEen+DgHqAhH19gYV7foOBfH2FfnyAhX18g4F9fIWAfICEf3uCgn57g4J8f4OBeoGCfnuCg3x/goJ7f4N/fICEfX6Bg3x9hH99f4V+fYGCfXyEgH1+hIB8gYJ/e4OBfn2DgnuAgoB7gYJ+fYKDe3+BgXx/g35+gIR8foGBfX2Ef35/g358gYF+fISAfn+Cf3uBgX98g4F9f4GBe4GBgHyBg31+gIJ7gIKAfX+EfX5/g3x+goB/foR+fn+Cfn2CgH99g399f4GAfIKBgH2CgX1/gIJ7gYGAfYCCfH9/g3yAgn9+foN9f3+Cfn6Df399g35+gIJ/fYN/f3yCf32AgYF8goB/fYGBfICAgnyBgX9+f4N9gICDfX+Cfn9+g31/gIJ+fYN+f32Df36AgYB8gn+AfYKBfYGAgXuBgH99gYN8gX+CfICBf39/hH2Af4J9foN+f36Efn+A'
        sound = document.createElement('audio');
        sound.preload = "auto";
        sound.autobuffer = true;
        sound.src = au;
        sound.load();  
    }
    //sound.stop(); 
    sound.currentTime = 0
    sound.play();  
}
//sound_play()   */

function togglebeep(){
    $("#volumeControl").toggleClass("active");
    localStorage.volumeControlActive=$("#volumeControl").hasClass("active")?!0:!1;
    beep()
}   
var snd
function beep(x) {
    x = x==undefined?!1:x
    var n = localStorage.volumeControlActive;
    if (n = x||( n ? JSON.parse(n) : !0), n)
        try {
            snd || (snd = new Audio("data:audio/wav;base64,UklGRogtAABXQVZFZm10IBAAAAABAAEAESsAABErAAABAAgAZGF0YWQtAACAgH+Af4CAf3+AgH+Bf4B/gH+Af3+AgH9/gH6Af39/gH9/gH+Af4B/gIB/f4CAf4B/gH+Af4B/f3+AgH+Af4CAgH+Af4CAf3+AgH+Af4B/gH+Af4CAgH9/gICAf4B/gIB/f4CAfoF/gH+Af3+Af4B/gH+Bf4B/f3+AgH+AfoB/gX+Af4CAgH9/gH+Af4B+gH9/gIB/f4F/gH+Af4B/f4B/gICAf3+Af4B/gH9/gIB/gH9/gp1VcoLNXHRtY66RMPJEc4edYIKJlGRg2UdkdPYRi7tHdLuSSWxv91Eto5R2nIFbgm/TYSHBolB3q2ZxhqKKQ5CXimhukHV6pos3lq2WOmzFYHaLfmCeh5ZbXsxmbnKraF6kmFZdzHdocKxraal1WWzNa2JzrYpVnINoarh0UIqifV6Qmm5pnYtPi5+HWGyok1OHvzN9unNjZpOuWlnHX3KriFhgq6VPVbmBaYSkXlvAh2RlkJ5tWLB6R7SbXHCMqXNCsIVFuIVceZKrYlCrmFp5lnhelq1ZYJ6mY1uri0uTq0trmKZzS6OrUHi0Xl6Oq3I6q7JUdZx+ZIC5Y0KmmH9TiLVXb7tvVJKahkp7vlhsrINifKCZQXe2aGiLjHVtpKE4grpieHOTkEiWqkeAt3RpbbWGN5uoU2G0kVFwwoJEm5RuWZivRlbRjT6OnpBNh7tHXMB/ToOamVCAuGRpjJpqU6WaTX6rdXKAqWlQrpJZcJORc2OqhFOkk2ZknZZfXKuPTpSWgWCQp19gnY9ZepuSR4vHVWGapGBdoZtCh7VXb4+qa0y1nkxrrnpfe69wUbSMaW6fi1Z2sm9QpoOKYYKrX3GifViUjIRThbhbcpSQa3mVhVmBqF95hJ5uZqiLVXyqb2x3n35QsodXhaR9Y3KvfkSmimxxlZpXeq54U5mNelqQq0uAn4ZrdpaJW4emVXqUjG9moZJeeKduc4OPdmGeiWx5lohteZ59T6GGcnaBnGpwqnZZpoNzb4KhYnKcf2uKkXtpjZ9Wd5t8bXefgGCRnWR8ioJ4bJqBX4uadG+Dm3tdmYlrfIqHaH6hdGuPjX9ogZ9kbZx+cYKJj2SEn2RymX1ydo6SX4SYdHqEinZwlIhYlItxgXiddWqag2SPgXd/dJ5vb5yEbn2OgnNznG56jHmJbJGLbnmZdXWBe5lZipdvfYiFfXt6nFiHknV+b6J2b4eVaYCEfoharHNolIN/d4OKh1indW2QdI9qjId/bZt6bY9unFmTiW2Hg4Zxi3ScV5GRXpNxkHWBe5ljh5dgmV+gcXOLhIJvlnSSWqR2aZlonmGJioRjmoNpmF+uWIOPeHx6lWqSZqxefZZolVuicn55l3F5l2KiUaR5bouBhGyaZqNUn4JgomaYYJR6i2mOj2GiWqlbhJJtf4GUYpxfrl53pVmaY51sh3SWdGyrWaFYonxpjX2HZ51lo1maiFujY5tfkHuRZYeeU6hYpGp7knGCd5tem1+mbm2lYplgl3KHb5Z4ZbVTol2UiGWJgIxioGSeZIuVVZ9xk2GSfItogqBTpl+fb3qUb4B1n1qXa5x6ZKdnj2uQc4h1jIJjq16ZZY6Ka4d+kGKaZ5huf5panXSMbISBim17oF+aaZV4c5F3enefYo5ylYBepHKGcI99fHmKgmKkcIVxjY9qfoeKZJNyjXZ/mGGPgIZugoeDc3qbaIp4iIF0jX14gI9shHiOf2eYfYJ4g4N6e4d/bpR9f3iEkmx5j4Fyg4KHcoGQbIKLgHR8kIRof5V0d4WHf3OQhWmJiXpzgZN4bI+Nb36Bi3Z3k3Z2iYhzfIORcXCceH90iYVxg4d7dJZ4f3SOi2GLhYNwhoWDcYePYZV6h2+BmHBzg5pjh32PdnKgZoJ9k214ipBzZqxrg26UhGSQhn5lpm9+c5CUT5mDiWOMiXtzhJ1Qn3qKa3uiaHh9o1qOeZF6Yq9fhniYbXiLi3pat2KIb5CLYJZ/g1+raoF1ip5FpH2HY5GIdXmApUSmeYVueqdhe4CoTpJ/iXpevVeDgJtndZGHfFLFXX96kItWm4SAV7RsfHmGozqng4JhlItzeX2tO6WAfnV1qWR4galKk4SCf1y9Wn2DlmZ6koGBU8ZbeIWHiVqchXxcuWhzhoGeP6aLdGaehWx+f6s4pI1xd3ylYXSKp0STknl+YLteb46YYHyVg31UxWFrjoqCXpiMd1q3bWyMgJpLm5RtZ6CAbn+Bp0OclGl4hJZodIyhR5SWb39srGdpl5RahZR7eGO7ZWSbjHJtk4xuYblrZpWGiVaVmmNnq3tqgYyaSZSeZHGTjG9wk5xHkJpvdnuhcmSYllWHkH10bq5wYpqPZ3qJj25osm1qkYl7aoyZZWuucW6CjYpYkJ1lcaF8dHGWkEyVmW5vjpJ0Y5uWTZGQfm18pG9gmpZahIqSaGyvbWeNlG51iJxnZ7JscX2Tg2GLnWxopXN7b5SPU5SUc2mVhHxmlphPmId/bIGYcmeVmVSRg41pcapqboyXZIGFlWdptWV2f5h6Z4uXbWOvbH10lo1SlZFzY56AfWmVm0qYhoFohpZ2aZGeTpKAjm1vqm5xh5hig3+TcGW1Z3p9k3lshpN0Y7BqgnaNjFeUjnZmoXp+bouaTJqHfm6KkHVrjJ5MlYOJcXGobmyIml2Gg5F0YrVpcoGTeW6Fl3Zer2x7eIuQWo6Vd2OeeX5uh6BQk458b4WNe2mJolGRh4R2bqB3aYqaYIeCjXdgrnFsiJJ4dIGVdV2ucXOBio5hhZtxY6B7e3OJnlOHmHRsi4t/ZoykUYaRfXVznYJfj51egIWNdmOogWCMlHJ2e5t0Xat+aoOLiGd4pXBioYB3dImXXHmmcGqThoRhjZ9Vfpt6cX+TiVWSn1l+jotxbZ+LUZGZZ3x9oW1iqIZbhZZ7cXKwbV2mgm10ko5lb7RuYJyCg1yUnFx1p3pijYeQTZWkVX2VjGZ3k5RJk6RcgX+hZWeijlGHpWl9brJnWqyBZ3WhfW9rt2tTrH5+X5+TYG+ud1Oifo9NnKdOfJqMVoqMlkmRsUuEhJ9bcqCRTYa0VINvsGVasYNjdLFpdWy2b0i8enhipYhgca97RrV5iFOdo0l+oIxHoIePTo64O4qNmVOAoYlQgcFBh32nY1+2fV11vlV6eK5yQ8h1bWeue2J8qYA6xXV4X56fRoSjjDyvg4Bai7s0j5eSS4yfe1l+zDWKjplhZLh0XnfHR3yLnnJCzXFlcLdtY4ycgDPQdGhwoZdEj5+HNbqFbW6LtzKSnYZFmp9raH/ML4mbh2BwuGpmfMk+eJ6Jc0zNb198uGFhm42AOtJ2WoGiiUSZmIM4wIlagYqqNJSje0eloVp3gMAwh6p3X3+2Xm2BwTt2sXdxXMhrW4ezWWKtfnxLzHdNj6F5S6SKiE+sh0uXiJBOmpl8UZ+mSIZ8o1CGo21wjaNUgIalSHm2ZXdrt2lfladbZ7Ruel2wfE6YoHRZrIR5WKKSR5SPhliZl3Jgm5xMkIGYWXyrY3COmWCEh5pcc7RacoabanOTl2drtWJwgJxzXZ+SbWOsfGx3noVMoo5uZ5aTZ2umk0eZkHtkfadoZ5uaVoiTg2dwsWVnkpdubJePZ3Cqb26GkX9bmJJjc6R7cICPjlKSk2N3lIhsfpSOVY6aZ3WGlmlynIdeh5p0b3+mZ2megmp0moNofKlxYp2CdWaVk156ooFhj4x+Yo2bXXyUimR+l4JhiaBhgYWRbWqhe2aHnWx8g5VzXqd4bIGVe3KFlHhepnpxeZGMX4mSfF+bhnN3ipxSiZN7Z4aXd26FqFWCj39zcKJ1boKmY3WPhH5dqXlvf5R7Z46Hg1ynfnCBhY9bi4qCYpuGcYZ4n1SLj3hvh5VtgXiiWIWVc390oWp4gJtieZx1h2OscGyHjHZpnHmJXqt9X5B/jVyShoRhmoteknWeWIuSeXCCmGCKcqRgf51uhm2eaX17mG1zo2qQYp92cIiIfmiia45im4JhmXiQYZl8f2uOjlmecZphipJvd4GZWZVznGd5pGOGcqBlg3+Tc2erX49onHxtkIaBYKRnjWaTjludeI9hlXqDcISbU6Fvk2mBk3F/eqBZl3KOc2+kY4p1nmiFgIZ8Ya5djnKUfmySfIVfqmeHdoiSVpx3iWSYfX18fp9NnnSJb4CXb4N3o1WSeod9Z6pmh3aYaoCEgodcsGWHd4mDao98jV2pboF+eZdYmHmKapWBd4RyoVOYfYF5fpRshHOiWI+Ge4ZopWaBeZZrfZB5jV2ranmBh4NplH2MXaN1dIZ5mVqVgoVoj4ZviHGkWY+Jenp3lW2EdKFihY91h2afbn18lHJ4lHKNX6N1comEhWuTeIhinH9qkXqUYo2EfWuPi2ePdpxfhZFzeX6XaoV7mmV5mm6EcJx1doSRcG+ZcodnnYFqjIaAaJN8g2mVi2ORfIxnh4x6cIuRZYx5kWl9mHJ6g5RrgX2QbXOgbYF8lHdxiIxyb59yf3eSg2KQiXdul4B7c5CLWpKGf26JknRzjJFdiYeEcXqgcHOIkGp8iYlzcKRxdIONeW+Ni3Zvn3dzgIiGY5CNdXSTgnF8iYxejo90d4ePbnaMjmCIk3d5eptscJGLa3yWfnVzn3Fpkod4cJWIcHKdemaOhoRlkZJrdZOGZoaJi2KJmWd5h5BrepCLZ4Gbanp9lXFul4dveZpzd3mYeWSdgXdxlIFtfJaAYJuDfGyNkWN+kodhkop/aYWeXH+MjWeAln5qfKVgfIiQcmygfWx3om50hZJ9XqN9cHSYgGmGkIZZn4FzdIqTYIaPiF2TinR2gKFahY+GZoGWc3Z6p11/kYNzbaJ0cnqkanOTg35eqHptfZp8ZZKGg1elhGmAjJJZjYqEW5aRZ4KBolaEkn9ngp1pf3umXHqZenZvpm54fKBpbJ13gmKoeW2Ek3pgnHuEXqSGY4uGi1mVhYBil5RcjX2YV4mVd22GoVyGfJxdeaNweXenZXiBmmloqXCBaqd1aIqQel2od4JmnohZkYWJWpuFfGqRlVKTf5JdjJdyc4OfVI1/kmZ5p2h8eaJgfYiPcWqvZ35znnNqk4l7Ya5venGWh1ebhYReoIFxdYyXTZqFiGOMlml4g6BSkIiIbHenYnx+oGB/kYV2Zq9ke32XdWyagXxhrG51f4+IW56BfmOff2yDh5ZSnYV7aoyUZIOEnFWRjHd0eaNgf4aaYICWd3prrWV4iJFzbJx5e2ercW+MiYVbnIB4ap6BaI2DkFWYiHNyjZNjiYKVWI2Sbnx9n2OAh49jfppvgHOlaXaOhnFwnnN9caRzapSAf2Scf3Z1moBilH6IX5WNbXqOjV+Lgoxhi5hpgYGWY3+LiGh+n2uBeZptcZOCc3Wecnx3mHZnmH99bpl/dHmRgGKYf4FtkY1pfouHY46Fg26Hl2SBhotogo6CcH+dZn6BjnNzl390eZpvd4GOe2ibgXd2kn1wgI2BZZmCeXWJjGiAjIZojoh6dYCVZoCMhXCCj3tzfJhqfIqFeXWUfHJ8lXF2ioZ/a5eAcH6NfnGIiYFpk4Rvf4eKbIOOgWuKiXJ9gJJsfpJ9cn+Pdnh/lHF3kn16dpB9coKRd3OPf31ukIVthIqBcYmDfm6MimqGhYhxgIx7cYaOboOCi3J5k3d2gJB1fIONdnGVdnt7jX9zh4p6bpN8e3eKimuIh35vioN7d4eRZ4iEgnJ+j3l4gpNsg4OCeXSWdnmBkHV5hYR9bZh4e4CJgW+IhH9slX55gIKNZ4iEf3GLh3eAf5Jkh4d+eH6Rdn59kmqBiXyAc5l1eoCNdXaMfoRsmnp2g4SCbY2Bg2yVgXGEfI9nioWBc4iKb4V5lGeEjXt8epJwgHqTbn2Rd4Rwl3N5gIx4c5R4iGqVfHGIgYVskn2DbI+Faot7kGmLhn1zhI5oi3iUaoKRdXx4lW2De5Jzd5dwhHCXdXiEjHxtmHOGbJOBboyBh2iTeoFvi41lkHuPaYeHeneClGSPe49sfJRyfnmZa4V+jHVwnG2EdJd3doiGfWedcYRzkIVpj3+EZpd7fnaJkl+RfIlqiIp3fICYX41+iHF5mXCAeplogoKGem2hbYJ5lHVyi4KBZaJzf3qKhmWRf4Rnmn54fIOSXJKAhW2KjXGAfZhcjYWBdnqcbH57l2aAi39/bqNre36RdHGSf4JnonN2gIeFZZWAgmmZf26DgJNdkoZ/cImNaoN9l16MjXp4eZppfn+WaH+Td39uoGx3ho51cZh6f2mfdnCKhoRnmH98a5iBaIyBj2CSiHZzio5miYGSYYiScHl+mGiAhZFpfJhvfnSdbnaNi3Nwm3V9cJx6bJKEfmmXfndyloRkkoKGZI+KcXeNjmONg4llhpdrfISVaICJiGt7nGt9f5dvc5GFcHOec3p6l3pnlYF4bpl+c3yTg1+Ug31sj41ufY6JYI6Hf2yHmWh9iY5mgY2BcH2fanuHjW9zlYFweJ9ydoOOeWiYgnN3mXxugo6AYZeHdXaPimmAjoNikI12dYiVZXuPhmiEknp0gJpnd4+GcHeZfnB9mm9xjYd4bpmDbX2VeGuKi31ol4lsfI2FaIWPf2iQj2t6h49nfJWAbYaTcXWEk2p2mH9wfZZ3b4OVcXCWgnd0lYBohJJ5a5KKd2+SiWWDjoBsiZB3bY2QZYCMiGx+mHdvhZNreouLcHadd2+Ak3Vwi451b5p8cnuRf2iMj3hslYRxeI2KY4qPe26KjW92i5BjhZF/cH6WcXOIkmp9kYFzdZxzcIeRdXGShXVvm3puh42AaZGJdG6VhGyDiopkjIx0couNaoCKjmSEknR2f5ZueouNa3uVdnh4m3JyjYt0cJV8eHOZe2yQhX1plIR0dJSGaIyEhWaMjXB5jI5mh4aIZoSWbnyBlWt9ioZteptvfX2XcnGQg3VymnZ8epR7aJR/fW2XgXV8joZikn+CbI2NboCIi2KLhYNug5hqgYGQaYCLgHR6nmmAf49ycpR+enOdcnp/i31omH19cpd+coCIh2GXfoB0i4tphIOMYpCGfXd/l2WDgoxrg457e3icZoCDinZ0l3l+cppveYaEgmmbenx0knxwiIKKZJh+eniGimmKgYxlkId1fXuWZYeDim+Cj3KAdZpogImFenSWdH9zmHF5jX6Eapl3e3iQfm+PfItllH91f4SKao5/iWiMiHCDe5VpiIOFcYCQbYZ2mGx/i318c5ZygneUdnaQeIRslnh7fI2BbJF3iWmQgnSEgotpjnyGbYiNboh7kWuFg4F2fpNsiXiSb3qNen50l3KEeY16cJN2hHGTe3p/h4Jqk3eHcIyGcYV/iWmPfYR0hJFriHqNboOGf3t8lGuHeox1eI97gHaUcYF8iH9vlXeEdY98doGDhmqUe4R2hodvhX+JbI6AgHh+kWqHfYpzgol8fXiUbIR/h3t3kXp+dpF0foGEg2+UeX53i351hYOIa5F9fXqCim+GgohuioN6fHyRbYSDh3V/iXl+eJRvgYaDfHSPenx4kXh6h4GDbpB9enyKgXSHgYVrjYJ4foKLcYWDhG+GiHaAfZFwf4eBdn2MeH98knN6iX98dY98e3yPenSKfoFwjoJ2gImBcIeCgm+KiXSBg4dwgYaBcoWNc4B/jHF6i393fpB3fX6MdnSPfnt6j392fot7cI9+fnWNhXGCh39vioV/dYaMb4GFhHKBjH12g49vfoSGdniSfXd/j3V4hYd6cpV9eHyLfnKEiH5vkoF4eoiGbIWJf3CMh3h4hI1rgoqBdIKOd3eCj258jIJ5eJR5c4KOdHeNg3xylHxygYp/b4uHfW+Rgm+ChoZsiYt8cYmKcH6EjWuDj3t0go5yeoWOb3yTenh3lHd1h4x2dZR9eXSTfm6JiX1vj4N5cY+FaomGhWuKi3Z0iY5phoaKbIGScniCkW5/iIpveJZ0eXyUdXWMiHVxmHd6eZN/a4+FfG2TgHZ5j4hmj4R/bIuLcnmKj2SKhYJugpVufYWSaYCLgXJ4nG98gZFzc4+CdnGddHmAkHxok4F6b5h+dICKhmGShHtwjoxtgIiLYYyIfHSDlmp+hY5mgo97d3qeanuFi3F0lXx5dZ5xdYaJfGmYf3l0mHxuh4WFYpeFdnaOiWeGhYpij4x1eoKUZYGIimiElHR7eptne4uHcniadXp2m25yj4N8bZt8dneVe2qQgoRnmINxeo2HZY2EiGaQjWx+g5Fkh4qFa4WVa359lml9kIJzeZlve3uXcHKWfntvmnd1fZJ8apd+gGuVgm2BjIVlk4KBao6NaISGjWaKin5uhJZmg4KRa32TenR7mmt+gpFycph4eXSZdXaEjntpmnl7cpSCbIiKg2WUgHpyjI5miYeHZouJeHSFlmSGhYlsfpN1eH6Zan+HiHNymnV4fJd0dYmHemubeXh6koBri4V/Z5eAdnuKjGSKhoFrjIt0fIWSY4WIgXCBlHN7gZRofouBd3aadHmAkXJ0joF7b5t6dYGMfWyOg31uloFygYaHZ4yHfXCNim6Ag41mhox7doORcHyDjmt/kHx5epZyd4SMc3aSfXx0lnhxh4h8cJGCe3ORgG2GhYNtjIh5dYuHbISEiGyGjnZ4g41uf4aJcH+Sdnp9kHR3iYh1eJJ5enmQenGLhntzkH93eY2CbYuFgHKJh3R6iYZth4aCcoONc3qFinGBiIN0fJFze4KLd3mMg3Z4kXh5gYp9c42DeHaNf3Z/ioNvi4R6doeGc3+Ihm+Gh3t4gY1zfYiGc3+JfXh9kHV7h4Z4eIt/eHqPeniHhH5yi4J3e4uBdYWFgXCIhXd8hod0gYaCcYSJd32BjHV9iIF2fYt5fX+NeHiJgHp5i356fox8dYl/f3WKg3eAh4Jyh4GBdIaJdYGDhnOBhYB2gox1gYCIdnuIf3p9jnh/f4h6dYx9fnqMfnmAhX9yjH5/eYiEdYGDg3GJgYB5g4txgoCFdIKGfnx+jnKAgIV6eot9fnuNdn2BhH90j3x/eYp9d4KBhHGOf357g4ZyhIGGcomDfHx9jHGDgYZ2goh6fnmPcYCDhH54jXp+eI14e4aBg3KOe3x6iIB2hoCHcIt/en6AiXGHgIdxhYV4gHuPcoODhHd+ineBeJF0f4aBfnWOeIB5jXt4iX2EcI59fHyIg3OJfIZvioJ4gIGLcId/hXGEiXWDe49xgYOBeHyOdYN6j3V6iH5+dZB5gHqMe3OLe4Rxj396foaEbot8hnGJh3WCgIluh4GEdIOOcoN7jXCAhoB6e5JygnqMdneNfX92kXh8fIl+cJB8gnOPf3Z/hYRsjn6DdIeIcIOAiW2IhYF3gI9tg36LcoCLfnp6km+Af4p4dpF7fXWRdnqBh4Bvk3x+dYyAc4SFhWyQgH12holuhYOIbYmGenh/kWyDhIhzf4x4enuTb3+Gh3l1kXl7eZJ3eYeFf26Te3l6jYFxiISDa4+Bd3yGim2HhYNtiIh2fIGRbIOHgnJ/j3V8f5NvfIqBeHaTeHl+kXd2i4B8cZR9doCNf2+Lgn9ukIRzgYeHa4eGfnCKi3GAg4xrgYp+dIKQc32Cjm57j314epR3eIKMdXSQfnt2k31yhIp7b46CfHSQhG+Eh4Jtioh7dIuLbIGFh26Cjnl3hY5vfYaJcHyTend/kXR3hol1dpR8eHuQe3CIiXpykYJ3eY2CbIeIfXGMiHZ3iohrhImCcYSPdHeGjG1/i4NzfZN2dYONdHeMhXV4lHl0gY16cY2HeHSRf3J/i4Fti4l4dIuHcXyLhmyGjHp0hYxyeoqIboGOfHR+knN2iYl0eY9/dHuSeHKJiXlzjoRzeZB+cYWJf2+LiHN4jIVvgouCboaNdHiGinF9jYNvgY92doOPdHeNhHN6kHp1gI95co6Ed3WOgnJ/jn9wi4Z5c4uIcH6Mg2+GiXxxho1wfYmHcX+NfHKBkXN6h4p1d49+dH6QeHaGi3hxkIB1eo+AcoWKfW6NhHZ5iohug4p/b4eJd3iGjW6AiYJxgY54d4ORcHuJg3Z4kXp3gY93doqEeXOSf3WAjX5xiIZ8cY+DdH+JhWyGiH1xiYp0foWKbYGKfXWCj3V7g4xvfIx+eHuSeHiCi3V2jn97dpJ9dIOIfXGMgnx1j4Jxg4WCbomHfHWJiW+Cg4duhIx6eYONcH6EiHJ9kHp7fY91eYWHd3iRe3x5j3tzh4V9c5B/e3iLgm+Ig4Jxi4V5eYaHboaDhXKFi3Z7goxvgoWFdn2QdX1+jHV7iIR6eJF3fHyLe3WKg350j3t6fIiCb4yCgHOKg3Z+hIhuiYOBdYOKdH6Ci3CEhYB4fI9zf4CLdn2Jfnt3kXZ8gIh9dYt+fXWOe3qBhYNwjH9+domDdoKCiG+Jgn15gopzgoKJcYOGfHx8jnSAgYd2fIl7fniQd32DhH11i3x/eI19eYWBg3GLf316iIR1hH+GcIiDfH2CiXODf4Zzgoh6gHyNdX+Cg3l8i3qBeo14e4SBf3aMfIB6in12hn6DdIqAfXyEg3SFfoV0hoV6f3+Hc4OAhXeBiXiBe4p2f4OCfHyLeYB6iXp5hoCAeIp8fnqGf3aIfoR2iIB7fYKFdId/hXeDhXiAf4d2g4KDeX6Jd4B8iHl+hIF9eop4gHyIfnmHf4B3iH19fYWDdoh/gXeFgnp+goZ1hn+CeICHeIGAiHeBgoB7e4p4gH+He3yEf315inp/f4Z/d4Z/f3iIf3yAg4N0hoB/eYSFeoCChXSDgoB7gIl5f4GGdn+Ef318jHp+gIV7eoZ/fnqLfXuBg392hoF+eoiBeYCCg3SGg357hIZ3f4GFdYKGfX1/iHh9goR4foh+fXyKenqDhHx6iIB9e4l9d4ODgHeHg3t7hoJ2goOCdoWGenuDhXaAhIR3gYh6fH+IeHyGg3l9int7foh7eIeDfHqJf3p9iH92h4N+d4eDeH2Gg3WFhIB3g4d2fYSFdoCHgHd/inh8god4fIiBeHyLenqBiH13ioF6eol/d4GHgHSIg3p5h4R2gIeDc4WFe3mDiXR/hoR1gIh8eYCMdnyGhHh7in14fox6eYaFe3aLf3l8i392hYV+c4qDd32HhXODhoBzhod3fIWJc4CHgXSBi3h8gYx0fImAeHuNe3mBjHl2iYF7d41/d4CKfXOIg310jIR1gIeDcYaFfnWHiXN/hIZxgYl9doKNdX2DiHN8jH15fo93eoKJd3aOfnt6jn11g4d8c4yCe3iMg3GDhoFxiYV7eIeIb4KFhHGDi3p5g4xxfoWFdX2Penl/jXV6hoZ4d5F8enyMe3SHhXxzkH94e4qCcIeGf3OLhXd7h4duhYaBc4WKdXuDi2+BiIF2fo91eoGMdHuKgnl4kXh4gIt6dYuDe3WQfXaAiIFwi4R8dIuDc4CGhm6Ih3x1hYlzfoWJb4OKfHd/jnN8hIpzfI18eXqRd3iFiHl2jX55eJB8dYWHf3GMgXl3jIJyhYWDb4mGeHiGiXGChYVwg4p4eoKNcn6GhnN9jnl7fY92eYiEeHePe3p8jnx0iYN8c42AeHyMgXGIg4BxioZ2fYeHb4SFgXKEi3V9hIpxf4eBdX+PdnyBi3V5ioF4epB5en+LenSLgXt3jn93gIh/cYqDfXaKhXR/hoNwh4V+doWKc3+EhnGBiX54gI50fYOHdnuMfnl9jnd6god6do2Ae3qMfXaDhn5yjIJ7eoiDc4KFgXKIhXt6hIhygIWDdIOJe3qAi3N+hoR4fYx7en6Ld3qGhHt5jH15fYp8d4aEfnWLgHh9h4J0hYWAdYiEd32EhnOCh4B2g4d4fIKIdX+IgHh+inl7gYl4e4mAe3qKfHmBiHx4iIJ8eImAd4GGgHaGg313h4R2gISEdYKGfHmDh3d/hIV3fod9eoCIenyDhnl7iH57fYl9eoOFfHiIf3x8h4F3g4R+d4WDe3uGhHeCg4F3goV7e4OGeICDgnl+iHt8gYd6fISCenyIfXyAhn16g4J8eod/fH+FgXeDgn56hYN7foODd4GDfnuBhnt9goR4f4R/fH+IfH2BhHt8hYB9fIh+e4GDfnqEgX18hoB6gIKAeIODfnyEg3p/gYJ4gYR9foCFen6Cg3p/hX5+foV8fIKCfH2FgH58hX56goF/eoWBfX2EgHmCgYJ6g4N8fYGDeoCBg3qBhXx+f4R7f4KCfX6GfH59hX18g4F+fIV+fn2DgHqDgYB6g4F8foKCeYOAgnuAg3t+gIV6gYKBfH2Ge39/hXx/g4F9e4Z8f36Ef3uEgH95hX9+foOCeYSAgHqDgX1+goR4g4CAe3+FfICAhnmAgoB9fId7f3+FfHyDgH96iH1/f4N/eYR/gHmGgH1/gYN3hICAe4OEe4CAhHeCgoB8f4d7f3+FeX+Df358iHt+f4R8e4V/gHqIfXyAgoB4hYCAeoaAe4CBg3eEgoB7g4R5gICFd4KEfn5+h3l/gIV6fod+fnyIen2BhH17h35/eYh9eoKCgXeHgH56hYJ4goGDd4WDfXuChXeBgYV3goV8fH6IeH+ChXp9iHt9fIl7fIOEfHqIfX17iH55hYKAd4eAfHuGgneEgoJ2hYR7fIOGdoKCgneBh3p9gYh4f4OCeX2Jen1+iXp7hYJ7eYp8fH6IfneGgX54iIB7foaCdYWCf3eGhHl+hIV0g4OAeIGIeH6ChnZ/hYB5fot5fYGHeXuHgHt7i3x7gIZ9doiBfHqKgHiAhYB0hoN9eYeEd3+Eg3SEhX56g4h2foOEdYCHfnt/ind8g4V4e4l/en2LenmDhXx4ioF7fIl+d4OEf3WJg3p7hoN1gYWBdYWGenuEhnV/hoJ2gol7eoGJdnyGg3l9inx5f4l6eYeEe3qKf3h+iX13hoR9d4mCd32HgXWEhn92hYZ3fYWEdYGIf3eCiHh7hId3fYmAeH6KenmCiHl6iYF5e4p+d4KIfXeIg3p6iIF2gYh/doWFe3iGhXV/h4J2goh7eIOId32HhHd+iX14gYl5e4aFeHqKf3h/iX14hYZ7eIiBeH2IgXaDh3x3hoV4fIeEdYGHfnaDh3l6hYd2foeAd3+Ke3mEh3l6h4F4fIp+eIOIfHeGg3l6iYF3gYd/dYWFenmHhXd/hoF1god7eYSHeHyGg3Z/iX15gYl6eoWEeHuJf3l/in13hYR6eYiCeX6JgHaDhX13hoV5fIeDdYGFf3eDiHl8hYV2foeAeICKe3uDhnh6h4F5fYp9eoGHe3eHgnt7iYB4gIZ/dYaEfHqHhHd/hYF1g4Z9eoSHd32Eg3aAiH56gIl4fISEeXyJf3p+iXp5g4V8eYmBe32IfniDhH93iIN7fIWCdoGEgXeFhXp8g4V2gIWCeIGHe3uAh3d+hYJ6fYl8e3+IenqGgn16iX56fod+eIaDfniIgXl+hYJ3hIN/eIWEeH6DhXaChX95gYd5foGGeH+Gf3t+iHp9gYd7e4d/fHuIfXuBhX55h4B9eoeAeYGEgXeFgX56hIR4gYKEd4KEfXuBh3iAgoR5foZ9fH6Ien6ChHt7h319fYh9fIKDfnmHfn58hoF5g4KAeIWBfX2EhHiCgYF4goR9fYGGeIGBgnp/hnx+f4d6foKBfHuIfX5+hn17g4F+eod+fn6EgHmDgIB5hYF9foKDd4OBgXqChHx/gIR4gYGBfH+GfH9/hXp+g4B+fId8fn6EfXuEgIB7hn59f4KAeoSAgHuEgHx/gIN5g4GAfIGDe39/hHqBg4B+foV7f3+EfH6Ef398hXx+f4N/fIR/gHuFf3yAgYJ6g4CAe4KBfICAg3qCgX98gIN7gH+Ee4CCfn59hXx/gIN9fYN+f3yEfn6Agn98g39/fISAfIGBgnqCgH98goN8gYCCe4CCfn2AhHyAgIN8foN+f36FfX6Agn18hH9/fYSAfIGBf3uDgH99g4F7gIGBe4GBf32Bg3uAgIJ7f4N+fn+EfH6Agn19hH9+foR+fYGCfnyEgH59g4B7gYGAe4OBfn2BgnqAgoF8gYN+fYCDe3+CgXx/hH59f4R8fYKCfn2Ffn1/g358goJ/fISAfH6DgHuCg397g4J8foKDeoGDgHyAhHx9gYR7f4SAfH6FfXyBhH19hIF9fIV+e4CEf3uEgX17hIF6gIOBeoKDfXuCg3p/g4J6gIR9e4CFe36Cg3t+hX58f4Z9fIODfHuFf3x9hX96g4N+eoSBfH2FgnmCg4B5g4N8fYOEeYCDgHmAhnx9goV6foSBen6HfXyAhnx7hIF8e4d+fICFf3mEgn16hoF7f4WBd4ODfnqEhHp+g4N3gYR/eoGHen6ChHh+hX97f4h7fIKEe3uGgHx9iH57gYR9eIaBfHyHgXmBg4B3hYN9fISEeH+DgneChX18goZ4foODeH+HfXx/iHl8hIN7fIh/fH6IfHmEg315iIF7fYZ/d4ODf3iGg3p9hIN2goSBeISGenyChXd/hYF5gIh6fICHeH2Ggnt9iXx7f4d7eYeCfXqIf3l/hn93hoN+eYaCeH+FgnaEhH54hIV3foOFd4GGfnmAiHh9goZ5fYd/en2JenuChnt6iH97e4h+eYKFfneHgXx6h4F3goWBdoWDfHqEhXeBhIJ2goZ8e4KId3+Eg3h+iHx7gIl6fISDenqJfnt+iH15hIN9eIiAe32HgXeEg353hoN6fYWEdoKEf3eDhnp9g4Z2f4WAeX+Ie3yBh3h8hYB7fIl9e4GHe3mGgXx6iX96gIV/d4WCfXmHgnl/hIJ2g4R+eoSGeX6DhHaAhn56gYh5fYKFeH2Hf3t+iXt7goR7eoeAfH2IfnmChH54hoJ8fIaBeIGDgHeFhHx8hIR3gIOCeIKGfHyBhnh9hIN5f4d9fH+HenuEg3t8iH57foZ9eYSDfXqHgXp+hYB4g4R/eYSDen2EgniChIB5goV6fYKEeX+FgHp/hnt8gYV7fYWBe32HfXuAhX16hYF8e4Z/eoCEgHmEg317hIJ6f4SBeYKEfXuChHp+g4N6gIV+e4CGe32Dg3t9hX97foZ9fIODfXuFgHt+hX97goN/eoSCfH2DgnqBg4B6goN8fYKEen+DgHqAhH19gYV7foOBfH2FfnyAhX18g4F9fIWAfICEf3uCgn57g4J8f4OBeoGCfnuCg3x/goJ7f4N/fICEfX6Bg3x9hH99f4V+fYGCfXyEgH1+hIB8gYJ/e4OBfn2DgnuAgoB7gYJ+fYKDe3+BgXx/g35+gIR8foGBfX2Ef35/g358gYF+fISAfn+Cf3uBgX98g4F9f4GBe4GBgHyBg31+gIJ7gIKAfX+EfX5/g3x+goB/foR+fn+Cfn2CgH99g399f4GAfIKBgH2CgX1/gIJ7gYGAfYCCfH9/g3yAgn9+foN9f3+Cfn6Df399g35+gIJ/fYN/f3yCf32AgYF8goB/fYGBfICAgnyBgX9+f4N9gICDfX+Cfn9+g31/gIJ+fYN+f32Df36AgYB8gn+AfYKBfYGAgXuBgH99gYN8gX+CfICBf39/hH2Af4J9foN+f36Efn+A"));
            snd.play()
        } catch (t) {
            alert(t)
        }
} 

//d = "2017-07-09 16:21:00"
function relaTime(d) {
  var d2 = new Date()
  var d3 = new Date(d)
  var d4 = d2.getTime() - d3.getTime()
  d4 = d4 / 1000

  if (d4 < 60) {
    return ("Ít giây trước")
  } else {
    var d5 = Math.floor(d4 / 60)
    if (d5 < 60) {
      return (d5 + " phút trước")
    } else {
      var d6 = Math.floor(d5 / 60)
      if (d6 < 24) {
        return (d6 + " giờ trước")
      } else {
        return (Math.floor(d6 / 24) + " ngày trước")
      }
    }
  }
}     
 
function strLen(note,length,defaul){
    if(note.length>0){
        return note.substr(0,length)
    }else{
        return defaul
    }
}
 
function xdate(x){
    return x.replace(/(\d+)\-(\d+)\-(\d+)\ (\d+)\:(\d+)\:\d+/,"$3/$2/$1 $4:$5")
}

function xdate2(x){
    return x.replace(/(\d+)\/(\d+)\/(\d+)\ (\d+)\:(\d+)/,"$3-$2-$1 $4:$5:00")
}

function ydate(x){
    return x.replace(/(\d+)\-(\d+)\-(\d+)/,"$3/$2/$1")
}

function ydate2(x){
    return x.replace(/(\d+)\/(\d+)\/(\d+)/,"$3-$2-$1")
}

function getDate(d){
    
    var xd , xf = function(e){return e<10?("0"+e):e}
    
    if(d!=undefined){ 
        if(d=="0000-00-00 00:00:00") return '';
        if(d.match(/\d+\/\d+\/\d+ \d+\:\d+/)) d = xdate2(d);
        xd = new Date(d); 
    } else    
        xd = new Date()
  
    return xf(xd.getDate())+"/"+xf(xd.getMonth()+1)+"/"+xd.getFullYear()+" "+xf(xd.getHours())+":"+xf(xd.getMinutes())
}

function getScrollY(y){
    var supportPageOffset = window.pageXOffset !== undefined;
    var isCSS1Compat = ((document.compatMode || "") === "CSS1Compat");
    
    if(y!=undefined)
    return x = supportPageOffset ? window.pageXOffset : isCSS1Compat ? document.documentElement.scrollLeft : document.body.scrollLeft;
    return y = supportPageOffset ? window.pageYOffset : isCSS1Compat ? document.documentElement.scrollTop : document.body.scrollTop;
}
var loaihds = {            //thu 1, chi 2 | 1 khach, 2 ncc
    2: ['Thu Tiền khách trả','customer',1,1],
    18: ['Thu Tiền khách trả','customer',1,1],
    4: ['Chi Tiền trả NCC','supplier',2,2],
    6: ['Thu Tiền NCC hoàn trả','supplier',1,2],
    12: ['Chi Tiền trả khách','customer',2,1],
     
    8: ['Thu Tiền','partner',1,0],
    9: ['Chi Tiền','partner',2,0]
}

var typehds = [
    'Khác','Khách hàng','Nhà cung cấp', 'Nhân viên' 
]

var typehds2 = [
    'partner','customer','supplier', 'user' 
]

//2019
function inhoadonOffline(ob){  
    var ts = {
        1:"printinvoice",         
        11:'printreturn'  ,
        17:'printorder'  //2019
    }
    var type = ts[ob.type]
    
    if(!type) return;
    
    var data = cache_prints.getItem(type)
    
    if(!data) return;
    
    $('#pprr').html(data)
    
    function mylove(data){    
        switch(type){
            default:
            case "printorder":
            case "printinvoice":
                if(type!='printinvoice'&&type!='printorder')
                type = "printinvoice"
                var cn = loadObject(cb,branches),tongtienhang=0
                
                $('#'+type).html($('#'+type).html().replace("{{ten_cua_hang}}",site_name))
                 
                $('#'+type).html($('#'+type).html().replace("{{ten_chi_nhanh}}",cn.name))
                 
                $('#'+type).html($('#'+type).html().replace("{{dien_thoai_chi_nhanh}}",cn.phone))
                 
                $('#'+type).html($('#'+type).html().replace(/{{ngay_ban}}/g,getDate(ob.date)))
                $('#'+type).html($('#'+type).html().replace(/{{gio_vao}}/g,getDate(ob.datestart)))
                $('#'+type).html($('#'+type).html().replace(/{{gio_ra}}/g,getDate(ob.date)))
                
                $('#'+type).html($('#'+type).html().replace("{{ma_hoa_don}}",ob.code))
                
                //customer
                var cc={}
                if(ob.customer>0){
                    cc = loadObject(ob.customer,customers)
                }else{
                    cc = {name:'Khách lẻ','address':'','phone':'','zone':'',debt:0}
                }
                 
                $('#'+type).html($('#'+type).html().replace("{{ten_khach_hang}}",cc.name))
                $('#'+type).html($('#'+type).html().replace("{{dia_chi_khach_hang}}",cc.address))
                $('#'+type).html($('#'+type).html().replace("{{khu_vuc_khach_hang}}",cc.zone))
                $('#'+type).html($('#'+type).html().replace("{{dien_thoai_khach_hang}}",cc.phone))
                //$('#'+type).html($('#'+type).html().replace("{{cong_no_khach_hang}}",formatCurrency(cc.debt)))
                 
                $('#'+type).html($('#'+type).html().replace("{{nguoi_ban}}",loadUser(ob.user).name))
                
                $('#'+type).html($('#'+type).html().replace("{{ghi_chu}}",ob.note))  
                
                var hx = $('#'+type+' table tbody').html( )+''
                 
                $('#'+type+' table tbody').html('')
                for(var i in ob.products){
                     
                    var qk = loadProduct(ob.products[i].product)
                    if(ob.products[i].name==undefined){
                        ob.products[i].name = qk.name
                    }
                    var hx2 = hx;
                    hx2=hx2.replace("{{stt}}",i-(-1))
                    hx2=hx2.replace("{{dvt}}",ob.products[i].uname==null?'':ob.products[i].uname)
                    hx2=hx2.replace("{{ma_hang_hoa}}",ob.products[i].code)
                    var nm = ob.products[i].name
                    //var adTop = 0;
                    if(ob.products[i].top != undefined && JSON.stringify(ob.products[i].top)!='{}'){
                        var nm2 = [];                        
                        for(var mm in ob.products[i].top){
                            var mm2 = loadProduct(mm)
                            var mm3 = ob.products[i].top[mm]                            
                            nm2.push('<p>+'+mm3[0]+' '+mm2.name+'</p>')
                            
                            //adTop += mm2.price*mm3;
                        }  
                        nm += nm2.join('')  //'<br />'+
                    }
                    hx2=hx2.replace("{{ten_hang_hoa}}",ob.products[i].name)
                    hx2=hx2.replace("{{hang_hoa}}",nm) 
                    
                    hx2=hx2.replace("{{ghi_chu_hang_hoa}}",ob.products[i].note)
                    /*
                    hx2=hx2.replace("{{gia_chiet_khau}}",formatCurrency(ob.products[i].price-ob.products[i].discount)+
                            (ob.products[i].discount>0?(" <span style='text-decoration: line-through;'>"+formatCurrency(ob.products[i].price)+"</span>"):""))
                    hx2=hx2.replace("{{gia_hang_hoa}}",formatCurrency(ob.products[i].price-ob.products[i].discount))  
                    */
                    hx2=hx2.replace("{{gia_chiet_khau}}",formatCurrency(priceafterdiscount.call(ob.products[i]))+
                            (ob.products[i].discount>0?(" <span style='text-decoration: line-through;'>"+formatCurrency(priceafter.call(ob.products[i]))+"</span>"):""))
                    hx2=hx2.replace("{{gia_hang_hoa}}",formatCurrency(priceafterdiscount.call(ob.products[i])))    
                    
                    hx2=hx2.replace("{{gia_goc_hang_hoa}}",formatCurrency(priceafterdiscount.call(ob.products[i])-(-ob.products[i].discount))) //ob.products[i].price      
                    hx2=hx2.replace("{{gia_chiet_khau_pt}}",(ob.products[i].discount>0 && ob.products[i].price>0?Math.round(100*ob.products[i].discount/ob.products[i].price):"0"))
                    hx2=hx2.replace("{{gia_chiet_khau_vnd}}",(ob.products[i].discount>0?formatCurrency(ob.products[i].discount):"0"))
                        
                    hx2=hx2.replace("{{so_luong}}",ob.products[i].quantity)
                    hx2=hx2.replace("{{tong_tien}}",formatCurrency(ob.products[i].quantity*priceafterdiscount.call(ob.products[i])))
                    
                    $('#'+type+' table tbody').append(                       
                        hx2
                    )
                    tongtienhang+=ob.products[i].quantity*priceafterdiscount.call(ob.products[i])//(ob.products[i].price-ob.products[i].discount)
                }
                 
                $('#'+type).html($('#'+type).html().replace("{{tong_tien_hang}}",formatCurrency(tongtienhang)))
                $('#'+type).html($('#'+type).html().replace("{{chiet_khau}}",formatCurrency(ob.discount)))
                
                var paid = 0;
                if(ob.invoices && ob.invoices.length) paid = ob.invoices.sum('price')
                $('#'+type).html($('#'+type).html().replace("{{tong_cong}}",formatCurrency(tongtienhang-ob.discount-paid)))
                $('#'+type).html($('#'+type).html().replace("{{khach_tra}}",formatCurrency(ob.paying)))
                
                $('#'+type).html($('#'+type).html().replace("{{bang_chu}}",DOCSO.doc(tongtienhang-ob.discount-paid)))
                
                if(ob.addtoaccount==1)//ob.status<=0 && 
                    $('#'+type).html($('#'+type).html().replace("{{cong_no_khach_hang}}",formatCurrency(cc.debt-ob.paying+tongtienhang-ob.discount)))
                else
                    $('#'+type).html($('#'+type).html().replace("{{cong_no_khach_hang}}",formatCurrency(cc.debt))) 
            break;
            
            case "printreturn":
                type = "printreturn"
                var cn = loadObject(cb,branches),tongtienhang=0
                
                $('#printreturn').html($('#printreturn').html().replace("{{ten_cua_hang}}",site_name))
                 
                $('#printreturn').html($('#printreturn').html().replace("{{ten_chi_nhanh}}",cn.name))
                 
                $('#printreturn').html($('#printreturn').html().replace("{{dien_thoai_chi_nhanh}}",cn.phone))
                 
                $('#printreturn').html($('#printreturn').html().replace("{{ngay_ban}}",getDate(ob.date)))
                
                $('#printreturn').html($('#printreturn').html().replace("{{ma_hoa_don}}",ob.code))
                
                //customer
                var cc={}
                if(ob.customer>0){
                    cc = loadObject(ob.customer,customers)
                }else{
                    cc = {name:'Khách lẻ','address':'','phone':'','zone':''}
                }
               
                $('#printreturn').html($('#printreturn').html().replace("{{ten_khach_hang}}",cc.name))
                $('#printreturn').html($('#printreturn').html().replace("{{dia_chi_khach_hang}}",cc.address))
                $('#printreturn').html($('#printreturn').html().replace("{{khu_vuc_khach_hang}}",cc.zone))
                $('#printreturn').html($('#printreturn').html().replace("{{dien_thoai_khach_hang}}",cc.phone))
                
                //nguoi ban: user
                 
                $('#printreturn').html($('#printreturn').html().replace("{{nguoi_ban}}",loadUser(ob.user).name))
                
                $('#printreturn').html($('#printreturn').html().replace("{{ghi_chu}}",ob.note))  
                
                var hx = $('#printreturn table tbody').html( )+''
                 
                $('#printreturn table tbody').html('')
                for(var i in ob.products){
                    if(ob.products[i].name==undefined){
                        ob.products[i].name = loadProduct(ob.products[i].product).name
                    }
                    
                    var hx2 = hx;
                    hx2=hx2.replace("{{ma_hang_hoa}}",ob.products[i].code)
                    hx2=hx2.replace("{{ten_hang_hoa}}",ob.products[i].name)
                    
                    /*hx2=hx2.replace("{{gia_chiet_khau}}",formatCurrency(ob.products[i].price-ob.products[i].discount)+
                            (ob.products[i].discount>0?(" <span style='text-decoration: line-through;'>"+formatCurrency(ob.products[i].price)+"</span>"):""))
                    hx2=hx2.replace("{{gia_hang_hoa}}",formatCurrency(ob.products[i].price-ob.products[i].discount))    */
                    hx2=hx2.replace("{{gia_chiet_khau}}",formatCurrency(priceafterdiscount.call(ob.products[i]))+
                            (ob.products[i].discount>0?(" <span style='text-decoration: line-through;'>"+formatCurrency(priceafter.call(ob.products[i]))+"</span>"):""))
                    hx2=hx2.replace("{{gia_hang_hoa}}",formatCurrency(priceafterdiscount.call(ob.products[i])))                             
                        
                    hx2=hx2.replace("{{so_luong}}",ob.products[i].quantity)
                    hx2=hx2.replace("{{tong_tien}}",formatCurrency(ob.products[i].quantity*priceafterdiscount.call(ob.products[i])))
                    
                    $('#printreturn table tbody').append(                         
                        hx2
                    )
                    tongtienhang+=ob.products[i].quantity*priceafterdiscount.call(ob.products[i])//(ob.products[i].price-ob.products[i].discount)
                }
                
                $('#printreturn').html($('#printreturn').html().replace("{{tong_tien_hang}}",formatCurrency(tongtienhang)))
                $('#printreturn').html($('#printreturn').html().replace("{{chiet_khau}}",formatCurrency(ob.discount)))
                $('#printreturn').html($('#printreturn').html().replace("{{phi}}",formatCurrency(ob.fee)))
                $('#printreturn').html($('#printreturn').html().replace("{{tong_cong}}",formatCurrency(tongtienhang-ob.discount+ob.fee)))
                $('#printreturn').html($('#printreturn').html().replace("{{tra_khach}}",formatCurrency(ob.paying)))
                 
            break;
                 
        }
         
        $('#'+type).printMe2( )
         
    };
    mylove(data)
}

        //var priceafterdiscount = function(){return this.price-this.discount}         
        //and after topping
        var priceafterdiscount = function(){
            if(this.top && JSON.stringify(this.top)!='{}'){
                var addTop = 0
                for(var i in this.top){
                    var i2 = this.top[i]
                    //var i3 = loadProduct(i)
                    //addTop += i3.price*i2
                    addTop += i2[0]*i2[1]
                }
                return this.price-this.discount+addTop
            }else
                return this.price-this.discount
        } 
        
        var priceafter = function(){
            if(this.top && JSON.stringify(this.top)!='{}'){
                var addTop = 0
                for(var i in this.top){
                    var i2 = this.top[i]
                    //var i3 = loadProduct(i)
                    //addTop += i3.price*i2
                    addTop += i2[0]*i2[1]
                }
                return this.price-(-addTop)
            }else
                return this.price
        }
        
function inhoadon(ob,type,edit){ console.log('inhoadon:',JSON.stringify(ob),JSON.stringify(type),JSON.stringify(edit))
    edit = typeof edit==undefined?false:edit;
    
    var ts = {
        1:"printinvoice",
        3:"printpurchase",
        5:"printpurchasereturn",
        
        2:"printpay",
        6:"printpay",
        8:"printpay",
        
        12:"printpay", //2019
        
        4:"printpaying",
        9:"printpaying",
        
        7:'printstock',
        
        10:'printdamage',
        
        11:'printreturn',  //2019
        
        17:'printorder'  //2019
    }
    if(type==undefined)
        type = ts[ob.type];
    else{
        if(ts[type]!=undefined)
            type = ts[type];        
    }
    
    console.log('inhoadon:', JSON.stringify(type))
     
    function mylove(data){
        /*if(type=='printinvoice'||type=='printreturn'){
            localStorage[type] = data
        }*/
        saveCachePrints(type,data);
        switch(type){
            default:
            case "printorder":
            case "printinvoice":
                if(type!='printinvoice' && type!='printorder')
                type = "printinvoice"
                var cn = loadObject(cb,branches),tongtienhang=0;
                
                //phòng bàn:
                if(cfs && cfs.type==1 && ob.table!='0' && ob.table!=''){
                    var tb = _getTableName(ob.table)
                    $('#'+type).html($('#'+type).html().replace("{{phong_ban}}",tb))        
                }
                
                $('#'+type).html($('#'+type).html().replace("{{ten_cua_hang}}",site_name))
                
                //$('#printinvoice .he div:nth(1) span').html(cn.name)
                $('#'+type).html($('#'+type).html().replace("{{ten_chi_nhanh}}",cn.name))
                
                //$('#printinvoice .he div:nth(2) span').html(cn.phone)
                $('#'+type).html($('#'+type).html().replace("{{dien_thoai_chi_nhanh}}",cn.phone))
                
                
                //$('#printinvoice .hd span').html(getDate(ob.date))
                $('#'+type).html($('#'+type).html().replace(/{{ngay_ban}}/g,getDate(ob.date)))
                $('#'+type).html($('#'+type).html().replace(/{{gio_vao}}/g,getDate(ob.datestart)))
                $('#'+type).html($('#'+type).html().replace(/{{gio_ra}}/g,getDate(ob.date)))
                
                
                $('#'+type).html($('#'+type).html().replace("{{ma_hoa_don}}",ob.code))
                
                //customer
                var cc={}
                if(ob.customer>0){
                    cc = loadObject(ob.customer,customers)
                }else{
                    cc = {name:'Khách lẻ','address':'','phone':'','zone':'',debt:0}
                }
                /*$('#printinvoice .he3:nth(0) div:nth(0) span').html(cc.name)
                $('#printinvoice .he3:nth(0) div:nth(1) span').html(cc.address)
                $('#printinvoice .he3:nth(0) div:nth(2) span').html(cc.zone)
                $('#printinvoice .he3:nth(0) div:nth(3) span').html(cc.phone)*/
                $('#'+type).html($('#'+type).html().replace("{{ten_khach_hang}}",cc.name))
                $('#'+type).html($('#'+type).html().replace("{{dia_chi_khach_hang}}",cc.address))
                $('#'+type).html($('#'+type).html().replace("{{khu_vuc_khach_hang}}",cc.zone))
                $('#'+type).html($('#'+type).html().replace("{{dien_thoai_khach_hang}}",cc.phone))
                //$('#'+type).html($('#'+type).html().replace("{{cong_no_khach_hang}}",formatCurrency(cc.debt)))
                
                //nguoi ban: user
                //$('#printinvoice .he3:nth(1) span').html(loadC)
                 
                $('#'+type).html($('#'+type).html().replace("{{nguoi_ban}}",loadUser(ob.user).name))
                
                $('#'+type).html($('#'+type).html().replace("{{ghi_chu}}",ob.note))  
                
                var hx = $('#'+type+' table tbody').html( )+''
                 
                $('#'+type+' table tbody').html('')
                for(var i in ob.products){
                    if(ob.products[i].name==undefined){
                        ob.products[i].name = loadProduct(ob.products[i].product).name
                    }
                    
                    var hx2 = hx;
                    hx2=hx2.replace("{{stt}}",i-(-1))
                    hx2=hx2.replace("{{dvt}}",ob.products[i].uname==null?'':ob.products[i].uname)
                    hx2=hx2.replace("{{ma_hang_hoa}}",ob.products[i].code)
                    var nm = ob.products[i].name
                    //var adTop = 0;
                    if(ob.products[i].top != undefined && JSON.stringify(ob.products[i].top)!='{}'){
                        var nm2 = [];                        
                        for(var mm in ob.products[i].top){
                            var mm2 = loadProduct(mm)
                            var mm3 = ob.products[i].top[mm]                            
                            nm2.push('<p>+'+mm3[0]+' '+mm2.name+'</p>')
                            
                            //adTop += mm2.price*mm3;
                        }  
                        nm += nm2.join('')  //'<br />'+
                    }
                    hx2=hx2.replace("{{ten_hang_hoa}}",ob.products[i].name)
                    hx2=hx2.replace("{{hang_hoa}}",nm) 
                    
                    hx2=hx2.replace("{{ghi_chu_hang_hoa}}",ob.products[i].note)
                    
                    /*
                    hx2=hx2.replace("{{gia_chiet_khau}}",formatCurrency(ob.products[i].price-ob.products[i].discount)+
                            (ob.products[i].discount>0?(" <span style='text-decoration: line-through;'>"+formatCurrency(ob.products[i].price)+"</span>"):""))
                    hx2=hx2.replace("{{gia_hang_hoa}}",formatCurrency(ob.products[i].price-ob.products[i].discount))  
                    */
                    hx2=hx2.replace("{{gia_chiet_khau}}",formatCurrency(priceafterdiscount.call(ob.products[i]))+
                            (ob.products[i].discount>0?(" <span style='text-decoration: line-through;'>"+formatCurrency(priceafter.call(ob.products[i]))+"</span>"):""))
                    hx2=hx2.replace("{{gia_hang_hoa}}",formatCurrency(priceafterdiscount.call(ob.products[i])))   
                    
                    hx2=hx2.replace("{{gia_goc_hang_hoa}}",formatCurrency(priceafterdiscount.call(ob.products[i])-(-ob.products[i].discount))) //ob.products[i].price       
                    hx2=hx2.replace("{{gia_chiet_khau_pt}}",(ob.products[i].discount>0 && ob.products[i].price>0?Math.round(100*ob.products[i].discount/ob.products[i].price):"0"))
                    hx2=hx2.replace("{{gia_chiet_khau_vnd}}",(ob.products[i].discount>0?formatCurrency(ob.products[i].discount):"0"))
                           
                    hx2=hx2.replace("{{so_luong}}",ob.products[i].quantity)
                    hx2=hx2.replace("{{tong_tien}}",formatCurrency(ob.products[i].quantity*priceafterdiscount.call(ob.products[i])))
                    
                    $('#'+type+' table tbody').append(
                         
                        hx2
                    )
                    tongtienhang+=ob.products[i].quantity*priceafterdiscount.call(ob.products[i]);
                    console.log('tongtienhang:',tongtienhang)
                }
                
                $('#'+type).html($('#'+type).html().replace("{{tong_tien_hang}}",formatCurrency(tongtienhang)))
                $('#'+type).html($('#'+type).html().replace("{{chiet_khau}}",formatCurrency(ob.discount)))
                
                var paid = 0;
                if(ob.invoices && ob.invoices.length) paid = ob.invoices.sum('price')
                $('#'+type).html($('#'+type).html().replace("{{tong_cong}}",formatCurrency(tongtienhang-ob.discount-paid)))
                $('#'+type).html($('#'+type).html().replace("{{khach_tra}}",formatCurrency(ob.paying)))
                
                $('#'+type).html($('#'+type).html().replace("{{bang_chu}}",DOCSO.doc(tongtienhang-ob.discount-paid)))
                
                //$('#'+type).printMe2( ) //
                if($('body').attr('ng-app')=='kv.sale' && ob.addtoaccount==1)//ob.status<=0
                    $('#'+type).html($('#'+type).html().replace("{{cong_no_khach_hang}}",formatCurrency(cc.debt-ob.paying+tongtienhang-ob.discount)))
                else
                    $('#'+type).html($('#'+type).html().replace("{{cong_no_khach_hang}}",formatCurrency(cc.debt)))
            break;
            
            case "printreturn":
                type = "printreturn"
                var cn = loadObject(cb,branches),tongtienhang=0
                
                $('#printreturn').html($('#printreturn').html().replace("{{ten_cua_hang}}",site_name))
                 
                $('#printreturn').html($('#printreturn').html().replace("{{ten_chi_nhanh}}",cn.name))
                 
                $('#printreturn').html($('#printreturn').html().replace("{{dien_thoai_chi_nhanh}}",cn.phone))
                 
                $('#printreturn').html($('#printreturn').html().replace("{{ngay_ban}}",getDate(ob.date)))
                
                $('#printreturn').html($('#printreturn').html().replace("{{ma_hoa_don}}",ob.code))
                
                //customer
                var cc={}
                if(ob.customer>0){
                    cc = loadObject(ob.customer,customers)
                }else{
                    cc = {name:'Khách lẻ','address':'','phone':'','zone':''}
                }
               
                $('#printreturn').html($('#printreturn').html().replace("{{ten_khach_hang}}",cc.name))
                $('#printreturn').html($('#printreturn').html().replace("{{dia_chi_khach_hang}}",cc.address))
                $('#printreturn').html($('#printreturn').html().replace("{{khu_vuc_khach_hang}}",cc.zone))
                $('#printreturn').html($('#printreturn').html().replace("{{dien_thoai_khach_hang}}",cc.phone))
                
                //nguoi ban: user
                 
                $('#printreturn').html($('#printreturn').html().replace("{{nguoi_ban}}",loadUser(ob.user).name))
                
                $('#printreturn').html($('#printreturn').html().replace("{{ghi_chu}}",ob.note))  
                
                var hx = $('#printreturn table tbody').html( )+''
                 
                $('#printreturn table tbody').html('')
                for(var i in ob.products){
                    if(ob.products[i].name==undefined){
                        ob.products[i].name = loadProduct(ob.products[i].product).name
                    }
                    
                    var hx2 = hx;
                    hx2=hx2.replace("{{ma_hang_hoa}}",ob.products[i].code)
                    hx2=hx2.replace("{{ten_hang_hoa}}",ob.products[i].name)
                    /*
                    hx2=hx2.replace("{{gia_chiet_khau}}",formatCurrency(ob.products[i].price-ob.products[i].discount)+
                            (ob.products[i].discount>0?(" <span style='text-decoration: line-through;'>"+formatCurrency(ob.products[i].price)+"</span>"):""))
                    hx2=hx2.replace("{{gia_hang_hoa}}",formatCurrency(ob.products[i].price-ob.products[i].discount)) 
                    */
                    hx2=hx2.replace("{{gia_chiet_khau}}",formatCurrency(priceafterdiscount.call(ob.products[i]))+
                            (ob.products[i].discount>0?(" <span style='text-decoration: line-through;'>"+formatCurrency(priceafter.call(ob.products[i]))+"</span>"):""))
                    hx2=hx2.replace("{{gia_hang_hoa}}",formatCurrency(priceafterdiscount.call(ob.products[i])))                             
                           
                    hx2=hx2.replace("{{so_luong}}",ob.products[i].quantity)
                    hx2=hx2.replace("{{tong_tien}}",formatCurrency(ob.products[i].quantity*priceafterdiscount.call(ob.products[i])))
                    
                    $('#printreturn table tbody').append(                         
                        hx2
                    )
                    tongtienhang+=ob.products[i].quantity*priceafterdiscount.call(ob.products[i])//(ob.products[i].price-ob.products[i].discount)
                }
                
                $('#printreturn').html($('#printreturn').html().replace("{{tong_tien_hang}}",formatCurrency(tongtienhang)))
                $('#printreturn').html($('#printreturn').html().replace("{{chiet_khau}}",formatCurrency(ob.discount)))
                $('#printreturn').html($('#printreturn').html().replace("{{phi}}",formatCurrency(ob.fee)))
                $('#printreturn').html($('#printreturn').html().replace("{{tong_cong}}",formatCurrency(tongtienhang-ob.discount+ob.fee)))
                $('#printreturn').html($('#printreturn').html().replace("{{tra_khach}}",formatCurrency(ob.paying)))
                
                //$('#'+type).printMe2( ) //
            break;
            
            case "printpurchase":
                 
                var cn = loadObject(cb,branches),tongtienhang=0
                
                $('#printpurchase').html($('#printpurchase').html().replace("{{ten_cua_hang}}",site_name))                                 
                $('#printpurchase').html($('#printpurchase').html().replace("{{ten_chi_nhanh}}",cn.name))                                 
                $('#printpurchase').html($('#printpurchase').html().replace("{{dien_thoai_chi_nhanh}}",cn.phone))
                                                 
                $('#printpurchase').html($('#printpurchase').html().replace("{{ngay_ban}}",getDate(ob.date)))                
                $('#printpurchase').html($('#printpurchase').html().replace("{{ma_hoa_don}}",ob.code))
                
                //supplier
                var cc={}
              
                cc = loadObject(ob.supplier,suppliers)
                 
                 
                $('#printpurchase').html($('#printpurchase').html().replace("{{ten_ncc}}",cc.name))
                $('#printpurchase').html($('#printpurchase').html().replace("{{dia_chi_ncc}}",cc.address))
                //$('#printpurchase').html($('#printpurchase').html().replace("{{khu_vuc_khach_hang}}",cc.zone))
                $('#printpurchase').html($('#printpurchase').html().replace("{{dien_thoai_ncc}}",cc.phone))
                
                //nguoi ban: user
                 
                $('#printpurchase').html($('#printpurchase').html().replace("{{nguoi_ban}}",loadUser(ob.user).name))
                
                $('#printpurchase').html($('#printpurchase').html().replace("{{ghi_chu}}",ob.note))  
                
                var hx = $('#printpurchase table tbody').html( )+''
                
                $('#printpurchase table tbody').html('')
                for(var i in ob.products){
                    if(ob.products[i].name==undefined){
                        ob.products[i] = $.extend(loadProduct(ob.products[i].product),ob.products[i])
                    }
                    
                    var hx2 = hx;
                    hx2=hx2.replace("{{ma_hang_hoa}}",ob.products[i].code)
                    hx2=hx2.replace("{{ten_hang_hoa}}",ob.products[i].name)
                    /*
                    hx2=hx2.replace("{{gia_chiet_khau}}",formatCurrency(ob.products[i].price-ob.products[i].discount)+
                            (ob.products[i].discount>0?(" <span style='text-decoration: line-through;'>"+formatCurrency(ob.products[i].price)+"</span>"):""))
                    hx2=hx2.replace("{{chiet_khau_hang_hoa}}",formatCurrency(ob.products[i].discount))  
                    */
                    hx2=hx2.replace("{{gia_chiet_khau}}",formatCurrency(priceafterdiscount.call(ob.products[i]))+
                            (ob.products[i].discount>0?(" <span style='text-decoration: line-through;'>"+formatCurrency(priceafter.call(ob.products[i]))+"</span>"):""))
                    hx2=hx2.replace("{{gia_hang_hoa}}",formatCurrency(priceafterdiscount.call(ob.products[i])))        
                                         
                    hx2=hx2.replace("{{gia_hang_hoa}}",formatCurrency(ob.products[i].price))         
                    hx2=hx2.replace("{{so_luong}}",ob.products[i].quantity)
                    hx2=hx2.replace("{{tong_tien}}",formatCurrency(ob.products[i].quantity*(ob.products[i].price-(ob.products[i].discount>0?ob.products[i].discount:0))))
                     
                    $('#printpurchase table tbody').append(
                        /*$('<tr><td>'+ob.products[i].code+'</td><td>'+ob.products[i].name+'</td><td>'+
                            formatCurrency(ob.products[i].price)+'</td><td>'+
                            ((ob.products[i].discount>0)?formatCurrency(ob.products[i].discount):'0')+                              
                            '</td><td>'+
                            ob.products[i].quantity+'</td><td>'+
                            formatCurrency(ob.products[i].quantity*(ob.products[i].price-(ob.products[i].discount>0?ob.products[i].discount:0)))+'</td></tr>')*/
                        hx2    
                    )
                    tongtienhang+=ob.products[i].quantity*priceafterdiscount.call(ob.products[i])//(ob.products[i].price-ob.products[i].discount)
                }
                
                /*$('#printpurchase table tfoot tr:nth(0) td:last-child').html(formatCurrency(tongtienhang))
                $('#printpurchase table tfoot tr:nth(1) td:last-child').html(formatCurrency(ob.discount))
                $('#printpurchase table tfoot tr:nth(2) td:last-child').html(formatCurrency(tongtienhang-ob.discount))*/
                $('#printpurchase').html($('#printpurchase').html().replace("{{tong_tien_hang}}",formatCurrency(tongtienhang)))
                $('#printpurchase').html($('#printpurchase').html().replace("{{chiet_khau}}",formatCurrency(ob.discount)))
                $('#printpurchase').html($('#printpurchase').html().replace("{{tong_cong}}",formatCurrency(tongtienhang-ob.discount)))
 
                //$('#printpurchase').printMe2( ) //
            break;
            
            case "printpurchasereturn":
                 
                var cn = loadObject(cb,branches),tongtienhang=0
                
                $('#printpurchasereturn').html($('#printpurchasereturn').html().replace("{{ten_cua_hang}}",site_name))                                 
                $('#printpurchasereturn').html($('#printpurchasereturn').html().replace("{{ten_chi_nhanh}}",cn.name))                                 
                $('#printpurchasereturn').html($('#printpurchasereturn').html().replace("{{dien_thoai_chi_nhanh}}",cn.phone))
                                                 
                $('#printpurchasereturn').html($('#printpurchasereturn').html().replace("{{ngay_ban}}",getDate(ob.date)))                
                $('#printpurchasereturn').html($('#printpurchasereturn').html().replace("{{ma_hoa_don}}",ob.code))
                
                //supplier
                var cc={}
              
                cc = loadObject(ob.supplier,suppliers)
                                  
                $('#printpurchasereturn').html($('#printpurchasereturn').html().replace("{{ten_ncc}}",cc.name))
                $('#printpurchasereturn').html($('#printpurchasereturn').html().replace("{{dia_chi_ncc}}",cc.address))
                //$('#printpurchasereturn').html($('#printpurchasereturn').html().replace("{{khu_vuc_khach_hang}}",cc.zone))
                $('#printpurchasereturn').html($('#printpurchasereturn').html().replace("{{dien_thoai_ncc}}",cc.phone))
                
                //nguoi ban: user
                 
                $('#printpurchasereturn').html($('#printpurchasereturn').html().replace("{{nguoi_ban}}",loadUser(ob.user).name))
                
                $('#printpurchasereturn').html($('#printpurchasereturn').html().replace("{{ghi_chu}}",ob.note))  
                
                var hx = $('#printpurchasereturn table tbody').html( )+''
                
                $('#printpurchasereturn table tbody').html('')
                for(var i in ob.products){
                    if(ob.products[i].name==undefined){
                        ob.products[i] = $.extend(loadProduct(ob.products[i].product),ob.products[i])
                    }
                    
                    var hx2 = hx;
                    hx2=hx2.replace("{{ma_hang_hoa}}",ob.products[i].code)
                    hx2=hx2.replace("{{ten_hang_hoa}}",ob.products[i].name)
                     
                    hx2=hx2.replace("{{gia_hang_hoa}}",formatCurrency(ob.products[i].price))  
                    hx2=hx2.replace("{{gia_tra_lai}}",formatCurrency(ob.products[i].price-ob.products[i].discount))         
                    hx2=hx2.replace("{{so_luong}}",ob.products[i].quantity)
                    hx2=hx2.replace("{{tong_tien}}",formatCurrency(ob.products[i].quantity*(ob.products[i].price-ob.products[i].discount)))
                     
                    $('#printpurchasereturn table tbody').append(
                        /*$('<tr><td>'+ob.products[i].code+'</td><td>'+ob.products[i].name+'</td><td>'+
                            formatCurrency(ob.products[i].price)+'</td><td>'+
                            formatCurrency(ob.products[i].discount)+                              
                            '</td><td>'+
                            ob.products[i].quantity+'</td><td>'+
                            formatCurrency(ob.products[i].quantity*(ob.products[i].price-ob.products[i].discount))+'</td></tr>')*/
                        hx2    
                    )
                    tongtienhang+=ob.products[i].quantity*(ob.products[i].price-ob.products[i].discount)
                }
                
                /*$('#printpurchasereturn table tfoot tr:nth(0) td:last-child').html(formatCurrency(tongtienhang))                 
                $('#printpurchasereturn table tfoot tr:nth(1) td:last-child').html(formatCurrency(ob.paying))*/
                $('#printpurchasereturn').html($('#printpurchasereturn').html().replace("{{tong_tien_hang}}",formatCurrency(tongtienhang)))                 
                $('#printpurchasereturn').html($('#printpurchasereturn').html().replace("{{tien_ncc_tra}}",formatCurrency(ob.paying)))
                 
                //$('#printpurchasereturn').printMe2( ) //
            break;
            
            case "printdamage":
                var cn = loadObject(cb,branches) 
                
                $('#printdamage').html($('#printdamage').html().replace("{{ten_cua_hang}}",site_name))                                 
                $('#printdamage').html($('#printdamage').html().replace("{{ten_chi_nhanh}}",cn.name))                                 
                $('#printdamage').html($('#printdamage').html().replace("{{dien_thoai_chi_nhanh}}",cn.phone))
                
                                 
                $('#printdamage').html($('#printdamage').html().replace("{{ngay_ban}}",getDate(ob.date)))                
                $('#printdamage').html($('#printdamage').html().replace("{{ma_hoa_don}}",ob.code))
                 
                //nguoi huy: user
                 
                $('#printdamage').html($('#printdamage').html().replace("{{nguoi_ban}}",loadUser(ob.user).name))
                
                $('#printdamage').html($('#printdamage').html().replace("{{ghi_chu}}",ob.note))  
                
                var hx = $('#printdamage table tbody').html( )+''
                
                $('#printdamage table tbody').html('')
                for(var i in ob.products){
                    if(ob.products[i].name==undefined){
                        ob.products[i] = $.extend(loadProduct(ob.products[i].product),ob.products[i])
                    }
                    
                    var hx2 = hx;
                    hx2=hx2.replace("{{ma_hang_hoa}}",ob.products[i].code)
                    hx2=hx2.replace("{{ten_hang_hoa}}",ob.products[i].name)
                     
                    hx2=hx2.replace("{{gia_hang_hoa}}",formatCurrency(ob.products[i].price))  
                    hx2=hx2.replace("{{ghi_chu_hang_hoa}}", ob.products[i].note )         
                    hx2=hx2.replace("{{so_luong}}",ob.products[i].quantity)
                    hx2=hx2.replace("{{tong_tien}}",formatCurrency(ob.products[i].quantity*(ob.products[i].price)))
                     
                    
                    $('#printdamage table tbody').append(
                        $('<tr><td>'+(1-(-i))+'</td><td>'+ob.products[i].code+'</td><td>'+ob.products[i].name+'</td><td>'+
                            formatCurrency(ob.products[i].price)+'</td>'+                         
                            '<td>'+
                            ob.products[i].quantity+'</td><td>'+
                            formatCurrency(ob.products[i].quantity*(ob.products[i].price))+'</td><td>'+
                            ob.products[i].note+'</td></tr>')
                    )
                     
                }
                 
                //$('#printdamage').printMe2( ) //
            break;
            
            case "printpay":
                 
                var cn = loadObject(cb,branches),tongtienhang=0
                
                $('#printpay').html($('#printpay').html().replace("{{ten_cua_hang}}",site_name))
                
                 
                $('#printpay').html($('#printpay').html().replace("{{ten_chi_nhanh}}",cn.name))
                
                 
                $('#printpay').html($('#printpay').html().replace("{{dien_thoai_chi_nhanh}}",cn.phone))
                
                
                 
                $('#printpay').html($('#printpay').html().replace("{{ngay_ban}}",getDate(ob.date)))
                
                $('#printpay').html($('#printpay').html().replace("{{ma_hoa_don}}",ob.code))
                
                //partner
                var zz = loaihds[ob.type][1]
                var cc={}
              
                //cc = loadPartner(ob.partner,ob.partnertype)
                cc = eval("load"+zz.capitalizeOne()+"(ob."+zz+",ob.partnertype)") 
                
                if(!cc){
                    cc = {
                        name:'Khách lẻ',
                        address:'',
                        phone:''
                    }
                }
                 
                $('#printpay').html($('#printpay').html().replace("{{ten_khach_hang}}",cc.name))
                $('#printpay').html($('#printpay').html().replace("{{dia_chi_khach_hang}}",cc.address))
                //$('#printpay').html($('#printpay').html().replace("{{khu_vuc_khach_hang}}",cc.zone))
                $('#printpay').html($('#printpay').html().replace("{{dien_thoai_khach_hang}}",cc.phone))
                 
                $('#printpay').html($('#printpay').html().replace("{{ghi_chu}}",ob.note))  
                
                $('#printpay').html($('#printpay').html().replace("{{so_tien}}",formatCurrency(ob.price)))  
                $('#printpay').html($('#printpay').html().replace("{{bang_chu}}",DOCSO.doc(ob.price)))
                
                //$('#printpay').printMe2( ) //
            break;
            
            case "printpaying":
                 
                var cn = loadObject(cb,branches),tongtienhang=0
                
                $('#printpaying').html($('#printpaying').html().replace("{{ten_cua_hang}}",site_name))
                
                 
                $('#printpaying').html($('#printpaying').html().replace("{{ten_chi_nhanh}}",cn.name))
                
                 
                $('#printpaying').html($('#printpaying').html().replace("{{dien_thoai_chi_nhanh}}",cn.phone))
                
                
                 
                $('#printpaying').html($('#printpaying').html().replace("{{ngay_ban}}",getDate(ob.date)))
                
                $('#printpaying').html($('#printpaying').html().replace("{{ma_hoa_don}}",ob.code))
                
                //partner
                var zz = loaihds[ob.type][1]
                var cc={}
              
                //cc = loadPartner(ob.partner,ob.partnertype)
                cc = eval("load"+zz.capitalizeOne()+"(ob."+zz+",ob.partnertype)") 
                if(!cc){
                    cc = {
                        name:'Khách lẻ',
                        address:'',
                        phone:''
                    }
                }
                 
                 
                $('#printpaying').html($('#printpaying').html().replace("{{ten_khach_hang}}",cc.name))
                $('#printpaying').html($('#printpaying').html().replace("{{dia_chi_khach_hang}}",cc.address))
                //$('#printpay').html($('#printpaying').html().replace("{{khu_vuc_khach_hang}}",cc.zone))
                $('#printpaying').html($('#printpaying').html().replace("{{dien_thoai_khach_hang}}",cc.phone))
                 
                $('#printpaying').html($('#printpaying').html().replace("{{ghi_chu}}",ob.note))  
                
                $('#printpaying').html($('#printpaying').html().replace("{{so_tien}}",formatCurrency(ob.price)))  
                $('#printpaying').html($('#printpaying').html().replace("{{bang_chu}}",DOCSO.doc(ob.price)))
                
                //$('#printpaying').printMe2( ) //
            break;
            
            case "printstock":
                 
                var cn = loadObject(cb,branches),tongthucte=0, tongthucte2=0,
                    tonglechtang=0,tonglechtang2=0,tonglechgiam=0,tonglechgiam2=0;
                
                $('#printstock').html($('#printstock').html().replace("{{ten_cua_hang}}",site_name))                                 
                $('#printstock').html($('#printstock').html().replace("{{ten_chi_nhanh}}",cn.name))                                 
                $('#printstock').html($('#printstock').html().replace("{{dien_thoai_chi_nhanh}}",cn.phone))
                                                 
                $('#printstock').html($('#printstock').html().replace("{{ngay_ban}}",getDate(ob.date)))                
                $('#printstock').html($('#printstock').html().replace("{{ma_hoa_don}}",ob.code))
                 
                //nguoi ban: user
                 
                $('#printstock').html($('#printstock').html().replace("{{nguoi_ban}}",loadUser(ob.user).name))
                
                $('#printstock').html($('#printstock').html().replace("{{ghi_chu}}",ob.note))  
                
                var hx = $('#printstock table tbody').html( )+''
                $('#printstock table tbody').html('')
                for(var i in ob.products){
                    var row = ob.products[i]
                    
                    if(ob.products[i].name==undefined){
                        ob.products[i] = $.extend(loadProduct(ob.products[i].product),ob.products[i])
                    }
                    
                    var hx2 = hx;
                    hx2=hx2.replace("{{ma_hang_hoa}}",ob.products[i].code)
                    hx2=hx2.replace("{{ten_hang_hoa}}",ob.products[i].name)
                     
                    hx2=hx2.replace("{{ton_kho}}",formatQuantity(ob.products[i].deliveryqty))  
                    hx2=hx2.replace("{{kiem_thuc_te}}",formatQuantity(ob.products[i].quantity))
                    
                    hx2=hx2.replace("{{so_luong_lech}}",formatQuantity(ob.products[i].quantity-ob.products[i].deliveryqty))
                    hx2=hx2.replace("{{gia_tri_lech}}", formatCurrency(ob.products[i].price2*(ob.products[i].quantity-ob.products[i].deliveryqty)) )         
                    
                    $('#printstock table tbody').append(
                        /*$('<tr><td>'+ob.products[i].code+'</td><td>'+ob.products[i].name+'</td><td>'+
                            formatQuantity(ob.products[i].deliveryqty)+'</td><td>'+
                            formatQuantity(ob.products[i].quantity)+                              
                            '</td><td>'+
                            formatQuantity(ob.products[i].quantity-ob.products[i].deliveryqty)+'</td><td>'+
                            formatCurrency(ob.products[i].price2*(ob.products[i].quantity-ob.products[i].deliveryqty))+'</td></tr>')*/
                        hx2    
                    )
                    
                    if(row.quantity>row.deliveryqty){
                        tonglechtang += row.quantity-0
                        tonglechtang2 += (row.price2)*(row.quantity-row.deliveryqty)
                    }else if(row.quantity<row.deliveryqty){
                        tonglechgiam += row.quantity-0
                        tonglechgiam2 += (row.price2)*(row.quantity-row.deliveryqty)
                    }
                    tongthucte += row.quantity-0
                    tongthucte2 += row.price2*row.quantity
                }
                
                $('#printstock').html($('#printstock').html().replace("{{tongthucte}}",formatCurrency(tongthucte)))
                $('#printstock').html($('#printstock').html().replace("{{tongthuctetien}}",formatCurrency(tongthucte2)))    
                
                $('#printstock').html($('#printstock').html().replace("{{tonglechtang}}",formatCurrency(tonglechtang)))
                $('#printstock').html($('#printstock').html().replace("{{tonglechtangtien}}",formatCurrency(tonglechtang2)))    
                
                $('#printstock').html($('#printstock').html().replace("{{tonglechgiam}}",formatCurrency(tonglechgiam)))
                $('#printstock').html($('#printstock').html().replace("{{tonglechgiamtien}}",formatCurrency(tonglechgiam2)))  
                
                $('#printstock').html($('#printstock').html().replace("{{tongchenhlech}}",formatCurrency(tonglechtang+tonglechgiam)))
                $('#printstock').html($('#printstock').html().replace("{{tongchenhlechtien}}",formatCurrency(tonglechtang2+tonglechgiam2)))  
                 
                //$('#printstock').printMe2( ) //
            break;
            
            case "printbarcode":
                type = "printbarcode";
                var cn = loadObject(cb,branches);
                var fx = $('#printbarcode').html();   //console.log('inhoadon:fx:', JSON.stringify(fx))
                $('#printbarcode').html('');
                
                //customer
                var cc={}
                if(ob.customer>0){
                    cc = loadObject(ob.customer,customers)
                }else{
                    cc = {name:'Khách lẻ','address':'','phone':'','zone':''}
                }   //console.log('inhoadon:cc:', JSON.stringify(cc),JSON.stringify(ob.products))
                
                var tpp = []
                for(var i in ob.products){
                    tpp[i] = fx
                    
                    tpp[i] = tpp[i].replace("{{ten_cua_hang}}",site_name);                 
                    tpp[i] = tpp[i].replace("{{ten_chi_nhanh}}",cn.name);                     
                    tpp[i] = tpp[i].replace("{{dien_thoai_chi_nhanh}}",cn.phone);                    
                    tpp[i] = tpp[i].replace("{{ngay_ban}}",getDate(ob.date));                    
                    tpp[i] = tpp[i].replace("{{ma_hoa_don}}",ob.code)
                    
                    tpp[i] = tpp[i].replace("{{ten_khach_hang}}",cc.name)
                    tpp[i] = tpp[i].replace("{{dia_chi_khach_hang}}",cc.address)
                    tpp[i] = tpp[i].replace("{{khu_vuc_khach_hang}}",cc.zone)
                    tpp[i] = tpp[i].replace("{{dien_thoai_khach_hang}}",cc.phone)
                    
                    //console.log('inhoadon:tpp1:', JSON.stringify(tpp[i]))
                    
                    //nguoi ban: user                
                    tpp[i] = tpp[i].replace("{{nguoi_ban}}",loadUser(ob.user).name)
                    
                    var qk = loadProduct(ob.products[i].product)
                    if(ob.products[i].name==undefined){
                        ob.products[i].name = qk.name
                    }
                    
                    tpp[i] = tpp[i].replace("{{ma_hang_hoa}}",ob.products[i].code)
                    var nm = ob.products[i].name
                    //var adTop = 0;
                    if(ob.products[i].top != undefined && JSON.stringify(ob.products[i].top)!='{}'){
                        var nm2 = [];                        
                        for(var mm in ob.products[i].top){
                            var mm2 = loadProduct(mm)
                            var mm3 = ob.products[i].top[mm]                            
                            nm2.push('<p>+'+mm3[0]+' '+mm2.name+'</p>')
                            
                            //adTop += mm2.price*mm3;
                        }  
                        nm += nm2.join('')  //'<br />'+
                    }
                    
                    //console.log('inhoadon:nm:', JSON.stringify(tpp[i]), JSON.stringify(nm))
                    
                    tpp[i] = tpp[i].replace("{{ten_hang_hoa}}",ob.products[i].name)
                    tpp[i] = tpp[i].replace("{{ghi_chu_hang_hoa}}",ob.products[i].note)
                    tpp[i] = tpp[i].replace("{{hang_hoa}}",nm)
                     
                    tpp[i] = tpp[i].replace("{{gia_chiet_khau}}",formatCurrency(priceafterdiscount.call(ob.products[i]))+
                            (ob.products[i].discount>0?(" <span style='text-decoration: line-through;'>"+formatCurrency(priceafter.call(ob.products[i]))+"</span>"):""))
                    tpp[i] = tpp[i].replace("{{gia_hang_hoa}}",formatCurrency(priceafterdiscount.call(ob.products[i])))        
                     
                    tpp[i] = tpp[i].replace("{{so_luong}}",ob.products[i].quantity)
                    tpp[i] = tpp[i].replace("{{tong_tien}}",formatCurrency(ob.products[i].quantity*priceafterdiscount.call(ob.products[i])))
                    
                    tpp[i] = '<div style="float:left;width:'+cfs.PrintBarCodeWidth+'px;height:'+
                        cfs.PrintBarCodeHeight+'px;margin-left:'+
                        cfs.PrintBarCodeDistanceColumn+'px;margin-bottom:'+
                        cfs.PrintBarCodeDistanceRow+'px;">'+tpp[i]+'</div>'; 
                        
                    //console.log('inhoadon:tpp2:', JSON.stringify(tpp[i]))    
                }
                
                $('#printbarcode').html('<div style="width:'+
                    (cfs.PrintBarCodeWidth+cfs.PrintBarCodeDistanceColumn)*cfs.PrintBarCodeColumns+'px;">'+
                    tpp.join('')+'<div>');
                  
                //$('#'+type).printMe2( ) //
            break;
        }
        
        if(!edit)
            $('#'+type).printMe2( )
        else{
            $('body').append(''+
            '<div id="ahth" style="width:100%;min-height:100%;z-index:1;position:fixed;top:0;left:0;background-color:white;">'+
            '<div class="row form-wrapper">'  +                                       
                '<div class="col-md-12">'+
                    '<textarea name="aahth" id="aahth" style="display: none;"></textarea>'+
                '</div>'+
                '<div class="col-md-12 col-sm-12 text-right">'+
                  '<button class="kv2Btn kv2BtnSetting ng-binding" onclick="printEdit()"><i class="fa fa-print"></i>In</button>'+
                  '&nbsp;<button class="kv2Btn kv2BtnSetting ng-binding" onclick="printExit()"><i class="fa fa-close"></i>Thoát</button>'+
                '</div>'+
              '</div></div>')
            editorInstance = CKEDITOR.replace( 'aahth', cko() );
            editorInstance.on('instanceReady',function(ev){
                editorInstance.setData($('#'+type).html()) ;
            });
        }
    }
    var data;
    if(data = cache_prints.getItem(type)){
        $('#pprr').html(data)
        mylove(data)    
    }else{    
        //$('#pprr').load("/print/"+type+".html",null,function(data){
        $('#pprr').load("/ajax.php?action=loadprint&name="+type+"",null,mylove)
    }
}

function intamtinh(ob){ console.log('intamtinh:',JSON.stringify(ob))
    var type = 'printinvoice' 
    function mylove(data){
        
        saveCachePrints(type,data);
                 
        var cn = loadObject(cb,branches),tongtienhang=0;
        
        $('#'+type).html($('#'+type).html().replace(/(<div[^>]*?>)[^<]+bán hàng[^<]*?<\/div>/i,"$1PHIẾU TẠM TÍNH</div>"))
        
        //phòng bàn:
        if(cfs && cfs.type==1 && ob.table!='0' && ob.table!=''){
            var tb = _getTableName(ob.table)
            $('#'+type).html($('#'+type).html().replace("{{phong_ban}}",tb))        
        }
        
        $('#'+type).html($('#'+type).html().replace("{{ten_cua_hang}}",site_name))        
        $('#'+type).html($('#'+type).html().replace("{{ten_chi_nhanh}}",cn.name))         
        $('#'+type).html($('#'+type).html().replace("{{dien_thoai_chi_nhanh}}",cn.phone))   
              
        $('#'+type).html($('#'+type).html().replace(/{{ngay_ban}}/g,getDate(ob.date)))
        $('#'+type).html($('#'+type).html().replace(/{{gio_vao}}/g,getDate(ob.datestart)))
        $('#'+type).html($('#'+type).html().replace(/{{gio_ra}}/g,getDate(ob.date)))
               
        $('#'+type).html($('#'+type).html().replace("{{ma_hoa_don}}",ob.code))
        
        //customer
        var cc={}
        if(ob.customer>0){
            cc = loadObject(ob.customer,customers)
        }else{
            cc = {name:'Khách lẻ','address':'','phone':'','zone':'',debt:0}
        }
         
        $('#'+type).html($('#'+type).html().replace("{{ten_khach_hang}}",cc.name))
        $('#'+type).html($('#'+type).html().replace("{{dia_chi_khach_hang}}",cc.address))
        $('#'+type).html($('#'+type).html().replace("{{khu_vuc_khach_hang}}",cc.zone))
        $('#'+type).html($('#'+type).html().replace("{{dien_thoai_khach_hang}}",cc.phone))
        //$('#'+type).html($('#'+type).html().replace("{{cong_no_khach_hang}}",formatCurrency(cc.debt)))
        
        //nguoi ban: user         
        $('#'+type).html($('#'+type).html().replace("{{nguoi_ban}}",loadUser(ob.user).name))
        
        $('#'+type).html($('#'+type).html().replace("{{ghi_chu}}",ob.note))  
        
        var hx = $('#'+type+' table tbody').html( )+''
         
        $('#printinvoice table tbody').html('')
        for(var i in ob.products){
            if(ob.products[i].name==undefined){
                ob.products[i].name = loadProduct(ob.products[i].product).name
            }
            
            var hx2 = hx;
            hx2=hx2.replace("{{stt}}",i-(-1))
            hx2=hx2.replace("{{dvt}}",ob.products[i].uname==null?'':ob.products[i].uname)
            hx2=hx2.replace("{{ma_hang_hoa}}",ob.products[i].code)
            var nm = ob.products[i].name
            //var adTop = 0;
            if(ob.products[i].top != undefined && JSON.stringify(ob.products[i].top)!='{}'){
                var nm2 = [];                        
                for(var mm in ob.products[i].top){
                    var mm2 = loadProduct(mm)
                    var mm3 = ob.products[i].top[mm]                            
                    nm2.push('<p>+'+mm3[0]+' '+mm2.name+'</p>')
                    
                    //adTop += mm2.price*mm3;
                }  
                nm += nm2.join('')  //'<br />'+
            }
            hx2=hx2.replace("{{ten_hang_hoa}}",ob.products[i].name)
            hx2=hx2.replace("{{hang_hoa}}",nm) 
            
            hx2=hx2.replace("{{ghi_chu_hang_hoa}}",ob.products[i].note)
             
            hx2=hx2.replace("{{gia_chiet_khau}}",formatCurrency(priceafterdiscount.call(ob.products[i]))+
                    (ob.products[i].discount>0?(" <span style='text-decoration: line-through;'>"+formatCurrency(priceafter.call(ob.products[i]))+"</span>"):""))
            hx2=hx2.replace("{{gia_hang_hoa}}",formatCurrency(priceafterdiscount.call(ob.products[i])))    
            
            hx2=hx2.replace("{{gia_goc_hang_hoa}}",formatCurrency(priceafterdiscount.call(ob.products[i])-(-ob.products[i].discount))) //ob.products[i].price       
            hx2=hx2.replace("{{gia_chiet_khau_pt}}",(ob.products[i].discount>0 && ob.products[i].price>0?Math.round(100*ob.products[i].discount/ob.products[i].price):"0"))
            hx2=hx2.replace("{{gia_chiet_khau_vnd}}",(ob.products[i].discount>0?formatCurrency(ob.products[i].discount):"0"))
                   
            hx2=hx2.replace("{{so_luong}}",ob.products[i].quantity)
            hx2=hx2.replace("{{tong_tien}}",formatCurrency(ob.products[i].quantity*priceafterdiscount.call(ob.products[i])))
            
            $('#'+type+' table tbody').append(
                 
                hx2
            )
            tongtienhang+=ob.products[i].quantity*priceafterdiscount.call(ob.products[i])//(ob.products[i].price-ob.products[i].discount)
            console.log('tongtienhang:',tongtienhang)
        }
 
        $('#'+type).html($('#'+type).html().replace("{{tong_tien_hang}}",formatCurrency(tongtienhang)))
        $('#'+type).html($('#'+type).html().replace("{{chiet_khau}}",formatCurrency(ob.discount)))
        
        var paid = 0;
        if(ob.invoices && ob.invoices.length) paid = ob.invoices.sum('price')
        $('#'+type).html($('#'+type).html().replace("{{tong_cong}}",formatCurrency(tongtienhang-ob.discount-paid)))
        $('#'+type).html($('#'+type).html().replace("{{khach_tra}}",formatCurrency(ob.paying)))
        
        $('#'+type).html($('#'+type).html().replace("{{bang_chu}}",DOCSO.doc(tongtienhang-ob.discount-paid)))
         
        //alert(-ob.paying)
        //alert(tongtienhang)
        //alert(-ob.discount)
        //var _ob = $.extend({},ob)
        //alert(cc.debt-_ob.paying+tongtienhang-_ob.discount)
        //alert(formatCurrency(cc.debt-_ob.paying+tongtienhang-_ob.discount))
        //if(ob.status<=0)
        if(ob.addtoaccount==1)
            $('#'+type).html($('#'+type).html().replace("{{cong_no_khach_hang}}",formatCurrency(cc.debt-ob.paying+tongtienhang-ob.discount)))
        else
            $('#'+type).html($('#'+type).html().replace("{{cong_no_khach_hang}}",formatCurrency(cc.debt)))
        
        $('#'+type).printMe2( ) 
         
         
    }
    var data;
    if(data = cache_prints.getItem(type)){
        $('#pprr').html(data)
        mylove(data)    
    }else{             
        $('#pprr').load("/ajax.php?action=loadprint&name="+type+"",null,mylove)
    }
}

function loadAjaxPrint(type,lo){
     
    if(!cache_prints.getItem(type)){     
        $.ajax({
            url: "/ajax.php?action=loadprint&name="+type+"",
            success: function(data){
                saveCachePrints(type,data);
                if(lo) lo.call()
            }
        })
    }else if(lo) lo.call()
    
}

function printEdit(){
    $('<div>'+editorInstance.getData()+'</div>').printMe2( )
}

function printExit(){
    $('#ahth').remove()
}

var editorInstance,cko = function(){return {
        height : $(window).height()-290,// $('body').height()- 16,
        allowedContent: true
    }},cko2 = {
        height : $('body').height()-16,
        allowedContent: {
            img: {
                attributes: [ '!src', 'alt', 'width', 'height'  ] ,
                styles: true,
                classes: true
            },
            p: {                 
                styles: true,
                classes: true
            },
            'center strong em h1 h2 table tr th tbody thead tfoot hr style span': {                 
                styles: true,
                classes: true
            },
            div: {
                attributes: [ 'class'  ] ,
                styles: true,
                classes: true
            },
            td: {
                attributes: [ 'colspan' ,'rowspan'  ] ,
                styles: true,
                classes: true
            }
        }
    }

function nCode2(d, getHour){
    getHour = getHour==undefined?true:getHour
    var date
    if(d) 
        date = new Date(d)
    else
        date = new Date()
    return spr(date.getDate())+"/"+spr(date.getMonth()+1)+"/"+date.getFullYear()+
        (getHour?(" "+spr(date.getHours())+":"+spr(date.getMinutes())):'')
}

function spr(x){
    if((x+"").length==1) return "0"+x
    return x;
}

function emptyPurchase(t){
    t = t==undefined ? 3:t
    return {
        type: t,
        products: [],
        status: -1,
        addtoaccount: 1,
        supplier:0,
        user:0,
        date:nCode2(),
        note:'',
        code:'',
    }
}   

//for damageItem
function emptyPurchase2( ){             
    return {
        type: 10,
        products: [],
        status: -1,
         
        user:0,
        date:nCode2(),
        note:'',
        code:'',
    }
}
 
jQuery.fn.printMe2 = function(options){

	// Setup options
	var settings = $.extend({
		// Defaults options.
		path: [],
		title: "",
		head: false,
        type: 'iframe',
		delay: 1000
	}, options );
	
	// Set the properties and run the plugin
	return this.each(function(){
		
		// Store the object
		var $this = $(this);
        
        if(settings.type=='popup'){

    		var w = window.open();
    		w.document.write( "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">" );
    		w.document.write( "<html>" );
    		w.document.write( "<head>" );
    		w.document.write( "<meta charset='utf-8'>" );
    
    		// Add the style sheets
    		for(i in settings.path){
    			w.document.write('<link rel="stylesheet" href="'+settings.path[i]+'">');
    		}
    		
    		// Close the head
    		w.document.write('</head><body>');
    
    		// Add a header when the title not is empty
    		if (settings.title != "")
    			w.document.write( "<h1>" + settings.title + "<\/h1>" );
    
    		// Add a content to print
    		w.document.write( $this.html() );
    		w.document.write('<script type="text/javascript">function closeme(){window.close();}setTimeout(closeme,50);window.print();</script></body></html>');
    		w.document.close();
        }else{
            var w = document.createElement('iframe');
            document.body.append(w)
    		w.contentDocument.write( "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">" );
    		w.contentDocument.write( "<html>" );
    		w.contentDocument.write( "<head>" );
    		w.contentDocument.write( "<meta charset='utf-8'>" );
    
    		// Add the style sheets
    		for(i in settings.path){
    			w.contentDocument.write('<link rel="stylesheet" href="'+settings.path[i]+'">');
    		}
    		
    		// Close the head
    		w.contentDocument.write('</head><body>');
    
    		// Add a header when the title not is empty
    		if (settings.title != "")
    			w.contentDocument.write( "<h1>" + settings.title + "<\/h1>" );
    
    		// Add a content to print
    		w.contentDocument.write( $this.html() );
            if(window.onafterprint!==null){
                w.contentDocument.write('<script>var afterprint = '+
                    window.onafterprint.toString()+
                    ';window.onafterprint=afterprint/*.bind(top)*/</script>');
            }
    		w.contentDocument.write('<script type="text/javascript">setTimeout(function(){window.print();},'+settings.delay+')</script></body></html>');
    		setTimeout(function(){ w.remove() },settings.delay+500)
        }
	});
}   

function setTitle(t){
    if(!branches || !cb){
        setTimeout(function(){setTitle(t)},500)
        return 
    }      
    document.title = t
}
        
        //tim con cua san pham
        function findchild(pid){
            var ret = [];
            for(var ii in products){
                pro = products[ii];
                
                if(pro.id==pid||pro.parent==pid) ret.push(pro)
            }
            return ret;
        }
        
        function checknum(val, val2, zero){
            val2 = val2 === 0 ? 0 :( val2 || 4);
            zero = zero==undefined ? true : zero;
            
            //rtrim(eval("1/2+6.2345").toFixed(10),"0")
            
            try{
                val3 = eval(val);
                if(zero && (val3<=0)) return '';
                if(val2>0){
                    val4 = rtrim(val3.toFixed(val2+2),"0.");//10
                    val5 = explode('.',val4);
                    if(val5[1]==undefined){
                        
                    }else{
                        if(val5[1].length<=val2) val3 = zero? Math.max(0,val4): val4;//fl
                        else val3 = val;//str
                    }
                }else{
                    //k check duoi, tat ca deu cho ra ket qua float
                    if(val2==0){
                        return Math.round(val3);
                    }else{
                        return val3.toFixed(Math.abs(val2));
                    }
                }
            }catch(e){
                val3 = '';//str
            }
            return val3;
        }        
        
        
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
        
        function _serialize(a,ch,f){//filter only lowercase: 1 ; autolower: 2
            b=''; m= true;
            ch = ch || []; //console.log((ch))
            a2 = typeof a =='string' ? $(a):a;
            a2.find('[ng-model],[k-ng-model]').each(function(k,v){
                
                //console.log((k))
                //console.log((v))
                if(m && ch.length>0 && ch.indexOf(k)!=-1){
                    
                    //console.log($(this).val())
                    
                    if($(this).v()=='') {
                        m = false;
                        return false;
                        
                    }
                        
                }
                if(m){
                    c = getname($(this).attr('ng-model')||$(this).attr('k-ng-model'));
                    if(f==2) c= c.toLowerCase()
                    if(f==undefined || f==2 || (f==1 && c.match(/^[a-z]+$/))){
                        
                        /*
                        if($(this).is(':radio')){
                            if(!$(this).is(':checked')) continue;
                        }
                        */
                        if($(this).is('[type="checkbox"]')){ 
                            if($(this).prop('checked') || $(this).is(':checked') || $(this).is('[checked]')){
                                var _v = $(this).v();
                                _v = _v==null ? '': _v; 
                                b += '&' + c+'='+encodeURIComponent(_v);    
                            } 
                        }else
                        if($(this).is('select[multiple]')){
                            var _v = $(this).val();                             
                            b += '&' + c+'='+(_v);
                        }else
                        
                        if(!$(this).is(':radio') ||($(this).is(':radio') && ($(this).is('[checked]')||$(this).prop('checked') || $(this).is(':checked')) )){  
                        
                            var _v = $(this).v();
                            _v = _v==null ? '': _v; 
                            b += '&' + c+'='+encodeURIComponent(_v);
                        
                        }
                    }
                }
            })
            return m?(b?b.substr(1):''):'';
        }
        
        function getname(str){
            return str.replace(new RegExp("^.+[.](.+)$","gi"),"$1");
        }     

function emptyST( ){
    t = 7
    return {
        type: t,
        products: [],
        status: -1,
        user:0,
        date:nCode2(),
        note:'',
        code:'',
    }
}               

function capitalizeOne(string){
	if(string==undefined) string = this
	var b=string.charAt(0).toUpperCase()
  return b+string.substring(1).toLowerCase()
}

String.prototype.capitalizeOne = capitalizeOne

var DOCSO = function() {
  var t = ["không", "một", "hai", "ba", "bốn", "năm", "sáu", "bảy", "tám", "chín"],
    r = function(r, n) {
      var o = "",
        a = Math.floor(r / 10),
        e = r % 10;
      return a > 1 ? (o = " " + t[a] + " mươi", 1 == e && (o += " mốt")) : 1 == a ? (o = " mười", 1 == e && (o += " một")) : n && e > 0 && (o = " lẻ"), 5 == e && a >= 1 ? o += " lăm" : 4 == e && a >= 1 ? o += " tư" : (e > 1 || 1 == e && 0 == a) && (o += " " + t[e]), o
    },
    n = function(n, o) {
      var a = "",
        e = Math.floor(n / 100),
        n = n % 100;
      return o || e > 0 ? (a = " " + t[e] + " trăm", a += r(n, !0)) : a = r(n, !1), a
    },
    o = function(t, r) {
      var o = "",
        a = Math.floor(t / 1e6),
        t = t % 1e6;
      a > 0 && (o = n(a, r) + " triệu", r = !0);
      var e = Math.floor(t / 1e3),
        t = t % 1e3;
      return e > 0 && (o += n(e, r) + " nghìn", r = !0), t > 0 && (o += n(t, r)), o
    };
  return {
    doc: function(r) {
      if (0 == r) return t[0];
      var n = "",
        a = "";
      do ty = r % 1e9, r = Math.floor(r / 1e9), n = r > 0 ? o(ty, !0) + a + n : o(ty, !1) + a + n, a = " tỷ"; while (r > 0);
      var h = n.trim()
       
      if(h.match(/ (nghìn|triệu|tỷ)$/)){
      	h+=" đồng chẵn"
      }else{
      	h+=" đồng"
      }
      return h.capitalizeOne()
    }
  }
}();

//alert( DOCSO.doc(123100) );

function backPri(x){
    var x2 = x.split('.')
    x2.pop()
    return x2.join('.')
}

var controlers = {
    '1.1':'Users',
    '1.2':'Branches',
    '2.1':'Products',
    '2.2':'StockTakes',
    '2.3':'PriceBook',
    '3.1':'Invoices',
    '3.2':'PurchaseOrder',
    '3.3':'PurchaseReturns',
    '3.4':'DamageItems',
    '3.5':'Returns',
    '4.1':'Customers',
    '4.2':'Suppliers',
    '5.1':'SaleReport',
    '5.2':'ProductReport',
    '5.3':'CustomerReport',
    '6.1':'CashFlow',
    '7.1':'TableAndRoom',
    '8.1':'News',
}

function objectLength(o){
    var b=0;for(var i in {}){b++}
    return b
}

function select(li, bo, index){
    //bg
    //console.log($(li).position())
    $(li).parent().siblings().find('a').removeClass('active');
    $(li).addClass('active');
    
    $(li).parents('kv-tabs').find('.tabBg').animate({
        left: $(li).position().left,
        width: $(li).parent().width()
    })
    
    $(li).parents('kv-tabs').find('kv-tab-pane>div').addClass('ng-hide');
    $(li).parents('kv-tabs').find('kv-tab-pane:nth('+index+')>div').removeClass('ng-hide');
}

function saveCacheInvoices_cashflows(data){
    cache_invoices.setItem("cashflows",data,{expirationAbsolute: null,
     expirationSliding: 3600,
     priority: Cache.Priority.HIGH,
     callback: function(k, v) { console.log('Cache removed: ' + k); }
    });
}
function loadJsonInvoices_cashflows(lo,va){
    console.log('loadJsonInvoices_cashflows');
    $.getJSON( "ajax.php?action=cashflows&branch="+cb, function( data ) {
          va = va == undefined ? 'invoices':va
          //invoices = data;
          eval(va+' = data')
          saveCacheInvoices_cashflows(data)
           if(lo!=undefined) lo.call();
        });
}        

var vkl

function loadJsonUserRoles(lo){
    $.getJSON( "ajax.php?action=user_roles", function( data ) {
          user_roles = data;
          saveCacheUserRoles()
           if(lo!=undefined) lo.call();
        });
}

function saveCacheUserRoles(){
    cache_users.setItem("user_roles",user_roles,{expirationAbsolute: null,
     expirationSliding: 3600,
     priority: Cache.Priority.HIGH,
     callback: function(k, v) { console.log('Cache removed: ' + k); }
    });
}   

function loadRoles(create2,cb2){
    if(create2==undefined) create2 = create;
    if(cb2==undefined) cb2 = cb;
    var gf = loadUser(create2)
    if(gf.admin==1){
        return ids.concat('admin');
    }
    if(gf.permissions.length==0) return []
    var pi = loadObject(cb2,gf.permissions,'branch').permission
    if(pi == undefined || pi<=0) return []
    return loadObject(pi,user_roles).roles.split(",")
}
/*
function checkRole(rid){
    return loadRoles().indexOf(rid)>=0
}
*/
function st(create2,cb2){ //settings
    var lr = loadRoles(create2,cb2), z = {}
    for(var i in lr){
        z[lr[i]] = 1        
    }
    return z;
}   

 
//2019
var rngs = {
    today:'Hôm nay',
    yesterday:'Hôm qua',
    thisweek:'Tuần này',
    '7day':'7 ngày qua',
    month:'Tháng này',
    lastmonth:'Tháng trước',
    monthlunar:'Tháng này (ÂL)',
    lastmonthlunar:'Tháng trước (ÂL)',
    '30day':'30 ngày qua',
    quarter:'Quý này',
    lastquarter:'Quý trước',
    year:'Năm này',
    lastyear:'Năm trước',
    yearlunar:'Năm này (ÂL)',
    lastyearlunar   :'Năm trước (ÂL)',
    'alltime':'Toàn thời gian',
    lastweek:'Tuần trước'
}

function findRangeByLabel(label){
    for(var i in rngs){
        if(rngs[i]==label)
            return i=='alltime'?'':$i;
    }
    
    return '';
}
 
        function removeCacheWhenChangeBranch(){
            cache_customers.removeItem('customer_groups')
            cache_customers.removeItem('customers')
            
            cache_invoices.removeItem('invoices')
            cache_invoices.removeItem('purchases')
            
            cache_orders.removeItem('orders')
            cache_orders.removeItem('purchase')
            
            cache_products.removeItem('products')
            cache_products.removeItem('categories')
            
            cache_suppliers.removeItem('suppliers')
            
            cache_tables.removeItem('tables')
            cache_tables.removeItem('table_groups')
            
            cache_kitchen.removeItem('kitchen')
            
            cache_invoices.removeItem("partners");
            cache_invoices.removeItem("cashflows");
            cache_invoices.removeItem("stocktakes");
            cache_invoices.removeItem("purchasereturns");
        }

function _calcTimeString(item){
    var a = Math.floor((item.t2)/60) - Math.floor((item.t1)/60);
    var b = [0,0]
    if(a>=60){
        b[0] = Math.floor(a/60)                
    }
    b[1] = a- 60*b[0];
    return b[0]+' giờ '+b[1]+ ' phút'
}

        function pretty(p,moreFunc){
            
            console.log('pretty')
            
            p = p == undefined?'html ':(p+' ');
            
            //if($(p).hasClass('has-pretty')) return false
            
            //$(p).addClass('has-pretty')
            
            //setTimeout(function(){
            $(p+'.prettyradio').unbind('click')
            $(p+'.prettyradio').click(function(ef){ //alert('test')
                t = $(this);
                t2 = t.find('input:radio');
                //console.log(t2.attr('name'));
                
                //console.log($('input:radio[name="'+t2.attr('name')+'"]'));
                
                if(t2.attr('checked')||t2.prop('checked')){  
                     
                    if($('input:radio[name="'+t2.attr('name')+'"]').length==1){
                        t2.val('0');
                        
                        t2.removeAttr('checked'); t2.prop('checked',false);
                        t2.next().removeClass('checked');
                    }
                }else{
                 
                    $('input:radio[name="'+t2.attr('name')+'"]').removeAttr('checked');
                    t2.attr('checked','checked'); t2.prop('checked',true);
                    $('input:radio[name="'+t2.attr('name')+'"]').next().removeClass('checked');
                    t.find('a').addClass('checked');
                    
                    if($('input:radio[name="'+t2.attr('name')+'"]').length==1)
                        t2.val('1');
                }
                
                if(ef!=undefined &&moreFunc!=undefined){
                    console.log('moreFunc');
                    moreFunc.call(this, t2);
                }
            });
            $(p+'.prettycheckbox').unbind('click')
            $(p+'.prettycheckbox').click(function(ef){
                t = $(this);
                t2 = t.find('input:checkbox');
                
                if(t2.attr('checked')!=undefined){  
                    t2.removeAttr('checked');
                     
                    t.find('a').removeClass('checked');    
                    
                    if($('input:checkbox[ng-model="'+t2.attr('ng-model')+'"]').length==1)
                        t2.val('0');
                }else{
                    //console.log(t2)
                    t2.attr('checked','checked');
                    //console.log(1) 
                    t.find('a').addClass('checked');
                    //console.log(2) 
                    if($('input:checkbox[ng-model="'+t2.attr('ng-model')+'"]').length==1)
                        t2.val('1');
                }
                if(ef!=undefined && moreFunc!=undefined){
                    console.log('moreFunc');
                    moreFunc.call( this, t2);
                }
            });
            
            //},1000);
        }   
        
 
    var defaultSettings= {
        IncludeVND: "0",
        SelectedPriceBookId: "0",
        col: "2",
        row: "4",
        size1: "10",
        size2: "10",
        size3: "14",
        size4: "99",
        size5: "142",
        size6: "5",
        size7: "35",
        size8: "1",
        size9: "1",
    };
    
    var z3;
    
    if(localStorage.defaultSettings==undefined){
        localStorage.defaultSettings = JSON.stringify(defaultSettings)
        z3 = defaultSettings
    }else{
        try{
            z3 = JSON.parse(localStorage.defaultSettings)
        }catch(e){
            z3 = defaultSettings 
            localStorage.defaultSettings = JSON.stringify(defaultSettings)
        }
    }
    
var defautPrintBarCode = {
    PrintBarCodeWidth: 188,
    PrintBarCodeHeight: 113,
    PrintBarCodeColumns: 1,
    PrintBarCodeDistanceColumn: 10,
    PrintBarCodeDistanceRow: 10,
}    

function _convertProducts(dataItem,notname){                        
    for(var i in dataItem.products){
        var row = dataItem.products[i]
        if(!notname)
            row.name = loadProduct(row.product).name
        if(!notname && row.topping && row.topping.match(/^\{/) && row.name2==undefined){
            var jq = JSON.parse(row.topping)
            
            row.name2 = [];
            //row.name3 = [];
            for(var j in jq){
                var j2 = loadProduct(j)                
                row.name2.push('+'+jq[j][0]+' '+j2.name+' ('+formatCurrency(jq[j][1])+')')
                //row.name3.push('+'+jq[j][0]+' '+j2.name)
            }
            row.name2 = row.name2.join('') //<br />
               
        }
        if(row.topping && row.topping.match(/^\d+$/)){
            var hh = loadObject(row.topping, dataItem.products, 'opid')
            if(!notname)
                hh.price -= -row.price;
            hh.discount -= -row.discount;
            //console.log('hh:',JSON.stringify(hh),JSON.stringify(row))
            //dataItem.products.splice(i,1)                                 
        }
    }   
    
    for(var i = dataItem.products.length-1;i>=0;i--){
        var row = dataItem.products[i]         
        if(row.topping && row.topping.match(/^\d+$/)){             
            dataItem.products.splice(i,1)                                 
        }
    }   
}         

        function loadJsonBranches(lo){
            
            console.log('loadJsonBranches');
            
            $.getJSON( "ajax.php?action=branches", function( data ) {
                  branches = data;
                  saveBranches(data)
                  if(lo!=undefined) lo.call();                  
            });
        }
        
        function saveBranches(data){
            
            console.log('saveBranches',JSON.stringify(data));
            
            cache_branches.setItem("branches",data,{expirationAbsolute: null,
             expirationSliding: 3600,
             priority: Cache.Priority.HIGH,
             callback: function(k, v) { console.log('Cache removed: ' + k); }
            });
        }  
        
function _getTableName(table){
    if(table && table!='0'){
        return (table+'').split(',').map(function(v){
            var tg = loadObject(v,tables)
            return tg ? tg.name : v;
        }).join('/')    
    }
    return ''    
} 

function _getOneTable(table,isInt){
    if(table && table!='0'){
        var table2 = table.split(',')[0]
        return (isInt == undefined || isInt==1)?parseInt(table2):table2
    }
    return ''   
} 

function savetables(){
    if(cfs['type']==1){ 
        console.log('savetables');
         
        cache_tables.setItem("tables",tables,{expirationAbsolute: null,
         expirationSliding: 3600,
         priority: Cache.Priority.HIGH,
         callback: function(k, v) { console.log('Cache removed: ' + k); }
        });
    } 
} 

function loadJsonTables(lo){
    if(cfs['type']==1){        
        if(cb==null) return;
        
        $.getJSON( "ajax.php?action=tables&branch="+cb, function( data ) {
              //tables = data;
              //savetables()
              filterTables(data)
               if(lo!=undefined) lo.call();
        }); 
    }
}   

function filterTables(t){
    if(!tables && !t) return;
     
    ////if(!tables) remove 6/11/19
    if(t)
        tables = t
    
    if(cfs.type==1){
        
        if(cfs.UseCod && !loadTable('-2')){
            tables.unshift({
                branch: cb,
                group: "0",
                id: "-2",
                name: "Giao đi",
                note: "",
                status: "0",
            })
        }
        
        if(!loadTable('-1')){
            tables.unshift({
                branch: cb,
                group: "0",
                id: "-1",
                name: "Mang về",
                note: "",
                status: "0",
            })
        }
        
        savetables()
    }
}         

//05/31/2019    
if (typeof Object.assign != 'function') {
  // Must be writable: true, enumerable: false, configurable: true
  Object.defineProperty(Object, "assign", {
    value: function assign(target, varArgs) { // .length of function is 2
      'use strict';
      if (target == null) { // TypeError if undefined or null
        throw new TypeError('Cannot convert undefined or null to object');
      }

      var to = Object(target);

      for (var index = 1; index < arguments.length; index++) {
        var nextSource = arguments[index];

        if (nextSource != null) { // Skip over if undefined or null
          for (var nextKey in nextSource) {
            // Avoid bugs when hasOwnProperty is shadowed
            if (Object.prototype.hasOwnProperty.call(nextSource, nextKey)) {
              to[nextKey] = nextSource[nextKey];
            }
          }
        }
      }
      return to;
    },
    writable: true,
    configurable: true
  });
}

function clamp_range(range) {
	if(range.e.r >= (1<<20)) range.e.r = (1<<20)-1;
	if(range.e.c >= (1<<14)) range.e.c = (1<<14)-1;
	return range;
}

var crefregex = /(^|[^._A-Z0-9])([$]?)([A-Z]{1,2}|[A-W][A-Z]{2}|X[A-E][A-Z]|XF[A-D])([$]?)([1-9]\d{0,5}|10[0-3]\d{4}|104[0-7]\d{3}|1048[0-4]\d{2}|10485[0-6]\d|104857[0-6])(?![_.\(A-Za-z0-9])/g;

/*
	deletes `nrows` rows STARTING WITH `start_row`
	- ws         = worksheet object
	- start_row  = starting row (0-indexed) | default 0
	- nrows      = number of rows to delete | default 1
*/

function delete_rows(ws, start_row, nrows) {
	if(!ws) throw new Error("operation expects a worksheet");
	var dense = Array.isArray(ws);
	if(!nrows) nrows = 1;
	if(!start_row) start_row = 0;

	/* extract original range */
	var range = XLSX.utils.decode_range(ws["!ref"]);
	var R = 0, C = 0;

	var formula_cb = function($0, $1, $2, $3, $4, $5) {
		var _R = XLSX.utils.decode_row($5), _C = XLSX.utils.decode_col($3);
		if(_R >= start_row) {
			_R -= nrows;
			if(_R < start_row) return "#REF!";
		}
		return $1+($2=="$" ? $2+$3 : XLSX.utils.encode_col(_C))+($4=="$" ? $4+$5 : XLSX.utils.encode_row(_R));
	};

	var addr, naddr;
	/* move cells and update formulae */
	if(dense) {
		for(R = start_row + nrows; R <= range.e.r; ++R) {
			if(ws[R]) ws[R].forEach(function(cell) { cell.f = cell.f.replace(crefregex, formula_cb); });
			ws[R-nrows] = ws[R];
		}
		ws.length -= nrows;
		for(R = 0; R < start_row; ++R) {
			if(ws[R]) ws[R].forEach(function(cell) { cell.f = cell.f.replace(crefregex, formula_cb); });
		}
	} else {
		for(R = start_row + nrows; R <= range.e.r; ++R) {
			for(C = range.s.c; C <= range.e.c; ++C) {
				addr = XLSX.utils.encode_cell({r:R, c:C});
				naddr = XLSX.utils.encode_cell({r:R-nrows, c:C});
				if(!ws[addr]) { delete ws[naddr]; continue; }
				if(ws[addr].f) ws[addr].f = ws[addr].f.replace(crefregex, formula_cb);
				ws[naddr] = ws[addr];
			}
		}
		for(R = range.e.r; R > range.e.r - nrows; --R) {
			for(C = range.s.c; C <= range.e.c; ++C) {
				addr = XLSX.utils.encode_cell({r:R, c:C});
				delete ws[addr];
			}
		}
		for(R = 0; R < start_row; ++R) {
			for(C = range.s.c; C <= range.e.c; ++C) {
				addr = XLSX.utils.encode_cell({r:R, c:C});
				if(ws[addr] && ws[addr].f) ws[addr].f = ws[addr].f.replace(crefregex, formula_cb);
			}
		}
	}

	/* write new range */
	range.e.r -= nrows;
	if(range.e.r < range.s.r) range.e.r = range.s.r;
	ws["!ref"] = XLSX.utils.encode_range(clamp_range(range));

	/* merge cells */
	if(ws["!merges"]) ws["!merges"].forEach(function(merge, idx) {
		var mergerange;
		switch(typeof merge) {
			case 'string': mergerange = XLSX.utils.decode_range(merge); break;
			case 'object': mergerange = merge; break;
			default: throw new Error("Unexpected merge ref " + merge);
		}
		if(mergerange.s.r >= start_row) {
			mergerange.s.r = Math.max(mergerange.s.r - nrows, start_row);
			if(mergerange.e.r < start_row + nrows) { delete ws["!merges"][idx]; return; }
		} else if(mergerange.e.r >= start_row) mergerange.e.r = Math.max(mergerange.e.r - nrows, start_row);
		clamp_range(mergerange);
		ws["!merges"][idx] = mergerange;
	});
	if(ws["!merges"]) ws["!merges"] = ws["!merges"].filter(function(x) { return !!x; });

	/* rows */
	if(ws["!rows"]) ws["!rows"].splice(start_row, nrows);
} 

function _catPath($id,$cats){
    if(!($id>0)) return [];
    $cur = $cats[$id];
    $ret = [$cur['name']];
    while($cur['parent_id']>0){
        $cur = $cats[$cur['parent_id']];
        $ret.unshift($cur['name']);
    }
    return $ret;
}

function _convertProductCategories(){
    var ret = {}
    for(var i in product_categories){
        ret[product_categories[i].id] = $.extend({}, product_categories[i])
    }
    return ret;
}

function _convertCustomerGroups(){
    var ret = {}
    for(var i in customer_groups){
        ret[customer_groups[i].id] = $.extend({}, customer_groups[i])
    }
    return ret;    
}

////////////

function loadJsonCustomerGroups(lo){
    console.log('loadJsonCustomerGroups');
    if(cb==null) return;
    
    $.getJSON( "ajax.php?action=customergroups&id="+cb, function( data ) {
          customer_groups = data;
          cache_customers.setItem("customer_groups",data,{expirationAbsolute: null,
                         expirationSliding: 3600,
                         priority: Cache.Priority.HIGH,
                         callback: function(k, v) { console.log('Cache removed: ' + k); }
                        });
           if(lo!=undefined) lo.call();
        });
} 
function loadJsonCustomers(lo){
    console.log('loadJsonCustomers');
    if(cb==null) return;
    
    $.getJSON( "ajax.php?action=customers&id="+cb, function( data ) {
          customers = data;
          saveCacheCustomers(data)
           if(lo!=undefined) lo.call();
        });
}

function saveCacheCustomers(data){ 
    if(data==undefined) data = customers
    cache_customers.setItem("customers",data,{expirationAbsolute: null,
                         expirationSliding: 3600,
                         priority: Cache.Priority.HIGH,
                         callback: function(k, v) { console.log('Cache removed: ' + k); }
                        });
}

var db = new Dexie("qlt");
var dbver;

window.addEventListener('unhandledrejection', function(ev) {
  if (ev.reason.name === 'DatabaseClosedError') {
    ev.preventDefault();
  }
});


function _createBook(ob,b,cb){
    db['Books-'+b].add(ob).then(function(){
        //console.log(arguments)
        if(cb){
            cb.call(null,null,ob)
        }
    }).catch(function(e){
        //console.log(arguments)
        if(cb){
            cb.call(null,e)
        }
    });
}

function _createBooks(obs,b,cb){
    db.table('Books-'+b).bulkPut(obs).then(function(){
         
        if(cb && cb.call){
            cb.call(null,null,ob)
        }
    }).catch(Dexie.BulkError, function(e){
        
        if(cb && cb.call){
            cb.call(null,e)
        }
    });
}
 
function _createLocations(){
    $.ajax({
        url: 'data/Locations.txt',
        success: function(data){
            data = JSON.parse(data);
            /*for(var i in data)
                db.Locations.add({Id: data[i].Id, Name: data[i].Name}).then(function(){
                    //console.log(arguments)
                    do1 = true;
                }).catch(function(){
                    //console.log(arguments)
                    do1 = true;
                });
            */
            data = data.map(function(x){
                return {Id: x.Id, Name: x.Name}
            })   
            db.Locations.bulkPut(data).then(function(lastKey) {
                do1 = true;
            }).catch(Dexie.BulkError, function (e) {                
                console.error ("Some raindrops did not succeed. However, " +
                   100000-e.failures.length + " raindrops was added successfully");
                
                do1 = true;   
            }); 
        }
    })
}
function _createWards(){
    $.ajax({
        url: 'data/Wards.txt',
        success: function(data){
            data = JSON.parse(data);
            /*for(var i in data){
                var qq = {Id: data[i].Id, Name: data[i].Name, ParentId: data[i].ParentId}
                //console.log(qq)
                db.Wards.add(qq).then(function(){
                    //console.log(arguments)
                    do2 = true;
                }).catch(function(){
                    //console.log(arguments)
                    do2 = true;
                });
            }*/
            data = data.map(function(x){
                return {Id: x.Id, Name: x.Name, ParentId: x.ParentId}
            })   
            db.Wards.bulkPut(data).then(function(lastKey) {
                do2 = true;
            }).catch(Dexie.BulkError, function (e) {                
                console.error ("Some raindrops did not succeed. However, " +
                   100000-e.failures.length + " raindrops was added successfully");
                
                do2 = true;   
            }); 
        }
    })
}

var schema1 = {
	Locations: 'Id',
    Wards: 'Id,ParentId'//,
    //Books: 'id,code'
},_schemas = [schema1]

var do1=false,do2=false;
var do1do2 = function(){
    db.Locations.count().then(function(c){
        console.log('c1:',c)
        if(c<714) _createLocations()
        else do1=true
    })
    db.Wards.count().then(function(c){
        console.log('c2:',c)
        if(c<11162) _createWards()
        else do2=true
    })
}
var do1do22 = function(){
    db.version(dbver).stores(schema1);
    db.open().then(function(){
        
        do1do2()
         
    }).catch(function(e){
        console.log('e1:',e)
    }) 
}
console.log('db.open()')
db.open().then(function(){    
    dbver = db.verno   
    console.log('db.tables:',JSON.stringify(db.tables)) 
    
    //if(db.tables.find(function(a){return a.name=='Wards'})==undefined || 
    //    db.tables.find(function(a){return a.name=='Locations'})==undefined){
    
        db.close()    
        do1do22()
    //}else{
    //    do1do2()
    //}
}).catch(function(){
    dbver = 1;
    do1do22()
})
 
//db.Locations.filter((a)=>{return a.Name == 'Hà Tĩnh - Huyện Kỳ Anh'}).toArray().then((result)=>{console.log(result[0])})
//db.Locations.filter((a)=>{return a.Name.toLowerCase().indexOf('Hà Tĩnh'.toLowerCase())>=0}).toArray().then(console.log)
//db.Wards.filter((a)=>{return a.ParentId==269}).toArray().then(console.log)
//   db.Wards.where('ParentId').equals(269).toArray().then(console.log)

//[1,2,3].reduce((a,b)=>{return a+b})
Array_prototype_sum = function(index){
    if(this.length==0)
        return 0
    if(index==undefined)
        return this.reduce((a,b)=>{return a-(-b)})
    if(this.length==1)
        return this[0][index]    
    var _ccc = 0;
    return this.reduce((a,b)=>{return ((_ccc++ == 0)?a[index]:a)-(-b[index])})
}

function array_sum(that,index){
    return Array_prototype_sum.call(that,index)
}

Object.defineProperty(Array.prototype, 'sum', {
    value: Array_prototype_sum
});

//v.constructor && (v.constructor+'').indexOf('function Array()')===0
//v.constructor && (v.constructor+'').indexOf('function Object()')===0

function isArray(v){
    try{
        return typeof v=='object' && v.constructor && (v.constructor+'').indexOf('function Array()')===0
    }catch(e){
        return false;
    }
}

function isObject(v){
    try{
        return typeof v=='object' && v.constructor && (v.constructor+'').indexOf('function Object()')===0
    }catch(e){
        return false;
    }
}

function _addInfoToProducts(x){
    
    for(var i in x.products){         
        var z = $.extend({}, loadProduct(x.products[i].product))
        if(z){
            x.products[i] = $.extend({},z,x.products[i])
        }
        x.products[i].maxquantity = z.quantity;
        //topping
        if(z.topping){
            if(x.products[i].topping && x.products[i].topping.slice(0,1)=='{'){
                x.products[i].top = JSON.parse(x.products[i].topping)
                
            }else{
                x.products[i].top = {}
            }
        }else{
            x.products[i].top = {}
            
        }
        x.products[i].topping = z.topping
        //end topping
    }
    
    return x.products; 
}

function uuidv4() {
  return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
    var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
    return v.toString(16);
  });
}

function generateClientId(s) {
  if(s==undefined && cfs) s = cfs.subdomain + '~';
  s = $.md5(s);
  s = s.replace(/^([a-f0-9]{8})([a-f0-9]{4})([a-f0-9]{4})/,"$1-$2-$3-");  
  return s;
}
 
function generateClientSecret(){
  /*return 'xxxxxxxx'.replace(/[x]/g, function(c) {
    var r = Math.random() * 16 | 0, v = r;
    return v.toString(16);
  });*/
  //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
  return 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'.replace(/[x]/g, function(c) {
    var r = Math.random() * 16 | 0, v = r;
    return v.toString(16).toUpperCase()
  });
}
 
function xoadau($str){ 
    if(!$str) return false;
    var $unicode =  {'a':'á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ',
                     'A':'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ằ|Ẳ|Ẵ|Ặ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
                     'd':'đ','D':'Đ',
                     'e':'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
                     'E':'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
                     'i':'í|ì|ỉ|ĩ|ị',
                     'I':'Í|Ì|Ỉ|Ĩ|Ị',
                     'o':'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
                     'O':'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
                     'u':'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
                     'U':'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
                     'y':'ý|ỳ|ỷ|ỹ|ỵ','Y':'Ý|Ỳ|Ỷ|Ỹ|Ỵ'};
    for(var $khongdau in $unicode) {
        var $codau = $unicode[$khongdau]
         
        $str = $str.replace(new RegExp('['+$codau+']','g'),$khongdau);
    }
    return $str;
}

function createAlias(value){
    //return xoadau(value).toLowerCase().replace(/[ ]+/g,'-')
    return xoadau(value).toLowerCase().replace(/\W+/g,'-').replace(/[-]+$/,'').replace(/^[-]+/,'')
}

function replaceObjectKeys(o,from,to){
    var o2={}
    for(var i in o){
        o2[i.replace(from,to)] = o[i]
    }
    return $.extend({},o2);
}
function toLowserCaseObjectKeys(o){
    var o2={}
    for(var i in o){
        o2[i.toLowerCase()] = o[i]
    }
    return $.extend({},o2);
}

var banks = { "Vietcombank":"Ngân hàng TMCP Ngoại thương Việt Nam","Vietinbank":"Ngân hàng TMCP Công Thương Việt Nam","BIDV":"Ngân hàng TMCP Đầu tư và Phát triển Việt Nam","Techcombank":"Ngân hàng TMCP Kỹ Thương Việt Nam","ACB":"Ngân hàng Á Châu","MB":"Ngân hàng TMCP Quân Đội","VPBank":"Ngân hàng TMCP Việt Nam Thịnh Vượng","Agribank":"Ngân hàng Nông nghiệp và Phát triển Nông thôn Việt Nam","SHB":"Ngân hàng TMCP Sài Gòn – Hà Nội","Sacombank":"Ngân hàng TMCP Sài Gòn Thương Tín","HDBank":"Ngân hàng TMCP phát triển Tp.HCM","PVCombank":"Ngân hàng TMCP Đại Chúng Việt Nam","Oceanbank":"Ngân hàng TM TNHH Một Thành Viên Đại Dương","LienVietPostBank":"Ngân hàng TMCP Bưu điện Liên Việt","TPBank":"Ngân hàng TMCP Tiên Phong","VIB":"Ngân hàng TMCP Quốc Tế Việt Nam","MSB Maritime Bank":"Ngân hàng TMCP Hàng Hải Việt Nam","Eximbank":"Ngân hàng TMCP Xuất nhập khẩu Việt Nam","ABBank":"Ngân hàng TMCP An Bình","OCB":"Ngân hàng TMCP Phương Đông","NASB":"Ngân hàng TMCP Bắc Á","DongA Bank":"Ngân hàng TMCP Đông Á","VietABank":"Ngân hàng TMCP Việt Á","BAOVIET Bank":"Ngân hàng TMCP Bảo Việt","SAIGONBANK":"NGÂN HÀNG TMCP SÀI GÒN CÔNG THƯƠNG","NamABank":"Ngân hàng TMCP Nam Á","GPBank":"Ngân hàng TM TNHH MTV Dầu Khí Toàn Cầu.","KienLongBank":"Kiên Long","VCCB":"Bản Việt","PG Bank":"Xăng dầu Petrolimex","VBSP":"Ngân hàng Chính sách Xã hội Việt Nam","VDB":"Ngân hàng Phát triển Việt Nam","Co-opBank":"Ngân hàng Hợp tác xã Việt Nam","DaiAbank":"Ngân hàng TMCP Đại Á","NCB":"NH TMCP Quốc Dân","Western Bank":"Ngân hàng Phương Tây","SCB":"Ngân hàng TMCP Sài Gòn","VietBank":"Ngân Hàng TMCP Việt Nam Thương Tín","Trustbank":"Ngân hàng Đại Tín","ANZ":"ANZ Việt Nam","Deutsche Bank":"Deutsche Bank Việt Nam","Citibank":"Ngân hàng Citibank Việt Nam","HSBC":"Ngân hàng TNHH MTV HSBC Việt Nam","Standard Chartered":"Standard Chartered","SHBVN":"Ngân hàng TNHH MTV Shinhan Việt Nam","HLBVN":"Ngân hàng Hong Leong Việt Nam","BIDC":"Ngân hàng Đầu tư và Phát triển Campuchia","CIB":"Crédit Agricole","Mizuho":"Ngân hàng Mizuho","MUFG":"MUFG Bank","SMBC":"Ngân hàng Sumitomo Mitsui","IVB":"Ngân hàng TNHH Indovina","VRB":"Ngân hàng Liên doanh Việt - Nga","SVB":"Ngân Hàng TNHH MTV Shinhan Việt Nam","PBVN":"Ngân hàng Public Việt Nam","LAOVIETBANK":"Ngân hàng Liên doanh Lào Việt","KHOBACNHANUOC":"Kho bạc nhà nước","NHNHANUOC":"Ngân hàng nhà nước",}


$.fn.select3 = function(ops){
    if(typeof ops=='undefined') ops = {};
    
    if(typeof ops=='string'){
        
    }else
    
    ops = $.extend({
        sources: false,
        selected: 0,
        empty: false,
        emptyValue: '0',        
        //width
        count: 2,//=height
    },ops); //console.log('ops',ops)
    
    //var selected = {}
    
    $(this).each(function(i,v){
        v._destroy = function(){
            if($(v).css('display')!='none') return;
            $(v).unwrap()
            $(v).prev().remove()
            $(v).show()
            $('body>.k-animation-container').data('item',null)
            $('body>.k-animation-container ul').html('')
            //alert('_destroy')
        }
        if(ops=='destroy'){
            v._destroy()
            return;
        }                
        
        if(typeof ops=='string') return;
        
        if($(v).css('display')=='none'){
            v._destroy()
            //return;
        } 
        var sources = {};
         
        v._name = (new Date().getTime())+''+Math.floor(Math.random()*1000)
         
        //selected[v._name] = ops.selected
        if(ops.empty)
            sources[ops.emptyValue] = ops.empty 
        
        //added 10/06/2019    
        for(var i in ops.sources){            
            if(!isObject(ops.sources[i]))
                sources[i] = ops.sources[i]
            else{
                if(ops['key']==undefined){
                    for(var j in ops.sources[i]){
                        if(ops['val']==j) continue;
                        ops['key'] = j;
                        break;
                    }
                }
                if(ops['val']==undefined){
                    for(var j in ops.sources[i]){
                        if(ops['key']==j) continue;
                        ops['val'] = j;
                        break;
                    }
                }
                sources[ops.sources[i][ops['key']]] = ops.sources[i][ops['val']]
            }
        }
        if(ops.sources!==false){
            $(v).html('')
            /*if(ops.empty)
                $(v).html('<option value="'+ops.emptyValue+'">'+ops.empty+'</option>')*/
            for(var i in /*ops.*/sources){
                //$(v).append()
                $(v).append('<option '+(ops.selected==i?' selected="selected"':'')+
                            ' value="'+i+'">'+/*ops.*/sources[i]+'</option>');
                            
            }
        }else{
            $(v).find('option').each(function(i2,v2){
                sources[$(v2).attr('value')] = $(v2).html()
                //if($(v2).attr('selected')) selected = $(v2).attr('value')
            })    
        }
        
        //$(v).unbind('change')
        //$(v).change(function(e){
            //selected[v._name] = $(this).val()
        //    alert('abc')
        //})
        
        v._w = ops.width ? ops.width : ($(v).css('width') ? $(v).css('width') : ($(v).width()+'px'))
        
        $(v).css({display:'none'}).
            wrap('<span title="" class="k-widget k-dropdown k-header" unselectable="on" role="listbox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-owns="" aria-disabled="false" aria-readonly="false" aria-busy="false" style="font-size: 12px; width: '+v._w+';">'+       
            '</span>');
            
        var ct='<span unselectable="on" class="k-dropdown-wrap k-state-default">'
        //$(v).find('option').each(function(i2,v2){
        var qq = $(v).find('option[selected]')
        if(qq.length)
            qq = qq.html()
        else{
            qq = $(v).find('option:nth(0)')
            if(qq.length)
                qq = qq.html()
            else
                qq = ''
        }
                
        ct+='<span unselectable="on" class="k-input ng-scope">'+qq+'</span>'
        //})
        ct+='<span unselectable="on" class="k-select"><span unselectable="on" class="k-icon k-i-arrow-s">select</span></span>'+
        '</span>';
        
        $(v).before(ct);
        
        var _c = function(){
            
        }
        
        var g1_ = function(e){
            var ii = $(this).attr('data-offset-index')
            //alert(ii);
                       
            ////$(v).find('option').removeAttr('selected')
            ////$(v).find('option[value="'+ii+'"]').attr('selected','selected')
            $(v).parent().find('.k-input').html(sources[ii])
                       
            $(v).find('option').removeAttr('selected') //fixed 10/05/2019
            $(v).find('option[value="'+ii+'"]').attr('selected','selected') //fixed 10/05/2019
                         
            $(v).val(ii)
            
            //console.log('abc:',$(this).siblings('.k-state-selected'))
             
            //$(this).siblings('.k-state-selected').removeClass('k-state-selected')
            //$(this).addClass('k-state-selected')
            $('body>.k-animation-container ul li.k-state-selected').removeClass('k-state-selected')
            $('body>.k-animation-container ul li[data-offset-index="'+ii+'"]').addClass('k-state-selected')
            
            //console.log('abc:','body>.k-animation-container ul li[data-offset-index="'+ii+'"]',$('body>.k-animation-container ul li[data-offset-index="'+ii+'"]'))
             
            $('body>.k-animation-container').hide() 
            
            $(v).trigger('change')
        }
        
        function _for(){ //alert($(v).width())
        
            if($('body>.k-animation-container').data('item')==v._name){
                return;
            }
            
            v._w = (!(v._w>0))?$(v).parent('span').width():v._w;//fixed 10/04/2019    v._w=='100%'
            
            $('body>.k-animation-container').data('item',v._name).width(v._w)
            $('body>.k-animation-container').find('>div').width(v._w)
            $('body>.k-animation-container ul').html('')
            
            for(var i in sources){   //alert(selected[v._name])         
                var ll = $('<li tabindex="-1" role="option" unselectable="on" class="k-item ng-scope" data-offset-index="'+i+'">'+sources[i]+'</li>')
                if(i==$(v).val())//if(i==selected[v._name])//ops.selected
                    ll.addClass('k-state-selected')
                ll.hover(function(e){
                    $(this).addClass('k-state-hover')
                },function(e){
                    $(this).removeClass('k-state-hover')
                })                
                $('body>.k-animation-container ul').append(ll)
                ll.click(g1_)
            }
            
            $('body>.k-animation-container ul').unbind('hover')
            $('body>.k-animation-container ul').hover(function(e){
                    
            },function(e){
                $('body>.k-animation-container').hide() 
            })
        }
        
        $(v).parent().unbind('click')
        $(v).parent().click(function(e){
            if($('body>.k-animation-container').data('item')==v._name && $('body>.k-animation-container').is(':visible')){
                $('body>.k-animation-container').hide()
                return;
            }
            
            _for()
            
            var that = $(this)
            $('body>.k-animation-container').css({
                display: 'block',
                top: that.offset().top+that.height(),
                left: that.offset().left,
            })
            
            $('body>.k-animation-container>div').css({
                display: 'block',
                transform: 'translateY(0px)'
            })
            
            if(ops.count<$('body>.k-animation-container ul li').length)
            $('body>.k-animation-container').height($('body>.k-animation-container ul li:first-child').height()*ops.count+4)
            else
            $('body>.k-animation-container').height($('body>.k-animation-container ul').height())
        })
        
    })
}

$.fn._toggleClass = function(_class, _function  , _switch){
    _switch = _switch==undefined? true: !!_switch;
    
    _class = _class.split(' ')
     
    $(this).each(function(i,v){
        if(_function!=undefined){
            var n = _function.call(v,i,v.className)            
        }else{
            var n = ''
        }
        
        for(var i in _class){
            if( (' '+v.className+' ').indexOf(' '+_class[i]+' ')>=0 ){
                v.className = (' '+v.className+' ').replace(' '+_class[i]+' ',' ').trim()
            }else{
                v.className+=' '+_class[i]
            }
        }
        
        if(_switch){                                    
            if(n){
                v.className+=' '+n
            }        
        }else{
            var className = v.className
            className = className.split(' ')
            for(var i in className){
                if(_class.indexOf(className[i])==-1){
                    className[i] = ''
                }
            }
            className = className.filter(function(i){
                return i!=''
            })
            v.className = className.join(' ')+' '+n
        }
    })
}


        function loadJsonBankAccount(lo){
            $.getJSON( "ajax.php?action=bankaccounts", function( data ) {
                  bankaccounts = data;
                  saveBankAccount()
                   if(lo!=undefined) lo.call();
                });
        } 
        
        function saveBankAccount(){
            cache.setItem("bankaccounts",bankaccounts,{expirationAbsolute: null,
                                 expirationSliding: 3600,
                                 priority: Cache.Priority.HIGH,
                                 callback: function(k, v) { console.log('Cache removed: ' + k); }
                                });
        }
        
Object.defineProperty(Array.prototype, 'merge', {
    enumerable: false,
    configurable: false,
    writable: false,
    value: function(b) {
       return this.concat(b.filter((item) => this.indexOf(item) < 0)) 
    }
});        

var paymentMethods = {
    'cash':'Tiền mặt',
    'card':'Thẻ',
    'transfer':'Chuyển khoản',
    'point':'Điểm',
    'voucher':'Voucher'
}

function suggestMoney(number){
    //tổng hợp các tờ tiền sao cho tổng>=number
    //các mệnh giá:
    //1000 2000 5000 10000 20000 50000 100000 200000 500000
    if(number==0) return [];
    var ff = [1000 ,2000 ,5000 ,10000 ,20000 ,50000 ,100000 ,200000 ,500000];
    var ret = []
    if(number%1000>0){
        number = 1000*Math.ceil(number/1000)
    }
    var oldnumber = number+0;
    var a = 0
    if(number>1000000){
        a = 1000000*Math.floor(number/1000000)
        number -= a
    }
    var oldnumber2 = number+0;
    if(number>500000){
        a += 500000
        number -= 500000
    }
    var oldnumber3 = number+0;
     
    if(number==1000){
        return ff.map(function(b){return b+a})
    }
    if(number==2000){
        return [2000 ,5000 ,10000 ,20000 ,50000 ,100000 ,200000 ,500000].map(function(b){return b+a})
    }
    if(number==3000){
        return [3000,4000 ,5000 ,10000 ,20000 ,50000 ,100000 ,200000 ,500000].map(function(b){return b+a})
    }
    if(number==4000){
        return [4000 ,5000 ,10000 ,20000 ,50000 ,100000 ,200000 ,500000].map(function(b){return b+a})
    }
    if(number==5000){
        return [5000 ,6000 ,10000 ,20000 ,50000 ,100000 ,200000 ,500000].map(function(b){return b+a})
    }
    if(number==6000){
        return [6000 ,7000 ,10000 ,20000 ,50000 ,100000 ,200000 ,500000].map(function(b){return b+a})
    }
    if(number==7000){
        return [7000 ,8000 ,10000 ,20000 ,50000 ,100000 ,200000 ,500000].map(function(b){return b+a})
    }
    if(number==8000){
        return [8000 ,9000 ,10000 ,20000 ,50000 ,100000 ,200000 ,500000].map(function(b){return b+a})
    }
    if(number==9000){
        return [9000 ,10000 ,20000 ,50000 ,100000 ,200000 ,500000].map(function(b){return b+a})
    }
    if(number==10000){
        return [10000,11000,20000 ,50000 ,100000 ,200000 ,500000].map(function(b){return b+a})
    }
    if(number==20000){
        return [20000,21000,50000,100000 ,200000 ,500000].map(function(b){return b+a})
    }
    if(number==30000){
        return [30000,40000,50000,100000 ,200000 ,500000].map(function(b){return b+a})
    }
    if(number==40000){
        return [40000,50000,100000 ,200000 ,500000].map(function(b){return b+a})
    }
    if(number==50000){
        return [50000,60000,100000 ,200000 ,500000].map(function(b){return b+a})
    }
    if(number==60000){
        return [60000,70000,100000 ,200000 ,500000].map(function(b){return b+a})
    }
    if(number==70000){
        return [70000,80000,100000 ,200000 ,500000].map(function(b){return b+a})
    }
    if(number==80000){
        return [80000,90000,100000 ,200000 ,500000].map(function(b){return b+a})
    }
    if(number==90000){
        return [90000,100000 ,200000 ,500000].map(function(b){return b+a})
    }
    if(number==100000){
        return [100000,110000 ,200000 ,500000].map(function(b){return b+a})
    }
    if(number==200000){
        return [200000,210000 ,500000].map(function(b){return b+a})
    }
    if(number==500000){
        return [500000,600000].map(function(b){return b+a})
    }
    
    if(number<500000 & number>200000){
        a += 200000
        number -= 200000        
    }else
    if(number<200000 & number>100000){
        a += 100000
        number -= 100000; //console.log('a,number:',a+0,number+0)        
    }else
    if(number<100000 & number>90000){
        a += 90000
        number -= 90000        
    }else
    if(number<90000 & number>80000){
        a += 80000
        number -= 80000        
    }else
    if(number<80000 & number>70000){
        a += 70000
        number -= 70000        
    }else
    if(number<70000 & number>60000){
        a += 60000
        number -= 60000        
    }else
    if(number<60000 & number>50000){
        a += 50000
        number -= 50000        
    }else
    if(number<50000 & number>40000){
        a += 40000
        number -= 40000        
    }else
    if(number<40000 & number>30000){
        a += 30000
        number -= 30000        
    }else
    if(number<30000 & number>20000){
        a += 20000
        number -= 20000        
    }else
    if(number<20000 & number>10000){
        a += 10000
        number -= 10000        
    }     
    //console.log('suggestMoney:',number+0)
    var qq = suggestMoney(number+0).map(function(b){return b+a})
    if(oldnumber2>500000 || oldnumber>1000000){// console.log('suggestMoney1:',number+0)
        
        if(oldnumber>1000000 && oldnumber2<500000){ //console.log('suggestMoney1a:',number+0)
        qq = qq.merge(ff.map(function(b){return b+1000000*Math.floor(oldnumber/1000000)})).
            filter(function(b){
                return b<=1000000*Math.ceil(oldnumber/1000000) && 
                b>=oldnumber && 
                (ff.indexOf(b%1000000)>=0|| b%1000000==0 || b%1000000<2*(oldnumber%1000000))
            }).
            sort(function(x, b) {return x - b })    
        }else{  //console.log('suggestMoney1b:',number+0)
        
        qq = qq.merge(ff.map(function(b){return b+500000*Math.floor(oldnumber/500000)})).
            filter(function(b){
                return b<=500000*Math.ceil(oldnumber/500000) && 
                b>=oldnumber && 
                (ff.indexOf(b%500000)>=0|| b%500000==0 || b%500000<2*(oldnumber%500000))
            }).
            sort(function(x, b) {return x - b })
        }
    }else{ //console.log('suggestMoney2:',number+0,qq)
        qq = qq.merge(ff).filter(function(b){
            return b>=oldnumber && 
            b<=oldnumber-oldnumber2+500000 && 
            (ff.indexOf(b%500000)>=0|| b%500000==0 || b<2*oldnumber)
        }).sort(function(x, b) {return x - b })
    }
        
    return qq
}