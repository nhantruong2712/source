<?

class thumb_controller{
    public function index(){
        if($_REQUEST['image']){
            $cache = WEBROOT.'/cache/';
            $image_request = $_REQUEST['image'];
            $w = isset($_REQUEST['w'])?$_REQUEST['w']:155;
            $h = isset($_REQUEST['h'])?$_REQUEST['h']:155;
            $name = $cache.md5($w.'x'.$h.'/'.$image_request).".jpg";
            if(!file_exists($name)){
                //if ( get_magic_quotes_gpc() ) $image_request = stripslashes( $_REQUEST['image'] );
                $t = new thumbnail( $image_request , ( int ) $w, ( int ) ( $h ), isset( $_REQUEST['f'] ) );
                $t->parseToFile($name);
            }//else echo @readfile($name);
            @header('Content-type: image/jpg');
            @readfile($name);
        }else Thumby::not_found();
    }
}