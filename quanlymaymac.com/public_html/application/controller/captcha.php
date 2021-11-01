<?

class captcha_controller{
    public function index($code='register'){
        captcha::set($code,5);
        captcha::generate($code,100,25);
    }
}