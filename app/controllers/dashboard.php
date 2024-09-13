<?php
class dashboard extends controller{
    public function __construct(){
        // echo 'This is the pages controller';
     }
 
     public function index(){
 
     }
    public function dashboard(){
        $data=[];

        $this->view('inventory/v_dashboard',$data);
    }
}