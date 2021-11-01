<?php
 
class search_controller extends controller{
    public function index(){
        $message = '';
         
        //render template
        view::render(
            'search',             
            array(  
                  
                 'message'=>$message,
                 'title'=>"Search",
                 'h1'=>'Tra Cứu - Đặt Đơn',
                  
                 'all'=> array(),
                  
            )            
        );
    }
}