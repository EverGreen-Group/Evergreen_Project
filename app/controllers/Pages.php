<?php
class Pages{
    public function __construct(){
       // echo 'This is the pages controller';
    }

    public function index(){

    }
    public function about($name){
        echo 'Hi' .$name;
        
    }
}