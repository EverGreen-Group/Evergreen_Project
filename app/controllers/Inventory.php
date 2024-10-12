<?php
class Inventory extends controller
{
    public function __construct()
    {
        // Initialization code if needed
    }

    public function index()
    {
        $data = [];

        $this->view('inventory/v_dashboard', $data);
    }

    public function product()
    {
        $data = [];

        $this->view('inventory/v_product', $data);
    }

    public function createproduct()
    {
        $data = [];

        $this->view('inventory/v_createproduct', $data);
    }

    public function fertilizerdashboard()
    {
        $data = [];

        $this->view('inventory/v_fertilizer_dashboard', $data);
    }

    public function fertilizer()
    {
        $data = [];

        $this->view('inventory/v_fertilizer_available', $data);
    }
    public function createfertilizer()
    {
        $data = [];

        $this->view('inventory/v_create_fertilizer', $data);
    }

    public function machine()
    {
        $data = [];

        $this->view('inventory/v_machineallocation', $data);
    }


    public function create()
    {
        $data = [];

        $this->view('inventory/v_create_product', $data);
    }

    public function item(){
        $data=[];

        $this->view('inventory/v_item',$data);
    }

   
}
