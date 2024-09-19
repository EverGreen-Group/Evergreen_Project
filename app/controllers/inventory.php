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

    public function ish()
    {
        $data = [];

        $this->view('inventory/v_fertilizer_dashboard', $data);
    }

}
