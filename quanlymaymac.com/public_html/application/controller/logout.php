<?php
class logout_controller extends controller{
    public function index(){
        session::delete('login' );
        session::delete('login_type' );                 
        session::delete('login_id' );
        url::redirect('login');
    }
}
