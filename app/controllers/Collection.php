<?php

class Collection extends Controller{
    private $collectionModel;

    public function __construct(){
        $this->collectionModel = $this->model('M_Collection');
    }

    public function index(){
        $this->view('vehicle_manager/v_collection_2', []);
    }

    public function Collection($collectionId) {


        $data = [
            ['collection_id', $collectionId]
        ];

        $this->view('vehicle_manager/v_collection_2', $data);
    }
}





?>