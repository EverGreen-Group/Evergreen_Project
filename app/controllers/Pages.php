<?php
class Pages extends controller{
    public function __construct(){
       // echo 'This is the pages controller';
    }

    public function index(){

    }
    public function about(){
                  
        $this->view('v_about');
        
    }
}