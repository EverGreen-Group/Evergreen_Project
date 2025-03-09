<?php
class Users extends controller {
    public function __construct(){

    }

    public function register(){
        $data=[];

        $this->view('users/v_register',$data);
    }

    
}