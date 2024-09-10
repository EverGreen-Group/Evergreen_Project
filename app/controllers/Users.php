<?php
class Users extends controller {
    public function __construct(){
        //echo 'This is the user controller';
    }

    public function register(){
        $data=[];

        $this->view('users/v_register',$data);
    }

    
}