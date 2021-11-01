<?php
class login_controller extends controller{
    public function index(){
        $login_type = (session::get('login_type'));
        
        if($login_type) {
            url::redirect($login_type);
            die();
        }
        
        view::render(
            'login',
            array(
                 
            )
        );
    }
     
}