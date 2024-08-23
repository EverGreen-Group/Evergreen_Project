<?php
class Pages extends controller{
    public function __construct(){
       // echo 'This is the pages controller';
    }

    public function index(){

    }
    public function about(){
        //echo 'Hi' .$name;
        $this->view('v_about');
        
    }
}